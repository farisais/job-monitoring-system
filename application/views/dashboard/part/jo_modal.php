<script>
$(document).ready(function(){
	$(".division-navigator").click(function(e){
		e.preventDefault();
		$('#jo-modal-wrapper a.selected').removeClass('selected');
		$(this).addClass('selected');
		var id = $( this ).find( 'input:hidden:eq(0)' ).val();
		var post = {};
		post['division'] = id;
		post['jo_no'] = $("#current-jo").val();
		$("#div-detail-wrapper").load('<?php echo base_url('/jo/get_division_detail');?>',post,function(str){
			
		});
		var post2 = {};
		post2['division'] = id;
		$("#button-update-wrapper").load('<?php echo base_url('/jo/check_button_update');?>',post2,function(str2){

		});
	});
	
	$("#comment-close").click(function(){       
			$("#comment-box").dialog("close");
			$('textarea#comment-text').val('');
			$("#file-upload-wrapper").html('');
			var post = {};
			post['division'] = $("#division-val").val();
			post['jo_no'] = $("#current-jo").val();
			$("#div-detail-wrapper").load('<?php echo base_url('/jo/get_division_detail');?>',post,function(str){
				
			});
	    });
	$("a.fancybox").fancybox();
	
	$("#update-jo").click(function(){
		var post = {};
		var postData = [];
		var table = document.getElementById('table-jo-detail');
		for(var i=1;i<(table.rows.length - 2);i++)
		{
			var row = [];
			var inputs = table.rows[i].getElementsByTagName('input');
            if(inputs.length > 2)
            {
    			row.push(inputs[0].value);
    			row.push(inputs[1].value);
    			row.push(inputs[2].value);
                
    			postData.push(row);
            
            }
		}
		post['input'] = JSON.stringify(postData);
		var id = $( this ).find( 'input:hidden:eq(0)' ).val();
		post['division'] = $("#division-val").val();
		post['jo_no'] = $("#current-jo").val();
		$("#div-detail-wrapper").load('<?php echo base_url('/jo/update_jo_detail');?>',post,function(str){
			alert('Data berhasil diupdate');
            $("#loading-div").css('display', 'block');
			$("#grid-wrapper").load('<?php echo base_url('/jo/reload_grid');?>',post,function(str){
                $("#loading-div").css('display', 'none');
			});
		});
	});

	 $("#add-file").click(function(){
		 	$("#file-attach-count").val(parseInt($("#file-attach-count").val()) + 1);
			$("#file-upload-wrapper").append('<div id="file-'+ $("#file-attach-count").val() +'" style="clear: both;"><a id="click-'+ $("#file-attach-count").val()  +'" style="float:left;color: red; margin-right: 10px;padding-top: 5px; font-size: 12px;" href="#">x</a><div style="float:left;"><input type="file" name="userfile[]" id="userfile[]" size="20" multiple="multiple"/></div></div>');
			$("#file-upload-wrapper").append('<script>$("#click-'+ $("#file-attach-count").val()  + '").click(function(){$("#file-' + $("#file-attach-count").val() + '").remove(); $("#file-attach-count").val(parseInt($("#file-attach-count").val()) - 1);})<\/script>') ;
			});
});

</script>
<style>
a.selected
{
	text-decoration: underline;
}
a
{
	text-decoration: none;
}
</style>
<?php
if(isset($result))
{
?>
	<div><h2 style="font-size: 16pt;text-decoration: underline;"><b>JO No: <?php echo $jo_no; ?></b></h2></div>
<?php
}
?>
<div style="float:left;">
<?php 
$i=0;
foreach($divisions as $div)
{
?>
<a href="#" class="division-navigator<?php if($i==0){echo ' selected';}?>"><?php if($i > 0) {echo ' | ';};?><?php echo $div['name'];?><input type="hidden" value="<?php echo $div['id_division'];?>" ></a>
<?php
	$i++; 
}
?>
</div>
<div id="button-update-wrapper" style="text-align: center;margin-top: -12px;float: right;">
	<?php $this->load->view('dashboard/part/button_update_wrapper', array('myclass' => $myclass, 'current_division' => $current_division)); ?>
</div>
<input id="current-jo" type="hidden" value="<?php echo $jo_no;?>">
<input id="current-div" type="hidden" value="<?php echo $current_division;?>" >
<div id="div-detail-wrapper">
	<?php
		$data['current_division'] = $current_division;
		$data['jo_model'] = $jo_model;
		$this->load->view('dashboard/part/division_activity_table', $data);
	?>
</div>

