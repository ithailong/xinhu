<?php
//文档模块word
class flow_wordClassModel extends flowModel
{


	
	public function floweditoffice($frs, $ofrs)
	{
		$this->update("`optdt`='".$this->rock->now."'", $this->id);
	}
	
}