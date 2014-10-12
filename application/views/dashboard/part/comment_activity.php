<script>
$(document).ready(function() {
	$("#comment-wrapper").css('opacity', 0).animate({opacity:1}, 800);
});
</script>
<style>
.fixed-comment
{
	position: fixed;
}
</style>
<div id="comment-box">
	<div style="float:right"><a id="comment-close" href="#" style="font-size: 12px; font-weight: 3pt;"><b>Close</b></a></div>
	<div><h2 style="font-size: 16pt;text-decoration: underline;"><b>All Comments</b></h2></div>
	<div id="comment-wrapper" style="margin-top: 5px; padding-left: 5px;padding-right: 5px; max-height: 300px; overflow-x: hidden; overflow-y: auto;">
	
	</div>
	<form action="<?php echo base_url('jo/update_comment');?>" method="post" id="upload-comment" enctype="multipart/form-data">
		<div>
			<textarea name="comment_text" id="comment-text" rows="5" cols="120"></textarea>
		</div>
		<div	id="file-upload-wrapper" style="margin-top: 5px;padding-left: 5px;">
			
		</div>
		<input type="hidden" name="detail_jo_activity_comment" id="detail-jo-activity-comment" value="">
		<input type="hidden" id="file-attach-count" value="0">
		<div style="clear: left;">
			<input type="submit" id="add-comment" style="padding-left: 10px;padding-right: 10px;padding-top:5px;padding-bottom:5px; margin-left:5px;margin-top:5px;" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only ui-state-hover" name="add_comment" value="Add Comment"><span class="ui-button=text">
			<button type="button" id="add-file" style="padding-left: 10px;padding-right: 10px;padding-top:5px;padding-bottom:5px; margin-left:5px;margin-top:5px;" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only ui-state-hover"><span class="ui-button=text">+ Image</span></button>
		</div>
	</form>
	<div>
		
	</div>
</div>
<script>
$( "#comment-box" ).dialog({
    autoOpen: false,
    height: "auto",
    width: 650,
    modal: true,
    position: "center",
    dialogClass: "fixed-comment",
    open: function(){
			$(this).closest(".ui-dialog").find(".ui-dialog-titlebar:first").hide();
	      }
  });
</script>
	