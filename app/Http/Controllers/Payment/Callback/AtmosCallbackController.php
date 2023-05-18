<?php

namespace App\Http\Controllers\Payment\Callback;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymoCallbackRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AtmosCallbackController extends Controller
{
    public function callbackTransaction(PaymoCallbackRequest $request)
    {
        $successResponse = [
            'status' => 1,
            'message' => 'Успешно'
        ];

        try {
            $array_data = $request->validated();
            // $request_data = ['message' => 'no request as it is callback'];
            // $dto = new CreateEventDto();
            // $dto->transaction_id = $array_data['transaction_id'];
            // $dto->uuid = CallbackData::generateUUID();
            // $dto->status = CallbackData::STATUS_ACTIVE;
            // $dto->event_name = CallbackData::EVENT_PAYMENT_CALLBACK;
            // $dto->json_data = json_encode($array_data);
            // $dto->request_data = json_encode($request_data);
            // $this->repository->store(new CallbackData($dto));

            Log::info($array_data);

        } catch (\Exception $exception) {
            $errorResponse = [
                'status' => 2,
                'message' => 'Tamom'
            ];
            return response()->json($errorResponse,500);
        }

        return response()->json($successResponse, 200);
    }
}
