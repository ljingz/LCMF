/* Column */
$(function(){
	$(".column li").each(function(){
		$(this).live("mouseover", function(){
			//$(this).children(".btnAdd").show();
		}).live("mouseout", function(){
			//$(this).children(".btnAdd").hide();
		});
	});
	$(".column .btnAdd").live("click", function(){
		if($(this).siblings("ul").length == 0){
			$(this).parent("li").append("<ul></ul>");
		}
		$(this).siblings("ul").append("<li><input type='text' class='textInput' size='18' minlength='2' maxlength='40'/></li>");
	});
	$(".column .btnDel").live("click", function(){
		$(this).parent("li").remove();
	});
});