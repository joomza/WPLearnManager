<div class="jslm_main-up-wrapper">
<?php 
$msgkey = JSLEARNMANAGERincluder::getJSModel('course')->getMessagekey();
JSLEARNMANAGERmessages::getLayoutMessage($msgkey);
JSLEARNMANAGERbreadcrumbs::getBreadcrumbs();
include_once(JSLEARNMANAGER_PLUGIN_PATH . 'includes/header.php');
if (jslearnmanager::$_error_flag_message == null) {
  function getDataRow($title, $value) {
      	$html = '<div class="jslm_row">
                  	<span class="jslm_left_data">' . $title . '</span>';
                  	if($value != "" && !empty($value)){
                  		$html .= '<span class="jslm_right_data">' . $value . '</span>';
                  	}elseif($value == "" && empty($value)){
                     	$html .= '<span class="jslm_right_data">------</span>';
                  	}
      	$html .= '</div>';
      	return $html;
   	}

   	function additionfields($title,$value,$i){
      $html = '';
      if($i%2 != 0){
        $html = '<div class="jslm_custom_field">
          ';
      }  
        $html .= '<div class="jslm_heading_right">
          <span class="jslm_heading">' . __($title,"learn-manager") . ':</span>';
				if($value != ""){    
		      $html .=	'<span class="jslm_text">' . __($value,"learn-manager") . '</span>';
        }
        $html .= "</div>";
        
      if($i%2 == 0){	
        $html .= '</div>';
      }  
      return $html;
    }

	function checkLinks($name) {
    $print = false;
    $configname = $name;

    $config_array = jslearnmanager::$_data['config'];
    if ($config_array["$configname"] == 1) {
        $print = true;
    }
    return $print;
	}

  function checkFields($name) {
    foreach (jslearnmanager::$_data[2] as $field) {
      $array =  array();
      $array[0] = 0;
      switch ($field->field) {
        case $name:
        if($field->showonlisting == 1){
          $array[0] = 1;
          $array[1] =  $field->fieldtitle;
        }
        return $array;
        break;
      }
    }
    return $array; 
  }
	require('coursedetail_leftside.php');
}else{
  echo jslearnmanager::$_error_flag_message;
}
?>
</div>
