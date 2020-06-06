<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\HttpResponses;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\HttpFoundation\Response;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, HttpResponses;
    protected $ok = Response::HTTP_OK;
    protected $created = Response::HTTP_CREATED;

}
