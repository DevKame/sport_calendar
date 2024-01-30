<?php



////////////////////////////////////    EVENT QUERIES  [start]  //////////////////////////////////////
/** ASSIGNS A TRAINER TO AN EVENT
 *  returns
 * {true | Exeption->getMessage()} => Bool | String */
function assignTrainer($eid, $tid) {
    $con = connect();
    $query =
    "UPDATE sport_cal_events
    SET trainer = ?
    WHERE id = ?";
    try {
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "si", $tid, $eid);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_free_result($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($con);
        return true;
    }
    catch(Exeption $e) {
        return $e.getMessage();
    }
}
/** REMOVES A STUDENT FROM AN EVENT
 *  returns
 * {true | Exeption->getMessage()} => Bool | String */
function removeStudent($id, $json) {
    $con = connect();
    $query =
    "UPDATE sport_cal_events
    SET students = ?,
    booked = booked - 1
    WHERE id = ?";
    try {
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "si", $json, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_free_result($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($con);
        return true;
    }
    catch(Exeption $e) {
        return $e.getMessage();
    }
}
/** ADDS A STUDENT TO AN EVENT
 *  returns
 * {true | Exeption->getMessage()} => Bool | String */
function addStudent($id, $json) {
    $con = connect();
    $query =
    "UPDATE sport_cal_events
    SET students = ?,
    booked = booked + 1
    WHERE id = ?";
    try {
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "si", $json, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_free_result($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($con);
        return true;
    }
    catch(Exeption $e) {
        return $e.getMessage();
    }
}
/** FETCHES AN EVENT WITH ONLY ITS STUDENTS
 *  returns
 * {$event | Exeption->getMessage()} => Array | String */
function fetchEventStudentsByID($id) {
    $con = connect();
    $event;
    $query =
    "SELECT students
    FROM sport_cal_events
    WHERE id = ?";
    $stmt = mysqli_prepare($con, $query);
    try {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_execute($stmt);
        mysqli_stmt_bind_result($stmt, $students);
        mysqli_stmt_store_result($stmt);
        while(mysqli_stmt_fetch($stmt)) {
            $event =
            [
                "students" => $students,
            ];
        }
        mysqli_stmt_free_result($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($con);
        return $event;
    }
    catch(Exeption $e) {
        mysqli_close($con);
        return $e->getMessage();
    }

}
/** FETCHES ALL EVENTS FROM A SPECIFIC DAY */
function fetchEventsForWeek($data) {
    $fetchedTermine = [];
            $con = connect();
            $fetchedEventsForDate =  [];
            $query =
            "SELECT id,
            name,
            day,
            month,
            year,
            hour,
            minute,
            max,
            trainer,
            info,
            groups,
            booked,
            old,
            students
            FROM sport_cal_events
            WHERE day = ?
            AND month = ?
            AND year = ?";

            $requestDay = (string)$data[0];
            if(strlen($requestDay) === 1)
            {
                $requestDay = "0$requestDay";
            }
            $requestMonth = (string)$data[1];
            if(strlen($requestMonth) === 1)
            {
                $requestMonth = "0$requestMonth";
            }
            $requestYear = (string)$data[2];

            $stmt = mysqli_prepare($con, $query);
            mysqli_stmt_bind_param($stmt, "sss", $requestDay, $requestMonth, $requestYear);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            mysqli_stmt_bind_result($stmt, $id, $name, $day, $month, $year, $hour, $minute, $max, $trainer, $info, $groups, $booked, $old, $students);
            while(mysqli_stmt_fetch($stmt))
            {
                $ev = [];
                $ev["id"] = $id;
                $ev["name"] = $name;
                $ev["day"] = $day;
                $ev["month"] = $month;
                $ev["year"] = $year;
                $ev["hour"] = $hour;
                $ev["minute"] = $minute;
                $ev["max"] = $max;
                $ev["trainer"] = $trainer;
                $ev["info"] = $info;
                $ev["groups"] = $groups;
                $ev["booked"] = $booked;
                $ev["old"] = $old;
                $ev["students"] = $students;
                $fetchedEventsForDate[] = $ev;
            }
            mysqli_stmt_free_result($stmt);
            mysqli_stmt_close($stmt);
            mysqli_close($con);
            return $fetchedEventsForDate;
}
/** ASSINGES THE TRAINER-ID $id TO THE EVENT WITH THE ID $eid
 *  returns
 * {$affectedRows | Exeption->getMessage()} => int | String */
function assignTrainerIDToEvent($id, $eid) {
    $con = connect();
    $query =
    "UPDATE sport_cal_events
    SET trainer = ?
    WHERE id = ?";
    try {
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "ii", $id, $eid);
        mysqli_stmt_execute($stmt);
        $affectedRows = mysqli_stmt_affected_rows($stmt);
        mysqli_stmt_free_result($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($con);
        return $affectedRows;
    }
    catch(Exeption $e) {
        return $e.getMessage();
    }
}
/** DELETES AN EVENT BASED ON ITS ID
 *  returns
 * {true | Exeption->getMessage()} => Bool | String */
function deleteEvent($id) {
    $con = connect();
    $query =
    "DELETE FROM sport_cal_events
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
/** CHANGES AN EVENT
 *  returns
 * {true | Exeption->getMessage()} => Bool | String */
function editEvent($id, $name, $fulldate, $fulltime, $year, $month, $day, $hour, $minute, $max, $trainer, $info, $groups) {
    $con = connect();
    $query =
    "UPDATE sport_cal_events
    SET name = ?,
    fulldate = ?,
    fulltime = ?,
    year = ?,
    month = ?,
    day = ?,
    hour = ?,
    minute = ?,
    max = ?,
    trainer = ?,
    info = ?,
    groups = ?
    WHERE id = ?";
    try {
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "ssssssssisssi", $name, $fulldate, $fulltime, $year, $month, $day, $hour, $minute, $max, $trainer, $info, $groups, $id);
        mysqli_stmt_execute($stmt);
        $affectedRows = mysqli_stmt_affected_rows($stmt);
        mysqli_stmt_free_result($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($con);
        return $affectedRows;
    }
    catch(Exeption $e) {
        return $e.getMessage();
    }
}
/** FETCHES ALL EXISTENT EVENTS THAT HAVE SAME DATE AND TIME TO CHECK FOR DOUBLES
 *  IGNORES THE EVENT WITH THE ID $id
 *  returns
 * {$totalEvents | Exeption->getMessage()} => int | String */
function getDoubleEventsExeptOwn($fd, $ft, $id) {
    $con = connect();
    $query =
    "SELECT *
    FROM sport_cal_events
    WHERE fulldate = ?
    AND fulltime = ?
    AND NOT id = ?";
    try {
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "ssi", $fd, $ft, $id);
        mysqli_execute($stmt);
        mysqli_stmt_store_result($stmt);
        $totalEvents = mysqli_stmt_num_rows($stmt);
        mysqli_stmt_free_result($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($con);
        return $totalEvents;
    }
    catch(Exeption $e) {
        mysqli_close($con);
        return $e->getMessage();
    }

}
/** SETS AN EVENT´S TRAINER VALUE BACK TO "no-trainer" FOR EVERY EVENT WHOSE TRAINER
 *  HAS ID OF $id
 *  returns
 * {$affectedRows | Exeption->getMessage()} => int | String */
function resetTrainerFromSingleEvent($id) {
    $con = connect();
    $query =
    "UPDATE sport_cal_events
    SET trainer = 'no-trainer'
    WHERE id = ?";
    try {
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $affectedRows = mysqli_stmt_num_rows($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($con);
        return $affectedRows;
    }
    catch(Exeption $e) {
        mysqli_close($con);
        return $e->getMessage();
    }
}
/** FETCHES ALL EXISTENT EVENTS
 *  returns
 * {$events | Exeption->getMessage()} => Array | String */
function getTotalEventsWithTrainerID($id) {
    $con = connect();
    $events = [];
    $query =
    "SELECT id, trainer
    FROM sport_cal_events
    WHERE trainer = ?";
    $stmt = mysqli_prepare($con, $query);
    try {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_execute($stmt);
        mysqli_stmt_bind_result($stmt, $id, $trainer);
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
                    "trainer" => $trainer,
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
/** CREATES A NEW EVENT
 *  returns
 * {true | Exeption->getMessage()} => Bool | String */
function createEvent($name, $fulldate, $fulltime, $year, $month, $day, $hour, $minute, $max, $trainer, $info, $groups) {
    $con = connect();
    $query =
    "INSERT INTO sport_cal_events
    (name, fulldate, fulltime, year, month, day, hour, minute, max, trainer, info, groups)
    VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";
    try {
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "ssssssssisss", $name, $fulldate, $fulltime, $year, $month, $day, $hour, $minute, $max, $trainer, $info, $groups);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($con);
        return true;
    }
    catch(Exeption $e) {
        return $e.getMessage();
    }
}
/** FETCHES ALL EXISTENT EVENTS THAT HAVE SAME DATE AND TIME TO CHECK FOR DOUBLES
 *  returns
 * {$totalEvents | Exeption->getMessage()} => int | String */
function getDoubleEvents($fd, $ft) {
    $con = connect();
    $query =
    "SELECT *
    FROM sport_cal_events
    WHERE fulldate = ?
    AND fulltime = ?";
    try {
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "ss", $fd, $ft);
        mysqli_execute($stmt);
        mysqli_stmt_store_result($stmt);
        $totalEvents = mysqli_stmt_num_rows($stmt);
        mysqli_stmt_free_result($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($con);
        return $totalEvents;
    }
    catch(Exeption $e) {
        mysqli_close($con);
        return $e->getMessage();
    }

}
/** SETS THE OLD VALUE OF THE EVENT WITH THE ID $id TO 1
 *  returns
 * {$affected | Exeption->getMessage()} => int | String */
function setEventAsOld($id) {
    $con = connect();
    $query =
    "UPDATE sport_cal_events
    SET
    old = 1
    WHERE id = ?";
    try {
        $stmt = mysqli_prepare($con, $query);
    
        mysqli_stmt_bind_param($stmt, "i", $id);
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
/** FETCHES ALL EXISTENT EVENTS
 *  returns
 * {$events | Exeption->getMessage()} => Array | String */
function getAllEvents() {
    $con = connect();
    $events = [];
    $query =
    "SELECT id, name, fulldate, day, month, year, fulltime, hour, minute, max, trainer, info, groups, booked, old, students
    FROM sport_cal_events";
    $stmt = mysqli_prepare($con, $query);
    try {
        mysqli_execute($stmt);
        mysqli_stmt_bind_result($stmt, $id, $name, $fulldate, $day, $month, $year, $fulltime, $hour, $minute, $max, $trainer, $info, $groups, $booked, $old, $students);
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
                    "name" => $name,
                    "fulldate" => $fulldate,
                    "day" => $day,
                    "month" => $month,
                    "year" => $year,
                    "fulltime" => $fulltime,
                    "hour" => $hour,
                    "minute" => $minute,
                    "max" => $max,
                    "trainer" => $trainer,
                    "info" => $info,
                    "groups" => $groups,
                    "booked" => $booked,
                    "old" => $old,
                    "students" => $students,
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
////////////////////////////////////    EVENT QUERIES  [end]  //////////////////////////////////////