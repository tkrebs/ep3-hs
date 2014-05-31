(function() {

    $(document).ready(function() {

        $("#ebf-user").autocomplete({
            "minLength": 1,
            "source": $("#ebf-url-provider").data("autocomplete-user-url")
        });

        $("#ebf-booking-info").show().appendTo( $("#ebf-booking").closest("td") );
        $("#ebf-user-info").show().appendTo( $("#ebf-user").closest("td") );
        $("#ebf-bill-info").show().appendTo( $("#ebf-status").closest("td") );

    });

})();