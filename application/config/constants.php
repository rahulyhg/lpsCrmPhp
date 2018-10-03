<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
  |--------------------------------------------------------------------------
  | Display Debug backtrace
  |--------------------------------------------------------------------------
  |
  | If set to TRUE, a backtrace will be displayed along with php errors. If
  | error_reporting is disabled, the backtrace will not display, regardless
  | of this setting
  |
 */
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
  |--------------------------------------------------------------------------
  | File and Directory Modes
  |--------------------------------------------------------------------------
  |
  | These prefs are used when checking and setting modes when working
  | with the file system.  The defaults are fine on servers with proper
  | security, but you may wish (or even need) to change the values in
  | certain environments (Apache running a separate process for each
  | user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
  | always be used to set the mode correctly.
  |
 */
defined('FILE_READ_MODE') OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE') OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE') OR define('DIR_WRITE_MODE', 0755);

/*
  |--------------------------------------------------------------------------
  | File Stream Modes
  |--------------------------------------------------------------------------
  |
  | These modes are used when working with fopen()/popen()
  |
 */
defined('FOPEN_READ') OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE') OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE') OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE') OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE') OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE') OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT') OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT') OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
  |--------------------------------------------------------------------------
  | Exit Status Codes
  |--------------------------------------------------------------------------
  |
  | Used to indicate the conditions under which the script is exit()ing.
  | While there is no universal standard for error codes, there are some
  | broad conventions.  Three such conventions are mentioned below, for
  | those who wish to make use of them.  The CodeIgniter defaults were
  | chosen for the least overlap with these conventions, while still
  | leaving room for others to be defined in future versions and user
  | applications.
  |
  | The three main conventions used for determining exit status codes
  | are as follows:
  |
  |    Standard C/C++ Library (stdlibc):
  |       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
  |       (This link also contains other GNU-specific conventions)
  |    BSD sysexits.h:
  |       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
  |    Bash scripting:
  |       http://tldp.org/LDP/abs/html/exitcodes.html
  |
 */
defined('EXIT_SUCCESS') OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR') OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG') OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE') OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS') OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT') OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE') OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN') OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX') OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

/* * ********************************************************************************* */
defined('WARNING') OR define('WARNING', "warning");
defined('SUCCESS') OR define('SUCCESS', "success");
defined('DANGER') OR define('DANGER', "danger");
defined('INFO') OR define('INFO', "info");
defined('PRIMARY') OR define('PRIMARY', "primary");

/* * ******************************************************************************* */
defined('SYSTEM_NAME') OR define('SYSTEM_NAME', "LABOR POSTER SERVICES CRM");
defined('SYSTEM_SHORT_NAME') OR define('SYSTEM_SHORT_NAME', "LPS CRM");







//////////////////////////////////////////////////////////////////////////////////
defined('TAB_fictitious') OR define('TAB_fictitious', "fictitious");
defined('TAB_TIMESTATION') OR define('TAB_TIMESTATION', "timeStation");
defined('TAB_fictitiousOrders') OR define('TAB_fictitiousOrders', "fictitiousOrders");
defined('TAB_fictitiousRefunds') OR define('TAB_fictitiousRefunds', "fictitiousRefunds");
defined('TAB_orders') OR define('TAB_orders', "orders");
defined('TAB_BRM') OR define('TAB_BRM', "brm");
defined('TAB_NONBRM') OR define('TAB_NONBRM', "nonBrm");
defined('TAB_expenses') OR define('TAB_expenses', "expenses");
defined('TAB_customers') OR define('TAB_customers', "customers");
defined('TAB_exCategory') OR define('TAB_exCategory', "exCategory");
defined('TAB_MAILING') OR define('TAB_MAILING', "mailing");
//defined('TAB_Rprospects') OR define('TAB_Rprospects', "Rprospects");
defined('TAB_prospects') OR define('TAB_prospects', "prospects");
defined('TAB_SESSIONS') OR define('TAB_SESSIONS', "sessions");
defined('TAB_USERS') OR define('TAB_USERS', "users");
defined('TAB_userPermissions') OR define('TAB_userPermissions', "userPermissions");
defined('TAB_userLog') OR define('TAB_userLog', "userLog");
defined('TAB_refunds') OR define('TAB_refunds', "refunds");
defined('TAB_vendors') OR define('TAB_vendors', "vendors");
defined('TAB_jobs') OR define('TAB_jobs', "jobs");
defined('TAB_banks') OR define('TAB_banks', "banks");
defined('TAB_deposits') OR define('TAB_deposits', "deposits");
defined('TAB_terminals') OR define('TAB_terminals', "terminals");
defined('TAB_settllements') OR define('TAB_settllements', "settllements");
defined('TAB_emailTemplates') OR define('TAB_emailTemplates', "emailTemplates");
/* * *********************************************** */
defined('TAB_acProspects') OR define('TAB_acProspects', "acProspects");
defined('TAB_ACBRM') OR define('TAB_ACBRM', "acBrm");
defined('TAB_ACorders') OR define('TAB_ACorders', "acOrders");
defined('TAB_ACrefunds') OR define('TAB_ACrefunds', "acRefunds");
/* * ********************************************************* */
defined('TAB_proDocProspects') OR define('TAB_proDocProspects', "proDocProspects");
defined('TAB_proDocOrders') OR define('TAB_proDocOrders', "proDocOrders");
defined('TAB_proDocCustomers') OR define('TAB_proDocCustomers', "proDocCustomers");
defined('TAB_proDocRefunds') OR define('TAB_proDocRefunds', "proDocRefunds");

