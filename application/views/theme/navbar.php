<?php
/**
 * Author S Brinta<brrinta@gmail.com>
 * Email: brrinta@gmail.com
 * Web: https://brinta.me
 * Do not edit file without permission of author
 * All right reserved by S Brinta<brrinta@gmail.com>
 * Created on : Apr 30, 2018, 3:46:43 PM
 */
if ($navBarSettings["topBar"]) {
    ?>
    <!-- fixed-top-->
    <nav class="header-navbar navbar-expand-md navbar navbar-with-menu fixed-top navbar-dark  bg-gradient-x-grey-blue navbar-shadow">
        <div class="navbar-wrapper">
            <div class="navbar-header">
                <ul class="nav navbar-nav flex-row">
                    <li class="nav-item mobile-menu d-md-none mr-auto"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ft-menu font-large-1"></i></a></li>
                    <li class="nav-item">
                        <a class="navbar-brand" href="<?= base_url() ?>">
                            <img class="brand-logo" alt="<?= SYSTEM_SHORT_NAME ?>" src="<?= logoSrc() ?>" width="32">
                            <h2 class="brand-text"><?= SYSTEM_SHORT_NAME ?></h2>
                        </a>
                    </li>
                    <li class="nav-item d-md-none">
                        <a class="nav-link open-navbar-container" data-toggle="collapse" data-target="#navbar-mobile"><i class="fa fa-ellipsis-v"></i></a>
                    </li>
                </ul>
            </div>
            <div class="navbar-container content">
                <div class="collapse navbar-collapse" id="navbar-mobile">
                    <ul class="nav navbar-nav mr-auto float-left">
                        <li class="nav-item d-none d-md-block"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ft-menu"></i></a></li>                        
                        <li class="nav-item d-none d-md-block"><a class="nav-link nav-link-expand" href="#"><i class="ficon ft-maximize"></i></a></li>                       
                    </ul>
                    <ul class="nav navbar-nav float-right">
                        <li class="nav-item">
                            <a class="nav-link nav-link-label" href="<?= dashboard_url() ?>">
                                <i class="fa fa-dashboard"></i>
                            </a>
                        </li>
                        <?php
                        if (hasPermission("checks")) {
                            ?>
                            <li class="nav-item">
                                <a class="nav-link nav-link-label" href="<?= base_url() ?>checks/.?ids=<?= getCurrentUser()->email ?>" 
                                   title="CRM check module" id="accounting" data-fancybox-type="iframe">
                                    <i class="fa fa-calculator glyphicon-50px"></i>
                                </a>
                            </li>                        
                            <?php
                        }
                        ?>
                        <li class="dropdown dropdown-user nav-item">
                            <a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown">
                                <span class="avatar avatar-online">
                                    <img src="<?= property_url() ?>images/avatar.png" alt="avatar"><i></i></span>
                                <span class="user-name"><?= $currentUser->lastName ?></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" modal-toggler="true" data-target="#remoteModal1" data-remote="<?= dashboard_url("importZipModal") ?>">
                                    <i class="fa fa-file-archive-o"></i> Upload Zip
                                </a>
                                <a class="dropdown-item" href="<?= user_url("profile") ?>" id="profile_nav"><i class="ft-user"></i> Edit Profile</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="<?= user_url("logout") ?>"><i class="ft-power"></i> Logout</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>   
    <?php
}

if ($navBarSettings["slideBar"]) {
    ?>
    <!-- ////////////////////////////////////////////////////////////////////////////-->
    <div class="main-menu menu-fixed menu-dark menu-accordion menu-shadow menu-collapsible" data-scroll-to-active="true">
        <div class="main-menu-content">
            <ul class="navigation navigation-main show" id="main-menu-navigation" data-menu="menu-navigation">
                <li class=" navigation-header">
                    <span>----*----</span><i class=" ft-minus" data-toggle="tooltip" data-placement="right"
                                             data-original-title="----*----"></i>
                </li>
                <li class=" nav-item " id="dashboard_nav">
                    <a href="<?= dashboard_url() ?>">
                        <i class="ft-home"></i>
                        <span class="menu-title">Dashboard</span>
    <!--                        <span class="badge badge badge-primary badge-pill float-right mr-2">3</span>-->
                    </a>
                </li>
                <?php
                if (hasPermission("prospects") || hasPermission("orders") || hasPermission("customers")) {
                    ?>
                    <li class="nav-item ">
                        <a href="#">
                            <i class="fa fa-map"></i>
                            <span class="menu-title">States</span>        
                        </a>

                        <ul class="menu-content">
                            <li id="state_nav">
                                <a class="menu-item text-warning" href="<?= dashboard_url("states") ?>">State Dashboard</a>
                            </li>
                            <?php
                            foreach ($__stateList as $short => $state) {
                                if (strlen($short) === 2) {
                                    ?>                                    
                                    <li id="<?= $short ?>_nav" class="has-sub"><a class="menu-item" href="#"><?= $state ?></a>
                                        <ul class="menu-content">
                                            <li class="is-shown"><a class="menu-item" href="<?= prospects_url($short) ?>"><?= $short ?> Prospects</a></li>
                                            <li class="is-shown"><a class="menu-item" href="<?= dashboard_url("orders/" . $short) ?>"><?= $short ?> Orders</a></li>
                                            <li class="is-shown"><a class="menu-item" href="<?= dashboard_url("customers/" . $short) ?>"><?= $short ?> Customers</a></li>
                                            <li class="is-shown"><a class="menu-item" href="<?= dashboard_url("refunds/" . $short) ?>"><?= $short ?> Refunds</a></li>
                                        </ul>
                                    </li>
                                    <?php
                                }
                            }
                            ?>
                        </ul>
                    </li>
                    <li class="nav-item ">
                        <a href="#">
                            <i class="fa fa-map"></i>
                            <span class="menu-title">R States</span>        
                        </a>

                        <ul class="menu-content">                           
                            <?php
                            foreach ($__stateList as $short => $state) {
                                if (strlen($short) === 3) {
                                    ?>                                    
                                    <li id="<?= $short ?>_nav" class="has-sub"><a class="menu-item" href="#"><?= $state ?></a>
                                        <ul class="menu-content">
                                            <li class="is-shown"><a class="menu-item" href="<?= prospects_url($short) ?>"><?= $short ?> Prospects</a></li>
                                            <li class="is-shown"><a class="menu-item" href="<?= dashboard_url("orders/" . $short) ?>"><?= $short ?> Orders</a></li>
                                            <li class="is-shown"><a class="menu-item" href="<?= dashboard_url("customers/" . $short) ?>"><?= $short ?> Customers</a></li>
                                            <li class="is-shown"><a class="menu-item" href="<?= dashboard_url("refunds/" . $short) ?>"><?= $short ?> Refunds</a></li>
                                        </ul>
                                    </li>
                                    <?php
                                }
                            }
                            ?>
                        </ul>
                    </li>
                    <?php
                }
                if (hasPermission("annualCompliance")) {
                    ?>
                    <li class = "nav-item ">
                        <a href = "#" class="text-warning">
                            <i class = "fa fa-at"></i>
                            <span class = "menu-title">Annual Compliance</span>
                        </a>
                        <ul class = "menu-content">
                            <li id="state_nav">
                                <a class="menu-item text-warning" href="<?= annualCompliance_url() ?>">AC Dashboard</a>
                            </li>
                            <li id="state_nav">
                                <a class="menu-item" href="<?= dashboard_url("brm") ?>">BRM</a>
                            </li>

                            <?php
                            foreach (getACState() as $short => $state) {
                                ?>                                
                                <li id="<?= $short ?>_nav" class="has-sub"><a class="menu-item" href="#"><?= $state ?></a>
                                    <ul class="menu-content">
                                        <li class="is-shown"><a class="menu-item" href="<?= annualCompliance_url("prospects/" . $short) ?>"><?= $short ?> Prospects</a></li>
                                        <li class="is-shown"><a class="menu-item" href="<?= annualCompliance_url("orders/" . $short) ?>"><?= $short ?> Orders</a></li>
                                        <li class="is-shown"><a class="menu-item" href="<?= annualCompliance_url("customers/" . $short) ?>"><?= $short ?> Customers</a></li>
                                        <li class="is-shown"><a class="menu-item" href="<?= annualCompliance_url("refunds/" . $short) ?>"><?= $short ?> Refunds</a></li>
                                    </ul>
                                </li>
                                <?php
                            }
                            ?>
                        </ul>
                    </li>
                    <?php
                }
                if (hasPermission("proDoc")) {
                    ?>
                    <li class = "nav-item ">
                        <a href = "#" class="text-success">
                            <i class = "fa fa-building-o"></i>
                            <span class = "menu-title">Property Documents</span>
                        </a>
                        <ul class = "menu-content">
                            <li id="proDoc_nav">
                                <a class="menu-item text-success" href="<?= proDoc_url() ?>">Pro Doc Dashboard</a>
                            </li>                           

                            <?php
                            foreach (getProDocState() as $short => $state) {
                                ?>                                
                                <li id="<?= $short ?>_nav" class="has-sub"><a class="menu-item" href="#"><?= $state ?></a>
                                    <ul class="menu-content">
                                        <li class="is-shown"><a class="menu-item" href="<?= proDoc_url("prospects/" . $short) ?>"><?= $short ?> Prospects</a></li>
                                        <?php
                                        if (hasPermission("proDoc/orders")) {
                                            ?>
                                            <li class="is-shown"><a class="menu-item" href="<?= proDoc_url("orders/" . $short) ?>"><?= $short ?> Orders</a></li>
                                            <?php
                                        }
                                        if (hasPermission("proDoc/customers")) {
                                            ?>
                                            <li class="is-shown"><a class="menu-item" href="<?= proDoc_url("customers/" . $short) ?>"><?= $short ?> Customers</a></li>
                                            <?php
                                        }
                                        if (hasPermission("proDoc/refunds")) {
                                            ?>
                                            <li class="is-shown"><a class="menu-item" href="<?= proDoc_url("refunds/" . $short) ?>"><?= $short ?> Refunds</a></li>
                                            <?php
                                        }
                                        ?>
                                    </ul>
                                </li>
                                <?php
                            }
                            ?>
                        </ul>
                    </li>
                    <?php
                }

                if (hasPermission("fictitious")) {
                    ?>
                    <li class = "nav-item " id="fictitious_nav">
                        <a href = "#">
                            <i class="fa icon-map"></i>
                            <span class="menu-title">Fictitious</span>
                        </a>
                        <ul class="menu-content">
                            <?php
                            if (hasPermission("fictitious")) {
                                ?>
                                <li>
                                    <a class="menu-item" href="<?= dashboard_url("fictitious") ?>">Fictitious</a>
                                </li>
                                <?php
                            }
                            if (hasPermission("fictitious/customers")) {
                                ?>
                                <li>
                                    <a class="menu-item" href="<?= dashboard_url("fictitiousCustomers") ?>">Fictitious Customers</a>
                                </li>
                                <?php
                            }
                            if (hasPermission("fictitious/add")) {
                                ?>
                                <li>
                                    <a class="menu-item" href="<?= dashboard_url("fictitiousOrders") ?>">Fictitious Orders</a>
                                </li>
                                <?php
                            }
                            if (hasPermission(TAB_fictitious . "/refund")) {
                                ?>
                                <li>
                                    <a class="menu-item" href="<?= dashboard_url("fictRefunds") ?>">Fictitious Refunds</a>
                                </li>
                                <?php
                            }
                            ?>
                        </ul>
                    </li>                   
                    <?php
                }
                if (hasPermission("brm")) {
                    ?>
                    <li class=" nav-item " id="brm_nav">
                        <a href="<?= dashboard_url("brm") ?>">
                            <i class="ft-star"></i>
                            <span class="menu-title">BRM</span>
                        </a>
                    </li>
                    <?php
                }
                if (hasPermission("nonBrm")) {
                    ?>
                    <li class=" nav-item " id="noBrm_nav">
                        <a href="<?= dashboard_url("nonBrm") ?>">
                            <i class="fa fa-star-half-full"></i>
                            <span class="menu-title">Non BRM</span>
                        </a>
                    </li>
                    <?php
                }
                if (hasPermission("mailing")) {
                    ?>
                    <li class=" nav-item " id="mailing_nav">
                        <a href="<?= dashboard_url("mailing") ?>">
                            <i class="ft-mail"></i>
                            <span class="menu-title">Mailings</span>
                        </a>
                    </li>
                    <?php
                }
                if (hasPermission("expenses")) {
                    ?>
                    <li class=" nav-item " id="expense_nav">
                        <a href="<?= dashboard_url("expense") ?>">
                            <i class="ft-tag"></i>
                            <span class="menu-title">Expense</span>
                        </a>
                    </li>
                    <?php
                }
                if (hasPermission("vendors")) {
                    ?>
                    <li class=" nav-item " id="vendors_nav">
                        <a href="<?= vendors_url() ?>">
                            <i class="icon-vector"></i>
                            <span class="menu-title">Vendors</span>
                        </a>
                    </li>
                    <?php
                }
                if (hasPermission("jobs")) {
                    ?>
                    <li class=" nav-item " id="jobs_nav">
                        <a href="<?= jobs_url() ?>">
                            <i class="icon-basket"></i>
                            <span class="menu-title">Jobs</span>
                        </a>
                    </li>
                    <?php
                }
                if (hasPermission("deposits")) {
                    ?>
                    <li class="nav-item" id="deposits_nav"><a href="#">
                            <i class="ft-airplay"></i>
                            <span class="menu-title">Deposits</span>
            <!--                        <span class="badge badge badge-primary badge-pill float-right mr-2">3</span>-->
                        </a>
                        <ul class="menu-content">
                            <li id="banks_nav"><a class="menu-item" href="<?= deposits_url("banks") ?>">
                                    <i class="fa fa-bank"></i>
                                    <span>Banks</span>
                                </a>
                            </li>
                            <li id="ccstatement_nav">
                                <a class="menu-item" href="<?= deposits_url("ccSettllements") ?>">
                                    <i class="ft-activity"></i>
                                    <span>CC Settllements</span>
                                </a>
                            </li>

                        </ul>
                    </li>         
                    <?php
                }
                if (hasPermission("reports")) {
                    ?>
                    <li class="nav-item"><a href="#">
                            <i class="ft-book"></i>
                            <span class="menu-title">Reports</span>                        
                        </a>                    
                        <ul class="menu-content">
                            <?php
                            if (hasPermission("reports/general")) {
                                ?>
                                <li id="reports_general_nav"><a class="menu-item" href="<?= reports_url() ?>">General Report</a></li>
                                <?php
                            }
                            if (hasPermission("reports/state PnL")) {
                                ?>
                                <li id="reports_statePL_nav"><a class="menu-item" href="<?= reports_url("plReports") ?>">State P&L Report</a></li>
                                <?php
                            }
                            if (hasPermission("reports/fictitious PnL")) {
                                ?>
                                <li id="reports_fictPL_nav"><a class="menu-item" href="<?= reports_url("plFictitious") ?>">Fictitious P&L Report</a></li>
                                <?php
                            }
                            if (hasPermission("reports/pmtQtyReport")) {
                                ?>
                                <li id="reports_fictPL_nav"><a class="menu-item" href="<?= reports_url("pmtQtyReport") ?>">Pmt Qty Report</a></li>
                                <?php
                            }
                            if (hasPermission("reports/acReports")) {
                                ?>
                                <li id="reports_acReports"><a class="menu-item" href="<?= reports_url("acReports") ?>">Annual Compliance Report</a></li>
                                <?php
                            }
                            if (hasPermission("reports/ordersReport")) {
                                ?>
                                <li id="reports_ordersReport"><a class="menu-item" href="<?= reports_url("ordersReport") ?>">Orders Report</a></li>
                                <?php
                            }if (hasPermission("reports/proDocReport")) {
                                ?>
                                <li id="reports_proDocReport"><a class="menu-item" href="<?= reports_url("proDocReport") ?>">Property Document Report</a></li>
                                <?php
                            }if (hasPermission("reports/conversionRateReport")) {
                                ?>
                                <li id="reports_conversionRateReport"><a class="menu-item" href="<?= reports_url("conversionRateReport") ?>">Conversion Rate Report</a></li>
                                <?php
                            }
                            ?>
                        </ul>
                    </li>
                    <?php
                }
                if ($_SESSION["user"]->admin) {
                    ?>

                    <li class="nav-item"><a href="#">
                            <i class="ft-settings"></i>
                            <span class="menu-title">Settings</span>                        
                        </a>                    
                        <ul class="menu-content">
                            <li id="users_nav"><a class="menu-item" href="<?= settings_url("users") ?>">Users</a></li>
                            <li id="userPermission_nav"><a class="menu-item" href="<?= settings_url("usersPermission") ?>">Users Permission</a></li>
                            <li id="timeStation_nav"><a class="menu-item" href="<?= timeStation_url() ?>">Time Station</a></li>
                            <li id="emailTemplate_nav"><a class="menu-item" href="<?= settings_url("emailTemplate") ?>">Email Template</a></li>
                            
                            <?php
                            // if ($_SESSION["user"]->position == "1") {
                            ?>
                            <li id="userLog_nav"><a class="menu-item" href="<?= settings_url("userLog") ?>">User Log</a></li>
                            <?php
                            //  }
                            ?>                                
                        </ul>
                    </li>
                    <?php
                }
                ?>
            </ul>
        </div>
    </div>
    <?php
}
if (isset($navMeta["active"])) {
    ?>

    <script>
        if (document.getElementById("<?= $navMeta["active"] ?>_nav")) {
            document.getElementById("<?= $navMeta["active"] ?>_nav").className += " active is-shown ";
        }
    </script>
    <?php
}
?>


<div class="app-content content">
    <div class="content-wrapper p-1">
        <?php
        if (!$navMeta["hideContentHeader"]) {
            ?>
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-12 mb-1 text-md-left text-center">
                    <h3 class="content-header-title mb-0 text-center text-md-left"><?= $navMeta["pageTitle"] ?></h3>
                    <div class="row breadcrumbs-top">
                        <div class="breadcrumb-wrapper col-12 text-center text-md-left">
                            <ol class="breadcrumb justify-content-md-start justify-content-center">
                                <?php
                                $navMeta["n"] = 1;
                                foreach ($navMeta["bc"] as $bc) {
                                    ?>
                                    <li class="breadcrumb-item <?= $navMeta["n"] == sizeof($navMeta["bc"]) ? "active" : "" ?>">

                                        <?php
                                        if ($bc["url"] && isset($bc["url"])) {
                                            ?>
                                            <a href="<?= $bc["url"] ?>"><?= $bc["page"] ?></a>
                                            <?php
                                        } else {
                                            ?><?= $bc["page"] ?>
                                            <?php
                                        }
                                        ?>
                                    </li>
                                    <?php
                                    $navMeta["n"] ++;
                                }
                                ?>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="content-header-right col-md-6 col-12 mb-1 text-md-right text-center" id="nav-right-container"></div>
            </div>
            <?php
        }
        if ($navBarSettings["topAlert"]) {

            if (isset($_SESSION["altMsg"])) {
                ?>
                <div class="alert brt-alert text-center alert-dismissible alert-<?= isset($_SESSION["altMsgType"]) ? $_SESSION["altMsgType"] : "info" ?>">
                    <?= $_SESSION["altMsg"] ?>
                </div>
                <?php
                unset($_SESSION["altMsg"]);
            }
        }
        if ($navBarSettings["topBar"]) {
            ?>
            <div class="content-body bg-white p-1">
                <?php
            }
            ?>