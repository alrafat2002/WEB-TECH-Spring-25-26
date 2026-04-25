<?php
$nameErr = $emailErr = $websiteErr = $genderErr = $companyErr = $reasonErr = $topicErr = "";
$name = $email = $website = $gender = $company = $reason = $topic = "";

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

    // Website (optional)
    $website = cleanInput($_POST["website"] ?? "");
    if (!empty($website) && !filter_var($website, FILTER_VALIDATE_URL)) {
        $websiteErr = "Invalid URL";
    }
    //companny
    if (empty($_POST["company"])) {
        $companyErr = "Company Name is required";
    } else {
        $company = cleanInput($_POST["company"]);
        if (!preg_match("/^[a-zA-Z-' ]*$/", $company)) {
            $companyErr = "Only letters and white space allowed";
        }
    }

    // Gender
    if (empty($_POST["gender"])) {
        $genderErr = "Gender is required";
    } else {
        $gender = cleanInput($_POST["gender"]);
    }
    //reason
    if (empty($_POST["reason"])) {
        $reasonErr = "Reason is required";
    } else {
        $reason = cleanInput($_POST["reason"]);
    }
    //opic
    if (empty($_POST["topic"])) {
        $topicErr = "Topic is required";
    } else {
        $topic = cleanInput($_POST["topic"]);
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Contact Me Form</title>
</head>

<body>

    <h1>Contact me Form</h1>
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
            Email: <input type="text" name="email" value="<?= $email ?>">
            <span style="color:red">*
                <?= $emailErr ?>
            </span><br><br>
            Gender:
            <input type="radio" name="gender" value="male" <?= ($gender == "male") ? "checked" : "" ?>> Male
            <input type="radio" name="gender" value="female" <?= ($gender == "female") ? "checked" : "" ?>> Female
            <input type="radio" name="gender" value="others" <?= ($gender == "others") ? "checked" : "" ?>> Others
            <span style="color:red">*
                <?= $genderErr ?>
            </span><br><br>
            Company: <input type="text" name="company" value="<?= $company ?>">
            <span style="color:red">*
                <?= $companyErr ?>
            </span><br><br>
            Reason of Contact:
            <input type="checkbox" name="reason" value="project" <?= ($gender == "project") ? "checked" : "" ?>> Project
            <input type="checkbox" name="reason" value="thesis" <?= ($gender == "thesis") ? "checked" : "" ?>> Thesis
            <input type="checkbox" name="reason" value="job" <?= ($gender == "job") ? "checked" : "" ?>> Job
            <span style="color:red">*
                <?= $reasonErr ?>
            </span><br><br>
            Topics:
            <input type="checkbox" name="topic" value="web" <?= ($gender == "web") ? "checked" : "" ?>> Web Development
            <input type="checkbox" name="topic" value="software" <?= ($gender == "software") ? "checked" : "" ?>> Software
            Development
            <input type="checkbox" name="topic" value="ai" <?= ($gender == "ai") ? "checked" : "" ?>> AI/ML Model
            Development
            <span style="color:red">*
                <?= $topicErr ?>
            </span><br><br>

            <input type="submit" value="Register">
            <input type="reset" value="Reset">


        </fieldset>

    </form>

    <br>

    <a href="index.html">Back to Home</a>

    <?php if (
        $_SERVER["REQUEST_METHOD"] == "POST" &&
        !$nameErr && !$emailErr && !$genderErr && !$websiteErr && !$companyErr && !$reasonErr && !$topicErr
    ): ?>
        <h3>Submitted values</h3>
        FirstName:
        <?= $name ?><br>
        LastName:
        <?= $name ?><br>
        Email:
        <?= $email ?><br>
        Gender:
        <?= $gender ?><br>
        Website:
        <?= $website ?><br>
        Company:
        <?= $company ?><br>
        Reason of Contact:
        <?= $reason ?><br>
        Topics:
        <?= $topic ?><br>
    <?php endif; ?>
</body>

</html>