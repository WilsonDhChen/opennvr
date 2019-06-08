<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>上级平台配置</title>
    {:W('jQuery')}
    {:W('Dialog')}
    {:W('Bootstrap')}
    <link rel="stylesheet" href="__STATIC__/css/ly.css">
    <script>
        $(function () {
            $("#Formelement").submit(function () {

                var _this = this;

                dialog.loading.show();
                $.when($.post("__URL__/parents_config_post", $(_this).serialize()))
                    .always(function () {
                        dialog.loading.hide();
                    })
                    .done(function (response) {
                        dialog.tips(response.status,response.info,function () {
                            if (response.status == 'success') {
                                window.location.href = "__URL__/parents";
                            }
                        });
                    });

                return false;
            });

        });


    </script>
</head>
<body style="padding: 30px " >
    <div class="panel panel-default">
        <div class="panel-heading clearfix">上级平台配置</div>
        <div class="panel-body">
            <div class="gb28181-container">

                    <div class="field-lists" style="margin-bottom: 40px">
                        <form id="Formelement">

                            <div class="input-group form-inline">
                                <span class="input-group-addon">启用</span>
                                <select name="enable" id="enable" class="form-control">
                                    <option value="0" {:$info['enable']==0?'selected':''}>否</option>
                                    <option value="1" {:$info['enable']==1?'selected':''}>是</option>
                                </select>
                            </div>
                            <div class="input-group form-inline">
                                <span class="input-group-addon">连接模式</span>
                                <select name="connect_mode" id="connect_mode" class="form-control">
                                    <option value="AUTO" {:$info['connect_mode']=='AUTO'?'selected':''}>AUTO</option>
                                    <option value="TCP" {:$info['connect_mode']=='TCP'?'selected':''}>TCP</option>
                                    <option value="UDP" {:$info['connect_mode']=='UDP'?'selected':''}>UDP</option>
                                </select>
                            </div>
                            <div class="input-group form-inline">
                                <span class="input-group-addon">上级平台国标ID</span>
                                <input type="text" name="server_id" class="form-control readonly-field" value="{$info.server_id}" />
                            </div>
                            <div class="input-group form-inline">
                                <span class="input-group-addon">上级平台名称</span>
                                <input type="text" name="name" class="form-control readonly-field" value="{$info.name}" />
                            </div>
                            <div class="input-group form-inline">
                                <span class="input-group-addon">上级平台IP</span>
                                <input type="text" name="addr" class="form-control readonly-field" value="{$info.addr}" />
                            </div>
                            <div class="input-group form-inline">
                                <span class="input-group-addon">上级平台端口</span>
                                <input type="number" name="port" class="form-control readonly-field" value="{$info.port}" />
                            </div>
                            <div class="input-group form-inline">
                                <span class="input-group-addon">绑定的网卡</span>
                                <select name="via_addr" id="via_addr" class="form-control">
                                    <if condition="via_addr">
                                        <volist name="via_addr" id="vo">
                                            <option value="{$vo.addr}" {:$info['via_addr']==$vo['addr']?"selected":""}>{$vo.name}({$vo.addr})</option>
                                        </volist>
                                        <else />
                                        <option value="*">ALL</option>
                                    </if>
                                </select>
                            </div>
							<div class="input-group form-inline">
                                <span class="input-group-addon">本地端口</span>
                                <input type="text" name="via_port" class="form-control readonly-field" value="{$info.via_port}" />
                            </div>
							<div class="input-group form-inline">
                                <span class="input-group-addon">Contact 地址</span>
                                <input type="text" name="contact_addr" class="form-control readonly-field" value="{$info.contact_addr}" />
                            </div>
                            <div class="input-group form-inline">
                                <span class="input-group-addon">本机RTSP地址</span>
                                <input type="text" name="rtsp_addr" class="form-control readonly-field" value="{$info.rtsp_addr}" />
                            </div>
                            <div class="input-group form-inline">
                                <span class="input-group-addon">本机VTS地址</span>
                                <input type="text" name="vts_addr" class="form-control readonly-field" value="{$info.vts_addr}" />
                            </div>
                            <div class="input-group form-inline">
                                <span class="input-group-addon">用户名</span>
                                <input type="text" name="user" class="form-control readonly-field" value="{$info.user}" />
                            </div>
                            <div class="input-group form-inline">
                                <span class="input-group-addon">密码</span>
                                <input type="text" name="pwd" class="form-control readonly-field" value="{$info.pwd}" />
                            </div>

                            <div class="input-group form-inline">
                                <span class="input-group-addon">保活时间</span>
                                <select name="keepalive_time" id="keepalive_time" class="form-control">
                                    <option value="10" {:$info['keepalive_time']==10?'selected':''}>10秒</option>
                                    <option value="20" {:$info['keepalive_time']==20?'selected':''}>20秒</option>
                                    <option value="30" {:$info['keepalive_time']==30?'selected':''}>30秒</option>
                                    <option value="60" {:$info['keepalive_time']==60?'selected':''}>60秒</option>
                                    <option value="90" {:$info['keepalive_time']==90?'selected':''}>90秒</option>
                                    <option value="120" {:$info['keepalive_time']==120?'selected':''}>120秒</option>
                                </select>
                            </div>
							<div class="input-group form-inline">
                                <span class="input-group-addon">自动推送目录</span>
                                <select name="autopush_catalog" id="autopush_catalog" class="form-control">
                                    <option value="0" {:$info['autopush_catalog']==0?'selected':''}>否</option>
                                    <option value="1" {:$info['autopush_catalog']==1?'selected':''}>是</option>
                                </select>
                            </div>
							<div class="input-group form-inline">
                                <span class="input-group-addon">共享所有目录</span>
                                <select name="shared_all" id="shared_all" class="form-control">
                                    <option value="0" {:$info['shared_all']==0?'selected':''}>否</option>
                                    <option value="1" {:$info['shared_all']==1?'selected':''}>是</option>
                                </select>
                            </div>
							<div class="input-group form-inline">
                                <span class="input-group-addon">Catalog数目</span>
                                <input type="text" name="catalog_per_packet" class="form-control readonly-field" value="{$info.catalog_per_packet}" />
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
                                <span class="input-group-addon">字符集</span>
                                <select name="charset" id="charset" class="form-control">
                                    <option value="UTF8" {:$info['server_type']=='UTF8'?'selected':''}>UTF8</option>
                                    <option value="GB18030" {:$info['server_type']=='GB18030'?'selected':''}>GB18030</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="id" value="{$info.id}">
                                <button class="btn btn-lg btn-success">{:$info['id']?"更新上级平台":"添加上级平台"}</button>
                            </div>
                        </form>
                    </div>
            </div>
        </div>


    </div>

</body>
</html>
