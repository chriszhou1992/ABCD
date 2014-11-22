$(function() {
    removeGames();
    removeCompanies();
    
});

function removeGames() {
    $(".removeGames").click(function() {
       var id = this.id;
       var name = $(this).attr("name").replace(/!-!-!/g, " ");
       
       var send = "game=" + name;
         $.ajax({  
            type: 'POST',  
            url: 'admin.php', 
            data: send,
            success: function() {
                $(document.getElementsByClassName("deleteGames")[id]).fadeOut("slow");
            },
            error: function() {
                alert("Unable to connect to the server.");
            }
        });
    });
}

function removeCompanies() {
    $(".removeCompanies").click(function() {
       var id = this.id;
       var name = $(this).attr("name").replace(/!-!-!/g, " ");
       
       var send = "company=" + name;
         $.ajax({  
            type: 'POST',  
            url: 'admin.php', 
            data: send,
            success: function() {
                $(document.getElementsByClassName("deleteCompanies")[id]).fadeOut("slow");
            },
            error: function() {
                alert("Unable to connect to the server.");
            }
        });
    });
}


