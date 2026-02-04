<?php
ob_start();
$_ENV['lang']='EN';
require "includes/app.php";
$inicio=true;
IncluirPlantilla("header", $inicio);
?>
    <main>                    
        <div class="about-me">
            <div class="info-container" id="info-container">
                <div class="foto-and-info" id="foto-and-info">
                    <img src="build/img/photo.webp" alt="Abraham C. Montes">
                    <div class="texto-inicio">
                        PhD Candidate | Drilling Automation<br></p>
                        <div class="iconos">
                            <a href="https://scholar.google.com/citations?user=Va0vQdsAAAAJ&hl=es"><img class="icono" src="build/img/icono_scholar_dark.svg" href="https://scholar.google.com/citations?user=Va0vQdsAAAAJ&hl=es" alt=""/></a>
                            <a href="https://www.linkedin.com/in/abraham-c-montes-6661a841/"><img class="icono" src="build/img/icono_linkedin_dark.svg" href="https://www.linkedin.com/in/abraham-c-montes-6661a841/" alt=""/></a>
                            <a href="https://www.researchgate.net/profile/Abraham-Montes-4"><img class="icono" src="/build/img/icono_rgate_dark.svg" alt=""/></a>
                            <a href="https://github.com/acmontesh"><img class="icono" src="build/img/icono_github_dark.svg" href="https://github.com/acmontesh" alt=""/></a>
                        </div>
                    </div> <!--Close div Texto-->
                    <div class="linkedin-post">
                        <div class="title-pinned-post">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-pinned-filled" width="32" height="32" viewBox="0 0 24 24" stroke-width="1.5" stroke="#000000" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M16 3a1 1 0 0 1 .117 1.993l-.117 .007v4.764l1.894 3.789a1 1 0 0 1 .1 .331l.006 .116v2a1 1 0 0 1 -.883 .993l-.117 .007h-4v4a1 1 0 0 1 -1.993 .117l-.007 -.117v-4h-4a1 1 0 0 1 -.993 -.883l-.007 -.117v-2a1 1 0 0 1 .06 -.34l.046 -.107l1.894 -3.791v-4.762a1 1 0 0 1 -.117 -1.993l.117 -.007h8z" stroke-width="0" fill="currentColor" />
                            </svg>   
                            <p>Pinned Post</p>
                        </div>
                        <iframe src="https://www.linkedin.com/embed/feed/update/urn:li:share:7424633982493061120?collapsed=1" height="490" width="504" frameborder="0" allowfullscreen="" title="Embedded post"></iframe>
                    </div>
                </div> <!--foto-and-info-->
                <div class="right-info-container">

                    <!-- ************************************** -->
                    <!-- *************  ABOUT ME *************** -->
                    <!-- ************************************** -->

                    <?php
                    IncluirPlantilla("about");
                    ?>

                    <!-- ************************************** -->
                    <!-- *************  RESEARCH *************** -->
                    <!-- ************************************** -->

                    <?php
                    IncluirPlantilla("res");
                    ?>
                    

                    <!-- ************************************** -->
                    <!-- *************  PUBLICATIONS *************** -->
                    <!-- ************************************** -->

                    <?php
                    IncluirPlantilla("papers");
                    ?>

                    <!-- ************************************** -->
                    <!-- *************  NEWS *************** -->
                    <!-- ************************************** -->

                    <?php
                    IncluirPlantilla("news");
                    ?>
                    

                    <!-- ************************************** -->
                    <!-- *************  BLOG *************** -->
                    <!-- ************************************** -->

                    <?php
                    IncluirPlantilla("blog");
                    IncluirPlantilla("carousel");
                    ?>

                    <!-- ************************************** -->
                    <!-- *************  CONTACT ME *************** -->
                    <!-- ************************************** -->

                    <?php
                    IncluirPlantilla("contacto");
                    ?>

                </div> <!-- Right Container -->
            </div> <!--info-container-->
            
        </div> <!--about-me container-->
    </main>

<?php
IncluirPlantilla("footer");
?>
