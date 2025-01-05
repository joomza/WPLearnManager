<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
if (!defined('ABSPATH'))
    die('Restricted Access');

$color1 = "#4d4d4d";
$color2 = "#e43039";
$color3 = "#fafafa";
$color4 = "#797b7e";
$color5 = "#d4d4d5"; // border color
$color6 = "#f0f0f0";
$color7 = "#ffffff";
$color8 = "#3c3435";
$color9 = "#D34034";
$color10 = jslm_adjustBrightness($color1, -30);

function jslm_adjustBrightness($hex, $steps) {
    // Steps should be between -255 and 255. Negative = darker, positive = lighter
    $steps = max(-255, min(255, $steps));
    // Normalize into a six character long hex string
    $hex = str_replace('#', '', $hex);
    if (strlen($hex) == 3) {
        $hex = str_repeat(substr($hex, 0, 1), 2) . str_repeat(substr($hex, 1, 1), 2) . str_repeat(substr($hex, 2, 1), 2);
    }
    // Split into three parts: R, G and B
    $color_parts = str_split($hex, 2);
    $return = '#';
    foreach ($color_parts as $color) {
        $color = hexdec($color); // Convert to decimal
        $color = max(0, min(255, $color + $steps)); // Adjust color
        $return .= str_pad(dechex($color), 2, '0', STR_PAD_LEFT); // Make two char hex code
    }
    return $return;
}?>
<?php  ob_start(); ?>
<style type="text/css">

    div.jslm_main-up-wrapper .jslm_input_field .tmce-active .switch-tmce ,
    div.jslm_main-up-wrapper .jslm_input_field .tmce-active .switch-html {background: <?php echo esc_attr($color6); ?> !important;color: <?php echo esc_attr($color4); ?> !important;border-bottom-color: <?php echo esc_attr($color6); ?> !important;}
    div.jslm_main-up-wrapper .jslm_input_field button:not(:hover):not(:active):not(.has-background) {background: <?php echo esc_attr($color6); ?> !important;color: <?php echo esc_attr($color4); ?> !important;}
    div.jslm_main-up-wrapper div.jslm_dropdown_wrp select.jslm_input_field{border: 1px solid <?php echo esc_attr($color5); ?>;background-color: <?php echo esc_attr($color7); ?>;color: <?php echo esc_attr($color4); ?>;}
    /* plugin color */
    .border-top{border-top: 1px solid <?php echo esc_attr( $color5 ); ?>;}
    .jslm_border_bottom{border-bottom: 1px solid <?php echo esc_attr( $color5 ); ?>;}
    li a.headerlinks.active,
    .jslm_section_title.active,
    a.active{background-color: <?php echo esc_attr( $color2 ); ?> ;color: <?php echo esc_attr( $color7 ); ?> ;}
    div.jslm_content_wrapper div.jslm_content_data a.button{background-color: <?php echo esc_attr( $color6 ); ?>;color: <?php echo esc_attr( $color1 ); ?>;border: 1px solid <?php echo esc_attr( $color5 ); ?>;}
    div.jslm_content_wrapper div.js-form-wrapper-newlogin div.js-imagearea img{background-color: <?php echo esc_attr( $color2 ); ?>}
    div.jslm_content_wrapper div.page_heading{color: <?php echo esc_attr( $color1 ); ?>}
    div.jslm_content_wrapper div.js-form-wrapper-newlogin div.js-dataarea input.button.jslms-newsubmit{color: <?php echo esc_attr( $color7 ); ?>;background-color: <?php echo esc_attr( $color2 ); ?>;}
    /*div.jslm-panel-heading.active{background-color: <?php echo esc_attr( $color2 ); ?>;}*/
    /* venobox */
    a.vbox-next,a.vbox-prev{background-color: <?php echo esc_attr( $color7 ); ?>;}
    /* Modal */
    .modal-header{background-color: <?php echo esc_attr( $color2 ); ?>;}
    div.jslm_content_data div.jslm_modal_body span.jslm_search_field input.jslm_field_style{border: 1px solid <?php echo esc_attr( $color5 ); ?>;background: <?php echo esc_attr( $color3 ); ?>;color: <?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_modal_body span.jslm_search_field input.jslm_field_style::-webkit-input-placeholder{color: <?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_modal_body span.jslm_search_field input.jslm_field_style::-moz-placeholder{color: <?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_modal_body span.jslm_search_field input.jslm_field_style::-ms-input-placeholder{color: <?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_modal_body span.jslm_search_field input.jslm_field_style::-moz-placeholder {color: <?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div#selectpaymentmethod div.jslm_modal_header button.jslm_close_button_style{color: <?php echo esc_attr( $color2 ); ?>}
    div.jslm_content_data div.jslm_modal_body div.jslm-payment-wrp a{border: 1px solid <?php echo esc_attr( $color5 ); ?>}
    div.jslm_content_data div.jslm_modal_body div.jslm-payment-wrp a:hover{border-color: <?php echo esc_attr( $color2 ); ?>;}
    div.jslm_content_data div.jslm_modal_body span.jslm_search_field span.jslm_save_button {border-top: 1px solid <?php echo esc_attr( $color2 ); ?>;}
    div.jslm_content_data div.jslm_modal_body span.jslm_search_field span.jslm_save_button input.jslm_save_button_style{background-color: <?php echo esc_attr( $color3 ); ?>;font-weight: 600;border:1px solid <?php echo esc_attr( $color5 ); ?>;color: <?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_modal_body span.jslm_search_field span.jslm_save_button input.jslm_save_button_style.jslm_saveNclose{border-color: <?php echo esc_attr( $color2 ); ?>;background-color: <?php echo esc_attr( $color2 ); ?>;color: #fff;}
    div.jslm_content_data div.jslm_modal_body div.jslm_payment_plan_description h5.jslm_payment_plan_desc{border: 1px solid <?php echo esc_attr( $color2 ); ?>;color: <?php echo esc_attr( $color4 ); ?>;background-color: <?php echo esc_attr( $color3 ); ?>;}
    div.jslm_content_data div.jslm_modal_body div.jslm_payment_plan_description h5.jslm_payment_plan_desc span.jslm_payment_plan_percentage{color: <?php echo esc_attr( $color2 ); ?>;}
    div.jslm_content_data div.jslm_error_message_wrapper a.error_btn{border: 1px solid <?php echo esc_attr( $color2 ); ?>;background-color: <?php echo esc_attr( $color2 ); ?>;color: #fff;}
    div.jslm_content_data div.jslm_error_message_wrapper a.error_btn.err_register_btn{border: 1px solid <?php echo esc_attr( $color5 ); ?>;background-color: <?php echo esc_attr( $color3 ); ?>;color: <?php echo esc_attr( $color4 ); ?> !important;}
    /* Login form placeholder */
    div.jslm_content_data div.jslm_data_container div.jslm_login_wrapper div.jslm_login_left div.jslm_input_field input::-webkit-input-placeholder {color:<?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_login_wrapper div.jslm_login_left div.jslm_input_field input::-moz-placeholder {color:<?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_login_wrapper div.jslm_login_left div.jslm_input_field input::-ms-placeholder {color:<?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_login_wrapper div.jslm_login_left div.jslm_input_field input::placeholder {color:<?php echo esc_attr( $color4 ); ?>;}
    /*header */
    div#jslearnmanager-header-main-wrapper nav{background-color: <?php echo esc_attr( $color1 ); ?>;}
    div#jslearnmanager-header-main-wrapper nav ul li{}
    div#jslearnmanager-header-main-wrapper nav ul li a{color:#fff;border-right: 1px solid #252525;}
    div#jslearnmanager-header-main-wrapper nav ul li:hover{background-color: <?php echo esc_attr( $color2 ); ?>;border-color: <?php echo esc_attr( $color2 ); ?>;}
    div#jslearnmanager-header-main-wrapper nav ul li a.active,
    div#jslearnmanager-header-main-wrapper nav ul li:hover a{border-color: <?php echo esc_attr( $color2 ); ?>;}
    div.jslm_content_data div.jslm_search_content div.jslm_top_title div.jslm_left_data h3.jslm_title_heading{color: <?php echo esc_attr( $color1 ); ?>}
    /* course list 2 coulmns */
    div#jslearnmanager-header-main-wrapper nav{border-bottom: 5px solid <?php echo esc_attr( $color2 ); ?>;}
    div.jslm_content_data div.jslm_search_content div.jslm_top_title{border-bottom: 2px solid <?php echo esc_attr( $color2 );?>;}
    div.jslm_content_data div.jslm_search_content div.jslm_top_title div.jslm_right_data span.jslm_sorting select{border: 1px solid <?php echo esc_attr( $color5 ); ?>;color: <?php echo esc_attr( $color4 ); ?>;background: <?php echo esc_attr( $color3 ); ?>;}
    div.jslm_content_data div.jslm_search_content div.jslm_top_title div.jslm_right_data span.jslm_sorting a.jslm_sort_img{background-color: <?php echo esc_attr( $color2 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_data_wrapper{border:1px solid <?php echo esc_attr( $color5 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_data_wrapper div.jslm_right{border-left: 1px solid <?php echo esc_attr( $color5 ); ?>;}
    /*div.jslm_content_data div.jslm_data_container div.jslm_data_wrapper div.jslm_right div.jslm_right_middle{border-top: 1px solid <?php echo esc_attr( $color5 ); ?>;}*/
    div.jslm_content_data div.jslm_data_container div.jslm_data_wrapper div.jslm_right_bottom{border-top: 1px solid <?php echo esc_attr( $color5 ); ?>;background-color: <?php echo esc_attr( $color3 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_data_wrapper div.jslm_right div.jslm_right_top{border-bottom:1px solid <?php echo esc_attr( $color5 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_data_wrapper div.jslm_right div.jslm_right_top div.jslm_right_top_left h4.jslm_heading_style a.jslm_course_title{color: <?php echo esc_attr( $color1 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_data_wrapper:hover div.jslm_right div.jslm_right_top div.jslm_right_top_left h4.jslm_heading_style a.jslm_course_title{color: <?php echo esc_attr( $color2 ); ?>}
    div.jslm_content_data div.jslm_data_container div.jslm_data_wrapper div.jslm_right div.jslm_right_middle div.jslm_middle_description{color: <?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_data_wrapper div.jslm_right_bottom div.jslm_right_bottom_left span.jslm_logos span.jslm_logo_wrp{color: <?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_data_wrapper div.jslm_left div.jslm_left_img_wrp div.overlay div.jslm_links span.jslm_link_icon a:hover{background-color: <?php echo esc_attr( $color2 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_data_wrapper div.jslm_left{}
    div.jslm_content_data div.jslm_search_content div.jslm_top_search div.jslm_search_box div.jslm_search_box_left div.jslm_input_search_left input.jslm_input_search{border: 1px solid <?php echo esc_attr( $color5 ); ?>;background-color: <?php echo esc_attr( $color3 ); ?>;color: <?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_search_content div.jslm_top_search div.jslm_search_box div.jslm_search_box_left div.jslm_input_search_left input.jslm_input_search::-webkit-input-placeholder {color:<?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_search_content div.jslm_top_search div.jslm_search_box div.jslm_search_box_left div.jslm_input_search_left input.jslm_input_search::-moz-placeholder {color:<?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_search_content div.jslm_top_search div.jslm_search_box div.jslm_search_box_left div.jslm_input_search_left input.jslm_input_search::-ms-placeholder {color:<?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_search_content div.jslm_top_search div.jslm_search_box div.jslm_search_box_left div.jslm_input_search_left input.jslm_input_search::placeholder {color:<?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_search_content div.jslm_top_search div.jslm_search_box div.jslm_search_box_left div.jslm_input_search_left i.fa-calendar{color: <?php echo esc_attr( $color4 ); ?>}
    div.jslm_content_data div.jslm_search_content div.jslm_top_search div.jslm_search_box div.jslm_search_box_left div.jslm_dropdown_wrp .select2-container--default .select2-selection--single {border-color: <?php echo esc_attr( $color5 ); ?>;background-color: <?php echo esc_attr( $color3 ); ?>;}
    div.jslm_content_data div.jslm_search_content div.jslm_top_search div.jslm_search_box div.jslm_search_box_left div.jslm_dropdown_wrp .select2-container--default .select2-selection--single .select2-selection__rendered {color: <?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_search_content div.jslm_top_search div.jslm_search_box div.jslm_search_box_right div.jslm_search_btn_wrap button.jslm_search_button_style.jslm_search{background-color: <?php echo esc_attr( $color2 ); ?>;border: 1px solid <?php echo esc_attr( $color2 ); ?>}
    div.jslm_content_data div.jslm_search_content div.jslm_top_search div.jslm_search_box div.jslm_search_box_right div.jslm_search_btn_wrap button.jslm_search_button_style.jslm_refresh{border: 1px solid <?php echo esc_attr( $color5 ); ?>;color: <?php echo esc_attr( $color4 ); ?>;background-color: <?php echo esc_attr( $color3 ); ?>;}
    div.jslm_content_data div.jslm_search_content div.jslm_top_title div.jslm_right_data span.jslm_sorting a.jslm_link,
    div.jslm_content_data div.jslm_search_content div.jslm_top_title div.jslm_right_data span.jslm_sorting span.jslm_link{background-color: <?php echo esc_attr( $color6 ); ?>;color: <?php echo esc_attr( $color1 ); ?> !important;border: 1px solid <?php echo esc_attr( $color5 ); ?>;}
    div.jslm_content_data div.jslm_search_content div.jslm_top_title div.jslm_right_data span.jslm_sorting a.jslm_link:hover{border-color: <?php echo esc_attr( $color2 ); ?>;}
    div.jslm_content_data div.jslm_search_content div.jslm_top_search{border-bottom: 1px solid <?php echo esc_attr( $color5 ); ?>}
    div.jslm_content_data div.jslm_data_container div.jslm_data_wrapper:hover{border-color: <?php echo esc_attr( $color2 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_data_wrapper div.jslm_left div.jslm_left_img_wrp div.overlay div.jslm_overlay_bottom div.jslm_overlay_bottom_name span.jslm_overlay_bottom_instructor a.jslm_instructor_link{color:<?php echo esc_attr( $color7 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_data_wrapper div.jslm_left div.jslm_left_img_wrp div.overlay div.jslm_overlay_bottom div.jslm_overlay_bottom_name span.jslm_overlay_bottom_instructor a.jslm_instructor_link:hover{color:<?php echo esc_attr( $color2 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_data_wrapper div.jslm_right_bottom div.jslm_right_bottom_right div.jslm_right_bottom_price h3.jslm_heading_style{color: <?php echo esc_attr( $color7 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_data_wrapper div.jslm_right div.jslm_right_top div.jslm_right_top_stars div.jslm_top_stars span.jslm_rating_number{color: <?php echo esc_attr( $color4 ); ?>}
    div.jslm_content_data div.jslm_data_container div.jslm_data_wrapper div.jslm_right div.jslm_custom_fields_wrapper div.jslm_custom_field span.jslm_heading{color: <?php echo esc_attr( $color1 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_data_wrapper div.jslm_right div.jslm_custom_fields_wrapper div.jslm_custom_field span.jslm_text{color: <?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_data_wrapper div.jslm_left div.jslm_left_img_wrp div.overlay div.jslm_overlay_bottom div.jslm_overlay_bottom_img{border:1px solid <?php echo esc_attr( $color7 ); ?>;}
    /*course detail*/
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_middle_potion{border:1px solid <?php echo esc_attr( $color5 ); ?>;}
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_middle_potion div.jslm_middle_bottom{border-top: 1px solid <?php echo esc_attr( $color5 ); ?>;}
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_middle_potion{background-color: <?php echo esc_attr( $color3 ); ?>;}
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_tabs_wrapper ul.nav-tabs{border-bottom: 1px solid <?php echo esc_attr( $color2 );?>;}
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_tabs_wrapper ul li.jslm_li_border{background-color: <?php echo esc_attr( $color3 ); ?>;}
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_tabs_wrapper ul li.jslm_li_border.active{background-color: <?php echo esc_attr( $color2 ); ?>;}
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_tabs_wrapper ul li.jslm_li_border a.jslm_li_anchor_styling{border: 1px solid <?php echo esc_attr( $color5 ); ?>;color: <?php echo esc_attr( $color4 ); ?>}
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_tabs_wrapper ul li.jslm_li_border a.jslm_li_anchor_styling.jslm_left_border{border-left: 1px solid <?php echo esc_attr( $color5 ); ?>;}
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_tabs_wrapper ul li.jslm_li_border.active a.jslm_li_anchor_styling{color: <?php echo esc_attr( $color7 ); ?>;}
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_tabs_wrapper div.jslm_my_content div.jslm_home_data_wrapper div.jslm_home_data_row div.jslm_row_body_text{color: <?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_tabs_wrapper div.jslm_my_content div.jslm_home_data_wrapper div.jslm_home_data_row div.jslm_row_heading h3.jslm_row_heading_style,
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_tabs_wrapper div.jslm_my_content div.jslm_members_wrapper div.jslm_home_data_row div.jslm_row_heading h3.jslm_row_heading_style,
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_tabs_wrapper div.jslm_my_content div.jslm_reviews_wrapper div.jslm_reviews_title div.jslm_row_heading h3.jslm_row_heading_style,
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_tabs_wrapper div.jslm_my_content div.jslm_curriculum_wrapper div.jslm_curriculum_title div.jslm_row_heading h3.jslm_row_heading_style{color: <?php echo esc_attr( $color1 ); ?>;}
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_tabs_wrapper div.jslm_my_content span.jslm_social_links{border:1px solid transparent;}
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_tabs_wrapper div.jslm_my_content div.jslm_home_data_wrapper div.jslm_home_data_row div.jslm_custom_fields_wrapper{border-top: 1px solid <?php echo esc_attr( $color5 ); ?>;}
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_tabs_wrapper div.jslm_my_content div.jslm_home_data_wrapper div.jslm_home_data_row div.jslm_custom_fields_wrapper div.jslm_custom_field,
    div.jslm_content_wrapper div.jslm_content_data div.jslm_instructor_wrapper div.jslm_right_data div.jslm_home_data_row div.jslm_custom_fields_wrapper div.jslm_custom_field,
    div.jslm_content_wrapper div.jslm_content_data div.jslm_student_wrapper div.jslm_right_data div.jslm_home_data_row div.jslm_custom_fields_wrapper div.jslm_custom_field{border-bottom: 1px solid <?php echo esc_attr( $color5 ); ?>;}
    div.jslm_content_wrapper div.jslm_content_data div.jslm_instructor_wrapper div.jslm_right_data div.jslm_home_data_row div.jslm_custom_fields_wrapper div.jslm_custom_field span.jslm_heading {color: <?php echo esc_attr( $color1 ); ?>;}
    div.jslm_content_wrapper div.jslm_content_data div.jslm_instructor_wrapper div.jslm_right_data div.jslm_home_data_row div.jslm_custom_fields_wrapper div.jslm_custom_field span.jslm_text {color: <?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_tabs_wrapper div.jslm_my_content div.jslm_social_wrapper span.jslm_text{color: <?php echo esc_attr( $color1 ); ?>;}
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_tabs_wrapper div.jslm_my_content div.jslm_home_data_wrapper div.jslm_home_data_row div.jslm_custom_fields_wrapper div.jslm_custom_field span.jslm_heading{color: <?php echo esc_attr( $color1 ); ?>;}
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_tabs_wrapper div.jslm_my_content div.jslm_home_data_wrapper div.jslm_home_data_row div.jslm_custom_fields_wrapper div.jslm_custom_field span.jslm_text{color: <?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_tabs_wrapper div.jslm_my_content div.jslm_home_data_wrapper div.jslm_home_data_row div.jslm_row_heading span.jslm_row_body_text{color: <?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_tabs_wrapper div.jslm_my_content div.jslm_members_wrapper div.jslm_member_data{border: 1px solid <?php echo esc_attr( $color5 ); ?>;background-color: <?php echo esc_attr( $color3 ); ?>;}
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_tabs_wrapper div.jslm_my_content div.jslm_members_wrapper div.jslm_member_data div.jslm_left_side div.jslm_img_wrapper img{}
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_tabs_wrapper div.jslm_my_content div.jslm_reviews_wrapper div.jslm_reviews_top_wrapper{border:1px solid <?php echo esc_attr( $color5 ); ?>;background: <?php echo esc_attr( $color3 ); ?>;}
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_tabs_wrapper div.jslm_my_content div.jslm_reviews_wrapper div.jslm_reviews_top_wrapper div.jslm_reviews_left div.jslm_square_box span.jslm_bold_text{color: <?php echo esc_attr( $color1 ); ?>;}
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_tabs_wrapper div.jslm_my_content div.jslm_reviews_wrapper div.jslm_reviews_top_wrapper div.jslm_reviews_left div.jslm_square_box{border:1px solid <?php echo esc_attr( $color5 ); ?>;}
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_tabs_wrapper div.jslm_my_content div.jslm_reviews_wrapper div.jslm_reviews_top_wrapper div.jslm_reviews_right div.jslm_reviews_right_data span.jslm_progress_bar{border:1px solid <?php echo esc_attr( $color5 ); ?>;}
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_tabs_wrapper div.jslm_my_content div.jslm_reviews_wrapper div.jslm_reviews_top_wrapper div.jslm_reviews_right div.jslm_reviews_right_data span.jslm_progress_bar span.jslm_progress_bar_percent{background-color: <?php  echo esc_attr( $color2 );?>;}
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_tabs_wrapper div.jslm_my_content div.jslm_reviews_wrapper div.jslm_reviews_title div.jslm_review_desc{color: <?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_tabs_wrapper div.jslm_my_content div.jslm_reviews_wrapper div.jslm_comments_wrapper{border:1px solid <?php echo esc_attr( $color5 ); ?>;background-color: <?php echo esc_attr( $color3 ); ?>;}
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_tabs_wrapper div.jslm_my_content div.jslm_reviews_wrapper div.jslm_comments_wrapper div.jslm_left_side div.jslm_img_wrapper img{}
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_tabs_wrapper div.jslm_my_content div.jslm_reviews_wrapper div.jslm_comments_wrapper div.jslm_right_side div.jslm_top span.jslm_top_left{color: <?php echo esc_attr( $color1 ); ?>;}
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_tabs_wrapper div.jslm_my_content div.jslm_reviews_wrapper div.jslm_comments_wrapper div.jslm_right_side div.jslm_bottom{color: <?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_tabs_wrapper div.jslm_my_content div.jslm_reviews_wrapper a.jslm_show_more{border:1px solid <?php echo esc_attr( $color5 ); ?>;}
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_tabs_wrapper div.jslm_my_content div.jslm_reviews_wrapper a.jslm_show_more:hover{border-color: <?php echo esc_attr( $color2 ); ?>}
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_tabs_wrapper div.jslm_my_content div.jslm_curriculum_wrapper div.jslm_panel_group div.jslm-panel-heading{background-color: <?php echo esc_attr( $color3 ); ?>;border:1px solid <?php echo esc_attr( $color5 ); ?>;}
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_tabs_wrapper div.jslm_my_content div.jslm_curriculum_wrapper div.jslm_panel_group div.jslm-panel-heading:hover{border-color:<?php echo esc_attr( $color2 ); ?>;}
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_tabs_wrapper div.jslm_my_content div.jslm_curriculum_wrapper div.jslm_panel_group div.jslm-panel-heading div.jslm_edit_section_left h6.jslm_section_heading a i{border:1px solid <?php echo esc_attr( $color5 ); ?>;color: <?php echo esc_attr( $color1 ); ?>;background-color: <?php echo esc_attr( $color7 ); ?>;}
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_tabs_wrapper div.jslm_my_content div.jslm_curriculum_wrapper div.jslm_panel_group div.jslm-panel-heading div.jslm_edit_section_left h6.jslm_section_heading a{color: <?php echo esc_attr( $color1 ); ?>}
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_tabs_wrapper div.jslm_my_content div.jslm_curriculum_wrapper div.jslm_panel_group div.jslm_container_style{border:1px solid <?php echo esc_attr( $color5 ); ?>;}
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_tabs_wrapper div.jslm_my_content div.jslm_curriculum_wrapper div.jslm_panel_group div.jslm_container_style div.jslm_edit_row_style{border:1px solid <?php echo esc_attr( $color5 ); ?>;background-color: <?php echo esc_attr( $color3 ); ?>;}
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_tabs_wrapper div.jslm_my_content div.jslm_curriculum_wrapper div.jslm_panel_group div.jslm_container_style div.jslm_edit_row_style:hover{border-color: <?php echo esc_attr( $color2 ); ?>;}
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_tabs_wrapper div.jslm_my_content div.jslm_curriculum_wrapper div.jslm_panel_group div.jslm_panel_single_group div.jslm-panel-heading.active div.jslm_edit_section_left h6.jslm_section_heading a,
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_tabs_wrapper div.jslm_my_content div.jslm_curriculum_wrapper div.jslm_panel_group div.jslm_panel_single_group div.jslm-panel-heading.active div.jslm_edit_section_left h6.jslm_section_heading a i{background-color: <?php echo esc_attr( $color2 ); ?>;color: #fff;}
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_tabs_wrapper div.jslm_my_content div.jslm_curriculum_wrapper div.jslm_panel_group div.jslm_panel_single_group div.jslm_container_style div.jslm_edit_row_style a.jslm_edit_lec_anchor{color: <?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_tabs_wrapper div.jslm_my_content div.jslm_curriculum_wrapper div.jslm_panel_group div.jslm_panel_single_group div.jslm_container_style div.jslm_edit_row_style span.jslm_edit_section_action i{border:1px solid <?php echo esc_attr( $color5 ); ?>;color: <?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_tabs_wrapper div.jslm_my_content div.jslm_curriculum_wrapper div.jslm_panel_group div.jslm_panel_single_group div.jslm_container_style div.jslm_edit_row_style span.jslm_edit_section_action a:hover{color: <?php echo esc_attr( $color7 ); ?>; background-color: <?php echo esc_attr( $color2 ); ?>;}
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_tabs_wrapper div.jslm_my_content div.jslm_curriculum_wrapper div.jslm_panel_group div.jslm_panel_single_group div.jslm_container_style div.jslm_edit_row_style span.jslm_edit_section_action a:hover i{color: <?php echo esc_attr( $color7 ); ?>;}
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_tabs_wrapper div.jslm_my_content div.jslm_reviews_wrapper div.jslm_leave_comments h3{color: <?php echo esc_attr( $color1 ); ?>}
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_tabs_wrapper div.jslm_my_content div.jslm_reviews_wrapper div.jslm_leave_comments span.jslm_warning_msg{color: <?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_tabs_wrapper div.jslm_my_content div.jslm_reviews_wrapper div.jslm_leave_comments div.jslm_form_wrapper span.jslm_title{color: <?php echo esc_attr( $color1 ); ?>}
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_tabs_wrapper div.jslm_my_content div.jslm_reviews_wrapper div.jslm_leave_comments div.jslm_form_wrapper span.jslm_button button.jslm_post_comment_btn{background-color: <?php echo esc_attr( $color2 ); ?>;color: <?php echo esc_attr( $color7 ); ?>}
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_tabs_wrapper div.jslm_my_content div.alert{border: 1px solid <?php echo esc_attr( $color5 ); ?>;background-color: <?php echo esc_attr( $color3 ) ?>;color: <?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_login_wrapper{border: 1px solid <?php echo esc_attr( $color5 ); ?>;background-color: <?php echo esc_attr( $color3 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_login_wrapper div.jslm_login_left h2.jslm_login_heading{color: <?php echo esc_attr( $color1 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_login_wrapper div.jslm_login_left div.jslm_input_field input{border-color: <?php echo esc_attr( $color5 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_login_wrapper div.jslm_login_left a.jslm_forgot_password{color: <?php echo esc_attr( $color4 ); ?>}
    div.jslm_content_data div.jslm_data_container div.jslm_login_wrapper div.jslm_login_left div.jslm_login_button input{background-color: <?php echo esc_attr( $color2 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_login_wrapper div.jslm_login_right{border-left: 1px solid <?php echo esc_attr( $color5 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_login_wrapper div.jslm_login_right div.jslm_social_login_top div.jslm_social_title h2.jslm_or_text{color: <?php echo esc_attr( $color1 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_login_wrapper div.jslm_login_right div.jslm_social_login_bottom div.jslm_social_login_icons{border: 1px solid <?php echo esc_attr( $color5 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_login_wrapper div.jslm_login_right div.jslm_social_login_bottom div.jslm_social_login_icons a div.jslm_login_text{color: <?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_addcourse_wrapper div.jslm_form_wrapper div.jslm_btn_row{border-top:2px solid <?php echo esc_attr( $color2 ); ?>;}
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_top_wrapper div.jslm_left_wrap div.jslm_title span.jslm_title{color: <?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_middle_potion div.jslm_middle_top div.jslm_logo_wrp,
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_middle_potion div.jslm_middle_top span.jslm_logo_wrp{color: <?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_middle_potion div.jslm_middle_top div.jslm_logo_wrp div.jslm_detail_title a{color: <?php echo esc_attr( $color2 ); ?>;}
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_middle_potion div.jslm_append_border {background: <?php echo esc_attr( $color5 ); ?>;}
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_top_wrapper div.jslm_right_wrap div.jslm_enroll_btn_wrap div.jslm_price_btn h2{color: <?php echo esc_attr( $color7 ); ?>;}
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_top_wrapper div.jslm_right_wrap div.jslm_enroll_btn_wrap div.jslm_price_btn h3.jslm_discountclass{color: <?php echo esc_attr( $color1 ); ?>;}
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_top_wrapper div.jslm_right_wrap div.jslm_enroll_btn_wrap div.jslm_price_btn h3.jslm_free{color:<?php echo esc_attr( $color7 ); ?>;}
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_img_wrapper{border:1px solid <?php echo esc_attr( $color5 ); ?>;}
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_top_wrapper div.jslm_left_wrap div.jslm_title h2{color: <?php echo esc_attr( $color1 ); ?>;}
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_tabs_wrapper div.jslm_my_content div.jslm_home_data_wrapper div.jslm_home_data_row,
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_tabs_wrapper div.jslm_my_content div.jslm_members_wrapper div.jslm_home_data_row{color: <?php echo esc_attr( $color4 ); ?>}
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_tabs_wrapper div.jslm_my_content div.jslm_members_wrapper div.jslm_member_data div.jslm_right_side span.jslm_text2.jslm_text1{color: <?php echo esc_attr( $color1 ); ?>}
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_tabs_wrapper div.jslm_my_content div.jslm_members_wrapper div.jslm_member_data div.jslm_right_side span.jslm_text2{color: <?php echo esc_attr( $color4 ); ?>}
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_top_wrapper div.jslm_right_wrap div.jslm_enroll_btn_wrap div.jslm_enroll_course_btn a,
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_top_wrapper div.jslm_right_wrap div.jslm_enroll_btn_wrap div.jslm_price_btn a{background-color: <?php echo esc_attr( $color2 ); ?>;color: <?php echo esc_attr( $color7 ); ?>;}
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_top_wrapper div.jslm_right_wrap div.jslm_enroll_btn_wrap div.jslm_price_btn h3.jslm_discount_color{color: <?php echo esc_attr( $color7 ); ?>;}
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_middle_potion div.jslm_middle_top div.jslm_logo_wrp div.jslm_detail_img_wrap{border:1px solid <?php echo esc_attr( $color5 ); ?>;}
    div.jslm_content_data div.jslm_modal_body div.jslm_payment_heading span.jslm_heading{color: <?php echo esc_attr( $color1 ); ?>;}
    div.jslm_content_data div.jslm_modal_body div.jslm_payment_heading span.jslm_sub_title{color: <?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_top_wrapper div.jslm_right_wrap div.jslm_enroll_btn_wrap div.jslm_enroll_course_btn h3.jslm-drop{background-color: <?php echo esc_attr( $color2 ); ?>;color: <?php echo esc_attr( $color7 ); ?>;}
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_top_wrapper div.jslm_right_wrap div.jslm_enroll_btn_wrap div.jslm_top_enrolled h3.jslm_take_course{background: <?php echo esc_attr( $color2 ); ?>;}
    /* Course search */
    div.jslm_content_data div.jslm_data_container div.jslm_addcourse_wrapper div.jslm_form_wrapper div.jslm_button_wrp{border-top: 2px solid <?php echo esc_attr( $color2 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_addcourse_wrapper div.jslm_form_wrapper div.jslm_button_wrp input.jslm_save_button{background-color: <?php echo esc_attr( $color6 ); ?>;color: <?php echo esc_attr( $color1 ); ?>;border: 1px solid <?php echo esc_attr( $color5 ); ?>;}
    /* Add course*/
    div.jslm_content_data div.jslm_data_container div.jslm_addcourse_wrapper div.jslm_form_wrapper div.jslm_row div.jslm_title{color: <?php echo esc_attr( $color1 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_addcourse_wrapper div.jslm_form_wrapper div.jslm_row div.jslm_upload_image div.jslm_file_upload label.jslm_file_upload_label{background-color: <?php echo esc_attr( $color2 ); ?>;border: 1px solid <?php echo esc_attr( $color5 ); ?>;color: #fff;}
    div.jslm_content_data div.jslm_data_container div.jslm_addcourse_wrapper div.jslm_form_wrapper div.jslm_row div.jslm_upload_image span.jslm_image_name{color: <?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_addcourse_wrapper div.jslm_form_wrapper div.jslm_row div.jslm_upload_image span.jslm_image_name a.jslm_delete_image{color: <?php echo esc_attr( $color2 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_addcourse_wrapper div.jslm_form_wrapper div.jslm_row div.jslm_upload_image span.jslm_file_extension{color: <?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_addcourse_wrapper div.jslm_form_wrapper div.jslm_row div.jslm_input_field input,
    div.jslm_content_data div.jslm_data_container div.jslm_addlecture_wrapper div.jslm_form_wrapper div.jslm_input_field div.jslm_input input{border-color: <?php echo esc_attr( $color5 ); ?>;background-color: <?php echo esc_attr( $color3 ); ?>;color: <?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_addcourse_wrapper div.jslm_form_wrapper div.jslm_row div.jslm_input_field input::-webkit-input-placeholder {color:<?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_addcourse_wrapper div.jslm_form_wrapper div.jslm_row div.jslm_input_field input::-moz-placeholder {color:<?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_addcourse_wrapper div.jslm_form_wrapper div.jslm_row div.jslm_input_field input::-ms-placeholder {color:<?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_addcourse_wrapper div.jslm_form_wrapper div.jslm_row div.jslm_input_field input::placeholder {color:<?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_addcourse_wrapper div.jslm_form_wrapper div.jslm_row div.jslm_input_field select,
    div.jslm_content_data div.jslm_data_container div.jslm_addlecture_wrapper div.jslm_form_wrapper div.jslm_input_field div.jslm_input select{background-color: <?php echo esc_attr( $color3 ); ?>;color: <?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_addcourse_wrapper div.jslm_form_wrapper div.jslm_btn_row button.jslm_btn_style{background-color: <?php echo esc_attr( $color2 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_addcourse_wrapper div.jslm_form_wrapper div.jslm_btn_row a.jslm_btn_style.jslm_btn_cancel{background-color: <?php echo esc_attr( $color3 ); ?>;border:1px solid <?php echo esc_attr( $color5 ); ?>;color: <?php echo esc_attr( $color4 ); ?>}
    div.jslm_content_data div.jslm_data_container div.jslm_addcourse_wrapper div.jslm_form_wrapper div.jslm_row div.jslm_input_field div.jslm_price_field.jslm_checkbox_style{border:1px solid <?php echo esc_attr( $color5 ); ?>;background-color: <?php echo esc_attr( $color3 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_addcourse_wrapper div.jslm_form_wrapper div.jslm_row div.jslm_input_field a.jslm_delete_file{color: <?php echo esc_attr( $color2 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_addcourse_wrapper div.jslm_form_wrapper div.jslm_row div.jslm_input_field input.jslm_padding_upl_file{border: 1px solid <?php echo esc_attr( $color5 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_addcourse_wrapper div.jslm_form_wrapper div.jslm_row div.jslm_input_field div.jslm_checkbox_cf label,
    div.jslm_content_data div.jslm_data_container div.jslm_addcourse_wrapper div.jslm_form_wrapper div.jslm_row div.jslm_input_field div.jslm_checkbox_cf div.jslm_checkbox_label,
    div.jslm_content_data div.jslm_data_container div.jslm_register_wrapper div.jslm_right_data div.jslm_input_wrapper div.jslm_input_field div.jslm_checkbox_cf label{color: <?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_addcourse_wrapper div.jslm_form_wrapper div.jslm_row div.jslm_input_field div#jslms_cust_file_ext{color: <?php echo esc_attr( $color4 ); ?>;}
    div.jslm_row div.jslm_input_field .select2.select2-container.select2-container--default .selection .select2-selection.select2-selection--multiple{border:1px solid <?php echo esc_attr( $color5 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_register_wrapper div.jslm_right_data div.jslm_has-success label{color: <?php echo esc_attr( $color2 ); ?>;}
    /* Dashboard */
    div.jslm_content_data div.jslm_data_container div.jslm_dashboard_wrapper{border: 1px solid <?php echo esc_attr( $color5 ); ?>;background-color: <?php echo esc_attr( $color3 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_dashboard_wrapper div.jslm_dashboard_left_wrp div.jslm_myprofile div.jslm_profile_heading,
    div.jslm_content_data div.jslm_data_container div.jslm_dashboard_wrapper div.jslm_dashboard_right_wrp div.jslm_myprofile div.jslm_profile_heading{border:1px solid <?php echo esc_attr( $color5 ); ?>;background-color: #E6E7E8;color: <?php echo esc_attr( $color1 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_dashboard_wrapper div.jslm_dashboard_left_wrp div.jslm_myprofile div.jslm_probileleft_middle{background-color: #fff;}
    div.jslm_content_data div.jslm_data_container div.jslm_dashboard_wrapper div.jslm_dashboard_left_wrp div.jslm_myprofile div.jslm_profileleft_middle,
    div.jslm_content_data div.jslm_data_container div.jslm_dashboard_wrapper div.jslm_dashboard_right_wrp div.jslm_myprofile div.jslm_profileleft_middle{background-color: #fff;border: 1px solid <?php echo esc_attr( $color5 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_dashboard_wrapper div.jslm_dashboard_left_wrp div.jslm_myprofile div.jslm_profileleft_middle div.jslm_myprofile_image div.jslm_image_wrapper {border:1px solid <?php echo esc_attr( $color5 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_dashboard_wrapper div.jslm_dashboard_left_wrp div.jslm_myprofile div.jslm_profileleft_middle span.jslm_title{color: <?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_dashboard_wrapper div.jslm_dashboard_left_wrp div.jslm_myprofile div.jslm_profileleft_middle span.jslm_title.jslm_name{color: <?php echo esc_attr( $color1 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_dashboard_wrapper div.jslm_dashboard_left_wrp div.jslm_myprofile div.jslm_links_list ul.jslm_usefullinks{border: 1px solid <?php echo esc_attr( $color5 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_dashboard_wrapper div.jslm_dashboard_left_wrp div.jslm_myprofile div.jslm_links_list ul.jslm_usefullinks li.jslm_links{border-bottom: 1px solid <?php echo esc_attr( $color5 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_dashboard_wrapper div.jslm_dashboard_left_wrp div.jslm_myprofile div.jslm_links_list ul.jslm_usefullinks li.jslm_links a {color: <?php echo esc_attr( $color1 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_dashboard_wrapper div.jslm_dashboard_left_wrp div.jslm_myprofile div.jslm_links_list ul.jslm_usefullinks li.jslm_links a:hover{color: <?php echo esc_attr( $color2 ); ?>}
    div.jslm_content_data div.jslm_data_container div.jslm_dashboard_wrapper div.jslm_dashboard_right_wrp div.jslm_myprofile div.jslm_profileleft_middle ul.nav-tabs li.jslm_li_styling{border: 1px solid <?php echo esc_attr( $color5 ); ?>}
    div.jslm_content_data div.jslm_data_container div.jslm_dashboard_wrapper div.jslm_dashboard_right_wrp div.jslm_myprofile div.jslm_profileleft_middle ul.nav-tabs li.jslm_li_styling a.jslm_anchor_style{color: <?php echo esc_attr( $color1 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_dashboard_wrapper div.jslm_dashboard_right_wrp div.jslm_myprofile div.jslm_profileleft_middle ul.nav-tabs{border-bottom: 2px solid  <?php echo esc_attr( $color2 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_dashboard_wrapper div.jslm_dashboard_right_wrp div.jslm_myprofile div.jslm_profileleft_middle div.jslm_dashboard_course_list{border: 1px solid <?php echo esc_attr( $color5 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_dashboard_wrapper div.jslm_dashboard_right_wrp div.jslm_myprofile div.jslm_profileleft_middle div.jslm_dashboard_course_list div.jslm_top_heading,
    div.jslm_content_data div.jslm_data_container div.jslm_dashboard_wrapper div.jslm_dashboard_right_wrp div.jslm_myprofile div.jslm_profileleft_middle div.jslm_payout_container div.jslm_top_heading{background-color: <?php echo esc_attr( $color3 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_dashboard_wrapper div.jslm_dashboard_right_wrp div.jslm_myprofile div.jslm_profileleft_middle div.jslm_dashboard_course_list div.jslm_top_heading span.jslm_heading_left_name,
    div.jslm_content_data div.jslm_data_container div.jslm_dashboard_wrapper div.jslm_dashboard_right_wrp div.jslm_myprofile div.jslm_profileleft_middle div.jslm_dashboard_course_list div.jslm_top_heading span.jslm_heading_right_category,
    div.jslm_content_data div.jslm_data_container div.jslm_dashboard_wrapper div.jslm_dashboard_right_wrp div.jslm_myprofile div.jslm_profileleft_middle div.jslm_dashboard_course_list div.jslm_top_heading span.jslm_list_title{color: <?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_dashboard_wrapper div.jslm_dashboard_right_wrp div.jslm_myprofile div.jslm_profileleft_middle div.jslm_dashboard_course_list div.jslm_bottom_list span.jslm_heading_right_category,
    div.jslm_content_data div.jslm_data_container div.jslm_dashboard_wrapper div.jslm_dashboard_right_wrp div.jslm_myprofile div.jslm_profileleft_middle div.jslm_dashboard_course_list div.jslm_bottom_list span.jslm_reward_data,
    div.jslm_content_data div.jslm_data_container div.jslm_dashboard_wrapper div.jslm_dashboard_right_wrp div.jslm_myprofile div.jslm_profileleft_middle div.jslm_payout_container div.jslm_payout_data_list div.jslm_row span.jslm_quiz_title{color: <?php echo esc_attr( $color1 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_dashboard_wrapper div.jslm_dashboard_right_wrp div.jslm_myprofile div.jslm_profileleft_middle div.jslm_dashboard_course_list div.jslm_bottom_list{border-top: 1px solid <?php echo esc_attr( $color5 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_dashboard_wrapper div.jslm_dashboard_right_wrp div.jslm_myprofile div.jslm_profileleft_middle div.jslm_dashboard_course_list div.jslm_earning_graph{border: 1px solid <?php echo esc_attr( $color5 ); ?>}
    div.jslm_content_data div.jslm_data_container div.jslm_dashboard_wrapper div.jslm_dashboard_right_wrp div.jslm_myprofile div.jslm_profileleft_middle div.jslm_dashboard_earning_graph_warpper{border: 1px solid <?php echo esc_attr( $color5 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_dashboard_wrapper div.jslm_dashboard_right_wrp div.jslm_myprofile div.jslm_profileleft_middle div.jslm_dashboard_earning_graph_warpper div.jslm_earning_top{border: 1px solid <?php echo esc_attr( $color5 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_dashboard_wrapper div.jslm_dashboard_right_wrp div.jslm_myprofile div.jslm_profileleft_middle div.jslm_dashboard_earning_graph_warpper div.jslm_earning_top div.jslm_grap_top_content span.jslm_content_data:first-child i{color: #FFC300;}
    div.jslm_content_data div.jslm_data_container div.jslm_dashboard_wrapper div.jslm_dashboard_right_wrp div.jslm_myprofile div.jslm_profileleft_middle div.jslm_dashboard_earning_graph_warpper div.jslm_earning_top div.jslm_grap_top_content span.jslm_content_data i{color: #038edd;}
    div.jslm_content_data div.jslm_data_container div.jslm_dashboard_wrapper div.jslm_dashboard_right_wrp div.jslm_myprofile div.jslm_profileleft_middle div.jslm_dashboard_earning_graph_warpper div.jslm_earning_bottom div.jslm_earning_bottom_content_data{border: 1px solid <?php echo esc_attr( $color5 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_dashboard_wrapper div.jslm_dashboard_right_wrp div.jslm_myprofile div.jslm_profileleft_middle div.jslm_dashboard_earning_graph_warpper div.jslm_earning_bottom div.jslm_earning_bottom_content_data span.jslm_revenue_data{border-right: 1px solid <?php echo esc_attr( $color5 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_dashboard_wrapper div.jslm_dashboard_right_wrp div.jslm_myprofile div.jslm_profileleft_middle div.jslm_dashboard_earning_graph_warpper div.jslm_earning_bottom div.jslm_earning_bottom_content_data span.jslm_text{color:<?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_dashboard_wrapper div.jslm_dashboard_right_wrp div.jslm_myprofile div.jslm_profileleft_middle div.jslm_payout_container{border: 1px solid <?php echo esc_attr( $color5 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_dashboard_wrapper div.jslm_dashboard_right_wrp div.jslm_myprofile div.jslm_profileleft_middle div.jslm_payout_container div.jslm_top_heading span.jslm_list_title{color: <?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_dashboard_wrapper div.jslm_dashboard_right_wrp div.jslm_myprofile div.jslm_profileleft_middle div.jslm_payout_container div.jslm_top_heading{border-bottom: 1px solid <?php echo esc_attr( $color5 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_dashboard_wrapper div.jslm_dashboard_right_wrp div.jslm_myprofile div.jslm_profileleft_middle div.jslm_payout_container div.jslm_payout_data_list div.jslm_row{border-bottom: 1px solid <?php echo esc_attr( $color5 ); ?>;color: <?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_dashboard_wrapper div.jslm_dashboard_right_wrp div.jslm_myprofile div.jslm_profileleft_middle div.jslm_payout_container div.jslm_payout_data_list div.jslm_row div.jslm_date_name_wrp span.jslm_date{border: 1px solid <?php echo esc_attr( $color5 ); ?>;background-color: <?php echo esc_attr( $color3 );?>;color: <?php echo esc_attr( $color1 ); ?>}
    div.jslm_content_data div.jslm_data_container div.jslm_dashboard_wrapper div.jslm_dashboard_right_wrp div.jslm_myprofile div.jslm_profileleft_middle div.jslm_payout_container div.jslm_payout_data_list div.jslm_row div.jslm_course_approve_wrp span.jslm_status{border: 1px solid <?php echo esc_attr( $color5 ); ?>;color: <?php echo esc_attr( $color4 ); ?>;color: #fff;}
    div.jslm_content_data div.jslm_data_container div.jslm_dashboard_wrapper div.jslm_dashboard_right_wrp div.jslm_myprofile div.jslm_profileleft_middle div.jslm_payout_container div.jslm_payout_data_list div.jslm_row div.jslm_course_approve_wrp span.jslm_course_name a{color: <?php echo esc_attr( $color4 ); ?>}
    div.jslm_content_data div.jslm_data_container div.jslm_dashboard_wrapper div.jslm_dashboard_right_wrp div.jslm_myprofile div.jslm_profileleft_middle div.jslm_payout_container div.jslm_payout_data_list div.jslm_row div.jslm_course_approve_wrp span.jslm_course_name a:hover{color: <?php echo esc_attr( $color2 );?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_dashboard_wrapper div.jslm_dashboard_right_wrp div.jslm_myprofile div.jslm_profileleft_middle div.jslm_payout_container div.jslm_payout_data_list div.jslm_row div.jslm_price{color: <?php echo esc_attr( $color1 ); ?>}
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_tabs_wrapper div.jslm_my_content div.jslm_curriculum_wrapper div.jslm_curriculum_title div.jslm_add_new_btn button{border: 1px solid <?php echo esc_attr( $color2 ); ?>;background-color: <?php echo esc_attr( $color3 ); ?>;color: <?php echo esc_attr( $color1 ); ?>}
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_tabs_wrapper div.jslm_my_content div.jslm_curriculum_wrapper div.jslm_curriculum_title div.jslm_add_new_btn button:hover{background-color: <?php echo esc_attr( $color2 ); ?>;color: <?php echo esc_attr( $color7 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_dashboard_wrapper div.jslm_dashboard_right_wrp div.jslm_myprofile div.jslm_profileleft_middle ul.nav-tabs li.jslm_li_styling.active{background-color: <?php echo esc_attr( $color2 ); ?>;color: <?php echo esc_attr( $color7 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_dashboard_wrapper div.jslm_dashboard_right_wrp div.jslm_myprofile div.jslm_profileleft_middle div.jslm_dashboard_course_list div.jslm_bottom_list span.jslm_heading_left_name a,
    div.jslm_content_data div.jslm_data_container div.jslm_dashboard_wrapper div.jslm_dashboard_right_wrp div.jslm_myprofile div.jslm_profileleft_middle div.jslm_dashboard_course_list div.jslm_bottom_list span.jslm_list_title a{color: <?php echo esc_attr( $color2 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_dashboard_wrapper div.jslm_dashboard_right_wrp div.jslm_myprofile div.jslm_profileleft_middle ul.nav-tabs a.jslm_view_all{background-color: <?php echo esc_attr( $color6 ); ?>;border:1px solid <?php echo esc_attr( $color5 ); ?>;color: <?php echo esc_attr( $color1 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_dashboard_wrapper div.jslm_dashboard_right_wrp div.jslm_myprofile div.jslm_profileleft_middle ul.nav-tabs a.jslm_view_all:hover{color: <?php echo esc_attr( $color7 ); ?>;background-color: <?php echo esc_attr( $color2 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_dashboard_wrapper div.jslm_dashboard_left_wrp div.jslm_myprofile div.jslm_profileleft_middle span.jslm_edit a{color: <?php echo esc_attr( $color7 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_dashboard_wrapper div.jslm_dashboard_right_wrp div.jslm_myprofile div.jslm_profileleft_middle div.jslm_dashboard_course_list div.jslm_bottom_list span.jslm_list_title{color: <?php echo esc_attr( $color1 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_dashboard_wrapper div.jslm_dashboard_right_wrp div.jslm_myprofile div.jslm_profileleft_middle div.jslm_payout_container div.jslm_payout_data_list div.jslm_row span.jslm_student_name{color: <?php echo esc_attr( $color4 ); ?>;}

    div.jslm_content_data div.jslm_data_container div.jslm_dashboard_wrapper div.jslm_dashboard_right_wrp div.jslm_myprofile div.jslm_profileleft_middle div.jslm_dashboard_course_list table.jslm-table tr.jslm-table-row,
    div.jslm_content_data div.jslm_data_container div.jslm_earning_list table.jslm-table tr.jslm-table-row{border-bottom: 1px solid <?php echo esc_attr( $color5 ); ?>;background-color: <?php echo esc_attr( $color3 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_earning_list table.jslm-table tbody tr.jslm-table-row:last-child{border-bottom: none;}
    div.jslm_content_data div.jslm_data_container div.jslm_dashboard_wrapper div.jslm_dashboard_right_wrp div.jslm_myprofile div.jslm_profileleft_middle div.jslm_dashboard_course_list table.jslm-table tr.jslm-table-row th.jslm-table-row-heading,
    div.jslm_content_data div.jslm_data_container div.jslm_dashboard_wrapper div.jslm_dashboard_right_wrp div.jslm_myprofile div.jslm_profileleft_middle div.jslm_dashboard_course_list table.jslm-table tbody tr.jslm-table-row:last-child {border-bottom: none;}
    div.jslm_content_data div.jslm_data_container div.jslm_dashboard_wrapper div.jslm_dashboard_right_wrp div.jslm_myprofile div.jslm_profileleft_middle div.jslm_dashboard_course_list table.jslm-table tr.jslm-table-row th.jslm-table-row-heading{color: <?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_earning_list table.jslm-table tr.jslm-table-row th.jslm-table-row-heading{color: <?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_dashboard_wrapper div.jslm_dashboard_right_wrp div.jslm_myprofile div.jslm_profileleft_middle div.jslm_dashboard_course_list table.jslm-table tbody.jslm-table-body td.jslm-table-data,
    div.jslm_content_data div.jslm_data_container div.jslm_earning_list table.jslm-table tbody.jslm-table-body tr.jslm-table-row td.jslm-table-data{color: <?php echo esc_attr( $color1 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_dashboard_wrapper div.jslm_dashboard_right_wrp div.jslm_myprofile div.jslm_profileleft_middle div.jslm_dashboard_course_list table.jslm-table tbody.jslm-table-body td.jslm-table-data a,
    div.jslm_content_data div.jslm_data_container div.jslm_dashboard_wrapper div.jslm_dashboard_right_wrp div.jslm_myprofile div.jslm_profileleft_middle div.jslm_payout_container div.jslm_payout_data_list div.jslm_row div.jslm_date_name_wrp span.jslm_student_name a{color:<?php echo esc_attr( $color2 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_earning_list table.jslm-table tbody.jslm-table-body  tr.jslm-table-row td.jslm-table-data a{color: <?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_dashboard_wrapper div.jslm_dashboard_right_wrp div.jslm_myprofile div.jslm_profileleft_middle div.jslm_dashboard_course_list table.jslm-table tbody.jslm-table-body td.jslm-table-data::before {color: <?php echo esc_attr( $color1 ); ?>;}
    /*profile/register*/
    div.jslm_content_data div.jslm_data_container div.jslm_register_wrapper div.jslm_left_data{background-color:<?php echo esc_attr( $color3 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_register_wrapper div.jslm_left_data div.jslm_file_upload label.jslm_file_upload_label{border: 1px solid <?php echo esc_attr( $color5 ); ?>;background-color: <?php echo esc_attr( $color2 ); ?>;color: #fff;}
    div.jslm_content_data div.jslm_data_container div.jslm_register_wrapper div.jslm_left_data div.jslm_img_wrapper{background-color: <?php echo esc_attr( $color7 ); ?>;border:1px solid <?php echo esc_attr( $color5 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_register_wrapper div.jslm_left_data span.jslm_text{color: <?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_register_wrapper div.jslm_left_data span.jslm_text a.jslm_delete_image_user{color: <?php echo esc_attr( $color2 ); ?>}
    div.jslm_content_data div.jslm_data_container div.jslm_register_wrapper div.jslm_right_data div.jslm_input_wrapper div.jslm_input_title{color: <?php echo esc_attr( $color1 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_register_wrapper div.jslm_right_data div.jslm_input_wrapper div.jslm_input_field input.jslm_input_style,
    div.jslm_content_data div.jslm_data_container div.jslm_register_wrapper div.jslm_right_data div.jslm_input_wrapper div.jslm_input_field_select select.jslm_input_style{background-color: <?php echo esc_attr( $color3 ); ?>;border: 1px solid <?php echo esc_attr( $color5 ); ?>;color: <?php echo esc_attr( $color4 ); ?>}
    div.jslm_content_data div.jslm_data_container div.jslm_register_wrapper div.jslm_right_data div.jslm_input_wrapper div.jslm_input_field input.jslm_input_style::-webkit-input-placeholder {color:<?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_register_wrapper div.jslm_right_data div.jslm_input_wrapper div.jslm_input_field input.jslm_input_style::-moz-placeholder {color:<?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_register_wrapper div.jslm_right_data div.jslm_input_wrapper div.jslm_input_field input.jslm_input_style::-ms-placeholder {color:<?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_register_wrapper div.jslm_right_data div.jslm_input_wrapper div.jslm_input_field input.jslm_input_style::placeholder {color:<?php echo esc_attr( $color4 ); ?>;}




    div.jslm_content_data div.jslm_data_container div.jslm_register_wrapper div.jslm_right_data div.jslm_input_wrapper div.jslm_input_field select.jslm_select_style::-webkit-input-placeholder {color:<?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_register_wrapper div.jslm_right_data div.jslm_input_wrapper div.jslm_input_field select.jslm_select_style::-moz-placeholder {color:<?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_register_wrapper div.jslm_right_data div.jslm_input_wrapper div.jslm_input_field select.jslm_select_style::-ms-placeholder {color:<?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_register_wrapper div.jslm_right_data div.jslm_input_wrapper div.jslm_input_field select.jslm_select_style::placeholder {color:<?php echo esc_attr( $color4 ); ?>;}

    div.jslm_content_data div.jslm_data_container div.jslm_register_wrapper div.jslm_right_data div.jslm_input_wrapper div.jslm_input_field div.jslm_checkbox_cf div.jslm_checkbox_label{color:<?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_register_wrapper div.jslm_right_data div.jslm_input_wrapper div.jslm_input_field select{background-color: <?php echo esc_attr( $color3 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_register_wrapper div.jslm_bottom_button{border-top: 3px solid <?php echo esc_attr( $color2 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_register_wrapper div.jslm_bottom_button input.jslm_button{background-color: <?php echo esc_attr( $color2 ); ?>;border:1px solid <?php echo esc_attr( $color2 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_register_wrapper div.jslm_bottom_button input.jslm_button.jslm_cancel,
    div.jslm_content_data div.jslm_data_container div.jslm_register_wrapper div.jslm_bottom_button a.jslm_button.jslm_cancel{background-color: <?php echo esc_attr( $color6 ); ?>;color: <?php echo esc_attr( $color1 ); ?>;border:1px solid <?php echo esc_attr( $color5 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_register_wrapper div.jslm_bottom_button a input.jslm_button.jslm_cancel{border:1px solid <?php echo esc_attr( $color5 ); ?>;background-color: <?php echo esc_attr( $color3 ); ?>;color: <?php echo esc_attr( $color4 ); ?>;}
    /* profile detail */
    div.jslm_content_wrapper div.jslm_content_data div.jslm_instructor_wrapper div.jslm_left_data div.jslm_img_wrapper,
    div.jslm_content_wrapper div.jslm_content_data div.jslm_student_wrapper div.jslm_left_data div.jslm_img_wrapper{border: 1px solid <?php echo esc_attr( $color5 ); ?>;background-color: <?php echo esc_attr( $color3 ); ?>;}
    div.jslm_content_wrapper div.jslm_content_data div.jslm_instructor_wrapper div.jslm_left_data a.jslm_send_message_anchor,
    div.jslm_content_wrapper div.jslm_content_data div.jslm_student_wrapper div.jslm_left_data a.jslm_send_message_anchor,
    div.jslm_content_wrapper div.jslm_content_data div.jslm_student_wrapper div.jslm_left_data div.jslm_send_message_wrapper span.jslm_approve_status,
    div.jslm_content_wrapper div.jslm_content_data div.jslm_instructor_wrapper div.jslm_left_data div.jslm_send_message_wrapper span.jslm_approve_status{background-color: <?php echo esc_attr( $color2 ); ?>;color: #fff;}
    div.jslm_content_wrapper div.jslm_content_data div.jslm_instructor_wrapper div.jslm_left_data div.jslm_social_icons a.jslm_social.jslm_facebook,
    div.jslm_content_wrapper div.jslm_content_data div.jslm_student_wrapper div.jslm_left_data div.jslm_social_icons a.jslm_social.jslm_facebook{background-color: #3b5998;box-shadow: 0 4px 0 #2f4a84 !important;color: #fff;}
    div.jslm_content_wrapper div.jslm_content_data div.jslm_instructor_wrapper div.jslm_left_data div.jslm_social_icons a.jslm_social.jslm_twitter,
    div.jslm_content_wrapper div.jslm_content_data div.jslm_student_wrapper div.jslm_left_data div.jslm_social_icons a.jslm_social.jslm_twitter{background-color: #29c5f6;box-shadow: 0 3px 0 #0ca2d1 !important;color: #fff;}
    div.jslm_content_wrapper div.jslm_content_data div.jslm_instructor_wrapper div.jslm_left_data div.jslm_social_icons a.jslm_social.jslm_linkedin,
    div.jslm_content_wrapper div.jslm_content_data div.jslm_student_wrapper div.jslm_left_data div.jslm_social_icons a.jslm_social.jslm_linkedin{background-color: #006cbf;box-shadow: 0 3px 0 #1a5aa4 !important;color: #fff;}
    div.jslm_content_wrapper div.jslm_content_data div.jslm_instructor_wrapper div.jslm_right_data div.jslm_top_heading div.jslm_heading .jslm_heading_title,
    div.jslm_content_wrapper div.jslm_content_data div.jslm_student_wrapper div.jslm_right_data div.jslm_top_heading div.jslm_heading .jslm_heading_title{color: <?php echo esc_attr( $color1 ); ?>;}
    div.jslm_content_wrapper div.jslm_content_data div.jslm_instructor_wrapper div.jslm_right_data div.jslm_top_heading div.jslm_heading span.jslm_label,
    div.jslm_content_wrapper div.jslm_content_data div.jslm_student_wrapper div.jslm_right_data div.jslm_top_heading div.jslm_heading span.jslm_label{background-color: #3E4095;color: #fff;border: 1px solid <?php echo esc_attr( $color5 ); ?>;}
    div.jslm_content_wrapper div.jslm_content_data div.jslm_instructor_wrapper div.jslm_right_data div.jslm_top_heading div.jslm_info .jslm_icons,
    div.jslm_content_wrapper div.jslm_content_data div.jslm_student_wrapper div.jslm_right_data div.jslm_top_heading div.jslm_info .jslm_icons{color: <?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_wrapper div.jslm_content_data div.jslm_student_wrapper div.jslm_right_data div.jslm_top_heading div.jslm_info .jslm_icons:first-child{color: <?php echo esc_attr( $color2 ); ?>;}
    div.jslm_content_wrapper div.jslm_content_data div.jslm_instructor_wrapper div.jslm_right_data div.jslm_top_heading div.jslm_info .jslm_icons:first-child {color: <?php echo esc_attr( $color2 ); ?>;}
    div.jslm_content_wrapper div.jslm_content_data div.jslm_instructor_wrapper div.jslm_right_data div.jslm_top_heading div.jslm_info .jslm_icons:first-child i,
    div.jslm_content_wrapper div.jslm_content_data div.jslm_student_wrapper div.jslm_right_data div.jslm_top_heading div.jslm_info .jslm_icons:first-child i{color: <?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_wrapper div.jslm_content_data div.jslm_instructor_wrapper div.jslm_right_data div.jslm_middle_content span.jslm_text,
    div.jslm_content_wrapper div.jslm_content_data div.jslm_student_wrapper div.jslm_right_data div.jslm_middle_content span.jslm_text{color: <?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_wrapper div.jslm_content_data div.jslm_instructor_wrapper div.jslm_right_data div.jslm_bottom_content span.jslm_logo,
    div.jslm_content_wrapper div.jslm_content_data div.jslm_student_wrapper div.jslm_right_data div.jslm_bottom_content span.jslm_logo{background-color: <?php echo esc_attr( $color3 ); ?>;border:1px solid <?php echo esc_attr( $color5 ); ?>;color: <?php echo esc_attr( $color1 ); ?>}
    div.jslm_content_wrapper div.jslm_content_data div.jslm_instructor_wrapper div.jslm_right_data div.jslm_bottom_content span.jslm_logo span.jslm_right span.jslm_bc_text,
    div.jslm_content_wrapper div.jslm_content_data div.jslm_student_wrapper div.jslm_right_data div.jslm_bottom_content span.jslm_logo span.jslm_right span.jslm_bc_text,
    div.jslm_content_wrapper div.jslm_content_data div.jslm_instructor_wrapper div.jslm_right_data div.jslm_bottom_content span.jslm_logo span.jslm_right span.jslm_number,
    div.jslm_content_wrapper div.jslm_content_data div.jslm_student_wrapper div.jslm_right_data div.jslm_bottom_content span.jslm_logo span.jslm_right span.jslm_number{color: <?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_earn_bottom_revenue div.jslm_revenue_wrapper span.jslm_number.jslm_payout_yellow{color: #808000;}
    div.jslm_content_wrapper div.jslm_content_data div.jslm_student_wrapper div.jslm_right_data div.jslm_home_data_row div.jslm_custom_fields_wrapper div.jslm_custom_field span.jslm_heading{color: <?php echo esc_attr( $color1 ); ?>;}
    div.jslm_content_wrapper div.jslm_content_data div.jslm_student_wrapper div.jslm_right_data div.jslm_home_data_row div.jslm_custom_fields_wrapper div.jslm_custom_field span.jslm_text{color: <?php echo esc_attr( $color4 ); ?>;}
    /* Earning */
    div.jslm_content_data div.jslm_data_container div.jslm_graph_view div.jslm_graph_wrap{border: 1px solid <?php echo esc_attr( $color5 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_graph_view div.jslm_graph_wrap div.jslm_graph_view_top{background-color: <?php echo esc_attr( $color3 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_earn_bottom_revenue div.jslm_revenue_wrapper,
    div.jslm_content_data div.jslm_data_container div.jslm_earn_bottom_revenue div.jslm_payout_wrapper{border: 1px solid <?php echo esc_attr( $color5 ); ?>;background-color: <?php echo esc_attr( $color3 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_earn_bottom_revenue div.jslm_revenue_wrapper span.jslm_gross,
    div.jslm_content_data div.jslm_data_container div.jslm_earn_bottom_revenue div.jslm_payout_wrapper span.jslm_gross{color: <?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_earning_list{border:1px solid <?php echo esc_attr( $color5 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_earning_list div.jslm_earning_list_top_heading{background-color: <?php echo esc_attr( $color3 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_earning_list div.jslm_earning_list_top_heading span.jslm_title{color: <?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_earning_list div.jslm_earning_list_bottom_data div.jslm_payout_row span.jslm_title{color: <?php echo esc_attr( $color1 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_earning_list div.jslm_earning_list_bottom_data div.jslm_payout_row span.jslm_title a{color: <?php echo esc_attr( $color2 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_earning_list div.jslm_earning_list_bottom_data div.jslm_payout_row{border-top: 1px solid <?php echo esc_attr( $color5 ); ?>;}
    /* Add lecture */
    div.jslm_content_data div.jslm_data_container div.jslm_top_content_data{border:1px solid <?php echo esc_attr( $color5 ); ?>;background-color: <?php echo esc_attr( $color3 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_top_content_data div.jslm_title_content h4.jslm_title_heading a.jslm_course_title{color: <?php echo esc_attr( $color1 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_top_content_data div.jslm_title_content span.jslm_title_category{color: <?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_addlecture_wrapper div.jslm_form_wrapper div.jslm_input_field div.jslm_input_title {color: <?php echo esc_attr( $color1 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_addlecture_wrapper div.jslm_form_wrapper div.jslm_file_wrapper h6.jslm_heading{color: <?php echo esc_attr( $color1 ); ?>}
    div.jslm_content_data div.jslm_data_container div.jslm_addlecture_wrapper div.jslm_form_wrapper div.jslm_file_wrapper div.jslm_file_wrapper div.jslm_file_upload label.jslm_file_upload_label{background-color: <?php echo esc_attr( $color3 ); ?>;border:1px solid <?php echo esc_attr( $color5 ); ?>;color: <?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_addlecture_wrapper div.jslm_form_wrapper div.jslm_file_wrapper div.jslm_file_wrapper span.jslm_file_extension{color: <?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_addlecture_wrapper div.jslm_form_wrapper div.jslm_file_wrapper div.jslm_append_files div.jslm_heading_wrp h6.jslm_heading{color: <?php echo esc_attr( $color1 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_addlecture_wrapper div.jslm_form_wrapper div.jslm_file_wrapper div.jslm_append_files div.jslm_upload_files_wrapper input.jslm_input_field_style{border: 1px solid <?php echo esc_attr( $color5 ); ?>;background: <?php echo esc_attr( $color3 ); ?>}
    div.jslm_content_data div.jslm_data_container div.jslm_addlecture_wrapper div.jslm_form_wrapper div.jslm_file_wrapper div.jslm_img_list{/*border-bottom: 1px solid <?php echo esc_attr( $color5 ); ?>;*/}
    div.jslm_content_data div.jslm_data_container div.jslm_addlecture_wrapper div.jslm_form_wrapper div.jslm_file_wrapper div.jslm_img_list div.jslm_heading_wrp h6.jslm_heading{color: <?php echo esc_attr( $color1 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_addlecture_wrapper div.jslm_form_wrapper div.jslm_file_wrapper div.jslm_img_list div.jslm_row{border:1px solid <?php echo esc_attr( $color5 ); ?>;background-color: <?php echo esc_attr( $color3 ); ?>;color: <?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_addlecture_wrapper div.jslm_form_wrapper div.jslm_file_wrapper div.jslm_img_list div.jslm_row span.jslm_right span.jslm_logo{border: 1px solid <?php echo esc_attr( $color5 ); ?>;background: #fff;}
    div.jslm_content_data div.jslm_data_container div.jslm_addlecture_wrapper div.jslm_form_wrapper div.jslm_file_wrapper div.jslm_img_list div.jslm_row span.jslm_right span.jslm_logo:hover{background-color: <?php echo esc_attr( $color2 ); ?>;color: <?php echo esc_attr( $color7 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_addlecture_wrapper div.jslm_form_wrapper div.jslm_file_wrapper div.jslm_img_list div.jslm_row span.jslm_right span.jslm_logo:hover a.jslm_logos i{color: <?php echo esc_attr( $color7 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_addlecture_wrapper div.jslm_form_wrapper div.jslm_file_wrapper div.jslm_append_files div.jslm_upload_files_wrapper button.jslm_delete_button{background-color: <?php echo esc_attr( $color7 ); ?>;color: <?php echo esc_attr( $color4 ); ?>;border: 1px solid <?php echo esc_attr( $color5 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_addlecture_wrapper div.jslm_form_wrapper div.jslm_file_wrapper div.jslm_append_files div.jslm_upload_files_wrapper button.jslm_delete_button:hover{background-color: <?php echo esc_attr( $color2 ); ?>;color: <?php echo esc_attr( $color7 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_addlecture_wrapper div.jslm_form_wrapper div.jslm_file_wrapper div.jslm_file_wrapper input.jslm_video_title,
    div.jslm_content_data div.jslm_data_container div.jslm_addlecture_wrapper div.jslm_form_wrapper div.jslm_file_wrapper div.jslm_file_wrapper input.jslm_video_url{border: 1px solid <?php echo esc_attr( $color5 ); ?>;background-color: <?php echo esc_attr( $color3 ); ?>;border:1px solid <?php echo esc_attr( $color5 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_addlecture_wrapper div.jslm_form_wrapper div.jslm_file_wrapper div.jslm_file_wrapper div.jslm_save_button button.jslm_btn_style{border: 1px solid <?php echo esc_attr( $color2 ); ?>;color: #fff;background-color: <?php echo esc_attr( $color2 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_addlecture_wrapper div.jslm_form_wrapper div.jslm_file_wrapper div.jslm_img_list div.jslm_row span.jslm_video_left span.jslm_right_data span.jslm_video_url a.jslm_video_url_link{color: <?php echo esc_attr( $color2 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_addlecture_wrapper div.jslm_form_wrapper div.jslm_file_wrapper div.jslm_append_files div.jslm_upload_video_files_wrapper button.jslm_delete_button{background-color: <?php echo esc_attr( $color7 ); ?>;color: red;border:1px solid <?php echo esc_attr( $color5 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_addlecture_wrapper div.jslm_form_wrapper div.jslm_file_wrapper div.jslm_append_files div.jslm_upload_video_files_wrapper button.jslm_delete_button:hover{background-color: <?php echo esc_attr( $color2 ); ?>;color: <?php echo esc_attr( $color7 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_addlecture_wrapper div.jslm_form_wrapper div.jslm_file_wrapper div.jslm_append_files div.jslm_upload_video_files_wrapper input.jslm_input_video_title,
    div.jslm_content_data div.jslm_data_container div.jslm_addlecture_wrapper div.jslm_form_wrapper div.jslm_file_wrapper div.jslm_append_files div.jslm_upload_video_files_wrapper input.jslm_input_video_url,
    div.jslm_content_data div.jslm_data_container div.jslm_addlecture_wrapper div.jslm_form_wrapper div.jslm_file_wrapper div.jslm_quiz_wrapper div.jslm_quiz_content div.jslm_quiz_heading input.jslm_ques_input_style,
    div.jslm_content_data div.jslm_data_container div.jslm_addlecture_wrapper div.jslm_form_wrapper div.jslm_file_wrapper div.jslm_quiz_wrapper div.jslm_quiz_content div.jslm_quiz_body div.jslm_row_data div.jslm_choice div.jslm_quiz_input_wrapper input.jslm_input_style{border-color: <?php echo esc_attr( $color5 ); ?>;background-color: <?php echo esc_attr( $color3 ); ?>;color: <?php echo esc_attr( $color4 ); ?>}
    div.jslm_content_data div.jslm_data_container div.jslm_addlecture_wrapper div.jslm_form_wrapper div.jslm_file_wrapper div.jslm_quiz_wrapper div.jslm_quiz_content div.jslm_quiz_body div.jslm_add_more_button button.jslm_add_more_button_styling{border: 1px solid <?php echo esc_attr( $color5 ); ?>;color: <?php echo esc_attr( $color4 ); ?>;background-color: <?php echo esc_attr( $color3 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_addlecture_wrapper div.jslm_form_wrapper div.jslm_file_wrapper div.jslm_quiz_wrapper div.jslm_quiz_content div.jslm_quiz_body div.jslm_button_style button{border:1px solid <?php echo esc_attr( $color2 ); ?>;background-color: <?php echo esc_attr( $color2 ); ?>;color: #fff;}
    div.jslm_content_data div.jslm_data_container div.jslm_addlecture_wrapper div.jslm_form_wrapper div.jslm_file_wrapper div.jslm_img_list div.jslm_quiz_wrapper div.jslm_heading span.jslm_right_option span.jslm_logo{border: 1px solid <?php echo esc_attr( $color5 ); ?>;color: <?php echo esc_attr( $color4 ); ?>;background: #fff;}
    div.jslm_content_data div.jslm_data_container div.jslm_addlecture_wrapper div.jslm_form_wrapper div.jslm_file_wrapper div.jslm_img_list div.jslm_quiz_wrapper div.jslm_heading span.jslm_right_option span.jslm_logo:hover{background-color: <?php echo esc_attr( $color2 ); ?>;color: <?php echo esc_attr( $color7 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_addlecture_wrapper div.jslm_form_wrapper div.jslm_file_wrapper div.jslm_img_list div.jslm_quiz_wrapper div.jslm_heading span.jslm_right_option span.jslm_logo:hover a i{color: <?php echo esc_attr( $color7 ); ?>}
    div.jslm_content_data div.jslm_data_container div.jslm_addlecture_wrapper div.jslm_form_wrapper div.jslm_file_wrapper div.jslm_img_list div.jslm_quiz_wrapper{border:1px solid <?php echo esc_attr( $color5 ); ?>;background-color: <?php echo esc_attr( $color3 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_addlecture_wrapper div.jslm_form_wrapper div.jslm_file_wrapper div.jslm_img_list div.jslm_quiz_wrapper div.jslm_heading div.jslm_left_heading h5{color: <?php echo esc_attr( $color4 ); ?>}
    div.jslm_content_data div.jslm_data_container div.jslm_addlecture_wrapper div.jslm_form_wrapper div.jslm_save_button{border-top: 2px solid <?php echo esc_attr( $color2 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_addlecture_wrapper div.jslm_form_wrapper div.jslm_save_button button.jslm_btn_style{background-color: <?php echo esc_attr( $color2 ); ?>;color: #fff;}
    div.jslm_content_data div.jslm_data_container div.jslm_addlecture_wrapper div.jslm_form_wrapper div.jslm_save_button button.jslm_btn_style.jslm_save_new{background-color: <?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_addlecture_wrapper div.jslm_form_wrapper div.jslm_save_button button.jslm_btn_style.jslm_cancel{background-color: <?php echo esc_attr( $color3 ); ?>;color: <?php echo esc_attr( $color4 ); ?>;border:1px solid <?php echo esc_attr( $color5 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_addlecture_wrapper div.jslm_form_wrapper div.jslm_file_wrapper div.jslm_heading_wrp{border-top: 1px solid <?php echo esc_attr( $color5 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_addlecture_wrapper div.jslm_form_wrapper div.jslm_file_wrapper div.jslm_quiz_wrapper span.jslm_delete a.jslm_delete_button i{color: <?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_addlecture_wrapper div.jslm_form_wrapper div.jslm_file_wrapper div.jslm_quiz_wrapper div.jslm_quiz_body div.jslm_row_data div.jslm_choice{border:1px solid <?php echo esc_attr( $color5 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_addlecture_wrapper div.jslm_form_wrapper div.jslm_file_wrapper div.jslm_quiz_wrapper div.jslm_quiz_body div.jslm_row_data div.jslm_choice div.jslm_quiz_input_wrapper + span.jslm_delete {border-left: 1px solid <?php echo esc_attr( $color5 ); ?> !important;}
    div.jslm_content_data div.jslm_data_container div.jslm_addlecture_wrapper div.jslm_form_wrapper div.jslm_file_wrapper div.jslm_quiz_wrapper div.jslm_quiz_heading input {border: 1px solid <?php echo esc_attr( $color5 ); ?>;background: <?php echo esc_attr( $color3 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_addlecture_wrapper div.jslm_form_wrapper div.jslm_file_wrapper div#new_upload_questions div.jslm_quiz_wrapper div.jslm_heading_width + span.jslm_delete a.jslm_delete_button {border: 1px solid <?php echo esc_attr( $color5 ); ?>;border-left: none;background: <?php echo esc_attr( $color7 ); ?>;color: <?php echo esc_attr( $color1 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_addlecture_wrapper div.jslm_form_wrapper div.jslm_input_field div.jslm_input input.jslm_input_field_style::-webkit-input-placeholder {color:<?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_addlecture_wrapper div.jslm_form_wrapper div.jslm_input_field div.jslm_input input.jslm_input_field_style::-moz-placeholder {color:<?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_addlecture_wrapper div.jslm_form_wrapper div.jslm_input_field div.jslm_input input.jslm_input_field_style::-ms-placeholder {color:<?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_addlecture_wrapper div.jslm_form_wrapper div.jslm_input_field div.jslm_input input.jslm_input_field_style::placeholder {color:<?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_addlecture_wrapper div.jslm_form_wrapper div.jslm_file_wrapper div.jslm_file_wrapper input.jslm_video_url::-webkit-input-placeholder {color:<?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_addlecture_wrapper div.jslm_form_wrapper div.jslm_file_wrapper div.jslm_file_wrapper input.jslm_video_url::-moz-placeholder {color:<?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_addlecture_wrapper div.jslm_form_wrapper div.jslm_file_wrapper div.jslm_file_wrapper input.jslm_video_url::-ms-placeholder {color:<?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_addlecture_wrapper div.jslm_form_wrapper div.jslm_file_wrapper div.jslm_file_wrapper input.jslm_video_url::placeholder {color:<?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_addlecture_wrapper div.jslm_form_wrapper div.jslm_file_wrapper div.jslm_file_wrapper input.jslm_video_title::-webkit-input-placeholder {color:<?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_addlecture_wrapper div.jslm_form_wrapper div.jslm_file_wrapper div.jslm_file_wrapper input.jslm_video_title::-moz-placeholder {color:<?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_addlecture_wrapper div.jslm_form_wrapper div.jslm_file_wrapper div.jslm_file_wrapper input.jslm_video_title::-ms-placeholder {color:<?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_addlecture_wrapper div.jslm_form_wrapper div.jslm_file_wrapper div.jslm_file_wrapper input.jslm_video_title::placeholder {color:<?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_addlecture_wrapper div.jslm_form_wrapper div.jslm_file_wrapper div.jslm_quiz_wrapper div.jslm_quiz_content div.jslm_quiz_heading input.jslm_ques_input_style::-webkit-input-placeholder {color:<?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_addlecture_wrapper div.jslm_form_wrapper div.jslm_file_wrapper div.jslm_quiz_wrapper div.jslm_quiz_content div.jslm_quiz_heading input.jslm_ques_input_style::-moz-placeholder {color:<?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_addlecture_wrapper div.jslm_form_wrapper div.jslm_file_wrapper div.jslm_quiz_wrapper div.jslm_quiz_content div.jslm_quiz_heading input.jslm_ques_input_style::-ms-placeholder {color:<?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_addlecture_wrapper div.jslm_form_wrapper div.jslm_file_wrapper div.jslm_quiz_wrapper div.jslm_quiz_content div.jslm_quiz_heading input.jslm_ques_input_style::placeholder {color:<?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_addlecture_wrapper div.jslm_form_wrapper div.jslm_file_wrapper div.jslm_quiz_wrapper div.jslm_quiz_body div.jslm_row_data div.jslm_choice div.jslm_quiz_input_wrapper div.jslm_input_wrp input.jslm_input_style::-webkit-input-placeholder {color:<?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_addlecture_wrapper div.jslm_form_wrapper div.jslm_file_wrapper div.jslm_quiz_wrapper div.jslm_quiz_body div.jslm_row_data div.jslm_choice div.jslm_quiz_input_wrapper div.jslm_input_wrp input.jslm_input_style::-moz-placeholder {color:<?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_addlecture_wrapper div.jslm_form_wrapper div.jslm_file_wrapper div.jslm_quiz_wrapper div.jslm_quiz_body div.jslm_row_data div.jslm_choice div.jslm_quiz_input_wrapper div.jslm_input_wrp input.jslm_input_style::-ms-placeholder {color:<?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_addlecture_wrapper div.jslm_form_wrapper div.jslm_file_wrapper div.jslm_quiz_wrapper div.jslm_quiz_body div.jslm_row_data div.jslm_choice div.jslm_quiz_input_wrapper div.jslm_input_wrp input.jslm_input_style::placeholder {color:<?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_addlecture_wrapper div.jslm_form_wrapper div.jslm_file_wrapper div.jslm_quiz_wrapper div.jslm_quiz_content div.jslm_quiz_body div.jslm_row_data div.jslm_choice div.jslm_quiz_input_wrapper div.jslm_checkbox_wrp{border-right: 1px solid #00a659;}
    /* Message */
    div.jslm_content_data div.jslm_data_container div.jslm_message_wrapper{border:1px solid <?php echo esc_attr( $color5 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_message_wrapper div.jslm_top{border-bottom: 1px solid <?php echo esc_attr( $color5 ); ?>;background-color: <?php echo esc_attr( $color3 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_message_wrapper div.jslm_top div.jslm_top_left span.jslm_text{color: <?php echo esc_attr( $color1 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_message_wrapper div.jslm_top div.jslm_top_right span.jslm_box_alert{border: 1px solid <?php echo esc_attr( $color3 ); ?>;background-color: <?php echo esc_attr( $color2 ); ?>;color: <?php echo esc_attr( $color7 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_message_wrapper div.jslm_top div.jslm_top_right span.jslm_msg_box{border:1px solid <?php echo esc_attr( $color5 ); ?>;color: <?php echo esc_attr( $color1 ); ?>;background-color: <?php echo esc_attr( $color7 ); ?>;color: #fff;}
    div.jslm_content_data div.jslm_data_container div.jslm_message_wrapper div.jslm_top div.jslm_top_right span.jslm_date{color: <?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_message_wrapper div.jslm_top div.jslm_top_right span.jslm_msg_box:hover{background-color: <?php echo esc_attr( $color2 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_message_wrapper div.jslm_top div.jslm_top_right span.jslm_msg_box:hover span.jslm_msg_box_inner a{color: <?php echo esc_attr( $color7 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_message_wrapper div.jslm_top div.jslm_top_right span.jslm_msg_box span.jslm_msg_box_inner a{color: <?php echo esc_attr( $color1 ); ?>}
    div.jslm_content_data div.jslm_data_container div.jslm_message_wrapper div.jslm_top div.jslm_top_left div.jslm_img_wrapper {border:1px solid <?php echo esc_attr( $color5 ); ?>;background: #fff;}
    div.jslm_content_data div.jslm_data_container div.jslm_message_wrapper div.jslm_bottom span.jslm_bottom_text{color: <?php echo esc_attr( $color1 ); ?>}
    div.jslm_content_data div.jslm_data_container div.jslm_form_container div.jslm_button_container button{background-color: <?php echo esc_attr( $color2 ); ?>;color: <?php echo esc_attr( $color7 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_history_container div.jslm_history_wrapper div.jslm_row{border:1px solid <?php echo esc_attr( $color5 ); ?>;background-color: <?php echo esc_attr( $color3 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_history_container div.jslm_history_wrapper div.jslm_row div.jslm_left_side div.jslm_img_wrapper{border:1px solid <?php echo esc_attr( $color5 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_history_container div.jslm_history_wrapper div.jslm_row div.jslm_right_side div.jslm_bottom_row span.jslm_text{color: <?php echo esc_attr( $color4 );?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_history_container div.jslm_history_wrapper div.jslm_row div.jslm_right_side div.jslm_top_row span.jslm_sender_label.jslm_green{background-color: <?php echo esc_attr( $color2 ); ?>;color: #fff;}
    div.jslm_content_data div.jslm_data_container div.jslm_history_container div.jslm_history_wrapper div.jslm_row div.jslm_right_side div.jslm_top_row span.jslm_sender_label.jslm_white{background-color: <?php echo esc_attr( $color7 ); ?>;color: <?php echo esc_attr( $color4 ); ?>;border: 1px solid <?php echo esc_attr( $color5 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_history_container div.jslm_history_wrapper div.jslm_row div.jslm_right_side div.jslm_top_row span.jslm_date{color: <?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_message_wrapper div.jslm_bottom_message{color: <?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_history_container div.jslm_history_wrapper div.jslm_row.jslm_gray_bg{background-color: <?php echo esc_attr( $color3 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_history_container div.jslm_history_wrapper div.jslm_row.jslm_white_bg{background-color: <?php echo esc_attr( $color7 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_history_container div.jslm_history_wrapper div.jslm_row:last-child{border-bottom: 1px solid <?php echo esc_attr( $color5 ); ?>;}

    /* register */
    div.jslm_content_data div.jslm_register_wrapper div.jslm_register_student div.jslm_register_as_a_student div.jslm_title .jslm_title_heading,
    div.jslm_content_data div.jslm_register_wrapper div.jslm_register_instructor div.jslm_register_as_a_instructor div.jslm_title .jslm_title_heading{color: <?php echo esc_attr( $color2 ); ?>}
    div.jslm_content_data div.jslm_register_wrapper div.jslm_register_student div.jslm_register_as_a_student div.jslm_text,
    div.jslm_content_data div.jslm_register_wrapper div.jslm_register_instructor div.jslm_register_as_a_instructor div.jslm_text{color: <?php echo esc_attr( $color4 ); ?>}
    div.jslm_content_data div.jslm_top_heading h1.jslm_heading_styling{color: <?php echo esc_attr( $color1 ); ?>;}
    div.jslm_content_data div.jslm_top_heading span.jslm_intructor_register_text{color:<?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_nav_tab_wrapper ul.nav-tabs.jslm_ul_styling{border:1px solid <?php echo esc_attr( $color5 ); ?>;background-color: <?php echo esc_attr( $color3 ); ?>;color: <?php echo esc_attr( $color4 ); ?>}
    div.jslm_content_data div.jslm_nav_tab_wrapper ul.nav-tabs.jslm_ul_styling li.jslm_li_styling_reg{border-right: 1px solid <?php echo esc_attr( $color5 ); ?>;}
    div.jslm_content_data div.jslm_nav_tab_wrapper ul.nav-tabs.jslm_ul_styling li.jslm_li_styling_reg:last-child{border-right: unset;}
    div.jslm_content_data div.jslm_nav_tab_wrapper ul.nav-tabs.jslm_ul_styling li.jslm_li_styling_reg a.jslm_anchor_style{color: <?php echo esc_attr( $color4 ); ?>}
    div.jslm_content_data div.jslm_tab_content_reg span.jslm_text{color: <?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_nav_tab_wrapper{border-bottom: 1px solid <?php echo esc_attr( $color5 ); ?>;}
    div.jslm_content_data div.jslm_register_wrapper div.jslm_register_student div.jslm_register_as_a_student,
    div.jslm_content_data div.jslm_register_wrapper div.jslm_register_instructor div.jslm_register_as_a_instructor{background-color: <?php echo esc_attr( $color3 ); ?>;border:1px solid <?php echo esc_attr( $color5 ); ?>;}
    div.jslm_content_data div.jslm_register_wrapper div.jslm_register_student a.jslm_register_anchor:hover ,
    div.jslm_content_data div.jslm_register_wrapper div.jslm_register_instructor a.jslm_register_anchor:hover {border-color: <?php echo esc_attr( $color2 ); ?>;}
    /* Lecture View */
    div.jslm_content_data div.jslm_data_container div.jslm_lecture_curriculum_wrp div.jslm_left_section_list{border: 1px solid <?php echo esc_attr( $color5 ); ?>;background-color: <?php echo esc_attr( $color3 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_lecture_curriculum_wrp div.jslm_left_section_list div.jslm_accordian_wrp div.jslm-panel-group div.jslm_panel_style{border: 1px solid <?php echo esc_attr( $color5 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_lecture_curriculum_wrp div.jslm_left_section_list div.jslm_accordian_wrp div.jslm-panel-group div.jslm_panel_style div.panel-heading div.jslm_panel_title div.jslm_edit_section_wrapper h5.jslm_section_heading{color: <?php echo esc_attr( $color1 ); ?>;border-bottom: 1px solid <?php echo esc_attr( $color5 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_lecture_curriculum_wrp div.jslm_left_section_list div.jslm_accordian_wrp div.jslm-panel-group div.jslm_panel_style div.panel-collapse div.jslm_lecture_panel_style div.jslm_panel_body_wrapper div.jslm_row_data{border:1px solid <?php echo esc_attr( $color5 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_lecture_curriculum_wrp div.jslm_left_section_list div.jslm_accordian_wrp div.jslm-panel-group div.jslm_panel_style div.panel-collapse div.jslm_lecture_panel_style div.jslm_panel_body_wrapper div.jslm_row_data a.jslm_view_section_anchor span.jslm_title{color: <?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_lecture_curriculum_wrp div.jslm_left_section_list div.jslm_accordian_wrp div.jslm-panel-group div.jslm_panel_style div.panel-collapse div.jslm_lecture_panel_style div.jslm_panel_body_wrapper div.jslm_row_data:hover{border-color: <?php echo esc_attr( $color2 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_lecture_curriculum_wrp div.jslm_left_section_list div.jslm_accordian_wrp div.jslm-panel-group div.jslm_panel_style div.panel-collapse div.jslm_lecture_panel_style div.jslm_panel_body_wrapper div.jslm_row_data.jslm_border_color_primary{border-color: <?php echo esc_attr( $color2 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_lecture_curriculum_wrp div.jslm_curriculum_right_wrp div.jslm_lecture_heading{color: <?php echo esc_attr( $color1 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_lecture_curriculum_wrp div.jslm_curriculum_right_wrp div.jslm_right_content_wrapper ul.jslm_modal_ul{border-bottom: 2px solid <?php echo esc_attr( $color2 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_lecture_curriculum_wrp div.jslm_curriculum_right_wrp div.jslm_right_content_wrapper ul.jslm_modal_ul li.jslm_modal_li{border: 1px solid <?php echo esc_attr( $color5 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_lecture_curriculum_wrp div.jslm_curriculum_right_wrp div.jslm_right_content_wrapper ul.jslm_modal_ul li.jslm_modal_li.active{background-color: <?php echo esc_attr( $color2 ); ?>; color: <?php echo esc_attr( $color7 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_lecture_curriculum_wrp div.jslm_curriculum_right_wrp div.jslm_right_content_wrapper ul.jslm_modal_ul li.jslm_modal_li.active a.jslm_modal_anchor{color: <?php echo esc_attr( $color7 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_lecture_curriculum_wrp div.jslm_curriculum_right_wrp div.jslm_right_content_wrapper ul.jslm_modal_ul li.jslm_modal_li:first-child{border-left: 1px solid <?php echo esc_attr( $color5 ); ?>}
    div.jslm_content_data div.jslm_data_container div.jslm_lecture_curriculum_wrp div.jslm_curriculum_right_wrp div.jslm_right_content_wrapper div.tab-content div.jslm_lecture_content_wrapper div.jslm_middle_content div.jslm_heading h4.jslm_heading_style,
    div.jslm_content_data div.jslm_data_container div.jslm_lecture_curriculum_wrp div.jslm_curriculum_right_wrp div.jslm_right_content_wrapper div.tab-content div.jslm_lecture_content_wrapper div.jslm_custom_fields_wrapper div.jslm_custom_fields_heading h4.jslm_heading_style{color: <?php echo esc_attr( $color1 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_lecture_curriculum_wrp div.jslm_curriculum_right_wrp div.jslm_right_content_wrapper div.tab-content div.jslm_lecture_content_wrapper div.jslm_middle_content div.jslm_description{color: <?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_lecture_curriculum_wrp div.jslm_curriculum_right_wrp div.jslm_right_content_wrapper div.tab-content div.jslm_lecture_content_wrapper div.jslm-iframe{border: 1px solid <?php echo esc_attr( $color5 ); ?>;background-color: <?php echo esc_attr( $color3 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_lecture_curriculum_wrp div.jslm_curriculum_right_wrp div.jslm_right_content_wrapper div.tab-content div.jslm_lecture_content_wrapper div.jslm-iframe a i{background-color: <?php echo esc_attr( $color2 ); ?>;color: <?php echo esc_attr( $color7 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_lecture_curriculum_wrp div.jslm_curriculum_right_wrp div.jslm_right_content_wrapper div.tab-content div.jslm_lecture_content_wrapper div.jslm_download_all a{background-color: <?php echo esc_attr( $color6 ); ?>;color: <?php echo esc_attr( $color1 ); ?>;border:1px solid <?php echo esc_attr( $color5 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_lecture_curriculum_wrp div.jslm_curriculum_right_wrp div.jslm_right_content_wrapper div.tab-content div.jslm_lecture_content_wrapper div.jslm_download_all a:hover{background-color: <?php echo esc_attr( $color2 ); ?>;color: <?php echo esc_attr( $color7 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_lecture_curriculum_wrp div.jslm_bottom_actions_button {border-top: 1px solid <?php echo esc_attr( $color5 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_lecture_curriculum_wrp div.jslm_bottom_actions_button div.jslm_left_button a.jslm_back,
    div.jslm_content_data div.jslm_data_container div.jslm_lecture_curriculum_wrp div.jslm_bottom_actions_button div.jslm_right_button a.jslm_next{border:1px solid <?php echo esc_attr( $color5 ); ?>;background-color: <?php echo esc_attr( $color3 ); ?>;color: <?php echo esc_attr( $color4 ); ?>}
    div.jslm_content_data div.jslm_data_container div.jslm_lecture_curriculum_wrp div.jslm_curriculum_right_wrp div.jslm_right_content_wrapper div.tab-content div.jslm_lecture_content_wrapper div.jslm_files_content_data{border:1px solid <?php echo esc_attr( $color5 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_lecture_curriculum_wrp div.jslm_curriculum_right_wrp div.jslm_right_content_wrapper div.tab-content div.jslm_lecture_content_wrapper div.jslm_files_content_data span.jslm_left_data i,
    div.jslm_content_data div.jslm_data_container div.jslm_lecture_curriculum_wrp div.jslm_curriculum_right_wrp div.jslm_right_content_wrapper div.tab-content div.jslm_lecture_content_wrapper div.jslm_files_content_data span.jslm_left_data a{color: <?php echo esc_attr( $color4 ); ?>}
    div.jslm_content_data div.jslm_data_container div.jslm_lecture_curriculum_wrp div.jslm_curriculum_right_wrp div.jslm_right_content_wrapper div.tab-content div.jslm_lecture_content_wrapper div.jslm_files_content_data span.jslm_right_anchor a.jslm_actions{border:1px solid <?php echo esc_attr( $color5 ); ?>;color: <?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_lecture_curriculum_wrp div.jslm_curriculum_right_wrp div.jslm_right_content_wrapper div.tab-content div.jslm_lecture_content_wrapper div.jslm_files_content_data span.jslm_right_anchor a.jslm_actions:hover{background-color: <?php echo esc_attr( $color2 ); ?>;color: <?php echo esc_attr( $color7 ); ?>}
    div.jslm_content_data div.jslm_data_container div.jslm_lecture_curriculum_wrp div.jslm_curriculum_right_wrp div.jslm_right_content_wrapper div.tab-content div.jslm_lecture_content_wrapper div.jslm_quiz_result_content div.jslm_result_top_content h2.jslm_heading_style{color: <?php echo esc_attr( $color1 ); ?>}
    div.jslm_content_data div.jslm_data_container div.jslm_lecture_curriculum_wrp div.jslm_curriculum_right_wrp div.jslm_right_content_wrapper div.tab-content div.jslm_lecture_content_wrapper div.jslm_quiz_result_content div.jslm_result_top_content h2.jslm_heading_style span.jslm_color{color: <?php echo esc_attr( $color2 ); ?>}
    div.jslm_content_data div.jslm_data_container div.jslm_lecture_curriculum_wrp div.jslm_curriculum_right_wrp div.jslm_right_content_wrapper div.tab-content div.jslm_lecture_content_wrapper div.jslm_quiz_result_content div.jslm_result_middle_content div.jslm_answer span.jslm_cirle_answer{border:1px solid <?php echo esc_attr( $color2 ); ?>;color: <?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_lecture_curriculum_wrp div.jslm_curriculum_right_wrp div.jslm_right_content_wrapper div.tab-content div.jslm_lecture_content_wrapper div.jslm_quiz_result_content div.jslm_result_middle_content div.jslm_answer span.jslm_cirle_answer:nth-child(2){border-color: #f00;}
    div.jslm_content_data div.jslm_data_container div.jslm_lecture_curriculum_wrp div.jslm_curriculum_right_wrp div.jslm_right_content_wrapper div.tab-content div.jslm_lecture_content_wrapper div.jslm_quiz_result_content div.jslm_result_middle_content div.jslm_answer span.jslm_cirle_answer:nth-child(3){border-color: #789;}
    div.jslm_content_data div.jslm_data_container div.jslm_lecture_curriculum_wrp div.jslm_curriculum_right_wrp div.jslm_right_content_wrapper div.tab-content div.jslm_lecture_content_wrapper div.jslm_quiz_result_content div.jslm_result_middle_content div.jslm_answer span.jslm_cirle_answer:nth-child(4){border-color: #038edd;}
    div.jslm_content_data div.jslm_data_container div.jslm_lecture_curriculum_wrp div.jslm_curriculum_right_wrp div.jslm_right_content_wrapper div.tab-content div.jslm_lecture_content_wrapper div.jslm_quiz_result_content div.jslm_result_middle_content div.jslm_answer span.jslm_cirle_answer span.jslm_marks{color: <?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_lecture_curriculum_wrp div.jslm_curriculum_right_wrp div.jslm_right_content_wrapper div.tab-content div.jslm_lecture_content_wrapper div.jslm_quiz_result_content div.jslm_result_middle_content div.jslm_answer span.jslm_cirle_answer:nth-child(2) span.jslm_marks{color: #f00;}
    div.jslm_content_data div.jslm_data_container div.jslm_lecture_curriculum_wrp div.jslm_curriculum_right_wrp div.jslm_right_content_wrapper div.tab-content div.jslm_lecture_content_wrapper div.jslm_quiz_result_content div.jslm_result_middle_content div.jslm_answer span.jslm_cirle_answer:nth-child(3) span.jslm_marks{color: #789;}
    div.jslm_content_data div.jslm_data_container div.jslm_lecture_curriculum_wrp div.jslm_curriculum_right_wrp div.jslm_right_content_wrapper div.tab-content div.jslm_lecture_content_wrapper div.jslm_quiz_result_content div.jslm_result_middle_content div.jslm_answer span.jslm_cirle_answer:nth-child(4) span.jslm_marks{color: #038edd;}
    div.jslm_content_data div.jslm_data_container div.jslm_lecture_curriculum_wrp div.jslm_curriculum_right_wrp div.jslm_right_content_wrapper div.tab-content div.jslm_lecture_content_wrapper div.jslm_quiz_result_content div.jslm_result_middle_content div.jslm_remarks{color: <?php echo esc_attr( $color4 ); ?>}
    div.jslm_content_data div.jslm_data_container div.jslm_lecture_curriculum_wrp div.jslm_curriculum_right_wrp div.jslm_right_content_wrapper div.tab-content div.jslm_lecture_content_wrapper div.jslm_custom_fields_wrapper{border-top:1px solid <?php echo esc_attr( $color5 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_lecture_curriculum_wrp div.jslm_curriculum_right_wrp div.jslm_right_content_wrapper div.tab-content div.jslm_lecture_content_wrapper div.jslm_custom_fields_wrapper div.jslm_custom_field span.jslm_heading{color: <?php echo esc_attr( $color1 ); ?>}
    div.jslm_content_data div.jslm_data_container div.jslm_lecture_curriculum_wrp div.jslm_curriculum_right_wrp div.jslm_right_content_wrapper div.tab-content div.jslm_lecture_content_wrapper div.jslm_custom_fields_wrapper div.jslm_custom_field span.jslm_text{color: <?php echo esc_attr( $color4 ); ?>}
    div.jslm_content_data div.jslm_data_container div.jslm_lecture_curriculum_wrp div.jslm_curriculum_right_wrp div.jslm_right_content_wrapper div.tab-content div.jslm_lecture_content_wrapper div.jslm_quiz_result_content div.jslm_result_bottom_content div.jslm_retake_button_wrapper a.jslm_retake_button{border: 1px solid <?php echo esc_attr( $color5 ); ?>;background-color: <?php echo esc_attr( $color2 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_lecture_curriculum_wrp div.jslm_curriculum_right_wrp div.jslm_right_content_wrapper ul.jslm_modal_ul li.jslm_modal_li a.jslm_modal_anchor{color: <?php echo esc_attr( $color1 ); ?>}
    div.jslm_content_data div.jslm_data_container div.jslm_lecture_curriculum_wrp div.jslm_curriculum_right_wrp div.jslm_right_content_wrapper div.tab-content div.jslm_lecture_content_wrapper div.jslm_quiz_container div.jslm_question_top_content{border:1px solid <?php echo esc_attr( $color5 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_lecture_curriculum_wrp div.jslm_curriculum_right_wrp div.jslm_right_content_wrapper div.tab-content div.jslm_lecture_content_wrapper div.jslm_quiz_container div.jslm_question_bottom_content div.jslm_mscq_wrapper{border:1px solid <?php echo esc_attr( $color5 ); ?>;background-color: #fff;}
    div.jslm_content_data div.jslm_data_container div.jslm_lecture_curriculum_wrp div.jslm_curriculum_right_wrp div.jslm_right_content_wrapper div.tab-content div.jslm_lecture_content_wrapper div.jslm_quiz_container div.jslm_question_bottom_content div.jslm_button a.jslm_next_button_anchor{border:1px solid <?php echo esc_attr( $color5 ); ?>;background-color: <?php echo esc_attr( $color2 ); ?>;color: #fff;}
    div.jslm_content_data div.jslm_data_container div.jslm_lecture_curriculum_wrp div.jslm_curriculum_right_wrp div.jslm_right_content_wrapper div.tab-content div.jslm_lecture_content_wrapper div.jslm_quiz_container div.jslm_question_bottom_content div.jslm_button a.jslm_previous_button_anchor,
    div.jslm_content_data div.jslm_data_container div.jslm_lecture_curriculum_wrp div.jslm_curriculum_right_wrp div.jslm_right_content_wrapper div.tab-content div.jslm_lecture_content_wrapper div.jslm_quiz_container div.jslm_question_bottom_content div.jslm_button button.jslm_next_button_anchor{border:1px solid <?php echo esc_attr( $color5 ); ?>;background-color: <?php echo esc_attr( $color3 ); ?>;color: <?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_tabs_wrapper div.jslm_my_content div.jslm_curriculum_wrapper div.jslm_panel_group div.jslm_panel_single_group div.jslm-panel-heading div.jslm_edit_section_right span.jslm_section_action a.jslm_delete_sec,
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_tabs_wrapper div.jslm_my_content div.jslm_curriculum_wrapper div.jslm_panel_group div.jslm_panel_single_group div.jslm-panel-heading div.jslm_edit_section_right span.jslm_section_action i.more-less{border:1px solid <?php echo esc_attr( $color5 ); ?>;background-color: <?php echo esc_attr( $color7 ); ?>;color: <?php echo esc_attr( $color4 ); ?>;}
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_tabs_wrapper div.jslm_my_content div.jslm_curriculum_wrapper div.jslm_panel_group div.jslm_panel_single_group div.jslm-panel-heading div.jslm_edit_section_right span.jslm_section_action a.jslm_delete_sec:hover{background-color: <?php echo esc_attr( $color2 ); ?>;color: <?php echo esc_attr( $color7 ); ?>}
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_tabs_wrapper div.jslm_my_content div.jslm_curriculum_wrapper div.jslm_panel_group div.jslm_panel_single_group div.jslm-panel-heading div.jslm_edit_section_right span.jslm_section_action a.jslm_new_sec{border:1px solid <?php echo esc_attr( $color5 ); ?>;background-color: <?php echo esc_attr( $color2 ); ?>;color: <?php echo esc_attr( $color7 ); ?>;}
    div.jslm_content_data div.jslm_detail_wrapper div.jslm_tabs_wrapper div.jslm_my_content div.jslm_curriculum_wrapper div.jslm_panel_group div.jslm_panel_single_group div.jslm-panel-heading div.jslm_edit_section_right span.jslm_section_action a.jslm_new_sec:hover{background-color: <?php echo esc_attr( $color2 ); ?>;color: <?php echo esc_attr( $color7 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_lecture_curriculum_wrp div.jslm_left_section_list div.jslm_accordian_wrp div.jslm-panel-group div.jslm_panel_style div.panel-heading div.jslm_panel_title div.jslm_edit_section_wrapper h5.jslm_section_heading a.jslm_section_title:hover{background-color: <?php echo esc_attr( $color2 ); ?>;color: <?php echo esc_attr( $color7 ); ?>;}
    div.jslm_content_data div.jslm_data_container div.jslm_lecture_curriculum_wrp div.jslm_close_btn button.jslm-section-panel-btn{background-color: <?php echo esc_attr( $color3 ); ?>;color: red;border: 1px solid <?php echo esc_attr( $color5 ); ?>;}
    /* vbox */
    .vbox-close{color: <?php echo esc_attr( $color2 ); ?> !important;background-color: <?php echo esc_attr( $color7 ); ?> !important;}
    /* paginatnion */
    div#jslearnmanager-pagination span.jslm_page-numbers.jslm_current{color:<?php echo esc_attr( $color1 ); ?>;}
    div#jslearnmanager-pagination a.page-numbers.next{background:<?php echo esc_attr( $color1 ); ?>;color:<?php echo esc_attr( $color7 ); ?>;}
    div#jslearnmanager-pagination a.page-numbers.prev{background:<?php echo esc_attr( $color1 ); ?>;color:<?php echo esc_attr( $color7 ); ?>;}

    /* breadcrumbs */
    div#jslms_breadcrumbs_parent div.home{background-color:<?php echo esc_attr( $color2 ); ?>;}
    div#jslms-header-main-wrapper{background:<?php echo esc_attr( $color1 ); ?>;border-bottom:5px solid <?php echo esc_attr( $color2 ); ?>;box-shadow: 0px 4px 1px <?php echo esc_attr( $color5 ); ?>;}
    div#jslms-header-main-wrapper a.headerlinks{color:<?php echo esc_attr( $color7 ); ?>;}
    div#jslms-header-main-wrapper a.headerlinks:hover{background:<?php echo esc_attr( $color2 ); ?>;color:<?php echo esc_attr( $color7 ); ?>;}

    /* Theme css */
    div#jslearnmanagers-wrapper div.my-course-data div.data-bigupper div.big-upper-upper{border-bottom:1px solid <?php echo esc_attr( $color5 ); ?>;}
    div#jslearnmanagers-wrapper div.my-course-data div.data-bigupper span.title {color:<?php echo esc_attr( $color8 ); ?>;}
    div#jslearnmanagers-wrapper div.my-course-data div.data-bigupper div.big-upper-upper a{color: <?php echo esc_attr( $color1 ); ?>;}
    div#jslearnmanagers-wrapper div.my-course-data div.data-bigupper div.big-upper-upper div.headingtext{color:<?php echo esc_attr( $color2 ); ?>;}
    div#jslearnmanagers-wrapper div.my-course-data div.data-bigupper span.bigupper-jobtotal {color:<?php echo esc_attr( $color4 ); ?>;background-color:<?php echo esc_attr( $color3 ); ?>;border:1px solid <?php echo esc_attr( $color5 ); ?>;}
    div#jslearnmanagers-wrapper div.my-course-data div.data-big-lower {background-color:<?php echo esc_attr( $color3 ); ?>;border-top:1px solid <?php echo esc_attr( $color5 ); ?>;}
    div#jslearnmanagers-wrapper div.my-course-data div.data-big-lower img.big-lower-img {background-color:<?php echo esc_attr( $color3 ); ?>;}
    div#jslearnmanagers-wrapper div.my-course-data div.data-bigupper a{color:<?php echo esc_attr( $color1 ); ?>;}
    div#jslearnmanagers-wrapper div.my-course-data div.data-bigupper div.big-upper-upper span.title{color:<?php echo esc_attr( $color8 ); ?>;}
    div#jslearnmanagers-wrapper div.my-course-data div.data-big-lower div.big-lower-data-icons img.icon-img {border:1px solid <?php echo esc_attr( $color5 ); ?>;background-color:<?php echo esc_attr( $color6 ); ?>;}
    div#jslearnmanagers-wrapper div.my-course-data div.data-big-lower div.big-lower-data-icons span.icon-text-box{border:1px solid <?php echo esc_attr( $color5 ); ?>;background-color:<?php echo esc_attr( $color6 ); ?>;}
    div#jslearnmanagers-wrapper div.my-course-data div.data-big-lower div.big-lower-data-icons span.icons-resume{color:<?php echo esc_attr( $color7 ); ?>; background-color:<?php echo esc_attr( $color1 ); ?>; border:1px solid <?php echo esc_attr( $color5 ); ?>;}
    div#jslearnmanagers-wrapper div.my-course-data div.data-bigupper div.big-upper-upper span.buttonu {color:<?php echo esc_attr( $color4 ); ?>;background-color:<?php echo esc_attr( $color3 ); ?>;border:1px solid <?php echo esc_attr( $color5 ); ?>;}

    div.jslm_content_wrapper div.tablenav span.page-numbers.current{color:<?php echo esc_attr( $color1 ); ?>;}
    div.jslm_content_wrapper div.tablenav a.page-numbers.next{background:<?php echo esc_attr( $color1 ); ?>;color:<?php echo esc_attr( $color7 ); ?>;}
    div.jslm_content_wrapper div.tablenav a.page-numbers.prev{background:<?php echo esc_attr( $color1 ); ?>;color:<?php echo esc_attr( $color7 ); ?>;}

   /* widgets / search course widget */
   div.jslearnmanager_search_courses_widget_wrapper #jslms-mod-heading {color: <?php echo esc_attr( $color1 ); ?>;}
    div.jslearnmanager_search_courses_widget_wrapper form.learnmanager_form div.js-mod-valwrapper div.js-form-mod-title {color: <?php echo esc_attr( $color1 ); ?>;}
    div.jslearnmanager_search_courses_widget_wrapper form.learnmanager_form div.js-mod-valwrapper div.js-form-mod-value input[type="text"] {border: 1px solid <?php echo esc_attr( $color5 ); ?>;background-color: <?php echo esc_attr( $color3 ); ?>;color: <?php echo esc_attr( $color4 ); ?>;}
    div.jslearnmanager_search_courses_widget_wrapper form.learnmanager_form div.js-mod-valwrapper div.js-form-mod-value select {border: 1px solid <?php echo esc_attr( $color5 ); ?>;background-color: <?php echo esc_attr( $color3 ); ?>;color: <?php echo esc_attr( $color4 ); ?>;}
    div.jslearnmanager_search_courses_widget_wrapper form.learnmanager_form div.js-mod-valwrapper div.bottombutton .jslm_save_button {background-color: <?php echo esc_attr( $color2 ); ?>;color: #fff;border: 1px solid <?php echo esc_attr( $color2 ); ?>;}
    div.jslearnmanager_search_courses_widget_wrapper form.learnmanager_form div.js-mod-valwrapper div.bottombutton .jslm_save_button:hover {background-color: #fff;color: <?php echo esc_attr( $color2 ); ?>;}
    div.jslearnmanager_search_courses_widget_wrapper form.learnmanager_form div.js-mod-valwrapper div.bottombutton .anchor {background-color: <?php echo esc_attr( $color6 ); ?>;color: <?php echo esc_attr( $color1 ); ?>;border: 1px solid <?php echo esc_attr( $color5 ); ?>;}
    div.jslearnmanager_search_courses_widget_wrapper form.learnmanager_form div.js-mod-valwrapper div.bottombutton .anchor:hover {background-color: #fff;}
  
   /* widgets / featured course widget */
   div.jslearnmanager_courses_widget_wrapper div.jslearnmanager_courses_widget_top div.jslearnmanager_courses_widget_title {color: <?php echo esc_attr( $color1 ); ?>;}
   div.jslearnmanager_courses_widget_wrapper div.jslearnmanager_courses_widget_top div.jslearnmanager_courses_widget_desc {color: <?php echo esc_attr( $color4 ); ?>;}
   div.jslearnmanager_courses_widget_wrapper div.jslearnmanager_courses_widget_data_wrapper div.jslm_data_wrapper {border:1px solid <?php echo esc_attr( $color5 ); ?>;}   
   div.jslearnmanager_courses_widget_wrapper div.jslearnmanager_courses_widget_data_wrapper div.jslm_data_wrapper:hover {border-color:<?php echo esc_attr( $color2 ); ?>;}   
   div.jslearnmanager_courses_widget_wrapper div.jslearnmanager_courses_widget_data_wrapper div.jslm_data_wrapper div.jslm_left div.jslm_left_img_wrp div.overlay div.jslm_links span.jslm_link_icon a:hover {background: <?php echo esc_attr( $color2 ); ?>;}
   div.jslearnmanager_courses_widget_wrapper div.jslearnmanager_courses_widget_data_wrapper div.jslm_data_wrapper div.jslm_left div.jslm_left_img_wrp div.overlay div.jslm_overlay_bottom div.jslm_overlay_bottom_img {border: 1px solid #fff;}
   div.jslearnmanager_courses_widget_wrapper div.jslearnmanager_courses_widget_data_wrapper div.jslm_data_wrapper div.jslm_left div.jslm_left_img_wrp div.overlay div.jslm_overlay_bottom div.jslm_overlay_bottom_name span.jslm_overlay_bottom_instructor a.jslm_instructor_link {color:#fff;}
   div.jslearnmanager_courses_widget_wrapper div.jslearnmanager_courses_widget_data_wrapper div.jslm_data_wrapper div.jslm_left div.jslm_left_img_wrp div.overlay div.jslm_overlay_bottom div.jslm_overlay_bottom_name span.jslm_overlay_bottom_instructor a.jslm_instructor_link:hover {color:<?php echo esc_attr( $color2 ); ?>;}
   div.jslearnmanager_courses_widget_wrapper div.jslearnmanager_courses_widget_data_wrapper div.jslm_data_wrapper div.jslm_right div.jslm_right_top div.jslm_right_top_left .jslm_heading_style .jslm_course_title {color:<?php echo esc_attr( $color1 ); ?>;}
   div.jslearnmanager_courses_widget_wrapper div.jslearnmanager_courses_widget_data_wrapper:hover div.jslm_data_wrapper div.jslm_right div.jslm_right_top div.jslm_right_top_left .jslm_heading_style .jslm_course_title {color:<?php echo esc_attr( $color2 ); ?>;}
   div.jslearnmanager_courses_widget_wrapper div.jslearnmanager_courses_widget_data_wrapper div.jslm_data_wrapper div.jslm_right_bottom{border-top: 1px solid <?php echo esc_attr( $color5 ); ?>;background-color: <?php echo esc_attr( $color3 ); ?>;}
   div.jslearnmanager_courses_widget_wrapper div.jslearnmanager_courses_widget_data_wrapper div.jslm_data_wrapper div.jslm_right_bottom div.jslm_right_bottom_right div.jslm_right_bottom_price .jslm_heading_style {color: #fff;background: #3E4095;}
    


    @media(min-width: 661px) and (max-width: 782px){
        div.jslm_content_data div.jslm_detail_wrapper div.jslm_tabs_wrapper div.jslm_my_content div.jslm_reviews_wrapper div.jslm_comments_wrapper div.jslm_right_side div.jslm_top{border-bottom: 1px solid <?php echo esc_attr( $color5 ); ?>;}
    }
    @media(min-width: 481px) and (max-width: 660px) {
        /* Course List */
        div.jslm_content_data div.jslm_data_container div.jslm_data_wrapper div.jslm_left{border-bottom: 1px solid <?php echo esc_attr( $color5 ); ?>;}
        /* Course detail */
        div.jslm_content_data div.jslm_detail_wrapper div.jslm_middle_potion div.jslm_middle_top div.jslm_logo_wrp{border-bottom: 1px solid <?php echo esc_attr( $color5 ); ?>;}
        div.jslm_content_data div.jslm_detail_wrapper div.jslm_tabs_wrapper div.jslm_my_content div.jslm_home_data_wrapper div.jslm_home_data_row div.jslm_custom_fields_wrapper div.jslm_custom_field div.jslm_heading_right{border-bottom: 1px solid <?php echo esc_attr( $color5 ); ?>;}
        div.jslm_content_data div.jslm_detail_wrapper div.jslm_tabs_wrapper div.jslm_my_content div.jslm_reviews_wrapper div.jslm_comments_wrapper div.jslm_right_side div.jslm_top{border-bottom: 1px solid <?php echo esc_attr( $color5 ); ?>;}
        /* Login */
        div.jslm_content_data div.jslm_data_container div.jslm_login_wrapper div.jslm_login_right{border-top: 1px solid <?php echo esc_attr( $color5 ); ?>;}
        /* Register */
        div.jslm_content_data div.jslm_nav_tab_wrapper ul.nav-tabs.jslm_ul_styling li.jslm_li_styling_reg{border-bottom: 1px solid <?php echo esc_attr( $color5 ); ?>;border-right: none;}

    }
    @media (max-width: 480px) {
        /* Course detail */
        div.jslm_content_data div.jslm_detail_wrapper div.jslm_tabs_wrapper ul.nav-tabs{border:1px solid <?php echo esc_attr( $color5 ); ?>;border-bottom: unset;}
        div.jslm_content_data div.jslm_detail_wrapper div.jslm_tabs_wrapper ul li.jslm_li_border a.jslm_li_anchor_styling{border-bottom: 1px solid <?php echo esc_attr( $color5 ); ?> !important;}
        div.jslm_content_data div.jslm_detail_wrapper div.jslm_middle_potion div.jslm_middle_top div.jslm_logo_wrp{border-bottom: 1px solid <?php echo esc_attr( $color5 ); ?>;}
        div.jslm_content_data div.jslm_detail_wrapper div.jslm_tabs_wrapper div.jslm_my_content div.jslm_home_data_wrapper div.jslm_home_data_row div.jslm_custom_fields_wrapper div.jslm_custom_field div.jslm_heading_right{border-bottom: 1px solid <?php echo esc_attr( $color5 ); ?>;}
        /* Messages */
        div.jslm_content_data div.jslm_data_container div.jslm_message_wrapper div.jslm_top div.jslm_top_left div.jslm_img_wrapper {border:1px solid <?php echo esc_attr( $color5 ); ?>;background: #fff;}
        /* responsive table */
        table.jslm-table thead a {color: <?php echo esc_attr( $color2 ); ?>;text-decoration: none;}
        /* Lecture view */
        div.jslm_content_data div.jslm_data_container div.jslm_lecture_curriculum_wrp div.jslm_curriculum_right_wrp div.jslm_right_content_wrapper ul.jslm_modal_ul{border:1px solid <?php echo esc_attr( $color5 ); ?>;}
        div.jslm_content_data div.jslm_data_container div.jslm_lecture_curriculum_wrp div.jslm_curriculum_right_wrp div.jslm_right_content_wrapper ul.jslm_modal_ul li.jslm_modal_li{border-bottom: 1px solid <?php echo esc_attr( $color5 ); ?> !important;}
        div.jslm_content_data div.jslm_data_container div.jslm_lecture_curriculum_wrp div.jslm_curriculum_right_wrp div.jslm_right_content_wrapper ul.jslm_modal_ul li.jslm_modal_li:nth-child(2),
        div.jslm_content_data div.jslm_data_container div.jslm_lecture_curriculum_wrp div.jslm_curriculum_right_wrp div.jslm_right_content_wrapper ul.jslm_modal_ul li.jslm_modal_li:nth-child(4){border-left:1px solid <?php echo esc_attr( $color5 ); ?>;}
        /* Login */
        div.jslm_content_data div.jslm_data_container div.jslm_login_wrapper div.jslm_login_right{border-top: 1px solid <?php echo esc_attr( $color5 ); ?>;}
        div.jslm_content_data div.jslm_nav_tab_wrapper ul.nav-tabs.jslm_ul_styling li.jslm_li_styling_reg{border-bottom: 1px solid <?php echo esc_attr( $color5 ); ?>;}
        div#jslearnmanager-wrapper div#jslearnmanager-messages-wrap div#jslearnmanager-msg-content-wrap div#jslearnmanager-msg-det-left {border-bottom: 1px solid <?php echo esc_attr( $color5 ) ;?>;}
        div#jslearnmanager-wrapper div#jslearnmanager-credit-pack-wrap div#jslearnmanager-credit-pack-wrapper div.jslearnmanager-credit-pack-data div.jslearnmanager-credit-pack-data-top span.jslearnmanager-credit-pack-top-left{border-bottom: 1px solid <?php echo esc_attr( $color5 ); ?>;}
        div#jslearnmanager-wrapper div#jslearnmanager-purchase-history-wrap div.jslearnmanager-purchase-history-wrapper div.jslearnmanager-purchase-history-data span.jslearnmanager-data-parts span.jslearnmanager-data-title {border-bottom: 1px solid <?php echo esc_attr( $color5 ); ?>;}

        /* register */
        div.jslm_content_data div.jslm_nav_tab_wrapper ul.nav-tabs.jslm_ul_styling li.jslm_li_styling_reg{border-right: none;}
   }

    <?php  if (is_rtl()) {?>
        div#jslms_breadcrumbs_parent div {border-right: 1px solid #ababab;border-left: none !important;}
        /*div.jslm_content_data div.jslm_detail_wrapper div.jslm_tabs_wrapper ul li.jslm_li_border a.jslm_li_anchor_styling{border-left: 1px solid <?php echo esc_attr( $color5 ); ?>}*/
        div.jslm_content_data div.jslm_detail_wrapper div.jslm_tabs_wrapper ul li.jslm_li_border:nth-child(4){border-left: 1px solid <?php echo esc_attr( $color5 ); ?>}
        div.jslm_content_data div.jslm_data_container div.jslm_login_wrapper div.jslm_login_right{border-right: 1px solid <?php echo esc_attr( $color5 ); ?>}
        div.jslm_content_data div.jslm_data_container div.jslm_data_wrapper div.jslm_right{border-right:1px solid <?php echo esc_attr( $color5 ); ?>;}
        div.jslm_content_data div.jslm_data_container div.jslm_lecture_curriculum_wrp div.jslm_curriculum_right_wrp div.jslm_right_content_wrapper ul.jslm_modal_ul li.jslm_modal_li:nth-child(4){border-left: 1px solid <?php echo esc_attr( $color5 ); ?> !important;}
        div.jslm_content_data div.jslm_data_container div.jslm_addlecture_wrapper div.jslm_form_wrapper div.jslm_file_wrapper div.jslm_quiz_wrapper div.jslm_quiz_content div.jslm_quiz_body div.jslm_row_data div.jslm_choice div.jslm_quiz_input_wrapper div.jslm_checkbox_wrp {border-left: 1px solid #00a659;border-right: none;}
        div.jslm_content_data div.jslm_data_container div.jslm_addlecture_wrapper div.jslm_form_wrapper div.jslm_file_wrapper div.jslm_quiz_wrapper div.jslm_quiz_body div.jslm_row_data div.jslm_choice div.jslm_quiz_input_wrapper + span.jslm_delete {border-right: 1px solid <?php echo esc_attr( $color5 ); ?> !important;border-left: none !important;}
        div.jslm_content_data div.jslm_data_container div.jslm_addlecture_wrapper div.jslm_form_wrapper div.jslm_file_wrapper div#new_upload_questions div.jslm_quiz_wrapper div.jslm_heading_width + span.jslm_delete a.jslm_delete_button {border-left: 1px solid <?php echo esc_attr( $color5 ); ?>;border-right: none;}
        div.jslm_content_data div.jslm_data_container div.jslm_lecture_curriculum_wrp div.jslm_curriculum_right_wrp div.jslm_right_content_wrapper ul.jslm_modal_ul li.jslm_modal_li:last-child {border-left: 1px solid <?php echo esc_attr( $color5 ); ?>;}
        @media(min-width: 661px) and (max-width: 782px){
        }
        @media(min-width: 481px) and (max-width: 660px) {
            div.jslm_content_data div.jslm_data_container div.jslm_lecture_curriculum_wrp div.jslm_curriculum_right_wrp div.jslm_right_content_wrapper ul.jslm_modal_ul li.jslm_modal_li:nth-child(2){border-left: 0px !important;}

        }
        @media (max-width: 480px){
            div.jslm_content_data div.jslm_data_container div.jslm_lecture_curriculum_wrp div.jslm_curriculum_right_wrp div.jslm_right_content_wrapper ul.jslm_modal_ul li.jslm_modal_li:first-child{border-left: 1px solid <?php echo esc_attr( $color5 ); ?> !important;}
            div.jslm_content_data div.jslm_data_container div.jslm_lecture_curriculum_wrp div.jslm_curriculum_right_wrp div.jslm_right_content_wrapper ul.jslm_modal_ul li.jslm_modal_li:nth-child(2), div.jslm_content_data div.jslm_data_container div.jslm_lecture_curriculum_wrp div.jslm_curriculum_right_wrp div.jslm_right_content_wrapper ul.jslm_modal_ul li.jslm_modal_li:nth-child(4){border-left:none;}
            div.jslm_content_data div.jslm_data_container div.jslm_lecture_curriculum_wrp div.jslm_curriculum_right_wrp div.jslm_right_content_wrapper ul.jslm_modal_ul li.jslm_modal_li:nth-child(3){border-left: 1px solid <?php echo esc_attr( $color5 ); ?> !important;}
            div.jslm_content_data div.jslm_detail_wrapper div.jslm_tabs_wrapper ul.nav-tabs{border:1px solid <?php echo esc_attr( $color5 ); ?>;border-bottom: unset;}
            div.jslm_content_data div.jslm_detail_wrapper div.jslm_tabs_wrapper ul li.jslm_li_border a.jslm_li_anchor_styling{border-bottom: 1px solid <?php echo esc_attr( $color5 ); ?> !important;}
        }
    <?php } ?>
</style>
