<?php

class Controller_Leaderboard extends Controller {
	
	public function action_index() {
		foreach(Model_Summary::getStatFields() as $field) {
			$best = Model_Summary::getBest($field['name']);
			echo "Total ".$field['label']." = ";
			echo $best->getMemberName();
			echo " ".$best->getWithUnits($field['name']);
			echo "<br/>";
		}
		return "";
	}
	
	public function action_fetch() {
		$data = json_decode(file_get_contents('http://app.strava.com/api/v1/clubs/462/members'));
		
		foreach($data->members as $member) {
			
			$m = Model_Member::forge(array(
			    'id' => $member->id,
			    'name' => $member->name
			));
			$m->save();
			
			$rides = json_decode(file_get_contents('http://app.strava.com/api/v1/rides?athleteId='.$member->id));
			
			$data = array();
			foreach($rides->rides as $ride) {
				$ride = json_decode(file_get_contents('http://app.strava.com/api/v1/rides/'.$ride->id));

				$ride = $ride->ride;
				$week = Model_Summary::getWeek(strtotime($ride->startDateLocal));
				
				if(isset($data[$week])) {
					$data[$week]['movingTime'] += $ride->movingTime;
					$data[$week]['distance'] += $ride->distance;
					$data[$week]['elevationGain'] += $ride->elevationGain;
					if($data[$week]['maximumSpeed'] < $ride->maximumSpeed) {
						$data[$week]['maximumSpeed'] = $ride->maximumSpeed;
					}
				} else {
					$data[$week] = array(
						'movingTime' => $ride->movingTime,
			            'distance' => $ride->distance,
			            'maximumSpeed' => $ride->maximumSpeed,
			            'elevationGain' => $ride->elevationGain
					);
				}
			}
			
			foreach($data as $week => $s) {
				$summary = Model_Summary::forge(array_merge(array(
				    'week' => $week,
				    'member' => $member->id
				),$s));
				$summary->save();
			}
		}
		
		return "Updated";
	}
}