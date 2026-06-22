<?php

function getDatabaseConnection(): PDO
{
    $databasePath = __DIR__ . '/invoice_manager.sqlite';

    $pdo = new PDO('sqlite:' . $databasePath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return $pdo;
}