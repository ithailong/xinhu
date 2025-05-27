<?php
class flow_custpriceClassModel extends flowModel
{
	public $minwidth	= 600;//子表最小宽

	public $goodsobj;
	
	public function initModel()
	{
		$this->goodsobj 	= m('goods');
	}
	
	public function flowxiangfields(&$fields)
	{
		$fields['base_name'] 	= '报价人';
		$fields['base_deptname'] = '报价人部门';
		$fields['base_sericnum'] = '报价单号';
		return $fields;
	}
	
	public function flowsearchfields()
	{
		$arr[] = array('name'=>'报价人...','fields'=>'uid');
		return $arr;
	}
	
	
	
	//子表数据替换处理
	protected function flowsubdata($rows, $lx=0){
		$db = m('goods');
		foreach($rows as $k=>$rs){
			$one = $db->getone($rs['aid']);
			if($one){
				$name = $one['name'];
				if(!isempt($one['xinghao']))$name.='('.$one['xinghao'].')';
				if($lx==1)$rows[$k]['aid'] = $name; //1展示时
				$rows[$k]['temp_aid'] = $name;
			}
		}
		return $rows;
	}
	
	//$lx,0默认,1详情展示，2列表显示
	public function flowrsreplace($rs, $lx=0)
	{
		//读取物品
		if($lx==2){
			$rs['wupinlist'] = $this->goodsobj->getgoodninfo($rs['id'], 1);
		}
		return $rs;
	}

}