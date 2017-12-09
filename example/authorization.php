<?php

require_once '../vendor/autoload.php';

session_start();

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
