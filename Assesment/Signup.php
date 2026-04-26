<?php
$nameErr = $emailErr = $contactErr= $websiteErr = $usernameErr = $passwordErr = "";
$name = $email = $contact = $website = $username = $password =  "";

function cleanInput($data)
{
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Name
    if (empty($_POST["name"])) {
        $nameErr = "Name is required";
    } else {
        $name = cleanInput($_POST["name"]);
        if (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
            $nameErr = "Only letters and white space allowed";
        }
    }
    

    // Email
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = cleanInput($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
    }
    //contact
    if (empty($_POST["contact"])) {
        $contactErr = "Contact no is required";
    } else {
        $contact = cleanInput($_POST["contact"]);
        if (!preg_match("/^[1-9' ]*$/", $contact)) {
            $contactErr = "Only letters, numbers and white space allowed";
        }
    }

    // Website (optional)
    $website = cleanInput($_POST["website"] ?? "");
    if (!empty($website) && !filter_var($website, FILTER_VALIDATE_URL)) {
        $websiteErr = "Invalid URL";
    }
    //username
    if (empty($_POST["username"])) {
        $usernameErr = "UserName is required";
    } else {
        $username = cleanInput($_POST["username"]);
        if (!preg_match("/^[a-zA-Z-1-9' ]*$/", $username)) {
            $usernameErr = "Only letters, numbers and white space allowed";
        }
    }
    // Password
    if (empty($_POST["password"])) {
        $passwordErr = "Password is required";
    } else {
        $password = cleanInput($_POST["password"]);
    if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/", $password)) {
        $passwordErr = "Password must be at least 8 characters and include uppercase, lowercase, and a number";
        }
    }


}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Sign up Form</title>
</head>

<body>

    <h1>Sign up Form</h1>
    <p><span style="color:red">* required field</span></p>
    <form method="post" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <fieldset>
            <legend>Personal Information</legend>

            FirstName: <input type="text" name="firstname" value="<?= $name ?>">
            <span style="color:red">*
                <?= $nameErr ?>
            </span><br><br>
            LastName: <input type="text" name="lastname" value="<?= $name ?>">
            <span style="color:red">*
                <?= $nameErr ?>
            </span><br><br>
            Contact no: <input type="text" name="contact" value="<?= $name ?>">
            <span style="color:red">*
                <?= $contactErr ?>
            </span><br><br>
            Email: <input type="text" name="email" value="<?= $email ?>">
            <span style="color:red">*
                <?= $emailErr ?>
            </span><br><br>
            UserName: <input type="text" name="username" value="<?= $username ?>">
            <span style="color:red">*
                <?= $usernameErr ?>
            </span><br><br>
            Password: <input type="password" name="password" value="<?= $password ?>">
            <span style="color:red">*
                <?= $passwordErr ?>
            </span><br><br>
 

            <input type="submit" value="Register">
            <input type="reset" value="Reset">


        </fieldset>

    </form>

    <br>

    <a href="index.html">Back to Home</a>

    <?php if (
        $_SERVER["REQUEST_METHOD"] == "POST" &&
        !$nameErr && !$emailErr && !$contactErr && !$websiteErr && !$usernameErr && !$passwordErr
    ): ?>
        <h3>Submitted values</h3>
        FirstName:
        <?= $name ?><br>
        LastName:
        <?= $name ?><br>
        Contact no:
        <?= $contact ?><br>
        Email:
        <?= $email ?><br>
        Website:
        <?= $website ?><br>
        UserName:
        <?= $username ?><br>
        Password:
        <?= $password ?><br>

    <?php endif; ?>
</body>

</html>