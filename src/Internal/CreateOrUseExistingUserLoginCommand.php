<?php

namespace Sandstorm\NeosApiClient\Internal;

final readonly class CreateOrUseExistingUserLoginCommand implements LoginCommandInterface
{

    public function __construct(public string $userName)
    {
    }

    static public function fromStdClass(\stdClass $data): self
    {
        return new self($data->userName);
    }

    public function jsonSerialize(): array
    {
        return [
            'command' => get_class($this),
            'userName' => $this->userName,
        ];
    }
}
