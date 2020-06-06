<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use  Illuminate\Database\Eloquent\Builder;

class MaximItem implements Rule
{
    /**
     * Create a new rule instance.
     *
     *
     */
    protected $builder;
    protected $maximum;
    protected $entity;

    public function __construct($builder, int $maximum, $entity)
    {
        $this->builder = $builder;
        $this->maximum = $maximum;
        $this->entity = $entity;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return !($this->builder->count() >= $this->maximum);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "{$this->maximum} {$this->entity} exceeded";
    }
}
