<?php

namespace Sandstorm\NeosApiClient;

use Sandstorm\NeosApiClient\Internal\CreateOrUseExistingUserLoginCommand;
use Sandstorm\NeosApiClient\Internal\SecureApiUriBuilder;

/**
 * API to build URIs which will perform actions on being opened. Methods in this namespace won't perform any action by
 * themselves. Any potential state change may only result once the URI is used to connect to the Neos backend.
 *
 * Instances of this class should not be created directly. Instead, use the {@see NeosApiClient} class to generate an
 * instance of this class for you:
 *
 * ```
 * $neosApiClient = NeosApiClient::create('https://your-neos-domain.com/', 'super-secret-key');
 * $neosApiClientUi = $neosApiClient->ui;
 * ```
 *
 * Currently, the following methods are provided:
 *
 * {@link contentEditing}: {@link ContentEditingBuilder} to open a Neos Ui with a logged-in user to perform edits
 */
final readonly class NeosApiClientUi
{
    public function __construct(private SecureApiUriBuilder $apiUriBuilder)
    {
    }

    public function contentEditing(string $userName): ContentEditingBuilder
    {
        return new ContentEditingBuilder($this->apiUriBuilder->withUserName($userName));
    }
}
