<?php 
/************************************************	
 *
 *	@Class : Image 
 *	@Role  : 提供图片的一些常用处理程序 
 *  @Demo  : 
 *			$img = new images();
 *			$img->loadFile("test.jpg")->crop(0,0,100,100)->resize(50,50)->waterMark("mark.png", 'left','center')->save("new.jpg");
 *
 ************************************************/  
  
 class Images { 
         var $img;   //图像标识符 
         var $info;  //保存图片的一些信息 
		 var $image_path;
  
         function Images($file=null) { 
			$this->__construct($file);
         } 
		 
		 function __construct($file=null){
			     if(!extension_loaded('gd')) 
                 throw new Exception("GD库没有加载."); 
                 if($file) $this->loadFile($file); 
		}
          
         function __destruct() { 
                 if(is_resource($this->img)) 
                         imagedestroy($this->img); 
         }
		 //返回图片信息  
         function __call($method, $arg) { 
                 if(substr($method, 0, 3) == 'get') { 
                         $attr = strtolower(substr($method, 3)); 
                         if(isset($this->info[$attr])) 
                                 return $this->info[$attr]; 
                         return null; 
                 } 
         } 
		 
         //返回图片资源 
         function getResource() { 
                 if(isset($this->img)) 
                         return $this->img; 
                 return null; 
         }
  		
		 // 获取图片信息
		 function getImgInfo($key=false){
			if ($key){
				return  isset($this->info[$key]) ? $this->info[$key] : null ;
			}else {
				return $this->info;
			}
		 }
         /** 
          * 保存到文件 
          *  
          * @param string $path 文件的绝对路径  
          */ 
         function save($path) { 
                 return $this->_output($path); 
         } 
  
         /** 
          * 将图片输出到浏览器 
          * 
          * @param $type 格式 
          */ 
         function output($type='gif') {                
                 return $this->_output('stream', $type); 
         } 
  
         /** 
          * 初始化，创建图像标识符 
          * 
          * @param $file 源文件 
          */ 
         function loadFile($file) { 
                 if(!file_exists($file)) {
                         throw new Exception("指定的文件不存在 => $file"); 
				 }
				 $this->image_path = $file;
                 $string = file_get_contents($file); 
                 $key = array('width', 'height', 'type'); 
                 $this->info = array_combine($key, array_slice(getimagesize($file), 0, 3)); 
                 $this->info['file'] = $file; 
                 $this->img = imagecreatefromstring($string); 
                 return $this; 
         } 
  
         /** 
          * 图片缩放 
          * 
          * @param int $width 目标宽度 
          * @param int $height 目标高度 
          * @param boolean $keepScale，是否等比缩放 
          * @return array $wh 包含缩放后宽度和高度的数组 $wh[0]为宽度，$wh[1]为高度 
          */ 
         function resize($width, $height, $keepScale=true) { 
                 $srcw   = $this->getWidth(); 
                 $srch   = $this->getHeight(); 
                 if($keepScale) {                        
                         //原始宽高比大于目标宽高比,调整高度 
                         if((double)($srcw/$srch) > (double)($width/$height)) 
                         { 
                                 $height = ceil($srch * $width / $srcw); 
                         }else{ 
                                 ///原始宽高比小于目标宽高比,调整宽度 
                                 $width = ceil($srcw * $height / $srch); 
                         } 
                 } 
  
                 //创建一个透明背景的图像 
                 $newimg = $this->_createAlphaImage($width, $height); 
                 //将原始重新采样复制到透明背景上 
                 imagecopyresampled($newimg, $this->img, 0, 0, 0, 0, $width, $height, $srcw, $srch); 
                 imagedestroy($this->img); 
                 $this->img = $newimg; 
                 $this->info['width'] = $width; 
                 $this->info['height'] = $height; 
                 return $this; 
         } 
  
         /** 
          * 创建一个透明图片,用于图像复制 
          * 
          * @param int $width 宽度 
          * @param int $height 高度 
          */ 
         function _createAlphaImage($width, $height){ 
                 $newimg = imagecreatetruecolor($width, $height); 
                 if($this->getType() == 1){ //gif图片 
                         $colorCount = imagecolorstotal($this->img); 
                         imagetruecolortopalette($newimg, true, $colorCount); 
                         imagepalettecopy($newimg,$this->img); 
                         $transparentcolor = imagecolortransparent($this->img); 
                         imagefill($newimg,0,0,$transparentcolor); 
                         imagecolortransparent($newimg,$transparentcolor);  
                 }elseif($this->getType() == 3){ //png图片 
                         imagealphablending($newimg,false); 
                         $col=imagecolorallocatealpha($newimg,255,255,255,127); 
                         imagefilledrectangle($newimg,0,0,$width,$height,$col); 
                         imagealphablending($newimg,true); 
                 } 
                 return $newimg; 
         } 
  
         /** 
          * 生成缩略图 
          * 
          * @param int $width 宽度 
          * @param int $height 高度 
          * @param boolean $crop 是否对超出部分进行裁剪,默认为是, 如果不裁剪,则缩图将等比缩放至小于目标尺寸 
          * @param boolean $center crop时是否在中间裁剪 
          * @param string $path 要生成的新文件名 
          */ 
         function thumbnail($width=128,$height=128, $crop=true, $center=true, $path=null) { 
  
                 $destw  = min($this->getWidth(), $width); 
                 $desth = min($this->getHeight(), $height); 
                 if($crop){ 
                         $srcw = $this->getWidth(); 
                         $srch = $this->getHeight();                      
                         $x = $y = 0; 
                         if((double)($srcw/$srch) > (double)($width/$height)) 
                         { 
                                 //计算应COPY的宽度 
                                 $srcw = ceil($width * $srch/ $height); 
                                 //计算起始的x坐标 
                                 if($center) $x = ceil(($this->getWidth() - $srcw) / 2); 
                         }else{ 
                                 //计算应COPY的高度 
                                 $srch = ceil($height * $srcw / $width); 
                                 //计算起始的y坐标 
                                 if($center) $y = ceil(($this->getHeight() - $srch) / 2); 
                         } 
                         //创建一个透明背景的图像 
                         $newimg = $this->_createAlphaImage($width, $height); 
                         //将原始重新采样复制到透明背景上 
                         imagecopyresampled($newimg, $this->img, 0, 0, $x, $y, $width, $height, $srcw, $srch); 
                         imagedestroy($this->img); 
                         $this->img = $newimg; 
                         $this->info['width'] = $width; 
                         $this->info['height'] = $height; 
                 }else{ 
                         $this->resize($destw, $desth); 
                 } 
                 if($path) return $this->save($path); 
                 return $this; 
         } 
  
         /** 
          * 图片裁剪 
          * 
          * @param int $x x坐标 
          * @param int $y y坐标 
          * @param int $w 裁剪宽度 
          * @param int $h 裁剪高度 
          * @return resource 返回裁剪后的图片资源 
          */ 
         function crop($x, $y, $w, $h){ 
                 $w = min($w, $this->getWidth()); 
                 $h = min($h, $this->getHeight()); 
                 $newimg = $this->_createAlphaImage($w, $h); 
                 imagecopy($newimg, $this->img, 0, 0, $x, $y, $w, $h); 
                 imagedestroy($this->img); 
                 $this->img = $newimg; 
                 $this->info['width'] = $w; 
                 $this->info['height'] = $h; 
                 return $this; 
         } 
  
         /** 
          * 对图片进行波纹处理 
          * 
          * @param int $grade 弯曲度数,越大图片变形越厉害 
          * @param string $dir h=水平, v=垂直 
          */ 
         function wave($grade=5, $dir="h"){ 
                 $w = $this->getWidth(); 
                 $h = $this->getHeight(); 
                 if($dir=="h"){ 
                         for($i=0;$i<$w;$i+=2){ 
                                 imagecopyresampled($this->img,$this->img, $i-2, sin($i/10)*$grade,$i,0,2,$h,2,$h); 
                         } 
                 }else{ 
                         for($i=0;$i<$h;$i+=2){ 
                                 imagecopyresampled($this->img,$this->img, sin($i/10)*$grade,$i-2,0,$i,$w,2,$w,2); 
                         } 
                 } 
                 return $this; 
         } 
  
         /** 
          * 给图片加带背景的一行文字 
          * 
          * @param string $text 水印文字 
          * @param string $font 字体文件的路径 
          * @param $color 文字的颜色 16进制，默认为黑色 
		  * @param $bgcolor 文字背景颜色
		  * @param $bgalpha 文字背景透明度
          * @param int $size     文字的大小 
          * @param string $path 如果指定则生成图片到$path 
          * @return 生成的水印图片路径 
          */ 
         function textMark($text, $font, $size=9, $color="#000000",$bgcolor="#ffffff",$bgalpha=100, $path=null) { 
                 if(!file_exists($font)) 
                         throw new Exception("字体文件不可用 => $font"); 
  
                 //取得图片的高度和宽度 
                 $mwidth = $this->getWidth(); 
                 $mheight= $this->getHeight(); 
                  
                 $color = $this->_hexColor($color); 
                 $color = imagecolorallocate($this->img, $color['r'], $color['g'], $color['b']); 
                 $black = imagecolorallocate($this->img, 0, 0, 0); 
				 $bgcolor = $this->_hexColor($bgcolor); 
                 $alpha = imagecolorallocatealpha($this->img, $bgcolor['r'], $bgcolor['g'], $bgcolor['b'],$bgalpha); 
                 //填充文字背景 
                 $box = imagettfbbox($size, 0, $font, $text); 
                 //文字补白 
                 $padding    = 6; 
                 $textheight = $box[1] - $box[7]; 
                 $bgheight   = $textheight + $padding * 2; 
                 //文字背景 
                 imagefilledrectangle($this->img, 0, $mheight-$bgheight, $mwidth, $mheight, $alpha); 
                 //小竖条 
                 //imagefilledrectangle($this->img, 10, $mheight-$padding-$textheight, 15, $mheight-$padding, $color); 
                 //填充文字 
                 $texty = $mheight - $bgheight/2 + $textheight/2; 
                 imagettftext($this->img, $size, 0, 10, $texty, $color, $font, $text); 
                 if($path) return $this->save($path); 
                 return $this; 
         } 
		 
         function textMark2($text, $font, $size=9, $color="#000000") { 
                 if(!file_exists($font)) 
                         throw new Exception("字体文件不可用 => $font"); 
  
                 //取得图片的高度和宽度 
                 $mwidth = $this->getWidth(); 
                 $mheight= $this->getHeight(); 
                  
                 $color = $this->_hexColor($color); 
                 $color = imagecolorallocate($this->img, $color['r'], $color['g'], $color['b']); 
                 $black = imagecolorallocate($this->img, 0, 0, 0); 
                 //填充文字背景 
                 $box = imagettfbbox($size, 0, $font, $text); 
                 //文字补白 
                 $padding    = 6; 
                 $textheight = $box[1] - $box[7]; 
                 $bgheight   = $textheight + $padding * 2; 

                 //填充文字 
                 $texty = $mheight - $bgheight/2 + $textheight/2; 
                 imagettftext($this->img, $size, 0, $mwidth-105, $texty, $color, $font, $text); 
                 return $this; 
         } 		 
  
         /** 
          * 用一张PNG图片给原始图片加水印，水印图片将自动调整到目标图片大小 
          * 
          * @param string $png png图片的路径 
          * @param string $hp 水平位置 left|center|right 
          * @param string $vp 垂直位置 top|center|bottom 
          * @param int    $pct 水印的透明度 0-100, 0为完全透明,100为完全不透明,只适用于非PNG图片水印 
          * @param string $path 如果指定则生成图片到$path 
          * @param  
          * @return 
          */ 
         function waterMark($markImg, $hp='center', $vp='center', $pct=50, $path=null) { 
                 //原图信息 
                 $srcw = $this->getWidth(); 
                 $srch = $this->getHeight(); 
  
                 //水印图信息 
                 $mark = new self($markImg); 
                 $markw = $mark->getWidth(); 
                 $markh = $mark->getHeight(); 
  
                 //水印图片大于目标图片，调整大小 
                 if($markw > $srcw || $markh > $srch) { 
                         //先将水印图片调整到原始图片大小-10个像素 
                         $mark->resize($srcw-10, $srch-10, true); 
                         $markw = $mark->getWidth(); 
                         $markh = $mark->getHeight(); 
                 } 
          
                 //判断水印位置 
                 $arrx = array('left' => 0, 'center' => floor(($srcw - $markw) / 2), 'right' => $srcw - $markw); 
                 $arry = array('top'  => 0, 'center' => floor(($srch - $markh) / 2), 'bottom' => $srch - $markh); 
                 $x = isset($arrx[$hp]) ? $arrx[$hp] : $arrx['center']; 
                 $y = isset($arry[$vp]) ? $arry[$vp] : $arry['center']; 
                  
                 //png图片水印 
                 if($mark->getType() == 3){ 
                         //打开混色模式 
                         imagealphablending($this->img, true); 
                         imagecopy($this->img, $mark->getResource(), $x, $y, 0, 0, $markw, $markh); 
                 }else{ 
                         imagecopymerge($this->img, $mark->getResource(), $x, $y, 0, 0, $markw, $markh, $pct); 
                 } 
                 unset($mark); 
                 if($path) return $this->save($path); 
                 return $this; 
         } 
  
         //返回由16进制组成的颜色索引 
         function _hexColor($hex) { 
                 $color = hexdec(substr($hex, 1)); 
         return array( 
             "r"     => ($color & 0xFF0000) >> 16, 
             "g" => ($color & 0xFF00) >> 8, 
             "b" => $color & 0xFF 
                 );       
         } 
  
  
         //png的alpha校正 
         function _pngalpha($format) { 
                 //PNG图像要保持alpha通道 
                 if($format == 'png') { 
                         imagealphablending($this->img, false); 
                         imagesavealpha($this->img, true); 
                 } 
         } 
  
         //输出函数，内部使用 
         function _output($path,$type=null) {                
                 $toFile = false; 
                 //输出到文件 
                 if($path !='stream') { 
                         if(!is_dir(dirname($path))) 
                                 throw new Exception('指定的路径不可用 =>'.$path); 
                         $type = pathinfo($path, PATHINFO_EXTENSION); 
                         $toFile = true; 
                 } 
                 //png的alpha校正 
                 $this->_pngalpha($type); 
  
                 if($type == "jpg") $type = "jpeg"; 
                 $func = "image".$type; 
                 if(!function_exists($func)) { 
                         $type = 'gif'; 
                         $func = 'imagegif'; 
                 } 
                 if($toFile) {  
                        if($type=='png'){
							call_user_func($func, $this->img, $path); 	 
						}else{
							call_user_func($func, $this->img, $path,100); 	
						}
						
                 }else{ 
						ob_start();
						if($type=='png'){
							call_user_func($func,$this->img,NULL);
						}else{
							call_user_func($func,$this->img,NULL,100);
						}
						
						$imagevariable = ob_get_contents();
						ob_end_clean();
						$cache_time =  3600*24*365;//一年
						header('Last-Modified: '.gmdate('D, d M Y H:i:s', filemtime($this->image_path)).' GMT', true, 200);
						header("Content-type:image/".$type);
						header('Expires: '.gmdate('D, d M Y H:i:s',time()+$cache_time).' GMT');
						header('Cache-Control: max-age='.$cache_time);
						echo $imagevariable;
                 } 
                 return $this; 
         } 
} 
 ?>