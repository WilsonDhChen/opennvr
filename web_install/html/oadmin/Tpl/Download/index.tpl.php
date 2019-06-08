<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>视频回放</title>
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

                $(".groups-lists dt").removeClass("active");
                $(this).addClass("active");

                if($(this).parent("dl").find("dd").find("a").length>0) {
                    if ($(this).parent("dl").find("dd").is(":hidden")) {
                        $(this).parent("dl").find("dd").slideDown(300);
                    }else{
                        $(this).parent("dl").find("dd").slideUp(300);
                    }
                    return false;
                }


                var _this = this;
                $.when($.get("__URL__/lists", {gid:gid}))
                    .done(function (response) {
                        
                        if (response.status=='success') {
                            var camera = '';
                            $.each(response.data,function (k, v) {

                                var livestatus = lives[v.nId]?lives[v.nId]:0;
                                var status = 1;
                                var livetips = '';
                                switch (livestatus) {
                                    case 0 : livetips = "已断开"; status=2;  break;
                                    case 1 : livetips = "已连接"; status=1;  break;
                                    case 2 : livetips = "连接中"; status=3; break;
                                    case 3 : livetips = "计划中"; status=3; break;
                                    case 4 : livetips = "等待外部推送"; status=3; break;
                                    case 5 : livetips = "等待重新连接"; status=3; break;
                                }

                                camera += '<a href="javascript:;" class="live-status-'+status+'"   title="'+v.sName+"："+livetips+'" target="detail_iframe" data-id="'+v.nId+'">'+v.sName+'</a>';
                            });
                            $(_this).parent("dl").find("dd").html(camera);
                            $("#detail_iframe").prop("src","__URL__/detail/gid/"+gid);
                            $('[data-toggle="tooltip"]').tooltip();

                        } else {
                            //dialog.tips("error","此分组下暂无设备");
                            return false;

                        }
                    });

            });

            $(".groups-lists").on("click","a",function () {

                $(".groups-lists").find(".active").removeClass("active");
                $(this).addClass("active");

                var id = $(this).data("id");
                $("#detail_iframe").prop("src","__URL__/detail/id/"+id);
            });

            //$(".groups-lists dl").eq(0).find("dt").click();

        });

    </script>
</head>
<body>
    <div class="container-left scrollbar-black">
        <p class="container-left-tips">设备</p>
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
        <p class="container-left-tips">视频详情</p>
        <div class="groups-container">
            <iframe name="detail_iframe" id="detail_iframe" src="" width="100%" height="100%" frameborder="0"></iframe>
        </div>
    </div>

</body>
</html>