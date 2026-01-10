<div class="gallery-wrapper" id="gallery-top">
    <h1 class="page-title">Galerija slika</h1>
    <p class="gallery-intro">Kliknite na sliku za veći prikaz.</p>

    <div class="gallery-grid">
        <?php
        $query = "SELECT id, title, image FROM news 
                  WHERE image != '' AND image IS NOT NULL AND archive = 'N' 
                  ORDER BY date DESC";
        
        $result = mysqli_query($dbc, $query);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                
                $img_path = $row['image'];
                $title = $row['title'];
                $id = $row['id'];
                
                $popup_id = "popup-" . $id;

                echo '
                <div class="gallery-item">
                    <a href="#' . $popup_id . '">
                        <figure>
                            <img src="' . $img_path . '" alt="' . $title . '">
                            <figcaption>
                                <span>' . $title . '</span>
                            </figcaption>
                        </figure>
                    </a>
                </div>

                <div id="' . $popup_id . '" class="css-lightbox">
                    <a href="#gallery-top" class="close-overlay"></a>
                    
                    <div class="lightbox-content">
                        <a href="#gallery-top" class="close-btn">&times;</a>
                        
                        <img src="' . $img_path . '" alt="' . $title . '">
                        
                        <div class="lightbox-caption">
                            <h3>' . $title . '</h3>
                            <a href="index.php?menu=8&id=' . $id . '">Pročitaj cijelu vijest &rarr;</a>
                        </div>
                    </div>
                </div>';
            }
        } else {
            echo '<p>Nema slika u galeriji.</p>';
        }
        ?>
    </div>
</div>