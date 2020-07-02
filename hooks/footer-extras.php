<?php
	$script_name = basename($_SERVER['PHP_SELF']);
	if($script_name == 'index.php' && isset($_GET['signIn'])){
			?>
			<style>
					body{
							background: url("images/2707.jpg") no-repeat fixed center center / cover;
					}				
			</style>
			<div class="alert alert-success" id="benefits">
				Benifits of becoming a member:
					<ul>
						<li> You'll be awsome!
						<li> We'll be richer!
				</ul>
				</div>
				<script>
						$j(function(){
								$j('#benefits').appentTo('#login_splash');

						})
			<?php
	}
?>

<nav class="navbar navbar-default navbar-fixed-bottom">
<div class="container">
<p class="navbar-text"><small>
						Powered by <a class="navbar-link" href="https://bigprof.com/appgini/" target="_blank">BigProf AppGini 5.82 </a>
					</small>

<?php
date_default_timezone_set("America/Los_Angeles");
echo "-  The time is " . date("h:i:sa");
?></p>
</div>
</nav>;
