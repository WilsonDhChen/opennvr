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

            $(".restart-btn").click(function () {
                dialog.confirm("确认重启国标服务？",function () {
                    dialog.loading.show();
                    $.when($.post("__URL__/restart"))
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
            })
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
                                <span class="input-group-addon">本机国标ID</span>
                                <input type="text" name="my_id" class="form-control readonly-field" value="{$info.my_id}" />
                            </div>
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
                                <span class="input-group-addon">下级设备接入验证方式</span>
                                <select name="register_need_auth" id="register_need_auth" class="form-control">
                                    <option value="0" {:$info['register_need_auth']==0?'selected':''}>什么都不验证</option>
                                    <option value="1" {:$info['register_need_auth']==1?'selected':''}>仅验证是否存在</option>
                                    <option value="2" {:$info['register_need_auth']==2?'selected':''}>验证密码</option>
                                </select>
                            </div>
                            <div class="input-group form-inline">
                                <span class="input-group-addon">下级设备接入时是否读取目录</span>
                                <select name="read_catalog" id="read_catalog" class="form-control">
                                    <option value="0" {:$info['read_catalog']==0?'selected':''}>否</option>
                                    <option value="1" {:$info['read_catalog']==1?'selected':''}>是</option>
                                </select>
                            </div>
                            <!-- <div class="input-group form-inline">
                                <span class="input-group-addon">目录刷新时间间隔</span>
                                <select name="refresh_catalog_time" id="refresh_catalog_time" class="form-control">
                                    <option value="0" {:$info['refresh_catalog_time']==0?'selected':''}>自动</option>
                                    <option value="60" {:$info['refresh_catalog_time']==60?'selected':''}>1分钟</option>
                                    <option value="120" {:$info['refresh_catalog_time']==120?'selected':''}>2分钟</option>
                                    <option value="180" {:$info['refresh_catalog_time']==180?'selected':''}>3分钟</option>
                                    <option value="300" {:$info['refresh_catalog_time']==300?'selected':''}>5分钟</option>
                                    <option value="480" {:$info['refresh_catalog_time']==480?'selected':''}>8分钟</option>
                                    <option value="600" {:$info['refresh_catalog_time']==600?'selected':''}>10分钟</option>
                                    <option value="900" {:$info['refresh_catalog_time']==900?'selected':''}>15分钟</option>
                                    <option value="1800" {:$info['refresh_catalog_time']==1800?'selected':''}>30分钟</option>
                                </select>
                            </div> -->
                            <div class="input-group form-inline">
                                <span class="input-group-addon">SIP会话超时时间</span>
                                <select name="sip_timeout" id="sip_timeout" class="form-control">
                                    <option value="60" {:$info['sip_timeout']==60?'selected':''}>1分钟</option>
                                    <option value="90" {:$info['sip_timeout']==90?'selected':''}>1.5分钟</option>
                                    <option value="120" {:$info['sip_timeout']==120?'selected':''}>2分钟</option>
                                    <option value="180" {:$info['sip_timeout']==180?'selected':''}>3分钟</option>
                                </select>
                            </div>
                            <!-- <div class="input-group form-inline">
                                <span class="input-group-addon">设备名字</span>
                                <input type="text" name="device_name" class="form-control readonly-field" value="{$info.device_name}" />
                            </div>
                            <div class="input-group form-inline">
                                <span class="input-group-addon">工作模式</span>
                                <select name="mode" id="mode" class="form-control">
                                    <option value="NVR" {:$info['mode']=='NVR'?'selected':''}>NVR</option>
                                    <option value="PLATFORM" {:$info['mode']=='PLATFORM'?'selected':''}>PLATFORM</option>
                                </select>
                            </div> -->
							<div class="input-group form-inline">
                                <span class="input-group-addon">视频转发</span>
                                <select name="forward_video" id="forward_video" class="form-control">
                                    <option value="0" {:$info['forward_video']==0?'selected':''}>否</option>
                                    <option value="1" {:$info['forward_video']==1?'selected':''}>是</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-lg btn-success">更新配置</button>
                                <button type="button" class="btn btn-lg btn-info restart-btn">重启国标服务</button>
                            </div>
                        </form>
                    </div>
            </div>
        </div>


    </div>

</body>
</html>
