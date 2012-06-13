<?php

class Model_Member extends \Model_Crud
{
    protected static $_table_name = 'members';
	protected static $_properties = array(
	    'id',
	    'name'
	);
	
	public static function forge(array $data = array())
	{
		if(isset($data['name'])) {
			$existing = self::find(array(
			    'where' => array(
					'name' => $data['name']
			    ),
			    'limit' => 1
			));
			if(count($existing) > 0) {
				$data['id'] = intVal($existing[0]->id);
			}
		}
		return parent::forge($data);
	}
	
	public function save($validate = true) {
		if(isset($this->id)) {
			$member = self::find_by_pk($this->id);
			if($member !== null) {
				$this->is_new(false);
			}
		}
		parent::save($validate);
	}
}

?>