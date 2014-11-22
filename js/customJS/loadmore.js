$(function() {
   loadMoreGames();
   loadMoreCompanies();
});

function loadMoreGames() {
     $(".gamesLM").click(function() {
         var name = $(this).attr("name");
        var send = "lastest=" + this.id + "&name=" + name; 
        var id = "G"+this.id;
        
        document.getElementById(id).innerHTML = '<center><i class="fa fa-spinner fa-3x fa-spin"></i></center>';
        
         $.ajax({  
            type: 'POST',  
            url: 'loadmore.php', 
            data: send,
            success: function(response) {
                document.getElementById(id).innerHTML = response;
                $("#"+id).show("slow");
            },
            error: function() {
                alert("Unable to connect to the server.");
            }
        }).done(function() {
            loadMoreGames();
            showDetails();
            removeGames();
        });
   });
}

function loadMoreCompanies() {
     $(".companiesLM").click(function() {
         var name = $(this).attr("name");
        var send = "lastestC=" + this.id + "&name=" + name; 
        var id = "C"+this.id;
        
        document.getElementById(id).innerHTML = '<center><i class="fa fa-spinner fa-3x fa-spin"></i></center>';
        
         $.ajax({  
            type: 'POST',  
            url: 'loadmore.php', 
            data: send,
            success: function(response) {
                document.getElementById(id).innerHTML = response;
                $("#"+id).show("slow");
            },
            error: function() {
                alert("Unable to connect to the server.");
            }
        }).done(function() {
            loadMoreCompanies();
            showDetails();
            removeCompanies();
        });
   });
}
