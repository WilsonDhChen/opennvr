<?php

class AppAction extends Action {
    
    public function camera_list()
    {
        $k = I('get.k');
        if ($k !== '97ec25cddaca7a2713c3ed420337da79')
            exit(json_encode(array(
                'code' => 1,
                'msg' => 'access denied',
            )));

        $pg = I('get.pg', 1);
        $pz = I('get.pz', 10);
        $offset = $pz*($pg-1);

        $Mdl = M();
        $ids = array(
            '21010530001310000001',
            '21010530001310000002',
            '21010530001310000003',
            '21010530001310000004',
            '21010530001310000005',
            '21010522001310000001',
            '21010522001310000002',
            '21010522001310000003',
        );
        $ids = implode(',', $ids);
        $res_data = array();
        if ($pg*1 === 1) {
            $str_where = "sTypeName='摄像机' AND sStatus='ON' AND sChid in({$ids})";
            $list = $Mdl->table('ext_gb28181_catalog')->field('sChid,sName,sUpdateTime')->where($str_where)->limit($offset, $pz)->select();
            $res_data = array_merge($res_data, $list);
        }

        $str_where = "sTypeName='摄像机' AND sStatus='ON' AND sChid not in({$ids})";
        /*
        $where = array(
            'sTypeName' => '摄像机',
            'sStatus' => 'ON',
        );
         */
        $list = $Mdl->table('ext_gb28181_catalog')->field('sChid,sName,sUpdateTime')->where($str_where)->limit($offset, $pz)->select();
        exit(json_encode(array(
            'code' => 0,
            'data' => array_merge($res_data, $list),
        )));
    }
	
    public function camera_info()
    {
        $k = I('get.k');
        if ($k !== '97ec25cddaca7a2713c3ed420337da79')
            exit(json_encode(array(
                'code' => 1,
                'msg' => 'access denied',
            )));
        $id = I('get.id', 0);
        if (empty($id))
            exit(json_encode(array(
                'code' => 1,
                'msg' => 'error video info',
                'data' => array(),
            )));

        $where = array(
            'sChid' => $id,
        );
        $Mdl = M();
        $info = $Mdl->table('ext_gb28181_catalog')->field('sChid,sName,sUpdateTime')->where($where)->find();
        if (empty($info))
            exit(json_encode(array(
                'code' => 1,
                'msg' => 'error video info',
                'data' => array(),
            )));

        exit(json_encode(array(
            'code' => 0,
            'data' => $info,
            'msg' => 'success',
        )));
    }

    
    public function ajax_getFlvUrl()
    {
        $id = I('post.id', '', 'string');
        $url = D('Stream')->get_flv($id);

        exit(json_encode(array(
            'code' => 0,
            'data' => $url,
            'msg' => 'success',
        )));
    }

}
