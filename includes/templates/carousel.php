<?php

$db=conectarDB();
$query="SELECT * FROM quotes";
$resultado = mysqli_query($db, $query);
?>

<div class="carousel-container">
    <h3>Quotes I Like</h3>
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