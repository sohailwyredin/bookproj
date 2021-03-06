<?php
require  'vendor/autoload.php';
    try {
        $client         = new MongoDB\Client();
        $database       = $client -> myblogsite;
        $collection     = $database ->articles;
   
    }
    catch (MongoConnectionException $e)
    {
        die('Failed to connect to MongoDB '.$e->getMessage());
    }
$currentPage = (isset($_GET['page'])) ? (int) $_GET['page'] : 1;

$articlesPerPage =2;

$skip = ($currentPage - 1) * $articlesPerPage;

$alldoc = $collection->find([] , ['title','saved_at'] );


$totalArticles = $collection->count(['title'=> 1,'saved_at' => 1]);


$totalPages = (int) ceil($totalArticles / $articlesPerPage);
// Create query object with all options:
$query = new \MongoDB\Driver\Query(
    [], // query (empty: select all)
    [ 'sort' => [ 'saved_at'=>-1 ],'skip'=>$skip, 'limit' => 40 ] // options
);
//$cursor->sort(array('saved_at'=>-1))->skip($skip)->limit($articlesPerPage);

$alldoc = $collection->find([] , ['title','saved_at'],['sort'=>['saved_at'=> -1]],['skip'=>$skip],['limit'=>$articlesPerPage]);

?>
<html>
    <head>
        <title>Dashboard</title>
        <link rel="stylesheet" href="style.css"/>
        <style type="text/css" media="screen">
            body { font-size: 13px; }
            div#contentarea { width : 650px; }
        </style>
    </head>
<body>
    <div id="contentarea">
          <div id="innercontentarea">
        <h1>Dashboard</h1>
        <table class="articles" cellspacing="0"
           cellpadding="0">
            <thead>
                <tr>
                    <th width="55%">Title</th>
                    <th width="27%">Created at</th>
                    <th width="*">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($alldoc as $item){?>
                    <tr>
                            <td>
                                <?php echo substr($item['title'], 0, 35). '...'; ?>
                            </td>
                            <td>
                           <?php //$a = new MongoDB\BSON\UTCDateTime((new DateTime())->getTimestamp()*1000);   -->
                            
                            

                                    $datetime = $item['saved_at']->toDateTime();
                                  //  $datetime->setTimezone(new DateTimeZone('Asia/Kolkata')); 
                                    $time=$datetime->format(DATE_ATOM);  
                                    $date = date('Y-m-d',strtotime($time));
                                     print  $date;

                                ?>
                            </td>
                            <td class="url">
                              <a href="blog.php?id=<?php echo $item['_id']; ?>">View </a>
                            </td>
                    </tr>
                <?php };?>
            </tbody>
        </table>
    </div>
          <div id="navigation">
        <div class="prev">
            <?php if($currentPage !== 1): ?>
                    <a href="<?php echo $_SERVER['PHP_SELF'].'?page='.($currentPage - 1); ?>">Previous </a>
            <?php endif; ?>
        </div>
         <div class="page-number;">
                 <?php echo $currentPage; ?>
        </div>
    <div class="next">
        <?php if($currentPage !== $totalPages): ?>
            <a href="<?php echo $_SERVER['PHP_SELF'].'?page='.($currentPage + 1); ?>">Next</a>
        <?php endif; ?>
    </div>
    <br class="clear"/>
    </div>
    </div>
</body>
</html>