<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
//TODO: change session_name to KDNAPCKIE for production
session_name("KDNAPCKIE-local");
session_start();

$res = [];
$res["success"] = false;
//################################################# CORS HEADERS:
header("Access-Control-Allow-Origin: http://localhost:8080");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
//################################################# GENERAL HEADERS:
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json");

require("../database/db.php");

//################################################# HANDLES GET REQUESTS:
// RETURNS Boolean INDICATING IF A USER IS LOGGED IN
if($_SERVER["REQUEST_METHOD"] === "GET")
{
    $events = getAllEvents();
    if(is_string($events)) {
        $res["reason"] = "connection-problems";
    }
    else {
        $failedUpdates = 0;
        foreach($events as $event)
        {
            if(isEventOld($event))
            {
                $turnToOldResult = setEventAsOld($event["id"]);
                if(is_string($turnToOldResult))
                {
                    $failedUpdates++;
                    break;
                } else {
                    $event["old"] = 1;
                }
            }
        }
        if($failedUpdates === 0)
        {
            $res["success"] = true;
            $res["events"] = $events;
        } else {
            $res["reason"] = "connection-problems";
        }
    }
}
//################################################# HANDLES POST REQUESTS
//################################################# DEPENDANT ON ITS TASK VALUE:
else if($_SERVER["REQUEST_METHOD"] === "POST")
{
    $req = json_decode(file_get_contents("php://input"));
    switch($req->task)
    {
        case "assign-trainer-to-event":
            $result = assignTrainerIDToEvent($req->id, $req->eid);
            if(is_string($result))
            {
                $res["reason"] = "connection-problems";
            } else {
                $res["success"] = true;
            }
            break;
        //################### CHANGES DATA OF AN EVENT:
        case "edit-event":
            $affectedRows = editEvent($req->id,$req->name,$req->fulldate,$req->fulltime,$req->year,$req->month,$req->day,$req->hour,$req->minute,$req->max,$req->trainer,$req->info,$req->groups);
            if(is_int($affectedRows))
            {
                $res["success"] = true;
            }
            else {
                $res["reason"] = "connection-problems";
            }
            break;
        //################### VALIDATES DATA USED FOR CHANGING AN EVENT:
        case "validate-event-edit":
            // VALIDATION OF NAME
            $trimmedName = trim($req->name);
            if($trimmedName === "" || strlen($req->name) > 24)
            {
                $res["reason"] = "invalid-name-value";
                break;
            }
            // VALIDATION OF DATETIME
            // DATETIME FORMAT MUST BE:  0000-00-00T00:00
            $datetimeregex = '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/';
            //      - DATETIME: FORMAT IS RIGHT AND DATE IS NOT IN PAST:
            if(!isEventInFuture($req) || preg_match($datetimeregex, $req->datetime) !== 1) {
                $res["reason"] = "invalid-datetime-value";
                break;
            }
            //      - DATETIME: DATE IS NOT ALREDY BOOKED AT THAT DATE:
            $doubles = getDoubleEventsExeptOwn($req->fulldate, $req->fulltime, $req->id);
            if(is_string($doubles))
            {
                $res["reason"] = "connection-problems";
                $res["errorpoint"] = "validate-event-edit: if(is_string(doubles))";
                break;
            } else {
                if($doubles > 0) {
                    $res["reason"] = "found-double";
                    break;
                }
            }
            // VALIDATION OF MAX PARTICIPANTS:
            if(is_string($req->max) || $req->max < 1)
            {
                $res["reason"] = "invalid-max-value";
                break;
            }
            // VALIDATION OF TRAINER:
            $trainerregex = '/^(no-trainer|\d+)$/';
            if(!preg_match($trainerregex, $req->trainer))
            {
                $res["reason"] = "invalid-trainer-value";
                break;
            }
            // VALIDATION OF INFO:
            if(strlen($req->info) > 256)
            {
                $res["reason"] = "invalid-info-value";
                break;
            }
            // VALIDATION OF GROUPS:
            if(strlen($req->groups) > 256)
            {
                $res["reason"] = "groups-too-long";
                break;
            }
            $res["success"] = true;
            break;
        //################### DELETES AN EVENT BASED ON ITS ID:
        case "delete-event":
            $result = deleteEvent($req->id);
            if(is_bool($result)) {
                if($result) {
                    $res["success"] = true;
                } else {
                    $res["reason"] = "connection-problems";
                }
            } else {
                $res["reason"] = "connection-problems";
            }
            break;
        //################### CREATES A NEW EVENT:
        case "create-event":
            if(!is_string(createEvent($req->name,$req->fulldate,$req->fulltime,$req->year,$req->month,$req->day,$req->hour,$req->minute,$req->max,$req->trainer,$req->info,$req->groups)))
            {
                $res["success"] = true;
            } else {
                $res["reason"] = "connection-problems";
            }
            break;
        //################### VALIDATES DATA FOR CREATING A NEW EVENT:
        case "validate-event":
            // VALIDATION OF NAME
            $trimmedName = trim($req->name);
            if($trimmedName === "" || strlen($req->name) > 24)
            {
                $res["reason"] = "invalid-name-value";
                break;
            }
            // VALIDATION OF DATETIME
            // DATETIME FORMAT MUST BE:  0000-00-00T00:00
            $datetimeregex = '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/';
            //      - DATETIME: FORMAT IS RIGHT AND DATE IS NOT IN PAST:
            if(!isEventInFuture($req) || preg_match($datetimeregex, $req->datetime) !== 1) {
                $res["reason"] = "invalid-datetime-value";
                break;
            }
            //      - DATETIME: DATE IS NOT ALREDY BOOKED AT THAT DATE:
            $doubles = getDoubleEvents($req->fulldate, $req->fulltime);
            if(is_string($doubles))
            {
                $res["reason"] = "connection-problems";
                break;
            } else {
                if($doubles > 0) {
                    $res["reason"] = "found-double";
                    break;
                }
            }
            // VALIDATION OF MAX PARTICIPANTS:
            if(is_string($req->max) || $req->max < 1)
            {
                $res["reason"] = "invalid-max-value";
                break;
            }
            // VALIDATION OF TRAINER:
            $trainerregex = '/^(no-trainer|\d+)$/';
            if(!preg_match($trainerregex, $req->trainer))
            {
                $res["reason"] = "invalid-trainer-value";
                break;
            }
            // VALIDATION OF INFO:
            if(strlen($req->info) > 256)
            {
                $res["reason"] = "invalid-info-value";
                break;
            }
            // VALIDATION OF GROUPS:
            if(strlen($req->groups) > 256)
            {
                $res["reason"] = "groups-too-long";
                break;
            }
            $res["success"] = true;
            break;
    }
}

function isEventInFuture($t) {
    $chosenEvent = mktime(
                            (int)$t->hour,
                            (int)$t->minute,
                            0,
                            (int)$t->month,
                            (int)$t->day,
                            (int)$t->year,);
    $currentTime = time();
    $result = $chosenEvent > $currentTime ?
    true : false;
    return $result;
}
// RETURNS Bool WETHER DATETIME VALUES ARE OLDER THAN CURRENT TIME:
function isEventOld($ev) {
    $now            = time();
    $event_stamp   = mktime((int)$ev["hour"], (int)$ev["minute"], 0, (int)$ev["month"], (int)$ev["day"], (int)$ev["year"]);
    $result = $event_stamp < $now ?
    true :
    false;
    return $result;
}




$res = json_encode($res);
echo $res;