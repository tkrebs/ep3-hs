(function() {

    var opacityPreset = 0.65;
    var durationPreset = 500;

    var bundleRadios;

    var currentRequest;

    $(document).ready(function() {

        /* Prepare bundles */

        bundleRadios = $(".bf-bundle");
        bundleRadios.hide();

        if (! bundleRadios.filter(":checked").length) {
            bundleRadios.first().prop("checked", true);
        }

        bundleRadios.filter(":checked").closest(".bundle").find(".bundle-enabled").show();

        var bundlesDisabled = bundleRadios.filter(":not(:checked)").closest(".bundle");

        bundlesDisabled.find(".bundle-enable").show();
        bundlesDisabled.find(".bundle-customization").hide();
        bundlesDisabled.css("opacity", opacityPreset);

        bundleRadios.on("change", function() {
            var that = $(this);
            var thatBundle = that.closest(".bundle");

            thatBundle.find(".bundle-enable").hide();
            thatBundle.find(".bundle-enabled").show();
            thatBundle.find(".bundle-customization").slideDown(durationPreset);
            thatBundle.css("opacity", 1.0);

            var otherBundles = bundleRadios.not(that).closest(".bundle");

            otherBundles.find(".bundle-enable").show();
            otherBundles.find(".bundle-enabled").hide();
            otherBundles.find(".bundle-customization").slideUp(durationPreset);
            otherBundles.css("opacity", opacityPreset);

            updateSubmit();
        });

        /* Prepare pricing */

        updatePricing(true);

        $(".bf-bi").on("change", function() {
            updatePricing(true);
        });

        $(".bf-bundle").on("change", function() {
            updatePricing(false);
        });

        /* Sync cols */

        sync( $(".bf-bn-price-col, .bf-bi-price-col") );

        /* Submit link */

        $("#customization-submit-input").hide();

        $("#customization-submit-link").on("click", "a", function(event) {
            event.preventDefault();

            $(this).closest("form").submit();
        });

        $("#customization-coupon-submit-link").on("click", "a", function(event) {
            event.preventDefault();

            $(this).closest("form").submit();
        });

        updateSubmit();

    });

    function updatePricing(forceLoading)
    {
        $("body").clearQueue().queue(function() {
            var activeBundle = bundleRadios.filter(":checked").closest(".bundle").find(".bundle-customization");
            var activeBundleItems = activeBundle.find(".bf-bi");

            if (activeBundle.length && activeBundleItems.length) {
                var activeBundlePricing = activeBundle.find(".bf-pricing");
                var activeBundlePricingLoading = activeBundle.find(".bf-pricing-loading");

                if (activeBundlePricing.length) {
                    if (forceLoading) {
                        activeBundlePricing.fadeOut(durationPreset / 2, function() {
                            activeBundlePricing.remove();

                            loadLoading(activeBundle, function() {
                                loadPricing(activeBundle);
                            });
                        });
                    }
                } else {
                    if (activeBundlePricingLoading.length) {
                        loadPricing(activeBundle);
                    } else {
                        loadLoading(activeBundle, function() {
                            loadPricing(activeBundle);
                        });
                    }
                }
            }

            $(this).dequeue();
        });
    }

    function loadLoading(activeBundle, callback)
    {
        var pricingLoading = $('<div class="bf-pricing-loading loading"></div>');

        activeBundle.append(pricingLoading);

        pricingLoading.css({
            "margin-top": 16,
            "height": 49
        }).hide().fadeIn(durationPreset, callback);
    }

    function loadPricing(activeBundle)
    {
        if (currentRequest) {
            currentRequest.abort();
        }

        var data = { "ajax": "true" };

        $.each( $("#bf").serializeArray(), function(index, input) {
            data[input.name] = input.value;
        } );

        currentRequest = $.ajax({
            "data": data,
            "error": function() {
                var pricingLoading = activeBundle.find(".bf-pricing-loading");

                pricingLoading.fadeOut(durationPreset / 2);

                currentRequest = undefined;
            },
            "success": function(data) {
                var bundlePricing = $(data);

                var pricingLoading = activeBundle.find(".bf-pricing-loading");

                if (pricingLoading.length) {
                    pricingLoading.fadeOut(durationPreset / 2, function() {
                        pricingLoading.remove();

                        activeBundle.append(bundlePricing);

                        bundlePricing.hide().fadeIn(durationPreset);
                    });
                } else {
                    activeBundle.append(bundlePricing);

                    bundlePricing.hide().fadeIn(durationPreset);
                }

                bundlePricing.find(".bf-pricing-price-col").css("width", activeBundle.find(".bf-bi-price-col:first").width());

                currentRequest = undefined;
            },
            "url": $("#customization-pricing").data("url")
        });
    }

    function updateSubmit()
    {
        var activeBundle = bundleRadios.filter(":checked").val();

        if (activeBundle === "coupon") {
            $("#customization-submit-link").hide();
            $("#customization-coupon-submit-link").show();
        } else {
            $("#customization-submit-link").show();
            $("#customization-coupon-submit-link").hide();
        }
    }

    function sync(cols)
    {
        var maxColWidth = 0;

        cols.each(function() {
            var colWidth = $(this).width();

            if (colWidth > maxColWidth) {
                maxColWidth = colWidth;
            }
        });

        cols.width(maxColWidth);
    }

})();