<?php
$emailErr = $websiteErr = $passwordErr = "";
$email = $website = $password =  "";

function cleanInput($data)
{
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Email
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = cleanInput($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
    }
    // Website (optional)
    $website = cleanInput($_POST["website"] ?? "");
    if (!empty($website) && !filter_var($website, FILTER_VALIDATE_URL)) {
        $websiteErr = "Invalid URL";
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
    <title>Login Form</title>
</head>

<body>

    <h1>Login Form</h1>
    <p><span style="color:red">* required field</span></p>
    <form method="post" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <fieldset>
            
            Email: <input type="text" name="email" value="<?= $email ?>">
            <span style="color:red">*
                <?= $emailErr ?>
            </span><br><br>
            Password: <input type="password" name="password" value="<?= $password ?>">
            <span style="color:red">*
                <?= $passwordErr ?>
            </span><br><br>

            <input type="submit" value="Login">


        </fieldset>

    </form>

    <br>

    <a href="index.html">Back to Home</a>

    <?php if (
        $_SERVER["REQUEST_METHOD"] == "POST" &&
        !$emailErr && !$websiteErr  && !$passwordErr
    ): ?>
        <h3>Submitted values</h3>
        Email:
        <?= $email ?><br>
        Website:
        <?= $website ?><br>
        Password:
        <?= $password ?><br>

    <?php endif; ?>
</body>

</html>