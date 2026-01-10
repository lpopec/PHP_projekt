<?php
$form_fname = '';
$form_lname = '';
$form_email = '';

if (isset($_SESSION['user_id'])) {
    $id = $_SESSION['user_id'];
    $query = "SELECT first_name, last_name, email FROM users WHERE id = $id";
    $result = mysqli_query($dbc, $query);
    
    if ($row = mysqli_fetch_assoc($result)) {
        $form_fname = $row['first_name'];
        $form_lname = $row['last_name'];
        $form_email = $row['email'];
    }
}

$msg = '';
$msgClass = '';

if (isset($_POST['submit_contact'])) {
    $form_fname = $_POST['firstname']; 
    $form_lname = $_POST['lastname'];
    $form_email = $_POST['email'];
    
    $firstname = mysqli_real_escape_string($dbc, $_POST['firstname']);
    $lastname = mysqli_real_escape_string($dbc, $_POST['lastname']);
    $email = mysqli_real_escape_string($dbc, $_POST['email']);
    $country = mysqli_real_escape_string($dbc, $_POST['country']);
    $message_body = mysqli_real_escape_string($dbc, $_POST['subject']);
    $newsletter = isset($_POST['newsletter']) ? 'DA' : 'NE';
    
    $query = "INSERT INTO contact_messages (first_name, last_name, email, country, message, newsletter) 
              VALUES ('$firstname', '$lastname', '$email', '$country', '$message_body', '$newsletter')";
    
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
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2781.7890741539636!2d15.966568315568852!3d45.8150109791062!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4765d6fe6907578f%3A0x6e760c23d027f310!2sTrg%20bana%20Josipa%20Jela%C4%8Di%C4%87a!5e0!3m2!1shr!2shr!4v1626251234567!5m2!1shr!2shr" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
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
                        <option value="Hrvatska">Hrvatska</option>
                        <option value="Njemačka">Njemačka</option>
                        <option value="BiH">BiH</option>
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