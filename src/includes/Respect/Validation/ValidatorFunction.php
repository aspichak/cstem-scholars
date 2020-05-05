<?php

namespace Respect\Validation;

use Respect\Validation\Validator;
use Respect\Validation\Exceptions\ValidationException;

/**
  * Decorator for Respect\Validation library. Allows the validator object to be 
  * called as if it is a function that returns either an error message or null 
  * if the value is valid.
  */
class ValidatorFunction
{
    private $validator;

    public function __construct()
    {
        $this->validator = new Validator();
    }

    public static function __callStatic($ruleName, $arguments)
    {
        return (new self())->$ruleName(...$arguments);
    }

    public function __call(string $ruleName, array $arguments): self
    {
        $this->validator->$ruleName(...$arguments);
        return $this;
    }

    public function __invoke($value)
    {
        try {
            $this->check($value);
        } catch (ValidationException $exception) {
            return $exception->getMessage();
        }

        return null;
    }
}