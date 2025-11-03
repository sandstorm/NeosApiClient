<?php

namespace Sandstorm\NeosApiClient\Internal;

/**
 * @internal
 */
final readonly class SwitchBaseWorkspaceLoginCommand implements LoginCommandInterface
{

    public function __construct(public string $baseWorkspace)
    {
    }

    static public function fromStdClass(\stdClass $data): self
    {
        return new self($data->baseWorkspace);
    }

    public function jsonSerialize(): array
    {
        return [
            'command' => get_class($this),
            'baseWorkspace' => $this->baseWorkspace,
        ];
    }
}
