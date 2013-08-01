<?php
/**
* Calendar Helper for CakePHP
*
* Copyright 2007-2008 John Elliott
* Licensed under The MIT License
* Redistributions of files must retain the above copyright notice.
*
*
* @author John Elliott
* @copyright 2008 John Elliott
* @link http://www.flipflops.org More Information
* @license http://www.opensource.org/licenses/mit-license.php The MIT License
*
* @author Juanvi Vercher
* @copyright 2013 Juanvi Vercher
* @license http://www.opensource.org/licenses/mit-license.php The MIT License
*
*/

class CalendarHelper extends FormHelper
{
	//var $helpers = array('Html','Javascript','Ajax','Session');
	var $model;
	var $field;



/**
 * Creates a selectable datetime widget using the jscalendar.
 * You need to include the jscalendar source in your layout before using this.
 *
 * See http://www.dynarch.com/demos/jscalendar/doc/html/reference.html for specifics.
 *
 * @param string    $fieldname      Name of a field, such as "Modelname/fieldname"
 * @param string    $value          Value, if any
 * @param string    $inputFormat    Dateformat of the associated input element
 * @param string    $displayFormat  Dateformat of the display/select widget
 * @param string    $align          Alignment of the JavaScript date widget
 * @param string    $htmlAttributes Attributes of the display component of the display span element.
 * @param boolean   $return         Whether this method should return a value or output it.  This overrrides AUTO_OUTPUT.
 * @return mixed                    Either string or boolean value, depends on AUTO_OUTPUT and $return.
 */
    function jsdatetime( $fieldName, $value=null, $inputFormat = '%Y-%m-%d %H:%M', $displayFormat = array( 'jscalendar' => '%d-%m-%Y %H:%M', 'php' => '%d-%m-%Y %H:%M' ), $align = 'Br', $htmlAttributes = array(), $return = false )
    {

		//setlocale(LC_TIME, 'es_ES');
        $this->setEntity($fieldName);
		//'php' => 'd \de F \de Y H:i'
        $out = array();

        $this->model=$this->model();
        $this->field=$this->field();
//        if(empty($value)){
//        	$value=date('Y-m-d H:i');
//        }
//
        /* If no date time defined show a sensible message */
        //if ( $value == '0000-00-00 00:00:00' || $value == '0000-00-00' || $value == null || $value == '' ) {
        if ( $value == '0000-00-00 00:00:00' || $value == '0000-00-00') {
        	$value=date('Y-m-d H:i');
        }


        if(!empty($value)){
        	$display_value = strftime( $displayFormat['php'], strtotime($value) );
        }
        else{
        	$display_value=__d('q3_toolbox','no date',true);
        }
//            /* If there is a time the message should reflect that */
//            if ( preg_match( '/:/', $displayFormat['jscalendar'] ) ) {
//                $display_value = 'Click to select date &amp; time';
//            } else {
//                $display_value = 'Click to select date';
//            }
//        } else {
//            /* Format the date/time and display it */
//            $display_value = strftime( $displayFormat['php'], strtotime($value) );
//        }

        /* Render the HTML elements */
//        $out[] = sprintf('<label id="%s-%s-date-display" class="date-field"> %s </label> <input type="hidden" id="%s-%s-date-input" name="data[%s][%s]" value="%s" /> <input class="button" onclick="resetDateElements( \'%s-%s-date-display\', \'%s-%s-date-input\' )" type="button" value="Reset" />',
//            $this->model,
//            $this->field,
//            $display_value,
//            $this->model,
//            $this->field,
//            $this->model,
//            $this->field,
//            $value,
//            $this->model,
//            $this->field,
//            $this->model,
//            $this->field
//        );
		if(isset($htmlAttributes['id'])){
			$html_id=$htmlAttributes['id'];
		}
		else{
			$html_id='cal';
		}

        $out[] = sprintf('<div class="campo"><strong id="%s-%s-%s-date-display" class="date-field" >%s</strong><input type="hidden" id="%s-%s-%s-date-input" name="data[%s][%s]" value="%s" />&nbsp;&nbsp;<button class="small" type="button" id="%s-%s-%s-date-button">'.$this->Html->image('iconos/calendario.png',array('width'=>16,'height'=>16,'alt'=>__d('q3_toolbox','Show calendar',true))).'</button></div>',
        	$html_id,
            $this->model,
            $this->field,
            $display_value,
            $html_id,
            $this->model,
            $this->field,
            $this->model,
            $this->field,
            $value,
            $html_id,
            $this->model,
            $this->field
        );

        /* Render the JavaScript code */
        $out[] = sprintf('
<script type="text/javascript">
Calendar.setup({
    inputField      :   "%s-%s-%s-date-input",                                // ID of the input field
    ifFormat        :   "%s",                                              // Format of the input field (even if hidden, this format will be honored)
    displayArea     :   "%s-%s-%s-date-display",                              // ID of the span where the date is to be shown
    button       	:   "%s-%s-%s-date-button",    // Button to trigger display
    daFormat        :   "%s",                                           // Format of the displayed date
    align           :   "%s",                                           // Alignment (defaults to "Bl")
    singleClick     :   true,
    weekNumbers     :   false,
    showsTime       :   %s
});
</script>',
        	$html_id,
            $this->model,
            $this->field,
            $inputFormat,
            $html_id,
            $this->model,
            $this->field,
            $html_id,
            $this->model,
            $this->field,
            $displayFormat['jscalendar'],
            $align,
            preg_match( '/:/', $displayFormat['jscalendar'] ) == true ? 'true' : 'false'
        );

        $out = join( '', $out );
        return $this->output( $out ? $out : null, $return );
    }

 /**
* Generates a Calendar for the specified by the month and year params and populates it with the content of the data array
*
* @param $year string
* @param $month string
* @param $data array
* @param $base_url
* @return string - HTML code to display calendar in view
*
*/

    function month($year = '', $month = '', $data = '', $base_url ='',$options=array())	{
    	$str = '';
    	if(!isset($options['language'])){
    		$language='spa';
    	}
    	else{
    		$language=$options['language'];
    	}

		switch($language){
    		case 'eng':
    					$month_list = array('january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december');
    					$day_list = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');
    					break;
    		case 'spa':
    		default:
    					$month_list = array('enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre');
    					$day_list = array('Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab', 'Dom');
    					break;
    	}
    	$day = 1;
    	$today = 0;

    	if($year == '' || $month == '') { // just use current yeear & month
    		$year = date('Y');
    		$month_num = date('n');

    	}
    	else{
    		$month_num=$month;
    	}

    	$month=$month_list[$month_num-1];
    	/*
    	 $flag = 0;

    	 for($i = 0; $i < 12; $i++) {
    	 if(strtolower($month) == $month_list[$i]) {
    	 if(intval($year) != 0) {
    	 $flag = 1;
    	 $month_num = $i + 1;
    	 break;
    	 }
    	 }
    	 }


    	 if($flag == 0) {
    	 $year = date('Y');
    	 $month = date('F');
    	 $month_num = date('m');
    	 }
    	 */

    	$next= isset($options['next']) && $options['next'];
    	$show_events= isset($options['show_events']) && $options['show_events'];

		if(isset($options['function'])){
			$function=$options['function'];
		}
		else{
			$function=false;
		}


    	if($next):

    	$next_year = $year;
    	$prev_year = $year;

    	$next_month = intval($month_num) + 1;
    	$prev_month = intval($month_num) - 1;

    	if($next_month == 13) {
    		$next_month = 'january';
    		$next_year = intval($year) + 1;
    	} else {
    		$next_month = $month_list[$next_month -1];
    	}

    	if($prev_month == 0) {
    		$prev_month = 'december';
    		$prev_year = intval($year) - 1;
    	} else {
    		$prev_month = $month_list[$prev_month - 1];
    	}

    	endif;
    	if($year == date('Y') && ($month_num == date('n'))) {
    		// set the flag that shows todays date but only in the current month - not past or future...
    		$today = date('j');
    	}

    	$days_in_month = date("t", mktime(0, 0, 0, $month_num, 1, $year));

    	$first_day_in_month = date('w', mktime(0,0,0, $month_num, 1, $year));
    	if($first_day_in_month==0){
    		$first_day_in_month=6;
    	}
    	else{
    		$first_day_in_month--;
    	}

    	$str .= '<table class="calendar">';

    	$str .= '<thead>';

    	$str .= '<tr>';
    	if($next):
    		$str .= '<th class="cell-prev">'.$this->Html->link(__d('q3_toolbox','prev', true), array($prev_year, $prev_month)).'</th><th colspan="5">';
    	else:
    		$str.='<th colspan="7">';
    	endif;
    	$str .= ucfirst($month) . ' ' . $year;
    	if($next):
    		$str .= '</th><th class="cell-next">'.$this->Html->link(__d('q3_toolbox','next', true), array($next_year, $next_month));
    	endif;


    	$str .= '</th></tr>';

    	$str .= '<tr>';

    	for($i = 0; $i < 7;$i++) {
    		$str .= '<th class="cell-header">' . $day_list[$i] . '</th>';
    	}

    	$str .= '</tr>';

    	$str .= '</thead>';

    	$str .= '<tbody>';

    	/*
    	 * Esto evita bucles infinitos
    	 */
    	$max=14;

    	while($day <= $days_in_month && $max>0) {

    		$str .= '<tr>';

    		for($i = 0; $i < 7; $i ++) {

    			if($show_events){
    				$cell = '&nbsp;';

    				if(isset($data[$day])) {
    					$cell = $data[$day];
    				}
    			}



    			$class = '';

    			if($i > 4) {
    				$class = ' class="cell-weekend';
    			}

    			if($day == $today) {
    				$class = ' class="cell-today';
    			}

    			//$first_day_in_month = strtolower($first_day_in_month);
    			if((($first_day_in_month) == $i || $day > 1) && ($day <= $days_in_month)) {

    				$clase_temporada='';
    				$fecha_procesada=sprintf('%04d-%02d-%02d',$year,$month_num,$day);

    				if(isset($options['temporadas'])):



					//debug($fecha_procesada);
					/*
					 * Si quitamos el if (empty invertiremos la prioridad de las temporadas
					 */

						foreach($options['temporadas'] as $temporada):
							if(empty($clase_temporada)){
								if(($fecha_procesada>=$temporada['Temporada']['inicio']) && ($fecha_procesada<=$temporada['Temporada']['fin'])){
									$clase_temporada='temporada_color_'.$temporada['Temporada']['color'];
								}
							}
						endforeach;
					endif;

					if(isset($options['habitacion_limites'])):
						if(isset($options['cantidad_por_defecto'])){
							$cantidad_por_defecto=$options['cantidad_por_defecto'];
						}
						else{
							$cantidad_por_defecto=0;
						}
					$cantidad_habitacion=false;


					//debug($fecha_procesada);
					/*
					 * Si quitamos el if (empty invertiremos la prioridad de las temporadas
					 */

						foreach($options['habitacion_limites'] as $habitacion_limite):
							//if(!($cantidad_habitacion)){
								if(($fecha_procesada>=$habitacion_limite[$options['habitacion_limites_modelo']]['inicio']) && ($fecha_procesada<=$habitacion_limite[$options['habitacion_limites_modelo']]['fin'])){
									$cantidad_habitacion=$habitacion_limite[$options['habitacion_limites_modelo']]['cantidad'];

								}
							//}
						endforeach;
					endif;

					if(isset($options['bonificaciones'])):
						if(isset($options['bonificacion_por_defecto'])){
							$bonificacion_por_defecto=$options['bonificacion_por_defecto'];
						}
						else{
							$bonificacion_por_defecto='&nbsp;';
						}
					$bonificacion_dias=false;
					$bonificacion_tipo=false;


					//debug($fecha_procesada);
					/*
					 * Si quitamos el if (empty invertiremos la prioridad de las temporadas
					 */

						foreach($options['bonificaciones'] as $bonificacion):
							//if(!($cantidad_habitacion)){
								if(($fecha_procesada>=$bonificacion[$options['bonificacion_modelo']]['inicio']) && ($fecha_procesada<=$bonificacion[$options['bonificacion_modelo']]['fin'])){
									$bonificacion_dias=$bonificacion[$options['bonificacion_modelo']]['dias'];
									$bonificacion_tipo=$bonificacion[$options['bonificacion_modelo']]['tipo'];
								}elseif($bonificacion[$options['bonificacion_modelo']]['completo']==1){
	$bonificacion_por_defecto=$bonificacion[$options['bonificacion_modelo']]['dias'];
								}
							//}
						endforeach;
					endif;


					if(isset($options['disponibilidad'][$fecha_procesada])){
						if($options['disponibilidad'][$fecha_procesada]){
							$clase_temporada='disponible';
						}
						else{
							$clase_temporada='no_disponible';
						}
					}




					if(empty($class)){
						if(!empty($clase_temporada)){
							$class=' class="'.$clase_temporada.'" ';
						}
					}
					else{
						if(!empty($clase_temporada)){
							$class.=' '.$clase_temporada.'" ';
						}
						else{
							$class.='" ';
						}
					}

    				if($function!=false){
						$fecha_valor=sprintf('%02d/%02d/%04d',$day,$month_num,$year);

						if(isset($options['fecha_inicio'])&& $options['fecha_inicio']==$fecha_procesada){
							$clase_enlace='class="seleccionado_inicio" ';
						}
						elseif(isset($options['fecha_fin'])&& $options['fecha_fin']==$fecha_procesada){
							$clase_enlace='class="seleccionado_fin" ';
						}
						else{
							$clase_enlace='';
						}
	    				$str .= '<td ' . $class. '><div class="cell-number"><a '.$clase_enlace.'href="#" onclick="'.$function.'(\''.$fecha_valor.'\',\''.$fecha_procesada.'\',this);return false;">' . $day . '</a></div>';
    				}
    				else{
    					$str .= '<td ' . $class. '><div class="cell-number">' . $day . '</div>';
    				}

    				if($show_events){
    					$str.='<div class="cell-data">' . $cell . '</div>';
    				}
    				if(isset($cantidad_habitacion)){
    					if($cantidad_habitacion!==false){
    						$str.='<div class="cell-data disponibilidad">' . $cantidad_habitacion . '</div>';

    					}
    					else{
    						$str.='<div class="cell-data disponibilidad_defecto">' . $cantidad_por_defecto . '</div>';
    					}
    				}

    			    if(isset($bonificacion_dias)){

    					if($bonificacion_dias!==false){
    						$clase_tipo='bonificacion_tipo_'.$bonificacion_tipo;
    						$str.='<div class="cell-data bonificacion '.$clase_tipo.'">' . $bonificacion_dias . '</div>';

    					}
    					else{
    						$str.='<div class="cell-data bonificacion_defecto">' . $bonificacion_por_defecto . '</div>';
    					}
    				}
    				$str.='</td>';
    				$day++;
    			} else {
    				if(!empty($class)){
    					$class.='" ';
					}
    				$str .= '<td ' . $class . '>&nbsp;</td>';
    				$max--;
    			}

    		}
    		$str .= '</tr>';
    	}

    	$str .= '</tbody>';

    	$str .= '</table>';
    	if($max<=0){
    		$str .='<p>Calculo abortado, error en bucle</p>';
    		debug($days_in_month);
    		debug($day);
    		debug($first_day_in_month);
    	}
    	return $str;
    }




}//class