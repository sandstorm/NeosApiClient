<?php

namespace Sandstorm\NeosApiClient\Internal;

class AdaptNeosUiLoginCommand implements LoginCommandInterface
{
    public function __construct(
        public ?bool $showMainMenu = null,
        public ?bool $showLeftSideBar = null,
        public ?bool $showDocumentTree = null,
        public ?bool $showEditPreviewDropDown = null,
        public ?bool $showDimensionSwitcher = null,
        public ?string $notifyOnPublishTarget = null,
        public ?PreviewMode $previewMode = null,
    )
    {
    }

    static public function fromStdClass(\stdClass $data): self
    {
        $previewMode = $data->previewMode !== null ? PreviewMode::fromString($data->previewMode) : null;
        return new self(
            showMainMenu: $data->showMainMenu,
            showLeftSideBar: $data->showLeftSideBar,
            showDocumentTree: $data->showDocumentTree,
            showEditPreviewDropDown: $data->showEditPreviewDropDown,
            showDimensionSwitcher: $data->showDimensionSwitcher,
            notifyOnPublishTarget: $data->notifyOnPublishTarget,
            previewMode: $previewMode
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'command' => get_class($this),
            'showMainMenu' => $this->showMainMenu,
            'showLeftSideBar' => $this->showLeftSideBar,
            'showDocumentTree' => $this->showDocumentTree,
            'showEditPreviewDropDown' => $this->showEditPreviewDropDown,
            'showDimensionSwitcher' => $this->showDimensionSwitcher,
            'notifyOnPublishTarget' => $this->notifyOnPublishTarget,
            'previewMode' => $this->previewMode,
        ];
    }
}
