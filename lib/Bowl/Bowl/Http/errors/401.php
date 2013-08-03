<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <title>云南社区通|401</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<style>

	    html{color:#000;background:#FFF;}body,div,dl,dt,dd,ul,ol,li,h1,h2,h3,h4,h5,h6,pre,code,form,fieldset,legend,input,button,textarea,p,blockquote,th,td{margin:0;padding:0;}table{border-collapse:collapse;border-spacing:0;}fieldset,img{border:0;}address,caption,cite,code,dfn,em,strong,th,var,optgroup{font-style:inherit;font-weight:inherit;}del,ins{text-decoration:none;}li{list-style:none;}caption,th{text-align:left;}h1,h2,h3,h4,h5,h6{font-size:100%;font-weight:normal;}q:before,q:after{content:'';}abbr,acronym{border:0;font-variant:normal;}sup{vertical-align:baseline;}sub{vertical-align:baseline;}legend{color:#000;}input,button,textarea,select,optgroup,option{font-family:inherit;font-size:inherit;font-style:inherit;font-weight:inherit;}input,button,textarea,select{*font-size:100%;}
		body{
			background-color:#EEF3FA;
		}
		#content{
			width:500px;
			margin:0 auto;
		    position:relative;
			background-color:#FFF;
			padding:20px 10px;
			height:300px;
		}
		#content h1{
			font-size:24px;
			color:#37ABF5;
			font-weight:bold;
			margin-bottom:20px;
		}
		
		#content .reson{
			    background-color: #DEDEDE;
			    height: 70px;
			    left: 309px;
			    padding: 10px 5px;
			    position: absolute;
			    top: 50px;
			    width: 200px;
		}
		
		#content .reson h4{
			font-size:14px;
			color:#787878;
		}
		#content p{
			color:red;
			font-size:14px;
			font-weight:bold;
			margin-bottom:10px;
		}
		#content .reson ul{
			padding:5px;
		}
		
		#content .reson ul li{
			color:#898989;
			line-height:20px;
			font-size:14px;
		}
	</style>
</head>
<body>
	<div id="content">
    	<h1>云南社区通</h1>
    	<div class="reson">
    		<h4>您看到这个页面可能是因为</h4>
	    	<ul>
	    		<li>您还未登录</li>
	    		<li>登录超时</li>
	    	</ul>
    	</div>
    	<p><?php echo $message;?></p>
    	<div class="tb">
    		<a href="#">返回首页</a>
    		<a href="#">重新登录</a>
    	</div>
    </div>
</body>
</html>