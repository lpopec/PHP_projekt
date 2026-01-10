<?php
    session_start();
    include 'dbconn.php';

    $id_home     = 1;
    $id_news     = 2;
    $id_contact  = 3;
    $id_about    = 4;
    $id_admin    = 5;
    $id_register = 6;
    $id_login    = 7;
    $id_details  = 8;  
    $id_logout   = 9;
    $id_gallery  = 10;

    $link_home     = "index.php?menu=$id_home";
    $link_news     = "index.php?menu=$id_news";
    $link_contact  = "index.php?menu=$id_contact";
    $link_about    = "index.php?menu=$id_about";
    $link_admin    = "index.php?menu=$id_admin";
    $link_register = "index.php?menu=$id_register";
    $link_login    = "index.php?menu=$id_login";
    $link_logout   = "index.php?menu=$id_logout";
    $link_gallery  = "index.php?menu=$id_gallery";

    if (isset($_GET['menu'])) {
        $trenutna_stranica = (int)$_GET['menu']; 
    } else {
        $trenutna_stranica = $id_home; 
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <meta name="author" content="Lovro-Mijo Popec">
    <meta name="viewport" content="width=device-width, initial-scale=1.0; maximum-scale=1.0; user-scalable=0;">
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta name="description" content="Projekt PHP web-programiranje Lovro-Mijo Popec">
    <meta name="keywords" content="PHP, web, programiranje, web-programiranje">
    <link rel="shortcut icon" href="img/favicon/favicon.png" type="image/png">
    <title>Projekt PHP web-programiranje Lovro-Mijo Popec</title>
</head>
<body>
    
    <header>
        <nav class="nav-style">
            <ul>
                <li><a href="<?php echo $link_home; ?>" class="<?php echo ($trenutna_stranica == $id_home) ? 'active' : ''; ?>">Home</a></li>
                
                <li><a href="<?php echo $link_news; ?>" class="<?php echo ($trenutna_stranica == $id_news) ? 'active' : ''; ?>">News</a></li>
                
                <li><a href="<?php echo $link_gallery; ?>" class="<?php echo ($trenutna_stranica == $id_gallery) ? 'active' : ''; ?>">Gallery</a></li>
                
                <li><a href="<?php echo $link_contact; ?>" class="<?php echo ($trenutna_stranica == $id_contact) ? 'active' : ''; ?>">Contact</a></li>
                
                <li><a href="<?php echo $link_about; ?>" class="<?php echo ($trenutna_stranica == $id_about) ? 'active' : ''; ?>">About</a></li>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="<?php echo $link_admin; ?>" class="<?php echo ($trenutna_stranica == $id_admin) ? 'active' : ''; ?>">Admin</a></li>
                <?php endif; ?>

                <?php if(isset($_SESSION['user_id'])): ?>
                    
                    <li class="push-right user-greeting">
                        <span style="padding: 10px; font-weight:bold;">Bok, <?php echo $_SESSION['firstname']; ?>!</span>
                    </li>
                    <li><a href="<?php echo $link_logout; ?>" style="background-color:#333;">Odjava</a></li>
                
                <?php else: ?>
                    
                    <li class="push-right">
                        <a href="<?php echo $link_register; ?>" class="<?php echo ($trenutna_stranica == $id_register) ? 'active' : ''; ?>">Register</a>
                    </li>
                    <li>
                        <a href="<?php echo $link_login; ?>" class="<?php echo ($trenutna_stranica == $id_login) ? 'active' : ''; ?>">Login</a>
                    </li>
                
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main>
    <?php
        switch ($trenutna_stranica) {
            case $id_home:
                include("home.php");
                break;
                
            case $id_news:
                include("news.php");
                break;
                
            case $id_contact:
                include("contact.php");
                break;
                
            case $id_about:
                include("about-us.php");
                break;
                
            case $id_admin:
                if (isset($_SESSION['role']) && (
                    $_SESSION['role'] == 'admin' || 
                    $_SESSION['role'] == 'editor' || 
                    $_SESSION['role'] == 'user'
                )) {
                     include("admin.php"); 
                } else {
                     echo "<div style='padding:50px; text-align:center;'>
                            <h2>Zabranjen pristup!</h2>
                            <p>Morate biti prijavljeni da biste pristupili ovoj stranici.</p>
                          </div>";
                }
                break;
                
            case $id_register:
                include("register.php");
                break;
                
            case $id_login:
                include("login.php");
                break;
                
            case $id_details: 
                include("news/news_details.php"); 
                break;
                
            case $id_logout: 
                session_unset();  
                session_destroy(); 
                header("Location: $link_home");
                exit();
                
            case $id_gallery: 
                include("gallery.php"); 
                break;
                
            default:
                include("home.php");
                break;
        }
    ?>
    </main>
    
    <footer>
        <?php
            print '
                <p>Copyright &copy; ' . date("Y") . ' Lovro-Mijo Popec. 
                    <a href="https://github.com/lpopec" target="_blank">
                        <img src="img/icons/github-mark.svg" title="Github" alt="Github">
                    </a>
                </p>'
        ?> 
    </footer>
</body>
</html>