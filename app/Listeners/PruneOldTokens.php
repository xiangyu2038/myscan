<?php
namespace  App\Listeners;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\Bridge\AccessToken;
use Laravel\Passport\Bridge\RefreshToken;
use Laravel\Passport\Events\AccessTokenCreated;
use Laravel\Passport\Events\RefreshTokenCreated;
use Lcobucci\JWT\Token;

class PruneOldTokens {
    public function __construct()
    {

    }

    public function handle(RefreshTokenCreated $event){

        DB::table('oauth_refresh_tokens')
            ->where('access_token_id', $event->accessTokenId)
            ->orWhere('revoked', true)
            ->delete();

    }

}