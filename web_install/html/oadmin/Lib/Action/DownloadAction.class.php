<?php

class DownloadAction extends BaseAction
{

    public function index()
    {

        $Mdl = M();
        $this->lists = $Mdl->table('bi_groups as a')->join("(select count(*) as total,nGroupId from bi_live_streams group by nGroupId) as b on a.nId = b.nGroupId")->where()->select();
        $this->nogroupnum =  M()->table("bi_live_streams")->where("nGroupId = 0")->count();

        $result = D('Live')->select();
        $lives = array();
        foreach ($result['streams'] as $value) {
            $lives[$value['sysid']] = $value['started'];
        }
        $this->assign("lives",$lives);

        $this->display();
    }

    public function detail()
    {

        $Live = D('Live');

        $gid = I('get.gid',0,'intval');
        $id = I('get.id',0,'intval');
        if (!$id) {
            $id = M()->table("bi_live_streams")->where("nGroupId = '{$gid}'")->getField("min(nId)");
        }

        $this->info = $Live->detail_by_sysid($id);

        $play_api_url = config("api_play_url")->value();
		$app = M()->table('config_global_keyvalue')->where("name = 'app'")->find();
        $host = $this->getHost();
        $this->play_url = str_replace(array('{id}','{host}','{app}'),array($this->info['id'],$host,$app['value']),$play_api_url);

        /*if ($play_api_url) {
            $this->play_url = str_replace("{id}",$this->info['id'],$play_api_url);
        } else {
            $this->play_url = "http://".$_SERVER['SERVER_ADDR']."/live/".$this->info['id'].'.flv';
        }*/
        $down_api_url = config("api_down_url")->value();
		$app = M()->table('config_global_keyvalue')->where("name = 'app'")->find();
        $this->down_url = str_replace(array('{id}','{host}','{app}'),array($this->info['id'],$host,$app['value']),$down_api_url).'?download=1';


        /*if ($play_api_url) {
            $this->down_url = str_replace("{id}",$this->info['id'],$down_api_url).'?download=1';
        } else {
            $this->down_url = "http://".$_SERVER['SERVER_ADDR']."/live/".$this->info['id'].'.ts?download=1';
        }*/

        $year = (int) $_GET['year']?(int) $_GET['year']:date('Y');
        $month = (int) $_GET['month']?(int) $_GET['month']:date('n');
        $checkon = D('Checkon');
        $calendar = $checkon->calendar($year,$month,$this->info['id']);

        $domain = M()->table('config_global_keyvalue')->where("name='domain'")->find();
        $database = !empty($domain) ? 'nvr_'.$domain['value'] : 'nvr_default';
        $config = C();
        $config = $config['db_config2'];
        $config['db_name'] = $database;
        $db = Db::getInstance($config);
        $db = M()->setDb($db);
        $table = 'nvr-gb28181-'.$this->info['id'];
        $mdb = $db->table($table);
        //var_dump($mdb);exit;
        $min_year = $mdb->getField("min(sBeginTime)");

        //$min_year = D('Vods')->table("nvr_live_".$this->info['id'])->getField("min(sBeginTime)");

        $this->min_year = date('Y',strtotime($min_year));
        
        $this->assign('year',$year);
        $this->assign('month',$month);
        $this->assign('calendar',$calendar);
        $this->display();
    }

    public function lists()
    {

        $gid = I('get.gid',0,'intval');
        $lists = M()->table("bi_live_streams")->field("nId,sName")->where("nGroupId = '{$gid}'")->order("nId asc")->select();

        if ($lists) {
            $this->response("success","获取成功",$lists);
        } else {
            $this->response("error","暂无数据");
        }
    }

    public function get_calendar()
    {

        $year = (int) $_POST['year']?(int) $_POST['year']:date('Y');
        $month = (int) $_POST['month']?(int) $_POST['month']:date('n');
        $id = I('post.id');

        $checkon = D('Checkon');
        $calendar = $checkon->calendar($year,$month,$id);

        $this->response("success",'',$calendar);

    }

    
    private function getHost()
    {
        $host = $_SERVER['HTTP_HOST'];
        $host = explode(':', $host);
        $host = array_shift($host);
        return $host;
    }

}
