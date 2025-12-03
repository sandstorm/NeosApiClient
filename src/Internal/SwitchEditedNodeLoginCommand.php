<?php

namespace Sandstorm\NeosApiClient\Internal;

class SwitchEditedNodeLoginCommand implements LoginCommandInterface
{
    public function __construct(
        public string $nodeId,
        public ?NodeCreation $nodeCreation = null,
    )
    {
    }

    static public function fromStdClass(\stdClass $data): self
    {
        $nodeCreation = $data->nodeCreation !== null ? NodeCreation::fromStdClass($data->nodeCreation) : null;
        return new self($data->nodeId, $nodeCreation);
    }

    public function jsonSerialize(): array
    {
        return [
            'command' => get_class($this),
            'nodeId' => $this->nodeId,
            'nodeCreation' => $this->nodeCreation,
        ];
    }

}
