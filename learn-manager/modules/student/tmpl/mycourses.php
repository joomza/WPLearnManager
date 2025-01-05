<div class="jslm_main-up-wrapper">
<?php if (!defined('ABSPATH')) die('Restricted Access');
$msgkey = JSLEARNMANAGERincluder::getJSModel('student')->getMessagekey();
JSLEARNMANAGERmessages::getLayoutMessage($msgkey);
$msgkey = JSLEARNMANAGERincluder::getJSModel('course')->getMessagekey();
JSLEARNMANAGERmessages::getLayoutMessage($msgkey);
JSLEARNMANAGERbreadcrumbs::getBreadcrumbs();
include_once(JSLEARNMANAGER_PLUGIN_PATH . 'includes/header.php');
function getDataRow($title, $value) {
	$html = '<div class="jslm_custom_field">
              	<span class="jslm_heading">' . $title . ':</span>';
          		if(strlen($value) > 40){
          			$value = substr($value,0,25).'....';
          		}
          		$html .= '<span class="jslm_text">' . $value . '</span>';
    $html .= '</div>';
  	return $html;
}

?>
<div class="jslm_content_wrapper"> <!-- lower bottom Content -->
	<div class="jslm_content_data">
		<div class="jslm_search_content">
			<div class="jslm_top_title">
				<div class="jslm_left_data"><h3 class="jslm_title_heading"><?php echo __("My Courses","learn-manager"); ?></h3></div>
			</div>
			<?php if(jslearnmanager::$_error_flag_message == null){ ?>
				<div class="jslm_top_search">
					<form name="jslearnmanagerform" id="jslearnmanagerform" method="post" action="<?php echo esc_url(jslearnmanager::makeUrl(array('jslmsmod'=>'instructor', 'jslmslay'=>'mycourses')))?>">
						<div class="jslm_search_box">
			            	<div class="jslm_search_box_left">
								<?php foreach (jslearnmanager::$_data[2] AS $field) {
									switch($field->field){
										case 'title': 	?>
											<div class="jslm_input_search_left">
						                	    <input class="jslm_input_search" type="text" name="mycoursetitle" value="<?php echo esc_attr(jslearnmanager::$_data['filter']['mycoursetitle']); ?>" id="coursetitle" placeholder="<?php echo esc_html__("Search Course","learn-manager"); ?>">
								            </div>
									<?php break;
										case 'category_id' : ?>
											<div class="jslm_dropdown_wrp">
						                        <?php echo wp_kses(JSLEARNMANAGERformfield::select('mycoursecategory', JSLEARNMANAGERincluder::getJSModel('category')->getCategoryForCombobox(), jslearnmanager::$_data['filter']['mycoursecategory'], __('Select category', 'learn-manager'), array('class' => 'jslm_input_field jslm_select_style jslm_select_full_width')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
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
			              <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('JSLEARNMANAGER_form_mycourse_search', 'JSLEARNMANAGER_MYCOURSES_SEARCH'), JSLEARNMANAGER_ALLOWED_TAGS);?>
			              <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('jslmslt', 'mycourses'), JSLEARNMANAGER_ALLOWED_TAGS);?>
			            </div>
					</form>
				</div>
			<?php } ?>
		</div>
		<?php if(jslearnmanager::$_error_flag_message == null){  ?>
			<div class="jslm_data_container">
				<?php
					include_once( 'studentcourselist.php');
		        ?>
	        </div>
		    <?php if (jslearnmanager::$_data[1]) {
	        		echo '<div class="tablenav" style="text-align: right;"><div class="tablenav-pages ">' . jslearnmanager::$_data[1] . '</div></div>';
            }
		}else{
			echo jslearnmanager::$_error_flag_message;
		}?>
    </div>
</div>
</div>

