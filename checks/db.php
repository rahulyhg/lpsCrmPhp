<?PHP
date_default_timezone_set("America/New_York");
session_start();
$_PG = $_REQUEST;
$action = (isset($_PG['action'])) ? (stripslashes($_PG['action'])) : 'main';
if (isset($_GET["ids"]))
    $_SESSION["ids"] = $_GET["ids"];
//////////////////////////////BD config///////////////////////////////

$mysql_user = 'labor_admin';
$mysql_pass = 'Champ@21';
$mysql_db = 'labor_crm';

$conn = new mysqli("localhost", $mysql_user, $mysql_pass, $mysql_db);
if ($conn->connect_error)
    die("Connection failed: " . $conn->connect_error);

function loginCheck() {
    //die("S=".$_SESSION["logged_in"]["id"]);
    if (!isset($_SESSION["ids"])) {
        header('Location: ../index.php');
        exit();
    }
    return true;
}

?> 