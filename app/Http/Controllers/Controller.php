<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function success($data,$message): JsonResponse
    {
        $respon['respon_status'] = array('status' => 'SUCCESS','message' => $message);
        if ($data!=null) {
            $respon['data'] = $data;
        }
        return response()->json($respon,200);
    }
}
