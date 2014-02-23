<html>
	<head>
		<meta name="mobile-web-app-capable" content="yes">
		<script type='text/javascript'>
function startG() {
	$('#php').load('monitor.php?func=gmodSTART');
}
function stopG() {
	$('#php').load('monitor.php?func=gmodSTOP');
}
function startJ() {
	$('#php').load('monitor.php?func=srvSTART');
}
function stopJ() {
	$('#php').load('monitor.php?func=srvSTOP');
}
</script>
<div id='php'></div>
		<title>D.O.L.A.N</title>
		<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
		<link rel="stylesheet" type="text/css" href="css/INCC.css">
		<meta name="viewport" content="width=device-width">
		<meta charset="UTF-8">
		<h4><b> Dirty Operational & Light Administration Nest (DOLAN)</b></h4>
				<ol><div id='mon2'><span class="label label-info">Loading...</span></div></ol>
				<ol><button class="btn label label-info" onclick="startJ()">Start</button><button class="btn label label-warning" onclick="stopJ()">Stop</button></ol>
		<hr>
	</head>
	<body>
		<div class="row">
			<div class="col-sm-6 col-md-4">
				<div class="thumbnail">
					<div class="caption">
						<h3>Garry's Mod</h3>
						<p><b> Server status : </b></p>
						<div id="mon">
							<span class="label label-info">Loading...</span>
						</div>
						<br>
						<p><b> Server control : </b></p>
						<button class="btn btn-success" onclick="startG()">Start</button>
						<button class="btn btn-warning" onclick="stopG()">Stop</button>
					</div>
				</div>
			</div>
			<div class="col-sm-6 col-md-4">
				<div class="thumbnail">
					<div class="caption">
						<h3>Minecraft</h3>
							<p><b> Server status : </b></p>
						<div id="mon4">
							<span class="label label-info">Loading...</span>
						</div>
						<br>
						<p><b> Server control : </b></p>
						<button class="btn btn-success" onclick="startM()">Start</button>
						<button class="btn btn-warning" onclick="stopM()">Stop</button>
						</div>
					</div>
				</div>
			<div class="col-sm-6 col-md-4">
				<div class="thumbnail">
					<div class="caption">
						<h3>Sensors :</h3>
						<div id='mon3'>
							<div class="progress progress-striped active">
							  <div class="progress-bar progress-bar-warning"  role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
							    <span class="sr-only">Loading sensors...</span>
							    Loading sensors...(This may take a while.)
							  </div>
							</div>
						</div>
						</div>
					</div>
				</div>
					<div class="col-sm-6 col-md-4">
				<div class="thumbnail">
					<div class="caption">
						<h3>Main system infos :</h3>
					</div>
				</div>
			</div>
		</div>
	</body>
<script src='https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js'></script>
<script type='text/javascript'>
var auto_refresh = setInterval(
function ()
{
$('#mon').load('monitor.php?func=gmodUP');
$('#mon2').load('monitor.php?func=srvUP');
$('#mon3').load('monitor.php?func=srvSENSORS');
}, 2000); // refresh every  milliseconds
</script>

</html>