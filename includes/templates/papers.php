<?php    
    $db = conectarDB();
    $query = "SELECT * FROM papers";
    $resultado = mysqli_query($db, $query);
?>


<div class="about-info">
    <div class="about-text">   
        <h1>Publications</h1>  

        <?php
        while($paper = mysqli_fetch_assoc($resultado)):
            $items[] = $paper;
        endwhile;
        $items = array_reverse($items ,true);
        foreach($items as $paper):
        ?>
        <div class="publication-box">
            <p><?php echo $paper['reference'] . " "?> <?php echo $paper['linkonepetro'] ? $paper['spe_code'] . ". " : ""?> <?php echo $paper['doi']?></p>
            <div class="iconos-papers">
                <?php if ($paper['linkscholar']) {?>
                <a href="<?php echo $paper['linkscholar']?>"><img class="icono" src="build/img/icono_scholar_dark.svg" alt=""></a>
                <?php }?>
                <?php if ($paper['linkonepetro']) {?>
                <a href="<?php echo $paper['linkonepetro']?>"><img class="icono" src="build/img/icono_onepetro.svg" alt=""></a>
                <?php }?>
                <?php if ($paper['linkgithub']) {?>
                <a href="<?php echo $paper['linkgithub']?>"><img class="icono" src="build/img/icono_github_dark.svg" alt=""></a>
                <?php }?>
                <?php if ($paper['linkjournal']) {?>
                <a href="<?php echo $paper['linkjournal']?>"><img class="icono" src="build/img/icono_journal.svg" alt=""></a>
                <?php }?>
            </div>
        </div>
        <?php
        endforeach;
        ?>
    </div>
</div><!-- Info container No. 3 - Publications Section -->

<?php
mysqli_close($db);
?>