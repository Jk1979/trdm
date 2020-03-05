<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);
//The username or email address of the account.

require_once 'vendor/autoload.php';
require_once 'helpers.php';
use App\Main;

$coockiefile= 'coockie.txt';

$proxyar = [
'178.128.108.61'=>'8080',
'113.53.225.66'=>'8080',
'181.112.56.2'=>'53281',
'212.92.204.54'=>'80',
'196.6.232.32'=>'8080',
'186.64.121.235'=>'8080'
];
//xprint($html,'$html');
// phpQuery::newDocument($html);
// $title = pq('title')->html();
//
// xd($title);
// php_query::unloadDocuments();


//The username or email address of the account.
define('USERNAME', 'info@domplitki.com.ua');

//The password of the account.
define('PASSWORD', 'jkalex1979');

//Set a user agent. This basically tells the server that we are using Chrome ;)
define('USER_AGENT', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/35.0.2309.372 Safari/537.36');

//Where our cookie information will be stored (needed for authentication).
define('COOKIE_FILE', 'cookie.txt');

//URL of the login form.
define('LOGIN_FORM_URL', 'http://webshop2.agromat.ua/login/index');
define('LOGIN_REQUEST_URL', 'http://webshop2.agromat.ua/catalog');


//Login action URL. Sometimes, this is the same URL as the login form.
define('LOGIN_ACTION_URL', 'http://webshop2.agromat.ua/login/index');


//An associative array that represents the required form fields.
//You will need to change the keys / index names to match the name of the form
//fields.
$postValues = array(
    'username' => USERNAME,
    'password' => PASSWORD,
    'singin'=>''
);


$err=false;
foreach($proxyar as $host => $port){
    //Initiate cURL.
    $curl = curl_init();
    
    //Set the URL that we want to send our POST request to. In this
    //case, it's the action URL of the login form.
    curl_setopt($curl, CURLOPT_URL, LOGIN_ACTION_URL);
    
    //Tell cURL that we want to carry out a POST request.
    curl_setopt($curl, CURLOPT_POST, true);
    
    //Set our post fields / date (from the array above).
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postValues));
    
    //We don't want any HTTPS errors.
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    
    //Where our cookie details are saved. This is typically required
    //for authentication, as the session ID is usually saved in the cookie file.
    curl_setopt($curl, CURLOPT_COOKIEJAR, COOKIE_FILE);
    
    //Sets the user agent. Some websites will attempt to block bot user agents.
    //Hence the reason I gave it a Chrome user agent.
    curl_setopt($curl, CURLOPT_USERAGENT, USER_AGENT);
    
    //Tells cURL to return the output once the request has been executed.
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    
    //Allows us to set the referer header. In this particular case, we are
    //fooling the server into thinking that we were referred by the login form.
    curl_setopt($curl, CURLOPT_REFERER, LOGIN_FORM_URL);
    
    //Do we want to follow any redirects?
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_PROXY,$host.':'.$port);
    curl_setopt($curl, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
    curl_setopt($curl, CURLOPT_TIMEOUT, 15);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
    //Execute the login request.
    curl_exec($curl);
    //Check for errors!
    if(curl_errno($curl)){
        // throw new Exception(curl_error($curl));
        // echo curl_error($curl);
        // die;
        $err = true;
    }
    if(!$err) break;
}

if($err){
    // throw new Exception(curl_error($curl));
    echo curl_error($curl);
    die;
}

//$requestUrl = 'http://webshop2.agromat.ua/catalog?ajax_req=1&warehouse=61053&CODE='.$code.'&numenlkature=1&table_length=10';
$requestUrl = 'http://webshop2.agromat.ua/catalog?ajax_req=1&warehouse=61053&CODE='.$code.'&numenlkature=1&table_length=10';

//We should be logged in by now. Let's attempt to access a password protected page
$postdata = [
    'url'=> $requestUrl,
    'is_edit'=>0,
    'is_order'=>0
];
curl_setopt($curl, CURLOPT_URL, LOGIN_REQUEST_URL);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postdata));


//Use the same cookie file.
curl_setopt($curl, CURLOPT_COOKIEJAR, COOKIE_FILE);

//Use the same user agent, just in case it is used by the server for session validation.
curl_setopt($curl, CURLOPT_USERAGENT, USER_AGENT);

//We don't want any HTTPS / SSL errors.
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

//Execute the GET request and print out the result.
$datahtml = json_decode(curl_exec($curl));
//print_r($datahtml);
//echo '<hr>';
if($datahtml->search == 'empty') $avail =  'Нет в наличии';
phpQuery::newDocument($datahtml->search);
$avail2 = 0;
$price = pq('tr:eq(0)')->find('td:eq(9)'); //('tbody > tr > td:eq(2)');
if(intval(pq($price)->text())== 0) {
    $p2 = pq('tr:eq(1)')->find('td:eq(9)');
    if(pq($p2)->text()) $avail2 = pq($p2)->text();
}

if(!$avail2) $avail =  pq($price)->text();
else $avail = $avail2;
