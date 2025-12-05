<?php

namespace Sandstorm\NeosApiClient\Internal;

class AdaptNeosUiLoginCommand implements LoginCommandInterface
{
    public function __construct(
        public ?bool $showMainMenu = null,
        public ?bool $showLeftSideBar = null,
        public ?PreviewMode $previewMode = null,
    )
    {
    }

    static public function fromStdClass(\stdClass $data): self
    {
        $previewMode = $data->previewMode !== null ? PreviewMode::fromString($data->previewMode) : null;
        return new self($data->showMainMenu, $data->showLeftSideBar, $previewMode);
    }

    public function jsonSerialize(): array
    {
        return [
            'command' => get_class($this),
            'showMainMenu' => $this->showMainMenu,
            'showLeftSideBar' => $this->showLeftSideBar,
            'previewMode' => $this->previewMode,
        ];
    }
}
