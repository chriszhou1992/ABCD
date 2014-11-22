function resizeSearchBar() {
    var barWidth = $(window).width() - $(".leftside").width() - $(".rightside").width() - 330;
    if(barWidth < 100) {
        barWidth = 320;
    }
    $(".customInput").css("width", barWidth);
    $("#searchSettings").css("width", barWidth);
}

$(function(){
    resizeSearchBar();
    $(window).resize(function() {
        resizeSearchBar();
    });
    
    $(".customInput").click(function() {
       $(".customClose").trigger("click"); 
    });
    
    normalSearch();
    
    $("#searchOption1").click(function() {
        toDoBeforeSearch();
        send = "option1=true";
        $.ajax({
             type: 'POST',  
            url: 'searchOptions.php', 
            data: send,
            success: function(response) {
                 setTimeout(function() {
                    document.getElementById('searchField').innerHTML = response;
                    toDoAfterSearch();
                }, 400);
                
            },
            error: function() {
                alert("Unable to search the the database.");
            }
        }).done(function() {
            
        }); 
    });
    
    $("#searchOption2").click(function() {
        toDoBeforeSearch();
        send = "option2=true";
        $.ajax({
             type: 'POST',  
            url: 'searchOptions.php', 
            data: send,
            success: function(response) {
                 setTimeout(function() {
                    document.getElementById('searchField').innerHTML = response;
                    toDoAfterSearch();
                }, 400);
                
            },
            error: function() {
                alert("Unable to search the the database.");
            }
        }).done(function() {
            
        }); 
    });
    
    $("#searchOption3").click(function() {
        toDoBeforeSearch();
        send = "option3=true";
        $.ajax({
             type: 'POST',  
            url: 'searchOptions.php', 
            data: send,
            success: function(response) {
                 setTimeout(function() {
                    document.getElementById('searchField').innerHTML = response;
                    toDoAfterSearch();
                }, 400);
                
            },
            error: function() {
                alert("Unable to search the the database.");
            }
        }).done(function() {
            
        }); 
    });
    
    $("#searchOption4").click(function() {
        toDoBeforeSearch();
        send = "option4=true";
        $.ajax({
             type: 'POST',  
            url: 'searchOptions.php', 
            data: send,
            success: function(response) {
                 setTimeout(function() {
                    document.getElementById('searchField').innerHTML = response;
                    toDoAfterSearch();
                }, 400);
                
            },
            error: function() {
                alert("Unable to search the the database.");
            }
        }).done(function() {
            
        }); 
    });
        
});


function normalSearch() {
    $(".customSearch").click(function(event) {
        event.preventDefault();
        
        toDoBeforeSearch();

        var search = $(".customInput").val();
        var send = "search="+search;
         $.ajax({  
            type: 'POST',  
            url: 'search.php', 
            data: send,
            success: function(response) {
                setTimeout(function() {
                    document.getElementById('searchField').innerHTML = response;
                    toDoAfterSearch();
                }, 400);
               
            },
            error: function() {
                alert("Unable to search the the database.");
            }
        });
    });
}

function toDoBeforeSearch() {
    $("#homeField").hide();
    hideNavDivs();
    
    $(".customActive").addClass("customNotActive");
    $(".customActive").removeClass("customActive");
    $("#searching").fadeIn("fast");
}

function toDoAfterSearch() {
    hideNavDivs();
    $("#searchField").fadeIn("slow", function() {
        showDetails();
    });
    $(".customInput").val("");

    // callbacks
    $(".customClose").click(function() {
       $(".dismissableHR").hide();
    });
}