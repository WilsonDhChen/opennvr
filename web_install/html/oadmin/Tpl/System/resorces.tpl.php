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
            resorces_details();
            setInterval(resorces_details,3000);
        });

        function resorces_details() {

            $.when($.get("__URL__/get_resorces"))
                .always(function () {
                    dialog.loading.hide();
                })
                .done(function (data) {
                    var response = data.data;

                    if(response.return ==0) {
                        var netword_html = '';

                        netword_html += '<div class="form-item">';
                        netword_html += '<label class="ui-label" for="BOOTPROTO">cpu使用率</label>';
                        netword_html += (100.00 - response.cpu.idle).toFixed(2) + '%';
                        netword_html += '</div>';
                        netword_html += '<div class="form-item">';
                        netword_html += '<label class="ui-label" for="BOOTPROTO">内存使用率</label>';
                        var memused = ((response.mem.used / response.mem.total) * 100).toFixed(2) + '%';
                        netword_html += memused;
                        netword_html += '</div>';
                        $.each(response.nets, function (k, v) {
                            var speed_send = format_speed2(v.speed_send);
                            var speed_recv = format_speed2(v.speed_recv);
                            netword_html += '<div class="form-item">';
                            netword_html += '<label class="ui-label" for="BOOTPROTO">网卡' + (k + 1) + '发送</label>';
                            netword_html += speed_send;
                            netword_html += '</div>';
                            netword_html += '<div class="form-item">';
                            netword_html += '<label class="ui-label" for="BOOTPROTO">网卡' + (k + 1) + '接收</label>';
                            netword_html += speed_recv;
                            netword_html += '</div>';
                        })
                        netword_html += '<div class="form-item">';
                        netword_html += '<label class="ui-label" for="BOOTPROTO">总通道</label>';
                        netword_html += response.transcode_video.total;
                        netword_html += '</div>';
                        netword_html += '<div class="form-item">';
                        netword_html += '<label class="ui-label" for="BOOTPROTO">已使用通道</label>';
                        netword_html += response.transcode_video.started;
                        netword_html += '</div>';


                        $(".con-list").html(netword_html);
                    }
                })
        }

        function format_speed2($bytes){

            $value = $bytes/Math.pow(2,40);
            if($value>1){
                return $value.toFixed(2)+'Tb';
            }

            $value = $bytes/Math.pow(2,30);
            if($value>1){
                return $value.toFixed(2)+'Gb';
            }

            $value = $bytes/Math.pow(2,20);
            if($value>1){
                return $value.toFixed(2)+'Mb';
            }

            $value = $bytes/Math.pow(2,10);
            if($value>1){
                return $value.toFixed(2)+'Kb';
            }

            return $value.toFixed(2)+'b';

        }
    </script>
</head>
<body style="padding: 30px " >

    <div class="panel panel-default">
        <div class="panel-heading clearfix">
            资源使用
        </div>
        <div class="panel-body">

            <div class="con-list">

            </div>

        </div>


    </div>

</body>
</html>