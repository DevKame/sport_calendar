<?php



function connect() {
    $host =     "localhost";
    $user =     "Kamedin";
    $pw =       "12345";
    $db =       "kame_apps";
    return mysqli_connect($host, $user, $pw, $db);
}
/** UPDATES THE GROUPS ENTRY OF AN EVENT BASED ON ITS ID WITH
 *  A JSON STRING CONTAINING THE NEW GROUP STRING
 *  returns
 *  {true/false | Exeption->getMessage()}     => Bool | String
*/
function updateEventGroups($id, $groups) {
    $con = connect();
    $query =
    "UPDATE sport_cal_EVENTS
    SET
    groups = ?
    WHERE id = ?";
    try {
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "si", $groups, $id);
        mysqli_stmt_execute($stmt);
        $affected = mysqli_stmt_affected_rows($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($con);
        return $affected === 0 ? false : true;
    }
    catch(Exeption $e) {
        mysqli_close($con);
        return $e->getMessage();
    }
}
/** UPDATES THE GROUPS ENTRY OF A TRAINING BASED ON ITS ID WITH
 *  A JSON STRING CONTAINING THE NEW GROUP STRING
 *  returns
 *  {true/false | Exeption->getMessage()}     => Bool | String
*/
function updateTrainingGroups($id, $groups) {
    $con = connect();
    $query =
    "UPDATE sport_cal_trainings
    SET
    groups = ?
    WHERE id = ?";
    try {
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "si", $groups, $id);
        mysqli_stmt_execute($stmt);
        $affected = mysqli_stmt_affected_rows($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($con);
        return $affected === 0 ? false : true;
    }
    catch(Exeption $e) {
        mysqli_close($con);
        return $e->getMessage();
    }
}
/** FETCHES THE ID AND GROUPS OF ALL EVENTS:
 *  returns
 * {$groups | Exeption->getMessage()} => Array | String */
function getAllEventGroups() {
    $con = connect();
    $events = [];
    $query =
    "SELECT
    id,
    groups
    FROM sport_cal_events";
    $stmt = mysqli_prepare($con, $query);
    try {
        mysqli_execute($stmt);
        mysqli_stmt_bind_result($stmt, $id, $groups);
        mysqli_stmt_store_result($stmt);
        $totalEvents = mysqli_stmt_num_rows($stmt);
        if($totalEvents === 0) {
            mysqli_stmt_free_result($stmt);
            mysqli_stmt_close($stmt);
        }
        else {
            while(mysqli_stmt_fetch($stmt)) {
                $events[] =
                [
                    "id" => $id,
                    "groups" => $groups,
                ];
            }
            mysqli_stmt_free_result($stmt);
            mysqli_stmt_close($stmt);
        }
        mysqli_close($con);
        return $events;
    }
    catch(Exeption $e) {
        mysqli_close($con);
        return $e->getMessage();
    }

}
/** FETCHES THE ID AND GROUPS OF ALL TRAININGS:
 *  returns
 * {$groups | Exeption->getMessage()} => Array | String */
function getAllTrainingGroups() {
    $con = connect();
    $trainings = [];
    $query =
    "SELECT
    id,
    groups
    FROM sport_cal_trainings";
    $stmt = mysqli_prepare($con, $query);
    try {
        mysqli_execute($stmt);
        mysqli_stmt_bind_result($stmt, $id, $groups);
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
/** UPDATES THE GROUPS ENTRY OF A USER BASED ON HIS ID WITH
 *  A JSON STRING CONTAINING THE NEW GROUP STRING
 *  returns
 *  {true/false | Exeption->getMessage()}     => Bool | String
*/
function updateUserGroups($id, $groups) {
    $con = connect();
    $query =
    "UPDATE sport_cal_user
    SET
    groups = ?
    WHERE id = ?";
    try {
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "si", $groups, $id);
        mysqli_stmt_execute($stmt);
        $affected = mysqli_stmt_affected_rows($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($con);
        return $affected === 0 ? false : true;
    }
    catch(Exeption $e) {
        mysqli_close($con);
        return $e->getMessage();
    }
}
/** FETCHES THE ID AND GROUPS OF ALL USERS:
 *  returns
 * {$groups | Exeption->getMessage()} => Array | String */
function getAllUserGroups() {
    $con = connect();
    $users = [];
    $query =
    "SELECT
    id,
    groups
    FROM sport_cal_user";
    $stmt = mysqli_prepare($con, $query);
    try {
        mysqli_execute($stmt);
        mysqli_stmt_bind_result($stmt, $id, $groups);
        mysqli_stmt_store_result($stmt);
        $totalUsers = mysqli_stmt_num_rows($stmt);
        if($totalUsers === 0) {
            mysqli_stmt_free_result($stmt);
            mysqli_stmt_close($stmt);
        }
        else {
            while(mysqli_stmt_fetch($stmt)) {
                $users[] =
                [
                    "id" => $id,
                    "groups" => $groups,
                ];
            }
            mysqli_stmt_free_result($stmt);
            mysqli_stmt_close($stmt);
        }
        mysqli_close($con);
        return $users;
    }
    catch(Exeption $e) {
        mysqli_close($con);
        return $e->getMessage();
    }

}
/** USES A USER-ID TO FETCH THIS USERS DATA
 *  args:
 *  {$id}                               => int
 *  returns
 * {$userData | Exeption->getMessage()} => assoz. Array | String */
function getUserDataFromID($id) {
    $con = connect();
    $userData = [];
    $query =
    "SELECT * FROM sport_cal_user
    WHERE id = ?";
    try {
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $fID, $fEmail, $fFn, $fLn, $fRole, $fGroups, $fPw);
        mysqli_stmt_store_result($stmt);
        while(mysqli_stmt_fetch($stmt)) {
            $userData["id"] = $fID;
            $userData["email"] = $fEmail;
            $userData["firstname"] = $fFn;
            $userData["lastname"] = $fLn;
            $userData["role"] = $fRole;
            $userData["groups"] = $fGroups;
        }
    
        mysqli_stmt_free_result($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($con);
        return $userData;
    }
    catch(Exeption $e) {
        mysqli_close($con);
        return $e->getMessage();
    }

}
/** FETCHES THE CORRESPONDING ID TO AN EMIAL
 *  args:
 *  {$email}            => String
 *  returns
 *  {$result | $error}  => int | String (message from catchBlock) */
function getUserIDFromEmail($email) {
    $con = connect();
    $id;
    $query =
    "SELECT id
    FROM sport_cal_user
    WHERE email = ?";
    try {
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        mysqli_stmt_bind_result($stmt, $fetchedID);
        while(mysqli_stmt_fetch($stmt)) {
            $id = $fetchedID;
        }
        $result = $fetchedID;
        mysqli_stmt_free_result($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($con);

        return (int)$result;
    }
    catch(Exeption $e) {
        mysqli_close($con);
        $error = $e->getMessage();
        return $e-getMessage();
    }
}
/** CHECKS IF A PASSWORD FITS TO AN EXISTING EMAIL
 *  args:
 *  {$pw}               => String
 *  {$email}            => String
 *  returns
 *  {$result | $error}  => Boolean | String (message from catchBlock) */
function isPasswordCorrect($pw, $email) {
    $con = connect();
    $password;
    $query =
    "SELECT password
    FROM sport_cal_user
    WHERE email = ?";
    try {
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        mysqli_stmt_bind_result($stmt, $fetchedPassword);
        while(mysqli_stmt_fetch($stmt)) {
            $password = $fetchedPassword;
        }
        $result = $pw === $fetchedPassword;
        mysqli_stmt_free_result($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($con);

        return $result;
    }
    catch(Exeption $e) {
        mysqli_close($con);
        $error = $e->getMessage();
        return $e-getMessage();
    }
}
/** CHECKS IF AN EMAIL EXISTS IN THE DATABASE
 *  args:
 *  {$email}            => String
 *  returns
 *  {$result | $error}  => int (0 or 1) | String (message from catchBlock) */
function doesEmailExist($email) {
    $con = connect();
    $result = 0;
    $error = "";
    $query =
    "SELECT email
    FROM sport_cal_user
    WHERE email = ?";

    try {
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        $result = mysqli_stmt_num_rows($stmt);
        mysqli_stmt_free_result($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($con);
    }
    catch(Exeption $e) {
        mysqli_close($con);
        $error = $e->getMessage();
    }

    if($error === "") {
        return $result;
    } else {
        return $error;
    }

}