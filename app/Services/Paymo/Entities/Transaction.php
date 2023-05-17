<?php


namespace App\Services\Paymo\Entities;

/**
 * {
    "amount": 5000000,
    "account": "12345",
    "terminal_id": "XXXXXXXX",
    "store_id": "XXXX",
    "lang": "ru"
    }
 * Class Transaction
 * @package App\Services\Paymo\Entities
 */
class Transaction
{

    private $params;

    private $store_id;

    private $lang = '';

    /**
     * Transaction constructor.
     * @param $amount
     * @param $account
     * @param $terminal_id
     * @param $store_id
     * @param string $lang
     */
    public function __construct($params, $store_id, string $lang)
    {
        // $this->amount = $amount;
        // $this->account = $account;
        // $this->terminal_id = $terminal_id;
        $this->store_id = $store_id;
        $this->params = $params;
        $this->lang = $lang;
    }

    /**
     * @return mixed
     */

    /**
     * @return mixed
     */

    /**
     * @return mixed
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @return mixed
     */
    public function getStoreId()
    {
        return $this->store_id;
    }

    /**
     * @return string
     */
    public function getLang(): string
    {
        return $this->lang;
    }

    public function body() {
        return [
            'store_id' => $this->getStoreId(),
            'params' => $this->getParams(),
            'lang' => $this->getLang(),
        ];
    }

}
