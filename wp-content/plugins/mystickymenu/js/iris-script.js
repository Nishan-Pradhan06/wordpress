(function (factory) {
    "use strict";
    if (typeof define === 'function' && define.amd) {
        define(['jquery'], factory);
    }
    else if(typeof module !== 'undefined' && module.exports) {
        module.exports = factory(require('jquery'));
    }
    else {
        factory(jQuery);
    }
}(function ($, undefined) {
    var priceOptions = {
        "50_websites": {
            "1_year": {
                "price": 149,
                "per_month":12.5,
                "link": "https://go.premio.io/?edd_action=add_to_cart&download_id=2199&edd_options[price_id]=43"
            },
            "2_year": {
                "price": 229,
                "per_month":10.0,
                "link": "https://go.premio.io/?edd_action=add_to_cart&download_id=2199&edd_options[price_id]=44"
            },
            "lifetime": {
                "price": 389,
                "link": "https://go.premio.io/?edd_action=add_to_cart&download_id=2199&edd_options[price_id]=45"
            }
        },
        "500_websites": {
            "1_year": {
                "price": 279,
                "per_month":23.5,
                "link": "https://go.premio.io/?edd_action=add_to_cart&download_id=2199&edd_options[price_id]=46"
            },
            "2_year": {
                "price": 419,
                "per_month":17.5,
                "link": "https://go.premio.io/?edd_action=add_to_cart&download_id=2199&edd_options[price_id]=47"
            },
            "lifetime": {
                "price": 699,
                "link": "https://go.premio.io/?edd_action=add_to_cart&download_id=2199&edd_options[price_id]=48"
            }
        },
        "1000_websites": {
            "1_year": {
                "price": 389,
                "per_month":32.5,
                "link": "https://go.premio.io/?edd_action=add_to_cart&download_id=2199&edd_options[price_id]=49"
            },
            "2_year": {
                "price": 585,
                "per_month":24.5,
                "link": "https://go.premio.io/?edd_action=add_to_cart&download_id=2199&edd_options[price_id]=50"
            },
            "lifetime": {
                "price": 959,
                "link": "https://go.premio.io/?edd_action=add_to_cart&download_id=2199&edd_options[price_id]=51"
            }
        }
        };
    $(document).ready(function($){
        $('.my-color-field').wpColorPicker();
        $(document).on('click', '.sticky-header-upgrade-now', function(e){
            //e.preventDefault();
            //$(".sticky-header-menu ul li a:last").trigger("click");
        });

        $(document).on("click", ".pricing-table-content", function(){
            if(!$(this).hasClass("active")) {
                $(".pricing-table-content").removeClass("active");
                $(this).addClass("active");
                var datFor = $(this).data("option");
                $(".multiple-options").each(function(){
                    $(this).find("option").prop("selected", false);
                    $(this).find("option[data-option='"+datFor+"']").prop("selected", true);
                    $(this).trigger("change");
                })
            }
        });

        if($(".multiple-options").length) {
            $(".multiple-options").select2({
                minimumResultsForSearch: -1
            });
        }
        if($(".multiple-web-options").length) {
            $(".multiple-web-options").select2({
                minimumResultsForSearch: -1
            });
        }
		
		
        $(document).on("change", ".multiple-options", function(){
			
            priceText = $(this).find("option:selected").attr("data-header");
            thisValue = $(this).val();
            thisPrice = $(this).find("option:selected").attr("data-price");
			thisperMonth = $(this).find("option:selected").attr("data-per-month");
			console.log("thisperMonth == " + thisperMonth);
            if(!$(this).hasClass("has-multiple-websites")) {
                $(this).closest(".price-table").find("a.cart-link").attr("href", thisValue);
                $(this).closest(".price-table").find(".plan-price").text("$" + thisPrice);
            } else {
                var webOption = $(".multiple-web-options").val();
                var priceSettings = priceOptions[webOption];
                var yearPlan = $(".multiple-options.has-multiple-websites option:selected").attr("data-option");
               
			   if(priceSettings[yearPlan] != undefined) {
                    priceSettings = priceSettings[yearPlan];
                    thisValue = priceSettings.link;
                    thisPrice = priceSettings.price;
					thisperMonth = priceSettings.per_month;
                }
            }
            thisOption = $(this).find("option:selected").attr("data-option");
            if(thisOption == "1_year") {
                thisPrice = thisPrice+"<span>/year</span>";
				per_month = "Less than <b>$" + thisperMonth + "</b>/mo · <b>Billed Annually</b>";
                priceText = "Renewals for <b>25% off</b>";
            } else if(thisOption == "2_year") {
                thisPrice = thisPrice+"<span>/2 years</span>";
				per_month = "Less than <b>$" + thisperMonth + "</b>/mo · <b>Billed Annually</b>";
                priceText = "Renewals for <b>25% off</b>";
            } else {
                thisPrice = thisPrice+"<span>/lifetime</span>";
				per_month = "<b>Best value</b>";
                priceText = "For lifetime";
            }
			
            $(this).closest(".price-table").find("a.cart-link").attr("href", thisValue);
            $(this).closest(".price-table").find(".plan-price").html("$" + thisPrice);
            $(this).closest(".price-table").find(".price-offer").html(priceText);
            $(this).closest(".price-table").find(".price-permonth").html(per_month);
			
			if ( per_month == '' ) {
				$(this).closest(".price-table").find(".price-permonth").hide();
			} else {
				$(this).closest(".price-table").find(".price-permonth").show();
			}
			
        });

        $(document).on("change", ".multiple-web-options", function(){
            $(".multiple-options.has-multiple-websites").trigger("change");
        });

        if($(".multiple-options.has-multiple-websites").length) {
            $(".multiple-options.has-multiple-websites").trigger("change");
        }
        checkForPricingPos();
        $(window).on("scroll", function(){
            checkForPricingPos();
        });

        $(window).on("resize", function(){
            checkForPricingPos();
        });

        function checkForPricingPos() {
            $(".bottom-position").each(function(){
                if($(this).closest(".price-table").find(".on-screen-pos").length) {
                    var toolPos = $(this).closest(".price-table").find(".on-screen-pos").offset().top - $(window).scrollTop() - $(window).height();
                    if(toolPos < 0) {
                        if ($(this).offset().top - $(window).scrollTop() - $(window).height() < -3) {
                            $(this).closest(".price-table").removeClass("is-fixed");
                            $(this).closest(".price-table").find(".price-table-bottom").prop("style", "");
                        } else {
                            $(this).closest(".price-table").addClass("is-fixed");
                            $(this).closest(".price-table").find(".price-table-bottom").css("top", ($(window).height() - 125) + "px");
                            $(this).closest(".price-table").find(".price-table-bottom").css("left", $(this).offset().left + "px");
                            $(this).closest(".price-table").find(".price-table-bottom").outerWidth($(this).closest(".price-table").width());
                        }
                    } else {
                        $(this).closest(".price-table").removeClass("is-fixed");
                        $(this).closest(".price-table").find(".price-table-bottom").prop("style", "");
                    }
                }
            });
            setTooltipPosition();
        }

        function setTooltipPosition() {
            if($(".html-tooltip:not(.no-position)").length) {
                $(".html-tooltip:not(.no-position)").each(function(){
                    if($(this).offset().top - $(window).scrollTop() > 540) {
                        $(this).addClass("top").removeClass("side").removeClass("bottom");
                        $(this).find(".tooltip-text").attr("style","");
                        $(this).find(".tooltip-text").removeClass("hide-arrow");
                    } else if($(window).height() - ($(this).offset().top - $(window).scrollTop()) > 460) {
                        $(this).addClass("bottom").removeClass("top").removeClass("side");
                        $(this).find(".tooltip-text").attr("style","");
                        $(this).find(".tooltip-text").removeClass("hide-arrow");
                    } else {
                        $(this).addClass("side").removeClass("top").removeClass("bottom");
                        if($(this).find(".tooltip-text").length) {
                            $(this).find(".tooltip-text").attr("style","");
                            $(this).find(".tooltip-text").removeClass("hide-arrow");

                            if($(this).find(".tooltip-text").offset().top - $(window).scrollTop() - 50 < 0) {
                                $(this).find(".tooltip-text").css("margin-top", Math.abs($(this).find(".tooltip-text").offset().top - $(window).scrollTop() - 50)+"px");
                                $(this).find(".tooltip-text").addClass("hide-arrow");
                            } else {
                                $(this).find(".tooltip-text").attr("style","");
                                if(($(this).find(".tooltip-text").offset().top + parseInt($(this).find(".tooltip-text").outerHeight()) - $(window).scrollTop() - $(window).height()) > 0) {
                                    $(this).find(".tooltip-text").css("margin-top", ((-1)*Math.abs($(this).find(".tooltip-text").offset().top + parseInt($(this).find(".tooltip-text").outerHeight()) - $(window).scrollTop() - $(window).height()) - 10)+"px");
                                    $(this).find(".tooltip-text").addClass("hide-arrow");
                                }
                            }
                        }
                    }
                });
            }
        }
    });
}));
