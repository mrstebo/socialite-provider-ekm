# socialite-provider-ekm

This provider is for Socialite 5

**_This is for testing the EKM provider before it is merged into the Socialite Providers repository_**

[![Packagist](https://img.shields.io/packagist/v/mrstebo/socialite-provider-ekm.svg?maxAge=2592000)](https://packagist.org/packages/mrstebo/socialite-provider-ekm)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)

# EKM

```bash
composer require mrstebo/socialite-provider-ekm
```

## Installation & Basic Usage

Please see the [Base Installation Guide](https://socialiteproviders.com/usage/), then follow the provider specific instructions below.

### Add configuration to `config/services.php`

```php
'ekm' => [
  'client_id' => env('EKM_CLIENT_ID'),
  'client_secret' => env('EKM_CLIENT_SECRET'),
  'redirect' => env('EKM_REDIRECT_URI'),
  'scopes' => [
      'openid',
      'profile',
      // Additional scopes
  ]
],
```

*The `openid` and `profile` scopes must be set in order to get an EKM users profile*

### Add provider event listener

Configure the package's listener to listen for `SocialiteWasCalled` events.

Add the event to your `listen[]` array in `app/Providers/EventServiceProvider`. See the [Base Installation Guide](https://socialiteproviders.com/usage/) for detailed instructions.

```php
protected $listen = [
    \SocialiteProviders\Manager\SocialiteWasCalled::class => [
        // ... other providers
        'SocialiteProviders\\EKM\\EKMExtendSocialite@handle',
    ],
];
```

### Usage

You should now be able to use the provider like you would regularly use Socialite (assuming you have the facade installed):

```php
return Socialite::driver('ekm')
            ->with(['prompt' => 'login'])
            ->scopes(config('services.ekm.scopes'))
            ->redirect();
```
