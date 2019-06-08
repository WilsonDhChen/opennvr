<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>系统工具</title>
    {:W('jQuery')}
    {:W('Dialog')}
    {:W('Bootstrap')}
    <link rel="stylesheet" href="__STATIC__/css/ly.css">
    <script>

        $(function () {
            $(".power-btn").click(function(){
                var action_type = $(this).attr("data-value");
                var info_tips = action_type=='restart'?'确定立即重启？':'确定立即关机';
                var success_tips = action_type=='restart'?'重启中，请等待':'关机中';
                dialog.confirm(info_tips,function(){
                    $.ajax({
                        url:"__URL__/tooler_post",
                        type:'POST',
                        data:{action:action_type},
                        dataType:'json',cache:false,
                        success:function(response){
                            if(response.status=='success') {
                                dialog.tips('success',success_tips);
                            } else{
                                dialog.tips('error',response.info);
                            }
                        },error:function(){
                            dialog.tips('error','网络故障，请重试！');
                        }
                    });
                });
            })
        });

    </script>
</head>
<body style="padding: 30px " >

    <div class="panel panel-default">
        <div class="panel-heading clearfix">
            系统工具
        </div>
        <div class="panel-body">
            <h3 class="alert alert-warning">系统重启</h3>
            <div class="ui-item clearfix">
                <a href="javascript:;" class="power-btn power-0 pull-left" data-value="restart"><img src="__STATIC__/image/power-0.png" width="100" alt="" /><p>立即重启</p></a>
                <a href="javascript:;" class="power-btn power-1 pull-left" data-value="shutdown"><img src="__STATIC__/image/power-1.png" width="100" alt="" /><p>立即关机</p></a>
            </div>

        </div>


    </div>

</body>
</html>