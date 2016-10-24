<?php
class ModelEbayChannelDateUtil extends Model {
	
	function minusDays($date, $days) {
		if($days > 0) {
			return date('Y-m-d H:i:s',strtotime(str_replace('-', '/', $date) . "-".$days." days"));
		} else {
			return $date;
		}
	} 	
	
	function plusDays($date, $days) {
		if($days > 0) {
			return date('Y-m-d H:i:s',strtotime(str_replace('-', '/', $date) . "+".$days." days"));
		} else {
			return $date;
		}
	}

	function toEbayFormat($date) {
		return date('Y-m-d\TH:i:s\Z', strtotime($date));
	}
	
	function now() {
		return date("Y-m-d H:i:s");
	}
	
	function isGrater($dateTime1, $dateTime2) {
		$dateTimestamp1 = strtotime($dateTime1->format('Y-m-d H:i:s'));
		$dateTimestamp2 = strtotime($dateTime2->format('Y-m-d H:i:s'));
		
		return $dateTimestamp1 >= $dateTimestamp2;
	}
	
	function toTimeLeft($startTime, $endTime) {
		if(empty($startTime) || empty($endTime)) {
			return '-';
		}
		$startTime=strtotime($startTime);
		$endTime=strtotime($endTime);
		$diff= $endTime - $startTime;
		if($diff > 0) {
			$days=floor($diff/(60*60*24));
			$hours=round(($diff-$days*60*60*24)/(60*60));
			return $days. "d " . $hours . "h";
		} else {
			return '-';
		}
	}
}
?>