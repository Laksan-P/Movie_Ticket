<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Laravel\Fortify\Contracts\TwoFactorConfirmedResponse as TwoFactorConfirmedResponseContract;
use Laravel\Fortify\Fortify;

class TwoFactorConfirmedResponse implements TwoFactorConfirmedResponseContract
{
    public function toResponse($request)
    {
        $recoveryCodes = $request->user()->recoveryCodes();

        return $request->wantsJson()
            ? new JsonResponse($recoveryCodes, 200)
            : back()
                ->with('status', Fortify::TWO_FACTOR_AUTHENTICATION_CONFIRMED)
                ->with('recoveryCodes', $recoveryCodes);
    }
}
