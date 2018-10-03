<?php

/**
 * Author S. Brinta <brrinta@gmail.com>
 * Email: i@brinta.me
 * Web: https://brinta.me
 * Do not edit file without permission of author
 * All right reserved by S. Brinta <brrinta@gmail.com>
 * Created on : Jun 29, 2018 , 7:44:39 PM
 * @property Report $report
 */
class Reports extends BRT_Controller {

    public $viewPath = "reports/";
    public $modalPath = "reports/modal/";

    public function __construct() {
        parent::__construct();
        $this->ifNotLogin();
        $this->load->model("report");
    }

    function index() {
        $this->ifNotPermited("reports/general");
        $this->navMeta = ["active" => "reports_general", "pageTitle" => "General Reports", "bc" => array(
                ["url" => "", "page" => __CLASS__]
        )];
        $day = date('w') - 1;
        $dateForm = date('d M, Y', strtotime('-' . $day . ' days'));
        $dateTo = date('d M, Y', strtotime('+' . (6 - $day) . ' days'));
        if (isset($_GET["dateForm"])) {
            $dateForm = $_GET["dateForm"];
        }
        if (isset($_GET["dateTo"])) {
            $dateTo = $_GET["dateTo"];
        }
        $this->report->setDate($dateForm, $dateTo);
        $reports = $this->report->getGeneralReport();
        $this->data["reports"] = $reports;
        $this->view($this->viewPath . "index");
    }

    function plReports() {
        $this->ifNotPermited("reports/state PnL");
        $this->navMeta = ["active" => "reports_statePL", "pageTitle" => "State P&L Reports", "bc" => array(
                ["url" => "", "page" => __CLASS__]
        )];

        $day = date('w') - 1;
        $dateForm = date('d M, Y', strtotime('-' . $day . ' days'));
        $dateTo = date('d M, Y', strtotime('+' . (6 - $day) . ' days'));
        if (isset($_GET["dateForm"])) {
            $dateForm = $_GET["dateForm"];
        }
        if (isset($_GET["dateTo"])) {
            $dateTo = $_GET["dateTo"];
        }
        $this->report->setDate($dateForm, $dateTo);
        $reports = $this->report->getGeneralReport();
        $this->data["reports"] = $reports;
        $this->view($this->viewPath . "plReports");
    }

    function plFictitious() {
        $this->ifNotPermited("reports/fictitious PnL");
        $this->navMeta = ["active" => "reports_fictPL", "pageTitle" => "Fictitious P&L Reports", "bc" => array(
                ["url" => "", "page" => __CLASS__]
        )];

        $day = date('w') - 1;
        $dateForm = date('d M, Y', strtotime('-' . $day . ' days'));
        $dateTo = date('d M, Y', strtotime('+' . (6 - $day) . ' days'));
        if (isset($_GET["dateForm"])) {
            $dateForm = $_GET["dateForm"];
        }
        if (isset($_GET["dateTo"])) {
            $dateTo = $_GET["dateTo"];
        }
        $this->report->setDate($dateForm, $dateTo);
        $reports = $this->report->fictious();
        // dnp($reports);
        $this->data["reports"] = $reports;
        $this->view($this->viewPath . "plFictitious");
    }

    function pmtQtyReport() {
        $this->ifNotPermited("reports/pmtQtyReport");
        $this->navMeta = ["active" => "reports_pmt", "pageTitle" => "Pmt Qty Reports", "bc" => array(
                ["url" => "", "page" => __CLASS__]
        )];

        $day = date('w') - 1;
        $dateForm = date('d M, Y', strtotime('-' . $day . ' days'));
        $dateTo = date('d M, Y', strtotime('+' . (6 - $day) . ' days'));
        if (isset($_GET["dateForm"])) {
            $dateForm = $_GET["dateForm"];
        }
        if (isset($_GET["dateTo"])) {
            $dateTo = $_GET["dateTo"];
        }
        $this->report->setDate($dateForm, $dateTo);
        $reports = $this->report->pmtQty();

        $this->data["reports"] = $reports;
        //dnp($reports);
        $this->view($this->viewPath . "pmtQtyReport");
    }

    function acReports() {
        $this->ifNotPermited("reports/acReports");
        $this->navMeta = ["active" => "acReports", "pageTitle" => "Annual Compliance Report", "bc" => array(
                ["url" => "", "page" => __CLASS__]
        )];

        $day = date('w') - 1;
        $dateForm = date('d M, Y', strtotime('-' . $day . ' days'));
        $dateTo = date('d M, Y', strtotime('+' . (6 - $day) . ' days'));
        if (isset($_GET["dateForm"])) {
            $dateForm = $_GET["dateForm"];
        }
        if (isset($_GET["dateTo"])) {
            $dateTo = $_GET["dateTo"];
        }
        $this->report->setDate($dateForm, $dateTo);
        $reports = $this->report->acReports();
        //   $this->output->enable_profiler(true);
        // dnp($reports);
        $this->data["reports"] = $reports;
        $this->view($this->viewPath . "acReports");
    }

    function ordersReport() {
        $this->ifNotPermited("reports/ordersReport");
        $this->navMeta = ["active" => "ordersReports", "pageTitle" => "Orders Report", "bc" => array(
                ["url" => "", "page" => __CLASS__]
        )];

        $day = date('w') - 1;
        $dateForm = date('d M, Y', strtotime('-' . $day . ' days'));
        $dateTo = date('d M, Y', strtotime('+' . (6 - $day) . ' days'));
        if (isset($_GET["dateForm"])) {
            $dateForm = $_GET["dateForm"];
        }
        if (isset($_GET["dateTo"])) {
            $dateTo = $_GET["dateTo"];
        }
        $this->report->setDate($dateForm, $dateTo);
        $reports = $this->report->ordersReports();
        //   $this->output->enable_profiler(true);
        //dnp($reports);
        $this->data["reports"] = $reports;
        $this->view($this->viewPath . "ordersReport");
    }

    function proDocReport() {
        $this->ifNotPermited("reports/proDocReport");
        $this->navMeta = ["active" => "proDocReport", "pageTitle" => "Property Doc Report", "bc" => array(
                ["url" => "", "page" => __CLASS__]
        )];

        $day = date('w') - 1;
        $dateForm = date('d M, Y', strtotime('-' . $day . ' days'));
        $dateTo = date('d M, Y', strtotime('+' . (6 - $day) . ' days'));
        if (isset($_GET["dateForm"])) {
            $dateForm = $_GET["dateForm"];
        }
        if (isset($_GET["dateTo"])) {
            $dateTo = $_GET["dateTo"];
        }
        $this->report->setDate($dateForm, $dateTo);
        $reports = $this->report->proDocReports();
        //   $this->output->enable_profiler(true);
        //dnp($reports);
        $this->data["reports"] = $reports;
        $this->view($this->viewPath . "proDocReport");
    }

    function conversionRateReport() {
        $this->ifNotPermited("reports/conversionRateReport");
        $this->navMeta = ["active" => "conversionRateReport", "pageTitle" => "Conversion Rate Report", "bc" => array(
                ["url" => "", "page" => __CLASS__]
        )];

        $day = date('w') - 1;
        $dateForm = date('d M, Y', strtotime('-' . $day . ' days'));
        $dateTo = date('d M, Y', strtotime('+' . (6 - $day) . ' days'));
        if (isset($_GET["dateForm"])) {
            $dateForm = $_GET["dateForm"];
        }
        if (isset($_GET["dateTo"])) {
            $dateTo = $_GET["dateTo"];
        }
        $this->report->setDate($dateForm, $dateTo);
        $reports = $this->report->conversionRateReports();
        //   $this->output->enable_profiler(true);
        //dnp($reports);
        $this->data["reports"] = $reports;
        $this->view($this->viewPath . "conversionRateReport");
    }

    function dump() {
        $day = date('w') - 1;
        $dateForm = date('d M, Y', strtotime('-' . $day . ' days'));
        $dateTo = date('d M, Y', strtotime('+' . (6 - $day) . ' days'));
        if (isset($_GET["dateForm"])) {
            $dateForm = $_GET["dateForm"];
        }
        if (isset($_GET["dateTo"])) {
            $dateTo = $_GET["dateTo"];
        }
        //$this->report->setDate($dateForm, $dateTo);
        $reports = $this->report->conversionRateReports();
        //$this->output->enable_profiler(true);
        dnp($reports);
    }

}
