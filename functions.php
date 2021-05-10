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

function insertValidate($title, $due_date)
{
    $errors = [];

    if ($title == '') {
        $errors[] = MSG_TITLE_REQUIRED;
    }
    if ($due_date == '') {
        $errors[] = MSG_DUE_REQUIRED;
    }

    return $errors;

}

function insertLearningPlan($title, $due_date)
{
    try {
        $dbh = connectDb();
        $sql = <<<EOM
        INSERT INTO
            plans
            (title, due_date)
        VALUES
            (:title, :due_date);
        EOM;
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':due_date', $due_date, PDO::PARAM_STR);
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

function updateValidate($title, $due_date, $plan)
{
    $errors = [];

    if ($title == '') {
        $errors[] = MSG_TITLE_REQUIRED;
    }
    if ($due_date == '') {
        $errors[] = MSG_DUE_REQUIRED;
    }
    if ($title == $plan['title'] && $due_date == $plan['due_date']) {
        $errors[] = MSG_UPDATE_REQUIRED;
    }

    return $errors;
}

function addCompletionDate($id)
{
    $dbh = connectDb();
    $sql = <<<EOM
    UPDATE
        plans
    SET
        completion_date = CURRENT_DATE
    WHERE
        id = :id
    EOM;
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
}

function updatePlan($id, $title, $due_date)
{
    $dbh = connectDb();

    $sql = <<<EOM
    UPDATE
        plans
    SET
        title = :title,
        due_date = :due_date
    WHERE
        id = :id
    EOM;

    $stmt = $dbh->prepare($sql);

    $stmt->bindParam(':title',$title,PDO::PARAM_STR);
    $stmt->bindParam(':due_date',$due_date,PDO::PARAM_STR);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    $stmt->execute();
}

function deletePlan($id)
{ 
    $dbh = connectDb();

    $sql = <<<EOM
    DELETE FROM
        plans
    WHERE
        id = :id
    EOM;

    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
}

function deleteCompletionDate($id)
{
    $dbh = connectDb();
    $sql = <<<EOM
    UPDATE
        plans
    SET
        completion_date = NULL
    WHERE
        id = :id
    EOM;
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
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

function findById($id)
{
    $dbh = connectDb();

    $sql = <<<EMO
    SELECT
        *
    FROM
        plans
    WHERE
        id = :id;
    EMO;

    $stmt = $dbh->prepare($sql);

    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}