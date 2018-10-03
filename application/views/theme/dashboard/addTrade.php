<?php
/**
 * Author S Brinta<brrinta@gmail.com>
 * Email: brrinta@gmail.com
 * Web: https://brinta.me
 * Do not edit file without permission of author
 * All right reserved by S Brinta<brrinta@gmail.com>
 * Created on : May 12, 2018, 11:32:33 AM
 */
?>
<form class="form" action="<?= dashboard_url("addTrade") ?>" method="post" novalidate id="addTrade_form">
    <div class="form-body">        
        <div class="row">
            <div class="col-md-6">
                <fieldset class="form-group">
                    <label for="userinput1">Date</label>
                    <input type="text" id="userinput1" class="form-control  todayDate" 
                           required placeholder="Date" name="Date" <?= $currentUser->position == USER_TRADER ? "disabled" : "" ?> >
                    <p class="help-block m-0 danger"></p>
                </fieldset>
            </div>
            <div class="col-md-6">
                <fieldset class="form-group">
                    <label for="ClientList">Client List</label>
                    <select type="text" id="ClientList" class="form-control" style="width: 100%;" required
                            placeholder="Client" name="Client">
                        <option value=""></option>
                    </select>                    
                    <p class="help-block m-0 danger"></p>
                </fieldset>
            </div>
            <div class="col-md-6">
                <fieldset class="form-group">
                    <label for="AccountSelection">Account Selection</label>
                    <select type="text" id="AccountSelection" class="form-control" style="width: 100%;"
                            placeholder="Account Selection" name="AccountSelection">
                        <option value="0"></option>
                    </select>                    
                    <p class="help-block m-0 danger"></p>
                </fieldset>
            </div>
            <div class="col-md-6">
                <fieldset class="form-group">
                    <label for="Commission">Commission</label>
<!--                    <input type="hidden" value="No" name="CommissionEdited">-->
                    <select type="number" id="Commission" class="form-control" style="width: 100%;"
                            placeholder="Commission" name="Commission">
                        <option value="0"></option>
                    </select>
                    <p class="help-block m-0 danger"></p>
                </fieldset>
            </div>


            <div class="col-md-6">
                <fieldset class="form-group">
                    <label for="tradeType">Trade Type </label>
                    <select class="form-control" id="tradeType" style="width: 100%;"
                            placeholder="Trade Type" name="TradeType">
                        <option>Single Stock</option>
                        <option>Basket</option>
                    </select>
                    <p class="help-block m-0 danger"></p>
                </fieldset>
            </div>
            <div class="col-md-6">
                <fieldset class="form-group">
                    <label for="Side">Side </label>
                    <select class="form-control" id="Side" style="width: 100%;"
                            placeholder="Side" name="Side">
                        <option>Buy</option>
                        <option>Sell</option>
                        <option>Sell Short</option>
                    </select>
                    <p class="help-block m-0 danger"></p>
                </fieldset>
            </div>

            <div class="col-md-6">
                <fieldset class="form-group">
                    <label for="symbol">Symbol</label>
                    <input class="form-control" id="symbol" style="width: 100%;"
                           pattern="^\$?[\d,]{0,8}$"
                           data-validation-pattern-message="Positive integer and max 8 digit"
                           placeholder="Symbol" name="Symbol">                        
                    <p class="help-block m-0 danger"></p>
                </fieldset>
            </div>

            <div class="col-md-6">
                <fieldset class="form-group">
                    <label for="Shares">Shares</label>
                    <input class="form-control" id="Shares" style="width: 100%;" required type="text" value="0"
                           pattern="^\$?[\d,]*$"
                           data-validation-pattern-message="Only Positive integer"
                           placeholder="Shares" name="Shares">                        
                    <p class="help-block m-0 danger"></p>
                </fieldset>
            </div>
            <div class="col-md-6">
                <fieldset class="form-group">
                    <label for="AveragePrice">Average Price</label>
                    <input class="form-control" id="AveragePrice" style="width: 100%;" required type="number" value="0.00"
                           placeholder="Average Price" name="AveragePrice">                        
                    <p class="help-block m-0 danger"></p>
                </fieldset>
            </div>
            <div class="col-md-6">
                <fieldset class="form-group">
                    <label for="TotalCommission">Total Commission</label>
                    <input class="form-control" id="TotalCommission" style="width: 100%;" readonly
                           placeholder="Total Commission" name="TotalCommission">                        
                    <p class="help-block m-0 danger"></p>
                </fieldset>
            </div>


            <div class="col-md-6">
                <fieldset class="form-group">
                    <label for="SoftDollars">Soft Dollars</label>
                    <input class="form-control" id="SoftDollars" style="width: 100%;"
                           placeholder="Soft Dollars" name="SoftDollars">                        
                    <p class="help-block m-0 danger"></p>
                </fieldset>
            </div>
            <div class="col-md-6">
                <fieldset class="form-group">
                    <label for="NetCommission">Net Commission</label>
                    <input class="form-control" id="NetCommission" style="width: 100%;"
                           placeholder="Net Commission" name="NetCommission">                        
                    <p class="help-block m-0 danger"></p>
                </fieldset>
            </div>
            <div class="col-md-6">
                <fieldset class="form-group">
                    <label>Is there any allocation to FR?</label>
                    <div class="form-inline">
                        <div class="custom-control custom-radio mr-1">
                            <input type="radio" class="custom-control-input" id="fryes" name="allocationToFR" value="Yes" required>
                            <label class="custom-control-label" for="fryes">Yes</label>
                        </div>
                        <div class="custom-control custom-radio mx-1">
                            <input type="radio" class="custom-control-input" id="frno" name="allocationToFR" value="No">
                            <label class="custom-control-label" for="frno">No</label>
                        </div>           
                        <input class="form-control" placeholder="FR" name="fr" id="fr" type="number" style=" -ms-flex-preferred-size: 0;  flex-basis: 0;
                               -ms-flex-positive: 1;  flex-grow: 1;  max-width: 100%;">
                    </div>
                    <p class="help-block m-0 danger"></p>
                </fieldset>
            </div>
            <div class="col-md-6">
                <fieldset class="form-group">
                    <label for="PotentialReferral">Potential Referral</label>
                    <select class="form-control  select2-dopdown" id="PotentialReferral" style="width: 100%;" multiple required
                            placeholder="Potential Referral" name="PotentialReferral[]">
                        <option>N/A</option>
                        <option>IB</option>
                        <option>FR</option>
                        <option>MS</option>
                        <option>TCA</option>
                    </select>
                    <p class="help-block m-0 danger"></p>
                </fieldset>
            </div>
            <div class="col-md-12">
                <fieldset class="form-group">
                    <label>Notes</label>
                    <textarea placeholder="Notes" class="form-control" name="Notes" rows="4" required></textarea>
                    <p class="help-block m-0 danger"></p>
                </fieldset>
            </div>




        </div>


        <div class="form-actions right">
            <button type="button" class="btn btn-warning mr-1">
                <i class="ft-x"></i> Cancel
            </button>
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-check-square-o"></i> Save
            </button>
        </div>
    </div>
</form>
<script>
    function chgTotalComm() {
        var share = parseInt($("#Shares").val().toString().replace(",", ""));
        var comm = parseInt($("#Commission").val().toString().replace(",", ""));
        var softDoll = parseInt($("#SoftDollars").val().toString().replace(",", ""));
        if ($.isNumeric(share) && $.isNumeric(comm)) {
            $("#TotalCommission").val(share * comm);
            if ($.isNumeric(softDoll)) {
                $("#NetCommission").val((comm * share) - softDoll);
            } else {
                $("#NetCommission").val("");
            }
        } else {
            $("#NetCommission").val("");
            $("#TotalCommission").val("");
        }


    }
    window.onload = function (event) {
        'use strict';

        $("#Shares").keyup(function (e) {
            $(this).val(format($(this).val()));
        });

        $(".select2-dopdown").select2();
        $("#Shares").on("keyup", function (e) {
            chgTotalComm();
        });
        $("#Commission").on("select2:select", function (e) {
            chgTotalComm();
        });

        $("#SoftDollars").on("keyup", function (e) {
            var softDoll = parseInt($("#SoftDollars").val().toString().replace(",", ""));
            var totalComm = parseInt($("#TotalCommission").val().toString().replace(",", ""));
            if ($.isNumeric(softDoll) && $.isNumeric(totalComm)) {
                $("#NetCommission").val(totalComm - softDoll);
            } else {
                $("#NetCommission").val("");
            }
        });
        $("#fr").hide();
        $('input[name="allocationToFR"]').on("change", function (e) {

            if ($('input[name="allocationToFR"]:checked').val() == "Yes") {
                console.log($('input[name="allocationToFR"]:checked').val());
                $("#fr").show();
            } else {
                $("#fr").hide();
            }
        });
        $("#tradeType").on("change", function (e) {
            var tradeValue = $("#tradeType").val();
            console.log(tradeValue);
            if ("Basket" == tradeValue) {
                $("#symbol").attr("disabled", true);
                $("#symbol").attr("required", false);

                $("#Side").attr("disabled", true);
                $("#Side").attr("required", false);
            } else {
                $("#symbol").attr("disabled", false);
                $("#symbol").attr("required", true);

                $("#Side").attr("disabled", false);
                $("#Side").attr("required", true);
            }
        });
        $("#ClientList").select2({
            //minimumInputLength: 2,
            tags: [],
            ajax: {
                url: '<?= dashboard_url("getClientList") ?>',
                dataType: 'json',
                type: "POST",
                quietMillis: 50,
                data: function (term) {
                    return term;
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {id: item.id, text: item.text};
                        })
                    };
                }, success(e, f, d) {
                    // console.log(e, f, d);
                },
                error(e, f, d) {
                    console.log(e, f, d);
                }
            }
        });

        var acc = $("#AccountSelection").select2({
            //minimumInputLength: 2,
            tags: [],
            ajax: {
                url: '<?= dashboard_url("getAccountSelection") ?>',
                dataType: 'json',
                type: "POST",
                quietMillis: 50,
                data: function (term) {
                    return {term: term, client: $("#ClientList").val()};
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {id: item.id, text: item.text};
                        })
                    };
                }, success(da, f, t) {
                    //   console.log( da,f,t);
                },
                error(er, f, d) {
                    console.log(er, f, d);
                }
            }
        });
        $("#ClientList").on("select2:select", function (evt) {
            if ($("#ClientList").val()) {
                $.ajax({
                    url: '<?= dashboard_url("getAccountSelection") ?>',
                    dataType: 'json',
                    type: "POST",
                    quietMillis: 50,
                    data: {client: $("#ClientList").val()},
                    success: function (dat, e, f) {
                        if (dat.length) {
                            console.log(dat.length);

                            $("#AccountSelection").html("<option value='" + dat[0].id + "'>" + dat[0].text + "</option>")
                            acc.select2("data", dat[0]);
                            $("#AccountSelection").attr("required", true);

                        } else {
                            $("#AccountSelection").attr("required", false);
                        }
                    },
                    error: function (d, e, f) {
                        console.log(d, e, f);
                    }
                });
            }
        });

        $("#Commission").select2({
            //minimumInputLength: 2,
            tags: [],
            ajax: {
                url: '<?= dashboard_url("getCommission") ?>',
                dataType: 'json',
                type: "POST",
                quietMillis: 50,
                data: function (term) {
                    return {term: term, client: $("#ClientList").val()};
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {id: item.id, text: item.text};
                        })
                    };
                }, success(e, f, d) {
                    console.log(e, f, d);
                },
                error(e, f, d) {
                    console.log(e, f, d);
                }
            }
        });
        $("#addTrade_form").on("change", function (e) {
            $("input,select,textarea").not("[type=submit]").jqBootstrapValidation('destroy');
            $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
        });
    };

</script>