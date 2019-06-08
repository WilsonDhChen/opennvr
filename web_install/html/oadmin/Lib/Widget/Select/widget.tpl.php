<select name="{$select_name}" <?php echo empty($disabled)?'':'disabled="disabled"'?> class="widget-select widget-select-<?php echo $index?>" data-selected="<?php echo empty($selected)?'':implode(',',$selected)?>" id="widgetSelect_<?php echo $index?>" onChange="widgetSelectChange_<?php echo $index?>(this)">
    <?php echo $placeholder?>
	<notempty name="top_name">
    <option value="{$top_id}">{$top_name}</option>
    </notempty>
<volist name="data" id="option">
	<option value="{$option[$node_id]}" <?php echo (isset($selected[0]) && $option[$node_id]==$selected[0])?'selected="selected"':''?> >{$option[$node_name]}</option>
</volist>    
</select>
<script type="text/javascript">


$('#widgetSelect_<?php echo $index?>').trigger('change');

function widgetSelectChange_<?php echo $index?>(select){
		
		var $this = $(select);
		
		var selected_attr = $('#widgetSelect_<?php echo $index?>').data('selected'),selected = [];
		
		if(selected_attr){
			selected = selected_attr.toString().split(',');
		}
		$this.nextAll('select').remove();
		
		if($this.val()===''){
			return ;			
		}
		
		var index = $this.index();
		
		//
		if($this.val()==selected[index]){
			selected[index] = '';
			$('#widgetSelect_<?php echo $index?>').data('selected',selected.join(','));	
		}
		
		<?php if($max_level>0):?>
		if(index>=<?php echo $max_level-1?>){
			return ;	
		}
		<?php endif;?>
		
		$.when($.post('<?php echo $api?>',{'id':$this.val(),'table':'<?php echo $table?>','node_id':'<?php echo $node_id?>','node_name':'<?php echo $node_name?>','parent_id':'<?php echo $parent_id?>','where':'<?php echo $where?>','order':'<?php echo $order?>'}))
		.done(function(response){
			if(response.length==0){
				return ;	
			}
			var select = '<select name="<?php echo $select_name?>" <?php echo empty($disabled)?'':'disabled="disabled"'?> class="widget-select widget-select-<?php echo $index?>" onChange="widgetSelectChange_<?php echo $index?>(this)"><?php echo $placeholder?>';
			$.each(response,function(i,item){
				
				if(selected[index+1] && item['<?php echo $node_id?>']==selected[index+1]){
					is_selected = 'selected="selected"';
				}else{
					is_selected = '';	
				}
				
				select+='<option value="'+item['<?php echo $node_id?>']+'" '+is_selected+'>'+item['<?php echo $node_name?>']+'</option>'	
			})
			select+= '</select>';
			$this.after(select);
			$this.next('select').trigger('change');
		})
		
}

</script>