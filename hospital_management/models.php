<?php
// ================================================================
// MODELS - All DB access using procedural mysqli + prepared stmts
// ================================================================

/* ------------------- Admin ------------------- */
function authAdmin($conn, $username, $password) {
    $stmt = mysqli_prepare($conn, "SELECT id, username, password FROM admins WHERE username = ?");
    mysqli_stmt_bind_param($stmt, 's', $username);
    mysqli_stmt_execute($stmt);
    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
    return ($row && password_verify($password, $row['password'])) ? $row : false;
}

/* ----------------- Doctor ----------------- */
function getDoctors($conn) {
    $r = mysqli_query($conn, "SELECT id, name, contact, username FROM doctors ORDER BY id DESC");
    return mysqli_fetch_all($r, MYSQLI_ASSOC);
}

function getDoctor($conn, $id) {
    $stmt = mysqli_prepare($conn, "SELECT id, name, contact, username FROM doctors WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
    return $row;
}

function addDoctor($conn, $name, $contact, $username, $password) {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = mysqli_prepare($conn,
        "INSERT INTO doctors (name, contact, username, password) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, 'ssss', $name, $contact, $username, $hash);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}

function updateDoctor($conn, $id, $name, $contact, $username) {
    $stmt = mysqli_prepare($conn,
        "UPDATE doctors SET name = ?, contact = ?, username = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'sssi', $name, $contact, $username, $id);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}

function deleteDoctor($conn, $id) {
    $stmt = mysqli_prepare($conn, "DELETE FROM doctors WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}

function searchDoctors($conn, $term) {
    $like = '%' . $term . '%';
    $stmt = mysqli_prepare($conn,
        "SELECT id, name, contact, username FROM doctors
         WHERE name LIKE ? OR username LIKE ? OR contact LIKE ?
         ORDER BY id DESC");
    mysqli_stmt_bind_param($stmt, 'sss', $like, $like, $like);
    mysqli_stmt_execute($stmt);
    $rows = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
    return $rows;
}

function authDoctor($conn, $username, $password) {
    $stmt = mysqli_prepare($conn,
        "SELECT id, name, username, password FROM doctors WHERE username = ?");
    mysqli_stmt_bind_param($stmt, 's', $username);
    mysqli_stmt_execute($stmt);
    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
    return ($row && password_verify($password, $row['password'])) ? $row : false;
}

function doctorUsernameExists($conn, $username, $excludeId = null) {
    if ($excludeId) {
        $stmt = mysqli_prepare($conn, "SELECT id FROM doctors WHERE username = ? AND id != ?");
        mysqli_stmt_bind_param($stmt, 'si', $username, $excludeId);
    } else {
        $stmt = mysqli_prepare($conn, "SELECT id FROM doctors WHERE username = ?");
        mysqli_stmt_bind_param($stmt, 's', $username);
    }
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $exists = mysqli_stmt_num_rows($stmt) > 0;
    mysqli_stmt_close($stmt);
    return $exists;
}

/* ------------------- Patient ------------------- */
function getPatients($conn) {
    $r = mysqli_query($conn, "SELECT id, name, disease, age, fee FROM patients ORDER BY id DESC");
    return mysqli_fetch_all($r, MYSQLI_ASSOC);
}

function getPatient($conn, $id) {
    $stmt = mysqli_prepare($conn, "SELECT id, name, disease, age, fee FROM patients WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
    return $row;
}

function addPatient($conn, $name, $disease, $age, $fee, $doctorId) {
    $stmt = mysqli_prepare($conn,
        "INSERT INTO patients (name, disease, age, fee, doctor_id) VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, 'ssidi', $name, $disease, $age, $fee, $doctorId);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}

function updatePatient($conn, $id, $name, $disease, $age, $fee) {
    $stmt = mysqli_prepare($conn,
        "UPDATE patients SET name = ?, disease = ?, age = ?, fee = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'ssidi', $name, $disease, $age, $fee, $id);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}

function deletePatient($conn, $id) {
    $stmt = mysqli_prepare($conn, "DELETE FROM patients WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}

function searchPatients($conn, $term) {
    $like = '%' . $term . '%';
    $stmt = mysqli_prepare($conn,
        "SELECT id, name, disease, age, fee FROM patients
         WHERE name LIKE ? OR disease LIKE ?
         ORDER BY id DESC");
    mysqli_stmt_bind_param($stmt, 'ss', $like, $like);
    mysqli_stmt_execute($stmt);
    $rows = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
    return $rows;
}
?>
