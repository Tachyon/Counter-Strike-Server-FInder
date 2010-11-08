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
            <div class="header">
            </div>
            <div class="navigation">
                <a href ="index.php">Scan Lan</a>&nbsp;&nbsp;&nbsp;&nbsp; <a href ="ping.html">Ping a Server</a>&nbsp;&nbsp;&nbsp;&nbsp;
                <a href ="rcon.php">Control a Server</a>
            </div>
            <div class="content">
	<?php
	define('PHGDIR', './');
	$phgdir = PHGDIR;
	$flag=0;
	include_once(PHGDIR . 'listen.class.php');
	include_once(PHGDIR . 'query.class.php');
	include_once(PHGDIR . 'hostel.class.php');
	include_once(PHGDIR . 'includes/daytime.inc.php');    // time function
	include_once(PHGDIR . 'includes/dns.inc.php');        // dns function
	
	$server = query::query_srv('hl');
	
	function srv_info ($sh_srv, $srv_rules, $use_file, $use_bind, $only, $phgtable)
		{   			// html: table to show server infos
			echo '<center><table border="0" cellpadding="2" cellspacing="2" width="' . $phgtable . '">' . "\n";
			
			// html: hostname, hostel and daytime 
			echo '<tr>' . '<th colspan="2">'
			.'Server Name :'. $srv_rules['hostname'] . '<br/>' 
			. 'Server Hostel :'.$srv_rules['hostel'] . ' Hostel' 
			. '</th>' . '</tr>' . "\n";
						
			// html: adress, game, gametype, mapname, players, privileges
			echo '<tr><td>' . "\n"
			. '<table border="0" cellpadding="3" cellspacing="0">' . "\n"
			. '<tr valign="top"><td align="left">IP:</td><td align="left">'         . $srv_rules['adress']     . '</td></tr>' . "\n"
			. $srv_rules['htmldetail'] 
			. '</table></td>' . "\n";
			
			// html: map picture
			echo '<td width="60%" align="center">' . "\n"
			. '<img alt="' . $srv_rules['mapname'] . '" src="' . $srv_rules['map'] . '" border="0">' . "\n"
			. '</td>' . "\n"
			. '</tr>' . "\n";

			// html: close info table
			echo '</table>' . "\n";
			echo '<a href="ping.php?ip='.substr($srv_rules['adress'],0,strlen($srv_rules['adress'])-6).'">Get Details</a>';
			echo '</center>';

		}
	
	$port = 27015;
	$broadcast_string = "\xFF\xFF\xFF\xFFTSource Engine Query\x00";
	$sock = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
	
	socket_set_option($sock, SOL_SOCKET, SO_BROADCAST, 1);
	
	socket_sendto($sock, $broadcast_string, strlen($broadcast_string), 0, '255.255.255.255', $port);
	//socket_sendto($sock, $broadcast_string, strlen($broadcast_string), 0, '10.9.0.255', $port);
	
	//socket_sendto($sock, $broadcast_string, strlen($broadcast_string), 0, '10.8.11.255', $port);
	//socket_sendto($sock, $broadcast_string, strlen($broadcast_string), 0, '10.8.51.255', $port);
	
	sleep(5);
	
	$from = "";
	$p = 0;
	$len = 1024;
	$i = 0;
	$read[0] = $sock;
	
	$input = 'No Server found';
	while(socket_select($read, $write = NULL, $except = NULL, 2))
		{
		$flag = 1;
		socket_recvfrom($sock, $input, 80000, 0 ,$from,$p);
		$server->s_info['info'] = $input;
		$srv_rules  = $server->getrules($phgdir);
		$srv_rules['map'] = $phgdir . $srv_rules['map_path'] . '/' . $srv_rules['mapname'] . '.jpg';
		if (!(file_exists($srv_rules['map'])))
			{ // set default map if no picture found
			$srv_rules['map'] = $phgdir . $srv_rules['map_path'] . '/' . $srv_rules['map_default'];
			}
		$srv_rules['time']= daytime(); 	// get server day time
		$srv_rules['hostel'] = hostel::query_srv($srv_rules['gameip']);		// get server hostel / location
		//$srv_rules['adress'] = $host.':' . $port;	// get server adress
		$srv_rules['adress'] =  $srv_rules['gameip'] ;
		srv_info($sh_srv, $srv_rules, $use_file, $use_bind, 1, 40);
		echo '<br/>'.'<br/>'.'<br/>';
		}
	if($flag==0)
		{
		echo "No Server Running";
		}
	socket_close($sock);
	
	?>
	</div>
        </div>
    </div>
  </body>
</html>
