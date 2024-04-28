<?php

namespace Modules\Auth\Http\Middleware\Api;

use App\Http\Enums\HttpStatusesEnum;
use App\Http\Responses\ErrorJsonResponse;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTelegramChannelAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response|ErrorJsonResponse
    {
        if (!$request->telegram_key_id) {
            return $next($request);
        }

        $user = $request->user();

        if (!$user->telegramKeys()->whereId($request->telegram_key_id)->exists()) {
            return new ErrorJsonResponse('You don\'t have access to this channel', HttpStatusesEnum::FORBIDDEN);
        }

        return $next($request);
    }
}
