<?php
/**
 * Author S Brinta<brrinta@gmail.com>
 * Email: brrinta@gmail.com
 * Web: https://brinta.me
 * Do not edit file without permission of author
 * All right reserved by S Brinta<brrinta@gmail.com>
 * Created on : Apr 30, 2018, 5:04:20 PM
 */
?>
<div id="position-top-full">
    <?php
    if (hasPermission("dashboard/stats")) {
        ?>
        <div class="row mb-2">        
            <div class="col-12 text-center">
                <h3 class="h3 text-bold-600"> Stats</h3>
            </div>
            <div class="col-md-3 col-6 my-1">            
                <div class="bg-gradient-directional-success text-white text-center p-2">
                    Total Charged<br>
                    <strong><?= $stats["totalCharged"] ? $stats["totalCharged"] : 0 ?></strong>
                </div>            
            </div>
            <div class="col-md-3 col-6 my-1">            
                <div class="bg-gradient-directional-success text-white text-center p-2">
                    Total Credit Card<br>
                    <strong><?= $stats["totalCreditCard"] ? $stats["totalCreditCard"] : 0 ?></strong>
                </div>            
            </div>
            <div class="col-md-3 col-6 my-1">            
                <div class="bg-gradient-directional-success text-white text-center p-2">
                    Total Check<br>
                    <strong><?= $stats["totalCheck"] ? $stats["totalCheck"] : 0 ?></strong>
                </div>            
            </div>
            <div class="col-md-3 col-6 my-1">            
                <div class="bg-gradient-directional-success text-white text-center p-2">
                    Total Received<br>
                    <strong><?= $stats["totalReceived"] ? $stats["totalReceived"] : 0 ?></strong>
                </div>            
            </div>
            <div class="col-md-3 col-6 my-1">            
                <div class="bg-gradient-directional-success text-white text-center p-2">
                    Total Sent<br>
                    <strong><?= $stats["totalSent"] ? $stats["totalSent"] : 0 ?></strong>
                </div>            
            </div>
            <div class="col-md-3 col-6 my-1">            
                <div class="bg-gradient-directional-success text-white text-center p-2">
                    Loss %<br>
                    <strong><?= $stats["lossPercent"] ? $stats["lossPercent"] : 0 ?></strong>
                </div>            
            </div>
            <div class="col-md-3 col-6 my-1">            
                <div class="bg-gradient-directional-success text-white text-center p-2">
                    Total Expenses<br>
                    <strong><?= $stats["totalExpense"] ? $stats["totalExpense"] : 0 ?></strong>
                </div>            
            </div>
            <div class="col-md-3 col-6 my-1">            
                <div class="bg-gradient-directional-success text-white text-center p-2">
                    Net<br>
                    <strong>No Data</strong>
                </div>            
            </div>
        </div>
        <?php
    }
    ?>
    <div class="row mb-2">        
        <div class="col-12 text-center">
            <h3 class="h3 text-bold-600"> Link</h3>
        </div>
        <?php
        if (hasPermission("brm")) {
            ?>
            <div class="col-md-3 col-6 my-1">
                <a href="<?= dashboard_url("brm") ?>">
                    <div class="bg-gradient-directional-blue-grey text-white text-center p-2">
                        <i class="ft-star fa-3x"></i><br>
                        <strong>BRM</strong>
                    </div>
                </a>
            </div>

            <?php
        }
        ?>
        <?php
        if (hasPermission("mailing")) {
            ?>
            <div class="col-md-3 col-6 my-1">
                <a href="<?= dashboard_url("mailing") ?>">
                    <div class="bg-gradient-directional-blue-grey text-white text-center p-2">
                        <i class="ft-mail fa-3x"></i><br>
                        <strong>Mailing</strong>
                    </div>
                </a>
            </div>

            <?php
        }
        ?>
        <?php
        if (hasPermission("expenses")) {
            ?>
            <div class="col-md-3 col-6 my-1">
                <a href="<?= dashboard_url("expense") ?>">
                    <div class="bg-gradient-directional-blue-grey text-white text-center p-2">
                        <i class="ft-tag fa-3x"></i><br>
                        <strong>Expense</strong>
                    </div>
                </a>
            </div>

            <?php
        }
        ?>
        <?php
        if (true) {
            ?>
            <div class="col-md-3 col-6 my-1">
                <a href="<?= dashboard_url("timeStation") ?>">
                    <div class="bg-gradient-directional-blue-grey text-white text-center p-2">
                        <i class="ft-clock fa-3x"></i><br>
                        <strong>Time Station</strong>
                    </div>
                </a>
            </div>

            <?php
        }
        ?>
        <div class="col-md-3 col-6 my-1">
            <a class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                <div class="bg-gradient-directional-amber text-white text-center p-2">
                    <i class="ft-map fa-3x"></i><br>
                    <strong>State</strong>
                </div>
            </a>
            <div class="dropdown-menu" x-placement="bottom-start">        
                <?php
                if (hasPermission("prospects/add")) {
                    ?>
                    <div class="dropdown-submenu">
                        <button class="dropdown-item" type="button"><strong>New Prospect</strong></button>
                        <div class="dropdown-menu arrow-left" role="menu">
                            <?php
                            foreach ($__stateList as $short => $state) {
                                if (strlen($short) === 2) {
                                    ?>
                                    <a class="dropdown-item" modal-toggler="true" data-target="#remoteModal1" data-remote="<?= dashboard_url("newProspects/" . $short) ?>">
                                        <strong><?= $state ?></strong>
                                    </a>
                                    <?php
                                }
                            }
                            ?>    
                        </div>
                    </div>
                    <div class="dropdown-divider"></div>
                    <?php
                }
                if (hasPermission("orders/add")) {
                    ?>
                    <div class="dropdown-submenu">
                        <button class="dropdown-item" type="button"><strong>New Payment</strong></button>
                        <div class="dropdown-menu arrow-left" role="menu">
                            <?php
                            foreach ($__stateList as $short => $state) {
                                if (strlen($short) === 2) {
                                    ?>
                                    <a class="dropdown-item" modal-toggler="true" data-target="#remoteModal1" data-remote="<?= dashboard_url("newPayment/" . $short) ?>">
                                        <strong><?= $state ?></strong>
                                    </a>
                                    <?php
                                }
                            }
                            ?>    
                        </div>
                    </div>
                    <div class="dropdown-divider"></div>
                    <?php
                }
                ?>
                <?php
                foreach ($__stateList as $short => $state) {
                    if (strlen($short) === 2) {
                        ?>
                        <a class="dropdown-item" href="<?= prospects_url($short) ?>">
                            <strong><?= $state ?></strong>
                        </a>
                        <?php
                    }
                }
                ?>    
            </div>
        </div>
        <div class="col-md-3 col-6 my-1">
            <a class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                <div class="bg-gradient-directional-cyan text-white text-center p-2">
                    <i class="ft-map fa-3x"></i><br>
                    <strong>R State</strong>
                </div>
            </a>
            <div class="dropdown-menu" x-placement="bottom-start">        
                <?php
                if (hasPermission("prospects/add")) {
                    ?>
                    <div class="dropdown-submenu">
                        <button class="dropdown-item" type="button"><strong>New Prospect</strong></button>
                        <div class="dropdown-menu arrow-left" role="menu">
                            <?php
                            foreach ($__stateList as $short => $state) {
                                if (strlen($short) === 3) {
                                    ?>
                                    <a class="dropdown-item" modal-toggler="true" data-target="#remoteModal1" data-remote="<?= dashboard_url("newProspects/" . $short) ?>">
                                        <strong><?= $state ?></strong>
                                    </a>
                                    <?php
                                }
                            }
                            ?>    
                        </div>
                    </div>
                    <div class="dropdown-divider"></div>
                    <?php
                }
                if (hasPermission("orders/add")) {
                    ?>
                    <div class="dropdown-submenu">
                        <button class="dropdown-item" type="button"><strong>New Payment</strong></button>
                        <div class="dropdown-menu arrow-left" role="menu">
                            <?php
                            foreach ($__stateList as $short => $state) {
                                if (strlen($short) === 3) {
                                    ?>
                                    <a class="dropdown-item" modal-toggler="true" data-target="#remoteModal1" data-remote="<?= dashboard_url("newPayment/" . $short) ?>">
                                        <strong><?= $state ?></strong>
                                    </a>
                                    <?php
                                }
                            }
                            ?>    
                        </div>
                    </div>
                    <div class="dropdown-divider"></div>
                    <?php
                }
                ?>
                <?php
                foreach ($__stateList as $short => $state) {
                    if (strlen($short) === 3) {
                        ?>
                        <a class="dropdown-item" href="<?= prospects_url($short) ?>">
                            <strong><?= $state ?></strong>
                        </a>
                        <?php
                    }
                }
                ?>    
            </div>
        </div>
        <?php
        if (hasPermission(TAB_fictitious)) {
            ?>
            <div class="col-md-3 col-6 my-1">
                <a class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <div class="bg-gradient-directional-blue-grey text-white text-center p-2">
                        <i class="ft-map fa-3x"></i><br>
                        <strong>Fictitious</strong>
                    </div>
                </a>
                <div class="dropdown-menu" x-placement="bottom-start"> 
                    <?php if (hasPermission("fictitious/add")) { ?>
                        <a class="dropdown-item" modal-toggler="true" data-target="#remoteModal1" data-remote="<?= dashboard_url("newFictitious") ?>">
                            <strong>New Fictitious</strong>
                        </a>
                        <?php } if (hasPermission("fictitious/add")) { ?>
                        <a class="dropdown-item" modal-toggler="true" data-target="#remoteModal1" data-remote="<?= dashboard_url("newFictitiousPayment") ?>">
                            <strong>New Payment</strong>
                        </a>
                    <?php } ?>
                    <a class="dropdown-item" href="<?= dashboard_url(TAB_fictitious) ?>">                    
                        <strong>Fictitious</strong>
                    </a>
                </div>
            </div>
            <?php
        }
        ?>
        
        
        
        
    </div>       
    <div class="row">        
        <div class="col-12 text-center">
            <h3 class="h3 text-bold-600">  State Details</h3>
        </div>
        <?php
        foreach (getState() as $ab => $sc) {
            // if (strlen($ab) === 2) {
            ?>
            <div class="col-md-4 col-6 my-1">
                <div class="bg-gradient-directional-teal text-white text-center">
                    <strong><?= $sc ?></strong><br>
                    Prospects: <?= isset($prospectsCount[$ab]) ? $prospectsCount[$ab] : 0 ?><br>
                    PreSort in this Month: <?= isset($prospectsMonthCount[$ab]) ? $prospectsMonthCount[$ab] : 0 ?><br>
                    Orders : <?= isset($orderCount[$ab]) ? $orderCount[$ab] : 0 ?><br>
                    Orders in this Month:<?= isset($orderMonthCount[$ab]) ? $orderMonthCount[$ab] : 0 ?><br>
                    Customers: <?= isset($customerCount[$ab]) ? $customerCount[$ab] : 0 ?><br>
                    Customers in this Month: <?= isset($customerMonthCount[$ab]) ? $customerMonthCount[$ab] : 0 ?><br>                    
                </div>

            </div>
            <?php
            // }
        }
        ?>
    </div>    
</div>
