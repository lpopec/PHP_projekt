<div class="gallery-wrapper" id="gallery-top">
    <h1 class="page-title">Galerija slika</h1>
    <p class="gallery-intro">Kliknite na album za pregled svih slika članka.</p>

    <div class="gallery-grid">
        <?php
        $query = "SELECT id, title, image FROM news 
                  WHERE image != '' AND image IS NOT NULL AND archive = 'N' 
                  ORDER BY date DESC";
        
        $result = mysqli_query($dbc, $query);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                
                $news_id = $row['id'];
                $main_img = $row['image'];
                $title = $row['title'];
                
                $all_images = array($main_img);
                
                $q_sub = "SELECT image_path FROM news_images WHERE news_id = $news_id";
                $r_sub = mysqli_query($dbc, $q_sub);
                while($sub_row = mysqli_fetch_assoc($r_sub)){
                    $all_images[] = $sub_row['image_path'];
                }
                
                $json_images = htmlspecialchars(json_encode($all_images), ENT_QUOTES, 'UTF-8');
                $count_images = count($all_images);
                
                echo '
                <div class="gallery-item" onclick="openAlbum(' . $json_images . ', \'' . addslashes($title) . '\', ' . $news_id . ')">
                    <figure>
                        <img src="' . $main_img . '" alt="' . $title . '">
                        
                        <div class="album-icon">
                            <i class="fa fa-camera"></i> ' . $count_images . ' slika
                        </div>

                        <figcaption>
                            <span>' . $title . '</span>
                        </figcaption>
                    </figure>
                </div>';
            }
        } else {
            echo '<p>Nema slika u galeriji.</p>';
        }
        ?>
    </div>
</div>

<div id="galleryModal" class="modal-slideshow">
    <span class="close-slideshow" onclick="closeAlbum()">&times;</span>
    
    <div class="slideshow-content">
        <img id="currentSlide" src="">
        
        <a class="prev-btn" onclick="changeSlide(-1)">&#10094;</a>
        <a class="next-btn" onclick="changeSlide(1)">&#10095;</a>
        
        <div class="slide-caption">
            <h3 id="slideTitle"></h3>
            <p id="slideCount"></p>
            <a id="slideLink" href="#" class="modal-link-btn">Pročitaj članak</a>
        </div>
    </div>
</div>

<script>
    var currentAlbum = [];
    var currentIndex = 0; 

    var modal = document.getElementById("galleryModal");
    var imgElement = document.getElementById("currentSlide");
    var titleElement = document.getElementById("slideTitle");
    var countElement = document.getElementById("slideCount");
    var linkElement = document.getElementById("slideLink");

    function openAlbum(images, title, id) {
        currentAlbum = images; 
        currentIndex = 0; 
        
        titleElement.innerText = title;
        linkElement.href = "index.php?menu=8&id=" + id;
        
        updateSlide();
        modal.style.display = "flex";
    }

    function closeAlbum() {
        modal.style.display = "none";
    }

    function changeSlide(n) {
        currentIndex += n;
        
        if (currentIndex >= currentAlbum.length) { currentIndex = 0; }
        if (currentIndex < 0) { currentIndex = currentAlbum.length - 1; }
        
        updateSlide();
    }

    function updateSlide() {
        imgElement.src = currentAlbum[currentIndex];
        countElement.innerText = "Slika " + (currentIndex + 1) + " od " + currentAlbum.length;
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            closeAlbum();
        }
    }
    
    document.addEventListener('keydown', function(event) {
        if (modal.style.display === "flex") {
            if (event.key === "ArrowLeft") changeSlide(-1);
            if (event.key === "ArrowRight") changeSlide(1);
            if (event.key === "Escape") closeAlbum();
        }
    });
</script>