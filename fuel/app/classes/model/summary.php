<?php

class Model_Summary extends \Model_Crud
{
    protected static $_table_name = 'summaries';
	protected static $_primary_key = 'id';
	protected static $_properties = array(
	    'id',
	    'week',
	    'member',
	    'movingTime',
	    'distance',
		'elevationGain',
		'maximumSpeed',
		'lastUpdated'
	);
	
	protected static $fields = array(
		'movingTime' => array(
			'name' => 'movingTime',
			'unit' => 'seconds',
			'label' => 'Moving Time'
		),
	    'distance' => array(
			'name' => 'distance',
			'unit' => 'meters',
			'outputUnit' => 'miles',
			'conversionFactor' => 0.000621371192,
			'label' => 'Distance'
		),
		'elevationGain' => array(
			'name' => 'elevationGain',
			'unit' => 'meters',
			'outputUnit' => 'feet',
			'conversionFactor' => 3.2808399,
			'label' => 'Elevation Gain'
		),
		'maximumSpeed' => array(
			'name' => 'maximumSpeed',
			'unit' => 'meters per hour',
			'outputUnit' => 'miles per hour',
			'conversionFactor' => 0.000621371192,
			'label' => 'Top Speed'
		),
	);
	
	public static function getStatFields() {
		return self::$fields;
	}
	
	public function get($field) {
		if(self::$fields[$field]['unit'] === "seconds") {
			return gmdate("H:i:s", $this->$field);
		} else {
			return $this->$field * self::$fields[$field]['conversionFactor'];
		}
	}
	
	public function getWithUnits($field) {
		if(self::$fields[$field]['unit'] === "seconds") {
			return gmdate("H:i:s", $this->$field);
		} else {
			return $this->$field * self::$fields[$field]['conversionFactor']." ".self::$fields[$field]['outputUnit'];
		}
	}
	
	public static function getWeek($time) {
		return ($time - ((date("w",$time)*60*60*24) + (date("G",$time)*60*60) + (date("i",$time)*60) + (date("s",$time))));
	}
	
	public function getMemberName() {
		$member = Model_Member::find_one_by('id', $this->member);
		if($member !== null) {
		    return $member->name;
		}
		return FALSE;
	}
	
	public static function getBest($field,$weekOffset = 0) {
		$week = self::getWeek(time() - ($weekOffset * 60 * 60 * 24 * 7));
		$best = self::find(array(
		    'where' => array(
		        'week' => $week
		    ),
		    'order_by' => array(
		        $field => 'desc'
		    ),
		    'limit' => 1
		));
		return $best[0];
	}
	
	public static function forge(array $data = array())
	{
		if(isset($data['week']) && isset($data['member'])) {
			$existing = self::find(array(
			    'where' => array(
			        'week' => $data['week'],
					'member' => $data['member']
			    ),
			    'limit' => 1
			));
			if(count($existing) > 0) {
				$data['id'] = intVal($existing[0]->id);
			}
		}
		$data['lastUpdated'] = time();
		return parent::forge($data);
	}
	
	public function save($validate = true) {
		if(isset($this->id)) {
			$this->is_new(false);
		}
		parent::save($validate);
	}
}

?>