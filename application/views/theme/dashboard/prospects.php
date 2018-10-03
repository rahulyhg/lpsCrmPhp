<?php
/*
 * Author S. Brinta <brrinta@gmail.com>
 * Email: i@brinta.me
 * Web: https://brinta.me
 * Do not edit file without permission of author
 * All right reserved by S. Brinta <brrinta@gmail.com>
 * Created on : Jun 22, 2018 , 6:10:44 PM
 */
?>
<div id="content-nav-right">
    <div class="btn-group float-md-right" role="group">
        <div class="btn-group" role="group">
            <button class="btn btn-outline-blue-grey dropdown-toggle dropdown-menu-right" id="btnGroupDrop1" type="button" data-toggle="dropdown" aria-haspopup="true">
                Prospects</button>
            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1" x-placement="bottom-start">
                <?php
                if (hasPermission("orders")) {
                    ?>
                    <a class="dropdown-item" href="<?= dashboard_url("orders/" . $_currentState) ?>">Orders</a>
                    <?php
                }
                if (hasPermission("customers")) {
                    ?>
                    <a class="dropdown-item" href="<?= dashboard_url("customers/" . $_currentState) ?>">Customers</a>
                    <?php
                }if (hasPermission("refunds")) {
                    ?>
                    <a class="dropdown-item" href="<?= dashboard_url("refunds/" . $_currentState) ?>">Refunds</a>
                    <?php
                }
                ?>
            </div>
        </div>
        <?php
        if (hasPermission("prospects/add")) {
            ?>
            <a class="btn btn-outline-blue-grey" modal-toggler="true" data-target="#remoteModal1" data-remote="<?= dashboard_url("newProspects/" . $_currentState) ?>">New Prospects</a>
            <?php
        }
        if (hasPermission("orders/add")) {
            ?>
            <a class="btn btn-outline-blue-grey" modal-toggler="true" data-target="#remoteModal1" data-remote="<?= dashboard_url("newPayment/" . $_currentState) ?>">New Payment</a>
            <?php
        }
        ?>
    </div>
</div>
<!----------------------------------------------------------------------------------------------------------------------->
<style>
    .pointer{
        cursor: pointer;
    }
</style>


<form class="row">
    <div class="form-group col-md-auto col-12">
        <select name="length" class="form-control" title="Item per page" id="length">                
            <option value="25" <?= isset($_GET["length"]) ? ($_GET["length"] === "25" ? "selected" : "") : "" ?>>25</option>
            <option value="50" <?= isset($_GET["length"]) ? ($_GET["length"] === "50" ? "selected" : "") : "" ?>>50</option>
            <option value="100" <?= isset($_GET["length"]) ? ($_GET["length"] === "100" ? "selected" : "") : "" ?>>100</option>
            <option value="200" <?= isset($_GET["length"]) ? ($_GET["length"] === "200" ? "selected" : "") : "" ?>>200</option>
            <option value="500" <?= isset($_GET["length"]) ? ($_GET["length"] === "500" ? "selected" : "") : "" ?>>500</option>
            <option value="1000" <?= isset($_GET["length"]) ? ($_GET["length"] === "1000" ? "selected" : "") : "" ?>>1,000</option>
<!--                <option value="0" <?= isset($_GET["length"]) ? ($_GET["length"] === "0" ? "selected" : "") : "" ?>>All</option>-->
        </select>
    </div>
    <div class="form-group col-md-auto col-12">            
        <select class="form-control" id="searchIn" name="searchIn" onchange="changeOnPreSortDate()">
            <?php
            foreach (getStateColumns($_currentState) as $cell => $column) {
                if (!isset($_GET["searchIn"])) {
                    if ($cell === "company") {
                        ?>
                        <option selected value="<?= $cell ?>"><?= $column ?></option>
                        <?php
                    } else {
                        ?>
                        <option value="<?= $cell ?>"><?= $column ?></option>
                        <?php
                    }
                } else {
                    if ($_GET["searchIn"] === $cell) {
                        ?>
                        <option selected value="<?= $cell ?>"><?= $column ?></option>
                        <?php
                    } else {
                        ?>
                        <option value="<?= $cell ?>"><?= $column ?></option>
                        <?php
                    }
                }
            }
            ?>
        </select>
    </div>
    <div class="form-group col-md col-12 ifNotPreSortDate">            
        <input type="text"  class="form-control" id="searchText" name="searchText" required placeholder="Search..."
               value="<?= isset($_GET["searchText"]) ? $_GET["searchText"] : "" ?>">
    </div>
    <div class="form-group col-md col-6 ifPreSortDate">
        <input type="text" class="form-control selectedDate" id="searchText1" name="searchText" required placeholder="From Date"
               value="<?= isset($_GET["searchText"]) ? $_GET["searchText"] : "" ?>">
    </div>
    <div class="form-group col-md col-6 ifPreSortDate">
        <input type="text" class="form-control selectedDate" id="searchText2" name="searchText1" required placeholder="To Date"
               value="<?= isset($_GET["searchText1"]) ? $_GET["searchText1"] : "" ?>">
    </div>
    <div class="form-group col-md-auto col-12">
        <button type="submit" class="btn btn-primary mb-2"><i class="ft-search"></i> Search</button>
    </div>

</form>

<?php
if (isset($results)) {
    //dnp($results["query"]);
    ?>
    <div class="row justify-content-center text-center">
        <div class="col-12">
            <h2 class="h2"><?= $results["resultSetCount"] ?> Entry found</h2>
        </div>
        <nav aria-label="Page navigation">
            <ul class="pagination" id="pagination" 
                data-currentpage="<?= isset($_GET["currentPage"]) ? $_GET["currentPage"] : "1" ?>"
                data-totalresult="<?= $results["resultSetCount"] ?>"
                data-searchtext="<?= isset($_GET["searchText"]) ? $_GET["searchText"] : "" ?>"
                data-searchtext1="<?= isset($_GET["searchText1"]) ? $_GET["searchText1"] : "" ?>"
                data-searchin="<?= isset($_GET["searchIn"]) ? $_GET["searchIn"] : "" ?>"
                ></ul>
        </nav>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <?php
                            foreach (getStateColumns($_currentState) as $column) {
                                ?>
                                <th class="text-center"><?= $column ?><br></th>
                                <?php
                            }
                            ?>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($results["resultSet"] as $result) {
                            $extra = "";
                            $dlt = "<p>Are you sure?</p><a class='btn btn-danger po-delete btn-sm p-1 rounded-0' href='" . dashboard_url('delete/' . TAB_prospects . '/' . $result->id) . "'>I am sure</a> <button class='btn pop-close btn-sm rounded-0 p-1'>No</button>";
                            if (hasPermission(TAB_prospects . "/edit")) {
                                $extra .= "<button data-remote='" . dashboard_url('editProspect/' . $result->id) . "' class='btn btn-link p-0 px-1' modal-toggler='true' data-target='#remoteModal1'><i class=\"fa fa-edit\"></i></button>";
                            }
                            if (hasPermission(TAB_prospects . "/delete")) {
                                $extra .= '<button type="button" class="btn btn-link p-0 px-1" data-container="body" data-toggle="popover" data-placement="left" data-html="true" data-content="' . $dlt . '"><i class="fa fa-trash"></i></button>';
                            }
                            $actions = "<div class=\"text-center\">"
                                    . $extra
                                    . "</div>";
                            ?>
                            <tr>      
                                <?php
                                foreach (getStateColumns($_currentState) as $cell => $column) {
                                    if ($cell === "preSortDate") {
                                        ?>
                                        <td><?= changeDateFormatToLong($result->$cell) ?></th>
                                            <?php
                                        } elseif ($cell === "contactID") {
                                            ?>
                                        <td class="onclickContactID pointer" data-prospectID="<?= $result->id ?>"><?= $result->$cell ?></th>
                                            <?php
                                        } else {
                                            ?>
                                        <td><?= $result->$cell ?></th>
                                            <?php
                                        }
                                    }
                                    ?>
                                <th><?= $actions ?></th>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        function pagein() {
            var $pagination = $("#pagination");
            var lengthSelector = $("#length").val();
            var showLength = parseInt(lengthSelector);
            var searchText = $pagination.data("searchtext");
            var searchText1 = $pagination.data("searchtext1");
            var searchIn = $pagination.data("searchin");
            var currentPage = $pagination.data("currentpage");
            if (showLength > 0) {
                var totalResult = parseInt($pagination.data("totalresult"));
                var $pagi = $('#pagination').twbsPagination({
                    totalPages: Math.ceil(totalResult / parseInt(showLength)),
                    startPage: currentPage,
                    visiblePages: 10
                            /* onPageClick: function (event, page) {
                             // fire onn load
                             }*/
                });
                $pagi.on('page', function (evt, page) {
                    var url = "<?= dashboard_url("prospects/" . $_currentState . "?") ?>length=" + showLength + "&searchIn=" + searchIn + "&searchText=" + searchText + "&currentPage=" + page;
                    url = url + (searchText1 ? "&searchText1=" + searchText1 : "");
                    //console.log(url);
                    open(url, "_self");
                });
            }
        }

    </script>
    <?php
    //  dnp($results);
}
?>

<script>
    window.onload = function () {
        changeOnPreSortDate();
<?php
if (isset($results)) {
    ?>
            pagein();
    <?php
}
?>
        $('.onclickContactID').on('click', function () {
            var id = $(this).attr("data-prospectID");            
            $('.loader').show();
            var $_modalID = "#remoteModal1";
            $($_modalID).load("<?= dashboard_url("newPayment/" . $_currentState . "?prospectID=") ?>" + id, function (e) {
                setTimeout(function (e) {
                    $($_modalID).modal("show");
                    $('.loader').hide();
                }, 1500);
            });
        });
    };
    function changeOnPreSortDate() {
        var $searchIn = $("#searchIn").val();
        if ($searchIn === "preSortDate") {
            $("#searchText").attr("disabled", true);
            $(".ifPreSortDate").show();
            $(".ifNotPreSortDate").hide();
            $("#searchText1").attr("disabled", false);
            $("#searchText2").attr("disabled", false);
        } else {
            $("#searchText").attr("disabled", false);
            $(".ifPreSortDate").hide();
            $(".ifNotPreSortDate").show();
            $("#searchText1").attr("disabled", true);
            $("#searchText2").attr("disabled", true);
        }
        $(".selectedDate").datetimepicker({
            format: 'DD MMM, YYYY'
        });
    }
</script>