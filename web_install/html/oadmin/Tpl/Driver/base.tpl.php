<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>磁盘设置</title>
    {:W('jQuery')}
    {:W('Dialog')}
    {:W('Bootstrap')}
    <link rel="stylesheet" href="__STATIC__/css/ly.css">
</head>
<body style="padding: 30px " >
    <div class="panel panel-default">
        <div class="panel-heading clearfix">磁盘设置</div>
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
                                <span class="input-group-addon">磁盘类型</span>
                                <select id="e-type" name="type" class="form-control">
                                    <option value="Local" {:$info['type']=='Local'?'selected':''}>本地磁盘</option>
                                    <option value="External" {:$info['type']=='External'?'selected':''}>扩展磁盘</option>
                                    <option value="Network" {:$info['type']=='Network'?'selected':''}>网络磁盘</option>
                                </select>
                            </div>
							<div class="input-group form-inline">
                                <span class="input-group-addon">写入模式</span>
                                <select id="e-write-mode"name="write-mode" class="form-control">
                                    <option value="Fast" {:$info['write-mode']=='Fast'?'selected':''}>速度优先</option>
                                    <option value="Balance" {:$info['write-mode']=='Balance'?'selected':''}>多磁盘平衡写入</option>
                                    <option value="Normal" {:$info['write-mode']=='Normal'?'selected':''}>顺序写入</option>
                                </select>
                            </div>
                            <br/>
                            <div class="form-group">
                                <button class="btn btn-lg btn-success">更新配置</button>
                            </div>
                        </form>
                    </div>
            </div>
        </div>
    </div>

<script type="text/javascript">
$(function () {
    $("#form-update").submit(function () {
        var _this = this;
        dialog.confirm("确认更新配置？",function () {
            dialog.loading.show();
            var post_data = {
                'enable':$('#e-enable')[0].checked ? 1 : 0,
                'type':$('#e-type').val(),
                'write-mode':$('#e-write-mode').val()
            };
            $.post("", post_data, function(res) {
                dialog.loading.hide();
                if (res.return === 0) {
                    dialog.alert("配置更新成功");
                } else {
                    dialog.alert(res.error);
                }
            }, 'json');
        });

        return false;
    });
});
</script>
</body>
</html>
