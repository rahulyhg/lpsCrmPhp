<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property \Mpdf\Mpdf $mPdf
 */
class Pdf {

    public function __construct() {
        include_once APPPATH . 'third_party/mPdf/autoload.php';
    }

    function loadMpdf($param = [
        'mode' => 'utf-8',
        'format' => [215.9, 279.4]
    ]) {
        return new \Mpdf\Mpdf($param);
    }

}
