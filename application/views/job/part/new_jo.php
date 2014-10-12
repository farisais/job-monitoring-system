<script>
 $(document).ready(function() {
   var jo_number = $( "#jo-number" ),
     po_number = $( "#po-number" ),
     po_date = $("#po-date"),
     del_date = $("#del-date"),
     customer = $("#customer"),
     pekan = $("#pekan"),
     currency = $("#currency"),
     nilai = $("#nilai-po"),
     allFields = $( [] ).add(jo_number).add(po_number).add(po_date).add(del_date).add(customer).add(pekan).add(currency).add(nilai);
	
   $( "#dialog-form-new-jo" ).dialog({
     autoOpen: false,
     height: "auto",
     width: 400,
     modal: true,
     buttons: {
       "Insert JO": function() {

            var data_post = {};
            data_post['action'] = $("#action-mode").val();
       		data_post['jo_no'] = $("#jo-number").val();
       		data_post['po_no'] = $("#po-number").val();
       		data_post['po_date'] = $("#po-date").val();
       		data_post['del_date'] = $("#del-date").val();
       		data_post['customer'] = $("#customer").val();
            data_post['type'] = $("#type").val();
       		data_post['pekan'] = $("#pekan").val();
       		data_post['currency'] = $("#currency").val();
       		data_post['nilai'] = $("#nilai-po").val();
       		data_post['jumlah_pekan'] = $("#jumlah-pekan").val();
       		data_post['jo_type'] = $("#jo-type").val();
            
       		var table = document.getElementById('table-product');
       		var postData = [];
    		for(var i=1;i<table.rows.length;i++)
    		{
    			var row = [];
    			var inputs = table.rows[i].getElementsByTagName('input');
    			row.push(inputs[0].value);
    			row.push(inputs[1].value);
    			
    			postData.push(row);
    		}
    		data_post['products'] = JSON.stringify(postData);
            if($("#action-mode").val() =='new')
            {
                $("#loading-div").css('display', 'block');
           		$("#grid-wrapper").load('<?php echo base_url('/jo/insert_jo');?>', data_post, function(str){
        			alert('Data JO berhasil di input');
                    $("#loading-div").css('display', 'none');
           		});
           		allFields.val( "" ).removeClass( "ui-state-error" );
            }
            else
            {
                //alert(JSON.stringify(data_post))
                $("#loading-div").css('display', 'block');
                $("#grid-wrapper").load('<?php echo base_url('/jo/update_jo');?>', data_post, function(str){
        			alert('Data JO berhasil di edit');
                    $("#loading-div").css('display', 'none');
           		});
           		allFields.val( "" ).removeClass( "ui-state-error" );
            }
          	$( this ).dialog( "close" );
       },
       Cancel: function() {
         $( this ).dialog( "close" );
		 
       }
     },
     close: function() {
           $("#table-product > tbody").html("");
           $("#action-mode").val('new');
           $("#edit-jo-no").val('');
       }
   });

  $( "#define-product-box" ).dialog({
	      autoOpen: false,
	      height: "auto",
	      width: 400,
	      modal: true,
	      position: "center",
	      open: function(){
				$(this).closest(".ui-dialog").find(".ui-dialog-titlebar:first").hide();
		      }
  	});
  $("#define-product").click(function(){
		$("#define-product-box").dialog("open");	
	});
	
	$("#product-close").click(function(){
			$("#define-product-box").dialog("close");
		});

	$.fn.AddRowProduct = function(product, product_id){
		$(this).find("tbody").append("<tr><td style='vertical-align: middle;'>"+ product +"</td><td><input type='text' value=''/></td><td style='vertical-align: middle;'><a href='#' class='delete-product'>x</a></td><input type='hidden' value='"+ product_id +"' /></tr>");
		$(this).find("tbody").append("<script>$('.delete-product').click(function(){$(this).closest('tr').remove();$(this).parent().parent().find('script').remove();});<\/script>");
		}
	$("#add-product").click(function(){
		var product_name = $("#product option:selected").text();
		var product_id = $("#product").val();
		$("#table-product").AddRowProduct(product_name, product_id);
	});

    Date.prototype.getWeek = function() {
      var onejan = new Date(this.getFullYear(),0,1);
      var today = new Date(this.getFullYear(),this.getMonth(),this.getDate());
      var dayOfYear = ((today - onejan + 86400000)/86400000);
      return Math.ceil(dayOfYear/7)
    };
    
	$("#po-date").change(function(){
		if($("#po-date").val() != '')
		{
            var date = $.datepicker.parseDate('dd/mm/yy', $("#po-date").val());
            
            $("#pekan").val(date.getWeek());
            if($("#del-date").val() != '')
            {
    			var data_post = {};
    			data_post['po_date'] = $("#po-date").val();
    			data_post['delivery_date'] = $("#del-date").val();
    			$.ajax({
    				url: "<?php echo site_url('jo/calculate_weeks');?>",
    				type: "POST",
    				data: data_post,
    				success: function(output){
    						$("#jumlah-pekan").val(output);
    					}
    	       	});
            }
		}
	});
    
	$("#del-date").change(function(){
			if($("#po-date").val() != '' && $("#del-date").val() != '')
			{
				var data_post = {};
				data_post['po_date'] = $("#po-date").val();
				data_post['delivery_date'] = $("#del-date").val();
				$.ajax({
					url: "<?php echo site_url('jo/calculate_weeks');?>",
					type: "POST",
					data: data_post,
					success: function(output){
							$("#jumlah-pekan").val(output);
						}
		       	});
			}
		});
	$(".datetime-input").datepicker({
		dateFormat:"dd/mm/yy", autoOpen : false, changeMonth : true, changeYear : true
	});
	$(".datetime-input").datepicker({
		dateFormat:"dd/mm/yy", autoOpen : false, changeMonth : true, changeYear : true
	});
 });
 </script>  
 <div id="dialog-form-new-jo" title="Insert New JO" style="z-index: 500;">
 <input type="hidden" id="action-mode" value="new" />
 <input type="hidden" id="edit-jo-no" value="" />
 <p class="validateTips">All form fields are required.</p>
 <div id="login-result-jo" style="color: red;"></div>
 <style>
 td.firstcol
 {
 	vertical-align: middle;
 	padding-right: 10px;
 }
 .fieldset tr td:first-child + td
 {
 	width: 250px;
 }
 </style>
 <?php 
 $this->load->view('job/part/define_product');
 ?>
 <table class="fieldset">
 	<tr>
 		<td class="firstcol">
 			JO Number
 		</td>
 		<td>
 			<input type="text" name="jo-number" id="jo-number" class="text ui-widget-content ui-corner-all" />
 		</td>
 	</tr>
 	<tr>
 		<td class="firstcol">
 			Customer
 		</td>
 		<td>
 			<select id="customer" class="ui-widget-content ui-corner-all">
 				<?php 
 				foreach($customers as $cust)
 				{?>
 				<option value="<?php echo $cust['id_customer'];?>"><?php echo $cust['name'];?></option>
 				<?php 
 				}?>
 			</select>
  		</td>
 	</tr>
    <tr>
 		<td class="firstcol">
 			Type
 		</td>
 		<td>
 			<select id="type" class="ui-widget-content ui-corner-all">
     			<option value="jo">JO</option>
                <option value="pb">PB</option>
 			</select>
  		</td>
 	</tr>
 	<tr>
 		<td class="firstcol">
 			PO Date
 		</td>
 		<td>
 			<input id="po-date" class="datetime-input text ui-widget-content ui-corner-all" type='text' value='' maxlength='10' />
 		</td>
 	</tr>
 	<tr>
 		<td class="firstcol">
 			Delivery Date
 		</td>
 		<td>
 			<input id="del-date" class="datetime-input text ui-widget-content ui-corner-all" type='text' value='' maxlength='10' />
 		</td>
 	</tr>
 	<tr>
 		<td class="firstcol">
 			PO Number
 		</td>
 		<td>
 			<input type="text" name="po-number" id="po-number" value="" class="text ui-widget-content ui-corner-all" />
 		</td>
 	</tr>
 	<tr>
 		<td class="firstcol">
 			Pekan
 		</td>
 		<td>
 			<input type="text" name="pekan" id="pekan" value="" class="text ui-widget-content ui-corner-all" />
 		</td>
 	</tr>
 	<tr>
 		<td class="firstcol">
 			Nilai PO
 		</td>
 		<td>
 			<select id="currency" size="1" style="float:left;" class="ui-widget-content ui-corner-all">
 				<option value="rp">Rp.</option>
 				<option value="us">US$</option>
 			</select>
 			<input style="float:left;" type="text" name="nilai-po" id="nilai-po" value="" class="text ui-widget-content ui-corner-all" />
 		</td>
 	</tr>
 	<tr>
 		<td class="firstcol">
 			JO Type
 		</td>
 		<td>
 			<select id="jo-type" size="1" style="float:left;" class="ui-widget-content ui-corner-all">
 				<option value="standard">Standard</option>
 				<option value="partial">Partial</option>
 			</select>
 			<div>
				<button type="button" id="define-product" style="padding-left: 10px;padding-right: 10px;padding-top:2px;padding-bottom:2px; margin-left:5px;" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only ui-state-hover"><span class="ui-button=text">Define Product</span></button>
			</div>
 		</td>
 	</tr>
 	<tr style="">
 		<td class="firstcol">
 			Jumlah Pekan
 		</td>
 		<td>
 			<input type="text" name="jumlah-pekan" id="jumlah-pekan" value="" class="text ui-widget-content ui-corner-all" />
 		</td>
 	</tr>
 </table>
</div>