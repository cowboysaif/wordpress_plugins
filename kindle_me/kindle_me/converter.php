<?php 

if (get_option('kms_ready') == 'true' && isset($_POST['convert']) == true ) {
$book_name = $_POST['book_name'];
$book_user_name = $_POST['book_user_name'];
function logLine($line) {
	global $log, $tStart, $tLast;
	$tTemp = gettimeofday();
	$tS = $tStart['sec'] + (((int)($tStart['usec']/100))/10000);
	$tL = $tLast['sec'] + (((int)($tLast['usec']/100))/10000);
	$tT = $tTemp['sec'] + (((int)($tTemp['usec']/100))/10000);
	$log .= sprintf("\n+%08.04f; +%08.04f; ", ($tT-$tS), ($tT-$tL)) . $line;
	$tLast = $tTemp;
}
$content_start =
"<?xml version=\"1.0\" encoding=\"utf-8\"?>\n"
. "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.1//EN\"\n"
. "    \"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd\">\n"
. "<html xmlns=\"http://www.w3.org/1999/xhtml\">\n"
. "<head>"
. "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n"
. "<link rel=\"stylesheet\" type=\"text/css\" href=\"styles.css\" />\n"
. "<title>".$book_user_name."</title>\n"
. "</head>\n"
. "<body>\n";

$bookEnd = "</body>\n</html>\n";
date_default_timezone_set('Europe/Berlin');
$tStart = gettimeofday();
$tLast = $tStart;
$log = $content_start . "<h1>Log:</h1>\n<pre>Started: " . gmdate("D, d M Y H:i:s T", $tStart['sec']) . "\n &#916; Start ;  &#916; Last  ;";
logLine("Start");

$fileDir = WP_PLUGIN_DIR . '/kindle_me/epub_classes/';
include_once( WP_PLUGIN_DIR. '/kindle_me/epub_classes/EPub.php');
logLine("include EPub");

$book = new EPub();
logLine("new EPub()");

$cat_id = get_cat_ID($book_name);

$chapters = get_posts(array('numberposts' => 10000, 'category' => $cat_id ));




// Title and Identifier are mandatory!
$book->setTitle($book_name);
$book->setIdentifier( $_POST['book_url'], EPub::IDENTIFIER_URI); // Could also be the ISBN number, prefered for published books, or a UUID.
$book->setLanguage("en"); // Not needed, but included for the example, Language is mandatory, but EPub defaults to "en". Use RFC3066 Language codes, such as "en", "da", "fr" etc.
//$book->setDescription($_POST['book_description']);
$book->setAuthor($_POST['book_author'],$_POST['book_author']); 
//$book->setPublisher("John and Jane Doe Publications", "http://JohnJaneDoePublications.com/"); // I hope this is a non existant address :) 
$book->setDate(time()); // Strictly not needed as the book date defaults to time().
$book->setRights($_POST['lstarea']); // As this is generated, this _could_ contain the name or licence information of the user who purchased the book, if needed. If this is used that way, the identifier must also be made unique for the book.
//$book->setSourceURL("http://JohnJaneDoePublications.com/books/TestBook.html");
logLine("Set up parameters");

$cssData = '<style>
body { margin:15px; font-family:Serif; font-size:small; text-align:justify; }
p { text-indent:10px; }
h1, h2, h3 { text-align:center; }
h4, h5, h6 { text-align:left; }
blockquote { margin:30px; text-indent:0px; }
</style>';

$book->addCSSFile("styles.css", "css1", $cssData);
logLine("Add css");

// This test requires you have an image, change "demo/cover-image.jpg" to match your location.
$target_path = WP_PLUGIN_DIR . "/kindle_me/uploads/";
$target_path = $target_path . basename( $_FILES['image_path']['name']); 
move_uploaded_file($_FILES['image_path']['tmp_name'], $target_path); 
$book->setCoverImage("Cover.jpg", file_get_contents($target_path), "image/jpeg");

// A better way is to let EPub handle the image itself, as it may need resizing. Most Ebooks are only about 600x800
//  pixels, adding megapix images is a waste of place and spends bandwidth. setCoverImage can resize the image.
//  When using this method, the given image path must be the absolute path from the servers Document root.

/* $book->setCoverImage("/absolute/path/to/demo/cover-image.jpg"); */

// setCoverImage can only be called once per book, but can be called at any point in the book creation.
logLine("Set Cover Image");

//$cover = $content_start . "<h1>".$_POST['book_name']."</h1>\n<h2>By: ".$_POST['book_author']."</h2>\n" . $bookEnd;
//$book->addChapter("Notices", "Cover.html", $cover);
for ( $i = 0 , $j = 0 ; $i < sizeof($chapters) ; $i++ ) {

	if ( $chapters[$i]->post_title == $_POST['lstarea_tp'] && $j == 0  ) {
	$copyright = $content_start .  "<h2>".$chapters[$i]->post_title."</h2><br></br>" . apply_filters('the_content', $chapters[$i]->post_content) . $bookEnd;
	$book->addChapter("titlepage" , "titlepage.html", $copyright);
	$j++;
	$i = 0;
	}
	if ( $chapters[$i]->post_title == $_POST['lstarea'] && $j == 1 ) {
	$copyright = $content_start .  "<h2>".$chapters[$i]->post_title."</h2><br></br>" .apply_filters('the_content', $chapters[$i]->post_content). $bookEnd;
	$book->addChapter("Copyright" , "cpr.html", $copyright);
	$j++;
	$i = 0;
	}
	else if ( $chapters[$i]->post_title == $_POST['lstarea_pr'] && $j == 2) {
	$copyright = $content_start .  "<h2>".$chapters[$i]->post_title."</h2><br></br>" .apply_filters('the_content', $chapters[$i]->post_content) . $bookEnd;
	$book->addChapter("prolouge" , "prolouge.html", $copyright);
	$j++;
	$i=0;
	}
	
}
$toc = $content_start . '<p><h1>Table of Contents</h1></p><br></br>';
for ( $i = sizeof($chapters) - 1 , $k = sizeof($chapters) - 1; $i >= 0  ; $i-- ) {
$j = sizeof($chapters) - $k;
if ( $chapters[$i]->post_title != $_POST['lstarea'] && $chapters[$i]->post_title != $_POST['lstarea_tp'] && $chapters[$i]->post_title != $_POST['lstarea_pr'] && $chapters[$i]->post_title != $_POST['lstarea_ar']&& $chapters[$i]->post_title != $_POST['lstarea_in'] ) {
$toc = $toc . '<a href="Chapter00'.$j.'.html">Chapter '.$j.' : '.$chapters[$i]->post_title.'</a><br/>';
$k--;
}
else {

}
}
$toc = $toc . '<a href="ar.html">Additional Resources</a><br/>';
$toc  = $toc . $bookEnd;

$book->addChapter("Table of Contents" , "toc.html", $toc);

for ( $i = 0 , $j = 0 ; $i < sizeof($chapters) ; $i++ ) {
	
if ( $chapters[$i]->post_title == $_POST['lstarea_in']) {
	$copyright = $content_start .  "<h2>".$chapters[$i]->post_title."</h2><br></br>" .apply_filters('the_content', $chapters[$i]->post_content) . 			
	$bookEnd;
	$book->addChapter("introduction" , "introduction.html", $copyright);
	}
}
for ( $i = sizeof($chapters) - 1 , $k = sizeof($chapters) - 1  ; $i >= 0  ; $i-- ) {
$j = sizeof($chapters) - $k ;
if ( $chapters[$i]->post_title != $_POST['lstarea']&& $chapters[$i]->post_title != $_POST['lstarea_tp'] && $chapters[$i]->post_title != $_POST['lstarea_pr'] && $chapters[$i]->post_title != $_POST['lstarea_ar'] && $chapters[$i]->post_title != $_POST['lstarea_in']) {
$chapter = $content_start . "<h1>Chapter ".$j ."</h1>\n"
	. "<h2>".$chapters[$i]->post_title."</h2>\n"
	. $book->wp_image_add_from_post(apply_filters('the_content', $chapters[$i]->post_content))
	. $bookEnd;

	logLine("Build Chapters");
$book->addChapter("Chapter ".$j.": ". $chapters[$i]->post_title , "Chapter00".$j.".html", $chapter);
logLine("Add Chapter 1");
$book->setSplitSize(15000);
require_once WP_PLUGIN_DIR . '/kindle_me/epub_classes/EPubChapterSplitter.php';
logLine("include EPubChapterSplitter.php");
$splitter = new EPubChapterSplitter();
$splitter->setSplitSize(15000); // For this test, we split at approx 15k. Default is 250000 had we left it alone.
logLine("new EPubChapterSplitter()");
$log .= "\n</pre>" . $bookEnd;
//$book->addChapter("Log", "Log.html", $log);
$k--;
}
else {
}
}

for ( $i = 0 ; $i < sizeof($chapters) ; $i++ ) {
	if ( $chapters[$i]->post_title == $_POST['lstarea_ar'] ) {
	$copyright = $content_start .  "<h2>".$chapters[$i]->post_title."</h2>\n<br></br>" . apply_filters('the_content', $chapters[$i]->post_content) . $bookEnd;
	$book->addChapter("ar" , "ar.html", $copyright);
	}	
}
$book->finalize(); // Finalize the book, and build the archive.
$book->saveBook( $book_user_name , WP_PLUGIN_DIR . '/kindle_me');
// Send the book to the client. ".epub" will be appended if missing.
//$zipData = $book->sendBook("Example1Book");
$bookstore = get_option('kms_book');
$bookstore[$book_name]['converted'] = 1;
$bookstore[$book_name]['url'] = $_POST['book_url'];
update_option('kms_book' , $bookstore );
}
else if (get_option('kms_ready') == 'true' && isset($_POST['cat_name']) && isset($_POST['convert']) == false && $_POST['cat_name'] != 'kms_publish'){
	echo '<form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
  <p>
 
  <input name="convert" id="convert" type ="hidden" value="1"></input>
  <input name="book_name" id="book_name" type ="hidden" value="'.$_POST['cat_name'].'"></input>
    <label for="book_name">Book Name : </label>
    <input name="book_user_name" size = "100" type="text" id="book_user_name" value="'. $_POST['cat_name'] .'" />
  </p>
  <p>
    <label for="book_url">Amazon URL : </label>
    <input name="book_url" type="text" id="book_url" value="" size="100"/>
  </p>

  <p>
    <label for="book_author">Author</label>
:    
<input type="text" name="book_author" id="book_author" size="100"/>
  </p>
  <p>
    <label for="book_publisher">Publisher</label>
    : 
    <input type="text" name="book_publisher" id="book_publisher" size="100"/>
  </p>
  <p>
   ';
$cat_id = get_cat_ID($_POST['cat_name']);

$chapters = get_posts(array('numberposts' => 10000, 'category' => $cat_id ));

echo '<label for="lstarea_tp"> Post to be converted as title page : </label>
<select name = "lstarea_tp" id = "lstarea_tp">';

for ( $i = 0 ; $i < sizeof($chapters) ; $i++ ) {
 echo '<option value="' . $chapters[$i]->post_title . '"> ' .$chapters[$i]->post_title . '</option>';
}

echo '</select> <br></br>';

echo '<label for="lstarea"> Post to be converted as copyright : </label>
<select name = "lstarea" id = "lstarea">';
for ( $i = 0 ; $i < sizeof($chapters) ; $i++ ) {
 echo '<option value="' . $chapters[$i]->post_title . '"> ' .$chapters[$i]->post_title . '</option>';
}

echo '</select> <br></br>';

echo '<label for="lstarea_pr"> Post to be converted as prologue : </label>
<select name = "lstarea_pr" id = "lstarea_pr">
<option value="">N/A</option>';

for ( $i = 0 ; $i < sizeof($chapters) ; $i++ ) {
 echo '<option value="' . $chapters[$i]->post_title . '"> ' .$chapters[$i]->post_title . '</option>';
}

echo '</select> <br></br>';

echo '<label for="lstarea_in"> Post to be converted as introduction : </label>
<select name = "lstarea_in" id = "lstarea_in">
<option value="">N/A</option>';

for ( $i = 0 ; $i < sizeof($chapters) ; $i++ ) {
 echo '<option value="' . $chapters[$i]->post_title . '"> ' .$chapters[$i]->post_title . '</option>';
}

echo '</select> <br></br>';

echo '<label for="lstarea_ar"> Post to be converted as additional resources : </label>
<select name = "lstarea_ar" id = "lstarea_ar">';

for ( $i = 0 ; $i < sizeof($chapters) ; $i++ ) {
 echo '<option value="' . $chapters[$i]->post_title . '"> ' .$chapters[$i]->post_title . '</option>';
}

echo '
</select> 
  </p>
  <p>
    <label for="image_path">Cover image : </label>
    <input type="file" name="image_path" id="image_path" />
  </p>
  <p>
    <input type="submit" name="book_convert" id="book_convert" value="Convert !" />
  </p>
</form>';
}

else if (get_option('kms_ready') == 'true' && isset($_POST['cat_name']) && isset($_POST['convert']) == false && $_POST['cat_name'] == 'kms_publish' ){

	$bookstore = get_option('kms_book');
	
	$bookstore[$_POST['cat_real_name']]['published'] = 1;
	
	update_option('kms_book' , $bookstore );
}
?>