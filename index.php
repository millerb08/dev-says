<?php 
putenv('REPOSITORY={"type":"sqlite","source":"inc/quotes.db"}');
include 'inc/bootstrap.php';
$pageTitle = "Developers Say the Darndest Things";
$section = "home";
require 'inc/header.php';
?>
    <div class="wrapper">
        
        <?php
        //echo getenv("SECRET");
        // get each line of file into an array
        $repository = json_decode(getenv("REPOSITORY"));
        echo $repository->type;
        switch($repository->type){
          case "text":
            $file = file($repository->source);
            // grab a random array element
            $quote = $file[array_rand($file)];
            break;
          case "sqlite":
              try {
                //if you are working in workspaces you will most likely need to start with 'sqlite:/www/'
                $db = new PDO("sqlite:/www/" . $repository->source);
                $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
                $result = $db->query('SELECT quote FROM Quotes ORDER BY RANDOM() LIMIT 1');
                $quote = $result->fetchColumn();
                break;
              } catch (Exception $e) {
                echo "Error!: " . $e->getMessage();
              }
          default: 
            $quote = "It works only on my machine.";
        }
        echo '<h1>' . $quote . '</h1>';
        ?>
    </div>
<?php 
require 'inc/footer.php';