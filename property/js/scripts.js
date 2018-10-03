String.prototype.replaceAll = function (search, replacement) {
    var target = this;
    return target.split(search).join(replacement);
};

(function (e, u) {
    'use strict';
    //console.log("hello");
    var todayPicker = $(".todayDate").pickadate({
        selectMonths: true,
        selectYears: 100,
        max: true
    });
    var datePicker = $(".datePicker").pickadate({
        selectMonths: true,
        selectYears: 100,
        max: true
    });
    if (todayPicker.pickadate('picker')) {
        todayPicker.pickadate('picker').set("select", new Date(Date.now()));
    }
    /*$(document).delegate(".todayDate", "focusin", function () {
     
     });*/
    $(".dateTimePicker").datetimepicker({
        format: 'DD MMM, YYYY hh:mm a'
    });
    $(".selectedTimeDate").datetimepicker({
        format: 'DD MMM, YYYY hh:mm a'
    });
    var birthDatePicker = $(".birthDate").pickadate({
        selectMonths: true,
        selectYears: 100,
        max: true
    });

    if (birthDatePicker.val()) {
        birthDatePicker.pickadate('picker').set("select", new Date(birthDatePicker.val()));
    }
    if ($("form").attr("novalidate") != undefined) {
        $("input,select,textarea").not("[type=submit]").jqBootstrapValidation({
            preventSubmit: true
        });
    }
    $('[data-toggle="tooltip"]').tooltip();
    var popOverSettings = {
        container: 'body',
        html: true,
        selector: '[data-toggle="popover"]'
    };
    $('body').popover(popOverSettings);


    $("select").each(function (elmN) {
        if ($(this).attr("value") !== undefined) {
            document.getElementById($(this).attr("id")).value = $(this).attr("value").toString();
        }
    });

    $(".slelectTwo").select2();

})(window);
$('.loader').hide();
$('body').on("click", "[modal-toggler='true']", function (e) {
    // console.log(e);
    $('.loader').show();
    var $_modalID = $(this).data("target");
    $($_modalID).load($(this).data('remote') ? $(this).data('remote') : $(this).attr('href'), function (e) {
        setTimeout(function (e) {
            $($_modalID).modal("show");
            $('.loader').hide();
        }, 1500);
    });
});
window.onclick = function (e) {
    if ($(e.target).hasClass("pop-close")) {
        $(e.target).closest("div.popover").popover("hide");
    }
};

