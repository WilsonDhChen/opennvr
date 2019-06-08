<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>上级平台</title>
    {:W('jQuery')}
    {:W('Dialog')}
    {:W('Bootstrap')}
    <link rel="stylesheet" href="__STATIC__/css/ly.css">
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip();

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

        function record_delete(id) {
            dialog.confirm("确定删除记录？",function () {
                $.when($.post("__URL__/network_delete", {id:id}))
                    .done(function (response) {
                        dialog.tips(response.status, response.info, function () {
                            if (response.status=='success') {
                                window.location.reload();
                            }
                        });
                    });
            })
        }
    </script>
</head>
<body>
<div id="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            网络配置
        </div>
        <div class="panel-body">
            <div class="form-container clearfix">
                <button type="button" class="btn btn-info restart-btn">重启国标服务</button>
                <a href="__URL__/network_info" class="btn btn-success btn-insert pull-right">新增</a>
            </div>
            <div class="lists-container" style="margin-top: 15px">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr class="active">
                            <th>Enable TCP</th>
                            <th>Enable UDP</th>
                            <th>IPV4 绑定地址</th>
                            <th>IPV6 绑定地址</th>
                            <th>端口号</th>
                            <th style="text-align: right">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <volist name="lists" id="vo">
                            <tr>
                                <td>
                                    {:$vo['enable_tcp']==1?"开启":"关闭"}
                                </td>
                                <td>
                                    {:$vo['enable_udp']==1?"开启":"关闭"}
                                </td>
                                <td>{$vo.bind_addr}</td>
                                <td>{$vo.bind_addr6}</td>
                                <td>{$vo.port}</td>
                                <td align="right">
                                    <a href="__URL__/network_info/id/{$vo.id}" class="btn btn-xs btn-success glyphicon glyphicon-ok"  title="点击修改"></a>
                                    <button type="button" class="btn btn-xs btn-danger glyphicon glyphicon-remove"  title="点击删除" onclick="record_delete({$vo.id})"></button>
                                </td>
                            </tr>
                        </volist>
                    </tbody>

                </table>
            </div>
        </div>
        <div class="panel-footer clearfix">
            <div class="pull-right">{$page_html}</div>
        </div>
	<div>
	<table class="table table-bordered table-hover">
	<thead>
		<tr class="active">
			<th>错误信息</th>
		</tr>
	</thead>
	</table>
	<volist name="booterror" id="vo">
			<p>	{$vo}</p>
	 </volist>

</div>
    </div>
</div>
</body>
</html>