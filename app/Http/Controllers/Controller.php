<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected function response($data, $status = 200): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'meta' => [
                'code'    => $status,
                'state' => 'success'
            ],
            'data' => $data
        ], $status);
    }
}
