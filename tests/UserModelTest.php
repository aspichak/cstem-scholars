<?php

require_once __DIR__ . '/../src/includes/init.php';
require_once __DIR__ . '/SchemaTest.php';

final class UserModelTest extends SchemaTest
{
    protected function setUp(): void
    {
        parent::setUp();
        $_SESSION = [];
    }

    protected function tearDown(): void
    {
    }

    public function testUserCanBeCreated()
    {
        $this->assertInstanceOf(User::class, new User());

        $user = new User([
            'name' => 'Alice Archer',
            'email' => 'alice@example.edu',
            'isAdmin' => true
        ]);

        $this->assertCount(0, $user->errors());
        $this->assertTrue($user->save());
        $this->assertEquals(1, User::count());
    }

    public function testCurrentUser()
    {
        $user = new User();
        $user->email = 'alice@example.edu';
        $user->name = 'Alice Archer';
        $user->isAdmin = true;
        $user->isReviewer = true;
        $user->save();

        $this->assertNull(User::current());

        $_SESSION['id'] = '00100001';
        $_SESSION['name'] = 'Student Name';
        $_SESSION['email'] = 'student@example.edu';

        $user = User::current();
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('00100001', $user->id);
        $this->assertEquals('Student Name', $user->name);
        $this->assertEquals('student@example.edu', $user->email);
        $this->assertEquals(false, $user->isAdmin);
        $this->assertEquals(false, $user->isAdvisor);
        $this->assertEquals(false, $user->isReviewer);
        $this->assertEquals(true, $user->isStudent);
        $this->assertEquals(['student'], $user->roles());

        $_SESSION['id'] = '00100001';
        $_SESSION['name'] = 'Alice Archer';
        $_SESSION['email'] = 'alice@example.edu';

        $user = User::current();
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('00100001', $user->id);
        $this->assertEquals('Alice Archer', $user->name);
        $this->assertEquals('alice@example.edu', $user->email);
        $this->assertEquals(true, $user->isAdmin);
        $this->assertEquals(false, $user->isAdvisor);
        $this->assertEquals(true, $user->isReviewer);
        $this->assertEquals(false, $user->isStudent);
        $this->assertContains('reviewer', $user->roles());
        $this->assertContains('admin', $user->roles());
        $this->assertCount(2, $user->roles());
        $this->assertTrue($user->hasRole('admin'));
        $this->assertTrue($user->hasRole('reviewer'));
        $this->assertFalse($user->hasRole('advisor'));
        $this->assertFalse($user->hasRole('student'));
    }

    public function testNotLoggedIn()
    {
        $this->assertNull(User::current());

        $_SESSION['id'] = '00100001';
        $this->assertNull(User::current());

        $_SESSION['name'] = 'Student Name';
        $this->assertNull(User::current());

        $_SESSION['email'] = 'student@example.edu';
        $this->assertInstanceOf(User::class, User::current());
    }
}
