<?PHP
date_default_timezone_set("America/New_York");
ini_set("session.cookie_lifetime", "5400");
ini_set("session.gc_maxlifetime", "5400");
session_set_cookie_params(5400);
//session_start();
set_time_limit(1800);
$_PG = $_REQUEST;
$action = (isset($_PG['action'])) ? (stripslashes($_PG['action'])) : 'main';
include "sms_gateway/smsGateway.php";
$mysql_user = 'labor_admin';
$mysql_pass = 'Champ@21';
$mysql_db = 'labor_crm';
//////////////////////////////DB config///////////////////////////////
$conn = new mysqli("localhost", $mysql_user, $mysql_pass, $mysql_db);
if ($conn->connect_error)
    die("Connection failed: " . $conn->connect_error);

$us_state_abbrevs_names = array(
    'AL' => 'ALABAMA',
    'AK' => 'ALASKA',
    'AS' => 'AMERICAN SAMOA',
    'AZ' => 'ARIZONA',
    'AR' => 'ARKANSAS',
    'CA' => 'CALIFORNIA',
    'CO' => 'COLORADO',
    'CT' => 'CONNECTICUT',
    'DE' => 'DELAWARE',
    'DC' => 'DISTRICT OF COLUMBIA',
    'FM' => 'FEDERATED STATES OF MICRONESIA',
    'FL' => 'FLORIDA',
    'GA' => 'GEORGIA',
    'GU' => 'GUAM GU',
    'HI' => 'HAWAII',
    'ID' => 'IDAHO',
    'IL' => 'ILLINOIS',
    'IN' => 'INDIANA',
    'IA' => 'IOWA',
    'KS' => 'KANSAS',
    'KY' => 'KENTUCKY',
    'LA' => 'LOUISIANA',
    'ME' => 'MAINE',
    'MH' => 'MARSHALL ISLANDS',
    'MD' => 'MARYLAND',
    'MA' => 'MASSACHUSETTS',
    'MI' => 'MICHIGAN',
    'MN' => 'MINNESOTA',
    'MS' => 'MISSISSIPPI',
    'MO' => 'MISSOURI',
    'MT' => 'MONTANA',
    'NE' => 'NEBRASKA',
    'NV' => 'NEVADA',
    'NH' => 'NEW HAMPSHIRE',
    'NJ' => 'NEW JERSEY',
    'NM' => 'NEW MEXICO',
    'NY' => 'NEW YORK',
    'NC' => 'NORTH CAROLINA',
    'ND' => 'NORTH DAKOTA',
    'MP' => 'NORTHERN MARIANA ISLANDS',
    'OH' => 'OHIO',
    'OK' => 'OKLAHOMA',
    'OR' => 'OREGON',
    'PW' => 'PALAU',
    'PA' => 'PENNSYLVANIA',
    'PR' => 'PUERTO RICO',
    'RI' => 'RHODE ISLAND',
    'SC' => 'SOUTH CAROLINA',
    'SD' => 'SOUTH DAKOTA',
    'TN' => 'TENNESSEE',
    'TX' => 'TEXAS',
    'UT' => 'UTAH',
    'VT' => 'VERMONT',
    'VI' => 'VIRGIN ISLANDS',
    'VA' => 'VIRGINIA',
    'WA' => 'WASHINGTON',
    'WV' => 'WEST VIRGINIA',
    'WI' => 'WISCONSIN',
    'WY' => 'WYOMING',
    'AE' => 'ARMED FORCES AFRICA \ CANADA \ EUROPE \ MIDDLE EAST',
    'AA' => 'ARMED FORCES AMERICA (EXCEPT CANADA)',
    'AP' => 'ARMED FORCES PACIFIC'
);


function mysql_insert($tblname, $data) {
    global $conn;
    $sql = "INSERT INTO " . $tblname . " " . $data;

    if ($conn->query($sql) === TRUE) {
        echo $tblname . " new record created successfully<br>";
        log_register($_SESSION["user"], "INSERT", $sql, 1);
    } else
        echo "Error: " . $sql . "<br>" . $conn->error;

    //$conn->close();
}

function mysql_update($tblname, $data) {
    global $conn;
    $sql = "UPDATE " . $tblname . " SET " . $data;
    //die($sql);
    if ($conn->query($sql) === TRUE) {
        echo $tblname . " record updated successfully<br>";
        log_register($_SESSION["user"], "UPDATE", $sql, 1);
    } else
        echo "Error: " . $sql . "<br>" . $conn->error;
}

function BD2PHP($Aux1) {
    $Aux = $Aux1 != "" ? substr($Aux1, 5, 2) . "/" . substr($Aux1, 8, 2) . "/" . substr($Aux1, 0, 4) : "";
    return $Aux;
}

function BD2PHP_time($Aux1) {
    $Aux = $Aux1 != "" ? substr($Aux1, 5, 2) . "/" . substr($Aux1, 8, 2) . "/" . substr($Aux1, 0, 4) . " " . substr($Aux1, 11, 5) : "";
    return $Aux;
}

function BD2PHP_time_ap($Aux1) {
    $Aux = $Aux1 != "" ? substr($Aux1, 5, 2) . "/" . substr($Aux1, 8, 2) . "/" . substr($Aux1, 0, 4) . " " . substr($Aux1, 11, 8) : "";
    return $Aux;
}

function PHP2BD($Aux1) {
    $Aux = $Aux1 != "" ? substr($Aux1, 6, 4) . "-" . substr($Aux1, 0, 2) . "-" . substr($Aux1, 3, 2) : "";
    return $Aux;
}

function PHP2BD_time($Aux1) {
    $Aux = $Aux1 != "" ? substr($Aux1, 6, 4) . "-" . substr($Aux1, 0, 2) . "-" . substr($Aux1, 3, 2) . " " . substr($Aux1, 11, 5) : "";
    return $Aux;
}

function PHP2BD_time_ap($Aux1) {
    //die($Aux1);
    $Aux = $Aux1 != "" ? substr($Aux1, 6, 4) . "-" . substr($Aux1, 0, 2) . "-" . substr($Aux1, 3, 2) . " " . substr($Aux1, 11, 8) : "";
    //die($Aux);
    return $Aux;
}

function diff_date($datetime1, $datetime2) {
    global $conn;
    $result = $conn->query("SELECT DATEDIFF('$datetime2','$datetime1') AS diff");
    $row = $result->fetch_assoc();
    return $row["diff"];
}

/**
 * easy image resize function
 * @param  $file - file name to resize
 * @param  $string - The image data, as a string
 * @param  $width - new image width
 * @param  $height - new image height
 * @param  $proportional - keep image proportional, default is no
 * @param  $output - name of the new file (include path if needed)
 * @param  $delete_original - if true the original image will be deleted
 * @param  $use_linux_commands - if set to true will use "rm" to delete the image, if false will use PHP unlink
 * @param  $quality - enter 1-100 (100 is best quality) default is 100
 * @param  $grayscale - if true, image will be grayscale (default is false)
 * @return boolean|resource
 */
function smart_resize_image($file, $string = null, $width = 0, $height = 0, $proportional = false, $output = 'file', $delete_original = true, $use_linux_commands = false, $quality = 100, $grayscale = false
) {

    if ($height <= 0 && $width <= 0)
        return false;
    if ($file === null && $string === null)
        return false;

    # Setting defaults and meta
    $info = $file !== null ? getimagesize($file) : getimagesizefromstring($string);
    $image = '';
    $final_width = 0;
    $final_height = 0;
    list($width_old, $height_old) = $info;
    $cropHeight = $cropWidth = 0;

    # Calculating proportionality
    if ($proportional) {
        if ($width == 0)
            $factor = $height / $height_old;
        elseif ($height == 0)
            $factor = $width / $width_old;
        else
            $factor = min($width / $width_old, $height / $height_old);

        $final_width = round($width_old * $factor);
        $final_height = round($height_old * $factor);
    }
    else {
        $final_width = ( $width <= 0 ) ? $width_old : $width;
        $final_height = ( $height <= 0 ) ? $height_old : $height;
        $widthX = $width_old / $width;
        $heightX = $height_old / $height;

        $x = min($widthX, $heightX);
        $cropWidth = ($width_old - $width * $x) / 2;
        $cropHeight = ($height_old - $height * $x) / 2;
    }

    # Loading image to memory according to type
    switch ($info[2]) {
        case IMAGETYPE_JPEG: $file !== null ? $image = imagecreatefromjpeg($file) : $image = imagecreatefromstring($string);
            break;
        case IMAGETYPE_GIF: $file !== null ? $image = imagecreatefromgif($file) : $image = imagecreatefromstring($string);
            break;
        case IMAGETYPE_PNG: $file !== null ? $image = imagecreatefrompng($file) : $image = imagecreatefromstring($string);
            break;
        default: return false;
    }

    # Making the image grayscale, if needed
    if ($grayscale) {
        imagefilter($image, IMG_FILTER_GRAYSCALE);
    }

    # This is the resizing/resampling/transparency-preserving magic
    $image_resized = imagecreatetruecolor($final_width, $final_height);
    if (($info[2] == IMAGETYPE_GIF) || ($info[2] == IMAGETYPE_PNG)) {
        $transparency = imagecolortransparent($image);
        $palletsize = imagecolorstotal($image);

        if ($transparency >= 0 && $transparency < $palletsize) {
            $transparent_color = imagecolorsforindex($image, $transparency);
            $transparency = imagecolorallocate($image_resized, $transparent_color['red'], $transparent_color['green'], $transparent_color['blue']);
            imagefill($image_resized, 0, 0, $transparency);
            imagecolortransparent($image_resized, $transparency);
        } elseif ($info[2] == IMAGETYPE_PNG) {
            imagealphablending($image_resized, false);
            $color = imagecolorallocatealpha($image_resized, 0, 0, 0, 127);
            imagefill($image_resized, 0, 0, $color);
            imagesavealpha($image_resized, true);
        }
    }
    imagecopyresampled($image_resized, $image, 0, 0, $cropWidth, $cropHeight, $final_width, $final_height, $width_old - 2 * $cropWidth, $height_old - 2 * $cropHeight);


    # Taking care of original, if needed
    if ($delete_original) {
        if ($use_linux_commands)
            exec('rm ' . $file);
        else
            @unlink($file);
    }

    # Preparing a method of providing result
    switch (strtolower($output)) {
        case 'browser':
            $mime = image_type_to_mime_type($info[2]);
            header("Content-type: $mime");
            $output = NULL;
            break;
        case 'file':
            $output = $file;
            break;
        case 'return':
            return $image_resized;
            break;
        default:
            break;
    }

    # Writing image according to type to the output destination and image quality
    switch ($info[2]) {
        case IMAGETYPE_GIF: imagegif($image_resized, $output);
            break;
        case IMAGETYPE_JPEG: imagejpeg($image_resized, $output, $quality);
            break;
        case IMAGETYPE_PNG:
            $quality = 9 - (int) ((0.9 * $quality) / 10.0);
            imagepng($image_resized, $output, $quality);
            break;
        default: return false;
    }

    return true;
}

function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if (getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if (getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if (getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if (getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
    else if (getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

function log_register($id_account, $field_id_name, $field_id_value, $table_affected, $field_affected, $query_type, $previous_value = "", $new_value = "", $id_module = 0) {
    global $conn;
    $ip_address = get_client_ip();
    $new_value = str_replace("'", "", $new_value);
    $new_value = str_replace("\"", "", $new_value);
    $dealership_id = $_SESSION["dealership_id"];
    if ($query_type == "UPDATE") {
        for ($k = 0; $k < (count($field_affected) - 1); $k++) {
            $field = $field_affected[$k];
            $sql = "SELECT `" . $field . "` FROM " . $table_affected . " WHERE " . $field_id_name . "=" . $field_id_value;
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $previous_value = $row[$field];
            $new_value2 = $new_value[$k];
            //if($previous_value!=$new_value2)
            //echo "$previous_value!=$new_value2<br>";
            if (@strcmp($previous_value, $new_value2) !== 0) {
                $sql = "INSERT INTO user_logs (id_account, field_id_name, field_id_value, table_affected, field_affected, query_type, previous_value, new_value, ip_address, id_module, date_log) VALUES (" . $id_account . ", \"" . $field_id_name . "\", \"" . $field_id_value . "\", \"" . $table_affected . "\", \"" . $field . "\", \"" . $query_type . "\", \"" . $previous_value . "\", \"" . $new_value2 . "\", \"" . $ip_address . "\", \"" . $id_module . "\", now())";
                $result = @$conn->query($sql);
            }
        }
    }
    if ($query_type == "DELETE") {
        $sql = "SELECT * FROM $table_affected WHERE $field_id_name=$field_id_value";
        $result = $conn->query($sql);
        $i = 0;
        //die($sql);
        $row = $result->fetch_assoc();
        foreach ($row as $key => $value) {
            $previous_value .= $value . ", ";
        }
        $previous_value = trim($previous_value, ', ');
        //die($previous_value);
        $sql = "INSERT INTO user_logs (id_account, field_id_name, field_id_value, table_affected, field_affected, query_type, previous_value, new_value, ip_address, id_module, date_log) VALUES ($id_account, $dealership_id,\"$field_id_name\", \"$field_id_value\", \"$table_affected\", \"$field_affected\", \"$query_type\", \"$previous_value\", \"$new_value\", \"$ip_address\", \"$id_module\", now())";

        $result = @$conn->query($sql);
    }
    if ($query_type == "INSERT") {
        $sql = "INSERT INTO user_logs (id_account, field_id_name, field_id_value, table_affected, field_affected, query_type, previous_value, new_value, ip_address, id_module, date_log) VALUES ($id_account, $dealership_id, \"$field_id_name\", \"$field_id_value\", \"$table_affected\", \"$field_affected\", \"$query_type\", \"$previous_value\", \"$new_value\", \"$ip_address\", \"$id_module\", now())";
        $result = @$conn->query($sql);
    }
}

function get_account_img($account_number_div) {
    // Set the content-type
    header('Content-type: image/jpg');

    // Create the image
    $im = imagecreatetruecolor(475, 18);
    // Create some colors
    $white = imagecolorallocate($im, 255, 255, 255);
    //$grey = imagecolorallocate($im, 128, 128, 128);
    $black = imagecolorallocate($im, 0, 0, 0);
    imagefilledrectangle($im, 0, 0, 474, 29, $white);

    // The text to draw
    $text = $account_number_div;
    // Replace path by your own font path
    $font = 'micrenc.ttf';

    // Add some shadow to the text
    //imagettftext($im, 20, 0, 11, 21, $grey, $font, $text);

    imagealphablending($im, true);
    imagesavealpha($im, true);
    // Add the text	
    $x = 7;
    $y = 13;
    //imagettftext($im, 20, 0, 7, 13, $black, $font, $text);
    imagettftext($im, 20, 0, $x, $y, $black, $font, $text);
    imagettftext($im, 20, 0, $x, $y + 0.8, $black, $font, $text);
    imagettftext($im, 20, 0, $x + 0.8, $y, $black, $font, $text);

    // Using imagepng() results in clearer text compared with imagejpeg()
    $path = "images/$account_number_div" . ".png";
    imagejpeg($im, $path, 100);
    //$im = imagecreatefrompng($path);
    //imagealphablending($im, true);
    imagedestroy($im);
}

function send_sms($number, $message_sms, $sms_type) {
    global $conn;
    $number = str_replace("(", "", $number);
    $number = str_replace(")", "", $number);
    $number = str_replace("-", "", $number);
    $number = str_replace(" ", "", $number);
    $smsGateway = new SmsGateway('leandro@advanceloading.com', 'Satelite2330');
    $page = 1;
    $result = $smsGateway->getDevices($page);
    $deviceID = $result["response"]["result"]["data"]["0"]["id"];
    $result = $smsGateway->sendMessageToNumber($number, $message_sms, $deviceID);
    //echo print_r($result)."<br>";
    if ($result["response"]["success"] == 1) {
        $id = $result["response"]["result"]["success"][0]["id"];
        $device_id = $result["response"]["result"]["success"][0]["device_id"];
        $message_sms = $result["response"]["result"]["success"][0]["message"];
        $status = $result["response"]["result"]["success"][0]["status"];
        $send_at = $result["response"]["result"]["success"][0]["send_at"];
        $queued_at = $result["response"]["result"]["success"][0]["queued_at"];
        $sent_at = $result["response"]["result"]["success"][0]["sent_at"];
        $delivered_at = $result["response"]["result"]["success"][0]["delivered_at"];
        $expires_at = $result["response"]["result"]["success"][0]["expires_at"];
        $canceled_at = $result["response"]["result"]["success"][0]["canceled_at"];
        $failed_at = $result["response"]["result"]["success"][0]["failed_at"];
        $received_at = $result["response"]["result"]["success"][0]["received_at"];
        $error = $result["response"]["result"]["success"][0]["error"];
        $created_at = $result["response"]["result"]["success"][0]["created_at"];
        $contact_id = $result["response"]["result"]["success"][0]["contact"]["id"];
        $contact_name = $result["response"]["result"]["success"][0]["contact"]["name"];
        $contact_number = $result["response"]["result"]["success"][0]["contact"]["number"];

        $sql = "INSERT INTO sent_sms (id, device_id, message, status_sms, send_at, queued_at, sent_at, delivered_at, expires_at, canceled_at, failed_at, received_at, `error`, created_at, contact_id, contact_name, contact_number, dealership_id, sms_type, date_register, manual_load) VALUES ('$id', '$device_id', \"$message_sms\", '$status', '$send_at', '$queued_at', '$sent_at', '$delivered_at', '$expires_at', '$canceled_at', '$failed_at', '$received_at', '$error', '$created_at', '$contact_id', '$contact_name', '$contact_number', '" . $_SESSION["dealership_id"] . "', '$sms_type', now(), 1)";
        //die($sql);
        $result = $conn->query($sql);
        //die("Result=".$result);
        return true;
    }
}

?>