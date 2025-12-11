<?php
declare(strict_types=1);

namespace Sandstorm\NeosApiClient;

use Sandstorm\NeosApiClient\Internal\SecureApiUriBuilder;

/**
 * General API Client for Neos. Main entry point for all Neos API related classes and methods.
 *
 * This Api requires the 'sandstorm/neosapi' Neos package to work. If the Neos instance does not include that package
 * this API Client is unable to perform any action at all.
 *
 * To create a new instance use the static {@link create} method like this:
 *
 *  ```
 *   $neosApiClient = NeosApiClient::create('https://your-neos-domain.com/', 'super-secret-key');
 *  ```
 *
 * The provided secret needs to be the same as the one set in Neos in the 'Sandstorm.NeosApi.Secret' setting key. If
 * the secret does not match the Neos backend will deny access to any action performed by this client directly or
 * indirectly (through generated URI's).
 *
 * Currently, the following API's are available:
 *
 * {@link $ui} ({@see NeosApiClientUi})  API to generate URL's for the Neos Ui
 */
final readonly class NeosApiClient
{
    /**
     * API to build URIs which will perform actions on being opened. Methods in this object won't perform any action
     * by themselves. Any potential state change may only result once the URI is used to connect to the Neos backend.
     */
    public NeosApiClientUi $ui;

    /**
     * Create a new NeosApiClient instance with the provided baseUrl and secret.
     *
     * The provided secret needs to be the same as the one set in Neos in the 'Sandstorm.NeosApi.Secret' setting key.
     * If  the secret does not match the Neos backend will deny access to any action performed by this client directly
     * or indirectly (through generated URI's).
     *
     * @param string $baseUrl the base url to your neos instance, e.g. 'https://your-neos-domain.com/'
     * @param string $jwtSecret the same secret as used in the 'Sandstorm.NeosApi.Secret' in the 'sandstorm/neosapi'
     *                          neos package.
     * @return self A new instance of this class
     */
    public static function create(string $baseUrl, string $jwtSecret): self
    {
        return new self(new SecureApiUriBuilder($baseUrl, $jwtSecret, null));
    }

    private function __construct(SecureApiUriBuilder $apiUriBuilder)
    {
        $this->ui = new NeosApiClientUi($apiUriBuilder);
    }
}
