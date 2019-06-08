<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Gb28181配置</title>
    {:W('jQuery')}
    {:W('Dialog')}
    {:W('Bootstrap')}
    <link rel="stylesheet" href="__STATIC__/css/ly.css">
    <script>
        $(function () {
            $("#Formelement").submit(function () {

                var _this = this;
                dialog.confirm("确认更新配置？",function () {
                    dialog.loading.show();
                    $.when($.post("__URL__/update", $(_this).serialize()))
                        .always(function () {
                            dialog.loading.hide();
                        })
                        .done(function (response) {
                            dialog.tips(response.status,response.info,function () {
                                if (response.status == 'success') {
                                    window.location.reload();
                                }
                            });
                        });
                });

                return false;
            });
            $("#Formelement1").submit(function () {

                var _this = this;
                dialog.confirm("确认更新配置？",function () {
                    dialog.loading.show();
                    $.when($.post("__URL__/gb28181_update", $(_this).serialize()))
                        .always(function () {
                            dialog.loading.hide();
                        })
                        .done(function (response) {
                            dialog.tips(response.status,response.info,function () {
                                if (response.status == 'success') {
                                    window.location.reload();
                                }
                            });
                        });
                });

                return false;
            });
        });


    </script>
</head>
<body style="padding: 30px " >
    <div class="panel panel-default">
        <div class="panel-heading clearfix">基本信息配置</div>
        <div class="panel-body">
            <div class="gb28181-container">

                    <div class="field-lists" style="margin-bottom: 40px">
                        <form id="Formelement">
                            <div class="input-group form-inline">
                                <span class="input-group-addon">日志级别</span>
                                <select name="log_level" id="log_level" class="form-control">
                                    <option value="FATAL" {:$info['log_level']==FATAL?'selected':''}>FATAL</option>
                                    <option value="ERROR" {:$info['log_level']==ERROR?'selected':''}>ERROR</option>
                                    <option value="WARNING" {:$info['log_level']==WARNING?'selected':''}>WARNING</option>
                                    <option value="INFO" {:$info['log_level']==INFO?'selected':''}>INFO</option>
                                    <option value="DEBUG" {:$info['log_level']==DEBUG?'selected':''}>DEBUG</option>
                                    <option value="DETAIL" {:$info['log_level']==DETAIL?'selected':''}>DETAIL</option>
                                </select>
                            </div>
                            <div class="input-group form-inline">
                                <span class="input-group-addon">是否输出调试信息</span>
                                <select name="log_debug" id="log_debug" class="form-control">
                                    <option value="0" {:$info['log_debug']==0?'selected':''}>否</option>
                                    <option value="1" {:$info['log_debug']==1?'selected':''}>是</option>
                                </select>
                            </div>
							<div class="input-group form-inline">
                                <span class="input-group-addon">网络会话超时时间</span>
                                <select name="network_timeout" id="network_timeout" class="form-control">
                                    <option value="20" {:$info['network_timeout']==20?'selected':''}>20秒</option>
									<option value="30" {:$info['network_timeout']==30?'selected':''}>30秒</option>
									<option value="45" {:$info['network_timeout']==45?'selected':''}>45秒</option>
									<option value="60" {:$info['network_timeout']==60?'selected':''}>60秒</option>
                                </select>
                            </div>
							<div class="input-group form-inline">
                                <span class="input-group-addon">视频秒开</span>
                                <select name="fast_start" id="fast_start" class="form-control">
                                    <option value="0" {:$info['fast_start']==0?'selected':''}>否</option>
									<option value="1" {:$info['fast_start']==1?'selected':''}>是</option>
                                </select>
                            </div>
							<div class="input-group form-inline">
                                <span class="input-group-addon">视频快照</span>
                                <select name="enable_snapshot" id="enable_snapshot" class="form-control">
                                    <option value="0" {:$info['enable_snapshot']==0?'selected':''}>否</option>
									<option value="1" {:$info['enable_snapshot']==1?'selected':''}>是</option>
                                </select>
                            </div>
							<div class="input-group form-inline">
                                <span class="input-group-addon">视频快照更新时间</span>
                                <select name="snapshot_updatetime" id="snapshot_updatetime" class="form-control">
                                    <option value="1" {:$info['snapshot_updatetime']==1?'selected':''}>1秒</option>
									<option value="2" {:$info['snapshot_updatetime']==2?'selected':''}>2秒</option>
									<option value="3" {:$info['snapshot_updatetime']==3?'selected':''}>3秒</option>
									<option value="5" {:$info['snapshot_updatetime']==5?'selected':''}>5秒</option>
									<option value="10" {:$info['snapshot_updatetime']==10?'selected':''}>10秒</option>
									<option value="20" {:$info['snapshot_updatetime']==20?'selected':''}>20秒</option>
									<option value="30" {:$info['snapshot_updatetime']==30?'selected':''}>30秒</option>
									<option value="45" {:$info['snapshot_updatetime']==45?'selected':''}>45秒</option>
									<option value="60" {:$info['snapshot_updatetime']==60?'selected':''}>60秒</option>
                                </select>
                            </div><div class="input-group form-inline">
                                <span class="input-group-addon">视频快照大小</span>
                                <select name="snapshot_size" id="snapshot_size" class="form-control">
                                    <option value="320x240" {:$info['snapshot_size']=='320x240'?'selected':''}>320x240</option>
									<option value="640x480" {:$info['snapshot_size']=='640x480'?'selected':''}>640x480</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-lg btn-success">更新配置</button>
                            </div>
                        </form>
                    </div>
            </div>
        </div>


    </div>
    <div class="panel panel-default">
        <div class="panel-heading clearfix">海康配置</div>
        <div class="panel-body">
            <div class="gb28181-container">

                    <div class="field-lists" style="margin-bottom: 40px">
                        <form id="Formelement1">
<div style="display:none"><?php echo json_encode($info); ?></div>
                            <!--<div class="input-group form-inline" style="display:none">
                                <span class="input-group-addon">GB28181 Passive 接收端口范围</span>
                                <input type="text" name="gb28181_port_range" class="form-control readonly-field" value="{$info1.gb28181_port_range}" />(*请填写例：25001-26000)
                            </div>-->
                            <div class="input-group form-inline">
                                <span class="input-group-addon">UDP 接收端口范围</span>
                                <input type="text" name="udp_port_range" class="form-control readonly-field" value="{$info1.udp_port_range}" />
				<span style="display:table-cell;vertical-align:middle;padding-left:10px;">(*请填写例：25001-26000)</span>
                            </div>
                            <div class="input-group form-inline">
                                <span class="input-group-addon">TCP 接收端口范围</span>
                                <input type="text" name="tcp_port_range" class="form-control readonly-field" value="{$info1.tcp_port_range}" />
				<span style="display:table-cell;vertical-align:middle;padding-left:10px;">(*请填写例：25001-26000)</span>
                            </div>
                            <div class="input-group form-inline">
                                <span class="input-group-addon">与海康平台私有协议传输</span>
                                <select name="enable_hiktcprtp" id="enable_hiktcprtp" class="form-control">
                                    <option value="1" {:$info1['enable_hiktcprtp']==1?'selected':''}>是</option>
                                    <option value="0" {:$info1['enable_hiktcprtp']==0?'selected':''}>否</option> 
                                </select>
                            </div>
                            <div class="input-group form-inline">
                                <span class="input-group-addon">海康视频端口</span>
                                <input type="number" name="hik_port_video" class="form-control readonly-field" value="{:$info1['hik_port_video']?$info1['hik_port_video']:25000}"/>
                            </div>
                            <div class="input-group form-inline">
                                <span class="input-group-addon">海康音频端口</span>
                                <input type="number" name="hik_port_audio" class="form-control readonly-field" value="{:$info1['hik_port_audio']?$info1['hik_port_audio']:25001}"/>
                            </div>
                            <div class="input-group form-inline">
                                <span class="input-group-addon">海康视频NAT端口</span>
                                <input type="number" name="hik_port_video4route" class="form-control readonly-field" value="{:$info1['hik_port_video4route']?$info1['hik_port_video4route']:0}"/>
                            </div>
                            <div class="input-group form-inline">
                                <span class="input-group-addon">海康音频NAT端口</span>
                                <input type="number" name="hik_port_audio4route" class="form-control readonly-field" value="{:$info1['hik_port_audio4route']?$info1['hik_port_audio4route']:0}"/>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-lg btn-success">更新配置</button>
                            </div>
                        </form>
                    </div>
            </div>
        </div>


    </div>

</body>
</html>
