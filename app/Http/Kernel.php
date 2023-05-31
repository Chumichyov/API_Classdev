<?php

namespace App\Http;

use App\Http\Middleware\CreatorDecision;
use App\Http\Middleware\CreatorDecisionAndLeader;
use App\Http\Middleware\ForDecisionMembers;
use App\Http\Middleware\ForFileMembers;
use App\Http\Middleware\ForFolderMembers;
use App\Http\Middleware\ForMembers;
use App\Http\Middleware\ForMessengerMembers;
use App\Http\Middleware\ForReviewMembers;
use App\Http\Middleware\ForTaskMembers;
use App\Http\Middleware\OnlyLeader;
use App\Http\Middleware\OnlyMember;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Illuminate\Http\Middleware\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];


    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array<string, class-string|string>
     */
    protected $routeMiddleware = [
        'forFolderMembers' => ForFolderMembers::class,
        'forTaskMembers' => ForTaskMembers::class,
        'forDecisionMembers' => ForDecisionMembers::class,
        'forMessengerMembers' => ForMessengerMembers::class,
        'forFileMembers' => ForFileMembers::class,
        'forReviewMembers' => ForReviewMembers::class,
        'forMembers' => ForMembers::class,
        'onlyMember' => OnlyMember::class,
        'onlyLeader' => OnlyLeader::class,
        'creatorDecision' => CreatorDecision::class,
        'creatorDecisionAndLeader' => CreatorDecisionAndLeader::class,
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \App\Http\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
    ];
}
