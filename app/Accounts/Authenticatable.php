<?php

namespace App\Accounts;

use Illuminate\Auth\Authenticatable as IlluminateAuthenticatable;

trait Authenticatable
{
    use IlluminateAuthenticatable;

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        if ($this->getAttribute('version') > 1) {
            return decrypt($this->password);
        }

        return $this->password;
    }
}
