<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <meta name="full-screen" content="yes">
    <meta name="x5-fullscreen" content="true">
    <link rel="stylesheet" href="__STATIC__/css/bootstrap.min.css">
    <link rel="stylesheet" href="__STATIC__/css/matrix-style.css" />
    <script src="http://libs.baidu.com/jquery/1.11.3/jquery.js"></script>
    <script src="__STATIC__/js/matrix.js"></script>
    <script src="__STATIC__/js/lodash.min.js"></script>
    <title>全屏投影</title>
    <link rel="stylesheet" href="__STATIC__/css/ly.css">
    <style>
        .close{height: 30px;background: #ccc;opacity: 0.3;font-size: 20px;line-height: 30px;display: none;position: absolute;text-align: right;z-index: 1000000}
    </style>
    {:W('Jwplayer')}
</head>
<body>
<div class="left">
    <div id="sidebar">
        <ul>
            <volist name="lists" id="list">
                <li class="submenu">
                    <a href="#">
                        <span>{$list.sName}</span>
                        <span class="label label-important">({$list['total']?$list['total']:0})</span>
                    </a>
                    <ul>
                        <volist name="streams" id="vo">
                            <if condition="$vo['nGroupId'] eq $list['nGroupId']">
                                <li><a class="menu_a" link="form-common.html" data=":281/gb28181/{$vo.sId}.flv">{$vo.sName}-{$vo.nGroupId}</a></li>
                            </if>
                        </volist>
                    </ul>
                </li>
            </volist>

        </ul>
    </div>
</div>

<div class="mean">

    <div class="kuang">
<!--        <div class="player">http://218.60.2.20:281/live/21010200001310000160.flv-->
<!--            <div id="myplayer"></div>-->
<!--        </div>-->
        <div class="video_item">
            <div class="tab-content" >
                <div class="tab-pane fade in active content" id="4xbox" >
                    <ul class="uls_4">
                        <li>
                            <div class="close">关闭</div>
                            <div class="videobox" ><div id="LVideoUls41"></div>
                            </div>
                        </li>
                        <li>
                            <div class="close">关闭</div>
                            <div class="videobox" ><div id="LVideoUls42"></div>
                            </div>
                        </li>
                        <li>
                            <div class="close">关闭</div>
                            <div class="videobox" ><div id="LVideoUls43"></div>
                            </div>
                        </li>
                        <li>
                            <div class="close">关闭</div>
                            <div class="videobox" ><div id="LVideoUls44"></div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="tab-pane fade content" id="6xbox" style="clear: both;">
                    <ul class="uls_6">
                        <li>
                            <div class="close">关闭</div>
                            <div class="videobox" ><div id="LVideoUls61"></div>
                            </div>
                        </li>
                        <li>
                            <div class="close">关闭</div>
                            <div class="videobox" ><div id="LVideoUls62"></div>
                            </div>
                        </li>
                        <li>
                            <div class="close">关闭</div>
                            <div class="videobox" ><div id="LVideoUls63"></div>
                            </div>
                        </li>
                        <li>
                            <div class="close">关闭</div>
                            <div class="videobox" ><div id="LVideoUls64"></div>
                            </div>
                        </li>
                        <li>
                            <div class="close">关闭</div>
                            <div class="videobox" ><div id="LVideoUls65"></div>
                            </div>
                        </li>
                        <li>
                            <div class="close">关闭</div>
                            <div class="videobox" ><div id="LVideoUls66"></div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="tab-pane fade content" id="9xbox" style="clear: both;overflow:hidden">
                    <ul class="uls_9">
                        <li>
                            <div class="close">关闭</div>
                            <div class="videobox" ><div id="LVideoUls91"></div>
                            </div>
                        </li>
                        <li>
                            <div class="close">关闭</div>
                            <div class="videobox" ><div id="LVideoUls92"></div>
                            </div>
                        </li>
                        <li>
                            <div class="close">关闭</div>
                            <div class="videobox" ><div id="LVideoUls93"></div>
                            </div>
                        </li>
                        <li>
                            <div class="close">关闭</div>
                            <div class="videobox" ><div id="LVideoUls94"></div>
                            </div>
                        </li>
                        <li>
                            <div class="close">关闭</div>
                            <div class="videobox" ><div id="LVideoUls95"></div>
                            </div>
                        </li>
                        <li>
                            <div class="close">关闭</div>
                            <div class="videobox" ><div id="LVideoUls96"></div>
                            </div>
                        </li>
                        <li>
                            <div class="close">关闭</div>
                            <div class="videobox" ><div id="LVideoUls97"></div>
                            </div>
                        </li>
                        <li>
                            <div class="close">关闭</div>
                            <div class="videobox" ><div id="LVideoUls98"></div>
                            </div>
                        </li>
                        <li>
                            <div class="close">关闭</div>
                            <div class="videobox" ><div id="LVideoUls99"></div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="ui-container indicate" style="overflow:hidden;">
            <div class="ui-container nav ">
                <ul id="nav" class=" nav nav-tabs" >
                    <li style="text-align:center"><a href="#4xbox" data-toggle="tab" data-value="4">4x</a></li>
                    <li style="text-align:center"><a href="#6xbox" data-toggle="tab" data-value="6">6x</a></li>
                    <li style="text-align:center"><a href="#9xbox" data-toggle="tab" data-value="9">9x</a></li>
                   <img src="__STATIC__/image/magnify.png"  id="loading" class="magnify">
                </ul>
            </div>
        </div>
    </div>
</div>

<script src="http://libs.baidu.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<script type="text/javascript">

    var showVideos=[];


    var selectedData;
    var screenNumber=4;
//
//    $(".magnify").onclick=function(){
//        var elem = $(".kuang")
//        requestFullScreen(elem);
//
//
//
//    };

    function requestFullScreen(element) {
        // 判断各种浏览器，找到正确的方法
        var requestMethod = element.requestFullScreen || //W3C
            element.webkitRequestFullScreen ||    //Chrome等s
            element.mozRequestFullScreen || //FireFox
            element.msRequestFullScreen; //IE11
        if (requestMethod) {
            requestMethod.call(element);
        }

    }

    resetVideoBoxSize();

    function openVideo(id,url)
    {
        var item ={id:id,url:url};

        showVideos.push(item);

        var player = jwplayer(id).setup({
            file: "http://"+window.location.hostname+url,
            width: "99.5%",
            height:"100%",
            volume:0,
            useaudio:true,
            autostart:true
        });

        player.onError(function (obj) { 
            player.play(); 
        });

        player.onComplete(function(){
            player.play(); 
        });
    }

    function resetVideoBoxFullscreenSize(){
        //剩余宽度高度计算
        var w = $(window).width();

        var rw = w;

        var videoWidth;
//        var videoHeight;
        if(screenNumber == 4){
            videoWidth = rw/2;


        }
        if(screenNumber == 6){
            videoWidth = rw/3;
        }
        if(screenNumber == 9){
            videoWidth = rw/3;
        }

        $(".videobox").css("width",videoWidth);
        $(".videobox").css("height",videoWidth*9/16);
    }

    function resetVideoBoxSize(){
        //剩余宽度高度计算
        var rw = $(".kuang").width();
//
//        var w = $(window).width();
////        var h = $(window).height() - $("#nav").height() - 50;
//
//        var rw = w - leftWidth;

        var videoWidth;
//        var videoHeight;
        if(screenNumber == 4){
            videoWidth = rw/2;
        }
        if(screenNumber == 6){
            videoWidth = rw/3;
        }
        if(screenNumber == 9){
            videoWidth = rw/3;
        }

        $(".videobox").css("width",videoWidth);
        $(".videobox").css("height",videoWidth*9/16);
    }


    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {




        //获取视频数量
        screenNumber = Number($(e.target).data("value"));

        resetVideoBoxSize();

        var addVideos = showVideos;

        //清空原来视频
        for(var i=0;i<showVideos.length;i++){
            var el = $("#"+showVideos[i].id+"_wrapper");
            el.empty();
            el.attr("id",showVideos[i].id);
        }

        showVideos = [];

        //打开视频
        for(var i=0;i<addVideos.length;i++){
            openVideo("LVideoUls"+screenNumber+(i+1),addVideos[i].url);
        }

    });


    $('#nav a').on('click', function (e) {
        //阻止默认行为
        e.preventDefault();
        $(this).tab('show');
    });

    $(".magnify").click(function(){
        var w = $(window).width();
        var h = $(window).height();
        $(".kuang").css({
            width:w,
            height:h,
            left:0,
            top:10
        });
        $(".left").hide();
        $(".indicate").hide();

        resetVideoBoxFullscreenSize();

        var addVideos = showVideos;

        //清空原来视频
        for(var i=0;i<showVideos.length;i++){
            var el = $("#"+showVideos[i].id+"_wrapper");
            el.empty();
            el.attr("id",showVideos[i].id);
        }

        showVideos = [];

        //打开视频
        for(var i=0;i<addVideos.length;i++){
            openVideo("LVideoUls"+screenNumber+(i+1),addVideos[i].url);
        }
    })

    $(function(){
        $(window).keydown(function (event) {
            if (event.keyCode == 27) {
                $(".kuang").css({
                    width:"77%",
                    left:200,
                    top:20
                });
                $(".left").show();
                $(".indicate").show();
                resetVideoBoxSize();
            }
        });
    });
//
//    $("#nav li").click(function(){
//        $()
//    });
//    <ul class="uls_4">
//        <li>
//        <div class="close">关闭</div>
//        <video id="LVideo"  xautoplay="autoplay" controls preload="auto"  x5-video-player-type="h5" x5-video-orientation="landscape|portrait" x5-video-player-fullscreen="true" playsinline="true"
//    x-webkit- airplay="true" webkit-playsinline="true" heightloop="false" src="" poster=""   >
//        </video>
//        </li>
//
//        jwplayer("myplayer").setup({
//            file: "rtmp://218.60.2.20/gb28181/21010200001310000160",
//            width: "100%",
//            height:"100%",
//            volume:60,
//            useaudio:true,
//            autostart:true,
//        });

    $(".submenu li a").click(function(event){
        event.stopPropagation();
        selectedData = $(this).attr("data");

    });

    $(".videobox").click(function(event){
        event.stopPropagation();

        if(selectedData=="") return;

        openVideo($(this).find("div")[0].id,selectedData);

        selectedData = "";
    })

    var lis = document.querySelectorAll(".uls_4 li");
    for(var i = 0;i<lis.length;i++){
        lis[i].onmouseover=function(){
            $(this).children().eq(0).css("display","block");

        }
        lis[i].onmouseout=function(){
            $(this).children().eq(0).css("display","none");

        }

    }


//    $(".submenu li a").click(function(event){
//        event.stopPropagation();
//        var data = $(this).attr("data");
//        $(".uls_6 li video").click(function(event){
//            event.stopPropagation();
//            $(this).attr("src",data);
//            data = ""
//        })
//    })

    var lis = document.querySelectorAll(".uls_6 li");
    for(var i = 0;i<lis.length;i++){
        lis[i].onmouseover=function(){
            $(this).children().eq(0).css("display","block");

        }
        lis[i].onmouseout=function(){
            $(this).children().eq(0).css("display","none");

        }

    }


//    $(".submenu li a").click(function(event){
//        event.stopPropagation();
//        var data = $(this).attr("data");
//        $(".uls_9 li video").click(function(event){
//            event.stopPropagation();
//
//
////            $(this).attr("src",data);
//            data = ""
//        })
//    })

    var lis = document.querySelectorAll(".uls_9 li");
    for(var i = 0;i<lis.length;i++){
        lis[i].onmouseover=function(){
            $(this).children().eq(0).css("display","block");

        }
        lis[i].onmouseout=function(){
            $(this).children().eq(0).css("display","none");

        }

    }

    $(".close").click(function(){
//        $(this).parent("video").attr("src","")

        var vb = $(this).siblings().children();
        vb.attr("id",vb[0].id.replace("_wrapper","")).empty();

        _.remove(showVideos,function(item){
            return item.id == vb[0].id;
        });


    })

</script>
</body>
</html>