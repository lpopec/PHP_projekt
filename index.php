<?php
    session_start();
    include 'dbconn.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <meta name="author" content="Lovro-Mijo Popec">
    <meta name="viewport" content="width=device-width, initial-scale=1.0; maximum-scale=1.0; user-scalable=0;">
    <meta http-equiv="content-type" content="text/html"; charset="utf-8">
    <meta name="description" content="Projekt PHP web-programiranje Lovro-Mijo Popec">
    <meta name="keywords" content="PHP, web, programiranje, web-programiranje">
    <link rel="shortcut icon" href="img/favicon/favicon.png" type="image/png">
    <title>Projekt PHP web-programiranje Lovro-Mijo Popec</title>
</head>
<body>
    <?php
        if (isset($_GET['menu'])) {
            $trenutna_stranica = $_GET['menu'];
        } 
        else {
            $trenutna_stranica = 1;
        }
    ?>
    <header>
        <nav class="nav-style">
            <ul>
                <li><a href="index.php?menu=1" class="<?php echo ($trenutna_stranica == 1) ? 'active' : ''; ?>">Home</a></li>
                <li><a href="index.php?menu=2" class="<?php echo ($trenutna_stranica == 2) ? 'active' : ''; ?>">News</a></li>
                <li><a href="index.php?menu=10" class="<?php echo ($trenutna_stranica == 10) ? 'active' : ''; ?>">Gallery</a></li>
                <li><a href="index.php?menu=3" class="<?php echo ($trenutna_stranica == 3) ? 'active' : ''; ?>">Contact</a></li>
                <li><a href="index.php?menu=4" class="<?php echo ($trenutna_stranica == 4) ? 'active' : ''; ?>">About</a></li>

                <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                    <li><a href="index.php?menu=5" class="<?php echo ($trenutna_stranica == 5) ? 'active' : ''; ?>">Admin</a></li>
                <?php endif; ?>

                <?php if(isset($_SESSION['user_id'])): ?>
                    <li class="push-right user-greeting"><span style="padding: 10px; font-weight:bold;">Bok, <?php echo $_SESSION['firstname']; ?>!</span></li>
                    <li><a href="index.php?menu=9" style="background-color:#333;">Odjava</a></li>
                <?php else: ?>
                    <li class="push-right"><a href="index.php?menu=6" class="<?php echo ($trenutna_stranica == 5) ? 'active' : ''; ?>">Register</a></li>
                    <li><a href="index.php?menu=7" class="<?php echo ($trenutna_stranica == 6) ? 'active' : ''; ?>">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    <main>
    <?php
        switch ($trenutna_stranica) {
            case 1:
                include("home.php");
                break;
            case 2:
                include("news.php");
                break;
            case 3:
                include("contact.php");
                break;
            case 4:
                include("about-us.php");
                break;
            case 5:
                include("admin.php");
                break;
            case 6:
                include("register.php");
                break;
            case 7:
                include("login.php");
                break;
            case 8: 
                include("news/news_details.php"); 
                break;
            case 9: 
                session_unset();  
                session_destroy(); 
                header("Location: index.php"); 
                exit();
            case 10: 
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