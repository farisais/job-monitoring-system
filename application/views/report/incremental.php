<h2>
<?php  
if(isset($subtitle))
{
	echo $subtitle;
}
?>
</h2>
<link rel="stylesheet" href="<?php echo base_url('/jqwidgets/styles/jqx.base.css');?>" type="text/css" />
<script type="text/javascript" src="<?php echo base_url('/jqwidgets/jqxcore.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('/jqwidgets/jqxchart.js');?>"></script>	
<script type="text/javascript" src="<?php echo base_url('/jqwidgets/jqxdata.js');?>"></script>	
<script type="text/javascript">
	$(document).ready(function () {
	   
		// prepare the data
		 var source =
         {
             datatype: "json",
             datafields: [
                 { name: 'time'},
                 { name: 'qty' }
             ],
             url: '<?php echo base_url('report/get_incremental_jo_data/' . $this->uri->segment(3) . '/' . $this->uri->segment(4));?>'
         };

         var dataAdapter = new $.jqx.dataAdapter(source, { async: false, autoBind: true, loadError: function (xhr, status, error) { alert('Error loading "' + source.url + '" : ' + error);} });

         // prepare jqxChart settings
         var settings = {
             title: "JO History per " + $("#time-domain-type").val() + " " + $("#chart-year").val(),
             description: "",
             showLegend: true,
             enableAnimations: true,
             padding: { left: 5, top: 5, right: 5, bottom: 5 },
             titlePadding: { left: 0, top: 0, right: 0, bottom: 10 },
             source: dataAdapter,
             categoryAxis:
                 {
                     dataField: 'time',
                     showGridLines: false
                 },
             colorScheme: 'scheme01',
             seriesGroups:
                 [
                     {
                         type: 'column',
                         columnsGapPercent: 50,
                         valueAxis:
                         {
                             displayValueAxis: true,
                             description: ''
                         },
                         series: [
                                 { dataField: 'qty', displayText: 'JO Quantity'}
                             ]
                     }

                 ]
         };
         
         $('#incremental_chart').jqxChart(settings);
         $("#apply-chart-incremental").click(function(){
            window.location = '<?php echo base_url() . 'report/incremental/';?>' + $("#select-type").val() + '/' + $("#select-year").val()
         });
	});
</script>
<input type="hidden" id="time-domain-type" value="<?php echo $this->uri->segment(3);?>" />
<input type="hidden" id="chart-year" value="<?php echo $this->uri->segment(4);?>" />
<div style="border: thin solid lightgray; width: 300px;padding: 10px">
    <div>
        <label style="float: left;padding-top:5px;">Select Time Domain</label>
        <select id="select-type" style="float: left;">
            <option value="week" <?php echo $this->uri->segment(3) == 'week' ? 'selected':''; ?>>Week</option>
            <option value="month" <?php echo $this->uri->segment(3) == 'month' ? 'selected':''; ?>>Month</option>
        </select>
    </div>
    <div style="clear: both; margin-top: 10px;">
        <label style="float: left;padding-top:5px;">Select Year</label>
        <select id="select-year" style="float: left; margin-left: 45px;">
           <?php
           for($i=0;$i<10;$i++)
           {?>
           <option value="<?php echo date('Y') - $i ?>" <?php echo $this->uri->segment(4) == date('Y') - $i ? 'selected':''; ?>><?php echo date('Y') - $i ?></option>
           <?php
           }
           ?>
        </select>
    </div>
    <div style="clear: both;">
  		<button type="button" id="apply-chart-incremental" style="padding: 6px;" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only ui-state-hover"><span class="ui-button=text">Apply Chart</span></button>
    </div>
</div>
<div style='height: auto; width: 100%; margin-top: 50px;'>
	<div id='incremental_chart' style="width:1000px; height:400px; position: relative; left: 0px; top: 0px; float:left;margin-right: 20px;">
	</div>
</div>