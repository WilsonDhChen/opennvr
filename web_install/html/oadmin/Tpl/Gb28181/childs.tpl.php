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

            $('[data-toggle="tooltip"]').tooltip()


        })

        function record_delete(id) {
            dialog.confirm("确定删除记录？",function () {

                $.when($.post("__URL__/childs_delete", {id:id}))
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
            下级配置
        </div>
        <div class="panel-body">
            <div class="form-container clearfix">
                <a href="__URL__/childs_config" class="btn btn-success btn-insert pull-right">新增</a>
            </div>
            <div class="lists-container" style="margin-top: 15px">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr class="active">
                            <th>下级Id</th>
                            <th>接收视频IP</th>
                            <th>平台厂家</th>
                            <th style="text-align: right">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <volist name="lists" id="vo">
                            <tr>
                                <td>{$vo.sDevId}</td>
                                <td>{$vo.ip_recv_video}</td>
                                <td>
                                    <foreach name="factory" item="vo1">
                                        {:$vo['server_type']==$vo1['py']?$vo1['name']:''}
                                    </foreach>
                                </td>
                                <td align="right">
                                    <a href="__URL__/childs_config/id/{$vo.nId}" class="btn btn-xs btn-success glyphicon glyphicon-ok" title="点击修改"></a>
                                    <button type="button" class="btn btn-xs btn-danger glyphicon glyphicon-remove" title="点击删除" onclick="record_delete({$vo.nId})"></button>
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

    </div>
</div>
</body>
</html>