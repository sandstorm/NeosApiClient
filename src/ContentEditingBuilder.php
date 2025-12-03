<?php

namespace Sandstorm\NeosApiClient;

use Sandstorm\NeosApiClient\Internal\NodeCreation;
use Sandstorm\NeosApiClient\Internal\SecureApiUriBuilder;
use Sandstorm\NeosApiClient\Internal\SwitchBaseWorkspaceLoginCommand;
use Sandstorm\NeosApiClient\Internal\SwitchDimensionLoginCommand;
use Sandstorm\NeosApiClient\Internal\SwitchEditedNodeLoginCommand;

final readonly class ContentEditingBuilder
{

    public function __construct(private SecureApiUriBuilder $apiUriBuilder)
    {
    }

    public function publishInto(string $workspace): self
    {
        return new self($this->apiUriBuilder->withCommand(new SwitchBaseWorkspaceLoginCommand($workspace)));
    }

    /**
     * @param array<string,string> $dimensions
     * @return self
     */
    public function dimensions(array $dimensions): self
    {
        return new self($this->apiUriBuilder->withCommand(new SwitchDimensionLoginCommand($dimensions)));
    }

    public function node(string $nodeId, ?NodeCreation $createIfNotExisting = null): self
    {
        return new self($this->apiUriBuilder->withCommand(new SwitchEditedNodeLoginCommand($nodeId, $createIfNotExisting)));
    }

    public function buildUri()
    {
        return $this->apiUriBuilder->buildUri('/api/embeddedBackend/open');
    }
}
