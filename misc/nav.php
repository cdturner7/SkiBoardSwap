<!-- ######################     Main Navigation   ########################## -->
<nav>
    <ol>
        <?php
        // This sets the current page to not be a link. Repeat this if block for
        //  each menu item 
        if ($path_parts['filename'] == "index") {
            print '<li class="activePage">Home</li>';
        } else {
            print '<li><a href="index.php">Home</a></li>';
        }
        
        if ($path_parts['filename'] == "tables") {
            print '<li class="activePage">Tables</li>';
        } else {
            print '<li><a href="tables.php">Tables</a></li>';
        }
        
        if ($path_parts['filename'] == "skihard") {
            print '<li class="activePage">Ski Hardgoods</li>';
        } else {
            print '<li><a href="skihard.php">Ski Hardgoods</a></li>';
        }
        
        if ($path_parts['filename'] == "snowboardhard") {
            print '<li class="activePage">Snowboard Hardgoods</li>';
        } else {
            print '<li><a href="snowboardhard.php">Snowboard Hardgoods</a></li>';
        }
        
        if ($path_parts['filename'] == "otherhard") {
            print '<li class="activePage">Other HardGoods</li>';
        } else {
            print '<li><a href="otherhard.php">Other Hardgoods</a></li>';
        }
        
        if ($path_parts['filename'] == "softgoods") {
            print '<li class="activePage">Softgoods</li>';
        } else {
            print '<li><a href="softgoods.php">Softgoods</a></li>';
        }
        
        
        
        
        
        ?>
    </ol>
</nav>
<!-- #################### Ends Main Navigation    ########################## -->