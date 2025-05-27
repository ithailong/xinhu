<?php 
/**
*	文件上传管理中心相关接口
*/

class rockfileChajian extends Chajian{
	

	protected function initChajian()
	{
		$url  = getconfig('rockfile_url');
		$urlb = getconfig('rockfile_localurl');
		if($urlb)$url = $urlb;
		$this->agentkey = getconfig('rockfile_key');
		if(substr($url,-1)!='/')$url.='/';
		$this->updatekel = $url;
		$this->updatekey = $url.'api.php';
	}
	
	
	public function geturlstr($mod, $act, $can=array())
	{
		$url 	= $this->updatekey;
		$url.= '?m='.$mod.'&a='.$act.'';
		$url.= '&agentkey='.$this->agentkey.'';
		$url.= '&websign='.md5($this->rock->HTTPweb).'';
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
	
	/**
	*	同步保存到自己的库
	*/
	public function getsave($fnum)
	{
		if(!$fnum)return $fnum;
		$nums = '';
		$fanu = explode(',', $fnum);
		$ids  = '';
		foreach($fanu as $st1){
			if(is_numeric($st1)){
				$ids.=','.$st1.'';
			}else{
				$nums.=','.$st1.'';
			}
		}
		if($nums){
			$nums = substr($nums, 1);
			$barr = $this->getdata('upload','filesaveinfo',array('nums'=>$nums));
			return $barr;
		}else{
			return returnerror('error');
		}
	}
	
	/**
	*	删除文件
	*/
	public function filedel($nums)
	{
		$barr = $this->getdata('upload','filedel',array('nums'=>$nums));
		return $barr;
	}
	
	/**
	*	上传
	*/
	public function uploadfile($fileid)
	{
		$fobj = m('file');
		$frs  = $fobj->getone($fileid);
		if(!$frs)return returnerror('1');
		$path = ROOT_PATH.'/'.$frs['filepath'];
		if(!file_exists($path))return returnerror('404');
		
		$url 	= $this->geturlstr('upload','upfile', array(
			'optid' 	=> $frs['optid'],
			'optname' 	=> $this->rock->jm->base64encode($frs['optname'])
		));
		
		$result 	= c('curl')->postcurl($url, array('file' => new CURLFile($path, '', $frs['filename'])), 1);
		if(!$result)return returnerror('errors');
		
		if(substr($result,0,1)!='{')return returnerror($result);
		$data = json_decode($result, true);
		
		$filenum 	= arrvalue($data, 'filenum');
		$thumbpath 	= arrvalue($data, 'thumbpath');
		if($filenum){
			$guar['filenum'] 	= $filenum;
			if($fobj->isimg($frs['fileext']))$guar['thumbplat'] = $thumbpath;
			$fobj->update($guar,$fileid);
			unlink($path);
			$path = ROOT_PATH.'/'.$frs['thumbpath'];
			if($path && file_exists($path))unlink($path);
		}
		return returnsuccess();
	}
}