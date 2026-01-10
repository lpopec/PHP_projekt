<?php
if (!isset($dbc)) { include 'dbconn.php'; }
$admin_action = isset($_GET['action']) ? $_GET['action'] : 'news';
?>

<div class="admin-wrapper">
    <div class="admin-tabs">
        <a href="index.php?menu=5&action=news" class="<?php echo ($admin_action == 'news') ? 'active-tab' : ''; ?>">Vijesti</a>
        <a href="index.php?menu=5&action=users" class="<?php echo ($admin_action == 'users') ? 'active-tab' : ''; ?>">Korisnici</a>
        
        <a href="index.php?menu=5&action=messages" class="<?php echo ($admin_action == 'messages') ? 'active-tab' : ''; ?>">Poruke</a>
    </div>

    <div class="admin-content">
        <?php
            if ($admin_action == 'users') {
                include 'admin/users_admin.php';
            } elseif ($admin_action == 'messages') {
                include 'admin/messages_admin.php'; 
            } else {
                include 'admin/news_admin.php';
            }
        ?>
    </div>
</div>