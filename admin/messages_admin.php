<?php
if (isset($_GET['delete_msg_id'])) {
    $id = $_GET['delete_msg_id'];
    mysqli_query($dbc, "DELETE FROM contact_messages WHERE id = $id");
    header("Location: index.php?menu=5&action=messages");
    exit();
}

if (isset($_GET['mark_read'])) {
    $id = $_GET['mark_read'];
    mysqli_query($dbc, "UPDATE contact_messages SET is_read = 1 WHERE id = $id");
    header("Location: index.php?menu=5&action=messages");
    exit();
}
?>

<div class="admin-table-container">
    <h3>Inbox Poruka</h3>
    
    <table>
        <thead>
            <tr>
                <th>Datum</th>
                <th>Pošiljatelj</th>
                <th>Email</th>
                <th>Poruka</th>
                <th>Akcije</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "SELECT * FROM contact_messages ORDER BY created_at DESC";
            $result = mysqli_query($dbc, $query);
            
            while($row = mysqli_fetch_assoc($result)) {
                $style = ($row['is_read'] == 0) ? "font-weight:bold; background-color:#f9f9f9;" : "";
                
                echo "<tr style='$style'>";
                echo "<td>" . date('d.m.Y H:i', strtotime($row['created_at'])) . "</td>";
                echo "<td>" . $row['first_name'] . " " . $row['last_name'] . "</td>";
                echo "<td><a href='mailto:" . $row['email'] . "'>" . $row['email'] . "</a></td>";
                
                echo "<td>" . nl2br($row['message']) . "</td>";
                
                echo "<td>";
                if($row['is_read'] == 0) {
                    echo "<a href='index.php?menu=5&action=messages&mark_read={$row['id']}' class='action-btn edit' style='background-color:#2ecc71'>✓ Pročitano</a> ";
                }
                
                echo "<a href='index.php?menu=5&action=messages&delete_msg_id={$row['id']}' class='action-btn delete' onclick='return confirm(\"Obrisati poruku?\")'>Obriši</a>";
                echo "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>