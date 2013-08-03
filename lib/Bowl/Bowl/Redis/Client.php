<?php
/**
 * php redis client.
 * User: xuzhengchao
 * Date: 11-9-6
 * Time: 上午9:31
 */
 
class Bowl_Redis_Client{

    private $ip;
    private $port;
    private $savePath;
    private $redis;

    function Bowl_Redis_Client()
    {
        $this->ip = Flea::getAppInf("redisHost");
        $this->port = Flea::getAppInf("redisPort");
        $this->savePath = Flea::getAppInf("savePath");
        $this->redis = new Redis();
        $this->redis->connect($this->ip,$this->port);
    }

    function set($key,$value)
    {
        $this->redis->set($key,$value);
    }
    
    /**
     * 
     * @param $key
     * @param $value
     * @param $interval单位是秒
     */
	function setex($key,$value,$interval)
    {
        $this->redis->set($key,$value);
        $this->redis->expire($key,$interval);
    }

    function get($key)
    {
        return $this->redis->get($key);
    }
    
	function rPush($key,$value)
    {
        return $this->redis->rPush($key,$value);
    }
    
	function lPop($key)
    {
        return $this->redis->lPop($key);
    }
    
    //start=0,end=-1 ，返回所有
	function lRange($key,$start,$end)
    {
        return $this->redis->lRange($key,$start,$end);
    }
    
	function lSize($key)
    {
        return $this->redis->lSize($key);
    }

    function save()
    {
        $this->redis->save();
    }

    function lastsave()
    {
        return $this->redis->lastsave();
    }

}
