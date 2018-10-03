<?php
/**
 * Author S Brinta<brrinta@gmail.com>
 * Email: brrinta@gmail.com
 * Web: https://brinta.me
 * Do not edit file without permission of author
 * All right reserved by S Brinta<brrinta@gmail.com>
 * Created on : Sep 29, 2018, 2:54:35 PM
 */
?>
<style>
</style>
<div id="content-nav-right">
    <div class="btn-group float-md-right" role="group">
        <div class="btn-group" role="group">
            <a class="btn btn-outline-blue-grey" modal-toggler="true" data-target="#remoteModal1" data-remote="<?= settings_url("addTemplate") ?>">Add Template</a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col table-responsive">        
        <table class="table table-striped table-bordered serverSide-table">
            <thead>
                <tr>
                    <th>Purpose</th>
                    <th style="width: 70px;">Active</th>
                    <th style="width: 120px;">Sender</th>
                    <th>Template</th>
                    <th style="width: 70px;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($templates as $tem) {
                    $dlt = "<p>Are you sure?</p><a class='btn btn-danger po-delete btn-sm p-1 rounded-0' href='" . settings_url('delete/' . TAB_emailTemplates . '/' . $tem->id) . "'>I am sure</a> <button class='btn pop-close btn-sm rounded-0 p-1'>No</button>";
                    ?>
                    <tr>
                        <td><?= getPurposes($tem->purpose) ?></td>
                        <td style="width: 70px;"><?= $tem->active ? "Yes" : "No" ?></td>
                        <td style="width: 120px;"><?= $tem->senderEmail ?></td>
                        <td><?= str_replace("\n", "<br>", $tem->template) ?></td>
                        <td style="width: 70px;">
                            <button data-remote="<?= settings_url('editTemplate/' . $tem->id) ?>" 
                                    class='btn btn-link p-0 px-1' modal-toggler='true' data-target='#remoteModal1'>
                                <i class="fa fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-link p-0 px-1" data-container="body" data-toggle="popover" data-placement="left" data-html="true" data-content="<?= $dlt ?>"><i class="fa fa-trash"></i></button>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>    
        </table>
    </div>
</div>
