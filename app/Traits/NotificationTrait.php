<?php

namespace App\Traits;

use App\Models\User;

trait NotificationTrait
{
    public function sendNotification($patient,$data)
    {
        $SERVER_API_KEY = 'AAAAx1BL-Zg:APA91bGiJZzFz2vEtXvnc7vFtLV7HzXNR_OMa4yI1zDLg7_0FiOXND35pTnbBHXJ55Pp7uqwUXcAzzW4Ic9u4jJsAVtZeCJ4yNhdlRbt2k7aUr2_cvzCpSVj97zY8xr8bUMu2Zhplw2P';
        
        $tokens = [] ; 
        foreach($patient->devices as $device){
            $tokens[] = $device->device;
        }
        
        $data = [
            "registration_ids" => $tokens,
            "notification" => [
                "title" => $data['title'],
                "body" => $data['body'],
                "sound"=> "default"
            ],
        ];

        $dataString = json_encode($data);
        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        $response = curl_exec($ch);
    }

}


