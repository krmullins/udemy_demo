<?php
	define('PREPEND_PATH', '../');
	$hooks_dir = dirname(__FILE__);
	include("{$hooks_dir}/../defaultLang.php");
	include("{$hooks_dir}/../language.php");
	include("{$hooks_dir}/../lib.php");
	
	include_once("{$hooks_dir}/../header.php");
	
	/* check access */
	$mi = getMemberInfo();
	if($mi['group'] != 'Admins'){
		echo error_message("Access denied");
		include_once("{$hooks_dir}/../footer.php");
		exit;
	}

	$ts0 = microtime(true);

	$numOrders = rand(200, 400);
	$ro = new RandomOrders($numOrders);
	
	$ts1 = microtime(true);
	if(!$ro->commit()) die('Error inserting orders: ' . db_error());

	$lastOrderDate = sqlValue("select max(OrderDate) from orders");
	$totalNumOrders = sqlValue("select count(1) from orders");
	$totalNumOrderItems = sqlValue("select count(1) from order_details");

	$ts2 = microtime(true);
	$totalTime = round($ts2 - $ts1, 3);
	
	?>

	<div class="alert alert-info vspacer-lg" style="font-size: 2rem;">
		Newly-created orders: <?php echo $numOrders; ?><br>
		Orders#: <?php echo number_format($totalNumOrders); ?><br>
		Order items#: <?php echo number_format($totalNumOrderItems); ?><br>
		Last order date: <?php echo $lastOrderDate; ?><br>
		Last operation took <?php echo $totalTime; ?> seconds.
	</div>

	<div class="alert alert-success">Orders added successfully on <?php echo date('H:i:s'); ?> ... Adding more in seconds.</div>
	<script>
		$j(function() {
			setTimeout(function(){
				$j('.alert-success')
					.removeClass('alert-success')
					.addClass('alert-warning')
					.html('Reloading. Please wait...'); 

				location.reload(); 
			}, 500);
			
			$j('title').html('<?php echo $lastOrderDate; ?> | <?php echo number_format($totalNumOrders); ?> | <?php echo $totalTime; ?> sec.');
		})
	</script>

	<?php
	include_once("{$hooks_dir}/../footer.php");

	/************************************************************/

	class RandomOrders {
		private $startDate, 
			$endDate, 
			$sqlOrders = [], 
			$sqlItems = [], 
			$products = [],
			$orderDate,
			$orderId,
			$customers = [],
			$employees = [],
			$shippers = [];

		public function __construct($numOrders = 1) {
			$this->orderDate = sqlValue("SELECT DATE_ADD(MAX(OrderDate), INTERVAL 1 day) FROM orders");

			$this->startDate = sqlValue("SELECT MAX(OrderDate) FROM orders");
			$this->endDate = date('Y-m-d', time() - 20 * 86400);

			// don't generate orders with a date exceeding end date
			if(strtotime($this->endDate) < strtotime($this->orderDate)) return;

			for($i = 0; $i < $numOrders; $i++) $this->placeOneOrder();
		}

		public function commit() {
			/*
			// create temp tables ...
			$newOrderId = sqlValue("SELECT MAX(OrderID) + 1 FROM orders");
			$newOrderItemId = sqlValue("SELECT MAX(odID) + 1 FROM order_details");

			$seo = ['silentErrors' => true];
			sql("DROP TABLE `scratch_orders`", $seo);
			sql("DROP TABLE `scratch_order_details`", $seo);
			sql("CREATE TABLE scratch_orders LIKE orders", $eo);
			sql("ALTER TABLE scratch_orders AUTO_INCREMENT={$newOrderId}", $eo);
			sql("CREATE TABLE scratch_order_details LIKE order_details", $eo);
			sql("ALTER TABLE scratch_order_details AUTO_INCREMENT={$newOrderItemId}", $eo);

			$ordersSQL = str_replace('`orders`', '`scratch_orders`', $this->getOrdersSQL());
			$itemsSQL = str_replace('`order_details`', '`scratch_order_details`', $this->getItemsSQL());


			if(!sql($ordersSQL, $eo)) return false;
			sql($itemsSQL, $eo);

			sql("INSERT INTO `orders` SELECT * FROM `scratch_orders`", $eo);
			sql("INSERT INTO `order_details` SELECT * FROM `scratch_order_details`", $eo);
			sql("DROP TABLE `scratch_orders`", $eo);
			sql("DROP TABLE `scratch_order_details`", $eo);
			*/
			if(!sql($this->getOrdersSQL(), $eo)) return false;
			if(!sql($this->getItemsSQL(), $eo)) return false;
			
			return true;
		}

		public function getOrdersSQL() {
			if(!count($this->sqlOrders)) return false;

			return "INSERT INTO `orders` " .
				"(`OrderID`, `CustomerID`, `EmployeeID`, `OrderDate`, `RequiredDate`, `ShippedDate`, `ShipVia`, `Freight`, `ShipName`, `ShipAddress`, `ShipCity`, `ShipRegion`, `ShipPostalCode`, `ShipCountry`) VALUES\n" .
				implode(",\n", $this->sqlOrders);
		}

		public function getItemsSQL() {
			if(!count($this->sqlItems)) return false;

			return "INSERT INTO `order_details` " .
				"(`OrderID`, `ProductID`, `UnitPrice`, `Quantity`) VALUES\n" .
				implode(",\n", $this->sqlItems);
		}

		/*****************************************/

		private function addDate($date, $days) {
			$ts = strtotime($date) + 86400 * $days;
			return date('Y-m-d', $ts);
		}

		private function nextOrderId() {
			if(!$this->orderId) {
				$this->orderId = sqlValue("SELECT MAX(OrderID) FROM orders");
			}

			return ++$this->orderId;
		}

		private function nextOrderDate() {
			if(!$this->orderDate) $this->orderDate = $this->startDate;

			$orderTS = strtotime($this->orderDate) + (rand(0, 10000) / 9930) * 86400;

			$endTS = strtotime($this->endDate);
			if($orderTS > $endTS) $orderTS = $endTS;

			$this->orderDate = date('Y-m-d', $orderTS);
			return $this->orderDate;
		}

		private function placeOneOrder() {
			// $orderDate = $this->nextOrderDate();
			$orderDate = $this->orderDate;
			$requiredDate = $this->addDate($orderDate, rand(2, 5));
			$shippedDate = $this->addDate($requiredDate, rand(-1, 10));

			$customerId = $this->randomCustomer();
			$employeeId = $this->randomEmployee();
			$shipperId = $this->randomShipper();
			$freight = rand(0, 5000) / 100;
			$orderId = $this->nextOrderId();

			$itemsCount = rand(1, 35);
			$items = $this->randomItems($itemsCount);

			$this->sqlOrders[] = "('{$orderId}','{$customerId}','{$employeeId}','{$orderDate}','{$requiredDate}','{$shippedDate}','{$shipperId}','{$freight}','{$customerId}','{$customerId}','{$customerId}','{$customerId}','{$customerId}','{$customerId}')";

			foreach($items as $item) {
				$this->sqlItems[] = "('{$orderId}', '{$item['id']}', '{$item['price']}', '{$item['qty']}')";
			}
		}

		private function randomItems($count) {
			if(!count($this->products)) {
				// cache products to reduce # queries
				$res = sql("select * from products order by ProductID", $eo);
				while($row = db_fetch_assoc($res)){
					$this->products[] = [
						'id' => $row['ProductID'],
						'price' => $row['UnitPrice']
					];
				}
			}

			// get $count unique productIDs
			$items = [];
			while(count($items) < $count) {
				$randProduct = $this->products[rand(0, count($this->products) - 1)];
				$items[$randProduct['id']] = $randProduct;
				$items[$randProduct['id']]['qty'] = rand(1, 30);
			}

			return $items;
		}

		private function randomCustomer() {
			if(!count($this->customers)) {
				$res = sql("select CustomerID from customers order by CustomerID", $eo);
				while($row = db_fetch_assoc($res)){
					$this->customers[] = $row['CustomerID'];
				}
			}

			return $this->customers[rand(0, count($this->customers) - 1)];
		}

		private function randomEmployee() {
			if(!count($this->employees)) {
				$res = sql("select EmployeeID from employees order by EmployeeID", $eo);
				while($row = db_fetch_assoc($res)){
					$this->employees[] = $row['EmployeeID'];
				}
			}

			return $this->employees[rand(0, count($this->employees) - 1)];
		}

		private function randomShipper() {
			if(!count($this->shippers)) {
				$res = sql("select ShipperID from shippers order by ShipperID", $eo);
				while($row = db_fetch_assoc($res)){
					$this->shippers[] = $row['ShipperID'];
				}
			}

			return $this->shippers[rand(0, count($this->shippers) - 1)];
		}
	}