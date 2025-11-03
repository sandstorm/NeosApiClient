<?php

namespace Sandstorm\NeosApiClient;

use Sandstorm\NeosApiClient\Internal\SecureApiUriBuilder;

final readonly class ContentEditingBuilder
{

    public function __construct(private SecureApiUriBuilder $apiUriBuilder)
    {
    }

    public function buildUri()
    {
        return $this->apiUriBuilder->buildUri('/api/embeddedBackend/open');
    }
}
