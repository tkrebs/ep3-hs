(function() {

    var calendarArrival;
    var calendarDeparture;

    var calendarArrivalInput;
    var calendarDepartureInput;

    $(document).ready(function() {

        calendarArrival = $("#calendar-arrival");
        calendarDeparture = $("#calendar-departure");

        calendarArrivalInput = $("#calendar-arrival-input");
        calendarDepartureInput = $("#calendar-departure-input");

        prepareCalendarDatepickers();
        prepareCalendarInput();
        prepareCalendarCapacityChoice();

        prepareCalendarScroll();

    });

    function prepareCalendarDatepickers()
    {
        calendarArrival.html("");
        calendarDeparture.html("");

        calendarArrival.datepicker({
            "altField": calendarArrivalInput,
            "beforeShowDay": updateCalendarArrivalDay,
            "defaultDate": calendarArrivalInput.val(),
            "minDate": "+1D",
            "onSelect": updateCalendarDeparture
        });

        calendarDeparture.datepicker({
            "altField": calendarDepartureInput,
            "beforeShowDay": updateCalendarDepartureDay,
            "defaultDate": calendarDepartureInput.val(),
            "minDate": "+1D",
            "onSelect": updateCalendarArrival
        });

        calendarArrival.datepicker("refresh");
        calendarDeparture.datepicker("refresh");
    }

    function updateCalendarArrivalDay(date)
    {
        var dateSelectionArrival = calendarArrival.datepicker("getDate");
        var dateSelectionDeparture = calendarDeparture.datepicker("getDate");

        if (date > dateSelectionArrival && date <= dateSelectionDeparture) {
            return [true, "cc-range-member"];
        }

        return [true, ""];
    }

    function updateCalendarDepartureDay(date)
    {
        var dateSelectionArrival = calendarArrival.datepicker("getDate");
        var dateSelectionDeparture = calendarDeparture.datepicker("getDate");

        if (date >= dateSelectionArrival && date < dateSelectionDeparture) {
            return [true, "cc-range-member"];
        }

        return [true, ""];
    }

    function updateCalendarArrival(selectedDate, obj)
    {
        calendarArrival.datepicker("option", "minDate", "+1D");

        calendarArrival.datepicker("refresh");
        calendarDeparture.datepicker("refresh");
    }

    function updateCalendarDeparture(selectedDate, obj)
    {
        calendarDeparture.datepicker("option", "minDate", selectedDate);

        calendarArrival.datepicker("refresh");
        calendarDeparture.datepicker("refresh");
    }

    function prepareCalendarInput()
    {
        calendarArrivalInput.on("change", function() {
            calendarArrival.datepicker("setDate", $(this).val());

            updateCalendarDeparture( calendarArrival.datepicker("getDate"), {} );
        });

        calendarDepartureInput.on("change", function() {
            calendarDeparture.datepicker("setDate", $(this).val());

            updateCalendarArrival( calendarDeparture.datepicker("getDate"), {} );
        });
    }

    function prepareCalendarCapacityChoice()
    {
        updateCalendarCapacityChoice();

        $(".calendar-capacity-radio").on("change", updateCalendarCapacityChoice);

        /* Switch submit button */

        $("#calendar-submit-input").hide();
        $("#calendar-submit-link").show();

        $("#calendar-submit-link").on("click", "a", function(event) {
            event.preventDefault();

            $(this).closest("form").submit();
        });
    }

    function updateCalendarCapacityChoice()
    {
        $(".calendar-capacity-radio").closest("tr").find("input, label").css("opacity", 0.5);
        $(".calendar-capacity-radio:checked").closest("tr").find("input, label").css("opacity", 1.0);
    }

    function prepareCalendarScroll()
    {
        var hash = window.location.hash;

        if (hash === "#calendar-results-animate") {
            window.location.hash = "#calendar-results";

            var scrollTop = $(document).scrollTop();

            $("html, body").scrollTop(0).animate({
                "scrollTop": scrollTop
            }, 1000);
        }
    }

})();