<?php
/**
 * Calendar Widget
 * @auther: kin
 * @date: 2014-10-21
 * @eg: W('Calendar'),W('Calendar','moa'); 
 */

class CalendarWidget extends Widget{

	
	public function render($data){
		
		return '<script src="__STATIC__/tool/calendar/WdatePicker.js"></script>';
	}
		
}