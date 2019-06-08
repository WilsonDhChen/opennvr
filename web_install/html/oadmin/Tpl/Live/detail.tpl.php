<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>实时视频</title>
    {:W('jQuery')}
    {:W('Dialog')}
    {:W('Bootstrap')}
    <link rel="stylesheet" href="__STATIC__/css/ly.css">
    <script type="text/javascript" src="__STATIC__/tool/vtsplayer/flv.js"></script>
    <!--{:W('Jwplayer')}-->
    <script>
        var x=0,y=0,z=0,zoom=0,cmd='ptz',timeout=0;

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

            $(".glyphicon-arrow-left").click(function () {
                x = -1;
                ptz();
            });
            $(".glyphicon-arrow-up").click(function () {
                y = 1;
                ptz();
            });
            $(".glyphicon-arrow-down").click(function () {
                y = -1;
                ptz();
            });
            $(".glyphicon-arrow-right").click(function () {
                x = 1;
                ptz();
            });
            $(".glyphicon-zoom-in").click(function () {
                zoom = 1.5;
                ptz();
            });
            $(".glyphicon-zoom-out").click(function () {
                zoom = 0.5;
                ptz();
            });
            $(".glyphicon-registration-mark").click(function () {
                cmd = "reset";
                ptz();
            })
        });

        function ptz() {
            var data = {
                    "sysid":"{$info.sysid}",
                    "x":x,
                    "y":y,
                    "z":z,
                    "timeout":timeout
            };
            if (cmd == 'reset') {
                cmd = 'ptz';
            }
            $.when($.post("__URL__/ptz_control", data))
                .done(function (response) {
                    dialog.tips(response.status,response.info)
                });
        }

    </script>
</head>
<body style="padding:0 30px 30px " >
    <div class="play-container">
        <h3>{$info.name}</h3>
        <p>播放地址：{$play_url}</p>
        <div class="player">
            <div id="myplayer" style="width:100%;height:100%"><video style="width:100%;height:100%" class="video-player" id="video-player" controls></video></div>
        </div>
        <if condition="$info['ptzsupport'] eq 1">
            <div class="play-control-container">
                <button type="button" class="btn btn-primary btn-lg glyphicon glyphicon-arrow-left" data-toggle="tooltip" data-placement="top" title="点击向左" ></button>
                <button type="button" class="btn btn-primary btn-lg glyphicon glyphicon-arrow-up" data-toggle="tooltip" data-placement="top" title="点击向上" ></button>
                <button type="button" class="btn btn-primary btn-lg glyphicon glyphicon-arrow-down" data-toggle="tooltip" data-placement="top" title="点击向下" ></button>
                <button type="button" class="btn btn-primary btn-lg glyphicon glyphicon-arrow-right" data-toggle="tooltip" data-placement="top" title="点击向右" ></button>
                <button type="button" class="btn btn-primary btn-lg glyphicon glyphicon-zoom-in" data-toggle="tooltip" data-placement="top" title="点击放大" ></button>
                <button type="button" class="btn btn-primary btn-lg glyphicon glyphicon-zoom-out" data-toggle="tooltip" data-placement="top" title="点击缩小" ></button>
                <button type="button" class="btn btn-primary btn-lg glyphicon glyphicon-registration-mark" data-toggle="tooltip" data-placement="top" title="点击回位" ></button>
            </div>
        </if>
    </div>


</body>
</html>
