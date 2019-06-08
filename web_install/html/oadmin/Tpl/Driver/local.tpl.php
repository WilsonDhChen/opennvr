<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>本地磁盘</title>
    {:W('jQuery')}
    {:W('Dialog')}
    {:W('Bootstrap')}
    <link rel="stylesheet" href="__STATIC__/css/ly.css">
</head>
<body>
<div id="container">
    <div class="panel panel-default">
        <div class="panel-heading">本地磁盘</div>
        <div class="panel-body">
            <div class="lists-container" style="margin-top: 15px">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr class="active">
                            <th>磁盘UUID</th>
                            <th>文件系统</th>
                            <th>设备路径</th>
                            <th>挂载路径</th>
                            <th>打开文件数</th>
                            <th>可用空间</th>
                            <th>可用百分比</th>
                            <th>启用</th>
                        </tr>
                    </thead>
                    <tbody>
                        <volist name="list" id="vo">
                            <tr>
                                <td>{$vo.uuid}</td>
                                <td>{$vo.fs}</td>
                                <td>{$vo.dev}</td>
                                <td>{$vo.root}</td>
                                <td>{$vo.opened-files}</td>
                                <td>{$vo.free-space}</td>
                                <td>{$vo.free-percent}</td>
                                <td>
                                    <input class="js-ckbox" data-id="{$vo.uuid}" type="checkbox" name="enable" value="{$vo.enable}" />
                                </td>
                            </tr>
                        </volist>
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
(function() {
$('.js-ckbox').click(function() {
    var id = $(this).attr('data-id');
    var enable = this.checked ? 1 : 0;
    var _this = this;

    dialog.confirm("确认要改变该磁盘的启用状态吗？",function () {
        dialog.loading.show();
        $.post("", {'uuid':id,'enable':enable}, function(res) {
            dialog.loading.hide();
            if (res.return === 0) {
                dialog.alert("磁盘状态修改成功");
            } else {
                dialog.alert(res.error);
                _this.checked = _this.checked ? false : true;
            }
        }, 'json');
    }, function() {
        _this.checked = _this.checked ? false : true;
    });
});
})();
$('.js-ckbox').each(function() {
    this.checked = this.value*1 === 1 ? true : false;
});
</script>
</body>
</html>
