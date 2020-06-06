<?php

namespace App\Http\Controllers\Concerns;


use Symfony\Component\HttpFoundation\Response;

trait HttpResponses{


    protected function response(int $statusCode, $data= null, $format = 'json')
    {
        $response = [
            'status'=> $statusCode,
            'message' => Response::$statusTexts[$statusCode],
        ];

        $response = !empty($data) ? array_merge($response, ['data'=>$data]) : $response;

        if($format === 'array')
        {
            return $response;
        }
        return response()->json($response,$statusCode);


    }

    protected function responseInArray(int $statusCode, $data= null)
    {
        return $this->response($statusCode, $data, 'array');
    }

    protected function ok()
    {
        return Response::HTTP_OK;
    }

    protected function created()
    {
        return Response::HTTP_CREATED;
    }

}