<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
  <head>
    <!-- What Are you looking here  ?? You Looser !! -->
    <title>Server Finder</title>
    <link href="Styles/style.css"rel="stylesheet" type="text/css" />
  </head>
  <body>
  	<div>
        <div id="wrapper">
            <div id="header">
				<img src="images/logo.png"/>
            </div>
            <div id="navigation">
                <a href ="index.php">Scan Lan</a><a href ="ping.html">Ping a Server</a><a href ="rcon.php">Control a Server</a>
            </div>
            <div id="ping">
    <?php
		define('CSDIR', './');
		$csdir = CSDIR;		$host = $_REQUEST['ip'];
		include_once(CSDIR . 'hostel.class.php');
		include_once(CSDIR . 'info.php');
			
		$hostel = hostel::query_srv($host);
			
		function info($csdir,$host,$hostel) 
			{
			$stats = & new info;
			$server = $stats->getstream($host, 27015, 27005);

			if ($server === true) 								// get the server rules
				{
				$srv_rules  = $stats->getrules($csdir);
				$srv_rules['playerlist'] = $stats->getplayers();  
				//$stats->getsettings();
				//$srv_rules['rules'] = $stats->getrules();
																										// full path to the map picture
				$srv_rules['map'] = $csdir . $srv_rules['map_path'] . '/' . $srv_rules['mapname'] . '.jpg';
				if (!(file_exists($srv_rules['map'])))
					{ 																					// set default map if no picture found
					$srv_rules['map'] = $csdir . $srv_rules['map_path'] . '/' . $srv_rules['map_default'];
					}
				}
			else
				{
				$msg = 'No Response';
				$srv_rules['playerlist'] = '';
				$srv_rules['hostname']    = $msg;
				$srv_rules['gamename']    = $msg . "<br>";
				$srv_rules['map']         = $csdir . 'maps/no_response.jpg';
				$srv_rules['mapname']     = 'no response';
				$srv_rules['sets']        = '-';
				$srv_rules['htmlinfo']    = '<tr valign="top"><td align="left">No</td><td align="left">Response</td></tr>' . "\n";
				$srv_rules['htmldetail'] = '<tr valign="top"><td align="left">No</td><td align="left">Response</td></tr>' . "\n";
				}
			$srv_rules['hostel'] = $hostel;						// get server hostel / location
			$srv_rules['adress'] = $host;						// get server adress
			return $srv_rules;
			}
			
		function srv_info ($srv_rules,$phgtable)
			{   
			$bar = '<table border="0" cellpadding="2" cellspacing="2" width="750px">'.'<tr><th colspan="2">' . '<a href="ping.php?ip='.$_REQUEST['ip'].'">Refresh</a></th></tr></table>' . "\n";

			// html: table to show server infos
			echo '<table border="0" cellpadding="2" cellspacing="2" width="750px">' . "\n";
			
			// html: hostname, hostel and daytime 
			echo '<tr>' . '<th colspan="2">'
			.'Server Name :'. $srv_rules['hostname'] . '<br>' 
			. 'Server Hostel :'.$srv_rules['hostel'] . ' Hostel' 
			//.', '. $srv_rules['time'] 
			. '</th>' . '</tr>' . "\n";
			
			// html: adress, game, gametype, mapname, players, privileges
			echo '<tr><td>' . "\n"
			. '<table border="0" cellpadding="3" cellspacing="0">' . "\n"
			. '<tr valign="top"><td align="left">IP:</td><td align="left">'         . $srv_rules['adress']     . '</td></tr>' . "\n"
			. $srv_rules['htmldetail'] 
			. '</table></td>' . "\n";
			
			// html: map picture
			echo '<td width="60%" align="right">' . "\n"
			. '<img alt="' . $srv_rules['mapname'] . '" src="' . $srv_rules['map'] . '" border="0">' . "\n"
			. '</td>' . "\n"
			. '</tr>' . "\n";

			// html: close info table
			echo '</table>' . "\n";
			
		
			echo '</table>';
			
			echo '<br/><br/><br/>';
			
			echo '<table border="0" cellpadding="2" cellspacing="2" width="50%">' . "\n";		// html: open playerlist table
			echo '<tr><td><table>';
			echo $srv_rules['playerlist'];														// html: playerlist
			echo '</table></td><td width="30%" align="center">'.$bar.'</td></tr>';
			echo '</table>';																	// html: close playerlist table
			
		}

			$srv_rules = info($csdir,$host,$hostel);
			srv_info($srv_rules,"100%");
				
			?>
			</div>
			<div id="footer">
				<?php
				echo '<a href="http://10.9.20.205" target="_blank">Hosted by Tachyon</a><br/><font color="'
					. '#hwiuh'
					. '"> 10.9.20.205 </font>'
					. '<br/><a href="http://saketsaurabh.co.cc/" target="_blank">saketsaurabh.co.cc</a>';
				?>
			</div>
        </div>
    </div>
  </body>
</html>






