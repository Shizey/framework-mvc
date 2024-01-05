#!/usr/bin/php

<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;
use Framework\Utils\Colors;
use Framework\Database;

Colors::formatPrintLn(['yellow', 'bold'], '📜 Loading fixtures...');

(Dotenv::createImmutable(__DIR__ . '/../..'))->load();
Colors::formatPrintLn(['green'], '✅ Environment variables loaded');

$db = Database::getInstance();
Colors::formatPrintLn(['green'], '✅ Database connection established');

$fixtures = scandir(__DIR__ . '/../Fixtures');
foreach ($fixtures as $fixture) {
    if (is_file(__DIR__ . '/../Fixtures/' . $fixture) && str_ends_with($fixture, '.php')) {
        Colors::formatPrintLn(['green'], '-------------------------');
        Colors::formatPrintLn(['yellow'], '📁 Loading ' . $fixture . '...');

        $fixture = 'Fixtures\\' . str_replace('.php', '', $fixture);

        // Check if fixtures implements FixturesInterface
        if (!in_array('Framework\Interfaces\FixturesInterface', class_implements($fixture))) {
            Colors::formatPrintLn(['red'], '❌ ' . $fixture . ' does not implement FixturesInterface');
            continue;
        }
        // Check if table exists
        if ($fixture::TABLE === '' || !$db->tableExists($fixture::TABLE)) {
            Colors::formatPrintLn(['red'], '❌ the table "' . $fixture::TABLE . '" does not exist');
            continue;
        }

        // Clear table
        Colors::formatPrintLn(['yellow'], '🧹 Clearing ' . $fixture::TABLE . ' table...');
        $db->query('DELETE FROM ' . $fixture::TABLE);

        // Load fixtures
        Colors::formatPrintLn(['yellow'], '⏳ Loading ' . $fixture::TABLE . ' table...');
        (new $fixture)->load();

        Colors::formatPrintLn(['green'], '✅ ' . $fixture::TABLE . ' table loaded');
    }
}

Colors::formatPrintLn(['green'], '-------------------------');
Colors::formatPrintLn(['green', 'bold'], '✅ All fixtures loaded');