<?php 
/**
* Coin Dashboard by Christian Haschek
* https://blog.haschek.at
*
* Donations always welcome
*    BTC: 1ChrisHMgr4DvEVXzAv1vamkviZNLPS7yx
*    ETH: 0x1337C2F18e54d72d696005d030B8eF168a4C0d95
* 
* Read more at
*    https://blog.haschek.at/2017/making-an-awesome-dashboard-for-your-crypto.html
*/

//settings
define('INFLUX_IP','influxdb'); // The IP address of your InfluxDB instance
define('INFLUX_PORT',25826); // The UDP (!) port of your InfluxDB instance


//------------- Code starts here ------------------------//

// get BTC 

out = getBTCWorth();


function getBTCWorth() {
    $json = json_decode(file_get_contents('https://api.coinmarketcap.com/v1/ticker/bitcoin/',true));

    sendToDB('btc24hrvolume,value='.$json['24h_volume_usd']);
    sendToDB('btcpriceusd,value='.$json['price_usd']);

}

function sendToDB($data)
{
	$socket = stream_socket_client("udp://".INFLUX_IP.":".INFLUX_PORT."");
	stream_socket_sendto($socket, $data);
	stream_socket_shutdown($socket, STREAM_SHUT_RDWR);
}

