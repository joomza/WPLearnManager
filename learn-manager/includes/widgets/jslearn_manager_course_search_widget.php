<?php
// Creating the widget 
class js_learn_manager_search_course_widget extends WP_Widget {

    function __construct() {
        parent::__construct(
                // Base ID of your widget
                'widget_js_learn_manager_course_search_widget_options',
                // Widget name will appear in UI
                __('Course Search', 'learn-manager'),
                // Widget description
                array('description' => __('Search courses form Learn Manager database', 'learn-manager'),)
        );
    }

    // Creating widget front-end
    // This is where the action happens
    public function widget($args, $instance) {

        $coursetitle = apply_filters('widget_coursetitle', $instance['coursetitle']);
        $title = apply_filters('widget_title', $instance['title']);
        $showtitle = apply_filters('widget_showtitle', $instance['showtitle']);
        $category = apply_filters('widget_category', $instance['category']);
        $price = 0;
        if(in_array('paidcourse', jslearnmanager::$_active_addons)){
        	$price = apply_filters('widget_price', $instance['price']);
        }
        $language = apply_filters('widget_language', $instance['language']);
        $courselevel = apply_filters('widget_courselevel', $instance['courselevel']);
        $instructor = apply_filters('widget_instructor', $instance['instructor']);
        $columnperrow = apply_filters('widget_columnperrow', $instance['columnperrow']);

        // before and after widget arguments are defined by themes
        echo esc_html($args['before_widget']);
        if (!empty($title))
            echo esc_attr($args['before_title'] . $title . $args['after_title']);

        // This is where you run the code and display the output
        //Frontend HTML starts

        jslearnmanager::addStyleSheets();
        $modules_html = JSLEARNMANAGERincluder::getJSModel('widget')->getSearchCourse_Widget($title, $showtitle, $coursetitle, $category, $price, $language, $courselevel, $instructor, $columnperrow);
        echo esc_html($modules_html);

        //Frontend HTML ends -------------
        // before and after widget arguments are defined by themes
        echo esc_html($args['after_widget']);
    }

    // Widget Backend 
    public function form($instance) {
        $title = (isset($instance['title'])) ? $instance['title'] : __('Search Course', 'learn-manager');
        $showtitle = (isset($instance['showtitle'])) ? $instance['showtitle'] : 1;
        $coursetitle = (isset($instance['coursetitle'])) ? $instance['coursetitle'] : 1;
        $category = (isset($instance['category'])) ? $instance['category'] : 1;

        $price = (isset($instance['price'])) ? $instance['price'] : 0;
        $language = (isset($instance['language'])) ? $instance['language'] : 1;
        $courselevel = (isset($instance['courselevel'])) ? $instance['courselevel'] : 1;
        $instructor = (isset($instance['instructor'])) ? $instance['instructor'] : 1;
        $columnperrow = (isset($instance['columnperrow'])) ? $instance['columnperrow'] : 1;
        ?>
        <!-- widgets admin form options -->
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php echo __('Title', 'learn-manager'); ?></label> 
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('showtitle')); ?>"><?php echo __('Show Title', 'learn-manager'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('showtitle')); ?>" name="<?php echo esc_attr($this->get_field_name('showtitle')); ?>">
                <option value="0" <?php if (esc_attr($showtitle) == 0) echo "selected"; ?>><?php echo __('Hide', 'learn-manager'); ?></option>
                <option value="1" <?php if (esc_attr($showtitle) == 1) echo "selected"; ?>><?php echo __('Show', 'learn-manager'); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('coursetitle')); ?>"><?php echo __('Title', 'learn-manager'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('coursetitle')); ?>" name="<?php echoesc_attr( $this->get_field_name('coursetitle')); ?>">
                <option value="0" <?php if (esc_attr($coursetitle) == 0) echo "selected"; ?>><?php echo __('Hide', 'learn-manager'); ?></option>
                <option value="1" <?php if (esc_attr($coursetitle) == 1) echo "selected"; ?>><?php echo __('Show', 'learn-manager'); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('category')); ?>"><?php echo __('Category', 'learn-manager'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('category')); ?>" name="<?php echo esc_attr($this->get_field_name('category')); ?>">
                <option value="0" <?php if (esc_attr($category) == 0) echo "selected"; ?>><?php echo __('Hide', 'learn-manager'); ?></option>
                <option value="1" <?php if (esc_attr($category) == 1) echo "selected"; ?>><?php echo __('Show', 'learn-manager'); ?></option>
            </select>
        </p>

        <?php if(in_array('paidcourse', jslearnmanager::$_active_addons)): ?>
	        <p>
	            <label for="<?php echo esc_attr($this->get_field_id('price')); ?>"><?php echo __('Course Price', 'learn-manager'); ?></label>
	            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('price')); ?>" name="<?php echo esc_attr($this->get_field_name('price')); ?>">
	                <option value="0" <?php if (esc_attr($price) == 0) echo "selected"; ?>><?php echo __('Hide', 'learn-manager'); ?></option>
	                <option value="1" <?php if (esc_attr($price) == 1) echo "selected"; ?>><?php echo __('Show', 'learn-manager'); ?></option>
	            </select>
	        </p>
        <?php endif; ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('language')); ?>"><?php echo __('Course Language', 'learn-manager'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('language')); ?>" name="<?php echo esc_attr($this->get_field_name('language')); ?>">
                <option value="0" <?php if (esc_attr($language) == 0) echo "selected"; ?>><?php echo __('Hide', 'learn-manager'); ?></option>
                <option value="1" <?php if (esc_attr($language) == 1) echo "selected"; ?>><?php echo __('Show', 'learn-manager'); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('courselevel')); ?>"><?php echo __('Course Level', 'learn-manager'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('courselevel')); ?>" name="<?php echo esc_attr($this->get_field_name('courselevel')); ?>">
                <option value="0" <?php if (esc_attr($courselevel) == 0) echo "selected"; ?>><?php echo __('Hide', 'learn-manager'); ?></option>
                <option value="1" <?php if (esc_attr($courselevel) == 1) echo "selected"; ?>><?php echo __('Show', 'learn-manager'); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('instructor')); ?>"><?php echo __('Instructor Name', 'learn-manager'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('instructor')); ?>" name="<?php echo esc_attr($this->get_field_name('instructor')); ?>">
                <option value="0" <?php if (esc_attr($instructor) == 0) echo "selected"; ?>><?php echo __('Hide', 'learn-manager'); ?></option>
                <option value="1" <?php if (esc_attr($instructor) == 1) echo "selected"; ?>><?php echo __('Show', 'learn-manager'); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('columnperrow')); ?>"><?php echo __('Column per row', 'learn-manager'); ?></label> 
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('columnperrow')); ?>" name="<?php echo esc_attr($this->get_field_name('columnperrow')); ?>" type="text" value="<?php echo esc_attr($columnperrow); ?>" />
        </p>
        <?php
    }

    // Updating widget replacing old instances with new
    public function update($new_instance, $old_instance) {
        $instance = array();

        $instance['title'] = (!empty($new_instance['title'])) ? filter_var(strip_tags($new_instance['title']), FILTER_SANITIZE_STRING) : '';
        $instance['showtitle'] = (!empty($new_instance['showtitle'])) ? filter_var(strip_tags($new_instance['showtitle']), FILTER_SANITIZE_STRING) : '';
        $instance['coursetitle'] = (!empty($new_instance['coursetitle'])) ? filter_var(strip_tags($new_instance['coursetitle']), FILTER_SANITIZE_STRING) : '';
        $instance['category'] = (!empty($new_instance['category'])) ? filter_var(strip_tags($new_instance['category']), FILTER_SANITIZE_STRING) : '';

        $instance['price'] = (!empty($new_instance['price'])) ? filter_var(strip_tags($new_instance['price']), FILTER_SANITIZE_STRING) : '';
        $instance['language'] = (!empty($new_instance['language'])) ? filter_var(strip_tags($new_instance['language']), FILTER_SANITIZE_STRING) : '';
        $instance['courselevel'] = (!empty($new_instance['courselevel'])) ? filter_var(strip_tags($new_instance['courselevel']), FILTER_SANITIZE_STRING) : '';
        $instance['instructor'] = (!empty($new_instance['instructor'])) ? filter_var(strip_tags($new_instance['instructor']), FILTER_SANITIZE_STRING) : '';
        $instance['columnperrow'] = (!empty($new_instance['columnperrow'])) ? filter_var(strip_tags($new_instance['columnperrow']), FILTER_SANITIZE_STRING) : '';

        return $instance;
    }

}

?>
