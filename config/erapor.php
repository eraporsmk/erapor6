<?php
if(env('APP_URL') == 'http://v6.erapor-smk.net'){
  $dapodik = 'http://172.18.4.11/erapor_server/';
//} elseif(env('APP_URL') == 'http://erapor6.test'){
  //$dapodik = 'http://localhost:8383/erapor_server/';
} else {
  $dapodik = 'http://103.40.55.242/erapor_server/';
}
return [
  'dapodik' => $dapodik,
  'dashboard' => 'http://app.erapor-smk.net/',
  'registration' => env('REGISTRATION', FALSE),
  'user_erapor' => 'masadi',
  'pass_erapor' => '@Bismill4h#',
];