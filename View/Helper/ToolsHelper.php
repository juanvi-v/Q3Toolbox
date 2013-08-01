<?php
/**
 * Q3Toolbox misc tools helper
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
class ToolsHelper extends Helper
{
	var $name='Tools';
	//var $helpers = array('Html','Javascript','Ajax','Session');

	/**
	 * @method cleanText
	 * convert wild text to htmlentities with encoding detection
	 * @param string $the_string
	 * @return string
	 */
	function clean_text($the_string){
        $text=htmlentities($the_string,ENT_COMPAT,mb_detect_encoding($the_string,array('UTF-8','iso-8859-1')));
		return $text;
    }

	/**
	 * @method usernameFormat
	 * convenience method to beautify user names
	 * @param string $nick
	 * @return string
	 */
    function usernameFormat($nick=null){
    	//return mb_convert_case(str_replace('-',' ',$nick),MB_CASE_TITLE);
    	return ucwords(str_replace('-',' ',$nick));
    }

    /**
     * @method title2Url
     * cleans text for use in url
     * may be deprecated to core CakePHP slug functions
     * @param varchar $title
     * @param int $size
     * @return varchar
     */
	function title2Url($title){
		$from=array(
			'á','é','í','ó','ú','ý',
			'Á','É','Í','Ó','Ú','Ý',
			'à','è','ì','ò','ù',
			'À','È','Ì','Ò','Ù',
			'â','ê','î','ô','û',
			'Â','Ê','Î','Ô','Û',
			'à','è','ì','ò','ù',
			'ñ','Ñ','ç','Ç',
			'%','+','’','.',',','\\','/','\'','"',':','¿','¡',' '
		);

		$to=array(
			'a','e','i','o','u','y',
			'a','e','i','o','u','y',
			'a','e','i','o','u',
			'a','e','i','o','u',
			'a','e','i','o','u',
			'a','e','i','o','u',
			'a','e','i','o','u',
			'n','n','c','c',
			'','','','','','','','','','','','-'
		);
		$title = strtolower(str_replace($from,$to,$title));
		return urlencode($title);
	}




	/**
	 * @method cutText
	 * cut text to a given size
	 * @param varchar $text
	 * @param int $size
	 * @param varchar $link
	 * @return varchar
	 */
	function cutText($text, $size, $link = '...<!-- Cut text -->'){
		$end_link = substr($link, strlen($link)-15);
		$wrap = wordwrap($text, $size, $link);
		$pos = strpos($wrap, $end_link)+15;
		if($pos == 15){
			$new_text = $text;
		}
		else{
			$new_text = substr($wrap, 0, $pos);
		}
		return nl2br($new_text);
	}

	/**
	 * @method IVA
	 * sums VAT (IVA) to a quantity
	 * rounded to two decimal positions
	 * @param number $amount
	 * @return number
	 */
    function IVA($amount){
    	if(defined('IVA')){
    		$iva=IVA;
    	}
    	else{
    		$iva=0.21;
    	}
        return round(($amount*(1+$iva))*100.0)/100.0;
    }

    /**
     * @method couta_iva
     * gets iva amount from a given price
     * rounded to two decimal positions
     * @param number $amount
     * @return number
     */
    function cuotaIVA($amount){
    	if(defined('IVA')){
    		$iva=IVA;
    	}
    	else{
    		$iva=0.21;
    	}
        return round(($amount*$iva)*100.0)/100.0;
    }


    /**
     * @method priceFormat
     * formats prices, with given options.
     * useful to change sitewide price formatting
     * @param unknown_type $price
     * @param unknown_type $options
     * @return Ambigous <string, unknown>
     */
    function priceFormat($price,$options=array()){
    	/*
    	 * These constants should be defined in bootstrap;
    	*/
    	if(defined('IVA')){
    		$iva=IVA;
    	}
    	else{
    		$iva=0.21;
    	}
    	if(defined('PRICE_FORMAT')){
    		$price_format=PRICE_FORMAT;
    	}
    	else{
    		$price_format='%01.2f';
    	}




    	$default_options=array(	'price_format'=>$price_format,
    			'decimal_separator'=>'.',
    			'thousands_separator'=>',',
    			'decimal_positions'=>2,
    			'currency'=>'&nbsp;&euro;',
    			'currency_position'=>'after'
    	);

    	foreach($options_procesar as $option=>$default_value){
    		if(isset($options[$opcion])){
    			$$option=$options[$option];
    		}
    		else{
    			$$option=$default_value;
    		}

    	}
    	/*
    	 * price includes VAT (IVA) and shoud be substracted.
    	 */
    	if(!empty($options['without_iva'])){
    		$price*=1.0/(1.0+$iva);
    	}

    	$formated_price=number_format(sprintf($price_format,$price),$decimal_positions,$decimal_separator,$thousands_separator);

    	if(!empty($currency)){
    		if($currency_position='before'){
    			$formated_price=$currency.$formated_price;
    		}
    		else{
    			$formated_price.=$currency;
    		}
    	}

    	return $formated_price;
    }

    /**
     * @method clean_language
     * cleans tags and gest language from wordpress plugin qtraslate
     * @param varchar $text
     * @param varchar $lang

     * @return varchar
     */
	function cleanLanguage($text,$lang=null){
		$detect='<!--:-->';
		if(strpos($text,$detect)===false):
			return $text;
		else:
			if(is_null($lang)){
                                if(!$this->Session->check('Config.language')){
                                        $lang='eng';
                                }
                                else{
                                        $lang=$this->Session->read('Config.language');
                                }
                        }
		switch($lang){
			case 'en':
			case 'eng':
					$lang_tag='en';
					break;
			case 'es':
			case 'spa':
			case 'esn':
			default:
					$lang_tag='es';
					break;

		}
		if(preg_match('/<!--:'.$lang_tag.'-->(.*)<!--:-->/msU',$text,$clean)!=1){
			preg_match('/<!--:..-->(.*)<!--:-->/msU',$text,$clean);
		}
	//	if(preg_match('/.*<!--:'.$lang_tag.'-->([^<]*)<!--:-->.*/msU',$text,$clean)!=1){
	//		preg_match('/<!--:..-->([^<]*)<!--:-->/msU',$text,$clean);
	//	}
		if(isset($clean[1])){
			return($clean[1]);
		}
		else{
			return('no->'.$text);
		}
		endif;
	}

/**
 * @method hex2RGB
 * @brief Convert a hexa decimal color code to its RGB equivalent
 *
 * http://es.php.net/manual/en/function.hexdec.php#99478
 *
 * @param string $hexStr (hexadecimal color value)
 * @param boolean $returnAsString (if set true, returns the value separated by the separator character. Otherwise returns associative array)
 * @param string $seperator (to separate RGB values. Applicable only if second parameter is true.)
 * @return array or string (depending on second parameter. Returns False if invalid hex color value)
 */
function hex2RGB($hexStr, $returnAsString = false, $seperator = ',') {
    $hexStr = preg_replace("/[^0-9A-Fa-f]/", '', $hexStr); // Gets a proper hex string
    $rgbArray = array();
    if (strlen($hexStr) == 6) { //If a proper hex code, convert using bitwise operation. No overhead... faster
        $colorVal = hexdec($hexStr);
        $rgbArray['R'] = 0xFF & ($colorVal >> 0x10);
        $rgbArray['G'] = 0xFF & ($colorVal >> 0x8);
        $rgbArray['B'] = 0xFF & $colorVal;
    } elseif (strlen($hexStr) == 3) { //if shorthand notation, need some string manipulations
        $rgbArray['R'] = hexdec(str_repeat(substr($hexStr, 0, 1), 2));
        $rgbArray['G'] = hexdec(str_repeat(substr($hexStr, 1, 1), 2));
        $rgbArray['B'] = hexdec(str_repeat(substr($hexStr, 2, 1), 2));
    } else {
        return false; //Invalid hex color code
    }
    return $returnAsString ? implode($seperator, $rgbArray) : $rgbArray; // returns the rgb string or the associative array
}

	/**
	 * @method gravatar
	 * inserts gravatar image tag for an email
	 * @param varchar $email
	 * @param int $size
	 * @param varchar $default
	 * @param varchar $alt
	 */
	function gravatar($email=null,$size=48,$default='/koala/ph_avatar.jpg',$alt='',$class='gravatar'){
		if(!empty($default)){
			if(!empty($email)){
				$default_url=$this->Html->url('/img/'.$default,true);
				$grav_url='http://www.gravatar.com/avatar/'.md5(strtolower(trim($email))).'?d='.urlencode($default_url).'&s='.$size;
			}
			else{
				$grav_url=$default;
			}
			return $this->Html->image($grav_url,array('width'=>$size,'height'=>$size,'alt'=>$alt,'class'=>$class));

		}
		else{
			return '';
		}

	}

	/**
	 * @method wpautoop
	 * This is a html format function for Wordpress Posts
	 * @param string $pee
	 * @param boolean $br
	 * @return string|mixed
	 */
	function wpautop($pee, $br = true) {
		$pre_tags = array();

		if ( trim($pee) === '' )
			return '';

		$pee = $pee . "\n"; // just to make things a little easier, pad the end

		if ( strpos($pee, '<pre') !== false ) {
			$pee_parts = explode( '</pre>', $pee );
			$last_pee = array_pop($pee_parts);
			$pee = '';
			$i = 0;

			foreach ( $pee_parts as $pee_part ) {
				$start = strpos($pee_part, '<pre');

				// Malformed html?
				if ( $start === false ) {
					$pee .= $pee_part;
					continue;
				}

				$name = "<pre wp-pre-tag-$i></pre>";
				$pre_tags[$name] = substr( $pee_part, $start ) . '</pre>';

				$pee .= substr( $pee_part, 0, $start ) . $name;
				$i++;
			}

			$pee .= $last_pee;
		}

		$pee = preg_replace('|<br />\s*<br />|', "\n\n", $pee);
		// Space things out a little
		$allblocks = '(?:table|thead|tfoot|caption|col|colgroup|tbody|tr|td|th|div|dl|dd|dt|ul|ol|li|pre|select|option|form|map|area|blockquote|address|math|style|p|h[1-6]|hr|fieldset|noscript|legend|section|article|aside|hgroup|header|footer|nav|figure|figcaption|details|menu|summary)';
		$pee = preg_replace('!(<' . $allblocks . '[^>]*>)!', "\n$1", $pee);
		$pee = preg_replace('!(</' . $allblocks . '>)!', "$1\n\n", $pee);
		$pee = str_replace(array("\r\n", "\r"), "\n", $pee); // cross-platform newlines
		if ( strpos($pee, '<object') !== false ) {
			$pee = preg_replace('|\s*<param([^>]*)>\s*|', "<param$1>", $pee); // no pee inside object/embed
			$pee = preg_replace('|\s*</embed>\s*|', '</embed>', $pee);
		}
		$pee = preg_replace("/\n\n+/", "\n\n", $pee); // take care of duplicates
		// make paragraphs, including one at the end
		$pees = preg_split('/\n\s*\n/', $pee, -1, PREG_SPLIT_NO_EMPTY);
		$pee = '';
		foreach ( $pees as $tinkle )
			$pee .= '<p>' . trim($tinkle, "\n") . "</p>\n";
		$pee = preg_replace('|<p>\s*</p>|', '', $pee); // under certain strange conditions it could create a P of entirely whitespace
		$pee = preg_replace('!<p>([^<]+)</(div|address|form)>!', "<p>$1</p></$2>", $pee);
		$pee = preg_replace('!<p>\s*(</?' . $allblocks . '[^>]*>)\s*</p>!', "$1", $pee); // don't pee all over a tag
		$pee = preg_replace("|<p>(<li.+?)</p>|", "$1", $pee); // problem with nested lists
		$pee = preg_replace('|<p><blockquote([^>]*)>|i', "<blockquote$1><p>", $pee);
		$pee = str_replace('</blockquote></p>', '</p></blockquote>', $pee);
		$pee = preg_replace('!<p>\s*(</?' . $allblocks . '[^>]*>)!', "$1", $pee);
		$pee = preg_replace('!(</?' . $allblocks . '[^>]*>)\s*</p>!', "$1", $pee);
		if ( $br ) {
			$pee = preg_replace_callback('/<(script|style).*?<\/\\1>/s', array(get_class($this),'_autop_newline_preservation_helper'), $pee);
			$pee = preg_replace('|(?<!<br />)\s*\n|', "<br />\n", $pee); // optionally make line breaks
			$pee = str_replace('<WPPreserveNewline />', "\n", $pee);
		}
		$pee = preg_replace('!(</?' . $allblocks . '[^>]*>)\s*<br />!', "$1", $pee);
		$pee = preg_replace('!<br />(\s*</?(?:p|li|div|dl|dd|dt|th|pre|td|ul|ol)[^>]*>)!', '$1', $pee);
		$pee = preg_replace( "|\n</p>$|", '</p>', $pee );

		if ( !empty($pre_tags) )
			$pee = str_replace(array_keys($pre_tags), array_values($pre_tags), $pee);

		return $pee;
	}
	/**
	 * @method _autoop_newline_preservation_helper
	 * auxiliar function to wpautoop
	 * @param array $matches
	 */
	private static function _autop_newline_preservation_helper( $matches ) {
	        return str_replace("\n", "<WPPreserveNewline />", $matches[0]);
	}


}//class
