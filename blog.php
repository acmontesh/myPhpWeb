<?php
require "includes/app.php";
IncluirPlantilla("header");
$id = $_GET['id'];
$id = filter_var($id, FILTER_VALIDATE_INT);
if (!$id) {
    header('Location: /');
}
$db = conectarDB();
$query = "SELECT * FROM blogentries WHERE id=${id}";
$resultado = mysqli_query($db,$query);
$blogentry = mysqli_fetch_assoc($resultado);
?>

    <main>
        <div class="single-container">
            
            <h4><?php echo $blogentry['headline']; ?></h4>
            <div class="images-new blog">
                <img src="/Imagenes/<?php echo $blogentry['image1']; ?>" alt="">
                <?php
                if ($blogentry['image2']) {
                ?>
                <img src="/Imagenes/<?php echo $blogentry['image2']; ?>" alt="">
                <?php
                } ?>
            </div>
            <strong><?php echo $blogentry['month'] . ", " . $blogentry['year'] . ". "; ?></strong>
            <p><?php echo $blogentry['content']; ?></p>
            <div class="social-share">
                <div class="share-linkedin">
                    <script src="https://platform.linkedin.com/in.js" type="text/javascript">lang: en_US</script>
                    <script type="IN/Share" data-url="/"></script>
                </div>
                <div class="share-twitter">
                    <a class="twitter-share-button"
                    href="www.abraham-montes.com"
                    data-size="large">
                    Tweet</a>
                </div>
                
            </div>
        </div>    
    

        
    </main>

    
<script>window.twttr = (function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0],
    t = window.twttr || {};
  if (d.getElementById(id)) return t;
  js = d.createElement(s);
  js.id = id;
  js.src = "https://platform.twitter.com/widgets.js";
  fjs.parentNode.insertBefore(js, fjs);

  t._e = [];
  t.ready = function(f) {
    t._e.push(f);
  };

  return t;
}(document, "script", "twitter-wjs"));</script>


<?php
IncluirPlantilla("footer");
?>