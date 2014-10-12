<script>
$(document).ready(function(){
    $( "#dialog-form-apply-filter" ).dialog({
     autoOpen: false,
     height: "auto",
     width: 500,
     modal: true,
     buttons: {
       "Apply Filter": function() {
            var data_post = null
            if($("#filter-table").find("tbody").find('tr').length >= 1 && $("#filter-table").find("tbody").find('tr').find('td:eq(0)').html() != "No Filter Assigned")
            {
                data_post = {};
                $("#filter-table tbody tr").each(function(i){
                    data_post[i] = {};
                    data_post[i]['relation'] = $(this).find('td:eq(0)').html();              
                    data_post[i]['field'] = $(this).find('td:eq(1) input[type=hidden]').val();
                    data_post[i]['condition'] = $(this).find('td:eq(2)').html();
                    data_post[i]['value'] = $(this).find('td:eq(3)').html();
                });
            }
            $("#loading-div").css('display', 'block');
            <?php if($this->session->userdata('page') == 'dahsboard/index')
            {?>                                                
                $("#grid-wrapper").load('<?php echo base_url('/jo/filter_jo');?>', data_post, function(str){
                    $("#loading-div").css('display', 'none');                               
         		});
            <?php
            }
            else
            {                                    
            ?>
                 $.ajax({
		    	 url: "<?php echo site_url('jo/filter_jo');?>",
					type: "POST",
					data: data_post,
					success: function(output){
							window.location = "<?php echo base_url() . $this->session->userdata('page') ?>";
						}	
			     });                                    
            <?php
            }
            ?>                                                                                                                    
          	$( this ).dialog( "close" );
       },
       Cancel: function() {
         $( this ).dialog( "close" );
       }
     },
     close: function() {
            var init_table = "<tr style='text-align: center;'><td colspan='5'>No Filter Assigned</td></tr>"
            $("#filter-table").find("tbody").html(init_table);
            clear_field();
       }
   });
   
   $("#clear-filter").click(function(){
        //alert($("#filter-table").find("tbody").find('tr').length);
        var init_table = "<tr style='text-align: center;'><td colspan='5'>No Filter Assigned</td></tr>"
        $("#filter-table").find("tbody").html(init_table);
   });
   
   $("#add-filter").click(function(){
        var relation = "<td>"+ $("#filter-relation").val() +"</td>";
        //alert($("#filter-table").find("tbody").find('tr').html());
        if($("#filter-table").find("tbody").find('tr').length == 1 && $("#filter-table").find("tbody").find('tr').find('td').html() == "No Filter Assigned")
        {
            $("#filter-table").find("tbody").html("");
            var relation = "<td></td>";
        }
        var field = "<td>"+ $("#filter-column option:selected").text() +"<input type='hidden' value='"+ $("#filter-column").val() +"'</td>";
        var condition = "<td>"+ $("#filter-condition").val() +"</td>";
        var value = "<td>"+ $("#filter-value").val() +"</td>";
        //alert($("#filter-column").val());
        if($("#filter-column").val() == 'po_date' || $("#filter-column").val() == 'delivery_date')
        {
            value = "<td>" + $("#filter-date").val() + "</td>";
        }
        var action = "<td><a href='#' class='delete-filter-row'>x</a></td>";
        $("#filter-table").find("tbody").append("<tr>"+ relation + field + condition + value + action +"</tr>");
        init_delete_click();
        clear_field();
   });
   
   function clear_field()
   {
        $("#filter-column").val("jo_no");
        $("#filter-date").css("display", "none");
        $("#filter-value").css("display", "block");
        $("#filter-value").val("");
        $("#filter-date").val("");
   }
   
   $("#filter-date").datepicker({dateFormat:'dd/mm/yy', changeMonth: true,
      changeYear: true});
   $("#filter-column").change(function(){
        if($(this).val() == 'po_date' || $(this).val() == 'delivery_date')
        {
            $("#filter-date").css("display", "block");
            $("#filter-value").css("display", "none");
        }
        else
        {
            $("#filter-date").css("display", "none");
            $("#filter-value").css("display", "block");
        }
   });
   
   function init_delete_click()
   {
       $('.delete-filter-row').click(function()
       {
            $(this).closest('tr').remove();
            if($("#filter-table").find("tbody").find('tr').length >= 1 && $("#filter-table").find("tbody").find('tr').find('td:eq(0)').html() != "No Filter Assigned")
            {
                $("#filter-table").find("tbody tr:eq(0)").find('td:eq(0)').html("")
            }
            else
            {
                var init_table = "<tr style='text-align: center;'><td colspan='5'>No Filter Assigned</td></tr>"
                $("#filter-table").find("tbody").html(init_table);
            }
       });
   }
});
</script>
<div id="dialog-form-apply-filter" title="Insert New JO" style="z-index: 500;">
 <p class="validateTips">All form fields are required.</p>

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
 .filter-field
 {
    float: left;
 }
 .filter-head
 {
    height: 20px;
    padding-top: 5px;
    text-align: center;
 }
 </style>
     <div>
        <div class="filter-field">
            <select id="filter-column">
                <option value="" disabled>Select Field</option>
                <option value="jo_no">JO No</option>
                <option value="customer.name">Customer</option>
                <option value="type">Type</option>
                <option value="po_date">PO Date</option>
                <option value="delivery_date">Delivery Date</option>
                <option value="pekan">Pekan</option>
                <option value="po_no">PO No</option>
                <option value="nilai_rp">Nilai (Rp.)</option>
                <option value="nilai_us">Nilai (US$)</option>
            </select>
        </div>
        <div class="filter-field">
            <select id="filter-condition">
                <option value="=">=</option>
                <option value=">">></option>
                <option value="<"><</option>
                <option value=">=">>=</option>
                <option value="<="><=</option>
            </select>
        </div>
        <div class="filter-field">
            <input id="filter-value" type="text" value="" placeholder="values"/>
        </div>
        <div class="filter-field">
            <input id="filter-date" type="text" value="" placeholder="values"  style="display: none;"/>
        </div>
        <div class="filter-field">
            <select id="filter-relation">
                <option value="OR">OR</option>
                <option value="AND">AND</option>
            </select>
        </div>
        <div class="filter-field">
            <button type="button" id="add-filter" style="float:left;padding-left: 6px; padding-right: 6px;margin-left:5px;margin-top:2px; height: 20px;" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only ui-state-hover"><span class="ui-button=text">+</span></button>
            <button type="button" id="clear-filter" style="float:left;padding-left: 6px; padding-right: 6px;margin-left:5px;margin-top:2px; height: 20px;" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only ui-state-hover"><span class="ui-button=text">Clear</span></button>
        </div>
     </div>
     <div style="margin-top: 40px; clear:both;">
        <table id="filter-table" class="tinytable">
            <thead>
                <tr>
                    <th class="filter-head">
                        
                    </th>
                    <th class="filter-head">
                        Field
                    </th>
                    <th class="filter-head">
                        Condition
                    </th>
                    <th class="filter-head">
                        Value
                    </th>
                    <th class="filter-head">
                        Action
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="5" style="text-align: center;">No Filter Assigned</td>
                </tr>
            </tbody>
        </table>
     </div>
</div>