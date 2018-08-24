<?php

namespace PolyAuth\Providers;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;

class UserProvider extends EloquentUserProvider
{

    protected $models;

    /**
     * Create a new user provider.
     *
     * @param  \Illuminate\Contracts\Hashing\Hasher  $hasher
     * @param  string  $model
     * @return void
     */
    public function __construct(HasherContract $hasher)
    {
        $this->models = config('auth.providers.users.models');
        parent::__construct($hasher, null);
    }

    /**
     * Retrieve a user by their unique identifier.
     *
     * @param  mixed  $identifier
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveById($identifier)
    {
        foreach ($this->models as $model) {
            $this->setModel($model);
            $user = parent::retrieveById($identifier);
            if ($user) {
                return $user;
            }
        }
        return null;
    }

    /**
     * Retrieve a user by their unique identifier and "remember me" token.
     *
     * @param  mixed  $identifier
     * @param  string  $token
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByToken($identifier, $token)
    {
        foreach ($this->models as $model) {
            $this->setModel($model);
            $user = parent::retrieveByToken($identifier, $token);
            if ($user) {
                return $user;
            }
        }
        return null;
    }

    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array  $credentials
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        foreach ($this->models as $model) {
            $this->setModel($model);
            $user = parent::retrieveByCredentials($credentials);
            if ($user) {
                return $user;
            }
        }
        return null;
    }

}
