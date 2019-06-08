<ul>
	<volist name="apps" id="app">
    <li class="app-item" id="app_{$app.navi_id}" onclick="OAUI.App.Run({appid:'<?php echo $app['navi_id']?>',name:'<?php echo $app['navi_name']?>',icon:'<?php echo $icons[$app['navi_id']]; ?>'})" title="{$app.navi_name}">
    	<p class="app-inner">
	        <em class="app-icon"><i class="icon {$icons[$app['navi_id']]}"></i></em>
	        <span class="app-name">{$app.navi_name}</span>
        </p>
    </li>
    </volist>
</ul>
