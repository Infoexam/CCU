<?php

namespace App\Events;

use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Queue\SerializesModels;

class SignIn
{
    use SerializesModels;

    /**
     * @var UserContract
     */
    public $user;

    /**
     * @var array
     */
    public $credentials;

    /**
     * Create a new event instance.
     *
     * @param UserContract $user
     * @param array $credentials
     */
    public function __construct(UserContract $user, array $credentials)
    {
        $this->user = $user;

        $this->credentials = $credentials;
    }
}
