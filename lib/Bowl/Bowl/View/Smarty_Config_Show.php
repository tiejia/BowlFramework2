<style>

    table.bowl_config{
        border:1px solid #FF9900;
    }
    
    table.bowl_config td{
        border:1px solid #FF9900;
    }

</style>
<h1 style="color:#FF9900;font-size:14px;font-weight:bold;">BowlFramework 视图设置信息</h1>
<table class="bowl_config">
    <tr><td bgcolor="#FF9900" colspan="2">Smarty设置</td></tr>
    <?foreach($config['smartyConfig'] as $key=>$value):?>
    <tr>
        <td><?echo $key?></td>
        <td><?echo $value?></td>
    </tr>
    <?endforeach?>
    <tr><td bgcolor="#FF9900" colspan="2">基本设置</td></tr>
    <tr>
        <td>当前使用模板</td>
        <td><?echo $config['useTheme']?></td>
    </tr>
    <tr>
        <td>BaseUrl</td>
        <td><?echo $config['baseUrl']?></td>
    </tr>
    <tr>
        <td>网页编码</td>
        <td><?echo $config['contentCharset']?></td>
    </tr>
    <tr>
        <td>Css服务器</td>
        <td><?echo $config['cssServer']?></td>
    </tr>
    <tr>
        <td>Jascript服务器</td>
        <td><?echo $config['jsServer']?></td>
    </tr>
    <tr>
        <td>图标服务器</td>
        <td><?echo $config['iconServer']?></td>
    </tr>
    <tr>
        <td>Flash服务器</td>
        <td><?echo $config['flashServer']?></td>
    </tr>
    <tr>
        <td>Flash服务器</td>
        <td><?echo $config['flashServer']?></td>
    </tr>
    <tr><td bgcolor="#FF9900" colspan="2">已加载的Javascript</td></tr>
    <?foreach($config['loadJsFiles'] as $key=>$value):?>
     <tr>
        <td><?echo $key?></td>
        <td><?echo $value?></td>
    </tr>
    <?endforeach?>
    <tr><td bgcolor="#FF9900" colspan="2">已加载的Css</td></tr>
    <?foreach($config['loadCssFiles'] as $key=>$value):?>
     <tr>
        <td><?echo $key?></td>
        <td><?echo $value?></td>
    </tr>
    <?endforeach?>
</table>

