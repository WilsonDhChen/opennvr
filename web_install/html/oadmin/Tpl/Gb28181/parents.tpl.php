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
        function Push_new(id) {
            dialog.confirm("确定共享推送？",function () {

                $.when($.get("http://{$_SERVER['HTTP_HOST']}:680/pushres",{id:id}))
                    .done(function (response) {
                        // dialog.tips(response.return, response.error, function () {
                            //console.log(response);
                            if (response.error=='ok') {
                                alert("成功");
                            }else{
                                alert(response.error);
                            }
                        // });
                    });
            })
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
            上级配置
        </div>
        <div class="panel-body">
            <div class="form-container clearfix">
                <a href="__URL__/parents_config" class="btn btn-success btn-insert pull-right">新增</a>
            </div>
            <div class="lists-container" style="margin-top: 15px">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr class="active">
                            <th>启用</th>
                            <th>连接模式</th>
                            <th>绑定的地址</th>
                            <th>上级平台国标ID</th>
                            <th>上级平台IP</th>
                            <!-- <th>上级平台端口</th> -->
                           <!--  <th>用户名</th>
                            <th>密码</th> -->
						
                            <th>保活时间</th>
                            <th>状态</th>
                            <th>最后一次活动时间</th>
							<th>错误信息</th>
                            <th style="text-align: right">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <volist name="lists" id="vo">
                            <tr>
                                <td>
                                    {:$vo['enable']==1?"是":"否"}
                                </td>
                                <td>{$vo.connect_mode}{:$status[$vo['id']]['c-mode']=="AUTO"&&$status[$vo['id']]['cur-c-mode']!==""?"(".$status[$vo['id']]['cur-c-mode'].")":""}</td>
                                <td>{$vo.via_addr}:<?php echo $status[$vo['id']]['localport'];?></td>
                                <td>{$vo.server_id}<span class="server_name">{:$vo['name']===""?"":"(".$vo['name'].")"}</span></td>
                                <td>{$vo.addr}:{$vo.port}</td>
                                <!-- <td>{$vo.port}</td> -->
                                <!-- <td>{$vo.user}</td>
                                <td>{$vo.pwd}</td> -->
                                <td>{$vo.keepalive_time}</td>
                                <td>
                                    <switch name="status[$vo['id']]['status']">
                                        <case value="1"><span class="label label-success">已经连接</span></case>
                                        <case value="2"><span class="label label-warning">正在连接</span></case>
                                        <default /><span class="label label-danger">没有连接</span>
                                    </switch>
                                </td>
                                <td>{$status[$vo['id']]['activetime']}</td>
								<td><?php echo $status[$vo['id']]['lasterror'];?></td>
                                <td align="right">
                                    <?php if($vo['shared_all']==0){?><a href="__URL__/share_data_list/id/<?php echo $vo['server_id']?>" class="btn btn-xs btn-success glyphicon " title="共享">共享</a><?php }?>
                                    <a href="#" class="btn btn-xs btn-success glyphicon " onclick="Push_new('{$vo.server_id}')" title="共享">共享推送</a>
                                    <a href="__URL__/parents_config/id/{$vo.id}" class="btn btn-xs btn-success glyphicon glyphicon-ok"  title="点击修改"></a>
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

    </div>
</div>
</body>
</html>
