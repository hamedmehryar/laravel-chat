# Laravel Chat
This package will allow you to add a full user messaging system into your Laravel application.


## Features
* Thread based conversation
* Invite other users to the thread

## Common uses
* Open threads (everyone can see everything)
* Group messaging (only participants can see their threads)
* One to one messaging (private or direct thread)


## Installation (Laravel 5.x) (installation in not recommended because this is a pre-release package)
In composer.json:

    "require": {
        "hamedmehryar/laravel-chat" "0.0.0"
    }

Run:

    composer update

Add the service provider to `config/app.php` under `providers`:

    'providers' => [
        Hamedmehryar\Chat\ChatServiceProvider::class,
    ]

Publish Assets

	php artisan vendor:publish --provider="Hamedmehryar\Chat\ChatServiceProvider"
	
Update config file to reference your User Model:

	config/chat.php

Migrate your database:

    php artisan migrate

Add the trait to your user model:

    use Hamedmehryar\Chat\Traits\Chatable;
    
    class User extends Model {
    	use Chatable;
    }

Add smiley.css file to your page

    <link rel="stylesheet" href="../path/to/your/public/hamedmehryar/chat/smiley.css" >


__Note:__ These examples use the [laravelcollective/html](http://laravelcollective.com/docs/5.0/html) package that is no longer included in Laravel 5 out of the box. Make sure you require this dependency in your `composer.json` file if you intend to use the example files.


## Credits

- [Hamed Mehryar](https://github.com/hamedmehryar)

### Special Thanks
This package used [cmgmyr/laravel-messenger](https://github.com/cmgmyr/laravel-messenger) as a starting point.
