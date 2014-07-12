<?php
if(!defined('APP_NAME')) exit('Access Denied');

class TagLibColumn extends TagLib {
	protected $tags = array(
		'childs'=>array(
			'attr'=>'id,column',
			'level'=>9
		),
		'siblings'=>array(
			'attr'=>'id,column',
			'level'=>9
		),
		'link'=>array(
			'attr'=>'column,target',
			'level'=>9
		)
	);
	
	public function _childs($attr, $content){
		$attr = $this->parseXmlAttr($attr, 'childs');
		$attr['column'] = $this->buildColumnId($attr['column']);
		$parseStr = '<?php ';
		$parseStr .= '$__Column=D("Column");';
		$parseStr .= '$__LIST__=$__Column->getChilds('.$attr['column'].');';
		$parseStr .= 'foreach($__LIST__ as $key=>$'.$attr['id'].'):';
		$parseStr .= '?>';
		$parseStr .= $content;
		$parseStr .= '<?php endforeach;?>';
		return $parseStr;
	}
	
	public function _siblings($attr, $content){
		$attr = $this->parseXmlAttr($attr, 'siblings');
		$attr['column'] = $this->buildColumnId($attr['column']);
		$parseStr = '<?php ';
		$parseStr .= '$__Column=D("Column");';
		$parseStr .= '$__columns=$__Column->getMap();';
		$parseStr .= 'if(empty($__columns['.$attr['column'].']["parentid"])):';
		$parseStr .= '$__LIST__=array($__columns['.$attr['column'].']);';
		$parseStr .= 'else:';
		$parseStr .= '$__LIST__=$__Column->getSiblings('.$attr['column'].');';
		$parseStr .= 'endif;';
		$parseStr .= 'foreach($__LIST__ as $key=>$'.$attr['id'].'):';
		$parseStr .= '?>';
		$parseStr .= $content;
		$parseStr .= '<?php endforeach;?>';
		return $parseStr;
	}
	
	public function _link($attr, $content){
		$attr = $this->parseXmlAttr($attr, 'link');
		$attr['column'] = $this->buildColumnId($attr['column']);
		$attr['target'] = $attr['target']?$attr['target']:'self';
		$parseStr .= '<a href="<?php echo W(\'ColumnLink\', '.$attr['column'].');?>" target="_'.$attr['target'].'">';
		$parseStr .= $content;
		$parseStr .= '</a>';
		return $parseStr;
	}
	
	protected function buildColumnId($column){
		if(0 === strpos($column, ':')){
            return substr($column, 1);
        }
        if(!is_numeric($column)){
			return $this->autoBuildVar($column);
		}
		return $column;
	}
}