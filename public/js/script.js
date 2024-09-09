$(document).ready(function () {
    $(".portfolio-menu ul li").click(function () {
        $(".portfolio-menu ul li").removeClass("active");
        $(this).addClass("active");

        var selector = $(this).attr("data-filter");
        $(".portfolio-item").isotope({
            filter: selector,
        });
        return false;
    });

    var popup_btn = $(".popup-btn");
    popup_btn.magnificPopup({
        type: "image",
        gallery: {
            enabled: true,
        },
    });
});

$(document).ready(function () {
    $(".flex.space-x-4 a").click(function () {
        $(".flex.space-x-4 a").removeClass("bg-gray-900 text-white active");
        $(this).addClass("bg-gray-900 text-white active");

        var selector = $(this).attr("data-filter");
        $(".portfolio-item").isotope({
            filter: selector,
        });
        return false;
    });
});
