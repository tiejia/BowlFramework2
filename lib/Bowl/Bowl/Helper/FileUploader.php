<?php
/**
 * 文件上传助手
 * 提供文件上传和统一存储
 *
 * @version 2.1
 * @package Bowl_Helper
 * @author zhaotiejia
 *
 */
class Bowl_Helper_FileUploader{

    private $uploadDir;
    private $uploadFiles = array();

    /**
     * 构造函数
     *
     * @param null $uploadDir 文件上传路径 默认会从配置文件中获取
     */
    public function __construct($uploadDir = null)
    {
        $this->uploadDir = is_null($uploadDir) ? Flea::getAppInf("TempUploadDir") : $uploadDir;
        if (!is_dir($this->uploadDir)) {
            throw new Bowl_Helper_FileUploader_Exception("您指定的上传目录[{$this->uploadDir}]不存在");
        }

        //分日期存储
        $date = date("Ymd");
        if(!is_dir($this->uploadDir.$date."/")){
            @mkdir($this->uploadDir.$date);
        }
        $this->uploadDir = $this->uploadDir.$date."/";

        if (is_array($_FILES)) {
            foreach($_FILES as $key=>$file){
                $this->uploadFiles[$key] = new Bowl_Helper_FileUploader_File($file);
            }
        }
    }

    /**
     * 获取上传的文件个数
     *
     * @return int
     */
    public function getUploadFileCount(){
        return count($this->uploadFiles);
    }

    /**
     * 获取上传文件存储目录
     *
     * @return mixed|null|string
     */
    public function getUploadDir(){
        return $this->uploadDir;
    }

    /**
     * 设置上传文件目录
     *
     * @param $uploadDir
     * @throws Bowl_Helper_FileUploader_Exception
     */
    public function setUploadDir($uploadDir){
        if(!is_dir($uploadDir)){
            throw new Bowl_Helper_FileUploader_Exception("您指定的上传目录[{$uploadDir}]不存在");
        }
        $this->uploadDir = $uploadDir;
    }

    /**
     * 获取上传文件
     *
     * @param $key
     * @return bool
     */
    public function getUploadFile($key){
        if(isset($this->uploadFiles[$key])){
            return $this->uploadFiles[$key];
        }else{
            return false;
        }
    }

    /**
     * 获取所有上传的文件
     *
     * @return array
     */
    public function getUploadFiles(){
        return $this->uploadFiles;
    }

    /**
     * 执行上传操作
     * 获取上传为文件
     * @param $fileKey $_FILES[]的key
     * @param int $maxSize 允许的文件大小
     * @param array $allowExt 允许的文件后缀
     * @return mixed 返回Bowl_Helper_File对象
     * @throws Bowl_Helper_FileUploader_Exception
     */
    public function upload($fileKey, $maxSize = 2048, $allowExt = array()){

        if(!isset($this->uploadFiles[$fileKey])){
            throw new Bowl_Helper_FileUploader_Exception("您指定的上传文件名{$fileKey}不存在");
        }

        $uploadFile = $this->uploadFiles[$fileKey];

        if ($uploadFile->getErrorCode() != 0) {
            throw new Bowl_Helper_FileUploader_Exception("上传文件{$fileKey}发生错误：" . $uploadFile['error']);
        }

        if (!is_null($maxSize) && $uploadFile->getFileSize > $maxSize) {
            throw new Bowl_Helper_FileUploader_Exception("对不起，您上传的文件超出了指定大小");
        }
        if (!empty($allowExt)&&!in_array($uploadFile->getFileExt(), $allowExt)) {
            throw new Bowl_Helper_FileUploader_Exception("对不起，您上传的文件格式错误！");
        }

        return $uploadFile->saveFile($this->uploadDir,Bowl::uuid().".".$uploadFile->getFileExt());

    }

    /**
     * 执行所有文件的上传操作
     * 如果同时上传多个文件，则返回结果是个数组包含多个Bowl_Helper_File对象
     * @param int $maxSize
     * @param array $allowExt
     * @return array
     */
    public function uploadAll($maxSize = 2048,$allowExt=array()){
        $returnArray = array();
        foreach($this->uploadFiles as $key => $uploadFile){
            $uploadResult = $this->upload($key,$maxSize,$allowExt);
            array_push($returnArray,$uploadResult);
        }
        return $returnArray;
    }
}

class Bowl_Helper_FileUploader_File{

    private $struct ;
    private $fileMime;
    private $fileExt;
    private $fileSize;
    private $fileBaseName;
    private $fileName;
    private $uploadTmp;

    public function __construct($struct){
        if(!is_array($struct)||empty($struct)){
            throw new Bowl_Helper_FileUploader_Exception("上传文件错误");
        }
        $this->struct = $struct;
        $this->fileMime = $this->struct['type'];
        $pathInfo = pathinfo($this->struct['name']);
        $this->fileBaseName = $pathInfo['basename'];
        $this->fileName = $pathInfo['filename'];
        $this->fileExt = strtolower($pathInfo['extension']);
        $this->errorCode = $this->struct['error'];
        $this->uploadTmp = $this->struct['tmp_name'];
        $this->fileSize = Bowl_Helper_FileSystem::fileSizeTrans($this->struct['size'],"B","K");
    }

    public function saveFile($destDir,$newName = null){
        $destDir = rtrim($destDir,"/")."/";
        if(move_uploaded_file($this->uploadTmp,$destDir.$newName)){
            return new Bowl_Helper_File($destDir.$newName);
        }else{
            throw new Bowl_Helper_FileUploader_Exception("对不起，处理上传文件时发生错误！");
        }
    }

    public function getErrorCode(){
        return $this->errorCode;
    }

    public function getFileMime(){
        return $this->fileMime;
    }

    public function getFileExt(){
        return $this->fileExt;
    }

    public function getFileSize(){
        return $this->fileSize;
    }

    public function getFileBaseName(){
        return $this->fileBaseName;
    }

    public function getFileName(){
        return $this->fileName;
    }
}

class Bowl_Helper_FileUploader_Exception extends Flea_Exception{

}

?>