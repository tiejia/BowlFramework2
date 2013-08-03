<?php
/**
 * 文件助手类
 * 为每个文件生成一个对象并提供便捷方法
 *
 * @package Bowl_Helper
 * @version 2.1
 * @author zhaotiejia@ebupt.com
 *
 */
class Bowl_Helper_File{

    private $file;
    private $fileExt;
    private $fileSize;
    private $fileName;
    private $fileBaseName;
    private $fileDir;

    /**
     * 文件助手构造函数
     *
     * @param $file 完整的文件路径，必须为存在的文件，否则将抛出异常
     * @throw Bowl_Helper_File_Exception
     */
    public function __construct($file){
        if(!file_exists($file)){
            throw new Bowl_Helper_File_Exception("文件{$file}不存在");
        }
        $pathInfo = pathinfo($file);
        $this->file = $file;
        $this->fileExt = strtolower($pathInfo['extension']);
        $this->fileBaseName = $pathInfo['basename'];
        $this->fileName = $pathInfo['filename'];
        $this->fileDir = $pathInfo['dirname'];
        $this->fileSize = Bowl_Helper_FileSystem::fileSizeTrans(filesize($this->file),"B","K");
    }

    /**
     * 获取文件完整路径
     *
     * @return mixed
     */
    public function getFilePath(){
        return $this->file;
    }

    /**
     * 获取文件扩展名
     * @return mixed
     */
    public function getFileExt(){
        return $this->fileExt;
    }

    /**
     * 获取文件大小(KB)
     * @return bool|float
     */
    public function getFileSize(){
        return $this->fileSize;
    }

    /**
     * 获取文件完整名称
     * 包含文件名和扩展名 如1.jpg
     * @return mixed
     */
    public function getFileBaseName(){
        return $this->fileBaseName;
    }

    /**
     * 获取文件名
     * 不包含扩展名 如1
     * @return mixed
     */
    public function getFileName(){
        return $this->fileName;
    }

    /**
     * 获取文件所在的目录
     *
     * @return mixed
     */
    public function getFileDir(){
        return $this->fileDir;
    }

    public function copy(){

    }

    public function move(){

    }

    public function rename(){

    }
}

class Bowl_Helper_File_Exception extends FLEA_Exception{


}