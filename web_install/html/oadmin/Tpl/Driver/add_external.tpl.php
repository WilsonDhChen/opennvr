<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>添加扩展磁盘</title>
    {:W('jQuery')}
    {:W('Dialog')}
    {:W('Bootstrap')}
    <link rel="stylesheet" href="__STATIC__/css/ly.css">
</head>
<body style="padding: 30px " >
    <div class="panel panel-default">
        <div class="panel-heading clearfix">添加扩展磁盘</div>
        <div class="panel-body">
            <div class="gb28181-container">
                    <div class="field-lists" style="margin-bottom: 40px">
                        <form id="form-update">
							<div class="input-group form-inline">
                                <span class="input-group-addon">是否启用</span>
                                <div class="form-control">
                                <label class="checkbox-inline">
                                    <input id="e-enable" type="checkbox" name="enable" {:$info['enable']===1?"checked":""} value="1"> 启用
                                </label>
                                </div>
                            </div>
                            <div class="input-group form-inline">
                                <span class="input-group-addon">磁盘挂载路径</span>
                                <input id="e-root" type="text" name="root" class="form-control" placeholder="请填写路径" />
                            </div>
                            <br/>
                            <div class="form-group">
                                <button class="btn btn-success" style="padding:8px 40px;">添 加</button>
                                <button class="btn btn-info" style="padding:8px 40px;" onclick="javascript:history.go(-1);return false;">返 回</button>
                            </div>
                        </form>
                    </div>
            </div>
        </div>
    </div>

<script type="text/javascript">
$(function () {
    $("#form-update").submit(function () {
        dialog.loading.show();
        var post_data = {
            'enable':$('#e-enable')[0].checked ? 1 : 0,
            'root':$('#e-root').val()
        };
        $.post("", post_data, function(res) {
            dialog.loading.hide();
            if (res.return === 0) {
                dialog.confirm('添加成功，要返回到列表页吗？', function() {
                    location.href = "/driver/external";
                }, function() {
                    location.reload();
                });
            } else {
                dialog.alert(res.error);
            }
        }, 'json');

        return false;
    });
});
</script>
</body>
</html>
