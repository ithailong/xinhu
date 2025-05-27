<?php
/**
*	此文件是流程模块【flowmenu.菜单管理】对应控制器接口文件。
*/ 
class mode_flowmenuClassAction extends inputAction{
	
	
	protected function savebefore($table, $arr, $id, $addbo){
		
	}
	
		
	protected function saveafter($table, $arr, $id, $addbo){
		
	}
	
	public $alldata = array();
	protected function storeafter($table, $rows)
	{
		$this->db->update('[Q]menu', '`status`=1' , '`id` in(1,2) and `status`=0');//总有一些人把系统菜单给停用了
		$pid = (int)$this->post('pid','0');
		if($pid>0){
			$this->showgetmenu($rows,0,1,1);
			$rows = $this->alldata;
		}
		
		return array(
			'rows' => $rows
		);
	}
	
	private function showgetmenu($rows,$pid, $oi, $zt)
	{
		$zh = 0;
		foreach($rows as $k=>$rs){
			if($pid==$rs['pid']){
				$zh++;
				$rs['level']	= $oi;
				$zthui			= $rs['status'];
				if($zt==0){
					$rs['ishui']=1;
					$zthui = 0;
				}
				$this->alldata[] 	= $rs;
				$len = count($this->alldata)-1;
				$cd  = $this->showgetmenu($rows,$rs['id'], $oi+1, $zthui);
				$this->alldata[$len]['stotal']=$cd;
			}
		}
		return $zh;
	}
}	
			