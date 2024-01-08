<?php

namespace Framework;

class Database extends \PDO
{
    /**
     * @var Database
     */
    private static $instance;

    /**
     * Database constructor.
     * The constructor is private to prevent multiple instances of the Database class
     * The constructor creates the database if it does not exist and selects it
     * The constructor executes the schema.sql file to create the tables
     */
    private function __construct()
    {
        parent::__construct(
            'mysql:host=' . $_ENV['DB_HOST'] . ';port=' . $_ENV['DB_PORT'] . ';charset=utf8',
            $_ENV['DB_USER'],
            $_ENV['DB_PASSWORD'],
            [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ
            ]
        );

        $dbName = $_ENV['DB_DATABASE'];

        $this->exec("CREATE DATABASE IF NOT EXISTS `$dbName`");
        $this->exec("use `$dbName`");

        $content = file_get_contents(__DIR__ . '/../../database/schema.sql');

        if ($content === false) {
            throw new \Exception("Impossible to read the schema.sql file");
        }

        $this->exec($content);
    }

    public function tableExists(string $table): bool
    {
        $stmt = $this->prepare("SHOW TABLES LIKE :table");
        $stmt->bindValue(':table', $table);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    /**
     * getInstance
     * The getInstance method is used to get the instance of the Database class
     * If the instance does not exist, it is created
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }

        return self::$instance;
    }
}