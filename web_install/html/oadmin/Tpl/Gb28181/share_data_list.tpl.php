<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>共享配置</title>
    {:W('jQuery')}
    {:W('Dialog')}
    {:W('Bootstrap')}
    <link rel="stylesheet" href="__STATIC__/css/ly.css">
    <link rel="stylesheet" type="text/css" href="__STATIC__/css/untitled.css">
    <link rel="stylesheet" href="__CSS__/css/css/demo.css" type="text/css">
    <link rel="stylesheet" href="__STATIC__/css/css/zTreeStyle/zTreeStyle.css" type="text/css">
    <script type="text/javascript" src="__STATIC__/js/js/jquery.ztree.core.js"></script>
    <script type="text/javascript" src="__STATIC__/js/js/jquery.ztree.excheck.js"></script>
    <script type="text/javascript" src="__STATIC__/js/js/jquery.ztree.exedit.js"></script>
    <script>
        var ext_table = "<?php echo $ext_table;?>";
        var sParentChid='';
        var f='1';
        var sChid = '';
        var sParentChid1='';
        var f1='1';
        var sChid1 = '';
        var setting = {
            check: {
                enable: true,
                chkboxType: { "Y": "", "N": "" },
                chkDisabledInherit: true
            },
            view: {
                selectedMulti: false
            },
            data: {
                simpleData: {
                    enable: true
                }
            },
            async: {
                enable: true,
                url:"/gb28181/ajax_share_data",
                autoParam:["id", "name=n", "level=lv"],
                otherParam:{"sParentChid":function(){ return sParentChid;},"f":function(){ return f;},"sChid":function(){ return sChid;},"ext_table":function(){ return ext_table;}},
                dataFilter: filter
            },
            callback: {
                beforeExpand: zTreeBeforeExpand,
                beforeClick: beforeClick,
                beforeAsync: beforeAsync,
                onAsyncError: onAsyncError,
                onAsyncSuccess: onAsyncSuccess,
                onClick: OnClick,
                beforeCheck: beforeCheck,
                onCheck: zTreeOnCheck
            }
        };
        var setting1 = {
            view: {
                //expandSpeed:"",
                //addHoverDom: addHoverDom,
                //removeHoverDom: removeHoverDom,
                selectedMulti: false
            },
            data: {
                simpleData: {
                    enable: true
                }
            },
            //edit: {
          //      enable: true
          //  },
            async: {
                enable: true,
                url:"/gb28181/ajax_pshare_data",
                autoParam:["id", "name=n", "level=lv"],
                otherParam:{"sParentChid":function(){ return sParentChid1;},"f":function(){ return f1;},"sChid":function(){ return sChid1;},"ext_table":function(){ return ext_table;}},
                dataFilter: filter
            },
            callback: {
                beforeExpand: zTreeBeforeExpand1,
                beforeClick: beforeClick1,
                beforeAsync: beforeAsync,
                onAsyncError: onAsyncError,
                onAsyncSuccess: onAsyncSuccess,
                onClick: OnClick1,
                beforeCheck: beforeCheck,
                onCheck: zTreeOnCheck,
                //beforeRemove: beforeRemove,
                //beforeRename: beforeRename
            }
        };
        function beforeRemove(treeId, treeNode) {
            var zTree = $.fn.zTree.getZTreeObj("treeDemo1");
            zTree.selectNode(treeNode);
            return confirm("确认删除 节点 -- " + treeNode.name + " 吗？");
        }       
        function beforeRename(treeId, treeNode, newName) {
            if (newName.length == 0) {
                setTimeout(function() {
                    var zTree = $.fn.zTree.getZTreeObj("treeDemo1");
                    zTree.cancelEditName();
                    alert("组织名称不能为空！");
                }, 0);
                return false;
            }
            return true;
        }
        var newCount = 1;
        function addHoverDom(treeId, treeNode) {
            f1='0';
            sChid1 = treeNode.id;
            sParentChid1 = treeNode.sParentChid;
            //console.log(treeNode);
            var sObj = $("#" + treeNode.tId + "_span");
            if (treeNode.editNameFlag || $("#addBtn_"+treeNode.tId).length>0) return;
            var addStr = "<span class='button add' id='addBtn_" + treeNode.tId
                + "' title='add node' onfocus='this.blur();'></span>";
            sObj.after(addStr);
            var btn = $("#addBtn_"+treeNode.tId);
            if (btn) btn.bind("click", function(){
                var zTree = $.fn.zTree.getZTreeObj("treeDemo1");
                //alert();
                show_Win('div_Test', 'title', event);
                //zTree.addNodes(treeNode, {id:(100 + newCount), pId:treeNode.id, name:"new node" + (newCount++)});
                return false;
            });
        };
        function removeHoverDom(treeId, treeNode) {
            $("#addBtn_"+treeNode.tId).unbind().remove();
        };
        function del_carmera(id,status){
            $.post("/gb28181/ajax_pshare_del",{id:id,status:status},function(result){
                var treeObj = $.fn.zTree.getZTreeObj("treeDemo");
                var nodes = treeObj.transformToArray(treeObj.getNodes());
                for(var i=0;i<nodes.length;i++){
                    if(nodes[i].id == id){
                        treeObj.checkNode(nodes[i], false, false,true);
                    }
                }
            });
        }
        function zTreeBeforeExpand(treeId, treeNode){
            sChid = treeNode.id;
            console.log(sChid);
            console.log(sParentChid);
            console.log(ext_table);
            //alert(sChid);
        }
        function zTreeBeforeExpand1(treeId, treeNode){
            sChid1 = treeNode.id;
            sParentChid1 = treeNode.sParentChid;
            f1='0';
            //alert(sChid);
        }
        function beforeCheck(treeId, treeNode){
            //alert(123);
            return true;
        }
        function zTreeOnCheck(event, treeId, treeNode){

            var halfCheck = treeNode.getCheckStatus();
            //console.log(halfCheck.checked);
            var treeObj = $.fn.zTree.getZTreeObj("treeDemo");
            var str ='';
            var nodes = getAllChildrenNodes(treeNode,str);
            nodes = nodes.substring(1);
            nodes = nodes.split(',');
            if(treeNode.isParent){
                var status = 1;
            }else{
                var status = 0;
            }
            var id = treeNode.id;
            var sid = treeNode.sParentChid;
            if(halfCheck.checked){
                //console.log(uid);
                $.post("/gb28181/ajax_pshare_add",{sChid:id,sParentChid:sid,status:status,ext_table:ext_table},function(result){
                    f1='1';
                    //sParentChid1 = '';
                    sChid1 = '';
                    $.fn.zTree.init($("#treeDemo1"), setting1);
                });
                var f = false;
            }else{
                $.post("/gb28181/ajax_pshare_del_check",{sChid:id,sParentChid:sid,status:status,ext_table:ext_table},function(result){
                    f1='1';
                    //sParentChid1 = '';
                    sChid1 = '';
                    $.fn.zTree.init($("#treeDemo1"), setting1);
                });
                var f = true;
            }
            
            for (var i=0, l=nodes.length; i < l; i++) {
                    treeObj.checkNode(treeObj.getNodesByParam("id",nodes[i], treeNode)[0],false,false);
                    treeObj.setChkDisabled(treeObj.getNodesByParam("id",nodes[i], treeNode)[0],halfCheck.checked);
                }
                
        }

        function getAllChildrenNodes(treeNode,result){
              if (treeNode.isParent) {
                var childrenNodes = treeNode.children;
                if (childrenNodes) {
                    for (var i = 0; i < childrenNodes.length; i++) {
                        //console.log(childrenNodes);
                        result += ',' + childrenNodes[i].id;
                        result = getAllChildrenNodes(childrenNodes[i], result);
                    }
                }
            }
            return result;
        }

        function filter(treeId, parentNode, childNodes) {
            if (!childNodes) return null;
            //console.log(childNodes);
            for (var i=0, l=childNodes.length; i<l; i++) {
                childNodes[i].name = childNodes[i].name.replace(/\.n/g, '.');
            }
            //console.log(childNodes);
            return childNodes;
        }
        function OnClick(event, treeId,treeNode){
            
            
        }
        function OnClick1(event, treeId,treeNode){
            sChid1 = treeNode.id;
            sParentChid1 = treeNode.sParentChid;
            f1='0';
            var zTree1 = $.fn.zTree.getZTreeObj("treeDemo1"),
            nodes1 = zTree1.getSelectedNodes();
            if (nodes1.length == 0) {    
                
            }else{
                if(!treeNode.children){
                    zTree1.reAsyncChildNodes(nodes[0], "add", true);
                }
            }
            
        }
    
        function beforeClick(treeId, treeNode) {
                sChid = treeNode.id;
                //console.log(treeNode.id);
                //console.log(idaaa);
                return true;
        }
        function beforeClick1(treeId, treeNode) {
                sChid1 = treeNode.id;
                sParentChid1 = treeNode.sParentChid;
                f1='0';
                //console.log(treeNode.id);
                //console.log(idaaa);
                return true;
        }
        //var log, className = "dark";
        function beforeAsync(treeId, treeNode) {
            return true;
        }
        function onAsyncError(event, treeId, treeNode, XMLHttpRequest, textStatus, errorThrown) {
            
        }
        function onAsyncSuccess(event, treeId, treeNode, msg) {
            f=0;
            // if(msg==''||!msg||msg=='[]'){
            //     alert("此组织下没有摄像头");
            // }           
        }
         
        $(document).ready(function(){
            $("#groups").change(function(){
                sParentChid1 = this.value;
                f1=1;
                sChid1='';
                $.fn.zTree.init($("#treeDemo1"), setting1);
                sParentChid = this.value;
                f=1;
                sChid='';
                $.fn.zTree.init($("#treeDemo"), setting);
            });
        });
        function re_all_data(){
        	sParentChid1 = $("#groups").val();
        	if(sParentChid1==''){
        		alert("请选择下级域");
        		return;
        	}
            f1=1;
            sChid1='';
            $.fn.zTree.init($("#treeDemo1"), setting1);
            sParentChid = $("#groups").val();
            f=1;
            sChid='';
            $.fn.zTree.init($("#treeDemo"), setting);
        }
        function re_sel_data(){
        	sParentChid1 = $("#groups").val();
        	if(sParentChid1==''){
        		alert("请选择下级域");
        		return;
        	}
            f1=1;
            sChid1='';
            $.fn.zTree.init($("#treeDemo1"), setting1);
        }
        function show_Win(div_Win, tr_Title, event) {
            var s_Width = document.documentElement.scrollWidth; //滚动 宽度
            var s_Height = document.documentElement.scrollHeight; //滚动 高度
            var js_Title = $(document.getElementById(tr_Title)); //标题
            js_Title.css("cursor", "move");
            //创建遮罩层
            $("<div id=\"div_Bg\"></div>").css({ "position": "absolute", "left": "0px", "right": "0px", "width": s_Width + "px", "height": s_Height + "px", "background-color": "#ffffff", "opacity": "0.6" }).prependTo("body");
            //获取弹出层
            var msgObj = $("#" + div_Win);
            msgObj.css('display', 'block'); //必须先弹出此行，否则msgObj[0].offsetHeight为0，因为"display":"none"时，offsetHeight无法取到数据；如果弹出框为table，则为'',如果为div，则为block，否则textbox长度无法充满td
            //y轴位置
            var js_Top = -parseInt(msgObj.height()) / 2 + "px";
            //x轴位置
            var js_Left = -parseInt(msgObj.width()) / 2 + "px";
            msgObj.css({ "margin-left": js_Left, "margin-top": js_Top });
            //使弹出层可移动
            msgObj.draggable({ handle: js_Title, scroll: false });
        }

        function re_div_Test(div_Win){
        	var msgObj = $("#" + div_Win);
        	msgObj.css('display', 'none');
        }
    </script>
    <style>
        #div_Test {
            position: fixed;
            position: absolute;
            top: 50%;
            left: 50%;
            border: 2px solid #C0C0C0;/*弹出框边框样式*/
            background-color: #FFFFFF;/*弹出框背景色*/
            display:none;
            z-index:100;
            width:400px;
            height:300px;
            text-align:center;
        }
    </style>
<body>
<div id="div_Test">
    <div id="title" style="padding-top:30px"></div>
    <div id="pId" style="padding-top:15px">组织父ID：<input type="text"/></div>
    <div id="gbId" style="padding-top:15px">组织国标ID：<input type="text"/></div>
    <div id="name" style="padding-top:15px">组织名称：<input type="text"/></div>
    <div style="padding-top:15px"><input type="button" value="确定" style="margin-right: 20px"/><input type="button" value="取消" onclick="re_div_Test('div_Test')"/></div>
</div>
<div id="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            共享配置
        </div>
        <div class="panel-body">
            <div class="lists-container" style="margin-top: 15px">
                <div class="contnt">
                    <p class="current">当前上级域：<span>{$id}</span></p>
                    <div class="cu_in">
                        <p class="current">下级域：</p>
                         <select class="dropdown" id="groups">
                              <option value=''>请选择</option>
                              <volist name="groups['devs']" id="vo">
                                <option value="{$vo.chid}" {:$sParentChid == $vo['chid']?"selected":""}>{$vo.chid}</option>
                             </volist>
                        </select>
                    </div>
                    <div class="tab_con">
                        <div class="con1" >
                            <div class="con2_head"><img src="__STATIC__/image/puter.png" class="con2_puter"><p>摄像头列表</p>
                                <div>
                                    <img src="__STATIC__/image/freshen.png" class="con1_freshen" style="cursor:pointer;float:right" onclick="re_all_data()">
                                </div>
                            </div>
                            <div class="zTreeDemoBackground left"  style=" overflow-y:auto; overflow-x:auto; height:420px">
                                <ul id="treeDemo" class="ztree"></ul>
                            </div>
                        </div>  
                        <div class="con2">
                            <div class="con2_head"><img src="__STATIC__/image/puter.png" class="con2_puter"><p>已选摄像头或组织</p>
                                <div>
                                	<img src="__STATIC__/image/add_new.png" class="con1_freshen" style="cursor:pointer;float:right;margin-right:50px"onclick="re_sel_data()">
                                    <img src="__STATIC__/image/freshen.png" class="con1_freshen" style="cursor:pointer;float:right"onclick="re_sel_data()">
                                </div>
                            </div>
                            <div class="zTreeDemoBackground left"  style=" overflow-y:auto; overflow-x:auto; height:420px">
                                <ul id="treeDemo1" class="ztree"></ul>
                            </div>
                        </div>    
                     </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>