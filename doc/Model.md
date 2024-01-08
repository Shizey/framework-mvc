# Model

To store information in database and interact with them in the application, you need to create a `Model`.

## SQL Creation

The first step to create a model is to define a SQL table link to the model. For that, you need to edit the file in `database/schema.sql`

Example with a user table

```sql
CREATE TABLE IF NOT EXISTS user(
   id INT AUTO_INCREMENT,
   username VARCHAR(50)  NOT NULL,
   PRIMARY KEY(id)
);
```

## Create a model

Then, you need to create a `Model` in the `src/Model`. That model need to extends the `BaseModel` class and implements the following methods

- table : A const variable where you store the table name
- save : This function need to be called when you change data in the model (for example tu `username` in the `UserModel`). You need to insert new data using the [PHP PDO](https://www.php.net/manual/fr/book.pdo.php) the new informations in the database.
- createObj : This function is called by the framework when you fetch information from the databse. Its role is to recreate the class when you fetch data from the databse

Example with `Model/UserModel`

```php
namespace Model;

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
```

## Insert to the database

When you instanciate a model, you can add him to the database by using the method save.

## Fetching the model

In every model who extend `BaseModel` you can use the following methods

- `fetchAll`
- `fetchById`
- `fetchBy` Fetch data using custom parameters

Examples with the `UserModel`

```php
$users = UserModel::fetchAll();
$user = UserModel::fetchById(2);
$user = UserModel::fetchBy(['username' => $body['username']]);
```

## Delete a record

To delete data from the database you can use the method `delete` from a model.
