<?php

    include "top.php";
    
    $file = fopen("sql/users.sql","r") or die("Error");
    
    //now print out each record
    $query = fread($file, filesize("sql/users.sql"));
    $info2 = $thisDatabaseReader->select($query, "", 0, 0, 0, 0, false, false);
    $span = count($info2);
    
    print "<table class = 'user'>";
    print "<caption>Users</caption>";
    print "<thead><tr><th>User Id</th><th>First Name</th><th>Last Name</th><th>email</th></tr></thead>";
    
    $columns = 4;    
    
    $highlight = 0; // used to highlight alternate rows
    foreach ($info2 as $rec) {
        $highlight++;
        if ($highlight % 2 != 0) {
            $style = ' odd ';
        } else {
            $style = ' even ';
        }
        print '<tr class="' . $style . '">';
        for ($i = 0; $i < $columns; $i++) {
            print '<td>' . $rec[$i] . '</td>';
        }
        print '</tr>';
    }
    // all done
    print '</table>';
    //print '<p> Query: ' . $query;
    print '<p class = "record"> Record Total: ' . $span;
include "footer.php";
?>