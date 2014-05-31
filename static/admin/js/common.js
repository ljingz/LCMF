/* Column */
var Column = {
	rowHtml: "",
	init: function(){
		$(".column ul").each(function(){
			$(".btnUp,.btnDown", this).removeClass("disabled");
			$("> li:first > div > .btnUp", this).addClass("disabled");
			$("> li:last > div > .btnDown", this).addClass("disabled");
			$("> li", this).each(function(){
				$("> [name$='[sequence]']", this).val($(this).index());
			});
		});
	}
};

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
		children = $(("<li>" + Column.rowHtml + "</li>").replace(/#columnid#/gm, columnid).replace(/#parentid#/gm, parentid)).appendTo(children);
		children.find("div").attr("level", level).css("padding-left", (level * 20) + "px");
		children.find("input,select").removeClass("hidden");
		Column.init();
	});
	
	$(".column li div .btnDel").live("click", function(){
		$(this).parents("li:first").remove();
		Column.init();
	});
	
	$(".column li div .btnEdit").live("click", function(){
		$(this).siblings("span").each(function(){
			$(this).html($(this).children().removeClass("hidden"));
		});
	});
	
	$(".column li div .btnUp").live("click", function(){
		$(this).parents("li:first").insertBefore($(this).parents("li:first").prev("li"));
		Column.init();
	});
	
	$(".column li div .btnDown").live("click", function(){
		$(this).parents("li:first").insertAfter($(this).parents("li:first").next("li"));
		Column.init();
	});
});

/* Table */
var Table = {
	init: function(){
		$(".pageFormContent .itemDetail .field").each(function(){
			$("> tr", this).each(function(){
				$("[name$='[sequence]']", this).val($(this).index());
			});
		});
	}
};
$(function(){
	$(".pageFormContent .itemDetail .field .btnUp").live("click", function(){
		$(this).parents("tr:first").insertBefore($(this).parents("tr:first").prev("tr"));
		Table.init();
	});
	
	$(".pageFormContent .itemDetail .field .btnDown").live("click", function(){
		$(this).parents("tr:first").insertAfter($(this).parents("tr:first").next("tr"));
		Table.init();
	});
});
