<?php

namespace SocialiteProviders\EKM;

use SocialiteProviders\Manager\OAuth2\AbstractProvider;
use SocialiteProviders\Manager\OAuth2\User;
use Lcobucci\JWT\Parser;

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
        // Waiting for documentation for this endpoint
        // $response = $this->getHttpClient()->get($this->getInstanceUri().'api/v1/account', [
        //     'headers' => [
        //         'Authorization' => 'Bearer '.$token,
        //     ],
        // ]);

        // return json_decode($response->getBody(), true);

        $parsedToken = (new Parser())->parse($token);
        $user = [
            'sub' => $parsedToken->getClaim('sub'),
            'server_id' => $parsedToken->getClaim('ServerId'),
        ];

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    protected function mapUserToObject(array $user)
    {
        return (new User())->setRaw($user)->map([
            'id'        => $user['sub'],
            'server_id' => $user['server_id'],
            // 'name'     => $user['name'],
            // 'email'    => $user['email'],
            // 'shop_name' => $user['shop_name']
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
