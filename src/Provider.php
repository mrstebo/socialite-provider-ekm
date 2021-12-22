<?php

namespace SocialiteProviders\EKM;

use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Token\Plain;
use SocialiteProviders\Manager\OAuth2\AbstractProvider;
use SocialiteProviders\Manager\OAuth2\User;

class Provider extends AbstractProvider
{
    /**
     * Unique Provider Identifier.
     */
    public const IDENTIFIER = 'EKM';

    /**
     * {@inheritdoc}
     */
    protected $scopeSeparator = ' ';

    /**
     * {@inheritdoc}
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase($this->getInstanceUri().'connect/authorize', $state);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenUrl()
    {
        return $this->getInstanceUri().'connect/token';
    }

    /**
     * {@inheritdoc}
     */
    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get($this->getInstanceUri().'api/v1/settings/shop_information', [
            'headers' => [
                'Authorization' => 'Bearer '.$token,
            ],
        ]);
        $shopInformation = (array) json_decode($response->getBody(), true);
        $config = Configuration::forUnsecuredSigner();
        $parsedToken = $config->parser()->parse($token);

        if ($parsedToken instanceof Plain)
        {
            $user = array_merge($shopInformation['data'], [
                'sub' => $parsedToken->claims()->get('sub'),
                'server_id' => $parsedToken->claims()->get('ServerId'),
            ]);

            return $user;
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function mapUserToObject(array $user)
    {
        return (new User())->setRaw($user)->map([
            'id'        => $user['sub'],
            'server_id' => $user['server_id'],
            'name'     => $user['first_name'].' '.$user['last_name'],
            'email'    => $user['email'],
            'shop_name' => $user['shop_name']
        ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenFields($code)
    {
        return array_merge(parent::getTokenFields($code), [
            'grant_type' => 'authorization_code',
        ]);
    }

    protected function getInstanceUri()
    {
        return $this->getConfig('instance_uri', 'https://api.ekm.net/');
    }

    /**
     * {@inheritdoc}
     */
    public static function additionalConfigKeys()
    {
        return ['instance_uri'];
    }
}
