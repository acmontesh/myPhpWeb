<?php
require "includes/app.php";
IncluirPlantilla("header");
$db=conectarDB();
$query="SELECT * FROM quotes";
$resultado = mysqli_query($db, $query);
?>

<main>
    <div class="single-container">
        <h1>Base</h1>
        <p>"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut 
            labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco 
            laboris nisi ut aliquip ex ea commodo consequat. 
            Duis aute irure dolor in reprehenderit in voluptate velit esse</p>
    </div>

<div class="carousel-container">
    <div class="all-quotes">
        <?php
        $idx = 0;
        while($quote = mysqli_fetch_assoc($resultado)):
        ?>
        <div class="quote-container <?php 
                                    switch ($idx) {
                                        case 0:
                                            echo "previous-left-quote";
                                            break;
                                        case 1:
                                            echo "previous-quote";
                                            break;
                                        case 2:
                                            echo "current-quote";
                                            break;
                                        case 3:
                                            echo "later-quote";
                                            break;
                                    }?>">
            <div class="quote-image" style="background: url(/Imagenes/<?php echo $quote['image'] ?>.webp); background-size: cover; background-position: center;"></div>
            <div class="quote-text">
                <img src="/build/img/quote_mark.svg" alt="">    
                <p><?php echo $quote['quote'] ?></p><p>-<?php echo $quote['author'] ?></p>
            </div>
        </div>      
        <?php $idx=$idx+1; 
                endwhile;?>  
    </div>
    
    <div class="pips">
    <?php  
        for($i=0;$i<=$idx;$i++){ ?>
        <div class="pip <?php echo $i==2 ? "current-pip" : ''; ?>"></div>
    <?php } ?>
    </div>
</div>
    </main>

<?php
IncluirPlantilla("footer");
?>
