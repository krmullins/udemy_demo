<?php
	// check this file's MD5 to make sure it wasn't called before
	$prevMD5=@implode('', @file(dirname(__FILE__).'/setup.md5'));
	$thisMD5=md5(@implode('', @file("./updateDB.php")));
	if($thisMD5==$prevMD5) {
		$setupAlreadyRun=true;
	}else{
		// set up tables
		if(!isset($silent)) {
			$silent=true;
		}

		// set up tables
		setupTable('customers', "create table if not exists `customers` (   `CustomerID` VARCHAR(5) not null , primary key (`CustomerID`), `CompanyName` VARCHAR(40) null , `ContactName` VARCHAR(30) null , `ContactTitle` VARCHAR(30) null , `Address` TEXT null , `City` VARCHAR(15) null , `Region` VARCHAR(15) null , `PostalCode` VARCHAR(10) null , `Country` VARCHAR(15) null , `Phone` VARCHAR(24) null , `Fax` VARCHAR(24) null , `TotalSales` DOUBLE(10,2) null ) CHARSET latin1", $silent);
		setupTable('employees', "create table if not exists `employees` (   `EmployeeID` INT not null auto_increment , primary key (`EmployeeID`), `TitleOfCourtesy` VARCHAR(50) null , `Photo` VARCHAR(40) null , `LastName` VARCHAR(50) null , `FirstName` VARCHAR(10) null , `Title` VARCHAR(30) null , `BirthDate` DATE null , `Age` INT null , `HireDate` DATE null , `Address` VARCHAR(50) null , `City` VARCHAR(15) null , `Region` VARCHAR(15) null , `PostalCode` VARCHAR(10) null , `Country` VARCHAR(15) null , `HomePhone` VARCHAR(24) null , `Extension` VARCHAR(4) null , `Notes` TEXT null , `ReportsTo` INT null , `TotalSales` DOUBLE(10,2) null ) CHARSET latin1", $silent);
		setupIndexes('employees', array('ReportsTo'));
		setupTable('orders', "create table if not exists `orders` (   `OrderID` INT not null auto_increment , primary key (`OrderID`), `status` VARCHAR(200) null , `CustomerID` VARCHAR(5) null , `EmployeeID` INT null , `OrderDate` DATE null , `RequiredDate` DATE null , `ShippedDate` DATE null , `ShipVia` INT(11) null , `Freight` FLOAT(10,2) null default '0' , `ShipName` VARCHAR(5) null , `ShipAddress` VARCHAR(5) null , `ShipCity` VARCHAR(5) null , `ShipRegion` VARCHAR(5) null , `ShipPostalCode` VARCHAR(5) null , `ShipCountry` VARCHAR(5) null , `total` DECIMAL(10,2) null ) CHARSET latin1", $silent);
		setupIndexes('orders', array('CustomerID','EmployeeID','ShipVia'));
		setupTable('order_details', "create table if not exists `order_details` (   `odID` INT unsigned not null auto_increment , primary key (`odID`), `OrderID` INT null default '0' , `ProductID` INT null default '0' , `Category` INT null , `CatalogPrice` INT null , `UnitsInStock` INT null , `UnitPrice` FLOAT(10,2) null default '0' , `Quantity` SMALLINT null default '1' , `Discount` FLOAT(10,2) null default '0' , `Subtotal` DOUBLE(10,2) null ) CHARSET latin1", $silent);
		setupIndexes('order_details', array('OrderID','ProductID'));
		setupTable('products', "create table if not exists `products` (   `ProductID` INT not null auto_increment , primary key (`ProductID`), `ProductName` VARCHAR(50) null , `SupplierID` INT(11) null , `CategoryID` INT null , `QuantityPerUnit` VARCHAR(50) null , `UnitPrice` FLOAT(10,2) null default '0' , `UnitsInStock` SMALLINT null default '0' , `UnitsOnOrder` SMALLINT(6) null default '0' , `ReorderLevel` SMALLINT null default '0' , `Discontinued` TINYINT null default '0' ) CHARSET latin1", $silent);
		setupIndexes('products', array('SupplierID','CategoryID'));
		setupTable('categories', "create table if not exists `categories` (   `CategoryID` INT not null auto_increment , primary key (`CategoryID`), `Picture` VARCHAR(40) null , `CategoryName` VARCHAR(50) null , unique `CategoryName_unique` (`CategoryName`), `Description` TEXT null ) CHARSET latin1", $silent);
		setupTable('suppliers', "create table if not exists `suppliers` (   `SupplierID` INT(11) not null auto_increment , primary key (`SupplierID`), `CompanyName` VARCHAR(50) null , `ContactName` VARCHAR(30) null , `ContactTitle` VARCHAR(30) null , `Address` VARCHAR(50) null , `City` VARCHAR(15) null , `Region` VARCHAR(15) null , `PostalCode` VARCHAR(10) null , `Country` VARCHAR(50) null , `Phone` VARCHAR(24) null , `Fax` VARCHAR(24) null , `HomePage` TEXT null ) CHARSET latin1", $silent);
		setupTable('shippers', "create table if not exists `shippers` (   `ShipperID` INT(11) not null auto_increment , primary key (`ShipperID`), `CompanyName` VARCHAR(40) not null , `Phone` VARCHAR(24) null , `NumOrders` INT null ) CHARSET latin1", $silent);
		setupTable('logs', "create table if not exists `logs` (   `id` INT unsigned not null auto_increment , primary key (`id`), `ip` VARCHAR(40) null , `ts` BIGINT null , `details` TEXT null ) CHARSET latin1", $silent, array( "ALTER TABLE `table9` RENAME `logs`","UPDATE `membership_userrecords` SET `tableName`='logs' where `tableName`='table9'","UPDATE `membership_userpermissions` SET `tableName`='logs' where `tableName`='table9'","UPDATE `membership_grouppermissions` SET `tableName`='logs' where `tableName`='table9'","ALTER TABLE logs ADD `field1` VARCHAR(40)","ALTER TABLE `logs` CHANGE `field1` `id` VARCHAR(40) null ","ALTER TABLE `logs` CHANGE `id` `id` INT unsigned not null auto_increment ","ALTER TABLE logs ADD `field2` VARCHAR(40)","ALTER TABLE logs ADD `field3` VARCHAR(40)","ALTER TABLE `logs` CHANGE `field2` `ip` VARCHAR(40) null ","ALTER TABLE `logs` CHANGE `field3` `ts` VARCHAR(40) null "," ALTER TABLE `logs` CHANGE `ts` `ts` BIGINT null ","ALTER TABLE logs ADD `field4` VARCHAR(40)","ALTER TABLE `logs` CHANGE `field4` `details` VARCHAR(40) null ","ALTER TABLE `logs` CHANGE `comments` `comments` TEXT null "));


		// save MD5
		if($fp=@fopen(dirname(__FILE__).'/setup.md5', 'w')) {
			fwrite($fp, $thisMD5);
			fclose($fp);
		}
	}


	function setupIndexes($tableName, $arrFields) {
		if(!is_array($arrFields)) {
			return false;
		}

		foreach($arrFields as $fieldName) {
			if(!$res=@db_query("SHOW COLUMNS FROM `$tableName` like '$fieldName'")) {
				continue;
			}
			if(!$row=@db_fetch_assoc($res)) {
				continue;
			}
			if($row['Key']=='') {
				@db_query("ALTER TABLE `$tableName` ADD INDEX `$fieldName` (`$fieldName`)");
			}
		}
	}


	function setupTable($tableName, $createSQL='', $silent=true, $arrAlter='') {
		global $Translation;
		ob_start();

		echo '<div style="padding: 5px; border-bottom:solid 1px silver; font-family: verdana, arial; font-size: 10px;">';

		// is there a table rename query?
		if(is_array($arrAlter)) {
			$matches=array();
			if(preg_match("/ALTER TABLE `(.*)` RENAME `$tableName`/", $arrAlter[0], $matches)) {
				$oldTableName=$matches[1];
			}
		}

		if($res=@db_query("select count(1) from `$tableName`")) { // table already exists
			if($row = @db_fetch_array($res)) {
				echo str_replace("<TableName>", $tableName, str_replace("<NumRecords>", $row[0],$Translation["table exists"]));
				if(is_array($arrAlter)) {
					echo '<br>';
					foreach($arrAlter as $alter) {
						if($alter!='') {
							echo "$alter ... ";
							if(!@db_query($alter)) {
								echo '<span class="label label-danger">' . $Translation['failed'] . '</span>';
								echo '<div class="text-danger">' . $Translation['mysql said'] . ' ' . db_error(db_link()) . '</div>';
							}else{
								echo '<span class="label label-success">' . $Translation['ok'] . '</span>';
							}
						}
					}
				}else{
					echo $Translation["table uptodate"];
				}
			}else{
				echo str_replace("<TableName>", $tableName, $Translation["couldnt count"]);
			}
		}else{ // given tableName doesn't exist

			if($oldTableName!='') { // if we have a table rename query
				if($ro=@db_query("select count(1) from `$oldTableName`")) { // if old table exists, rename it.
					$renameQuery=array_shift($arrAlter); // get and remove rename query

					echo "$renameQuery ... ";
					if(!@db_query($renameQuery)) {
						echo '<span class="label label-danger">' . $Translation['failed'] . '</span>';
						echo '<div class="text-danger">' . $Translation['mysql said'] . ' ' . db_error(db_link()) . '</div>';
					}else{
						echo '<span class="label label-success">' . $Translation['ok'] . '</span>';
					}

					if(is_array($arrAlter)) setupTable($tableName, $createSQL, false, $arrAlter); // execute Alter queries on renamed table ...
				}else{ // if old tableName doesn't exist (nor the new one since we're here), then just create the table.
					setupTable($tableName, $createSQL, false); // no Alter queries passed ...
				}
			}else{ // tableName doesn't exist and no rename, so just create the table
				echo str_replace("<TableName>", $tableName, $Translation["creating table"]);
				if(!@db_query($createSQL)) {
					echo '<span class="label label-danger">' . $Translation['failed'] . '</span>';
					echo '<div class="text-danger">' . $Translation['mysql said'] . db_error(db_link()) . '</div>';
				}else{
					echo '<span class="label label-success">' . $Translation['ok'] . '</span>';
				}
			}
		}

		echo "</div>";

		$out=ob_get_contents();
		ob_end_clean();
		if(!$silent) {
			echo $out;
		}
	}
?>