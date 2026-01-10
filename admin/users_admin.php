<?php
if (isset($_GET['delete_user_id'])) {
    $id = $_GET['delete_user_id'];
    mysqli_query($dbc, "DELETE FROM users WHERE id = $id");
    header("Location: index.php?menu=5&action=users");
    exit();
}

if (isset($_POST['save_user'])) {
    $username = mysqli_real_escape_string($dbc, $_POST['username']);
    $email = mysqli_real_escape_string($dbc, $_POST['email']);
    $first_name = mysqli_real_escape_string($dbc, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($dbc, $_POST['last_name']);
    $role = mysqli_real_escape_string($dbc, $_POST['role']);
    $id = $_POST['id'];
    
    $pass_query_part = "";
    if (!empty($_POST['password'])) {
        $hashed_pwd = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $pass_query_part = ", password='$hashed_pwd'";
    }

    if ($id) {
        $query = "UPDATE users SET 
                  username='$username', 
                  email='$email',
                  first_name='$first_name', 
                  last_name='$last_name', 
                  role='$role' 
                  $pass_query_part 
                  WHERE id=$id";
    } else {
        $hashed_pwd = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $query = "INSERT INTO users (username, password, email, first_name, last_name, role) 
                  VALUES ('$username', '$hashed_pwd', '$email', '$first_name', '$last_name', '$role')";
    }
    
    $result = mysqli_query($dbc, $query);
    
    if (!$result) {
        die("Greška kod spremanja korisnika: " . mysqli_error($dbc));
    }

    header("Location: index.php?menu=5&action=users");
    exit();
}

$edit_mode = isset($_GET['edit']);
$edit_row = [];
if ($edit_mode && isset($_GET['id'])) {
    $id = $_GET['id'];
    $r = mysqli_query($dbc, "SELECT * FROM users WHERE id=$id");
    $edit_row = mysqli_fetch_assoc($r);
}
?>

<?php if ($edit_mode || isset($_GET['add'])): ?>
    
    <div class="admin-form-container">
        <h3><?php echo isset($_GET['add']) ? 'Dodaj Novog Korisnika' : 'Uredi Korisnika'; ?></h3>
        <a href="index.php?menu=5&action=users" class="btn-back">&larr; Natrag</a>
        
        <form method="post" action="index.php?menu=5&action=users">
            <input type="hidden" name="id" value="<?php echo isset($edit_row['id']) ? $edit_row['id'] : ''; ?>">
            
            <div class="form-group">
                <label>Ime (First Name):</label>
                <input type="text" name="first_name" value="<?php echo isset($edit_row['first_name']) ? $edit_row['first_name'] : ''; ?>" required>
            </div>

            <div class="form-group">
                <label>Prezime (Last Name):</label>
                <input type="text" name="last_name" value="<?php echo isset($edit_row['last_name']) ? $edit_row['last_name'] : ''; ?>" required>
            </div>

            <div class="form-group">
                <label>E-mail:</label>
                <input type="email" name="email" value="<?php echo isset($edit_row['email']) ? $edit_row['email'] : ''; ?>" required>
            </div>

            <div class="form-group">
                <label>Korisničko ime (Username):</label>
                <input type="text" name="username" value="<?php echo isset($edit_row['username']) ? $edit_row['username'] : ''; ?>" required>
            </div>

            <div class="form-group">
                <label>Lozinka:</label>
                <input type="password" name="password" placeholder="<?php echo ($edit_mode) ? 'Ostavite prazno ako ne mijenjate' : 'Unesite lozinku'; ?>" <?php echo ($edit_mode) ? '' : 'required'; ?>>
            </div>

            <div class="form-group">
                <label>Uloga (Role):</label>
                <select name="role">
                    <option value="user" <?php if(isset($edit_row['role']) && $edit_row['role'] == 'user') echo 'selected'; ?>>Korisnik (User)</option>
                    <option value="admin" <?php if(isset($edit_row['role']) && $edit_row['role'] == 'admin') echo 'selected'; ?>>Administrator</option>
                </select>
            </div>
            
            <button type="submit" name="save_user" class="btn-submit">Spremi Korisnika</button>
        </form>
    </div>

<?php else: ?>

    <div class="table-actions">
        <a href="index.php?menu=5&action=users&add=1" class="btn-add-new">+ Novi Korisnik</a>
    </div>
    
    <div class="admin-table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Ime i Prezime</th>
                    <th>Username</th>
                    <th>E-mail</th>
                    <th>Uloga</th>
                    <th>Akcije</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $r = mysqli_query($dbc, "SELECT * FROM users ORDER BY id DESC");
                while($row = mysqli_fetch_assoc($r)) {
                    $role_style = ($row['role'] == 'admin') ? 'color:red; font-weight:bold;' : 'color:green;';
                    
                    echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['first_name']} {$row['last_name']}</td>
                        <td>{$row['username']}</td>
                        <td>{$row['email']}</td>
                        <td style='$role_style'>" . strtoupper($row['role']) . "</td>
                        <td>
                            <a href='index.php?menu=5&action=users&edit=1&id={$row['id']}' class='action-btn edit'>Uredi</a>
                            <a href='index.php?menu=5&action=users&delete_user_id={$row['id']}' class='action-btn delete' onclick='return confirm(\"Jeste li sigurni da želite obrisati korisnika {$row['username']}?\")'>Obriši</a>
                        </td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

<?php endif; ?>