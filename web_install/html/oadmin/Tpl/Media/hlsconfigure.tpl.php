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
        <div class="panel-heading clearfix">HLS 配置</div>
        <div class="panel-body">
            <div class="gb28181-container">

                    <div class="field-lists" style="margin-bottom: 40px">
                        <form id="Formelement">
                            <div class="input-group form-inline">
                                <span class="input-group-addon">输出HLS</span>
                                <select name="output_hls" id="output_hls" class="form-control">
                                    <option value="0" {:$info['output_hls']==0?'selected':''}>否</option>
                                    <option value="1" {:$info['output_hls']==1?'selected':''}>是</option>
                                </select>
                            </div>
							<div class="input-group form-inline">
                                <span class="input-group-addon">输出到内存</span>
                                <select name="memory_file" id="memory_file" class="form-control">
									<option value="1" {:$info['memory_file']==1?'selected':''}>是</option>
                                    <option value="0" {:$info['memory_file']==0?'selected':''}>否</option> 
                                </select>
                            </div>
							<div class="input-group form-inline">
                                <span class="input-group-addon">HLS输出目录</span>
                                <input type="text" name="hls_dir" class="form-control readonly-field" value="{:$info['hls_dir']?$info['hls_dir']:'/'}" />
                            </div>
							<div class="input-group form-inline">
                                <span class="input-group-addon">HLS TS文件前缀</span>
                                <input type="text" name="hls_ts_prefix" class="form-control readonly-field" value="{$info.hls_ts_prefix}" />
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