$(function() {
    var padding = ($(window).height()-$('#Games').height()-80)/2.0;
    $('.modal-dialog').css('padding-top', padding);
    
    
    $(".settingCogs").click(function() {
        $(".settingCogs").toggleClass("fa-spin");
    });
    
    $("#resetPasswordTitle").click(function() {
        $(".settingCogs").toggleClass("fa-spin");
    });
    
    $(".NEWPASSWORD").hide();
    $(".CONFIRMNEWPASSWORD").hide();
    
    $(".resetpassword").keyup(function() {
        var send = "checkPassword="+$(".resetpassword").val();
        $.ajax({  
            type: 'POST',  
            url: 'resetPassword.php', 
            data: send,
            success: function(response) {
                if(response === "Password verified!") {
                    $(".RESETPASSWORD").fadeOut("fast", function() {
                        $(".NEWPASSWORD").fadeIn("slow");
                        $(".resetpassword").val("");
                    });
                }
            },
            error: function() {
                alert("Unable to comfirm the password.");
            }
        });
    });
    
    $(".checkNewPassword").click(function() {
        $(".NEWPASSWORD").fadeOut("fast", function() {
            $(".CONFIRMNEWPASSWORD").fadeIn("slow");
        });
    });
    
     $(".confirmNewPassword").click(function() {
        var newpassword = $(".newpassword").val();
        var confirm = $(".confirmnewpassword").val();
        $(".newpassword").val("");
        $(".confirmnewpassword").val("");
        if(newpassword !== confirm) {
            var feedback = '<div class="alert alert-danger alert-dismissable">' +
                                '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                                '<strong>Reset password Failed!</strong> The new passwords you entered are inconsistent.' +
                            '</div>';
            $(".CONFIRMNEWPASSWORD").fadeOut("slow", function() {
                document.getElementById("resetPasswordFeedback").innerHTML = feedback;
            });
            return;
        }
        if(newpassword.length < 6 || confirm.length < 6) {
             var feedback = '<div class="alert alert-danger alert-dismissable">' +
                                '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                                '<strong>Reset password Failed!</strong> The new password you entered should contain at least 6 characters.' +
                            '</div>';
            $(".CONFIRMNEWPASSWORD").fadeOut("slow", function() {
                document.getElementById("resetPasswordFeedback").innerHTML = feedback;
            });
            
            return;
        }
        
        
        var send = "resetPassword="+confirm;
        $.ajax({  
            type: 'POST',  
            url: 'resetPassword.php', 
            data: send,
            success: function(response) {
                if(response === "success") {
                    var feedback = '<div class="alert alert-success alert-dismissable">' +
                                        '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                                        '<strong>Password reset successfully!</strong> Your password has been changed!' +
                                    '</div>';
                    $(".CONFIRMNEWPASSWORD").fadeOut("slow", function() {
                        document.getElementById("resetPasswordFeedback").innerHTML = feedback;
                        $("#resetPasswordTitle").trigger("click");
                    });
                } else {
                    var feedback = '<div class="alert alert-danger alert-dismissable">' +
                                        '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                                        '<strong>Reset password Failed!</strong> Your password has not been changed!' +
                                    '</div>';
                    $(".CONFIRMNEWPASSWORD").fadeOut("slow", function() {
                        document.getElementById("resetPasswordFeedback").innerHTML = feedback;
                    });
                }
            },
            error: function() {
                alert("Unable to connect to the server.");
            }
        });
    });
    
    $("#resetPasswordFeedback").click(function() {
        $(".RESETPASSWORD").fadeIn("slow");
    });
    
    
    $(".name").popover({trigger: "manual", placement: "top", content: "<span class='popover'>Name needs to be composed of at least 6 English alphabets.</span>"});
    $('.name').popover('hide');
    
    $(".name").focusout(function() {
        var input = $(".name").val();
        if(input === "") {
            if($(".NAME").hasClass("has-success")) {
                var progress = parseInt(document.getElementById("progressIndicator").innerHTML);
                progress -= 1;
                document.getElementById("progressIndicator").innerHTML = progress;
                progress = progress * 25;
                var width = progress + "%";
                $(".signUpprogress").css("width", width);
            }
            $(".NAME").removeClass("has-success");
            $(".NAME").removeClass("has-error");
            $('.name').popover('hide');
            $("#signupButton").attr("disabled", true);
            return;
        }
        
        var send = "email=no&password=no&confirm=no&name=" + input;
        $.ajax({  
            type: 'POST',  
            url: 'validate.php', 
            data: send,
            success: function(response) {
                if(response === "true") {
                    if(!$(".NAME").hasClass("has-success")) {
                        var progress = parseInt(document.getElementById("progressIndicator").innerHTML);
                        progress += 1;
                        document.getElementById("progressIndicator").innerHTML = progress;
                        progress = progress * 25;
                        var width = progress + "%";
                        $(".signUpprogress").css("width", width);
                    }
                    $(".NAME").addClass("has-success");
                    $(".NAME").removeClass("has-error");
                    $('.name').popover('hide');
                    if(completed()) {
                        $("#signupButton").attr("disabled", false);
                    }
                } else {
                    if(response === "exists") {
                        $('.name').attr("data-content", "User with this name already exists.");
                    } else if(response === "invalid") {
                        $('.name').attr("data-content", "Name can not be an email address.");
                    } else {
                        $('.name').attr("data-content", "Name needs to be composed of at least 6 English alphabets.");
                    }
                    if($(".NAME").hasClass("has-success")) {
                        var progress = parseInt(document.getElementById("progressIndicator").innerHTML);
                        progress -= 1;
                        document.getElementById("progressIndicator").innerHTML = progress;
                        progress = progress * 25;
                        var width = progress + "%";
                        $(".signUpprogress").css("width", width);
                    }
                    $('.name').popover('show');
                    $(".NAME").addClass("has-error");
                    $(".NAME").removeClass("has-success");
                    $("#signupButton").attr("disabled", true);
                }
            },
            error: function() {
                alert("Unable to validate the input.");
            }
        });
    });
    
    
    $(".email").popover({trigger: "manual", placement: "top", content: "The email address is not valid."});
    $('.email').popover('hide');
    
    $(".email").focusout(function() {
        var input = $(".email").val();
        if(input === "") {
            if($(".EMAIL").hasClass("has-success")) {
                var progress = parseInt(document.getElementById("progressIndicator").innerHTML);
                progress -= 1;
                document.getElementById("progressIndicator").innerHTML = progress;
                progress = progress * 25;
                var width = progress + "%";
                $(".signUpprogress").css("width", width);
            }
            $(".EMAIL").removeClass("has-success");
            $(".EMAIL").removeClass("has-error");
            $('.email').popover('hide');
            $("#signupButton").attr("disabled", true);
            return;
        }
        
        var send = "password=no&confirm=no&name=no&email=" + input;
        $.ajax({  
            type: 'POST',  
            url: 'validate.php', 
            data: send,
            success: function(response) {
                if(response === "true") {
                    if(!$(".EMAIL").hasClass("has-success")) {
                        var progress = parseInt(document.getElementById("progressIndicator").innerHTML);
                        progress += 1;
                        document.getElementById("progressIndicator").innerHTML = progress;
                        progress = progress * 25;
                        var width = progress + "%";
                        $(".signUpprogress").css("width", width);
                    }
                    $(".EMAIL").addClass("has-success");
                    $(".EMAIL").removeClass("has-error");
                    $('.email').popover('hide');
                    if(completed()) {
                        $("#signupButton").attr("disabled", false);
                    }
                } else {
                    if($(".EMAIL").hasClass("has-success")) {
                        var progress = parseInt(document.getElementById("progressIndicator").innerHTML);
                        progress -= 1;
                        document.getElementById("progressIndicator").innerHTML = progress;
                        progress = progress * 25;
                        var width = progress + "%";
                        $(".signUpprogress").css("width", width);
                    }
                    if(response === "exists") {
                        $('.email').attr("data-content", "User with this email address already exists.");
                    } else {
                        $('.email').attr("data-content", "The email address is not valid.");
                    }
                    $('.email').popover('show');
                    $(".EMAIL").addClass("has-error");
                    $(".EMAIL").removeClass("has-success");
                    $("#signupButton").attr("disabled", true);
                }
            },
            error: function() {
                alert("Unable to validate the input.");
            }
        });
    });
    
    $(".password").popover({trigger: "manual", placement: "top", content: "Password needs to be composed of at least 6 characters."});
    $('.password').popover('hide');
    
    $(".password").focusout(function() {
        var input = $(".password").val();
        if(input === "") {
            if($(".PASSWORD").hasClass("has-success")) {
                var progress = parseInt(document.getElementById("progressIndicator").innerHTML);
                progress -= 1;
                document.getElementById("progressIndicator").innerHTML = progress;
                progress = progress * 25;
                var width = progress + "%";
                $(".signUpprogress").css("width", width);
            }
            $(".PASSWORD").removeClass("has-success");
            $(".PASSWORD").removeClass("has-error");
            $('.password').popover('hide');
            if($(".CONFIRM").hasClass("has-success")) {
                var progress = parseInt(document.getElementById("progressIndicator").innerHTML);
                progress -= 1;
                document.getElementById("progressIndicator").innerHTML = progress;
                progress = progress * 25;
                var width = progress + "%";
                $(".signUpprogress").css("width", width);
            }
            $(".CONFIRM").removeClass("has-success");
            $(".CONFIRM").removeClass("has-error");
            $(".confirm").val("");
            $("#signupButton").attr("disabled", true);
            return;
        }
        
        var send = "email=no&confirm=no&name=no&password=" + input;
        $.ajax({  
            type: 'POST',  
            url: 'validate.php', 
            data: send,
            success: function(response) {
                if(response === "true") {
                    if(!$(".PASSWORD").hasClass("has-success")) {
                        var progress = parseInt(document.getElementById("progressIndicator").innerHTML);
                        progress += 1;
                        document.getElementById("progressIndicator").innerHTML = progress;
                        progress = progress * 25;
                        var width = progress + "%";
                        $(".signUpprogress").css("width", width);
                    }
                    $(".PASSWORD").addClass("has-success");
                    $(".PASSWORD").removeClass("has-error");
                    $('.password').popover('hide');
                    if(completed()) {
                        $("#signupButton").attr("disabled", false);
                    }
                    if($(".confirm").val() !== $(".password").val()) {
                        if($(".CONFIRM").hasClass("has-success")) {
                            var progress = parseInt(document.getElementById("progressIndicator").innerHTML);
                            progress -= 1;
                            document.getElementById("progressIndicator").innerHTML = progress;
                            progress = progress * 25;
                            var width = progress + "%";
                            $(".signUpprogress").css("width", width);
                        }
                        $(".CONFIRM").removeClass("has-success");
                        $(".CONFIRM").removeClass("has-error");
                        $(".confirm").val("");
                        $("#signupButton").attr("disabled", true);
                    }
                } else {
                    if($(".PASSWORD").hasClass("has-success")) {
                        var progress = parseInt(document.getElementById("progressIndicator").innerHTML);
                        progress -= 1;
                        document.getElementById("progressIndicator").innerHTML = progress;
                        progress = progress * 25;
                        var width = progress + "%";
                        $(".signUpprogress").css("width", width);
                    }
                    $('.password').popover('show');
                    $(".PASSWORD").addClass("has-error");
                    $(".PASSWORD").removeClass("has-success");
                    if($(".CONFIRM").hasClass("has-success")) {
                        var progress = parseInt(document.getElementById("progressIndicator").innerHTML);
                        progress -= 1;
                        document.getElementById("progressIndicator").innerHTML = progress;
                        progress = progress * 25;
                        var width = progress + "%";
                        $(".signUpprogress").css("width", width);
                    }
                    $(".CONFIRM").removeClass("has-success");
                    $(".CONFIRM").removeClass("has-error");
                    $(".confirm").val("");
                    $("#signupButton").attr("disabled", true);
                }
            },
            error: function() {
                alert("Unable to validate the input.");
            }
        });
    });
    
    $(".confirm").popover({trigger: "manual", placement: "top", content: "Passwords do not match."});
    $('.confirm').popover('hide');
    
    $(".confirm").focusout(function() {
        var input = $(".confirm").val();
        if(input === "") {
            if($(".CONFIRM").hasClass("has-success")) {
                var progress = parseInt(document.getElementById("progressIndicator").innerHTML);
                progress -= 1;
                document.getElementById("progressIndicator").innerHTML = progress;
                progress = progress * 25;
                var width = progress + "%";
                $(".signUpprogress").css("width", width);
            }
            $(".CONFIRM").removeClass("has-success");
            $(".CONFIRM").removeClass("has-error");
            $('.confirm').popover('hide');
            $("#signupButton").attr("disabled", true);
            return;
        }
        
        var send = "password="+$(".password").val()+"&email=no&name=no&confirm=" + input;
        $.ajax({  
            type: 'POST',  
            url: 'validate.php', 
            data: send,
            success: function(response) {
                if(response === "true") {
                    if(!$(".CONFIRM").hasClass("has-success")) {
                        var progress = parseInt(document.getElementById("progressIndicator").innerHTML);
                        progress += 1;
                        document.getElementById("progressIndicator").innerHTML = progress;
                        progress = progress * 25;
                        var width = progress + "%";
                        $(".signUpprogress").css("width", width);
                    }
                    $(".CONFIRM").addClass("has-success");
                    $(".CONFIRM").removeClass("has-error");
                    $('.confirm').popover('hide');
                    if(completed()) {
                        $("#signupButton").attr("disabled", false);
                    }
                } else {
                    if($(".CONFIRM").hasClass("has-success")) {
                        var progress = parseInt(document.getElementById("progressIndicator").innerHTML);
                        progress -= 1;
                        document.getElementById("progressIndicator").innerHTML = progress;
                        progress = progress * 25;
                        var width = progress + "%";
                        $(".signUpprogress").css("width", width);
                    }
                    $('.confirm').popover('show');
                    $(".CONFIRM").addClass("has-error");
                    $(".CONFIRM").removeClass("has-success");
                    $("#signupButton").attr("disabled", true);
                }
            },
            error: function() {
                alert("Unable to validate the input.");
            }
        });
    });
    
    $("#signupButton").click(function(event) {
        event.preventDefault();
        
        var name = $(".name").val();
        var password = $(".password").val();
        
        var send = "password="+$(".password").val()+"&email="+$(".email").val()+"&name="+$(".name").val();
        
         $.ajax({  
            type: 'POST',  
            url: 'createNewAccount.php', 
            data: send,
            success: function(response) {
                if(response === "success") {
                    $(".name").val("");
                    $(".NAME").removeClass("has-success");
                    $(".email").val("");
                    $(".EMAIL").removeClass("has-success");
                    $(".password").val("");
                    $(".PASSWORD").removeClass("has-success");
                    $(".confirm").val("");
                    $(".CONFIRM").removeClass("has-success");
                    document.getElementById("progressIndicator").innerHTML = "0";
                    $(".signUpprogress").css("width", "0%");
                }
               
            },
            error: function() {
                alert("Unable to create the account.");
            }
        }).done(function() {
            $(".loginTrigger").trigger("click");
            $(".loginuser").val(name);
            $(".loginpassword").val(password);
        });
    });
    
    login();
    logout();
});

function completed() {
    return $(".NAME").hasClass("has-success") && $(".EMAIL").hasClass("has-success") && $(".PASSWORD").hasClass("has-success") && $(".CONFIRM").hasClass("has-success");
}

function hideSignupForm() {
    $(".PROGRESS").fadeOut("slow");
    $(".NAME").fadeOut("slow");
    $(".EMAIL").fadeOut("slow");
    $(".PASSWORD").fadeOut("slow");
    $("#signupButton").fadeOut("slow");
    $(".signupHR").fadeOut("slow");
    $(".CONFIRM").fadeOut("slow", function() {
        $("#signupSuccess").fadeIn("fast");
    });
}

function login() {
    $("#loginButton").click(function(event) {
        event.preventDefault();
        
        if($(".loginuser").val() === "" || $(".loginpassword").val() === "") {
            $("#login-modal").modal("hide");
            return;
        }
        
        var send = "user="+$(".loginuser").val()+"&password="+$(".loginpassword").val();
        
        $.ajax({  
            type: 'POST',  
            url: 'login.php', 
            data: send,
            success: function(response) {
                if(response === "admin") {
                    location.reload();
                } else if(response !== "Invalid password" && response !== "The account does not exist.") {
                   
                    document.getElementsByClassName("dropdownLog")[0].innerHTML = '<li><a href=#><i class="fa fa-fighter-jet"></i>&nbspLogged in as ' + response + '</a></li>'
                                            + '<li><a href=# id="destroySession"><i class="fa fa-sign-out"></i>&nbsp&nbspLog out</a></li>'
                                            + '<li class="divider"></li>'
                                            + '<li class="showLiked"><a href="#" data-toggle="modal"><i class="fa fa-trophy"></i>&nbsp&nbspGames liked</a></li>';
                    
                    $(".users").addClass("fa-user");
                    $(".users").removeClass("fa-users");
                    $(".usersCaret").addClass("caretBrown");
                    $(".customInput").hide();
                    $(".suggestGames").show("fast", function() {
                        resizeSearchBar();
                        $(".customInput").show("slow");
                    });
                    
                    $("#suggestFieldPie").html("");
                    
                    
                    $("#login-modal").modal("hide");
                    $("#account-modal").modal("show");
                    $(".suggestGames").trigger("click");
                } else if(response === "Invalid password") {
                    var feedback = '<div class="alert alert-white alert-dismissable">' + '<hr>' +
                                        '<button type="button" class="close customCloseLogin" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                                        '<i class="fa fa-exclamation-triangle customWarning"></i>&nbsp&nbspThe password is not correct.' +
                                    '</div>';
                    document.getElementById("loginFeedback").innerHTML = feedback;
                } else if(response === "The account does not exist.") {
                    var feedback = '<div class="alert alert-white alert-dismissable">' + '<hr>' +
                                        '<button type="button" class="close customCloseLogin" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                                        '<i class="fa fa-exclamation-triangle customWarning"></i>&nbsp&nbspThe username or email address does not exist.' +
                                    '</div>';
                    document.getElementById("loginFeedback").innerHTML = feedback;
                }
            },
            error: function() {
                alert("Unable to log in.");
            }
        }).done(function() {
            logout();
        });
    });
    
    
}

function logout() {
    $("#destroySession").click(function(event) {
        
        event.preventDefault();
        var send = "destroySession=true";
        
         $.ajax({  
            type: 'POST',  
            url: 'login.php', 
            data: send,
            success: function(response) {
                if(response === "admin") {
                    location.reload();
                } else if(response === "success") {
                   
                    var changeTo = '<li><a href="#login-modal" data-toggle="modal" class="loginTrigger"><i class="fa fa-sign-in"></i>&nbsp&nbsp Log in</a></li>'
                                    + '<li class="divider"></li>'
                                    + '<li><a href="#signup-modal" data-toggle="modal"><i class="fa fa-rocket"></i>&nbsp&nbsp Sign up</a></li>';
                    document.getElementsByClassName("dropdownLog")[0].innerHTML = changeTo;
                     
                    $(".users").addClass("fa-users");
                    $(".users").removeClass("fa-user");
                    $(".usersCaret").removeClass("caretBrown");
                    $(".suggestGames").hide("fast", function() {
                        resizeSearchBar();
                        $(".customInput").show("fast");
                    });
                    $("#suggestField").hide();
                     
                    $(".loginuser").val("");
                    $(".loginpassword").val("");
                    document.getElementById("loginFeedback").innerHTML = "";
                    
                    $(".Home").trigger("click");
                }
            },
            error: function() {
                alert("Unable to log out.");
            }
        });
    });
}



