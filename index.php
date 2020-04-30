<?php 
include 'inc/bootstrap.php';
$pageTitle = "Developers Say the Darndest Things";
$section = "home";
require 'inc/header.php';
?>
    <div class="wrapper">
        
        <?php
        // get each line of file into an array
        $file = file('inc/quotes.txt');
        // grab a random array element
        $quote = $file[array_rand($file)];
        echo '<h1>' . $quote . '</h1>';
        ?>
    </div>
<?php 
require 'inc/footer.php';