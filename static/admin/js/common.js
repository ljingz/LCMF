/* Column */
$(function(){	
	$(".column li div").live("mouseover", function(event){
		$(this).addClass("hover");
		event.stopPropagation();
	}).live("mouseout", function(event){
		$(this).removeClass("hover");
		event.stopPropagation();
	});
	
	$(".column li div .btnAdd").live("click", function(){
		var columnid = "tmp" + new Date().getTime();
		var parentid = $(this).parents("div[columnid]:first").attr("columnid");
		var level = parseInt($(this).parents("div[level]:first").attr("level")) + 1;
		var children = $(this).parent().next("ul");
		if(children.length == 0){
			$(this).parent().after("<ul></ul>");
			children = $(this).parent().next("ul");
		}
		children = $(("<li>" + rowHtml + "</li>").replace(/#columnid#/gm, columnid).replace(/#parentid#/gm, parentid)).appendTo(children);
		children.find("div").attr("level", level).css("padding-left", (level * 20) + "px");
		children.find("input,select").removeClass("hidden");
	});
	
	$(".column li div .btnDel").live("click", function(){
		$(this).parents("li:first").remove();
	});
	
	$(".column li div .btnEdit").live("click", function(){
		$(this).siblings("span").each(function(){
			$(this).html($(this).children().removeClass("hidden"));
		});
	});
});