<?php

namespace Modules\Auth\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTelegramKeyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $channel = $request->user()->telegramKeys();
        $id = $request->route('id');

        if (!$channel->whereId($id)->exists()) {
            return redirect()->intended('dashboard');
        }

        return $next($request);
    }
}
