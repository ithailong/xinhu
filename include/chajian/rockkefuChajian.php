<?php 
/**
*	客服
*/

class rockkefuChajian extends Chajian{
	
	public $openkey,$updatekel,$updatekey;

	protected function initChajian()
	{
		$url  = getconfig('rockkefu_url');
		$urlb = getconfig('rockkefu_localurl');
		if($urlb)$url  = $urlb;
		$this->openkey = getconfig('rockkefu_key');
		if(substr($url,-1)!='/')$url.='/';
		$this->updatekel = $url;
		$this->updatekey = $url.'api.php';
	}
	
	
	public function geturlstr($mod, $act, $can=array())
	{
		$url 	= $this->updatekey;
		$url.= '?m='.$mod.'&a='.$act.'';
		$url.= '&openkey='.md5($this->openkey).'';
		foreach($can as $k=>$v)$url.='&'.$k.'='.$v.'';
		return $url;
	}

	
	/**
	*	get获取数据
	*/
	public function getdata($mod, $act, $can=array())
	{
		$url 	= $this->geturlstr($mod, $act, $can);
		$cont 	= c('curl')->getcurl($url);
		if(!isempt($cont) && contain($cont, 'success')){
			$data  	= json_decode($cont, true);
		}else{
			$data 	= returnerror('无法访问,'.$cont.'');
		}
		return $data;
	}
	
	/**
	*	post发送数据
	*/
	public function postdata($mod, $act, $can=array(), $cans=array())
	{
		$url 	= $this->geturlstr($mod, $act, $cans);
		$cont 	= c('curl')->postcurl($url, $can);
		if(!isempt($cont) && contain($cont, 'success')){
			$data  	= json_decode($cont, true);
		}else{
			$data 	= returnerror('无法访问,'.$cont.'');
		}
		return $data;
	}
	
}