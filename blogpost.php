<?php

require  'vendor/autoload.php';

 $action = (!empty($_POST['btn_submit']) && ($_POST['btn_submit'] === 'Save')) ? 'save_article': 'show_form';

switch($action){

    case 'save_article':
        try
        {
            $client         = new MongoDB\Client();
            $database       = $client -> myblogsite;
            $collection     = $database ->articles;
            $article =
                      [
                        'title' => $_POST['title'],
                        'content' => $_POST['content'],
                        'saved_at' => new MongoDB\BSON\UTCDateTime((new DateTime())->getTimestamp()*1000)
                     ];
             $collection->insertOne($article);
            }
            catch(MongoConnectionException $e)
            {
                die("Failed to connect to database ".$e->getMessage());
            }

            catch(MongoException $e)
            {
                die('Failed to insert data '.$e->getMessage());
            }
    break;
    case 'show_form':
    default:
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns="http://www.w3.org/1999/html" xml:lang="en" lang="en">
        <head>
            <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
            <link rel="stylesheet" href="stylesheet.css"/>

            <title>Blog Post Creator</title>
        </head>
        <body>
            <div id="contentarea">
                <div id="innercontentarea">
                     <h1>Blog Post Creator</h1>
                        <?php if ($action === 'show_form'): ?>
                             <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
                                 <h3>Title</h3>
                                 <p><input type="text" name="title" id="title/"></p>
                                 <h3>Content</h3>
                                 <textarea name="content" rows="20"></textarea>
                                 <p><input type="submit" name="btn_submit" value="Save"/> </p>
                             </form>
                        <?php else: ?>
                    <p>Article saved with title :::::<?php echo $article['title']; ?></p> </br>

                         <a href="blogpost.php">Write another one?</a></p>
                         <?php endif;?>
                 </div>
            </div>
        </body>
</html>