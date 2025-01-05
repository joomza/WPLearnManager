<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

if (!defined('JSLEARNMANAGER_FILE_TYPE_ERROR')) {
    define('JSLEARNMANAGER_FILE_TYPE_ERROR', 'FILE_TYPE_ERROR');
    define('JSLEARNMANAGER_FILE_SIZE_ERROR', 'FILE_SIZE_ERROR');
    define('JSLEARNMANAGER_ALREADY_EXIST', 'ALREADY_EXIST');
    define('JSLEARNMANAGER_ALREADY_ENROLLED', 'ALREADY_ENROLLED');
    define('JSLEARNMANAGER_NOT_EXIST', 'NOT_EXIST');
    define('JSLEARNMANAGER_IN_USE', 'IN_USE');
    define('JSLEARNMANAGER_SET_DEFAULT', 'SET_DEFAULT');
    define('JSLEARNMANAGER_SET_DEFAULT_ERROR', 'SET_DEFAULT_ERROR');
    define('JSLEARNMANAGER_STATUS_CHANGED', 'STATUS_CHANGED');
    define('JSLEARNMANAGER_STATUS_CHANGED_ERROR', 'STATUS_CHANGED_ERROR');
    define('JSLEARNMANAGER_APPROVED', 'APPROVED');
    define('JSLEARNMANAGER_APPROVE_ERROR', 'APPROVE_ERROR');
    define('JSLEARNMANAGER_REJECTED', 'REJECTED');
    define('JSLEARNMANAGER_REJECT_ERROR', 'REJECT_ERROR');
    define('JSLEARNMANAGER_UN_PUBLISHED', 'UN_PUBLISHED');
    define('JSLEARNMANAGER_UN_PUBLISH_ERROR', 'UN_PUBLISH_ERROR');
    define('JSLEARNMANAGER_UNPUBLISH_DEFAULT_ERROR', 'UNPUBLISH_DEFAULT_ERROR');
    define('JSLEARNMANAGER_PUBLISHED', 'PUBLISHED');
    define('JSLEARNMANAGER_PUBLISH_ERROR', 'PUBLISH_ERROR');
    define('JSLEARNMANAGER_REQUIRED', 'REQUIRED');
    define('JSLEARNMANAGER_REQUIRED_ERROR', 'REQUIRED_ERROR');
    define('JSLEARNMANAGER_NOT_REQUIRED', 'NOT_REQUIRED');
    define('JSLEARNMANAGER_NOT_REQUIRED_ERROR', 'NOT_REQUIRED_ERROR');
    define('JSLEARNMANAGER_ORDER_UP', 'ORDER_UP');
    define('JSLEARNMANAGER_ORDER_UP_ERROR', 'ORDER_UP_ERROR');
    define('JSLEARNMANAGER_ORDER_DOWN', 'ORDER_DOWN');
    define('JSLEARNMANAGER_ORDER_DOWN_ERROR', 'ORDER_DOWN_ERROR');
    define('JSLEARNMANAGER_SAVED', 'SAVED');
    define('JSLEARNMANAGER_SAVE_ERROR', 'SAVE_ERROR');
    define('JSLEARNMANAGER_DELETED', 'DELETED');
    define('JSLEARNMANAGER_DELETE_ERROR', 'DELETE_ERROR');
    define('JSLEARNMANAGER_VERIFIED', 'VERIFIED');
    define('JSLEARNMANAGER_APPLY', 'APPLY');
    define('JSLEARNMANAGER_APPLY_ERROR', 'APPLY_ERROR');
    define('JSLEARNMANAGER_UN_VERIFIED', 'UN_VERIFIED');
    define('JSLEARNMANAGER_VERIFIED_ERROR', 'VERIFIED_ERROR');
    define('JSLEARNMANAGER_UN_VERIFIED_ERROR', 'UN_VERIFIED_ERROR');
    define('JSLEARNMANAGER_INVALID_REQUEST', 'INVALID_REQUEST');
    define('JSLEARNMANAGER_ENABLED', 'ENABLED');
    define('JSLEARNMANAGER_DISABLED', 'DISABLED');
    define('JSLEARNMANAGER_WOO_UNPUBLISHED', 'WOO_UNPUBLISHED');
    define('JSLEARNMANAGER_EXISTS', 'EXISTS');
    define('JSLEARNMANAGER_UNKNOW_INSTRUCTOR', 'UNKNOW_INSTRUCTOR');
    define('JSLEARNMANAGER_ENROLLED', 'ENROLLED');
    define('JSLEARNMANAGER_NOTANINSTRUCTOR', 'NOTANINSTRUCTOR');
    define('JSLEARNMANAGER_SEND', 'SEND');
    define('JSLEARNMANAGER_SEND_ERROR', 'SEND_ERROR');
    define('JSLEARNMANAGER_PENDING', 'PENDING');
    define('JSLEARNMANAGER_LIMIT_EXCEED','LIMIT_EXCEED');
    define('LEARN_MANAGER_ALREADYREGISTER','ALREADYREGISTER');

    define('JSLEARNMANAGER_EMPTY_MESSAGE','EMPTY_MESSAGE');
    define('JSLEARNMANAGER_ENABLE_ERROR','ENABLE_ERROR');
    define('JSLEARNMANAGER_DISABLE_ERROR','DISABLE_ERROR');
    define('JSLEARNMANAGER_COURSE_APPROVAL_PENDING','COURSE_APPROVAL_PENDING');
    define('JSLEARNMANAGER_PLUGIN_PATH', plugin_dir_path( __DIR__ ));
    define('JSLEARNMANAGER_PLUGIN_URL', plugin_dir_url( __DIR__ ));
    define('JSLEARNMANAGER_ALLOWED_TAGS',array(
        'div'      => array(
            'class'  => array(),
            'id' => array(),
            'data-sitekey' => array(),
            'title' => array(),
            'role' => array(),
            'onclick' => array(),
            'onmouseout' => array(),
            'onmouseover' => array(),
            'data-section' => array(),
            'data-sectionid' => array(),
            'data-boxid' => array(),
            'data-id' => array(),
            'style' => array(),
        ),
        'button'      => array(
            'class'  => array(),
            'id' => array(),
            'type' => array(),
            'title' => array(),
            'role' => array(),
            'onclick' => array(),
            'data-dismiss' => array(),
            'aria-label' => array(),
            'style' => array(),
        ),
        'i'      => array(
            'class'  => array(),
            'id' => array(),
            'style' => array(),
        ),
        'h1'      => array(
            'class'  => array(),
            'id' => array(),
            'style' => array(),
        ),
        'h2'      => array(
            'class'  => array(),
            'id' => array(),
            'style' => array(),
        ),
        'h3'      => array(
            'class'  => array(),
            'id' => array(),
            'style' => array(),
        ),
        'h4'      => array(
            'class'  => array(),
            'id' => array(),
            'style' => array(),
        ),
        'h5'      => array(
            'class'  => array(),
            'id' => array(),
            'style' => array(),
        ),
        'h6'      => array(
            'class'  => array(),
            'id' => array(),
            'style' => array(),  
        ),
        'font'      => array(
            'class'  => array(),
            'id' => array(),
            'style' => array(),
        ),
        'span'      => array(
            'class'  => array(),
            'id' => array(),
            'dir' => array(),
            'role' => array(),
            'title' => array(),
            'aria-haspopup' => array(),
            'aria-expanded' => array(),
            'tabindex' => array(),
            'aria-labelledby' => array(),
            'aria-hidden' => array(),
            'style' => array(),
        ),
        'nav'      => array(
            'class'  => array(),
            'id' => array(),
            'style' => array(),
        ),
        'input'      => array(
            'type'  => array(),
            'id' => array(),
            'class' => array(),
            'name' => array(),
            'value' => array(),
            'onclick' => array(),
            'onchange' => array(),
            'data-validation' => array(),
            'required' => array(),
            'size' => array(),
            'placeholder' => array(),
            'checked' => array(),
            'autocomplete' => array(),
            'multiple' => array(),
            'rel' => array(),
            'maxlength' => array(),
            'disabled' => array(),
            'readonly' => array(),
            'data-for' => array(),
            'credit_userid' => array(),
            'data-dismiss' => array(),
            'data-validation-optional' => array(),
            'data-myrequired' => array(),
            'style' => array(),
        ),
        'textarea'     => array(
            'rows' => array(),
            'name' => array(),
            'class' => array(),
            'id' => array(),
            'value' => array(),
            'cols' => array(),
            'data-validation' => array(),
            'data-myrequired' => array(),
            'autocomplete' => array(),
            'style' => array(),
        ),
        'button'      => array(
            'type'  => array(),
            'id' => array(),
            'class' => array(),
            'name' => array(),
            'value' => array(),
            'onclick' => array(),
            'data-validation' => array(),
            'required' => array(),
            'data-dismiss' => array(),
            'style' => array(),
        ),
        'select'      => array(
            'id' => array(),
            'class' => array(),
            'name' => array(),
            'onchange' => array(),
            'data-validation' => array(),
            'required' => array(),
            'multiple' => array(),
            'data-myrequired' => array(),
            'style' => array(),
        ),
        'option'      => array(
            'id' => array(),
            'class' => array(),
            'name' => array(),
            'value' => array(),
            'selected' => array(),
            'tabindex' => array(),
            'aria-hidden' => array(),
            'style' => array(),
        ),
        'img'      => array(
            'src'  => array(),
            'id' => array(),
            'class' => array(),
            'onclick' => array(),
            'alt' => array(),
            'width' => array(),
            'height' => array(),
            'border' => array(),
            'style' => array(),
        ),
        'link'      => array(
            'src'  => array(),
            'id' => array(),
            'rel' => array(),
            'href' => array(),
            'media' => array(),
            'style' => array(),
        ),
        'meta'      => array(
            'property'  => array(),
            'content' => array(),
            'style' => array(),
        ),
        'a'      => array(
            'href'  => array(),
            'title' => array(),
            'onclick' => array(),
            'id' => array(),
            'class' => array(),
            'name' => array(),
            'data-toggle' => array(),
            'data-placement' => array(),
            'data-id' => array(),
            'data-name' => array(),
            'data-email' => array(),
            'data-id' => array(),
            'data-name' => array(),
            'data-email' => array(),
            'message' => array(),
            'confirmmessage' => array(),
            'data-for' => array(),
            'data-sortby' => array(),
            'data-image1' => array(),
            'data-image2' => array(),
            'data-showmore' => array(),
            'data-scrolltask' => array(),
            'data-offset' => array(),
            'data-section' => array(),
            'data-parent' => array(),
            'target' => array(),
            'style' => array(),
        ),
        'ul'      => array(
            'type'  => array(),
            'id' => array(),
            'class' => array(),
            'style' => array(),
        ),
        'ol'      => array(
            'id' => array(),
            'class' => array(),
            'style' => array(),
        ),
        'li'      => array(
            'id' => array(),
            'class' => array(),
            'onclick' => array(),
            'style' => array(),
        ),
        'dl'      => array(
            'id' => array(),
            'class' => array(),
            'style' => array(),
        ),
        'dt'      => array(
            'id' => array(),
            'class' => array(),
            'style' => array(),
        ),
        'dd'      => array(
            'id' => array(),
            'class' => array(),
            'style' => array(),
        ),
        'table'      => array(
            'id' => array(),
            'class' => array(),
            'style' => array(),
        ),
        'tr'      => array(
            'id' => array(),
            'class' => array(),
            'style' => array(),
        ),
        'td'      => array(
            'id' => array(),
            'class' => array(),
            'style' => array(),
        ),
        'th'      => array(
            'id' => array(),
            'class' => array(),
            'style' => array(),
        ),
        'p'      => array(
            'id' => array(),
            'class' => array(),
            'style' => array(),
        ),
        'form'      => array(
            'id' => array(),
            'class' => array(),
            'method' => array(),
            'action' => array(),
            'enctype' => array(),
        ),
        'label'      => array(
            'id' => array(),
            'class' => array(),
            'for' => array(),
            'onclick' => array(),
            'style' => array(),
        ),
        'i'     => array(
            'id' => array(),
            'class' => array(),
            'aria-hidden' => array(),
            'style' => array(),
        ),
        'style'     => array(
            'src' => array(),
            'type' => array(),
            'class' => array(),
            'style' => array(),
        ),
        'script'     => array(
            'src' => array(),
            'type' => array(),
            'class' => array(),
            'style' => array(),
        ),
        'br'     => array(
            'style' => array(),),
        'hr'     => array(
            'id' => array(),
            'class' => array(),
            'style' => array(),),
        'b'     => array(
            'style' => array(),),
        'em'     => array(
            'style' => array(),),
        'strong' => array(
            'style' => array(),
        ),
        'small' => array(
            'style' => array(),),
        ' ' => array(),
    ));
}
?>
