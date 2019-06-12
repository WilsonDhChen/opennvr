<?php

class CheckonModel extends Model{
	
	//日历
	public function calendar($y,$m,$id){
		$lunar = new Lunar();
		
		//日
		$d = date('j');
		//星期
		$w = date('N');
		//当月天数
		$t = date('t',mktime(0,0,0,$m,1,$y));
		//当月开始
		$m_s = strtotime($y.'-'.$m.'-1 00:00:00');
		//当月结束
		$m_o = strtotime($y.'-'.$m.'-'.$t.' 23:59:59');
		//当月星期
		$m_w = date('N',mktime(0,0,0,$m,1,$y));
		
		//日期数组
		$calendar_arr = array();
		$k=0;
		//如果星期数大于1 补前面天数
		if($m_w>1){
			$lack_s = $m_w-1;
			//上月天数
			$lastday = mktime(0,0,0,$m,0,$y);
			$t_prev = date('t',$lastday);
			$m_has_0 = $m<11?0:'';			
			for($i=$t_prev-$lack_s+1;$i<=$t_prev;$i++){
				$d_has_0 = $i<10?0:'';
				$calendar_arr[$k]['val']     = $i;
				$calendar_arr[$k]['date_time'] = $y.'-'.$m_has_0.($m-1).'-'.$d_has_0.$i;
				$calendar_arr[$k]['time']    = strtotime($y.'-'.($m-1).'-'.$i);
				$calendar_arr[$k]['status']  = 1;
				$calendar_arr[$k]['c_val']   = $lunar->S2L(date($y.'-'.($m-1).'-'.$i));
				$k++;
			}
		}else{
			$lack_s = 0;
		}
		//当月日期数组		
		$m_has_0 = $m<10?0:'';		
		for($i=1;$i<=$t;$i++){
			$d_has_0 = $i<10?0:'';
			$calendar_arr[$k]['val']     = $i;
			$calendar_arr[$k]['date_time'] = $y.'-'.$m_has_0.$m.'-'.$d_has_0.$i;
			$calendar_arr[$k]['time']    = strtotime($y.'-'.($m).'-'.$i);
			$calendar_arr[$k]['status']  = 1;			
			$calendar_arr[$k]['c_val']   = $lunar->S2L(date($y.'-'.$m.'-'.$i));
			$k++;
		}		
		//如果当月天数加补前天数除与7有余数则补后天数
		$take = ($t + $lack_s)%7;
		if($take!=0){
			$lack_o = 7-$take;
			//下月天数
			$m_has_0 = $m<9?0:'';
			for($i=1;$i<=$lack_o;$i++){
				$d_has_0 = $i<10?0:'';
				$calendar_arr[$k]['val']     = $i;
				$calendar_arr[$k]['date_time'] = $y.'-'.$m_has_0.($m+1).'-'.$d_has_0.$i;
				$calendar_arr[$k]['time']    = strtotime($y.'-'.($m+1).'-'.$i);
				$calendar_arr[$k]['status']  = 1;
				$calendar_arr[$k]['c_val']   = $lunar->S2L(date($y.'-'.($m+1).'-'.$i));
				$k++;
			}
		}
		
		$html ='';
		$m_o = $calendar_arr[$k-1]['time']+86400;
		//录像标记
        $get_sign = $this->get_sign_new($m_s,$m_o,$id);

		foreach($calendar_arr as $key=>$val){
			//判断日期是否是当月
			//$style=$val['m']==1?'':' not_m';
			//判断此日期是否可选择

            if($val['time']>strtotime($y.'-'.$m.'-'.$d+1) || !in_array($val['time'],$get_sign)){
                $style=' not_m';
                $event='';
            }else{
                $event=' onclick="checkdate(this)"';
                $style='';
            }

			if($val['time']==strtotime(date('Y-m-d 00:00:00'))){
				$datetips = "当前日期";	
				$style.=' now_date';
			}


			if($key==0){
				$html .= '<tr>
		                	<td  title="'.$datetips.'">
		                    	<div class="date-list">
		                        	<a href="javascript:;" '.$event.' class="data-list-box '.$style.'" date-time="'.$val['date_time'].'" date-totime="'.$val['time'].'" date-status="'.$val['status'].'" >
		                        		<span class="date-val">'.$val['val'].'</span>
		                            	<span class="date-val-c">'.$val['c_val'].'</span>
										<span class="date-val-tips"></span>										
		                            </a>
		                        </div>
		                    </td>';
			}elseif(($key+1)%7==1){
				$html .= '</tr><tr>
		                	<td title="'.$datetips.'">
		                    	<div class="date-list">
		                        	<a href="javascript:;" '.$event.' class="data-list-box'.$style.'" date-time="'.$val['date_time'].'" date-totime="'.$val['time'].'" date-status="'.$val['status'].'" >
		                        		<span class="date-val">'.$val['val'].'</span>
		                            	<span class="date-val-c">'.$val['c_val'].'</span>
										<span class="date-val-tips"></span>											
		                            </a>
		                        </div>
		                    </td>';	
			}elseif(($key+1)%7==0){
				$html .= '
		                	<td class="color-red" title="'.$datetips.'">
		                    	<div class="date-list">
		                        	<a href="javascript:;" '.$event.' class="data-list-box'.$style.'" date-time="'.$val['date_time'].'" date-totime="'.$val['time'].'" date-status="'.$val['status'].'" >
		                        		<span class="date-val">'.$val['val'].'</span>
		                            	<span class="date-val-c">'.$val['c_val'].'</span>	
										<span class="date-val-tips"></span>										
		                            </a>
		                        </div>
		                    </td>';	
			}else{
				$html .= '
		                	<td title="'.$datetips.'">
		                    	<div class="date-list">
		                        	<a href="javascript:;" '.$event.' class="data-list-box'.$style.'" date-time="'.$val['date_time'].'" date-totime="'.$val['time'].'" date-status="'.$val['status'].'" >
		                        		<span class="date-val">'.$val['val'].'</span>
		                            	<span class="date-val-c">'.$val['c_val'].'</span>	
										<span class="date-val-tips"></span>
		                            </a>
		                        </div>
		                    </td>';	
			}
			
		}
		$html .= '</tr>';
		return $html;		
	}

	public function get_sign($stime,$otime,$id){

		$res = D('Vods')->field("nYear,nMonth,nDay")->table("nvr-live-".$id)->where(" nBeginTime between $stime and $otime ")->group("nYear,nMonth,nDay")->select();
		$arr = array();
		foreach($res as $val){
			$arr[] = strtotime($val['nYear'].'-'.$val['nMonth'].'-'.$val['nDay']);
		}
        
		return $arr;
	}

    public function get_sign_new($stime, $otime, $id) {
        
        $domain = M()->table('config_global_keyvalue')->where("name='domain'")->find();
        $database = !empty($domain) ? 'nvr_'.$domain['value'] : 'nvr_default';
        $config = C();
        $config = $config['db_config2'];
        $config['db_name'] = $database;
        $db = Db::getInstance($config);
        $db = M()->setDb($db);
        $table = 'nvr-gb28181-'.$id;
        $mdb = $db->table($table);

        $res = $mdb->field("nYear,nMonth,nDay")->where(" nBeginTime between $stime and $otime ")->group("nYear,nMonth,nDay")->select();
		$arr = array();
		foreach($res as $val){
			$arr[] = strtotime($val['nYear'].'-'.$val['nMonth'].'-'.$val['nDay']);
		}
        
		return $arr;
    }
	
}
