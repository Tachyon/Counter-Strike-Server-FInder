<?php 
class listen
{ 
    function start()
		{
		// Server IP address
		$address = '10.9.20.205';

		// Port to listen
		$port = 27005;

		$mysock = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);

		socket_bind($mysock,$address, $port) or die('Could not bind to address');
		socket_listen($mysock, 5);
		$client = socket_accept($mysock);
		
		// read 1024 bytes from client
		$input = socket_read($client, 1024);

		// write received data to the file
		writeToFile('abc.txt', $input);

		socket_close($client);
		socket_close($mysock);
		return $input;
		}
	
	function writeToFile($strFilename, $strText)
	{
		  if($fp = @fopen($strFilename,"w "))
		 {
			  $contents = fwrite($fp, $strText);
			  fclose($fp);
			  return true;
		  }else{
			  return false;
		  }

	  } 
}
?>