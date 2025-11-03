<?php

namespace Sandstorm\NeosApiClient;

use Sandstorm\NeosApiClient\Internal\SecureApiUriBuilder;
use Sandstorm\NeosApiClient\Internal\SwitchBaseWorkspaceLoginCommand;

final readonly class ContentEditingBuilder
{

    public function __construct(private SecureApiUriBuilder $apiUriBuilder)
    {
    }

    public function publishInto(string $workspace): self
    {
        return new self($this->apiUriBuilder->withCommand(new SwitchBaseWorkspaceLoginCommand($workspace)));
    }

    public function buildUri()
    {
        return $this->apiUriBuilder->buildUri('/api/embeddedBackend/open');
    }
}
