<style>
.accordion td:hover
{
	background: #c6d5e1;
    
}
</style>
<script>
$(document).ready(function() {
	$("#table-jo-detail").css('opacity', 0).animate({opacity:1}, 800);
	$(".datetime-input").datepicker({dateFormat:"dd/mm/yy", autoOpen : false, changeMonth : true, changeYear : true});

	$(".comment").click(function(){
		$("#comment-box").dialog("open");
		var post = {};
		post['id_detail_jo_activity'] = $(this).next().val();
		$("#detail-jo-activity-comment").val($(this).next().val());
		$("#comment-wrapper").load('<?php echo base_url('/jo/load_comment')?>',post,function(str){
				
			});
    	});

	var options = {
		target: "#comment-wrapper",
		success: showResponse 
	};
	
	$("#upload-comment").submit(function(e){
    		e.preventDefault();
		$(this).ajaxSubmit(options);
    	});
	
	  function showResponse()
	  {
	      $("#comment-text").val('');
	      $("#file-upload-wrapper").html("");
	  }


	 $("#table-jo-detail tfoot tr:not(.accordion)").hide();
	 $("#table-jo-detail tfoot tr:first-child").show();
	 $("#table-jo-detail tfoot tr.accordion").click(function(){
	 $(this).nextAll("tr").fadeToggle();
	    });

	 $(".clear-actual").click(function(){
		$(this).prev().val("");
		
	});
	
});
</script>

<input type="hidden" id="division-val" value="<?php echo $current_division?>" >
<table id="table-jo-detail" class="tinytable" style="margin-top:10px;margin-bottom:10px;float:left;">
		<thead>
			<tr style="height: 20px;">
				<th style="padding: 5px;">
					Activities
				</th>
				<th style="padding: 5px;">
					Plan
				</th>
				<th style="padding: 5px;">
					Actual
				</th>
				<th style="padding: 5px;">
					Status
				</th>
				<th style="padding: 5px;">
					Comment
				</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$input_status = '';
			$input_status_admin = '';
			if($myclass->authorize_admin_user() || $myclass->authorize_division_user('4'))
			{
				$input_status_admin = 'datetime-input';
				$input_status = 'datetime-input';
			}
				
            $latest_seq = $myclass->get_latest_seq($division_activity[0]['jo_no']);
            
            if(count($latest_seq) > 0)
            {
                $next_seq = $myclass->get_next_seq($division_activity[0]['jo_no'], $latest_seq[0]['seq']);
            }
            
			foreach($division_activity as $divact)
			{
				$plan = (empty($divact['plan'])) ? '' : date('d/m/Y', strtotime($divact['plan']));
				$actual = (empty($divact['actual'])) ? '' : date('d/m/Y', strtotime($divact['actual']));
                
                $check_seq = false;
                
                if(count($latest_seq) > 0)
                {
                    if($divact['seq'] <= $latest_seq[0]['seq'])
                    {
                        $check_seq = true;
                    }
                    
                    if(count($next_seq) && $divact['seq'] == $next_seq[0]['seq'])
                    {
                        $check_seq = true;
                    }
                }
                else
                {
                    if($divact['seq'] == $myclass->get_init_seq())
                    {
                        $check_seq = true;
                    }
                }
            
                
                
                
                if(($myclass->authorize_division_user($current_division) && $check_seq) || $myclass->authorize_admin_user())
                {
		              $input_status = 'datetime-input';
                }
                else
                {
                    $input_status = '';
                }
			?>
			<tr>
				<td style="width: 90px;">
				<?php echo $divact['name'];?>
				</td>
                <?php
                if($divact['pb_default_complete'] == true && $divact['jo_type'] == 'pb')
                {?>
                    <td colspan=2>PB - Complete by Default</td> 
                    <td style="text-align: center; width: 10px;">
					   <img src="<?php echo base_url('/images/1.png');?>">
				    </td>
                <?php    
                }
                else
                {
                ?>
				<td>
					<input style="float:left;" type="text" <?php echo 'class="' . $input_status_admin . '"';?> value="<?php echo $plan;?>" readonly/>
				</td>
				<td>
					<input style="float:left;" type="text" <?php echo 'class="' . $input_status . '"';?> value="<?php echo $actual;?>" readonly/>
					<button type="button" style="float:right;padding-left: 2px;padding-right: 2px;padding-top:2px;padding-bottom:2px;" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only ui-state-hover clear-actual"><span class="ui-button=text">P</span></button>
				</td>
				<td style="text-align: center; width: 10px;">
					<img src="<?php echo base_url('/images/' . $myclass->get_status_activity($divact['plan'], $divact['actual']) . '.png');?>">
				</td>
                <?php
                }
                ?>
				<td style="width: 150px;">
					<?php 
					$comments = $jo_model->get_comment_detail($divact['id_detail_jo_activity']);
					if(count($comments) > 0)
					{
						echo substr($comments[0]['comment'], 0, 15) . '...';
					}
					?>
					<a href="#" class="comment" style="float: right; text-decoration: underline; color: orange;"><b>
					<?php 
					if(count($comments) > 0)
					{
						echo '('. count($comments) .')';
					}
					else
					{
						echo '(add)';
					}
					?>
					</b></a><input type="hidden" value="<?php echo $divact['id_detail_jo_activity'];?>" >
				</td>
				<input type="hidden" value="<?php echo $divact['id_detail_jo_activity']; ?>" />
			</tr>
			<?php
			}
			?>
		</tbody>
		<tfoot>
			<tr class="accordion">
				<td colspan="5" style="text-align: center; vertical-align: middle;">
					Product Detail
				</td>
			</tr>
			<tr>
				<td colspan="5">
					<div style="max-height: 200px; overflow-x: hidden; overflow-y: auto;">
					<table style="width:100%;">
						<tr>
							<th style="padding: 5px;">
								Product Id
							</th>
							<th style="padding: 5px;">
								Product Name
							</th>
							<th style="padding: 5px;">
								Quantity
							</th>
						</tr>
						<?php 
						foreach($jo_product as $pro)
						{?>
							<tbody>
								<tr>
									<td>
										<?php echo $pro['product'];?>
									</td>
									<td>
										<?php echo $pro['name'];?>
									</td>
									<td>
										<?php echo $pro['quantity'];?>
									</td>
								</tr>
							</tbody>
						<?php 
						}
						?>
					</table>
					</div>
				</td>
			</tr>
		</tfoot>
	</table>
	
	
	<?php 
	$this->load->view('dashboard/part/comment_activity');
	?>