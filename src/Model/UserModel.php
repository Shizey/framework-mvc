<?php

namespace Model;

use Framework\BaseModel;
use Framework\Database;

class UserModel extends BaseModel
{

    public const TABLE = 'user';
    private string $username;

    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function save(): void
    {
        $this->db = Database::getInstance();
        if (self::fetchById($this->getId())) {
            $stmt = $this->db->prepare("UPDATE " . self::TABLE . " SET username = :username WHERE id = :id");
            $stmt->bindValue(':username', $this->getUsername());
            $stmt->bindValue(':id', $this->getId());
            $stmt->execute();
        } else {
            $stmt = $this->db->prepare("INSERT INTO " . self::TABLE . " (username) VALUES (:username)");
            $stmt->bindValue(':username', $this->getUsername());
            $stmt->execute();
        }
    }

    public function createObj(array $data): self
    {
        $user = new UserModel();
        $user->setUsername($data['username']);
        $user->setId($data['id']);
        return $user;
    }
}