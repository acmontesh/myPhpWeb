<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-DVFP354CG0"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-DVFP354CG0');
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Abraham Montes</title>
    <link rel="stylesheet" href="/build/css/app.css">
</head>
<body>
    <header class="header <?php echo $inicio ? 'inicio' : '';?>">
        <div class="contenido-header">
        <!-- <video controls="false" autoplay="autoplay">
            <source src="/build/img/stuck.mp4" type="video/mp4">
        </video> -->
        <?php 
        if ($inicio) { ?>
            <div class="barra" id="barra">
                <a class="brand" href="/">Abraham C. Montes</a>

                <div class="menu-hamburguesa">                    
                    <div class="top-burger"></div>    
                    <div class="middle-burger"></div>    
                    <div class="bottom-burger"></div>    
                
                <!-- <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-menu-2" width="40" height="40" viewBox="0 0 24 24" stroke-width="3" stroke="#33302B" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M4 6l16 0" />
                        <path d="M4 12l16 0" />
                        <path d="M4 18l16 0" />
                    </svg>  -->
                </div>
                <nav class="navegacion">
                    <a class="link-nav-bar">About me</a>
                    <a class="link-nav-bar">Research</a>
                    <a class="link-nav-bar">Publications</a>
                    <a class="link-nav-bar">News</a>
                    <a class="link-nav-bar">Blog</a>
                    <a class="link-nav-bar">Contact</a>
                </nav>
                <nav class="language-dark">
                    <div class="dark-mode-button">
                        <img src="/build/img/darkmode.svg" alt="">
                    </div>
                    <a href="/">EN |</a>
                    <a class="disabled-a" href="/">ES</a>
                </nav>
            </div> <!--Close div barra-->
            <?php 
                }
                    ?>
            
        </div>
        <?php 
        if ($inicio) { ?>
        <div class="texto-inicio">
                <h3>Abraham C. Montes</h3>
                <p>PhD Student<br>
                   Drilling Automation<br>
                   The University of Texas at Austin
                </p>
                <div class="iconos">
                    <a href="https://scholar.google.com/citations?user=Va0vQdsAAAAJ&hl=es"><img src=/build/img/icono_scholar.svg alt=""/></a>  <!--""-->
                    <a href="https://www.linkedin.com/in/abraham-c-montes-6661a841/"><img src="/build/img/icono_linkedin.svg" alt=""/></a>
                    <a href="https://github.com/acmontesh"><img src="/build/img/icono_github.svg" alt=""/></a>
                </div>
                <div class="contenedor-flecha">
                    <img src="/build/img/arrowdown.svg" height=20 alt="">
                </div>
        </div> <!--Close div Texto-->        
        <?php 
    }
        ?>
    </header>