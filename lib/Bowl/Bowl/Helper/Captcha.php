<?php
/**
 * 基于GD的验证码生成程序
 * 使用前请确定是否安装GD库和其扩展
 *
 * @package Bowl_Helper
 * @version 2.1
 * @author zhaotiejia@ebupt.com
 *
 */
class Bowl_Helper_Captcha{
	 //@定义验证码图片高度
	 private $height;
	 //@定义验证码图片宽度
	 private $width;
	 //@定义验证码字符个数
	 private $textNum;
	 //@定义验证码字符内容
	 private $textContent;
	 //@定义字符颜色
	 private $fontColor;
	 //@定义随机出的文字颜色
	 private $randFontColor;
	 //@定义字体大小
	 private $fontSize;
	 //@定义字体
	 private $fontFamily;
	 //@定义背景颜色
	 private $bgColor;
	 //@定义随机出的背景颜色
	 private $randBgColor;
	 //@定义字符语言
	 private $textLang;
	 //@定义干扰点数量
	 private $noisePoint;
	 //@定义干扰线数量
	 private $noiseLine;
	 //@定义是否扭曲
	 private $distortion;
	 //@定义扭曲图片源
	 private $distortionImage;
	 //@定义是否有边框
	 private $showBorder;
	 //@定义验证码图片源
	 private $image;

	 /**
      * 构造函数
      */
	 public function __construct(){
		 $this->textNum=4;
		 $this->fontSize=16;
		 $this->fontFamily=dirname(__FILE__)."/font/Tuffy.ttf";
		 $this->textLang='en';
		 $this->noisePoint=30;
		 $this->noiseLine=3;
		 $this->distortion=false;
		 $this->showBorder=false;
	 }

	 /**
      * 设置生成验证码图片的宽度
      *
      * @param $w
      */
	 public function setWidth($w){
	 	$this->width=$w;
	 }

	 /**
      * 设置生成的验证码图片的高度
      *
      * @param $h
      */
	 public function setHeight($h){
	 	$this->height=$h;
	 }

	 /**
      * 设置验证码中包含的字符数
      *
      * @param $textN
      */
	 public function setTextNumber($textN){
	 	$this->textNum=$textN;
	 }

	 /**
      * 设置字体颜色
      *
      * @param $fc
      */
	 public function setFontColor($fc){
	 	$this->fontColor=sscanf($fc,'#%2x%2x%2x');
	 }

	 /**
      * 设置字体大小
      *
      * @param $n
      */
	 public function setFontSize($n){
	 	$this->fontSize=$n;
	 }

	 /**
      * 设置字体
      *
      * @param $ffUrl
      */
	 public function setFontFamily($ffUrl){
	 	$this->fontFamily=$ffUrl;
	 }


	 public function setTextLang($lang){
	 	$this->textLang=$lang;
	 }


	 public function setBgColor($bc){
	 	$this->bgColor=sscanf($bc,'#%2x%2x%2x');
	 }


	 public function setNoisePoint($n){
	 	$this->noisePoint=$n;
	 }


	 public function setNoiseLine($n){
	 	$this->noiseLine=$n;
	 }


	 public function setDistortion($b){
	 	$this->distortion=$b;
	 }


	 public function setShowBorder($border){
	 	$this->showBorder=$border;
	 }


	 public function initImage(){
		 if(empty($this->width)){$this->width=floor($this->fontSize*1.3)*$this->textNum+10;}
		 if(empty($this->height)){$this->height=$this->fontSize*2;}
		 $this->image=imagecreatetruecolor($this->width,$this->height);
		 if(empty($this->bgColor)){
		 $this->randBgColor=imagecolorallocate($this->image,mt_rand(100,255),mt_rand(100,255),mt_rand(100,255));
		 }else{
		 $this->randBgColor=imagecolorallocate($this->image,$this->bgColor[0],$this->bgColor[1],$this->bgColor[2]);
		 }
		 imagefill($this->image,0,0,$this->randBgColor);
	 }

	 //@产生随机字符
	 public function randText($type){
		 $string='';
		 switch($type){
			 case 'en':
				 $str='ABCDEFGHJKLMNPQRSTUVWXY3456789';
				 for($i=0;$i<$this->textNum;$i++){
					$string=$string.','.$str[mt_rand(0,29)];
				 }
				break;
			 case 'cn':
				 for($i=0;$i<$this->textNum;$i++) {
					$string=$string.','.chr(rand(0xB0,0xCC)).chr(rand(0xA1,0xBB));
				 }
				$string=iconv('GB2312','UTF-8',$string); //转换编码到utf8
				break;
		 }
		 return substr($string,1);
	 }

	 //@输出文字到验证码
	 public function createText(){
		 $textArray=explode(',',$this->randText($this->textLang));
		 $this->textContent=join('',$textArray);
		 if(empty($this->fontColor)){
		 $this->randFontColor=imagecolorallocate($this->image,mt_rand(0,100),mt_rand(0,100),mt_rand(0,100));
		 }else{
		 $this->randFontColor=imagecolorallocate($this->image,$this->fontColor[0],$this->fontColor[1],$this->fontColor[2]);
		 }
		 for($i=0;$i<$this->textNum;$i++){
		 $angle=mt_rand(-1,1)*mt_rand(1,20);
		 imagettftext($this->image,$this->fontSize,$angle,5+$i*floor($this->fontSize*1.3),floor($this->height*0.75),$this->randFontColor,$this->fontFamily,$textArray[$i]);
		 }
	 }

	 //@生成干扰点
	 public function createNoisePoint(){
		 for($i=0;$i<$this->noisePoint;$i++){
		 $pointColor=imagecolorallocate($this->image,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));
		 imagesetpixel($this->image,mt_rand(0,$this->width),mt_rand(0,$this->height),$pointColor);
		 }
	 }

	 //@产生干扰线
	 public function createNoiseLine(){
		 for($i=0;$i<$this->noiseLine;$i++) {
			 $lineColor=imagecolorallocate($this->image,mt_rand(0,255),mt_rand(0,255),20);
			 imageline($this->image,0,mt_rand(0,$this->width),$this->width,mt_rand(0,$this->height),$lineColor);
		 }
	 }

	 //@扭曲文字
	 public function distortionText(){
		 $this->distortionImage=imagecreatetruecolor($this->width,$this->height);
		 imagefill($this->distortionImage,0,0,$this->randBgColor);
		 for($x=0;$x<$this->width;$x++){
			 for($y=0;$y<$this->height;$y++){
				 $rgbColor=imagecolorat($this->image,$x,$y);
				 imagesetpixel($this->distortionImage,(int)($x+sin($y/$this->height*2*M_PI-M_PI*0.5)*3),$y,$rgbColor);
			 }
		}
	 	$this->image=$this->distortionImage;
	 }

	 /**
      * 创建验证码图片
      *
      * @return mixed 返回验证码字符串
      */
	 public function createImage(){
		 $this->initImage(); //创建基本图片
		 $this->createText(); //输出验证码字符
		 if($this->distortion){$this->distortionText();} //扭曲文字
		 $this->createNoisePoint(); //产生干扰点
		 $this->createNoiseLine(); //产生干扰线
		 if($this->showBorder){imagerectangle($this->image,0,0,$this->width-1,$this->height-1,$this->randFontColor);}
		 imagepng($this->image);
		 imagedestroy($this->image);
		 if($this->distortion){imagedestroy($this->distortionImage);}
		 return $this->textContent;
	 }
}