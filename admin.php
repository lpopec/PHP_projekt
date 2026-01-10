<?php
if (!isset($dbc)) { include 'dbconn.php'; }
$admin_action = isset($_GET['action']) ? $_GET['action'] : 'news';
$role = $_SESSION['role'];
?>

<div class="admin-wrapper">
    <div class="admin-tabs">
        <a href="<?php echo $link_admin; ?>&action=news" class="<?php echo ($admin_action == 'news') ? 'active-tab' : ''; ?>">Vijesti</a>
        
        <?php if ($role == 'admin'): ?>
            <a href="<?php echo $link_admin; ?>&action=users" class="<?php echo ($admin_action == 'users') ? 'active-tab' : ''; ?>">Korisnici</a>  
            
            <a href="<?php echo $link_admin; ?>&action=messages" class="<?php echo ($admin_action == 'messages') ? 'active-tab' : ''; ?>">Poruke</a>
        <?php endif; ?>
    </div>

    <div class="admin-content">
        <?php
            if ($admin_action == 'users' && $role == 'admin') {
                include 'admin/users_admin.php';
            } elseif ($admin_action == 'messages') {
                include 'admin/messages_admin.php'; 
            } else {
                include 'admin/news_admin.php';
            }
        ?>
    </div>
</div>