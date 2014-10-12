
<link rel="stylesheet" href="<?php echo base_url('/jqwidgets/styles/jqx.base.css');?>" type="text/css" />
<script type="text/javascript" src="<?php echo base_url('/jqwidgets/jqxcore.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('/jqwidgets/jqxbuttons.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('/jqwidgets/jqxscrollbar.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('/jqwidgets/jqxmenu.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('/jqwidgets/jqxinput.js');?>"></script>
<script type="text/javascript">
            $(document).ready(function () {
                // Create jqxButton widgets.
                $("#jqxButton").jqxButton({ width: '150', theme: 'base' });
                $("#jqxSubmitButton").jqxButton({ width: '150', theme: 'base' });
                $("#jqxDisabledButton").jqxButton({ disabled: true, width: '150', theme: 'base' });
                // Subscribe to Click events.
                $("#jqxButton").on('click', function () {
                    var data_post = {};
                    data_post['filter_jo_no'] = $("#input-jo").val();
                    data_post['filter_customer'] = $("#input-cust").val();
                    $("#check-select-all").attr('checked', false);
    				if(data_post['filter_jo_no'] == '' && data_post['filter_customer'] == '')
    				{
    					$("#grid-wrapper").load('<?php echo base_url('/jo/hide_grid');?>',data_post,function(str){
                		});
    				}
    				else
    				{
    				    $("#loading-div").css('display', 'block');
                    	$("#grid-wrapper").load('<?php echo base_url('/jo/reload_grid_specific');?>',data_post,function(str){
                    	   $("#loading-div").css('display', 'none');
                    		});
    				}
                });

                $("#check-select-all").on('click', function(){
					//alert($(this).is(':checked'));
					var data_post = {};
					data_post['grid_load'] = 'all';
					if($(this).is(':checked'))
					{
						$("#input-cust").val('');
						$("#input-jo").val('');
                        $("#loading-div").css('display', 'block');
						$("#grid-wrapper").load('<?php echo base_url('/jo/reload_grid_all');?>',data_post,function(str){
						  $("#loading-div").css('display', 'none');
	                    	});
					}
					else
					{
						$("#grid-wrapper").load('<?php echo base_url('/jo/hide_grid');?>',data_post,function(str){
                    		});
					}
                    });
                $("#jqxSubmitButton").on('click', function () {
                    $("#events").find('span').remove();
                    $("#events").append('<span>Submit Button Clicked</span');
                });
                
                $("#apply-year-filter-grid").click(function(){
                    $("#loading-div").css('display', 'block');
                    var data_post = {};
                    data_post['year'] = $("#select-year").val();
                    $("#grid-wrapper").load('<?php echo base_url('/jo/apply_year');?>',data_post,function(str){
                    	   $("#loading-div").css('display', 'none');
              		});
                });
			 var customers = new Array(
					<?php 
					$i=0;
					foreach($customers as $cust)
					{
						$init = ',"';
						if($i == 0)
						{
							$init = '"';
						}
						echo $init . $cust['name'] . '"';
						$i++;
					}
					?>
					 );
			 
			 var jo_list = new Array(
						<?php 
						$i=0;
						foreach($jo as $j)
						{
							$init = ',"';
							if($i == 0)
							{
								$init = '"';
							}
							echo $init . $j['jo_no'] . '"';
							$i++;
						}
						?>
						 );
                $("#input-cust").jqxInput({placeHolder: "Enter a Customer", height: 25, width: 250, minLength: 1, theme: 'base', source: customers });
                $("#input-jo").jqxInput({placeHolder: "Enter JO Number", height: 25, width: 200, minLength: 1, theme: 'base', source: jo_list });
            });
        </script>
        <script type="text/javascript">
            $(document).ready(function () {
                });
        </script>
        <div style="float: left;">
            <div style="width: 200px">
                <div style="clear: both;">
                    <label style="float: left;padding-top:5px;">Select Year</label>
                    <select id="select-year" style="float: left; margin-left: 20px;">
                        <option value="all">All</option>
                       <?php
                       for($i=0;$i<10;$i++)
                       {?>
                       <option value="<?php echo date('Y') - $i ?>" <?php echo $this->session->userdata('year') == date('Y') - $i ? 'selected':''; ?>><?php echo date('Y') - $i ?></option>
                       <?php
                       }
                       ?>
                    </select>
                </div>
                <div style="">
              		<button type="button" id="apply-year-filter-grid" style="padding: 6px;margin-top:-2px;" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only ui-state-hover"><span class="ui-button=text">Apply</span></button>
                </div>
            </div>
        </div>
         <div style="">
         	<div >
         		<div style="float:left;padding-top:7px;">
         		<label>Customer:</label>
         	</div>
         	<div style="float:left;margin-left:5px;">
	         		<input type="text" id="input-cust" value="<?php echo $this->session->userdata('customer_filter');?>" />
	         	</div>
         	</div>
         	<div>
         		<div style="float:left;margin-left:20px;padding-top:7px;">
         			<label>JO Number:</label>
         		</div>
         		<div style="float:left;margin-left:5px;">
	         		<input type="text" id="input-jo" value="<?php echo $this->session->userdata('jo_filter');?>" />
	         	</div>
         	</div>
         	<div>
         		<div style="float:left;margin-left:5px;">
	         		<input type="checkbox" id="check-select-all" <?php echo ($this->session->userdata('grid_load') == 'all') ? 'checked' : '';?>/>
	         	</div>
         		<div style="float:left;padding-top:7px;">
         			<label>Select All</label>
         		</div>
         	</div>  
         	<div style="margin-left:680px;margin-bottom:20px;">
	         	<input type="button" value="Find" id='jqxButton' />
         	</div>     
         	
         </div>
		 <div class='line'></div>