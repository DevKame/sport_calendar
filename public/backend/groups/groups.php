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
    $groups = getAllGroups();
    if(is_string($groups)) {
        $res["reason"] = "connection-problems";
    }
    else {
        $res["success"] = true;
        $res["groups"] = $groups;
    }
}
//################################################# HANDLES POST REQUESTS
//################################################# DEPENDANT ON ITS TASK VALUE:
else if($_SERVER["REQUEST_METHOD"] === "POST")
{
    $req = json_decode(file_get_contents("php://input"));
    switch($req->task)
    {
        //################### VALIDATES AN INTENDED GROUP NAME:
        case "create-group":
            if(!is_string(createGroup($req->name)))
            {
                $res["success"] = true;
            } else {
                $res["reason"] = "connection-problems";
            }
            break;
        //################### VALIDATES AN INTENDED GROUP NAME:
        case "validate-group":
            // FIRST CHECK IF THE VALUE IS VALID:
            $trimmedName = trim($req->name);
            if($trimmedName === "")
            {
                $res["reason"] = "invalid-value";
                break;
            }
            // FIRST CHECK IF THE VALUE IS LONGER THAN 25 CHARACTERS:
            if(strlen($req->name) > 25)
            {
                $res["reason"] = "too-long";
                break;
            }
            // FIRST CHECK IF THE VALUE ALREADY EXISTS:
            $allGroups = getAllGroups();
            $doubles = 0;
            foreach($allGroups as $group) {
                if(strtolower($group["name"]) === strtolower($req->name))
                {
                    $doubles++;
                    break;
                }
            }
            if($doubles > 0) {
                $res["reason"] = "found-double";
            } else {
                $res["success"] = true;
            }
            break;
    }
}




$res = json_encode($res);
echo $res;