<label style="float: left;">Currency</label><input id="currency" type="text" style="float:left;" value="<?php echo $currency;?>"><button type="button" id="update-currency" style="padding: 5px; margin-left:5px;margin-top:0px;float:left;" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only ui-state-hover"><span class="ui-button=text">Update</span></button>
<div style="float:left;margin-left: 20px;padding-top: 2px;font-size: 12pt;">Total JO : Rp.<?php echo number_format($total_jo);?></div>
<div id='jqxChart_total' style="clear: both; width:980px; height:400px; left: 0px; top: 0px;">
</div>
<script>
$(document).ready(function(){

	var source =
    {
        datatype: "json",
        datafields: [
            { name: 'month' },
            { name: 'nilai' }
        ],
        url: '<?php echo base_url('report/get_nilai_total/' . $currency . '/' . $year);?>'
    };

    var dataAdapter = new $.jqx.dataAdapter(source, { async: false, autoBind: true, loadError: function (xhr, status, error) { alert('Error loading "' + source.url + '" : ' + error);} });

    // prepare jqxChart settings
    var settings = {
        title: "Nilai Total JO Rp / Bulan",
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
        colorScheme: 'scheme03',
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
    $('#jqxChart_total').jqxChart(settings);
    $("#update-currency").click(function(){
 		var post = {};
 		post['currency'] = $("#currency").val();
        post['year'] = <?php echo $year; ?>;
	 	$("#total-wrapper").load('<?php echo base_url('/report/load_total_ajax/' . $year)?>',post,function(str){
			
		});
 });
});
</script>