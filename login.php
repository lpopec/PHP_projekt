<?php
$msg = '';

if (isset($_POST['submit'])) {
    $username = mysqli_real_escape_string($dbc, $_POST['username']);
    $password = $_POST['password'];
    
    $sql = "SELECT id, first_name, last_name, password, role FROM users WHERE username = ?";
    $stmt = mysqli_prepare($dbc, $sql);
    mysqli_stmt_bind_param($stmt, 's', $username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    
    if (mysqli_stmt_num_rows($stmt) == 1) {
        mysqli_stmt_bind_result($stmt, $id, $first_name, $last_name, $real_password, $role);
        mysqli_stmt_fetch($stmt);
        
        if (password_verify($password, $real_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;
            $_SESSION['firstname'] = $first_name;
            $_SESSION['role'] = $role; 
            
            if($role == 'admin') {
                header("Location: $link_admin");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            $msg = '<p style="color:red; text-align:center;">Pogrešna lozinka!</p>';
        }
    } else {
        $msg = '<p style="color:red; text-align:center;">Korisnik ne postoji!</p>';
    }
}
?>

<div class="login-wrapper">
    <div class="login-box">
        <h1 class="page-title">Prijava</h1>
        <p class="login-subtitle">Dobrodošli natrag!</p>
        
        <?php echo $msg; ?>

        <form action="" method="post">
            <div class="form-group">
                <label for="username">Korisničko ime *</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Lozinka *</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" name="submit" class="btn-submit">Prijavi se</button>
        </form>
    </div>
</div>