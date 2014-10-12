<link rel="stylesheet" type="text/css" href="<?php echo base_url('/css/tsc_datagrid.css');?>" />
<style>
.fixed-dialog
{
	position: fixed;
}
.fixed-comment
{

}
</style>
<script type='text/javascript' src='<?php echo base_url("/js/tsc_datagrid.js");?>'></script>
<script>
jQuery(document).ready(function() {
	$("#tsort-tablewrapper").css('opacity', 0).animate({opacity:1}, 800);
});
</script>
<div id="jo-modal">
	<div id="jo-modal-wrapper">

	</div>
</div>
<div id="delete-jo-modal" title="Delete JO">
	<div style="height: 20px;"></div>
	<table>
		<tr>
			<td>
			Select JO to be deleted : 
			</td>
			<td>
				<select id="jo-to-delete" style="width: 100px;" class="ui-widget-content ui-corner-all">
 				<?php 
 				foreach($jo as $j)
 				{?>
 				<option value="<?php echo $j['jo_no'];?>"><?php echo $j['jo_no'];?></option>
 				<?php 
 				}?>
 			</select>
			</td>
		</tr>
	</table>
</div>

<div id="edit-jo-modal" title="Edit JO">
	<div style="height: 20px;"></div>
	<table>
		<tr>
			<td>
			Select JO to edit : 
			</td>
			<td>
				<select id="jo-to-edit" style="width: 100px;" class="ui-widget-content ui-corner-all">
 				<?php 
 				foreach($jo as $j)
 				{?>
 				<option value="<?php echo $j['jo_no'];?>"><?php echo $j['jo_no'];?></option>
 				<?php 
 				}?>
 			</select>
			</td>
		</tr>
	</table>
</div>

<div id="tsort-tablewrapper" style="width:100%;">
  <div id="tsort-tableheader">
    <div class="tsort-search">
      <select id="tsort-columns" onchange="sorter.search('query')">
      </select>
      <input type="text" id="query" onkeyup="sorter.search('query')" />
    </div>
    <?php 
    if($myclass->authorize_admin_user())
    {
    ?>
    <div style="float:left;">
  		<button type="button" id="add-new-jo" style="padding: 6px; margin-left:5px;margin-top:5px;" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only ui-state-hover"><span class="ui-button=text">+ Add JO</span></button>
  </div>
  <div>
  		<button type="button" id="delete-jo" style="float:left;padding: 6px; margin-left:5px;margin-top:5px;" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only ui-state-hover"><span class="ui-button=text">Delete JO</span></button>
  </div>
  <div>
  		<button type="button" id="edit-jo" style="float:left;padding: 6px; margin-left:5px;margin-top:5px;" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only ui-state-hover"><span class="ui-button=text">Edit JO</span></button>
  </div>
   <div>
  		<button type="button" id="apply-filter" style="float:left;padding: 6px; margin-left:5px;margin-top:5px;" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only ui-state-hover"><span class="ui-button=text"><?php echo $this->session->userdata('is_filtered') == true ? 'Filter : On' : 'Filter' ?></span></button>
  </div>
  <?php 
    }
  ?>
  <div style="float: right; margin-left: 10px;">
  		<button type="button" id="export-excel" style="float:left;padding: 6px; margin-left:5px;margin-top:5px;" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only ui-state-hover"><span class="ui-button=text">Export Excel</span></button>
  </div>
  <script>
  jQuery(document).ready(function () {
	  <?php 
	  if(count($jo) > 0)
	  {?>
	  $('#tsctablesort1').on('click', 'tbody tr', function(){
		  var jo_no = $(this).find('td:first').text();
			  if(jo_no != '' && jo_no != null)
			  {
				  var post = {};
				  post['jo_no'] = jo_no;
					  $("#jo-modal-wrapper").load('<?php echo base_url('/jo/get_detail');?>',post,function(str){
						  $( "#jo-modal" ).dialog( "open" );
						});
			  }
			});  
	     <?php 
		}?>
	     $( "#add-new-jo" ).click(function() {
		     var data_post = {};
		     $.ajax({
		    	 url: "<?php echo site_url('jo/create_jo_no');?>",
					type: "POST",
					data: data_post,
					success: function(output){
							$("#jo-number").val(output);$("#jo-number").val(output);
						}	
			     });
                    //alert($("#action-mode").val());
	               $( "#dialog-form-new-jo" ).dialog( "open" );
	       });
	       
	     $( "#jo-modal" ).dialog({
		      autoOpen: false,
		      height: "auto",
		      width: 650,
		      dialogClass: "fixed-dialog",
		      modal: true
		    });

	     $( "#delete-jo-modal" ).dialog({
		      autoOpen: false,
		      height: "auto",
		      width: 300,
		      dialogClass: "fixed-dialog",
		      modal: true,
		    	 buttons: {
		    	       "Delete JO": function() {
			    	   
		    	    	   if($("#jo-to-delete").val() != '' || $("#jo-to-delete").val() != null)
				    	   {
					    	   if(confirm('Are you sure to delte JO ' + $("#jo-to-delete").val() + '?'))
					    	   {
					    		   var data_post = {};
					    		   data_post['jo_no_delete'] = $("#jo-to-delete").val();
					    		   $.ajax({
				    			    	 url: "<?php echo site_url('jo/delete_jo');?>",
				    						type: "POST",
				    						data: data_post,
				    						success: function(output){
					    						alert('JO ' + $("#jo-to-delete").val() + 'successfully deleted.');
					    						var post = {};
                                                $("#loading-div").css('display', 'block');
				    							$("#grid-wrapper").load('<?php echo base_url('/jo/reload_grid');?>',post,function(str){
												    $("#loading-div").css('display', 'none');
				    							});
				    				     }
				    		   		});
					    	   }
				    	   }
				    	   else
				    	   {
					    	   alert("Insert JO NO to delete.");
				    	   }
		    	       },
		    	       Cancel: function() {
		    	         $( this ).dialog( "close" );
		    	       }
		    	     }
		    });

		$("#export-excel").click(function(){
			window.open('data:application/vnd.ms-excel,' + encodeURIComponent($("#export-table").html()));
			e.preventDefault();
			});	

		$("#delete-jo").click(function(){
			$( "#delete-jo-modal" ).dialog( "open" );
			});  

		$("#edit-jo").click(function(){
			$("#edit-jo-modal").dialog("open"); 
		});   

		 $( "#edit-jo-modal" ).dialog({
		      autoOpen: false,
		      height: "auto",
		      width: 300,
		      dialogClass: "fixed-dialog",
		      modal: true,
		    	 buttons: {
		    	       "Edit JO": function() {
		    	    	   if($("#jo-to-edit").val() != '' || $("#jo-to-edit").val() != null)
				    	   {
				    		   var data_post = {};
				    		   data_post['jo_no'] = $("#jo-to-edit").val();
				    		   $.ajax({
			    			    	url: "<?php echo site_url('jo/edit_jo');?>",
			    					type: "POST",
			    					data: data_post,
			    					success: function(output){
				    					//alert(JSON.stringify(output));
				    					//alert(JSON.parse(output)['jo'][0].jo_no);
				    					//alert(JSON.parse(output)['product'].length);
				    					var data = JSON.parse(output);
				    					$("#jo-number").val(data['jo'][0].jo_no);
				    					$("#jo-number").prop('readonly', true);
				    					$("#customer").val(data['jo'][0].customer);
                                        $("#type").val(data['jo'][0].type);
				    					var po_date = $.datepicker.parseDate("yy-mm-dd", data['jo'][0].po_date);
										$("#po-date").val($.datepicker.formatDate('dd/mm/yy', po_date));
										var delivery_date = $.datepicker.parseDate('yy-mm-dd', data['jo'][0].delivery_date);
                                        
										$("#del-date").val($.datepicker.formatDate('dd/mm/yy', delivery_date));
                                        $("#po-number").val(data['jo'][0].po_no);
                                        
                                        $("#pekan").val(data['jo'][0].pekan);
                                        
                                        if(data['jo'][0].nilai_rp == 0)
                                        {
                                            $("#currency").val('us');
                                            $("#nilai-po").val(data['jo'][0].nilai_us);
                                        }
                                        else
                                        {
                                            $("#currency").val('rp');
                                            $("#nilai-po").val(data['jo'][0].nilai_rp);
                                        }
                                        
                                        if(data['jo'][0].partial_jo == 0)
                                        {
                                            $("#jo-type").val('standard');
                                        }
                                        else
                                        {
                                            $("#jo-type").val('partial');
                                        }
                                        
                                        for(var i=0;i<data['product'].length;i++)
                                        {
                                            $("#table-product").find("tbody").append('<tr><td style="vertical-align: middle;">'+ data['product'][i].product +' : ' + data['product'][i].name + '</td><td style="vertical-align: middle;"><input type="text" value="'+ data['product'][i].quantity +'" /></td><td style="vertical-align: middle;"><a href="#" class="delete-product">x</a></td><input type="hidden" value="'+ data['product'][i].product + '"/></tr>');
                                            $("#table-product").find("tbody").append("<script>$('.delete-product').click(function(){$(this).closest('tr').remove();$(this).parent().parent().find('script').remove();});<\/script>");
                                        }
                                        
                                        $("#action-mode").val('edit');
                                        $("#edit-jo-no").val(data['jo'][0].jo_no);
                                        
				    					$( "#dialog-form-new-jo" ).dialog( "open" );
				    					
			    				    }
			    		   		});
                                $( this ).dialog( "close" );
				    	   }
				    	   else
				    	   {
					    	   alert("Insert JO NO to edit.");
				    	   }
		    	       },
		    	       Cancel: function() {
		    	         $( this ).dialog( "close" );
		    	       }
		    	     }
		    });
            $("#apply-filter").click(function(){
                if($("#filter-session-value").val() != null && $("#filter-session-value").val() != '' && $("#filter-session-value").val() != 'false')
                {
                    var dataFilter = JSON.parse($("#filter-session-value").val());
                    var relation, field, condition, value, action, row;
                    $("#filter-table").find("tbody").html("");
                    for(var i=0;i<dataFilter.length; i++)
                    {
                        //alert('1');
                        relation =  "<td>"+ dataFilter[i]['relation'] +"</td>";
                        //alert(relation);
                        //alert(dataFilter[i]['field']);
                        field = "<td>"+ $("#filter-column option[value='"+ dataFilter[i]['field'] +"']").text() +"<input type='hidden' value='"+ dataFilter[i]['field'] +"'</td>";
                        //alert(field);
                        condition = "<td>"+ dataFilter[i]['condition'] +"</td>";
                        value = "<td>"+ dataFilter[i]['value'] +"</td>";
                        action = "<td><a href='#' class='delete-filter-row'>x</a></td>";
                        row = relation + field + condition + value + action;
                        //alert(row);
                        $("#filter-table").find("tbody").append("<tr>"+ row +"</tr>");
                    }
                }
                else
                {
                    var init_table = "<tr style='text-align: center;'><td colspan='5'>No Filter Assigned</td></tr>"
                    $("#filter-table").find("tbody").html(init_table);
                }
                
                $("#dialog-form-apply-filter").dialog("open");
            });
	 });
  
  </script>
    <span class="tsort-details">
    <div>Records <span id="tsort-startrecord"></span>-<span id="tsort-endrecord"></span> of <span id="tsort-totalrecords"></span></div>
    <div><a href="javascript:sorter.reset()">reset</a></div>
    </span>
  </div>
 <div id="export-table">
  <table cellpadding="0" cellspacing="0" border="0" id="tsctablesort1" class="tinytable">
    <thead>
      <tr>
        <th><h3>Jo No</h3></th>
        <th><h3>Customer</h3></th>
        <th><h3>Type</h3></th>
        <th><h3>PO Date</h3></th>
        <th><h3>Delivery Date</h3></th>
        <th><h3>Pekan</h3></th>
        <th><h3>PO No</h3></th>
        <th><h3>Nilai (Rp.)</h3></th>
        <th><h3>Nilai (US$)</h3></th>
        <?php 
        $i=0;
        foreach($divisions as $div)
        {
        	echo '<th><h3>'. $div['abbreviation'] .'</h3></th>';
        	$i++;
        }
        ?>
      </tr>
    </thead>
    <tbody>
    <?php
    if(count($jo) > 0)
    { 
	    foreach($jo as $j)
	    {
	    		$jo_type = 'Standard';
	    		if($j['partial_jo'] == '1')
	    		{
	    			$jo_type = 'Partial';
	    		}
                $pekan = date("W", strtotime($j['po_date']));	
	    		echo '<tr id="'. $j['jo_no'] .'">';?>
			<td><?php echo $j['jo_no']; ?></td>
			<td><?php echo $j['name']; ?></td>
			<td><?php echo strtoupper($j['type'])?></td>
			<td><?php echo $j['po_date']; ?></td>
			<td><?php echo $j['delivery_date']; ?></td>
			<td><?php echo $pekan ?></td>
			<td><?php echo $j['po_no']; ?></td>
			<td><?php echo $j['nilai_rp']; ?></td>
			<td><?php echo $j['nilai_us']; ?></td>
			<?php
			//$division_status = $jo_model->get_division_status($j['jo_no']);
			foreach($divisions as $div)
        		{
	        		echo '<td style="text-align: center;"><span style="visibility: hidden;">'. $myclass->get_status_division($j['jo_no'], $div['id_division']) .'</span><img src="'. base_url('/images/' . $myclass->get_status_division($j['jo_no'], $div['id_division']) . '.png') . '" ></td>';
	        	}
				echo '</tr>';
	    }
    }
    else
    {?>
    		<tr>
    			<td style="text-align: center;" colspan="<?php echo (9 + $i);?>">No Record Found</td>
    		</tr>	
    <?php 	
    }
    ?>
    </tbody>
  </table>
  </div> 
  <div id="tsort-tablefooter">
    <div id="tsort-tablenav">
      <div> <img src="<?php echo base_url('/css/images/first.gif');?>" width="16" height="16" alt="First Page" onclick="sorter.move(-1,true)" /> <img src="<?php echo base_url('/css/images/previous.gif');?>" width="16" height="16" alt="First Page" onclick="sorter.move(-1)" /> <img src="<?php echo base_url('/css/images/next.gif');?>" width="16" height="16" alt="First Page" onclick="sorter.move(1)" /> <img src="<?php echo base_url('/css/images/last.gif');?>" width="16" height="16" alt="Last Page" onclick="sorter.move(1,true)" /> </div>
      <div>
        <select id="tsort-pagedropdown">
        </select>
      </div>
      <div> <a href="javascript:sorter.showall()">view all</a> </div>
    </div>
    <div id="tsort-tablelocation">
      <div>
        <select onchange="sorter.size(this.value)">
          <option value="5">5</option>
          <option value="10" selected="selected">10</option>
          <option value="20">20</option>
          <option value="50">50</option>
          <option value="100">100</option>
        </select>
        <span>Entries Per Page</span> </div>
      <div class="tsort-page">Page <span id="tsort-currentpage"></span> of <span id="tsort-totalpages"></span></div>
    </div>
  </div>
  <?php 
  if(count($jo) > 0)
  {?>
  <script type="text/javascript">
    var sorter = new TINY.table.sorter('sorter','tsctablesort1',{
        headclass:'head',
        ascclass:'asc',
        descclass:'desc',
        evenclass:'tsort-evenrow',
        oddclass:'tsort-oddrow',
        evenselclass:'tsort-evenselected',
        oddselclass:'tsort-oddselected',
        paginate:true, // pagination (true,false)
        size:10, // show 10 results per page
        colddid:'tsort-columns',
        currentid:'tsort-currentpage',
        totalid:'tsort-totalpages',
        startingrecid:'tsort-startrecord',
        endingrecid:'tsort-endrecord',
        totalrecid:'tsort-totalrecords',
        hoverid:'tsort-selectedrow',
        pageddid:'tsort-pagedropdown',
        navid:'tsort-tablenav',
        sortcolumn:0, // sort column 1
        columns:[{index:6, format:'%', decimals:1},{index:7, format:'$', decimals:0}], // classify for proper sorting
        init:true // activate datagrid (true,false)
    });
  </script>
  <?php 
  }?>
</div>
<!-- DC DataGrid Settings -->

<!-- DC DataGrid End -->
<div class="tsc_clear"></div>
<?php 	
if(isset($dialogforms) && $this->session->userdata('is_dialog_set') == false)
{
	foreach($dialogforms as $dialog)
	{
		echo $dialog;
	}
}
?>
<input type="hidden" value='<?php echo json_encode($this->session->userdata('filter')); ?>' id="filter-session-value" />