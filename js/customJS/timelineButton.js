$("#companyField").on("click", "button#gameButton", function () {
    //alert("clicked!");
    var send = "game=" + $(this).text();
    $.ajax({
        type: 'POST',
        url: 'showGameInfo.php',
        data: send,
        success: function (response) {
            $(".customActive").addClass("customNotActive");
            $(".customActive").removeClass("customActive");
            $(".Games").addClass("customActive");

            document.getElementById('gameField').innerHTML = response;
            $("#suggestField").hide();
            $("#suggestFieldPie").hide();
            $("#suggestFieldPieLabel").hide();
            $("#searchField").hide();
            $("#suggestBC").hide();
            $("#Credit").hide();


            $("#Companies").hide();

            $("#gamesList").fadeOut("fast", function () {
                $("#gameField").fadeIn("slow");
                $("#Games").fadeIn("slow");
            });
            $("#myCarousel").fadeOut("fast");

        },
        error: function () {
            alert("Unable to load the game data.");
        }
    }).done(function () {
        $(".gameCarousel").click(function () {
            $(".gameCarousel").toggleClass('largeImages');
        });
        $('.carousel').carousel({
            interval: 1000
        });
        activateGameLikeButton();
    });
});