<?php
$msg = '';

if (isset($_POST['submit'])) {
    $firstname = mysqli_real_escape_string($dbc, $_POST['firstname']);
    $lastname = mysqli_real_escape_string($dbc, $_POST['lastname']);
    $email = mysqli_real_escape_string($dbc, $_POST['email']);
    $username = mysqli_real_escape_string($dbc, $_POST['username']);
    $country_code = mysqli_real_escape_string($dbc, $_POST['country']); 
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
        
        $sql_insert = "INSERT INTO users (first_name, last_name, email, username, password, country_code, role) 
                       VALUES (?, ?, ?, ?, ?, ?, 'user')";
        
        $stmt_insert = mysqli_prepare($dbc, $sql_insert);
        
        mysqli_stmt_bind_param($stmt_insert, 'ssssss', $firstname, $lastname, $email, $username, $hashed_password, $country_code);
        
        if (mysqli_stmt_execute($stmt_insert)) {
            echo '<p style="color:green; text-align:center; font-weight:bold; padding:20px;">Registracija uspješna! Preusmjeravanje na prijavu...</p>';
            header("refresh:2;url=$link_login"); 
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
                <label for="country">Država *</label>
                <select name="country" id="country" required style="width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ddd; border-radius: 4px;">
                    <option value="">Odaberite državu...</option>
                    <?php
                    $q_countries = "SELECT * FROM countries ORDER BY name ASC";
                    $r_countries = mysqli_query($dbc, $q_countries);
                    
                    while($row_country = mysqli_fetch_assoc($r_countries)) {
                        echo '<option value="' . $row_country['country_code'] . '">' . $row_country['name'] . '</option>';
                    }
                    ?>
                </select>
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
                Već imate račun? <a href="<?php echo $link_login; ?>">Prijavite se ovdje</a>.
            </p>
        </form>
    </div>
</div>