<h2>
<?php  
if(isset($subtitle))
{
	echo $subtitle;
}
?>
</h2>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('/css/tsc_datagrid.css');?>" />
<div style="margin-top: 10px;margin-bottom: 10px; float: left; width: 100%;">
    <button type="button" id="apply-filter" style="float:left;padding: 6px; margin-left:5px;margin-top:5px; padding-left: 10px; padding-right: 10px;" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only ui-state-hover"><span class="ui-button=text"><?php echo $this->session->userdata('is_filtered') == true ? 'Filter : On' : 'Filter' ?></span></button>
</div>
<?php $this->load->view('filter/filter_dialog'); ?>
<link rel="stylesheet" href="<?php echo base_url('/jqwidgets/styles/jqx.base.css');?>" type="text/css" />
<script type="text/javascript" src="<?php echo base_url('/jqwidgets/jqxcore.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('/jqwidgets/jqxchart.js');?>"></script>	
<script type="text/javascript" src="<?php echo base_url('/jqwidgets/jqxdata.js');?>"></script>	
<script type="text/javascript">
	$(document).ready(function () {
		// prepare the data
		var source;
		var dataAdapter;
		var settings;
		<?php
		$i=0; 
		foreach($divisions as $div)
		{
		?> 
		source =
		{
			 datatype: "json",
			 datafields: [
				 { name: 'status'},
				 { name: 'value'}
			],
			url: '<?php echo base_url('report/get_data_pie/' . $div['id_division']);?>',
			cache: false
		};		
		
	   dataAdapter = new $.jqx.dataAdapter(source,
		{
			autoBind: true,
			async: false,
			downloadComplete: function () { },
			loadComplete: function () { },
			loadError: function () { }
		});
	   $.jqx._jqxChart.prototype.colorSchemes.push({ name: 'myScheme', colors: ['#00B82E', '#ff0000', '#2419B2', '#FFCC00', '#aaaaaa'] });
	 // prepare jqxChart settings
		settings = {
			title:  "<?php echo $div['name']; ?>",
			description: "",
			enableAnimations: true,
			showLegend: true,
			legendLayout: {left: 400, top: 140, width: 300, height: 200, flow: 'vertical'},
			padding: { left: 5, top: 5, right: 5, bottom: 5 },
			titlePadding: { left: 0, top: 0, right: 0, bottom: 10 },
			source: dataAdapter,
			colorScheme: 'myScheme',
			seriesGroups:
				[
					{
						type: 'pie',
						showLabels: true,
						series: [
                        { dataField: 'value', displayText: 'status', labelRadius: 120, initialAngle: 15, radius: 95, centerOffset: 0, formatSettings: {sufix: ' JO', decimalPlaces: 0} }
						  ]
					}
				]
		};

		// setup the chart
		$('#jqxChart_<?php echo $div['name'];?>').jqxChart(settings);
		<?php 
		$i++;
		}
		?>
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
<div style="margin-bottom: 10px; width: 100%; height: auto;">
	<?php 
	foreach($divisions as $div)
	{
	?>
 		<div style="width:500px; height:300px; float: left; margin-right: 10px; margin-bottom: 10px;" id="jqxChart_<?php echo $div['name'];?>"></div>
 	<?php 
	}
 	?>
 </div>
 <input type="hidden" value='<?php echo json_encode($this->session->userdata('filter')); ?>' id="filter-session-value" />