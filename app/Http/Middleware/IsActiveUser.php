<?php

namespace App\Http\Middleware;

use App\Http\Controllers\API\BaseController;
use App\Models\UserBook;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class IsActiveUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user('api');

         if($user->is_active == 0 ){
            $response  = [
                'success' => false,
                'data' => 'Please wait until getting active'
            ];
            return response()->json($response,400);
        }
        return $next($request);
    }
}
