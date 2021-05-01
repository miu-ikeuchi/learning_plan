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

function insertValidate($title)
{
    $error = [];

    if ($title == '') {
        $error[] = MSG_TITLE_REQUIRED;
    }

    return $error;

}

function insertDueValidate($due_date)
{
    $due_error = [];

    if ($due_date == '') {
        $due_error[] = MSG_DUE_REQUIRED;
    }

    return $due_error;
}

function insertPlan($title)
{
    try {
        $dbh = connectDb();
        $sql = <<<EOM
        INSERT INTO
            plans
            (title)
        VALUES
            (title);
        EOM;
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function insertDue($due_date)
{
    try {
        $dbh = connectDb();
        $sql = <<<EOM
        INSERT INTO
            plans(due_date)
        VALUES
            (due_date);
        EOM;
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}


function createErrMsg($errors)
{
    $err_msg = "<ul class=\"errors\">\n";

    foreach ($errors as $error) {
    $err_msg .= "<li>" . h($error) . "</li>\n";
    }

    $err_msg .= "</ul>\n";

    return $err_msg;
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