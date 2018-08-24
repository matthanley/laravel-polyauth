# Polymorphic Authentication for Laravel

Allows authentication using multiple User models in Laravel 5.

## Installation

Installation is performed via Composer:  

`composer require matthanley/laravel-polyauth`

## Configuration

Register the auth driver by adding the following to the `boot()` method in `app/Providers/AuthServiceProvider.php`:

```php
\Auth::provider('polymorphic', function ($app) {
    return $app->make(\PolyAuth\Providers\UserProvider::class);
});
```

Update `config/auth.php` to set your auth driver to `polymorphic` and define the models with which you wish to authenticate:

```php
'providers' => [
    'users' => [
        'driver' => 'polymorphic',
        'models' => [
            App\User::class,
            App\Admin::class,
        ],
    ],
],
```

Ensure your user models are using globally unique identifiers and add events to enforce globally unique emails/usernames etc. For example:

```php

/**
 * Migrations
 */

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->primary('id');
            /* ... */
        });
    }
}

/**
 * Models
 */

use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Uuid;

class User extends Authenticatable
{

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Generate an ID
            $model->setAttribute($model->getKeyName(), Uuid::uuid1()->toString());
            // Make sure email is globally unique
            if (Auth::getProvider()->retrieveByCredentials(['email' => $model->email])) {
                throw new \Exception();
            }
        });
    }

    /* ... */
}

```
