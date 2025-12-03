<?php

namespace Sandstorm\NeosApiClient\Internal;

class NodeCreation implements \JsonSerializable
{
    public function __construct(public string $nodeType, public string $parentNodeId)
    {
    }

    static public function fromStdClass(\stdClass $data): self
    {
        return new self($data->nodeType, $data->parentNodeId);
    }

    public function jsonSerialize(): array
    {
        return [
            'nodeType' => $this->nodeType,
            'parentNodeId' => $this->parentNodeId,
        ];
    }

}
