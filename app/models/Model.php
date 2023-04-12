<?php

namespace App\Models;

class Model
{
    protected $dbh;
    protected $config;

    public function __construct()
    {
        $this->config = include __DIR__ . '/../../configs/main.php';
        $this->dbh = new \PDO('mysql:host=' . $this->config['mysql']['host'] . ';dbname=' . $this->config['mysql']['dbname'], $this->config['mysql']['user'], $this->config['mysql']['password']);
    }

    protected function query(string $sql, array $data = []): array
    {
        $sth = $this->dbh->prepare($sql);
        $sth->execute($data);

        return $sth->fetchAll(\PDO::FETCH_ASSOC);
    }

    protected function execute(string $sql, array $data = []): bool
    {
        $sth = $this->dbh->prepare($sql);
        return $sth->execute($data);
    }
}