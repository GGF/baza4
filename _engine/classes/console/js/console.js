	var cmsConsole_errors = 0;
	$(document).bind("keyup", function(e) {
		
		if (e.which == "120") {
			
			cmsConsole_main_show();
			cmsConsole_toggle();
			
		}
		
	});
	
	function cmsConsole_main_toggle() { $("#cmsConsoleMain").toggle(1, function(){ cmsConsole_margin(); }); }
	function cmsConsole_main_show()		{ $("#cmsConsoleMain").show(1, function(){ cmsConsole_margin(); }); }
	function cmsConsole_main_hide()		{ $("#cmsConsoleMain").hide(1, function(){ cmsConsole_margin(); }); }
	
	function cmsConsole_toggle() 			{ $("#cmsConsole").toggle(1, function(){ cmsConsole_margin(); }); }
	function cmsConsole_show()				{ $("#cmsConsole").show(1, function(){ cmsConsole_margin(); }); }
	function cmsConsole_hide()				{ $("#cmsConsole").hide(1, function(){ cmsConsole_margin(); }); }
	
	function cmsConsole_margin() {
		
		var obj = $("#cmsConsole");
		
		if (obj.css("display") == "none") {
			
			$("html").css({paddingBottom: 0});
			
		} else {
			
			$("html").css({paddingBottom: obj.height()});
			
		}
		
	}
	
	function cmsConsole_tab(id) {
		
		$(".cmsTabs td").removeClass("cmsTabs_sel1").removeClass("cmsTabs_sel2").removeClass("cmsTabs_sel3");
		
		$("#tab_" + id + "1").addClass("cmsTabs_sel1");
		$("#tab_" + id + "2").addClass("cmsTabs_sel2");
		$("#tab_" + id + "3").addClass("cmsTabs_sel3");
		
		$(".cmsConsole_tab").hide();
		$("#cmsConsole_tab_" + id).show();
		
	}
	
	
	
	function cmsConsole(text, pane)					{ cmsConsole_out(text, pane, ""); }
	function cmsConsole_error(text, pane)		{ cmsConsole_out(text, pane, "error"); }
	function cmsConsole_warning(text, pane) { cmsConsole_out(text, pane, "warning"); }
	function cmsConsole_notice(text, pane)	{ cmsConsole_out(text, pane, "notice"); }
	function cmsConsole_plain(text, pane)		{ cmsConsole_out(text, pane, "plain"); }
	
	function cmsConsole_out(text, pane, type) {
		
		if (!pane) pane = "console";
		
		if (type == "error") {
			
			cmsConsole_errors++;
			
			cmsConsole_show();
			cmsConsole_tab(pane);
			$("#cmsConsole_errors").html("<span class='error'>Ошибки: " + cmsConsole_errors + "</span>");
			
		}
		
		if (type) type = " cmsConsole_" + type;
		
		$("#cmsConsole_tab_" + pane).append("<div class='cmsConsole_line" + type + "'>" + text + "</div>");
		
	}

	function cmsConsole_clear() {
		$("#cmsConsole_tab_console").html('');
		$("#cmsConsole_tab_time").html('');
		$("#cmsConsole_tab_mysql").html('');
	}