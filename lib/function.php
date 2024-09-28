<?php
include("simple_html_dom.php");

function filter($data){
    $filter = stripslashes(strip_tags(htmlspecialchars(htmlentities($data,ENT_QUOTES))));
    return $filter;
}

function enc_id($string) {
    $encrypt = base64_encode(convert_uuencode(gzdeflate($string)));
    return $encrypt;
}

function dec_id($string) {
    $decrypt = gzinflate(convert_uudecode(base64_decode($string)));
    return $decrypt;
}

function random($length) {
	$str = "";
	$characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
	$max = count($characters) - 1;
	for ($i = 0; $i < $length; $i++) {
		$rand = mt_rand(0, $max);
		$str .= $characters[$rand];
	}
	return $str;
}

function random_number($length) {
	$str = "";
	$characters = array_merge(range('0','9'));
	$max = count($characters) - 1;
	for ($i = 0; $i < $length; $i++) {
		$rand = mt_rand(0, $max);
		$str .= $characters[$rand];
	}
	return $str;
}

function getCookies() {
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://www.pointblank.id/login/form',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.5938.63 Safari/537.36",
        ),
    ));

    $response = curl_exec($curl);

    // Memisahkan header dan body
    list($header, $body) = explode("\r\n\r\n", $response, 2);

    // Menyimpan cookie ke dalam array
    $cookies = [];
    if (preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $header, $matches)) {
        foreach ($matches[1] as $cookie) {
            list($name, $value) = explode('=', $cookie, 2);
            $cookies[trim($name)] = trim($value);
        }
    }

    curl_close($curl);

    return $cookies['SESSION'];
}

function authLogin($username, $password, $getCookies)
{
    $url = "https://www.pointblank.id/login/process";
    $cookie = "SESSION=".$getCookies.";";
    $data = [
        'loginFail' => '0',
        'userid' => $username,
        'password' => $password
    ];

    // Initialize cURL
    $ch = curl_init($url);

    // Set cURL options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Cookie: $cookie",
        "Cache-Control: max-age=0",
        "Content-Type: application/x-www-form-urlencoded",
        "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.5938.63 Safari/537.36",
        "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7",
        "Referer: https://www.pointblank.id/login/form",
        "Accept-Encoding: gzip, deflate, br",
        "Accept-Language: en-US,en;q=0.9",
    ]);

    // Execute the request
    $response = curl_exec($ch);

    // Close cURL session
    curl_close($ch);

    // Get Html
    $html = str_get_html($response);

    // Menyimpan semua alert dalam array
    $alerts = [];

    // Mencari semua elemen <script>
    foreach ($html->find('script') as $script) {
        // Mencari string alert di dalam teks script
        if (preg_match_all('/alert\("(.*?)"\);/', $script->innertext, $matches)) {
            $alerts = array_merge($alerts, $matches[1]);
        }
    }

    if (empty($alerts[0]) == 1) {
        return "success";
    } else {
        return "failed";
    }
}

function changePassword($passwordLama, $passwordBaru, $getCookies)
{
    $url = "https://www.pointblank.id/password/change/process";
    $cookie = "SESSION=".$getCookies.";";
    $data = [
        'oldpassword' => $passwordLama,
        'password' => $passwordBaru
    ];

    // Initialize cURL
    $ch = curl_init($url);

    // Set cURL options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Cookie: $cookie",
        "Content-Type: application/x-www-form-urlencoded;charset=UTF-8",
        "Accept: application/json, text/javascript, */*; q=0.01",
        "X-Requested-With: XMLHttpRequest",
        "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.5938.63 Safari/537.36",
        "Referer: https://www.pointblank.id/mypage/info/view",
        "Accept-Encoding: gzip, deflate, br",
        "Accept-Language: en-US,en;q=0.9",
    ]);

    // Execute the request
    $response = curl_exec($ch);

    // Close cURL session
    curl_close($ch);

    return json_decode($response, TRUE)['resultCode'];
}