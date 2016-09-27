<?php

namespace App\Extensions;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;

class InfoexamUserProvider extends EloquentUserProvider
{
    /**
     * The password preprocessing version.
     *
     * @var int
     */
    const VERSION = 2;

    /**
     * The algorithmic cost that should be used.
     *
     * @var int
     */
    const ROUNDS = 13;

    /**
     * Validate a user against the given credentials.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  array  $credentials
     *
     * @return bool
     */
    public function validateCredentials(UserContract $user, array $credentials)
    {
        $method = 'getPasswordVersion'.$this->getUserVersion($user);

        $plain = call_user_func([$this, $method], $credentials['password'], $user);

        return $this->hasher->check($plain, $user->getAuthPassword());
    }

    /**
     * Get the latest version of preprocessing password.
     *
     * @param string $password
     * @param UserContract $user
     *
     * @return string
     */
    public function getPasswordLatestVersion($password, UserContract $user)
    {
        $method = 'getPasswordVersion'.$this->getCurrentVersion();

        return call_user_func([$this, $method], $password, $user);
    }

    /**
     * Get the version 1 preprocessing password.
     *
     * @param string $password
     *
     * @return string
     */
    protected function getPasswordVersion1($password)
    {
        return $password;
    }

    /**
     * Get the version 2 preprocessing password.
     *
     * @param string $password
     * @param UserContract $user
     *
     * @return string
     */
    protected function getPasswordVersion2($password, UserContract $user)
    {
        $mixed = implode('|', [$password, $user->getAttribute('username')]);

        return base64_encode(hash('sha512', $mixed, true));
    }

    /**
     * Check if user version is equal to current version.
     *
     * @param UserContract $user
     *
     * @return bool
     */
    public function needsUpgrade(UserContract $user)
    {
        return $this->getCurrentVersion() !== $this->getUserVersion($user);
    }

    /**
     * Get the current store version.
     *
     * @return int
     */
    public function getCurrentVersion()
    {
        return self::VERSION;
    }

    /**
     * Get user password store version.
     *
     * @param UserContract $user
     *
     * @return int
     */
    public function getUserVersion(UserContract $user)
    {
        return $user->getAttribute('version');
    }

    /**
     * Get the algorithmic cost that should be used.
     *
     * @return int
     */
    public function getBcryptRounds()
    {
        return self::ROUNDS;
    }
}
