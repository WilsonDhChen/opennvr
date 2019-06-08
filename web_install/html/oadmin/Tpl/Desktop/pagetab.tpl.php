<div class="pagetab-item" id="pagetab_{$appid}" data-appid="{$appid}">
  <div class="pagetab-sidebar">
      <div class="sidebar-wrap">
		<volist name="naviB" id="tab">
        <dl data-navid="{$tab.navi_id}" class="sidetab-item<?php echo $key==0 ? ' sidetab-item-active':'';?><?php echo $tab['naviC']?' sidetab-item-navi':'';?>" <?php echo empty($tab['naviC'])?'onclick="OAUI.Pagetab.Open(this)" data-iframe="'.$tab['iframe'].'"':'';?> title="{$tab.navi_name}">
        	<dt>
                <!--<img class="site-icon" src="__STATIC__/icon/{$tab.navi_id}/48.png" alt="{$tab.navi_name}" width="48" height="48"  onerror="OAUI.Pagetab.Icon(this)">-->
                <i class="site-icon {$icons[$tab['navi_id']]}"></i>
                <p class="site-name">{$tab.navi_name}</p>
            </dt>
            <notempty name="tab['naviC']">
            <dd>
	            <ul class="side-submenu">
	            <volist name="tab['naviC']" id="sub">
	           		<li onclick="OAUI.Pagetab.Open(this)"  data-iframe="{$sub.iframe}" title="{$sub.navi_name}">{$sub.navi_name}</li>
	            </volist>
	            </ul>
            </dd>            
            </notempty>
        </dl>
        </volist>
      </div>
  </div>
<div class="pagetab-pagebar">
	<iframe src="<?php echo empty($pagetab_src) ? U('desktop/nothing') : $pagetab_src?>" frameborder="0" class="pagetab-iframe" onload="OAUI.Pagetab.Load(this)"></iframe>
</div>
</div>
