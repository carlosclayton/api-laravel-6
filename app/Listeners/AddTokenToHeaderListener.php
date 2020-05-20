<?php

namespace App\Listeners;

use Dingo\Api\Event\ResponseWasMorphed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Tymon\JWTAuth\JWT;

class AddTokenToHeaderListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    private $jwt;

    public function __construct(JWT $jwt)
    {
        $this->jwt = $jwt;
    }

    /**
     * Handle the event.
     *
     * @param ResponseWasMorphed $event
     * @return void
     */
    public function handle(ResponseWasMorphed $event)
    {
        $token = $this->jwt->getToken();
        if ($token) {
            $event->response->headers->set('Authorization', "Bearer{$token->get()}");
        }
    }
}
