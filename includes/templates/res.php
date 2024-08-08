<?php    
    $db = conectarDB();
    $query = "SELECT * FROM research";
    $resultado = mysqli_query($db, $query);
    $query2 = "SELECT * FROM about";
    $resultado2 = mysqli_query($db, $query2);
?>


<div class="about-info">
    <div class="about-text">   
        <h1>Research</h1>                    
        <?php
        $texts=mysqli_fetch_assoc($resultado2);
        $resTextHeader  =   $texts['resheader'];
        echo $resTextHeader;
        ?>
        <?php
        while($res = mysqli_fetch_assoc($resultado)):
            $items[] = $res;
        endwhile;
        foreach($items as $res):
        ?>  
        <div class="research-boxes">
            <div class="research-box">
                <div class="header-resbox">
                    <p><?php echo $res['project_name'] ?></p>
                </div>
                <div class="moreinfo-box">
                    <p><?php echo $res["longdesc"] ?></p>
                    <div class="read-more-button">
                        <a href="<?php echo $res["link_paper"] ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-dots" width="60" height="30" viewBox="0 0 24 24" stroke-width="2" stroke="#9e9e9e" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M5 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                            <path d="M12 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                            <path d="M19 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                        </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <?php
        endforeach;
        ?>
    </div>
</div><!-- Info container No. 2 - Research Section -->

<?php
mysqli_close($db);
?>