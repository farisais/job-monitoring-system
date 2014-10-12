<input id="detail-activity" type="hidden" value="<?php echo $id_detail;?>">
<script>
$(document).ready(function(){
	$(".comment-user").hover(function(){
			var id_comment = $(this).find("input[class=id-comment]").val();
			if($(this).find('input[class=id-user]').val() == '<?php echo $this->session->userdata('jms_userid');?>')
			{
				$(this).append($('<div style="float: right;font-size: 18pt;margin-top: -15px;"><a class="delete-comment" href="#" style="color: red;"><b>x</b></a></div>'));
				$(this).append($('<script>$(".delete-comment").click(function(){'
						+ 'if(confirm("Are you sure you want to delete this comment?"))'
						+ '{'
							+ 'var post = {};'
							+ 'post["id_detail_jo_activity"] = $("#detail-activity").val();'
							+ 'post["id_comment"] = ' + id_comment + ';'
							+ '$("#comment-wrapper").load("<?php echo base_url('/jo/delete_comment');?>",post,function(str){});'
						+ '}'
						+ '});<\/script>'));
			}
		},
		function(){
			if($(this).find('input[class=id-user]').val() == '<?php echo $this->session->userdata('jms_userid');?>')
			{
				$(this).find("div:last").remove();
				$(this).find("script").remove();
			}
		});
});

</script>
<?php 
foreach ($comments as $com)
{
?>
	<div class="comment-user">
		<div style="font-size: 12pt;"><?php echo $com['comment']?></div>
		<div style="width: 20px;">
		<?php 
		$i=0;
		foreach($detail_comments as $dc)
		{
			foreach($dc as $d)
			{
				if(isset($d['comment_jo_detail']))
				{
					
					if($d['comment_jo_detail'] == $com['id_comment_jo_detail'])
					{
					?>
					<div style="width: 50px; margin-top: 5px;margin-bottom: 5px; float: left;">
						<a class="fancybox" href="<?php echo base_url('/images/upload/' . $d['filepath']);?>"><img style="-webkit-filter: drop-shadow(2px 2px 2px #222); filter: drop-shoadow(2px 2px 2px #222)" src="<?php echo base_url('/images/upload/' . $d['filepath']);?>" alt="" width="50"/></a>
					</div>
					<?php 
					}
				}
			}
			$i++;
		}?>
		</div>
		<div style="font-size: 8pt;color: gray;margin-top:2px;clear: left;">by: <?php echo $com['full_name'];?> at <?php echo date('d-m-Y H:i:s', strtotime($com['time']));?></div>
		<input class="id-comment" type="hidden" value="<?php echo $com['id_comment_jo_detail'];?>" />
		<input class="id-user" type="hidden" value="<?php echo $com['user'];?>" />
	</div>
	<div class="line"></div>
<?php 
}
?>
