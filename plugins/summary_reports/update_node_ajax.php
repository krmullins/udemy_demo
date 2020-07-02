<?php
	include(dirname(__FILE__).'/summary_reports.php');

	/*
		Ajax-callable file to update a summary report

		$_REQUEST includes the following:
		axp: md5 hash of project
		table_name: source table (the table containing the report) 
		report-title
		group-table
		label: field name used as label field
		label-field-index: index of label field
		first-caption: label of group-by column
		second-caption: label of value column
		how-to-summarize: grouping function
		group-array: names of groups allowed to access report, one per line
		look-up-table: in csae label field is a lookup field, this is its parent table
		look-up-value: parentCaption1 fieldname of look-up-table
		date-field
		date-field-index
		report-header-url
		data-table-section
		piechart-section
		barchart-section
		report-footer-url
		report-id: index of current report OR report hash
		custom-where: optional custom where conditions to be ANDed to report query
		override-permissions: 1 = retrieve all data into report rather than user-accessible data
		
		@return  string  updated reports of the given table, JSON-encoded string
	*/
	
	$summary_reports = new summary_reports(array(
		'title' => 'Summary Reports',
		'name' => 'summary_reports', 
		'logo' => 'summary_reports-logo-lg.png' 
	));
	
	$summary_reports->reject_non_admin('Access denied');
	
	$projectFile = '';
	$summary_reports->get_xml_file($_REQUEST['axp'], $projectFile);
	
	$report = new stdClass();

	$summary_reports->log(array('_REQUEST' => $_REQUEST), true);

	$report->report_hash = $_REQUEST['report-hash'];
	if(!$report->report_hash) $report->report_hash = $summary_reports->random_hash();

	$report->title = strip_tags($_REQUEST['report-title']);
	$report->table = $_REQUEST['table_name'];

	$report->table_index = $summary_reports->table_index($report->table);
	
	$parent_table = $_REQUEST['group-table'];
	if(in_array($parent_table, $summary_reports->get_table_names())) {
		$report->parent_table = $parent_table;
	}

	// retrieve stored reports from project
	$reports = array();
	$sr_node = $summary_reports->get_table_plugin_node($report->table);
	if($sr_node && isset($sr_node->report_details)) {
		$reports = @json_decode($sr_node->report_details, true);
		if($reports === null || !is_array($reports)) $reports = array();
	}

	$summary_reports->log(array('saved reports' => $reports));

	// report-id could be numeric index of report OR report hash ...
	// in case it's report hash, we need to determine the report index
	$report_id = $_REQUEST['report-id'];
	$summary_reports->log(array('report_id from _REQUEST' => $report_id));

	if($report_id && !is_numeric($report_id)) {
		// report_id is hash .. we need to convert it to report index
		$index_found = false;
		foreach($reports as $report_index => $rep) {
			if($rep['report_hash'] == $report_id) {
				$index_found = true;
				$report_id = $report_index;
				break;
			}
		}
		if(!$index_found) $report_id = false;
	}
	// after the above checks, if report_id is not numeric and is not 0,
	// then this is a new report to be appended to the end of the reports array
	if(!$report_id && $report_id !== '0' && $report_id !== 0) $report_id = count($reports);
	$summary_reports->log(array('processed report_id' => $report_id));

	// label field for report is by default the first field in table,
	// or parent table if report is grouped by field from another table,
	// unless request specifies a valid label field to be used instead
	$table_fields = $summary_reports->get_table_fields($report->table);
	if($report->parent_table) {
		$table_fields = $summary_reports->get_table_fields($report->parent_table);
	}
	$report->label = $table_fields[0]; 
	if(in_array($_REQUEST['label'] , $table_fields)) $report->label = $_REQUEST['label']; 

	$report->caption1 = $_REQUEST['first-caption'];
	$report->caption2 = $_REQUEST['second-caption'];
	$report->group_function = $_REQUEST['how-to-summarize'];
	$report->group_function_field = $_REQUEST['summarized-value'];

	// retrieve groups allowed to access the report
	// by parsing request>group-array, converting contents of
	// each line to an array item
	$report->group_array = array();
	if(isset($_REQUEST['group-array'])) {
		$group_str = $_REQUEST['group-array'];
		$group_str = str_replace(array("\r", "\n"), '%GS%', $group_str);
		$group_array = explode('%GS%', $group_str);
		for($i = 0; $i < count($group_array); $i++) {
			if(strlen($group_array[$i])) $report->group_array[] = trim($group_array[$i]);
		}
	}

	if(isset($_REQUEST['look-up-table'])) {
		$report->look_up_table = $_REQUEST['look-up-table'];
	}
	 
	if(isset($_REQUEST['look-up-value'])) {
		$report->look_up_value = $_REQUEST['look-up-value'];
	}

	if(isset($_REQUEST['label-field-index'])) {
		$report->label_field_index = $_REQUEST['label-field-index'];
	}

	if(isset($_REQUEST['date-field']) && $_REQUEST['date-field'] != ''){
		$report->date_field = $_REQUEST['date-field'];
		if(isset($_REQUEST['date-field-index'])) {	
			$report->date_field_index = $_REQUEST['date-field-index'];
		} 
	}
	
	if(isset($_REQUEST['report-header-url'])) {
		$report->report_header_url = $_REQUEST['report-header-url'];
	}
	
	if(isset($_REQUEST['report-footer-url'])) {
		$report->report_footer_url = $_REQUEST['report-footer-url'];
	}
	
	$report->data_table_section = isset($_REQUEST['data_table_section']) ? 1 : 0;
	$report->barchart_section = isset($_REQUEST['barchart_section']) ? 1 : 0;
	$report->piechart_section = isset($_REQUEST['piechart_section']) ? 1 : 0;

	$report->override_permissions = isset($_REQUEST['override-permissions']) ? 1 : 0;
	$report->custom_where = isset($_REQUEST['custom-where']) ? trim($_REQUEST['custom-where']) : '';
	
	$all_lookup_fields = '';
	$date_separators = array(
		'1' => '-',
		'2' => ' ',
		'3' => '.',
		'4' => '/',
		'5' => ','
	);	
	 
	$date_separator_index = (string) $summary_reports->project_xml->dateSeparator;
	$report->date_separator = $date_separators[ $date_separator_index ];
	
	$summary_reports->log(array('report' => $report));

	$reports[$report_id] = $report;

	$json_nodes = json_encode($reports);
	 
	$summary_reports->log(array('new reports' => $reports));

	/* update node */
	$summary_reports->update_project_plugin_node(array(
		'projectName' => $projectFile,
		'tableIndex' => $report->table_index,
		'nodeName' => 'report_details',
		'pluginName' => 'summary_reports',
		'data' => $json_nodes
	));

	echo $json_nodes;
