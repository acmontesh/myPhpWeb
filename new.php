<?php
require "includes/app.php";
IncluirPlantilla("header");
$id = $_GET['id'];
$id = filter_var($id, FILTER_VALIDATE_INT);
if (!$id) {
    header('Location: /');
}
$db = conectarDB();
$query="SELECT * FROM news WHERE id = ${id}";
$new = mysqli_query($db, $query);
$new = mysqli_fetch_assoc($new);
?>

    <main>
        <div id="news-container" class="single-container">
            <div class="images-new">
                <img src="/build/img/<?php echo $new['image1']; ?>" alt="">
                <?php
                if ($new['image2']) {
                ?>
                <img src="/build/img/<?php echo $new['image2']; ?>" alt="">
                <?php
                } ?>
            </div>
            <h4><?php echo $new['headline']; ?></h4>
            <strong><?php echo $new['month'] . ", " . $new['year'] . ". "; ?></strong>
            <p><?php echo $new['content']; ?></p>
            <div class="go-back-button">
            <a href="/">
            Back
            </a>
        </div>
        </div>
        
    </main>

<?php
IncluirPlantilla("footer");
?>