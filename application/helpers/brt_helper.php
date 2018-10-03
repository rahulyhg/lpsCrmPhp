<?php

/**
 * Author S Brinta<brrinta@gmail.com>
 * Email: brrinta@gmail.com
 * Web: https://brinta.me
 * Do not edit file without permission of author
 * All right reserved by S Brinta<brrinta@gmail.com>
 * Created on : Apr 30, 2018, 3:40:29 PM
 */
function getEncryptedTest($text) {
    $ci = &get_instance();
    return $ci->encryption->encrypt($text);
}

function getdecryptedTest($text) {
    $ci = &get_instance();
    return $ci->encryption->decrypt($text);
}

function getStateColumns($stateShort = FALSE) {
    $ci = &get_instance();
    return $stateShort ? $ci->brt->stateColumns[$stateShort] : $ci->brt->stateColumns;
}

function getstateColumnNumbers($stateShort = FALSE, $column = false) {
    $ci = &get_instance();
    return $stateShort ? ($column ? $ci->brt->stateColumnNumbers[$stateShort][$column] : $ci->brt->stateColumnNumbers[$stateShort]) : $ci->brt->stateColumnNumbers;
}

function getState($state = FALSE) {
    $ci = &get_instance();
    return $state ? $ci->brt->statesList[$state] : $ci->brt->statesList;
}

function getBrmState($state = FALSE) {
    $ci = &get_instance();
    return $state ? $ci->brt->brmColumns[$state] : $ci->brt->brmColumns;
}

function getBRMSortState() {
    $states = [];
    foreach (getBrmState() as $id => $state) {
        array_push($states, $id);
    }
    return $states;
}

function getProDocState($state = FALSE) {
    $ci = &get_instance();
    return $state ? $ci->brt->proDocState[$state] : $ci->brt->proDocState;
}

function getProDocStateColumns($stateShort = FALSE) {
    $ci = &get_instance();
    return $stateShort ? $ci->brt->proDocStateColumns[$stateShort] : $ci->brt->proDocStateColumns;
}

function getProDocStateColumnsShow($stateShort = FALSE, $column = false) {
    $ci = &get_instance();
    if ($column)
        return $stateShort ? $ci->brt->proDocStateShowColumns[$stateShort][$column] : $ci->brt->proDocStateShowColumns[$stateShort];
    return $stateShort ? $ci->brt->proDocStateShowColumns[$stateShort] : $ci->brt->proDocStateShowColumns;
}

function getPurposes($purpose = false) {
    $ci = &get_instance();
    return $purpose ? $ci->brt->purposes[$purpose] : $ci->brt->purposes;
}

/**
 * 
 * @param type $data
 */
function dnd($data) {
    echo "<pre>";
    var_dump($data);
    echo "</pre>";
    echo "<br>";
    echo "<br>";
}

function convertTime($dec) {
    // start by converting to seconds
    $seconds = ($dec * 3600);
    // we're given hours, so let's get those the easy way
    $hours = floor($dec);
    // since we've "calculated" hours, let's remove them from the seconds variable
    $seconds -= $hours * 3600;
    // calculate minutes left
    $minutes = floor($seconds / 60);
    // remove those from seconds as well
    $seconds -= $minutes * 60;
    // return the time formatted HH:MM:SS
    return lz($hours) . ":" . lz($minutes);
}

// lz = leading zero
function lz($num) {
    return (strlen($num) < 2) ? "0{$num}" : $num;
}

/**
 * 
 * @param type $data
 */
function dnp($data) {
    echo "<pre>";
    print_r($data);
    echo "</pre>";
    echo "<br>";
    echo "<br>";
}

/**
 * 
 * @param type $date
 * @param type $format
 * @return type
 */
function changeDateFormat($date, $format = "Y-m-d") {
    return date($format, strtotime(str_replace(",", " ", $date)));
}

function changeDateFormatToLong($date, $format = "d M, Y") {
    return date($format, strtotime(str_replace(",", " ", $date)));
}

/**
 * 120x120 logo
 * @return logo url
 */
function logoSrc($size = 120) {
    return property_url("images/logo.png");
}

function annualCompliance_url($uri = "") {
    return base_url("annualCompliance/" . $uri);
}

function proDoc_url($uri = "") {
    return base_url("proDoc/" . $uri);
}

function curl_url($uri = "") {
    return base_url("curl/" . $uri);
}

/**
 * 
 * @param type $uri
 * @return type
 */
function property_url($uri = "") {
    return base_url("property/" . $uri);
}

/**
 * 
 * @param type $getParameter
 * @return type
 */
function login_url($getParameter = "") {
    return base_url("login" . ($getParameter ? "?" . $getParameter : ""));
}

/**
 * 
 * @param type $getParameter
 * @return type
 */
function signup_url($getParameter = "") {
    return base_url("signup" . ($getParameter ? "?" . $getParameter : ""));
}

/**
 * 
 * @param type $getParameter
 * @return type
 */
function recover_url($getParameter = "") {
    return base_url("recover" . ($getParameter ? "?" . $getParameter : ""));
}

/**
 * 
 * @param type $uri
 * @return type
 */
function home_url($uri = "") {
    return base_url("site/" . $uri);
}

/**
 * 
 * @param type $uri
 * @return type
 */
function dashboard_url($uri = "") {
    return base_url("dashboard/" . $uri);
}

/**
 * 
 * @param type $uri
 * @return type
 */
function deposits_url($uri = "") {
    return base_url("deposits/" . $uri);
}

/**
 * 
 * @param type $uri
 * @return type
 */
function vendors_url($uri = "") {
    return base_url("vendors/" . $uri);
}

function jobs_url($uri = "") {
    return base_url("jobs/" . $uri);
}

/**
 * 
 * @param type $uri
 * @return type
 */
function prospects_url($uri = "") {
    return dashboard_url("prospects/" . $uri);
}

/**
 * 
 * @param type $uri
 * @return type
 */
function user_url($uri = "") {
    return base_url("user/" . $uri);
}

/**
 * 
 * @param type $uri
 * @return type
 */
function timeStation_url($uri = "") {
    return base_url("timeStation/" . $uri);
}

/**
 * 
 * @param type $uri
 * @return type
 */
function settings_url($uri = "") {
    return base_url("settings/" . $uri);
}

/**
 * 
 * @param type $uri
 * @return type
 */
function reports_url($uri = "") {
    return base_url("reports/" . $uri);
}

function currentUserPosition() {
    return $_SESSION["position"];
}

/**
 * 
 * @return boolean
 */
function currentUserIsAdmin() {
    return $_SESSION["user"]->admin;
}

function getCurrentUser() {
    return isset($_SESSION["user"]) ? $_SESSION["user"] : false;
}

function hasPermissionField($field) {
    return isset($_SESSION["permission"][$field]) ? true : false;
}

function hasPermission($text) {
    $permission = false;
    $check = explode("/", $text);
    $permissions = isset($_SESSION["permission"][$check[0]]) ? (array) $_SESSION["permission"][$check[0]] : false;
    if ($permissions) {
        if (isset($permissions[0]->all)) {
            return $permissions[0]->all;
        } else {
            if (sizeof($check) > 1) {
                foreach ($permissions as $p) {
                    // dnp($p);
                    $v = $check[1];
                    // dnd($p->$v);
                    $r = (array) $p;
                    if (key($r) === $v) {
                        $permission = $p->$v;
                    }
                }
            } else {
                $ret = false;
                foreach ((array) $permissions as $p) {
                    $r = (array) $p;
                    $k = key($r);
                    // dnp(key($r));
                    if ($p->$k) {
                        $ret = $p->$k;
                    }
                }
                $permission = $ret;
            }
        }
    }
    return $permission;
}

function getPermissionDetails($path = 0) {
    $ci = &get_instance();
    return $path ? $ci->brt->permissionColumns[$path] : $ci->brt->permissionColumns;
}

/**
 * 
 * @param type $array
 * @param type $value
 * @return type
 */
function removeFromArray($array, $value) {
    if (($key = array_search($value, $array)) !== false) {
        unset($array[$key]);
    }
    return $array;
}

/* * ************************************ac*********************************** */

function getACStateColumns($stateShort = FALSE) {
    $ci = &get_instance();
    return $stateShort ? $ci->brt->ACstateColumns[$stateShort] : $ci->brt->ACstateColumns;
}

function getACState($state = FALSE) {
    $ci = &get_instance();
    return $state ? $ci->brt->ACstatesList[$state] : $ci->brt->ACstatesList;
}

function getACBrmState($state = FALSE) {
    $ci = &get_instance();
    return $state ? $ci->brt->ACbrmColumns[$state] : $ci->brt->ACbrmColumns;
}

function createColumnsArray($end_column, $first_letters = '') {
    $columns = array();
    $length = strlen($end_column);
    $letters = range('A', 'Z');

    // Iterate over 26 letters.
    foreach ($letters as $letter) {
        // Paste the $first_letters before the next.
        $column = $first_letters . $letter;

        // Add the column to the final array.
        $columns[] = $column;

        // If it was the end column that was added, return the columns.
        if ($column == $end_column)
            return $columns;
    }

    // Add the column children.
    foreach ($columns as $column) {
        // Don't itterate if the $end_column was already set in a previous itteration.
        // Stop iterating if you've reached the maximum character length.
        if (!in_array($end_column, $columns) && strlen($column) < $length) {
            $new_columns = createColumnsArray($end_column, $column);
            // Merge the new columns which were created with the final columns array.
            $columns = array_merge($columns, $new_columns);
        }
    }

    return $columns;
}

function convertDate($cuDate, $newFormat = "d-m-Y", $currentFormat = "m/d/Y") {
    $date = DateTime::createFromFormat($currentFormat, $cuDate);
    $newDate = $date->format($newFormat);
    return $newDate;
}

function clean($str) {
    $str = html_entity_decode($str);
    return preg_replace("/\s|&nbsp;/", '', $str);
}

function sendMail($to, $subject, $message, $from="admin@laborposterservices.com") {
    $ci = &get_instance();
    $ci->load->library('email');
    //$ci->email->set_newline("<br>");
    $ci->email->from($from); // change it to yours
    $ci->email->to($to); // change it to yours
    $ci->email->subject($subject);
    $ci->email->message($message);
    if ($ci->email->send()) {
        return true;
    } else {
        return false;
       // show_error($ci->email->print_debugger());
    }
}
