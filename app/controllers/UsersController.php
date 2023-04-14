<?php

namespace App\Controllers;

use App\Models\User;

class UsersController extends Controller
{
    /**
     * @OA\Post(
     *     path="/crud/users/login",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="username",
     *         in="query",
     *         description="User username",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="User password",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(response="200", description="User logged in"),
     *     @OA\Response(response="400", description="Require username and password"),
     *     @OA\Response(response="401", description="Invalid password"),
     *     @OA\Response(response="404", description="User not found"),
     * )
     */
    public function login()
    {
        $username = $this->params['username'];
        $password = $this->params['password'];

        if (!isset($username) || empty($username) || !isset($password) || empty($password) || strlen($password) < 6) {
            $this->response([
                'message' => 'Require username and password',
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
                'message' => 'User logged in',
            ], 
            200,
            [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $user->generateToken(),
            ]);
        } else {
            $this->response([
                'message' => 'Invalid password',
            ], 401);
        }
    }

    /**
     * @OA\Post(
     *     path="/crud/users/register",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="username",
     *         in="query",
     *         description="User username",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="User password",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(response="201", description="User registered"),
     *     @OA\Response(response="400", description="Require username and password"),
     *     @OA\Response(response="401", description="User already exists"),
     *     @OA\Response(response="500", description="Internal server error"),
     * )
     */
    public function register()
    {
        $username = $this->params['username'];
        $password = $this->params['password'];

        if (!isset($username) || empty($username) || !isset($password) || empty($password) || strlen($password) < 6) {
            $this->response([
                'message' => 'Require username and password',
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