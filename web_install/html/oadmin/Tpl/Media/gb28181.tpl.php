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
        <div class="panel-heading clearfix">GB28181端口配置</div>
        <div class="panel-body">
            <div class="gb28181-container">

                    <div class="field-lists" style="margin-bottom: 40px">
                        <form id="Formelement">
							<div class="input-group form-inline">
                                <span class="input-group-addon">GB28181 Passive 接收端口范围</span>
                                <input type="text" name="gb28181_port_range" class="form-control readonly-field" value="{$info.gb28181_port_range}" />(*请填写例：25001-26000)
                            </div>
							<div class="input-group form-inline">
                                <span class="input-group-addon">与海康平台私有协议传输</span>
                                <select name="enable_hiktcprtp" id="enable_hiktcprtp" class="form-control">
									<option value="1" {:$info['enable_hiktcprtp']==1?'selected':''}>是</option>
                                    <option value="0" {:$info['enable_hiktcprtp']==0?'selected':''}>否</option> 
                                </select>
                            </div>
							<div class="input-group form-inline">
                                <span class="input-group-addon">海康视频端口</span>
                                <input type="number" name="hik_port_video" class="form-control readonly-field" value="{:$info['hik_port_video']?$info['hik_port_video']:25000}"/>
                            </div>
							<div class="input-group form-inline">
                                <span class="input-group-addon">海康音频端口</span>
                                <input type="number" name="hik_port_audio" class="form-control readonly-field" value="{:$info['hik_port_audio']?$info['hik_port_audio']:25001}"/>
                            </div>
							<div class="input-group form-inline">
                                <span class="input-group-addon">海康视频NAT端口</span>
                                <input type="number" name="hik_port_video4route" class="form-control readonly-field" value="{:$info['hik_port_video4route']?$info['hik_port_video4route']:0}"/>
                            </div>
							<div class="input-group form-inline">
                                <span class="input-group-addon">海康音频NAT端口</span>
                                <input type="number" name="hik_port_audio4route" class="form-control readonly-field" value="{:$info['hik_port_audio4route']?$info['hik_port_audio4route']:0}"/>
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