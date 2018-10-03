<?php

/*
 *  Author S. Brinta <brrinta@gmail.com>
 *  Email: i@brinta.me
 *  Web: https://brinta.me
 *  Do not edit file without permission of author
 *  All right reserved by S. Brinta <brrinta@gmail.com>
 *  Created on: Sep 8, 2018 10:56:34 AM
 */

/**
 * Description of Test
 *
 * @author S. Brinta <brrinta@gmail.com>
 */
class Test extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    function index() {
        $data["title"] = "vantiv test";
        $this->load->view("test", $data);
    }

}
