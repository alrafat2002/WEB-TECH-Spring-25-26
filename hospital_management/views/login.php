<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login &mdash; Hospital Management</title>
<link rel="stylesheet" href="style.css">
</head>
<body class="auth-body">

<div class="auth-shell">
    <div class="auth-side">
        <div class="logo-big">&#127973;</div>
        <h1>Hospital Management System</h1>
        <p>Manage doctors and patients in one place. Sign in to access your dashboard.</p>
        <ul class="feature-list">
            <li>&#10003; Admin manages doctors</li>
            <li>&#10003; Doctors manage patients</li>
            <li>&#10003; Live AJAX search</li>
            <li>&#10003; Secure session login</li>
        </ul>
    </div>

    <div class="auth-form-wrap">
        <div class="auth-card">
            <h2>Welcome Back</h2>
            <p class="muted">Please sign in to continue</p>

            <?php if (!empty($error)): ?>
                <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="index.php?page=login" class="form" novalidate>
                <div class="field">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username"
                           value="<?= htmlspecialchars($prefill ?? '') ?>"
                           placeholder="Enter username" required autofocus>
                </div>
                <div class="field">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password"
                           placeholder="Enter password" required>
                </div>
                <label class="checkbox">
                    <input type="checkbox" name="remember" <?= !empty($prefill) ? 'checked' : '' ?>>
                    <span>Remember me</span>
                </label>
                <button type="submit" class="btn btn-primary btn-block">Sign In</button>
            </form>

            <p class="auth-foot">Don't have an account?
                <a href="index.php?page=register">Register as Doctor</a>
            </p>
            <p class="hint"><strong>Default Admin:</strong> admin / admin123</p>
        </div>
    </div>
</div>

</body>
</html>
