<?php
/**
*	客服
*/

class rockkefuClassAction extends apiAction
{
	
	public function initAction()
	{
		parent::initAction();
		$type   = @$_SERVER['REQUEST_METHOD'];
		$cans	= $_GET;
		$cans['user'] = $this->userrs['user'];
		unset($cans['m']);
		unset($cans['a']);
		
		$url 	= c('rockkefu')->geturlstr('openkefu',A, $cans);
		if($type=='POST'){
			$result = c('curl')->postcurl($url, $_POST);
		}else{
			$result = c('curl')->getcurl($url);
		}
		if(!$result)$result = json_encode(returnerror('无法访问'));
		echo $result;
		exit;
	}
}