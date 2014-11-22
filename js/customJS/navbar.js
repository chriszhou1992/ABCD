

// jquery shorthand for onLoad event
$(function() { 
    hideNavDivs();
    
    $("li").click(function(event) {
        event.preventDefault();
       
        if($(this).hasClass("customNotActive")) {
            $(".customActive").addClass("customNotActive");
            $(".customActive").removeClass("customActive");
            $(this).removeClass("customNotActive");
            $(this).addClass("customActive");
        }
       
        if($(this).hasClass("Games")) {
            //$("#myCarousel").fadeOut("fast", function() {
                //hideNavDivs();
                //$("#Games").fadeIn("slow");
            //});
            
            hideNavDivs();
            $("#homeField").hide();
            $("#myCarousel").fadeIn("slow");
            $("#Games").fadeIn("slow");
            
            $("#gameField").fadeOut("fast", function() {
                $("#gamesList").fadeIn("slow");
            });
        }
        
        if($(this).hasClass("Companies")) {
            hideNavDivs();
            $("#homeField").hide();
            $("#myCarousel").fadeOut("fast", function() {
                $("#Companies").fadeIn("slow");
            });
            
            $("#companyField").fadeOut("fast", function() {
                $("#companiesList").fadeIn("slow");
            });
        }
        
        if($(this).hasClass("Home")) {
            hideNavDivs();
            $("#homeField").fadeIn("slow");
        }
        
        if($(this).hasClass("Credit")) {
            hideNavDivs();
            $("#homeField").hide();
            $("#Credit").fadeIn("slow");
        }
        
        if($(this).hasClass("suggestGames")) {
            hideNavDivs();
            $("#homeField").hide();
        }
    });
    
    $("#try").click(function() {
        $("#signup-modal").modal("show"); 
    });
        
});

function hideNavDivs() {
    $("#suggestFieldPie").html("");
    $("#Games").hide();
    $("#Companies").hide();
    $("#myCarousel").hide();
    $("#searchField").hide();
    $("#searching").hide();
    $("#suggestField").hide();
    $("#suggestFieldPie").hide();
    $("#suggestFieldPieLabel").hide();
    $("#suggestBC").hide();
    $("#Credit").hide();
}
