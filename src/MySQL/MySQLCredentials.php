<?php

declare(strict_types=1);

namespace MySqlSshTunnel\MySQL;

class MySQLCredentials
{
    private string $username;
    private string $password;
    private ?string $database;
    private string $host;
    private int $port;

    public function __construct(
        string $username,
        string $password,
        ?string $database = null,
        string $host = '127.0.0.1',
        int $port = 3306
    ) {
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;
        $this->host = $host;
        $this->port = $port;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getDatabase(): ?string
    {
        return $this->database;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getPort(): int
    {
        return $this->port;
    }
}
