<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Concerns\RespondsWithJson;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use RespondsWithJson;

    public function show(Request $request)
    {
        return $this->jsonSuccess('Authenticated user retrieved.', [
            'user' => $request->user(),
        ]);
    }
}
