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


//################################################# HANDLES GET REQUESTS:
// RETURNS Boolean INDICATING IF A USER IS LOGGED IN
if($_SERVER["REQUEST_METHOD"] === "GET")
{
    if(isset($_SESSION["kame-sportcal-logged-user"])) {
        $res["logged_user_existent"] = true;
    } else {
        $res["logged_user_existent"] = false;
    }
    unset($res["success"]);
}
//################################################# HANDLES POST REQUESTS
//################################################# DEPENDANT ON ITS TASK VALUE:
else if($_SERVER["REQUEST_METHOD"] === "POST")
{
    $req = json_decode(file_get_contents("php://input"));
    require("../database/general.php");
    switch($req->task)
    {
        //############# FETCHES USER ID OUT OF $_SESSION["kame-sportcal-logged-user"]:
        case "try-logout":
            session_unset();
            session_destroy();
            break;
        //############# FETCHES USER ID OUT OF $_SESSION["kame-sportcal-logged-user"]:
        case "get-userid-from-session":
            $res["session_id"] = $_SESSION["kame-sportcal-logged-user"];
            $res["success"] = true;
            break;
        //######################################## RETURNS USERDATA FROM USER ID:
        case "get-userdata-from-id":
            $userData = getUserDataFromID($req->id);
            if(is_string($userData)) {
                $res["reason"] = "connection-problems";
            }
            else {
                $res["success"] = true;
                $res["logged_user"] = $userData;
            }
            break;
        //################################################# USER TRIES TO LOGIN:
        case "try-login":
            $emailMatches = doesEmailExist($req->email);
            if(is_int($emailMatches))
            {
                if($emailMatches === 0) {
                    $res["reason"] = "email-doesnt-exist";
                }
                else {
                    $passwordCorrect = isPasswordCorrect($req->password, $req->email);
                    if(is_bool($passwordCorrect)) {
                        if($passwordCorrect) {
                            $logged_user_id = getUserIDFromEmail($req->email);
                            if(is_int($logged_user_id)) {
                                $res["success"] = true;
                                $res["id_of_logged_user"] = $logged_user_id;
                                $_SESSION["kame-sportcal-logged-user"] = $logged_user_id;
                            } else {
                                session_destroy();
                                $res["reason"] = "connection-problems";
                            }
                        } else {
                            $res["reason"] = "wrong-pw";
                        }
                    } else {
                        $res["reason"] = "connection-problems";
                    }
                }
            }
            else {
                $res["reason"] = "connection-problems";
            }
            break;
    }
}




$res = json_encode($res);
echo $res;