<?php

namespace Sandstorm\NeosApiClient\Internal;

class AdaptNeosUiLoginCommand implements LoginCommandInterface
{
    public function __construct(
        public ?bool $showMainMenu = null,
        public ?bool $showLeftSideBar = null,
    )
    {
    }

    static public function fromStdClass(\stdClass $data): self
    {
        return new self($data->showMainMenu, $data->showLeftSideBar);
    }

    public function jsonSerialize(): array
    {
        return [
            'command' => get_class($this),
            'showMainMenu' => $this->showMainMenu,
            'showLeftSideBar' => $this->showLeftSideBar,
        ];
    }

}
