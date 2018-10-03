<div id="<?= $folio ?>salesHistoryButton" class="menu"><img src="<?= property_url("images/fancybox_loading@2x.gif")?>"></div>
<script>
    var urls = {
        bookAndPage: "http://pubrec3.hillsclerk.com/oncore/showdetails.aspx?BookType=",
        BasicSearch: "https://gis.hcpafl.org/PropertySearch/services/Search/BasicSearch",
        ParcelData: "https://gis.hcpafl.org/PropertySearch/services/Search/ParcelData"
    };
    var $divID = $("#<?= $folio ?>salesHistoryButton");
    jQuery.getJSON(
            urls.BasicSearch,
            {
                folio: "<?= $folio ?>",
                page: 1,
                pagesize: 40
            },
            function (data, t, j) {
                var prospect = data[0];
                getHilSalesHis(prospect.pin);
            });
    function getHilSalesHis(pin) {
       // $divID.html("");
        jQuery.getJSON(
                urls.ParcelData,
                {pin: pin},
                function (data, t, j) {
                    $divID.html("");
                    var salesHistorys = data.salesHistory;
                    salesHistorys.forEach(function (salesHistory) {
                        //  console.log(salesHistory, "aja");
                        var book = salesHistory.book.replace(/^0+/, ""),
                                page = salesHistory.page.replace(/^0+/, ""),
                                url = urls.bookAndPage + "O";
                        var href = url + "&Book=" + book + "&Page=" + page;
                        var a = "<a class='menu-item' target='_blank' href='" + href + "' >" + moment(salesHistory.saleDate, "YYYY-MM-DD").format("DD MMM, YYYY") + "</a><br>";
                        $divID.append(a);

                    });
                    console.log($divID.html());

                });
    }
</script>