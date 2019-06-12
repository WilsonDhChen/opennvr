<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>视频回放</title>
    {:W('jQuery')}
    {:W('Dialog')}
    {:W('Calendar')}
    {:W('Bootstrap')}
    <link rel="stylesheet" href="__STATIC__/css/ly.css">
    <link rel="stylesheet" href="__STATIC__/css/calendar.css">
    <!--{:W('Jwplayer')}-->
    <script type="text/javascript" src="__STATIC__/tool/vtsplayer/flv.js"></script>
    <script>

        $(function () {

            $('[data-toggle="tooltip"]').tooltip();

            var video = $('#video-player')[0];

            var player = flvjs.createPlayer({
                type: 'flv',
                url:'{$play_url}',
                enableWorker: false,
                lazyLoadMaxDuration: 3 * 60, 
                seekType: 'range'
            });
            player.attachMediaElement(video);
            player.load();
            player.play();
/*
            jwplayer("myplayer").setup({
                file: "{$play_url}",
                width: "100%",
                height:"100%",
                volume:60,
                useaudio:true,
                autostart:true,
            });
*/

            $(".glyphicon-search").click(function () {

                var datetime = $("input[name=date]").val();
                if (!datetime) {
                    dialog.tips("error","请选择需要预览视频的日期");
                    return false;
                }

                var stime = $("input[name=stime]").val();
                var otime = $("input[name=otime]").val();
                if (!stime) {
                    dialog.tips("error","请选择需要预览视频的开始时间");
                    return false;
                }
                var url_str = '?vod=1';
                url_str += "&starttime="+timefliter(datetime+" "+stime);

                if (otime) {
                    url_str += "&endtime="+timefliter(datetime+" "+otime);
                }
                url_str = "{$play_url}"+url_str;

                player.unload();
                player.detachMediaElement();
                player.destroy();
                player = null;

                player = flvjs.createPlayer({
                    type: 'flv',
                    url:url_str,
                    enableWorker: false,
                    lazyLoadMaxDuration: 3 * 60, 
                    seekType: 'range'
                });
                player.attachMediaElement(video);
                player.load();
                player.play();
/*
                jwplayer("myplayer").setup({
                    file: url_str,
                    width: "100%",
                    height:"100%",
                    volume:60,
                    useaudio:true,
                    autostart:true
                });
*/

            })

        });

        function timefliter(value) {
            var timer = value.replace(new RegExp('-','gm'),'');
            timer = timer.replace(" ","");
            timer = timer.replace(new RegExp(':','gm'),'');
            return timer;
        }

        function ttn(value) {
            var timer = value.split(":");
            return parseInt(timer[0])*3600+parseInt(timer[1])*60+parseInt(timer[2]);
        }


    </script>
</head>
<body style="padding:0 30px 30px 30px " >
    <div class="play-container">
        <h3>{$info.name}</h3>
        <div class="player" style="width: 470px; height: 243px">
            <div id="myplayer" style="width:100%;height:100%"><video style="width:100%;height:100%" class="video-player" id="video-player" controls></video></div>
        </div>
        <div class="calendar-container">
            <div class="leave-box clearfix">
                <div class="calendar">
                    <div class="select-box clearfix">
                        <div class="select-year pull-left">
                            <div class="select-year-box cf_a">
                                <div class="select-year-val pull-left text_uncheck" year_val="{$year}"><font>{$year}</font>年 </div>
                                <div class="select-year-icon pull-left"></div>
                                <div class="select-year-val-list">
                                    <?php for($y= $min_year;$y<=date('Y');$y++){?>
                                    <div class="select-year-list-val" year_val="{$y}"><font>{$y}</font>年 </div>
                                    <?php }?>
                                </div>
                            </div>
                        </div>
                        <div class="select-month pull-left">
                            <div class="select-month-box cf_a">
                                <div class="select-month-icon-1 pull-left"  onclick="change_month_minus()"></div>
                                <div class="select-month-val-box pull-left clearfix">
                                    <div class="select-month-val pull-left text_uncheck"><font>{$month}</font>月 </div>
                                    <div class="select-month-icon-2 pull-left" ></div>
                                    <div class="select-month-val-list">
                                        <?php
                                        for($i=1;$i<13;$i++){
                                            ?>
                                            <div class="select-month-list-val" onclick="change_month({$i})"><font>{$i}</font>月 </div>
                                        <?php }?>
                                    </div>
                                </div>
                                <div class="select-month-icon-3 pull-left" onclick="change_month_plus()"></div>
                            </div>
                        </div>
                        <div class="leave-btn pull-left"  style="width:86px">
                            <a href="javascript:;" onclick="back_today({:date('Y')},{:date('n')})" style="width:86px">返回今天</a>
                        </div>

                    </div>
                    <div class="calendar_box">
                        <table cellpadding="3" cellspacing="0" border="0" width="100%">
                            <thead>
                            <tr>
                                <th>一</th>
                                <th>二</th>
                                <th>三</th>
                                <th>四</th>
                                <th>五</th>
                                <th>六</th>
                                <th class="color-red">日</th>
                            </tr>
                            </thead>
                            <tbody class="calendar-tbox">
                            {$calendar}
                            </tbody>
                        </table>
                    </div>
                    <div class="leave-view">
                        <p class="leave-title">视频范围</p>
                        <div class="leave-date-info">
                            <div class="playback-contorl-container">

                                <div class="form-group">
                                    <label class="control-label">日期选择</label>
                                    <input type="text" name="date" class="form-control" value="" readonly placeholder="请选择日历上的日期">
                                </div>
                                <div class="form-group">
                                    <label class="control-label">时间选择</label>
                                    <input type="text" class="Wdate form-control" name="stime" readonly autocomplete="off" onFocus="WdatePicker({dateFmt:'HH:mm:ss',isShowClear:true,isShowToday:true,errDealMode:-1})" />
                                    至
                                    <input type="text" class="Wdate form-control" name="otime" readonly autocomplete="off" onFocus="WdatePicker({dateFmt:'HH:mm:ss',isShowClear:true,isShowToday:true,errDealMode:-1})" />
                                </div>

                                <button type="button" class="btn btn-primary glyphicon glyphicon-download-alt">下载</button>
                                <button type="button" class="btn btn-primary glyphicon glyphicon-search">预览</button>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script>

        var vods_download_timer = <?php echo config("vods_download_timer")->value()*3600?>;

        function checkdate(obj){
            if (!$(obj).hasClass("data-list-box-focus")) {
                $(".calendar-tbox .data-list-box").removeClass("data-list-box-focus");
                $(obj).addClass("data-list-box-focus");
            }

            $("input[name=date]").val($(obj).attr("date-time"));
        }

        var id = "{$info.id}";
        $(function(){

            $(".glyphicon-download-alt").click(function () {
                var datetime = $("input[name=date]").val();
                if (!datetime) {
                    dialog.tips("error","请选择需要下载视频的日期");
                    return false;
                }
                var stime = $("input[name=stime]").val();
                var otime = $("input[name=otime]").val();
                if (!stime) {
                    dialog.tips("error","请选择视频开始时间");
                    return false;
                }
                if (!otime) {
                    dialog.tips("error","请选择视频结束时间");
                    return false;
                }

                if (ttn(otime)-ttn(stime)>vods_download_timer) {
                    dialog.tips("error","视频长度不得大于<?php echo config("vods_download_timer")->value()?>小时");
                    return false;
                }
                var url_str = "{$down_url}";
                url_str += "&starttime="+timefliter(datetime+" "+stime);
                url_str += "&endtime="+timefliter(datetime+" "+otime);

                window.open(url_str);

            });

            $(".select-year").click(function(){
                if($(this).hasClass("select-year-val-list_show")){
                    $(this).removeClass("select-year-val-list_show")
                    $(".select-year-val-list").hide();
                }else{
                    $(this).addClass("select-year-val-list_show")
                    $(".select-year-val-list").show();
                }
            })
            $(".select-year-list-val").click(function(){
                var cyear = $(this).attr("year_val");
                var cmonth = $(".select-month-val font").text();

                $(".select-year-val").attr("year_val",cyear);
                $(".select-year-val font").text(cyear);

                dialog.loading.show();
                $.when($.post("__URL__/get_calendar",{year:cyear,month:cmonth,id:id}))
                    .always(function() {
                        dialog.loading.hide();
                    })
                    .done(function (response) {
                        $(".calendar-tbox").html(response.data);
                    })

                //window.location.href="__URL__/detail/id/{$info.sysid}/year/"+cyear+"/month/{$month}";
            })
            $(".select-month-val-box").click(function(){
                if($(this).hasClass("select-month-val-list_show")){
                    $(this).removeClass("select-month-val-list_show")
                    $(".select-month-val-list").hide();
                }else{
                    $(this).addClass("select-month-val-list_show")
                    $(".select-month-val-list").show();
                }
            })

            $(".calendar-tbox").on("click",".not_m",function () {
                dialog.tips("error","对不起，该日期无视频！");
            })


        });
        function change_month_minus() {
            var cmonth = parseInt($(".select-month-val font").text());
            if (cmonth>1) {
                change_month(cmonth-1);
            } else {
                var cyear = parseInt($(".select-year-val").attr("year_val"));

                $(".select-year-val").attr("year_val",cyear-1);
                $(".select-year-val font").text(cyear-1);
                change_month(12);
            }
        }

        function change_month_plus() {
            var cmonth = parseInt($(".select-month-val font").text());
            if (cmonth<12) {
                change_month(cmonth+1);
            } else {
                var cyear = parseInt($(".select-year-val").attr("year_val"));

                $(".select-year-val").attr("year_val",cyear+1);
                $(".select-year-val font").text(cyear+1);
                change_month(1);
            }
        }

        function change_month(value){
            var cyear = $(".select-year-val").attr("year_val");
            $(".select-month-val font").text(value);

            dialog.loading.show();
            $.when($.post("__URL__/get_calendar",{year:cyear,month:value,id:id}))
                .always(function() {
                    dialog.loading.hide();
                })
                .done(function (response) {
                    $(".calendar-tbox").html(response.data);
                })
            /*if(value!=0){
                window.location.href="__URL__/detail/id/{$info.sysid}/year/{$year}/month/"+value;
            }*/
        }
        function back_today(year,month){
            $(".select-year-val").attr("year_val",year);
            $(".select-year-val font").text(year);
            $(".select-month-val font").text(month);
            dialog.loading.show();
            $.when($.post("__URL__/get_calendar",{year:year,month:month,id:id}))
                .always(function() {
                    dialog.loading.hide();
                })
                .done(function (response) {
                    $(".calendar-tbox").html(response.data);
                });

            //window.location.href="__URL__/detail/id/{$info.sysid}/year/"+year+"/month/"+month;
        }
    </script>
</body>
</html>
