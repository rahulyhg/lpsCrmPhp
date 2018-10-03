<?php

defined('BASEPATH') OR exit('No direct script access allowed');

$route["autoLogin"] = "site/autoLogin";
$route["ressetPassword"] = "site/ressetPassword";
$route["reset"] = "site/reset";
$route["signup"] = "site/signUp";
$route["recover"] = "site/recoverPassword";
$route["login"] = "site/login";
$route['default_controller'] = 'site';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
