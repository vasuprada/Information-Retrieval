<?php
// make sure browsers see this page as utf-8 encoded HTML
header('Content-Type: text/html; charset=utf-8');

$limit = 10;
$query = isset($_REQUEST['q']) ? $_REQUEST['q'] : false;
$additional=isset($_REQUEST['algorithm']) ? $_REQUEST['algorithm'] : false;
$results = false;

if ($query)
{
  // The Apache Solr Client library should be on the include path
  // which is usually most easily accomplished by placing in the
  // same directory as this script ( . or current directory is a default
  // php include path entry in the php.ini)
  require_once('solr-php-client/Apache/Solr/Service.php');
  include '/var/www/html/SpellCorrector.php';
  
  // create a new solr service instance - host, port, and webapp
  // path (all defaults in this example)
  $solr = new Apache_Solr_Service('localhost', 8983, '/solr/hw3');

  // if magic quotes is enabled then stripslashes will be needed
  if (get_magic_quotes_gpc() == 1)
  {
    $query = stripslashes($query);
  }
  $additional_parameter=array('sort'=>'pageRankFile desc,score desc');
  // in production code you'll always want to use a try /catch for any
  // possible exceptions emitted  by searching (i.e. connection
  // problems or a query parsing error)
  try
  { 
    if($additional=='pagerank')  
        $results = $solr->search($query, 0, $limit,$additional_parameter);
    else
        $results = $solr->search($query, 0, $limit);
        
  }
  catch (Exception $e)
  {
    // in production you'd probably log or email this error to an admin
    // and then show a special message to the user but for this example
    // we're going to show the full exception'
    die("<html><head><title>SEARCH EXCEPTION</title><body><pre>{$e->__toString()}</pre></body></html>");
  }
}

?>
<html>
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
  <link href="http://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet">
      

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type='text/javascript' language='javascript'     src='http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.9/jquery-ui.min.js'></script>

  <head>
    <title>Compare Lucene and Pagerank Search Algorithms</title>
  </head>
  <body>
   <div class="ui-widget">
    <form  accept-charset="utf-8" method="get">
      <label for="q">Search:</label>
      <input id="q" name="q" type="text" value="<?php echo htmlspecialchars($query, ENT_QUOTES, 'utf-8'); ?>" size="50" /></br>
      <input type="radio" name="algorithm" value="solr" checked>Use Solr<input type="radio" name="algorithm" value="pagerank"/>Use Pagerank</br>
 <input type="submit" value="Search"></input> 
<p><?php 
            $flag = 0;
            $prediction ="";
            $query_lower ="";
            $count_word = 1;
            $query_lower = strtolower($query);
           // echo '<p>'. $query_lower .'</p>'
            $split_words = explode(" ",$query_lower);
            foreach($split_words as $query2)  
             {
              $new_query = SpellCorrector::correct($query2);

              if($count_word==1)
	         $prediction = $prediction.$new_query;
              else
              	 $prediction = $prediction." ".$new_query;
              
              $count_word = $count_word + 1;
             }

              if($prediction == $query_lower)
               {
		 $flag = 0;
                echo '<p></p>';
		}
              else
                {
		$flag = 1;
                echo 'Did You Mean:'.'<a href="hw4.php?q=' .$prediction. '&algorithm=' .$additional. '">'.$prediction.'</a>';
                }
           /* if($flag == 0)
	       echo '<p></p>';
           else
              echo 'Did You Mean:'.'<a href="hw4.php?q=' .$prediction. '&algorithm=' .$additional. '">'.$prediction.'</a>';*/
      ?></p>

     <!--<input type="submit" value="Search"></input>-->
    </form>
</div>
<script>


$(document).ready(function(){
    $("#q").keyup(function(){
	var term = $("input:text").val().toLowerCase();
        console.log(term);
        $.ajax({url: 'http://localhost:8983/solr/hw3/suggest',
		async:true,
		type: "GET",
		data: {'wt':'json', 'q':term},
        	dataType : 'jsonp',   
        	crossDomain:true,
		jsonp: 'json.wrf',
		
            
	 success: function(data){
	
	var i;
	var suggestions = [];
	var word = " ";
        var word_trim =" ";
        var count = 1;
	for (i=0;i<data['suggest']['suggest'][term]['numFound'];i++)
	{	

		word = data['suggest']['suggest'][term]['suggestions'][i]['term'];
                
                word_trim = word.replace('usc dana and david dornsife college of letters arts and sciences','');
                if(word_trim.indexOf('vasu') > -1 || word_trim.split(' ').length > 6)
                 continue;
                else
                 {
		suggestions.push(word_trim);
                count++;
                 }
            if(count>5) break;
 
	}
	   
	console.log(suggestions);
            $( "#q" ).autocomplete({
               source: suggestions
            });
        }});
	
    });
});


</script>


<?php

// display results
if ($results)
{
  $total = (int) $results->response->numFound;
  $start = min(1, $total);
  $end = min($limit, $total);
?>
    <div>Results: <?php echo $start; ?> - <?php echo $end;?> of <?php echo $total; ?>:</div>
    <ol>
<?php
  // iterate result documents
  foreach ($results->response->docs as $doc)
  {
      $id = $doc->id;
      $title = $doc->title;
      $author_name = $doc->author;
      $date_created = $doc->created;
      $size_stream = round(($doc->stream_size)/1024,2);
      $finaltitle = str_replace(">","|",$title);

      if (strpos($id,'.html')!==FALSE) {
            $link=substr(urldecode($id),18);
            $finallink=str_replace(".html","",$link);
	   }
        else{
            $finallink= substr(urldecode($id),18);
            }   
?>

        <li>
         <!--<p><?php echo "http://".$finallink;?></p>-->
          <p><a href="<?php echo 'http://'.$finallink;?>"><?php if($finaltitle)echo $finaltitle;else echo 'Document'?></a></p>
        </li>
        <?php
        if($author_name!="")
            echo "Author:"." ".$author_name."   ";
        else
            echo "Author:"." "."N/A"."   ";
        if($date_created!="")
            echo "Date of Creation:"." ".$date_created."   ";
        else
            echo "Date of Creation:"." "."N/A"."   ";
        if($size_stream!="")
            echo "Size:"." ".$size_stream."KB   ";
        else
            echo "Size:"." "."N/A"."KB   ";
      
        ?>       
<?php
  }
?>
    </ol>
<?php
} 
?>
  </body>
</html>

