<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Laravel\Fortify\Contracts\RecoveryCodesGeneratedResponse as RecoveryCodesGeneratedResponseContract;
use Laravel\Fortify\Fortify;

class RecoveryCodesGeneratedResponse implements RecoveryCodesGeneratedResponseContract
{
    public function toResponse($request)
    {
        $recoveryCodes = $request->user()->recoveryCodes();

        return $request->wantsJson()
            ? new JsonResponse($recoveryCodes, 200)
            : back()
                ->with('status', Fortify::RECOVERY_CODES_GENERATED)
                ->with('recoveryCodes', $recoveryCodes);
    }
}
