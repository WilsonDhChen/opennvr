<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>视频广场</title>
    {:W('jQuery')}
    {:W('Dialog')}
    {:W('Bootstrap')}
    <script type="text/javascript" src="__STATIC__/tool/ckplayer/ckplayer.js" charset="utf-8"></script>
    <script type="text/javascript" src="__STATIC__/tool/vtsplayer/flv.js" charset="utf-8"></script>
    <script>
        $(function () {

            var play_url = '{$play_url}';
            var m3u8_play_url = "{$m3u8_play_url}";
            var videoElement = document.getElementById('videoElement');
            if (!IsPC()) {
                $("#a1").remove();
                videoElement.src = m3u8_play_url;
                videoElement.play();
            } else {

                if( flvjs.isSupported() ) {
                    $("#a1").remove();
                    var flvplayer = flvjs.createPlayer({
                        type: 'flv',
                        url: play_url
                    });
                    flvplayer.attachMediaElement(videoElement);
                    flvplayer.load();
                    flvplayer.play();

                } else {
                    $("#videoElement").remove();
                    var flashvars={
                        f:play_url,
                        c:0,
                        p:1,
                        lv:1
                    };
                    var params={bgcolor:'#FFF',allowFullScreen:true,allowScriptAccess:'always',wmode:'transparent'};
                    CKobject.embedSWF('__STATIC__/tool/ckplayer/ckplayer.swf','a1','ckplayer_a1','100%','380',flashvars,params);
                }
            }

        });

        function isWeiXin(){
            var ua = window.navigator.userAgent.toLowerCase();
            if(ua.match(/MicroMessenger/i) == 'micromessenger'){
                return true;
            }else{
                return false;
            }
        }

        function IsPC()
        {
            var userAgentInfo = navigator.userAgent;
            var Agents = new Array("Android", "iPhone", "SymbianOS", "Windows Phone", "iPad", "iPod");
            var flag = true;
            for (var v = 0; v < Agents.length; v++) {
                if (userAgentInfo.indexOf(Agents[v]) > 0) { flag = false; break; }
            }
            return flag;
        }
    </script>
</head>
<body style="width: 600px;">

<div class="player" style="width: 100%; height: 450px; background: #000000">
    <div id="a1"></div>
    <video id="videoElement" width="100%" height="100%" autoplay controls></video>
</div>


</body>
</html>