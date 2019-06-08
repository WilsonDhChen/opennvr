<?php

class GroupsAction extends BaseAction
{

    public function index()
    {
        $where = "1=1";
        $sName = I('get.sName','','input_filter');
        if ($sName) {
            $where .= " and sName like '%{$sName}%'";
        }

        $Mdl = M();
        $this->lists = $Mdl->table('bi_groups')->where($where)->page();
        $this->page_html = $Mdl->page->html();
        $this->sName = $sName;
        $this->display();
    }

    public function add()
    {

        $name = I('post.name','','input_filter');
        if (!$name) {
            $this->response("error","请输入分组名称");
        }

        if (M()->table('bi_groups')->where("sName = '{$name}'")->count()) {
            $this->response("error","分组名称已存在，请重新输入");
        }

        $result = M()->table('bi_groups')->add(array("sName"=>$name));

        if ($result) {
            $this->response("success","添加成功");
        } else {
            $this->response("error","网络故障，请重试！");
        }

    }

    public function update()
    {
        $name = I('post.name','','input_filter');
        $id = I('post.id',0,intval);
        if (!$id) {
            $this->response("error","参数丢失，请重试！");
        }
        if (!$name) {
            $this->response("error","请输入分组名称");
        }

        if (M()->table('bi_groups')->where("sName = '{$name}' and nId !='{$id}'")->count()) {
            $this->response("error","分组名称已存在，请重新输入");
        }

        $result = M()->table('bi_groups')->where("nId = '{$id}'")->save(array("sName"=>$name));

        if ($result!==false) {
            $this->response("success","修改成功");
        } else {
            $this->response("error","网络故障，请重试！");
        }
    }

    public function delete()
    {
        $id = I('post.id',0,intval);
        if (!$id) {
            $this->response("error","参数丢失，请重试！");
        }
        if ($id == 1) {
            $this->response("error","对不起，改组不允许删除！");
        }
        $result = D('Live')->delete_group($id);

        if ($result['return']==0) {
            $this->response("success","删除成功");
        } else {
            $this->response("error",$result['error']);
        }
    }

}