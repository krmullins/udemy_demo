<?php 
	if(!isset($Translation)){ @header('Location: index.php'); exit; }

	$advanced_search_mode = 0;
	$search_mode_session_key = substr('spm_' . basename(__FILE__), 0, -4);
	if(isset($_REQUEST['advanced_search_mode'])) {
		/* if user explicitly sets search mode by clicking Filter_x from the filters page, 
		 * apply requested mode, and store into session */
		$advanced_search_mode = intval($_REQUEST['advanced_search_mode']) ? 1 : 0;
		$_SESSION[$search_mode_session_key] = $advanced_search_mode;

	} elseif(isset($_SESSION[$search_mode_session_key])) {
		/* otherwise, check if search mode for given table is specified in user's 
		 * session and apply it */
		$advanced_search_mode = intval($_SESSION[$search_mode_session_key]) ? 1 : 0;
	}
?>

	<input type="hidden" name="advanced_search_mode" value="<?php echo $advanced_search_mode; ?>" id="advanced_search_mode">
	<script>
		$j(function(){
			$j('.btn.search_mode').appendTo('.page-header h1');
			$j('.btn.search_mode').click(function(){
				var mode = parseInt($j('#advanced_search_mode').val());
				$j('#advanced_search_mode').val(1 - mode);
				if(typeof(beforeApplyFilters) == 'function') beforeApplyFilters();
				return true;
			});
		})
	</script>

<?php if($advanced_search_mode){ ?>
	<button class="btn btn-lg btn-success pull-right search_mode" id="simple_search_mode" type="submit" name="Filter_x" value="1">Switch to simple search mode</button>
	<script>
		$j(function() {
			$j('#simple_search_mode').click(function() {
				if(!confirm('If you switch to simple search mode, any filters defined here will be lost! Do you still which to proceed?')) return false;
				$j('.clear_filter').click();
			})		
		})
	</script>
	<?php include(dirname(__FILE__) . '/../defaultFilters.php'); ?>
	
<?php }else{ ?>

	<button class="btn btn-lg btn-default pull-right search_mode" type="submit" name="Filter_x" value="1">Switch to advanced search mode</button>
			
			<!-- load bootstrap datetime-picker -->
			<link rel="stylesheet" href="resources/bootstrap-datetimepicker/bootstrap-datetimepicker.css">
			<script type="text/javascript" src="resources/moment/moment-with-locales.min.js"></script>
			<script type="text/javascript" src="resources/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js"></script>
			
			
			<div class="page-header"><h1>
				<a href="orders_view.php" style="text-decoration: none; color: inherit;">
					<img src="resources/table_icons/cash_register.png"> 					Orders Filters</a>
			</h1></div>

		
	<div class="row vspacer-lg" style="border-bottom: dotted 2px #DDD;" >
		
		<div class="hidden-xs hidden-sm col-md-3 vspacer-lg text-right"><label for="">Order ID</label></div>
		<div class="hidden-md hidden-lg col-xs-12 vspacer-lg"><label for="">Order ID</label></div>
		
					
		<div class="col-xs-3 col-md-1 vspacer-lg text-center">Between</div>
		<input type="hidden" name="FilterAnd[1]" value="and">
		<input type="hidden" name="FilterField[1]" value="1">   
		<input type="hidden" name="FilterOperator[1]" value="greater-than-or-equal-to">
		<div class="col-xs-9 col-md-3 col-lg-2 vspacer-md">
			<input type="text" class="numeric form-control" name="FilterValue[1]" value="<?php echo htmlspecialchars($FilterValue[1]); ?>" size="3">
		</div>

				<div class="col-xs-3 col-md-1 text-center vspacer-lg and"> and </div>
		<input type="hidden" name="FilterAnd[2]" value="and">
		<input type="hidden" name="FilterField[2]" value="1">  
		<input type="hidden" name="FilterOperator[2]" value="less-than-or-equal-to">
		<div class="col-xs-9 col-md-3 col-lg-2 vspacer-md">
			<input type="text" class="numeric form-control" name="FilterValue[2]" value="<?php echo htmlspecialchars($FilterValue[2]); ?>" size="3">
		</div>

		
		<div class="col-xs-3 col-xs-offset-9 col-md-offset-0 col-md-1">
			<button type="button" class="btn btn-default vspacer-md btn-block" title='Clear fields' onclick="clearFilters($j(this).parent());" ><span class="glyphicon glyphicon-trash text-danger"></button>
		</div>

			</div>

		
			<!-- ########################################################## -->
					
	<div class="row vspacer-lg" style="border-bottom: dotted 2px #DDD;" >
		
		<div class="hidden-xs hidden-sm col-md-3 vspacer-lg text-right"><label for="">Status</label></div>
		<div class="hidden-md hidden-lg col-xs-12 vspacer-lg"><label for="">Status</label></div>
		
				
		<input type="hidden" name="FilterAnd[4]" value="and">
		<input type="hidden" name="FilterField[4]" value="2">  
		<input type="hidden" name="FilterOperator[4]" value="like">
		<div class="col-md-8 col-lg-6 vspacer-md">
			<input type="text" class="form-control" name="FilterValue[4]" value="<?php echo htmlspecialchars($FilterValue[4]); ?>" size="3">
		</div>
		
		
		<div class="col-xs-3 col-xs-offset-9 col-md-offset-0 col-md-1">
			<button type="button" class="btn btn-default vspacer-md btn-block" title='Clear fields' onclick="clearFilters($j(this).parent());" ><span class="glyphicon glyphicon-trash text-danger"></button>
		</div>

			</div>


		
			<!-- ########################################################## -->
					

	<div class="row vspacer-lg" style="border-bottom: dotted 2px #DDD;">
		
		<div class="hidden-xs hidden-sm col-md-3 vspacer-lg text-right"><label for="">Customer</label></div>
		<div class="hidden-md hidden-lg col-xs-12 vspacer-lg"><label for="">Customer</label></div>
		
		
		<div class="col-md-8 col-lg-6 vspacer-md">
			<div id="filter_3" class="vspacer-lg"><span></span></div>

			<input type="hidden" class="populatedLookupData" name="5" value="<?php echo htmlspecialchars($FilterValue[5]); ?>" >
			<input type="hidden" name="FilterAnd[5]" value="and">
			<input type="hidden" name="FilterField[5]" value="3">  
			<input type="hidden" id="lookupoperator_3" name="FilterOperator[5]" value="equal-to">
			<input type="hidden" id="filterfield_3" name="FilterValue[5]" value="<?php echo htmlspecialchars($FilterValue[5]); ?>" size="3">
		</div>
		
		
		<div class="col-xs-3 col-xs-offset-9 col-md-offset-0 col-md-1">
			<button type="button" class="btn btn-default vspacer-md btn-block" title='Clear fields' onclick="clearFilters($j(this).parent());" ><span class="glyphicon glyphicon-trash text-danger"></button>
		</div>

			</div>

	<script>

		$j(function(){
			/* display a drop-down of categories that populates its content from ajax_combo.php */
			$j("#filter_3").select2({
				ajax: {
					url: "ajax_combo.php",
					dataType: 'json',
					cache: true,
					data: function(term, page){ return { s: term, p:page, t:"orders", f:"CustomerID" }; },
					results: function (resp, page) { return resp; }
				}
			}).on('change', function(e){
				$j("#filterfield_3").val(e.added.text);
				$j("#lookupoperator_3").val('equal-to');
				if (e.added.id=='{empty_value}'){
					$j("#lookupoperator_3").val('is-empty');
				}
			});


			/* preserve the applied category filter and show it when re-opening the filters page */
			if ($j("#filterfield_3").val().length){
				
				//None case 
				if ($j("#filterfield_3").val() == '<None>'){
					$j("#filter_3").select2( 'data' , {
						id: '{empty-value}',
						text: '<None>'
					});
					$j("#lookupoperator_3").val('is-empty');
					return;
				}
				$j.ajax({
					url: 'ajax_combo.php',
					dataType: 'json',
					data: {
						s: $j("#filterfield_3").val(),  //search term
						p: 1,                                         //page number
						t: "orders",                //table name
						f: "CustomerID"               //field name
					}
				}).done(function(response){
					if (response.results.length){
						$j("#filter_3").select2('data' , {
							id: response.results[1].id,
							text: response.results[1].text
						});
					}
				});
			}

		});
	</script>

		
			<!-- ########################################################## -->
					

	<div class="row vspacer-lg" style="border-bottom: dotted 2px #DDD;">
		
		<div class="hidden-xs hidden-sm col-md-3 vspacer-lg text-right"><label for="">Employee</label></div>
		<div class="hidden-md hidden-lg col-xs-12 vspacer-lg"><label for="">Employee</label></div>
		
		
		<div class="col-md-8 col-lg-6 vspacer-md">
			<div id="filter_4" class="vspacer-lg"><span></span></div>

			<input type="hidden" class="populatedLookupData" name="6" value="<?php echo htmlspecialchars($FilterValue[6]); ?>" >
			<input type="hidden" name="FilterAnd[6]" value="and">
			<input type="hidden" name="FilterField[6]" value="4">  
			<input type="hidden" id="lookupoperator_4" name="FilterOperator[6]" value="equal-to">
			<input type="hidden" id="filterfield_4" name="FilterValue[6]" value="<?php echo htmlspecialchars($FilterValue[6]); ?>" size="3">
		</div>
		
		
		<div class="col-xs-3 col-xs-offset-9 col-md-offset-0 col-md-1">
			<button type="button" class="btn btn-default vspacer-md btn-block" title='Clear fields' onclick="clearFilters($j(this).parent());" ><span class="glyphicon glyphicon-trash text-danger"></button>
		</div>

			</div>

	<script>

		$j(function(){
			/* display a drop-down of categories that populates its content from ajax_combo.php */
			$j("#filter_4").select2({
				ajax: {
					url: "ajax_combo.php",
					dataType: 'json',
					cache: true,
					data: function(term, page){ return { s: term, p:page, t:"orders", f:"EmployeeID" }; },
					results: function (resp, page) { return resp; }
				}
			}).on('change', function(e){
				$j("#filterfield_4").val(e.added.text);
				$j("#lookupoperator_4").val('equal-to');
				if (e.added.id=='{empty_value}'){
					$j("#lookupoperator_4").val('is-empty');
				}
			});


			/* preserve the applied category filter and show it when re-opening the filters page */
			if ($j("#filterfield_4").val().length){
				
				//None case 
				if ($j("#filterfield_4").val() == '<None>'){
					$j("#filter_4").select2( 'data' , {
						id: '{empty-value}',
						text: '<None>'
					});
					$j("#lookupoperator_4").val('is-empty');
					return;
				}
				$j.ajax({
					url: 'ajax_combo.php',
					dataType: 'json',
					data: {
						s: $j("#filterfield_4").val(),  //search term
						p: 1,                                         //page number
						t: "orders",                //table name
						f: "EmployeeID"               //field name
					}
				}).done(function(response){
					if (response.results.length){
						$j("#filter_4").select2('data' , {
							id: response.results[1].id,
							text: response.results[1].text
						});
					}
				});
			}

		});
	</script>

		
			<!-- ########################################################## -->
			
	<div class="row vspacer-lg" style="border-bottom: dotted 2px #DDD;" >
		
		<div class="hidden-xs hidden-sm col-md-3 vspacer-lg text-right"><label for="">Order Date</label></div>
		<div class="hidden-md hidden-lg col-xs-12 vspacer-lg"><label for="">Order Date</label></div>
		
		
		<div class="col-xs-3 col-md-1 vspacer-lg text-center">Between</div>
		
		<input type="hidden" name="FilterAnd[7]" value="and">
		<input type="hidden" name="FilterField[7]" value="5">   
		<input type="hidden" name="FilterOperator[7]" value="greater-than-or-equal-to">
		
		<div class="col-xs-9 col-md-3 col-lg-2 vspacer-md">
			<input type="text"  class="form-control" id="from-date_5"  name="FilterValue[7]" value="<?php echo htmlspecialchars($FilterValue[7]); ?>" size="10">
		</div>

				<div class="col-xs-3 col-md-1 text-center vspacer-lg"> and </div>
		
		<input type="hidden" name="FilterAnd[8]" value="and">
		<input type="hidden" name="FilterField[8]" value="5">  
		<input type="hidden" name="FilterOperator[8]" value="less-than-or-equal-to">
		
		<div class="col-xs-9 col-md-3 col-lg-2 vspacer-md">
			<input type="text" class="form-control" id="to-date_5" name="FilterValue[8]" value="<?php echo htmlspecialchars($FilterValue[8]); ?>" size="10">
		</div>
		
		
		<div class="col-xs-3 col-xs-offset-9 col-md-offset-0 col-md-1">
			<button type="button" class="btn btn-default vspacer-md btn-block" title='Clear fields' onclick="clearFilters($j(this).parent());" ><span class="glyphicon glyphicon-trash text-danger"></button>
		</div>

			</div>

				
	<script>
		$j(function(){
			//date
			$j("#from-date_5 , #to-date_5 ").datetimepicker({
				format: 'MM/DD/YYYY'
			});

			$j("#from-date_5" ).on('dp.change' , function(e){
				date = moment(e.date).add(1, 'month');  
				$j("#to-date_5 ").val(date.format('MM/DD/YYYY')).data("DateTimePicker").minDate(e.date);
			});
		});
	</script>

		
			<!-- ########################################################## -->
			
	<div class="row vspacer-lg" style="border-bottom: dotted 2px #DDD;" >
		
		<div class="hidden-xs hidden-sm col-md-3 vspacer-lg text-right"><label for="">Shipped Date</label></div>
		<div class="hidden-md hidden-lg col-xs-12 vspacer-lg"><label for="">Shipped Date</label></div>
		
		
		<div class="col-xs-3 col-md-1 vspacer-lg text-center">Between</div>
		
		<input type="hidden" name="FilterAnd[9]" value="and">
		<input type="hidden" name="FilterField[9]" value="7">   
		<input type="hidden" name="FilterOperator[9]" value="greater-than-or-equal-to">
		
		<div class="col-xs-9 col-md-3 col-lg-2 vspacer-md">
			<input type="text"  class="form-control" id="from-date_7"  name="FilterValue[9]" value="<?php echo htmlspecialchars($FilterValue[9]); ?>" size="10">
		</div>

				<div class="col-xs-3 col-md-1 text-center vspacer-lg"> and </div>
		
		<input type="hidden" name="FilterAnd[10]" value="and">
		<input type="hidden" name="FilterField[10]" value="7">  
		<input type="hidden" name="FilterOperator[10]" value="less-than-or-equal-to">
		
		<div class="col-xs-9 col-md-3 col-lg-2 vspacer-md">
			<input type="text" class="form-control" id="to-date_7" name="FilterValue[10]" value="<?php echo htmlspecialchars($FilterValue[10]); ?>" size="10">
		</div>
		
		
		<div class="col-xs-3 col-xs-offset-9 col-md-offset-0 col-md-1">
			<button type="button" class="btn btn-default vspacer-md btn-block" title='Clear fields' onclick="clearFilters($j(this).parent());" ><span class="glyphicon glyphicon-trash text-danger"></button>
		</div>

			</div>

				
	<script>
		$j(function(){
			//date
			$j("#from-date_7 , #to-date_7 ").datetimepicker({
				format: 'MM/DD/YYYY'
			});

			$j("#from-date_7" ).on('dp.change' , function(e){
				date = moment(e.date).add(1, 'month');  
				$j("#to-date_7 ").val(date.format('MM/DD/YYYY')).data("DateTimePicker").minDate(e.date);
			});
		});
	</script>

		
			<!-- ########################################################## -->
			
	<div class="row vspacer-lg" style="border-bottom: dotted 2px #DDD;" >
		
		<div class="hidden-xs hidden-sm col-md-3 vspacer-lg text-right"><label for="">Required Date</label></div>
		<div class="hidden-md hidden-lg col-xs-12 vspacer-lg"><label for="">Required Date</label></div>
		
		
		<div class="col-xs-3 col-md-1 vspacer-lg text-center">Between</div>
		
		<input type="hidden" name="FilterAnd[11]" value="and">
		<input type="hidden" name="FilterField[11]" value="6">   
		<input type="hidden" name="FilterOperator[11]" value="greater-than-or-equal-to">
		
		<div class="col-xs-9 col-md-3 col-lg-2 vspacer-md">
			<input type="text"  class="form-control" id="from-date_6"  name="FilterValue[11]" value="<?php echo htmlspecialchars($FilterValue[11]); ?>" size="10">
		</div>

				<div class="col-xs-3 col-md-1 text-center vspacer-lg"> and </div>
		
		<input type="hidden" name="FilterAnd[12]" value="and">
		<input type="hidden" name="FilterField[12]" value="6">  
		<input type="hidden" name="FilterOperator[12]" value="less-than-or-equal-to">
		
		<div class="col-xs-9 col-md-3 col-lg-2 vspacer-md">
			<input type="text" class="form-control" id="to-date_6" name="FilterValue[12]" value="<?php echo htmlspecialchars($FilterValue[12]); ?>" size="10">
		</div>
		
		
		<div class="col-xs-3 col-xs-offset-9 col-md-offset-0 col-md-1">
			<button type="button" class="btn btn-default vspacer-md btn-block" title='Clear fields' onclick="clearFilters($j(this).parent());" ><span class="glyphicon glyphicon-trash text-danger"></button>
		</div>

			</div>

				
	<script>
		$j(function(){
			//date
			$j("#from-date_6 , #to-date_6 ").datetimepicker({
				format: 'MM/DD/YYYY'
			});

			$j("#from-date_6" ).on('dp.change' , function(e){
				date = moment(e.date).add(1, 'month');  
				$j("#to-date_6 ").val(date.format('MM/DD/YYYY')).data("DateTimePicker").minDate(e.date);
			});
		});
	</script>

		
			<!-- ########################################################## -->
						<!-- filter actions -->
			<div class="row">
				<div class="col-md-3 col-md-offset-3 col-lg-offset-4 col-lg-2 vspacer-lg">
					<input type="hidden" name="apply_sorting" value="1">
					<button type="submit" id="applyFilters" onclick="beforeApplyFilters(event);return true;" class="btn btn-success btn-block btn-lg"><i class="glyphicon glyphicon-ok"></i> Apply filters</button>
				</div>
									<div class="col-md-3 col-lg-2 vspacer-lg">
						<button type="submit" onclick="beforeApplyFilters(event);return true;" class="btn btn-default btn-block btn-lg" id="SaveFilter" name="SaveFilter_x" value="1"><i class="glyphicon glyphicon-align-left"></i> Save &amp; apply filters</button>
					</div>
								<div class="col-md-3 col-lg-2 vspacer-lg">
					<button onclick="beforeCancelFilters();" type="submit" id="cancelFilters" class="btn btn-warning btn-block btn-lg"><i class="glyphicon glyphicon-remove"></i> Cancel</button>
				</div>
			</div>

			<script>
				$j(function(){
					//stop event if it is already bound
					$j(".numeric").off("keydown").on("keydown", function (e) {
						// Allow: backspace, delete, tab, escape, enter and .
						if ($j.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
							// Allow: Ctrl+A, Command+A
							(e.keyCode == 65 && ( e.ctrlKey === true || e.metaKey === true ) ) || 
							// Allow: home, end, left, right, down, up
							(e.keyCode >= 35 && e.keyCode <= 40)) {
								// let it happen, don't do anything
								return;
						}
						// Ensure that it is a number and stop the keypress
						if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
							e.preventDefault();
						}
					});                
				});
				
				/* function to handle the action of the clear field button */
				function clearFilters(elm){
					var parentDiv = $j(elm).parent(".row ");
					//get all input nodes
					inputValueChildren = parentDiv.find("input[type!=radio][name^=FilterValue]");
					inputRadioClildren = parentDiv.find("input[type=radio][name^=FilterValue]");
					
					//default input nodes ( text, hidden )
					inputValueChildren.each(function( index ) {
						$j( this ).val('');
					});
					
					//radio buttons
					inputRadioClildren.each(function( index ) {
						$j( this ).removeAttr('checked');

						//checkbox case
						if ($j( this ).val()=='') $j(this).attr("checked", "checked").click();
					});
					
					//lookup and select dropdown
					parentDiv.find("div[id$=DropDown],div[id^=filter_]").select2("val", "");

					//for lookup
					parentDiv.find("input[id^=lookupoperator_]").val('equal-to');

				}
				
				function checkboxFilter(elm) {
					if (elm.value == "null") {
						$j("#" + elm.className).val("is-empty");
					} else {
						$j("#" + elm.className).val("equal-to");
					}
				}
				
				/* funtion to remove unsupplied fields */
				function beforeApplyFilters(event){
				
					//get all field submitted values
					$j(":input[type=text][name^=FilterValue],:input[type=hidden][name^=FilterValue],:input[type=radio][name^=FilterValue]:checked").each(function( index ) {
						  
						//if type=hidden  and options radio fields with the same name are checked, supply its value
						if ( $j( this ).attr('type')=='hidden' &&  $j(":input[type=radio][name='"+$j( this ).attr('name')+"']:checked").length >0 ){
							return;
						}
						  
						  //do not submit fields with empty values
						if ( !$j( this ).val()){
						  var fieldNum =  $j(this).attr('name').match(/(\d+)/)[0];
						  $j(":input[name='FilterField["+fieldNum+"]']").val('');
						 
						  };
					});

				}
				
				function beforeCancelFilters(){
					

					//other fields
					$j('form')[0].reset();

					//lookup case ( populate with initial data)
					$j(":input[class='populatedLookupData']").each(function(){
					  

						$j(":input[name='FilterValue["+$j(this).attr('name')+"]']").val($j(this).val());
						if ($j(this).val()== '<None>'){
							$j(this).parent(".row ").find('input[id^="lookupoperator"]').val('is-empty');
						}else{
							$j(this).parent(".row ").find('input[id^="lookupoperator"]').val('equal-to');
						}
							
					})

					//options case ( populate with initial data)
					$j(":input[class='populatedOptionsData']").each(function(){
					   
						$j(":input[name='FilterValue["+$j(this).attr('name')+"]']").val($j(this).val());
					})


					//checkbox, radio options case
					$j(":input[class='checkboxData'],:input[class='optionsData'] ").each(function(){
						var filterNum = $j(this).val();
						var populatedValue = eval("filterValue_"+filterNum);                  
						var parentDiv = $j(this).parent(".row ");

						//check old value
						parentDiv.find("input[type=radio][value='"+populatedValue+"']").attr('checked', 'checked').click();
					
					})

					//remove unsuplied fields
					beforeApplyFilters();

					return true;
				}
			</script>
			
			<style>
				.form-control{ width: 100% !important; }
				.select2-container, .select2-container.vspacer-lg{ max-width: unset !important; width: 100%; margin-top: 0 !important; }
			</style>


		

<?php } ?>