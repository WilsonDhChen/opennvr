<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>视频广场</title>
    {:W('jQuery')}
    {:W('Dialog')}
    {:W('Bootstrap')}
    <link rel="stylesheet" href="__STATIC__/css/square.css">
    <link rel="stylesheet" href="__STATIC__/tool/awesome/css/font-awesome.min.css">
</head>
<body style="background:#EFF0F5">

<div id="Header">
    <div class="header clearfix">
        <a href="__URL__/index" class="logo pull-left"><img src="__STATIC__/image/logo.png" alt=""></a>
        <div class="search-container pull-right">
            <form method="get" action="__URL__/index">
                <div class="form-inline">
                    <select name="groupid" id="groupid" class="form-control" style="background:#4A5561;border-color:#4A5561;color:#ccc">
                        <option value="">全部分组</option>
                        <volist name="groups" id="vo">
                            <option value="{$vo.nId}" {:$vo['nId']==$groupid?'selected':''}>{$vo.sName}</option>
                        </volist>
                    </select>
                    <input type="text" class="form-control" placeholder="请输入摄像头名称" style="background:#4A5561;border-color:#4A5561;color:#ccc" name="keywords" value="{$keywords}">
                    <button class="btn" style="background:#4A5561;color:#ccc;" title="搜索"><i class="glyphicon glyphicon-search"></i></button>
                    <a class="btn" style="background:#4A5561;color:#ccc;" href="__APP__/desktop" title="登录"><i class="glyphicon glyphicon-user"></i></a>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="Mainer">
    <div class="mainer">
        <ul class="clearfix live-lists">
            <volist name="lists" id="vo">
                <li>
                    <a data-id="{$vo.sId}" data-title="{$vo.sName}" href="javascript:;">
                        <div class="cover">
                            <img src="{$vo.shot_img}" onerror="this.src = '__STATIC__/image/cover-error.jpg'">
                            <div class="cover-mask"></div>
                            <div class="cover-play"></div>
                        </div>
                        <p class="camera-name">{$vo.sName}</p>
                        <p class="cover-status cover-status-{:$live[$vo['nId']]}"><switch name="live[$vo['nId']]">
                                    <case value="0">已断开</case>
                                    <case value="1"><i class="fa fa-align-right fa-fw rotate-90"></i>直播中</case>
                                    <case value="2"><i class="fa fa-spinner fa-fw"></i>连接中</case>
                                    <case value="3">计划中</case>
                                    <case value="4">等待外部推送</case>
                                    <case value="5"><i class="fa fa-times-circle-o fa-fw"></i>等待重新连接</case>
                                </switch></p>
                    </a>
                </li>
            </volist>
        </ul>
        <div class="center-pagination">{$page_html}</div>
    </div>
</div>

<div id="Footer">
    <div class="footer">
        VTS2.0
    </div>
</div>
<script>
    $(function () {
        $(".live-lists li a").click(function () {
            var id = $(this).data("id");
            var title = $(this).data("title");
            dialog.frame("__URL__/detail/id/"+id,title,true)
        })
    })
</script>

</body>
</html>
