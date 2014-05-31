(function() {

    var durationPreset = 500;

    $(document).ready(function() {

        $(".config-form").hide();

        $(".config-form .message").closest(".config-form").show();

        /* Introduce ... the section headers */

        $(".ecf-section-header").on("click", function(event) {
            event.preventDefault();

            var currentConfigForm = $(this).siblings(".config-form");

            if (currentConfigForm.is(":visible")) {
                currentConfigForm.slideUp(durationPreset);
            } else {
                $(".config-form:visible").not(currentConfigForm).slideUp(durationPreset);

                if (! currentConfigForm.is(":visible")) {
                    currentConfigForm.slideDown(durationPreset);
                }
            }
        });

    });

})();