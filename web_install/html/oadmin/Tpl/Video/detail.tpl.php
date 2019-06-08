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
    <!--{:W('Jwplayer')}-->
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
                var stime = $("input[name=stime]").val();
                var otime = $("input[name=otime]").val();
                if (!stime && !otime) {
                    return false;
                }
                var url_str = '';
                if (stime) {
                    url_str += "&starttime="+timefliter(stime);
                }
                if (otime) {
                    url_str += "&endtime="+timefliter(otime);
                }
                url_str = "{$play_url}"+url_str;
                jwplayer("myplayer").setup({
                    file: url_str,
                    width: "100%",
                    height:"100%",
                    volume:60,
                    useaudio:true,
                    autostart:true
                });

            })
            
            $(".glyphicon-download-alt").click(function () {
                var stime = $("input[name=stime]").val();
                var otime = $("input[name=otime]").val();
                if (!stime || !otime) {
                    dialog.tips("error","请选择视频时间段");
                    return false;
                }
            })



        });

        function timefliter(value) {
            var timer = value.replace(new RegExp('-','gm'),'');
            timer = timer.replace(" ","");
            timer = timer.replace(new RegExp(':','gm'),'');
            return timer;
        }


    </script>
</head>
<body style="padding: 30px " >
    <div class="play-container">
        <div class="player">
            <div id="myplayer" style="width:100%;height:100%"><video style="width:100%;height:100%" class="video-player" id="video-player" controls></video></div>
        </div>
        <div class="playback-contorl-container">
            <form id="FormItem">
                <div class="form-group form-inline">
                    <label class="control-label">时间选择</label>
                    <input type="text" class="Wdate form-control" name="stime" readonly autocomplete="off" onFocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',isShowClear:true,isShowToday:true,errDealMode:-1})" />
                    至
                    <input type="text" class="Wdate form-control" name="otime" readonly autocomplete="off" onFocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',isShowClear:true,isShowToday:true,errDealMode:-1})" />
                    <button type="button" class="btn btn-primary glyphicon glyphicon-search">预览</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
