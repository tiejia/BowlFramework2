<?php
/**
 * 基于Xhprof的性能分析工具
 * 需要安装xhprof扩展和对应工具，请勿在生产环境中使用
 *
 * @package Bowl_Profiler
 * @version 2.1
 * @author zhaotiejia@ebupt.com
 *
 */

/**
 * Xhprofile 回调函数
 *
 * @param $profileTag 分析标记
 */
function bowl_profiler_xhprof_callback($profileTag){
    $profileData = xhprof_disable();
    $xhprofBaseUrl = "http://10.1.60.154:8888/xhprof/xhprof_html/index.php";
    require_once dirname(__FILE__).DS."xhprof_lib/utils/xhprof_lib.php";
    require_once dirname(__FILE__).DS."xhprof_lib/utils/xhprof_runs.php";
    $xhprof_runs = new XHProfRuns_Default();
    $run_id = $xhprof_runs->save_run($profileData, $profileTag);
    $url = $xhprofBaseUrl."?run=$run_id&source=$profileTag";
    echo "<a style='text-align:center;font-size:18px;font-weight:bolder;display:block;color:red;margin-top:10px;margin-bottom:20px;' href='".$url."' target='_blank'>查看XHprof性能分析数据@BowlFramework</a>";
}

/**
 * 启用Xhproffile
 *
 * @param $profileTag 分析标记
 */
function bowl_profiler_xhprof_run($profileTag){
    xhprof_enable();
    register_shutdown_function("bowl_profiler_xhprof_callback",$profileTag);
}

?>