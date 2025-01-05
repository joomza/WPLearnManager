<div class="jslm_main-up-wrapper">
<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
$msgkey = JSLEARNMANAGERincluder::getJSModel('coursesearch')->getMessagekey();
JSLEARNMANAGERMessages::getLayoutMessage($msgkey);
JSLEARNMANAGERbreadcrumbs::getBreadcrumbs();
include_once(JSLEARNMANAGER_PLUGIN_PATH . 'includes/header.php');
if (jslearnmanager::$_error_flag_message == null) {
?>
    <div class="jslm_content_wrapper"> <!-- lower bottom Content -->
        <div class="jslm_content_data">
            <div class="jslm_search_content">
                <div class="jslm_top_title">
                    <div class="jslm_left_data"><h3 class="jslm_title_heading"><?php echo __("Course Search","learn-manager"); ?></h3></div>
                </div>
            </div>
            <div class="jslm_data_container no-padding-top">
                <div class="jslm_addcourse_wrapper">
                    <form id="lms_form" method="post" action="<?php echo esc_url(jslearnmanager::makeUrl(array('jslmsmod'=>'course', 'jslmslay'=>'courselist', 'jslearnmanagerpageid'=>jslearnmanager::getPageid()))); ?>">
                        <div class="jslm_form_wrapper">
                            <?php function getRow($title, $value) {
                                $html = '<div class="jslm_row">
                                            <div class="jslm_title">' . $title . '</div>
                                            <div class="jslm_input_field">' . $value . '</div>
                                        </div>';
                                return $html;
                            }
                            foreach (jslearnmanager::$_data[2] AS $field) {
                                switch ($field->field) {
                                    case 'title':
                                        $title = __($field->fieldtitle, 'learn-manager');
                                        $value = JSLEARNMANAGERformfield::text('coursetitle', isset(jslearnmanager::$_data[0]['filter']->courestitle) ? jslearnmanager::$_data[0]['filter']->courestitle : '', array('class' => 'inputbox '));
                                        echo wp_kses(getRow($title, $value), JSLEARNMANAGER_ALLOWED_TAGS);
                                        break;
                                    case 'category_id':
                                        $title = __($field->fieldtitle, 'learn-manager');
                                        $value = JSLEARNMANAGERformfield::select('category', JSLEARNMANAGERincluder::getJSModel('category')->getCategoriesForCombo(), isset(jslearnmanager::$_data[0]['filter']->category) ? jslearnmanager::$_data[0]['filter']->category : '', __('Select','learn-manager') .'&nbsp;'. __('Category', 'learn-manager'), array('class' => 'jslm_select_style jslm_select_full_width '));
                                        echo wp_kses(getRow($title, $value), JSLEARNMANAGER_ALLOWED_TAGS);
                                        break;
                                    // case 'access_type':
                                    //     $title = __($field->fieldtitle, 'learn-manager');
                                    //     $value = JSLEARNMANAGERformfield::select('access_type', JSLEARNMANAGERincluder::getJSModel('accesstype')->getaccesstypeForCombo(), isset(jslearnmanager::$_data[0]['filter']->access_type) ? jslearnmanager::$_data[0]['filter']->access_type : '', __('Select','learn-manager') .'&nbsp;'. __('Access Type', 'learn-manager'), array('class' => 'jslm_select_style jslm_select_full_width'));
                                    //     echo wp_kses(getRow($title, $value), JSLEARNMANAGER_ALLOWED_TAGS);
                                    //     break;
                                    case 'price':
                                        if(in_array('paidcourse',jslearnmanager::$_active_addons)){
                                            $title = __($field->fieldtitle, 'learn-manager');
                                            $value = JSLEARNMANAGERformfield::select('currencyid', JSLEARNMANAGERincluder::getJSModel('currency')->getCurrencyForCombo(), isset(jslearnmanager::$_data[0]['filter']->currencyid) ? jslearnmanager::$_data[0]['filter']->currencyid : '', __('Select','learn-manager') .'&nbsp;'. __('Currency', 'learn-manager'), array('class' => 'jslm_select_style jslm_select_full_width sal'));
                                            $value .= '<div class="jslm_price_wrapper">';
                                            $value .= JSLEARNMANAGERformfield::text('rangestart',  isset(jslearnmanager::$_data[0]['filter']->pricestart) ? jslearnmanager::$_data[0]['filter']->pricestart : '', array('placeholder' => 'Price start from'));
                                            $value .= JSLEARNMANAGERformfield::text('rangeend',  isset(jslearnmanager::$_data[0]['filter']->priceend) ? jslearnmanager::$_data[0]['filter']->priceend : '', array('placeholder' => 'price end to'));
                                            $value .= '</div>';
                                            echo getRow($title, $value);
                                        }
                                        break;
                                    case 'language':
                                        $title = __($field->fieldtitle, 'learn-manager');
                                        $value = JSLEARNMANAGERformfield::select('language', JSLEARNMANAGERincluder::getJSModel('language')->getlanguageForCombo(), isset(jslearnmanager::$_data[0]['filter']->language) ? jslearnmanager::$_data[0]['filter']->language : '', __('Select','learn-manager') .'&nbsp;'. __('Language', 'learn-manager'), array('class' => 'jslm_select_style jslm_select_full_width '));
                                        echo wp_kses(getRow($title, $value), JSLEARNMANAGER_ALLOWED_TAGS);
                                        break;
                                    case 'course_level':
                                        $title = __($field->fieldtitle, 'learn-manager');
                                        $value = JSLEARNMANAGERformfield::select('courselevel', JSLEARNMANAGERincluder::getJSModel('courselevel')->getLevelForCombo(), isset(jslearnmanager::$_data[0]['filter']->courselevel) ? jslearnmanager::$_data[0]['filter']->courselevel : '', __('Select','learn-manager') .'&nbsp;'. __('Course Level', 'learn-manager'), array('class' => 'jslm_select_style jslm_select_full_width '));
                                        echo getRow($title, $value);
                                        break;
                                    case 'instructor_id':
                                        $title =  __($field->fieldtitle, 'learn-manager');
                                        $value = JSLEARNMANAGERformfield::text('instructorname',  isset(jslearnmanager::$_data[0]['filter']->instructorname) ? jslearnmanager::$_data[0]['filter']->instructorname : '', array('placeholder' => 'Instructor'));
                                        echo wp_kses(getRow($title,$value), JSLEARNMANAGER_ALLOWED_TAGS);
                                    default:
                                        $i = 1;
                                        echo JSLEARNMANAGERincluder::getObjectClass('customfields')->formCustomFieldsForSearch($field, $i);
                                        break;
                                }
                            }?>
                            <div class="jslm_button_wrp js-form" id="save-button">
                                <?php echo wp_kses(JSLEARNMANAGERformfield::submitbutton('save', __('Search Course', 'learn-manager'), array('class' => 'jslm_save_button')), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                            </div>
                            <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('JSLEARNMANAGER_form_search', 'JSLEARNMANAGER_SEARCH'), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                            <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('jslmslt', 'courselist'), JSLEARNMANAGER_ALLOWED_TAGS); ?>
                            <input type="hidden" id="issearchform" name="issearchform" value="1"/>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php
}else{
    echo jslearnmanager::$_error_flag_message;
} ?>
</div>
