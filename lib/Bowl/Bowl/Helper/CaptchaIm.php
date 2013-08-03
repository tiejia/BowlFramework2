<?php
/**
 * 基于ImageMagick生成验证码图片
 * 使用前请确定是否安装imagick扩展
 *
 * @package Bowl_Helper
 * @version 2.1
 * @author zhaotiejia@ebupt.com
 *
 */
class Bowl_Helper_CaptchaIm{


    private $width = 80;
    private $height = 30;
    private $font;


    public function __construct(){
        $this->font = dirname(__FILE__)."/font/Tuffy.ttf";
    }

    //@产生随机字符
    public function randText($type = "en"){
         $string='';
         switch($type){
             case 'en':
                 $str='ABCDEFGHJKLMNPQRSTUVWXY3456789';
                 for($i=0;$i<4;$i++){
                    $string=$string.$str[mt_rand(0,29)];
                 }
                break;
             case 'cn':
                 for($i=0;$i<4;$i++) {
                    $string=$string.','.chr(rand(0xB0,0xCC)).chr(rand(0xA1,0xBB));
                 }
                $string=iconv('GB2312','UTF-8',$string); //转换编码到utf8
                break;
         }
         return $string;
    }

    /**
     * 生成验证码图片并发送给客户端
     *
     * @return string 验证码字符串
     */
    public function createImage(){

        $img = new Imagick();
        $img->newImage($this->width, $this->height, new ImagickPixel('#A2D0B4'), 'png');

        $text = new ImagickDraw();
        $text->setFilLColor('#6A2E29');
        $text->setFont($this->font);
        $text->setFontSize($this->height - 10);
        $text->setGravity(Imagick::GRAVITY_CENTER);

        $randText = $this->randText();
        $text->annotation(0, 0, $randText);
        $img->drawImage($text);
        // generate noise
        $noise = new ImagickDraw();
        $noise->setFilLColor('#92D214');
        for ($i=0; $i<30; $i++) {
           $x = mt_rand(0,$this->width);
           $y = mt_rand(0,$this->height);
           $noise->circle($x, $y, $x+mt_rand(0.3, 1.7), $y+mt_rand(0.3, 1.7));
        }
        for($i=0; $i<5; $i++) {
           $noise->line(mt_rand(0,$this->width), mt_rand(0,$this->height), mt_rand(0,$this->width), mt_rand(0,$this->height));
        }
        //$img->waveImage(1, mt_rand(60, 100));
        $img->drawImage($noise);
        $img->swirlImage(mt_rand(10, 30));
        header( "Content-Type: image/{$img->getImageFormat()}" );
        echo $img->getImageBlob();
        return $randText;
    }


}
