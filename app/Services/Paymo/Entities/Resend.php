<?php


namespace App\Services\Paymo\Entities;

/**
 *
 * {
 * "transaction_id": 00000,
 * "otp": 111111,
 * "store_id": 0000
 * }
 * Class ConfirmTransaction
 * @package App\Services\Paymo\Entities
 *
 */
class Resend
{

    private $transaction_id;

    /**
     * ConfirmTransaction constructor.
     * @param $transaction_id
     */
    public function __construct($transaction_id)
    {
        $this->transaction_id = $transaction_id;
    }

    /**
     * @return mixed
     */
    public function getTransactionId()
    {
        return $this->transaction_id;
    }

    /**
     * @return mixed
     */


    /**
     * @return array
     * {
     * "transaction_id": 00000,
     * "otp": 111111,
     * "store_id": 0000
     * }
     */
    public function body()
    {
        return [
            'transaction_id' => $this->getTransactionId()
        ];
    }




}