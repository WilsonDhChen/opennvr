<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>下级在线设备</title>
    {:W('jQuery')}
    {:W('Dialog')}
    {:W('Bootstrap')}
    <link rel="stylesheet" href="__STATIC__/css/ly.css">
    <script>
        $(function () {

            $('[data-toggle="tooltip"]').tooltip()

            $("#addrtype").change(function () {
                if($(this).val()==1) {
                    $("#sAreaNameSelect").hide();
                    $("input[name=sAreaNameInput]").show();
                } else {
                    $("#sAreaNameSelect").show();
                    $("input[name=sAreaNameInput]").hide();
                }
            })

        })

    </script>
</head>
<body>
<div id="container" style="width: 640px; max-height: 600px; overflow: auto">
    <div class="panel panel-default">

        <div class="panel-body">
            <form method="get" class="form-inline">
                <div class="input-group" style="margin-bottom: 10px;">
                    <label for="sParentChid" class="input-group-addon">上级平台国标ID</label>
                    <select name="sParentChid" id="sParentChid"class="form-control" >
                        <option value="">全部</option>
                        <volist name="groups['devs']" id="vo">
                            <option value="{$vo.chid}" {:$sParentChid == $vo['chid']?"selected":""}>{$vo.chid}</option>
                        </volist>
                    </select>
                </div>
                <div class="input-group" style="margin-bottom: 10px;">

                    <label for="sChid" class="input-group-addon">
                        <select name="addrtype" id="addrtype" style="background: #eee; border: 0">
                            <option value="0" {:$addrtype == 0?"selected":''}>选择地区</option>
                            <option value="1" {:$addrtype == 1?"selected":''}>自定义输入</option>
                        </select>
                    </label>
                    <select name="sAreaNameSelect" id="sAreaNameSelect" class="form-control" style="{:$addrtype==1?'display:none':''}">
                        <volist name="addrs['address']" id="val">
                            <option value="{$val}" {:$sAreaName == $val?'selected':''}>{$val}</option>
                        </volist>
                    </select>
                    <input type="text" class="form-control"  style="{:$addrtype==1?'':'display:none'}" name="sAreaNameInput" value="{$sAreaName}" placeholder="请输入地区">
                </div>
                <div class="input-group" style="margin-bottom: 10px;">
                    <label for="sChid" class="input-group-addon">国标ID</label>
                    <input type="text" class="form-control" name="sChid" value="{$sChid}" placeholder="请输入国标ID">
                </div>
                <div class="input-group" style="margin-bottom: 10px;">
                    <label for="sName" class="input-group-addon">名称</label>
                    <input type="text" class="form-control" name="sName" value="{$sName}" placeholder="请输入名称">
                </div>

                <div class="form-group" style="margin-bottom: 10px;">
                    <button class="btn btn-primary">查询</button>
                </div>
            </form>
            <div class="lists-container" style="margin-top: 15px">
                <table class="table table-bordered table-hover">
                    <thead>
                    <tr class="active">
                        <th>国标ID</th>
                        <th>名称</th>
                        <th>地区</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <volist name="lists" id="vo">
                        <tr>

                            <td>{$vo.sChid}</td>
                            <td>{$vo.sName}</td>
                            <td>{$vo.sAreaName}</td>
                            <td><a href="javascript:;" class="select-chid" data-id="{$vo.sChid}" data-name="{$vo.sName}">选择</a></td>
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
<script>
    $(function () {
        
        $(".select-chid").click(function () {
            
            window.parent.getallchs($(this).data("id"),$(this).data("name"));
            
        })
    })
</script>
