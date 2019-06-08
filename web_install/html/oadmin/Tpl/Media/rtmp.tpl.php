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
        function record_delete(id) {
            dialog.confirm("确定删除记录？",function () {
                $.when($.post("__URL__/rtmp_delete", {id:id}))
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
            RTMP网络
        </div>
        <div class="panel-body">
            <div class="form-container clearfix">
                <a href="__URL__/rtmp_info" class="btn btn-success btn-insert pull-right">新增</a>
            </div>
            <div class="lists-container" style="margin-top: 15px">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr class="active">
                            <th>Enable</th>
                            <th>IPV4 绑定地址</th>
                            <th>IPV6 绑定地址</th>
                            <th>端口号</th>
							<th>开启推送</th>
							<th>开启播放</th>
                            <th style="text-align: right">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <volist name="lists" id="vo">
                            <tr>
                                <td>
                                    {:$vo['enable']==1?"开启":"关闭"}
                                </td>
                                <td>{$vo.bind_addr}</td>
                                <td>{$vo.bind_addr6}</td>
                                <td>{$vo.port}</td>
								<td>
                                    {:$vo['publish']==1?"开启":"关闭"}
                                </td>
								<td>
                                    {:$vo['playback']==1?"开启":"关闭"}
                                </td>
                                <td align="right">
                                    <a href="__URL__/rtmp_info/id/{$vo.id}" class="btn btn-xs btn-success glyphicon glyphicon-ok"  title="点击修改"></a>
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