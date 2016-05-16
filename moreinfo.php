<?php
$refno = htmlspecialchars( $_GET["refno"]);
//echo $refno."<br>";
$table = htmlspecialchars( $_GET["table"]);
//echo $table."<br>";
$URL = "http://lancasteruad.oxfordarchaeology.com/rest/index.php/" . $table . "/UADReferenceNumber/" . $refno;
//echo $URL."<br>";
$json = file_get_contents($URL);
$jsonIterator = new RecursiveIteratorIterator(
    new RecursiveArrayIterator(json_decode($json, TRUE)),
    RecursiveIteratorIterator::SELF_FIRST);

//Image problem - see for example:
//http://localhost/LancasterUAD/moreinfo.php?table=human_readable_images&refno=387 - link but no image
//http://localhost/LancasterUAD/moreinfo.php?table=human_readable_events&refno=474 - no link

$report=$subreport=array();
$i=-1;
foreach ($jsonIterator as $key => $val) {
	if(is_array($val)) {
       // echo "$key:"."<br>";
    } else {
        //echo "$key => $val"."<br>";
		switch($key){
			case 'uniqueid':
				//$subreport[$i]="<span class='meta_item'><strong>ID:</strong> $val </span>\n";
				break;
			case 'UADReferenceNumber':
				$i++;
				$subreport[$i]="<span class='meta_item'><strong>UAD ref:</strong> $val </span>\n";
				break;
			case 'Type':
				$subreport[$i].="<span class='meta_item'><strong>Type of monument:</strong> $val </span>\n";
				break;
			case 'Easting':
				$subreport[$i].="<span class='meta_item'><strong>Easting:</strong> $val </span>\n";
				break;
			case 'Northing':
				$subreport[$i].="<span class='meta_item'><strong>Northing:</strong> $val </span>\n";
				break;
			case 'PositionAccuracy':
				$subreport[$i].="<span class='meta_item'><strong>PositionAccuracy:</strong> $val </span>\n";
				break;
			case 'ShortName':
				//$subreport[$i].="<span class='meta_item'><strong>ShortName:</strong> $val </span>\n";
				break;
			case 'NarrowPeriod':
				$subreport[$i].="<span class='meta_item'><strong>Period:</strong> $val </span>\n";
				break;
			case 'BroadPeriod':
				$subreport[$i].="<span class='meta_item'><strong>Period</strong> (broad): $val </span>\n";
				break;
			case 'wkb_geometry':
				//$subreport[$i].="<span class='meta_item'><strong>Geometry:</strong> $val </span>\n";
				break;
			case 'BroadClass':
				//$subreport[$i].="<span class='meta_item'><strong>BroadClass:</strong> $val </span>\n";
				break;
			case 'TypeID':
				//$subreport[$i].="<span class='meta_item'><strong>Period:</strong> $val </span>\n";
				break;
			case 'Term':
				$subreport[$i].="<span class='meta_item'><strong>Term:</strong> $val </span>\n";
				break;
			case 'Hyperlink':
				$subreport[$i].="<span class='meta_item'><strong>Link:</strong> <a href='$val' target='uadimage'>$val</a></span>\n";
				//is it an image?
        //http://lancasteruad.oxfordarchaeology.com/uad/images/thumbs/ needs to be prepended to $val
				$imageurl[$i]=$val;
				break;
				
			case 'Name':
				$title[$i]=$val;
				break;
			case 'Description':
				$val=str_replace('/n', '</p>\n<p>', $val);
				$description[$i]="<p>$val </p>\n";
				break;
			default:
				$subreport[$i].="<span class='meta_item'><strong>".$key.":</strong> $val </span>\n";
				break;
		}
    }
}

?>
<html>
<head>
  <title>Lancaster UAD webmap</title>
  <meta charset="UTF-8">
  <!-- fontawesome -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
  <!-- bootstrap -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  <!-- jquery -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <!-- leaflet -->
  <link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.7.5/leaflet.css"/>
  <script src="http://cdn.leafletjs.com/leaflet-0.7.5/leaflet.js"></script>
  <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
  <link rel="shortcut icon" href="./favicon.ico" type="image/x-icon" />
  <script src="js/leaflet.zoomhome.js"></script>
  <!--<script src="js/leaflet-hash.js"></script> -->
  <link rel="stylesheet" href="css/leaflet.zoomhome.css" />
  <link rel="stylesheet" href="css/leaflet-search.css" />
  <link rel="stylesheet" href="css/uad.css?v=<?php echo rand();?>" type="text/css" media="all">
  <script src="js/leaflet-search-patched.js"></script>
  <script src="http://cdnjs.cloudflare.com/ajax/libs/fuse.js/1.2.2/fuse.js"></script>
  <script src="js/Autolinker.min.js"></script>
  <script type="text/javascript">

  <!--
    jQuery(document).ready(function(){jQuery(window).scroll(function(){if(jQuery(this).scrollTop()>100){jQuery(".scroll-to-top").fadeIn()}else{jQuery(".scroll-to-top").fadeOut()}});jQuery(".scroll-to-top").click(function(){jQuery("html, body").animate({scrollTop:0},800);return false})})
    
    function menutoggle(){
      $('#menuitems').toggle(500);
      var menuon=document.getElementById("#menuitems").style.display;
      if(menuon=="none"){
        document.getElementById("menu").style.border = "none";
      }
    }
    
    function clearcookiedisplay(){
      $('#cookies').hide(500);
    }
  -->
  </script>
  <noscript>
  <style type="text/css">
  @media screen and (max-width: 770px) {
    #logo img{
      max-width: 100%;
    }
    #menuitems{
      display: block;
    }
    a#menubutton {
      display: none;
    }
  }
  </style>
  </noscript>

</head> 
  <body class="home blog">
    <div id="page" class="hfeed site">
    <header id="masthead" class="site-header" role="banner">
      <div class="row" id="menu">
        <div id="logo">
          <a href="http://beyondthecastle.org/"><img src="./images/btc_header2.gif" alt="Beyond The Castle" height="76" width="379"></a>
        </div><!-- end of #logo -->
        <div id="menuitems">
          <ul>
          <li><a title="Home" href="http://beyondthecastle.org/">Home</a></li>
          <li><a title="About the Project" href="http://beyondthecastle.org/about/">About the Project</a></li>
          <li><a title="Contact" href="http://beyondthecastle.org/contact/">Contact</a></li>
          </ul>
        </div>
        <a id="menubutton" onclick="menutoggle()" title="Toggle menu display."></a>
        
      </div>
    </header><!-- #masthead -->

    <div id="content" class="row">
		<div class="block">
			<div id="report">
				<?php 
				for($i=0; $i<count($title); $i++){
					echo "<div class='clearing'>&nbsp;</div>\n";
					echo "<div class='descriptive'>\n"
					."<h3>$title[$i]</h3>\n";
					if(isset($imageurl[$i])){
						echo "\n<img class='aligncenter' id='photo' src='$imageurl[$i]' alt='$title[$i]' />\n";
					}
					echo $description[$i]
					."\n</div>\n";
					echo "<div class='meta'>$subreport[$i]</div>\n";
				}
				?>
			</div>
			<div class="clearing">&nbsp;</div>
			<p><strong>Please note:</strong> <em>Descriptions may appear more than once if the same monument has multiple periods or classifications.</em></p>
			<div class="sub-nav">
			<a href="#" onclick="javascript:window.close();" class="button">Close</a>
			</div><!-- sub-nav -->
		</div><!-- block -->

    </div><!-- close .main-content -->
<footer id="colophon" class="site-footer" role="contentinfo">
  <div class="row">
    <a id="facebook"></a>
    
    <nav role="navigation">
      <div class="half">
        <ul id="menu-footer-menu" class="nav footer-nav clearfix">
        <li id="menu-item-145" class="menu-item menu-item-type-custom menu-item-object-custom current-menu-item current_page_item menu-item-home menu-item-145"><a href="http://beyondthecastle.org/">Home</a></li>
        <li id="menu-item-149" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-has-children menu-item-149"><a href="http://beyondthecastle.org/what-you-can-do-to-help/">What You Can Do To Help</a>
        <ul class="sub-menu">
          <li id="menu-item-150" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-150"><a href="http://beyondthecastle.org/get-involved-2/">Get Involved</a></li>
        </ul>
        </li>
        <li id="menu-item-142" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-142"><a href="http://beyondthecastle.org/contact/">Contact</a></li>
        </ul>
      </div>
      <div class="half">
        <div class="copyright"><a href="http://beyondthecastle.org/wordpress/" title="Beyond The Castle">Beyond The Castle</a>  All rights reserved.</div>
      </div>
      
    </nav>
  </div>
  <div style="display: none;" class="scroll-to-top">&#094;</div><!-- .scroll-to-top -->
  
</footer><!-- #colophon -->

<!-- #page -->
<script type="text/javascript">
<!--
//document.getElementById("photo").style.display  = "none";
$(document).ready(function() {
	jQuery('#photo').load(function(){;
        alert(jQuery(this).height());
        alert(jQuery(this).width());
	});
	
	
});
// -->
</script>
</body>
</html>