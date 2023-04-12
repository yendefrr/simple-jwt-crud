<?php

namespace App\Controllers;

use App\Models\News;
use App\models\User;

class NewsController extends Controller
{
    public function all()
    {
        $news = News::all();

        $this->response($news);
    }

    public function add()
    {
        if (!User::verifyToken($this->token)) {
            $this->response([
                'message' => 'Invalid token',
            ], 401);
        }
        
        $news = new News();
        
        $news->title = $this->params['title'];
        $news->content = $this->params['content'];
        
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