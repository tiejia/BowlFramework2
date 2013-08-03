<?php
/**
 * 分页助手
 * 提供与框架相关联的分页支持
 *
 * @package Bowl_Helper
 * @version 2.1
 * @author zhaotiejia@ebupt.com
 */
class Bowl_Helper_Pager{

 	/**
 	 * 可以使用的配置信息
 	 * @var unknown_type
 	 */
 	private $_allowedOptions = array(
 	   "totalItems","currentPage","perPage",
 	   "firstPageName","lastPageName","prePageName",
 	   "nextPageName","pagerBodyLength","urlConfig"
 	);


    private $_urlConfig = array();

 	/**
 	 * 当前页码
 	 * @var unknown_type
 	 */
 	private $_currentPage = 1;

 	/**
 	 * 页码显示的长度
 	 * @var unknown_type
 	 */
 	private $_pagerBodyLength = 3;
 	/**
 	 * 总的数据量
 	 * @var unknown_type
 	 */
 	private $_totalItems = 0;
 	/**
 	 * 总页数
 	 * @var unknown_type
 	 */
 	private $_totalPage = 0;
 	/**
 	 * 每页显示的数据量
 	 * @var unknown_type
 	 */
 	private $_perPage = 10;
 	/**
 	 * 首页页码
 	 * @var unknown_type
 	 */
 	private $_firstPage = 1;
 	/**
 	 * 末页页码
 	 * @var unknown_type
 	 */
 	private $_lastPage = 0;
 	/**
 	 * 首页名称
 	 * @var unknown_type
 	 */
 	private $_firstPageName="首页";
 	/**
 	 * 末页名称
 	 * @var unknown_type
 	 */
 	private $_lastPageName = "末页";
 	/**
 	 * 上一页名称
 	 * @var unknown_type
 	 */
 	private $_prePageName = "上一页";
 	/**
 	 * 下一页名称
 	 * @var unknown_type
 	 */
 	private $_nextPageName = "下一页";

 	/**
 	 * 分页数据
 	 * @var unknown_type
 	 */
 	private $_pager = array();

 	/**
 	 * 初始化分页工具
 	 * @param $options
 	 * @return unknown_type
 	 */
 	function __construct($options=array()){
 		$this->setOptions($options);
 	}

 	/**
 	 * 获取分页数据
 	 * @return unknown_type
 	 */
 	public function getPager(){
 		//计算总页数
 		$this->getTotalPage();
 		if($this->_totalPage==1||$this->_totalItems==0){
 			return false;
 		}
 		//设置末页页码
 		$this->_lastPage = $this->_totalPage;
 		$pagerBody = $this->getPagerBody();
 		$firstPager = $this->getFirstPager();
 		$lastPager = $this->getLastPager();
 		$prePager = $this->getPrePager();
 		$nextPager = $this->getNextPager();

 		return array(
 		    "firstpage"=>$firstPager,
 		    "prepage"=> $prePager,
 		    "pagebody"=>$pagerBody,
 		    "nextpage"=>$nextPager,
 		    "lastpage"=>$lastPager
 		);

 	}
 	/**
 	 * 获取首页按钮
 	 * @return unknown_type
 	 */
    private function getFirstPager(){
    	$firstPager = array();
    	if($this->_currentPage==1||$this->_totalPage==1||$this->_pagerBodyLength>=$this->_totalPage){
    	    $firstPager = array("uri"=>false);
    	}else{
    		$firstPager = array("uri"=>$this->_url(1),"index"=>1);
    	}
        return $firstPager;
    }
    /**
     * 获取末页按钮
     * @return unknown_type
     */
    private function getLastPager(){
    	$lastPager = array();
    	if($this->_currentPage==$this->_totalPage||$this->_totalPage==1||$this->_pagerBodyLength>=$this->_totalPage){
    	    $lastPager = array("uri"=>false);
    	}else{
    		$lastPager = array("uri"=>$this->_url($this->_totalPage),"index"=>$this->_totalPage);
    	}
        return $lastPager;
    }

    /**
     *
     * 使用FleaPHP生成URL
     * @param $page
     * @return string
     */
    private function _url($page){
        $controller = isset($this->_urlConfig[0])?$this->_urlConfig[0]:null;
        $actionName = isset($this->_urlConfig[1])?$this->_urlConfig[1]:null;
        $params = isset($this->_urlConfig[2])?$this->_urlConfig[2]:array();
        $params['p'] = $page;
        return url($controller,$actionName,$params);
    }

 	/**
 	 * 获取下一页按钮
 	 * @return unknown_type
 	 */
    private function getNextPager(){
    	$nextPager = array();
    	if($this->_currentPage==$this->_totalPage||$this->_totalPage==1){
    		$nextPager = array("uri"=>false);
    	}else{
    		$nextPager = array("uri"=>$this->_url($this->_currentPage+1),"index"=>$this->_currentPage+1);
    	}
    	return $nextPager;
    }
   /**
    * 获取上一页按钮
    * @return unknown_type
    */
    private function getPrePager(){
    	$prePager = array();


    	if($this->_currentPage==1||$this->_totalPage==1){
    		$prePager = array("uri"=>false);
    	}else{
    		$prePager = array("uri"=>$this->_url($this->_currentPage-1),"index"=>$this->_currentPage-1);
    	}
    	return $prePager;
    }

 	/**
 	 * 获取总页数
 	 * @return unknown_type
 	 */
 	private function getTotalPage(){
 		if($this->_totalItems%$this->_perPage>0){
 		    $this->_totalPage  = intval($this->_totalItems/$this->_perPage)+1;
 		}else{
 			$this->_totalPage  = intval($this->_totalItems/$this->_perPage);
 		}
 	}

 	/**
 	 * 获取主体页码
 	 * @return unknown_type
 	 */
 	private function getPagerBody(){
 	    $pagerBody = array();
 	    $pageNum = intval($this->_pagerBodyLength/2);

 	    $start = 1;
 	    $end = $this->_pagerBodyLength;
 	    if($this->_currentPage>$pageNum){
 	    	$start = $this->_currentPage-$pageNum;
 	    	$end = $this->_currentPage+$pageNum;
 	    }

 	    if($end>$this->_totalPage){
 	    	$start = $this->_totalPage - $this->_pagerBodyLength;
 	    	$end = $this->_totalPage;
 	    }

 	    if($start<1){
 	    	$start = 1;
 	    }
 	    if($end >$this->_totalPage){
 	    	$end = $this->_totalPage;
 	    }

 	    for($i = $start;$i<=$end;$i++){
 		    if($i==$this->_currentPage){
             	$pagerBody[]=array("uri"=>$this->_url($i),"current"=>true,"index"=>$i);
             }else{
                 $pagerBody[] = array("uri"=>$this->_url($i),"current"=>false,"index"=>$i);
             }
	 	}
 	    return $pagerBody;
 	}

 	/**
 	 * 设置配置
 	 * @param $options
 	 * @return unknown_type
 	 */
    private function setOptions($options=array()){
        foreach ($options as $key => $value) {
        	if (in_array($key, $this->_allowedOptions) && (!is_null($value))) {
                $this->{'_' . $key} = $value;
            }
        }
    }
 }
?>