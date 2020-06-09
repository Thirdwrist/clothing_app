<?php

namespace App\Http\Middleware;

use function abort;
use App\Http\Controllers\Concerns\HttpResponses;
use Closure;
use Symfony\Component\HttpFoundation\Response;

class CheckIfBelongsAuthenticated
{
    use HttpResponses;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!($request->user()->id === $request->route('user')->id))
        {
            abort($this->response(Response::HTTP_FORBIDDEN));
        }

        return $next($request);
    }
}
