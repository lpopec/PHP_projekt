<?php
global $dbc;
if (!$dbc || $dbc === 1 || $dbc === true || !($dbc instanceof mysqli)) {
    if (file_exists('konekcija.php')) { include 'konekcija.php'; }
    elseif (file_exists('../konekcija.php')) { include '../konekcija.php'; }
}

if (isset($_GET['delete_id'])) {
    $id = (int)$_GET['delete_id'];

    $query = "SELECT image FROM news WHERE id = $id";
    $result = mysqli_query($dbc, $query);
    $row = mysqli_fetch_assoc($result);
    
    $image_path = $row['image'];

    if (!empty($image_path) && file_exists($image_path)) {
        unlink($image_path);
    }

    mysqli_query($dbc, "DELETE FROM news WHERE id = $id");
    
    header("Location: index.php?menu=5&action=news");
    exit();
}

if (isset($_POST['save_news'])) {
    $title = mysqli_real_escape_string($dbc, $_POST['title']);
    $description = mysqli_real_escape_string($dbc, $_POST['description']);
    $content = mysqli_real_escape_string($dbc, $_POST['content']);
    $archive = isset($_POST['archive']) ? 'Y' : 'N';
    $id = $_POST['id'];

    $picture = $_FILES['image']['name']; 
    $target_dir = 'img/'; 
    
    if (!empty($picture)) {
        $unique_image_name = time() . '_' . $picture; 
        $target_file = $target_dir . $unique_image_name;
        
        move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
        
        $image_path_db = 'img/' . $unique_image_name;
    } else {
        $image_path_db = $_POST['existing_image'];
    }

    if ($id) {
        $query = "UPDATE news SET 
                  title='$title', 
                  description='$description', 
                  content='$content', 
                  image='$image_path_db', 
                  archive='$archive' 
                  WHERE id=$id";
    } else {
        $query = "INSERT INTO news (title, description, content, image, archive) 
                  VALUES ('$title', '$description', '$content', '$image_path_db', '$archive')";
    }
    
    $result = mysqli_query($dbc, $query);
    if (!$result) die("Greška: " . mysqli_error($dbc));

    header("Location: index.php?menu=5&action=news");
    exit();
}

$edit_mode = isset($_GET['edit']);
$edit_row = [];
if ($edit_mode && isset($_GET['id'])) {
    $id = $_GET['id'];
    $r = mysqli_query($dbc, "SELECT * FROM news WHERE id=$id");
    $edit_row = mysqli_fetch_assoc($r);
}
?>

<?php if ($edit_mode || isset($_GET['add'])): ?>
    
    <div class="admin-form-container">
        <h3><?php echo isset($_GET['add']) ? 'Dodaj Vijest' : 'Uredi Vijest'; ?></h3>
        <a href="index.php?menu=5&action=news" class="btn-back">&larr; Natrag</a>
        
        <form method="post" action="index.php?menu=5&action=news" enctype="multipart/form-data">
            
            <input type="hidden" name="id" value="<?php echo isset($edit_row['id']) ? $edit_row['id'] : ''; ?>">
            <input type="hidden" name="existing_image" value="<?php echo isset($edit_row['image']) ? $edit_row['image'] : ''; ?>">
            
            <div class="form-group">
                <label>Naslov:</label>
                <input type="text" name="title" value="<?php echo isset($edit_row['title']) ? $edit_row['title'] : ''; ?>" required>
            </div>

            <div class="form-group">
                <label>Kratki opis:</label>
                <textarea name="description" rows="3"><?php echo isset($edit_row['description']) ? $edit_row['description'] : ''; ?></textarea>
            </div>

            <div class="form-group">
                <label>Sadržaj:</label>
                <textarea name="content" rows="10" required><?php echo isset($edit_row['content']) ? $edit_row['content'] : ''; ?></textarea>
            </div>

            <div class="form-group">
                <label>Slika:</label>
                
                <?php if(isset($edit_row['image']) && $edit_row['image'] != ""): ?>
                    <div style="margin-bottom:10px;">
                        <img src="<?php echo $edit_row['image']; ?>" width="100" style="border:1px solid #ccc; padding:2px;">
                        <br><small>Trenutna slika</small>
                    </div>
                <?php endif; ?>

                <input type="file" name="image" accept="image/*">
                <small style="color:#888;">Odaberite novu sliku ako želite zamijeniti trenutnu.</small>
            </div>

            <div class="form-group checkbox-group">
                <label>
                    <input type="checkbox" name="archive" value="Y" 
                        <?php if(isset($edit_row['archive']) && $edit_row['archive'] == 'Y') echo "checked"; ?> 
                        style="width:auto; margin-right:10px;">
                    Spremi u arhivu
                </label>
            </div>
            
            <button type="submit" name="save_news" class="btn-submit">Spremi</button>
        </form>
    </div>

<?php else: ?>

    <div class="table-actions">
        <a href="index.php?menu=5&action=news&add=1" class="btn-add-new">+ Nova Vijest</a>
    </div>
    
    <div class="admin-table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th width="80">Slika</th> <th>Naslov</th>
                    <th>Datum</th>
                    <th>Arhiva</th>
                    <th>Akcije</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $r = mysqli_query($dbc, "SELECT * FROM news ORDER BY date DESC");
                while($row = mysqli_fetch_assoc($r)) {
                    $archive_status = ($row['archive'] == 'Y') ? '<span style="color:red; font-weight:bold;">DA</span>' : '<span style="color:green;">NE</span>';
                    
                    $img_display = ($row['image']) ? "<img src='" . $row['image'] . "' width='60' height='40' style='object-fit:cover;'>" : "Nema slike";

                    echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$img_display}</td>
                        <td>{$row['title']}</td>
                        <td>" . date('d.m.Y H:i', strtotime($row['date'])) . "</td>
                        <td>{$archive_status}</td>
                        <td>
                            <a href='index.php?menu=5&action=news&edit=1&id={$row['id']}' class='action-btn edit'>Uredi</a>
                            <a href='index.php?menu=5&action=news&delete_id={$row['id']}' class='action-btn delete' onclick='return confirm(\"Brisati?\")'>Obriši</a>
                        </td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

<?php endif; ?>