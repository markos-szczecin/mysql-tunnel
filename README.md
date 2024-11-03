# SSH tunnel for MySQL

Creating SSH tunnel for remote MySQL server


## Notice

SSH Tunnel currently doesn't support prompted SSH password for provided private key, so make sure your ssh key doesn't require password

If you are not sure your SSH key pair require passphrase you can generate new SSH key pair

```bash
#generate key pair without passphrase
ssh-keygen -t rsa -b 4096 -f ~/.ssh/id_rsa_no_pass -N ""

#copy public key to remote server
ssh-copy-id -i ~/.ssh/id_rsa_no_pass.pub user@host
```

Make sure your PHP app has permission to access SSH private key file

## Installation

Use Composer to install:

Package might not be yet available in packagist.

Create directory "composer-artifact" in your project root directory and put the project zip package in there

Register directory in your composer.json

```json
...
    "repositories": [
        {
            "type": "artifact",
            "url": "composer-artifact/"
        }
    ],
...
```

Run command

```bash
composer require mpedzik/mysql-tunnel
```

## Usage

```php
$tunnelBuilder = new TunnelBuilder(
        new SSHCredentials(
            'ssh.example.host',
            'user',
            'path/to/private_key'
        ),
        new MySQLCredentials(
            'username',
            'pass',
            'dbname'
        ),
        (new GetAvailablePort())->execute()
    );
    $mysql= $tunnelBuilder->build()->run();
    $q = $mysql->query('SELECT * FROM example LIMIT 1');
    print_r($q->fetch());
```
