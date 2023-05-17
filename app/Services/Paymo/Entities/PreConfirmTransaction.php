<?php


namespace App\Services\Paymo\Entities;

/**
 *
 * {
 *  "card_token": "<card-token>",
 *  "store_id": 0000,
 *  "transaction_id": 00000
 * }
 * Class PreConfirmTransaction
 * @package App\Services\Paymo\Entities
 */
class PreConfirmTransaction
{

    private $card_token;

    private $store_id;

    private $transaction_id;
    
    private $lang;

    /**
     * PreConfirmTransaction constructor.
     * @param $card_token
     * @param $store_id
     * @param $transaction_id
     */
    public function __construct($card_token, $store_id, $transaction_id, string $lang)
    {
        $this->card_token = $card_token;
        $this->store_id = $store_id;
        $this->transaction_id = $transaction_id;
        $this->lang = $lang;
    }

    /**
     * @return mixed
     */
    public function getCardToken()
    {
        return $this->card_token;
    }

    /**
     * @return mixed
     */
    public function getStoreId()
    {
        return $this->store_id;
    }

    /**
     * @return mixed
     */
    public function getTransactionId()
    {
        return $this->transaction_id;
    }

    public function getLang()
    {
        return $this->lang;
    }

    /**
     * {
     * "card_token": "<card-token>",
     * "store_id": 0000,
     * "transaction_id": 00000
     * }
     * @return array
     */

    public function body() {

        return [
            'card_token' => $this->getCardToken(),
            'store_id' => $this->getStoreId(),
            'transaction_id' => $this->getTransactionId(),
            'lang' => $this->getLang()
        ];
    }
}
