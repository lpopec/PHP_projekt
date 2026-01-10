<?php
if (isset($_GET['delete_msg_id'])) {
    $id = (int)$_GET['delete_msg_id'];
    mysqli_query($dbc, "DELETE FROM contact_messages WHERE id = $id");
    header("Location: $link_admin&action=messages");
    exit();
}

if (isset($_GET['mark_read'])) {
    $id = (int)$_GET['mark_read'];
    mysqli_query($dbc, "UPDATE contact_messages SET is_read = 1 WHERE id = $id");
    header("Location: $link_admin&action=messages");
    exit();
}

$results_per_page = 10; 

$sql = "SELECT count(id) AS total FROM contact_messages";
$result = mysqli_query($dbc, $sql);
$row = mysqli_fetch_assoc($result);
$total_results = $row['total'];

$number_of_pages = ceil($total_results / $results_per_page);

if (!isset($_GET['page'])) {
    $page = 1;
} else {
    $page = (int)$_GET['page'];
}

$this_page_first_result = ($page - 1) * $results_per_page;
?>

<div class="admin-table-container">
    <h3>Inbox Poruka (Ukupno: <?php echo $total_results; ?>)</h3>
    
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
            $query = "SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT " . $this_page_first_result . ',' .  $results_per_page;
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
                    echo "<a href='$link_admin&action=messages&mark_read={$row['id']}' class='action-btn edit' style='background-color:#2ecc71'>✓</a> ";
                }
                echo "<a href='$link_admin&action=messages&delete_msg_id={$row['id']}' class='action-btn delete' onclick='return confirm(\"Obrisati poruku?\")'>X</a>";
                
                echo "</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <div style="margin-top: 20px; text-align: center;">
        <?php
        for ($i = 1; $i <= $number_of_pages; $i++) {
            $active_style = ($i == $page) ? "background-color: #007BFF; color: white;" : "background-color: #eee;";
            
            echo '<a href="' . $link_admin . '&action=messages&page=' . $i . '" 
                     style="display:inline-block; padding:8px 12px; margin:2px; text-decoration:none; border-radius:4px; ' . $active_style . '">' 
                     . $i . '</a> ';
        }
        ?>
    </div>
</div>