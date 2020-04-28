<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\HttpFoundation\Response;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    protected $ok = Response::HTTP_OK;
    protected $created = Response::HTTP_CREATED;

    protected function response(int $statusCode, $data= null)
    {
        $response = [
            'status'=> $statusCode,
            'message' => Response::$statusTexts[$statusCode],
        ];

        $response = !empty($data) ? array_merge($response, ['data'=>$data]) : $response;

        return response()->json($response,$statusCode);


    }


}
