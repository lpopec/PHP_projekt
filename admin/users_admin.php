<?php
global $dbc;
if (!$dbc) { include 'dbconn.php'; }

if (isset($_GET['delete_id'])) {
    $id = (int)$_GET['delete_id'];
    if ($id == $_SESSION['user_id']) {
        echo "<script>alert('Ne možete obrisati sami sebe!'); window.location='$link_admin&action=users';</script>"; exit();
    }
    mysqli_query($dbc, "DELETE FROM users WHERE id = $id");
    header("Location: $link_admin&action=users"); exit();
}

if (isset($_POST['save_user'])) {
    $id = $_POST['id'];
    $first_name = mysqli_real_escape_string($dbc, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($dbc, $_POST['last_name']);
    $email = mysqli_real_escape_string($dbc, $_POST['email']);
    $username = mysqli_real_escape_string($dbc, $_POST['username']);
    $role = mysqli_real_escape_string($dbc, $_POST['role']);
    $country_code = mysqli_real_escape_string($dbc, $_POST['country']);
    
    $password_sql = "";
    if (!empty($_POST['password'])) {
        $hashed_pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $password_sql = ", password = '$hashed_pass'";
    }

    if ($id) {
        $query = "UPDATE users SET first_name='$first_name', last_name='$last_name', email='$email', username='$username', role='$role', country_code='$country_code' $password_sql WHERE id=$id";
        mysqli_query($dbc, $query);
    } else {
        if (empty($_POST['password'])) {
            echo "<script>alert('Lozinka je obavezna!');</script>";
        } else {
            $hashed_pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $query = "INSERT INTO users (first_name, last_name, email, username, password, role, country_code) 
                      VALUES ('$first_name', '$last_name', '$email', '$username', '$hashed_pass', '$role', '$country_code')";
            mysqli_query($dbc, $query);
        }
    }
    header("Location: $link_admin&action=users"); exit();
}

$edit_mode = isset($_GET['edit']);
$user_row = [];
if ($edit_mode && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $r = mysqli_query($dbc, "SELECT * FROM users WHERE id=$id");
    $user_row = mysqli_fetch_assoc($r);
}
?>

<?php if ($edit_mode || isset($_GET['add'])): ?>
    
    <div class="admin-form-container">
        <h3><?php echo isset($_GET['add']) ? 'Dodaj korisnika' : 'Uredi korisnika'; ?></h3>
        <a href="<?php echo $link_admin; ?>&action=users" class="btn-back">&larr; Natrag</a>
        
        <form method="post" action="<?php echo $link_admin; ?>&action=users">
            <input type="hidden" name="id" value="<?php echo isset($user_row['id']) ? $user_row['id'] : ''; ?>">
            
            <div class="form-group"><label>Ime:</label><input type="text" name="first_name" value="<?php echo isset($user_row['first_name']) ? $user_row['first_name'] : ''; ?>" required></div>
            <div class="form-group"><label>Prezime:</label><input type="text" name="last_name" value="<?php echo isset($user_row['last_name']) ? $user_row['last_name'] : ''; ?>" required></div>
            <div class="form-group"><label>Email:</label><input type="email" name="email" value="<?php echo isset($user_row['email']) ? $user_row['email'] : ''; ?>" required></div>
            <div class="form-group"><label>Username:</label><input type="text" name="username" value="<?php echo isset($user_row['username']) ? $user_row['username'] : ''; ?>" required></div>
            
            <div class="form-group">
                <label>Država:</label>
                <select name="country" class="form-control">
                    <option value="">Odaberite državu...</option>
                    <?php
                    $q_c = "SELECT * FROM countries ORDER BY name ASC";
                    $r_c = mysqli_query($dbc, $q_c);
                    while($c = mysqli_fetch_assoc($r_c)) {
                        $selected = (isset($user_row['country_code']) && $user_row['country_code'] == $c['country_code']) ? 'selected' : '';
                        echo '<option value="'.$c['country_code'].'" '.$selected.'>'.$c['name'].'</option>';
                    }
                    ?>
                </select>
            </div>

            <div class="form-group"><label>Lozinka:</label><input type="password" name="password" placeholder="***"></div>
            
            <div class="form-group"><label>Uloga:</label>
                <select name="role" class="form-control" required>
                    <option value="user" <?php if(isset($user_row['role']) && $user_row['role'] == 'user') echo 'selected'; ?>>Korisnik</option>
                    <option value="editor" <?php if(isset($user_row['role']) && $user_row['role'] == 'editor') echo 'selected'; ?>>Editor</option>
                    <option value="admin" <?php if(isset($user_row['role']) && $user_row['role'] == 'admin') echo 'selected'; ?>>Admin</option>
                </select>
            </div>
            
            <button type="submit" name="save_user" class="btn-submit">Spremi</button>
        </form>
    </div>

<?php else: ?>

    <?php
    $results_per_page = 10;
    $sql_count = "SELECT count(id) AS total FROM users";
    $result_count = mysqli_query($dbc, $sql_count);
    $row_count = mysqli_fetch_assoc($result_count);
    $total_results = $row_count['total'];
    $number_of_pages = ceil($total_results / $results_per_page);
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $start_from = ($page - 1) * $results_per_page;
    ?>

    <div class="table-actions">
        <a href="<?php echo $link_admin; ?>&action=users&add=1" class="btn-add-new">+ Novi Korisnik</a>
    </div>
    
    <div class="admin-table-container">
        <h3>Popis korisnika</h3>
        <table>
            <thead><tr><th>Ime</th><th>Email</th><th>Država</th><th>Uloga</th><th>Akcije</th></tr></thead>
            <tbody>
                <?php
                $q = "SELECT users.*, countries.name AS country_name 
                      FROM users 
                      LEFT JOIN countries ON users.country_code = countries.country_code 
                      ORDER BY users.role ASC, users.last_name ASC 
                      LIMIT $start_from, $results_per_page";
                
                $r = mysqli_query($dbc, $q);
                
                while($row = mysqli_fetch_assoc($r)) {
                    $role_style = '';
                    if($row['role'] == 'admin') $role_style = 'font-weight:bold; color:red;';
                    elseif($row['role'] == 'editor') $role_style = 'font-weight:bold; color:blue;';
                    
                    $country_display = $row['country_name'] ? $row['country_name'] : '-';
                    
                    echo "<tr>
                        <td>{$row['first_name']} {$row['last_name']} ({$row['username']})</td>
                        <td>{$row['email']}</td>
                        <td>{$country_display}</td> <td style='$role_style'>" . ucfirst($row['role']) . "</td>
                        <td>
                            <a href='$link_admin&action=users&edit=1&id={$row['id']}' class='action-btn edit'>Uredi</a>"; 
                            if ($row['id'] != $_SESSION['user_id']) {
                                echo "<a href='$link_admin&action=users&delete_id={$row['id']}' class='action-btn delete' onclick='return confirm(\"Obrisati?\")'>Obriši</a>";
                            }
                    echo "</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <div style="margin-top: 20px; text-align: center;">
            <?php
            for ($i = 1; $i <= $number_of_pages; $i++) {
                $active = ($i == $page) ? "background-color: #007BFF; color: white;" : "background-color: #eee;";
                echo '<a href="' . $link_admin . '&action=users&page=' . $i . '" 
                         style="display:inline-block; padding:8px 12px; margin:2px; text-decoration:none; border-radius:4px; ' . $active . '">' . $i . '</a> ';
            }
            ?>
        </div>
    </div>
<?php endif; ?>