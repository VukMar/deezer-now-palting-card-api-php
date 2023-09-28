<?php
$app_id = "your-app_id";
$app_secret = "your-app-secret";
$my_url = "your-app-redirect-url";

session_start();
$code = $_REQUEST["code"];

if (empty($code)) {
    $_SESSION['state'] = md5(uniqid(rand(), TRUE)); // CSRF protection

    $dialog_url = "https://connect.deezer.com/oauth/auth.php?app_id=" . $app_id
        . "&redirect_uri=" . urlencode($my_url) . "&perms=email,offline_access,listening_history"
        . "&state=" . $_SESSION['state'];

    header("Location: " . $dialog_url);
    exit;
} else {
    if ($_REQUEST['state'] == $_SESSION['state']) {
        $token_url = "https://connect.deezer.com/oauth/access_token.php?app_id="
            . $app_id . "&secret="
            . $app_secret . "&code=" . $code;

        // Initialize cURL session
        $ch = curl_init($token_url);

        // Set cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute the cURL request to get the token response
        $response = curl_exec($ch);

        // Check for cURL errors
        if (curl_errno($ch)) {
            die('cURL error: ' . curl_error($ch));
        }

        // Close cURL session
        curl_close($ch);

        parse_str($response, $token_data);

        // Check if the access token was obtained successfully
        if (isset($token_data['access_token'])) {
            $access_token = $token_data['access_token'];

            // Store the access token in a JSON file
            $token_data = ['access_token' => $access_token];
            file_put_contents('token.json', json_encode($token_data));

            echo "Access token obtained successfully and stored.";
        } else {
            echo "Access token not found in the response.";
        }
    } else {
        echo "The state does not match. You may be a victim of CSRF.";
    }
}
?>
