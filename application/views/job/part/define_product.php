<div id="define-product-box">
	<div style="float:right"><a id="product-close" href="#" style="font-size: 12px; font-weight: 3pt;"><b>Close</b></a></div>
 	<div><h2 style="font-size: 16pt;text-decoration: underline;"><b>Add JO Products</b></h2></div>
 	<div>
	 	<table>
	 		<tr>
		 		<td class="firstcol">
		 			Customer
		 		</td>
		 		<td>
		 			<select id="product" class="ui-widget-content ui-corner-all" style="width:200px;">
		 				<?php 
		 				foreach($products as $prod)
		 				{?>
		 				<option value="<?php echo $prod['id_product'];?>"><?php echo $prod['id_product'] . ' : ' . $prod['name'];?></option>
		 				<?php 
		 				}?>
		 			</select>
		  		</td>
		  		<td>
					<button type="button" id="add-product" style="padding-left: 10px;padding-right: 10px;padding-top:2px;padding-bottom:2px; margin-left:5px;" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only ui-state-hover"><span class="ui-button=text">Add Product</span></button>
		  		</td>
	 	</table>
 	</div>
 	<div style="margin-top:10px;margin-bottom: 20px;">
 		<table class="tinytable" id="table-product">
 				<thead>
 					<tr style="height: 20px;">
 						<th style="padding: 5px;">
 							Products
 						</th>
 						<th style="padding: 5px;">
 							Quantity
 						</th>
						<th style="padding: 5px;">
							
						</th>
 					</tr>
 				</thead>
				<tbody>
				</tbody>
 			</table>
 	</div>
</div>
