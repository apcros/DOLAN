<html>
	<head>
		<meta name="mobile-web-app-capable" content="yes">
		<link rel="shortcut icon" sizes="196x196" href="icon.png">
		<script type='text/javascript'>
function startG() {
	$('#php').load('monitor.php?func=servicePwr&srv=gmod&act=START');
}
function stopG() {
	$('#php').load('monitor.php?func=servicePwr&srv=gmod&act=STOP');
}
function startJ() {
	$('#php').load('monitor.php?func=srvSTART');
}
function stopJ() {
	$('#php').load('monitor.php?func=srvSTOP');
}
function toggleIDENTITY() {
	$('#php').load('monitor.php?func=toggleLED&id=identifyswitch');
}
function toggleFAULT() {
	$('#php').load('monitor.php?func=toggleLED&id=faultswitch');
}
</script>
		<title>D.O.L.A.N</title>
		<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
		<link rel="stylesheet" type="text/css" href="css/INCC.css">
		<meta name="viewport" content="width=device-width">
		<meta charset="UTF-8">
		<p><h4><b>D.O.L.A.N</b></h4>
		<i>Dirty Operational & Light Administration Nest</i></p>
				<ol><div id='mon2'class='lab1'><span class="label label-info">Loading...</span></div></ol>
				<ol><button class="btn btn btn-primary bt1" onclick="startJ()">Start</button><button class="btn btn btn-danger bt1" onclick="stopJ()">Stop</button></ol>
		<hr>
	</head>
	<div id='php' class='info'></div>
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
						<button class="btn btn-primary" onclick="startG()">Start</button>
						<button class="btn btn-danger" onclick="stopG()">Stop</button>
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
						<button class="btn btn-primary" onclick="startM()">Start</button>
						<button class="btn btn-danger" onclick="stopM()">Stop</button>
						</div>
					</div>
				</div>
			<div class="col-sm-6 col-md-4">
				<div class="thumbnail">
					<div class="caption">
						<h3>Sensors :</h3>
						<div id='mon3'>
							<div class="progress progress-striped active">
							  <div class="progress-bar progress-bar-info"  role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
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
						<div class="progress progress-striped active">
							  <div class="progress-bar progress-bar-info"  role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
							    <span class="sr-only">Loading system info..</span>
							    Loading system infos...(This may take a while.)
							  </div>
							</div>
						<div id="mon5"></div>
					</div>
				</div>
			</div>
			<div class="col-sm-6 col-md-4">
				<div class="thumbnail">
					<div class="caption">
						<h3>Leds :</h3>
						<button class="btn btn-warning" onclick="toggleIDENTITY()">Toggle Identify LED</button>
						<button class="btn btn-danger" onclick="toggleFAULT()">Toggle Fault LED</button>
						<hr>
						<div id="mon6">
								<div class="progress progress-striped active">
							  <div class="progress-bar progress-bar-info"  role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
							    <span class="sr-only">Loading leds status...</span>
							    Loading leds status... (This may take some times. Go get a coffee.)
							  </div>
							</div>

						</div>
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
$('#mon').load('monitor.php?func=servicePwr&srv=gmod&act=ISUP');
$('#mon2').load('monitor.php?func=srvUP');
$('#mon3').load('monitor.php?func=srvSENSORS');
$('#mon4').load('monitor.php?func=servicePwr&srv=minecraft&act=ISUP');
$('#mon5').load('monitor.php?func=srvINFOS');
$('#mon6').load('monitor.php?func=LEDSstatus');
}, 2000); // refresh every  milliseconds
</script>

</html>