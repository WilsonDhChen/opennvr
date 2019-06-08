<?php
class StreamModel extends Model{

    // 根据国标ID获取flv流播放地址
    public function get_flv($gb_id, $api_url=null, $app=null, $domain=null) {
        if (empty($api_url))
            $api_url = config('api_play_url')->value();

        if (empty($app)) {
            $app = M()->table('config_global_keyvalue')->where("name ='app'")->find();
            $app = $app['value'];
        }

        if (empty($domain)) {
            $domain = M()->table('config_global_keyvalue')->where("name='domain'")->find();
            $domain = $domain['value'];
        }

        $suffix = explode('?', $api_url);
        
        $param = array();
        if (!empty($suffix[1])) 
            parse_str($suffix[1], $param);

        if (!empty($domain))
            $param['domain'] = $domain;

        $api_url = $suffix[0].'?'.urldecode(http_build_query($param));
        
        $host = $this->get_host();
        $rep_old = array('{id}', '{host}', '{app}');
        $rep_new = array($gb_id, $host, $app);
        $url = str_replace($rep_old, $rep_new, $api_url);
        return $url;
    }

    public function get_host() {
        $host = $_SERVER['HTTP_HOST'];
        $host = explode(':', $host);
        $host = array_shift($host);
        return $host;
    }
    
}
