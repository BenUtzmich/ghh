<?php
/////////////////////////////////////////////////////////
//Google Hack Honeypot v1.1
//Honeypot File
//http://ghh.sourceforge.net - many thanks to SourceForge
/////////////////////////////////////////////////////////
//Copyright (C) 2005 GHH Project
//
//This program is free software; you can redistribute it and/or modify 
//it under the terms of the GNU General Public License as published by 
//the Free Software Foundation; either version 2 of the License, or 
//(at your option) any later version.
//
//This program is distributed in the hope that it will be useful, 
//but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY 
//or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License 
//for more details.
//
//You should have received a copy of the GNU General Public License along 
//with this program; if not, write to the 
//Free Software Foundation, Inc., 
//59 Temple Place, Suite 330, 
//Boston, MA 02111-1307 USA
////////////////////////////////////////////////////////

////////////////////////////////////////////////////////
//Begin Configuration Section
////////////////////////////////////////////////////////

//Enter the path to the GHH global configuration file
$ConfigFile = '';
//Enter the URL of the page that links to this honeypot (I.E http://yourdomain.com/forums/index.php, Wherever you put your transparent link to the honeypot.)
$SafeReferer = '';

////////////////////////////////////////////////////////
//End Configuration Section
////////////////////////////////////////////////////////

////////////////////////////////////////////////////////
//Housekeeping Section
//Include config, disable the header protection, init variables, stealth the errors.
////////////////////////////////////////////////////////
error_reporting(0);
$Honeypot = true;
include($ConfigFile);
////////////////////////////////////////////////////////
//End housekeeping section
////////////////////////////////////////////////////////

//Attack Acquisition Section
$Attack = getAttacker();

//Determine Standard Signatures
$Signature = standardSigs($Attack, $SafeReferer);

////////////////////////////////////////////////////////
//Begin Custom Honeypot Section
//GHH Honeypot by GHH project for GHDB Signature # (inurl:passlist.txt)
//Dumps a typical interesting file.
////////////////////////////////////////////////////////
$HoneypotName = "PASSLIST"; //This name should be unique if reporting multiple honeypots to one file or database. Helps determine what honeypot made a log.

//pass.txt Generator

usernamePlainText();

//Username:PlaintextPass
function usernamePlainText(){
$size = rand(1,20);
$goodies = array();
	for($i =0; $i < $size; $i++){
	$string = DumpPassword();
	$goodies[] = DumpUser() . ":" . $string . "\n";
	}
shuffle($goodies);

for ($i=0;$i<count($goodies);$i++)
echo $goodies[$i];
}

function DumpUser(){
	//0 for generated, 1 for popular
	$a = rand(0,1);
	$Length = rand(5,9);
	$vowels = array('a','e', 'i', 'o', 'u', 'y');
	$pass = '';
	//Generate name
		if ($a == 0){
				for($i=0;$i<$Length;$i++){
					$pass .= chr(rand(97,122));
					$pass .= $vowels[rand(0,5)];
				}
		return $pass;
		}
	//Typical users
		if ($a == 1){
			$names = array('root', 'admin', 'administrator');
			return $names[rand(0,sizeof($names)-1)];
		}
}
function DumpPassword(){
	//Type of password - 0 MD5, 1 easy plaintext, 2 ugly plaintext
	//To remove MD5 output, change to $Type = rand(1,2);
	$Type = rand(0,2);

		if ($Type == 0){
	
		$MD5 = array(0,1,2,3,4,5,6,7,8,9,'a','b','c','d','e','f');
		$pass = '';
			for ($i=0;$i<32;$i++){
				$pass .= $MD5[rand(0,15)];
			}
		return $pass;
		}

		//Easy plaintext Passwords!
		if ($Type == 1){
	
			$EZPass = array('admin', 'Admin', 'ADMIN', 'administrator', 'ADMINISTRATOR', 'root', '1234', '11111', 'pass', 'passwd', 'password');
			return $EZPass[rand(0,sizeof($EZPass)-1)];
		}

		//Hard plaintext passwords!
		if ($Type == 2){

			$Length = rand(4,10);
			$pass = '';
			$a = rand(0,5);

				if($a==0){
					for($i=0;$i<$Length;$i++){
					$pass .= chr(rand(48,57));
				}
		}
		
			if($a==1){
				for($i=0;$i<$Length;$i++){
					$pass .= chr(rand(65,90));
				}
			}
	
			if($a==2){
				for($i=0;$i<$Length;$i++){
					$pass .= chr(rand(97,122));
				}
			}
			if($a==3){
				for($i=0;$i<$Length;$i++){
					$pass .= chr(rand(65,122));
				}
			}
			if($a==4){
				for($i=0;$i<$Length;$i++){
					$pass .= chr(rand(48,90));
				}
			}
			if($a==5){
				for($i=0;$i<$Length;$i++){
					$pass .= chr(rand(48,122));
				}
			}
		
	return $pass;
}
}//End Dump Pass Function

//Find our target in the referrer site
if (strstr($Attack['referer'], "passlist")){
 $Signature[] = "Target in URL";
}

//Finds if exact GHDB signature was used
if (strstr ($Attack['referer'], "inurl%3Apasslist.txt")){
 $Signature[] = "GHDB Signature!";
}
////////////////////////////////////////////////////////
//End Custom Honeypot Section
////////////////////////////////////////////////////////

////////////////////////////////////////////////////////
//Begin Logging Section
////////////////////////////////////////////////////////

writeLog($Owner, $HoneypotName, $DateTime, $Attack, $Signature, $LogType, $Filename, $DBName, $DBUser, $DBPass, $Server);

////////////////////////////////////////////////////////
//End Logging Section
////////////////////////////////////////////////////////
//End of template.php
////////////////////////////////////////////////////////
?>
