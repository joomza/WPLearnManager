<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
?>
<?php
    $theme = wp_get_theme();
    $theme_chk = 0;
    if($theme == 'JS Learn Manager'){
        $theme_chk = 1;
    }
    $msgkey = JSLEARNMANAGERincluder::getJSModel('jslearnmanager')->getMessagekey();
    JSLEARNMANAGERmessages::getLayoutMessage($msgkey);
?>
<div id="jslearnmanageradmin-wrapper">
    <div id="jslearnmanageradmin-leftmenu">
        <?php JSLEARNMANAGERincluder::getClassesInclude('jslearnmanageradminsidemenu'); ?>
    </div>
    <div id="jslearnmanageradmin-data">
        <div id="jslearnmanageradmin-wrapper-top">
            <div id="jslearnmanageradmin-wrapper-top-left">
                <div id="jslearnmanageradmin-breadcrunbs">
                    <ul>
                        <li>
                            <a href="admin.php?page=jslearnmanager">
                                <?php echo __('Dashboard', 'learn-manager'); ?>
                            </a>
                        </li>
                        <li><?php echo __('Short Codes','js-support-ticket'); ?></li>
                    </ul>
                </div>
            </div>
            <div id="jslearnmanageradmin-wrapper-top-right">
                <div id="jslearnmanageradmin-help-txt">
                   <a Href="<?php echo esc_url(admin_url("admin.php?page=jslearnmanager&jslmslay=help")); ?>" title="<?php echo __('help','leARN-MANAGER'); ?>">
                        <img alt="<?Php ecHo __('help','learn-manager'); ?>" src="<?php echo JSLEARNMANAGER_PLUGIN_URL; ?>includes/images/help.png" />
                    </a>
                </div>
                <div id="jslearnmanageradmin-vers-txt">
                    <?php echo __('Version :'); ?>
                    <span class="jslearnmanageradmin-ver">
                        <?php echo esc_html(JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigValue('versioncode')); ?>
                    </span>
                </div>
            </div>
        </div>
       <div class="jslm_dashboard">
            <span class="jslm_heading-dashboard"><?php echo __('Short Codes', 'learn-manager'); ?></span>
                <a href="https://www.youtube.com/watch?v=fY6uVljRYeE" target="_blank" class="jslmsadmin-add-link black-bg button js-cp-video-popup" title="<?php echo __('Watch Video', 'learn-manager'); ?>">
                <img alt="<?php echo __('arrow','learn-manager'); ?>" src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/play-btn.png"/>
                <?php echo __('Watch Video','learn-manager'); ?>
            </a>
        </div>            
    <div id="jslms-data-wrp">
        <table id="js-table">
        <thead>
            <tr>
                <th id="short-code-left" class="left-row"><?php echo __('Title', 'learn-manager'); ?></th>
                <th id="short-code-middle" class="left-row"><?php echo __('Short code', 'learn-manager'); ?></th>
                <th id="short-code-right" class="left-row"><?php echo __('Description', 'learn-manager'); ?></th>
            </tr>
        </thead>
        <tbody>
            <tr valign="top">
                <td id="short-code-left" class="left-row">
                    <?php echo __('Add Course','learn-manager'); ?>
                </td>
                <td id="short-code-middle" class="left-row">
                    <?php echo '[jslearnmanager page="1"]'; ?>
                </td>
                <td id="short-code-right" class="left-row">
                    <?php echo __('To add or','learn-manager').' '. __('create new course','learn-manager'); ?>
                </td>
            </tr>
            <tr valign="top">
                <td id="short-code-left" class="left-row">
                    <?php echo __('Add Lecture','learn-manager'); ?>
                </td>
                <td id="short-code-middle" class="left-row">
                    <?php echo '[jslearnmanager page="2"]'; ?>
                </td>
                <td id="short-code-right" class="left-row">
                    <?php echo __('To','learn-manager').' '. __('add course lecture','learn-manager'); ?>
                </td>
            </tr>
            <?php if($theme_chk == 1){ ?>
                <tr valign="top">
                    <td id="short-code-left" class="left-row">
                        <?php echo __('Course by category','learn-manager'); ?>
                    </td>
                    <td id="short-code-middle" class="left-row">
                        <?php echo '[jslearnmanager page="3"]'; ?>
                    </td>
                    <td id="short-code-right" class="left-row">
                        <?php echo __('Show','learn-manager').' '. __('courses by category','learn-manager'); ?>
                    </td>
                </tr>
            <?php } ?>
            <tr valign="top">
                <td id="short-code-left" class="left-row">
                    <?php echo __('Course detail','learn-manager'); ?>
                </td>
                <td id="short-code-middle" class="left-row">
                    <?php echo '[jslearnmanager page="4"]'; ?>
                </td>
                <td id="short-code-right" class="left-row">
                    <?php echo __('Show','learn-manager').' '. __('course detail','learn-manager'); ?>
                </td>
            </tr>
            <tr valign="top">
                <td id="short-code-left" class="left-row">
                    <?php echo __('Course list','learn-manager'); ?>
                </td>
                <td id="short-code-middle" class="left-row">
                    <?php echo '[jslearnmanager page="5"]'; ?>
                </td>
                <td id="short-code-right" class="left-row">
                    <?php echo __('Show','learn-manager').' '. __('courses','learn-manager'); ?>
                </td>
            </tr>
            <tr valign="top">
                <td id="short-code-left" class="left-row">
                    <?php echo __('Edit Course','learn-manager'); ?>
                </td>
                <td id="short-code-middle" class="left-row">
                    <?php echo '[jslearnmanager page="6"]'; ?>
                </td>
                <td id="short-code-right" class="left-row">
                    <?php echo __('Edit','learn-manager').' '. __('course','learn-manager'); ?>
                </td>
            </tr>
            <tr valign="top">
                <td id="short-code-left" class="left-row">
                    <?php echo __('Lecture Detail','learn-manager'); ?>
                </td>
                <td id="short-code-middle" class="left-row">
                    <?php echo '[jslearnmanager page="7"]'; ?>
                </td>
                <td id="short-code-right" class="left-row">
                    <?php echo __('Show','learn-manager').' '. __('lecture detail','learn-manager'); ?>
                </td>
            </tr>
            <tr valign="top">
                <td id="short-code-left" class="left-row">
                    <?php echo __('Shortlist Courses','learn-manager'); ?>
                </td>
                <td id="short-code-middle" class="left-row">
                    <?php echo '[jslearnmanager page="8"]'; ?>
                </td>
                <td id="short-code-right" class="left-row">
                    <?php echo __('Show','learn-manager').' '. __('shortlist courses','learn-manager'); ?>
                </td>
            </tr>
            <tr valign="top">
                <td id="short-code-left" class="left-row">
                    <?php echo __('Instructor Profile','learn-manager'); ?>
                </td>
                <td id="short-code-middle" class="left-row">
                    <?php echo '[jslearnmanager page="9"]'; ?>
                </td>
                <td id="short-code-right" class="left-row">
                    <?php echo __('Show','learn-manager').' '. __('instructor profile','learn-manager'); ?>
                </td>
            </tr>
            <tr valign="top">
                <td id="short-code-left" class="left-row">
                    <?php echo __('Student Message List','learn-manager'); ?>
                </td>
                <td id="short-code-middle" class="left-row">
                    <?php echo '[jslearnmanager page="11"]'; ?>
                </td>
                <td id="short-code-right" class="left-row">
                    <?php echo __('Show','learn-manager').' '. __('student message list','learn-manager'); ?>
                </td>
            </tr>
            <tr valign="top">
                <td id="short-code-left" class="left-row">
                    <?php echo __('Student Profile','learn-manager'); ?>
                </td>
                <td id="short-code-middle" class="left-row">
                    <?php echo '[jslearnmanager page="12"]'; ?>
                </td>
                <td id="short-code-right" class="left-row">
                    <?php echo __('Show','learn-manager').' '. __('student profile','learn-manager'); ?>
                </td>
            </tr>
            <tr valign="top">
                <td id="short-code-left" class="left-row">
                    <?php echo __('Instructor Dashboard','learn-manager'); ?>
                </td>
                <td id="short-code-middle" class="left-row">
                    <?php echo '[jslearnmanager page="14"]'; ?>
                </td>
                <td id="short-code-right" class="left-row">
                    <?php echo __('Show','learn-manager').' '. __('instructor dashboard','learn-manager'); ?>
                </td>
            </tr>
            <tr valign="top">
                <td id="short-code-left" class="left-row">
                    <?php echo __('Instructor Register','learn-manager'); ?>
                </td>
                <td id="short-code-middle" class="left-row">
                    <?php echo '[jslearnmanager page="15"]'; ?>
                </td>
                <td id="short-code-right" class="left-row">
                    <?php echo __('Register','learn-manager').' '. __(' page for instructor','learn-manager'); ?>
                </td>
            </tr>
            <tr valign="top">
                <td id="short-code-left" class="left-row">
                    <?php echo __('Login','learn-manager'); ?>
                </td>
                <td id="short-code-middle" class="left-row">
                    <?php echo '[jslearnmanager page="16"]'; ?>
                </td>
                <td id="short-code-right" class="left-row">
                    <?php echo __('Login','learn-manager').' '. __('for user','learn-manager'); ?>
                </td>
            </tr>
            <tr valign="top">
                <td id="short-code-left" class="left-row">
                    <?php echo __('Register','learn-manager'); ?>
                </td>
                <td id="short-code-middle" class="left-row">
                    <?php echo '[jslearnmanager page="17"]'; ?>
                </td>
                <td id="short-code-right" class="left-row">
                    <?php echo __('Register','learn-manager').' '. __('layout','learn-manager'); ?>
                </td>
            </tr>
            <tr valign="top">
                <td id="short-code-left" class="left-row">
                    <?php echo __('Student Dashboard','learn-manager'); ?>
                </td>
                <td id="short-code-middle" class="left-row">
                    <?php echo '[jslearnmanager page="18"]'; ?>
                </td>
                <td id="short-code-right" class="left-row">
                    <?php echo __('Show Student','learn-manager').' '. __('Dashboard','learn-manager'); ?>
                </td>
            </tr>
            <tr valign="top">
                <td id="short-code-left" class="left-row">
                    <?php echo __('Student Register','learn-manager'); ?>
                </td>
                <td id="short-code-middle" class="left-row">
                    <?php echo '[jslearnmanager page="19"]'; ?>
                </td>
                <td id="short-code-right" class="left-row">
                    <?php echo __('Show Student','learn-manager').' '. __('Register','learn-manager'); ?>
                </td>
            </tr>
            <tr valign="top">
                <td id="short-code-left" class="left-row">
                    <?php echo __('User Dashboard','learn-manager'); ?>
                </td>
                <td id="short-code-middle" class="left-row">
                    <?php echo '[jslearnmanager page="21"]'; ?>
                </td>
                <td id="short-code-right" class="left-row">
                    <?php echo __('Show user','learn-manager').' '. __('dashboard','learn-manager'); ?>
                </td>
            </tr>
            <tr valign="top">
                <td id="short-code-left" class="left-row">
                    <?php echo __('User Profile','learn-manager'); ?>
                </td>
                <td id="short-code-middle" class="left-row">
                    <?php echo '[jslearnmanager page="22"]'; ?>
                </td>
                <td id="short-code-right" class="left-row">
                    <?php echo __('Show user','learn-manager').' '. __('profile','learn-manager'); ?>
                </td>
            </tr>
            <tr valign="top">
                <td id="short-code-left" class="left-row">
                    <?php echo __('Edit Profile','learn-manager'); ?>
                </td>
                <td id="short-code-middle" class="left-row">
                    <?php echo '[jslearnmanager page="23"]'; ?>
                </td>
                <td id="short-code-right" class="left-row">
                    <?php echo __('Show edit','learn-manager').' '. __('profile','learn-manager'); ?>
                </td>
            </tr>
            <tr valign="top">
                <td id="short-code-left" class="left-row">
                    <?php echo __('Instructor Message','learn-manager'); ?>
                </td>
                <td id="short-code-middle" class="left-row">
                    <?php echo '[jslearnmanager page="24"]'; ?>
                </td>
                <td id="short-code-right" class="left-row">
                    <?php echo __('Show','learn-manager').' '. __('instructor message','learn-manager'); ?>
                </td>
            </tr>
            <tr valign="top">
                <td id="short-code-left" class="left-row">
                    <?php echo __('Instructor List','learn-manager'); ?>
                </td>
                <td id="short-code-middle" class="left-row">
                    <?php echo '[jslearnmanager page="26"]'; ?>
                </td>
                <td id="short-code-right" class="left-row">
                    <?php echo __('Show','learn-manager').' '. __('Instructors List','learn-manager'); ?>
                </td>
            </tr>
            <tr valign="top">
                <td id="short-code-left" class="left-row">
                    <?php echo __('Course Search','learn-manager'); ?>
                </td>
                <td id="short-code-middle" class="left-row">
                    <?php echo '[jslearnmanager_course_search]'; ?>
                </td>
                <td id="short-code-right" class="left-row">
                    <?php echo __('Show Course Search','learn-manager'); ?>
                </td>
            </tr>
        </tbody>
        </table>
       </div>
      </div>
</div>
