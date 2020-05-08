<?php

use Respect\Validation\ValidatorFunction as v;

class User extends Model
{
    protected static $primaryKey = 'email';

    public function __construct($form = [])
    {
        $this->fillable = [
            'email' => v::email()->length(null, 50)->setName('Email address'),
            'name' => v::length(2, 50)->setName('Name'),
            'isAdvisor',
            'isReviewer',
            'isAdmin'
        ];

        $this->id = $form['id'] ?? null;
        parent::__construct($form);
    }

    public static function current()
    {
        if (in_array(null, HTTP::session(['id', 'name', 'email']))) {
            return null;
        }

        $user = self::get(HTTP::session('email')) ?? new User();
        $user->id = HTTP::session('id');
        $user->name = HTTP::session('name');
        $user->email = HTTP::session('email');

        return $user;
    }

    public static function authorize($role, $allow = true)
    {
        /*
        if (User::current()->hasRole($role) && $allow) {
            HTTP::error(
                'You are not authorized to access this page.',
                401,
                'Unauthorized Access'
            );
        }
        */
        $user = User::current();
        switch ($role) {
            case "student":
                return User::isAuthorized($user->isStudent());
            case "admin":
                return User::isAuthorized($user->isAdmin());
            case "reviewer":
                return User::isAuthorized($user->isReviewer());
            case "advisor":
                return User::isAuthorized($user->isAdvisor());
            default:
                return User::isAuthorized(false);
        }
    }

    private static function isAuthorized($flag) {
        if (!$flag) {
            HTTP::error(
                'You are not authorized to access this page.',
                401,
                'Unauthorized Access'
            );
        }
        else {
            return true;
        }
    }

    public function isAdmin()
    {
        return (bool)$this->isAdmin;
    }

    public function isAdvisor()
    {
        return (bool)$this->isAdvisor;
    }

    public function isReviewer()
    {
        return (bool)$this->isReviewer;
    }

    public function isStudent()
    {
        return !($this->isAdvisor() || $this->isReviewer() || $this->isAdmin());
    }

    public function roles()
    {
        $roles = [];

        if ($this->isStudent()) {
            $roles[] = 'student';
        }

        if ($this->isAdvisor()) {
            $roles[] = 'advisor';
        }

        if ($this->isReviewer()) {
            $roles[] = 'reviewer';
        }

        if ($this->isAdmin()) {
            $roles[] = 'admin';
        }

        return $roles;
    }

    public function hasRole($role)
    {
        return in_array($role, $this->roles());
    }
}
