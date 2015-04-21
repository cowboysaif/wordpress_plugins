<?php
/*********************/
/*                   */
/*  Dezend for PHP5  */
/*         NWS       */
/*      Nulled.WS    */
/*                   */
/*********************/

function mm_post_test( $curl, $vars )
{
    extract( $vars );
    $page = get( "http://getboo.com/login.php" );
    $postdata = array( "name" => $username, "pass" => $password, "remember" => "on", "submitted" => "Log In" );
    $page = post( "http://getboo.com/login.php", $postdata, "http://getboo.com/login.php" );
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_LOGIN;
    return ASP_ERROR_LOGIN;
    $page = get( "http://getboo.com/add.php" );
    $postdata = array( "folderid" => 0, "url" => $link, "title" => $title, "description" => $extended, "publicChk" => "on", "tags" => implode( " ", $tags ), "submitted" => "Add Bookmark" );
    $hiddens = mm_search( "type=\"hidden\"", ">", $page );
    foreach ( $hiddens as $hidden )
    {
        $hd_name = mm_search( "name=\"", "\"", $hidden, false );
        $hd_name = trim( $hd_name[0] );
        $hd_val = mm_search( "value=\"", "\"", $hidden, false );
        $hd_val = trim( $hd_val[0] );
        if ( $hd_name == "token" )
        {
            $postdata[$hd_name] = $hd_val;
        }
    }
    $page = post( "http://getboo.com/add.php", $postdata, "http://getboo.com/add.php" );
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_POST;
    return ASP_ERROR_POST;
    return ASP_ERROR_OK;
}

function mm_post_tweako( $curl, $vars )
{
    extract( $vars );
    if ( !empty( $extra ) )
    {
        $extra = str_replace( "http://www.tweako.com/?q=user/login", "", $extra );
        $extra = substr( $extra, 0, strpos( $extra, "/" ) );
    }
    $postdata = array( "edit[name]" => $username, "edit[pass]" => $password, "edit[form_id]" => "user_login", "op" => "Log in" );
    $page = post( "http://www.tweako.com/user/login?destination=user%2Flogin", $postdata, "http://www.tweako.com/submit" );
    if ( $err = getError( ) )
    {
        return $err;
    }
    if ( 200 != getHttpCode( ) )
    {
        return ASP_ERROR_LOGIN;
    }
    if ( !strpos( $page, "user/{$username}" ) )
    {
        return ASP_ERROR_LOGIN;
    }
    $page = get( "http://www.tweako.com/node/add/storylink" );
    $postdata = array( "edit[title]" => $title, "edit[taxonomy][tags][2]" => implode( ",", $tags ), "edit[vote_storylink_url]" => $link, "edit[body]" => $extended, "edit[changed]" => 1, "op" => "Preview", "edit[taxonomy][1]" => 13 );
    $hiddens = mm_search( "type=\"hidden\"", ">", $page );
    foreach ( $hiddens as $hidden )
    {
        $hd_name = mm_search( "name=\"", "\"", $hidden, false );
        $hd_name = trim( $hd_name[0] );
        $hd_val = mm_search( "value=\"", "\"", $hidden, false );
        $hd_val = trim( $hd_val[0] );
        $postdata[$hd_name] = $hd_val;
    }
    $page = post( "http://www.tweako.com/node/add/storylink", $postdata, "http://www.tweako.com/node/add/storylink" );
    if ( $err = getError( ) )
    {
        return $err;
    }
    if ( 200 != getHttpCode( ) )
    {
        return ASP_ERROR_POST;
    }
    $postdata['edit[format]'] = 9;
    $hiddens = mm_search( "type=\"hidden\"", ">", $page );
    foreach ( $hiddens as $hidden )
    {
        $hd_name = mm_search( "name=\"", "\"", $hidden, false );
        $hd_name = trim( $hd_name[0] );
        $hd_val = mm_search( "value=\"", "\"", $hidden, false );
        $hd_val = trim( $hd_val[0] );
        $postdata[$hd_name] = $hd_val;
    }
    $postdata['op'] = "Submit";
    $page = post( "http://www.tweako.com/node/add/storylink", $postdata, "http://www.tweako.com/node/add/storylink" );
    if ( $err = getError( ) )
    {
        return $err;
    }
    if ( 200 != getHttpCode( ) )
    {
        return ASP_ERROR_POST;
    }
    return ASP_ERROR_OK;
}

function mm_post_linkswarm( $curl, $vars )
{
    extract( $vars );
    if ( !empty( $extra ) )
    {
        $extra = str_replace( "http://www.linkswarm.com/", "", $extra );
        $extra = substr( $extra, 0, strpos( $extra, "/" ) );
    }
    $postdata = array( "username" => $username, "password" => $password, "submit" => "Login" );
    $page = get( "http://www.linkswarm.com/swarmers/login/" );
    $hiddens = mm_search( "type='hidden'", ">", $page );
    foreach ( $hiddens as $hidden )
    {
        $hd_name = mm_search( "name='", "'", $hidden, false );
        $hd_name = trim( $hd_name[0] );
        $hd_val = mm_search( "value='", "'", $hidden, false );
        $hd_val = trim( $hd_val[0] );
        $postdata[$hd_name] = $hd_val;
    }
    $page = post( "http://www.linkswarm.com/swarmers/login/", $postdata, "http://www.linkswarm.com/" );
    if ( $err = getError( ) )
    {
        return $err;
    }
    if ( 200 != getHttpCode( ) )
    {
        return ASP_ERROR_LOGIN;
    }
    $page = get( "http://www.linkswarm.com/swarm_link/" );
    $postdata = array( "title" => $title, "tags" => implode( ",", $tags ), "url" => $link, "description" => $extended, "is_nsfw" => "on", "flavor" => "link" );
    $hiddens = mm_search( "type='hidden'", ">", $page );
    foreach ( $hiddens as $hidden )
    {
        $hd_name = mm_search( "name='", "'", $hidden, false );
        $hd_name = trim( $hd_name[0] );
        $hd_val = mm_search( "value='", "'", $hidden, false );
        $hd_val = trim( $hd_val[0] );
        $postdata[$hd_name] = $hd_val;
    }
    $page = post( "http://www.linkswarm.com/swarm_link/", $postdata, "http://www.linkswarm.com/swarm_link/" );
    if ( $err = getError( ) )
    {
        return $err;
    }
    if ( 200 != getHttpCode( ) )
    {
        return ASP_ERROR_POST;
    }
    return ASP_ERROR_OK;
}

function mm_post_twine( $curl, $vars )
{
    extract( $vars );
    if ( !empty( $extra ) )
    {
        $extra = str_replace( "http://www.twine.com/twine/", "", $extra );
        $extra = substr( $extra, 0, strpos( $extra, "/" ) );
    }
    $postdata = array( "username" => $username, "password" => $password, "rememberMe" => "true", "_location" => "http://www.twine.com/", "_form_key" => "yjxxkr" );
    get( "http://www.twine.com/" );
    $page = post( "https://www.twine.com/login", $postdata, "http://www.twine.com/" );
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_LOGIN;
    return ASP_ERROR_LOGIN;
    $page = get( "http://www.twine.com/create?type=basic:Bookmark" );
    $postdata = array( "http://www.w3.org/2000/01/rdf-schema#label" => $title, "http://www.radarnetworks.com/2007/09/12/basic#tag" => implode( ",", $tags ), "http://www.radarnetworks.com/2007/09/12/basic#url" => $link, "http://www.radarnetworks.com/2007/09/12/basic#description" => $extended );
    $hiddens = mm_search( "type=\"hidden\"", ">", $page );
    foreach ( $hiddens as $hidden )
    {
        $hd_name = mm_search( "name=\"", "\"", $hidden, false );
        $hd_name = trim( $hd_name[0] );
        $hd_val = mm_search( "value=\"", "\"", $hidden, false );
        $hd_val = trim( $hd_val[0] );
        $postdata[$hd_name] = $hd_val;
    }
    $page = post( "http://www.twine.com/_iform/save", $postdata, "http://www.twine.com/create?type=basic:Bookmark" );
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_POST;
    return ASP_ERROR_OK;
}

function mm_post_koolontheweb( $curl, $vars )
{
    extract( $vars );
    if ( 250 < strlen( $extended ) )
    {
        $extended = substr( $extended, 0, 247 )."...";
    }
    $postdata = array( "login" => $username, "password" => $password, "remember_me" => 1 );
    get( "http://koolontheweb.com/login" );
    $page = post( "http://koolontheweb.com/login", $postdata, "http://koolontheweb.com/login" );
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_LOGIN;
    return ASP_ERROR_LOGIN;
    $postdata = array( "link[url]" => $link, "link[title]" => $title, "link[description]" => $extended, "link[tag_list]" => implode( ",", $tags ), "link[is_private]" => 0 );
    get( "http://koolontheweb.com/post" );
    $page = post( "http://koolontheweb.com/users/{$username}/links", $postdata, "http://koolontheweb.com/post" );
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_POST;
    return ASP_ERROR_POST;
    return ASP_ERROR_OK;
}

function mm_post_kaboomit( $curl, $vars )
{
    extract( $vars );
    $postdata = array( "name" => $username, "pass" => $password, "form_id" => "user_login", "op" => "Log in" );
    get( "http://ka-boom-it.com/user/login" );
    $page = post( "http://ka-boom-it.com/user/login", $postdata, "http://ka-boom-it.com/user/login" );
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_LOGIN;
    return ASP_ERROR_LOGIN;
    $page = get( "http://ka-boom-it.com/node/add/drigg" );
    $postdata = array( "url" => $link, "title" => $title, "body" => $extended, "taxonomy[1]" => $extra, "taxonomy[tags][3]" => implode( ",", $tags ), "form_id" => "drigg_node_form", "op" => "Submit" );
    $hiddens = mm_search( "type=\"hidden\"", ">", $page );
    foreach ( $hiddens as $hidden )
    {
        $hd_name = mm_search( "name=\"", "\"", $hidden, false );
        $hd_name = trim( $hd_name[0] );
        $hd_val = mm_search( "value=\"", "\"", $hidden, false );
        $hd_val = trim( $hd_val[0] );
        if ( $hd_name == "form_token" )
        {
            $postdata[$hd_name] = $hd_val;
        }
    }
    $page = post( "http://ka-boom-it.com/node/add/drigg", $postdata, "http://ka-boom-it.com/node/add/drigg" );
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_POST;
    return ASP_ERROR_POST;
    return ASP_ERROR_OK;
}

function mm_post_dewfun( $curl, $vars )
{
    extract( $vars );
    $postdata = array( "username" => $username, "password" => $password, "remember" => 1, "Submit" => "Login" );
    get( "http://dewfun.com/login.php" );
    $page = post( "http://dewfun.com/login.php", $postdata, "http://dewfun.com/login.php" );
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_LOGIN;
    return ASP_ERROR_LOGIN;
    $postdata = array( "story_url" => $link, "dupe" => 1 );
    get( "http://dewfun.com/add_story.php" );
    $page = post( "http://dewfun.com/add_story.php", $postdata, "http://dewfun.com/add_story.php" );
    $postdata = array( "story_url" => $link, "story_title" => $title, "story_tags" => implode( ",", $tags ), "story_desc" => $extended, "story_category" => $extra );
    $page = post( "http://dewfun.com/add_story.php", $postdata, "http://dewfun.com/add_story.php" );
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_POST;
    return ASP_ERROR_OK;
}

function mm_post_a1webmarks( $curl, $vars )
{
    extract( $vars );
    $postdata = array( "username" => $username, "password" => $password, "remember" => 1, "submit" => "Submit" );
    get( "http://www.a1-webmarks.com/login.html" );
    $page = post( "http://www.a1-webmarks.com/login.html", $postdata, "http://www.a1-webmarks.com/login.html" );
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_LOGIN;
    return ASP_ERROR_LOGIN;
    $postdata = array( "link_1" => $link, "title" => $title, "tag" => implode( ",", $tags ), "remarks" => $extended, "private_remarks" => null, "privacy" => 3, "evaluation" => 2, "favorite" => 1, "submit" => "Submit", "id" => 0, "orig_link" => "http://", "orig_tag" => null );
    get( "http://www.a1-webmarks.com/bm_edit.html" );
    $page = post( "http://www.a1-webmarks.com/bm_edit.html", $postdata, "http://www.a1-webmarks.com/bm_edit.html" );
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_POST;
    return ASP_ERROR_OK;
}

function mm_post_pligg( $curl, $vars )
{
    extract( $vars );
    if ( substr( $extra, 0 - 1, 1 ) != "/" )
    {
        $extra .= "/";
    }
    $actionurl = "login";
    $page = get( $extra.$actionurl );
    if ( 200 != getHttpCode( ) )
    {
        $actionurl = "login.php";
    }
    $postdata = array( "username" => $username, "password" => $password, "persistent" => "on", "processlogin" => 1, "return" => "/" );
    $page = post( $extra.$actionurl, $postdata, $extra.$actionurl );
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_LOGIN;
    return ASP_ERROR_LOGIN;
    $actionurl = "submit";
    $page = get( $extra.$actionurl );
    if ( 200 != getHttpCode( ) )
    {
        $actionurl = "submit.php";
        $page = get( $extra.$actionurl );
    }
    $postdata = array( "url" => $link, "phase" => 1 );
    $hiddens = mm_search( "type=\"hidden\"", ">", $page );
    foreach ( $hiddens as $hidden )
    {
        $hd_name = mm_search( "name=\"", "\"", $hidden, false );
        $hd_name = trim( $hd_name[0] );
        $hd_val = mm_search( "value=\"", "\"", $hidden, false );
        $hd_val = trim( $hd_val[0] );
        if ( $hd_name != "phase" )
        {
            $postdata[$hd_name] = $hd_val;
        }
    }
    $page = post( $extra.$actionurl, $postdata, $extra.$actionurl );
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_POST;
    return ASP_ERROR_POST;
    $postdata = array( "title" => $title, "tags" => implode( ",", $tags ), "bodytext" => $extended, "url" => $link, "phase" => 2 );
    $hiddens = mm_search( "<option", "</option>", $page );
    foreach ( $hiddens as $hidden )
    {
        $hd_name = mm_search( "\">", "</", $hidden, false );
        $hd_name = trim( str_replace( "&nbsp;", "", $hd_name[0] ) );
        $hd_val = mm_search( "value = \"", "\"", $hidden, false );
        $hd_val = trim( $hd_val[0] );
        if ( !empty( $hd_val ) && strtolower( $hd_name ) == strtolower( $extra1 ) )
        {
            $postdata['category'] = $hd_val;
        }
    }
    if ( empty( $postdata['category'] ) )
    {
        $postdata['category'] = 1;
    }
    $hiddens = mm_search( "type=\"hidden\"", ">", $page );
    foreach ( $hiddens as $hidden )
    {
        $hd_name = mm_search( "name=\"", "\"", $hidden, false );
        $hd_name = trim( $hd_name[0] );
        $hd_val = mm_search( "value=\"", "\"", $hidden, false );
        $hd_val = trim( $hd_val[0] );
        $postdata[$hd_name] = $hd_val;
    }
    $page = post( $extra.$actionurl, $postdata, $extra.$actionurl );
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_POST;
    return ASP_ERROR_POST;
    $hiddens = mm_search( "type=\"hidden\"", ">", $page );
    foreach ( $hiddens as $hidden )
    {
        $hd_name = mm_search( "name=\"", "\"", $hidden, false );
        $hd_name = trim( $hd_name[0] );
        $hd_val = mm_search( "value=\"", "\"", $hidden, false );
        $hd_val = trim( $hd_val[0] );
        $postdata[$hd_name] = $hd_val;
    }
    $page = post( $extra.$actionurl, $postdata, $extra.$actionurl );
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_POST;
    return ASP_ERROR_OK;
}

function mm_post_propeller( $curl, $vars )
{
    extract( $vars );
    if ( empty( $tags ) )
    {
        $tags[] = "uncategorised";
    }
    $postdata = array( "member_name" => $username, "password" => $password, "next" => "/", "submit" => "sign in" );
    $page = get( "http://www.propeller.com/signin/", null, "http://www.propeller.com/" );
    $hiddens = mm_search( "type=\"hidden\"", ">", $page );
    foreach ( $hiddens as $hidden )
    {
        $hd_name = mm_search( "name=\"", "\"", $hidden, false );
        $hd_name = trim( $hd_name[0] );
        $hd_val = mm_search( "value=\"", "\"", $hidden, false );
        $hd_val = trim( $hd_val[0] );
        if ( $hd_name == "crumb" || $hd_name == "crumb_sig" )
        {
            $postdata[$hd_name] = $hd_val;
        }
    }
    $page = get( "https://www.propeller.com/ajax/security/propellersignin/", $postdata, "http://www.propeller.com/signin/" );
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_LOGIN;
    return ASP_ERROR_LOGIN;
    $postdata = array( "url" => $link );
    $page = post( "http://www.propeller.com/story/submit/", $postdata, "http://www.propeller.com/story/submit/" );
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_POST;
    return ASP_ERROR_POST;
    $postdata = array( "title_multi" => 0 - 1, "title_multi_text" => $title, "description" => $extended, "user_tags" => implode( " ", $tags ), "category" => $extra, "story_image" => 0 );
    $page = post( "http://www.propeller.com/story/submit/content/", $postdata, "http://www.propeller.com/story/submit/content/" );
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_POST;
    return ASP_ERROR_POST;
    $page = get( "http://www.propeller.com/story/submit/save/" );
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_POST;
    return ASP_ERROR_POST;
    return ASP_ERROR_OK;
}

function mm_post_taggly( $curl, $vars )
{
    extract( $vars );
    $page = post( "http://taggly.com/login/", array( "username" => $username, "password" => $password, "submitted" => "Log In", "query" => "" ) );
    return ASP_ERROR_LOGIN;
    if ( getHttpCode( ) == 0 )
    {
        return ASP_ERROR_PROXY;
    }
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_LOGIN;
    $posturl = "http://taggly.com/bookmarks/".$username."?action=add";
    $page = get( $posturl );
    $number = mm_search( "name=\"address", "\"", $page, false );
    $number = trim( $number[0] );
    $posturl = "http://taggly.com/bookmarks/".$username;
    $page = post( $posturl, array( "address".$number => $link, "title".$number => $title, "description".$number => $extended, "tags".$number => implode( ",", $tags ), "0", "Add Bookmark" ) );
    if ( getHttpCode( ) == 0 )
    {
        return ASP_ERROR_PROXY;
    }
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_POST;
    return ASP_ERROR_OK;
}

function mm_post_simpy( $curl, $vars )
{
    extract( $vars );
    auth( $username, $password );
    $page = get( "http://www.simpy.com/simpy/api/rest/SaveLink.do", array( "title" => $title, "href" => $link, "accessType" => "1", "tags" => implode( ",", $tags ), "note" => $extended ) );
    if ( getHttpCode( ) == 0 )
    {
        return ASP_ERROR_PROXY;
    }
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_LOGIN;
    $status_data = mm_search( "<code>", "</code>", $page, false );
    $status_data = $status_data[0];
    return ASP_ERROR_POST;
    return ASP_ERROR_OK;
}

function mm_post_feedmelinks( $curl, $vars )
{
    extract( $vars );
    $page = post( "http://feedmelinks.com/login", array( "userId" => $username, "password" => $password, "op" => "login", "debug" => "" ) );
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_LOGIN;
    $args = array( );
    $page = post( "http://feedmelinks.com/categorize", $args );
    $args = array( "url" => $link, "isPrivate" => "", "name" => $title, "op" => "submit", "ref" => "", "debug" => "", "version" => "pre_version", "from" => "mobile_bitch", "loggedIn" => "" );
    foreach ( $tags as $i => $tag )
    {
        $args["new_tag_{$i}"] = $tag;
    }
    $page = post( "http://feedmelinks.com/submit-link", $args );
    $page = get( "http://feedmelinks.com", "" );
    $code = mm_search( $title."</a>", "<img", $page, false );
    if ( $code[0] )
    {
        $page_to_public = mm_search( "href=\"", "\"", $code[0], false );
        $page = get( "http://feedmelinks.com".$page_to_public[0], "" );
    }
    if ( getHttpCode( ) == 0 )
    {
        return ASP_ERROR_PROXY;
    }
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_POST;
    return ASP_ERROR_OK;
}

function mm_post_excites( $curl, $vars )
{
    extract( $vars );
    $page = post( "http://www.excites.com/home/", array( "login" => $username, "password" => $password, "remember_me" => "", "sign_in" => "Sign In!" ) );
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_LOGIN;
    return ASP_ERROR_LOGIN;
    $page = get( "http://www.excites.com/add_bookmark/" );
    $postdata = array( "url" => $link, "title" => $title, "tags" => implode( " ", $tags ), "notes" => $extended, "private_flag" => "0", "save" => "Save" );
    $hiddens = mm_search( "type=\"hidden\"", ">", $page );
    foreach ( $hiddens as $hidden )
    {
        $hd_name = mm_search( "name=\"", "\"", $hidden, false );
        $hd_name = trim( $hd_name[0] );
        $hd_val = mm_search( "value=\"", "\"", $hidden, false );
        $hd_val = trim( $hd_val[0] );
        $postdata[$hd_name] = $hd_val;
    }
    if ( $err = getError( ) )
    {
        return $err;
    }
    $page = post( "http://www.excites.com/add_bookmark/", $postdata, "http://www.excites.com/add_bookmark/" );
    if ( getHttpCode( ) == 0 )
    {
        return ASP_ERROR_PROXY;
    }
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_POST;
    return ASP_ERROR_OK;
}

function mm_post_furl( $curl, $vars )
{
    extract( $vars );
    $page = get( "http://www.furl.net/members/login" );
    $page = post( "http://www.furl.net/members/login", array( "username" => $username, "password" => $password, "autoLogin" => 1, "Submit" => "Log In" ), "http://www.furl.net/members/login" );
    return ASP_ERROR_LOGIN;
    $postdata = array( "item[url]" => $link, "item[title]" => $title, "groups[]" => "", "topics" => implode( ",", $tags ), "item[keywords]" => implode( ", ", $tags ), "item[public]" => "true", "item[isRead]" => "true", "item[description]" => $extended, "item[clip]" => "", "item[rating]" => 5, "item[title_orig]" => "", "save_and_email" => "", "item[via]" => "" );
    $page = post( "http://www.furl.net/items", $postdata, "http://www.furl.net/items/new" );
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_POST;
    return ASP_ERROR_OK;
}

function mm_post_shoutwire( $curl, $vars )
{
    extract( $vars );
    $page = get( "http://shoutwire.com/login" );
    $postdata = array( "__EVENTTARGET" => "", "__EVENTARGUMENT" => "", "ctl06\$Username" => $username, "ctl06\$Password" => $password, "ctl06\$ctl00" => "Sign in" );
    $hiddens = mm_search( "type=\"hidden\"", ">", $page );
    foreach ( $hiddens as $hidden )
    {
        $hd_name = mm_search( "name=\"", "\"", $hidden, false );
        $hd_name = trim( $hd_name[0] );
        $hd_val = mm_search( "value=\"", "\"", $hidden, false );
        $hd_val = trim( $hd_val[0] );
        if ( $hd_name == "__VIEWSTATE" )
        {
            $postdata[$hd_name] = $hd_val;
        }
    }
    $page = post( "http://shoutwire.com/login", $postdata, "http://shoutwire.com/login" );
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_LOGIN;
    return ASP_ERROR_LOGIN;
    $page = get( "http://shoutwire.com/submit" );
    $postdata = array( "ctl06\$txtLink" => $link, "ctl06\$btnSubmitStep1" => "Submit Link" );
    $hiddens = mm_search( "type=\"hidden\"", ">", $page );
    foreach ( $hiddens as $hidden )
    {
        $hd_name = mm_search( "name=\"", "\"", $hidden, false );
        $hd_name = trim( $hd_name[0] );
        $hd_val = mm_search( "value=\"", "\"", $hidden, false );
        $hd_val = trim( $hd_val[0] );
        if ( $hd_name == "__VIEWSTATE" )
        {
            $postdata[$hd_name] = $hd_val;
        }
    }
    $page = post( "http://shoutwire.com/submit", $postdata, "http://shoutwire.com/submit" );
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_POST;
    return ASP_ERROR_POST;
    return ASP_ERROR_OK;
}

function mm_post_getboo( $curl, $vars )
{
    extract( $vars );
    $page = get( "http://getboo.com/login.php" );
    $postdata = array( "name" => $username, "pass" => $password, "remember" => "on", "submitted" => "Log In" );
    $hiddens = mm_search( "type=\"hidden\"", ">", $page );
    foreach ( $hiddens as $hidden )
    {
        $hd_name = mm_search( "name=\"", "\"", $hidden, false );
        $hd_name = trim( $hd_name[0] );
        $hd_val = mm_search( "value=\"", "\"", $hidden, false );
        $hd_val = trim( $hd_val[0] );
        if ( $hd_name == "token" )
        {
            $postdata[$hd_name] = $hd_val;
        }
    }
    $page = post( "http://getboo.com/login.php", $postdata, "http://getboo.com/login.php" );
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_LOGIN;
    return ASP_ERROR_LOGIN;
    $page = get( "http://getboo.com/add.php" );
    $postdata = array( "folderid" => 0, "url" => $link, "title" => $title, "description" => $extended, "publicChk" => "on", "tags" => implode( " ", $tags ), "submitted" => "Add Bookmark" );
    $hiddens = mm_search( "type=\"hidden\"", ">", $page );
    foreach ( $hiddens as $hidden )
    {
        $hd_name = mm_search( "name=\"", "\"", $hidden, false );
        $hd_name = trim( $hd_name[0] );
        $hd_val = mm_search( "value=\"", "\"", $hidden, false );
        $hd_val = trim( $hd_val[0] );
        if ( $hd_name == "token" )
        {
            $postdata[$hd_name] = $hd_val;
        }
    }
    $page = post( "http://getboo.com/add.php", $postdata, "http://getboo.com/add.php" );
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_POST;
    return ASP_ERROR_POST;
    return ASP_ERROR_OK;
}

function mm_post_newsvine( $curl, $vars )
{
    extract( $vars );
    $extra = trim( $extra );
    if ( empty( $extra ) )
    {
        $extra = "-";
    }
    $page = post( "https://www.newsvine.com/_tools/user/login", array( "m" => "login", "email" => $username, "pass" => $password ), "https://www.newsvine.com/_tools/user/login" );
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_LOGIN;
    return ASP_ERROR_LOGIN;
    $postdata = array( "url" => $link, "headline" => $title, "newsType" => "x", "categoryTag" => $extra, "tags" => implode( " ", $tags ), "publishTo" => "groups", "groupId[]" => 0 );
    $page = post( "http://www.newsvine.com/_action/tools/saveLink", $postdata, "http://www.newsvine.com/_tools/seed" );
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_POST;
    return ASP_ERROR_OK;
}

function mm_post_mybookmarks( $curl, $vars )
{
    extract( $vars );
    $page = post( "http://www.mybookmarks.com/marks/", array( "cmd" => "authenticate", "login" => $username, "password" => $password, "mode" => "p" ), "http://www.mybookmarks.com/marks/cmd=login/" );
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_LOGIN;
    return ASP_ERROR_LOGIN;
    $postdata = array( "cmd" => "new_bookmark", "protocol" => "", "url" => $link, "desc" => $title, "quick" => 1, "mode" => "page" );
    $page = post( "http://www.mybookmarks.com/marks/", $postdata, "http://www.mybookmarks.com/marks/cmd=print_add_bookmark/mode=page" );
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_POST;
    return ASP_ERROR_OK;
}

function mm_post_dzone( $curl, $vars )
{
    extract( $vars );
    $extra = trim( $extra );
    if ( empty( $extra ) )
    {
        $extra = "news";
    }
    $page = post( "http://www.dzone.com/links/j_acegi_security_check", array( "j_username" => $username, "j_password" => $password, "_acegi_security_remember_me" => "on" ), "http://www.dzone.com/links/loginLightbox.html" );
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_LOGIN;
    return ASP_ERROR_LOGIN;
    $postdata = array( "title" => $title, "url" => $link, "description" => $extended, "tags" => $extra );
    $page = post( "http://www.dzone.com/links/add.html", $postdata, "http://www.dzone.com/links/add.html" );
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_POST;
    return ASP_ERROR_POST;
    return ASP_ERROR_OK;
}

function mm_post_delirious( $curl, $vars )
{
    extract( $vars );
    if ( empty( $extra ) )
    {
        $extra = "http://de.lirio.us/";
    }
    else if ( substr( $extra, 0 - 1, 1 ) != "/" )
    {
        $extra .= "/";
    }
    if ( $extra == "http://de.lirio.us/" )
    {
        setRedirect( false );
        $page = post( $extra."login.php", array( "username" => $username, "password" => $password, "submitted" => "Log In", "query" => "" ) );
        if ( $err = getError( ) )
        {
            return $err;
        }
        return ASP_ERROR_LOGIN;
        $page = post( $extra."bookmarks.php/".$username."?action=add", array( "address" => $link, "title" => $title, "tags" => implode( ",", $tags ), "description" => $extended, "status" => "0", "submitted" => "Add Bookmark" ) );
        if ( $err = getError( ) )
        {
            return $err;
        }
        return ASP_ERROR_OK;
    }
    auth( $username, $password );
    $page = get( $extra."api/posts/add", array( "url" => $link, "tags" => implode( " ", $tags ), "description" => $title, "extended" => $extended, "shared" => "yes" ) );
    if ( getHttpCode( ) == 0 )
    {
        return ASP_ERROR_PROXY;
    }
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_LOGIN;
    return ASP_ERROR_POST;
    return ASP_ERROR_OK;
}

function mm_post_searchles( $curl, $vars )
{
    extract( $vars );
    $extra = trim( $extra );
    if ( empty( $extra ) )
    {
        $extra = "1";
    }
    $page = post( "http://www.searchles.com/login", array( "login[username]" => $username, "login[password]" => $password, "return" => "http://www.searchles.com/", "formsubmit" => "Sign In" ) );
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_LOGIN;
    return ASP_ERROR_LOGIN;
    if ( !is_array( $tags ) || empty( $tags ) )
    {
        $tags = explode( " ", $title );
    }
    $extended = trim( strip_tags( $extended ) );
    $page = post( "http://www.searchles.com/links/add_link", "", "http://www.searchles.com/my" );
    $Post_link_id = mm_search( "/links/add_link/", "\"", $page, false );
    $Post_link = "http://www.searchles.com/links/add_link/".$Post_link_id[0];
    $postdata = array( "link[url]" => $link, "description[title]" => $title, "tags[tags]" => implode( ",", $tags ), "description[desc]" => $extended, "storyChannel" => $extra, "submit" => "Submit Post" );
    $hiddens = mm_search( "type=\"hidden\"", ">", $page );
    foreach ( $hiddens as $hidden )
    {
        $hd_name = mm_search( "name=\"", "\"", $hidden, false );
        $hd_name = trim( $hd_name[0] );
        $hd_val = mm_search( "value=\"", "\"", $hidden, false );
        $hd_val = trim( $hd_val[0] );
        $postdata[$hd_name] = $hd_val;
    }
    if ( $err = getError( ) )
    {
        return $err;
    }
    $page = post( $Post_link, $postdata, "http://www.searchles.com/links/add_link/" );
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_POST;
    return ASP_ERROR_OK;
}

function mm_post_bibsonomy( $curl, $vars )
{
    extract( $vars );
    $page = get( "http://www.bibsonomy.org/login" );
    $page = post( "http://www.bibsonomy.org/login", array( "loginMethod" => "db", "referer" => "http://www.bibsonomy.org/", "username" => $username, "password" => $password ) );
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_LOGIN;
    return ASP_ERROR_LOGIN;
    $pst_array = array( "url" => $link, "submit" => "check" );
    $page = post( "http://www.bibsonomy.org/postBookmark", $pst_array );
    if ( count( $tags ) == 0 )
    {
        $tags[] = $title;
    }
    $postdata = array( "post.resource.url" => $link, "post.resource.title" => $title, "post.description" => $extended, "tags" => implode( " ", $tags ), "abstractGrouping" => "public", "jump" => "false", "intraHashToUpdate" => "" );
    $hiddens = mm_search( "<input value=\"", "type=\"hidden\"", $page );
    foreach ( $hiddens as $hidden )
    {
        $hd_name = mm_search( "name=\"", "\"", $hidden, false );
        $hd_name = trim( $hd_name[0] );
        $hd_val = mm_search( "value=\"", "\"", $hidden, false );
        $hd_val = trim( $hd_val[0] );
        if ( $hd_name == "postID" || $hd_name == "ckey" )
        {
            $postdata[$hd_name] = $hd_val;
        }
    }
    if ( $err = getError( ) )
    {
        return $err;
    }
    $page = post( "http://www.bibsonomy.org/postBookmark", $postdata, "http://www.bibsonomy.org/postBookmark" );
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_POST;
    return ASP_ERROR_OK;
}

function mm_post_faves( $curl, $vars )
{
    extract( $vars );
    $page = post( "https://secure.faves.com/signIn", array( "rUsername" => $username, "rPassword" => $password, "action" => "Sign In" ) );
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_LOGIN;
    return ASP_ERROR_LOGIN;
    $page = get( "http://faves.com/CreateDot.aspx" );
    $postdata = array( "rateSelect" => "1", "urlText" => $link, "subjectText" => $title, "tagsText" => implode( ",", $tags ), "noteText" => $extended, "shareSelect" => "Public", "addFriendsText" => "", "publishButton" => "Publish" );
    $hiddens = mm_search( "type=\"hidden\"", ">", $page );
    foreach ( $hiddens as $hidden )
    {
        $hd_name = mm_search( "name=\"", "\"", $hidden, false );
        $hd_name = trim( $hd_name[0] );
        $hd_val = mm_search( "value=\"", "\"", $hidden, false );
        $hd_val = trim( $hd_val[0] );
        $postdata[$hd_name] = $hd_val;
    }
    if ( $err = getError( ) )
    {
        return $err;
    }
    $page = post( "http://faves.com/CreateDot.aspx", $postdata, "http://faves.com/CreateDot.aspx" );
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_POST;
    return ASP_ERROR_OK;
}

function mm_post_xilinus( $curl, $vars )
{
    extract( $vars );
    $page = post( "http://my.xilinus.com/login/login", array( "user_login" => $username, "user_password" => $password, "" => "Login" ) );
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_LOGIN;
    return ASP_ERROR_LOGIN;
    if ( count( $tags ) == 0 )
    {
        $tags[] = $title;
    }
    $page = get( "http://my.xilinus.com/link/add" );
    $postdata = array( "link[url]" => $link, "group[name]" => $title, "tag_link" => implode( " ", $tags ), "link[public]" => "1" );
    $hiddens = mm_search( "type=\"hidden\"", ">", $page );
    foreach ( $hiddens as $hidden )
    {
        $hd_name = mm_search( "name=\"", "\"", $hidden, false );
        $hd_name = trim( $hd_name[0] );
        $hd_val = mm_search( "value=\"", "\"", $hidden, false );
        $hd_val = trim( $hd_val[0] );
        $postdata[$hd_name] = $hd_val;
    }
    if ( $err = getError( ) )
    {
        return $err;
    }
    $page = post( "http://my.xilinus.com/link/add", $postdata, "http://my.xilinus.com/link/add" );
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_POST;
    return ASP_ERROR_OK;
}

function mm_post_buddymarks( $curl, $vars )
{
    extract( $vars );
    $page = post( "http://buddymarks.com/login.php", array( "action" => "login", "bookmark_title" => "", "bookmark_url" => "", "form_username" => $username, "form_password" => $password, "login_type" => "permanent", "" => "Login" ) );
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_LOGIN;
    return ASP_ERROR_LOGIN;
    if ( !is_array( $tags ) || empty( $tags ) )
    {
        $tags = explode( " ", $title );
    }
    $page = get( "http://buddymarks.com/add_bookmark.php" );
    $postdata = array( "form_buddymark" => "true", "form_public_link" => "true", "form_group_type" => "existing", "form_group_title" => "", "form_group_parent_id" => 0, "form_cat_id" => 1, "bookmark_url" => $link, "bookmark_title" => $title, "form_tags" => implode( ",", $tags ), "form_description" => $extended, "form_private" => "2" );
    $hiddens = mm_search( "<option", "<option", $page );
    foreach ( $hiddens as $hidden )
    {
        $hd_name = mm_search( "'>", "\n", $hidden, false );
        if ( !empty( $hd_name ) )
        {
            $hd_name = trim( $hd_name[0] );
        }
        $hd_val = mm_search( "value='", "'>", $hidden, false );
        if ( !empty( $hd_val ) )
        {
            $hd_val = trim( $hd_val[0] );
        }
        if ( !empty( $hd_val ) && strtolower( $hd_name ) == strtolower( $extra ) )
        {
            $postdata['form_group_id'] = $hd_val;
            break;
        }
    }
    if ( $err = getError( ) )
    {
        return $err;
    }
    $postdata['back_url'] = "http://buddymarks.com/";
    $page = post( "http://buddymarks.com/add_bookmark.php?action=insert_bookmark", $postdata, "http://buddymarks.com/add_bookmark.php" );
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_POST;
    return ASP_ERROR_OK;
}

function mm_post_connectedy( $curl, $vars )
{
    extract( $vars );
    $page = post( "http://www.connectedy.com/login.php", array( "user" => $username, "password" => $password ) );
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_LOGIN;
    return ASP_ERROR_LOGIN;
    $page = get( "http://www.connectedy.com/add-link.php?category=0" );
    $postdata = array( "url" => $link, "title" => $title, "dest" => "0", "shared" => "1", "" => "Add" );
    $hiddens = mm_search( "type=\"hidden\"", ">", $page );
    foreach ( $hiddens as $hidden )
    {
        $hd_name = mm_search( "name=\"", "\"", $hidden, false );
        $hd_name = trim( $hd_name[0] );
        $hd_val = mm_search( "value=\"", "\"", $hidden, false );
        $hd_val = trim( $hd_val[0] );
        $postdata[$hd_name] = $hd_val;
    }
    if ( $err = getError( ) )
    {
        return $err;
    }
    $page = post( "http://www.connectedy.com/modify.php", $postdata, "http://www.connectedy.com/add-link.php?category=0" );
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_POST;
    return ASP_ERROR_OK;
}

function mm_post_misterwong( $curl, $vars )
{
    extract( $vars );
    $extra = trim( $extra );
    if ( empty( $extra ) )
    {
        $extra = "com";
    }
    $page = post( "http://www.mister-wong.".$extra."/login", array( "_method" => "POST", "data[User][name]" => $username, "data[User][password]" => $password ), "http://www.mister-wong.".$extra."/users/login/" );
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_LOGIN;
    return ASP_ERROR_LOGIN;
    return ASP_ERROR_LOGIN;
    return ASP_ERROR_LOGIN;
    return ASP_ERROR_LOGIN;
    if ( 200 < strlen( $extended ) )
    {
        $extended = substr( $extended, 0, 197 )."..";
    }
    $page = get( "http://www.mister-wong.".$extra."/addurl" );
    if ( count( $tags ) == 0 )
    {
        $tags[] = $title;
    }
    if ( 12 < count( $tags ) )
    {
        $tags = array_slice( $tags, 0, 12 );
    }
    $postdata = array( "_method" => "POST", "data[Bookmark][return]" => "http://www.mister-wong.".$extra."/user/", "data[Bookmark][url]" => $link, "data[Bookmark][title]" => $title, "data[Bookmark][comment]" => $extended, "data[Bookmark][tags]" => implode( " ", $tags ), "data[Bookmark][status]" => "public", "data[Twitter][username]" => "Username", "data[Twitter][password2]" => "Password", "data[Twitter][password]" => "", "data[Twitter][save_data]" => "yes", "data[Twitter][message]" => "" );
    $hiddens = mm_search( "type=\"hidden\"", ">", $page );
    foreach ( $hiddens as $hidden )
    {
        $hd_name = mm_search( "name=\"", "\"", $hidden, false );
        $hd_name = trim( $hd_name[0] );
        $hd_val = mm_search( "value=\"", "\"", $hidden, false );
        $hd_val = trim( $hd_val[0] );
        $postdata[$hd_name] = $hd_val;
    }
    if ( $err = getError( ) )
    {
        return $err;
    }
    $page = post( "http://www.mister-wong.".$extra."/addurl", $postdata, "http://www.mister-wong.".$extra."/addurl" );
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_POST;
    return ASP_ERROR_OK;
}

function mm_post_blinklist( $curl, $vars )
{
    extract( $vars );
    $page = post( "http://www.blinklist.com/user/login", array( "username" => $username, "password" => $password ) );
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_LOGIN;
    $page = post( "http://www.blinklist.com/blink", array( "name" => $title, "url" => $link, "tags" => implode( ",", $tags ), "description" => $extended, "email" => "" ) );
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_POST;
    return ASP_ERROR_OK;
}

function mm_post_delicious( $curl, $vars )
{
    extract( $vars );
    auth( $username, $password );
    $page = get( "https://api.del.icio.us/v1/posts/add", array( "url" => $link, "tags" => implode( " ", $tags ), "description" => $title, "extended" => $extended, "shared" => "yes" ) );
	
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_LOGIN;
    return ASP_ERROR_POST;
    return ASP_ERROR_OK;
}

function mm_post_myweb( $curl, $vars )
{
    extract( $vars );
    setRedirect( false );
    $page = post( "https://login.yahoo.com/config/login", ".tries=1&.src=srch&.md5=&.hash=&.js=&.last=&promo=&.intl=us&.bypass=&.partner=&.yplus=&.emailCode=&pkg=&stepid=&.ev=&hasMsgr=0&.chkP=Y&.done=".urlencode( "http://myweb2.search.yahoo.com" )."&.pd=srch_ver%3d0&login=".urlencode( $username )."&passwd=".urlencode( $password ) );
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_LOGIN;
    $page = get( "http://myweb.yahoo.com/myresults/bookmarklet" );
    preg_match( "#name=\\.scrumb value=\"([^\"]+)\"#", $page, $match );
    return ASP_ERROR_POST;
    $vars = makeQuery( array( ".scrumb" => $match[1], "p" => "", "ei" => "UTF-8", "myfr" => "bmlt", "onesummary" => 1, "hl" => "0", "ft" => "", "v" => "1", "bkln" => "1", ".done" => "http://myweb.yahoo.com/myresults/bookmarklet?ei=UTF-8", "u" => $link, "t" => $title, "tag" => implode( ",", $tags ), "d" => $extended ) );
    $page = post( "http://myweb.yahoo.com/myresults/insertresult", $vars );
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_POST;
    return ASP_ERROR_POST;
    return ASP_ERROR_OK;
}

function mm_post_blogmarks( $curl, $vars )
{
    extract( $vars );
    $page = post( "http://blogmarks.net/", array( "login" => $username, "pwd" => $password, "connect" => "1" ) );
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_LOGIN;
    $page = get( "http://blogmarks.net/my/marks,new" );
    $page = post( "http://blogmarks.net/my/marks,new", array( "url" => $link, "title" => $title, "content" => $extended, "public-tags" => implode( ",", $tags ), "private-tags" => "", "via" => "", "visibility" => "0", "referer" => "http://blogmarks.net/my/marks", "id" => "" ) );
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_POST;
    return ASP_ERROR_OK;
}

function mm_post_scuttle( $curl, $vars )
{
    extract( $vars );
    if ( empty( $extra1 ) )
    {
        $custom_tags = implode( " ", $tags );
    }
    else
    {
        $custom_tags = implode( $extra1, $tags );
    }
    if ( empty( $extra ) )
    {
        $extra = "http://scuttle.org/";
    }
    else if ( substr( $extra, 0 - 1, 1 ) != "/" )
    {
        $extra .= "/";
    }
    if ( $extra == "http://scuttle.org/" )
    {
        setRedirect( false );
        $page = post( $extra."login/", array( "username" => $username, "password" => $password, "submitted" => "Log In", "query" => "" ) );
        if ( $err = getError( ) )
        {
            return $err;
        }
        return ASP_ERROR_LOGIN;
        $page = post( $extra."bookmarks/".$username, array( "address" => $link, "title" => $title, "tags" => $custom_tags, "description" => $extended, "status" => "0", "submitted" => "Add Bookmark" ) );
        if ( $err = getError( ) )
        {
            return $err;
        }
        return ASP_ERROR_POST;
        return ASP_ERROR_OK;
    }
    auth( $username, $password );
    $page = get( $extra."api/posts/add", array( "url" => $link, "tags" => $custom_tags, "description" => $title, "extended" => $extended, "shared" => "yes" ) );
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_LOGIN;
    return ASP_ERROR_POST;
    return ASP_ERROR_OK;
}

function mm_post_scuttleplus( $curl, $vars )
{
    extract( $vars );
    if ( !empty( $settings['BookmarkScuttleLinks'] ) )
    {
        $extended = substr( $extendedHTML, 0, $settings['BookmarkScuttleCharLimit'] )."...";
    }
    $extra = trim( $extra );
    if ( empty( $extra ) )
    {
        $extra = "http://scuttle.org/";
    }
    else if ( substr( $extra, 0 - 1, 1 ) != "/" )
    {
        $extra .= "/";
    }
    $postdata = array( "url" => $link, "tags" => implode( ",", $tags ), "description" => $title, "extended" => $extended, "shared" => "yes" );
    auth( $username, $password );
    $page = get( $extra."api/posts_add.php", $postdata );
    if ( getHttpCode( ) == 0 )
    {
        return ASP_ERROR_PROXY;
    }
    return ASP_ERROR_LOGIN;
    return ASP_ERROR_POST;
    return ASP_ERROR_OK;
}

function mm_post_markaboo( $curl, $vars )
{
    extract( $vars );
    $page = post( "http://www.markaboo.com/users/authenticate", array( "login" => $username, "password" => $password, "commit" => "Log in" ) );
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_LOGIN;
    setRedirect( false );
    $page = post( "http://www.markaboo.com/resources/create", array( "resource[url]" => $link, "resource[title]" => $title, "resource[description]" => $extended, "tag_list" => implode( ",", $tags ), "resource[private]" => "0", "resource[rating]" => "5", "commit" => "Create Bookmark" ) );
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_POST;
    return ASP_ERROR_OK;
}

function mm_post_linkagogo( $curl, $vars )
{
    extract( $vars );
    $password = substr(  );
    $page = get( "http://www.linkagogo.com/go/Logout" );
    $page = get( "http://www.linkagogo.com/go/Authenticate" );
    $page = post( "http://www.linkagogo.com/go/Authenticate", array( "userName" => $username, "code" => $password, "btnLogin" => "Login" ), "http://linkagogo.com/go/Authenticate" );
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_LOGIN;
    $page = get( "http://www.linkagogo.com/go/AddMenu" );
    preg_match( "#<option value=\"([^\"]+)\">\\s*Home</option>#si", $page, $match );
    return ASP_ERROR_POST;
    $home_folder = $match[1];
    $page = post( "http://www.linkagogo.com/go/AddMenu", array( "url" => $link, "title" => $title, "comments" => $extended, "keywords" => implode( " ", $tags ), "favorites" => "on", "alias" => "", "rating" => "4", "remind" => "-9", "days" => "", "folder" => $home_folder, "newFolder" => "", "image" => "", "user" => $username, "password" => "null", "submit" => "Add" ) );
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_POST;
    return ASP_ERROR_OK;
}

function mm_post_backflip( $curl, $vars )
{
    extract( $vars );
    $extra = trim( $extra );
    setRedirect( false );
    $page = post( "http://www.backflip.com/perl/auth.pl?ls=01", array( "username" => $username, "password" => $password, "sekret" => "simpleijit", "keep_password" => "true" ) );
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_LOGIN;
    $page = post( "http://www.backflip.com/perl/addMark.pl", array( "url" => $link, "title" => $title, "note" => $extended, "folder" => 0, "close" => "true", "target" => "", "source" => "B", "SubmitUrl" => "Backflip It!" ) );
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_POST;
    return ASP_ERROR_OK;
}

function mm_post_google( $curl, $vars )
{
    extract( $vars );
    setRedirect( false );
    $page = post( "https://www.google.com/accounts/ServiceLoginAuth", array( "continue" => "http://www.google.com/bookmarks/", "service" => "bookmarks", "nui" => "1", "hl" => "en", "Email" => $username, "Passwd" => $password, "rmShown" => "1", "null" => "Sign in" ) );
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_LOGIN;
    $page = get( "http://www.google.com/bookmarks/mark?op=add" );
    return ASP_ERROR_POST;
    $submit_url = "http://www.google.com".str_replace( "&amp;", "&", $match[1] );
    $page = get( $submit_url );
    return ASP_ERROR_POST;
    return ASP_ERROR_POST;
    $submit_url = "http://www.google.com".str_replace( "&amp;", "&", $match2[1] );
    $sig = $match[1];
    $postdata = array( "bkmk" => $link, "title" => $title, "labels" => implode( ",", $tags ), "annotation" => $extended, "sig" => $sig, "prev" => "/lookup", "q" => "is:bookmarked", "btnA" => "Add bookmark" );
    $page = post( $submit_url, $postdata );
    foreach ( $postdata as $key => $val )
    {
        $str .= "{$key} == {$val} \n";
    }
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_POST;
    return ASP_ERROR_OK;
}

function mm_post_linkatopia( $curl, $vars )
{
    extract( $vars );
    $page = get( "http://linkatopia.com/login" );
    $hiddens = mm_search( "type=\"hidden\"", ">", $page );
    foreach ( $hiddens as $hidden )
    {
        $hd_name = mm_search( "name=\"", "\"", $hidden, false );
        $hd_name = trim( $hd_name[0] );
        $hd_val = mm_search( "value=\"", "\"", $hidden, false );
        $hd_val = trim( $hd_val[0] );
        $postdata[$hd_name] = $hd_val;
    }
    $rrr = array_merge( $postdata, array( "a1" => $username, "a2" => $password, "submit" => "Submit" ) );
    $page = post( "http://linkatopia.com/login", $rrr );
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_LOGIN;
    return ASP_ERROR_LOGIN;
    $page = get( "http://linkatopia.com/add" );
    if ( !is_array( $tags ) || empty( $tags ) )
    {
        $tags[] = substr( $title, 0, 15 );
    }
    $postdata = array( "rateSelect" => "1", "xurl" => $link, "xtitle" => $title, "xtags" => implode( ",", $tags ), "xnotes" => $extended, "nbox1" => "255 characters max", "zprivacy" => "0", "zdate" => date( "Ymd", time( ) ), "zrating" => "0", "addlink" => "Save" );
    $hiddens = mm_search( "type=\"hidden\"", ">", $page );
    foreach ( $hiddens as $hidden )
    {
        $hd_name = mm_search( "name=\"", "\"", $hidden, false );
        $hd_name = trim( $hd_name[0] );
        $hd_val = mm_search( "value=\"", "\"", $hidden, false );
        $hd_val = trim( $hd_val[0] );
        $postdata[$hd_name] = $hd_val;
    }
    if ( $err = getError( ) )
    {
        return $err;
    }
    $page = post( "http://linkatopia.com/add", $postdata, "http://linkatopia.com/add" );
    if ( getHttpCode( ) == 0 )
    {
        return ASP_ERROR_PROXY;
    }
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_POST;
    return ASP_ERROR_OK;
}

function mm_post_sphinn( $curl, $vars )
{
    extract( $vars );
    $extra = trim( $extra );
    if ( count( $tags ) == 0 )
    {
        $tags[] = $title;
    }
    $page = post( "http://sphinn.com/login", array( "action" => "login", "processlogin" => "1", "return" => "", "username" => $username, "password" => $password, "persistent" => "", "" => "Login" ) );
    return ASP_ERROR_LOGIN;
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_LOGIN;
    $postdata = array( "url" => $link, "title" => $title, "tags" => implode( ",", $tags ), "bodytext" => htmlentities( strip_tags( $extended ) ), "category" => $extra, "submit" => "Submit News Story", "trackback" => "" );
    $page = get( "http://sphinn.com/submit" );
    if ( getHttpCode( ) == 0 || strpos( $page, "You have already submitted a post today" ) )
    {
        return ASP_ERROR_PROXY;
    }
    $hiddens = mm_search( "type=\"hidden\"", ">", $page );
    foreach ( $hiddens as $hidden )
    {
        $hd_name = mm_search( "name=\"", "\"", $hidden, false );
        $hd_name = trim( $hd_name[0] );
        $hd_val = mm_search( "value=\"", "\"", $hidden, false );
        $hd_val = trim( $hd_val[0] );
        if ( $hd_name )
        {
            $found = true;
        }
        $postdata[$hd_name] = $hd_val;
    }
    return ASP_ERROR_POST;
    $postdata[phase] = "1";
    $postdata[url] = $link;
    $postdata[category] = $extra;
    $page = post( "http://sphinn.com/submit", $postdata );
    if ( getHttpCode( ) == 0 )
    {
        return ASP_ERROR_PROXY;
    }
    $hiddens = mm_search( "type=\"hidden\"", ">", $page );
    foreach ( $hiddens as $hidden )
    {
        $hd_name = mm_search( "name=\"", "\"", $hidden, false );
        $hd_name = trim( $hd_name[0] );
        $hd_val = mm_search( "value=\"", "\"", $hidden, false );
        $hd_val = trim( $hd_val[0] );
        $postdata[$hd_name] = $hd_val;
    }
    $postdata[phase] = "2";
    $page = post( "http://sphinn.com/submit", $postdata );
    if ( getHttpCode( ) == 0 )
    {
        return ASP_ERROR_PROXY;
    }
    $postdata[phase] = "3";
    $page = post( "http://sphinn.com/submit", $postdata );
    if ( getHttpCode( ) == 0 )
    {
        return ASP_ERROR_PROXY;
    }
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_POST;
    return ASP_ERROR_OK;
}

function mm_post_magnolia( $curl, $vars )
{
    extract( $vars );
    if ( count( $tags ) == 0 )
    {
        $tags[] = substr( $title, 0, 39 );
    }
    $page = post( "http://ma.gnolia.com/authentication/signin_dispatcher", array( "preferred_auth_provider[name]" => "magnolia", "username" => $username, "password" => $password, "auth_method_magnolia" => "password" ), "http://ma.gnolia.com/signin" );
    if ( $err = getError( ) )
    {
        return $err;
    }
    $page = post( "http://ma.gnolia.com/bookmarks/create", array( "url" => $link, "title" => $title, "description" => $extended, "tags" => implode( ",", $tags ), "private" => "", "rating" => 0 ), "http://ma.gnolia.com/bookmarks/add" );
    if ( $err = getError( ) )
    {
        return $err;
    }
    if ( 302 == getHttpCode( ) || 200 == getHttpCode( ) )
    {
    }
    else
    {
        return $debug ? aspcleanhtml( $page, "post" ) : ASP_ERROR_POST;
    }
    return ASP_ERROR_POST;
    return ASP_ERROR_OK;
}

function mm_post_linkroll( $curl, $vars )
{
    extract( $vars );
    if ( count( $tags ) == 0 )
    {
        $tags[] = $title;
    }
    $page = post( "http://www.linkroll.com/index.php?action=authorize", array( "remember" => 1, "user_login" => $username, "user_password" => $password, "track" => "", "url" => "", "title" => "" ), "http://www.linkroll.com/index.php?action=login" );
    return ASP_ERROR_LOGIN;
    $page = get( "http://www.linkroll.com/index.php?action=addLink" );
    $page = post( "http://www.linkroll.com/index.php?action=saveLink&id=", array( "user" => "", "link_url" => $link, "link_title" => $title, "link_description" => $extended, "link_categories" => implode( ",", $tags ), "B3" => "Save" ), "http://www.linkroll.com/index.php?action=addLink" );
    if ( $err = getError( ) )
    {
        return $err;
    }
    return ASP_ERROR_POST;
    return ASP_ERROR_OK;
}

function mm_search( $start, $end, $string, $borders = true )
{
    $reg = "!".preg_quote( $start )."(.*?)".preg_quote( $end )."!is";
    preg_match_all( $reg, $string, $matches );
    if ( $borders )
    {
        return $matches[0];
    }
    return $matches[1];
}

function aspcleanhtml( $page, $type )
{
    $search = array( "|<script[^>]*?>.*?</script>|si", "|<style[^>]*?>.*?</style>|si", "|<xml[^>]*?>.*?</xml>|si", "|<[/!]*?[^<>]*?>|si", "|<!--.*?-->|si", "|&[A-Za-z]+;|i", "|&#[0-9]+;|i", "|(\\s)+|s" );
    $page = preg_replace( $search, " ", $page );
    switch ( $type )
    {
        case "login" :
            $page = "Login error - ".$page;
            break;
        case "post" :
            $page = "Posting error - ".$page;
    }
    return $page;
}

?>
