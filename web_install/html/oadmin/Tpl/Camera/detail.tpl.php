<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>视频通道参数</title>
    {:W('jQuery')}
    {:W('Dialog')}
    {:W('Bootstrap')}
    <link rel="stylesheet" href="__STATIC__/css/ly.css">
    <script>
        var ox;
        var wx;
        var dx;
        var dragging = false;
        var creading = false;
        var plan_week;
        var paln_hour;
        var drag_type;
        var plan_width = 720;
        var ml;
        var mr;
        var ow;
        var tips_hover=false;
        var time_from =false;
        var camerafram;

        $(function () {

            $('[data-toggle="tooltip"]').tooltip();

            var type_value = $("#type").val();
            if (type_value=='ExternalPush' || type_value =="GB28181") {
                $(".outurl-container").hide();
                $(".TrancodeContainer").hide();
                
                //$(".field-lists").eq($(".TrancodeContainer").index()).hide();
            }
            if(type_value =="GB28181"){
                $(".pdid").hide();
            }else{
               $(".pdid").show(); 
            }
            $("#type").change(function () {
                $(".extends-field").hide();
                $("."+$(this).val()+"-field").show();

                var value = $(this).val();
                // if(value =="GB28181"){
                //     $(".GB28181-field").hide();
                // }
                if (value=='ExternalPush' || value =="GB28181") {
                    $(".outurl-container").hide();
                    $(".TrancodeContainer").hide();
                } else {
                    $(".outurl-container").show();
                    $(".TrancodeContainer").show();
                }
                if(value =="GB28181"){
                    $(".pdid").hide();
                }else{
                   $(".pdid").show(); 
                }
            });

            $(".nav-tabs li").click(function () {
                if ($(this).hasClass("active")) {
                    return false;
                }
                $(".field-lists").hide();
                $(".nav-tabs li").removeClass("active");
                $(this).addClass("active");
                $(".field-lists").eq($(this).index()).show();

                if($(this).hasClass("RecordContainer")) {
                    ox = $(".plan-write")[0].offsetLeft+60;
                    wx = $(".plan-hours")[0].offsetLeft;
                }

            });

            $(".plan-hours").mousedown(function (event) {
                dx = event.pageX;
                dragging = true;
                plan_week = this;
                drag_type = 1;
                ml = 0;
                mr = plan_width;
                var sign = dx - ox;

                if( $(this).find(".plan-selected").length>0) {

                    $(this).find(".plan-selected").each(function () {
                        var otl = $(this)[0].offsetLeft;
                        var otr = otl+$(this).width();

                        if (otr<sign) {
                            ml = otr>ml?otr:ml;
                        }

                        if (otl>sign) {
                            mr = otl>mr?mr:otl;
                        }
                    });
                }

                var tips_top = $(this).parents(".plan-week").index()*58 + 20;
                $(".plan-time-tips-left").css({"top":tips_top+"px","left":dx - ox + wx -17});
                $(".plan-time-tips-right").css({"top":tips_top+"px","left":dx-17});
                $(".plan-time-tips-left").html(scale_to_time(sign));
                $(".plan-time-tips-right").html(scale_to_time(sign));
                event.stopPropagation();
            });

            $(document).mousemove(function(event){

                if(!dragging){
                    return false;
                }
                tips_hover = true;
                time_from = true;
                $(".plan-time-tips-hover").hide();
                $(".plan-time-form").hide();

                var ofx = event.pageX;

                //新增区域 拖拉
                if(drag_type==1) {
                    if (ofx > (ox+mr)) {
                        $("#"+paln_hour).css("width",(mr+ox-dx)+"px");
                        $(".plan-time-tips-right").html(scale_to_time(mr));
                        return false;
                    }

                    if (ofx < dx ) {
                        $("#"+paln_hour).css("width","2px");
                        return false;
                    }
                    var wdh = ofx - dx;
                    if (!creading) {
                        creading = true;
                        paln_hour = "plan_"+$(plan_week).parents(".plan-week").index()+"_"+$(plan_week).find(".plan-selected").length;
                        $(plan_week).append("<div class='plan-selected plan-hour-selected-active' id = '"+paln_hour+"' style='left:"+(dx-ox)+"px; '><div class='plan-selected-control'><div class='plan-selected-left'></div><div class='plan-selected-right'></div></div></div>");
                    }
                    $("#"+paln_hour).width(wdh);

                    $(".plan-time-tips-right").css("left",dx-ox + wdh + wx - 17);
                    $(".plan-time-tips-right").html(scale_to_time(dx-ox+wdh));
                    if($(".plan-time-tips").is(":hidden")) {
                        $(".plan-time-tips").show();
                    }
                }

                //拖拉已有区域
                if(drag_type==2) {
                    var hl = ofx - dx;
                    var hr = hl + $("#"+paln_hour).outerWidth();

                    if ( ml > hl) {
                        $("#"+paln_hour).css("left",ml);
                        $(".plan-time-tips-left").html(scale_to_time(ml));
                        return false;
                    }

                    if ( mr < hr) {
                        $("#"+paln_hour).css("left",(mr - $("#"+paln_hour).outerWidth())+"px");
                        $(".plan-time-tips-right").html(scale_to_time(mr));
                        return false;
                    }

                    $("#"+paln_hour).css("left",ofx-dx);

                    $(".plan-time-tips-left").css("left",hl+wx-17);
                    $(".plan-time-tips-right").css("left",hr+wx-17);

                    $(".plan-time-tips-left").html(scale_to_time(hl));
                    $(".plan-time-tips-right").html(scale_to_time(hr));

                    if($(".plan-time-tips").is(":hidden")) {
                        $(".plan-time-tips").show();
                    }

                }

                //放大缩小区域 左
                if(drag_type==3) {

                    var hl = ofx - ox;

                    if ( ml > hl) {
                        $("#"+paln_hour).css({"left":ml+"px","width":(ow-ml)+"px"});
                        $(".plan-time-tips-left").html(scale_to_time(ml));
                        return false;
                    }

                    if ( ow < hl) {
                        $("#"+paln_hour).css({"left":ow+"px","width":"2px"});
                        $(".plan-time-tips-left").html(scale_to_time(ow));
                        return false;
                    }

                    $("#"+paln_hour).css({"left":hl+"px","width":(ow-hl)+"px"});

                    $(".plan-time-tips-left").css("left",hl+wx-17);
                    $(".plan-time-tips-left").html(scale_to_time(hl));

                    if($(".plan-time-tips").is(":hidden")) {
                        $(".plan-time-tips").show();
                    }
                }

                //放大缩小区域 右
                if(drag_type==4) {

                    var hr = ofx - ox;
                    if ( mr <= hr) {
                        $("#"+paln_hour).width(mr-dx-2);
                        $(".plan-time-tips-right").html(scale_to_time(mr));
                        return false;
                    }
                    if ( dx > hr) {
                        $("#"+paln_hour).width(2);
                        $(".plan-time-tips-right").html(scale_to_time(dx));
                        return false;
                    }

                    $("#"+paln_hour).width(hr-dx);


                    $(".plan-time-tips-right").css("left",hr+wx-17);
                    $(".plan-time-tips-right").html(scale_to_time(hr));

                    if($(".plan-time-tips").is(":hidden")) {
                        $(".plan-time-tips").show();
                    }
                }

            });

            $(document).mouseup(function () {
                dragging = false;
                creading = false;
                tips_hover = false;
                paln_hour = false;

                $(".plan-time-tips").hide();

            });

            $(".plan-hours").on("mousedown",".plan-selected",function (event) {
                $(".plan-selected").removeClass("plan-selected-active");
                $(this).addClass("plan-selected-active");

                dx = event.pageX - $(this)[0].offsetLeft;
                dragging = true;
                paln_hour = $(this).prop("id");
                drag_type = 2;

                ml = 0;
                mr = plan_width;
                var hl = $(this)[0].offsetLeft;
                var hr = $(this)[0].offsetLeft+$(this).outerWidth();

                if( $(this).siblings(".plan-selected").length>0) {

                    $(this).siblings(".plan-selected").each(function () {
                        var otl = $(this)[0].offsetLeft;
                        var otr = otl+$(this).width();

                        if (otr<=hl) {
                            ml = otr>ml?otr:ml;
                        }

                        if (otl>=hr) {
                            mr = otl>mr?mr:otl;
                        }
                    });
                }

                var tips_top = $(this).parents(".plan-week").index()*58 + 20;
                $(".plan-time-tips-left").css({"top":tips_top+"px","left":hl+wx-17});
                $(".plan-time-tips-right").css({"top":tips_top+"px","left":hr+wx-17});

                $(".plan-time-tips-left").html(scale_to_time(hl));
                $(".plan-time-tips-right").html(scale_to_time(hr));

                event.stopPropagation();

            });

            $(".plan-hours").on("mousedown",".plan-selected-left",function (event) {

                if(!$(this).parents(".plan-selected").hasClass("plan-selected-active")) {
                    $(".plan-selected").removeClass("plan-selected-active");
                    $(this).parents(".plan-selected").addClass("plan-selected-active");
                }

                dragging = true;
                paln_hour = $(this).parents(".plan-selected").prop("id");
                dx = $("#"+paln_hour)[0].offsetLeft;
                drag_type = 3;

                ml = 0;
                var hl = dx;
                ow = $("#"+paln_hour)[0].offsetLeft+$("#"+paln_hour).outerWidth();
                if( $("#"+paln_hour).siblings(".plan-selected").length>0) {

                    $("#"+paln_hour).siblings(".plan-selected").each(function () {
                        var otl = $(this)[0].offsetLeft;
                        var otr = otl+$(this).outerWidth();

                        if (otr<=hl) {
                            ml = otr>ml?otr:ml;
                        }
                    });
                }

                var tips_top = $(this).parents(".plan-week").index()*58 + 20;
                $(".plan-time-tips-left").css({"top":tips_top+"px","left":hl+wx-17});
                $(".plan-time-tips-right").css({"top":tips_top+"px","left":ow+wx-17});

                $(".plan-time-tips-left").html(scale_to_time(dx));
                $(".plan-time-tips-right").html(scale_to_time(ow));

                event.stopPropagation();
            });

            $(".plan-hours").on("mousedown",".plan-selected-right",function (event) {
                if(!$(this).parents(".plan-selected").hasClass("plan-selected-active")) {
                    $(".plan-selected").removeClass("plan-selected-active");
                    $(this).parents(".plan-selected").addClass("plan-selected-active");
                }

                dragging = true;
                paln_hour = $(this).parents(".plan-selected").prop("id");
                dx = $("#"+paln_hour)[0].offsetLeft;
                drag_type = 4;

                mr = plan_width;
                ow = $("#"+paln_hour)[0].offsetLeft+$("#"+paln_hour).outerWidth();
                if( $("#"+paln_hour).siblings(".plan-selected").length>0) {

                    $("#"+paln_hour).siblings(".plan-selected").each(function () {
                        var otl = $(this)[0].offsetLeft;
                        var otr = otl+$(this).outerWidth();

                        if (otl>=ow) {
                            mr = otl>mr?mr:otl;
                        }
                    });
                }

                var tips_top = $(this).parents(".plan-week").index()*58 + 20;
                $(".plan-time-tips-left").css({"top":tips_top+"px","left":dx+wx-17});
                $(".plan-time-tips-right").css({"top":tips_top+"px","left":ow+wx-17});

                $(".plan-time-tips-left").html(scale_to_time(dx));
                $(".plan-time-tips-right").html(scale_to_time(ow));

                event.stopPropagation();
            });

            $(".plan-hours").on("mouseover",".plan-selected",function (event) {

                if(tips_hover) {
                    return false;
                }
                var tl = $(this)[0].offsetLeft+wx;
                var tr = $(this).outerWidth()+tl;

                var tips_top = $(this).parents(".plan-week").index()*58;
                $(".plan-time-tips-hover-top").html(scale_to_time(tl-wx)+" - "+scale_to_time(tr-wx));

                $(".plan-time-tips-hover").css({"top":tips_top+"px","left":(((tr-tl)/2+tl)-49)+"px"});
                $(".plan-time-tips-hover").show();
            });

            $(".plan-hours").on("mouseleave",".plan-selected",function (event) {
                $(".plan-time-tips-hover").hide();
            });

            $(".plan-hours").on("click",".plan-selected",function (event) {

                if(time_from) {
                    time_from = false;
                    return false;
                }

                var tl = $(this)[0].offsetLeft+wx;

                var tr = $(this).outerWidth()+tl;

                var tips_top = $(this).parents(".plan-week").index()*58 - 60;

                var time_start = scale_to_time(tl-wx).split(":");
                var time_over = scale_to_time(tr-wx).split(":");

                $("input[name=plan_time_start_h]").val(time_start[0]);
                $("input[name=plan_time_start_m]").val(time_start[1]);
                $("input[name=plan_time_over_h]").val(time_over[0]);
                $("input[name=plan_time_over_m]").val(time_over[1]);

                $(".plan-time-form").css({"top":tips_top+"px","left":(((tr-tl)/2+tl)-89)+"px"});
                $(".plan-time-form").show();

            });


            $(".time-form-close").click(function () {
                $(".plan-time-form").hide();
            });

            $(".delete-scale").click(function () {
                $(".plan-selected-active").remove();
                $(".plan-time-form").hide();
            });

            $(".save-scale").click(function () {

                var shh = parseInt($("input[name=plan_time_start_h]").val());
                var smm = parseInt($("input[name=plan_time_start_m]").val());
                var ohh = parseInt($("input[name=plan_time_over_h]").val());
                var omm = parseInt($("input[name=plan_time_over_m]").val());

                var s_l = shh*60*(720/24/60)+smm*(720/24/60);
                var s_r = ohh*60*(720/24/60)+omm*(720/24/60);
                if (s_l>s_r) {
                   dialog.tips("error","结束时间不得小于开始时间");
                    return false;
                }
                $(".plan-selected-active").css({"left":s_l,"width":s_r-s_l});
                $(".plan-time-form").hide();
            });

            $('.form-control-plan-time').on('input', function () {

                var value = parseInt($(this).val());
                var type  = $(this).data('type');
                if(isNaN(value) || value<0) {
                    $(this).val('00').select();
                    return ;
                }

                if(type=='hour' && value>24) {
                    $(this).val('00').select();
                    return ;
                }else if (value==24) {
                    if ($(this).prop("name")=='plan_time_start_h') {
                        $(this).val('00').select();
                        return ;
                    } else {
                        $("input[name=plan_time_over_m]").val('00').select();
                    }
                    return ;
                }

                if(type=='minute'){
                    if ($(this).prop("name")=='plan_time_start_m') {
                        if ($("input[name=plan_time_start_h]").val() == 24 || value>59) {
                           $(this).val('00').select();
                        }
                    } else {
                        if ($("input[name=plan_time_over_h]").val() == 24 || value>59) {
                            $(this).val('00').select();
                        }
                    }
                    return ;
                }

            });

            $(".delete-all-hours").click(function () {
                $(".plan-selected").remove();
            });

            $(".save-all-hours").click(function () {
                $(".plan-selected").remove();
                $(".plan-hours").each(function (k,v) {
                    var paln_hour = "plan_"+$(this).parents(".plan-week").index()+"_0";
                    $(this).append("<div class='plan-selected' id = '"+paln_hour+"' style='left:0; width:720px '><div class='plan-selected-control'><div class='plan-selected-left'></div><div class='plan-selected-right'></div></div></div>");
                })
            });
            
            $(".ONVIF-get-token").click(function () {
                var onvifaddr = $("input[name=onvifaddr]").val();
                var onvifuser = $("input[name=onvifuser]").val();
                var onvifpwd = $("input[name=onvifpwd]").val();
                camerafram = dialog.ajax("__URL__/get_scan","选择数据",{'url': onvifaddr, 'user': onvifuser, 'pwd': onvifpwd});

            });
            
            $(".gb28181-get-url").click(function () {
                camerafram = dialog.frame("__URL__/get_allchsbytree","选择数据",true);
            });




            $("#FormElement").submit(function () {

                if (!$("input[name=name]").val()) {
                    dialog.tips("error","请输入通道名称");
                    return false;
                }
                if (!$("input[name=id]").val()) {
                    dialog.tips("error","请输入频道ID");
                    return false;
                }

                if(!/^\w+$/.test($("input[name=id]").val())){
                    dialog.tips("error","频道ID只能输入数字，字母或下划线");
                    return false;
                }

                $(".extends-field:hidden").remove();

                $(".wday input").each(function () {
                    $(this).val('');
                });

                if ($(".plan-selected").length>0) {
                    $(".plan-selected").each(function (k, v) {
                        var tl = $(this)[0].offsetLeft;
                        var tr = $(this).outerWidth()+tl;
                        var time_start = scale_to_time(tl);
                        var time_over = scale_to_time(tr);
                        var week = $(this).parents(".plan-week").index()+1;
                        if (week==7) {
                           week = 0;
                        }
                        if ($.trim($("input[name='wday"+week+"']").val())) {
                            $("input[name='wday"+week+"']").val($("input[name='wday"+week+"']").val()+";"+time_start+"-"+time_over);
                        } else {
                            $("input[name='wday"+week+"']").val(time_start+"-"+time_over);
                        }
                    });
                }

                dialog.loading.show();
                $.when($.post("__URL__/save", $("#FormElement").serialize()))
                    .always(function () {
                        dialog.loading.hide();
                    })
                    .done(function (response) {

                        dialog.tips(response.status,response.info,function () {
                            if (response.status=='success') {
                                <?php if(!$info):?>
                                    window.parent.location.reload();
                                <?php else :?>
                                    window.location.reload();
                                <?php endif;?>
                            }
                        });

                    });

                return false;

            });

            <?php if($info):?>

                var wdays = ["{$info.wday1}","{$info.wday2}","{$info.wday3}","{$info.wday4}","{$info.wday5}","{$info.wday6}","{$info.wday0}"];
                $.each(wdays,function (k, v) {
                    var wd = v.split(";");
                    var x;
                    for (x in wd) {
                        var plan_hour = "plan_"+k+"_"+$(".plan-week").eq(k).find(".plan-selected").length;
                        var timer = wd[x].split("-");
                        var hl = time_to_scale(timer[0]);
                        var hr = time_to_scale(timer[1])-hl;
                        $(".plan-week").eq(k).find(".plan-hours").append("<div class='plan-selected' id = '"+plan_hour+"' style='left:"+hl+"px; width:"+hr+"px '><div class='plan-selected-control'><div class='plan-selected-left'></div><div class='plan-selected-right'></div></div></div>");
                    }
                });


            <?php endif;?>

        });

        function scale_to_time(value) {

            var mv = value / (720/24/60);
            var hh = parseInt(mv/60);
            var mm = mv%60;
            return (Array(2).join('0') + hh).slice(-2)+":"+(Array(2).join('0') + mm).slice(-2);
        }

        function time_to_scale(timer) {
            if (!timer) {
                return false;
            }
            var time = timer.split(":");
            return time[0]*(720/24)+time[1]*(720/24/60);
        }

        function getallchs(id,name) {

            $("input[name=gb28181inputid]").val(id);

            if(!$("input[name=name]").val()) {
                $("input[name=name]").val(name);
            }
            if(!$("input[name=id]").val()) {
                $("input[name=id]").val(id);
            }
            camerafram.close().remove();
        }

    </script>
</head>
<body style="padding: 30px " >
        <form id="FormElement">
            <ul class="nav nav-tabs" style="margin-bottom: 40px">
                <li role="presentation" class="active"><a href="javascript:;">基本参数</a></li>
                <if condition="spower('GB28181Power')">
					<?php if($device_name!='PLATFORM'){?>
						<li role="presentation"><a href="javascript:;">GB28181输出</a></li>
					<?php }?>
                </if>
                <if condition="spower('UDPPower')">
                    <li role="presentation"><a href="javascript:;">UDP组播输出</a></li>
                </if>
                <if condition="spower('RecordPower')">
                    <li role="presentation" class="RecordContainer"><a href="javascript:;">录像配置</a></li>
                </if>
                <if condition="spower('TrancodePower')">
                    <li role="presentation" class="TrancodeContainer"><a href="javascript:;">视频转码</a></li>
                </if>
            </ul>
            <div class="field-lists">
                <div class="input-group form-inline">
                    <span class="input-group-addon">分组</span>
                    <select name="groupid" id="groupid" class="form-control">
                        <option value="">未分组</option>
                        <volist name="groups" id="vo">
                            <option value="{$vo.nId}" {:$gid==$vo['nId']?"selected":""}>
                                {$vo.sName}
                            </option>
                        </volist>
                    </select>
                </div>
                <div class="input-group form-inline">
                    <span class="input-group-addon">类型</span>
                    <select name="type" id="type" class="form-control">
                        <volist name="liveStreamTypes" id="vo">
                            <option value="{$vo}" {:$info['type']==$vo?"selected":""}>
                                <switch name="vo">
                                    <case value="Pull">拉流(RTSP/RTMP/HTTP/HLS)</case>
                                    <case value="UDPRecv">接收UDP组播</case>
                                    <case value="VirtualLive">虚拟直播</case>
                                    <case value="VirtualLiveMulti">多文件虚拟直播</case>
                                    <case value="VirtualLiveMultiEPG">多文件虚拟EPG直播</case>
                                    <case value="ExternalPush">外部推送</case>
                                    <default />{$vo}
                                </switch>
                            </option>
                        </volist>
                    </select>
                </div>
                <div class="extends-field Pull-field" style="{:in_array($info['type'],array('Pull'))?'':'display: none'} ">
                    <div class="input-group form-inline">
                        <span class="input-group-addon">视频输入地址</span>
                        <input type="text" name="url" class="form-control" value="{$info.url}">
                    </div>
                </div>
                <div class="extends-field UDPRecv-field" style="{:in_array($info['type'],array('UDPRecv'))?'':'display: none'} ">
                    <div class="input-group form-inline">
                        <span class="input-group-addon">接收地址</span>
                        <input type="text" name="udprecvaddr" class="form-control" value="{$info.udprecvaddr}">
                    </div>
                    <div class="input-group form-inline">
                        <span class="input-group-addon">选择接收网卡</span>
                        <select name="udprecveth" id="udprecveth" class="form-control">
                            <volist name="udprecveths" id="vo">
                                <option value="{$vo.addr}" {:$info['udprecveth']==$vo['addr']?"selected":""}>{$vo.name}({$vo.addr})</option>
                            </volist>
                        </select>
                    </div>
                </div>
                <div class="extends-field GB28181-field" style="{:$info['type']=='GB28181'?'':'display: none'} ">
                    <div class="input-group form-inline">
                        <span class="input-group-addon">GB28181国标输入ID</span>
                        <input type="text" name="gb28181inputid" class="form-control" value="{$info.gb28181inputid}">
                        <span class="input-group-addon btn btn-info gb28181-get-url" style="width: 80px;">自动填写</span>
                    </div>
                </div>
                <div class="extends-field ONVIF-field"  style="{:$info['type']=='ONVIF'?'':'display: none'}">
                    <div class="input-group form-inline">
                        <span class="input-group-addon">ONVIF输入地址</span>
                        <input type="text" name="onvifaddr" class="form-control" value="{$info.onvifaddr}">
                    </div>
                    <div class="input-group form-inline">
                        <span class="input-group-addon">ONVIF 用户</span>
                        <input type="text" name="onvifuser" class="form-control" value="{$info.onvifuser}">
                    </div>
                    <div class="input-group form-inline">
                        <span class="input-group-addon">ONVIF 密码</span>
                        <input type="text" name="onvifpwd" class="form-control" value="{$info.onvifpwd}">
                    </div>
                    <div class="input-group form-inline">
                        <span class="input-group-addon">视频 Token</span>
                        <input type="text" name="onvifvideotoken" class="form-control onvif-url" value="{$info.onvifvideotoken}">
                        <span class="input-group-addon btn btn-info ONVIF-get-token" style="width: 80px;">自动填写</span>
                    </div>
                    <div class="input-group form-inline">
                        <span class="input-group-addon">PTZ Token</span>
                        <input type="text" name="onvifptztoken" class="form-control" value="{$info.onvifptztoken}">
                    </div>
                </div>
                <div class="input-group form-inline">
                    <span class="input-group-addon">连接类型</span>
                    <select name="starttype" id="starttype" class="form-control">
                        <option value="0" {:$info['starttype']==0?'selected':''}>自动连接</option>
                        <option value="1" {:$info['starttype']==1?'selected':''}>手动连接</option>
                        <option value="2" {:$info['starttype']==2?'selected':''}>按需连接</option>
                    </select>
                </div>
                <div class="input-group form-inline">
                    <span class="input-group-addon">通道名称</span>
                    <input type="text" name="name" class="form-control" value="{$info.name}" >
                </div>
                <div class="input-group form-inline pdid" style='{:$info['type']=='BG28181'?'':'display: none'}'>
                    <span class="input-group-addon"><i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="top" title="频道ID只能输入数字，字母或下划线"></i> 频道ID</span>
                    <input type="text" name="id" class="form-control" value="{$info.id}" <?php if(!spower("update_id")):?>disabled<?php endif;?> >
                </div>
                <div class="input-group form-inline outurl-container">
                    <span class="input-group-addon">转发到第三方</span>
                    <input type="text" name="outurl" class="form-control" value="{$info.outurl}" >
                </div>
                <div class="input-group form-inline">
                    <span class="input-group-addon">发布到视频广场</span>
                    <select name="publish" id="publish" class="form-control">
                        <option value="1" {:$info['publish']==1?'selected':''}>ON</option>
                        <option value="0" <?php if($info && $info['publish']!=1){echo 'selected';}?>>OFF</option>
                    </select>
                </div>
            </div>
            
            <?php if($device_name!='PLATFORM'){?>   
            <if condition="spower('GB28181Power')">
                <div class="field-lists" style="display: none;">
                    <div class="input-group form-inline">
                        <span class="input-group-addon">开启</span>
                        <select name="gb28181output" id="gb28181output" class="form-control">
                            <option value="0" {:$info['gb28181output']!=1?"selected":""}>OFF</option>
                            <option value="1" {:$info['gb28181output']==1?"selected":""}>ON</option>
                        </select>
                    </div>
                    <div class="input-group form-inline">
                        <span class="input-group-addon">GB28181国标ID</span>
                        <input type="text" name="gb28181id" class="form-control" value="{$info.gb28181id}">
                    </div>
                </div>
            </if>
            <?php }?>
            <if condition="spower('UDPPower')">
                <div class="field-lists" style="display: none;">
                <div class="input-group form-inline">
                    <span class="input-group-addon">开启</span>
                    <select name="udptsoutput" id="udptsoutput" class="form-control">
                        <option value="0" {:$info['udptsoutput']!=1?"selected":""}>OFF</option>
                        <option value="1" {:$info['udptsoutput']==1?"selected":""}>ON</option>
                    </select>
                </div>
                <div class="input-group form-inline">
                    <span class="input-group-addon">输出地址</span>
                    <input type="text" name="udptsoutaddr" class="form-control" value="{$info.udptsoutaddr}">
                </div>
                <div class="input-group form-inline">
                    <span class="input-group-addon">选择输出网卡</span>
                    <select name="udptsouteth" id="udptsouteth" class="form-control">
                        <if condition="eths">
                            <volist name="eths" id="vo">
                                <option value="{$vo.addr}" {:$info['udptsouteth']==$vo['addr']?"selected":""}>{$vo.name}({$vo.addr})</option>
                            </volist>
                        <else />
                            <option value="*">ALL</option>
                        </if>
                    </select>
                </div>
                <div class="input-group form-inline">
                    <span class="input-group-addon">频道名</span>
                    <input type="text" name="udptsoutservicename" class="form-control" value="{$info.udptsoutservicename}">
                </div>
                <div class="input-group form-inline">
                    <span class="input-group-addon">频道PID</span>
                    <input type="text" name="udptsoutserviceid" class="form-control" value="{$info.udptsoutserviceid}">
                </div>
                <div class="input-group form-inline">
                    <span class="input-group-addon">输出码率</span>
                    <input type="text" name="udptsoutmuxrate" class="form-control" value="{$info.udptsoutmuxrate}">
                </div>
            </div>
            </if>
             
            <if condition="spower('RecordPower')">
                <div class="field-lists" style="display: none">
                <div class="plan-container" onselectstart="return false;">
                    <div class="clearfix">
                        <div class="pull-left">
                            <div class="form-group">
                                <button style="" type="button" class="btn btn-primary save-all-hours" >全部录制</button>
                                <button style="" type="button" class="btn btn-danger delete-all-hours">禁止录制</button>
                            </div>
                            <div class="plan-write">
                                <?php for($i=0;$i<7;$i++):?>
                                    <div class="plan-week clearfix">
                                        <div class="pull-left plan-day">
                                            星期<switch name="i">
                                                <case value="0">一</case>
                                                <case value="1">二</case>
                                                <case value="2">三</case>
                                                <case value="3">四</case>
                                                <case value="4">五</case>
                                                <case value="5">六</case>
                                                <case value="6">日</case>
                                            </switch>
                                        </div>
                                        <div class="pull-left plan-hours-scale">
                                            <div class="plan-hours-scale-value clearfix">
                                                <?php for($m=0;$m<=12;$m++):?>
                                                    <i class="plan-hours-value plan-hours-value-{$m}">{$m*2}</i>
                                                <?php endfor;?>
                                            </div>
                                            <div class="plan-hours-scale-line clearfix">
                                                <?php for($m=0;$m<=24;$m++):?>
                                                    <i class="plan-one-hour plan-one-hour-{$m}"></i>
                                                <?php endfor;?>
                                            </div>
                                        </div>
                                        <div class="pull-left plan-hours"></div>
                                    </div>
                                <?php endfor;?>
                                <div class="plan-time-tips">
                                    <i class="plan-time-tips-left">00:00</i>
                                    <i class="plan-time-tips-right">24:00</i>
                                </div>
                                <div class="plan-time-tips-hover">
                                    <div class="plan-time-tips-hover-top">
                                        00:00 - 24:00
                                    </div>
                                    <div class="plan-time-tips-hover-bottom"></div>
                                </div>
                                <div class="plan-time-form">
                                    <div class="plan-time-form-top">
                                        <div class="form-group-sm form-inline">
                                            <input type="text" class="form-control form-control-plan-time" data-type="hour" maxlength="2" name="plan_time_start_h"> :
                                            <input type="text" class="form-control form-control-plan-time" data-type="minute" maxlength="2" name="plan_time_start_m">
                                            -
                                            <input type="text" class="form-control form-control-plan-time" data-type="hour" maxlength="2" name="plan_time_over_h"> :
                                            <input type="text" class="form-control form-control-plan-time" data-type="minute" maxlength="2" name="plan_time_over_m">
                                        </div>
                                        <div class="form-group-sm" style="margin-top: 10px">
                                            <button type="button" class="btn btn-danger btn-xs delete-scale">删除</button>
                                            <button type="button" class="btn btn-success btn-xs save-scale" style="margin:0 10px">保存</button>
                                            <button type="button" class="btn btn-default btn-xs time-form-close">关闭</button>
                                        </div>
                                    </div>
                                    <div class="plan-time-form-bottom"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="wday">
                    <input type="hidden" name="wday0" value="{$info.wday0}">
                    <input type="hidden" name="wday1" value="{$info.wday1}">
                    <input type="hidden" name="wday2" value="{$info.wday2}">
                    <input type="hidden" name="wday3" value="{$info.wday3}">
                    <input type="hidden" name="wday4" value="{$info.wday4}">
                    <input type="hidden" name="wday5" value="{$info.wday5}">
                    <input type="hidden" name="wday6" value="{$info.wday6}">
                </div>
            </div>
            </if>
            <if condition="spower('TrancodePower')">
                <div class="field-lists" style="display: none">
                <div class="input-group form-inline">
                    <span class="input-group-addon">视频转码</span>
                    <select name="videotranscode" id="videotranscode" class="form-control">
                        <option value="0" {:$info['videotranscode']!=1?"selected":""}>OFF</option>
                        <option value="1" {:$info['videotranscode']==1?"selected":""}>ON</option>
                    </select>
                </div>
                <div class="input-group form-inline">
                    <span class="input-group-addon">分辨率</span>
                    <select name="videosize" id="videosize" class="form-control">
                        <volist name="camera_dpi" id="vo">
                            <option value="{$vo.name}" {:$info['videotranscode']==$vo['name']?"selected":""} >{$vo.name}</option>
                        </volist>
                    </select>
                </div>
                <div class="input-group form-inline">
                    <span class="input-group-addon">帧率</span>
                    <select name="videofps" id="videofps" class="form-control">
                        <option value="">默认</option>
                        <volist name="camera_fps" id="vo">
                            <option value="{$vo.name}" {:$info['videofps']==$vo['name']?"selected":""}>{$vo.name}</option>
                        </volist>
                    </select>
                </div>
                <div class="input-group form-inline">
                    <span class="input-group-addon">关键帧间隔</span>
                    <input type="text" name="gopsize" class="form-control" value="{$info.gopsize}">
                </div>
                <div class="input-group form-inline">
                    <span class="input-group-addon">码率</span>
                    <input type="text" name="videobitrate" class="form-control" value="{$info.videobitrate}">
                </div>

                <div class="input-group form-inline">
                    <span class="input-group-addon">画面比率</span>
                    <select name="aspect" id="aspect" class="form-control">
                        <option value="">默认</option>
                        <volist name="camera_aspect" id="vo">
                            <option value="{$vo.name}"  {:$info['aspect']==$vo['name']?"selected":""}>{$vo.name}</option>
                        </volist>
                    </select>
                </div>
                <div class="input-group form-inline">
                    <span class="input-group-addon">反交错</span>
                    <select name="deinterlace" id="deinterlace" class="form-control">
                        <option value="0" {:$info['deinterlace']!=1?"selected":""}>OFF</option>
                        <option value="1" {:$info['deinterlace']==1?"selected":""}>ON</option>
                    </select>
                </div>
                <div class="input-group form-inline">
                    <span class="input-group-addon">编码质量</span>
                    <select name="vprofile" id="vprofile" class="form-control">
                        <option value="">默认</option>
                        <volist name="camera_vprofile" id="vo">
                            <option value="{$vo.name}" {:$info['vprofile']==$vo['name']?"selected":""}>
                            <switch name="vo['name']">
                                <case value="baseline">低</case>
                                <case value="main">中</case>
                                <case value="high">高</case>
                                <default />{$vo.name}
                            </switch>
                            </option>
                        </volist>
                    </select>
                </div>
                <div class="input-group form-inline">
                    <span class="input-group-addon">编码方式</span>
                    <select name="videobitratetype" id="videobitratetype" class="form-control">
                        <option value="">默认</option>
                        <volist name="camera_videobitratetype" id="vo">
                            <option value="{$vo.name}" {:$info['videobitratetype']==$vo['name']?"selected":""}>{$vo.name}</option>
                        </volist>
                    </select>
                </div>
                <div class="input-group form-inline">
                    <span class="input-group-addon">音频转码</span>
                    <select name="audiotranscode" id="audiotranscode" class="form-control">
                        <option value="0" {:$info['audiotranscode']!=1?"selected":""}>OFF</option>
                        <option value="1" {:$info['audiotranscode']==1?"selected":""}>ON</option>
                    </select>
                </div>
                <div class="input-group form-inline">
                    <span class="input-group-addon">音频采样率</span>
                    <select name="audiosamplerate" id="audiosamplerate" class="form-control">
                        <option value="">默认</option>
                        <volist name="camera_audiosamplerate" id="vo">
                            <option value="{$vo.name}" {:$info['audiosamplerate']==$vo['name']?"selected":""}>{$vo.name}</option>
                        </volist>
                    </select>
                </div>
                <div class="input-group form-inline">
                    <span class="input-group-addon">音频通道</span>
                    <select name="audiochannels" id="audiochannels" class="form-control">
                        <option value="">默认</option>
                        <volist name="camera_audiochannels" id="vo">
                            <option value="{$vo.name}" {:$info['audiochannels']==$vo['name']?"selected":""}>{$vo.name}</option>
                        </volist>
                    </select>
                </div>
                <div class="input-group form-inline">
                    <span class="input-group-addon">参数</span>
                    <select name="haveargs" id="haveargs" class="form-control">
                        <option value="0" {:$info['haveargs']!=1?"selected":""}>OFF</option>
                        <option value="1" {:$info['haveargs']==1?"selected":""}>ON</option>
                    </select>
                </div>
                <div class="input-group form-inline">
                    <span class="input-group-addon">参数值</span>
                    <input type="text" name="args" class="form-control" value="{$info.args}">
                </div>
            </div>
            </if>
            <div style="width: 420px">
                <input type="hidden" name="sysid" value="{$info.sysid}">
                <button class="btn btn-primary btn-block">{:$info?"更新":"添加"}</button>
            </div>
        </form>

</body>
</html>