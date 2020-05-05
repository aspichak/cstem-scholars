<?php

use Respect\Validation\ValidatorFunction as v;

class Period extends Model
{
    public function __construct($form = [])
    {
        $this->fillable = [
            'beginDate' => v::date('Y-m-d'),
            'deadline' => v::date('Y-m-d'),
            'advisorDeadline' => v::date('Y-m-d'),
            'budget' => v::number()->min(0)->setName('Budget')
        ];

        parent::__construct($form);
    }

    public static function current()
    {
        return self::first('beginDate <= :date AND deadline >= :date', ['date' => date('Y-m-d')]);
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

        return $errors;
    }

    public function applications($where = '', ...$params)
    {
        return Application::all("periodID = ? AND ($where)", $where, $this->id, ...$params);
    }
}
