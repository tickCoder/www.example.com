
<html>
    
    <!--php与html嵌套-->
    
    <?php
    $hdw = $_GET['hdw'];
    ?>
    
    <body>
        
        <?php
        if (empty($hdw)) {
        ?>
        
        <h1>empty</h1>
        
        <?php 
        } else {
        ?>
        
        <h2>not empty</h2>
        
        <?php
        }
        ?>
        
    </body>
    
</html>

