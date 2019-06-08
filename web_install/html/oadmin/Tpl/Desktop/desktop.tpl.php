<ul>
	<volist name="apps" id="app">
	<li class="app-item" id="app_{$app.navi_id}" onclick="OAUI.App.Run({appid:'<?php echo $app['navi_id']?>',name:'<?php echo $app['navi_name']?>',icon:'__STATIC__/icon/<?php echo $app['navi_id']?>/32.png'})" title="{$app.navi_name}">
    	<p class="app-inner">
	        <img class="app-icon" src="__STATIC__/icon/{$app.navi_id}/48.png" alt="{$app.navi_name}" width="48" height="48">
	        <span class="app-name">{$app.navi_name}</span>
        </p>
    </li>
    </volist>
</ul>