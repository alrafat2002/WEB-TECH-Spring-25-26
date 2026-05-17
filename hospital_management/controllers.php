<?php
// ================================================================
// CONTROLLERS - request handling + role-based logic
// ================================================================

/* ============== Login ============== */
function loginCtrl($conn) {
    $error = '';
    $prefill = $_COOKIE['remember_user'] ?? '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $u = trim($_POST['username'] ?? '');
        $p = $_POST['password'] ?? '';
        $remember = isset($_POST['remember']);

        if ($u === '' || $p === '') {
            $error = 'Please fill in both fields.';
        } else {
            // Try admin first
            $admin = authAdmin($conn, $u, $p);
            if ($admin) {
                $_SESSION['user'] = [
                    'id' => $admin['id'], 'username' => $admin['username'],
                    'name' => 'Administrator', 'role' => 'admin'
                ];
                if ($remember) setcookie('remember_user', $u, time() + 86400 * 30, '/');
                else setcookie('remember_user', '', time() - 3600, '/');
                header('Location: index.php?page=admin');
                exit;
            }
            // Then doctor
            $doc = authDoctor($conn, $u, $p);
            if ($doc) {
                $_SESSION['user'] = [
                    'id' => $doc['id'], 'username' => $doc['username'],
                    'name' => $doc['name'], 'role' => 'doctor'
                ];
                if ($remember) setcookie('remember_user', $u, time() + 86400 * 30, '/');
                else setcookie('remember_user', '', time() - 3600, '/');
                header('Location: index.php?page=doctor');
                exit;
            }
            $error = 'Invalid username or password.';
        }
    }

    require 'views/login.php';
}

/* ============== Register (doctor self-registration) ============== */
function registerCtrl($conn) {
    $error = $success = '';
    $old = ['name' => '', 'contact' => '', 'username' => ''];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name     = trim($_POST['name'] ?? '');
        $contact  = trim($_POST['contact'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm  = $_POST['confirm_password'] ?? '';
        $old = compact('name', 'contact', 'username');

        if ($name === '' || $contact === '' || $username === '' || $password === '') {
            $error = 'All fields are required.';
        } elseif (strlen($password) < 6) {
            $error = 'Password must be at least 6 characters.';
        } elseif ($password !== $confirm) {
            $error = 'Passwords do not match.';
        } elseif (doctorUsernameExists($conn, $username)) {
            $error = 'Username is already taken.';
        } else {
            if (addDoctor($conn, $name, $contact, $username, $password)) {
                $success = 'Account created! You can now log in.';
                $old = ['name' => '', 'contact' => '', 'username' => ''];
            } else {
                $error = 'Registration failed. Try again.';
            }
        }
    }

    require 'views/register.php';
}

/* ============== Admin Dashboard (manages doctors) ============== */
function adminCtrl($conn) {
    $action = $_GET['action'] ?? 'list';
    $error = '';
    $editing = null;

    /* --- Add (POST) --- */
    if ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $name     = trim($_POST['name'] ?? '');
        $contact  = trim($_POST['contact'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($name === '' || $contact === '' || $username === '' || $password === '') {
            $error = 'All fields are required.';
        } elseif (strlen($password) < 6) {
            $error = 'Password must be at least 6 characters.';
        } elseif (doctorUsernameExists($conn, $username)) {
            $error = 'Username is already taken.';
        } else {
            if (addDoctor($conn, $name, $contact, $username, $password)) {
                header('Location: index.php?page=admin&msg=added');
                exit;
            }
            $error = 'Failed to add doctor.';
        }
    }

    /* --- Update (POST) --- */
    if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $id       = intval($_GET['id'] ?? 0);
        $name     = trim($_POST['name'] ?? '');
        $contact  = trim($_POST['contact'] ?? '');
        $username = trim($_POST['username'] ?? '');

        if ($name === '' || $contact === '' || $username === '') {
            $error = 'No field can be empty (NULL). All fields are required.';
            $editing = ['id' => $id, 'name' => $name, 'contact' => $contact, 'username' => $username];
        } elseif (doctorUsernameExists($conn, $username, $id)) {
            $error = 'That username is used by another doctor.';
            $editing = ['id' => $id, 'name' => $name, 'contact' => $contact, 'username' => $username];
        } else {
            if (updateDoctor($conn, $id, $name, $contact, $username)) {
                header('Location: index.php?page=admin&msg=updated');
                exit;
            }
            $error = 'Update failed.';
            $editing = ['id' => $id, 'name' => $name, 'contact' => $contact, 'username' => $username];
        }
    }

    /* --- Show edit form (GET) --- */
    if ($action === 'edit' && !$editing) {
        $id = intval($_GET['id'] ?? 0);
        $editing = getDoctor($conn, $id);
    }

    /* --- Delete (GET) --- */
    if ($action === 'delete') {
        $id = intval($_GET['id'] ?? 0);
        if ($id > 0) deleteDoctor($conn, $id);
        header('Location: index.php?page=admin&msg=deleted');
        exit;
    }

    $doctors = getDoctors($conn);
    require 'views/admin.php';
}

/* ============== Doctor Dashboard (manages patients) ============== */
function doctorCtrl($conn) {
    $action = $_GET['action'] ?? 'list';
    $error = '';
    $editing = null;

    /* --- Add (POST) --- */
    if ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $name    = trim($_POST['name'] ?? '');
        $disease = trim($_POST['disease'] ?? '');
        $age     = trim($_POST['age'] ?? '');
        $fee     = trim($_POST['fee'] ?? '');

        if ($name === '' || $disease === '' || $age === '' || $fee === '') {
            $error = 'All fields are required.';
        } elseif (!ctype_digit($age) || intval($age) < 0) {
            $error = 'Age must be a non-negative whole number.';
        } elseif (!is_numeric($fee) || floatval($fee) < 0) {
            $error = 'Fee must be a non-negative number.';
        } else {
            $docId = $_SESSION['user']['id'];
            if (addPatient($conn, $name, $disease, intval($age), floatval($fee), $docId)) {
                header('Location: index.php?page=doctor&msg=added');
                exit;
            }
            $error = 'Failed to add patient.';
        }
    }

    /* --- Update (POST) --- */
    if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $id      = intval($_GET['id'] ?? 0);
        $name    = trim($_POST['name'] ?? '');
        $disease = trim($_POST['disease'] ?? '');
        $age     = trim($_POST['age'] ?? '');
        $fee     = trim($_POST['fee'] ?? '');

        if ($name === '' || $disease === '' || $age === '' || $fee === '') {
            $error = 'No field can be empty (NULL). All fields are required.';
            $editing = ['id' => $id, 'name' => $name, 'disease' => $disease,
                        'age' => $age, 'fee' => $fee];
        } elseif (!ctype_digit($age) || intval($age) < 0) {
            $error = 'Age must be a non-negative whole number.';
            $editing = ['id' => $id, 'name' => $name, 'disease' => $disease,
                        'age' => $age, 'fee' => $fee];
        } elseif (!is_numeric($fee) || floatval($fee) < 0) {
            $error = 'Fee must be a non-negative number.';
            $editing = ['id' => $id, 'name' => $name, 'disease' => $disease,
                        'age' => $age, 'fee' => $fee];
        } else {
            if (updatePatient($conn, $id, $name, $disease, intval($age), floatval($fee))) {
                header('Location: index.php?page=doctor&msg=updated');
                exit;
            }
            $error = 'Update failed.';
            $editing = ['id' => $id, 'name' => $name, 'disease' => $disease,
                        'age' => $age, 'fee' => $fee];
        }
    }

    /* --- Show edit form --- */
    if ($action === 'edit' && !$editing) {
        $id = intval($_GET['id'] ?? 0);
        $editing = getPatient($conn, $id);
    }

    /* --- Delete --- */
    if ($action === 'delete') {
        $id = intval($_GET['id'] ?? 0);
        if ($id > 0) deletePatient($conn, $id);
        header('Location: index.php?page=doctor&msg=deleted');
        exit;
    }

    $patients = getPatients($conn);
    require 'views/doctor.php';
}
?>
