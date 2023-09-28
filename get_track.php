<?php
// Read the access token from the JSON file
$token_file = 'token.json';

if (file_exists($token_file)) {
    $token_data = json_decode(file_get_contents($token_file), true);

    if (isset($token_data['access_token'])) {
        $access_token = $token_data['access_token'];

        // Use the access token to fetch user information from Deezer
        $api_url = "https://api.deezer.com/user/me/history?access_token=" . $access_token;

        // Initialize a cURL session
        $ch = curl_init($api_url);

        // Set cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute the cURL request to get user information
        $user_info_response = curl_exec($ch);

        // Check for cURL errors
        if (curl_errno($ch)) {
            die('cURL error: ' . curl_error($ch));
        }

        // Close cURL session
        curl_close($ch);

        // Parse the JSON response for user information
        $user_history = json_decode($user_info_response, true);

        $jsonFileName = 'Track.json';
        $newTrackData = [
            "data" => $user_history['data'][0],
            "date" => date('d-m-Y H:i') // Use 24-hour format (H:i)
        ];

        // Check if the file exists
        if (file_exists($jsonFileName)) {
            // Read the JSON file
            $jsonData = file_get_contents($jsonFileName);

            // Parse the JSON data into a PHP associative array
            $storedTrackData = json_decode($jsonData, true);

            if ($storedTrackData !== null) {
                if ($storedTrackData['data']['id'] === $newTrackData['data']['id']) {
                    $date1 = $storedTrackData['date'];
                    $date2 = $newTrackData['date'];

                    // Calculate the time difference with the current date and time
                    $dateTime1 = DateTime::createFromFormat('d-m-Y H:i', $date1);
                    $dateTime2 = DateTime::createFromFormat('d-m-Y H:i', $date2);
                    $interval = $dateTime1->diff($dateTime2);
                    $totalMinutesPassed = $interval->days * 24 * 60 + $interval->h * 60 + $interval->i;
                    
                    if ($totalMinutesPassed > 10) {
                        $data = [
                            "data" => null,
                            "date" => date('d-m-Y H:i') // Use 24-hour format (H:i)
                        ];
                        //Send the blank data
                        echo json_encode($data);
                    } else {
                        //if 10 minutes have not passed send th stored track data
                        echo json_encode($storedTrackData);
                    }
                } else {
                    //if no id match is found make new record to Track.json
                    file_put_contents($jsonFileName, json_encode($newTrackData));
                    //send new track data
                    echo json_encode($newTrackData);
                }
            } else {
                //if no data in Track.json is found make new record
                file_put_contents($jsonFileName, json_encode($newTrackData));
                //Send the obtained data
                echo json_encode($newTrackData);
            }
        } else {
            //If no Track.json is found create Track.json and append track data
            file_put_contents($jsonFileName, json_encode($newTrackData));
            //Send the new data
            echo json_encode($newTrackData);
        }
    } else {
        echo "Access token not found in the JSON file.";
    }
} else {
    echo "Token file not found.";
}
?>
