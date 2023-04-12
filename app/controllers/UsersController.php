<?php

namespace App\Controllers;

use App\Models\User;

class UsersController extends Controller
{
    public function login()
    {
        $username = $this->params['username'];
        $password = $this->params['password'];

        if (!isset($username) || empty($username) || !isset($password) || empty($password) || strlen($password) < 6) {
            $this->response([
                'message' => 'Invalid username or password',
            ], 400);
        }

        $user = User::find($this->params['username']);

        if (empty($user)) {
            $this->response([
                'message' => 'User not found',
            ], 404);
        }

        if (password_verify($this->params['password'], $user->password_hash)) {
            $this->response([
                'token' => $user->generateToken(),
            ], 200);
        } else {
            $this->response([
                'message' => 'Invalid password',
            ], 401);
        }
    }

    public function register()
    {
        $username = $this->params['username'];
        $password = $this->params['password'];

        if (!isset($username) || empty($username) || !isset($password) || empty($password) || strlen($password) < 6) {
            $this->response([
                'message' => 'Invalid username or password',
            ], 400);
        }

        if (User::find($username)) {
            $this->response([
                'message' => 'User already exists',
            ], 400);
        }

        $user = new User();

        $user->username = $username;
        $user->password_hash = password_hash($password, PASSWORD_DEFAULT);

        if ($user->save()) {
            $this->response([
                'message' => 'User registered',
            ], 201);
        } else {
            $this->response([
                'message' => 'Internal server error',
            ], 500);
        }
    }
}