<?php	
define('CSDIR', './');
$csdir = CSDIR;

//include_once(CSDIR . 'listen.class.php');
include_once(CSDIR . 'info.php');
include_once(CSDIR . 'hostel.class.php');

function srv_info ($srv_rules)
	{   	
	echo "<tr>
			<td>". $srv_rules['gameip']."</td>
			<td>".$srv_rules['hostel']."</td>
			<td>".$srv_rules['hostname']."</td>
			<td>".$srv_rules['mapname']."</td>
			<td>".$srv_rules['nowplayers']."/".$srv_rules['maxplayers']."</td>
			<td>".$srv_rules['sets']."</td>
			<td><a href='ping.php?ip=".$srv_rules['gameip']."'>Get Details</a></td>
		</tr>";
	
	}
	
$server =& new info;
$port = 27015;
$broadcast_string = "\xFF\xFF\xFF\xFFTSource Engine Query\x00";
$sock = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
socket_set_option($sock, SOL_SOCKET, SO_DEBUG, 1);


for($k=1;$k<=10;$k++)
	{
	for($j=1;$j<100;$j=$j+10)
		for($i=1;$i<255;$i++)
			{
			//$ip = $hostel_ip[$j].".".$i;
			$ip ="10.".$k.".".$j++.".".$i;
			socket_sendto($sock, $broadcast_string, strlen($broadcast_string), 0, $ip, $port);
			//echo $ip."<br/>";
			$ip ="10.".$k.".".$j.".".$i;
			
			socket_sendto($sock, $broadcast_string, strlen($broadcast_string), 0, $ip, $port);
			//echo $ip."<br/>";
			$j--;
			
			
			}
	if($k==1)
		$k=8;
	}

$from = "";
$p = 0;
$len = 1024;
$i = 0;
$flag=0;
$read[] = $sock;


while(socket_select($read, $write = NULL, $except = NULL, 2))
	{
	$input="";
	$i++;
	error_reporting (E_ALL ^ E_WARNING);
	socket_recvfrom($sock, $input, 80000, 0 ,$from,$p);
	if(strlen($input))
		{
		//echo "input ".$input;
		if($flag == 0)
			{
			echo '<table>'."\n";
			echo "<tr>
				<th>IP</th>
				<th>Hostel</th>
				<th>Server Name</th>
				<th>Map</th>
				<th>Players</th>
				<th>Password</th>
				<th></th>
			</tr>";
			}
		$flag = 1;
		$server->s_info['info'] = $input;
		$srv_rules  = $server->getrules($csdir);
		$srv_rules['map'] = $csdir . $srv_rules['map_path'] . '/' . $srv_rules['mapname'] . '.jpg';
		if (!(file_exists($srv_rules['map'])))
			{ 																// set default map if no picture found
			$srv_rules['map'] = $csdir . $srv_rules['map_path'] . '/' . $srv_rules['map_default'];
			}
		$srv_rules['hostel'] = hostel::query_srv($srv_rules['gameip']);		// get server hostel / location
		srv_info($srv_rules);
		}
	}
	
if($flag==0)
	{
	echo "No Server Running";
	}
else
	echo '</table>' . "";
//echo $i;

socket_close($sock);

?>