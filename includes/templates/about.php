<?php
$db=conectarDB();
$query="SELECT * FROM about";
$resultado = mysqli_query($db, $query);
?>
<div id='container-about' class="about-info">
    <div class="about-text">   
        <h1 id="titulo-about-me">About me</h1>                    
        <?php
        $texts=mysqli_fetch_assoc($resultado);
        $aboutText  =   $texts['abouttext'];
        echo $aboutText;
        ?>
    </div>
    <div class="interests-education smaller-font">
        <div class="interests">
            <h2>Interests</h2>
            <?php
            $intText  =   $texts['intereststext'];
            echo $intText;
            ?>
            
            <ul class="list-bullets">
                <li>Drilling Anomaly Detection in Real Time</li>
                <li>Stuck Pipe Prevention</li>
                <li>Wellbore Quality Assessment</li>
                <li>Hole Cleaning Automation</li>
                <li>Well Planning Automation</li>
                <li>Analysis of Longitudinal Data</li>
            </ul>
        </div>
        <div class="education smaller-font">
            <h2>Education</h2>
            <ul class="list-bullets gradcap">
                <li>
                    <strong>PhD Petroleum Engineering</strong><br>
                    The University of Texas at Austin<br>
                    Expected Completion: June, 2026
                </li>
                <li>
                    <strong>MS Systems and Computational Engineering</strong><br>
                    Pontificia Universidad Javeriana<br>
                    Bogotá, Colombia, Mar 2022
                </li>
                <li>
                    <strong>BEng Petroleum Engineering</strong><br>
                    Universidad Nacional de Colombia<br>
                    Medellín, Colombia, Sep 2014
                </li>
            </ul>
        </div>
    </div>
    <div class="about-text">   
        <h2>Professional Experience</h2>    
        <?php
            $profText  =   $texts['proftext'];
            echo $profText;
            ?>                
        <ul>
            <li>Deep onshore wells in the Colombian foothills</li>
            <li>Exploratory wells</li>
            <li>Thru-tubing ultra-slim laterals</li>
        </ul>
        <?php
            $addText  =   $texts['additionaltext'];
            echo $addText;
            ?>   
        
    </div>
</div><!-- Info container No. 1 - About Section -->