<?php

class NetworkAction extends BaseAction
{

    public function index()
    {

        $this->lists = D('Network')->lists();

        $this->display();
    }

    public function update()
    {

        $data['BOOTPROTO'] = I('post.BOOTPROTO');
        $data['BROADCAST'] = I('post.BROADCAST');
        $data['DEVICE'] = I('post.DEVICE');
        $data['DNS1'] = I('post.DNS1');
        $data['GATEWAY'] = I('post.GATEWAY');
        $data['IPADDR'] = I('post.IPADDR');
        $data['LINKED'] = I('post.LINKED');
        $data['MACADDR'] = I('post.MACADDR');
        $data['NETMASK'] = I('post.NETMASK');
        $data['UP'] = 1;

        $post_data = array(
            'devices'=>$data,
            'restart'=>I('post.restart')
        );

        $result = D('Network')->update($post_data);
        if ($result['return'] == 0) {
            $this->response("success","更新成功");
        } else {
            $this->response("error","网络故障，请重试！");
        }

    }

    public function restart()
    {
        $data = array("action"=>"restart_network");
        $result = D('Network')->restart($data);
        if ($result['return'] == 0) {
            $this->response("success","重启成功");
        } else {
            $this->response("error","网络故障，请重试！");
        }
    }


}