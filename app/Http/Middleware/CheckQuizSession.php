<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
class CheckQuizSession
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!session()->has('quiz_user_id') || !session()->has('quiz_attempt_id')) {
            return response()->json([
                'error' => 'No active quiz session. Please start a new quiz.'
            ], 403);
        }
        return $next($request);
    }
}