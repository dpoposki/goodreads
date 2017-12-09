<?php

/*
 * This file is part of the Goodreads package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Poposki\Goodreads;

use League\OAuth1\Client\Credentials\ClientCredentialsInterface;
use League\OAuth1\Client\Credentials\CredentialsException;
use League\OAuth1\Client\Signature\SignatureInterface;

/**
 * @author Darko Poposki <poposki.darko@gmail.com>
 */
class Provider
{
    use Server\MethodsTrait,
        Server\AuthorizationTrait;

    /**
     * @var Server
     */
    protected $server;

    /**
     * @param ClientCredentialsInterface|array $clientCredentials
     * @param SignatureInterface|null $signature
     */
    public function __construct($clientCredentials, SignatureInterface $signature = null)
    {
        $this->setServer($clientCredentials, $signature);
    }

    /**
     * @param string $path
     * @param array $parameters
     * @param bool $authenticate
     *
     * @return array
     * @throws CredentialsException
     */
    public function get($path, $parameters = [], $authenticate = false)
    {
        return $this->server->request('GET', $path, $parameters, $authenticate);
    }

    /**
     * @param string $path
     * @param array $parameters
     * @param bool $authenticate
     *
     * @return array
     * @throws CredentialsException
     */
    public function post($path, $parameters = [], $authenticate = false)
    {
        return $this->server->request('POST', $path, $parameters, $authenticate);
    }

    /**
     * @param string $path
     * @param array $parameters
     * @param bool $authenticate
     *
     * @return array
     * @throws CredentialsException
     */
    public function put($path, $parameters = [], $authenticate = false)
    {
        return $this->server->request('PUT', $path, $parameters, $authenticate);
    }

    /**
     * @param string $path
     * @param array $parameters
     * @param bool $authenticate
     *
     * @return array
     * @throws CredentialsException
     */
    public function delete($path, $parameters = [], $authenticate = false)
    {
        return $this->server->request('DELETE', $path, $parameters, $authenticate);
    }

    /**
     * @return Server
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * @param ClientCredentialsInterface|array $clientCredentials
     * @param SignatureInterface|null $signature
     */
    public function setServer($clientCredentials, SignatureInterface $signature = null)
    {
        $this->server = new Server($clientCredentials, $signature);
    }
}
