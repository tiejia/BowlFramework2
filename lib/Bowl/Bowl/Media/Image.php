<?php
/**
 * 基于ImageMagick的图片处理类
 * 使用该类之前请确定已安装 imagick扩展
 *
 * @package Bowl_Media
 * @version 2.1
 * @author zhaotiejia@ebupt.com
 *
 */
class Bowl_Media_Image{

	
	private $outputImagick;
	private $imagick; 	
	private $imgFile;

    /**
     * 构造函数
     *
     * @param $imgFile 要处理的图片文件或Bowl_Helper_File对象
     *
     */
	public function __construct($imgFile){
		if(!extension_loaded("imagick")){
			throw new Bowl_Media_Exception("您还没有加载Imagick扩展，请确定您是否已经安装");
		}
        if($imgFile instanceof Bowl_Helper_File){
            $this->imgFile = $imgFile;
        }else{
            if(!file_exists($imgFile)){
                throw new Bowl_Media_Exception("您要处理的图片不存在");
            }
            $this->imgFile = new Bowl_Helper_File($imgFile);
        }

		$this->imagick = new Imagick();
		$this->imagick->readimage($this->imgFile->getFilePath());
		$this->outputImagick = $this->imagick;
	}


    /**
     * 获取imagick对象
     *
     * @return Imagick
     */
    public function getImgick(){
        return $this->imagick;
    }

    /**
     * 获取图片格式
     * 返回格式为全小写
     * @return string
     */
    public function getImgFormat(){
        return strtolower($this->imagick->getImageFormat());
    }

    /**
     * 获取图片尺寸
     * 返回包含图片高宽的数组
     * @return array
     */
	public function getGeo(){
		return $this->imagick->getImageGeometry();
	}

    /**
     * 获取图片exif信息
     *
     * @return mixed
     */
	public function getExif(){
		return $this->outputImgick->getImageProperties("exif:*");
	}
	
	/**
	 * 图片缩略图
     * 会对图片做等比缩放
     *
	 * @param $width 缩略图宽度
	 * @param $height 缩略图高度
	 */
	public function thumbnail($width,$height){
		$this->outputImagick->thumbnailImage($width,$height,true);
	}
	
	/**
	 * 缩放图片
	 * @param unknown_type $width 宽度
	 * @param unknown_type $height 高度
	 * @param unknown_type $fit 是否等比缩放
	 */
	public function resize($width,$height,$fit=true){
		if($this->getImgFormat() == "gif"){
			$destImg = new Imagick();
			$color_transparent = new ImagickPixel("transparent"); //透明色
			foreach($this->outputImagick as $cimg){
				$page = $cimg->getImagePage();
				$tmp = new Imagick();
				$tmp->newImage($page['width'], $page['height'], $color_transparent, 'gif');
				$tmp->compositeImage($cimg, Imagick::COMPOSITE_OVER, $page['x'], $page['y']);
				$tmp->thumbnailImage($width, $height, true);
				$destImg->addImage($tmp);
				$destImg->setImagePage($tmp->getImageWidth(), $tmp->getImageHeight(), 0, 0);
				$destImg->setImageDelay($cimg->getImageDelay());
				$destImg->setImageDispose($cimg->getImageDispose());
			}
			$this->outputImagick = $destImg;
		}else{
			$this->outputImagick->resizeimage($width, $height, Imagick::FILTER_LANCZOS, 1,$fit);
		}
	}
	
	/**
	 * 截取图片
	 * @param unknown_type $width
	 * @param unknown_type $height
	 * @param unknown_type $x
	 * @param unknown_type $y
	 * @throws Bowl_Media_Exception
	 */
	public function crop($width,$height,$x,$y){
		$srcWh = $this->outputImagick->getImageGeometry();
		$srcWidth = $srcWh["width"];
		$srcHeight= $srcWh["height"];
		//裁剪范围超出图片大小
		if($x+$width>$srcWidth||$srcHeight<$y+$height){
			throw new Bowl_Media_Exception("您设置的裁剪位置已经超出图片本身!");
		}
		$this->outputImagick->cropImage($width,$height,$x,$y);
	}
	
	/**
	 * 销毁Imagick对象
	 */	
	public function clear(){
		if($this->outputImagick instanceof Imagick) $this->outputImagick->clear();
		if($this->imagick instanceof Imagick) $this->imagick->clear();
		$this->imagick = null;
		$this->outputImagick = null;
	}
	
	/**
	 * 另存图片
	 * @param $destDir 保存的目录
	 * @param $newName 新的图片名称 如果不填写则使用原图名称
	 * @param $newExt  新的图片扩展名 如果不填写则使用原图格式
     *
	 */
	public function saveAs($destDir,$newName=null,$newExt=null){
		$destDir = rtrim($destDir,"/")."/";
		//没有图片名称则取图片默认名称
		if(empty($newName)){
			if(empty($newExt)){
				$dest = $destDir.$this->imgFile->getFileName().".".$this->getImgFormat();
			}else{
				$dest = $destDir.$this->imgFile->getFileName().".".$newExt;
			}
		}else{
			if(empty($newExt)){
				$dest = $destDir.$newName.".".$this->getImgFormat();
			}else{
				$dest = $destDir.$newName.".".$newExt;
			}
		}
		$this->outputImagick->coalesceImages();
		$this->outputImagick->writeImages($dest, true);
		$this->outputImagick->clear();
        return new Bowl_Helper_File($dest);
	}
	
	/**
	 * 保存图片
     * 生成图片将覆盖原图片
	 */
	public function save(){
		$this->outputImagick->coalesceImages();
		$this->outputImagick->writeImages($this->imgFile->getFilePath(), true);
		$this->outputImagick->clear();
        return new Bowl_Helper_File($this->imgFile->getFilePath());
	}
}

class Bowl_Media_Exception extends FLEA_Exception{

}
?>