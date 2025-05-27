<?php
class flow_carmreseClassModel extends flowModel
{


	protected function flowbillwhere($uid, $lx)
	{
		$where  = '';
		//$key 	= $this->rock->post('key');
		//$dt 	= $this->rock->post('dt');
		//if($key != '')$where.=" and (`carnum`='$key' or `usename` like '%$key%' or `optname` like '%$key%')";
		//if($dt != '')$where.=" and (`applydt`='$dt' or `startdt` like '$dt%')";
		
		return array(
			'where' => $where,
			'order' => 'optdt desc'
		);
	}
	
	//自定义审核人读取
	protected function flowcheckname($num){
		$sid = '';
		$sna = '';
		//驾驶员审核读取
		if($num=='jias'){
			$sid = $this->rs['jiaid'];
			$sna = $this->rs['jianame'];
		}
		return array($sid, $sna);
	}
	
	//可预定的车辆
	public function getcardata()
	{
		$where	= $this->adminmodel->getcompanywhere(1);
		$rows 	= m('carm')->getall("`ispublic`=1 and `state`=1 ".$where."",'carnum as name,id as value');
		$db 	= m('carmrese');
		//读取车辆最后公里数
		foreach($rows as $k=>$rs){
			$onrs = $db->getone("`status`=1 and `carid`=".$rs['value']." and ifnull(`kmend`,0)<>0", '`kmend`,`kmstart`','`kmend` desc');
			$km   = '';
			if($onrs){
				$km = $onrs['kmend'];
				if(isempt($km))$km = $onrs['kmstart'];
			}
			$rows[$k]['kmstart'] = $km;
		}
		return $rows;
	}
}