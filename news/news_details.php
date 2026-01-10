<?php
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); 
    
    $query = "SELECT * FROM news WHERE id = $id";
    $result = mysqli_query($dbc, $query);
    
    if ($row = mysqli_fetch_array($result)) {
        $img_src = !empty($row['image']) ? $row['image'] : '';
        $date = date('d. F Y.', strtotime($row['date'])); // Format: 10. October 2023.
        
        // Prikaz HTML-a
        echo '
        <div class="news-details-wrapper">
            
            <a href="index.php?menu=2" class="back-link">&larr; Natrag na vijesti</a>
            
            <article class="single-news">
                <header class="news-header">
                    <span class="news-date">' . $date . '</span>
                    <h1 class="news-title">' . $row['title'] . '</h1>
                </header>';

                if ($img_src) {
                    echo '
                    <figure class="news-banner">
                        <img src="' . $img_src . '" alt="' . $row['title'] . '">
                        <figcaption>' . $row['title'] . '</figcaption>
                    </figure>';
                }

                echo '
                <div class="news-body">
                    <p class="lead">' . $row['description'] . '</p>
                    <hr>
                    ' . nl2br($row['content']) . '
                </div>
            </article>
        </div>';
        
    } else {
        echo "<p>Tražena vijest ne postoji.</p>";
    }
} else {
    header("Location: index.php?menu=2");
}
?>