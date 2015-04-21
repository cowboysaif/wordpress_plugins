<?php
if ( get_option('kms_ready') == 'false' && isset($_POST['hdn1']) == false && isset($_POST['hdn2']) == false && isset($_POST['hdn3']) == false && isset($_POST['hdn4']) == false ){
echo '
<div id = "wrap">
<div id="apDiv1">
<form id="form1" name="form1" method="post" action="">
<div id="wizard_image"><img src="' . WP_PLUGIN_URL . '/kindle_me/ebook1_1411360i.png" width="220" height="207" /></div>
<div id="wizard_heading1">Welcome to KindleMe ebook Creator !</div>
<div id="body">
  <p>This setup wizard will help you get started!</p>
  <p>The plugin will automatically generate a category named &quot;kindle me&quot;.</p>
  <p>Under this category, you will create a subcategory for every single ebook.
This way you can individually select which posts you want to be part of
the ebook. The plugin will then convert the selected posts, in the order of
their post id, into perfectly formatted epub files ready for you to upload to
amazon.
</p>
  <p>To start, click next to create &quot;kindle me&quot; category.</p>
</div>
<div id="button">
<input name="hdn1" type="hidden" value="1" />
<input type="submit" name="btn" id="btn" value="Create category &quot;kindle me&quot;" />
</div>
</form>
</div>
</div>';

$cat_defaults = array(
  'cat_name' => 'kindle me',
  'category_description' => 'category for kindle book conversion. See "kindle me" plugin for more details' ,
  'category_nicename' => 'kindle' );
wp_insert_category($cat_defaults);
$books = array();
update_option('kms_books' , $books );
}

else if (get_option('kms_ready') == 'false' && isset($_POST['hdn1']) == true ) {
echo'
<div id = "wrap">
<div id="apDiv1">
<form id="form1" name="form1" method="post" action="">
<div id="wizard_image"><img src="' . WP_PLUGIN_URL . '/kindle_me/ebook1_1411360i.png" width="220" height="207" /></div>
<div id="wizard_heading1">Welcome to KindleMe ebook Creator !</div>
<div id="body">
  <p>The &quot;Kindle Me&quot; category was successfully created!</p>
  <p>From now on, create a new category for every ebook you want to create, and
select the &quot;Kindle Me&quot; category as a parent category just like in the screenshot
below.
</p>
</div>
<div id="button">
<input name="hdn2" type="hidden" value="1" />
<input type="submit" name="btn" id="button_next" value="Next" />
<div id="edit_cat"><img src="' . WP_PLUGIN_URL . '/kindle_me/edit_cat.png" width="460" height="294"></div>
</div>
</form>
</div>
</div>';
}
else if (get_option('kms_ready') == 'false' && isset($_POST['hdn2']) == true) {
echo '
<div id="apDiv1">
<form id="form1" name="form1" method="post" action="">
<div id="wizard_image"><img src="' . WP_PLUGIN_URL . '/kindle_me/ebook1_1411360i.png" width="220" height="207" /></div>
<div id="wizard_heading1">Welcome to KindleMe ebook Creator !</div>
<div id="body">
  <p>You are only a couple of clicks away from creating your perfecty formatted
Kindle ebook!
</p>
<p>After you have successfully created your book subcategory and added the posts
you want to turn into an ebook, you will find a "Click to Convert" button on the 
main plugin page. Click on it to convert the posts in the selected category into an
ebook.
</p>
<p>After you are done, you may click on the download button to download the .epub
file to your computer in order to manually publish it on amazon.
</p>
</div>
<div id="button">
<input name="hdn3" type="hidden" value="1" />
<input type="submit" name="btn" id="button_next" value="Next" />
<div id="edit_cat"><img src="' . WP_PLUGIN_URL . '/kindle_me/pic2.png" width="765" height="126"></div>
</div>
</form>
</div>';
}
else if (get_option('kms_ready') == 'false' && isset($_POST['hdn3']) == true) {
echo '
<div id="apDiv1">
<form id="form1" name="form1" method="post" action="">
<div id="wizard_image"><img src="' . WP_PLUGIN_URL . '/kindle_me/ebook1_1411360i.png" width="220" height="207" /></div>
<div id="wizard_heading1">Welcome to KindleMe ebook Creator !</div>
<div id="body">
  <p>Fill in the book details in the convert menu. Note that the amazon URL field will
have to be left blank the first time you convert your ebook. You will get the link to
your amazon book once it is published. After that, come back and re-convert, inserting
the link to your published book.
</p>
<P>When you are done, you will be able to click on the "Insert Link" button to place a 
link to your published amazon book on every post of that book category.
</p>
</div>
<div id="button">
<input name="hdn4" type="hidden" value="1" />
<input type="submit" name="btn" id="button_next" value="Next" />
<div id="edit_cat"><img src="' . WP_PLUGIN_URL . '/kindle_me/desc.png" width="300" height="261"></div>
</div>
</form>
</div>';
update_option('kms_ready', 'true');
}
?>