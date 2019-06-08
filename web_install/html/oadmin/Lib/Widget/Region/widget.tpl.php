<eq name="count" value="0">
<script type="text/javascript">
if(typeof(jQuery)==='function'){
$(function(){
	
	$('select.region-select').change(function(){
		
		var $this = $(this);
		//如果总level等于当前select的level 则无需再调用数据
		if($this.attr('data-select-level')==$this.attr('data-region-level')){
			return ;	
		}
		
		var id = $this.val();
		if(id==0){
			return ;	
		}
		
		$.getJSON('<?php echo $region_ajax_api?>',{id:id},function(response){
			if(!response){
				return ;	
			}
				
			//清空联动子级数据option
			$this.nextAll().each(function(){
				$(this).find("option[value!=0]").remove();
			})
			//region 下一级
			$next = $this.next();
			var option_html = '';
		  	$.each(response, function(i,item){
				option_html+='<option value="'+item.id+'">'+item.<?php echo $region_name_field;?>+'</option>';
		  	});
			$next.append(option_html);			
			
		});
		
	})
	
	//解决火狐等浏览器 select缓存
	$("select.region-select[data-select-level=1]").each(function(){
		
		if($(this).val()>0){
			if($(this).attr('data-selected')=='0'){
				$(this).trigger('change');	
			}else{
				$(this).nextAll('select').addBack().each(function(){
					$(this).find('option[value='+$(this).attr('data-selected')+']').prop({selected:true})	
				})
					
			}
			
		}
	})		
	


		
})
}
</script>
</eq>
<volist name="region_data" id="region">
<select name="{$region.select_name}"  title="{$region.area_name}" class="region-select" data-region-level="{$level}" data-select-level="{$region.level}" data-selected="{$region.selected|default=0}">
	<option value="0">{$region.area_name}</option>
	<volist name="region['options']" id="option">
	<option value="{$option.id}" <?php echo $region['selected']==$option['id'] ? 'selected':''; ?> >{$option[$region_name_field]}</option>
	</volist>
</select>
</volist>
