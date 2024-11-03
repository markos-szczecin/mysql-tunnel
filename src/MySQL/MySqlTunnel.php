<?php

declare(strict_types=1);

namespace MySqlSshTunnel\MySQL;

use Exception;
use PDO;
use PDOException;

class MySqlTunnel
{
    private string $sshCommand;
    private array $descriptorSpec;
    /** @var false|resource */
    private $process = false;
    private MySQLCredentials $mySQLCredentials;
    private int $localPort;
    private int $timeout;

    public function __construct(
        MySQLCredentials $mySQLCredentials,
        int $localPort,
        string $sshCommand,
        array $descriptorSpec,
        int $timeout = 10
    )
    {
        $this->sshCommand = $sshCommand;
        $this->descriptorSpec = $descriptorSpec;
        $this->mySQLCredentials = $mySQLCredentials;
        $this->localPort = $localPort;
        $this->timeout = $timeout;
    }

    public function run(): PDO
    {
        $this->process = proc_open($this->sshCommand, $this->descriptorSpec, $pipes);

        if (!is_resource($this->process)) {
            throw new Exception('Could not establish SSH tunnel.');
        }

        $startTime = time();

        $dsn = 'mysql:host=127.0.0.1;port=' . $this->localPort . ';dbname=' . $this->mySQLCredentials->getDatabase();

        try {
            $e = null;
            while (time() - $startTime < $this->timeout) {
                try {
                    return new PDO($dsn, $this->mySQLCredentials->getUsername(), $this->mySQLCredentials->getPassword());
                } catch (\Throwable $e) {
                    continue;
                }
            }
            throw new Exception($e ? $e->getMessage() : 'Timeout error. Cannot establish MySQL connection');
        } catch (PDOException $e) {
            $this->closeTunnel();
            $errors = [$e->getMessage()];
            if (is_resource($pipes[2])) {
                $errors[] = stream_get_contents($pipes[2]);
            }
            throw new Exception('Could not connect to MySQL via SSH tunnel: ' . implode(' | ', $errors));
        }
    }

    private function closeTunnel(): void
    {
        if (is_resource($this->process)) {
            proc_terminate($this->process);
            proc_close($this->process);
        }
    }

    public function __destruct()
    {
        $this->closeTunnel();
    }
}
