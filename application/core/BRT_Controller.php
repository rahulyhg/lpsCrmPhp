<?php

/**
 * Author S Brinta<brrinta@gmail.com>
 * Email: brrinta@gmail.com
 * Web: https://brinta.me
 * Do not edit file without permission of author
 * All right reserved by S Brinta<brrinta@gmail.com>
 * Created on : Apr 30, 2018, 3:31:17 PM
 * @property CI_Encryption $encryption
 * @property Crm $crm Description
 */
class BRT_Controller extends CI_Controller {

    private $viewInit = "theme/";
    public $meta = [], $data = [], $navMeta = [];

    public function __construct() {
        parent::__construct();
        $upconfig['upload_path'] = 'uploads/';
        $upconfig["encrypt_name"] = TRUE;
        $upconfig['allowed_types'] = '*';
        $this->upload->initialize($upconfig);
        $emconfig['useragent'] = "Brinta";
        $emconfig['mailtype'] = "html";
        $this->email->initialize($emconfig);
        if (isset($_SESSION["user"])) {
            $this->checkPer($_SESSION["user"]);
        }
        $this->load->model("crm");
    }

    public function view($page, $hideContentHeader = false) {
        $this->data["title"] = isset($this->data["title"]) ? $this->data["title"] : SYSTEM_NAME;
        if (!isset($this->data["navBarSettings"])) {
            $this->navBarSettings();
        }
        if ($this->getUserData()) {
            $this->data["currentUser"] = $this->mdb->getSingleData(TAB_USERS, ["id" => $this->getUserID()]);
        }

        $this->setNavMeta($hideContentHeader);
        $this->data["__stateList"] = getState();
        $passData = array_merge($this->data, ["navMeta" => $this->navMeta], $this->meta);
        $this->load->view($this->viewInit . "header", $passData);
        $this->load->view($this->viewInit . "navbar", $passData);
        $this->load->view($this->viewInit . $page, $passData);
        $this->load->view($this->viewInit . "footer", $passData);
    }

    public function modal($page) {
        $this->data["__stateList"] = getState();
        $passData = array_merge($this->data, $this->meta);
        $this->load->view($this->viewInit . $page, $passData);
    }

    private function setNavMeta($hideContentHeader) {
        $this->navMeta["pageTitle"] = isset($this->navMeta["pageTitle"]) ? $this->navMeta["pageTitle"] : "";
        $this->navMeta["bc"] = isset($this->navMeta["bc"]) ? $this->navMeta["bc"] : [["page" => "", "url" => "j"]];
        $this->navMeta["hideContentHeader"] = $hideContentHeader;
    }

    /**
     * Setting for menubar
     * @param type $topBar
     * @param type $slideBar
     * @return type
     */
    function navBarSettings($topBar = true, $slideBar = true, $topAlert = true) {
        $this->data["showNavBar"] = $slideBar;
        $this->data["navBarSettings"] = ["slideBar" => $slideBar, "topBar" => $topBar, "topAlert" => $topAlert];
//dnd( $this->meta);
    }

    function redirectToReference($msg = "", $msgType = "") {
        $this->setAlertMsg($msg, $msgType);
        return redirect($_SERVER["HTTP_REFERER"]);
    }

    function redirectToUrl($url, $msg = "", $msgType = "") {
        $this->setAlertMsg($msg, $msgType);
        return redirect($url);
    }

    function someThingWrong() {
        $this->setAlertMsg("Something Wrong!", DANGER);
        return redirect(dashboard_url());
    }

    function setAlertMsg($msg = "", $msgType = "") {
        if ($msg) {
            $_SESSION["altMsg"] = $msg;
            $_SESSION["altMsgType"] = $msgType;
        }
    }

    public function logout() {       
        $this->session->unset_userdata('user');
        $this->session->unset_userdata('permission');
        session_destroy();        
        return redirect(site_url());
    }

    function getUserID() {
        return $this->getUserData()->id;
    }

    function getUserData() {
        return isset($_SESSION["user"]) ? $_SESSION["user"] : false;
    }

    function getUserInfo($col) {
        return $this->getUserData()->$col;
    }

    function gotoReferrer() {
        return redirect($this->agent->referrer());
    }

    function getSessionID() {
        return session_id();
    }

    function getUniID() {
        return $_SESSION["uniID"];
    }

    function getCurrentUserPosition() {
        return $_SESSION["position"];
    }

    /**
     * redirect to login page if not loged in user
     * @return type
     */
    function ifNotLogin() {
        if (!$this->getUserData()) {
            $request_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
            return $this->redirectToUrl(login_url() . "?redirect=" . $request_link, "Access proteced!<br>Please Login", "danger");
        }
    }

    /**
     * redirect if not admin user
     * @return type
     */
    function ifNotAdmin() {
        if (!$this->getUserData()->admin) {
            return $this->redirectToUrl(login_url(), "Access proteced!", "danger");
        }
    }

    /**
     * redirect to dash page if log in user
     * @return type
     */
    function ifLogin() {
        if ($this->getUserData()) {
            return $this->redirectToUrl(dashboard_url(), "Welcome!", "info");
        }
    }

    function ifNotValidState($state) {
        if (!array_key_exists($state, $this->brt->statesList)) {
            return $this->redirectToUrl(dashboard_url(), "State Not valid!", DANGER);
        }
    }

    function ifNotPermited($per) {
        if (!hasPermission($per)) {
            return $this->redirectToUrl(login_url(), "Access proteced!<br>Please Login with permitted Account!", "danger");
        }
    }

    function checkPer($user) {
        $permission = $this->mdb->getSingleData(TAB_userPermissions, ["id" => $user->position]);
        // dnp($permission);
        // $columns = $this->db->list_fields(TAB_userPermissions);
        $sessData = [];
        foreach (json_decode($permission->permissions) as $col => $pf) {
            $sessData[$col] = $pf;
        }
        $this->session->set_userdata("permission", $sessData);
    }

}
