(function() {

    $(document).ready(function() {

        $("#ebif-due").on("change", updateDue);

        updateDue();

    });

    function updateDue()
    {
        var dueChoice = $("#ebif-due").val();

        switch (dueChoice) {
            case "per_item":
                $("#ebif-amount-required").closest("tr").hide();
                $("#ebif-amount-min").closest("tr").show();
                $("#ebif-amount-max").closest("tr").show();
                break;
            case "per_night":
                $("#ebif-amount-required").closest("tr").show();
                $("#ebif-amount-min").closest("tr").hide();
                $("#ebif-amount-max").closest("tr").hide();
                break;
        }
    }

})();