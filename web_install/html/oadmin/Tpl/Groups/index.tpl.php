<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>分组管理</title>
    {:W('jQuery')}
    {:W('Dialog')}
    {:W('Bootstrap')}
    <link rel="stylesheet" href="__STATIC__/css/ly.css">
    <script>
        $(function () {

            //$('[data-toggle="tooltip"]').tooltip()

            $(".btn-insert").click(function () {
                dialog.prompt("请输入组名",function () {
                    var name = $.trim(this.value);
                    if(!name) {
                        dialog.tips("error","请输入组名");
                        return false;
                    }
                    $.when($.post("__URL__/add", {name: name}))
                        .done(function (response) {
                            dialog.tips(response.status, response.info, function () {
                                if (response.status=='success') {
                                    window.location.reload();
                                }
                            });
                        });
                    return false;
                })
            })


        })
        
        function update(id,name) {

            dialog.prompt("请输入组名",function () {
                var name = $.trim(this.value);
                if(!name) {
                    dialog.tips("error","请输入组名");
                    return false;
                }
                $.when($.post("__URL__/update", {name: name, id:id}))
                    .done(function (response) {
                        dialog.tips(response.status, response.info, function () {
                            if (response.status=='success') {
                                window.location.reload();
                            }
                        });
                    });
                return false;
            },name)
        }

        function record_delete(id) {
            dialog.confirm("确定删除记录？",function () {

                $.when($.post("__URL__/delete", {id:id}))
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
            分组列表
        </div>
        <div class="panel-body">
            <div class="form-container">
                <form method="get" class="form-inline">
                    <div class="input-group">
                        <label for="sName" class="input-group-addon">组名</label>
                        <input type="text" class="form-control" name="sName" value="{$sName}" placeholder="请输入组名">
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary">查询</button>
                        <button type="button" class="btn btn-success btn-insert">新增</button>

                    </div>
                </form>
            </div>
            <div class="lists-container" style="margin-top: 15px">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr class="active">
                            <th>序号</th>
                            <th>组名</th>
                            <th style="text-align: right">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <volist name="lists" id="vo">
                            <tr>
                                <td>{$vo.nId}</td>
                                <td>{$vo.sName}</td>
                                <td align="right">
                                    <button type="button" class="btn btn-xs btn-success glyphicon glyphicon-ok" data-toggle="tooltip" data-placement="top" title="点击修改" onclick="update({$vo.nId},'{$vo.sName}')"></button>
                                    <if condition="$vo['nId'] neq 1">
                                        <button type="button" class="btn btn-xs btn-danger glyphicon glyphicon-remove" data-toggle="tooltip" data-placement="top" title="点击删除" onclick="record_delete({$vo.nId})"></button>
                                    </if>
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
