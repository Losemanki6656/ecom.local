<?php


namespace App\Services\Paymo\Entities;


class Expiry
{
    private $value;

    /**
     * Expiry constructor.
     * @param $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }




}
