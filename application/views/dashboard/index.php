<h2>
<?php  
if(isset($subtitle))
{
	echo $subtitle;
}
?>
</h2>
<p>
<?php  
if(isset($content))
{
	echo $content;
}
?>
</p>
<script>
$(document).ready(function(){
	var data_post = {};
    $("#loading-div").css('display', 'block');
	$("#grid-wrapper").load('<?php echo base_url('/jo/reload_grid');?>',data_post,function(str){
	   $("#loading-div").css('display', 'none');
	});
});
</script>
<div id="filter-wrapper" style="padding-top: 10px;" >
<?php $this->load->view('dashboard/part/filter');?>
</div>
<div id="loading-div" style="text-align: center;display: none;">
    <img src="<?php echo base_url() . 'images/ajax-loader.gif'; ?>"/> 
</div>
<div id="grid-wrapper" style="margin-top: 20px;">
<?php //$this->load->view('dashboard/part/grid');?>
</div>
<input type="hidden" id="dialog-form-load-status" value="false">
<?php


	
    
