<?php
// This script and data application were generated by AppGini 5.82
// Download AppGini for free from https://bigprof.com/appgini/download/

	$currDir=dirname(__FILE__);
	include("$currDir/defaultLang.php");
	include("$currDir/language.php");
	include("$currDir/lib.php");
	@include("$currDir/hooks/products.php");
	include("$currDir/products_dml.php");

	// mm: can the current member access this page?
	$perm=getTablePermissions('products');
	if(!$perm[0]) {
		echo error_message($Translation['tableAccessDenied'], false);
		echo '<script>setTimeout("window.location=\'index.php?signOut=1\'", 2000);</script>';
		exit;
	}

	$x = new DataList;
	$x->TableName = "products";

	// Fields that can be displayed in the table view
	$x->QueryFieldsTV = array(
		"`products`.`ProductID`" => "ProductID",
		"`products`.`ProductName`" => "ProductName",
		"IF(    CHAR_LENGTH(`suppliers1`.`CompanyName`), CONCAT_WS('',   `suppliers1`.`CompanyName`), '') /* Supplier */" => "SupplierID",
		"IF(    CHAR_LENGTH(`categories1`.`CategoryName`), CONCAT_WS('',   `categories1`.`CategoryName`), '') /* Category */" => "CategoryID",
		"`products`.`QuantityPerUnit`" => "QuantityPerUnit",
		"CONCAT('$', FORMAT(`products`.`UnitPrice`, 2))" => "UnitPrice",
		"`products`.`UnitsInStock`" => "UnitsInStock",
		"`products`.`UnitsOnOrder`" => "UnitsOnOrder",
		"`products`.`ReorderLevel`" => "ReorderLevel",
		"concat('<i class=\"glyphicon glyphicon-', if(`products`.`Discontinued`, 'check', 'unchecked'), '\"></i>')" => "Discontinued",
	);
	// mapping incoming sort by requests to actual query fields
	$x->SortFields = array(
		1 => '`products`.`ProductID`',
		2 => 2,
		3 => '`suppliers1`.`CompanyName`',
		4 => '`categories1`.`CategoryName`',
		5 => 5,
		6 => '`products`.`UnitPrice`',
		7 => '`products`.`UnitsInStock`',
		8 => '`products`.`UnitsOnOrder`',
		9 => '`products`.`ReorderLevel`',
		10 => '`products`.`Discontinued`',
	);

	// Fields that can be displayed in the csv file
	$x->QueryFieldsCSV = array(
		"`products`.`ProductID`" => "ProductID",
		"`products`.`ProductName`" => "ProductName",
		"IF(    CHAR_LENGTH(`suppliers1`.`CompanyName`), CONCAT_WS('',   `suppliers1`.`CompanyName`), '') /* Supplier */" => "SupplierID",
		"IF(    CHAR_LENGTH(`categories1`.`CategoryName`), CONCAT_WS('',   `categories1`.`CategoryName`), '') /* Category */" => "CategoryID",
		"`products`.`QuantityPerUnit`" => "QuantityPerUnit",
		"CONCAT('$', FORMAT(`products`.`UnitPrice`, 2))" => "UnitPrice",
		"`products`.`UnitsInStock`" => "UnitsInStock",
		"`products`.`UnitsOnOrder`" => "UnitsOnOrder",
		"`products`.`ReorderLevel`" => "ReorderLevel",
		"`products`.`Discontinued`" => "Discontinued",
	);
	// Fields that can be filtered
	$x->QueryFieldsFilters = array(
		"`products`.`ProductID`" => "Product ID",
		"`products`.`ProductName`" => "Product Name",
		"IF(    CHAR_LENGTH(`suppliers1`.`CompanyName`), CONCAT_WS('',   `suppliers1`.`CompanyName`), '') /* Supplier */" => "Supplier",
		"IF(    CHAR_LENGTH(`categories1`.`CategoryName`), CONCAT_WS('',   `categories1`.`CategoryName`), '') /* Category */" => "Category",
		"`products`.`QuantityPerUnit`" => "Quantity Per Unit",
		"`products`.`UnitPrice`" => "Unit Price",
		"`products`.`UnitsInStock`" => "Units In Stock",
		"`products`.`UnitsOnOrder`" => "Units On Order",
		"`products`.`ReorderLevel`" => "Reorder Level",
		"`products`.`Discontinued`" => "Discontinued",
	);

	// Fields that can be quick searched
	$x->QueryFieldsQS = array(
		"`products`.`ProductID`" => "ProductID",
		"`products`.`ProductName`" => "ProductName",
		"IF(    CHAR_LENGTH(`suppliers1`.`CompanyName`), CONCAT_WS('',   `suppliers1`.`CompanyName`), '') /* Supplier */" => "SupplierID",
		"IF(    CHAR_LENGTH(`categories1`.`CategoryName`), CONCAT_WS('',   `categories1`.`CategoryName`), '') /* Category */" => "CategoryID",
		"`products`.`QuantityPerUnit`" => "QuantityPerUnit",
		"CONCAT('$', FORMAT(`products`.`UnitPrice`, 2))" => "UnitPrice",
		"`products`.`UnitsInStock`" => "UnitsInStock",
		"`products`.`UnitsOnOrder`" => "UnitsOnOrder",
		"`products`.`ReorderLevel`" => "ReorderLevel",
		"concat('<i class=\"glyphicon glyphicon-', if(`products`.`Discontinued`, 'check', 'unchecked'), '\"></i>')" => "Discontinued",
	);

	// Lookup fields that can be used as filterers
	$x->filterers = array('SupplierID' => 'Supplier', 'CategoryID' => 'Category', );

	$x->QueryFrom = "`products` LEFT JOIN `suppliers` as suppliers1 ON `suppliers1`.`SupplierID`=`products`.`SupplierID` LEFT JOIN `categories` as categories1 ON `categories1`.`CategoryID`=`products`.`CategoryID` ";
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
	$x->ScriptFileName = "products_view.php";
	$x->RedirectAfterInsert = "products_view.php?SelectedID=#ID#";
	$x->TableTitle = "Products";
	$x->TableIcon = "resources/table_icons/handbag.png";
	$x->PrimaryKey = "`products`.`ProductID`";
	$x->DefaultSortField = '2';
	$x->DefaultSortDirection = 'asc';

	$x->ColWidth   = array(  250, 200, 120, 150, 70, 100);
	$x->ColCaption = array("Product Name", "Supplier", "Category", "Quantity Per Unit", "Unit Price", "Discontinued");
	$x->ColFieldName = array('ProductName', 'SupplierID', 'CategoryID', 'QuantityPerUnit', 'UnitPrice', 'Discontinued');
	$x->ColNumber  = array(2, 3, 4, 5, 6, 10);

	// template paths below are based on the app main directory
	$x->Template = 'templates/products_templateTV.html';
	$x->SelectedTemplate = 'templates/products_templateTVS.html';
	$x->TemplateDV = 'templates/products_templateDV.html';
	$x->TemplateDVP = 'templates/products_templateDVP.html';

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
		$x->QueryWhere="where `products`.`ProductID`=membership_userrecords.pkValue and membership_userrecords.tableName='products' and lcase(membership_userrecords.memberID)='".getLoggedMemberID()."'";
	}elseif($perm[2]==2 || ($perm[2]>2 && $DisplayRecords=='group' && !$_REQUEST['NoFilter_x'])) { // view group only
		$x->QueryFrom.=', membership_userrecords';
		$x->QueryWhere="where `products`.`ProductID`=membership_userrecords.pkValue and membership_userrecords.tableName='products' and membership_userrecords.groupID='".getLoggedGroupID()."'";
	}elseif($perm[2]==3) { // view all
		// no further action
	}elseif($perm[2]==0) { // view none
		$x->QueryFields = array("Not enough permissions" => "NEP");
		$x->QueryFrom = '`products`';
		$x->QueryWhere = '';
		$x->DefaultSortField = '';
	}
	// hook: products_init
	$render=TRUE;
	if(function_exists('products_init')) {
		$args=array();
		$render=products_init($x, getMemberInfo(), $args);
	}

	if($render) $x->Render();

	// hook: products_header
	$headerCode='';
	if(function_exists('products_header')) {
		$args=array();
		$headerCode=products_header($x->ContentType, getMemberInfo(), $args);
	}  
	if(!$headerCode) {
		include_once("$currDir/header.php"); 
	}else{
		ob_start(); include_once("$currDir/header.php"); $dHeader=ob_get_contents(); ob_end_clean();
		echo str_replace('<%%HEADER%%>', $dHeader, $headerCode);
	}

	echo $x->HTML;
	// hook: products_footer
	$footerCode='';
	if(function_exists('products_footer')) {
		$args=array();
		$footerCode=products_footer($x->ContentType, getMemberInfo(), $args);
	}  
	if(!$footerCode) {
		include_once("$currDir/footer.php"); 
	}else{
		ob_start(); include_once("$currDir/footer.php"); $dFooter=ob_get_contents(); ob_end_clean();
		echo str_replace('<%%FOOTER%%>', $dFooter, $footerCode);
	}
?>