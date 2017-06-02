<?php
session_start();

include 'User.php';

$userId = $_SESSION['userId'];

$userName = User::loadUserById($_GET['userId'])->getUserName();
$userEmail = User::loadUserById($_GET['userId'])->getUserEmail();

$text = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['userName']) || isset($_POST['userEmail'])) {

        $userName = $_POST['userName'];
        $email = $_POST['userEmail'];

        try {
            if (!User::updateUser($userId, $userName, $email)) {
                throw new Exception("ERROR - you cannot update your data!");
            }
        } catch (Exception $ex) {
            die($ex->getMessage());
        } $text = "Changes made in DataBase!";
    } elseif (isset($_POST['userAccount'])) {
        header('Location: showUserAccount.php');
    }
}
?>

<!DOCTYPE html>
<html>
    <style>
        input[type=text] {
            width: 100%;
            padding: 12px 20px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type=submit] {
            width: 100%;
            background-color: #4CAF50;
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type=submit]:hover {
            background-color: #45a049;
        }

        div {
            border-radius: 5px;
            background-color: #f2f2f2;
            padding: 20px;
        }
    </style>

    <head>
        <title>Edit User</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>

    <body>
        <div>
            <h4><?php echo $text ?></h4>
            <h2>Edit User data :</h2>
            <form action="#" method="POST">
                <label>User name:</label>
                <input type="text" name="userName" value="<?php echo $userName ?>"><br/>
                <label>User email:</label>
                <input type="text" name="userEmail" value="<?php echo $userEmail ?>"><br/>
                <input type="submit" name="updateUser" value="Update">
            </form>
            <form action="#" method="POST"><input type="submit" name="userAccount" value="Back to User account"></form>
        </div>
    </body>
</html>
