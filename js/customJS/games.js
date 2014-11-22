$(function() { 
    $('#myCarousel').carousel({
        interval: 2000
    });
    
    $(".suggestGames").click(function() {
        $(".suggestGames").removeClass("blink");
    });
    
     $("#suggestFieldPie").click(function() {
        
        $("#suggestFieldPie").fadeOut("fast", function() {
            $(".navbar").show("fast");
            $("body").css("padding-top", 60);
            $("#suggestBC").css("margin-bottom", 0);
            $("#suggestField").fadeIn("slow");
            $("#suggestBC").fadeIn("slow");
            $("#suggestFieldPieLabel").fadeIn("slow");
        });
        
    });
    
    hideField();
    showDetails();
    
    $(".suggestGames").click(function() {
        
        $.ajax({
            url: 'showSuggestedGames.php', 
            success: function(response) {
                
                var flag = response === "<div class='alert alert-white'>"
                                            + "<i class='fa fa-lightbulb-o'></i>&nbsp&nbsp"
                                            + "Ooops, there is currently no suggestion on games that you might like. Tip: The tab will blink once there are new suggestions for you!"
                                        + "</div>";
                                
                $('#suggestField').html(response);  
                if(!flag) {
                    $("#searching").fadeIn("fast");
                    $.ajax({
                       url: 'showStat.php', 
                       success: function(response) {
                           if(response !== "false") {
                               response = $.parseJSON(response);
                           
                                var da = Array();

                                for(var game in response) {
                                    var temp = new Object();
                                    temp.label = game;
                                    temp.value = response[game];
                                    da.push(temp);
                                    break;
                                }
                                for(var game in response) {
                                    if(response[game] === da[0].value && game !== da[0].label) {
                                        da[0].label = da[0].label + "!-!-!" + game;
                                    }
                                    var temp = new Object();
                                    temp.label = game;
                                    temp.value = response[game];
                                    da.push(temp);
                                }


                                showPieChart(da);
                                setTimeout(function() {
                                    $("#searching").hide();
                                     //$("#suggestFieldPie").fadeIn("slow");
                                     $("#suggestField").fadeIn("slow");
                                     //$("#suggestFieldPieLabel").fadeIn("slow");
                                      $("#suggestBC").fadeIn("slow");
                                 }, 300);
                            } else {
                            
                                setTimeout(function() {
                                   $("#searching").hide();
                                    $("#suggestField").fadeIn("slow");
                                }, 300);
                            }
                       },
                       error: function() {
                           alert("Unable to load the stat.");
                       }
                    });
                } else {
                    $("#suggestField").fadeIn("slow");
                }
                
            },
            error: function() {
                alert("Unable to load the data.");
            }
        }).done(function() {
            
            showDetails();
            activateRemoveButton();
            activateDislikeButton();
            $(".showLiked").click(function() {
        
                $.ajax({
                    url: 'showLiked.php',
                    success: function(response) {
                        if(response !== "<div class='alert alert-white'><i class='fa fa-crosshairs'></i>&nbsp&nbspYour collection is empty because you have not liked any game yet!</div>") {
                            $("#sendToFriend").show();
                        }
                        document.getElementById('likedField').innerHTML = response;

                    },
                    error: function() {
                        alert("Unable to load liked game.");
                    }
                }).done(function() {
                    showDetails();
                    $("#liked-modal").modal("show");
                }); 
            });
            
            $(".suggestFF").click(function() {
                $(".suggestFF").removeClass("blink");
                
                
            });
            $(".suggestFU").click(function() {
                if(!$("#ourList").hasClass("outList")) {
                    document.getElementById('ourList').innerHTML = '<center><i class="fa fa-spinner fa-spin fa-5x"></i></center>';
                    $("#ourList").addClass("outList");
                } else {
                     $("#ourList").removeClass("outList");
                    return;
                }
                $(".suggestFU").removeClass("blink");
                 $.ajax({
                    url: 'showSuggestedBasedOnLike.php',
                    success: function(response) {
                        
                        document.getElementById('ourList').innerHTML = response;

                    },
                    error: function() {
                        alert("Unable to load the list.");
                    }
                }).done(function() {
                        showDetails();
                        activateRemoveButton();
                        activateDislikeButton();
                   });
            });
            
            $("#suggestBC").click(function() {
                $("#suggestField").hide();
                $("#suggestBC").hide();
                $(".navbar").hide("fast");
                $("body").css("padding-top", 0);
                $("#suggestFieldPie").fadeIn("slow");
                $("#suggestFieldPieLabel").hide();
            });
        });
        
        
    });
    
    $(".showLiked").click(function() {
        
        $.ajax({
            url: 'showLiked.php',
            success: function(response) {
                if(response !== "<div class='alert alert-white'><i class='fa fa-crosshairs'></i>&nbsp&nbspYour collection is empty because you have not liked any game yet!</div>") {
                    $("#sendToFriend").show();
                }
                document.getElementById('likedField').innerHTML = response;
                
            },
            error: function() {
                alert("Unable to load liked game.");
            }
        }).done(function() {
            showDetails();
            $("#liked-modal").modal("show");
        }); 
    });
    
    $(".shareGames").click(function() {
        var friend = $(".friendName").val();
        var send = "friend=" + $(".friendName").val();
         $.ajax({
            type: 'POST', 
            url: 'sendToFriend.php',
            data: send,
            success: function(response) {
                if(response == "user not found") {
                    var feedback = '<div class="alert alert-danger alert-dismissable">' +
                                        '<button type="button" class="close sendToFeedbackDismiss" data-dismiss="alert" aria-hidden="true" style="padding-top: 2px;"><i class="fa fa-times"></i></button>' +
                                        '<strong>Unable to send.</strong> It appears that your friend has not signed up yet or you had a typo.'+
                                    '</div>';
                } else if(response == "yourself") {
                     var feedback = '<div class="alert alert-danger alert-dismissable">' +
                                        '<button type="button" class="close sendToFeedbackDismiss" data-dismiss="alert" aria-hidden="true" style="padding-top: 2px;"><i class="fa fa-times"></i></button>' +
                                        '<strong>Unable to send.</strong> The name or email address you entered is associated with your account. Ask your friend to suggest you some games!'+
                                    '</div>';
                } else {
                    var feedback = '<div class="alert alert-info alert-dismissable">' +
                                        '<button type="button" class="close sendToFeedbackDismiss" data-dismiss="alert" aria-hidden="true" style="padding-top: 2px;"><i class="fa fa-times"></i></button>' +
                                        '<strong>Success!</strong> You have shared your favorite games with '+ friend + "!" +
                                    '</div>';
                    $(".friendName").val("");
                }
                
                
                
                $(".SENDTO").fadeOut("slow", function() {
                    $("#sendToFeedback").fadeIn("fast");
                    document.getElementById('sendToFeedback').innerHTML = feedback;
                });
                
            },
            error: function() {
                alert("Unable to connect to server.");
            }
        }).done(function() {
        }); 
        
    });
    
    $("#sendToFeedback").click(function() {
        $(".SENDTO").fadeIn("slow");
    });
});

function hideField() {
    $("#gameField").hide();
    $("#companyField").hide();
}

function showDetails() {
    $(".games").off("click");
     $(".companies").off("click");
     $(".games").click(function() {
         
        $("#liked-modal").modal("hide");
        var send = "game="+$(this).attr("name").replace(/!-!-!/g, "'");
        $.ajax({  
            type: 'POST',  
            url: 'showGameInfo.php', 
            data: send,
            success: function(response) {
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
                
                $("#gamesList").fadeOut("fast", function() {
                    $("#gameField").fadeIn("slow");
                    $("#Games").fadeIn("slow");
                });
                $("#myCarousel").fadeOut("fast");
                
            },
            error: function() {
                alert("Unable to load the game data.");
            }
        }).done(function() {
            $(".gameCarousel").click(function() {
                $(".gameCarousel").toggleClass('largeImages');
            });
            $('.carousel').carousel({
                interval: 1000
            });
            activateGameLikeButton();
        });
    });
    
     $(".companies").click(function() {
        var send = "company="+$(this).attr("name").replace(/!-!-!/g, "'");
        $.ajax({  
            type: 'POST',  
            url: 'showCompanyInfo.php', 
            data: send,
            success: function(response) {
                document.getElementById('companyField').innerHTML = response;
                $("#searchField").hide();
                $("#suggestFieldPie").hide();
                $("#suggestFieldPieLabel").hide();
                $("#companiesList").fadeOut("fast", function() {
                    $("#companyField").fadeIn("slow");
                    $("#Companies").fadeIn("slow");
                });
            },
            error: function() {
                alert("Unable to load the company data.");
            }
        }).done(function() {
            activateCompanyLikeButton();
        });
    });
   
}

function activateGameLikeButton() {
    $("#likeButton").click(function() {
        
        var name = $(this).attr("name").replace(/!-!-!/g, " ");
        var send = "game=" + name;
        
        if($("#likeButton").hasClass("unlike")) {
            $.ajax({  
                type: 'POST',  
                url: 'unlike.php', 
                data: send,
                success: function(response) {
                    document.getElementsByClassName("displayLikes")[0].innerHTML = "Total Likes: " + response;
                    document.getElementById("likeButton").innerHTML = '<i class="fa fa-thumbs-up"></i>&nbsp&nbsp<span class="justLike">Like</span><span class="like"> '+name+"</span>";
                    $("#likeButton").removeClass("unlike");
                },
                error: function() {
                    alert("Unable to connect to the server.");
                }
            }).done(function() {

            });
        } else {
            $.ajax({  
                type: 'POST',  
                url: 'like.php', 
                data: send,
                success: function(response) {
                    if(response == "please log in to like") {
                        alert("Please log in first.");
                        $(".loginTrigger").trigger("click");
                    } else {
                        document.getElementsByClassName("displayLikes")[0].innerHTML = "Total Likes: " + response;
                        suggestGames(send);
                        document.getElementById("likeButton").innerHTML = '<i class="fa fa-thumbs-up"></i>&nbsp&nbsp<span class="justLike">Unlike</span><span class="like"> '+name+"</span>";
                        $("#likeButton").addClass("unlike");
                    }

                },
                error: function() {
                    alert("Unable to connect to the server.");
                }
            }).done(function() {

            });
        }
    });
}

function activateCompanyLikeButton() {
    $("#likeButton").click(function() {
        
        var name = $(this).attr("name").replace(/!-!-!/g, " ");
        var send = "company=" + name;
        
        if($("#likeButton").hasClass("unlike")) {
            $.ajax({  
                type: 'POST',  
                url: 'unlike.php', 
                data: send,
                success: function(response) {
                    if(response == "please log in to like") {
                        alert("Please log in first.");
                        $(".loginTrigger").trigger("click");
                    } else {
                        document.getElementsByClassName("displayLikes")[0].innerHTML = "Total Likes: " + response;
                        document.getElementById("likeButton").innerHTML = '<i class="fa fa-thumbs-up"></i>&nbsp&nbsp<span class="justLike">Like</span><span class="like"> '+name+"</span>";
                        $("#likeButton").removeClass("unlike");
                    }

                },
                error: function() {
                    alert("Unable to connect to the server.");
                }
            }).done(function() {

            });
        } else {
            $.ajax({  
                type: 'POST',  
                url: 'like.php', 
                data: send,
                success: function(response) {
                    if(response == "please log in to like") {
                        alert("Please log in first.");
                        $(".loginTrigger").trigger("click");
                    } else {
                        document.getElementsByClassName("displayLikes")[0].innerHTML = "Total Likes: " + response;
                        document.getElementById("likeButton").innerHTML = '<i class="fa fa-thumbs-up"></i>&nbsp&nbsp<span class="justLike">Unlike</span><span class="like"> '+name+"</span>";
                        $("#likeButton").addClass("unlike");
                    }

                },
                error: function() {
                    alert("Unable to connect to the server.");
                }
            }).done(function() {

            });
        }
    });
}

function suggestGames(send) {
    $.ajax({  
        type: 'POST',  
        url: 'suggest.php', 
        data: send,
        success: function(response) {
            if(response == "please log in to like") {
                alert("Please log in first.");
                $(".loginTrigger").trigger("click");
            } else if(response == "no similar games") {
                
            } else {
                $(".suggestGames").addClass("blink");
            }
        },
        error: function() {
            alert("Unable to connect to the server.");
        }
    }).done(function() {
        showDetails();
    });
}

function activateRemoveButton() {
    $(".removeButton").click(function() {
        
        var id = this.id;
        
        var name = $(this).attr("name").replace(/!-!-!/g, " ");
        var send = "game=" + name;
         $.ajax({  
            type: 'POST',  
            url: 'removeSuggestedGame.php', 
            data: send,
            success: function() {
                $(document.getElementsByClassName("removeDiv")[id]).fadeOut("slow");
            },
            error: function() {
                alert("Unable to connect to the server.");
            }
        }).done(function() {
        });
    });
}

function activateDislikeButton() {
    
    
    $(".dislikeButton").click(function() {
        
        var id = this.id;
        
        var name = $(this).attr("name").replace(/!-!-!/g, " ");
        var send = "game=" + name +"&dislike=true";
        
         $.ajax({  
            type: 'POST',  
            url: 'removeSuggestedGame.php', 
            data: send,
            success: function() {
                $(document.getElementsByClassName("removeDiv")[id]).fadeOut("slow");
            },
            error: function() {
                alert("Unable to connect to the server.");
            }
        }).done(function() {
        });
        
    });
}
