<!--UEditor 加载开始-->
<textarea id="<?php echo $id?>" name="<?php echo $name?>" style="width:<?php echo $width?>;height:<?php echo $height?>;"><?php echo $content?></textarea>
<?php if($count==1):?>
<script type="text/javascript"  src="__STATIC__/tool/ueditor/ueditor.config.js"></script>
<script type="text/javascript"  src="__STATIC__/tool/ueditor/ueditor.all.js"> </script>
<?php endif;?>
<script type="text/javascript">
        var <?php echo $var;?> = UE.getEditor('<?php echo($id);?>'<?php echo($config);?>);
</script>
<!--UEditor 加载结束-->
