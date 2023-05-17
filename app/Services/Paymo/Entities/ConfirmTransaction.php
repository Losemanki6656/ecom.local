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
class ConfirmTransaction
{

    private $transaction_id;

    private $otp;

    private $store_id;

    /**
     * ConfirmTransaction constructor.
     * @param $transaction_id
     * @param $otp
     * @param $store_id
     */
    public function __construct($transaction_id, $otp, $store_id)
    {
        $this->transaction_id = $transaction_id;
        $this->otp = $otp;
        $this->store_id = $store_id;
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
    public function getOtp()
    {
        return $this->otp;
    }

    /**
     * @return mixed
     */
    public function getStoreId()
    {
        return $this->store_id;
    }


    /**
     * @return array
     * {
     * "transaction_id": 00000,
     * "otp": 111111,
     * "store_id": 0000
     * }
     */
    public function body() {
        return [
            'store_id' => $this->getStoreId(),
            'otp' => $this->getOtp(),
            'transaction_id' => $this->getTransactionId()
        ];
    }




}
