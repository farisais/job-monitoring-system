<?php 
if($myclass->authorize_admin_user() || $myclass->authorize_division_user($current_division) || $myclass->authorize_division_user('4'))
{
?>
	<button type="button" id="update-jo" style="padding-left: 10px;padding-right: 10px;padding-top:5px;padding-bottom:5px; margin-left:5px;margin-top:5px;" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only ui-state-hover"><span class="ui-button=text">Update</span></button>
	<script>
	$("#update-jo").click(function(){
		var post = {};
		var postData = [];
		var table = document.getElementById('table-jo-detail');
		for(var i=1;i<(table.rows.length - 2);i++)
		{
			var row = [];
			var inputs = table.rows[i].getElementsByTagName('input');
			row.push(inputs[0].value);
			row.push(inputs[1].value);
			row.push(inputs[2].value);
			postData.push(row);
		}
		post['input'] = JSON.stringify(postData);
		var id = $( this ).find( 'input:hidden:eq(0)' ).val();
		post['division'] = $("#division-val").val();
		post['jo_no'] = $("#current-jo").val();
		$("#div-detail-wrapper").load('<?php echo base_url('/jo/update_jo_detail');?>',post,function(str){
			alert('Data berhasil diupdate');
			$("#grid-wrapper").load('<?php echo base_url('/jo/reload_grid');?>',post,function(str){

			});
		});
	});
	</script>	
<?php 
}
?>