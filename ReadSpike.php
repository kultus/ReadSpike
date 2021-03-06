<?php

  //force php zipping? http://www.webcodingtech.com/php/gzip-compression.php
   
  ob_start("ob_gzhandler"); 
  
  // header("Cache-Control: max-age=1");
  
  require 'API_cache.php';
  
  // fn feedCaller: deal with individual feeds
    
      function feedCaller($cache_file, $api_call, $cache_for){      
  
        $api_cache = new API_cache ($api_call, $cache_for, $cache_file);
          
        if (!$res = $api_cache->get_api_cache())
          $res = "Error: Could not load cache - please reload\n";
      
        return $res;
        
      }

?>
<!DOCTYPE html>

<html>

 <head>

  <meta charset="UTF-8">

  <title>ReadSpike simple news aggregator</title>

  <link rel="stylesheet" href="css/ReadSpike.css" type="text/css">

  <meta name="viewport" content="width=device-width, maximum-scale=1.0" />
  <meta name="author" content="http://blackspike.com">
  <meta name="description" content="ReadSpike is a simple collection of the latest and most interesting news from around the web. Always up to date news.">
  
	<!-- For non-Retina iPhone, iPod Touch, and Android 2.1+ devices: -->
	<link rel="apple-touch-icon" href="imgs/blackspike-icon-57.png">
	<!-- For first- and second-generation iPad: -->
	<link rel="apple-touch-icon" sizes="72x72" href="imgs/blackspike-icon-72.png">
	<!-- For iPhone with high-resolution Retina display: -->
	<link rel="apple-touch-icon" sizes="114x114" href="imgs/blackspike-icon-114.png">
	<!-- For third-generation iPad with high-resolution Retina display: -->
	<link rel="apple-touch-icon" sizes="144x144" href="imgs/blackspike-icon-144.png">


  <script type="text/javascript">

    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-35227261-1']);
    _gaq.push(['_trackPageview']);

    (function() {
      var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
      ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
      var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();

  </script>

 </head>

 <body id="home">


<!-- !HEADER -->

 <header class="main-header">

  <h1 class="logo-header">ReadSpike simple news aggregator beta v1</h1>
  
  <p class="site-description">ReadSpike is a simple collection of the latest and most interesting news from around the web. Always up to date news.</p>

  <form action="https://www.google.com/search" method="get" class="google-search-form">

    <input type="text" placeholder="Search google..." name="q" class="google-search-input" />

  </form>

 </header>



<!-- !COLUMNS -->


 <div class="col-group">


<!-- !RIGHT COL -->

  <div class="col right-col">


    <section class="reddit-section">
    
      
      <h1><a href="http://www.reddit.com">Reddit</a></h1>
  
      <?php //!REDDIT
      
          // call fn feedCaller
          
          $decodeFeed = feedCaller('cache/api-cache-Reddit.json', 'http://www.reddit.com/.json?limit=100', 3);          
          $decodeReddit = json_decode($decodeFeed, true);
  
  
          echo "<ol class='links-list comments-list thumbnails-list'>\n";
  
            foreach($decodeReddit[data][children] as $story){
            
            
            // is it an image link
            $hasImage = "";             
            $imgExts = array("gif", "jpg", "jpeg", "png", "tiff", "tif", "svg");
            $url = $story[data][url];
            $urlExt = pathinfo($url, PATHINFO_EXTENSION);
            
            if (in_array($urlExt, $imgExts)) {                  
                 $hasImage = 1;
            }        
            
              
            // write li with no/nsfw/has thumbnail class
            if($story[data][over_18]){
              echo "<li class='nsfw'>";
            } else {
              if($hasImage){
                echo "<li class='image-link'>";                
              } else {
                echo "<li>";  
              }         
            }
  
  
            // title
            echo "<a href=\"".$story[data][url]."\"  class=\"story-title\" title=\"".$story[data][num_comments]." ".$story[data][subreddit]." ".$story[data][selftext]."\" >".$story[data][title]."</a>\n";
  
  
            // comments
            echo "<a href=\"http://www.reddit.com/".$story[data][permalink]."\"  class=\"comments\" title=\"comments\">&#10078;</a>";
  
  
  
            if (in_array($urlExt, $imgExts)) {
  
              echo "<a class='hide-image'>&times;</a>";
              echo "<a href=\"".$story[data][url]."\" class='show-image'>&#x25BE;</a>";
  
            }
  
            // thumbnail
            if($hasImage && $story[data][thumbnail] && !$story[data][over_18]){echo "<div data-thumbnail=".$story[data][thumbnail]." class='thumbnail tooltip-holder'></div>";}
  
  
            // detect if show image layer
            
            
            if ($hasImage) {                  
                 
              echo "<div class='full-image'></div>";
            }
             
  
             echo "</li>\n";
  
          }
        /* !WRITE OUT ALL JSON
  
          echo "<pre>";
          print_r($decodeReddit);
          echo "</pre>";
          
        */
  
          echo "</ol>\n\n";
      ?>
            
      <h2 class="show-more"><a href="#">Show more</a></h2>
    
    </section>
      
    
  </div>



<!-- !MIDDLE COL -->

  <div class="col middle-col">
  
  <section class="HN-section">

    <h1><a href="http://news.ycombinator.com">Hacker News</a></h1>

    <?php //!HACKER NEWS
    
          // call fn feedCaller
          
          $decodeFeed = feedCaller('cache/api-cache-HN.json', 'http://open.dapper.net/transform.php?dappName=HNhomepage&transformer=JSON&applyToUrl=http%3A%2F%2Fnews.ycombinator.com%2F', 10);          
          $decodeHN = json_decode($decodeFeed, true);
  
        echo "<ol class='links-list comments-list'>\n";

      foreach($decodeHN[groups][Post]as $storyHN){


        echo "<li>";
        echo "<a href=\"".$storyHN[NumComments][0][href]."\"  class=\"comments\" title=\"comments\">&#10078;</a>";
        echo "<a href=\"".$storyHN[Title][0][href]."\"  class=\"story-title\">".$storyHN[Title][0][value]."</a>";
        echo "</li>\n";
      }

      echo "</ol>\n\n";

      /* !WRITE OUT ALL JSON

      echo "<pre>";
      print_r($decodeHN);
      echo "</pre>";

      */

  ?>
    
    <h2 class="show-more"><a href="#">Show more</a></h2>
    
  </section>

<!-- !DIGG -->

  <section class="digg-section">


  <h1><a href="http://digg.com/">New Digg</a></h1>

  <?php //!NEW DIGG
    
          // call fn feedCaller
          
          $decodeFeed = feedCaller('cache/api-cache-Digg.json', 'http://pipes.yahoo.com/pipes/pipe.run?_id=90ce1ca4854fc79f898e273584a8d67a&_render=json&feedcount=10&feedurl=http%3A%2F%2Fdigg.com%2Frss%2Ftopstories.xml', 7);          
          $decodeDigg = json_decode($decodeFeed, true);


        echo "<ol class='links-list'>\n";

      foreach($decodeDigg[value][items] as $storyDigg){


           echo "<li><a href=\"".$storyDigg[link]."\"  title=\"".$storyDigg[description]."\" >".$storyDigg[title]."</a></li>\n";

        }

        echo "</ol>\n\n";

?>

  </section>


  </div>



<!-- !LEFT COL -->


  <div class="col left-col">



<!-- !NEWS HEADER -->
  
  <section class="news-section">
  
    <h1>
    <a href="http://www.bbc.co.uk/news" class="tab-header bbc-title" data-source="bbc-news">BBC</a>
    <a href="http://news.google.com/" class="tab-header GNews-title" data-source="GNews-news">Google</a>
    </h1>


<!-- !BBC NEWS -->

  <article class="tab-content bbc-news">
    <?php //!BBC
    
          // call fn feedCaller
          
          $decodeFeed = feedCaller('http://pipes.yahoo.com/pipes/pipe.run?_id=14c960a6df0000f567e08fc1c74fee15&_render=json&feedcount=10&feedurl=http%3A%2F%2Ffeeds.bbci.co.uk%2Fnews%2Frss.xml', 7);          
          $decodeBBC = json_decode($decodeFeed, true);


        echo "<ol class='links-list'>\n";

      foreach($decodeBBC[value][items] as $storyBBC){


           echo "<li><a href=\"".$storyBBC[link]."\"  title=\"".$storyBBC[description]."\" >".$storyBBC[title]."</a></li>\n";

        }

        echo "</ol>\n\n";


    ?>

  </article>



<!-- !GOOGLE NEWS -->


  <article class="tab-content GNews-news">
  <?php //!GOOGLE NEWS
    
          // call fn feedCaller
          
          $decodeFeed = feedCaller('cache/api-cache-GNews.json', 'http://ajax.googleapis.com/ajax/services/feed/load?v=1.0&num=38&q=http%3A%2F%2Fnews.google.com%2Fnews%3Foutput%3Drss', 15);          
          $decodeGNews = json_decode($decodeFeed, true);
          
        echo "<ol class='links-list'>\n";

      foreach($decodeGNews[responseData][feed][entries] as $storyGNews){


           echo "<li><a href=\"".$storyGNews[link]."\"  title=\"".$storyGNews[contentSnippet]."\" >".$storyGNews[title]."</a></li>\n";

        }

        echo "</ol>\n\n";

      /* !WRITE OUT ALL JSON

      echo "<pre>";
      print_r($decodeGNews);
      echo "</pre>";
      */

?>

  </article>

  </section>
  
  
  
  
  <section class="pinboard-section">
  
   <h1><a href="http://www.pinboard.in/popular/">Pinboard</a></h1>

  <?php //!PINBOARD
    
          // call fn feedCaller
          
          $decodeFeed = feedCaller('cache/api-cache-Pinboard.json', 'http://feeds.pinboard.in/json/popular/', 15);          
          $decodePinboard = json_decode($decodeFeed, true);

        echo "<ol class='links-list'>\n";

      foreach($decodePinboard as $storyPB){

         echo "<li><a href=\"".$storyPB[u]."\" >".$storyPB[d]."</a></li>\n";

      }

      echo "</ol>\n\n";

?>


    <h2 class="show-more"><a href="#">Show more</a></h2>
    
  </section>

  </div>


<!-- !/col-group -->
</div>



<!-- !FOOTER -->

<footer class="page-footer">

   <a href="http://blackspike.com/">by blackspike.com</a>  
  
</footer>

      <div name="contact" class="contact-form">
        <form method="post" class="feedback-form" action="contactengine.php">
          <a class='close-contact-form'>&times;</a>
          <h1>Feedback</h1>
          <input type="text" name="name" id="name" placeholder="Your name" />
          <input type="email" name="email" id="email" placeholder="Your email" required />
          <input type="text" placeholder="Your message" name="message" id="message" size="14" required />
          <input type="submit" name="submit" value="Send" class="submit-form" />      
          <p><a href="http://www.twitter.com/readspike">Or get in touch on twitter at <strong>@ReadSpike</strong></a></p>
          
        <div class="result"></div>
        
        </form>
      </div>

      
   <a href="#home" class="back-to-top">&#x25B2;</a>
   <a class="show-contact-form">Send feedback</a>
   
    <div class="settings">
     <a class="darkSwitch">dark</a>
     <a class="lightSwitch">light</a>
    </div>



  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
  <script src="js/ReadSpike.js" type="text/javascript"></script>


 </body>
</html>
