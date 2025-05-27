<?php
/**
*	此文件是流程模块【custxiao.销售】对应控制器接口文件。
*/ 
class mode_custpriceClassAction extends inputAction{
	
	private $sssaid;
	protected function savebefore($table, $arr, $id, $addbo){
		$data = $this->getsubtabledata(0);
		if(count($data)==0)return '至少要有一行记录';
		$this->sssaid = '0';
		foreach($data as $k=>$rs){
			$this->sssaid.=','.$rs['aid'].'';
			if(isset($rs['aid']))foreach($data as $k1=>$rs1){
				if($k!=$k1){
					if($rs['aid']==$rs1['aid'])
						return '行'.($k1+1).'的物品已在行'.($k+1).'上填写，不要重复填写';
				}
			}
		}
		
		
		$rows['type'] = '6';//一定要是6，不能去掉
		return array(
			'rows'=>$rows
		);
	}
	
		
	protected function saveafter($table, $arr, $id, $addbo){
		
	}
	
	//读取物品
	public function getgoodsdata()
	{
		return m('goods')->getgoodsdata(2);
	}
	
	//读取我的客户
	public function getmycust()
	{
		$rows = m('crm')->getmycust($this->adminid, $this->rock->arrvalue($this->rs, 'custid'));
		return $rows;
	}
	
}	
			