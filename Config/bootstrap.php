<?php

/**
 * IVA (VAT) constant
 * may use date comparison for programmed increase
 * @var number
 */
define('IVA',0.21);

/**
 * default price format, printf syntax
 * @var string
 */
define('PRICE_FORMAT','%01.2f');

$active_lang=Configure::read('Config.language');
switch($active_lang){
	case 'eng': $local='en_GB';
	break;
	case 'spa':
	default:	$local='es_ES';
	break;
}
setlocale(LC_TIME,$local);