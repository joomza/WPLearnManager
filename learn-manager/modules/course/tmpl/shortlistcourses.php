<div class="jslm_main-up-wrapper">
<?php if (!defined('ABSPATH')) die('Restricted Access');
$msgkey = JSLEARNMANAGERincluder::getJSModel('course')->getMessagekey();
JSLEARNMANAGERmessages::getLayoutMessage($msgkey);
JSLEARNMANAGERbreadcrumbs::getBreadcrumbs();
include_once(JSLEARNMANAGER_PLUGIN_PATH . 'includes/header.php');
function getDataRow($title, $value) {
	$html = '<div class="jslm_custom_field">
              	<span class="jslm_heading">' . $title . ':</span>';
              	if($value != ""){
              		if(strlen($value) > 40){
              			$value = substr($value,0,25).'....';
              		}
              		$html .= '<span class="jslm_text">' . $value . '</span>';
                }else{
                 	$html .= '<span class="jslm_text">------</span>';
              	}
  	$html .= '</div>';
  	return $html;
}
$sortarray = array(
	(object) array('id' => 1, 'text' => __('Category', 'learn-manager')),
	(object) array('id' => 3, 'text' => __('Created', 'learn-manager')),
	(object) array('id' => 5, 'text' => __('Title', 'learn-manager')));
	if(in_array('paidcourse', jslearnmanager::$_active_addons))
    	$sortarray[] = (object) array('id' => 2, 'text' => __('Price', 'learn-manager'));


function checkLinks($name) {
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
?>
<div class="jslm_content_wrapper"> <!-- lower bottom Content -->
	<div class="jslm_content_data">
		<div class="jslm_search_content">
			<div class="jslm_top_title">
				<?php
		            $image1 = JSLEARNMANAGER_PLUGIN_URL."includes/images/sort-1.png";
		            $image2 = JSLEARNMANAGER_PLUGIN_URL."includes/images/sort-2.png";
		            $sorton = 1;
		            jslearnmanager::$_data['sortby'] = 1;
		            if ( jslearnmanager::$_data['sortby'] == 1) {
		                $image = $image1;
		            } else {
		                $image = $image2;
		            }
	            ?>
				<div class="jslm_left_data"><h3 class="jslm_title_heading"><?php echo __("Shortlist Courses","learn-manager"); ?></h3></div>
				<?php if (jslearnmanager::$_error_flag_message == null) { ?>
					<div class="jslm_right_data">
						<span class="jslm_sorting">
							<?php echo wp_kses(JSLEARNMANAGERformfield::select('jslm_sorting', $sortarray, jslearnmanager::$_data['combosort'], '', array('class' => 'jslm_select_box_style jslm_sort_combo', 'onchange' => 'changeCombo();')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
							<a class="jslm_sort_img" href="#" data-image1="<?php echo esc_attr($image1); ?>" data-image2="<?php echo esc_attr($image2); ?>" data-sortby="<?php echo esc_attr(jslearnmanager::$_data['sortby']); ?>"><img alt="<?php echo esc_attr(__("sortby","learn-manager")); ?>" title="<?php echo esc_attr(__("sortby","learn-manager")); ?>" id="jslm_sortingimage" src="<?php echo esc_attr($image); ?>" /></a>
						</span>
					</div>
				<?php } ?>
			</div>
			<?php if (jslearnmanager::$_error_flag_message == null) { ?>
				<div class="jslm_top_search">
					<form name="jslearnmanagerform" id="jslearnmanagerform" method="post" action="<?php echo esc_url(jslearnmanager::makeUrl(array('jslmsmod'=>'course', 'jslmslay'=>'shortlistcourses', 'jslearnmanagerpageid'=>jslearnmanager::getPageid())))?>">
						<div class="jslm_search_box">
			            	<div class="jslm_search_box_left">
								<?php foreach (jslearnmanager::$_data[3] AS $field) {
									switch($field->field){
										case 'title': 	?>
											<div class="jslm_input_search_left">
						                	    <input id="coursetitle" class="jslm_input_search" type="text" name="coursetitle" value="<?php echo esc_attr(jslearnmanager::$_data['filter']['coursetitle']); ?>" id="coursetitle" placeholder="<?php echo esc_html__("Search Course","learn-manager"); ?>">
								            </div>
									<?php break;
										case 'category_id' : ?>
											<div class="jslm_dropdown_wrp">
												<?php echo wp_kses(JSLEARNMANAGERformfield::select('category', JSLEARNMANAGERincluder::getJSModel('category')->getCategoryForCombobox(), jslearnmanager::$_data['filter']['category'], __('Select category', 'learn-manager'), array('class' => 'jslm_input_field jslm_select_style jslm_select_full_width')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
						                    </div>
									<?php break;
										}
								} ?>
							</div>
							<div class="jslm_search_box_right">
			                   	<div class="jslm_search_btn_wrap">
				                  	<button class="jslm_search_button_style jslm_refresh" type="reset" onclick="resetCourseListFrom()"><?php echo esc_attr(__('Reset','learn-manager')); ?></button>
				                   	<button class="jslm_search_button_style jslm_search" type="submit"><?php echo esc_attr(__('Search','learn-manager')); ?></button>
			               		</div>
			               	</div>
			               	<?php echo wp_kses(JSLEARNMANAGERformfield::hidden('sortby', jslearnmanager::$_data['sortby']), JSLEARNMANAGER_ALLOWED_TAGS); ?>
							<?php echo wp_kses(JSLEARNMANAGERformfield::hidden('sorton', jslearnmanager::$_data['sorton']), JSLEARNMANAGER_ALLOWED_TAGS); ?>
							<?php echo wp_kses(JSLEARNMANAGERformfield::hidden('issearchform', isset(jslearnmanager::$_data['issearchform']) ? jslearnmanager::$_data['issearchform'] : 0), JSLEARNMANAGER_ALLOWED_TAGS); ?>
			               	<?php echo wp_kses(JSLEARNMANAGERformfield::hidden('JSLEARNMANAGER_form_search', 'JSLEARNMANAGER_SEARCH'), JSLEARNMANAGER_ALLOWED_TAGS); ?>
			               	<?php echo wp_kses(JSLEARNMANAGERformfield::hidden('jslmslt', 'shortlistcourses'), JSLEARNMANAGER_ALLOWED_TAGS); ?>
			            </div>
					</form>
				</div>
		</div>
		<div class="jslm_data_container">
			<?php
				include_once( 'shortlistcourseslist.php');
			?>
        </div>
        <?php if (isset(jslearnmanager::$_data['shortlist_pagination'])) {
        		echo wp_kses(JSLEARNMANAGERformfield::hidden('JSLEARNMANAGER_form_search', 'JSLEARNMANAGER_SEARCH'), JSLEARNMANAGER_ALLOWED_TAGS);
        		echo '<div class="tablenav" style="text-align: right;"><div class="tablenav-pages">' . esc_html(jslearnmanager::$_data['shortlist_pagination']) . '</div></div>';
            }
        }else{
			echo jslearnmanager::$_error_flag_message;
			echo "</div>";
		}?>
    </div>
</div>
</div>


