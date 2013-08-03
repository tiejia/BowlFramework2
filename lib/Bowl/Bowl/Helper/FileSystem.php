<?php
/**
 * 文件系统助手类
 * 提供对文件系统的便捷操作
 *
 * @package Bowl_Helper
 * @version 2.1
 * @author zhaotiejia@ebupt.com
 *
 */
class Bowl_Helper_FileSystem
{

    /**
     * 单位B
     */
    CONST FILE_SIZE_B = "B";
    /**
     * 单位K
     */
    CONST FILE_SIZE_KB = "K";
    /**
     * 单位M
     */
    CONST FILE_SIZE_MB = "M";
    /**
     * 单位G
     */
    CONST FILE_SIZE_GB = "G";


    /**
     * 递归创建目录
     *
     * @static
     * @param $dir 目录如：a/b 会创建a和a下的/b,如果目录已存在则不会覆盖
     * @param int $mode 目录权限，默认为0777
     * @return bool
     */
    static function mkdirs($dir, $mode = 0777)
    {
        if (!is_dir($dir)) {
            self::mkdirs(dirname($dir), $mode);
            return mkdir($dir, $mode);
        }
        return true;
    }

    /**
     * 递归删除目录
     *
     * @static
     *
     */
    static function rmdirs($dir)
    {

    }

    /**
     * 文件大小单位转换
     *
     * @static
     * @param $size 大小值
     * @param string $from 输入单位 使用静态常量
     * @param string $to 输出单位 使用静态常量
     * @return bool|float
     */
    static function fileSizeTrans($size, $from = self::FILE_SIZE_B, $to = self::FILE_SIZE_MB)
    {
        $unit = array(
            self::FILE_SIZE_B,
            self::FILE_SIZE_KB,
            self::FILE_SIZE_MB,
            self::FILE_SIZE_GB
        );

        if ($from == $to) {
            return $size;
        }

        $fromKey = array_keys($unit, $from);
        if (!is_array($fromKey)) {
            return false;
        }
        $fromIndex = $fromKey[0];
        $toKey = array_keys($unit, $to);
        if (!is_array($toKey)) {
            return false;
        }
        $toIndex = $toKey[0];
        //缩小单位
        if ($fromIndex > $toIndex) {
            $tmpSize = $size;
            for ($i = $toIndex; $i < $fromIndex; $i++) {
                $tmpSize = $tmpSize * 1024;
            }
            return round($tmpSize, 2);
        } else {
            $tmpSize = $size;
            for ($i = $fromIndex; $i < $toIndex; $i++) {
                $tmpSize = $tmpSize / 1024;
            }
            return round($tmpSize, 2);
        }

    }

}