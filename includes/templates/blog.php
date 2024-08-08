<?php
$db=conectarDB();
$query="SELECT * FROM blogentries";
$resultado = mysqli_query($db, $query);
?>

<div class="about-info">
    <div class="about-text">   
        <h1>Blog</h1>    
        
        <?php
        $pinnedBlogs = [];
        $unpinnedBlogs = [];
        $nPinned = 0;
        while($blogentry=mysqli_fetch_assoc($resultado)):
            if ($blogentry['pinned']){
                $nPinned= $nPinned + 1;
                $pinnedBlogs[] = $blogentry;
            } else{
                $unpinnedBlogs[] = $blogentry;
            }   
        endwhile;
        if ($nPinned>0) {
        ?>
        <h4>Pinned Blog Entries</h4>
        <div class="pinned-news-box">
            <?php 
            foreach($pinnedBlogs as $blogentry): ?>
            <div class="pinned-new" style="background: linear-gradient(to top, rgba(0, 0, 0, 1.0) 0%, rgba(117, 117, 117, 0.5) 60%, rgba(49, 48, 49, 0) 100%), url(./../../../Imagenes/<?php echo $blogentry['image1'] ?>) center; background-size:cover;">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-pinned-filled" width="42" height="42" viewBox="0 0 24 24" stroke-width="1.5" stroke="#000000" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M16 3a1 1 0 0 1 .117 1.993l-.117 .007v4.764l1.894 3.789a1 1 0 0 1 .1 .331l.006 .116v2a1 1 0 0 1 -.883 .993l-.117 .007h-4v4a1 1 0 0 1 -1.993 .117l-.007 -.117v-4h-4a1 1 0 0 1 -.993 -.883l-.007 -.117v-2a1 1 0 0 1 .06 -.34l.046 -.107l1.894 -3.791v-4.762a1 1 0 0 1 -.117 -1.993l.117 -.007h8z" stroke-width="0" fill="currentColor" />
                </svg>
                <div class="content-pinned-new">
                    <p><strong><?php echo $blogentry['month'] . ', ' . $blogentry['year'] . ": " ?></strong><?php echo $blogentry['headline'] ?></p>
                    <a href="/blog.php?id=<?php echo $blogentry['id'] ?>">
                        <div class="read-more-button">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-dots" width="60" height="30" viewBox="0 0 24 24" stroke-width="2" stroke="#9e9e9e" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M5 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                                <path d="M12 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                                <path d="M19 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                            </svg>
                        </div>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>     
        <?php } ?> 
        <div class="other-news-box">
            <?php
            if ($nPinned>0) {
            ?>
            <h4>Other Blog Entries</h4>
            <?php };
            foreach($unpinnedBlogs as $blogentry):
            ?>
            <div class="other-new">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-article" width="37" height="37" viewBox="0 0 24 24" stroke-width="2" stroke="#33302B" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M3 4m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" />
                    <path d="M7 8h10" />
                    <path d="M7 12h10" />
                    <path d="M7 16h10" />
                </svg>
                <div class="content-other-new">
                    <p><strong><?php echo $blogentry['month'] . ', ' . $blogentry['year'] . ": " ?></strong><?php echo $blogentry['headline'] ?></p>
                    <a href="/blog.php?id=<?php echo $blogentry['id'] ?>">
                        <div class="read-more-button">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-dots" width="60" height="30" viewBox="0 0 24 24" stroke-width="2" stroke="#9e9e9e" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M5 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                                <path d="M12 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                                <path d="M19 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                            </svg>
                        </div>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>

        </div>           
    </div>
</div><!-- Info container No. 5 - Blog -->