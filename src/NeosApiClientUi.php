<?php

namespace Sandstorm\NeosApiClient;

use Sandstorm\NeosApiClient\Internal\CreateOrUseExistingUserLoginCommand;
use Sandstorm\NeosApiClient\Internal\SecureApiUriBuilder;

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
