<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>BowlFramework 运行环境</title>
    <style>
        table{

        }
    </style>
</head>
<body>
    <h1>BowlFramework运行环境信息</h1>
    <hr>
    <h2>系统环境</h2>
    <table>
        <tr>
            <th>PHP版本:</th>
            <td><?php echo phpversion();?></td>
        </tr>
        <tr>
            <th>BowlFramework版本:</th>
            <td><?php echo Bowl::version();?></td>
        </tr>
    </table>
    <hr>
    <h2>扩展</h2>
    <table>
        <tr>
            <th>扩展模块</th>
            <th>状态</th>
            <th>要求</th>
            <th>说明</th>
        </tr>
        <?php foreach($phpExtensions as $ext):?>
        <tr>
            <td><?php echo $ext['name'];?></td>
            <td>
                <?php
                    if(extension_loaded($ext['module'])){
                        echo "<span style='color:green'>已安装</span>";
                    }else{
                        echo "<span style='color:red;'>未安装</span>";
                    }
                ?>
            </td>
            <td>
                <?php
                    if($ext['required'])
                        echo "<span style='color:red'>必须安装</span>";
                    else
                        echo "根据模块安装"
                ?>
            </td>
            <td>
                <?php echo $ext['desc'];?>
            </td>
        </tr>
        <?php endforeach;?>
    </table>

    <h2>程序目录</h2>
    <table>
        <tr>
            <th>目录</th>
            <th>状态</th>
            <th>描述</th>
        </tr>
        <?php foreach($directorys as $dir):?>
        <tr>
            <td><?php echo $dir['path'];?></td>
            <td>
                <?php
                    if($dir['exists']){
                        echo "<span style='color:green'>已创建</span>";
                    }else{
                        echo "<span style='color:red;'>未创建</span>";
                    }
                ?>
            </td>
            <td>
                <?php echo $dir['desc'];?>
            </td>
        </tr>
        <?php endforeach;?>
    </table>

    <h2>视图目录</h2>
    <table>
        <tr>
            <th>使用主题</th>
            <td><?php echo $useTheme;?></td>
        </tr>
        <tr>
            <th>模板目录</th>
            <td><?php echo $viewConfig['SmartyConfig']['template_dir'];?></td>
            <td>
                <?php
                    if(is_dir($viewConfig['SmartyConfig']['template_dir']))
                        echo "<span style='color:green'>已创建</span>";
                    else
                        echo "<span style='color:red'>未创建</span>";
                ?>
            </td>
        </tr>
        <tr>
            <th>模板编译目录</th>
            <td><?php echo $viewConfig['SmartyConfig']['compile_dir'];?></td>
            <td>
                <?php
                    if(is_dir($viewConfig['SmartyConfig']['compile_dir']))
                        echo "<span style='color:green'>已创建</span>";
                    else
                        echo "<span style='color:red'>未创建</span>";
                ?>
            </td>
        </tr>
        <tr>
            <th>模板缓存目录</th>
            <td><?php echo $viewConfig['SmartyConfig']['cache_dir'];?></td>
            <td>
                <?php
                    if(is_dir($viewConfig['SmartyConfig']['cache_dir']))
                echo "<span style='color:green'>已创建</span>";
            else
                echo "<span style='color:red'>未创建</span>";
                ?>
            </td>
        </tr>
    </table>
</body>
</html>