<?php

/**
 * Author S. Brinta <brrinta@gmail.com>
 * Email: i@brinta.me
 * Web: https://brinta.me
 * Do not edit file without permission of author
 * All right reserved by S. Brinta <brrinta@gmail.com>
 * Created on : Aug 9, 2018 , 9:44:15 AM
 */
class Curl extends BRT_Controller {

    public $viewPath = "theme/curl/";

    public function __construct() {
        parent::__construct();
    }

//0232260074120
    function index() {
        foreach ($this->getBroSalesHistory() as $link) {
            echo $link->text . " " . $link->href . br(2);
        }
    }

    function getSalesHistory($stateID, $contactID) {
        if (strtolower($stateID) === "hil") {
            $this->getHilSalesHistory($contactID);
        } elseif (strtolower($stateID) === "bro") {
            $links = $this->getBroSalesHistory($contactID);
            if (sizeof($links) > 0) {
                foreach ($links as $link) {
                    echo '<a class="menu-item" target="_blank" href="' . $link->href . '">', $link->text->date . "</a>" . br();
                }
            }
        } elseif (strtolower($stateID) === "mia") {
            $link = "$contactID";
            $this->getMiaSalesHistory($contactID);
        } elseif (strtolower($stateID) === "lac") {
            $links = $this->getLacSalesHistory($contactID);
            if (sizeof($links) > 0) {
                foreach ($links as $link) {
                    echo '<a class="menu-item" target="_blank" href="' . $link->href . '">', $link->text->date . "</a>" . br();
                }
            }
        } elseif (strtolower($stateID) === "sar") {
            $links = $this->getSarSalesHistory($contactID);
            // dnp($links);
            if (sizeof($links) > 0) {
                foreach ($links as $link) {
                    //dnp($links);
                    echo '<a class="menu-item" target="_blank" href="' . $link->href . '">', $link->text->date . "</a>" . br();
                }
            }
        }
    }

    function getMiaSalesHistory($contactID) {
        $folio = str_pad($contactID, 13, "0", STR_PAD_LEFT);
        $data["folio"] = $folio;
        $data["data"] = $this->getMiaJson($folio);
        $this->load->view($this->viewPath . "getMiaSalesHistory", $data);
    }

    function getMiaJson($folio) {
        $url = "http://miamidade.champtechsolutions.com/api/miamidade/salesinfo/$folio";
        $cURL = curl_init();
        curl_setopt($cURL, CURLOPT_URL, $url);
        curl_setopt($cURL, CURLOPT_HTTPGET, true);
        curl_setopt($cURL, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cURL, CURLOPT_HTTPHEADER, array(
            //'Content-Type: application/json',
            'Accept: application/json'
        ));

        $result = curl_exec($cURL);
        curl_close($cURL);
        return $result;
    }

    function getHilSalesHistory($contactID) {
        $folio = str_pad($contactID, 10, "0", STR_PAD_LEFT);
        $data["folio"] = $folio;
        $this->load->view($this->viewPath . "getHilSalesHistory", $data);
    }

    function getBroSalesHistory($contactID) {
        $folio = str_pad($contactID, 12, "0", STR_PAD_LEFT);
        $hrefParser = "https://officialrecords.broward.org/AcclaimWeb/Details/";
        $url = "http://www.bcpa.net/RecInfo.asp?URL_Folio=$folio";
        $curlSession = curl_init();
        curl_setopt($curlSession, CURLOPT_URL, $url);
        curl_setopt($curlSession, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);

        $html = curl_exec($curlSession);
        curl_close($curlSession);
        $dom = new DOMDocument;
        @$dom->loadHTML($html);
        $links = $dom->getElementsByTagName('a');
        $returnLink = [];
        foreach ($links as $link) {
            $href = $link->getAttribute('href');
            if (strpos($href, $hrefParser) !== false) {
                $aParent = $link->parentNode;
                array_push($returnLink, (object) ["text" => $this->bro_date($aParent), 'href' => $href]);
            }
        }
        return $returnLink;
    }

    private function bro_date($aParent) {
        $price = $aParent->previousSibling->previousSibling;
        $type = $price->previousSibling->previousSibling;
        $date = $type->previousSibling->previousSibling->nodeValue;

        if (preg_match_all('/\d{1,2}\/\d{1,2}\/\d{1,4}/', $date, $matches)) {
            $date = convertDate($matches[0][0], "d M, Y");
        } else {
            $date = "N/V";
        }
        return (object) ["date" => $date, "type" => clean($type->nodeValue), "price" => clean($price->nodeValue)];
    }

    function getLacSalesHistory($contactID) {
        $folio = str_pad($contactID, 7, "0", STR_PAD_LEFT);
        $url = "https://www.lakecopropappr.com/property-details.aspx?AltKey=$folio";
        $curlSession = curl_init();
        curl_setopt($curlSession, CURLOPT_URL, $url);
        curl_setopt($curlSession, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);
        $returnLink = [];
        $html = curl_exec($curlSession);
        curl_close($curlSession);
        $dom = new DOMDocument;
        @$dom->loadHTML($html);
        $xpath = new DOMXPath($dom);
        $table = $xpath->query("//table[@class='property_data_table'][@title='Sales History']")[0];
        $tableHtml = $table->ownerDocument->saveHTML($table);
        $dom1 = new DOMDocument;
        @$dom1->loadHTML($tableHtml);
        $xpath1 = new DOMXPath($dom1);
        $links = $xpath1->query("//a");
        foreach ($links as $link) {
            $href = $link->getAttribute('href');
            $aParent = $link->parentNode;
            array_push($returnLink, (object) ["text" => $this->lac_date($aParent), 'href' => $href]);
        }
        return $returnLink;
    }

    private function lac_date($aParent) {
        $date = $aParent->nextSibling->nextSibling;
        $Instrument = $date->nextSibling->nextSibling;
        $Qualified = $Instrument->nextSibling->nextSibling;
        $Vacant = $Qualified->nextSibling->nextSibling;
        $price = $Vacant->nextSibling->nextSibling;

        if (preg_match_all('/\d{1,2}\/\d{1,2}\/\d{1,4}/', $date->nodeValue, $matches)) {
            $date = convertDate($matches[0][0], "d M, Y");
        } else {
            $date = "N/V";
        }
        return (object) ["date" => $date,
                    "instrument" => clean($Instrument->nodeValue),
                    "qualified" => clean($Qualified->nodeValue),
                    "vacant" => clean($Vacant->nodeValue),
                    "price" => clean($price->nodeValue)];
    }

    function getSarSalesHistory($contactID) {
        $folio = str_pad($contactID, 10, "0", STR_PAD_LEFT);
        $url = "https://www.sc-pa.com/propertysearch/parcel/details/$folio";
        $curlSession = curl_init();
        curl_setopt($curlSession, CURLOPT_URL, $url);
        curl_setopt($curlSession, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);
        $returnLink = [];
        $html = curl_exec($curlSession);
        curl_close($curlSession);
        $dom = new DOMDocument;
        @$dom->loadHTML($html);
        $xpath = new DOMXPath($dom);
        $table = $xpath->query("//table[@class='grid'][thead//tr//th//text()[contains(., 'Recorded Consideration')]]")[0];

        $tableHtml = $table->ownerDocument->saveHTML($table);
        //echo $tableHtml;
        $dom1 = new DOMDocument;
        @$dom1->loadHTML($tableHtml);
        $xpath1 = new DOMXPath($dom1);
        $links = $xpath1->query("//a");
        foreach ($links as $link) {
            $href = $link->getAttribute('href');
            $aParent = $link->parentNode;
            array_push($returnLink, (object) ["text" => $this->sar_date($aParent), 'href' => $href]);
        }
        //dnp($returnLink);
        return $returnLink;
    }

    private function sar_date($aParent) {
        $RecordedConsideration = $aParent->previousSibling->previousSibling;
        $date = $RecordedConsideration->previousSibling->previousSibling;
        $InstrumentNumber = $aParent;
        $QualificationCode = $InstrumentNumber->nextSibling->nextSibling;
        $GrantororSeller = $QualificationCode->nextSibling->nextSibling;
        $InstrumentType = $GrantororSeller->nextSibling->nextSibling;

        if (preg_match_all('/\d{1,2}\/\d{1,2}\/\d{1,4}/', $date->nodeValue, $matches)) {
            $date = convertDate($matches[0][0], "d M, Y");
        } else {
            $date = "N/V";
        }
        return (object) ["date" => $date,
                    "RecordedConsideration" => clean($RecordedConsideration->nodeValue),
                    "InstrumentNumber" => clean($InstrumentNumber->nodeValue),
                    "QualificationCode" => clean($QualificationCode->nodeValue),
                    "GrantororSeller" => clean($GrantororSeller->nodeValue),
                    "InstrumentType" => clean($InstrumentType->nodeValue)];
    }

}
