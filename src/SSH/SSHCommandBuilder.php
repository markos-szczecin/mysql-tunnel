<?php

declare(strict_types=1);

namespace MySqlSshTunnel\SSH;

use MySqlSshTunnel\MySQL\MySQLCredentials;

class SSHCommandBuilder
{
    private int $localPort;
    private SSHCredentials $sshCredentials;
    private MySQLCredentials $mysqlCredentials;

    public function __construct(
        SSHCredentials $sshCredentials,
        MySQLCredentials $mysqlCredentials,
        int $localPort
    ) {
        $this->localPort = $localPort;
        $this->sshCredentials = $sshCredentials;
        $this->mysqlCredentials = $mysqlCredentials;
    }

    public function build(): string
    {
        $cmd = 'ssh -o StrictHostKeyChecking=no ';

        if ($this->sshCredentials->getPrivateKey()) {
            $cmd .= '-i ' . escapeshellarg($this->sshCredentials->getPrivateKey()) . ' ';
        }

        $cmd .= '-L ' . $this->localPort . ':' . $this->mysqlCredentials->getHost() . ':' . $this->mysqlCredentials->getPort() . ' ';
        $cmd .= '-N '; // Do not execute a remote command
        $cmd .= '-p ' . $this->sshCredentials->getPort() . ' ';
        $cmd .= escapeshellarg($this->sshCredentials->getUsername() . '@' . $this->sshCredentials->getHost());

        return $cmd;
    }
}
