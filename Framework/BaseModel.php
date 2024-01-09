<?php

namespace Framework;

/**
 * Class BaseModel
 * The BaseModel class is used to have a base for all the models link to the database.
 */
abstract class BaseModel
{
    protected Database $db;
    protected const TABLE = '';
    private int $id;

    final public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * @return object[]
     */
    public static function fetchAll(): array
    {
        $stmt = Database::getInstance()->prepare('SELECT * FROM '.static::TABLE);
        $stmt->execute();
        $objs = [];
        foreach ($stmt->fetchAll() as $obj) {
            $arrayObj = get_object_vars($obj);
            $objs[] = (new static())->createObj($arrayObj);
        }

        return $objs;
    }

    public function getId(): int
    {
        return $this->id ?? -1;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public static function fetchById(int $id): object|false
    {
        $stmt = Database::getInstance()->prepare('SELECT * FROM '.static::TABLE.' WHERE id = :id');
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $fetched = $stmt->fetch();
        if ($fetched) {
            return (new static())->createObj(get_object_vars($fetched));
        }

        return false;
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return object[]
     */
    public static function fetchBy(array $data): array
    {
        $query = 'SELECT * FROM '.static::TABLE.' WHERE ';
        foreach ($data as $key => $value) {
            $query .= $key.' = :'.$key.' AND ';
        }
        $stmt = Database::getInstance()->prepare(substr($query, 0, -5));
        foreach ($data as $key => $value) {
            $stmt->bindValue(':'.$key, $value);
        }
        $stmt->execute();
        $instance = new static();

        return array_map(function ($obj) use ($instance) {
            return $instance->createObj(get_object_vars($obj));
        }, $stmt->fetchAll());
    }

    public function delete(): void
    {
        $stmt = Database::getInstance()->prepare('DELETE FROM '.static::TABLE.' WHERE id = :id');
        $stmt->bindValue(':id', $this->getId());
        $stmt->execute();
    }

    /**
     * This function create an object from an array of data.
     *
     * @param array<string, mixed> $data
     */
    abstract public function createObj(array $data): object;

    abstract public function save(): void;
}
