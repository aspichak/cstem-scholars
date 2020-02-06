<?php
/**
* Authors: Danny Messina, James Reisenhauer, Andy Johnson, Collin Nolen, Jeremy Gay, Turner Lehmbecker
*
*/
namespace SSO\SingleSignOn;
$message = "wrong answer";
echo "<script type='text/javascript'>alert('$message');</script>";
class SSOClient
{
    private $client;
    private $app_name;
    private $default_session_id;
    private $default_session_name;
	private $username;
	private $attributes;

    /**
	* Constructor for SSO client
	*/
    public function __construct($application_name, $protocol = 'SAML_VERSION_1_1')
    {
		//save the app name for unique sessions
		$this->app_name = $application_name;
        $this->username = null;
        $this->attributes = [];
		
		//setup the unique session
		$this->set_session();
		
		//check initialization of CAS
		$initialized = \phpCAS::isInitialized();
		
		//if CAS isn't initialized, initialize it
		//move into its own function, maybe?
		if(!$initialized)
		{
			require_once 'config.php';
            $this->cas_host = $cas_host;
            $this->cas_port = $cas_port;
            $this->cas_context = $cas_context;
            $this->cas_server_ca_cert_path = $cas_server_ca_cert_path;
            $this->cas_debug_log = $cas_debug_log;
            $this->cas_real_hosts = $cas_real_hosts;
			
			//set which protocol CAS should use
			switch($protocol)
			{
				case 'CAS_VERSION_1_0':
					\phpCAS::client(CAS_VERSION_1_0, $this->cas_host, $this->cas_port, $this->cas_context, true);
                    break;
				case 'CAS_VERSION_2_0':
					\phpCAS::client(CAS_VERSION_2_0, $this->cas_host, $this->cas_port, $this->cas_context, true);
					break;
				case 'CAS_VERSION_3_0': //not currently supported
					\phpCAS::client(CAS_VERSION_3_0, $this->cas_host, $this->cas_port, $this->cas_context, true);
                    break;
				case 'SAML_VERSION_1_1': //add support for SAML 2.0 later
				default:
					\phpCAS::client(SAML_VERSION_1_1, $this->cas_host, $this->cas_port, $this->cas_context);
			}
			
			//set the authentication certificate
			\phpCAS::setCasServerCACert($this->cas_server_ca_cert_path);
		}
		else //initialized, just need to check to see if CAS servers have already been set
		{
			if(!isset($this->cas_real_hosts))
				$this->cas_real_hosts = false;
		}
		
		//restore the default session
		$this->restore_session();
    }

	/**
	* Do the authentication
	* don't need to return the credentials after authentication
	* up to the app when credentials should be gathered
	*/
    public function authenticate()
    {
        //update the session
		$this->set_session();
		
		//perform authentication
		\phpCAS::handleLogoutRequests(true, $this->cas_real_hosts);
		\phpCAS::forceAuthentication();
		
		$this->username = \phpCAS::getUser();
		
		//are there attributes associated with this user?
		if (\phpCAS::hasAttributes()) 
            $this->attributes = \phpCAS::getAttributes();
		
		
		//are we debugging?
		if (defined('EWU_SSO_DEBUG')) 
		{
            \phpCAS::setDebug($this->cas_debug_log);
            \phpCAS::setVerbose(true);
        }
		
		//restore app session
		$this->restore_session();
    }
    public function logout()
    {
        //update the session
		$this->set_session();
		\phpCAS::logout();
		
		// Restore default session
        $this->restore_session();
    }
	
	public function get_credentials()
	{
		$results = [];
		$results['user'] = $this->username;
		$resutls['attributes'] = $this->attributes;
		return $results;
	}
	
	public function get_user(){return $this->username;}
	public function get_attributes(){return $this->attributes;}
	
	/**
	* Give this session/instance of the app a unique ID
	*/
	private function set_session()
	{
		// Save any existing session
        $this->default_session_id = session_id();
        $this->default_session_name = session_name();

        // Save & close any existing session
        if (isset($_SESSION)) 
		{
            session_write_close();
            session_unset();
        }

        // Set a specific session name for authentication
        $name = 'auth_'.$this->app_name;
        session_name($name);
        if (isset($_COOKIE[$name])) 
		{
            session_id($_COOKIE[$name]);
            session_start();
        } 
		else 
		{
            session_regenerate_id();
        }
	}
	
	/**
	* Restore a session
	*/
	private function restore_session()
	{
		// Close any existing session
        if (isset($_SESSION)) {
            session_write_close();
            session_unset();
        }

        // Set the Correct Session Name & Id
        session_name($this->default_session_name);
        session_id($this->default_session_id);
	}
}
