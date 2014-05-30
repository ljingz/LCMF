/* Column */
$(function(){	
	$(".column li div").live("mouseover", function(event){
		$(this).addClass("hover");
		var column = $(this).children("div").children("a");
		event.stopPropagation();
	}).live("mouseout", function(event){
		$(this).removeClass("hover");
		event.stopPropagation();
	});
});