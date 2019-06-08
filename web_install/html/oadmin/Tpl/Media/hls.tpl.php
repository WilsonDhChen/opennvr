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
                $.when($.post("__URL__/hls_delete", {id:id}))
                    .done(function (response) {
                        dialog.tips(response.status, response.info, function () {
                            if (response.status=='success') {
                                window.location.reload();
                            }
                        });
                    });
            })
        }
        $(function () {
        $("#Formelement").submit(function () {

                var _this = this;
                dialog.confirm("确认更新配置？",function () {
                    dialog.loading.show();
                    $.when($.post("__URL__/hlsconfigure_update", $(_this).serialize()))
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
<body>
<div id="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            HLS网络
        </div>
        <div class="panel-body">
            <div class="form-container clearfix">
                <a href="__URL__/hls_info" class="btn btn-success btn-insert pull-right">新增</a>
            </div>
            <div class="lists-container" style="margin-top: 15px">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr class="active">
                            <th>Enable</th>
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
                                    {:$vo['enable']==1?"开启":"关闭"}
                                </td>
                                <td>{$vo.bind_addr}</td>
                                <td>{$vo.bind_addr6}</td>
                                <td>{$vo.port}</td>
                                <td align="right">
                                    <a href="__URL__/hls_info/id/{$vo.id}" class="btn btn-xs btn-success glyphicon glyphicon-ok"  title="点击修改"></a>
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
<!-- 	<thead>
		<tr class="active">
			<th>错误信息</th>
		</tr>
	</thead>
	</table>
	<volist name="booterror" id="vo">
			<p>	{$vo}</p>
	 </volist> -->

</div>
 <div class="panel panel-default">
        <div class="panel-heading clearfix">HLS 配置</div>
        <div class="panel-body">
            <div class="gb28181-container">

                    <div class="field-lists" style="margin-bottom: 40px">
                        <form id="Formelement">
                            <div class="input-group form-inline">
                                <span class="input-group-addon">输出HLS</span>
                                <select name="output_hls" id="output_hls" class="form-control">
                                    <option value="0" {:$info['output_hls']==0?'selected':''}>否</option>
                                    <option value="1" {:$info['output_hls']==1?'selected':''}>是</option>
                                </select>
                            </div>
                            <div class="input-group form-inline">
                                <span class="input-group-addon">输出到内存</span>
                                <select name="memory_file" id="memory_file" class="form-control">
                                    <option value="1" {:$info['memory_file']==1?'selected':''}>是</option>
                                    <option value="0" {:$info['memory_file']==0?'selected':''}>否</option> 
                                </select>
                            </div>
                            <div class="input-group form-inline">
                                <span class="input-group-addon">HLS输出目录</span>
                                <input type="text" name="hls_dir" class="form-control readonly-field" value="{:$info['hls_dir']?$info['hls_dir']:'/'}" />
                            </div>
                            <div class="input-group form-inline">
                                <span class="input-group-addon">HLS TS文件前缀</span>
                                <input type="text" name="hls_ts_prefix" class="form-control readonly-field" value="{$info.hls_ts_prefix}" />
                            </div>
                            <div class="form-group">
                                <button class="btn btn-lg btn-success">更新配置</button>
                            </div>
                        </form>
                    </div>
            </div>
        </div>


    </div>

    </div>
</div>
</body>
</html>