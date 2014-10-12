<h2>
<?php  
if(isset($subtitle))
{
	echo $subtitle;
}
?>
</h2>
<div style="border: thin solid lightgray; width: 300px;padding: 10px">
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
                 { name: 'month' },
                 { name: 'nilai' }
             ],
             url: '<?php echo base_url('report/get_nilai_rp/'. $this->uri->segment(4));?>'
         };

         var dataAdapter = new $.jqx.dataAdapter(source, { async: false, autoBind: true, loadError: function (xhr, status, error) { alert('Error loading "' + source.url + '" : ' + error);} });

         // prepare jqxChart settings
         var settings = {
             title: "Nilai JO Rp / Bulan",
             description: "",
             showLegend: true,
             enableAnimations: true,
             padding: { left: 5, top: 5, right: 5, bottom: 5 },
             titlePadding: { left: 0, top: 0, right: 0, bottom: 10 },
             source: dataAdapter,
             categoryAxis:
                 {
                     dataField: 'month',
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
                             unitInterval: 1000,
                             displayValueAxis: true,
                             description: 'Nilai Rp (1000000)'
                         },
                         series: [
                                 { dataField: 'nilai', displayText: 'Nilai Rp'}
                             ]
                     }

                 ]
         };

         // setup the chart
         $('#jqxChart_rp').jqxChart(settings);
         
         source =
         {
             datatype: "json",
             datafields: [
                 { name: 'month' },
                 { name: 'nilai' }
             ],
             url: '<?php echo base_url('report/get_nilai_us/' . $this->uri->segment(4));?>'
         };

         dataAdapter = new $.jqx.dataAdapter(source, { async: false, autoBind: true, loadError: function (xhr, status, error) { alert('Error loading "' + source.url + '" : ' + error);} });

         // prepare jqxChart settings
         settings = {
             title: "Nilai JO USD / Bulan",
             description: "",
             showLegend: true,
             enableAnimations: true,
             padding: { left: 5, top: 5, right: 5, bottom: 5 },
             titlePadding: { left: 0, top: 0, right: 0, bottom: 10 },
             source: dataAdapter,
             categoryAxis:
                 {
                     dataField: 'month',
                     showGridLines: false
                 },
             colorScheme: 'scheme02',
             seriesGroups:
                 [
                     {
                         type: 'column',
                         columnsGapPercent: 50,
                         valueAxis:
                         {
                             unitInterval: 100,
                             displayValueAxis: true,
                             description: 'Nilai USD (1000)'
                         },
                         series: [
                                 { dataField: 'nilai', displayText: 'Nilai USD'}
                             ]
                     }

                 ]
         };

         // setup the chart
         $('#jqxChart_us').jqxChart(settings);
         $("#apply-chart-incremental").click(function(){
            
            window.location = '<?php echo base_url() . 'report/summary/'.$currency ;?>' + '/' + $("#select-year").val();
         });
         
	});
</script>
<div style='height: auto; width: 100%; margin-top: 20px;'>
	<div id='jqxChart_rp' style="width:550px; height:400px; position: relative; left: 0px; top: 0px; float:left;margin-right: 20px;">
	</div>
	<div id='jqxChart_us' style="width:550px; height:400px; position: relative; left: 0px; top: 0px; float:left;">
	</div>
</div>

<div style='height: auto; width: 100%;clear: both; padding-top: 20px;'>
	<div id="total-wrapper">
	<?php
	$data = array();
	$data['currency'] = $currency;
    $data['year'] = $this->uri->segment(4);
	$this->load->view('report/total_wrapper', $data);
	?>
	</div>
</div>