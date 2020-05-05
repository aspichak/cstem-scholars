<?php

require_once __DIR__ . '/../src/includes/init.php';

use PHPUnit\Framework\TestCase;

final class ApplicationModelTest extends TestCase
{
    private $validApp;

    protected function setUp(): void
    {
        DB::configure('sqlite::memory:', null, null, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

        $schema = file_get_contents(__DIR__ . '/../setup.sql');
        $schema .= "
            INSERT INTO User (name, email, isAdvisor, isReviewer, isAdmin) 
            VALUES ('Advisor', 'advisor@email.com', true, false, false);
        ";

        DB::pdo()->exec($schema);

        $this->validApp = [
            // Basic Details
            'name'            => 'Valid Name',
            'email'           => 'valid@email.com',
            'title'           => 'Valid Project Title',

            // Major & GPA
            'major'           => 'Computer Science',
            'gpa'             => '4.0',
            'graduationDate'  => '2030-05-01',

            // Advisor Information
            'advisorName'     => 'Ad Visor',
            'advisorEmail'    => 'advisor@email.com',

            // Objective & Results
            'description'     => 'Valid project description',
            'timeline'        => 'Valid timeline',

            // Budget
            'justification'   => 'Valid budget justification',
            'totalBudget'     => 123.45,
            'requestedBudget' => '123.45',
            'fundingSources'  => 'Valid sources',

            'terms'           => 'agree'
        ];
    }

    protected function tearDown(): void
    {
    }

    public function testApplicationCanBeCreated()
    {
        $this->assertInstanceOf(Application::class, new Application());

        $app = new Application($this->validApp);

        $app->studentID = '007123456';
        $app->periodID  = 1;
        $app->status    = 'submitted';

        $this->assertCount(0, $app->errors());
        $this->assertTrue($app->save());
        $this->assertIsNumeric($app->id);

        $this->assertEquals(1, Application::count());
        $this->assertEquals('Valid Name', Application::first()->name);
    }

    public function testApplicationTerms()
    {
        $this->assertInstanceOf(Application::class, new Application());

        $invalidApp = $this->validApp;
        unset($invalidApp['terms']);

        $app = new Application($invalidApp);
        $app->studentID = '007123456';
        $app->periodID  = 1;
        $app->status    = 'submitted';
        
        $this->assertFalse($app->save());
        $this->assertNull($app->id);
        $this->assertCount(1, $app->errors());

        $this->assertEquals(0, Application::count());
    }

    public function testApplicationNullStatus()
    {
        $this->expectException(InvalidArgumentException::class);

        $app = new Application($this->validApp);
        $app->save();
    }

    public function testApplicationInvalidStatus()
    {
        $this->expectException(InvalidArgumentException::class);

        $app = new Application($this->validApp);
        $app->status = 'invalid_status';
        $app->save();
    }
}
