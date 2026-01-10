<?php
global $dbc;
if (!$dbc){
    include 'dbconn.php';
}


$current_role = $_SESSION['role'];
$current_user_id = $_SESSION['user_id'];

if (isset($_GET['delete_gallery_img'])) {
    $img_id = (int)$_GET['delete_gallery_img'];
    $news_id = (int)$_GET['news_id'];
    
    if ($current_role == 'user') {
        $check_owner = mysqli_query($dbc, "SELECT id FROM news WHERE id = $news_id AND user_id = $current_user_id");
        if (mysqli_num_rows($check_owner) == 0) die("Zabranjeno.");
    }

    $q = "SELECT image_path FROM news_images WHERE id = $img_id";
    $r = mysqli_query($dbc, $q);
    $img_row = mysqli_fetch_assoc($r);
    if ($img_row && file_exists($img_row['image_path'])) { unlink($img_row['image_path']); }
    mysqli_query($dbc, "DELETE FROM news_images WHERE id = $img_id");
    
    header("Location: $link_admin&action=news&edit=1&id=$news_id");
    exit();
}

if (isset($_GET['delete_id'])) {
    $id = (int)$_GET['delete_id'];
    if ($current_role == 'user') {
        $check = mysqli_query($dbc, "SELECT id FROM news WHERE id=$id AND user_id=$current_user_id");
        if(mysqli_num_rows($check) == 0) {
            echo "<script>alert('Možete brisati samo svoje vijesti!'); window.location='$link_admin&action=news';</script>";
            exit();
        }
    }
    $q = "SELECT image FROM news WHERE id = $id";
    $r = mysqli_query($dbc, $q); $row = mysqli_fetch_assoc($r);
    if (!empty($row['image']) && file_exists($row['image'])) { unlink($row['image']); }
    $q_gal = "SELECT image_path FROM news_images WHERE news_id = $id";
    $r_gal = mysqli_query($dbc, $q_gal);
    while($img = mysqli_fetch_assoc($r_gal)) { if(file_exists($img['image_path'])) unlink($img['image_path']); }
    mysqli_query($dbc, "DELETE FROM news WHERE id = $id");
    header("Location: $link_admin&action=news");
    exit();
}

if (isset($_POST['save_news'])) {
    $title = mysqli_real_escape_string($dbc, $_POST['title']);
    $description = mysqli_real_escape_string($dbc, $_POST['description']);
    $content = mysqli_real_escape_string($dbc, $_POST['content']);
    $id = $_POST['id'];
    $archive = ($current_role == 'user') ? 'Y' : (isset($_POST['archive']) ? 'Y' : 'N');

    $image_path_db = $_POST['existing_image'];
    if (!empty($_FILES['image']['name'])) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $new_name = uniqid() . '.' . $ext;
        move_uploaded_file($_FILES['image']['tmp_name'], 'img/' . $new_name);
        $image_path_db = 'img/' . $new_name;
    }

    if ($id) {
        if ($current_role == 'user') {
            $check = mysqli_query($dbc, "SELECT id FROM news WHERE id=$id AND user_id=$current_user_id");
            if(mysqli_num_rows($check) == 0) die("Zabranjeno.");
        }
        $query = "UPDATE news SET title='$title', description='$description', content='$content', image='$image_path_db', archive='$archive' WHERE id=$id";
        mysqli_query($dbc, $query);
    } else {
        $query = "INSERT INTO news (title, description, content, image, archive, user_id) 
                  VALUES ('$title', '$description', '$content', '$image_path_db', '$archive', '$current_user_id')";
        mysqli_query($dbc, $query);
        $id = mysqli_insert_id($dbc);
    }
    
    if (!empty($_FILES['gallery']['name'][0])) {
        $files = $_FILES['gallery'];
        for ($i = 0; $i < count($files['name']); $i++) {
            if (!empty($files['name'][$i])) {
                $ext = pathinfo($files['name'][$i], PATHINFO_EXTENSION);
                $dest = 'img/' . uniqid('gal_') . '.' . $ext;
                if (move_uploaded_file($files['tmp_name'][$i], $dest)) {
                    mysqli_query($dbc, "INSERT INTO news_images (news_id, image_path) VALUES ($id, '$dest')");
                }
            }
        }
    }
    header("Location: $link_admin&action=news");
    exit();
}

$edit_mode = isset($_GET['edit']);
$edit_row = []; $gallery_images = [];
if ($edit_mode && isset($_GET['id'])) {
    $id = $_GET['id'];
    $query_cond = "WHERE id=$id";
    if ($current_role == 'user') $query_cond .= " AND user_id=$current_user_id";
    $r = mysqli_query($dbc, "SELECT * FROM news $query_cond");
    $edit_row = mysqli_fetch_assoc($r);
    if (!$edit_row && $current_role == 'user') {
        echo "<script>alert('Zabranjen pristup.'); window.location='$link_admin&action=news';</script>"; exit();
    }
    if ($edit_row) {
        $r_gal = mysqli_query($dbc, "SELECT * FROM news_images WHERE news_id=" . $edit_row['id']);
        while($img = mysqli_fetch_assoc($r_gal)) $gallery_images[] = $img;
    }
}
?>

<?php if ($edit_mode || isset($_GET['add'])): ?>
    <div class="admin-form-container">
        <h3><?php echo isset($_GET['add']) ? 'Nova Vijest' : 'Uredi Vijest'; ?></h3>
        <?php if($current_role == 'user'): ?>
            <div style="background:#fff3cd; color:#856404; padding:10px; margin-bottom:15px; border:1px solid #ffeeba;">Vaša vijest čeka odobrenje.</div>
        <?php endif; ?>
        <form method="post" action="<?php echo $link_admin; ?>&action=news" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo isset($edit_row['id']) ? $edit_row['id'] : ''; ?>">
            <input type="hidden" name="existing_image" value="<?php echo isset($edit_row['image']) ? $edit_row['image'] : ''; ?>">
            <div class="form-group"><label>Naslov:</label><input type="text" name="title" value="<?php echo isset($edit_row['title']) ? $edit_row['title'] : ''; ?>" required></div>
            <div class="form-group"><label>Opis:</label><textarea name="description" rows="3"><?php echo isset($edit_row['description']) ? $edit_row['description'] : ''; ?></textarea></div>
            <div class="form-group"><label>Sadržaj:</label><textarea name="content" rows="10" required><?php echo isset($edit_row['content']) ? $edit_row['content'] : ''; ?></textarea></div>
            <div class="form-group"><label>Glavna slika:</label><input type="file" name="image" accept="image/*"></div>
            <div class="form-group"><label>Galerija:</label><input type="file" name="gallery[]" multiple accept="image/*">
                <?php if (!empty($gallery_images)): ?>
                    <div style="margin-top:10px; display:flex; gap:10px;">
                        <?php foreach($gallery_images as $g): ?>
                            <div><img src="<?php echo $g['image_path']; ?>" width="50"><br><a href="<?php echo $link_admin; ?>&action=news&delete_gallery_img=<?php echo $g['id']; ?>&news_id=<?php echo $edit_row['id']; ?>" style="color:red">[x]</a></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            <?php if ($current_role == 'admin' || $current_role == 'editor'): ?>
                <div class="form-group checkbox-group"><label><input type="checkbox" name="archive" value="Y" <?php if(isset($edit_row['archive']) && $edit_row['archive'] == 'Y') echo "checked"; ?>> Spremi u arhivu</label></div>
            <?php endif; ?>
            <button type="submit" name="save_news" class="btn-submit">Spremi</button>
            <a href="<?php echo $link_admin; ?>&action=news" class="btn-back" style="float:right; margin-top:10px;">Odustani</a>
        </form>
    </div>
<?php else: ?>
    
    <?php
    $results_per_page = 10; 
    
    $count_sql = "SELECT COUNT(id) AS total FROM news";
    if ($current_role == 'user') { $count_sql .= " WHERE user_id = $current_user_id"; }
    
    $c_result = mysqli_query($dbc, $count_sql);
    $c_row = mysqli_fetch_assoc($c_result);
    $total_results = $c_row['total'];
    
    // 2. Broj stranica
    $number_of_pages = ceil($total_results / $results_per_page);
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $start_from = ($page - 1) * $results_per_page;
    ?>

    <div class="table-actions">
        <a href="<?php echo $link_admin; ?>&action=news&add=1" class="btn-add-new">+ Nova Vijest</a>
    </div>
    
    <div class="admin-table-container">
        <h3>Popis vijesti (Ukupno: <?php echo $total_results; ?>)</h3>
        <table>
            <thead><tr><th>ID</th><th>Naslov</th><th>Autor ID</th><th>Status</th><th>Akcije</th></tr></thead>
            <tbody>
                <?php
                $list_query = "SELECT * FROM news";
                if ($current_role == 'user') { $list_query .= " WHERE user_id = $current_user_id"; }
                $list_query .= " ORDER BY date DESC LIMIT $start_from, $results_per_page";
                
                $r = mysqli_query($dbc, $list_query);
                while($row = mysqli_fetch_assoc($r)) {
                    $status = ($row['archive'] == 'Y') ? '<span style="color:red">Arhivirano</span>' : '<span style="color:green">Objavljeno</span>';
                    echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['title']}</td>
                        <td>{$row['user_id']}</td>
                        <td>{$status}</td>
                        <td>
                            <a href='$link_admin&action=news&edit=1&id={$row['id']}' class='action-btn edit'>Uredi</a>
                            <a href='$link_admin&action=news&delete_id={$row['id']}' class='action-btn delete' onclick='return confirm(\"Brisati?\")'>Obriši</a>
                        </td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>

        <div style="margin-top: 20px; text-align: center;">
            <?php
            for ($i = 1; $i <= $number_of_pages; $i++) {
                $active = ($i == $page) ? "background-color: #007BFF; color: white;" : "background-color: #eee;";
                echo '<a href="' . $link_admin . '&action=news&page=' . $i . '" 
                         style="display:inline-block; padding:8px 12px; margin:2px; text-decoration:none; border-radius:4px; ' . $active . '">' 
                         . $i . '</a> ';
            }
            ?>
        </div>
    </div>
<?php endif; ?>