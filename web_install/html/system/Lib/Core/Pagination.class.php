<?php
/**
 * Pagination 分页
 * Author: Kin@JXY
 * CreatedAt: 2017-04-25 16:25
 * UpdatedAt: 2017-05-01 20:05
 */

class Pagination
{

    //记录偏移量
    public $offset = 0;
    //每页显示记录数
    public $length = 10;

    //总记录数
    private $total_rows = null;
    //当前页数
    private $page_num = 1;
    //总页数
    private $page_total = 0;
    //当前url，不含域名
    private $url = 0;
    //分页模式
    private $mode = null;
    //分页器容器id
    private $id = null;
    //css样式 除了支持常规CSS属性外， 额外支持: link-color 文字颜色，hover-color 鼠标经过背景色，active-color 当前页颜色(主题色)， disabled-color 无效项颜色 ,页码链接padding值
    private $style =  array('link-color'=>'#666666', 'hover-color'=>'#eeeeee', 'active-color'=>'#3c3c3c' ,'disabled-color'=>'#cccccc' ,'border-color'=>'#e2e2e2', 'link-padding'=>'6px 12px');
    //分页a链接 包裹元素
    private $ul_wrap = null;
    //分页模块
    private $modules = array(
        /***
         *  以下所有模块项 值设置为false时 不显示
         */

        //上一页
        'prev'    => '&thinsp;&#139;&thinsp;',
        //下一页
        'next'    => '&thinsp;&#155;&thinsp;',
        //首页
        'first'  => '%first%',
        //尾页
        'last'   => '%last%',
        //分页码列数
        'colnum'   => 5,
        //是否显示跳转页码
        'jump'      => true,

        /**
         * 分页数据信息
         * 可用变量
         * %total_rows% 总记录条数
         * %length%  每页记录条数
         * %page_total%  总页数
         * %page_num%  当前页数
         * 例：'info' => '总记录条数:%total_rows%,每页记录条数:%length%,总页数:%page_total%,当前页数:%page_num%',
         */
        'info'      => false,


    );


    /***
     * Pagination constructor.
     * @param $total_rows  {总记录数}
     * @param int $page_rows {每页显示记录数}
     * @param page $mode
     * {分页模式 当mode值【能】被正则 \w+ 匹配时，mode值代表 url get普通模式的分页字段名}
     * {当mode值【不能】被正则 \w+ 匹配时，mode值代表 url rewrite伪静态模式的匹配规则(正则)}
     * 【url rewrite 分页模式说明】
     * mode 匹配规则(正则) 不需要左右定界符
     * 正则内容分为【三个可匹配子模式】 格式如下 (前置参数)(参数定界符(页码数匹配可用\d+或[0-9]+))?
     * 注意：如果【前置参数】中需要用到()子模式 请使用(?:) 非捕获模式 , 所有部分特殊字符都需要转移尤其注意参数定界符
     * 另外第二个子模式中后面跟?,表示分页参数要么无，要么只有一次  一般分页首页不会出现分页参数
     * 例1:  /your/path/2            => (your\/path)(\/(\d+))?
     * 例2:  /your/path/20170501/2   => (your\/path\/\d{8})(\-(\d+))?
     * 例3:  /your-path-2-p1-p2      => (your\-path)(\-(\d+))?
     * 例4:  /your/2  /your/path/2   => (your(?:\/path)?)(\-(\d+))?
     */

    public function __construct($total_rows, $page_rows = 20, $mode = 'page')
    {

        $this->url      = $this->getCurrentURL();

        $this->mode     = $mode;

        $this->total_rows     =(int) $total_rows;

        $this->length         =(int) $page_rows;

        $this->page_total     =(int) ceil($this->total_rows/$this->length);

        $this->page_num       =(int) $this->getCurrentPage();

        $this->offset         =(int) ($this->page_num-1)*$this->length;


    }


    //分页器容器 id，默认无id
    public function id($id)
    {
        $this->id = $id;

        return $this;
    }


    //配置模块显示隐藏、内容
    public function module($module, $content = null)
    {
        if (is_array($module)) {
            $this->modules = array_merge($this->modules ,$module);
        }else if(is_string($module) && !is_null($content)){
            $this->modules[$module] = $content;
        }

        return $this;
    }

    //快捷设置 active-color颜色 / 主题色
    public function color($active_color)
    {

        if (is_array($this->style)) {
            $this->style['active-color'] = $active_color;
        }

        return $this;

    }


    /**
     * @param false $ul_wrap
     */
    public function html($ul_wrap = false)
    {
        $html = '';

        if ($this->total_rows < 1) {
            return $html;
        }

        $this->ul_wrap =(boolean) $ul_wrap;

        $html.= $this->buildStyleCSS();

        $id = is_null($this->id) ? '' : ' id="'.$this->id.'"';

        $html.= $this->ul_wrap ?'<ul class="pagination"'.$id.'>':'<div class="pagination"'.$id.'>';

        $html.= $this->buildInfoHtml();
        $html.= $this->buildPrevHtml();
        $html.= $this->buildFirstHtml();
        $html.= $this->buildColnumHtml();
        $html.= $this->buildLastHtml();
        $html.= $this->buildNextHtml();
        $html.= $this->buildJumpHtml();

        $html.= $this->ul_wrap ?'</ul>':'</div>';

        return $html;

    }

    //外观样式
    private function buildStyleCSS()
    {
        $css = '';

        //style属性为false时 不输出 CSS
        if ($this->style === false) {
            return $css;
        }

        $css.= '<style type="text/css">';

        //ul wrap时 设置 ul,li 基础css
        if ($this->ul_wrap) {
            $css .= '.pagination{ overflow:hidden; padding:0;margin:0;}';
            $css .= '.pagination li{ float:left;list-style:none;box-sizing:inherit;}';
        }
        //除了额外支持的 css样式，其他css自带样式 均加在Pagination容器上
        if (!empty($this->style)) {
            $css.= '.pagination{box-sizing:content-box;';
            foreach ($this->style as $key=>$val){
                if (in_array($key, array('link-color', 'hover-color', 'active-color', 'disabled-color', 'border-color', 'link-padding'))) {
                    continue;
                }

                $css.= "{$key}:{$val};";
            }
            $css.= '}';
        }

        //额外支持的css样式
        $style = array(
            'link-color'    => isset($this->style['link-color']) ? "color:{$this->style['link-color']};" : '',
            'hover-color'   => isset($this->style['hover-color']) ? "background-color:{$this->style['hover-color']};" : '',
            'active-color'  => isset($this->style['active-color']) ? "background-color:{$this->style['active-color']};border-color:{$this->style['active-color']};" : '',
            'disabled-color'=> isset($this->style['disabled-color']) ? "color:{$this->style['disabled-color']};" : '',
            'border-color'  => isset($this->style['border-color']) ? "border-color:{$this->style['border-color']};" : '',
            'link-padding'  => isset($this->style['link-padding']) ? "padding:{$this->style['link-padding']};" : '',
        );


        $css.= '.pagination span{box-sizing:inherit;}';
        $css.= '.pagination a{display:inline-block;box-sizing:inherit;text-decoration:none;border-width:1px;border-style:solid;position:relative;margin-right:-1px;background-color:#fff;';
        $css.= $style['link-padding'].$style['link-color'].$style['border-color'].'}';
        $css.= '.pagination a:hover{'.$style['hover-color'].'z-index:2;}';
        //圆角
        if ($this->ul_wrap) {
            $css .= '.pagination li.previous a{border-radius:2px 0 0 2px;}';
            $css .= '.pagination li.next a{border-radius:0 2px 2px 0;}';
        } else {
            $css .= '.pagination a{vertical-align: middle;}';
            $css .= '.pagination a:first-of-type{border-radius:2px 0 0 2px;}';
            $css .= '.pagination a:last-of-type{border-radius:0 2px 2px 0;}';
        }

        if (!empty($style['active-color'])) {
            if ($this->ul_wrap) {
                $css.= '.pagination li.active a,.pagination li.active a:hover{'.$style['active-color'].'color:#fff;z-index:3;cursor:default;}';
            } else {
                $css.= '.pagination  a.active,.pagination  a.active:hover{'.$style['active-color'].'color:#fff;z-index:3;cursor:default;}';
            }
        }

        if(!empty($style['disabled-color'])){
            if ($this->ul_wrap) {
                $css.= '.pagination li.disabled a{'.$style['disabled-color'].'cursor:default;}';
                $css.= '.pagination li.disabled a:hover{background-color:inherit;}';
            } else {
                $css.= '.pagination a.disabled{'.$style['disabled-color'].'cursor:default;}';
                $css.= '.pagination a.disabled:hover{background-color:inherit;}';
            }
        }

        if ($this->modules['jump'] !== false) {
            $css.= '.pagination .jump{vertical-align:middle;display:inline-block;padding-left:10px;'.$style['link-color'].'}';
            $css.= '.pagination .jump input{display:inline-block;box-sizing:inherit;border-width:1px;border-style:solid;border-radius:2px;font-size:inherit;font-family:inherit;line-height:inherit;outline:none;';
            $css.= $style['link-padding'].$style['link-color'].$style['border-color'].'}';
            $css.= '.pagination .jump input[type=number]{box-shadow:inset 0 0 1px #eee;max-width:70px;}';
            $css.= '.pagination .jump input[type=button]{background-color:#fff;cursor:pointer;}';
            $css.= '.pagination .jump input[type=button]:hover{'.$style['hover-color'].'}';

        }

        if ($this->modules['info'] !== false) {
            $css.= '.pagination .infomation{vertical-align:middle;display:inline-block;padding-right:10px;font-size:inherit;font-family:inherit;border-width:1px;border-style:solid;border-color:transparent;';
            $css.= $style['link-padding'].$style['link-color'].'}';
        }

        $css.= '</style>';

        return $css;
    }


    //数据信息 html
    private function buildInfoHtml()
    {
        if ($this->modules['info'] === false) {
            return '';
        }

        $html = $this->ul_wrap ?'<li class="infomation"><span>':'<span class="infomation">';
        //变量替换
        $html.= str_ireplace(
            array('%total_rows%', '%length%', '%page_total%', '%page_num%'),
            array($this->total_rows, $this->length, $this->page_total, $this->page_num),
            $this->modules['info']);

        $html.= $this->ul_wrap ?'</span></li>':'</span>';

        return $html;

    }


    //上一页 html
    private function buildPrevHtml()
    {
        $html = '';

        if ($this->modules['prev'] === false) {
            return $html;
        }

        if ($this->page_num == 1) {
            $href  = '';
            $class = 'class="previous disabled"';
        } else {
            $href = ' href="'.$this->getPageLink($this->page_num-1).'"';
            $class = 'class="previous"';
        }


        $html.= $this->ul_wrap ?'<li '.$class.' title="上一页"><a'.$href.'>':'<a '.$class.$href.' title="上一页">';
        //变量替换
        $html.= $this->modules['prev'];

        $html.= $this->ul_wrap ?'</a></li>':'</a>';

        return $html;

    }

    //下一页 html
    private function buildNextHtml()
    {

        $html = '';

        if ($this->modules['next'] === false) {
            return $html;
        }

        if ($this->page_num == $this->page_total) {
            $href  = '';
            $class = 'class="next disabled"';
        } else {
            $href = ' href="'.$this->getPageLink($this->page_num+1).'"';
            $class = 'class="next"';
        }


        $html.= $this->ul_wrap ?'<li '.$class.' title="下一页"><a'.$href.'>':'<a '.$class.$href.' title="下一页">';

        $html.= $this->modules['next'];

        $html.= $this->ul_wrap ?'</a></li>':'</a>';

        return $html;

    }

    //首页 html
    private function buildFirstHtml()
    {

        $html = '';

        if ($this->modules['first'] === false) {
            return $html;
        }

        if ($this->page_num == 1) {
            $href  = '';
            $class = ' class="active"';
        } else {
            $href  = ' href="'.$this->getPageLink(1).'"';
            $class = '';
        }


        $html.= $this->ul_wrap ?'<li '.$class.' title="第一页"><a'.$href.'>':'<a'.$class.$href.' title="第一页">';
        //变量替换
        $html.= str_ireplace('%first%', 1, $this->modules['first']);

        $html.= $this->ul_wrap ?'</a></li>':'</a>';

        return $html;

    }


    //尾页 html
    private function buildLastHtml()
    {

        $html = '';

        if ($this->modules['last'] === false || $this->page_total==1) {
            return $html;
        }

        if ($this->page_num == $this->page_total) {
            $href  = '';
            $class = ' class="active"';
        } else {
            $href  = ' href="'.$this->getPageLink($this->page_total).'"';
            $class = '';
        }


        $html.= $this->ul_wrap ?'<li '.$class.' title="最后一页"><a'.$href.'>':'<a'.$class.$href.' title="最后一页">';
        //变量替换
        $html.= str_ireplace('%last%', $this->page_total, $this->modules['last']);

        $html.= $this->ul_wrap ?'</a></li>':'</a>';

        return $html;

    }

    //分页码列 html
    private function buildColnumHtml()
    {

        $html = '';

        if (empty($this->modules['colnum']) || !is_numeric($this->modules['colnum'])) {
            return $html;
        }

        $col_pos = (int) floor($this->modules['colnum']/2);
        $col_start = $this->page_num - $col_pos;
        $col_start = $col_start<2 ? 2 : $col_start;


        $col_end = $col_start + $this->modules['colnum'];

        if($col_end>$this->page_total){
            $col_end = $this->page_total;
            $col_diff = $col_end-$col_start;
            if($col_diff<$this->modules['colnum']){
                $col_start-= $this->modules['colnum']-$col_diff;
                $col_start = $col_start<2 ? 2 : $col_start;
            }
        }

        //左边隐藏占位页码 …
        if($col_start>2 && $this->page_total>$this->modules['colnum'] && $this->page_num>=$this->modules['colnum']){
            $html.= $this->ul_wrap ?'<li class="disabled"><a>':'<a class="disabled">';
            $html.= '…';
            $html.= $this->ul_wrap ?'</a></li>':'</a>';
        }

        //循环页码列
        for ($i=$col_start; $i<$col_end; $i++) {

            if ($this->page_num == $i) {
                $href  = '';
                $class = ' class="active"';
            } else {
                $href  = ' href="'.$this->getPageLink($i).'"';
                $class = '';
            }

            $html.= $this->ul_wrap ?'<li '.$class.'><a'.$href.'>':'<a'.$class.$href.'>';
            $html.= $i;

            $html.= $this->ul_wrap ?'</a></li>':'</a>';
        }

        //右边隐藏占位页码 …
        if(($this->page_total-$col_end>0) && ($this->page_total>$this->modules['colnum']) && ($this->page_num+$col_pos<$this->page_total)){
            $html.= $this->ul_wrap ?'<li class="disabled"><a>':'<a class="disabled">';
            $html.= '…';
            $html.= $this->ul_wrap ?'</a></li>':'</a>';
        }

        return $html;

    }

    //跳转页码 html
    private function buildJumpHtml()
    {

        $html = '';

        if ($this->modules['jump']===false) {
            return $html;
        }

        $html.= $this->ul_wrap ?'<li class="jump">':'<span class="jump">';

        $html.= '到第 <input type="number" id="pageJumpInput" onkeypress="if(event.keyCode==13)document.getElementById(\'pageJumpBtn\').click()" value="'.$this->page_num.'" onfocus="this.select()" min="1" max="'.$this->page_total.'"> 页 ';
        $html.= '<input type="button" id="pageJumpBtn" value="确定" onclick="var pageNum=document.getElementById(\'pageJumpInput\').value;if(!/\d+/.test(pageNum)) return;location=\''.$this->getPageURL().'\'.replace(\'%page_num%\',pageNum)">';


        $html.= $this->ul_wrap ?'</li>':'</span>';

        return $html;

    }

    //分页样式
    public function style($key,$val = null)
    {
        $args_num = func_num_args();

        if ($args_num==1 && $key === false) {
            $this->style = false;
        } elseif($args_num==1 && is_array($key)) {
            $this->style = array_merge($this->style, $key);
        } elseif($args_num==2) {
            $this->style[$key] = $val;
        }

        return $this;
    }

    //获取指定页码的URL
    private function getPageLink($page_num)
    {
        static $url = null;
        if (is_null($url)) {
            $url = $this->getPageURL();
        }

        return str_replace('%page_num%',$page_num,$url);

    }

    //获取带有页码占位符的分页URL
    private function getPageURL()
    {
        $url = $this->url;

        //普通模式  url get
        if (preg_match('/^\w+$/i', $this->mode)) {

            $query = parse_url($url, PHP_URL_QUERY);
            if (empty($query)) {
                $url.= "?{$this->mode}=%page_num%";
            } elseif(stripos($query, "{$this->mode}=")!==false) {
                $url = preg_replace('/('.$this->mode.'\=)\d+/i', "$1%page_num%",$url);
            } else {
                $url.= "&{$this->mode}=%page_num%";
            }

            return $url;

        }

        //自定义模式 url rewrite
        $pattern = '/'.$this->mode.'/i';
        preg_match($pattern, $this->url, $matches);
        $count = count($matches);

        $placeholder = '%page_num%';

        //匹配数量为2，第一页情况 即url中 不包含有页码信息
        if ($count == 2) {
            preg_match('/\(([^(]+)\((?:\\\d|\[0\-9\])\+\)\)\?/i', $this->mode, $pm);
            $placeholder = stripcslashes($pm[1]).'%page_num%';
        } elseif ($count == 4) {
        //匹配数量为4，非第一页情况 即url中 包含有页码信息
            $placeholder = str_ireplace($matches[3], '%page_num%', $matches[2]);
        }

        //
        if($matches[1]===''){
            return preg_replace('/'.preg_quote($matches[2], '/').'/i', $placeholder, $url,1);
        } else {
            return preg_replace($pattern, $matches[1].$placeholder, $url);
        }


    }


    //获取当前页码数
    private function getCurrentPage()
    {
        $page_num = 1;

        //普通模式  url get
        if (preg_match('/^\w+$/i', $this->mode)) {

            $page_num = isset($_GET[$this->mode]) ? intval($_GET[$this->mode]) : 1;

        } else {
        //自定义模式 url rewrite

            $pattern = '/'.$this->mode.'/i';
            preg_match($pattern, $this->url, $matches);
            $count = count($matches);
            //匹配数量为4时，即当前rewrite url 已经带有页码数，子模式中 第四项 为当前页码数
            if ($count==4) {
                $page_num =(int) $matches[3];
            }

            //其他情况皆为 第1页

        }

        //小于1页 等于 1页
        if($page_num < 1){
            $page_num = 1;
        }
        //超过总页数 等于 总页数
        if($page_num > $this->page_total){
            $page_num = $this->page_total;
        }

        return $page_num;
    }

    private function getCurrentURL()
    {
        return $_SERVER['REQUEST_URI'];
    }


}
