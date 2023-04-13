<?php

namespace App\Controllers;

use App\Models\News;
use App\models\User;

class NewsController extends Controller
{
    /**
     * @OA\Get(
     *     path="/crud/news/all",
     *     tags={"News"},
     *     @OA\Response(response="200", description="Get all news"),
     * )
     */
    public function all()
    {
        $news = News::all();

        $this->response($news);
    }

    /**
     * @OA\Post(
     *     path="/crud/news/add",
     *     tags={"News"},
     *     @OA\Parameter(
     *         name="title",
     *         in="query",
     *         description="News title",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="content",
     *         in="query",
     *         description="News content",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(response="201", description="News added"),
     *     @OA\Response(response="401", description="Invalid token"),
     *     @OA\Response(response="500", description="Internal server error"),
     * )
     */
    public function add()
    {
        $title = $this->params['title'];
        $content = $this->params['content'];
        if (!isset($title) || empty($title) || !isset($content) || empty($content)) {
            $this->response([
                'message' => 'Invalid username or password',
            ], 400);
        }

        if (!User::verifyToken($this->token)) {
            $this->response([
                'message' => 'Invalid token',
            ], 401);
        }
        
        $news = new News();
        
        $news->title = $title;
        $news->content = $content;
        
        if ($news->save()) {
            $this->response([
                'message' => 'News added',
            ], 201);
        } else {
            $this->response([
                'message' => 'Internal server error',
            ], 500);
        }
    }

    /**
     * @OA\Patch(
     *     path="/crud/news/edit",
     *     tags={"News"},
     *     @OA\Parameter(
     *         name="id",
     *         in="query",
     *         description="News ID",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="title",
     *         in="query",
     *         description="News title",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="content",
     *         in="query",
     *         description="News content",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(response="200", description="Updated news"),
     *     @OA\Response(response="401", description="Invalid token"), 
     *     @OA\Response(response="404", description="News not found"),
     *     @OA\Response(response="500", description="Internal server error"),
     * )
     */
    public function edit()
    {
        if (!User::verifyToken($this->token)) {
            $this->response([
                'message' => 'Invalid token',
            ], 401);
        }

        $news = News::find($this->params['id']);

        if (empty($news)) {
            $this->response([
                'message' => 'News not found',
            ], 404);
        }

        $news->title = $this->params['title'] ?? $news->title;
        $news->content = $this->params['content'] ?? $news->content;

        if ($news->update()) {
            $this->response([
                'message' => 'News updated',
            ], 200);
        } else {
            $this->response([
                'message' => 'Internal server error',
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/crud/news/{id}",
     *     tags={"News"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="News ID",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(response="200", description="Deleted news"),
     *     @OA\Response(response="404", description="News not found"),
     * )
     */
    public function remove()
    {
        if (!User::verifyToken($this->token)) {
            $this->response([
                'message' => 'Invalid token',
            ], 401);
        }

        $news = News::find($this->params['id']);

        if (empty($news)) {
            $this->response([
                'message' => 'News not found',
            ], 404);
        }

        if ($news->delete()) {
            $this->response([
                'message' => 'News removed',
            ], 200);
        } else {
            $this->response([
                'message' => 'Internal server error',
            ], 500);
        }
    }
}