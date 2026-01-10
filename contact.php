<?php
$form_fname = '';
$form_lname = '';
$form_email = '';
$form_country = ''; 

if (isset($_SESSION['user_id'])) {
    $id = $_SESSION['user_id'];
    $query = "SELECT first_name, last_name, email, country_code FROM users WHERE id = $id";
    $result = mysqli_query($dbc, $query);
    
    if ($row = mysqli_fetch_assoc($result)) {
        $form_fname = $row['first_name'];
        $form_lname = $row['last_name'];
        $form_email = $row['email'];
        $form_country = $row['country_code']; 
    }
}

$msg = '';
$msgClass = '';

if (isset($_POST['submit_contact'])) {
    $form_fname = $_POST['firstname']; 
    $form_lname = $_POST['lastname'];
    $form_email = $_POST['email'];
    $form_country = $_POST['country']; 
    
    $firstname = mysqli_real_escape_string($dbc, $_POST['firstname']);
    $lastname = mysqli_real_escape_string($dbc, $_POST['lastname']);
    $email = mysqli_real_escape_string($dbc, $_POST['email']);
    $country_code = mysqli_real_escape_string($dbc, $_POST['country']); 
    $message_body = mysqli_real_escape_string($dbc, $_POST['subject']);
    $newsletter = isset($_POST['newsletter']) ? 'DA' : 'NE';
    
    $query = "INSERT INTO contact_messages (first_name, last_name, email, country_code, message, newsletter) 
              VALUES ('$firstname', '$lastname', '$email', '$country_code', '$message_body', '$newsletter')";
    
    $result = mysqli_query($dbc, $query);
    
    if ($result) {
        $msg = 'Vaša poruka je uspješno zaprimljena!';
        $msgClass = 'alert-success';
    } else {
        $msg = 'Greška pri spremanju: ' . mysqli_error($dbc);
        $msgClass = 'alert-danger';
    }
}
?>

<div class="contact-wrapper">
    <h1 class="page-title">Kontaktirajte nas</h1>
    
    <?php if($msg != ''): ?>
        <div class="alert <?php echo $msgClass; ?>">
            <?php echo $msg; ?>
        </div>
    <?php endif; ?>

    <div class="contact-container">
        <div class="map-container">
            <iframe src="https://maps.google.com/maps?q=Zagreb&t=&z=13&ie=UTF8&iwloc=&output=embed" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>

        <div class="form-container">
            <form action="" method="post">
                <div class="form-group">
                    <label>Ime *</label>
                    <input type="text" name="firstname" value="<?php echo $form_fname; ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Prezime *</label>
                    <input type="text" name="lastname" value="<?php echo $form_lname; ?>" required>
                </div>
                
                <div class="form-group">
                    <label>E-mail *</label>
                    <input type="email" name="email" value="<?php echo $form_email; ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Država *</label>
                    <select name="country" required>
                        <option value="">Odaberite...</option>
                        <?php
                        $q_countries = "SELECT * FROM countries ORDER BY name ASC";
                        $r_countries = mysqli_query($dbc, $q_countries);
                        
                        while($row_country = mysqli_fetch_assoc($r_countries)) {
                            $selected = ($row_country['country_code'] == $form_country) ? 'selected' : '';
                            
                            echo '<option value="' . $row_country['country_code'] . '" ' . $selected . '>' . $row_country['name'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Poruka *</label>
                    <textarea name="subject" style="height:150px" required></textarea>
                </div>

                <div class="form-group checkbox-group">
                    <input type="checkbox" id="news" name="newsletter">
                    <label for="news">Pretplata na newsletter</label>
                </div>

                <button type="submit" name="submit_contact" class="btn-submit">Pošalji</button>
            </form>
        </div>
    </div>
</div>