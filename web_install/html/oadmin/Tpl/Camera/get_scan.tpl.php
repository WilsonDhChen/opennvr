<div style="width: 300px">
    <if condition="$info['return']">
        <div class="alert alert-danger" role="alert">{$info.error}</div>
    <else />
        <if condition="!$info['chs']">
            <div class="alert alert-danger" role="alert">该地址暂无通道数据</div>
        </if>
        <div class="form-group">
            <label class="control-label">选择通道</label>
            <select name="chs" id="chs" class="form-control">
                <volist name="info['chs']" id="vo">
                    <option value="{$key}">{$vo.name}</option>
                </volist>
            </select>
        </div>
        <div class="form-group">
            <label class="control-label">选择码流</label>
            <select name="profiles" id="profiles" class="form-control">
                <volist name="info['chs'][0]['profiles']" id="val">
                    <option value="{$val}">{$val}</option>
                </volist>
            </select>
        </div>
        <div class="form-group clearfix">
            <button type="button" class="btn btn-primary pull-right chs-confirm">确定</button>
        </div>
    </if>
</div>
<script>
    var chs_data = <?php echo json_encode($info['chs'])?>;
    $(function () {
        $("#chs").change(function () {
            var profiles_data = chs_data[$(this).val()];
            var profiles_html = '';
            $.each(profiles_data.profiles,function (k, v) {
                profiles_html += '<option value="'+v+'">'+v+'</option>';
            });

            $("#profiles").html(profiles_html);
        });
        $(".chs-confirm").click(function () {
            $("input[name=onvifptztoken]").val(chs_data[$("#chs").val()].ptz);
            $(".onvif-url").val($("#profiles").val());
            camerafram.close().remove();
        })
    })
</script>
