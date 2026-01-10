<?php
$msg = '';

if (isset($_POST['submit'])) {
    $firstname = mysqli_real_escape_string($dbc, $_POST['firstname']);
    $lastname = mysqli_real_escape_string($dbc, $_POST['lastname']);
    $email = mysqli_real_escape_string($dbc, $_POST['email']);
    $username = mysqli_real_escape_string($dbc, $_POST['username']);
    $password = $_POST['password'];
    
    $sql = "SELECT username FROM users WHERE username = ? OR email = ?";
    $stmt = mysqli_prepare($dbc, $sql);
    mysqli_stmt_bind_param($stmt, 'ss', $username, $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    
    if (mysqli_stmt_num_rows($stmt) > 0) {
        $msg = '<p style="color:red; text-align:center;">Korisničko ime ili email već postoje!</p>';
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $sql_insert = "INSERT INTO users (first_name, last_name, email, username, password, role) VALUES (?, ?, ?, ?, ?, 'user')";
        $stmt_insert = mysqli_prepare($dbc, $sql_insert);
        mysqli_stmt_bind_param($stmt_insert, 'sssss', $firstname, $lastname, $email, $username, $hashed_password);
        
        if (mysqli_stmt_execute($stmt_insert)) {
            echo '<p style="color:green; text-align:center; font-weight:bold; padding:20px;">Registracija uspješna! Preusmjeravanje na prijavu...</p>';
            header("refresh:2;url=index.php?menu=7"); 
            exit(); 
        } else {
            $msg = '<p style="color:red; text-align:center;">Greška pri registraciji!</p>';
        }
    }
}
?>

<div class="register-wrapper">
    <div class="register-box">
        <h1 class="page-title">Registracija</h1>
        <p class="register-subtitle">Ispunite formu za kreiranje novog računa.</p>
        
        <?php echo $msg; ?>

        <form action="" method="post">
            <div class="form-group">
                <label for="fname">Ime *</label>
                <input type="text" id="fname" name="firstname" required>
            </div>
            <div class="form-group">
                <label for="lname">Prezime *</label>
                <input type="text" id="lname" name="lastname" required>
            </div>
            <div class="form-group">
                <label for="email">E-mail adresa *</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="username">Korisničko ime *</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Lozinka *</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" name="submit" class="btn-submit">Registriraj se</button>
            
            <p class="login-link">
                Već imate račun? <a href="index.php?menu=7">Prijavite se ovdje</a>.
            </p>
        </form>
    </div>
</div>