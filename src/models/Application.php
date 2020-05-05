<?php

use Respect\Validation\ValidatorFunction as v;

class Application extends Model
{
    public const DEPARTMENTS = [
        'Biology',
        'Chemistry',
        'Computer Science',
        'Design',
        'Engineering',
        'Environmental Science',
        'Geology',
        'Mathematics',
        'Natural Science',
        'Physics'
    ];

    public const VALID_STATES = [
        'draft',                   // application was saved but not submitted for review
        'submitted',               // application was submitted; waiting for advisor signoff
        'advisor_requested_info',  // advisor requested additional information
        'pending_review',          // advisor signed off on the application
        'reviewer_requested_info', // reviewer requested additional information
        'reviewed',                // at least one reviewer approved the application
        'rejected',                // application was rejected
        'awarded'                  // application received funding
    ];

    private $hasAgreedToTerms = false;

    public function __construct($form = [])
    {
        $this->fillable = [
            // Basic Details
            'name' => v::length(3, 50)->setName('Name'),
            'email' => v::email()->length(null, 50)->setName('Email address'),
            'title' => v::length(3, 140)->setName('Project title'),

            // Major & GPA
            'major' => v::in(self::DEPARTMENTS)->setTemplate('Invalid major'),
            'gpa' => v::number()->min(2.0)->max(4.0)->setName('GPA'),
            'graduationDate' => v::date()->between('today', '+10 years')->setName('Expected Graduation Date'),

            // Advisor Information
            'advisorName' => v::length(3, 50)->setName('Advisor name'),
            'advisorEmail' => 'Application::validateAdvisorEmail',

            // Objective & Results
            'description' => v::length(3, 6000)->setName('Objective'),
            'timeline' => v::length(3, 2000)->setName('Timeline'),

            // Budget
            'justification' => v::length(3, 2000)->setName('Budget description'),
            'totalBudget' => v::number()->min(0)->setName('Budget amount'),
            'requestedBudget' => v::number()->min(0)->max(2000)->setName('Requested amount'),
            'fundingSources' => v::length(3, 140)->setName('Funding sources')
        ];

        $this->guarded = [
            'studentID',
            'periodID',
            'status',
            'attachment'
        ];

        $this->hasAgreedToTerms = (($form['terms'] ?? '') == 'agree');

        parent::__construct($form);
    }

    public function errors()
    {
        $errors = parent::errors();

        if (!$this->hasAgreedToTerms) {
            $errors[] = 'You must agree to Terms and Conditions';
        }

        return $errors;
    }

    public function save()
    {
        if (!in_array($this->status, self::VALID_STATES)) {
            throw new InvalidArgumentException("Invalid application status: \"{$this->status}\"");
        }

        return parent::save();
    }

    public function reviews()
    {
        return Review::all('applicationID = ?', $this->id);
    }

    public static function validateAdvisorEmail($email)
    {
        if (User::count('email = ? AND isAdvisor = 1', $email)) {
            return null;
        } else {
            return 'There doesn\'t appear to be an advisor associated with that email';
        }
    }
}
