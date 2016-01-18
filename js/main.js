(function(window, $){
	"use strict";
	
	$(document).foundation();
	$(document).ready(function() {
		$(".rf-accounts-table").dataTable({
			"lengthChange": false,
			"pageLength": 50
		});
		
		var nowTemp = new Date();
		var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
		
		$(".cb-datefield").fdatepicker({
			"onRender": function (date) {
				return date.valueOf() < now.valueOf() ? "disabled" : "";
			}
		});
		
		setTimeout(function(){
			$(".icon-chevron-left").addClass("fa fa-chevron-left").removeClass("icon-chevron-left");
			$(".icon-chevron-right").addClass("fa fa-chevron-right").removeClass("icon-chevron-right");
		}, 1500);
	});
})(window, jQuery);