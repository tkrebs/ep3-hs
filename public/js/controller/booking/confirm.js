(function() {

    $(document).ready(function() {

        /* Submit link */

        $("#confirmation-submit-input").hide();
        $("#confirmation-submit-link").show();

        $("#confirmation-submit-link").on("click", "a", function(event) {
            event.preventDefault();

            $(this).closest("form").submit();
        });

    });

})();