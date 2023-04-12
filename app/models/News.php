<?php

namespace App\Models;

class News extends Model
{
    public $id;
    public $title;
    public $content;

    protected static string $table = 'news';

    public static function find($id): ?self
    {
        $model = new static();
        $data = $model->query('SELECT * FROM ' . self::$table . ' WHERE id = :id', [':id' => $id]);
        if (empty($data)) {
            return null;
        }

        $model->id = $data[0]['id'];
        $model->title = $data[0]['title'];
        $model->content = $data[0]['content'];

        return $model;
    }

    public static function all() {
        $model = new static();
        return $model->query('SELECT * FROM ' . static::$table);
    }

    public function save(): bool
    {
        $sql = 'INSERT INTO ' . self::$table . ' (title, content) VALUES (:title, :text)';
        return $this->execute($sql, [':title' => $this->title, ':text' => $this->content]);
    }

    public function update(): bool
    {
        $sql = 'UPDATE ' . self::$table . ' SET title = :title, content = :text WHERE id = :id';
        return $this->execute($sql, [':title' => $this->title, ':text' => $this->content, ':id' => $this->id]);
    }

    public function delete(): bool
    {
        $sql = 'DELETE FROM ' . self::$table . ' WHERE id = :id';
        return $this->execute($sql, [':id' => $this->id]);
    }
}