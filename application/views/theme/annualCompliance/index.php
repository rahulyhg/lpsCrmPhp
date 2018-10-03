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
    <div class="row mb-2">       

        <?php
        if (hasPermission("prospects/add")) {
            ?>
            <div class="col-md-3 col-6 my-1">
                <a class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <div class="bg-gradient-directional-blue text-white text-center p-2">
                        <i class="ft-map fa-3x"></i><br>
                        <strong>New Prospects</strong>
                    </div>
                </a>
                <div class="dropdown-menu" x-placement="bottom-start">
                    <?php
                    foreach (getACState() as $short => $state) {
                        ?>
                        <a class="dropdown-item" modal-toggler="true" data-target="#remoteModal1" data-remote="<?= annualCompliance_url("newProspects/" . $short) ?>">
                            <strong><?= $state ?></strong>
                        </a>
                        <?php
                    }
                    ?>    
                </div>
            </div>
        <?php }
        ?>        
        <?php
        if (hasPermission("orders/add")) {
            ?>
            <div class="col-md-3 col-6 my-1">
                <a class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <div class="bg-gradient-directional-blue text-white text-center p-2">
                        <i class="ft-map fa-3x"></i><br>
                        <strong>New Payment</strong>
                    </div>
                </a>
                <div class="dropdown-menu" x-placement="bottom-start">
                    <?php
                    foreach (getACState() as $short => $state) {
                        ?>
                        <a class="dropdown-item" modal-toggler="true" data-target="#remoteModal1" data-remote="<?= annualCompliance_url("newPayment/" . $short) ?>">
                            <strong><?= $state ?></strong>
                        </a>
                        <?php
                    }
                    ?>    
                </div>
            </div>
        <?php }
        ?>
        <?php
        if (false) {
            ?>
            <div class="col-md-3 col-6 my-1">
                <a href="<?= annualCompliance_url("brm") ?>">
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
        foreach (getACState() as $short => $state) {
            ?>
           <div class="col-md-3 col-6 my-1">
                    <a class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <div class="bg-gradient-directional-amber text-white text-center p-2">
                            <i class="ft-map fa-3x"></i><br>
                            <strong><?= $state ?></strong>
                        </div>
                    </a>
                    <div class="dropdown-menu" x-placement="bottom-start">

                        <a class="dropdown-item" href="<?= annualCompliance_url("prospects/".$short) ?>"><?= $short ?> Prospects</a>
                        <a class="dropdown-item" href="<?= annualCompliance_url("orders/" . $short) ?>"><?= $short ?> Orders</a>
                        <a class="dropdown-item" href="<?= annualCompliance_url("customers/" . $short) ?>"><?= $short ?> Customers</a>
                        <a class="dropdown-item" href="<?= annualCompliance_url("refunds/" . $short) ?>"><?= $short ?> Refunds</a>

                    </div>
                </div>              
            <?php
        }
        ?>       
    </div>
</div>
