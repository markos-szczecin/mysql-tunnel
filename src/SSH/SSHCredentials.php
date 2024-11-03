<?php

declare(strict_types=1);

namespace MySqlSshTunnel\SSH;

class SSHCredentials
{
    private string $host;
    private int $port;
    private string $username;
    private ?string $privateKey;

    public function __construct(
        string $host,
        string $username,
        ?string $privateKey = null,
        int $port = 22
    )
    {
        $this->host = $host;
        $this->port = $port;
        $this->username = $username;
        $this->privateKey = $privateKey;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getPort(): int
    {
        return $this->port;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPrivateKey(): ?string
    {
        return $this->privateKey;
    }
}
