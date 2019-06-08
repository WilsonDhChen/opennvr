<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>下级配置</title>
    {:W('jQuery')}
    {:W('Dialog')}
    {:W('Bootstrap')}
    <link rel="stylesheet" href="__STATIC__/css/ly.css">
    <script>
        $(function () {
            $("#Formelement").submit(function () {
                if($("input[name='sDevId']").val()==''){
                    alert("请填写下级ID");
                    return false;
                }
                var _this = this;
                console.log($(_this).serialize());
                dialog.loading.show();
                $.when($.post("__URL__/childs_config_post", $(_this).serialize()))
                    .always(function () {
                        dialog.loading.hide();
                    })
                    .done(function (response) {
                        dialog.tips(response.status,response.info,function () {
                            if (response.status == 'success') {
                                window.location.href = "__URL__/childs";
                            }
                        });
                    });

                return false;
            });
			
			
			if($('#gbversion').val()=='GB/T28181-2011'){
	
				var option = "";
                    option += "<option value='AUTO' {:$info['transfer_mode']=='AUTO'?'selected':''} >AUTO</option>";
					option += "<option value='UDP' {:$info['transfer_mode']=='UDP'?'selected':''} >UDP</option>";
					option += "<option value='VTS' {:$info['transfer_mode']=='VTS'?'selected':''} >VTS</option>";
					option += "<option value='HikTCP' {:$info['transfer_mode']=='HikTCP'?'selected':''} >HikTCP</option>";
					
				$('#transfer_mode').html(option);				
			}
			
			if($('#gbversion').val()=='GB/T28181-2016'){
				
				var option = "";
                    option += "<option value='AUTO' {:$info['transfer_mode']=='AUTO'?'selected':''} >AUTO</option>";
					option += "<option value='TCP-Passive' {:$info['transfer_mode']=='TCP-Passive'?'selected':''} >TCP-Passive</option>";
					option += "<option value='TCP-Active' {:$info['transfer_mode']=='TCP-Active'?'selected':''} >TCP-Active</option>";
					option += "<option value='UDP' {:$info['transfer_mode']=='UDP'?'selected':''} >UDP</option>";
					option += "<option value='VTS' {:$info['transfer_mode']=='VTS'?'selected':''} >VTS</option>";
					option += "<option value='HikTCP' {:$info['transfer_mode']=='HikTCP'?'selected':''} >HikTCP</option>";
					
				$('#transfer_mode').html(option);
				
			}			
				
			$('#gbversion').change(function(){
				
				if($('#gbversion').val()=='GB/T28181-2011'){
	
				var option = "";
                    option += "<option value='AUTO' {:$info['transfer_mode']=='AUTO'?'selected':''} >AUTO</option>";
					option += "<option value='UDP' {:$info['transfer_mode']=='UDP'?'selected':''} >UDP</option>";
					option += "<option value='VTS' {:$info['transfer_mode']=='VTS'?'selected':''} >VTS</option>";
					option += "<option value='HikTCP' {:$info['transfer_mode']=='HikTCP'?'selected':''} >HikTCP</option>";
					
				$('#transfer_mode').html(option);				
				}
			
				
				if($('#gbversion').val()=='GB/T28181-2016'){
				
				var option = "";
                    option += "<option value='AUTO' {:$info['transfer_mode']=='AUTO'?'selected':''} >AUTO</option>";
					option += "<option value='TCP-Passive' {:$info['transfer_mode']=='TCP-Passive'?'selected':''} >TCP-Passive</option>";
					option += "<option value='TCP-Active' {:$info['transfer_mode']=='TCP-Active'?'selected':''} >TCP-Active</option>";
					option += "<option value='UDP' {:$info['transfer_mode']=='UDP'?'selected':''} >UDP</option>";
					option += "<option value='VTS' {:$info['transfer_mode']=='VTS'?'selected':''} >VTS</option>";
					option += "<option value='HikTCP' {:$info['transfer_mode']=='HikTCP'?'selected':''} >HikTCP</option>";
					
				$('#transfer_mode').html(option);
				
				}
			
				
			});

        });


    </script>
</head>
<body style="padding: 30px " >
    <div class="panel panel-default">
        <div class="panel-heading clearfix">下级配置</div>
        <div class="panel-body">

            <div class="field-lists" style="margin-bottom: 40px">
                <form id="Formelement">
                    <div class="input-group form-inline">
                        <span class="input-group-addon">下级Id</span>
                        <input type="text" name="sDevId" class="form-control readonly-field" value="{$info.sDevId}" />
                    </div>
                    <div class="input-group form-inline">
                        <span class="input-group-addon">用户名</span>
                        <input type="text" name="sUser" class="form-control readonly-field" value="{$info.sUser}" />
                    </div>
                    <div class="input-group form-inline">
                        <span class="input-group-addon">密码</span>
                        <input type="text" name="sPwd" class="form-control readonly-field" value="{$info.sPwd}" />
                    </div>
                    <div class="input-group form-inline">
                                <span class="input-group-addon">平台厂家</span>
                                <select name="server_type" id="server_type" class="form-control">
                                    <option value="">请选择</option>
                                    <foreach name="factory" item="vo">
                                        <option value="{$vo.py}" {:$info['server_type']==$vo['py']?'selected':''}>{$vo.name}</option>
                                    </foreach>
                                </select>
                            </div>
                    <div class="input-group form-inline">
                        <span class="input-group-addon">接收视频IP</span>
                        <input type="text" name="ip_recv_video" class="form-control readonly-field" value="{$info.ip_recv_video}" />
                    </div>
                    <div class="input-group form-inline">
                        <span class="input-group-addon">目录类型</span>
                        <select name="org_type" id="org_type" class="form-control">
                            <option value="Civil" {:$info['org_type']=='Civil'?'selected':''}>Civil</option>
                            <option value="ParentID" {:$info['org_type']=='ParentID'?'selected':''}>ParentID</option>
                        </select>
                    </div>
                    <div class="input-group form-inline">
                        <span class="input-group-addon">国标版本</span>
                        <select name="gbversion" id="gbversion" class="form-control" >
                            <option value="GB/T28181-2011" {:$info['gbversion']=='GB/T28181-2011'?'selected':''}>GB/T28181-2011</option>
                            <option value="GB/T28181-2016" {:$info['gbversion']=='GB/T28181-2016'?'selected':''}>GB/T28181-2016</option>
                        </select>
                    </div>
                    <div class="input-group form-inline">
                        <span class="input-group-addon">视频传输方式</span>
                        <select name="transfer_mode" id="transfer_mode" class="form-control">
                            
							<option value="AUTO" {:$info['transfer_mode']=='AUTO'?'selected':''}>AUTO</option>
							<option value="UDP" {:$info['transfer_mode']=='UDP'?'selected':''}>UDP</option>
							<option value="VTS" {:$info['transfer_mode']=='VTS'?'selected':''}>VTS</option>
							<option value="HikTCP" {:$info['transfer_mode']=='HikTCP'?'selected':''}>HikTCP</option>
							
							
							<option value="AUTO" {:$info['transfer_mode']=='AUTO'?'selected':''}>AUTO</option>
                            <option value="TCP-Passive" {:$info['transfer_mode']=='TCP-Passive'?'selected':''}>TCP-Passive</option>
							<option value="TCP-Active" {:$info['transfer_mode']=='TCP-Active'?'selected':''}>TCP-Active</option>
							<option value="UDP" {:$info['transfer_mode']=='UDP'?'selected':''}>UDP</option>
							<option value="VTS" {:$info['transfer_mode']=='VTS'?'selected':''}>VTS</option>
							<option value="HikTCP" {:$info['transfer_mode']=='HikTCP'?'selected':''}>HikTCP</option>
							
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="id" value="{$info.nId}">
                        <button class="btn btn-lg btn-success">{:$info?"更新":"添加"}</button>
                    </div>
                </form>
            </div>
        </div>


    </div>
<script>
	
</script>
</body>
</html>