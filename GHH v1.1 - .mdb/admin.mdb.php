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
//GHH Honeypot by GHH project for GHDB Signature #162 (allinurl: admin mdb)
////////////////////////////////////////////////////////
$HoneypotName = "ADMIN.MDB"; //This name should be unique if reporting multiple honeypots to one file or database. Helps determine what honeypot made a log.

//Random File size
$length = mt_rand(500000,800000);

for($i = 0; $i < $length; $i++)
	randomHex();

function randomHex(){
echo chr(mt_rand(0,127));
}

//Find our target in the referrer site
if (strstr($Attack['referer'], "admin") || strstr($Attach['referer'], "mdb"){
 $Signature[] = "Target in URL";
}

//Finds if exact GHDB signature was used
if (strstr ($Attack['referer'], "allinurl%3A+admin+mdb")){
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
