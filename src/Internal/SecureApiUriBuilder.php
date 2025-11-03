<?php

namespace Sandstorm\NeosApiClient\Internal;

use Firebase\JWT\JWT;

/**
 * @internal
 */
final class SecureApiUriBuilder
{
    /**
     * @var LoginCommandInterface[]
     */
    private array $commands;

    public function __construct(
        private string        $baseUrl,
        private string        $jwtSecret,
        private string|null   $userName,
        LoginCommandInterface ...$commands,
    )
    {
        $this->commands = $commands;
    }

    public function withCommand(LoginCommandInterface $command): self
    {
        $commands = [...$this->commands, $command];
        return new self($this->baseUrl, $this->jwtSecret, $this->userName, ...$commands);
    }

    public function withUserName(string $userName): self
    {
        return new self($this->baseUrl, $this->jwtSecret, $userName, ...$this->commands);
    }

    public function buildUri(string $apiEndpointPath): string
    {
        $payload = [
            //'iss' => 'http://example.org',
            //'aud' => 'http://example.com',
            'sub' => $this->userName,
            'iat' => time(),
            'nbf' => time(),
            'exp' => time() + 60*10, // 10 minutes??
            'neos_cmd' => array_map(fn(LoginCommandInterface $command) => $command->jsonSerialize(), $this->commands),
        ];
        $jwt = JWT::encode($payload, $this->jwtSecret, 'HS256');
        return $this->baseUrl . $apiEndpointPath . '?neosapi_auth_jwt=' . rawurlencode($jwt) ;
    }


}
