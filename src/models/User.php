<?php

use Respect\Validation\ValidatorFunction as v;

class User extends Model
{
    protected static $primaryKey = 'email';

    public $ewuID;
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

        parent::__construct($form);
    }

    public static function current()
    {
        $user = User::get($_SESSION['email']) ?? new User();

        $user->ewuID = $_SESSION['id'];
        $user->name = $_SESSION['name'];
        $user->email = $_SESSION['email'];
        $user->isStudent = !($user->isAdvisor || $user->isReviewer || $user->isAdmin);

        return $user;
    }

    public static function authorize($role, $allow)
    {
        if (User::current()->hasRole($role) && $allow) {
            error(
                'You are not authorized to access this page.',
                'You are not authorized to access this page.',
                401
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
