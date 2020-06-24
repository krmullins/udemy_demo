<?php
	/* Script for executing custom SQL queries */
	define('PREPEND_PATH', '../');
	$hooks_dir = dirname(__FILE__);
	include("{$hooks_dir}/../defaultLang.php");
	include("{$hooks_dir}/../language.php");
	include("{$hooks_dir}/../lib.php");
	
	include_once("{$hooks_dir}/../header.php");
	
	/* check access -- allow only admins */
	$mi = getMemberInfo();
	if(!in_array($mi['group'], array('Admins'))){
		echo error_message("Access denied");
		include_once("{$hooks_dir}/../footer.php");
		exit;
	}
	
	$sql = $_POST['sql'];
	$error = '';
	
	$result = process_sql_command($sql, $error);
	echo show_title();
	echo show_sql_errors($error);
	echo show_sql_form($sql);
	echo show_results($result);
	
	include_once("{$hooks_dir}/../footer.php");
	
	// ---------------------------------------------------------------------
	
	function show_title() {
		ob_start();
		?>
		<div class="page-header"><h1>Run a custom SQL query</h1></div>
		<?php
		return ob_get_clean();
	}
	
	function process_sql_command($sql, &$error) {
		if(!$sql) return true;
		
		$eo = ['silentErrors' => true];
		$res = sql($sql, $eo);
		
		$error = $eo['error'];
		return $res;
	}
	
	function show_sql_errors($error) {
		if(!$error) return '';
		
		ob_start();
		?>
		<div class="alert alert-danger">
			<p><b>The following error occured</b></p>
			<p><?php echo $error; ?></p>
		</div>
		<?php
		
		return ob_get_clean();
	}
	
	function show_sql_form($sql) {
		ob_start();
		?>
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
			<div class="form-group">
				<label for="sql" class="control-label">SQL Command</label>
				<textarea class="form-control" id="sql" name="sql" autofocus></textarea>
				<span class="help-block">This is so dangerous. Please be responsible! <button type="button" class="btn btn-info btn-sm" id="last-sql"><i class="glyphicon glyphicon-paste"></i> Insert last SQL command</button></span>
			</div>
			
			<div class="row">
				<div class="col-sm-4">
					<button class="btn btn-primary btn-lg btn-block" value="submit" id="submit_button" type="submit" name="submit_button">Execute Query</button>
				</div>
			</div>
		</form>
		<style>
			#sql {
				width: 100em;
				height: 10em;
				overflow: auto;
			}
			form {
				margin-bottom: 2em;
			}
		</style>
		<script>
			$j(function() {
				var lastSQL = <?php echo json_encode($sql); ?>;
				
				$j('form').on('submit', function() {
					return confirm('Are you really really sure you want to execute your SQL command?');
				});
				
				$j('#last-sql').click(function() {
					$j('#sql').val(lastSQL);
				});
			})
		</script>
		<?php
		return ob_get_clean();
	}
	
	function show_results($res) {
		$affected = db_affected_rows();
		$returned = db_num_rows($res);
		
		ob_start();
		if($returned) {
			$num_fields = db_num_fields($res);
			?>
			<h3>Query returned <?php echo $returned; ?> rows, with <?php echo $num_fields; ?> fields in each.</h3>
			<table class="table table-striped table-bordered table-hover">
				<thead>
					<tr>
						<?php for($i = 0; $i < $num_fields; $i++) { ?>
							<th><?php echo db_field_name($res, $i) ?></th>
						<?php } ?>
					</tr>
				</thead>
				<tbody>
				<?php while($row = db_fetch_assoc($res)) { ?>
					<tr>
						<?php foreach($row as $name => $val){ ?>
							<td><?php echo htmlspecialchars($val); ?></td>
						<?php } ?>
					</tr>
				<?php } ?>
				</tbody>
			</table>
			<?php 
		}else{
			?>
			<h3><?php echo intval($affected); ?> rows affected by previous SQL query.</h3>
			<?php
		}
		
		return ob_get_clean();
	}