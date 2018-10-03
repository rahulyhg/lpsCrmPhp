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
                    <strong><?= number_format($stats["totalCreditCard"] ? $stats["totalCreditCard"] : 0, 2) ?></strong>
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
                    <strong>$<?= number_format($stats["totalExpense"] ? $stats["totalExpense"] : 0, 2) ?></strong>
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
        if (hasPermission("deposits")) {
            ?>
            <div class="col-md-3 col-6 my-1">
                <a href="<?= deposits_url() ?>">
                    <div class="bg-gradient-directional-blue-grey text-white text-center p-2">
                        <i class="ft-airplay fa-3x"></i><br>
                        <strong>Deposits</strong>
                    </div>
                </a>
            </div>

            <?php
        }
        ?>
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
                <a href="<?= timestation_url() ?>">
                    <div class="bg-gradient-directional-blue-grey text-white text-center p-2">
                        <i class="ft-clock fa-3x"></i><br>
                        <strong>Time Station</strong>
                    </div>
                </a>
            </div>

            <?php
        }
        ?>
        <?php
        if (hasPermission(TAB_jobs)) {
            ?>
            <div class="col-md-3 col-6 my-1">
                <a href="<?= jobs_url() ?>">
                    <div class="bg-gradient-directional-blue-grey text-white text-center p-2">
                        <i class="icon-basket fa-3x"></i><br>
                        <strong>Active Jobs</strong>
                    </div>
                </a>                
            </div>
            <?php
        }
        ?>
        <div class="col-md-3 col-6 my-1">
            <a href="<?= dashboard_url("states") ?>">
                <div class="bg-gradient-directional-amber text-white text-center p-2">
                    <i class="ft-map fa-3x"></i><br>
                    <strong>State</strong>
                </div>
            </a>          
        </div>
        <div class="col-md-3 col-6 my-1">
            <a href="<?= dashboard_url("rStates") ?>">
                <div class="bg-gradient-directional-cyan text-white text-center p-2">
                    <i class="ft-map fa-3x"></i><br>
                    <strong>R State</strong>
                </div>
            </a>            
        </div>

        <?php
        if (hasPermission(TAB_fictitious)) {
            ?>
            <div class="col-md-3 col-6 my-1">
                <a href="<?= dashboard_url(TAB_fictitious) ?>">
                    <div class="bg-gradient-directional-blue-grey text-white text-center p-2">
                        <i class="ft-map fa-3x"></i><br>
                        <strong>Fictitious</strong>
                    </div>
                </a>                
            </div>
            <?php
        }
        ?>
        <?php
        if (hasPermission("annualCompliance")) {
            ?>
            <div class="col-md-3 col-6 my-1">
                <a href="<?= annualCompliance_url() ?>">
                    <div class="bg-red text-white text-center p-2">
                        <i class="ft-anchor fa-3x"></i><br>
                        <strong>Annual Compliance</strong>
                    </div>
                </a>                
            </div>
            <?php
        }
        ?>

        <?php
        if (hasPermission("proDoc")) {
            ?>
            <div class="col-md-3 col-6 my-1">
                <a href="<?= proDoc_url() ?>">
                    <div class="bg-blue text-white text-center p-2">
                        <i class="fa-building-o fa fa-3x"></i><br>
                        <strong>Property Document</strong>
                    </div>
                </a>                
            </div>
            <?php
        }
        ?>
        <?php
        
        if (hasPermission("reports/general")) {
            
            ?>
            <div class="col-md-3 col-6 my-1">
                <a href="<?= reports_url() ?>">
                    <div class="bg-bitbucket text-white text-center p-2">
                        <i class="ft-book fa-3x"></i><br>
                        <strong>Reports</strong>
                    </div>
                </a>                
            </div>
            <?php
        }
        ?>



    </div>       
    <?php
    if (hasPermission("dashboard/details")) {
        ?>
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
        <?php
    }
    ?>
</div>
