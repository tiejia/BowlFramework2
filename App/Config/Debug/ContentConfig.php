<?php
/**
 * 用户内容配置
 * 对用户内容的管理配置
 *
 * @author zhaotiejia@ebupt.com
 * @version 2.1
 * @package BowlConfig
 */
return array(
    
   //文件上传目录{{按照Bowl的约定该目录不能被Web访问，并且将定时清楚数据}}
   //@注意:{{用户上传文件先存储到临时目录中，经过处理后放置到对应SaveDir中}}
   "TempUploadDir"=>BOWL_BASE_DIR."/~runtime/upload/",
    
   /*************************************************
    * 存储配置
    * 在服务端的存储
    *************************************************/

   //图片类文件存储目录{{jpg,png,gif,jpeg...}}
   "ImageSaveDir"=>BOWL_BASE_DIR."public/content/images/",
   
   //文本类文件存储目录{{txt,log...}}
   "TextsSaveDir"=>BOWL_BASE_DIR."public/admin/content/texts/",
   
   //视频类文件存储目录{{swf,avi,wma..}}
   "VideoSaveDir"=>BOWL_BASE_DIR."public/admin/content/videos/",
   
   //文档类文件存储目录{{doc,excel..}}
   "DocsSaveDir"=>BOWL_BASE_DIR."public/admin/content/docs/",

   //音频类文件存储目录{{mp3,wav..}}
   "AudiosSaveDir"=>BOWL_BASE_DIR."public/admin/content/audios/",
   
   /****************************************************
    * 访问配置
    * 通过Http访问方式
    ***************************************************/
   
   //内容服务器地址
   "ContentServer"=>BOWL_BASE_URL."content/",

   //图片URL规则{{}}
   "ImagesUrlRule"=>BOWL_BASE_URL."content/images/%s",
  
   //文本URL规则
   "TextsUrlRule"=>BOWL_BASE_URL."content/texts/%s",
   
   //视频URL规则
   "VideosUrlRule"=>BOWL_BASE_URL."content/videos/%s",
   
   //文档URL规则
   "DocsUrlRule"=>BOWL_BASE_URL."content/docs/%s",

   //音频URL规则
   "AudiosUrlRule"=>BOWL_BASE_URL."content/audios/%s"
   
);
?>