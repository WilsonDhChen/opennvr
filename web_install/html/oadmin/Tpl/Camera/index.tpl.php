<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>摄像头管理</title>
    {:W('jQuery')}
    {:W('Dialog')}
    {:W('Bootstrap')}
    <link rel="stylesheet" href="__STATIC__/css/ly.css">
    <script>
        var lives = <?php echo $lives?json_encode($lives):"[]"; ?>;
        $(function () {
            
            $('[data-toggle="tooltip"]').tooltip();

            $(".groups-lists dt").click(function () {
                var gid = $(this).parent("dl").data("id");

                <?php if(spower("add")):?>
                    $("#detail_iframe").prop("src","__URL__/detail/gid/"+gid);
                <?php endif;?>

                if($(this).parent("dl").find("dd").find("a").length>0) {
                    if ($(this).parent("dl").find("dd").is(":hidden")) {
                        $(this).parent("dl").find("dd").slideDown(300);
                    }else{
                        $(this).parent("dl").find("dd").slideUp(300);
                    }
                    return false;
                }

                $(".groups-lists dt").removeClass("active");
                $(this).addClass("active");
                var _this = this;
                $.when($.get("__URL__/lists", {gid:gid}))
                    .done(function (response) {
                        if (response.status=='success') {
                            var camera = '';
                            $.each(response.data,function (k, v) {
                                var livetips = "未知";
                                var actiontips ='点击连接';
                                var actionicon = "glyphicon-import";
                                var livestatus = lives[v.nId]?lives[v.nId]['started']:0;
                                var status = 1;
                                var error_info = lives[v.nId]['error']?"("+lives[v.nId]['error']+")":'';
                                switch (livestatus) {
                                    case 0 : livetips = "已断开"; status=2;  break;
                                    case 1 : livetips = "已连接"; status=1;  break;
                                    case 2 : livetips = "连接中"; status=3; break;
                                    case 3 : livetips = "计划中"; status=3; break;
                                    case 4 : livetips = "等待外部推送"; status=3; break;
                                    case 5 : livetips = "等待重新连接"; status=3; break;
                                }
                                livetips += error_info;
                                if (livestatus==0) {
                                    actiontips = '点击连接';
                                    actionicon = "glyphicon-export";
                                } else {
                                    actiontips = '点击断开';
                                    actionicon = "glyphicon-import";
                                }
                                if(gid==0) {
                                    camera += '<a href="javascript:;" class="live-status-'+status+'" data-status="'+livestatus+'"  title="'+v.sName+'" target="detail_iframe" data-id="'+v.nId+'">'+v.sName+' <?php if(spower("delete")):?><i class="delete-btn glyphicon glyphicon-remove" title="点击删除"></i><?php endif;?></a>';
                                } else {
                                    camera += '<a href="javascript:;" class="live-status-'+status+'" data-status="'+livestatus+'" title="'+v.sName+"："+livetips+'" target="detail_iframe" data-id="'+v.nId+'">'+v.sName+' <?php if(spower("camera_action")):?><i class="action-btn glyphicon '+actionicon+'" title="'+actiontips+'"></i><?php endif;?><?php if(spower("delete")):?><i class="delete-btn glyphicon glyphicon-remove" title="点击删除"></i><?php endif;?></a>';
                                }

                            });
                            $(_this).parent("dl").find("dd").html(camera);
                            $('[data-toggle="tooltip"]').tooltip();
                        }
                    });

            });

            $(".groups-lists").on("click", "dd a",function () {
                $(".groups-lists").find(".active").removeClass("active");
                $(this).addClass("active");
            });

            $(".groups-lists").on("mouseenter", "dd a",function () {
                $(this).find(".delete-btn").show();
                $(this).find(".action-btn").show();

            });

            $(".groups-lists").on("mouseleave", "dd a",function () {
                $(this).find(".delete-btn").hide();
                $(this).find(".action-btn").hide();
            });

            $(".groups-lists").on("click","a",function () {
                var id = $(this).data("id");
                $("#detail_iframe").prop("src","__URL__/detail/id/"+id);
            });

            $(".groups-lists").on("click",".delete-btn",function (event) {
                event.stopPropagation();

                var id = $(this).parent().data("id");
                var _this = this;
                dialog.confirm("确定删除摄像头？",function () {
                    $.when($.post("__URL__/delete", {id:id}))
                        .done(function (response) {
                            dialog.tips(response.status, response.info, function () {
                                if (response.status=='success') {
                                    $("#detail_iframe").prop("src","__URL__/detail/gid/"+$(_this).parents("dl").data("id"));
                                    $(_this).parent().remove();
                                }
                            });
                        });
                })
            });

            $(".groups-lists").on("click",".action-btn",function (event) {
                event.stopPropagation();

                var id = $(this).parent().data("id");
                var tips ;
                var status;
                if ($(this).parent().data("status") == 0) {
                    status = "start";
                    tips = "确定连接摄像头";
                } else {
                    status = "stop";
                    tips = "确定断开摄像头";
                }
                var _this = this;

                dialog.confirm(tips,function () {
                    $.when($.post("__URL__/camera_action", {id:id,status:status}))
                        .done(function (response) {
                            dialog.tips(response.status, response.info, function () {
                                $(".tooltip").remove();
                                setTimeout(function(){
                                    getinfo(id,$(_this).parent());
                                },5000)
                            });
                        });
                })
            });

            //$(".groups-lists dl").eq(0).find("dt").click();

        });

        function getinfo(id,obj) {
            $.when($.post("__URL__/getinfo", {id:id}))
                .done(function (response) {
                    var livetips = "未知";
                    var actiontips ='点击连接';
                    var actionicon = "glyphicon-import";
                    var livestatus = response.data;
                    var status = 1;
                    switch (livestatus) {
                        case 0 : livetips = "已断开"; status=2; break;
                        case 1 : livetips = "已连接"; status=1; break;
                        case 2 : livetips = "连接中"; status=3; break;
                        case 3 : livetips = "计划中"; status=3; break;
                        case 4 : livetips = "等待外部推送"; status=3; break;
                        case 5 : livetips = "等待重新连接"; status=3; break;
                    }
                    if (livestatus==0) {
                        actiontips = '点击连接';
                        actionicon = "glyphicon-export";
                    } else {
                        actiontips = '点击断开';
                        actionicon = "glyphicon-import";
                    }
                    $(obj).removeClass("live-status-1 live-status-2 live-status-3");
                    $(obj).addClass("live-status-"+status);
                    $(obj).data("status",livestatus);
                    $(obj).prop("title",livetips);
                    $(obj).attr("data-original-title",livetips);
                    $(obj).find(".action-btn").removeClass("glyphicon-export glyphicon-import");
                    $(obj).find(".action-btn").addClass(actionicon);
                    $(obj).find(".action-btn").prop("title",actiontips);


                });
        }

    </script>
</head>
<body>

    <div class="container-left scrollbar-black">
        <p class="container-left-tips clearfix">
            设备
            <span class="glyphicon glyphicon-refresh pull-right" style="cursor: pointer" onclick="window.location.reload()" data-toggle="tooltip" data-placement="bottom" title="刷新"></span>
        </p>
        <div class="groups-lists">
            <volist name="lists" id="vo">
                <dl data-id="{$vo.nId}">
                    <dt>{$vo.sName} ({:$vo['total']?$vo['total']:0})</dt>
                    <dd></dd>
                </dl>
            </volist>
            <dl data-id="0">
                <dt>未启用的通道 ({$nogroupnum})</dt>
                <dd></dd>
            </dl>
        </div>
    </div>
    <div class="container-right scrollbar-black">
        <p class="container-left-tips">视频通道参数</p>
        <div class="groups-container">
            <iframe name="detail_iframe" id="detail_iframe" src="" width="100%" height="100%" frameborder="0"></iframe>
        </div>
    </div>

</body>
</html>