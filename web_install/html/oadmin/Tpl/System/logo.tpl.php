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
				var formData = new FormData($( "#Formelement" )[0]);
				
                var _this = this;
				console.log(formData);
                dialog.confirm("确认更新配置？",function () {
                    dialog.loading.show();
                    $.when($.post("__URL__/logo_upload",$(_this).serialize()))
                        .always(function () {
                            dialog.loading.hide();
                        })
                        .done(function (response) {
							console.log(response);
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
        <div class="panel-heading clearfix">logo配置</div>
        <div class="panel-body">
            <div class="gb28181-container">

                    <div  class="field-lists" style="margin-bottom: 40px">
                        <form   action="__URL__/logo_upload" method="post"  enctype="multipart/form-data"><!-- id="Formelement" -->
                             <div class="form-group">
								<label for="inputfile" style="background: #000000"><img src="/oadmin/static/image/logo.png" title="logo" alt="logo" width="160" height="38"></label>
								<input type="file" id="logo_img" name='logo_img'>
								<p class="help-block">宽度160高度38透明png</p>
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