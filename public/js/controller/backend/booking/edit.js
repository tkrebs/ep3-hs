(function() {

    $(document).ready(function() {

        $("#ebf-user").autocomplete({
            "minLength": 1,
            "source": $("#ebf-url-provider").data("autocomplete-user-url")
        });

        $("#ebf-user-info").show().appendTo( $("#ebf-user").closest("td") );
        $("#ebf-booking-info").show().appendTo( $("#ebf-status").closest("td") );

        if ($("#ebf-bill-info").text().match(/[a-zA-Z]/)) {
            $("#ebf-bill-info").show().appendTo( $("#ebf-status").closest("td") );
        }

    });

})();