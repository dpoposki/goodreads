<?php

/*
 * This file is part of the Goodreads package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Poposki\Goodreads\Server;

use League\OAuth1\Client\Credentials\TemporaryCredentials;

/**
 * @author Darko Poposki <poposki.darko@gmail.com>
 */
trait AuthorizationTrait
{
    /**
     * @return \Poposki\Goodreads\Server
     */
    abstract public function getServer();

    /**
     * @param TemporaryCredentials $temporaryCredentials
     *
     * @return string
     */
    public function getAuthorizationUrl(TemporaryCredentials $temporaryCredentials)
    {
        return $this->getServer()->getAuthorizationUrl($temporaryCredentials);
    }

    /**
     * @return TemporaryCredentials
     */
    public function getTemporaryCredentials()
    {
        return $this->getServer()->getTemporaryCredentials();
    }

    /**
     * @param TemporaryCredentials $temporaryCredentials
     * @param string $temporaryIdentifier
     *
     * @return \League\OAuth1\Client\Credentials\TokenCredentials
     * @throws \League\OAuth1\Client\Credentials\CredentialsException
     */
    public function getTokenCredentials(TemporaryCredentials $temporaryCredentials, $temporaryIdentifier)
    {
        return $this->getServer()->getTokenCredentialsWithoutVerifier($temporaryCredentials, $temporaryIdentifier);
    }

    /**
     * @return \League\OAuth1\Client\Credentials\TokenCredentials|null
     */
    public function getToken()
    {
        return $this->getServer()->getToken();
    }
}
