(function() {

    $(document).ready(function() {

        $("#ebif-pid-name").autocomplete({
            "minLength": 1,
            "source": $("#ebif-url-provider").data("autocomplete-product-url")
        });

    });

})();