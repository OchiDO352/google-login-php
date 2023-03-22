<?php
require 'config.php';

//require หาไฟล์ google-api ที่โหลดมาติตั้ง
require 'google-api/vendor/autoload.php';

// Creating new google client instance
$client = new Google_Client();

// Enter your Client ID
$client->setClientId('322408248165-el4gpt09964ml54e419h6g4s7kkvm5n4.apps.googleusercontent.com');
// Enter your Client Secrect
$client->setClientSecret('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');
// Enter the Redirect URL
$client->setRedirectUri('http://localhost/google-login-php/login.php');

// Adding those scopes which we want to get (email & profile Information)
$client->addScope("email");
$client->addScope("profile");


//Login with Google
if (isset($_GET['code'])) {

    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

    if (!isset($token["error"])) {

        $client->setAccessToken($token['access_token']);

        // getting profile information
        $google_oauth = new Google_Service_Oauth2($client);
        $google_account_info = $google_oauth->userinfo->get();

        // Storing data into database
        $id = mysqli_real_escape_string($db_connection, $google_account_info->id);
        $full_name = mysqli_real_escape_string($db_connection, trim($google_account_info->name));
        $email = mysqli_real_escape_string($db_connection, $google_account_info->email);
        $profile_pic = mysqli_real_escape_string($db_connection, $google_account_info->picture);

        // checking user already exists or not
        $get_user = mysqli_query($db_connection, "SELECT `google_id` FROM `users` WHERE `google_id`='$id'");
        if (mysqli_num_rows($get_user) > 0) {

            //เก็บค่า $_SESSION เพื่อใช้ส่งไปหน้า index.php
            $_SESSION['login_id'] = $id;
            header('Location: index.php');
            exit;

        } else {

            // if user not exists we will insert the user
            $insert = mysqli_query($db_connection, "INSERT INTO `users`(`google_id`,`name`,`email`,`profile_image`) VALUES('$id','$full_name','$email','$profile_pic')");

            if ($insert) {
                
                //เก็บค่า $_SESSION เพื่อใช้ส่งไปหน้า index.php
                $_SESSION['login_id'] = $id;
                header('Location: index.php');
                exit;
            } else {
                echo "Sign up failed!(Something went wrong).";
            }

        }

    } else {
        header('Location: login.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login - Google</title>
</head>

<body>
    <center>
        <div>
            <h2>Login</h2>
        </div>
        <div>
            <a type="button" href="<?php echo $client->createAuthUrl(); ?>">
                Sign in with Google
            </a>

        </div>
    </center>
</body>

</html>