<?php


namespace App\Services\Paymo;


use App\Services\Paymo\Entities\ConfirmTransaction;
use App\Services\Paymo\Entities\Expiry;
use App\Services\Paymo\Entities\PreConfirmTransaction;
use App\Services\Paymo\Entities\Transaction;
use App\Services\Paymo\Token;
use App\Services\Paymo\PaymoRequest;

class TransactionProcess
{
    const BASE_URL = 'https://partner.atmos.uz';

    const CREATE_URL = self::BASE_URL.'/merchant/bulk/pay/create';

    const PRE_CONFIRM_TRANSACTION = self::BASE_URL.'/merchant/bulk/pay/pre-confirm';

    const CONFIRM_TRANSACTION = self::BASE_URL.'/merchant/bulk/pay/confirm';

    const RESEND_OTP_TRANSACTION = self::BASE_URL.'/merchant/pay/otp-resend';

    public function resendOtpTransaction($transaction_id) {
        $request = new PaymoRequest(self::RESEND_OTP_TRANSACTION, [
            'transaction_id' => $transaction_id
        ], new Token());
        return $request->sendRequest();
    }

    public function createTransaction(Transaction $transaction) {
        $request = new PaymoRequest(self::CREATE_URL, $transaction->body(), new Token());
        return $request->sendRequest();
    }

    public function preConfirmTransaction(PreConfirmTransaction $preConfirmTransaction) {
        $request = new PaymoRequest(self::PRE_CONFIRM_TRANSACTION, $preConfirmTransaction->body(), new Token());
        return $request->sendRequest();
    }

    public function confirmTransaction(ConfirmTransaction $confirmTransaction) {
        $request = new PaymoRequest(self::CONFIRM_TRANSACTION, $confirmTransaction->body(), new Token());
        return $request->sendRequest();
    }


}
