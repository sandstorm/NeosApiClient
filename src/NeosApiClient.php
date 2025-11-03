<?php
declare(strict_types=1);

namespace Sandstorm\NeosApiClient;

use Sandstorm\NeosApiClient\Internal\SecureApiUriBuilder;

final readonly class NeosApiClient
{
    public NeosApiClientUi $ui;

    public static function create(string $baseUrl, string $jwtSecret): self
    {
        return new self(new SecureApiUriBuilder($baseUrl, $jwtSecret, null));
    }

    private function __construct(SecureApiUriBuilder $apiUriBuilder)
    {
        $this->ui = new NeosApiClientUi($apiUriBuilder);
    }
}
