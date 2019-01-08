<?php
namespace  App\Listeners;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\Bridge\AccessToken;
use Laravel\Passport\Bridge\RefreshToken;
use Laravel\Passport\Events\AccessTokenCreated;
use Laravel\Passport\Events\RefreshTokenCreated;
use Lcobucci\JWT\Token;

class RevokeOldTokens {
    public function __construct()
    {

    }

    public function handle(AccessTokenCreated $event){

        DB::table('oauth_access_tokens')->where('id', '!=', $event->tokenId)
            ->where('user_id', $event->userId)
            ->where('client_id', $event->clientId)
            ->where('expires_at', '<', Carbon::now())
            ->orWhere('revoked', true)
            ->delete();
    }
}