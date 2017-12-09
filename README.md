# Goodreads PHP

[![Latest Version](https://img.shields.io/github/release/dpoposki/goodreads.svg?style=flat-square)](https://github.com/dpoposki/goodreads/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)

A PHP client for consuming the Goodreads API.

## Install

Via Composer

``` bash
$ composer require poposki/goodreads
```

## Usage

This guide will help you navigate [configuring the client](#set-configuration-when-creating-the-provider), [authenticating your users and retrieving an access token](#authenticate-your-users-and-store-access-token), and [accessing the api on their behalf](#access-the-api-with-access-token).

Full provider documentation is available in the [API Guide](API-GUIDE.md).

*Make sure you have secured your Goodreads API keys before going further. You can check the Goodreads [API documentation](https://www.goodreads.com/api/documentation) and the [developer key](https://www.goodreads.com/api/keys) section.*

This project includes a [basic api example](https://github.com/dpoposki/goodreads/tree/master/example/index.php) and an [OAuth authorization example]((https://github.com/dpoposki/goodreads/tree/master/example/authorization.php)).

### Configure the client

The Goodreads provider needs a few configuration settings to operate successfully.

Setting | Description
--- | ---
`identifier` | Required, the application key associated with your application.
`secret` | Required, the application secret associated with your application.
`callback_uri` | Required when using package to help get access tokens.
`oauth_token` | Required when using the package to make authenticated API requests on behalf of a user.
`oauth_token_secret` | Required when using the package to make authenticated API requests on behalf of a user.

#### Set configuration when creating the provider

```php
$provider = new \Poposki\Goodreads\Provider([
    'identifier'         => 'your-application-key',
    'secret'             => 'your-application-secret',
    'callback_uri'       => 'http://your-callback-uri/',
    'oauth_token'        => 'abcdefghijklmnopqrstuvwxyz',
    'oauth_token_secret' => 'abcdefghijklmnopqrstuvwxyz',
]);
```

### Authenticate your users and store access token

The Goodreads provider is capable of assisting you in walking your users through the OAuth authorization process and providing your application with access token credentials.

This package utilizes [The League's OAuth1 Client](https://github.com/thephpleague/oauth1-client) to provide this assistance.

```php
// Create a provider instance.
$provider = new \Poposki\Goodreads\Provider([
    'identifier'   => 'your-application-key',
    'secret'       => 'your-application-secret',
    'callback_uri' => 'http://your-callback-uri/',
]);

// Obtain Temporary Credentials and User Authorization
// Goodreads does not use an oauth_verifier, instead they have an authorize param
// If the authorize param is 1, user has granted access, otherwise user denied access
if (!isset($_GET['oauth_token'], $_GET['authorize']) || $_GET['authorize'] != 1) {

    // First part of OAuth 1.0 authentication is to
    // obtain Temporary Credentials.
    $temporaryCredentials = $provider->getTemporaryCredentials();

    // Store credentials in the session, we'll need them later
    $_SESSION['temporary_credentials'] = serialize($temporaryCredentials);
    session_write_close();

    // Second part of OAuth 1.0 authentication is to obtain User Authorization
    // by redirecting the resource owner to the login screen on the server.
    // Create an authorization url.
    $authorizationUrl = $provider->getAuthorizationUrl($temporaryCredentials);

    // Redirect the user to the authorization URL. The user will be redirected
    // to the familiar login screen on the server, where they will login to
    // their account and authorize your app to access their data.
    header('Location: ' . $authorizationUrl);
    exit;

// Obtain Token Credentials
} else {

    try {

        // Retrieve the temporary credentials we saved before.
        $temporaryCredentials = unserialize($_SESSION['temporary_credentials']);

        // We will now obtain Token Credentials from the server.
        $tokenCredentials = $provider->getTokenCredentials(
            $temporaryCredentials,
            $_GET['oauth_token']
        );

        // We have token credentials, which we may use in authenticated
        // requests against the service provider's API.
        echo $tokenCredentials->getIdentifier() . "\n";
        echo $tokenCredentials->getSecret() . "\n";

        // Store token credentials so that you can use them for authorized requests later on
        // ...

    } catch (\Exception $e) {

        // Failed to get the token credentials or user details.
        exit($e->getMessage());

    }

}
```

### Access the API with access token

```php
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
```

Most of the methods available in the [API Guide](API-GUIDE.md) require entity ids to conduct business.

## License

Licensed under the MIT License - see the [LICENSE](LICENSE.md) file for details
