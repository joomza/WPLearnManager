<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

// today
function jslearnmanager_dashboard_widgets_todaystats() {
    wp_add_dashboard_widget(
            'jslearnmanager_dashboard_widgets_todaystats', // Widget slug.
            __('Today Stats', 'learn-manager'), // Title.
            'jslearnmanager_dashboard_widget_function_todaystats' // Display function.
    );
}

add_action('wp_dashboard_setup', 'jslearnmanager_dashboard_widgets_todaystats');
function jslearnmanager_dashboard_widget_function_todaystats() {
    jslearnmanager::addStyleSheets();
    $html = JSLEARNMANAGERincluder::getJSModel('jslearnmanager')->getTodayStatsForWidget();
    echo esc_html($html);
}

// today
function jslearnmanager_dashboard_widgets_lastweekstats() {

    wp_add_dashboard_widget(
            'jslearnmanager_dashboard_widgets_lastweekstats', // Widget slug.
            __('Last Week Stats', 'learn-manager'), // Title.
            'jslearnmanager_dashboard_widget_function_lastweekstats' // Display function.
    );
}

add_action('wp_dashboard_setup', 'jslearnmanager_dashboard_widgets_lastweekstats');
function jslearnmanager_dashboard_widget_function_lastweekstats() {
    jslearnmanager::addStyleSheets();
    $html = JSLEARNMANAGERincluder::getJSModel('jslearnmanager')->getLastWeekStatsForWidget();
    echo esc_html($html);
}

// total
function jslearnmanager_dashboard_widgets_totalstats() {

    wp_add_dashboard_widget(
            'jslearnmanager_dashboard_widgets_totalstats', // Widget slug.
            __('Total Stats', 'learn-manager'), // Title.
            'jslearnmanager_dashboard_widget_function_totalstats' // Display function.
    );
}

add_action('wp_dashboard_setup', 'jslearnmanager_dashboard_widgets_totalstats');
function jslearnmanager_dashboard_widget_function_totalstats() {
    jslearnmanager::addStyleSheets();
    $html = JSLEARNMANAGERincluder::getJSModel('jslearnmanager')->getTotalStatsForWidget();
    echo esc_html($html);
}

// Latest courses
function jslearnmanager_dashboard_widgets_latest_courses() {

    wp_add_dashboard_widget(
            'jslearnmanager_dashboard_widgets_latest_courses', // Widget slug.
            __('Newly Created Courses', 'learn-manager'), // Title.
            'jslearnmanager_dashboard_widget_function_latest_courses' // Display function.
    );
}

add_action('wp_dashboard_setup', 'jslearnmanager_dashboard_widgets_latest_courses');
function jslearnmanager_dashboard_widget_function_latest_courses() {
    jslearnmanager::addStyleSheets();
    $html = JSLEARNMANAGERincluder::getJSModel('jslearnmanager')->getLatestCoursesForWidget();
    echo esc_html($html);
}

// Latest instructor
function jslearnmanager_dashboard_widgets_latest_instructor() {

    wp_add_dashboard_widget(
            'jslearnmanager_dashboard_widgets_latest_instructor', // Widget slug.
            __('Newly Registered Instructors', 'learn-manager'), // Title.
            'jslearnmanager_dashboard_widget_function_latest_instructor' // Display function.
    );
}

add_action('wp_dashboard_setup', 'jslearnmanager_dashboard_widgets_latest_instructor');
function jslearnmanager_dashboard_widget_function_latest_instructor() {
    jslearnmanager::addStyleSheets();
    $html = JSLEARNMANAGERincluder::getJSModel('jslearnmanager')->getLatestInstructorsForWidget();
    echo esc_html($html);
}

// Latest student
function jslearnmanager_dashboard_widgets_latest_student() {

    wp_add_dashboard_widget(
            'jslearnmanager_dashboard_widgets_latest_student', // Widget slug.
            __('Newly Registered Students', 'learn-manager'), // Title.
            'jslearnmanager_dashboard_widget_function_latest_student' // Display function.
    );
}

add_action('wp_dashboard_setup', 'jslearnmanager_dashboard_widgets_latest_student');
function jslearnmanager_dashboard_widget_function_latest_student() {
    jslearnmanager::addStyleSheets();
    $html = JSLEARNMANAGERincluder::getJSModel('jslearnmanager')->getLatestStudentsForWidget();
    echo esc_html($html);
}

// latest enrollment
function jslearnmanager_dashboard_widgets_latest_enrollment() {

    wp_add_dashboard_widget(
            'jslearnmanager_dashboard_widgets_latest_enrollment', // Widget slug.
            __('Latest Enrollment', 'learn-manager'), // Title.
            'jslearnmanager_dashboard_widget_function_latest_enrollment' // Display function.
    );
}

add_action('wp_dashboard_setup', 'jslearnmanager_dashboard_widgets_latest_enrollment');
function jslearnmanager_dashboard_widget_function_latest_enrollment() {
    jslearnmanager::addStyleSheets();
    $html = JSLEARNMANAGERincluder::getJSModel('jslearnmanager')->getLatestEnrollmentForWidget();
    echo esc_html($html);
}

// latest reviews
function jslearnmanager_dashboard_widgets_latest_reviews() {

    wp_add_dashboard_widget(
            'jslearnmanager_dashboard_widgets_latest_reviews', // Widget slug.
            __('Latest Reviews', 'learn-manager'), // Title.
            'jslearnmanager_dashboard_widget_function_latest_reviews' // Display function.
    );
}

add_action('wp_dashboard_setup', 'jslearnmanager_dashboard_widgets_latest_reviews');
function jslearnmanager_dashboard_widget_function_latest_reviews() {
    jslearnmanager::addStyleSheets();
    $html = apply_filters("jslm_coursereview_data_for_dashboard_widget",'');
    echo esc_html($html);
}

?>