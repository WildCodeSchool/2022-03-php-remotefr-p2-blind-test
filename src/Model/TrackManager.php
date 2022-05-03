<?php

namespace App\Model;

use App\Model\AbstractManager;

class TrackManager extends AbstractManager
{
    public const TABLE = 'track';
    public function selectPathRand(int $id): array|false
    {
        $statement = $this->pdo->prepare("SELECT path FROM " . static::TABLE .
            " WHERE category_id=:id ORDER BY RAND()");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function selectAll(string $orderBy = '', string $direction = 'ASC'): array
    {
        $query = 'SELECT t.*, c.name  FROM ' . static::TABLE . ' as t JOIN category as c ON c.id = t.category_id';
        if ($orderBy) {
            $query .= ' ORDER BY ' . $orderBy . ' ' . $direction;
        }

        return $this->pdo->query($query)->fetchAll();
    }

    public function insert(array $track): int
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE .
            " (`title`,`artist`, `path`, `category_id`)
        VALUES (:title,:artist, :path, :category_id)");

        $statement->bindValue(':title', $track['title'], \PDO::PARAM_STR);
        $statement->bindValue(':artist', $track['artist'], \PDO::PARAM_STR);
        $statement->bindValue(':path', $track['path'], \PDO::PARAM_STR);
        $statement->bindValue(':category_id', $track['category'], \PDO::PARAM_INT);

        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }

    public function update(array $track): bool
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE .
        " SET `title`=:title, `path`=:path, `category_id`=:category_id  WHERE `id`=:id");
        $statement->bindValue('id', $track['id'], \PDO::PARAM_INT);
        $statement->bindValue('title', $track['title'], \PDO::PARAM_STR);
        $statement->bindValue(':path', $track['path'], \PDO::PARAM_STR);
        $statement->bindValue(':category_id', $track['category'], \PDO::PARAM_INT);

        return $statement->execute();
    }
}
