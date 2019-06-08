<?php
include 'php/useragent.class.php';
require_once 'php/reader.php';
$useragent = UserAgentFactory::analyze($_SERVER['HTTP_USER_AGENT']);
/*
   Cache whole database into system memory and share among other scripts & websites
   WARNING: Please make sure your system have sufficient RAM to enable this feature
*/
// $db = new \IP2Location\Database('./databases/IP-COUNTRY-SAMPLE.BIN', \IP2Location\Database::SHARED_MEMORY);

/*
   Cache the database into memory to accelerate lookup speed
   WARNING: Please make sure your system have sufficient RAM to enable this feature
*/
// $db = new \IP2Location\Database('./databases/IP-COUNTRY-SAMPLE.BIN', \IP2Location\Database::MEMORY_CACHE);


/*
	Default file I/O lookup
*/
if (!empty($_SERVER["HTTP_CLIENT_IP"])){
//check for ip from share internet
$ip = $_SERVER["HTTP_CLIENT_IP"];}
elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
// Check for the Proxy User
$ip = $_SERVER["HTTP_X_FORWARDED_FOR"];}
else{
$ip = $_SERVER["REMOTE_ADDR"];}
// This will print user's real IP Address
// does't matter if user using proxy or not.
if(filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
$db = new \IP2Location\Database('src/ipv4/ipv4.bin', \IP2Location\Database::MEMORY_CACHE);}
else {
$db = new \IP2Location\Database('src/ipv6/ipv6.bin', \IP2Location\Database::MEMORY_CACHE);}
$records = $db->lookup($ip, \IP2Location\Database::ALL);

echo '<pre>';
echo 'User Agent            : ' . $useragent->useragent . "\n";
echo 'System                : ' . $useragent->os['title'] . "\n";
echo 'Browser               : ' . $useragent->browser['title'] . "\n";
echo 'IP Version            : ' . $records['ipVersion'] . "\n";
echo 'IP Address            : ' . $records['ipAddress'] . "\n";
echo 'Country Code          : ' . $records['countryCode'] . "\n";
echo 'Country Name          : ' . $records['countryName'] . "\n";
echo 'Region Name           : ' . $records['regionName'] . "\n";
echo 'City Name             : ' . $records['cityName'] . "\n";
echo 'Latitude              : ' . $records['latitude'] . "\n";
echo 'Longitude             : ' . $records['longitude'] . "\n";
echo 'Time Zone             : ' . $records['timeZone'] . "\n";
echo 'ZIP Code              : ' . $records['zipCode'] . "\n";
echo 'ISP Name              : ' .gethostbyaddr($ip) . "\n";
echo '</pre>';
?>