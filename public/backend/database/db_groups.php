<?php

////////////////////////////////////    GROUP QUERIES  [start]  //////////////////////////////////////
/** RENAMES A GROUP
 *  returns
 * {$affected | Exeption->getMessage()} => int | String */
function editGroup($id, $name) {
    $con = connect();
    $query =
    "UPDATE sport_cal_groups
    SET
    name = ?
    WHERE id = ?";
    try {
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "si", $name, $id);
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
/** VALIDATES AN INTENDED NEW GROUPNAME FOR EDITING THIS GROUP
 *  SKIPS ITS OWN NAME
 *  returns
 * {$matches | Exeption->getMessage()} => Array | String */
function doubleCheckForEdit($id) {
    $con = connect();
    $query =
    "SELECT name FROM sport_cal_groups
    WHERE NOT id = ?";
    try {
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        mysqli_stmt_bind_result($stmt, $fName);
        $matches = [];
        while(mysqli_stmt_fetch($stmt)) {
            $matches[] = $fName;
        }
        mysqli_stmt_free_result($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($con);
        return $matches;
    }
    catch(Exeption $e) {
        mysqli_close($con);
        return $e->getMessage();
    }
}
/** DELETES A GROUP BASED ON ITS ID
 *  returns
 * {true | Exeption->getMessage()} => Bool | String */
function deleteGroup($id) {
    $con = connect();
    $query =
    "DELETE FROM sport_cal_groups
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
/** CREATES A NEW GROUP
 *  returns
 * {true | Exeption->getMessage()} => Bool | String */
function createGroup($name) {
    $con = connect();
    $query =
    "INSERT INTO sport_cal_groups
    (name)
    VALUES (?)";
    try {
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "s", $name);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($con);
        return true;
    }
    catch(Exeption $e) {
        return $e.getMessage();
    }
}
/** FETCHES ALL EXISTENT GROUPS
 *  returns
 * {$groups | Exeption->getMessage()} => Array | String */
function getAllGroups() {
    $con = connect();
    $groups = [];
    $query =
    "SELECT * FROM sport_cal_groups";
    $stmt = mysqli_prepare($con, $query);
    try {
        mysqli_execute($stmt);
        mysqli_stmt_bind_result($stmt, $id, $name);
        mysqli_stmt_store_result($stmt);
        $totalGroups = mysqli_stmt_num_rows($stmt);
        if($totalGroups === 0) {
            mysqli_stmt_free_result($stmt);
            mysqli_stmt_close($stmt);
        }
        else {
            while(mysqli_stmt_fetch($stmt)) {
                $groups[] = ["id" => $id, "name" => $name];
            }
            mysqli_stmt_free_result($stmt);
            mysqli_stmt_close($stmt);
        }
        mysqli_close($con);
        return $groups;
    }
    catch(Exeption $e) {
        mysqli_close($con);
        return $e->getMessage();
    }

}
////////////////////////////////////    GROUP QUERIES  [end]  //////////////////////////////////////
