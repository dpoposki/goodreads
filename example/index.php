<?php

require_once '../vendor/autoload.php';

$provider = new \Poposki\Goodreads\Provider([
    'identifier'         => 'your-application-key',
    'secret'             => 'your-application-secret',
    'oauth_token'        => 'user-oauth-token',
    'oauth_token_secret' => 'user-oauth-token-secret',
]);

try {

    // Get id of user who authorized OAuth
    $user = $provider->getUserId();

    // Get info about an author by id
    $author = $provider->getAuthorById(7160538);

} catch (\Exception $e) {

    // Failed to get the token credentials.
    exit($e->getMessage());

}
