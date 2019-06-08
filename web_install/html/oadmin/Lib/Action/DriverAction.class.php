<?php
class DriverAction extends BaseAction
{
    public function base()
    {
        $post = !empty($_POST) ? $_POST : null;
        if (!empty($post)) {
            $post['cmd'] = 'update-base';
            $res = D('Driver')->update('base', $post);
            exit(json_encode($res));
        }

        $info = D('Driver')->get('base');
        $this->assign('info', $info);
        $this->display();
    }

    public function local()
    {
        $post = !empty($_POST) ? $_POST : null;
        if (!empty($post)) {
            $post['enable'] = intval($post['enable']);
            $post_data = array(
                'cmd' => 'update-local',
                'localdisks' => array($post),
            );
            $res = D('Driver')->update('base', $post_data);
            exit(json_encode($res));
        }

        $list = D('Driver')->get('local');
        if (isset($list['localdisks']))
            $list = $list['localdisks'];

        $this->assign('list', $list);
        $this->display();
    }

    public function external()
    {
        $post = !empty($_POST) ? $_POST : null;
        if (!empty($post)) {
            $oper = $post['oper'];
            unset($post['oper']);
            $post_data = array();
            switch ($oper) {
                case 'update':
                    $post['enable'] = intval($post['enable']);
                    $post_data['cmd'] = 'update-external';
                    $post_data['externaldisks'] = array($post);
                    break;
                case 'del':
                    $post_data['cmd'] = 'del-disk';
                    $post_data['uuid'] = $post['uuid'];
                    break;
                case 'add':
                    $post_data['cmd'] = 'add-external';
                    $post_data['enable'] = $post['enable']*1;
                    $post_data['root'] = $post['root'];
                    break;
            }

            $res = D('Driver')->update('base', $post_data);
            exit(json_encode($res));
        }

        $list = D('Driver')->get('external');
        if (isset($list['externaldisks']))
            $list = $list['externaldisks'];
            
        $this->assign('list', $list);
        $this->display();
    }

    public function add_external()
    {
        $post = !empty($_POST) ? $_POST : null;
        if (!empty($post)) {
            $post['cmd'] = 'add-external';

            $res = D('Driver')->update('base', $post);
            exit(json_encode($res));
        }

        $this->display();
    }

    public function network()
    {
        $post = !empty($_POST) ? $_POST : null;
        if (!empty($post)) {
            $post['enable'] = intval($post['enable']);
            $post_data = array(
                'cmd' => 'update-network',
                'networkdisks' => array($post),
            );
            $res = D('Driver')->update('base', $post_data);
            exit(json_encode($res));
        }

        $list = D('Driver')->get('network');
        if (isset($list['networkdisks']))
            $list = $list['networkdisks'];

        $this->assign('list', $list);
        $this->display();
    }
}
