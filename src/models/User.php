<?php

use Respect\Validation\ValidatorFunction as v;

class User extends Model
{
    protected static $primaryKey = 'email';

    public $id;
    public $isAdvisor;
    public $isReviewer;
    public $isAdmin;
    public $isStudent;

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
        $user->isStudent = !($user->isAdvisor || $user->isReviewer || $user->isAdmin);

        return $user;
    }

    public static function authorize($role, $allow = true)
    {
        if (User::current()->hasRole($role) && $allow) {
            HTTP::error(
                'You are not authorized to access this page.',
                401,
                'Unauthorized Access'
            );
        }
    }

    public function roles()
    {
        $roles = [];

        if ($this->isStudent) {
            $roles[] = 'student';
        }

        if ($this->isAdvisor) {
            $roles[] = 'advisor';
        }

        if ($this->isReviewer) {
            $roles[] = 'reviewer';
        }

        if ($this->isAdmin) {
            $roles[] = 'admin';
        }

        return $roles;
    }

    public function hasRole($role)
    {
        return in_array($role, $this->roles());
    }
}
