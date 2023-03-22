<?php
require 'config.php';

//รับ $_SESSION มาจากหน้า login.php
$id = $_SESSION['login_id'];

//ตรวจสอบ ข้อมูลใน database
$get_user = mysqli_query($db_connection, "SELECT * FROM `users` WHERE `google_id`='$id'");
if (mysqli_num_rows($get_user) > 0) {
    $user = mysqli_fetch_assoc($get_user);
} else {
    header('Location: logout.php');
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        <?php echo $user['name']; ?>
    </title>
</head>

<body>
    <center>
        <div>
            <h2>My Account</h2>
        </div>
        <div>
            <div>
                <img src="<?php echo $user['profile_image']; ?>" alt="<?php echo $user['name']; ?>">
            </div>
            <div>
                <h1>
                    <?php echo $user['name']; ?>
                </h1>
                <p>
                    <?php echo $user['email']; ?>
                </p>
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </center>
</body>

</html>