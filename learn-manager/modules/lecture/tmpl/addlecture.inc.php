<script>
	var b = 0;
 	var fileindexcounter = 0;

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
  	var validExtFiles = "<?php echo JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('file_file_type'); ?>";
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
	function getUploadedFiles(){
		var getallFiles = document.getElementById("uploadfiles_"+fileindexcounter).files;
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
			       	newdiv.setAttribute('class','jslm_upload_files_wrapper lms_upload_files_wrapper');
			       	document.getElementById('appendfiles').appendChild(newdiv);
			      
			       	var inputelement = document.createElement('input'); // Create Input Field for Name
			       	inputelement.setAttribute("type", "text");
			       	inputelement.setAttribute("name", "filedata[]");
			       	inputelement.setAttribute("id","inputfile_"+b);
			       	inputelement.setAttribute("class","jslm_input_field_style lms_input_field_style");
			       	newdiv.appendChild(inputelement);

					var deletebtn = document.createElement("button");
			       	deletebtn.setAttribute("type","submit");
			       	deletebtn.setAttribute("name","dltbtn");
			       	deletebtn.setAttribute("role","submit");
			       	deletebtn.setAttribute("onclick","removeFile(js_uploadfiles_"+b+",1)");
			       	deletebtn.setAttribute("class", "jslm_delete_button lms_delete_button fa fa-close");
			       	newdiv.appendChild(deletebtn);
			       	document.getElementById('inputfile_'+b).value = getallFiles[a].name;
			       	b++;
			    }
      		}
  		}
  		if(getallFiles.length > allowfilestoupload ){
  			alert("You've uploaded files more than allowed files limit.");
  		}
  		if(invalidsize > 0 || invalidext > 0 ){
  			alert("Some invalid files not uploaded due to invalid size or extensions");
  			invalidext = invalidsize = 0;
  		}
      	jQuery('input#uploadfiles_'+fileindexcounter).hide();
      	fileindexcounter = getallFiles.length + fileindexcounter;
      	filechecker = 0;
      	jQuery('div#js_uploadFiles .jslm_file_upload').append('<input class="files jslm_file_upload_input lms_file_upload_input lms-upl-input" id="uploadfiles_'+fileindexcounter+'" type="file"  name="filename[]" onchange="getUploadedFiles()" multiple="multiple" />');
 	}

    // For removing new files
 	function removeFile(Id,checker=0){
 		var elem = document.getElementById(Id.id);
  		if(elem == null){
  			elem = document.getElementById(id);
  		}
      	elem.parentNode.removeChild(elem);
      	if(checker == 1){
 			filechecker = 0; 
 		}
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
		      		alert("You've entered Same URL.");
		      		return;
		      	}
				filelist.push(getallFiles);
			    var newdiv = document.createElement('div');
		      	newdiv.setAttribute('id','videourl_'+filelist.length);
		      	newdiv.setAttribute('class','jslm_upload_video_files_wrapper lms_upload_video_files_wrapper');
		      	document.getElementById('add_video_url').appendChild(newdiv);

		      	var inputelement = document.createElement('input'); // Create Input Field for Title
		  		inputelement.setAttribute("type", "text");
		      	inputelement.setAttribute("name", "video_title[][filename]");
		      	inputelement.setAttribute("id","inputtitle_"+filelist.length);
		      	inputelement.readOnly= true ;
		      	inputelement.setAttribute("class","jslm_input_field_style lms_input_field_style jslm_input_video_title lms_input_video_title");
		      	newdiv.appendChild(inputelement);

		  		var inputelement = document.createElement('input'); // Create Input Field for Url
		  		inputelement.setAttribute("type", "text");
		      	inputelement.setAttribute("name", "video_url[][fileurl]");
		      	inputelement.setAttribute("id","inputurl_"+filelist.length);
		      	inputelement.readOnly = true;
		      	inputelement.setAttribute("class","jslm_input_field_style lms_input_field_style jslm_input_video_url lms_input_video_url");
		  		newdiv.appendChild(inputelement);

		  		var deletebtn = document.createElement("button");
		  		deletebtn.setAttribute("type","button");
		      	deletebtn.setAttribute("name","dltbtn");
		      	deletebtn.setAttribute("onclick","removediv(videourl_"+filelist.length+")")
		      	deletebtn.setAttribute("class", "jslm_delete_button lms_delete_button fa fa-close");
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
      	var removeid = Id.id.substr(Id.id.lastIndexOf('_') + 1);
      	filelist[removeid-1] = "__________";
      	elem.parentNode.removeChild(elem);
	}
	
	// For Add new question and options
	function addAnswerInput(divName){
        
        var rowdiv = document.createElement('div');
        rowdiv.setAttribute("class", "jslm_row_data lms_row_data");
        rowdiv.setAttribute("id",'delete_option_'+counter);
        document.getElementById(divName).appendChild(rowdiv);

        var choicespan = document.createElement('div');
        choicespan.setAttribute("class","jslm_choice lms_choice");
        rowdiv.appendChild(choicespan);

        var newspan = document.createElement('div');
		newspan.setAttribute("class","jslm_quiz_input_wrapper lms_quiz_input_wrapper");
		choicespan.appendChild(newspan);

		var checkboxwrp = document.createElement('div');
		checkboxwrp.setAttribute("class","jslm_checkbox_wrp lms_checkbox_wrp");
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
		inputwrp.setAttribute("class","jslm_input_wrp lms_input_wrp");
		newspan.appendChild(inputwrp);


		var newinput = document.createElement('input');
		newinput.setAttribute("type","text");
		newinput.setAttribute("name","options");
		newinput.setAttribute("id","ques_option_"+counter);
		newinput.setAttribute("class","jslm_input_style lms_input_style");
		newinput.setAttribute("placeholder","<?php echo __('Enter Choice','learn-manager'); ?>");
		newinput.setAttribute("onkeyup","createinput('ques_option_"+counter+"')");
		inputwrp.appendChild(newinput);


		var newspandlt = document.createElement('span');
		newspandlt.setAttribute("class","jslm_delete lms_delete");
		choicespan.appendChild(newspandlt);

		<?php if(jslearnmanager::$_learn_manager_theme == 1){ ?>
			var ques_optn_del = "jslm_delete_button";
		<?php }else{  ?>
			var ques_optn_del = "jslm_delete_ques_option";	
		<?php } ?>

		var hyperlink = document.createElement('a');
		hyperlink.setAttribute("class","jslm_delete_ques_option lms_delete_ques_option");
		hyperlink.setAttribute("onclick","deleteQuestionPortion('delete_option_"+counter+"')");
		newspandlt.appendChild(hyperlink);

		var dlticon = document.createElement('i');
		dlticon.setAttribute("class","fa fa-close");
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
		
    	if(question != "" && questionoption  && optionselected){
	    	jQuery("#addnewquizdiv").show();
	    	jQuery("#lecture_error_message").css("display","none");
	    	jQuery("#lecture_error_message").html("");
	    	var wrap_div = document.createElement('div');
	    	wrap_div.setAttribute("id","add_new_question_"+d);
	    	wrap_div.setAttribute("class","jslm_wrapper lms_wrapper");
	    	document.getElementById(divName).appendChild(wrap_div);

	    	var quiz_wrap_div = document.createElement('div');
	    	quiz_wrap_div.setAttribute("class","jslm_quiz_wrapper lms_quiz_wrapper");
	    	quiz_wrap_div.setAttribute("id","quiz_wrapper");
	    	wrap_div.appendChild(quiz_wrap_div);

	    	var quiz_head_div = document.createElement('div');
	    	quiz_head_div.setAttribute("class","jslm_quiz_heading lms_quiz_heading jslm_heading_width lms_heading_width");
	    	quiz_wrap_div.appendChild(quiz_head_div);

	    	var add_new_question = document.createElement('input');
	    	add_new_question.setAttribute("class","jslm_ques_input_style lms_ques_input_style");
	    	add_new_question.setAttribute("type","text");
	    	add_new_question.setAttribute("name","quizquestion[]["+d+"]");
	    	add_new_question.setAttribute("value",question);
	    	add_new_question.readOnly = true;
	    	quiz_head_div.appendChild(add_new_question);

	    	var spanopdlt = document.createElement('span');
	  		spanopdlt.setAttribute("class","jslm_delete lms_delete lms-border");
	  		quiz_wrap_div.appendChild(spanopdlt);
	    	
	    	var deletehyper = document.createElement("a");
	  		deletehyper.setAttribute("class","jslm_delete_button lms_delete_button");
	      	deletehyper.setAttribute("onclick","deleteQuestionPortion('add_new_question_"+d+"')");
	      	spanopdlt.appendChild(deletehyper);

	      	var dlthypericon = document.createElement('i');
	      	dlthypericon.setAttribute("class","fa fa-close");
	      	deletehyper.appendChild(dlthypericon);

	    	var quiz_body_div = document.createElement('div');
	    	quiz_body_div.setAttribute("class","jslm_quiz_body lms_quiz_body");
	    	quiz_wrap_div.appendChild(quiz_body_div);

	    	

	    	for(var new_options = 0; new_options<alloptions.length; new_options++){
	      		if(alloptions[new_options].value != '' && alloptions[new_options].value != null){
		    		var checkboxselect = false;
		    		if(allselectedoptions[new_options].checked === true){
		    			checkboxselect = true;
		    		}
		    		var quiz_row_div = document.createElement('div');
			    	quiz_row_div.setAttribute("class","jslm_row_data lms_row_data");
			    	quiz_row_div.setAttribute("id","new_answer_"+optionanswers);
			    	quiz_body_div.appendChild(quiz_row_div);

		    		var choice_span = document.createElement('div');
			    	choice_span.setAttribute("class","jslm_choice lms_choice");
			    	quiz_row_div.appendChild(choice_span);

		    		var checkbox_span = document.createElement('div');
	    			checkbox_span.setAttribute("class","jslm_quiz_input_wrapper lms_quiz_input_wrapper");
	    			choice_span.appendChild(checkbox_span);

		    		var checkboxwrp = document.createElement('div');
	    			checkboxwrp.setAttribute("class","jslm_checkbox_wrp lms_checkbox_wrp");
	    			checkbox_span.appendChild(checkboxwrp);
	    			
	    			var checkboxdiv = document.createElement('div');
	    			checkboxdiv.setAttribute("class","exp");
	    			checkboxwrp.appendChild(checkboxdiv);
	    			
	    			var checkboxdiv2 = document.createElement('div');
	    			checkboxdiv2.setAttribute("class","checkbox");
	    			checkboxdiv.appendChild(checkboxdiv2);

	    			if(checkboxselect == true){
	    				var optioncheckbox = document.createElement("input");
			    		optioncheckbox.setAttribute("type","radio");
			    		optioncheckbox.setAttribute("name","checkboxselect[]["+optionanswers+"]")
			    		optioncheckbox.setAttribute("value",alloptions[new_options].value);
			    		optioncheckbox.setAttribute("class","jslm_input_checkbox_style lms_input_checkbox_style");
			    		optioncheckbox.readOnly = true ;
			    		optioncheckbox.setAttribute("checked","checked");
		    			checkboxdiv2.appendChild(optioncheckbox);
		    		}

		    		var label = document.createElement('label');
	    			label.setAttribute("class","option_checkbox2");
	    			checkboxdiv2.appendChild(label);
		    		
		    		var label_span = document.createElement('span');
	    			label.appendChild(label_span);
		    		
		    		var inputwrp = document.createElement('div');
	    			inputwrp.setAttribute("class","jslm_input_wrp lms_input_wrp");
	    			checkbox_span.appendChild(inputwrp);

		    		var optionlist = document.createElement("input");
		      		optionlist.setAttribute("type","text");
		      		optionlist.setAttribute("name","answer[]["+d+"]["+optionanswers+"]");
		      		optionlist.setAttribute("id" , "answer_"+d);
		      		optionlist.setAttribute("class" , "jslm_input_style lms_input_style");
		      		optionlist.setAttribute("value",alloptions[new_options].value);
		      		optionlist.readOnly = true ;
		      		inputwrp.appendChild(optionlist);
		      		if(!checkboxselect){
			      		var spanopdlt = document.createElement('span');
			      		spanopdlt.setAttribute("class","jslm_delete lms_delete");
			      		choice_span.appendChild(spanopdlt);

			      		var optionhyper = document.createElement('a');
			      		optionhyper.setAttribute("onclick","removediv(new_answer_"+optionanswers+")");
			      		optionhyper.setAttribute("class","jslm_delete_button lms_delete_button");

			      		spanopdlt.appendChild(optionhyper);

			      		var dlticon = document.createElement('i');
						dlticon.setAttribute("class","fa fa-close");
						optionhyper.appendChild(dlticon);
					}
		    		optionanswers++;
	    		}
	    	}
	    	d++;
	    	document.getElementById('quiz_question').value = "";
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
    	if(elem == null){
    		elem = Id.id;
    		elem = document.getElementById(elem);
    	}
      	elem.parentNode.removeChild(elem);
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
		if(jQuery('.jslm_select_full_width')){
			jQuery('.jslm_select_full_width').selectable();
		}
		jQuery.validate({
			onSuccess : function(){
				jQuery("#loading").css("display","block");
			}
		});
		jQuery(".venobox").venobox({
            infinigall: true,
            titleattr: 'data-title',
        });
		jQuery("#addmoreoptions").click(function(){
			addAnswerInput('addDynamicAnswers');
	      	return false;
		});
		
	});
    
	// For edit quiz question
	function editQuizQuestion(id,divid) {
		jQuery("#loading").show();
		var htmlquestion = document.getElementById(divid).innerHTML = "";
		jQuery.post(common.ajaxurl ,{action: "jslearnmanager_ajax",jslmsmod: "lecture",task: "questionforForm",id:id} , function (data,response) {
        	if(data){
        		var g = (data);
        	  	jQuery("#"+divid).append(g);
        	  	jQuery("#loading").hide();
        	}
     	});
  	};


</script>
<script type="text/javascript">
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

</script>
