<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="public/css/styleBackEnd.css">
        <title>Stand up</title>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
        <link href="https://fonts.googleapis.com/css?family=Amatic+SC|Architects+Daughter&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/p5.js/0.9.0/p5.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/p5.js/0.9.0/addons/p5.dom.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/p5.js/0.9.0/addons/p5.sound.min.js"></script>
        <script src="https://unpkg.com/ml5@0.3.1/dist/ml5.min.js"></script>

        <script src= public/js/ajax.js></script>
        <script src= public/js/script.js></script>
    </head>

    <body>
        <header id="smallHeader">
            <div class="connect">
                <div>
                    <a href="index.php?action=logout">
                        <i class="fas fa-user"><span>Déconnexion </span></i>
                    </a>                
                </div>
            </div>
            <h1> Stand up !</h1>
            </header>
            <img src="public/img/assto2.png" class="astroImg">      
 
        <?= $content ?> 
        
<!--         <script type="text/javascript" src="public/js/menu.js"></script>
 --><!--         <script type="text/javascript" src="public/js/scrollbar.js"></script>
 -->    </body>
</html>
