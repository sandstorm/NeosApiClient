<?php

namespace Sandstorm\NeosApiClient\Internal;

class SwitchDimensionLoginCommand implements LoginCommandInterface
{

    /**
     * @param array<string,string> $dimensions
     */
    public function __construct(public array $dimensions)
    {
    }

    static public function fromStdClass(\stdClass $data): self
    {
        return new self(get_object_vars($data->dimensions));
    }

    public function jsonSerialize(): array
    {
        return [
            'command' => get_class($this),
            'dimensions' => $this->dimensions,
        ];
    }

}
