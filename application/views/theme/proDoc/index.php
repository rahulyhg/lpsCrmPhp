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
        if (hasPermission("proDoc/add")) {
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
                    foreach (getProDocState() as $short => $state) {
                        ?>
                        <a class="dropdown-item" modal-toggler="true" data-target="#remoteModal1" 
                           data-remote="<?= proDoc_url("newProspects/" . $short) ?>">
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
        if (hasPermission("proDoc/orderAdd")) {
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
                    foreach (getProDocState() as $short => $state) {
                        ?>
                        <a class="dropdown-item" modal-toggler="true" data-target="#remoteModal1" data-remote="<?= proDoc_url("newPayment/" . $short) ?>">
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
        foreach (getProDocState() as $short => $state) {
            ?>
            <div class="col-md-3 col-6 my-1">
                <a class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <div class="bg-gradient-directional-amber text-white text-center p-2">
                        <i class="ft-map fa-3x"></i><br>
                        <strong><?= $state ?></strong>
                    </div>
                </a>
                <div class="dropdown-menu" x-placement="bottom-start">
                    <?php
                    if (hasPermission("proDoc")) {
                        ?>
                        <a class="dropdown-item" href="<?= proDoc_url("prospects/" . $short) ?>"><?= $short ?> Prospects</a>
                        <?php
                    }
                    if (hasPermission("proDoc/orders")) {
                        ?>
                        <a class="dropdown-item" href="<?= proDoc_url("orders/" . $short) ?>"><?= $short ?> Orders</a>
                        <?php
                    }
                    if (hasPermission("proDoc/customers")) {
                        ?>
                        <a class="dropdown-item" href="<?= proDoc_url("customers/" . $short) ?>"><?= $short ?> Customers</a>
                        <?php
                    }
                    if (hasPermission("proDoc/refunds")) {
                        ?>
                        <a class="dropdown-item" href="<?= proDoc_url("refunds/" . $short) ?>"><?= $short ?> Refunds</a>
                        <?php
                    }
                    ?>
                </div>

            </div>
            <?php
        }
        ?>       
    </div>
</div>
