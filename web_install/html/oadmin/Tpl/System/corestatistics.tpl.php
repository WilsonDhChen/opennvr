<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>系统核心计数</title>
    {:W('jQuery')}
    {:W('Dialog')}
    {:W('Bootstrap')}
    <link rel="stylesheet" href="__STATIC__/css/ly.css">
    <script>
        
        $(function () {
            dialog.loading.show();
            $.when($.get("__URL__/getcoreinfo"))
                .always(function () {
                    dialog.loading.hide();
                })
                .done(function (data) {
                    var response = data.data;

                    if(response['return']!=0){
                        $(".spage-content").html("<h3>无法获取直播服务器状态</h3>");
                        return false;
                    }

                    var spage = '';
                    spage += '<h3>内存信息</h3>';
                    spage += '<p><span>空闲：'+format_size2(response.cached_memory.free)+'</span><span>最大：'+format_size2(response.cached_memory['max'])+'</span><span>使用：'+format_size2(response.cached_memory.used)+'</span> <span>其他：'+format_size2(response.apimemory)+'</span> </p>';
                    spage += '<p><span>TSFF：'+format_size2(response.tsffmemory)+'</span><span>LUA：'+format_size2(response.luamemory)+'</span><span>快照：'+format_size2(response.snapshotmemory)+'</span> </p>';
                    spage += '<h3>链接信息</h3>';
                    spage += '<p><span>ETS：'+response.connections.ets+'</span><span>HTTP：'+response.connections['http']+'</span><span>RTMP：'+response.connections.rtmp+'</span><span>RTSP_TS：'+response.connections.rtsp_ts+'</span></p>';
                    spage += '<h3>流媒体对象计数</h3>';
                    spage += '<p>'  ;

                    spage +='<span>HLS内存切片：'+format_size2(response.obj_counter.hls_memory)+'</span>' ;
                    spage +='<span>HLS 文件数：'+response.obj_counter.hls_files+'</span>' ;
                    spage +='<span>HLS 共享IOBuffer：'+response.obj_counter.hls_shared_iobuffer+'</span>' ;
                    spage +='<span>HLS Event：'+response.obj_counter.hls_events+'</span>' ;
                    spage +='<span>HLS TS Muxer共享IOBuffer：'+response.obj_counter.hlsts_muxer_shared_iobuffer+'</span><br>' ;

                    spage +='<span>RTMP Session：'+response.obj_counter.rtmp_sessions+'</span>' ;
                    spage +='<span>RTMP Network Session：'+response.obj_counter.rtmp_net_sessions+'</span>' ;
                    spage +='<span>RTMP Event：'+response.obj_counter.rtmp_events+'</span><br>' ;

                    spage +='<span>FLV 共享IOBuffer：'+response.obj_counter.flv_shared_iobuffer+'</span><br>' ;

                    spage +='<span>MP4 共享IOBuffer：'+response.obj_counter.mp4_shared_iobuffer+'</span>' ;
                    spage +='<span>MP4 Event：'+response.obj_counter.mp4_events+'</span><br>' ;

                    spage +='<span>RTSP Network Session：'+response.obj_counter.rtsp_net_sessions+'</span>' ;
                    spage +='<span>RTSP Event：'+response.obj_counter.rtsp_events+'</span>' ;
                    spage +='<span>RTSP IOBuffer：'+response.obj_counter.rtsp_iobuffer+'</span>' ;
                    spage +='<span>RTSP 共享IOBuffer：'+response.obj_counter.rtsp_shared_iobuffer+'</span><br>' ;


                    spage +='<span>HTTPTS Network Session：'+response.obj_counter.httpts_net_sessions+'</span>' ;
                    spage +='<span>HTTPTS Event：'+response.obj_counter.httpts_events+'</span><br>' ;

                    spage +='<span>HTTP HLS Network Session：'+response.obj_counter.httphls_net_sessions+'</span>' ;
                    spage +='<span>HTTP HLS Event：'+response.obj_counter.httphls_events+'</span><br>' ;

                    spage +='<span>ETS Network Session：'+response.obj_counter.ets_net_sessions+'</span>' ;
                    spage +='<span>ETS Event：'+response.obj_counter.ets_events+'</span><br>' ;
                    
                    
                    spage +='<span>UDP Network Session：'+response.obj_counter.udp_ctxs+'</span><br>' ;

                    spage +='<span>Send Event：'+response.obj_counter.send_events+'</span>' ;
                    spage +='<span>视频源 TS Muxer共享IOBuffer：'+response.obj_counter.sourcets_muxer_shared_iobuffer+'</span>' ;
                    spage +='<span>H264 Nalu Item：'+response.obj_counter.h264_naluitems+'</span>' ;
                    spage +='<span>视频源：'+response.obj_counter.mdsource+'个</span><br>' ;
                     spage +='<span>缓存的视频：'+format_size2(response.obj_counter.cached_video_memory)+'</span><br>'  ;
                    spage +='<span>缓存的视频帧：'+response.obj_counter.cached_video_frames+'</span><br>'  ;
                    spage +='<span>缓存的视频组：'+response.obj_counter.cached_video_framess+'</span><br>'   ;

                    spage += '</p>'  ;

                    spage += '<h3>核心对象计数</h3>';
                    spage += '<p>'  ;
                    spage +='<span>NetIOBuffer：'+response.obj_counter.netiobuffer+'</span><br>' ;
                    spage +='<span>Event：'+response.obj_counter.event+'</span><br>' ;
                    spage +='<span>ConnContext：'+response.obj_counter.conncontext+'</span><br>' ;
                    spage +='<span>Shared Memory：'+response.obj_counter.sharedmem_counter+'</span><br>' ;
                    spage +='<span>Malloc2：'+response.obj_counter.malloc2+'</span><br>' ;
                    spage += '</p>'  ;

                    spage += '<h3>运行时间：'+toTime(response.uptime)+'</h3>';
                    $(".spage-content").html(spage);
                })
        })

        function toTime(mins){

            var days  = Math.floor(mins/1440);
            var hours = Math.floor(mins/60)%24;
            var minss = mins%60;

            var s = '';

            if(days){
                s += days+'天';
            }
            if(hours){
                s += hours+'小时';
            }

            s += minss+'分钟';

            return s;
        }

        //单位转换
        function format_size2($bytes){

            $value = $bytes/Math.pow(2,40);
            if($value>1){
                return $value.toFixed(2)+'TB';
            }

            $value = $bytes/Math.pow(2,30);
            if($value>1){
                return $value.toFixed(2)+'GB';
            }

            $value = $bytes/Math.pow(2,20);
            if($value>1){
                return $value.toFixed(2)+'MB';
            }

            $value = $bytes/Math.pow(2,10);
            if($value>1){
                return $value.toFixed(2)+'KB';
            }

            return $value+'B';

        }
    </script>
</head>
<body style="padding: 30px " >

    <div class="panel panel-default">
        <div class="panel-heading clearfix">
            系统核心计数
        </div>
        <div class="panel-body">

            <div class="spage-content">

            </div>

        </div>


    </div>

</body>
</html>