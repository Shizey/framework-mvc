# Fixtures

Fixtures are used to load a "fake" set of data into a database that can then be used for testing

## Create a Fixture

To create your Fixture you need to create a file in the folder `src/Fixtures`. That file need to implements `Framework\Interfaces\FixturesInterface` and have a `const` variable named `TABLE`. Then, you can import your model and create whatever fake data you want !

Example with `src/Fixtures/UserFixtures.php`

```php
namespace Fixtures;

use Framework\Interfaces\FixturesInterface;
use Model\UserModel;

class UserFixtures implements FixturesInterface
{
    public const TABLE = 'user';
    public function load(): void
    {
        $user = new UserModel();

        $user->setUsername('admin');
        $user->save();
    }
}
```

## Load your Fixtures

**⚠️ Load a fixture will remove all content in the current table**

To run your fixtures, simply run the following command

```bash
php src/Framework/Fixtures.php
```
