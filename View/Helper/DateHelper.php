<?php
/**
 * Q3Toolbox date operations helper
 *
 * PHP version 5
 *
 * Copyright (c) 2013, Juanvi Vercher
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) 2013, Juanvi Vercher
 * @link          www.artvisual.net
 * @package       Q3Toolbox
 * @subpackage    Q3Toolbox.helpers
 * @since         v 0.5.0 (07-Jul-2013)
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 *
 */
class DateHelper extends Helper
{
	var $name='Date';
	//var $helpers = array('Html','Javascript','Ajax','Session');


	/**
	 * Formats a MySQL date string using the choosen format
	 *
	 * @param string $format default 'normal' localized versions of format strings
	 * @param datetime $fecha default 'Now'
	 */
	function dateFormat($date='Now',$format='normal'){
		switch($format){
			case 'abreviada': $format_string='%d/%m/%Y'; //European date format
				break;
			case 'abbreviated': $format_string='%m/%d/%Y'; //American date format
				break;
			case 'abreviada_hora': $format_string='%d/%m/%Y, %H:%M'; //European date format
				break;
			case 'abbreviated_hour': $format_string='%m/%d/%Y, %H:%M'; //American date format
				break;
			case 'ampliada': $format_string='%e de %B de %Y';
				break;
			case 'extended': $format_string='%e%O %B %Y';
				break;
			case 'extended_am': $format_string='%B %e%O, %Y';
				break;
			case 'ampliada_hora': $format_string='%e de %B de %Y, %H:%M';
				break;
			case 'extended_hour': $format_string='%e%O %B %Y, %H:%M';
				break;
			case 'extended_hour_am': $format_string='%B %e%O, %Y, %H:%M';
				break;
			case 'semana': $format_string='%A, %e de %B de %Y';
				break;
			case 'week': $format_string='%A, %e%O %B %Y';
				break;
			case 'week_am': $format_string='%A, %B %e%O, %Y';
				break;
			case 'semana_hora': $format_string='%A, %d de %B de %Y, %H:%M';
				break;
			case 'week_hour': $format_string='%A, %e%O %B %Y, %H:%M';
				break;
			case 'week_hour_am': $format_string='%A, %B %e%O, %Y, %H:%M';
				break;
			case 'marca':
			case 'timestamp': $format_string='%d/%m/%Y %H:%M:%S';
				break;
			case 'compacta_hora': $format_string='%e %b %Y, %H:%M';
				break;
			case 'time': $format_string="%H:%M";
				break;
			case 'normal':
			default:		$format_string='%e %b %Y';
		}
		/*
		 * ordinal tweak
		 */
		$date_value=strtotime($date);
		$format_string = str_replace('%O', date('S', $date_value), $format_string);

		$date_string=strftime($format_string,$date_value);
		/*
		 * htmlentities ftw
		 */
		return htmlentities($date_string,ENT_COMPAT,mb_detect_encoding($date_string,array('UTF-8','iso-8859-1')));
	}


	/**
	 * @method timeAgo
	 * gets approximated time
	 * @param unknown_type $fecha
	 * @return string
	 */
	function timeAgo($date){
		/*
		 * convenience variables
		 */
		$minute = 60;
		$hour = 60*$minute;
		$day = 24*$hour;
		$month = 30*$day;
		$year = 365*$day;

		$date_timestamp = strtotime($date);
		$now_timestamp = time();
		$difference = $now_timestamp-$date_timestamp;
		if($difference<$minute){
			return __d('q3_toolbox','less than a minute');
		}
		elseif($difference<2*$minute){
			return __d('q3_toolbox','one minute ago');
		}
		elseif($difference<$hour){
			return sprintf(__d('q3_toolbox','%s minutes ago'),floor($difference/$minute));
		}
		elseif($difference<2*$hour){
			return __d('q3_toolbox','one hour ago');
		}
		elseif($difference<$day){
			return sprintf(__d('q3_toolbox','%s hours ago'),floor($difference/$hour));
		}
		elseif($difference<2*$day){
			return __d('q3_toolbox','yesterday');
		}
		elseif($difference<$month){
			return sprintf(__d('q3_toolbox','%s days ago'),floor($difference/$day));
		}
		elseif($difference<2*$month){
			return __d('q3_toolbox','last month');
		}
		elseif($difference<$year){
			return sprintf(__d('q3_toolbox','%s months ago'),floor($difference/$month));;
		}
		elseif($difference<2*$year){
			return __d('q3_toolbox','last year');
		}
		else{
			return sprintf(__d('q3_toolbox','%s years ago'),floor($difference/$year));;
		}
	}

	/**
	 * @method getAge
	 * Calculate the age from a given birth date
	 * @param unknown_type $Birthdate
	 * @return number
	 */
	function getAge($birthdate){
		// Explode the date into meaningful variables
		list($birthYear,$birthMonth,$birthDay) = explode("-", $birthdate);

		// Find the differences
		$yearDiff = date("Y") - $birthYear;
		$monthDiff = date("m") - $birthMonth;
		$dayDiff = date("d") - $birthDay;

		// If the birthday has not occured this year
		if ($dayDiff < 0 || $monthDiff < 0)
		$yearDiff--;

		return $yearDiff;
	}

	/** @method parseSeconds
	 *
	 * @param int $seconds
	 * @param array $options
	 *
	 * formats a numer of seconds as hours:minutes
	 */
	function parseSeconds($seconds=0,$options=array()){
		$hours=$seconds/3600;
		$remains=$seconds%3600;
		$hours=($seconds-$remains)/3600;
		$seconds=$remains%60;
		$minutes=($remains-$seconds)/60;


		if(empty($options['format'])){
			$format='default';
		}
		else{
			$format=$options['format'];
		}
		switch($format){
			case 'default':
				default: $output=sprintf('%sh %sm',$hours,$minutes);
				break;
		}

		return($output);
	}
}//class