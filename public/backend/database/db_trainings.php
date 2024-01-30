<?php

////////////////////////////////////    TRAININGS QUERIES  [start]  //////////////////////////////////////
/** CHANGES A TRAINING
 *  returns
 * {$affected | Exeption->getMessage()} => int | String */
function editTraining($id, $name, $groups) {
    $con = connect();
    $query =
    "UPDATE sport_cal_trainings
    SET
    name = ?,
    groups = ?
    WHERE id = ?";
    try {
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "ssi", $name, $groups, $id);
        mysqli_stmt_execute($stmt);
        $affected = mysqli_stmt_affected_rows($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($con);
        return $affected;
    }
    catch(Exeption $e) {
        mysqli_close($con);
        return $e->getMessage();
    }
}
/** CHECKS IF THERE IS A DOUBLICAT NAME DESPITE THE ONE CONNECTED TO THE PROVIDED ID
 *  returns
 * {$totalDoubles | Exeption->getMessage()} => int | String */
function getTrainingNameExeptOwn($name, $id) {
    $con = connect();
    $query =
    "SELECT name
    FROM sport_cal_trainings
    WHERE name = ?
    AND NOT id = ?";
    $stmt = mysqli_prepare($con, $query);
    try {
        mysqli_stmt_bind_param($stmt, "si", $name, $id);
        mysqli_execute($stmt);
        mysqli_stmt_store_result($stmt);
        $totalDoubles = mysqli_stmt_num_rows($stmt);
        mysqli_stmt_free_result($stmt);
        mysqli_stmt_close($stmt);
        
        mysqli_close($con);
        return $totalDoubles;
    }
    catch(Exeption $e) {
        mysqli_close($con);
        return $e->getMessage();
    }

}
/** DELETES A TRAINING USING ITS ID
 *  returns
 * {true | Exeption->getMessage()} => Bool | String */
function deleteTraining($id) {
    $con = connect();
    $query =
    "DELETE FROM sport_cal_trainings
    WHERE id = ?";
    try {
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $affected = mysqli_stmt_affected_rows($stmt);
        mysqli_stmt_free_result($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($con);
        if($affected === 0)
        {
            return false;
        }
        else {
            return true;
        }
    }
    catch(Exeption $e) {
        mysqli_close($con);
        return $e->getMessage();
    }
}
/** CREATES A NEW TRAINING
 *  returns
 * {true | Exeption->getMessage()} => Bool | String */
function createTraining($name, $groups) {
    $con = connect();
    $query =
    "INSERT INTO sport_cal_trainings
    (name, groups)
    VALUES (?,?)";
    try {
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "ss", $name, $groups);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($con);
        return true;
    }
    catch(Exeption $e) {
        return $e.getMessage();
    }
}
/** FETCHES THE NAMES OF POSSIBLE DUBLICATES REGARDING TRAINING NAMES
 *  returns
 * {$totalEmails | Exeption->getMessage()} => int | String */
function getAllTrainingNames($name) {
    $con = connect();
    $query =
    "SELECT name
    FROM sport_cal_trainings
    WHERE name = ?";
    $stmt = mysqli_prepare($con, $query);
    try {
        mysqli_stmt_bind_param($stmt, "s", $name);
        mysqli_execute($stmt);
        mysqli_stmt_store_result($stmt);
        $totalNames = mysqli_stmt_num_rows($stmt);
        mysqli_stmt_free_result($stmt);
        mysqli_stmt_close($stmt);
        
        mysqli_close($con);
        return $totalNames;
    }
    catch(Exeption $e) {
        mysqli_close($con);
        return $e->getMessage();
    }

}
/** FETCHES ALL EXISTENT TRAININGS
 *  returns
 * {$trainings | Exeption->getMessage()} => Array | String */
function getAllTrainings() {
    $con = connect();
    $trainings = [];
    $query =
    "SELECT
    id,
    name,
    groups
    FROM sport_cal_trainings";
    $stmt = mysqli_prepare($con, $query);
    try {
        mysqli_execute($stmt);
        mysqli_stmt_bind_result($stmt, $id, $name, $groups);
        mysqli_stmt_store_result($stmt);
        $totalTrainings = mysqli_stmt_num_rows($stmt);
        if($totalTrainings === 0) {
            mysqli_stmt_free_result($stmt);
            mysqli_stmt_close($stmt);
        }
        else {
            while(mysqli_stmt_fetch($stmt)) {
                $trainings[] =
                [
                    "id" => $id,
                    "name" => $name,
                    "groups" => $groups,
                ];
            }
            mysqli_stmt_free_result($stmt);
            mysqli_stmt_close($stmt);
        }
        mysqli_close($con);
        return $trainings;
    }
    catch(Exeption $e) {
        mysqli_close($con);
        return $e->getMessage();
    }

}
////////////////////////////////////    TRAININGS QUERIES  [end]  //////////////////////////////////////
