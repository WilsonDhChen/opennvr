<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>网络设置</title>
    {:W('jQuery')}
    {:W('Dialog')}
    {:W('Bootstrap')}
    <link rel="stylesheet" href="__STATIC__/css/ly.css">
    <script>
        $(function () {
            $(".formelement").submit(function () {

                var _this = this;
                dialog.confirm("确认更新该网卡？",function () {
                    dialog.loading.show();
                    $.when($.post("__URL__/update", $(_this).serialize()))
                        .always(function () {
                            dialog.loading.hide();
                        })
                        .done(function (response) {
                            dialog.tips(response.status,response.info);
                        });
                });

                return false;
            });

            $(".btn-restart").click(function () {
                dialog.confirm("确认重启网络？",function () {
                    dialog.loading.show();
                    $.when($.post("__URL__/restart"))
                        .always(function () {
                            dialog.loading.hide();
                        })
                        .done(function (response) {
                            dialog.tips(response.status,response.info);
                        });
                });
            });
        });

        function BootprotoChange(s){
            var $mod = $(s).parents('.formelement');
            if($(s).val()=='dhcp'){
                $mod.find('.readonly-field').val('').prop({readonly:true});
            }else{
                $mod.find('.readonly-field').prop({readonly:false});
            }
        }

    </script>
</head>
<body style="padding: 30px " >

    <div class="panel panel-default">
        <div class="panel-heading clearfix">
            网卡列表
            <button type="button" class="btn pull-right btn-primary btn-restart">重启网络</button>
        </div>
        <div class="panel-body">
            <div class="network-container">
                <div class="network-lists clearfix row">
                    <volist name="lists" id="vo">
                        <div class="pull-left col-lg-4  col-sm-6 col-xs-12 field-lists" style="margin-bottom: 40px">
                            <form class="formelement">
                                <h4>网卡{:$key+1}</h4>
                                <div class="input-group form-inline">
                                    <span class="input-group-addon">地址分配</span>
                                    <select name="BOOTPROTO" id="BOOTPROTO" class="form-control" onclick="BootprotoChange(this)">
                                        <option value="dhcp" {:$vo['BOOTPROTO']=='dhcp'?"selected":""}>动态</option>
                                        <option value="static" {:$vo['BOOTPROTO']=='static'?"selected":""}>静态</option>
                                    </select>
                                </div>
                                <div class="input-group form-inline">
                                    <span class="input-group-addon">IP地址</span>
                                    <input type="text" name="IPADDR" class="form-control readonly-field" value="{$vo.IPADDR}" readonly="{:$vo['BOOTPROTO']=='dhcp'?'readonly':''}" />
                                </div>
                                <div class="input-group form-inline">
                                    <span class="input-group-addon">广播地址</span>
                                    <input type="text" name="BROADCAST" class="form-control readonly-field" value="{$vo.BROADCAST}" readonly="{:$vo['BOOTPROTO']=='dhcp'?'readonly':''}" />
                                </div>
                                <div class="input-group form-inline">
                                    <span class="input-group-addon">网关地址</span>
                                    <input type="text" name="GATEWAY" class="form-control readonly-field" value="{$vo.GATEWAY}" readonly="{:$vo['BOOTPROTO']=='dhcp'?'readonly':''}" />
                                </div>
                                <div class="input-group form-inline">
                                    <span class="input-group-addon">子网掩码</span>
                                    <input type="text" name="NETMASK" class="form-control readonly-field" value="{$vo.NETMASK}" readonly="{:$vo['BOOTPROTO']=='dhcp'?'readonly':''}" />
                                </div>
                                <div class="input-group form-inline">
                                    <span class="input-group-addon">DNS1</span>
                                    <input type="text" name="DNS1" class="form-control" value="{$vo.DNS1}" />
                                </div>
                                <div class="input-group form-inline">
                                    <span class="input-group-addon">DNS2</span>
                                    <input type="text" name="DNS2" class="form-control" value="{$vo.DNS2}" />
                                </div>
                                <div class="input-group form-inline">
                                    <span class="input-group-addon">MAC地址</span>
                                    <input type="text" name="MACADDR" class="form-control" value="{$vo.MACADDR}" />
                                </div>
                                <div class="input-group form-inline">
                                    <span class="input-group-addon">链接状态</span>
                                    <input type="text" name="LINKED" class="form-control" value="{:$vo['LINKED']==1?'已连接':'断开'}" disabled/>
                                </div>
                                <div class="input-group form-inline">
                                    <span class="input-group-addon">广播地址</span>
                                    <input type="text" name="LINKED_BROADCAST" class="form-control" value="{$vo['LINKED_BROADCAST']}" disabled/>
                                </div>
                                <div class="input-group form-inline">
                                    <span class="input-group-addon">IP地址</span>
                                    <input type="text" name="LINKED_IPADDR" class="form-control" value="{$vo['LINKED_IPADDR']}" disabled/>
                                </div>
                                <div class="input-group form-inline">
                                    <span class="input-group-addon">子网掩码</span>
                                    <input type="text" name="LINKED_NETMASK" class="form-control" value="{$vo['LINKED_NETMASK']}" disabled/>
                                </div>
                                <div class="input-group form-inline">
                                    <span class="input-group-addon">重启网卡</span>
                                    <select name="restart" id="restart" class="form-control">
                                        <option value="1">是</option>
                                        <option value="0">否</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <input type="hidden" name="DEVICE" value="{$vo.DEVICE}">
                                    <button class="btn btn-success">更新网卡</button>
                                </div>
                            </form>
                        </div>
                    </volist>
                </div>
            </div>
        </div>


    </div>

</body>
</html>