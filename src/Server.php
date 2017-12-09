<?php

/*
 * This file is part of the Goodreads package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Poposki\Goodreads;

use GuzzleHttp\Exception\BadResponseException;
use League\OAuth1\Client\Credentials\ClientCredentialsInterface;
use League\OAuth1\Client\Credentials\CredentialsException;
use League\OAuth1\Client\Credentials\TemporaryCredentials;
use League\OAuth1\Client\Credentials\TokenCredentials;
use League\OAuth1\Client\Server\Server as AbstractServer;
use League\OAuth1\Client\Server\User;
use League\OAuth1\Client\Signature\SignatureInterface;

/**
 * @author Darko Poposki <poposki.darko@gmail.com>
 */
class Server extends AbstractServer
{
    const GOODREADS_HOST_URL_SSL = 'https://www.goodreads.com';

    /**
     * @var TokenCredentials|null
     */
    protected $tokenCredentials;

    /**
     * @param ClientCredentialsInterface|array $clientCredentials
     * @param SignatureInterface|null $signature
     */
    public function __construct($clientCredentials, SignatureInterface $signature = null)
    {
        if (is_array($clientCredentials)) {
            $this->setToken($clientCredentials);
        }

        parent::__construct($clientCredentials, $signature);
    }

    /**
     * @return TokenCredentials|null
     */
    public function getToken()
    {
        return $this->tokenCredentials;
    }

    /**
     * @param array $clientCredentials
     */
    public function setToken(array $clientCredentials)
    {
        if (!isset($clientCredentials['oauth_token'], $clientCredentials['oauth_token_secret'])) {
            return;
        }

        $tokenCredentials = new TokenCredentials();
        $tokenCredentials->setIdentifier($clientCredentials['oauth_token']);
        $tokenCredentials->setSecret($clientCredentials['oauth_token_secret']);

        $this->tokenCredentials = $tokenCredentials;
    }

    /**
     * @param string $method
     * @param string $path
     * @param array $parameters
     * @param bool $authenticate
     *
     * @return array
     * @throws CredentialsException
     */
    public function request($method, $path, $parameters = [], $authenticate = false)
    {
        $uri = $this->buildEndpointUri($path, $parameters, $authenticate);

        $headers = [];

        if ($authenticate) {
            $headers = $this->getAuthenticationHeaders($method, $uri);
        }

        $response = $this->createHttpClient()->request($method, $uri, ['headers' => $headers]);

        $body = (string) $response->getBody();

        if ($parameters['format'] == 'json') {
            return json_decode($body, true);
        }

        // This magical line is a copy-paste from https://stackoverflow.com/a/20431742/1279092
        // Do you know of a better way to transform XML to an array? Please make a PR
        return json_decode(json_encode(simplexml_load_string($body, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    }

    /**
     * @param string $method
     * @param string $uri
     *
     * @return array
     * @throws CredentialsException
     */
    protected function getAuthenticationHeaders($method, $uri)
    {
        if (!$this->tokenCredentials) {
            throw new CredentialsException('Unable to request the given path because of missing OAuth credentials');
        }

        return $this->getHeaders($this->tokenCredentials, $method, $uri);
    }

    /**
     * @param string $path
     * @param array $parameters
     * @param bool $authenticate
     *
     * @return string
     */
    protected function buildEndpointUri($path, &$parameters, $authenticate)
    {
        $uri = self::GOODREADS_HOST_URL_SSL . '/' . ltrim($path, '/');

        if (!$authenticate) {
            $parameters['key'] = $this->clientCredentials->getIdentifier();
        }

        if (!isset($parameters['format'])) {
            $parameters['format'] = 'xml';
        }

        $uri .= '?' . http_build_query($parameters);

        return $uri;
    }

    /**
     * Creates temporary credentials from the body response.
     *
     * @param string $body
     *
     * @return TemporaryCredentials
     * @throws CredentialsException
     */
    protected function createTemporaryCredentials($body)
    {
        parse_str($body, $data);

        if (!$data || !is_array($data)) {
            throw new CredentialsException('Unable to parse temporary credentials response.');
        }

        $temporaryCredentials = new TemporaryCredentials();
        $temporaryCredentials->setIdentifier($data['oauth_token']);
        $temporaryCredentials->setSecret($data['oauth_token_secret']);

        return $temporaryCredentials;
    }

    /**
     * Retrieves token credentials by passing in the temporary credentials,
     * the temporary credentials identifier as passed back by the server
     *
     * @param TemporaryCredentials $temporaryCredentials
     * @param string               $temporaryIdentifier
     *
     * @return TokenCredentials
     * @throws CredentialsException
     */
    public function getTokenCredentialsWithoutVerifier(TemporaryCredentials $temporaryCredentials, $temporaryIdentifier)
    {
        if ($temporaryIdentifier !== $temporaryCredentials->getIdentifier()) {
            throw new \InvalidArgumentException(
                'Temporary identifier passed back by server does not match that of stored temporary credentials.
                Potential man-in-the-middle.'
            );
        }

        $uri = $this->urlTokenCredentials();

        $client = $this->createHttpClient();

        $headers = $this->getHeaders($temporaryCredentials, 'POST', $uri);

        try {
            $response = $client->post($uri, [
                'headers' => $headers,
            ]);
        } catch (BadResponseException $e) {
            return $this->handleTokenCredentialsBadResponse($e);
        }

        return $this->createTokenCredentials((string) $response->getBody());
    }

    /**
     * {@inheritdoc}
     */
    public function urlTemporaryCredentials()
    {
        return self::GOODREADS_HOST_URL_SSL . '/oauth/request_token';
    }

    /**
     * {@inheritdoc}
     */
    public function urlAuthorization()
    {
        return self::GOODREADS_HOST_URL_SSL . '/oauth/authorize';
    }

    /**
     * {@inheritdoc}
     */
    public function urlTokenCredentials()
    {
        return self::GOODREADS_HOST_URL_SSL . '/oauth/access_token';
    }

    /**
     * {@inheritdoc}
     */
    public function urlUserDetails()
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function userDetails($data, TokenCredentials $tokenCredentials)
    {
        return new User();
    }

    /**
     * {@inheritdoc}
     */
    public function userUid($data, TokenCredentials $tokenCredentials)
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function userEmail($data, TokenCredentials $tokenCredentials)
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function userScreenName($data, TokenCredentials $tokenCredentials)
    {
        return '';
    }
}
