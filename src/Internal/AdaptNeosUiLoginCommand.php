<?php

namespace Sandstorm\NeosApiClient\Internal;

class AdaptNeosUiLoginCommand implements LoginCommandInterface
{
    public function __construct(
        public ?bool $showMainMenu = null,
        public ?bool $showLeftSideBar = null,
        public ?bool $showEditPreviewDropDown = null,
        public ?bool $showDimensionSwitcher = null,
        public ?bool $showPublishDropDown = null,
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
            showEditPreviewDropDown: $data->showEditPreviewDropDown,
            showDimensionSwitcher: $data->showDimensionSwitcher,
            showPublishDropDown: $data->showPublishDropDown,
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
            'showEditPreviewDropDown' => $this->showEditPreviewDropDown,
            'showDimensionSwitcher' => $this->showDimensionSwitcher,
            'showPublishDropDown' => $this->showPublishDropDown,
            'notifyOnPublishTarget' => $this->notifyOnPublishTarget,
            'previewMode' => $this->previewMode,
        ];
    }
}
