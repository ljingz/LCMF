<?php
if(!defined('APP_NAME')) exit('Access Denied');

class TagLibData extends TagLib {
	protected $tags = array(
		'content'=>array(
			'attr'=>'id,column,dataid',
			'level'=>9
		),
		'list'=>array(
			'attr'=>'id,column,enable,recommend,headline,order,limit,chunk',
			'level'=>9
		),
		'link'=>array(
			'attr'=>'data,title,target',
			'level'=>9
		)
	);
	
	public function _content($attr, $content){
		$attr = $this->parseXmlAttr($attr, 'content');
		$attr['column'] = $this->buildParam($attr['column']);
		if(empty($attr['dataid'])){
			$attr['dataid'] = 'null';
		}else{
			$attr['dataid'] = $this->buildParam($attr['dataid']);
		}
		$parseStr = '<?php ';
		$parseStr .= '$__Data=D("Data");';
		$parseStr .= '$'.$attr['id'].'=$__Data->getInfo('.$attr['column'].', '.$attr['dataid'].');';
		$parseStr .= '?>';
		$parseStr .= $content;
		return $parseStr;
	}
	
	public function _list($attr, $content){
		$attr = $this->parseXmlAttr($attr, 'list');
		$attr['column'] = $this->buildParam($attr['column']);
		if(isset($attr['enable'])){
			$options['query']['enable'] = $attr['enable'];
		}else{
			$options['query']['enable'] = '1';
		}
		if(isset($attr['recommend'])){
			$options['query']['recommend'] = $attr['recommend'];
		}
		if(isset($attr['headline'])){
			$options['query']['headline'] = $attr['headline'];
		}
		if(isset($attr['order'])){
			$options['order'] = $attr['order'];
		}
		if(isset($attr['limit'])){
			$options['limit'] = $attr['limit'];
		}
		$parseStr = '<?php ';
		$parseStr .= '$__Data=D("Data");';
		$parseStr .= '$__LIST__=$__Data->getList('.$attr['column'].', '.var_export($options, true).');';
		if($attr['chunk']){
			$parseStr .= '$__LIST__=array_chunk($__LIST__, '.$attr['chunk'].');';
		}
		$parseStr .= 'foreach($__LIST__ as $key=>$'.$attr['id'].'):';
		$parseStr .= '?>';
		$parseStr .= $content;
		$parseStr .= '<?php endforeach;?>';
		return $parseStr;
	}
	
	public function _link($attr, $content){
		$attr = $this->parseXmlAttr($attr, 'link');
		$attr['data'] = $this->buildParam($attr['data']);
		$attr['title'] = $attr['title']?$attr['title']:'title';
		$attr['target'] = $attr['target']?$attr['target']:'blank';
		$parseStr = '<?php ';
		$parseStr .= '$__data='.$attr['data'].';';
		$parseStr .= '?>';
		$parseStr .= '<a href="<?php echo W(\'DataLink\', $__data);?>" title="<?php echo $__data[\''.$attr['title'].'\'];?>" target="_'.$attr['target'].'">';
		$parseStr .= $content;
		$parseStr .= '</a>';
		return $parseStr;
	}
	
	protected function buildParam($param){
		if(0 === strpos($param, ':')){
            return substr($param, 1);
        }
        if(!is_numeric($param)){
			return $this->autoBuildVar($param);
		}
		return $param;
	}
}