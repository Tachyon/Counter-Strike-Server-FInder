<?php 
class hostel
	{ 
    function query_srv($ip)
		{
		//echo " ip ".$ip;
		$len = strlen($ip);
		do
			{
			$char = substr($ip,$len-1,1);
			$len--;
			//echo $char." ";
			}while($char != ".");
		$ip_range = substr($ip,0,$len);
		
		switch ($ip_range)
			{    
			case '10.8.1':  
			case '10.8.0': 
				return 'Limbdi';break;
			case '10.8.10':
			case '10.8.11': 
				return 'De'; break;
			case '10.8.20':
			case '10.8.21': 
				return 'Vivekanand'; break;
			case '10.9.20': 
			case '10.9.21': 
				return 'Morvi'; break;
			case '10.9.20': return 'Morvi'; break;
			case '10.8.41': return 'Rajputana'; break;
			case '10.8.40': return 'Rajputana'; break;
			case '10.8.51': return 'Visvesaraiya'; break;
			case '10.8.61': return 'IT-Girls'; break;
			case '10.9.11': return 'Dhanrajgiri'; break;
			case '10.9.1': return 'Raman'; break;
			case '10.8.31': return 'Vishwakarma';break;
			default: return "Non IT";
			}
		}
	}
?>
