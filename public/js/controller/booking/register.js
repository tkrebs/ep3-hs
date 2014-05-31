(function() {

    var paymentRadios;

    $(document).ready(function() {

        paymentRadios = $("#rf-payment").closest("tr").find('input');
        paymentRadios.on("change", updatePayment);

        updatePayment();

        /* Submit */

        $("#registration-submit-link").on("click", "a", performSubmit);
        $("#registration-submit-link-paypal").on("click", "a", performSubmit);

    });

    function updatePayment()
    {
        updateSubmit();
    }

    function updateSubmit()
    {
        $("#registration-submit-input").hide();

        var paymentRadioChecked = paymentRadios.filter(":checked");

        if (paymentRadioChecked.length) {
            switch (paymentRadioChecked.attr("value")) {
                case 'paypal':
                    $("#registration-submit-link").hide();
                    $("#registration-submit-link-paypal").show();
                    break;
                case 'creditcard':
                    $("#registration-submit-link").hide();
                    $("#registration-submit-link-paypal").show();
                    break;
                case 'invoice':
                    $("#registration-submit-link").show();
                    $("#registration-submit-link-paypal").hide();
                    break;
            }
        } else {
            $("#registration-submit-link").show();
            $("#registration-submit-link-paypal").hide();
        }
    }

    function performSubmit(event)
    {
        event.preventDefault();

        $(this).closest("form").submit();
    }

})();