<?php
// This script and data application were generated by AppGini 5.82
// Download AppGini for free from https://bigprof.com/appgini/download/

	$currDir=dirname(__FILE__);
	include("$currDir/defaultLang.php");
	include("$currDir/language.php");
	include("$currDir/lib.php");
	@include("$currDir/hooks/logs.php");
	include("$currDir/logs_dml.php");

	// mm: can the current member access this page?
	$perm=getTablePermissions('logs');
	if(!$perm[0]) {
		echo error_message($Translation['tableAccessDenied'], false);
		echo '<script>setTimeout("window.location=\'index.php?signOut=1\'", 2000);</script>';
		exit;
	}

	$x = new DataList;
	$x->TableName = "logs";

	// Fields that can be displayed in the table view
	$x->QueryFieldsTV = array(
		"`logs`.`id`" => "id",
		"`logs`.`ip`" => "ip",
		"`logs`.`ts`" => "ts",
		"`logs`.`details`" => "details",
	);
	// mapping incoming sort by requests to actual query fields
	$x->SortFields = array(
		1 => '`logs`.`id`',
		2 => 2,
		3 => '`logs`.`ts`',
		4 => 4,
	);

	// Fields that can be displayed in the csv file
	$x->QueryFieldsCSV = array(
		"`logs`.`id`" => "id",
		"`logs`.`ip`" => "ip",
		"`logs`.`ts`" => "ts",
		"`logs`.`details`" => "details",
	);
	// Fields that can be filtered
	$x->QueryFieldsFilters = array(
		"`logs`.`id`" => "ID",
		"`logs`.`ip`" => "Ip",
		"`logs`.`ts`" => "Ts",
		"`logs`.`details`" => "Details",
	);

	// Fields that can be quick searched
	$x->QueryFieldsQS = array(
		"`logs`.`id`" => "id",
		"`logs`.`ip`" => "ip",
		"`logs`.`ts`" => "ts",
		"`logs`.`details`" => "details",
	);

	// Lookup fields that can be used as filterers
	$x->filterers = array();

	$x->QueryFrom = "`logs` ";
	$x->QueryWhere = '';
	$x->QueryOrder = '';

	$x->AllowSelection = 1;
	$x->HideTableView = ($perm[2]==0 ? 1 : 0);
	$x->AllowDelete = $perm[4];
	$x->AllowMassDelete = false;
	$x->AllowInsert = $perm[1];
	$x->AllowUpdate = $perm[3];
	$x->SeparateDV = 1;
	$x->AllowDeleteOfParents = 0;
	$x->AllowFilters = 1;
	$x->AllowSavingFilters = 0;
	$x->AllowSorting = 1;
	$x->AllowNavigation = 1;
	$x->AllowPrinting = 1;
	$x->AllowPrintingDV = 1;
	$x->AllowCSV = 1;
	$x->RecordsPerPage = 10;
	$x->QuickSearch = 1;
	$x->QuickSearchText = $Translation["quick search"];
	$x->ScriptFileName = "logs_view.php";
	$x->RedirectAfterInsert = "logs_view.php?SelectedID=#ID#";
	$x->TableTitle = "Logs";
	$x->TableIcon = "table.gif";
	$x->PrimaryKey = "`logs`.`id`";

	$x->ColWidth   = array(  150, 150, 150);
	$x->ColCaption = array("Ip", "Ts", "Details");
	$x->ColFieldName = array('ip', 'ts', 'details');
	$x->ColNumber  = array(2, 3, 4);

	// template paths below are based on the app main directory
	$x->Template = 'templates/logs_templateTV.html';
	$x->SelectedTemplate = 'templates/logs_templateTVS.html';
	$x->TemplateDV = 'templates/logs_templateDV.html';
	$x->TemplateDVP = 'templates/logs_templateDVP.html';

	$x->ShowTableHeader = 1;
	$x->TVClasses = "";
	$x->DVClasses = "";
	$x->HighlightColor = '#FFF0C2';
	$x->HasCalculatedFields = false;

	// mm: build the query based on current member's permissions
	$DisplayRecords = $_REQUEST['DisplayRecords'];
	if(!in_array($DisplayRecords, array('user', 'group'))) { $DisplayRecords = 'all'; }
	if($perm[2]==1 || ($perm[2]>1 && $DisplayRecords=='user' && !$_REQUEST['NoFilter_x'])) { // view owner only
		$x->QueryFrom.=', membership_userrecords';
		$x->QueryWhere="where `logs`.`id`=membership_userrecords.pkValue and membership_userrecords.tableName='logs' and lcase(membership_userrecords.memberID)='".getLoggedMemberID()."'";
	}elseif($perm[2]==2 || ($perm[2]>2 && $DisplayRecords=='group' && !$_REQUEST['NoFilter_x'])) { // view group only
		$x->QueryFrom.=', membership_userrecords';
		$x->QueryWhere="where `logs`.`id`=membership_userrecords.pkValue and membership_userrecords.tableName='logs' and membership_userrecords.groupID='".getLoggedGroupID()."'";
	}elseif($perm[2]==3) { // view all
		// no further action
	}elseif($perm[2]==0) { // view none
		$x->QueryFields = array("Not enough permissions" => "NEP");
		$x->QueryFrom = '`logs`';
		$x->QueryWhere = '';
		$x->DefaultSortField = '';
	}
	// hook: logs_init
	$render=TRUE;
	if(function_exists('logs_init')) {
		$args=array();
		$render=logs_init($x, getMemberInfo(), $args);
	}

	if($render) $x->Render();

	// hook: logs_header
	$headerCode='';
	if(function_exists('logs_header')) {
		$args=array();
		$headerCode=logs_header($x->ContentType, getMemberInfo(), $args);
	}  
	if(!$headerCode) {
		include_once("$currDir/header.php"); 
	}else{
		ob_start(); include_once("$currDir/header.php"); $dHeader=ob_get_contents(); ob_end_clean();
		echo str_replace('<%%HEADER%%>', $dHeader, $headerCode);
	}

	echo $x->HTML;
	// hook: logs_footer
	$footerCode='';
	if(function_exists('logs_footer')) {
		$args=array();
		$footerCode=logs_footer($x->ContentType, getMemberInfo(), $args);
	}  
	if(!$footerCode) {
		include_once("$currDir/footer.php"); 
	}else{
		ob_start(); include_once("$currDir/footer.php"); $dFooter=ob_get_contents(); ob_end_clean();
		echo str_replace('<%%FOOTER%%>', $dFooter, $footerCode);
	}
?>