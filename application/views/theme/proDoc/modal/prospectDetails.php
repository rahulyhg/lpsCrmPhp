<?php
/**
 * Author S Brinta<brrinta@gmail.com>
 * Email: brrinta@gmail.com
 * Web: https://brinta.me
 * Do not edit file without permission of author
 * All right reserved by S Brinta<brrinta@gmail.com>
 * Created on : Mar 30, 2018, 7:30:04 PM
 */
?><div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="newEventMoalLebel"><strong><?= getProDocState($_currentState) ?> <?= $prospect ? "-" . $prospect->contactID : "" ?></strong> Prospects Details</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body text-center">                     
            <?php
            if (!$prospect) {
                ?>
                <h1>No prospect details found!</h1>
                <?php
            } else {
                ?>
                <table class="table table-striped table-bordered">
                    <?php
                    foreach (getProDocStateColumnsShow($_currentState) as $cell => $column) {
                        ?>
                        <tr>
                            <th><?= $column ?></th>
                            <td><?= $prospect->$cell ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
            <?php }
            ?>
        </div>
    </div>            
</div>

<script type="text/javascript">

</script>