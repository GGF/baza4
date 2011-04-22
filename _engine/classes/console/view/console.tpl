<div id='cmsConsole' style='display: none'>
	<table class='frame fullHeight'>
		<tr><td colspan='2'><div id='cmsConsoleMain' style='display: none'>
			<table class='frame fullHeight'>
				<tr><td colspan='3'>
					<table class='cmsTabs frame fullHeight'>
						<tr>
							<td class='cmsTabs_first' nowrap></td>
							<td class='cmsTabs_norm1 cmsTabs_sel1' id='tab_console1' nowrap></td>
							<td class='cmsTabs_norm2 cmsTabs_sel2' id='tab_console2' nowrap><a href='javascript:cmsConsole_tab("console");' class='tab' onMouseOver='cmsTab_over("_console")' onMouseOut='cmsTab_out("_console")'>Консоль</a></td>
							<td class='cmsTabs_norm3 cmsTabs_sel3' id='tab_console3' nowrap></td>
							<td class='cmsTabs_norm1' id='tab_time1' nowrap></td>
							<td class='cmsTabs_norm2' id='tab_time2' nowrap><a href='javascript:cmsConsole_tab("time");' class='tab' onMouseOver='cmsTab_over("_time")' onMouseOut='cmsTab_out("_time")'>Время</a></td>
							<td class='cmsTabs_norm3' id='tab_time3' nowrap></td>
							<td class='cmsTabs_norm1' id='tab_mysql1' nowrap></td>
							<td class='cmsTabs_norm2' id='tab_mysql2' nowrap><a href='javascript:cmsConsole_tab("mysql");' class='tab' onMouseOver='cmsTab_over("_mysql")' onMouseOut='cmsTab_out("_mysql")'>MySQL</a></td>
							<td class='cmsTabs_norm3' id='tab_mysql3' nowrap></td>
							<td class='cmsTabs_last' width='100%' align='right' nowrap><a href='javascript: cmsConsole_main_hide(); void(0);'><img src='{$lego->getWebDir()}/view/css/ui/close.gif'></a></td>
						</tr>
					</table>
				</td></tr>
				<tr>
					<td class='cmsTabs_content cmsConsole_content'>
						<div class='cmsConsole_tab' id='cmsConsole_tab_console'></div>
						<div class='cmsConsole_tab' id='cmsConsole_tab_time' style='display: none'></div>
						<div class='cmsConsole_tab' id='cmsConsole_tab_mysql' style='display: none'></div>
					</div></td>
				</tr>
			</table>
	
	
		</td></tr>
		<tr onclick='cmsConsole_main_toggle()'>
			<td class='cmsConsole_text' width='100%'>Отчет Osmio CMS <small>(щелкните для открытия)</small></td>
			<td class='cmsConsole_text' id='cmsConsole_errors' nowrap>Нет ошибок</td>
		</tr>
	</table>
</div>
{$scripts}