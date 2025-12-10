<?php

namespace Sandstorm\NeosApiClient;

use Sandstorm\NeosApiClient\Internal\AdaptNeosUiLoginCommand;
use Sandstorm\NeosApiClient\Internal\NodeCreation;
use Sandstorm\NeosApiClient\Internal\PreviewMode;
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

    public function hideMainMenu(): self
    {
        return new self($this->apiUriBuilder->withCommand(new AdaptNeosUiLoginCommand(showMainMenu: false)));
    }

    public function hideLeftSideBar(): self
    {
        return new self($this->apiUriBuilder->withCommand(new AdaptNeosUiLoginCommand(showLeftSideBar: false)));
    }

    public function hideEditPreviewDropDown(): self
    {
        return new self($this->apiUriBuilder->withCommand(new AdaptNeosUiLoginCommand(showEditPreviewDropDown: false)));
    }

    public function hideDimensionSwitcher(): self
    {
        return new self($this->apiUriBuilder->withCommand(new AdaptNeosUiLoginCommand(showDimensionSwitcher: false)));
    }

    public function notifyOnPublish(string $targetOrigin): self
    {
        return new self($this->apiUriBuilder->withCommand(new AdaptNeosUiLoginCommand(notifyOnPublishTarget: $targetOrigin)));
    }

    public function minimalUi(): self
    {
        // to be extended once more elements can be hidden
        return new self($this->apiUriBuilder->withCommand(new AdaptNeosUiLoginCommand(
            showMainMenu: false,
            showLeftSideBar: false,
            showEditPreviewDropDown: false,
            showDimensionSwitcher: false
        )));
    }

    public function editPreviewMode(PreviewMode $previewMode): self
    {
        return new self($this->apiUriBuilder->withCommand(new AdaptNeosUiLoginCommand(
            previewMode: $previewMode,
        )));
    }

    public function buildUri()
    {
        return $this->apiUriBuilder->buildUri('/api/embeddedBackend/open');
    }
}
