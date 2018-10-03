<div id="<?= $folio ?>salesHistoryButton" class="menu"><img src="<?= property_url("images/fancybox_loading@2x.gif") ?>"></div>
<script>
    var $divID = $("#<?= $folio ?>salesHistoryButton");

    var dat = JSON.parse('<?= $data ?>');
    // console.log(dat);
    $divID.html("");
    var salesHistorys = dat.Data;
    salesHistorys.forEach(function (salesHistory) {
        var href = salesHistory.OrBookPageUrl;
        var a = "<a class='menu-item' target='_blank' href='" + href + "' >" + moment(salesHistory.SalesDate, "MM/DD/YYYY").format("DD MMM, YYYY") + "</a><br>";
        $divID.append(a);
    });

</script>