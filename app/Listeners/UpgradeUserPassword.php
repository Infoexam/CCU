<?php

namespace App\Listeners;

use App\Events\SignIn;
use App\Extensions\InfoexamUserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpgradeUserPassword implements ShouldQueue
{
    /**
     * @var InfoexamUserProvider
     */
    protected $provider;

    /**
     * Handle the event.
     *
     * @param  SignIn  $event
     *
     * @return void
     */
    public function handle(SignIn $event)
    {
        $this->provider = new InfoexamUserProvider(app(Hasher::class), $event->credentials);

        if ($this->provider->needsUpgrade($event->user)) {
            $this->upgradeUserPassword($event->user, $event->credentials['password']);
        }
    }

    /**
     * Upgrade user password to latest storing version.
     *
     * @param UserContract $user
     * @param string $password
     *
     * @return bool
     */
    protected function upgradeUserPassword(UserContract $user, $password)
    {
        $password = $this->provider->getPasswordLatestVersion($password, $user);

        $password = bcrypt($password, ['rounds' => $this->provider->getBcryptRounds()]);

        $user->setAttribute('password', encrypt($password));
        $user->setAttribute('version', $this->provider->getCurrentVersion());

        return $user->save();
    }
}
