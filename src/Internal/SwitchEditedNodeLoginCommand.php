<?php

namespace Sandstorm\NeosApiClient\Internal;

class SwitchEditedNodeLoginCommand implements LoginCommandInterface
{
    public function __construct(public string $nodeId)
    {
    }

    static public function fromStdClass(\stdClass $data): self
    {
        return new self($data->nodeId);
    }

    public function jsonSerialize(): array
    {
        return [
            'command' => get_class($this),
            'nodeId' => $this->nodeId,
        ];
    }

}
