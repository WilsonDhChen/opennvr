<div id="witaForm_Rule_Maker">
<style type="text/css">
.ui-dialog-ajax .ui-dialog-body{
    padding: 0;
}
.ui-dialog-ajax .ui-dialog-footer{
    border-top: 1px solid #E5E5E5;
    padding-top: 10px;
    padding-bottom: 10px;
}
.ui-dialog-ajax .ui-dialog-content{
    max-height: 600px;
    overflow-y: auto;
    overflow-x: hidden;
}
.ui-dialog-ajax .ui-dialog-content::-webkit-scrollbar-track,
.scrollbar::-webkit-scrollbar-track {
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
    background-color: #F5F5F5;
    border-radius: 0;
}

.ui-dialog-ajax .ui-dialog-content::-webkit-scrollbar,
.scrollbar::-webkit-scrollbar{
    width: 6px;
    background-color: #F5F5F5;
    border-radius: 0;
}

.ui-dialog-ajax .ui-dialog-content::-webkit-scrollbar-thumb,
.scrollbar::-webkit-scrollbar-thumb{
    background-color: #666;
    border-radius: 0;
}

#witaForm_Rule_Maker{
	min-width: 500px;
}
#witaForm_Rule_Maker .hide{
    display: none;
}
#witaForm_Rule_Maker .invisible{
    visibility: hidden;
}
#witaForm_Rule_Maker .oaui-form .field{
    text-align: right;
    width: 30%;
}
#witaForm_Rule_Maker .oaui-form .type-text {
    background-color: #70b5f1;
    color: #fff;
    border-radius: 3px;
    padding: 4px 12px;
}

#witaForm_Rule_Maker .oaui-form .sort-input-text {
    width: 126px;
}

#witaForm_Rule_Maker .oaui-form .callback-textarea{
    font-size: 12px;
    font-family: "Courier New", sans-serif;
    color: #2b7dcc;
    min-height: 140px;
}
#witaForm_Rule_Maker .oaui-form .rule-item-selected {
    height: 48px;
}

</style>

<table width="100%" class="oaui-form">
    <thead>
        <tr>
            <td class="field"><strong>字段类型</strong>：</td>
            <td class="input"><span class="type-text">{$type}</span><input type="hidden" value="{$type}" id="fieldType"></td>
        </tr>
    </thead>
    <tbody>
    <if condition="in_array($type, array('text', 'password', 'number', 'email', 'url', 'search', 'tel', 'textarea','date', 'time', 'datetime'))">
        <tr data-rule="required">
            <td class="field"><strong>必须填写</strong>：</td>
            <td class="input form-item-group">
                <label><input type="checkbox" value="true"></label>
            </td>
        </tr>
    </if>
    <if condition="in_array($type, array('radio', 'checkbox'))">
        <tr data-rule="checked">
            <td class="field"><strong>必须选择</strong>：</td>
            <td class="input form-item-group">
                <label><input type="checkbox" value="true"></label>
            </td>
        </tr>
    </if>
    <eq name="type" value="file">
        <tr data-rule="uploaded">
            <td class="field"><strong>必须上传</strong>：</td>
            <td class="input form-item-group">
                <label><input type="checkbox" value="true"></label>
            </td>
        </tr>
        <tr id="ruleOptionFileExtLimit">
            <td class="field"><strong>类型限制</strong>：</td>
            <td class="input form-item-group">
                <label><input type="checkbox" value="true" id="fileExtLimitOptions"></label>
            </td>
        </tr>
        <tr data-rule="ext" class="hide">
            <td class="field"><strong>允许扩展名</strong>：</td>
            <td class="input form-item-group">
                <input type="text" class="oaui-input-text" placeholder="扩展名不包含点,多个以半角逗号分隔" value="">
            </td>
        </tr>
    </eq>
    <eq name="type" value="select">
        <tr data-rule="selected">
            <td class="field"><strong>必须选择</strong>：</td>
            <td class="input form-item-group rule-item-selected">
                <label><input type="checkbox" value="true" id="selectOptions"></label>
                <label class="invisible">- <input type="text" class="oaui-input-text sort-input-text" placeholder="排除值" value=""></label>
            </td>
        </tr>
    </eq>
    <if condition="in_array($type, array('checkbox', 'select'))">
        <tr id="ruleOptionMultiItem">
            <td class="field"><strong>多项选择</strong>：</td>
            <td class="input form-item-group">
                <label><input type="checkbox" value="true" id="multiItemsOptions"></label>
            </td>
        </tr>
    </if>
    <eq name="type" value="checkbox">
        <tr data-rule="minchecked" class="rule-group-checked hide">
            <td class="field"><strong>最少选择</strong>：</td>
            <td class="input form-item-group">
                <input type="number" class="oaui-input-text" min="0" placeholder="最少选择项个数" value="">
            </td>
        </tr>
        <tr data-rule="maxchecked" class="rule-group-checked hide">
            <td class="field"><strong>最多选择</strong>：</td>
            <td class="input form-item-group">
                <input type="number" class="oaui-input-text" min="0" placeholder="最多选择项个数" value="">
            </td>
        </tr>
    </eq>
    <eq name="type" value="select">
        <tr data-rule="minselected" class="rule-group-selected hide">
            <td class="field"><strong>最少选择</strong>：</td>
            <td class="input form-item-group">
                <input type="number" class="oaui-input-text" min="0" placeholder="最少选择项个数" value="">
            </td>
        </tr>
        <tr data-rule="maxselected" class="rule-group-selected hide">
            <td class="field"><strong>最多选择</strong>：</td>
            <td class="input form-item-group">
                <input type="number" class="oaui-input-text" min="0" placeholder="最多选择项个数" value="">
            </td>
        </tr>
    </eq>
    <eq name="type" value="text">
        <tr id="ruleOptionTextBuiltIn">
            <td class="field"><strong>内置规则</strong>：</td>
            <td class="input">
                <select class="oaui-select" title="内置规则" id="textOptions">
                    <option value="empty">不使用内置规则</option>
                    <option value="email">电子邮箱</option>
                    <option value="telephone">电话号码(中国)</option>
                    <option value="cellphone">手机号码(中国)</option>
                    <option value="url">URL网址</option>
                    <option value="qq">QQ号码(5~11位数字)</option>
                    <option value="zip">邮政编码(中国)</option>
                    <option value="idcard">身份证号(中国)</option>
                    <option value="currency">货币金额</option>
                    <option value="datetime">日期时间(YYYY-mm-dd HH:ii:ss)</option>
                    <option value="date">日期(YYYY-mm-dd)</option>
                    <option value="time">时间(HH:ii:ss)</option>
                    <option value="number">数字字符</option>
                    <option value="numeric">数字(多进制)</option>
                    <option value="integer">整数</option>
                    <option value="ptint">正整数</option>
                    <option value="ntint">负整数</option>
                    <option value="decimal">浮点数</option>
                    <option value="english">字母字符</option>
                    <option value="chinese">汉字字符</option>
                </select>
            </td>
        </tr>
    </eq>
    <eq name="type" value="email">
        <tr data-rule="email">
            <td class="field"><strong>电子邮箱</strong>：</td>
            <td class="input form-item-group">
                <label><input type="checkbox" value="true" disabled checked></label>
            </td>
        </tr>
    </eq>
    <eq name="type" value="number">
        <tr data-rule="number">
            <td class="field"><strong>数字类型</strong>：</td>
            <td class="input form-item-group">
                <label><input type="checkbox" value="true" disabled checked></label>
            </td>
        </tr>
    </eq>
    <eq name="type" value="tel">
        <tr data-rule="tel">
            <td class="field"><strong>电话号码</strong>：</td>
            <td class="input">
                <select class="oaui-select" title="选择号码类型" id="rulePhoneTypeOptions">
                    <option value="number">通用号码(数字)</option>
                    <option value="cellphone">手机号码(中国)</option>
                    <option value="telephone">固话号码(中国)</option>
                </select>
            </td>
        </tr>
    </eq>
    <eq name="type" value="url">
        <tr data-rule="url">
            <td class="field"><strong>URL网址</strong>：</td>
            <td class="input form-item-group">
                <label><input type="checkbox" value="true" disabled checked></label>
            </td>
        </tr>
    </eq>
    <eq name="type" value="date">
        <tr data-rule="date">
            <td class="field"><strong>日期</strong>：</td>
            <td class="input form-item-group">
                <label><input type="checkbox" value="true" disabled checked></label>
            </td>
        </tr>
    </eq>
    <eq name="type" value="time">
        <tr data-rule="time">
            <td class="field"><strong>时间</strong>：</td>
            <td class="input form-item-group">
                <label><input type="checkbox" value="true" disabled checked></label>
            </td>
        </tr>
    </eq>
    <eq name="type" value="datetime">
        <tr data-rule="datetime">
            <td class="field"><strong>日期时间</strong>：</td>
            <td class="input form-item-group">
                <label><input type="checkbox" value="true" disabled checked></label>
            </td>
        </tr>
    </eq>
    <if condition="in_array($type, array('text', 'password', 'number', 'email', 'url', 'search', 'textarea'))">
        <tr data-rule="minlength" class="rule-group-length">
            <td class="field"><strong>最小长度</strong>：</td>
            <td class="input form-item-group">
                <input type="number" class="oaui-input-text" min="0" placeholder="输入的字符最小长度" value="">
            </td>
        </tr>
        <tr data-rule="maxlength" class="rule-group-length">
            <td class="field"><strong>最大长度</strong>：</td>
            <td class="input form-item-group">
                <input type="number" class="oaui-input-text" min="0" placeholder="输入的字符最大长度" value="">
            </td>
        </tr>
    </if>
    <if condition="in_array($type, array('text', 'number'))">
        <tr data-rule="minrange" class="rule-group-range">
            <td class="field"><strong>最小数字</strong>：</td>
            <td class="input form-item-group">
                <input type="number" class="oaui-input-text" placeholder="输入的数字最小数值"  value="">
            </td>
        </tr>
        <tr data-rule="maxrange" class="rule-group-range">
            <td class="field"><strong>最大数字</strong>：</td>
            <td class="input form-item-group">
                <input type="number" class="oaui-input-text" placeholder="输入的数字最大数值"  value="">
            </td>
        </tr>
    </if>
    <if condition="in_array($type, array('text', 'password', 'search', 'hidden', 'textarea'))">
    <tr data-rule="regexp">
        <td class="field"><strong>正则规则</strong>：</td>
        <td class="input form-item-group">
            <input type="text" class="oaui-input-text sort-input-text" placeholder="正则表达式" value="">-<input type="text" class="oaui-input-text sort-input-text" placeholder="错误提示信息" value="">
        </td>
    </tr>
    </if>
    <tr data-rule="callback">
        <td class="field"><strong>回调规则</strong>：</td>
        <td class="input form-item-group">
            <textarea class="oaui-textarea callback-textarea scrollbar" placeholder="【自定义回调函数验证说明】&#xA此处请直接写函数体代码，&#xAthis为当前字段对象，&#xA可通过$(this).val()获取值，&#xA如验证正确请return true，&#xA如验证错误请return '错误提示信息' 。"></textarea>
        </td>
    </tr>
    </tbody>
</table>

<script>
$(function(){
    var fieldType = $('#fieldType').val();
	var srcRule = {<?php echo $rule;?>};
	var newRule = {};

    var $maker = $('#witaForm_Rule_Maker');


	//
	dialog.getCurrent().button(
        [
            {
                value:'生成规则',
                autofocus:true,
                callback:function(){
                    this.close(parseNewRule()).remove();
                }
            },
            {
                value:'取消',
                callback:function(){
                    this.close(false).remove();
                }
            }
        ]
    );

    //
    $('#selectOptions').on('change', function () {
        var $exclude = $(this).parent('label').next('label');
        if($(this).is(':checked')) {
            $exclude.removeClass('invisible');
        } else {
            $exclude.addClass('invisible');
        }
    });
    //
    $('#multiItemsOptions').on('change', function () {
        var maps = {select:'selected', checkbox:'checked'};
        var $group = $maker.find("tr.rule-group-"+maps[fieldType]);
        if($(this).is(':checked')) {
            $group.show();
        } else {
            $group.hide();
        }
    });
    //fileExtLimitOptions
    $('#fileExtLimitOptions').on('change', function () {

        if($(this).is(':checked')) {
            $maker.find("tr[data-rule='ext']").show();
        } else {
            $maker.find("tr[data-rule='ext']").hide();
        }
    });
    //rulePhoneTypeOptions
    $('#rulePhoneTypeOptions').on('change', function () {
        var maps = ['number', 'telephone' ,'cellphone'], vaule;
        for(var i=0; i<maps.length; i++) {
            value = $(this).val();
            if(maps[i]==value) {
                newRule[$(this).val()] = true;
            }else if(maps[i] in newRule){
                delete newRule[maps[i]];
            }
        }

    });


    //
    var ruleLoadHandlers = {
        required:function () {
            if('required' in srcRule && srcRule.required) {
                $(this).find(":checkbox:eq(0)").prop('checked', true);
            }
        },
        minlength:function () {
            if('minlength' in srcRule && /^[0-9]+$/.test(srcRule.minlength)) {
                $(this).find("input:eq(0)").val(srcRule.minlength);
            }
        },
        maxlength:function () {
            if('maxlength' in srcRule && /^[0-9]+$/.test(srcRule.maxlength)) {
                $(this).find("input:eq(0)").val(srcRule.maxlength);
            }
        },
        minrange:function () {
            if('minrange' in srcRule && $.isNumeric(srcRule.minrange)) {
                $(this).find("input:eq(0)").val(srcRule.minrange);
            }
        },
        maxrange:function () {
            if('maxrange' in srcRule && $.isNumeric(srcRule.maxrange)) {
                $(this).find("input:eq(0)").val(srcRule.maxrange);
            }
        },
        regexp:function () {
            if('regexp' in srcRule) {
                var pattern,tips;
                if($.isArray(srcRule.regexp)) {
                    pattern = srcRule.regexp[0];
                    tips = srcRule.regexp[1];
                }else if($.isPlainObject(srcRule.regexp)){
                    pattern = srcRule.pattern;
                    tips = srcRule.tips;
                }

                $(this).find("input:eq(0)").val(pattern);
                $(this).find("input:eq(1)").val(tips);
            }
        },
        callback:function () {
            if('callback' in srcRule && $.type(srcRule.callback)==='function') {
                var functionCode = srcRule.callback.toString();
                var functionBody = functionCode.match(/function<?php echo $backslash?>s*<?php echo $backslash?>(<?php echo $backslash?>)<?php echo $backslash?>{([<?php echo $backslash?>s<?php echo $backslash?>S]*)<?php echo $backslash?>}/i);
                $(this).find("textarea:eq(0)").val(functionBody[1] || '');
            }
        },
        url:function () {

        },
        email:function () {

        },
        number:function () {
            
        },
        date:function () {

        },
        time:function () {

        },
        datetime:function () {

        },
        tel:function () {

            var maps = ['number', 'telephone' ,'cellphone'];
            var $select = $(this).find("select:eq(0)");
            for(var i=0; i<maps.length; i++) {
                if(maps[i] in srcRule && srcRule[maps[i]]) {
                    $select.find("option[value='"+maps[i]+"']").prop('selected', true);
                }
            }
        },
        selected:function () {
            if('selected' in srcRule) {
                $(this).find(":checkbox:eq(0)").prop('checked', true).trigger('change');

                var value;
                if($.isArray(srcRule.selected)) {
                    var arrayString = srcRule.selected.toString();
                    if (arrayString.indexOf("'")===-1) {
                        arrayString = "'"+arrayString.replace(/<?php echo $backslash?>,/g, "','")+"'";
                    } else {
                        arrayString = '"'+arrayString.replace(/<?php echo $backslash?>,/g, '","')+'"';
                    }
                    value = "["+arrayString+"]";
                }else if(srcRule.selected===''){
                    value = "''";
                }else{
                    value = srcRule.selected;
                }

                $(this).find(":text:eq(0)").val(value);
            }
        },
        checked:function () {
            if('checked' in srcRule && srcRule.checked) {
                $(this).find(":checkbox:eq(0)").prop('checked', true);
            }
        },
        uploaded:function () {
            if('uploaded' in srcRule && srcRule.uploaded) {
                $(this).find(":checkbox:eq(0)").prop('checked', true);
            }
        }

    };
    function parseSrcRule() {

        $('.oaui-form', $maker).find("tr:visible").each(function () {
            var ruleName = $(this).data('rule');
            if(ruleName) {
                ruleLoadHandlers[ruleName].call(this);
            } else {
                parseLoadOptions.call(this);
            }
        });

    }

    function parseLoadOptions() {

        var ruleOption = $(this).prop('id');

        switch (ruleOption) {
            case 'ruleOptionTextBuiltIn' :
                $('#textOptions', $maker).find('option').each(function () {
                    var builtIn = $(this).val();
                    if(builtIn=='empty') {
                        return true;
                    }
                    if(builtIn in srcRule) {
                        $(this).prop('selected', true);
                        return false;
                    }

                });
                break;

            case 'ruleOptionMultiItem' :

                var minInRule,maxInRule;
                if(fieldType=='select') {
                    minInRule= 'minselected' in srcRule && /^[0-9]+$/.test(srcRule.minselected);
                    maxInRule= 'maxselected' in srcRule && /^[0-9]+$/.test(srcRule.maxselected);
                } else if(fieldType=='checkbox') {
                    minInRule= 'minchecked' in srcRule && /^[0-9]+$/.test(srcRule.minchecked);
                    maxInRule= 'maxchecked' in srcRule && /^[0-9]+$/.test(srcRule.maxchecked);
                }



                if(minInRule){
                    if(fieldType=='select') {
                        $("tr[data-rule='minselected']", $maker).find('input:eq(0)').val(srcRule.minselected);
                    } else if(fieldType=='checkbox') {
                        $("tr[data-rule='minchecked']", $maker).find('input:eq(0)').val(srcRule.minchecked);
                    }
                }
                if(maxInRule){

                    if(fieldType=='select') {
                        $("tr[data-rule='maxselected']", $maker).find('input:eq(0)').val(srcRule.maxselected);
                    } else if(fieldType=='checkbox') {
                        $("tr[data-rule='maxchecked']", $maker).find('input:eq(0)').val(srcRule.maxchecked);
                    }
                }

                if(minInRule || maxInRule) {
                    $(this).find(":checkbox").prop('checked', true);
                    if(fieldType=='select') {
                        $("tr.rule-group-selected").show();
                    } else if(fieldType=='checkbox') {
                        $("tr.rule-group-checked").show();
                    }

                }else {
                    $(this).find(":checkbox").prop('checked', false);
                    if(fieldType=='select') {
                        $("tr.rule-group-selected").hide();
                    } else if(fieldType=='checkbox') {
                        $("tr.rule-group-checked").hide();
                    }
                }

                break;

            case 'ruleOptionFileExtLimit':
                var $ext = $("tr[data-rule='ext']", $maker).find('input:eq(0)');
                if('ext' in srcRule && srcRule.ext){
                    $ext.val(srcRule.ext);
                    $(this).find(":checkbox").prop('checked', true).trigger('change');
                }

                break;

        }

    }

    //
    var placeholders = [];

    var ruleHandlers = {
        required : function () {
            if($(this).find(':checkbox').is(':checked')) {
                newRule.required = true;
            }
        },
        minlength:function () {
            var value = $.trim($(this).find('input:eq(0)').val());
            if(!/^[0-9]+$/.test(value)) {
                return false;
            }
            newRule.minlength = parseInt(value);

        },
        maxlength:function () {
            var value = $.trim($(this).find('input:eq(0)').val());
            if(!/^[0-9]+$/.test(value)) {
                return false;
            }
            newRule.maxlength = parseInt(value);

        },
        minrange:function () {
            var value = $.trim($(this).find('input:eq(0)').val());
            if(!$.isNumeric(value)) {
                return false;
            }
            newRule.minrange = parseFloat(value);

        },
        maxrange:function () {
            var value = $.trim($(this).find('input:eq(0)').val());
            if(!$.isNumeric(value)) {
                return false;
            }
            newRule.maxrange = parseFloat(value);

        },
        regexp:function () {

            var placeholder = {};
            var value = [];
            value[0] = placeholder.identifier = '[%REGEXP'+Math.random()+'%]';

            placeholder.content = $.trim($(this).find('input:eq(0)').val());

            if(placeholder.content==='') {
                return false;
            }
            //兼容正则表达式没有写//定界符情况
            if(!(placeholder.content.indexOf('/')===0 && placeholder.content.lastIndexOf('/')===placeholder.content.length-1)){
                placeholder.content = '/'+placeholder.content+'/';
            }

            value[1] = $.trim($(this).find('input:eq(1)').val()) || '';
            placeholders.push(placeholder);
            newRule.regexp = value;

        },
        url:function () {
            var checked = $(this).find(':checkbox:eq(0)').is(':checked');
            if(checked) {
                newRule.url = true;
            }
        },
        email:function () {
            var checked = $(this).find(':checkbox:eq(0)').is(':checked');
            if(checked) {
                newRule.email = true;
            }
        },
        number:function () {
            var checked = $(this).find(':checkbox:eq(0)').is(':checked');
            if(checked) {
                newRule.number = true;
            }
        },
        date:function () {
            var checked = $(this).find(':checkbox:eq(0)').is(':checked');
            if(checked) {
                newRule.date = true;
            }
        },
        time:function () {
            var checked = $(this).find(':checkbox:eq(0)').is(':checked');
            if(checked) {
                newRule.time = true;
            }
        },
        datetime:function () {
            var checked = $(this).find(':checkbox:eq(0)').is(':checked');
            if(checked) {
                newRule.datetime = true;
            }
        },
        tel:function () {
            var checked = $(this).find(':checkbox:eq(0)').is(':checked');
            if(checked) {
                newRule.tel = true;
            }
        },
        uploaded:function () {

            var checked = $(this).find(':checkbox:eq(0)').is(':checked');
            if(!checked) {
                return false;
            }

            newRule.uploaded = true;
        },
        ext:function () {
            var value = $.trim($(this).find('input:eq(0)').val());
            if(value==='') {
                return false;
            }
            newRule.ext = value;
        },
        selected:function () {

            var placeholder = {};
            var value;
            var checked = $(this).find(':checkbox:eq(0)').is(':checked');
            if(!checked) {
                return false;
            }

            var exclude = $.trim($(this).find(':text:eq(0)').val());
            if(exclude==='') {
                value = true;
            }else{
                value = placeholder.identifier = '[%SELECTED'+Math.random()+'%]';
                placeholder.content = exclude;
                placeholders.push(placeholder);
            }

            newRule.selected = value;
        },
        minselected:function () {
            var value = $.trim($(this).find('input:eq(0)').val());
            if(!/^[0-9]+$/.test(value)) {
                return false;
            }
            newRule.minselected = parseInt(value);

        },
        maxselected:function () {
            var value = $.trim($(this).find('input:eq(0)').val());
            if(!/^[0-9]+$/.test(value)) {
                return false;
            }
            newRule.maxselected = parseInt(value);

        },
        checked:function () {

            var checked = $(this).find(':checkbox:eq(0)').is(':checked');
            if(!checked) {
                return false;
            }

            newRule.checked = true;
        },
        minchecked:function () {
            var value = $.trim($(this).find('input:eq(0)').val());
            if(!/^[0-9]+$/.test(value)) {
                return false;
            }
            newRule.minchecked = parseInt(value);

        },
        maxchecked:function () {
            var value = $.trim($(this).find('input:eq(0)').val());
            if(!/^[0-9]+$/.test(value)) {
                return false;
            }
            newRule.maxchecked = parseInt(value);

        },
        callback:function () {
            var placeholder = {};
            var value;
            value = placeholder.identifier = '[%CALLBACK'+Math.random()+'%]';

            placeholder.content = $.trim($(this).find('textarea').val());

            if(placeholder.content==='') {
                return false;
            }
            placeholder.content = 'function (){<?php echo $backslash?>n'+placeholder.content+'<?php echo $backslash?>n}';

            placeholders.push(placeholder);
            newRule.callback = value;
        }
    };


    
    function parseOptions() {

        var ruleOption = $(this).prop('id');

        switch (ruleOption) {
            case 'ruleOptionTextBuiltIn' :
                var builtIn = $(this).find('select').val();
                if(builtIn!=='empty') {
                    newRule[builtIn] = true;
                }
                break;

            case 'ruleOptionMultiItem' :

                //todo

                break;
        }

    }
    
    function parseNewRule() {

        $('.oaui-form', $maker).find("tr:visible").each(function () {
            var ruleName = $(this).data('rule');
            if(ruleName) {
                ruleHandlers[ruleName].call(this);
            } else {
                parseOptions.call(this);
            }
        });

        var newRuleStr = JSON.stringify(newRule);
        newRuleStr = newRuleStr.substring(1, newRuleStr.length-1);
        //{//替换占位符}
        var length = placeholders.length;
        if(length>0) {
            for(var i=0; i<length; i++) {
                newRuleStr = newRuleStr.replace('"'+placeholders[i].identifier+'"', placeholders[i].content);
            }
        }

        return newRuleStr;
    }



    //{//解析原rule数据}
    parseSrcRule();


    //
    $('#textOptions').on('change', function () {
        var lengthMaps = ['empty', 'email', 'url', 'english', 'chinese', 'number', 'currency'];
        var rangeMaps  = ['number', 'numeric', 'integer', 'ptint', 'ntint', 'decimal', 'currency'];
        var regexpMaps  = ['empty', 'english', 'chinese', 'number', 'currency'];
        var callbackExcludekMaps  = ['qq', 'telephone', 'cellphone', 'idcard', 'zip', 'datetime', 'date', 'time'];

        var value = $(this).val();

        if($.inArray(value, lengthMaps)>-1) {
            $maker.find('.rule-group-length').show();
        }else{
            $maker.find('.rule-group-length').hide();
        }

        if($.inArray(value, rangeMaps)>-1) {
            $maker.find('.rule-group-range').show();
        }else{
            $maker.find('.rule-group-range').hide();
        }

        if($.inArray(value, regexpMaps)>-1) {
            $maker.find("tr[data-rule='regexp']").show();
        }else{
            $maker.find("tr[data-rule='regexp']").hide();
        }

        if($.inArray(value, callbackExcludekMaps)===-1) {
            $maker.find("tr[data-rule='callback']").show();
        }else{
            $maker.find("tr[data-rule='callback']").hide();
        }

    });


})
</script>

<switch name="type">
    <case value="text">
        <script>
            $('#textOptions').trigger('change');
        </script>
    </case>
    <case value="tel">
        <script>
            $('#rulePhoneTypeOptions').trigger('change');
        </script>
    </case>
</switch>

</div>