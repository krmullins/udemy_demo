<?php
// This script and data application were generated by AppGini 5.82
// Download AppGini for free from https://bigprof.com/appgini/download/

	$currDir=dirname(__FILE__);
	include("$currDir/defaultLang.php");
	include("$currDir/language.php");
	include("$currDir/lib.php");
	@include("$currDir/hooks/orders.php");
	include("$currDir/orders_dml.php");

	// mm: can the current member access this page?
	$perm=getTablePermissions('orders');
	if(!$perm[0]) {
		echo error_message($Translation['tableAccessDenied'], false);
		echo '<script>setTimeout("window.location=\'index.php?signOut=1\'", 2000);</script>';
		exit;
	}

	$x = new DataList;
	$x->TableName = "orders";

	// Fields that can be displayed in the table view
	$x->QueryFieldsTV = array(
		"`orders`.`OrderID`" => "OrderID",
		"`orders`.`status`" => "status",
		"IF(    CHAR_LENGTH(`customers1`.`CompanyName`), CONCAT_WS('',   `customers1`.`CompanyName`), '') /* Customer */" => "CustomerID",
		"IF(    CHAR_LENGTH(`employees1`.`LastName`) || CHAR_LENGTH(`employees1`.`FirstName`), CONCAT_WS('',   `employees1`.`LastName`, ', ', `employees1`.`FirstName`), '') /* Employee */" => "EmployeeID",
		"if(`orders`.`OrderDate`,date_format(`orders`.`OrderDate`,'%m/%d/%Y'),'')" => "OrderDate",
		"if(`orders`.`RequiredDate`,date_format(`orders`.`RequiredDate`,'%m/%d/%Y'),'')" => "RequiredDate",
		"if(`orders`.`ShippedDate`,date_format(`orders`.`ShippedDate`,'%m/%d/%Y'),'')" => "ShippedDate",
		"IF(    CHAR_LENGTH(`shippers1`.`CompanyName`), CONCAT_WS('',   `shippers1`.`CompanyName`), '') /* Ship Via */" => "ShipVia",
		"`orders`.`Freight`" => "Freight",
		"IF(    CHAR_LENGTH(`customers1`.`CompanyName`), CONCAT_WS('',   `customers1`.`CompanyName`), '') /* Ship Name */" => "ShipName",
		"IF(    CHAR_LENGTH(`customers1`.`Address`), CONCAT_WS('',   `customers1`.`Address`), '') /* Ship Address */" => "ShipAddress",
		"IF(    CHAR_LENGTH(`customers1`.`City`), CONCAT_WS('',   `customers1`.`City`), '') /* Ship City */" => "ShipCity",
		"IF(    CHAR_LENGTH(`customers1`.`Region`), CONCAT_WS('',   `customers1`.`Region`), '') /* Ship Region */" => "ShipRegion",
		"IF(    CHAR_LENGTH(`customers1`.`PostalCode`), CONCAT_WS('',   `customers1`.`PostalCode`), '') /* Ship Postal Code */" => "ShipPostalCode",
		"IF(    CHAR_LENGTH(`customers1`.`Country`), CONCAT_WS('',   `customers1`.`Country`), '') /* Ship Country */" => "ShipCountry",
		"`orders`.`total`" => "total",
	);
	// mapping incoming sort by requests to actual query fields
	$x->SortFields = array(
		1 => '`orders`.`OrderID`',
		2 => 2,
		3 => '`customers1`.`CompanyName`',
		4 => 4,
		5 => '`orders`.`OrderDate`',
		6 => '`orders`.`RequiredDate`',
		7 => '`orders`.`ShippedDate`',
		8 => '`shippers1`.`CompanyName`',
		9 => '`orders`.`Freight`',
		10 => '`customers1`.`CompanyName`',
		11 => '`customers1`.`Address`',
		12 => '`customers1`.`City`',
		13 => '`customers1`.`Region`',
		14 => '`customers1`.`PostalCode`',
		15 => '`customers1`.`Country`',
		16 => '`orders`.`total`',
	);

	// Fields that can be displayed in the csv file
	$x->QueryFieldsCSV = array(
		"`orders`.`OrderID`" => "OrderID",
		"`orders`.`status`" => "status",
		"IF(    CHAR_LENGTH(`customers1`.`CompanyName`), CONCAT_WS('',   `customers1`.`CompanyName`), '') /* Customer */" => "CustomerID",
		"IF(    CHAR_LENGTH(`employees1`.`LastName`) || CHAR_LENGTH(`employees1`.`FirstName`), CONCAT_WS('',   `employees1`.`LastName`, ', ', `employees1`.`FirstName`), '') /* Employee */" => "EmployeeID",
		"if(`orders`.`OrderDate`,date_format(`orders`.`OrderDate`,'%m/%d/%Y'),'')" => "OrderDate",
		"if(`orders`.`RequiredDate`,date_format(`orders`.`RequiredDate`,'%m/%d/%Y'),'')" => "RequiredDate",
		"if(`orders`.`ShippedDate`,date_format(`orders`.`ShippedDate`,'%m/%d/%Y'),'')" => "ShippedDate",
		"IF(    CHAR_LENGTH(`shippers1`.`CompanyName`), CONCAT_WS('',   `shippers1`.`CompanyName`), '') /* Ship Via */" => "ShipVia",
		"`orders`.`Freight`" => "Freight",
		"IF(    CHAR_LENGTH(`customers1`.`CompanyName`), CONCAT_WS('',   `customers1`.`CompanyName`), '') /* Ship Name */" => "ShipName",
		"IF(    CHAR_LENGTH(`customers1`.`Address`), CONCAT_WS('',   `customers1`.`Address`), '') /* Ship Address */" => "ShipAddress",
		"IF(    CHAR_LENGTH(`customers1`.`City`), CONCAT_WS('',   `customers1`.`City`), '') /* Ship City */" => "ShipCity",
		"IF(    CHAR_LENGTH(`customers1`.`Region`), CONCAT_WS('',   `customers1`.`Region`), '') /* Ship Region */" => "ShipRegion",
		"IF(    CHAR_LENGTH(`customers1`.`PostalCode`), CONCAT_WS('',   `customers1`.`PostalCode`), '') /* Ship Postal Code */" => "ShipPostalCode",
		"IF(    CHAR_LENGTH(`customers1`.`Country`), CONCAT_WS('',   `customers1`.`Country`), '') /* Ship Country */" => "ShipCountry",
		"`orders`.`total`" => "total",
	);
	// Fields that can be filtered
	$x->QueryFieldsFilters = array(
		"`orders`.`OrderID`" => "Order ID",
		"`orders`.`status`" => "Status",
		"IF(    CHAR_LENGTH(`customers1`.`CompanyName`), CONCAT_WS('',   `customers1`.`CompanyName`), '') /* Customer */" => "Customer",
		"IF(    CHAR_LENGTH(`employees1`.`LastName`) || CHAR_LENGTH(`employees1`.`FirstName`), CONCAT_WS('',   `employees1`.`LastName`, ', ', `employees1`.`FirstName`), '') /* Employee */" => "Employee",
		"`orders`.`OrderDate`" => "Order Date",
		"`orders`.`RequiredDate`" => "Required Date",
		"`orders`.`ShippedDate`" => "Shipped Date",
		"IF(    CHAR_LENGTH(`shippers1`.`CompanyName`), CONCAT_WS('',   `shippers1`.`CompanyName`), '') /* Ship Via */" => "Ship Via",
		"`orders`.`Freight`" => "Freight",
		"IF(    CHAR_LENGTH(`customers1`.`CompanyName`), CONCAT_WS('',   `customers1`.`CompanyName`), '') /* Ship Name */" => "Ship Name",
		"IF(    CHAR_LENGTH(`customers1`.`Address`), CONCAT_WS('',   `customers1`.`Address`), '') /* Ship Address */" => "Ship Address",
		"IF(    CHAR_LENGTH(`customers1`.`City`), CONCAT_WS('',   `customers1`.`City`), '') /* Ship City */" => "Ship City",
		"IF(    CHAR_LENGTH(`customers1`.`Region`), CONCAT_WS('',   `customers1`.`Region`), '') /* Ship Region */" => "Ship Region",
		"IF(    CHAR_LENGTH(`customers1`.`PostalCode`), CONCAT_WS('',   `customers1`.`PostalCode`), '') /* Ship Postal Code */" => "Ship Postal Code",
		"IF(    CHAR_LENGTH(`customers1`.`Country`), CONCAT_WS('',   `customers1`.`Country`), '') /* Ship Country */" => "Ship Country",
		"`orders`.`total`" => "Total",
	);

	// Fields that can be quick searched
	$x->QueryFieldsQS = array(
		"`orders`.`OrderID`" => "OrderID",
		"`orders`.`status`" => "status",
		"IF(    CHAR_LENGTH(`customers1`.`CompanyName`), CONCAT_WS('',   `customers1`.`CompanyName`), '') /* Customer */" => "CustomerID",
		"IF(    CHAR_LENGTH(`employees1`.`LastName`) || CHAR_LENGTH(`employees1`.`FirstName`), CONCAT_WS('',   `employees1`.`LastName`, ', ', `employees1`.`FirstName`), '') /* Employee */" => "EmployeeID",
		"if(`orders`.`OrderDate`,date_format(`orders`.`OrderDate`,'%m/%d/%Y'),'')" => "OrderDate",
		"if(`orders`.`RequiredDate`,date_format(`orders`.`RequiredDate`,'%m/%d/%Y'),'')" => "RequiredDate",
		"if(`orders`.`ShippedDate`,date_format(`orders`.`ShippedDate`,'%m/%d/%Y'),'')" => "ShippedDate",
		"IF(    CHAR_LENGTH(`shippers1`.`CompanyName`), CONCAT_WS('',   `shippers1`.`CompanyName`), '') /* Ship Via */" => "ShipVia",
		"`orders`.`Freight`" => "Freight",
		"IF(    CHAR_LENGTH(`customers1`.`CompanyName`), CONCAT_WS('',   `customers1`.`CompanyName`), '') /* Ship Name */" => "ShipName",
		"IF(    CHAR_LENGTH(`customers1`.`Address`), CONCAT_WS('',   `customers1`.`Address`), '') /* Ship Address */" => "ShipAddress",
		"IF(    CHAR_LENGTH(`customers1`.`City`), CONCAT_WS('',   `customers1`.`City`), '') /* Ship City */" => "ShipCity",
		"IF(    CHAR_LENGTH(`customers1`.`Region`), CONCAT_WS('',   `customers1`.`Region`), '') /* Ship Region */" => "ShipRegion",
		"IF(    CHAR_LENGTH(`customers1`.`PostalCode`), CONCAT_WS('',   `customers1`.`PostalCode`), '') /* Ship Postal Code */" => "ShipPostalCode",
		"IF(    CHAR_LENGTH(`customers1`.`Country`), CONCAT_WS('',   `customers1`.`Country`), '') /* Ship Country */" => "ShipCountry",
		"`orders`.`total`" => "total",
	);

	// Lookup fields that can be used as filterers
	$x->filterers = array('CustomerID' => 'Customer', 'EmployeeID' => 'Employee', 'ShipVia' => 'Ship Via', );

	$x->QueryFrom = "`orders` LEFT JOIN `customers` as customers1 ON `customers1`.`CustomerID`=`orders`.`CustomerID` LEFT JOIN `employees` as employees1 ON `employees1`.`EmployeeID`=`orders`.`EmployeeID` LEFT JOIN `shippers` as shippers1 ON `shippers1`.`ShipperID`=`orders`.`ShipVia` ";
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
	$x->AllowSavingFilters = 1;
	$x->AllowSorting = 1;
	$x->AllowNavigation = 1;
	$x->AllowPrinting = 1;
	$x->AllowPrintingDV = 1;
	$x->AllowCSV = 0;
	$x->RecordsPerPage = 10;
	$x->QuickSearch = 1;
	$x->QuickSearchText = $Translation["quick search"];
	$x->ScriptFileName = "orders_view.php";
	$x->RedirectAfterInsert = "orders_view.php?SelectedID=#ID#";
	$x->TableTitle = "Orders";
	$x->TableIcon = "resources/table_icons/cash_register.png";
	$x->PrimaryKey = "`orders`.`OrderID`";
	$x->DefaultSortField = '1';
	$x->DefaultSortDirection = 'desc';

	$x->ColWidth   = array(  75, 150, 200, 150, 100, 150, 150, 150);
	$x->ColCaption = array("Order ID", "Status", "Customer", "Employee", "Order Date", "Ship Via", "Ship Country", "Total");
	$x->ColFieldName = array('OrderID', 'status', 'CustomerID', 'EmployeeID', 'OrderDate', 'ShipVia', 'ShipCountry', 'total');
	$x->ColNumber  = array(1, 2, 3, 4, 5, 8, 15, 16);

	// template paths below are based on the app main directory
	$x->Template = 'templates/orders_templateTV.html';
	$x->SelectedTemplate = 'templates/orders_templateTVS.html';
	$x->TemplateDV = 'templates/orders_templateDV.html';
	$x->TemplateDVP = 'templates/orders_templateDVP.html';

	$x->ShowTableHeader = 1;
	$x->TVClasses = "";
	$x->DVClasses = "";
	$x->HighlightColor = '#FFF0C2';
	$x->HasCalculatedFields = true;

	// mm: build the query based on current member's permissions
	$DisplayRecords = $_REQUEST['DisplayRecords'];
	if(!in_array($DisplayRecords, array('user', 'group'))) { $DisplayRecords = 'all'; }
	if($perm[2]==1 || ($perm[2]>1 && $DisplayRecords=='user' && !$_REQUEST['NoFilter_x'])) { // view owner only
		$x->QueryFrom.=', membership_userrecords';
		$x->QueryWhere="where `orders`.`OrderID`=membership_userrecords.pkValue and membership_userrecords.tableName='orders' and lcase(membership_userrecords.memberID)='".getLoggedMemberID()."'";
	}elseif($perm[2]==2 || ($perm[2]>2 && $DisplayRecords=='group' && !$_REQUEST['NoFilter_x'])) { // view group only
		$x->QueryFrom.=', membership_userrecords';
		$x->QueryWhere="where `orders`.`OrderID`=membership_userrecords.pkValue and membership_userrecords.tableName='orders' and membership_userrecords.groupID='".getLoggedGroupID()."'";
	}elseif($perm[2]==3) { // view all
		// no further action
	}elseif($perm[2]==0) { // view none
		$x->QueryFields = array("Not enough permissions" => "NEP");
		$x->QueryFrom = '`orders`';
		$x->QueryWhere = '';
		$x->DefaultSortField = '';
	}
	// hook: orders_init
	$render=TRUE;
	if(function_exists('orders_init')) {
		$args=array();
		$render=orders_init($x, getMemberInfo(), $args);
	}

	if($render) $x->Render();

	// hook: orders_header
	$headerCode='';
	if(function_exists('orders_header')) {
		$args=array();
		$headerCode=orders_header($x->ContentType, getMemberInfo(), $args);
	}  
	if(!$headerCode) {
		include_once("$currDir/header.php"); 
	}else{
		ob_start(); include_once("$currDir/header.php"); $dHeader=ob_get_contents(); ob_end_clean();
		echo str_replace('<%%HEADER%%>', $dHeader, $headerCode);
	}

	echo $x->HTML;
	// hook: orders_footer
	$footerCode='';
	if(function_exists('orders_footer')) {
		$args=array();
		$footerCode=orders_footer($x->ContentType, getMemberInfo(), $args);
	}  
	if(!$footerCode) {
		include_once("$currDir/footer.php"); 
	}else{
		ob_start(); include_once("$currDir/footer.php"); $dFooter=ob_get_contents(); ob_end_clean();
		echo str_replace('<%%FOOTER%%>', $dFooter, $footerCode);
	}
?>