<div class="news-wrapper">
    <h1 class="page-title">Najnovije Vijesti</h1>
    
    <div class="news-grid">
        <?php
        $query = "SELECT * FROM news WHERE archive = 'N' ORDER BY date DESC";
        $result = mysqli_query($dbc, $query);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result)) {
                
                $img_src = !empty($row['image']) ? $row['image'] : 'https://placehold.co/600x400?text=No+Image';
                
                $date = date('d.m.Y.', strtotime($row['date']));
                
                echo '
                <article class="news-card">
                    <div class="news-image">
                        <img src="' . $img_src . '" alt="' . $row['title'] . '">
                    </div>
                    <div class="news-content">
                        <time datetime="' . $row['date'] . '">' . $date . '</time>
                        <h2>' . $row['title'] . '</h2>
                        <p>' . $row['description'] . '</p> <a href="index.php?menu=8&id=' . $row['id'] . '" class="read-more">Pročitaj više &rarr;</a>
                    </div>
                </article>';
            }
        } else {
            echo '<p>Trenutno nema novosti.</p>';
        }
        ?>
    </div>
</div>