<?php

class PageModel extends Model{
			
	protected $config = array(
							
							//预设分页
							'page_preset'	=> array(
										//默认分页
										'default'	=> array(
															 //每页记录数(create方法第三个参数 可覆盖此项)
															 'size'=>20,
															 //分页栏每页显示的页数
															 'roll'=>5,
															 //分页样式
															 'style'=>'default',
															 //分页配置
															 'config'=>array(
															 			'header' =>'条记录',
																		'prev' 	 =>'上一页',
																		'next' 	 =>'下一页',
																		'first'  =>'首　页',
																		'last' 	 =>'尾　页',
																		'theme'  =>'<span class="pageinfo">%nowPage%/%totalPage%</span> %first% %upPage% %linkPage% %downPage% %end%',
															 )),
															 
										//默认分页
										'article'	=> array(
															 //每页记录数(create方法第三个参数 可覆盖此项)
															 'size'=>1,
															 //分页栏每页显示的页数
															 'roll'=>5,
															 //分页样式
															 'style'=>'default',
															 //分页配置
															 'config'=>array(
															 			'header' =>'条记录',
																		'prev' 	 =>'上一页',
																		'next' 	 =>'下一页',
																		'first'  =>'首　页',
																		'last' 	 =>'尾　页',
																		'theme'  =>'%first% %prePage% %linkPage% %end%',
															 )),
															 
										//moa grid
										'sys_grid'	=> array(
															 //每页记录数(create方法第三个参数 可覆盖此项)
															 'size'=>20,
															 //分页栏每页显示的页数
															 'roll'=>7,
															 //分页样式
															 'style'=>'sys_grid',
															 //分页配置
															 'config'=>array(
															 			'header' =>'条记录',
																		'prev' 	 =>'上一页',
																		'next' 	 =>'下一页',
																		'first'  =>'首　页',
																		'last' 	 =>'尾　页',
																		'theme'  =>'<span class="pageinfo">当前共%totalRow%条记录 %nowPage%/%totalPage%页 </span> %first% %upPage% %linkPage% %downPage% %end%',
															 )),															 															 
															 

							
							),
							
							//分页样式
							'page_style'	=> array(
										'default'		=> array(
																''				=> 'padding:10px;margin:3px;',
																'span.pageinfo' => 'color:#999;',
																'a'				=> 'padding:4px 10px;margin:2px;color:#666;text-decoration:none; background:#eaeaea;',
																'a:hover'		=> 'color:#fff; background:#000;',
																'span.current'	=> 'padding:4px 10px;font-weight:bold;margin:2px;color:#fff;background-color:#b82925;',
																'span.disabled'	=> 'padding:4px 10px;margin:2px;color:#ddd;',
															),
															
										'blue'		=> array(
																''		=> 'padding:10px;margin:3px;',
																'a'				=> 'padding:4px 10px;margin:2px;color:#f00;text-decoration:none; background:#ff0;',
																'a:hover'		=> 'color:#fff; background:#000;',
																'span.current'	=> 'padding:4px 10px;font-weight:bold;margin:2px;color:#fff;background-color:#b82925;',
																'span.disabled'	=> 'padding:4px 10px;margin:2px;color:#ddd;',
															),
										'sys_grid'		=> array(
																''		=> 'padding:5px;',
																'span.pageinfo' => 'color:#666;',
																'a'				=> 'padding:3px 8px;margin:2px;color:#333;text-decoration:none; background:#ccc;',
																'a:hover'		=> 'color:#fff; background:#000;',
																'span.current'	=> 'padding:3px 8px;margin:2px;color:#fff;background-color:#666;',
																'span.disabled'	=> 'padding:3px 8px;margin:2px;color:#ddd;',
															),																														
															
														
									
							),

							
	
	);
	
	//分页limit 参数
	public $limit = array();
	
	protected $Page;
	
	/**
	 * $total 总记录数
	 * $page_preset 预置分页 默认使用 default 预置分页
	 * $page_size  每页记录数，可覆盖预置分页中的[每页记录数] size  默认使用预置分页中的 size
	 * $page_style 分页样式，可覆盖预置分页中的[分页样式] style   默认使用预置分页中的 style
	 */
	public function create($total,$page_preset='default',$page_size = NULL,$page_style = NULL){

		//获取预置分页
		$preset = $this->config['page_preset'][$page_preset];
		//每页记录数 传递的记录数可覆盖预置的
		$preset['size'] 	= intval($page_size)>0 ? $page_size : $preset['size'];
		//样式名称
		$preset['style'] 	=  empty($page_style) ? ( empty($preset['style']) ? 'default' : $preset['style'] ) : $page_style;
		//样式配置
		$style 	= $this->config['page_style'][$preset['style']];
		
		$this->Page = new Page($total,$preset['size']);
		//
		if( intval($preset['roll'])>0 ){
			$this->Page->rollPage = $preset['roll'];			
		}
		if(!empty($preset['config'])){
			foreach($preset['config'] as $key => $val ){
				$this->Page->setConfig($key,$val);	
			}
		}
		//
		$this->limit[0] = $this->Page->firstRow;
		$this->limit[1] = $this->Page->listRows;
		//
		$page_selector = "page-{$preset['style']}";
		$page_html = '<div class="page-container">';
		$page_html.= $this->get_page_css($page_selector,$style);
		$page_html.= '<div class="'.$page_selector.'">'.$this->Page->show().'</div>';
		$page_html.= '</div>';
		
		return $page_html;
		
				
	}
	
	
	private function get_page_css($page_selector,$style){
		if( empty($style) ) return '';
		
		$page_selector = '.'.$page_selector;
		$css = '<style type="text/css">';
		foreach($style as $key=>$val){
			$css.= $page_selector.' '.$key.'{'.$val.'}';			
		}
		$css.= '</style>';		
		
		return $css;
	
	}
	

  	
	
}