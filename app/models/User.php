<?php

namespace App\models;

use Firebase\JWT\JWT;

class User extends Model
{
    public $id;
    public $username;
    public $password_hash;

    protected static $table = 'users';

    public static function find($username): ?self
    {
        $model = new static();

        $data = $model->query('SELECT * FROM ' . self::$table . ' WHERE username = :username', [':username' => $username]);
        if (empty($data)) {
            return null;
        }

        $model->id = $data[0]['id'];
        $model->username = $data[0]['username'];
        $model->password_hash = $data[0]['password_hash'];

        return $model;
    }

    public function save() : bool
    {
        $sql = 'INSERT INTO ' . self::$table . ' (username, password_hash) VALUES (:username, :password_hash)';
        return $this->execute($sql, [':username' => $this->username, ':password_hash' => $this->password_hash]);
    }

    public function generateToken(): string
    {
        $payload = [
            'data' => [
                'id' => $this->id,
                'username' => $this->username,
            ],
            'exp' => time() + $this->config['jwt']['expire'],
        ];

        return JWT::encode($payload, $this->config['jwt']['secret'], $this->config['jwt']['algorithm']);
    }

    public static function verifyToken($token): bool
    {
        if (empty($token)) {
            return false;
        }

        $model = new static();

        try {
            JWT::decode($token, $model->config['jwt']['secret'], ['HS256']);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}