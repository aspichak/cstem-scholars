<?php

use Respect\Validation\ValidatorFunction as v;

class Period extends Model
{
    public $id, $beginDate, $deadline, $advisorDeadline, $budget;

    public function __construct($form = [], $fillGuardedColumns = false)
    {
        $this->fillable = [
            'beginDate' => v::date('Y-m-d')->setName('Start date'),
            'deadline' => v::date('Y-m-d')->setName('Student deadline'),
            'advisorDeadline' => v::date('Y-m-d')->setName('Review deadline'),
            'budget' => v::number()->min(0)->setName('Budget')
        ];

        parent::__construct($form, $fillGuardedColumns);
    }

    public static function current()
    {
        return self::first('beginDate <= :date AND deadline >= :date', ['date' => date('Y-m-d')]);
    }

    public static function currentForAdvisors()
    {
        return self::first('beginDate <= :date AND advisorDeadline >= :date', ['date' => date('Y-m-d')]);
    }

    public function errors()
    {
        $errors = parent::errors();

        if ($this->beginDate > $this->deadline) {
            $errors['beginDate'] = 'Begin date must be earlier than the application deadline';
        }

        if ($this->deadline > $this->advisorDeadline) {
            $errors['advisorDeadline'] = 'Advisor deadline cannot precede the application deadline';
        }

        if (Period::exists('beginDate <= ? AND advisorDeadline >= ?', $this->beginDate, $this->beginDate)) {
            $errors['beginDate'] = 'Start date cannot be within an existing period window';
        }

        if (Period::exists('beginDate <= ? AND advisorDeadline >= ?', $this->advisorDeadline, $this->advisorDeadline)) {
            $errors['advisorDeadline'] = 'Review deadline cannot be within an existing period window';
        }

        return $errors;
    }

    public function applications($where = '', ...$params)
    {
        return Application::all("periodID = ? AND ($where)", $where, $this->id, ...$params);
    }
}
