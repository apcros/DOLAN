<?php

// config header

// ----IP's ----
$ServerSP = "192.168.1.92"; //The service processor of the server. This is not really common
 
$Server = "192.168.1.109"; // The ip of the server you want to control.

// ----Users ---

 
$SP_user = "apcros";

$main_user = "servers";

// ---Misc--
 
$name = "Jarvis"; // The name of your server (Not used that much)

// ---Function list ([GFX] is for functions that return html code for graphics purpose) ---
/**
isup($ip : String , $port : String) - return true if the port linked to the ip is up

srvUP() - [GFX] return bootstrap label with state of the server

toggleLED() - Toggle the specified led (identifyswitch or faultswitch). id var come from GET


status($a : boolean) - [GFX] return a label danger or success. 


servicePWR() - Start/Stop or return the state of the service named with 'srv' var. The action is identified with 'act' var (START/STOP or ISUP). Vars come from GET

srvSTART() - Power the server on by using the SP

srvSTOP() - Stop the server

srvPOWER() - return true if the server is getting power. NOT if the server is booted

IDled()  - [GFX] return a bootstrap label with status of the Identify LED

FAULTled()  - [GFX] return a bootstrap label with status of the Fault LED

LEDStatus() - Just a call to IDled() and FAULTled()

srvSENSORS() - Get sensors, Parse and display them (with echoBar())

echoBar($title : String , $str : integer, $unit : string, $max : integer) - [GFX] Display a bootstrap progress bar with 'title' as title, 'str' as current value, 'unit' as the unit (Just displayed. Dissocied from title to easily process data. Ex : low RPM must be displayed as a warning, and low temp as a success)
Max is the maximum value.



**/


if((isset($_GET['func']))&&(function_exists($_GET['func'])))   //Called every time	
	{
	call_user_func($_GET['func']);
	}


function isup($ip, $port) {
	
	$socket = @fsockopen("$ip", "$port");
	
	if ($socket === false) {
		
		return false;
	
	} 
	else {
		
		return true;
	
	}
}

function srvUP() {
	
	global $Server, $name;

	if (isup("$Server","22")) { 
		
		echo "<span class='label label-success'>Status : $name is ready to go !</span>"; //The server is considered booted if ssh is running
	
	} 
	else {
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

function toggleLED() {

	global $SP_user, $ServerSP;

	if(isset($_GET['id'])) {
		
		$sensor = shell_exec("ssh $SP_user@$ServerSP 'sensor get'");
		$id = $_GET['id'];
		
		if ($id == 'identifyswitch') {
			
			preg_match('~identifyswitch(.*?)Good/Fail~',$sensor,$IDl);
		
		}
		if ($id == 'faultswitch') {
			
			preg_match('~faultswitch(.*?)Good/Fail~',$sensor,$IDl);
		
		}
		
		if ($IDl[1] == 1) {
			
			shell_exec("ssh $SP_user@$ServerSP 'sensor set -i $id -v off'");
		
		} 
		else {
			
			shell_exec("ssh $SP_user@$ServerSP 'sensor set -i $id -v on'");
		
		}

	}
}


function status($a) {
	if ($a) {
		
		echo "<span class='label label-success'>The server is up</span>";
	
	}  
	else {
		
		echo "<span class='label label-danger'>The server is down</span>";
	}
}




function servicePWR() {
	
	global $main_user, $Server;
	
	if(isset($_GET['srv']) && isset($_GET['act'])){

			$srv = $_GET['srv'];
			$act = $_GET['act'];

			if ($srv == 'gmod') {
				
				if($act == 'START') {

					$gmodAnswer = shell_exec("ssh $main_user@$Server 'screen -ls gmod | tail -n 2' 2>&1"); 
					
					if ((fnmatch('*Socket in*', $gmodAnswer))) {
							
							echo"<span class='label label-warning'>Error server is already launched</span>";
					
					} 
					else {
							
							shell_exec("ssh $main_user@$Server '~/scripts/gmodSTART.sh'");
					
					}

				}
				
				if($act == 'STOP') {

					shell_exec("ssh $main_user@$Server 'screen -X -S gmod quit'");

				}

				if($act == 'ISUP') {

					$gmodAnswer = shell_exec("ssh $main_user@$Server 'screen -ls gmod | tail -n 2' 2>&1"); 

					if ((fnmatch('*Socket in*', $gmodAnswer))) { //So dirty
						
						status(true);
					
					} 
					else {
						
						status(false);
					
					}

				}
			}

			if ($srv == 'minecraft') {
				
				if($act == 'START') {

				}
				
				if($act == 'STOP') {

				}

				if($act == 'ISUP') {
					
				}
			}
	}
}


function srvSTART() {

	global $SP_user, $ServerSP;
	// If you do not have a SP on your server, you can exec a WOL command instead

	shell_exec("ssh $SP_user@$ServerSP 'platform set power state on'");
}

function srvSTOP() {

	global $SP_user, $ServerSP;
	//Again, for thoses who don't have a SP you can just execute the "shutdown -h" cmd on the server.

	shell_exec(" ssh $SP_user@$ServerSP 'platform set power state off'");
}

function srvPOWER() {

	// Indicate if the server is getting power NOT if the server is booted. eg : if the server is in the BIOS, this will return true.
	global $SP_user, $ServerSP;
	
	$powerstate = shell_exec("ssh $SP_user@$ServerSP 'platform get power state'");
	
	if (fnmatch('*On*',$powerstate))
	{
		return true;
	}
	else 
	{
		return false;
	}
}

function IDLed() {

	// This function is more for the SunFire v20z because it has an ID button to trigger a led.
	global $SP_user, $ServerSP;
	
	$sensor = shell_exec("ssh $SP_user@$ServerSP 'sensor get'");
	preg_match('~identifyswitch(.*?)Good/Fail~',$sensor,$id);

	if($id[1] == 1) {
		
		echo "<label class='label label-warning'>Identify LED activated</label><br>";
	
	} 
	else {
		
		echo "<label class='label label-info'>Identify LED not activated</label><br>";
	
	}
}

function FAULTLed() {

	// This function is more for the SunFire v20z because it has a fault led

	global $SP_user, $ServerSP;
	
	$sensor = shell_exec("ssh $SP_user@$ServerSP 'sensor get'");
	preg_match('~faultswitch(.*?)Good/Fail~',$sensor,$id);

	if($id[1] == 1) {
		
		echo "<br><label class='label label-danger'>Fault LED activated</label><br>";
	
	} 
	else {
		
		echo "<br><label class='label label-info'>Fault LED not activated</label><br>";
	
	}
}

function LEDSstatus() {
	//Just a call to ID LED and FAULT, just for graphics purpose

	IDLed();
	FAULTLed();
}

function srvSENSORS() {

	// Again, this is coded for the v20z, but on any other server I guess you can replace "sensor get" with "lm-sensors" and use preg_match as well.

	global $SP_user, $ServerSP;
	
	$sensor = shell_exec("ssh $SP_user@$ServerSP 'sensor get'");
	
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
	
	} 
	else 
	{

		echo "<label class='label label-primary'>Please switch on for more sensors</label>";
	
	}

}

function echoBar($title, $str, $unit, $max){
	
	$percent = floor((($str/$max)*100));
	
	if($unit == " RPM") {
		
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