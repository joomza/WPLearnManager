<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
$msgkey = JSLEARNMANAGERincluder::getJSModel('lecture')->getMessagekey();
JSLEARNMANAGERmessages::getLayoutMessage($msgkey);
JSLEARNMANAGERbreadcrumbs::getBreadcrumbs();
wp_enqueue_script('jquery-ui-datepicker');
wp_enqueue_script('jquery-ui-core');
wp_enqueue_style('jslm_venoboxcss',JSLEARNMANAGER_PLUGIN_URL .'includes/css/venobox.css');
wp_enqueue_script('jslm_venoboxjs',JSLEARNMANAGER_PLUGIN_URL .'includes/js/venobox.js');
$config = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigByFor('default');
if ($config['date_format'] == 'm/d/Y' || $config['date_format'] == 'd/m/y' || $config['date_format'] == 'm/d/y' || $config['date_format'] == 'd/m/Y') {
	$dash = '/';
} else {
	$dash = '-';
}
$dateformat = $config['date_format'];
$firstdash = strpos($dateformat, $dash, 0);
$firstvalue = substr($dateformat, 0, $firstdash);
$firstdash = $firstdash + 1;
$seconddash = strpos($dateformat, $dash, $firstdash);
$secondvalue = substr($dateformat, $firstdash, $seconddash - $firstdash);
$seconddash = $seconddash + 1;
$thirdvalue = substr($dateformat, $seconddash, strlen($dateformat) - $seconddash);
$js_dateformat = '%' . $firstvalue . $dash . '%' . $secondvalue . $dash . '%' . $thirdvalue;
$js_scriptdateformat = $firstvalue . $dash . $secondvalue . $dash . $thirdvalue;
$js_scriptdateformat = str_replace('Y', 'yy', $js_scriptdateformat);
$image_file_types = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('image_file_type');
$file_size = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('allowed_file_size');
$file_file_types = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('file_file_type');

?>
</script>
<script>
	var b = 0;
 	var c = 0;
 	var counter  = 1
 	function createinput(id){
 		document.getElementById(id).onkeypress = function(e){
		    if (!e) e = window.event;
		    	var keyCode = e.keyCode || e.which;
		    if (keyCode == '13'){
		      addAnswerInput('addDynamicAnswers');
		      return false;
		    }
  		}
	}

	var invalidext = 0;
	var invalidsize = 0;
	var validExtImages = "<?php echo JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('image_file_type'); ?>";
  	var validExtFiles  = "<?php echo JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('file_file_type'); ?>";
  	var validExt = validExtImages.concat(",", validExtFiles);
  	function fileExtValidate(fdata) {
     	var filePath = fdata.name;
     	var getFileExt = filePath.substring(filePath.lastIndexOf('.') + 1).toLowerCase();
     	var pos = validExt.indexOf(getFileExt);
     	if(pos < 0) {
            invalidext++;
            return false;
     	} else {
        	return true;
     	}
  	}


  	function fileSizeValidate(fdata){
  		var filesize = "<?php echo JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('allowed_file_size'); ?>";
     	var uploadedfilesize = fdata.size;
     	if((uploadedfilesize/1000) > filesize){
        	invalidsize++;
        	return false;
     	}else{
        	return true;
     	}
  	}

 	//call to create new Input
	var getallfileslength = 0;
	var allowfilestoupload = 0;
	var filechecker = 0;
	var jslm_pre_field_obj = '';
	function getUploadedFiles(){
 		var getallFiles = document.getElementById("uploadfiles_"+c).files;
 		var getnooffilesallowed = <?php echo JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('max_allowed_lecturesfiles') != "" ? JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('max_allowed_lecturesfiles') : 0; ?>;
 		var totaluploadedfiles = document.getElementsByName("filedata[]").length; // uploaded files in html
 		getallfileslength += getallFiles.length;
 		if(totaluploadedfiles != 0){
 			allowfilestoupload = getnooffilesallowed - totaluploadedfiles;
 			if(allowfilestoupload > getallFiles.length){
 				allowfilestoupload = getallFiles.length;
 			}
 		}else{
 			if(getnooffilesallowed < getallFiles.length){
 				allowfilestoupload = getnooffilesallowed;
 			}else{
 				allowfilestoupload = getallFiles.length;
 			}
 		}
 		jQuery("#addnewfilediv").show();
 		if(totaluploadedfiles == 0){
	 		var jslm_js_field_obj = document.createElement('div');
	 		jslm_js_field_obj.setAttribute("id","jslm_obj_upload_file_wrapper")
	       	jslm_js_field_obj.setAttribute('class','jslm_js-field-obj js-col-lg-9 js-col-md-9 no-padding');
	       	document.getElementById('appendfiles').appendChild(jslm_js_field_obj);
	       	jslm_pre_field_obj = jslm_js_field_obj;
       	}else{
       		jslm_js_field_obj = jslm_pre_field_obj;
       	}
       	console.log(jslm_js_field_obj);
 		for(var a=0; a<getallFiles.length;a++){
      		if(fileExtValidate(getallFiles[a])){
      			if(fileSizeValidate(getallFiles[a])) {
      				filechecker++;
      				if(filechecker > allowfilestoupload){
      					filechecker = 0;
      					break;
      				}
      				var newdiv = document.createElement('div');
			       	newdiv.setAttribute('id','js_uploadfiles_'+b);
			       	newdiv.setAttribute('class','jslm_upload_files_wrapper');
			       	jslm_js_field_obj.appendChild(newdiv);

			       	var inputelement = document.createElement('input'); // Create Input Field for Name
			       	inputelement.setAttribute("type", "text");
			       	inputelement.setAttribute("name", "filedata[]");
			       	inputelement.setAttribute("id","inputfile_"+b);
			       	inputelement.setAttribute("class","jslm_input_field_style");
			       	newdiv.appendChild(inputelement);

					var deletebtn = document.createElement("button");
			       	deletebtn.setAttribute("type","submit");
			       	deletebtn.setAttribute("name","dltbtn");
			       	deletebtn.setAttribute("role","submit");
			       	deletebtn.setAttribute("onclick","removeFile(js_uploadfiles_"+b+",1)");
			       	deletebtn.setAttribute("class", "jslm_delete_button fa fa-close");
			       	newdiv.appendChild(deletebtn);
			       	document.getElementById('inputfile_'+b).value = getallFiles[a].name;
			       	b++;
			    }
      		}
  		}
  		if(invalidsize > 0 || invalidext > 0){
  			alert("Some invalid files not uploaded due to invalid size or extensions");
  			invalidext = invalidsize = 0;
  		}
      	jQuery('input#uploadfiles_'+c).hide();
      	c=getallFiles.length + c;
      	filechecker = 0;
      	// jQuery('div#js_uploadFiles .jslm_file_upload').after
      	jQuery('<input class="files jslm_file_upload_input" id="uploadfiles_'+c+'" type="file"  name="filename[]" onchange="getUploadedFiles()" multiple="multiple" />').insertBefore('div#js_uploadFiles .jslm_uploadoption');
 	}

    // For removing new files
 	function removeFile(Id){
  		var elem = document.getElementById(Id.id);
      	elem.remove();
 	}

 	//For removing old files
 	function removeOldfile(id,lectureid){
 		var remove_div = document.getElementById(id.id);
		document.getElementById('remove_old_'+lectureid).value = lectureid;
		jQuery(remove_div).hide();
	}

	//For removing Questions
	function removeQuestion(id,questionid){
		id = id+questionid;
		var remove_div = document.getElementById(id);
		document.getElementById('remove_ques_'+questionid).value = questionid;
		jQuery('#'+id).hide();
	}

	// For uploaded video urls
	var filelist = [];
	function videoValidURL() {
		jQuery("#videourl").css("border-color","#d4d4d5");
		jQuery("#videotitle").css("border-color","#d4d4d5");
		jQuery("#videovalidatetitle").remove();
		jQuery("#videovalidateurl").remove();
		jQuery("#urlvalidate").remove();
		var str = document.getElementById("videourl").value;
		var res = String(str).match(/^(http:\/\/|https:\/\/)(vimeo\.com|player.vimeo.com|youtu\.be|www\.youtube\.com)\/([\w\/]+)([\?].*)?$/);
		if(res == null){
			jQuery("#videourl").css("border-color","red");
			jQuery("#videourl").append().after("<div id='urlvalidate' class='urlvalidation'>URL is not valid.</div>");
			return false;
		}else{
			return true;
	    }
	}

 	function getvideourl(){

		jQuery("#videourl").css("border-color","#d4d4d5");
		jQuery("#videotitle").css("border-color","#d4d4d5");
		jQuery("#videovalidatetitle").remove();
		jQuery("#videovalidateurl").remove();
		jQuery("#urlvalidate").remove();
		if(document.getElementById("videotitle").value != '' && document.getElementById("videourl").value != ''){
	      	var getallFiles = document.getElementById("videourl").value;
	      	var getvideotitle = document.getElementById("videotitle").value;
	      	if(videoValidURL(getallFiles)){
	      		jQuery("#addnewvideodiv").show();
	      		if(filelist.indexOf(getallFiles) == 0){
		      		document.getElementById("videotitle").value = "";
		      		document.getElementById("videourl").value = "";
		      		return;
		      	}
				filelist.push(getallFiles);
			    var newdiv = document.createElement('div');
		      	newdiv.setAttribute('id','videourl_'+filelist.length);
		      	newdiv.setAttribute('class','jslm_upload_video_files_wrapper');
		      	document.getElementById('jslm_obj_video_append').appendChild(newdiv);

		      	var inputelement = document.createElement('input'); // Create Input Field for Title
		  		inputelement.setAttribute("type", "text");
		      	inputelement.setAttribute("name", "video_title[][filename]");
		      	inputelement.setAttribute("id","inputtitle_"+filelist.length);
		      	inputelement.setAttribute("class","jslm_input_field_style jslm_input_video_title");
		      	newdiv.appendChild(inputelement);

		  		var inputelement = document.createElement('input'); // Create Input Field for Url
		  		inputelement.setAttribute("type", "text");
		      	inputelement.setAttribute("name", "video_url[][fileurl]");
		      	inputelement.setAttribute("id","inputurl_"+filelist.length);
		      	inputelement.setAttribute("class","jslm_input_field_style jslm_input_video_url");
		  		newdiv.appendChild(inputelement);

		  		var deletebtn = document.createElement("button");
		  		deletebtn.setAttribute("type","button");
		      	deletebtn.setAttribute("name","dltbtn");
		      	deletebtn.setAttribute("onclick","removediv(videourl_"+filelist.length+")")
		      	deletebtn.setAttribute("class", "jslm_delete_button fa fa-close");
		      	newdiv.appendChild(deletebtn);

		      	document.getElementById('inputtitle_'+filelist.length).value = getvideotitle;
		      	document.getElementById('inputurl_'+filelist.length).value = getallFiles;
		      	document.getElementById("videotitle").value = "";
		      	document.getElementById("videourl").value = "";
	      	}
      	}else{
      		if(document.getElementById("videourl").value == ""){
	      		jQuery("#videourl").css("border-color","red");
				jQuery("#videourl").append().after("<div id='videovalidateurl' class='urlvalidation'>Required field is missing.</div>");
      		}
      		if(document.getElementById("videotitle").value == ""){
      			jQuery("#videotitle").css("border-color","red");
				jQuery("#videotitle").append().after("<div id='videovalidatetitle' class='urlvalidation'>Required field is missing.</div>");
      		}
      	}
 	}
 	// For remove specific div
 	function removediv(Id){
 		var elem = document.getElementById(Id.id);
      	elem.remove();
	}

	// For Add new question and options
	function addAnswerInput(divName){

        var rowdiv = document.createElement('div');
        rowdiv.setAttribute("class", "jslm_row_data");
        rowdiv.setAttribute("id",'delete_option_'+counter);
        document.getElementById(divName).appendChild(rowdiv);

        var choicespan = document.createElement('span');
        choicespan.setAttribute("class","jslm_choice");
        rowdiv.appendChild(choicespan);

        var newspan = document.createElement('span');
		newspan.setAttribute("class","jslm_quiz_input_wrapper");
		choicespan.appendChild(newspan);

		var checkboxwrp = document.createElement('span');
		checkboxwrp.setAttribute("class","jslm_checkbox_wrp");
		newspan.appendChild(checkboxwrp);

		var checkbox = document.createElement('input');
		checkbox.setAttribute("type","radio");
		checkbox.setAttribute("id","option_checkbox");
		checkbox.setAttribute("name","option_checkbox");
		checkbox.setAttribute("class","jslm_input_checkbox_style");
		checkboxwrp.appendChild(checkbox);

		var inputwrp = document.createElement('span');
		inputwrp.setAttribute("class","jslm_input_wrp");
		newspan.appendChild(inputwrp);


		var newinput = document.createElement('input');
		newinput.setAttribute("type","text");
		newinput.setAttribute("name","options");
		newinput.setAttribute("id","ques_option_"+counter);
		newinput.setAttribute("class","jslm_input_style");
		newinput.setAttribute("onkeyup","createinput('ques_option_"+counter+"')");
		inputwrp.appendChild(newinput);


		var newspandlt = document.createElement('span');
		newspandlt.setAttribute("class","jslm_delete");
		choicespan.appendChild(newspandlt);

		var hyperlink = document.createElement('a');
		hyperlink.setAttribute("class","jslm_delete_button");
		hyperlink.setAttribute("onclick","deleteQuestionPortion('delete_option_"+counter+"')");
		newspandlt.appendChild(hyperlink);

		var dlticon = document.createElement('i');
		dlticon.setAttribute("class","fa fa-times");
		hyperlink.appendChild(dlticon);
		counter++;
    }

    var d=0;
    var optionanswers = 0;
    // For Add new question and options
	function addAnswerInput(divName){

        var rowdiv = document.createElement('div');
        rowdiv.setAttribute("class", "jslm_row_data");
        rowdiv.setAttribute("id",'delete_option_'+counter);
        document.getElementById(divName).appendChild(rowdiv);

        var choicespan = document.createElement('div');
        choicespan.setAttribute("class","jslm_choice");
        rowdiv.appendChild(choicespan);

        var newspan = document.createElement('div');
		newspan.setAttribute("class","jslm_quiz_input_wrapper");
		choicespan.appendChild(newspan);

		var checkboxwrp = document.createElement('div');
		checkboxwrp.setAttribute("class","jslm_checkbox_wrp");
		newspan.appendChild(checkboxwrp);

		var exp = document.createElement('div');
		exp.setAttribute("class","exp");
		checkboxwrp.appendChild(exp);


		var checkbox = document.createElement('div');
		checkbox.setAttribute("class","checkbox");
		exp.appendChild(checkbox);

		var input = document.createElement('input');
		input.setAttribute("id","option_checkbox"+counter);
		input.setAttribute("name","option_checkbox");
		input.setAttribute("value",counter);
		input.setAttribute("type","radio");
		checkbox.appendChild(input);

		var label = document.createElement('LABEL');
		label.setAttribute("for","option_checkbox"+counter);
		checkbox.appendChild(label);

		var span = document.createElement('span');
		label.appendChild(span);

		var inputwrp = document.createElement('div');
		inputwrp.setAttribute("class","jslm_input_wrp");
		newspan.appendChild(inputwrp);


		var newinput = document.createElement('input');
		newinput.setAttribute("type","text");
		newinput.setAttribute("name","options");
		newinput.setAttribute("id","ques_option_"+counter);
		newinput.setAttribute("class","jslm_input_style");
		newinput.setAttribute("onkeyup","createinput('ques_option_"+counter+"')");
		inputwrp.appendChild(newinput);


		var newspandlt = document.createElement('span');
		newspandlt.setAttribute("class","jslm_delete");
		choicespan.appendChild(newspandlt);

		var hyperlink = document.createElement('a');
		hyperlink.setAttribute("class","jslm_delete_button");
		hyperlink.setAttribute("onclick","deleteQuestionPortion('delete_option_"+counter+"')");
		newspandlt.appendChild(hyperlink);

		var dlticon = document.createElement('i');
		dlticon.setAttribute("class","fa fa-times");
		hyperlink.appendChild(dlticon);
		counter++;
    }

    var d=0;
    var optionanswers = 0;
    function addquestionsinput(divName){
    	m = 0;
    	var question = document.getElementById('quiz_question').value;
    	var alloptions = document.getElementsByName('options');
    	var allselectedoptions = document.getElementsByName('option_checkbox');
    	for (var rs = 0; rs < allselectedoptions.length; rs++) {
		    if (allselectedoptions[rs].checked && alloptions[rs].value != "") {
		    	var optionselected = true;
		        break;
		    }else if(allselectedoptions[rs].checked){
		    	var optionselectedcheck = true;
		    }
		}
		for (var rs = 0; rs < alloptions.length; rs++) {
		    if (alloptions[rs].value != "") {
		    	var questionoption = true;
		    	break;
		    }
		}
		if(question != "" && questionoption && optionselected){
			var wrap_div = document.createElement('div');
	    	wrap_div.setAttribute("id","add_new_question_"+d);
	    	wrap_div.setAttribute("class","jslm_wrapper");
	    	document.getElementById(divName).appendChild(wrap_div);

	    	var quiz_wrap_div = document.createElement('div');
	    	quiz_wrap_div.setAttribute("class","jslm_quiz_wrapper");
	    	quiz_wrap_div.setAttribute("id","quiz_wrapper");
	    	wrap_div.appendChild(quiz_wrap_div);

	    	var quiz_head_div = document.createElement('div');
	    	quiz_head_div.setAttribute("class","jslm_quiz_heading jslm_heading_width");
	    	quiz_wrap_div.appendChild(quiz_head_div);

	    	var add_new_question = document.createElement('input');
	    	add_new_question.setAttribute("class","jslm_ques_input_style");
	    	add_new_question.setAttribute("type","text");
	    	add_new_question.setAttribute("name","quizquestion[]["+d+"]");
	    	add_new_question.setAttribute("value",question);
	    	quiz_head_div.appendChild(add_new_question);

	    	var spanopdlt = document.createElement('span');
	  		spanopdlt.setAttribute("class","jslm_delete");
	  		quiz_wrap_div.appendChild(spanopdlt);

	    	var deletehyper = document.createElement("a");
	  		deletehyper.setAttribute("class","jslm_delete_button");
	      	deletehyper.setAttribute("onclick","deleteQuestionPortion('add_new_question_"+d+"')");
	      	spanopdlt.appendChild(deletehyper);

	      	var dlthypericon = document.createElement('i');
	      	dlthypericon.setAttribute("class","fa fa-trash");
	      	deletehyper.appendChild(dlthypericon);

	    	var quiz_body_div = document.createElement('div');
	    	quiz_body_div.setAttribute("class","jslm_quiz_body");
	    	quiz_wrap_div.appendChild(quiz_body_div);



	    	for(var new_options = 0; new_options<alloptions.length; new_options++){
	      		if(alloptions[new_options].value != '' && alloptions[new_options].value != null){
		    		var checkboxselect = false;
		    		if(allselectedoptions[new_options].checked === true){
		    			checkboxselect = true;
		    		}
		    		var quiz_row_div = document.createElement('div');
			    	quiz_row_div.setAttribute("class","jslm_row_data");
			    	quiz_body_div.appendChild(quiz_row_div);

		    		var choice_span = document.createElement('div');
			    	choice_span.setAttribute("class","jslm_choice");
			    	choice_span.setAttribute("id","new_answer_"+optionanswers);
			    	quiz_row_div.appendChild(choice_span);

		    		var checkbox_span = document.createElement('div');
	    			checkbox_span.setAttribute("class","jslm_quiz_input_wrapper");
	    			choice_span.appendChild(checkbox_span);

		    		var checkboxwrp = document.createElement('div');
	    			checkboxwrp.setAttribute("class","jslm_checkbox_wrp");
	    			checkbox_span.appendChild(checkboxwrp);

	    			if(checkboxselect == true){
	    				var optioncheckbox = document.createElement("input");
			    		optioncheckbox.setAttribute("type","radio");
			    		optioncheckbox.setAttribute("name","checkboxselect[]["+optionanswers+"]")
			    		optioncheckbox.setAttribute("value",alloptions[new_options].value);
			    		optioncheckbox.setAttribute("class","jslm_input_checkbox_style");
			    		optioncheckbox.setAttribute("readonly","readonly");
			    		optioncheckbox.setAttribute("checked","checked");
		    			checkboxwrp.appendChild(optioncheckbox);
		    		}


		    		var inputwrp = document.createElement('div');
	    			inputwrp.setAttribute("class","jslm_input_wrp");
	    			checkbox_span.appendChild(inputwrp);

		    		var optionlist = document.createElement("input");
		      		optionlist.setAttribute("type","text");
		      		optionlist.setAttribute("name","answer[]["+d+"]["+optionanswers+"]");
		      		optionlist.setAttribute("id" , "answer_"+d);
		      		optionlist.setAttribute("class" , "jslm_input_style");
		      		optionlist.setAttribute("value",alloptions[new_options].value);
		      		inputwrp.appendChild(optionlist);

		      		var spanopdlt = document.createElement('span');
		      		spanopdlt.setAttribute("class","jslm_delete");
		      		choice_span.appendChild(spanopdlt);

		      		var optionhyper = document.createElement('a');
		      		optionhyper.setAttribute("onclick","removediv(new_answer_"+optionanswers+")");
		      		optionhyper.setAttribute("class","jslm_delete_button");

		      		spanopdlt.appendChild(optionhyper);

		      		var dlticon = document.createElement('i');
					dlticon.setAttribute("class","fa fa-times");
					optionhyper.appendChild(dlticon);
		    		optionanswers++;
	    		}
	    	}
	    	d++;
	    	document.getElementById('quiz_question').value = "";
	    	jQuery("#new_upload_questions").css("display","block");
	    	resetQuizFields();
	    }else{
	    	jQuery("#lecture_error_message").css("display","block");
			jQuery("#lecture_error_message").html("");
			if(question == ""){
				jQuery("#lecture_error_message").append("Question is missing");
			}else if(!optionselectedcheck){
				jQuery("#lecture_error_message").append("No option is selected");
			}else if(!questionoption){
				jQuery("#lecture_error_message").append("Add atleast one option");
			}else{
				jQuery("#lecture_error_message").append("Seletced Option value missing.");
			}
	    }
    }

    function resetQuizFields(){
    	jQuery('#addDynamicAnswers').empty();
    	addAnswerInput('addDynamicAnswers');
	}

    function deleteQuestionPortion(Id){
    	var elem = document.getElementById(Id);
      	elem.remove();
	}

	// Active tab
	jQuery(function () {
		var url = window.location.hash;
		if (url != "") {
			jQuery(".tab-pane").removeClass("active").addClass("fade");
		    jQuery(url).addClass("active in").removeClass("fade");
		    jQuery('a[href="'+ url +'"]').tab('show');
		}
	});

	jQuery(document).ready(function(){
		jQuery(".venobox").venobox({
            infinigall: true,
            titleattr: 'data-title',
        });
		jQuery('.custom_date').datepicker({dateFormat: '<?php echo esc_js($js_scriptdateformat); ?>'});
		jQuery("#addmoreoptions").click(function(){
			addAnswerInput('addDynamicAnswers');
	      	return false;
		});
		jQuery.validate();
	});

</script>
<center><div id="loading" style="display: none"><img src="<?php echo JSLEARNMANAGER_PLUGIN_URL ?>includes/images/loading.gif"></div></center>
<div id="jslearnmanageradmin-wrapper">
    <div id="jslearnmanageradmin-leftmenu">
        <?php  JSLEARNMANAGERincluder::getClassesInclude('jslearnmanageradminsidemenu'); ?>
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
                        <li><?php echo __('Add Lecture','js-support-ticket'); ?></li>
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
            <span class="jslm_heading-dashboard"><?php echo __('Add Lecture', 'learn-manager') ?></span>
            <a target="blank" href="https://www.youtube.com/watch?v=cPH5zKlhNpo&ab_channel=WPLearnManager&start=78" class="jslmsadmin-add-link black-bg" title="<?php echo __('Watch Video', 'learn-manager'); ?>">
                <img alt="arrow" src="<?php echo JSLEARNMANAGER_PLUGIN_URL; ?>includes/images/play-btn.png">
                <?php echo __('Watch Video', 'learn-manager'); ?>
            </a>
        </div>
        <div id="jslms-data-wrp">
    	<?php if(jslearnmanager::$_error_flag_message == null ) { $course = jslearnmanager::$_data['course_info']; } ?>
        <?php if (jslearnmanager::$_error_flag_message == null) { ?>
		   	<div class="jslm_top_content_data">
				<div class="jslm_left_content">
					<div class="jslm_img_wrapper">
						<?php if(empty($course->course_file)){
							$coursedefault = JSLEARNMANAGER_PLUGIN_URL.'includes/images/default-course-image.png';
						}else{
							$coursedefault = $course->course_file;
						}	?>
						<img src="<?php echo esc_url($coursedefault); ?>">
					</div>
				</div>
				<div class="jslm_right_content">
					<div class="jslm_top-content">
						<a class="jslm_title_heading" href="<?php echo esc_url(admin_url('admin.php?page=jslm_course&jslmslay=coursedetail&jslearnmanagerid='.$course->course_id)); ?>"><?php echo esc_html($course->course_name); ?></a>
					</div>
					<div class="jslm_middle_content">
						<span class="jslm_data_wrapper">
				   			<span class="jslm_left">
				      			<span class="jslm_img_wrapper">
				      				<?php if(empty($course->image)){
				      					$defaultimage = JSLEARNMANAGER_PLUGIN_URL.'includes/images/default-user.png';
				      				}else{
				      					$defaultimage = $course->image;
			      					}
		      						?>
				         			<img src="<?php echo esc_url($defaultimage);?>">
				      			</span>
				   			</span>
				  			<span class="jslm_right">
				      			<span class="jslm_title"><?php echo __("Instructor","learn-manager"); ?></span>
				      				<span class="jslm_text"><?php echo esc_html(__($course->instructor_name)); ?></span>
				   			</span>
						</span>
						<span class="jslm_data_wrapper">
				  			<span class="jslm_right">
				      			<span class="jslm_title"><?php echo __("Category","learn-manager"); ?></span>
				      				<span class="jslm_text"><?php echo esc_html(__($course->category_name)); ?></span>
				   			</span>
						</span>
						<span class="jslm_data_wrapper">
				  			<span class="jslm_right">
				      			<span class="jslm_title"><?php echo __("Section","learn-manager"); ?></span>
				      				<span class="jslm_text"><?php echo esc_html(__($course->section_name)); ?></span>
				   			</span>
						</span>
					</div>
				</div>
			</div>
			<form action="<?php echo admin_url("admin.php?page=jslm_lecture&action=jslmstask&task=savelecture&jslearnmanagerpageid=jslearnmanager::getPageid()"); ?>" method="post" name="detail_lectureForm" id="jslearnmanager-form" enctype="multipart/form-data">
				<div class="jslm_content_data">
					<?php foreach (jslearnmanager::$_data[3] AS $field) {
							switch ($field->field) {
								case 'name':?>
									<div class="jslm_js-field-wrapper js-row no-margin">
										<div class="jslm_js-field-title js-col-lg-3 js-col-md-3 jslm_no-padding"><?php echo __("Lecture Title","js_learn-manager")?>
											<?php
											$req = "";
											if ($field->required == 1) {
				                				$req = 'required';
				                				echo '<font color="red">&nbsp*</font>';
				             				} ?>
			             				</div>
										<div class="jslm_js-field-obj js-col-lg-9 js-col-md-9 no-padding"><?php echo wp_kses(JSLEARNMANAGERformfield::text($field->field, isset(jslearnmanager::$_data[0]->name) ? __(jslearnmanager::$_data[0]->name,'learn-manager') : '',array('placeholder'=>'Add Title','class'=>'jslm_inputbox jslm_one', 'data-validation'=>$req)), JSLEARNMANAGER_ALLOWED_TAGS); ?></div>
									</div>
								<?php
								break;
								case 'description': ?>
									<div class="jslm_js-field-wrapper js-row no-margin">
										<div class="jslm_js-field-title js-col-lg-3 js-col-md-3 jslm_no-padding"><?php echo __("Lecture Description","js_learn-manager")?>
											<?php
											$req = '';
											if ($field->required == 1) {
				                				$req = 'required';
				                				echo '<font color="red">&nbsp*</font>';
				             				} ?>

			             				</div>
										<div class="jslm_js-field-obj js-col-lg-9 js-col-md-9 no-padding">
											<?php echo wp_editor(isset(jslearnmanager::$_data[0]->description) ? jslearnmanager::$_data[0]->description: '', $field->field, array('media_buttons' => false, 'data-validation' => $req)); ?>
										</div>
									</div>

								<?php break;
								default:
									echo JSLEARNMANAGERincluder::getObjectClass('customfields')->formCustomFields($field,2);

						 		break;
							}
						}
					?>
					<?php echo wp_kses(JSLEARNMANAGERformfield::hidden('id', isset(jslearnmanager::$_data[0]->id) ? __(jslearnmanager::$_data[0]->id , 'learn-manager') : ''), JSLEARNMANAGER_ALLOWED_TAGS); ?>
					<?php echo wp_kses(JSLEARNMANAGERformfield::hidden('section_id', isset(jslearnmanager::$_data['course_info']->section_id) ? jslearnmanager::$_data['course_info']->section_id : ''), JSLEARNMANAGER_ALLOWED_TAGS); ?>
					<!-- Add Files & Images -->
					<?php foreach (jslearnmanager::$_data[3] AS $field) {
							switch ($field->field) {
								case 'filename':	?>
									<div id="js_uploadFiles" class="jslm_js-field-wrapper js-row no-margin" >
										<div class="jslm_js-field-title js-col-lg-3 js-col-md-3 jslm_no-padding"><?php echo __("Add Files/images","learn-manager"); ?></div>
			                          	<div class="jslm_js-field-obj js-col-lg-9 js-col-md-9 no-padding jslm_file_upload">
			                          		<input id="uploadfiles_0" class="jslm_file_upload_input jslm_uploadoption" type="file" name="filename[]" multiple="multiple" onchange="getUploadedFiles()"/><br/>
				                          	<small><?php echo __("Max file size","js_learn-manager")?> <?php echo esc_html($file_size)." kb." ; ?><?php echo __(" Allowed Type: ","js_learn-manager")?><?php echo "(".$image_file_types.", ".$file_file_types.")"; ?></small><br/>
											<small><?php echo __("Files Allowed :","js_learn-manager")?> <?php echo JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('max_allowed_lecturesfiles') != "" ? JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('max_allowed_lecturesfiles') : 0; ?></small>
										</div>
									</div>
									<div id="appendfiles" class="jslm_append_files jslm_js-field-wrapper js-row no-margin">
										<div class="jslm_js-field-title js-col-lg-3 js-col-md-3 jslm_no-padding" style="display: none" id="addnewfilediv"><?php echo __("Recently added files","js_learn-manager")?></div>
									</div>
		 							<?php if(isset(jslearnmanager::$_data['files']) && count(jslearnmanager::$_data['files']) > 0){ ?>
					      				<div class="jslm_js-field-wrapper js-row no-margin">
						      				<div class="jslm_js-field-title js-col-lg-3 js-col-md-3 jslm_no-padding"><?php echo __("Existing Images & Files","js_learn-manager")?></div>
						      				<div class="js-col-lg-9 js-col-md-9">
						      				<?php
							      				for($f=0, $fp = count(jslearnmanager::$_data['files']); $f < $fp; $f++ ){
							      					$filedata = jslearnmanager::$_data['files'][$f];
							      					if (strstr($image_file_types, $filedata->filetype)) {
													    $allow = "image";
													} else {
													    $allow = "iframe";
													} ?>
										      		<div class="jslm_row" id="jslm_file_<?php echo esc_attr($filedata->file_id); ?>">
										      			<span class="jslm_left jslm_left_files"><i class="fa fa-file-image-o"></i>
										      				<?php echo esc_html($filedata->filename); ?>
										      			</span>
										      			<span class="jslm_right">
										      				<input type="hidden" name="deletefiles[]" id="remove_old_<?php echo esc_attr($filedata->file_id); ?>" data-atr-lecture_id="<?php echo esc_attr($filedata->file_id) ?>" value="" />
										      				<span class="jslm_logo"><a href="#" onclick="removeOldfile(jslm_file_<?php echo esc_js($filedata->file_id); ?>,<?php echo esc_js($filedata->file_id); ?>)" class="jslm_logos"><i class="fa fa-trash"></i></a></span>
										      				<span class="jslm_logo"><a data-gall="gallery01" data-vbtype="<?php echo esc_attr($allow); ?>" href="<?php echo esc_url($filedata->fileurl); ?>" class="jslm_logos venobox" ><i class="fa fa-eye"></i></a></span>
										      			</span>
										      		</div>
										<?php	} ?>
											</div>
										</div>
						      		<?php
						      		} ?>

					<?php  	break;
							}
						}	?>
					<?php echo wp_kses(JSLEARNMANAGERformfield::hidden('jslm_sid', isset(jslearnmanager::$_data['course_info']->section_id) ? __(jslearnmanager::$_data['course_info']->section_id , 'learn-manager') : ''), JSLEARNMANAGER_ALLOWED_TAGS); ?>
					<?php echo wp_kses(JSLEARNMANAGERformfield::hidden('lecture_id', isset(jslearnmanager::$_data[0]->id) ? __(jslearnmanager::$_data[0]->id , 'learn-manager') : ''), JSLEARNMANAGER_ALLOWED_TAGS); ?>

					<!-- Add Files & Images -->
					<!-- Add Videos-->
			    	<?php foreach (jslearnmanager::$_data[3] AS $field) {
							switch ($field->field) {
			    				case 'file_type': ?>
						    		<div class="jslm_js-field-wrapper js-row no-margin">
						    			<div class="jslm_js-field-title js-col-lg-3 js-col-md-3 jslm_no-padding">
						    				<?php echo __("Add Lecture Video","learn-manager"); ?>
						    			</div>
						    			<div class="jslm_js-field-obj js-col-lg-9 js-col-md-9 no-padding">
						    				<input class="js-col-lg-10 js-col-md-10" type="text" name="videotitle" id="videotitle" placeholder="<?php echo esc_html("Video Title","learn-manager"); ?>"  >
											<input class="js-col-lg-10 js-col-md-10" type="text" name="videourl" id="videourl" placeholder="<?php echo esc_html("Video Url","learn-manager"); ?>"><br /><br /><br />
											<br/><small><?php echo __("Allowed Types:","learn-manager"); ?><?php echo __("Youtube,Vimeo","learn-manager"); ?></small><br />
											<button class="jslm_btn_style" onclick="getvideourl()" type="button" required><?php echo __("Add to List","learn-manager"); ?></button>
										</div>
						    		</div>
						    		<div class="jslm_js-field-wrapper js-row no-margin">
										<div id="add_video_url" class="jslm_append_files">
											<div class="jslm_heading_wrp jslm_js-field-title js-col-lg-3 js-col-md-3 jslm_no-padding" style="display: none" id="addnewvideodiv"><?php echo __("Recently added videos","js_learn-manager")?></div>
											<div id="jslm_obj_video_append" class="jslm_js-field-obj js-col-lg-9 js-col-md-9 no-padding"></div>
										</div>
									</div>
									<?php if(isset(jslearnmanager::$_data['videofiles']) && count(jslearnmanager::$_data['videofiles'])>0){ ?>
										<div class="jslm_js-field-wrapper js-row no-margin">
											<div class="jslm_js-field-title js-col-lg-3 js-col-md-3 jslm_no-padding"><?php echo __("Existing media and Videos","js_learn-manager")?></div>
											<div class="js-col-lg-9 js-col-md-9 no-padding">
											<?php
												for ($f=0, $fp =count(jslearnmanager::$_data['videofiles']); $f < $fp; $f++) {
													if(jslearnmanager::$_data['videofiles'][$f]->filetype == 'video'){
														$videofiles = jslearnmanager::$_data['videofiles'][$f];
													} ?>
									      		<div id="jslm_file_<?php echo esc_attr($videofiles->file_id); ?>" class="jslm_row jslm_video_row">
													<span class="jslm_video_left ">
														<span class="jslm_left_logo">
													 		<i class="fa fa-youtube-play"></i>
														</span>
														<span class="jslm_right_data">
															 <span class="jslm_video_title"><?php echo isset($videofiles->filename) ? esc_html(__($videofiles->filename , 'learn-manager')) : '' ;?></span>
															 <span class="jslm_video_url"><a class="jslm_video_url_link" href="<?php echo esc_url($videofiles->videourl); ?>" target="_blank"> <?php echo isset($videofiles->videourl) ? esc_html($videofiles->videourl) : '' ;?></a></span>
														</span>
													</span>
													<span class="jslm_right jslm_right_for_video">
														<input type="hidden" name="deletevideos[]" id="remove_old_<?php echo esc_attr($videofiles->file_id);?>" value="" />
														<span class="jslm_logo"><a href="#" onclick="removeOldfile(jslm_file_<?php echo esc_js($videofiles->file_id); ?>,<?php echo esc_js($videofiles->file_id); ?>)" class="jslm_logos"><i class="fa fa-trash"></i></a></span>
														<span class="jslm_logo"><a data-gall="gallery02" data-vbtype="video" data-title="<?php echo esc_attr($videofiles->filename); ?>" href="<?php echo esc_url($videofiles->videourl); ?>" target="_blank" class="jslm_logos venobox"><i class="fa fa-eye"></i></a></span>
													</span>
												</div>
											<?php } ?>
											</div>
										</div>
						      		<?php } ?>
								    <?php echo wp_kses(JSLEARNMANAGERformfield::hidden('jslm_sid', isset(jslearnmanager::$_data['course_info']->section_id) ? jslearnmanager::$_data['course_info']->section_id : ''), JSLEARNMANAGER_ALLOWED_TAGS); ?>
									<?php echo wp_kses(JSLEARNMANAGERformfield::hidden('lecture_id', isset(jslearnmanager::$_data[0]->id) ? jslearnmanager::$_data[0]->id : ''), JSLEARNMANAGER_ALLOWED_TAGS); ?>
						<?php 	break;
						}
					} ?>
					<!-- Add Videos-->
					<!-- Add Quiz-->
					<?php do_action("jslm_quiz_wrapper_for_quiz_for_admin",$course); ?>
					<!-- Add Quiz-->
					<div class="jslm_js-field-wrapper js-row no-margin">
						<div class="jslm_js-submit-container js-col-lg-8 js-col-md-8 js-col-md-offset-2 js-col-md-offset-2">
	            			<a id="form-cancel-button" href="<?php echo esc_url(admin_url('admin.php?page=jslm_course&jslmslay=coursedetail&jslearnmanagerid=' . $course->course_id));?>#curriculum"><?php echo __("Cancel","learn-manager"); ?></a>
	            			<input class="button" type="submit" value="<?php echo  __("Save Lecture","learn-manager"); ?>">
	        			</div>
			        	<?php echo wp_kses(JSLEARNMANAGERformfield::hidden('jslm_sid',isset(jslearnmanager::$_data['course_info']->section_id)), JSLEARNMANAGER_ALLOWED_TAGS); ?>

			        	<?php echo wp_kses(JSLEARNMANAGERformfield::hidden('jslm_lid', isset(jslearnmanager::$_data[0]->id) ? jslearnmanager::$_data[0]->id : ''), JSLEARNMANAGER_ALLOWED_TAGS); ?>

					</div>
				</div>
			</form>
	</div>
	</div>
</div>
<?php }else{
	echo jslearnmanager::$_error_flag_message;
} ?>
