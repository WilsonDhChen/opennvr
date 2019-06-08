<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>下级在线设备</title>
    {:W('jQuery')}
    {:W('Dialog')}
    <!--{:W('Jwplayer')}-->
    {:W('Bootstrap')}
    <link rel="stylesheet" href="__STATIC__/css/ly.css">
    <script>
        $(function () {

            $('[data-toggle="tooltip"]').tooltip()
            
            $("#addrtype").change(function () {
                if($(this).val()==1) {
                    $("#sAreaNameSelect").hide();
                    $("input[name=sAreaNameInput]").show();
                } else {
                    $("#sAreaNameSelect").show();
                    $("input[name=sAreaNameInput]").hide();
                }
            })

        })
</script>
</head>
<body>
        <div style="display:none; z-index:999; position:fixed!important;top:75%;" id="player">
            <button class="btn btn-primary" id="btnClosePlayer">关闭</button>
            <div id="playerContenter"><video class="video-player" id="video-player" controls></video></div>
        </div>
<div id="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            下级在线设备
        </div>
        <div class="panel-body">
            <form method="get" class="form-inline">
                <?php if($device_name=='PLATFORM'){?> 
                <div class="input-group">
                    <label for="sParentChid" class="input-group-addon">上级平台国标ID</label>
                    <select name="sParentChid" id="sParentChid"class="form-control" >
                        <option value="">全部</option>
                        <volist name="groups['devs']" id="vo">
                            <option value="{$vo.chid}" {:$sParentChid == $vo['chid']?"selected":""}>{$vo.chid}</option>
                        </volist>
                    </select>
                </div>
                <?php }?>
                <div class="input-group">
                    <label for="sChid" class="input-group-addon">国标ID</label>
                    <input type="text" class="form-control" name="sChid" value="{$sChid}" placeholder="请输入国标ID">
                </div>
                <div class="input-group">
                    <label for="sName" class="input-group-addon">名称</label>
                    <input type="text" class="form-control" name="sName" value="{$sName}" placeholder="请输入名称">
                </div>
                <div class="input-group">

                    <label for="sChid" class="input-group-addon">
                        <select name="addrtype" id="addrtype" style="background: #eee; border: 0">
                            <option value="0" {:$addrtype == 0?"selected":''}>选择地区</option>
                            <option value="1" {:$addrtype == 1?"selected":''}>自定义输入</option>
                        </select>
                    </label>
                    <select name="sAreaNameSelect" id="sAreaNameSelect" class="form-control" style="{:$addrtype==1?'display:none':''}">
                        <volist name="addrs['address']" id="val">
                            <option value="{$val}" {:$sAreaName == $val?'selected':''}>{$val}</option>
                        </volist>
                    </select>
                    <input type="text" class="form-control"  style="{:$addrtype==1?'':'display:none'}" name="sAreaNameInput" value="{$sAreaName}" placeholder="请输入地区">
                </div>
                <div class="form-group">
                    <button class="btn btn-primary">查询</button>
                </div>
            </form>
            <div class="lists-container" style="margin-top: 15px">
                <table class="table table-bordered table-hover">
                    <thead>
                    <tr class="active">

                        <th>国标ID</th>
                        <th>上级平台国标ID</th>
                        <th>名称</th>
                        <th>地区码</th>
                        <th>经度</th>
                        <th>纬度</th>
                        <th>状态</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <volist name="lists" id="vo">
                        <tr>

                            <td>{$vo.sChid}</td>
                            <td>{$vo.sParentChid}</td>
                            <td>{$vo.sName}</td>
                            <td>{:$vo['sCivil']?"【".$vo['sCivil']."】":''}{$vo.sAreaName}</td>
                            <td>{$vo.fLongitude}</td>
                            <td>{$vo.fLatitude}</td>
                            <td>{$vo.sStatus}</td>
                            <td>
                            <button class="btn btn-primary btnPlay" data-gbid="{$vo.sChid}" style="padding:0px">播放</button>
                            <button class="btn btn-primary playState" data-gbid="{$vo.sChid}"  style="padding:0px">状态</button>
                            </td>
                        </tr>
                    </volist>
                    </tbody>

                </table>
            </div>
        </div>
        <div class="panel-footer clearfix">
            <div class="pull-right">{$page_html}</div>
        </div>

    </div>
</div>
<script type="text/javascript" src="__STATIC__/tool/vtsplayer/flv.js"></script>
<script>
var player = null;
$(".btnPlay").click(function(){
    $("#player").show();

    var gbid = $(this).data("gbid");

    $.post('/app/ajax_getflvurl', {id:gbid}, function(res) {
        if (res.code*1 === 0) {
            //var url = "http://"+window.location.hostname + ":281/gb28181/"+gbid+".flv";
            var url = res.data;
            
            var video = $('#video-player')[0];
            video.width = 320;
            video.height = 180;

            if (player !== null) {
                player.unload();
                player.detachMediaElement();
                player.destroy();
                player = null;
            }

            player = flvjs.createPlayer({
                type: 'flv',
                url: url,
                enableWorker: false,
                lazyLoadMaxDuration: 3 * 60, 
                seekType: 'range'
            });
            player.attachMediaElement(video);
            player.load();
            player.play();
        }
    }, 'json');

    //var url = "http://"+window.location.hostname + ":281/gb28181/"+gbid+".flv";
    

/*
    player = jwplayer("playerContenter").setup({
        file: url,
        width: "200px",
        height:"113px",
        volume:0,
        useaudio:true,
        autostart:true
    });
*/
});
$(".playState").click(function(){
    var gbid = $(this).data("gbid");
    // var url="http://"+window.location.hostname + ":580/streamstatus?app=gb28181&id="+gbid;
    // alert(url);
    $.ajax({
        url: "http://"+window.location.hostname + ":580/streamstatus?app=gb28181&id="+gbid,
        type: 'GET',
        dataType: 'json',
    })
        .done(function (data) {
               if (data.return == -1) {
                alert('请先点击播放获取状态');
               }else if (data.return == 0) {

                if (data.status.NoReceiveData == 1) {
                    alert('无数据接收');
                }else if(data.status.UnknowCodec == 1){
                    alert('解码格式不正确');
                }else if(data.status.FoundInvalidNalu == 1){
                    alert('错误的Nalu');
                }else{
                    alert('数据正常');
                }

               }
         
        })
});
$("#btnClosePlayer").click(function(){
    $("#player").hide();
});
</script>
</body>
</html>
