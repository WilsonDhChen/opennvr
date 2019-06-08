<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>我的设备</title>
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
                    $.when($.post("__URL__/my_equipment", $(_this).serialize()))
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
        <div class="panel-heading clearfix">我的设备配置</div>
        <div class="panel-body">
            <div class="gb28181-container">

                    <div class="field-lists" style="margin-bottom: 40px">
                        <form id="Formelement">
                            <div class="input-group form-inline">
                                <span class="input-group-addon">设备名字</span>
                                <input type="text" name="device_name" class="form-control readonly-field" value="{$device_name}" />
                            </div>
                            <div class="input-group form-inline">
                                <span class="input-group-addon">工作模式</span>
                                <select name="mode" id="mode" class="form-control">
                                    <option value="NVR" {:$mode=='NVR'?'selected':''}>NVR</option>
                                    <option value="PLATFORM" {:$mode=='PLATFORM'?'selected':''}>PLATFORM</option>
                                </select>
                            </div>
							<div class="input-group form-inline">
                                <span class="input-group-addon">Application</span>
                                <input type="text" name="app" class="form-control readonly-field" value="{$app}" />
                            </div>
							<div class="input-group form-inline">
                                <span class="input-group-addon">Domain</span>
                                <input type="text" name="domain" class="form-control readonly-field" value="{$domain}" />
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
