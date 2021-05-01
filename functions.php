<?php
require_once __DIR__ . '/config.php';

function connectDb()
{
    try {
        return new PDO(
            DSN,
            USER,
            PASSWORD,
            [PDO::ATTR_ERRMODE =>
            PDO::ERRMODE_EXCEPTION]
        );
    } catch (PDOException $e) {
        echo $e->getMessage();
        exit;
    }
}

function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

function findPlanByDate()
{
    $dbh = connectDb();

    $sql = <<<EOM
    SELECT
        *
    FROM 
        plans
    WHERE
        completion_date IS NULL
    ORDER BY
        due_date ASC;
    EOM;

    $stmt = $dbh->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function findPlanByCompDate()
{
    $dbh = connectDb();

    $sql = <<<EOM
    SELECT
        *
    FROM 
        plans
    WHERE
        completion_date IS NOT NULL
    ORDER BY
        due_date ASC;
    EOM;

    $stmt = $dbh->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}