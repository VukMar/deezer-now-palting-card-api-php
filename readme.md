Deezer API Integration for User Listening History
This repository contains PHP code for integrating the Deezer API into your web application to fetch and store a user's listening history. This README provides an overview of the code and how to set up and use it in your project.

## Table of Contents
[Overview]
[Prerequisites]
[Installation]
[Usage]
[File Descriptions]
[Contributing]
[License]
## Overview
This PHP code allows you to integrate the Deezer API into your web application to retrieve a user's listening history and store it in a JSON file. The code is divided into two parts:

OAuth Authentication: The OAuthTokenFetch.php script handles the OAuth authentication process with Deezer. It obtains an access token from Deezer and stores it in a JSON file for later use.

Fetching and Storing Listening History: The get_track.php script uses the access token obtained in the first step to fetch the user's listening history from Deezer. It then compares the newly fetched track data with the previously stored data, and based on the timestamp, it either sends the stored data or updates it with the new track data.

## Prerequisites
Before using this code, you need to:

Create a Deezer Application: Visit the Deezer Developers portal and create a new application to obtain your app_id and app_secret.

Configure Redirect URL: Specify a redirect URL in your Deezer application settings. This should match the URL where your OAuth process is handled (e.g., your-app-redirect-url).

PHP and cURL: Ensure that PHP is installed on your server, and the cURL extension is enabled.

## Installation
Clone the repository to your server or download the ZIP file and extract it.

Open the OAuthTokenFetch.php and get_track.php files and update the following variables at the beginning of each script:

$app_id: Your Deezer application ID.
$app_secret: Your Deezer application secret.
$my_url: The redirect URL specified in your Deezer application settings.
Create an empty JSON file named token.json in the repository's root directory. This file will be used to store the Deezer access token.
## Usage
OAuth Authentication (OAuthTokenFetch.php)
Access the OAuthTokenFetch.php script through your web browser. This initiates the OAuth authentication process with Deezer.

Users will be redirected to Deezer for authorization. Once authorized, they will be redirected back to your specified redirect URL ($my_url).

The access token will be obtained and stored in the token.json file for future use.

Fetching and Storing Listening History (get_track.php)
Access the get_track.php script through your web browser or include it in your application logic.

The script will use the stored access token to fetch the user's listening history from Deezer's API.

The fetched track data will be compared with the previously stored data, and one of the following actions will occur:

If no data is stored, a new record will be created in Track.json.
If the track ID matches an existing record and less than 10 minutes have passed since the last update, the stored data will be sent as a response.
If the track ID matches an existing record, but more than 10 minutes have passed, a blank response will be sent, indicating that the data is too old to use.
If no matching track ID is found, a new record will be created in Track.json.
## File Descriptions
OAuthTokenFetch.php: Handles the OAuth authentication process with Deezer and stores the access token in token.json.
get_track.php: Uses the access token to fetch the user's listening history from Deezer and manages the storage of track data in Track.json.
Contributing
Contributions to this repository are welcome. If you find any issues or have suggestions for improvements, please open an issue or create a pull request.

## License
This code is licensed under the MIT License. You are free to use and modify it for your projects.