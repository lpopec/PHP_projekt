<?php
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "SELECT * FROM news WHERE id = $id";
    $result = mysqli_query($dbc, $query);
    
    if ($row = mysqli_fetch_array($result)) {
        $img_src = !empty($row['image']) ? $row['image'] : '';
        $date = date('d. F Y.', strtotime($row['date'])); 
        
        $gallery_query = "SELECT * FROM news_images WHERE news_id = $id";
        $gallery_result = mysqli_query($dbc, $gallery_query);

        echo '
        <div class="news-details-wrapper">
            <a href="' . $link_news . '" class="back-link">&larr; Natrag na vijesti</a>
            
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
                </div>';

                if (mysqli_num_rows($gallery_result) > 0) {
                    echo '<div class="news-gallery-section">';
                    echo '<h3>Galerija fotografija</h3>';
                    echo '<div class="news-gallery-grid">';
                    
                    while($g_row = mysqli_fetch_assoc($gallery_result)) {
                        echo '<div class="news-gallery-item">
                                <img src="' . $g_row['image_path'] . '" onclick="openModal(\'' . $g_row['image_path'] . '\')">
                              </div>';
                    }
                    
                    echo '</div></div>';
                }
            echo '</article>
        </div>';
        
    } else {
        echo "<p>Tražena vijest ne postoji.</p>";
    }
} else {
    header("Location: $link_news");
}
?>

<div id="imageModal" class="modal-simple" onclick="this.style.display='none'">
  <span class="close-simple">&times;</span>
  <img class="modal-content-simple" id="img01">
</div>

<script>
function openModal(src) {
    var modal = document.getElementById("imageModal");
    var modalImg = document.getElementById("img01");
    modal.style.display = "block";
    modalImg.src = src;
}
</script>