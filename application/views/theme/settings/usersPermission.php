<?php
/**
 * Author S Brinta<brrinta@gmail.com>
 * Email: brrinta@gmail.com
 * Web: https://brinta.me
 * Do not edit file without permission of author
 * All right reserved by S Brinta<brrinta@gmail.com>
 * Created on : May 12, 2018, 2:54:35 PM
 */
?>
<style>
    .gty{

    }
</style>
<div class="row">
    <div class="col text-center">
        <button class="btn btn-primary square" modal-toggler="true" data-target="#remoteModal1" data-remote="<?= settings_url("newUserType") ?>"><i class="ft-plus-square"></i> New User Type</button>
    </div>
</div>
<div class="row">
    <div class="col text-center">
        <h1>User Type</h1>
        <table class="table table-striped table-bordered serverSide-table dtr-inline text-left">
            <thead>
                <tr>
                    <th>User Position</th>
                    <th>Permission</th>
                    <th>White Listed IP</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($userPermissions as $up) {
                    ?>
                    <tr>
                        <th><?= $up->userType ?></th>
                        <td><?php
                            $permissions = json_decode($up->permissions);
                            foreach (getPermissionDetails() as $pc => $fields) {
                                echo "<strong class='success'>" . strtoupper($pc) . "</strong>: ";
                                $pers = isset($permissions->$pc) ? $permissions->$pc : false;
                                if ($pers) {
                                    foreach ($pers as $per) {
                                        $permission = (array) $per;
                                        echo "<b class='text-danger'>" . ucfirst(key($permission)) . "</b> : " . ($permission[key($permission)] ? "Yes" : "No");
                                        echo '  ';
                                    }
                                } else {
                                    echo "<b class='text-danger'>All </b> :" . "No";
                                }
                                echo "<br>";
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            $whiteList = $up->whiteListedIP;
                            if (strlen($whiteList) < 2) {
                                echo "All IP!";
                            } else {
                                foreach (explode(";", $whiteList) as $ip) {
                                    echo $ip, '<br>';
                                }
                            }
                            ?>
                        </td>
                        <td>
                            <button class="btn square btn-transparent" modal-toggler="true" data-target="#remoteModal1" data-remote="<?= settings_url("editUserType/" . $up->id) ?>"><i class="fa fa-edit"></i></button>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>    
        </table>
    </div>
</div>
<script>
    window.onload = function () {

    };
</script>