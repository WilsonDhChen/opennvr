<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2012 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

/**
 * 缓存管理类
 * @category   Think
 * @package  Think
 * @subpackage  Core
 * @author    liu21st <liu21st@gmail.com>
 */
class Cache {

    /**
     * 操作句柄
     * @var string
     * @access protected
     */
    protected $handler    ;

    /**
     * 缓存连接参数
     * @var integer
     * @access protected
     */
    protected $options = array();

    /**
     * 连接缓存
     * @access public
     * @param string $type 缓存类型
     * @param array $options  配置数组
     * @return object
     */
    public function connect($type='',$options=array()) {
        if(empty($type))  $type = C('DATA_CACHE_TYPE');
        $type  = strtolower(trim($type));
        $class = 'Cache'.ucwords($type);
        if(class_exists($class))
            $cache = new $class($options);
        else
            throw_exception(L('_CACHE_TYPE_INVALID_').':'.$type);
        return $cache;
    }

    public function __get($name) {
        return $this->get($name);
    }

    public function __set($name,$value) {
        return $this->set($name,$value);
    }

    public function __unset($name) {
        $this->rm($name);
    }
    public function setOptions($name,$value) {
        $this->options[$name]   =   $value;
    }

    public function getOptions($name) {
        return $this->options[$name];
    }

    /**
     * 取得缓存类实例
     * @static
     * @access public
     * @return mixed
     */
    static function getInstance() {
       $param = func_get_args();
        return get_instance_of(__CLASS__,'connect',$param);
    }

    /**
     * 队列缓存
     * @access protected
     * @param string $key 队列名
     * @return mixed
     */
    // 
    protected function queue($key) {
        static $_handler = array(
            'file'  =>  array('F','F'),
            'xcache'=>  array('xcache_get','xcache_set'),
            'apc'   =>  array('apc_fetch','apc_store'),
        );
        $queue  =  isset($this->options['queue'])?$this->options['queue']:'file';
        $fun    =  isset($_handler[$queue])?$_handler[$queue]:$_handler['file'];
        $queue_name=isset($this->options['queue_name'])?$this->options['queue_name']:'think_queue';
        $value  =  $fun[0]($queue_name);
        if(!$value) {
            $value   =  array();
        }
        // 进列
        if(false===array_search($key, $value))  array_push($value,$key);
        if(count($value) > $this->options['length']) {
            // 出列
            $key =  array_shift($value);
            // 删除缓存
            $this->rm($key);
             if(APP_DEUBG){
                //调试模式下，记录出列次数
                N($queue_name.'_out_times',1,true);
            }
        }
        return $fun[1]($queue_name,$value);
    }
    
    public function __call($method,$args){
        //调用缓存类型自己的方法
        if(method_exists($this->handler, $method)){
           return call_user_func_array(array($this->handler,$method), $args);
        }else{
            throw_exception(__CLASS__.':'.$method.L('_METHOD_NOT_EXIST_'));
            return;
        }
    }
}