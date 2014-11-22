$(function(){
	var selection = null;
	
	$("#companyField").on("mouseenter", "div.emptyTag", function() {
		$(this).find("i").removeClass("fa-plus")
				.addClass("fa-plus-circle");

		//$(this).animate({backgroundColor: "#0000AA"}); needs Jquery.Color plugin
		selection = d3.select(".emptyTag");
		selection.transition().style("background-color", "#8888CC");

	}).on("mouseleave", "div.emptyTag", function() {
		$(this).find("i").removeClass("fa-plus-circle").addClass("fa-plus");

		selection.transition()
			.style("background-color", "white")
			.style("color", "black");
	});
	
	$("#companyField").on("click", "div.emptyTag", function() {
			selection.style("color", "rgba(255, 255, 255, 1)");

			$("#tagButton").css("display", "inline-block");
			$(".tagForm").css("display", "inline-block");

			console.log("reached here");
			$(this).animate({opacity: 0}, {complete: function() {
					$(this).html("");
				}
			});
		});

	//click handler for submitting new tag form
	$("#companyField").on("click", "#tagButton", function() {
		
		var form = $("#tags").find(".tagForm"),
			content = form.val();

		form.removeClass("has-error");
		if (content && content.length === 0)	//no content entered
		{
			form.attr("data-content", "You haven't entered any content yet!");
			form.addClass("has-error");
			return;
		}
		
		var send = "content=" + content + "&&id=" + form.attr("belong_id") + "&&type=company";
		$.ajax({
			type: 'POST',
			url: 'modifyTag.php',
			data: send,
			success: function(response) {
				//$(".emptyTag").css("display", "none").removeClass("emptyTag");
				$(".emptyTag").html("").removeClass("emptyTag");
				$("#tags").append(response);
				//$(".emptyTag").css("display", "inline");
				//$('#likedField').html(response);
			},
			error: function() {
				alert("Unable to insert tag.");
			}
		}).done(function() {
			$(this).css("display", "none");
			$("#tagButton").css("display", "none");
			$("#tags").find(".tagForm").css("display", "none").html("");
		});
	});//click handler

	$("#companyField").on("click", "div.tag", function() {
		$(this).css("border-width", "5px");
	});

	$("#companyField").on("mouseenter", "div.tag", function() {
		$(this).css("border-width", "4px");
		var author = $(this).attr("author");
		var likes = $(this).attr("likes");

		if (typeof(author) !== "undefined") {
			$(this).find("i").addClass("fa fa-times")
					.css("padding-left", "8px");
		}

		var tagLikes = $(this).find("div");

		//console.log('like= <i class="fa fa-heart-o></i>' + " " + likes + " ");
		
		tagLikes.html(" " + likes + " " + '<i class="fa fa-heart-o"></i>');

	}).on("mouseleave", "div.tag", function() {
		$(this).css("border-width", "2px");

		$(this).find("i.fa-times").removeClass("fa fa-times")
				.css("padding-left", "0px");

		var tagLikes = $(this).find(".tagLikes");
		tagLikes.html('');
		
		/*selection.transition()
			.style("background-color", "white")
			.style("color", "black");*/
	});

	/*****like function*****/
	$("#companyField").on("mouseenter", "div.tagLikes i", function() {
		$(this).removeClass("fa fa-heart-o");
		$(this).addClass("fa fa-heart");

		$(this).css("color", "white");
	}).on("mouseleave", "div.tagLikes i", function() {
		$(this).css("color", "black");

		$(this).removeClass("fa fa-heart");
		$(this).addClass("fa fa-heart-o");
	});

	$("#companyField").on("click", "div.tagLikes i", function() {
		//console.log("%o", (this));
		var grandParent = $(this).parent().parent();

		var likes = parseInt(grandParent.attr("likes")) + 1,
			id = grandParent.attr("tag_id");

		var likeArea = $(this).parent();
		
		var send = "likes=" + likes + "&&id=" + id + "&&type=company";
		$.ajax({
			type: 'POST',
			url: 'modifyTag.php',
			data: send,
			success: function(response) {
				console.log(response);
				//$(".tag").html(response);
				if (response === "success") {
					successFlag = true;
					console.log(likeArea.html());
					likeArea.animate({opacity: 0}, {
						complete: function() {
							//var originalHTML = likeArea.html();
							likeArea.html(" " + likes + ' <i class="fa fa-heart"></i>');
							likeArea.parent().attr("likes", likes);
							console.log(" " + likes + ' <i class="fa fa-heart"></i>');
							likeArea.animate({opacity:1});
						}
					});
				}
				else if (response === "log in error") {
					alert("You need to log in to promote tags!");
					$("#login-modal").modal("show");
				}
				else
					alert("You have already promoted this tag!");
			},
			error: function() {
				alert("Unable to promote tag.");
			}
		}).done(function() {

		});
		
	});

	/*****delete function*****/
	$("#companyField").on("mouseenter", "div.tag i", function() {
		$(this).css("color", "white");
	}).on("mouseleave", "div.tag i", function() {
		$(this).css("color", "black");
	});

	$("#companyField").on("click", "div.tag i.fa-times", function() {
		$(this).parent().animate({opacity: 0}, {complete: function() {
				$(this).css("display", "none");
			}
		});

		var parent = $(this).parent();
		var id = parent.attr("tag_id");

		var send = "id=" + id + "&&type=company";
		$.ajax({
			type: 'POST',
			url: 'modifyTag.php',
			data: send,
			success: function(response) {
				console.log(response);
				//$(".tag").html(response);
				if (response === "success") {
					
				}
				else if (response === "log in error")
					alert("You need to log in to delete tags!");
				else
					alert("Error occured in deletion! Check your connection");
			},
			error: function() {
				alert("Unable to delete tag.");
			}
		});
	});
});