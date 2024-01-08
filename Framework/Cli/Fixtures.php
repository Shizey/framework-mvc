<?php

namespace Framework\Cli;

use Dotenv\Dotenv;
use Framework\Database;
use Framework\Utils\Colors;

class Fixtures
{
    public static function load(): void
    {
        Colors::formatPrintLn(['yellow', 'bold'], '📜 Loading fixtures...');

        Dotenv::createImmutable(__DIR__.'/../..')->load();
        Colors::formatPrintLn(['green'], '✅ Environment variables loaded');

        $db = Database::getInstance();
        Colors::formatPrintLn(['green'], '✅ Database connection established');

        $fixtures = scandir(__DIR__.'/../../src/Fixtures');

        if (!$fixtures) {
            Colors::formatPrintLn(['red'], '❌ No fixtures found');

            return;
        }

        foreach ($fixtures as $fixture) {
            if (is_file(__DIR__.'/../../src/Fixtures/'.$fixture) && str_ends_with($fixture, '.php')) {
                Colors::formatPrintLn(['green'], '-------------------------');
                Colors::formatPrintLn(['yellow'], '📁 Loading '.$fixture.'...');

                $fixture = 'Fixtures\\'.str_replace('.php', '', $fixture);

                $classImplements = class_implements($fixture);

                if (!$classImplements) {
                    Colors::formatPrintLn(['red'], '❌ '.$fixture.' does not implement any interface');
                    continue;
                }

                if (!in_array('Framework\Interfaces\FixturesInterface', $classImplements)) {
                    Colors::formatPrintLn(['red'], '❌ '.$fixture.' does not implement FixturesInterface');
                    continue;
                }
                // Check if table exists
                if ($fixture::TABLE === '' || !$db->tableExists($fixture::TABLE)) {
                    Colors::formatPrintLn(['red'], '❌ the table "'.$fixture::TABLE.'" does not exist');
                    continue;
                }

                // Clear table
                Colors::formatPrintLn(['yellow'], '🧹 Clearing '.$fixture::TABLE.' table...');
                $db->query('DELETE FROM '.$fixture::TABLE);

                // Load fixtures
                Colors::formatPrintLn(['yellow'], '⏳ Loading '.$fixture::TABLE.' table...');

                /**
                 * @var Fixtures $fixtureClass
                 */
                $fixtureClass = new $fixture();
                $fixtureClass->load();

                Colors::formatPrintLn(['green'], '✅ '.$fixture::TABLE.' table loaded');
            }
        }

        Colors::formatPrintLn(['green'], '-------------------------');
        Colors::formatPrintLn(['green', 'bold'], '✅ All fixtures loaded');
    }
}
