<?php

// config header

// ----IP's ----
$ServerSP = "192.168.1.111"; //The service processor of the server. This is not really common
 
$Server = "192.168.1.109"; // The ip of the server you want to control.

// ----Users ---

 
$SP_user = "apcros";

$main_user = "servers";

// ---Misc--
 
$name = "Jarvis"; // The name of your server




if((isset($_GET['func']))&&(function_exists($_GET['func']))) 
	{
	call_user_func($_GET['func']);
	}

function isup($ip, $port) {
	$socket = @fsockopen("$ip", "$port");
	if ($socket === false) {
		return false;
	} else {
		return true;
	}
}

function srvUP() {
	global $Server, $name;

	if (isup("$Server","22")) { 
		echo "<span class='label label-success'>Status : $name is ready to go !</span>"; //The server is considered booted if ssh is running
	} else {
		if (!srvPOWER())
		{
		echo "<span class='label label-danger'>Status : $name is dead :'(</span>";
		}
		else
		{
			echo "<span class='label label-info'>Status : $name is booting..</span>"; //The server is considered booting if ssh is NOT running BUT SP says that the server is getting power
		}
	}
}

function status($a) {
	if ($a) {
		echo "<span class='label label-success'>The server is up</span>";
	}  else {
		echo "<span class='label label-danger'>The server is down</span>";
	}
}

function gmodUP() {

	global $main_user, $Server;
	
	//Search for the screen named "gmod" and return a bootstrap label with the correct info

	$gmodAnswer = shell_exec("sudo ssh $main_user@$Server 'screen -ls gmod | tail -n 2' 2>&1"); 

	if ((fnmatch('*Socket in*', $gmodAnswer))) { //So dirty
		status(true);
	} else {
		status(false);
	}
}

function gmodSTART() {

	global $main_user, $Server;

	//Verify that gmod isn't launched, and execute the launching script on the server

	$gmodAnswer = shell_exec("sudo ssh $main_user@$Server 'screen -ls gmod | tail -n 2' 2>&1"); 
	if ((fnmatch('*Socket in*', $gmodAnswer))) {
			echo"<span class='label label-warning'>Error server is already launched</span>";
	} else {
			shell_exec("sudo ssh $main_user@$Server '~/scripts/gmodSTART.sh'");
	}

}

function gmodSTOP() {

	global $main_user, $Server;

	shell_exec("sudo ssh $main_user@$Server 'screen -X -S gmod quit'");
}

function srvSTART() {

	global $SP_user, $ServerSP;
	// If you do not have a SP on your server, you can exec a WOL command instead

	shell_exec("sudo ssh $SP_user@$ServerSP 'platform set power state on'");
}

function srvSTOP() {

	global $SP_user, $ServerSP;
	//Again, for thoses who don't have a SP you can just execute the "shutdown -h" cmd on the server.

	shell_exec("sudo ssh $SP_user@$ServerSP 'platform set power state off'");
}

function srvPOWER() {

	// Indicate if the server is getting power NOT if the server is booted. eg : if the server is in the BIOS, this will return true.
	global $SP_user, $ServerSP;
	$powerstate = shell_exec("sudo ssh $SP_user@$ServerSP 'platform get power state'");
	if (fnmatch('*On*',$powerstate))
	{
		return true;
	}
	else 
	{
		return false;
	}
}

function IDLed($id) {

	// This function is more for the SunFire v20z because it has an ID button to trigger a led.

	if($id == 1) {
		echo "<label class='label label-danger'>ID Led activated !</label><br>";
	} else {
		echo "<label class='label label-success'>Everything seems fine</label><br>";
	}
}

function srvSENSORS() {

	// Again, this is coded for the v20z, but on any other server I guess you can replace "sensor get" with "lm-sensors" and use preg_match as well.

	global $SP_user, $ServerSP;
	$sensor = shell_exec("sudo ssh $SP_user@$ServerSP 'sensor get'");
	preg_match('~ambienttemp(.*?)C~',$sensor,$ambient);
	preg_match('~cpu0.dietemp(.*?)C~',$sensor,$cpu0);
	preg_match('~cpu1.dietemp(.*?)C~',$sensor,$cpu1);
	preg_match('~fan1.tach(.*?)RPM~',$sensor,$fan1);
	preg_match('~fan2.tach(.*?)RPM~',$sensor,$fan2);
	preg_match('~fan3.tach(.*?)RPM~',$sensor,$fan3);
	preg_match('~fan4.tach(.*?)RPM~',$sensor,$fan4);
	preg_match('~fan5.tach(.*?)RPM~',$sensor,$fan5);
	preg_match('~fan6.tach(.*?)RPM~',$sensor,$fan6);
	preg_match('~hddbp.temp(.*?)C~',$sensor,$hdd);
	preg_match('~sp.temp(.*?)C~',$sensor,$sptemp);
	preg_match('~identifyswitch(.*?)Good/Fail~',$sensor,$IDl);
	echo "<h4> SP sensors : </h4>";
	echoBar("Ambient temp :", "$ambient[1]", " °C", "45");
	echoBar("SP temp :", "$sptemp[1]", " °C", "90");
	IDLed($IDl[1]);
	echo "<hr><h4> Main system sensors : </h4>";
	if(srvPOWER()) //Theses sensors returns "NA" if the server is not booted, so it's better to not show theses.
	{
			echoBar("Hard drive temp :","$hdd[1]", " °C", "85");
			echoBar("CPU0 temp :", "$cpu0[1]"," °C", "110");
			echoBar("CPU1 temp :", "$cpu1[1]"," °C", "110");
			echoBar("Fan 1 speed :", "$fan1[1]"," RPM", "10000");
			echoBar("Fan 2 speed :", "$fan2[1]"," RPM", "10000");
			echoBar("Fan 3 speed :", "$fan3[1]"," RPM", "10000");
			echoBar("Fan 4 speed :", "$fan4[1]"," RPM", "10000");
			echoBar("Fan 5 speed :", "$fan5[1]"," RPM", "10000");
			echoBar("Fan 6 speed :", "$fan6[1]"," RPM", "10000");
	} else 
	{
		echo "<label class='label label-warning'>Please switch on for more sensors</label>";
	}

}

function echoBar($title, $str, $unit, $max){
	$percent = floor((($str/$max)*100));
	if($unit == " RPM") 
		{
		if($percent<40) 
			{
				$color = "progress-bar-danger";
			} 
		else 
		{ 
			if ($percent >= 40 && $percent<80) 
			{
				$color = "progress-bar-success";
			} 
			else 
			{
				$color ="progress-bar-info"; 
			}
		}

	}
	else 
	{
		if($unit == " °C") 
		{
		if($percent<40) 
			{
				$color = "progress-bar-success";
			} 
		else 
		{ 
			if ($percent >= 40 && $percent<80) 
			{
				$color = "progress-bar-info";
			} 
			else 
			{
				$color ="progress-bar-danger"; 
			}
		}

	}
	}
	echo "<label>$title</label>";
	echo "<div class='progress'>";
  	echo "<div class='progress-bar $color' role='progressbar' aria-valuenow='$str' aria-valuemin='0' aria-valuemax='$max' style='width: ".$percent."%;'>";
    echo $str;
    echo $unit;
  	echo "</div>";
	echo "</div>";
}
?>