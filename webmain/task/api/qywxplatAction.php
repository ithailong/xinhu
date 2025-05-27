<?php
/**
*	信呼企业微信回调
*/
class qywxplatClassAction extends apiAction
{
	
	public function initAction()
	{
		$this->display= false;
		
	}
	
	/**
	*	验证的使用回调
	*/
	public function indexAction()
	{
		$huitoken 	= $this->get('huitoken');
		if(!$huitoken)return 'huitoken isempty';
		$mytoekn	= $this->option->getval('qywxplat_huitoken');
		if(md5($mytoekn) != $huitoken)return 'huitoken error';
		
		$postdata 	= $this->getpostdata();
		$calltype 	= '';
		$userid 	= '';
		if($postdata){
			$data 		= json_decode($postdata, true);
			$calltype 	= arrvalue($data, 'calltype');
			//m('log')->addlog('信呼回调', $postdata);
			$userid 	= arrvalue($data, 'userid');
			//$this->rock->debugs($postdata,'qywxcall_'.$calltype.'');
		}
		$where  = "`user`='$userid'";
		
		//激活关注
		if($calltype=='subscribe'){
			m('zqywx_user')->update('`state`=1', $where);
		}
		
		//取消激活
		if($calltype=='unsubscribe'){
			m('zqywx_user')->update('`state`=4', $where);
		}
		
		//删除用户
		if($calltype=='delete_user'){
			m('zqywx_user')->delete($where);
		}
		
		//创建和更新用户
		if($calltype=='create_user' || $calltype=='update_user'){
			c('rockqueue')->push('qywx,qywxplatuserget', array(
				'userid' 	=> $userid,
			));
		}
		
		
		return 'success';
	}
	
	/**
	*	回调处理的
	*/
	public function backAction()
	{
		$huitoken 	= $this->get('huitoken');
		if(!$huitoken)return 'huitoken isempty';
		$mytoekn	= $this->option->getval('wxqyplat_huitoken');
		if(md5($mytoekn) != $huitoken)return 'huitoken error';
		
		$postdata 	= $this->getpostdata();
		$calltype 	= '';
		$userid 	= '';
		if($postdata){
			$data 		= json_decode($postdata, true);
			$calltype 	= arrvalue($data, 'calltype');
			$userid 	= arrvalue($data, 'userid');
		}
		
		$where  = "`userid`='$userid'";
		$obj 	= m('zwxqy_user');
		
		//激活关注
		if($calltype=='subscribe'){
			$obj->update('`state`=1', $where);
		}
		
		//取消激活
		if($calltype=='unsubscribe'){
			$obj->update('`state`=4', $where);
		}
		
		//取消授权
		if($calltype=='authcancel'){
			$obj->delete('1=1');
		}
		
		return 'success';
	}
}