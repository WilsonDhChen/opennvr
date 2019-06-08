<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Gb28181配置</title>
    {:W('jQuery')}
    {:W('Dialog')}
    {:W('Bootstrap')}
    <link rel="stylesheet" href="__STATIC__/css/ly.css">
    <script>
        $(function () {
            $("#Formelement").submit(function () {

                var _this = this;
                dialog.confirm("确认更新配置？",function () {
                    dialog.loading.show();
                    $.when($.post("__URL__/record_update", $(_this).serialize()))
                        .always(function () {
                            dialog.loading.hide();
                        })
                        .done(function (response) {
                            dialog.tips(response.status,response.info,function () {
                                if (response.status == 'success') {
                                    window.location.reload();
                                }
                            });
                        });
                });

                return false;
            });
        });


    </script>
</head>
<body style="padding: 30px " >
    <div class="panel panel-default">
        <div class="panel-heading clearfix">录制配置</div>
        <div class="panel-body">
            <div class="gb28181-container">

                    <div class="field-lists" style="margin-bottom: 40px">
                        <form id="Formelement">
							<div class="input-group form-inline">
                                <span class="input-group-addon">录制类型</span>
                                <select name="record_type" id="record_type" class="form-control">
                                    <option value="NVR" {:$info['record_type']=='NVR'?'selected':''}>NVR</option>
                                    <option value="NVR2" {:$info['record_type']=='NVR2'?'selected':''}>NVR2</option>
									<option value="SESSION" {:$info['record_type']=='SESSION'?'selected':''}>SESSION</option>
                                </select>
                            </div>
                            <div class="input-group form-inline">
                                <span class="input-group-addon">录制TS</span>
                                <select name="record_ts" id="record_ts" class="form-control">
                                    <option value="0" {:$info['record_ts']==0?'selected':''}>否</option>
                                    <option value="1" {:$info['record_ts']==1?'selected':''}>是</option>
                                </select>
                            </div>
							<div class="input-group form-inline">
                                <span class="input-group-addon">TS录制目录</span>
                                <input type="text" name="record_ts_dir" class="form-control readonly-field" value="{$info.record_ts_dir}" />
                            </div>
							<div class="input-group form-inline">
                                <span class="input-group-addon">录制MP4</span>
                                <select name="record_mp4" id="record_mp4" class="form-control">
                                    <option value="0" {:$info['record_mp4']==0?'selected':''}>否</option>
                                    <option value="1" {:$info['record_mp4']==1?'selected':''}>是</option>
                                </select>
                            </div>
							<div class="input-group form-inline">
                                <span class="input-group-addon">MP4录制目录</span>
                                <input type="text" name="record_mp4_dir" class="form-control readonly-field" value="{$info.record_mp4_dir}" />
                            </div>
							<div class="input-group form-inline">
                                <span class="input-group-addon">录制FLV</span>
                                <select name="record_flv" id="record_flv" class="form-control">
                                    <option value="0" {:$info['record_flv']==0?'selected':''}>否</option>
                                    <option value="1" {:$info['record_flv']==1?'selected':''}>是</option>
                                </select>
                            </div>
							<div class="input-group form-inline">
                                <span class="input-group-addon">FLV录制目录</span>
                                <input type="text" name="record_flv_dir" class="form-control readonly-field" value="{$info.record_flv_dir}" />
                            </div>
                            <div class="form-group">
                                <button class="btn btn-lg btn-success">更新配置</button>
                            </div>
                        </form>
                    </div>
            </div>
        </div>


    </div>

</body>
</html>