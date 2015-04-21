<?php


function aspinit( )
{
    load_plugin_textdomain( ASPTextDomain, PLUGINDIR."/".dirname( plugin_basename( __FILE__ ) )."/languages", dirname( plugin_basename( __FILE__ ) )."/languages" );
    if ( $_POST['action'] == "ASPExportAccounts" )
    {
        $settings = get_option( ASPOptions );
        $fileContent = "";
        foreach ( $settings['Accounts'] as $Accounts )
        {
            $fileContent .= sprintf( "\"%d\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\"", $Accounts['enabled'], $Accounts['service'], $Accounts['extra'], $Accounts['extra1'], $Accounts['username'], $Accounts['password'] )."\r\n";
        }
        header( "Pragma: public" );
        header( "Last-Modified: ".gmdate( "D, d M Y H:i:s" )." GMT" );
        header( "Cache-Control: no-store, no-cache, must-revalidate" );
        header( "Cache-Control: pre-check=0, post-check=0, max-age=0" );
        header( "Content-Transfer-Encoding: none" );
        header( "Content-Type: text/plain; name=\"CSV-Export-File.txt\"" );
        header( "Content-Disposition: attachment; filename=\"CSV-Export-File.txt\"" );
        print $fileContent;
        exit( );
    }
}

function aspbookmark( $PostID, $Resubmit = false )
{
	
    $settings = get_option( ASPOptions );
    if ( empty( $PostID ) )
	{
        return true;
    }
    if ( !$Resubmit && in_array( $PostID, $settings['Posted'] ) )
    {
        return true;
    }
    set_time_limit( 0 );
    $post = wp_get_single_post( $PostID );
	file_put_contents('aspbookmark.txt' , $postID);
    $ConditionsTags = array_intersect( $post->tags_input, $settings['ConditionsTags'] );
    $ConditionsCategories = array_intersect( $post->post_category, $settings['ConditionsCategories'] );
    if ( !empty( $settings['ConditionsTags'] ) && empty( $ConditionsTags ) )
    {
        return true;
    }
    if ( !empty( $settings['ConditionsCategories'] ) && empty( $ConditionsCategories ) )
    {
        return true;
    }
    $postCustomTitle = get_post_meta( $PostID, "ASP-Title", true );
    $postCustomExtended = get_post_meta( $PostID, "ASP-Description", true );
    $postCustomTitle = empty( $postCustomTitle ) ? "" : explode( "\n", $postCustomTitle );
    $postCustomExtended = empty( $postCustomExtended ) ? "" : explode( "\n", $postCustomExtended );
    $siteError = 0;
    $siteDone = 0;
    $siteReport = "";
    $siteReports = array( );
    $Accounts = array( );
    $Parms = array( "service" => "", "username" => "", "password" => "", "extra" => "", "extra1" => "", "link" => get_permalink( $PostID ), "title" => $post->post_title, "extended" => "", "extendedHTML" => "", "tags" => array( ), "result" => "", "settings" => $settings, "debug" => $settings['ReportsPageContent'] );
    require_once( ASPPath."/mm_services.php" );
    foreach ( $settings['Accounts'] as $Account )
    {
        if ( !function_exists( "mm_post_".$Account['service'] ) )
        {
            continue;
        }
        if ( !empty( $Account['enabled'] ) && !empty( $Account['username'] ) && !empty( $Account['password'] ) )
        {
            $Accounts[] = $Account;
        }
		
    }
	
    if ( !empty( $Accounts ) )
    {
		
        if ( $settings[$AccountLimit] && $settings[$AccountLimit] < count( $Accounts ) )
        {
            shuffle( $Accounts );
        }
        if ( $settings['Tags'] == "Post" )
        {
            if ( $settings['PostTags'] == "WordPress" )
            {
                $Parms['tags'] = $post->tags_input;
            }
            else if ( $settings['PostTags'] == "Manual" && !empty( $settings['PostTagsManualStarter'] ) && !empty( $settings['PostTagsManualSeparator'] ) )
            {
                $content = $post->post_content;
                $ManualTagsStarter = $settings['PostTagsManualStarter'];
                $ManualTagsSeparator = $settings['PostTagsManualSeparator'];
                preg_match( "/".$ManualTagsStarter."\\s*(([0-9a-zA-Z ]*)\\s*".$ManualTagsSeparator."\\s*)*([0-9a-zA-Z ]*)\\b/i", $content, $ManualTagsMatches );
                if ( !empty( $ManualTagsMatches ) )
                {
                    $ManualTagsMatches = preg_replace( "/\\s*".$ManualTagsSeparator."\\s*/i", $ManualTagsSeparator, trim( reset( $ManualTagsMatches ) ) );
                    $ManualTagsMatches = preg_replace( "/".$ManualTagsStarter."/i", "", $ManualTagsMatches );
                    $Parms['tags'] = explode( $ManualTagsSeparator, trim( $ManualTagsMatches ) );
                }
            }
        }
        if ( $settings['Tags'] == "Yahoo" )
        {
            $cURLPostData = array( "appid" => $settings['YahooKey'], "output" => "php", "context" => $post->post_content );
            $cURL = curl_init( );
            curl_setopt( $cURL, CURLOPT_URL, "http://search.yahooapis.com/ContentAnalysisService/V1/termExtraction" );
            curl_setopt( $cURL, CURLOPT_POST, true );
            curl_setopt( $cURL, CURLOPT_POSTFIELDS, $cURLPostData );
            curl_setopt( $cURL, CURLOPT_HEADER, false );
            curl_setopt( $cURL, CURLOPT_RETURNTRANSFER, true );
            $cURLResponse = unserialize( curl_exec( $cURL ) );
            curl_close( $cURL );
            if ( !empty( $cURLResponse['ResultSet']['Result'] ) )
            {
                $Parms['tags'] = $cURLResponse['ResultSet']['Result'];
                if ( 1 < $settings['YahooTagsLimit'] && $settings['YahooTagsLimit'] < count( $Parms['tags'] ) )
                {
                    $Parms['tags'] = array_slice( $Parms['tags'], 0, $settings['YahooTagsLimit'] );
                }
            }
        }
        if ( ( $settings['Tags'] == "Preset" || $settings['TagsFallback'] && empty( $Parms['tags'] ) ) && !empty( $settings['PresetTags'] ) )
        {
            $PresetTags = array_map( "trim", array_filter( explode( ",", $settings['PresetTags'] ), "trim" ) );
            $Parms['tags'] = $PresetTags;
        }
        if ( empty( $Parms['tags'] ) )
        {
            $Parms['tags'][] = "Uncategorized";
        }
        if ( $settings['TagsLimit'] && $settings['TagsLimit'] < count( $Parms['tags'] ) )
        {
            shuffle( $Parms['tags'] );
            $Parms['tags'] = array_slice( $Parms['tags'], 0, $settings['TagsLimit'] );
        }
        $extended = str_replace( array( "\t", "\r", "\n" ), "", strip_tags( $post->post_content ) );
        if ( 255 < strlen( $extended ) )
        {
            $extended = substr( $extended, 0, 252 )."...";
        }
        $Parms['extended'] = $extended;
        $extendedHTML = str_replace( array( "\t", "\r", "\n" ), "", strip_tags( $post->post_content, "<a>" ) );
        $Parms['extendedHTML'] = $extendedHTML;
        foreach ( $Accounts as $Account )
        {
            $siteFunction = "mm_post_".$Account['service'];
            $Parms['title'] = empty( $postCustomTitle ) ? $Parms['title'] : trim( $postCustomTitle[array_rand( $postCustomTitle, 1 )] );
            $Parms['extended'] = empty( $postCustomExtended ) ? $Parms['extended'] : trim( $postCustomExtended[array_rand( $postCustomExtended, 1 )] );
            $Parms['service'] = $Account['service'];
            $Parms['username'] = $Account['username'];
            $Parms['password'] = $Account['password'];
            $Parms['extra'] = $Account['extra'];
            $Parms['extra1'] = $Account['extra1'];
            if ( empty( $settings['BookmarkTestMode'] ) )
            {
                require_once( ASPPath."/class-key.php" );
                $licenseReport = checklicense( $settings['LicenseKey'] );
				
                if ( !empty( $licenseReport ) )
                {	
                    $Parms['result'] = $licenseReport;
                    $siteReports[] = $Parms;
                    ++$siteError;
                    break;
                }
                require_once( ASPPath."/class-curl.php" );
                if ( empty( $settings['ReportsCURL'] ) )
                {
                    $Var_10824->ASPCurl( );
                    $cURL =& $Var_10824;
                }
                else
                {
                    $Var_10920->ASPCurl( ASPPathReports.ASPFileCURLLog );
                    $cURL =& $Var_10920;
                }
                setTimeout( 10, 60 );
                $Parms['result'] = $siteFunction( $cURL, $Parms );
                close( );
            }
            else
            {
                $Parms['result'] = "ASP_ERROR_OK";
            }
            if ( $Parms['result'] == "ASP_ERROR_OK" )
            {
                ++$siteDone;
            }
            else
            {
                ++$siteError;
            }
            $siteReports[] = $Parms;
            if ( $settings['AccountLimit'] && $settings['AccountLimit'] <= $siteDone )
            {
                break;
                break;
            }
        }
        if ( $siteDone && empty( $settings['BookmarkTestMode'] ) && !in_array( $PostID, $settings['Posted'] ) )
        {
            $settings['Posted'][] = $PostID;
            update_option( ASPOptions, $settings );
        }
    }
    else
    {
		
        $siteReport = __( "No active accounts found for bookmarking.", ASPTextDomain );
    }
    aspreports( $Parms, $siteReport, $siteReports );
    return true;
}

function aspremotebookmark( $args )
{
	file_put_contents('aspbookmark.txt' , 2);
    set_time_limit( 0 );
    $settings = get_option( ASPOptions );
    $siteError = 0;
    $siteDone = 0;
    $siteReport = "";
    $siteReports = array( );
    $Accounts = array( );
    $Parms = array( "service" => "", "username" => "", "password" => "", "extra" => "", "extra1" => "", "link" => $args['link'], "title" => $args['title'], "extended" => $args['extended'], "extendedHTML" => $args['extended'], "tags" => array_map( "trim", array_filter( explode( ",", $args['tags'] ), "trim" ) ), "result" => "", "debug" => $settings['ReportsPageContent'] );
    require_once( ASPPath."/mm_services.php" );
    foreach ( $settings['Accounts'] as $Account )
    {
        if ( !function_exists( "mm_post_".$Account['service'] ) )
        {
            continue;
        }
        if ( !empty( $Account['enabled'] ) && !empty( $Account['username'] ) && !empty( $Account['password'] ) )
        {
            $Accounts[] = $Account;
        }
		
    }
	file_put_contents('hereiam.txt' , array_shift($Accounts));
    if ( !empty( $Accounts ) )
    {
        if ( $settings['AccountLimit'] && $settings['AccountLimit'] < count( $Accounts ) )
        {
            shuffle( $Accounts );
        }
        if ( $settings['TagsLimit'] && $settings['TagsLimit'] < count( $Parms['tags'] ) )
        {
            shuffle( $Parms['tags'] );
            $Parms['tags'] = array_slice( $Parms['tags'], 0, $settings['TagsLimit'] );
        }
        foreach ( $Accounts as $Account )
        {
            $siteFunction = "mm_post_".$Account['service'];
            $Parms['service'] = $Account['service'];
            $Parms['username'] = $Account['username'];
            $Parms['password'] = $Account['password'];
            $Parms['extra'] = $Account['extra'];
            $Parms['extra1'] = $Account['extra1'];
            if ( empty( $settings['BookmarkTestMode'] ) )
            {
                require_once( ASPPath."/class-key.php" );
                $licenseReport = checklicense( $settings['LicenseKey'] );
                if ( !empty( $licenseReport ) )
                {
                    $Parms['result'] = $licenseReport;
                    $siteReports[] = $Parms;
                    ++$siteError;
                    break;
                }
                require_once( ASPPath."/class-curl.php" );
                if ( empty( $settings['ReportsCURL'] ) )
                {
                    $Var_4008->ASPCurl( );
                    $cURL =& $Var_4008;
                }
                else
                {
                    $Var_4104->ASPCurl( ASPPathReports.ASPFileCURLLog );
                    $cURL =& $Var_4104;
                }
                setTimeout( 10, 60 );
                $Parms['result'] = $siteFunction( $cURL, $Parms );
            }
            else
            {
                $Parms['result'] = "ASP_ERROR_OK";
            }
            if ( $Parms['result'] == "ASP_ERROR_OK" )
            {
                ++$siteDone;
            }
            else
            {
                ++$siteError;
            }
            $Parms['title'] = $Parms['title']." (".$Parms['link'].")";
            $siteReports[] = $Parms;
            if ( $settings['AccountLimit'] && $settings['AccountLimit'] <= $siteDone )
            {
                break;
                break;
            }
        }
    }
    else
    {
        $siteReport = __( "No active accounts found for bookmarking.", ASPTextDomain );
    }
    aspreports( $Parms, $siteReport, $siteReports );
    return true;
}

function asppublishpost( $PostID )
{
	file_put_contents('function_fired' , $PostID );
    $args = array( $PostID, false );
    if ( $timestamp = wp_next_scheduled( "ASPBookmarkHook", $args ) )
    {
		file_put_contents( 'timestamp.txt' , $timestamp);
        wp_unschedule_event( $timestamp, "ASPBookmarkHook", $args );
    }
    wp_schedule_single_event( time( ), "ASPBookmarkHook", $args );
    return true;
}

function aspreports( $Parms, $siteReport = "", $siteReports = array( ) )
{
    $settings = get_option( ASPOptions );
    $siteResult = array( "ASP_ERROR_LOGIN" => __( "Login error, bookmarking failed!", ASPTextDomain ), "ASP_ERROR_POST" => __( "Posting error, bookmarking failed!", ASPTextDomain ), "ASP_ERROR_OK" => __( "Bookmarking done!", ASPTextDomain ) );
    $logContent = empty( $settings['BookmarkTestMode'] ) ? "" : "TEST MODE \n";
    if ( $settings['ReportsLog'] || $settings['ReportsEmail'] )
    {
        $logContent .= sprintf( "Time: %s \n", date( "d-m-Y h:i A", current_time( "timestamp" ) ) );
        $logContent .= sprintf( "Post: %s  |  Tags: %s \n", $Parms['title'], implode( ", ", $Parms['tags'] ) );
        if ( !empty( $siteReport ) )
        {
            $logContent .= "Error: {$siteReport} \n";
        }
        else if ( !empty( $siteReports ) )
        {
            foreach ( $siteReports as $report )
            {
                $tempResult = array_key_exists( $report['result'], $siteResult ) ? $siteResult[$report['result']] : $report['result'];
                $logContent .= sprintf( "%s  |  %s  |  %s \n", $report['service'], $report['username'], $tempResult );
            }
        }
        $logContent = $logContent."\n";
    }
    if ( $settings['ReportsEmail'] )
    {
        $logContentEmail = "Blog: ".get_option( "siteurl" )."\n{$logContent}";
        $logContentEmail = nl2br( $logContentEmail );
        $logContentEmail = "<table border=\"0\" style=\"width: 100%; font-family: Verdana, Arial, sans-serif; font-size: 13px; text-align: justify;\">\n\t\t\t<tr> <td>".$logContentEmail."</td> </tr>\n\t\t\t</table>";
        wp_mail( get_option( "admin_email" ), get_option( "blogname" )." - ".__( "ASP Bookmarking Report", ASPTextDomain ), $logContentEmail, $headers = "Content-type: text/html; charset=iso-8859-1" );
    }
    if ( $settings['ReportsLog'] )
    {
        $logFile = fopen( ASPPathReports.ASPFileLog, "at" );
        if ( $logFile && flock( $logFile, LOCK_EX ) )
        {
            fwrite( $logFile, $logContent );
            flock( $logFile, LOCK_UN );
            fclose( $logFile );
        }
    }
}

function aspthecontent( $content )
{
    $settings = get_option( ASPOptions );
    if ( $settings['PostTagsManualHide'] )
    {
        $ManualTagsStarter = $settings['PostTagsManualStarter'];
        $ManualTagsSeparator = $settings['PostTagsManualSeparator'];
        preg_match( "/".$ManualTagsStarter."\\s*(([0-9a-zA-Z ]*)\\s*".$ManualTagsSeparator."\\s*)*([0-9a-zA-Z ]*)\\b/i", $content, $ManualTagsMatches );
        return str_replace( trim( reset( $ManualTagsMatches ) ), "", $content );
    }
    return $content;
}

function aspadmin( )
{
    global $ASPVersion;
    global $ASPOptionsKeys;
    global $ASPServices;
    $ASPLicenseFile = get_bloginfo( "wpurl" )."/wp-content/plugins/".dirname( plugin_basename( __FILE__ ) )."/class-key.php";
    $dewfun = array( "9" => "Bollywood", "6" => "Entertainment", "2" => "Funny", "7" => "Gaming", "8" => "General", "3" => "News", "4" => "Sports", "1" => "Technology", "10" => "Trailers", "5" => "Videos", "11" => "Watch Online Movies" );
    $dzone = array( ".net" => ".net", "agile" => "agile", "ajax" => "ajax", "announcement" => "announcement", "apple" => "apple", "books" => "books", "c-and-cpp" => "c-and-cpp", "coldfusion" => "coldfusion", "css-html" => "css-html", "database" => "database", "eclipse" => "eclipse", "flash-flex" => "flash-flex", "frameworks" => "frameworks", "games" => "games", "groovy" => "groovy", "gui" => "gui", "hardware" => "hardware", "how-to" => "how-to", "humor" => "humor", "java" => "java", "javascript" => "javascript", "methodology" => "methodology", "microsoft" => "microsoft", "mobile" => "mobile", "news" => "news", "open source" => "open source", "opinion" => "opinion", "other languages" => "other languages", "perl" => "perl", "php" => "php", "python" => "python", "research" => "research", "reviews" => "reviews", "ria" => "ria", "ruby" => "ruby", "security" => "security", "server" => "server", "standards" => "standards", "tools" => "tools", "trends" => "trends", "unix-linux" => "unix-linux", "usability" => "usability", "web 2.0" => "web 2.0", "web design" => "web design", "web services" => "web services", "windows" => "windows", "xml" => "xml" );
    $kaboomit = array( "27" => "Family & Society", "1" => "Internet Marketing", "2" => "Copywriting & Articles", "23" => "Network Marketing", "3" => "Mentoring & Coaching", "24" => "Multi Level Marketing - MLM", "4" => "Promotion & Advertising", "5" => "Website Design & Software", "6" => "Graphics & Templates", "7" => "Photography & Art", "8" => "Seminars & Workshops", "9" => "Books & Authors", "10" => "Personal Development", "11" => "Money & Finance", "12" => "Games & Gaming", "13" => "Videos & Podcasts", "14" => "Travel ", "15" => "Health & Alternative Health", "16" => "Environment", "17" => "Hand Made & Hobbies", "18" => "Sports", "19" => "Politics", "20" => "Religion", "21" => "Pets & Animals", "22" => "Food Topics", "25" => "Automotive", "26" => "Home & Garden " );
    $misterWong = array( "com" => "US", "cn" => "China", "de" => "Germany", "fr" => "France", "es" => "Spain", "ru" => "Russia" );
    $newsvine = array( "arts" => "Arts", "business" => "Business", "education" => "Education", "entertainment" => "Entertainment", "fashion" => "Fashion", "health" => "Health", "history" => "History", "home-garden" => "Home Garden", "odd-news" => "Odd News", "politics" => "Politics", "religion" => "Religion", "science" => "Science", "sports" => "Sports", "technology" => "Technology", "travel" => "Travel", "us-news" => "U.S. News", "world-news" => "World News" );
    $propeller = array( "11" => "News", "12" => "Arts & Entertainment", "14" => "Style", "15" => "Sports", "16" => "Business & Finance", "17" => "Humor", "18" => "Science & Technology", "20" => "Family", "24" => "Religion", "25" => "Health & Fitness", "26" => "Politics" );
    $shoutwire = array( "8" => "Apple", "85" => "Autos", "5" => "Design", "9" => "Gaming Tech", "44" => "General Tech", "11" => "Hardware", "10" => "Linux", "14" => "P2P", "12" => "Programming", "55" => "Science - Space", "13" => "Security", "6" => "Software", "86" => "Weapons", "4" => "Biz Technology", "45" => "General Biz", "3" => "Jobs Economy", "1" => "Markets Stocks Funds", "2" => "Personal Finance", "82" => "Douchebaggery", "47" => "General Politics", "54" => "Law - Justice", "84" => "Mideast Conflict", "29" => "US Politics", "83" => "War", "30" => "World", "34" => "Books", "58" => "Celebrities", "35" => "Gaming Entertainment", "48" => "General Entertainment", "71" => "Humor", "31" => "Movies", "32" => "Music", "52" => "Oddly Enough", "56" => "Pets - Animals", "33" => "TV", "37" => "Conditions", "81" => "Conspiracy", "57" => "Dating", "39" => "Diet Fitness", "80" => "Dumbass", "49" => "General Lifestyle", "77" => "Hero", "36" => "Lifestyle Tech", "38" => "Parenting", "78" => "Scary", "79" => "Strange", "76" => "WTF?!", "50" => "General Weather", "42" => "US Weather", "43" => "World", "51" => "General Education", "53" => "History", "40" => "US Education", "41" => "World", "63" => "American Football", "59" => "Baseball", "60" => "Basketball", "64" => "Golf", "65" => "Hockey", "66" => "Motorsport", "69" => "Other Sports", "67" => "Soccer", "68" => "Tennis", "72" => "Informative General", "70" => "Other General", "73" => "Adult General", "88" => "Interviews", "87" => "NSFW", "74" => "SFW" );
    $sphinn = array( "2" => "Google", "27" => "-Google SEO", "4" => "-Google AdWords", "3" => "-Google AdSense", "5" => "-Google Searching", "28" => "-Google Other", "6" => "Yahoo", "7" => "-Yahoo SEO", "8" => "-Yahoo Paid Search", "9" => "-Yahoo Publisher Network", "29" => "-Yahoo Searching", "30" => "-Yahoo Other", "10" => "Microsoft", "31" => "-Microsoft SEO", "11" => "-Microsoft adCenter", "32" => "-Microsoft Searching", "33" => "-Microsoft Other", "19" => "Search Marketing", "38" => "-SEM", "20" => "-SEO", "22" => "-Paid Search", "21" => "-Link Building", "39" => "-SEM Industry", "40" => "-In House SEM", "41" => "-Other Search Marketing", "23" => "Social Media", "34" => "-News Sites", "35" => "-Bookmarking", "36" => "-Networking", "53" => "-Blogging", "37" => "-Other Social Media", "48" => "Online Marketing", "49" => "-Web Analytics", "50" => "-Contextual Ads", "51" => "-Affiliate Marketing", "52" => "-Display Advertising", "62" => "-Usability", "63" => "-Domaining", "61" => "-Other Online Marketing", "24" => "Searching", "25" => "-Ask.com", "26" => "-AOL", "58" => "-Local & Maps", "59" => "-Mobile Search", "60" => "-Video Search", "44" => "-Tips & Tools", "45" => "-Other Searching", "46" => "Other", "47" => "-Sphinn Zone", "54" => "-Jobs Wanted", "55" => "-Jobs Offered", "56" => "-Water Cooler" );
    if ( empty( $_POST['action'] ) )
    {
        require_once( ASPPath."/class-version.php" );
        if ( version_compare( $ASPVersion, $version, "<" ) )
        {
            print "<div class=\"error\"><p><strong>".sprintf( __( ASPName." %s is available!", ASPTextDomain ), $version )."</strong><br />".__( "Please upgrade to the latest version which you received by email</a>.", ASPTextDomain )."</p></div>";
        }
    }
    if ( $_POST['action'] == "ASPSaveSettings" )
    {
        $settings = get_option( ASPOptions );
        $Post = aspcheckpostvalues( $_POST, $ASPOptionsKeys );
        foreach ( $Post as $key => $val )
        {
            if ( array_key_exists( $key, $settings ) )
            {
                $settings[$key] = $val;
            }
        }
        if ( update_option( ASPOptions, $settings ) )
        {
            print "<div class=\"updated\"><p><strong>".__( "Settings updated successfully.", ASPTextDomain )."</strong></p></div>";
        }
    }
    if ( $_POST['action'] == "ASPImportAccounts" )
    {
        $settings = get_option( ASPOptions );
        $CSVFile = "CSVFile";
        if ( $_FILES[$CSVFile] )
        {
            $fileCSV = fopen( $_FILES[$CSVFile]['tmp_name'], "r" );
            $CSVKeys = array( "enabled", "service", "extra", "extra1", "username", "password" );
            $CSVDone = $CSVError = 0;
            $Accounts = $settings['Accounts'];
            while ( ( $CSVValues = fgetcsv( $fileCSV, filesize( $_FILES[$CSVFile]['tmp_name'] ) + 50 ) ) !== false )
            {
                if ( count( $CSVValues ) == count( $CSVKeys ) )
                {
                    $CSVAccounts = array_combine( $CSVKeys, $CSVValues );
                    $Accounts[] = array( "service" => $CSVAccounts['service'], "enabled" => $CSVAccounts['enabled'] ? $CSVAccounts['enabled'] : "", "extra" => $CSVAccounts['extra'], "extra1" => $CSVAccounts['extra1'], "username" => $CSVAccounts['username'], "password" => $CSVAccounts['password'] );
                    ++$CSVDone;
                }
                else
                {
                    ++$CSVError;
                }
            }
            fclose( $fileCSV );
            $settings['Accounts'] = $Accounts;
            if ( update_option( ASPOptions, $settings ) )
            {
                print "<div class=\"updated\"><p><strong>".sprintf( __( "%d Bookmarking accounts imported and %d failed.", ASPTextDomain ), $CSVDone, $CSVError )."</strong></p></div>";
            }
        }
    }
    if ( $_POST['action'] == "ASPSaveAccounts" )
    {
        $settings = get_option( ASPOptions );
        $Post = $_POST;
        $Accounts = array( );
        foreach ( $Post['service'] as $key => $value )
        {
            if ( !empty( $Post['username'][$key] ) && !empty( $Post['password'][$key] ) )
            {
                $Accounts[] = array( "service" => $Post['service'][$key], "enabled" => $Post['enabled'][$key], "extra" => $Post['extra'][$key], "extra1" => $Post['extra1'][$key], "username" => $Post['username'][$key], "password" => $Post['password'][$key] );
            }
        }
        asort( $Accounts );
        $settings['Accounts'] = $Accounts;
        if ( update_option( ASPOptions, $settings ) )
        {
            print "<div class=\"updated\"><p><strong>".__( "Bookmarking accounts updated successfully.", ASPTextDomain )."</strong></p></div>";
        }
    }
    if ( $_POST['action'] == "ASPDeleteAccounts" )
    {
        $settings = get_option( ASPOptions );
        $settings['Accounts'] = array( );
        if ( update_option( ASPOptions, $settings ) )
        {
            print "<div class=\"updated\"><p><strong>".__( "Bookmarking accounts deleted successfully.", ASPTextDomain )."</strong></p></div>";
        }
    }
    if ( $_POST['action'] == "ASPDeleteReport" )
    {
        if ( file_exists( ASPPathReports.ASPFileLog ) && unlink( ASPPathReports.ASPFileLog ) )
        {
            print "<div class=\"updated\"><p><strong>".__( "Bookmarking report deleted successfully.", ASPTextDomain )."</strong></p></div>";
        }
        else
        {
            print "<div class=\"error\"><p><strong>".__( "Bookmarking report deletion failed.", ASPTextDomain )."</strong></p></div>";
        }
    }
    if ( file_exists( ASPPathReports.ASPFileLog ) )
    {
        $BookmarkingLogSize = filesize( ASPPathReports.ASPFileLog );
        if ( version_compare( phpversion( ), "5.1.0", ">=" ) && 102400 < $BookmarkingLogSize )
        {
            $BookmarkingLog = file_get_contents( ASPPathReports.ASPFileLog, false, null, $BookmarkingLogSize - 102400, $BookmarkingLogSize );
        }
        else
        {
            $BookmarkingLog = file_get_contents( ASPPathReports.ASPFileLog );
        }
    }
    else
    {
        $BookmarkingLog = __( "No logs found.", ASPTextDomain );
    }
    $settings = get_option( ASPOptions );
    if ( !function_exists( "curl_init" ) )
    {
        print "<div class=\"error\"><p>".__( "This plugin requires <strong><a target=\"_blank\" href=\"http://curl.haxx.se/\">cURL library</a></strong> to be installed on Web Server.", ASPTextDomain )."</p></div>";
    }
    if ( !is_writable( ASPPathReports ) )
    {
        print "<div class=\"error\"><p>".__( "Please ensure that the following directory is writable", ASPTextDomain ).":<br><strong>".ASPPathReports."</strong></p></div>";
    }
    if ( empty( $settings['LicenseKey'] ) )
    {
        print "<div class=\"error\"><p>".__( "Please enter your <strong>License Key</strong> that can be retrieved <a target=\"_blank\" href=\"http://lma.mass-automation.appspot.com/recover\">here</a>.", ASPTextDomain )."</p></div>";
    }
    if ( $settings['Tags'] == "Yahoo" && empty( $settings['YahooKey'] ) )
    {
        print "<div class=\"error\"><p>".__( "Please enter your <strong><a target=\"_blank\" href=\"http://developer.yahoo.com/wsregapp/\">Yahoo Application ID</a></strong>.", ASPTextDomain )."</p></div>";
    }
    if ( !empty( $settings['BookmarkTestMode'] ) )
    {
        print "<div class=\"error\"><p>".__( "The plugin is running in <strong>Test Mode</strong>.", ASPTextDomain )."</p></div>";
    }
    if ( !empty( $settings['BookmarkActive'] ) && !empty( $settings['LicenseKey'] ) && !empty( $settings['Accounts'] ) )
    {
        foreach ( $settings['Accounts'] as $Accounts )
        {
            if ( $Accounts['enabled'] )
            {
                print "<div class=\"updated\"><p>".__( "The plugin is active and will automatically bookmark to the enabled websites next time you publish a post!", ASPTextDomain )."</p></div>";
                break;
                break;
            }
        }
    }
    if ( !empty( $_GET['ASPPostID'] ) )
    {
        $PostID = $_GET['ASPPostID'];
        $post =& get_post( $PostID );
        $args = array( $PostID, true );
        if ( $timestamp = wp_next_scheduled( "ASPBookmarkHook", $args ) )
        {
            wp_unschedule_event( $timestamp, "ASPBookmarkHook", $args );
        }
        wp_schedule_single_event( time( ), "ASPBookmarkHook", $args );
        print "<div class=\"updated\"><p>".__( "Bookmarking process queued for", ASPTextDomain )." \"<strong>{$post->post_title}</strong>\".</p></div>";
        print "<div class=\"wrap\"> <h2>".ASPName."</h2> <p><a href=\"admin.php?page=".plugin_basename( __FILE__ )."\">".__( "Continue...", ASPTextDomain )."</a></p> </div>";
    }
    else
    {
        echo "\n\t\t<div class=\"wrap\">\n\n\t\t\t<h2>";
        print ASPName;
        echo "</h2>\n\n\t\t\t";
        echo "<s";
        echo "tyle type=\"text/css\">\n\n\t\t\t\t#ASPAccounts input, #ASPAccounts select\n\t\t\t\t{\n\t\t\t\t\tborder: 1px solid #D7D7D7;\n\t\t\t\t}\n\n\t\t\t\t.ASPAccountsText\n\t\t\t\t{\n\t\t\t\t\twidth: 100%;\n\t\t\t\t}\n\n\t\t\t\t.ASPSpacer\n\t\t\t\t{\n\t\t\t\t\tborder: none;\n\t\t\t\t\tcolor: #e6e6e6;\n\t\t\t\t\tbackground: #e6e6e6;\n\t\t\t\t}\n\n\t\t\t</style>\n\n\t";
        if ( isset( $_GET['PHPInfo'] ) )
        {
            echo "\n\t\t\t<h3>PHP Information</h3>\n\n\t\t\t";
            phpinfo( INFO_GENERAL | INFO_CONFIGURATION | INFO_MODULES );
            echo "\n\t\t\t<hr class=\"ASPSpacer\" />\n\t\t\t<p></p>\n\n\t";
        }
        echo "\n\t";
        if ( isset( $_GET['Settings'] ) )
        {
            echo "\n\t\t\t<h3>Plugin Settings</h3>\n\n\t\t\t";
            print "<pre>";
            print_r( $settings );
            print "</pre>";
            echo "\n\t\t\t<hr class=\"ASPSpacer\" />\n\t\t\t<p></p>\n\n\t";
        }
        echo "\n\t";
        if ( isset( $_GET['errorLog'] ) )
        {
            echo "\n\t\t\t<h3>Error Log</h3>\n\n\t\t\t";
            if ( file_exists( ASPPathReports.ASPFileErrorLog ) )
            {
                print "<pre>".file_get_contents( ASPPathReports.ASPFileErrorLog )."</pre>";
            }
            else
            {
                print "No logs found.";
            }
            echo "\n\t\t\t<hr class=\"ASPSpacer\" />\n\t\t\t<p></p>\n\n\t";
        }
        echo "\n\t";
        if ( isset( $_GET['errorCURL'] ) )
        {
            echo "\n\t\t\t<h3>cURL Log</h3>\n\n\t\t\t";
            if ( file_exists( ASPPathReports.ASPFileCURLLog ) )
            {
                print "<pre>".file_get_contents( ASPPathReports.ASPFileErrorLog )."</pre>";
            }
            else
            {
                print "No logs found.";
            }
            echo "\n\t\t\t<hr class=\"ASPSpacer\" />\n\t\t\t<p></p>\n\n\t";
        }
        echo "\n\t\t\t<h3>";
        _e( "General Settings", ASPTextDomain );
        echo "</h3>\n\n\n\t\t\t";
        echo "<s";
        echo "tyle type=\"text/css\">\n\n\t\t\t\t#ASP-Tab\n\t\t\t\t{\n\t\t\t\t\tclear: both;\n\t\t\t\t\theight: 100%;\n\t\t\t\t\tborder-bottom: 5px solid #eeeeee;\n\t\t\t\t\toverflow: hidden;\n\t\t\t\t}\n\n\t\t\t\t.ASP-Tab-Content, .ASP-Tab-Content-Alt\n\t\t\t\t{\n\t\t\t\t\tdisplay: block;\n\t\t\t\t}\n\n\t\t\t\t.ASP-Tab-Content-Alt\n\t\t\t\t{\n\t\t\t\t\tdisplay: none;\n\t\t\t\t}\n\n\t\t\t\t.ASP-Tab-Menu, .ASP-Tab-Menu-Alt\n\t\t\t\t{\n\t\t\t\t\tfloat: left;\n\t\t\t\t\twidth: auto;\n\t\t\t\t\tpadding: 8px;\n\t\t\t\t\tborder: 1px so";
        echo "lid #eeeeee;\n\t\t\t\t\tborder-bottom: none;\n\t\t\t\t\tbackground: #eeeeee;\n\t\t\t\t\ttext-align: center;\n\t\t\t\t\ttext-decoration: none;\n\t\t\t\t}\n\n\t\t\t\t.ASP-Tab-Menu-Alt\n\t\t\t\t{\n\t\t\t\t\tbackground: none;\n\t\t\t\t}\n\n\t\t\t</style>\n\n\t\t\t";
        echo "<s";
        echo "cript type=\"text/javascript\">\n\n\t\t\t\tfunction ASPTab(tab)\n\t\t\t\t{\n\t\t\t\t\tif (!tab)\n\t\t\t\t\t{\n\t\t\t\t\t\treturn;\n\t\t\t\t\t}\n\n\t\t\t\t\tvar d = document;\n\t\t\t\t\tvar aElm = d.getElementsByTagName('a');\n\t\t\t\t\tvar trElm = d.getElementsByTagName('tr');\n\n\t\t\t\t\td.getElementById('ASPTabActive').value = tab;\n\n\t\t\t\t\tfor (i = 0; i < aElm.length; i++)\n\t\t\t\t\t{\n\t\t\t\t\t\tif (aElm[i].id && aElm[i].id.match('ASP-Tab-Menu'))\n\t\t\t\t\t\t{\n\t\t\t\t\t\t\taElm[i].";
        echo "className = 'ASP-Tab-Menu-Alt';\n\t\t\t\t\t\t}\n\t\t\t\t\t}\n\n\t\t\t\t\tfor (i = 0; i < trElm.length; i++)\n\t\t\t\t\t{\n\t\t\t\t\t\tif (trElm[i].id && trElm[i].id.match('ASP-Tab-Content'))\n\t\t\t\t\t\t{\n\t\t\t\t\t\t\ttrElm[i].className = 'ASP-Tab-Content-Alt';\n\t\t\t\t\t\t}\n\t\t\t\t\t}\n\n\t\t\t\t\td.getElementById('ASP-Tab-Menu-' + tab).className = 'ASP-Tab-Menu';\n\t\t\t\t\td.getElementById('ASP-Tab-Content-' + tab).className = 'ASP-Tab-Content';\n\t\t\t\t}\n\n\t\t\t</scrip";
        echo "t>\n\n\t\t\t<div id=\"ASP-Tab\">\n\n\t\t\t\t<a id=\"ASP-Tab-Menu-Bookmarking\" class=\"ASP-Tab-Menu\" href=\"javascript:void(0)\" onclick=\"ASPTab('Bookmarking')\">Bookmarking</a>\n\t\t\t\t<a id=\"ASP-Tab-Menu-Conditions\" class=\"ASP-Tab-Menu-Alt\" href=\"javascript:void(0)\" onclick=\"ASPTab('Conditions')\">Conditions</a>\n\t\t\t\t<a id=\"ASP-Tab-Menu-Tags\" class=\"ASP-Tab-Menu-Alt\" href=\"javascript:void(0)\" onclick=\"ASPTab('Tags')\">Tags</a>\n";
        echo "\t\t\t\t<a id=\"ASP-Tab-Menu-PostTags\" class=\"ASP-Tab-Menu-Alt\" href=\"javascript:void(0)\" onclick=\"ASPTab('PostTags')\">Post Tags</a>\n\t\t\t\t<a id=\"ASP-Tab-Menu-PresetTags\" class=\"ASP-Tab-Menu-Alt\" href=\"javascript:void(0)\" onclick=\"ASPTab('PresetTags')\">Preset Tags</a>\n\t\t\t\t<a id=\"ASP-Tab-Menu-Yahoo\" class=\"ASP-Tab-Menu-Alt\" href=\"javascript:void(0)\" onclick=\"ASPTab('Yahoo')\">Yahoo</a>\n\t\t\t\t<a id=\"ASP-Tab-Menu-Rep";
        echo "orts\" class=\"ASP-Tab-Menu-Alt\" href=\"javascript:void(0)\" onclick=\"ASPTab('Reports')\">Reports</a>\n\t\t\t\t<a id=\"ASP-Tab-Menu-Display\" class=\"ASP-Tab-Menu-Alt\" href=\"javascript:void(0)\" onclick=\"ASPTab('Display')\">Display</a>\n\t\t\t\t<a id=\"ASP-Tab-Menu-License\" class=\"ASP-Tab-Menu-Alt\" href=\"javascript:void(0)\" onclick=\"ASPTab('License')\">License</a>\n\n\t\t\t</div>\n\n\n\t\t\t<form id=\"ASPSettings\" method=\"post\" action=\"\"";
        echo ">\n\n\t\t\t\t<table class=\"form-table\">\n\n\t\t\t\t\t<tr id=\"ASP-Tab-Content-Bookmarking\" class=\"ASP-Tab-Content\" valign=\"top\">\n\t\t\t\t\t<th scope=\"row\"><label>";
        _e( "Bookmarking", ASPTextDomain );
        echo "</label></th>\n\t\t\t\t\t\t<td>\n\t\t\t\t\t\t\t<p><input id=\"BookmarkActive\" name=\"BookmarkActive\" type=\"checkbox\" value=\"1\" ";
        if ( $settings['BookmarkActive'] )
        {
            print "checked=\"checked\"";
        }
        echo " /> <label for=\"BookmarkActive\">";
        _e( "Activate.", ASPTextDomain );
        echo "</label></p>\n\t\t\t\t\t\t\t<p><input id=\"BookmarkTestMode\" name=\"BookmarkTestMode\" type=\"checkbox\" value=\"1\" ";
        if ( $settings['BookmarkTestMode'] )
        {
            print "checked=\"checked\"";
        }
        echo " /> <label for=\"BookmarkTestMode\">";
        _e( "Test mode. Plugin will simulate the bookmarking process without actually posting to websites.", ASPTextDomain );
        echo "</label></p>\n\t\t\t\t\t\t\t<p><input id=\"AccountLimit\" name=\"AccountLimit\" type=\"text\" value=\"";
        print $settings['AccountLimit'];
        echo "\" size=\"2\" /> <label for=\"AccountLimit\">";
        _e( "Randomly select website accounts for bookmarking. (0 = No limit)", ASPTextDomain );
        echo "</label></p>\n\t\t\t\t\t\t\t<p><input id=\"BookmarkScuttleLinks\" name=\"BookmarkScuttleLinks\" type=\"checkbox\" value=\"1\" ";
        if ( $settings['BookmarkScuttleLinks'] )
        {
            print "checked=\"checked\"";
        }
        echo " /> <label for=\"BookmarkScuttleLinks\">";
        _e( "scuttlePLUS: Allow hyperlinks (text links) in description.", ASPTextDomain );
        echo "</label>\n\t\t\t\t\t\t\t<br /><input id=\"BookmarkScuttleCharLimit\" name=\"BookmarkScuttleCharLimit\" type=\"text\" value=\"";
        print $settings['BookmarkScuttleCharLimit'];
        echo "\" size=\"2\" /> <label for=\"BookmarkScuttleCharLimit\">";
        _e( "scuttlePLUS: Character length of description text.", ASPTextDomain );
        echo "</label></p>\n\t\t\t\t\t\t</td>\n\t\t\t\t\t</tr>\n\n\t\t\t\t\t<tr id=\"ASP-Tab-Content-Conditions\" class=\"ASP-Tab-Content-Alt\" valign=\"top\">\n\t\t\t\t\t<th scope=\"row\"><label>";
        _e( "Bookmarking Conditions<br />(No selection = No restriction)", ASPTextDomain );
        echo "</label></th>\n\t\t\t\t\t\t<td>\n\t\t\t\t\t\t\t<p>";
        _e( "Restrict bookmarking to the selected Tags.", ASPTextDomain );
        echo "</p>\n\t\t\t\t\t\t\t<p>\n\t\t\t\t\t\t\t";
        $ASPPostTags = get_tags( array( "hide_empty" => false ) );
        foreach ( $ASPPostTags as $tag )
        {
            echo "\t\t\t\t\t\t\t\t<input id=\"ConditionsTags-";
            print $tag->term_id;
            echo "\" name=\"ConditionsTags[]\" type=\"checkbox\" value=\"";
            print $tag->name;
            echo "\" ";
            if ( in_array( $tag->name, $settings['ConditionsTags'] ) )
            {
                print "checked=\"checked\"";
            }
            echo " /> <label for=\"ConditionsTags-";
            print $tag->term_id;
            echo "\">";
            _e( $tag->name, ASPTextDomain );
            echo "</label> &nbsp;\n\t\t\t\t\t\t\t";
        }
        echo "\t\t\t\t\t\t\t</p>\n\t\t\t\t\t\t\t<p>OR</p>\n\t\t\t\t\t\t\t<p>";
        _e( "Restrict bookmarking to the selected Categories.", ASPTextDomain );
        echo "</p>\n\t\t\t\t\t\t\t<p>\n\t\t\t\t\t\t\t";
        $ASPPostCategories = get_categories( array( "hide_empty" => false ) );
        foreach ( $ASPPostCategories as $category )
        {
            echo "\t\t\t\t\t\t\t\t";
            echo "<s";
            echo "pan style=\"white-space: nowrap;\"><input id=\"ConditionsCategories-";
            print $category->term_id;
            echo "\" name=\"ConditionsCategories[]\" type=\"checkbox\" value=\"";
            print $category->term_id;
            echo "\" ";
            if ( in_array( $category->term_id, $settings['ConditionsCategories'] ) )
            {
                print "checked=\"checked\"";
            }
            echo " /> <label for=\"ConditionsCategories-";
            print $category->term_id;
            echo "\">";
            _e( $category->name, ASPTextDomain );
            echo "</label> &nbsp;</span>\n\t\t\t\t\t\t\t";
        }
        echo "\t\t\t\t\t\t\t</p>\n\t\t\t\t\t\t</td>\n\t\t\t\t\t</tr>\n\n\t\t\t\t\t<tr id=\"ASP-Tab-Content-Tags\" class=\"ASP-Tab-Content-Alt\" valign=\"top\">\n\t\t\t\t\t<th scope=\"row\"><label>";
        _e( "Tags", ASPTextDomain );
        echo "</label></th>\n\t\t\t\t\t\t<td>\n\t\t\t\t\t\t\t<p>";
        _e( "Select which Tags to use for bookmarking.", ASPTextDomain );
        echo "</p>\n\t\t\t\t\t\t\t<p><input id=\"TagsPost\" name=\"Tags\" type=\"radio\" value=\"Post\" ";
        if ( $settings['Tags'] == "Post" )
        {
            print "checked=\"checked\"";
        }
        echo " /> <label for=\"TagsPost\">";
        _e( "Post Tags", ASPTextDomain );
        echo "</label>\n\t\t\t\t\t\t\t<br /><input id=\"TagsPreset\" name=\"Tags\" type=\"radio\" value=\"Preset\" ";
        if ( $settings['Tags'] == "Preset" )
        {
            print "checked=\"checked\"";
        }
        echo " /> <label for=\"TagsPreset\">";
        _e( "Preset Tags", ASPTextDomain );
        echo "</label>\n\t\t\t\t\t\t\t<br /><input id=\"TagsYahoo\" name=\"Tags\" type=\"radio\" value=\"Yahoo\" ";
        if ( $settings['Tags'] == "Yahoo" )
        {
            print "checked=\"checked\"";
        }
        echo " /> <label for=\"TagsYahoo\">";
        _e( "Yahoo Term Extraction API", ASPTextDomain );
        echo "</label></p>\n\t\t\t\t\t\t\t<p><input id=\"TagsFallback\" name=\"TagsFallback\" type=\"checkbox\" value=\"1\" ";
        if ( $settings['TagsFallback'] )
        {
            print "checked=\"checked\"";
        }
        echo " /> <label for=\"TagsFallback\">";
        _e( "Use Preset Tags if no other Tags are found.", ASPTextDomain );
        echo "</label></p>\n\t\t\t\t\t\t\t<p><input id=\"TagsLimit\" name=\"TagsLimit\" type=\"text\" value=\"";
        print $settings['TagsLimit'];
        echo "\" size=\"2\" /> <label for=\"TagsLimit\">";
        _e( "Randomly select Tags for bookmarking. (0 = No limit)", ASPTextDomain );
        echo "</label></p>\n\t\t\t\t\t\t</td>\n\t\t\t\t\t</tr>\n\n\t\t\t\t\t<tr id=\"ASP-Tab-Content-PostTags\" class=\"ASP-Tab-Content-Alt\" valign=\"top\">\n\t\t\t\t\t<th scope=\"row\"><label>";
        _e( "Post Tags", ASPTextDomain );
        echo "</label></th>\n\t\t\t\t\t\t<td>\n\t\t\t\t\t\t\t<p>";
        _e( "Select which Post Tags to use for bookmarking.", ASPTextDomain );
        echo "</p>\n\t\t\t\t\t\t\t<p><input id=\"PostTagsWordPress\" name=\"PostTags\" type=\"radio\" value=\"WordPress\" ";
        if ( $settings['PostTags'] == "WordPress" )
        {
            print "checked=\"checked\"";
        }
        echo " /> <label for=\"PostTagsWordPress\">";
        _e( "WordPress Tags", ASPTextDomain );
        echo "</label>\n\t\t\t\t\t\t\t<br /><input id=\"PostTagsManual\" name=\"PostTags\" type=\"radio\" value=\"Manual\" ";
        if ( $settings['PostTags'] == "Manual" )
        {
            print "checked=\"checked\"";
        }
        echo " /> <label for=\"PostTagsManual\">";
        _e( "Manual Tags", ASPTextDomain );
        echo "</label></p>\n\t\t\t\t\t\t\t<p><input id=\"PostTagsManualStarter\" name=\"PostTagsManualStarter\" type=\"text\" value=\"";
        print $settings['PostTagsManualStarter'];
        echo "\" size=\"5\" /> <label for=\"PostTagsManualStarter\">";
        _e( "Manual Tag Starter", ASPTextDomain );
        echo "</label>\n\t\t\t\t\t\t\t<br /><input id=\"PostTagsManualSeparator\" name=\"PostTagsManualSeparator\" type=\"text\" value=\"";
        print $settings['PostTagsManualSeparator'];
        echo "\" size=\"5\" /> <label for=\"PostTagsManualSeparator\">";
        _e( "Manual Tag Separator", ASPTextDomain );
        echo "</label>\n\t\t\t\t\t\t\t<br />";
        _e( "(e.g. Tags: tag1, tag2, tag3)", ASPTextDomain );
        echo "</p>\n\t\t\t\t\t\t\t<p><input id=\"PostTagsManualHide\" name=\"PostTagsManualHide\" type=\"checkbox\" value=\"1\" ";
        if ( $settings['PostTagsManualHide'] )
        {
            print "checked=\"checked\"";
        }
        echo " /> <label for=\"PostTagsManualHide\">";
        _e( "Hide Manual Tags in Post.", ASPTextDomain );
        echo "</label></p>\n\t\t\t\t\t\t</td>\n\t\t\t\t\t</tr>\n\n\t\t\t\t\t<tr id=\"ASP-Tab-Content-PresetTags\" class=\"ASP-Tab-Content-Alt\" valign=\"top\">\n\t\t\t\t\t<th scope=\"row\"><label for=\"PresetTags\">";
        _e( "Preset Tags", ASPTextDomain );
        echo "</label></th>\n\t\t\t\t\t\t<td>\n\t\t\t\t\t\t\t<p><textarea id=\"PresetTags\" name=\"PresetTags\" cols=\"60\" rows=\"4\" style=\"width: 98%;\">";
        print $settings['PresetTags'];
        echo "</textarea>\n\t\t\t\t\t\t\t";
        _e( "Enter comma separated tags. (e.g. tag1, tag2, tag3)", ASPTextDomain );
        echo "</p>\n\t\t\t\t\t\t</td>\n\t\t\t\t\t</tr>\n\n\t\t\t\t\t<tr id=\"ASP-Tab-Content-Yahoo\" class=\"ASP-Tab-Content-Alt\" valign=\"top\">\n\t\t\t\t\t<th scope=\"row\"><label>";
        _e( "Yahoo API", ASPTextDomain );
        echo "</label></th>\n\t\t\t\t\t\t<td>\n\t\t\t\t\t\t\t<p><input id=\"YahooKey\" name=\"YahooKey\" type=\"password\" value=\"";
        print $settings['YahooKey'];
        echo "\" size=\"75\" autocomplete=\"off\" />\n\t\t\t\t\t\t\t<br />Enter your <a target=\"_blank\" href=\"http://developer.yahoo.com/wsregapp/\">Yahoo Application ID</a>.</p>\n\t\t\t\t\t\t\t<p><input id=\"YahooTagsLimit\" name=\"YahooTagsLimit\" type=\"text\" value=\"";
        print $settings['YahooTagsLimit'];
        echo "\" size=\"2\" /> <label for=\"YahooTagsLimit\">";
        _e( "Maximum number of Tags to use from API. (0 = No limit)", ASPTextDomain );
        echo "</label></p>\n\t\t\t\t\t\t</td>\n\t\t\t\t\t</tr>\n\n\t\t\t\t\t<tr id=\"ASP-Tab-Content-Reports\" class=\"ASP-Tab-Content-Alt\" valign=\"top\">\n\t\t\t\t\t<th scope=\"row\"><label>";
        _e( "Reports", ASPTextDomain );
        echo "</label></th>\n\t\t\t\t\t\t<td>\n\t\t\t\t\t\t\t<p>";
        _e( "Bookmarking reports.", ASPTextDomain );
        echo "</p>\n\t\t\t\t\t\t\t<p><input id=\"ReportsLog\" name=\"ReportsLog\" type=\"checkbox\" value=\"1\" ";
        if ( $settings['ReportsLog'] )
        {
            print "checked=\"checked\"";
        }
        echo " /> <label for=\"ReportsLog\">";
        _e( "Generate log file.", ASPTextDomain );
        echo "</label>\n\t\t\t\t\t\t\t<br /><input id=\"ReportsEmail\" name=\"ReportsEmail\" type=\"checkbox\" value=\"1\" ";
        if ( $settings['ReportsEmail'] )
        {
            print "checked=\"checked\"";
        }
        echo " /> <label for=\"ReportsEmail\">";
        _e( "Send by Email to Administrator.", ASPTextDomain );
        echo "</label></p>\n\t\t\t\t\t\t\t<p>";
        _e( "Debugging reports.", ASPTextDomain );
        echo "</p>\n\t\t\t\t\t\t\t<p><input id=\"ReportsCURL\" name=\"ReportsCURL\" type=\"checkbox\" value=\"1\" ";
        if ( $settings['ReportsCURL'] )
        {
            print "checked=\"checked\"";
        }
        echo " /> <label for=\"ReportsCURL\">";
        _e( "Generate cURL log file.", ASPTextDomain );
        echo "</label>\n\t\t\t\t\t\t\t<br /><input id=\"ReportsPageContent\" name=\"ReportsPageContent\" type=\"checkbox\" value=\"1\" ";
        if ( $settings['ReportsPageContent'] )
        {
            print "checked=\"checked\"";
        }
        echo " /> <label for=\"ReportsPageContent\">";
        _e( "Show actual page content in reports for bookmarking errors.", ASPTextDomain );
        echo "</label></p>\n\t\t\t\t\t\t</td>\n\t\t\t\t\t</tr>\n\n\t\t\t\t\t<tr id=\"ASP-Tab-Content-Display\" class=\"ASP-Tab-Content-Alt\" valign=\"top\">\n\t\t\t\t\t<th scope=\"row\"><label>";
        _e( "Display", ASPTextDomain );
        echo "</label></th>\n\t\t\t\t\t\t<td>\n\t\t\t\t\t\t\t<p><input id=\"DisplayManagePostColumn\" name=\"DisplayManagePostColumn\" type=\"checkbox\" value=\"1\" ";
        if ( $settings['DisplayManagePostColumn'] )
        {
            print "checked=\"checked\"";
        }
        echo " /> <label for=\"DisplayManagePostColumn\">";
        _e( "Show additional column on Manage Post page.", ASPTextDomain );
        echo "</label></p>\n\t\t\t\t\t\t</td>\n\t\t\t\t\t</tr>\n\n\t\t\t\t\t<tr id=\"ASP-Tab-Content-License\" class=\"ASP-Tab-Content-Alt\" valign=\"top\">\n\t\t\t\t\t<th scope=\"row\"><label for=\"LicenseKey\">";
        _e( "License Key", ASPTextDomain );
        echo "</label> <br /> <a target='_blank' href=\"http://lma.mass-automation.appspot.com/recover\">";
        _e( "Retrieve", ASPTextDomain );
        echo "</a></th>\n\t\t\t\t\t\t<td>\n\t\t\t\t\t\t\t<p><input id=\"LicenseKey\" name=\"LicenseKey\" type=\"password\" value=\"";
        print $settings['LicenseKey'];
        echo "\" size=\"50\" autocomplete=\"off\" />\n\t\t\t\t\t\t\t<br /><a href=\"javascript:void(0)\" onclick=\"ASPLicenseCheck()\">";
        _e( "Check Status", ASPTextDomain );
        echo "</a>: ";
        echo "<s";
        echo "pan id=\"LicenseKeyStatus\"></span></p>\n\t\t\t\t\t\t</td>\n\t\t\t\t\t</tr>\n\n\t\t\t\t</table>\n\n\t\t\t\t<input id=\"ASPTabActive\" name=\"ASPTabActive\" type=\"hidden\" value=\"\" />\n\t\t\t\t<input name=\"action\" type=\"hidden\" value=\"ASPSaveSettings\" />\n\n\t\t\t\t<p class=\"submit\"><input class=\"button-primary\" name=\"submit\" type=\"submit\" value=\"";
        _e( "Save Settings", ASPTextDomain );
        echo "\" /></p>\n\n\t\t\t</form>\n\n\t\t\t<hr class=\"ASPSpacer\" />\n\t\t\t<p></p>\n\n\t\t\t<form method=\"post\" action=\"\">\n\n\t\t\t\t<h3>";
        _e( "Bookmarking Accounts", ASPTextDomain );
        echo "</h3>\n\n\t\t\t\t<p align=\"right\" style=\"margin-top: -35px;\">";
        echo "<s";
        echo "trong><a href=\"javascript:void(0)\" onclick=\"ASPTableObj.AddRow(1)\">";
        _e( "Add Account", ASPTextDomain );
        echo "</a></strong></p>\n\n\t\t\t\t<table id=\"ASPAccounts\" class=\"widefat\">\n\n\t\t\t\t\t<thead>\n\t\t\t\t\t<tr>\n\t\t\t\t\t<th scope=\"col\">";
        _e( "Enable", ASPTextDomain );
        echo "</th>\n\t\t\t\t\t<th scope=\"col\">";
        _e( "Website", ASPTextDomain );
        echo "</th>\n\t\t\t\t\t<th scope=\"col\">";
        _e( "Parameter 1", ASPTextDomain );
        echo "</th>\n\t\t\t\t\t<th scope=\"col\">";
        _e( "Parameter 2", ASPTextDomain );
        echo "</th>\n\t\t\t\t\t<th scope=\"col\">";
        _e( "Username", ASPTextDomain );
        echo "</th>\n\t\t\t\t\t<th scope=\"col\">";
        _e( "Password", ASPTextDomain );
        echo "</th>\n\t\t\t\t\t<th scope=\"col\">";
        _e( "Action", ASPTextDomain );
        echo "</th>\n\t\t\t\t\t</tr>\n\t\t\t\t\t</thead>\n\n\t\t\t\t\t<tbody id=\"ASPAccountsBody\">\n\n\t\t\t\t\t</tbody>\n\n\t\t\t\t</table>\n\n\t\t\t\t<p>\n\t\t\t\t\t";
        echo "<s";
        echo "pan  style=\"float: left;\">&nbsp; <input id=\"ASPToggle\" name=\"ASPToggle\" type=\"checkbox\" value=\"1\" onclick=\"ASPAccountsToggle(this)\" /> <label for=\"ASPToggle\">";
        _e( "Toggle All", ASPTextDomain );
        echo "</label></span>\n\t\t\t\t\t<input style=\"float: right; margin-top: 0px; color: red;\" class=\"button-secondary\" name=\"submit\" type=\"submit\" value=\"";
        _e( "Delete All Accounts", ASPTextDomain );
        echo "\" onclick=\"return ASPAccountsDeleteAll(this);\" />\n\t\t\t\t\t<input style=\"float: right; margin-top: 0px; margin-right: 20px;\" class=\"button-secondary\" name=\"submit\" type=\"submit\" value=\"";
        _e( "Export Accounts", ASPTextDomain );
        echo "\" onclick=\"this.form.action.value = 'ASPExportAccounts';\" />\n\t\t\t\t</p>\n\n\t\t\t\t<div style=\"clear: both;\"></div>\n\n\t\t\t\t<input name=\"action\" type=\"hidden\" value=\"ASPSaveAccounts\" />\n\n\t\t\t\t<p class=\"submit\"><input class=\"button-primary\" name=\"submit\" type=\"submit\" value=\"";
        _e( "Save Accounts", ASPTextDomain );
        echo "\" /></p>\n\n\t\t\t</form>\n\n\t\t\t<hr class=\"ASPSpacer\" />\n\t\t\t<p></p>\n\n\t\t\t<form enctype=\"multipart/form-data\" method=\"post\" action=\"\">\n\n\t\t\t\t<h3>";
        _e( "Import Bookmarking Accounts", ASPTextDomain );
        echo "</h3>\n\n\t\t\t\t<table class=\"form-table\">\n\n\t\t\t\t\t<tr valign=\"top\">\n\t\t\t\t\t<th scope=\"row\"><label for=\"CSVFile\">";
        _e( "CSV File", ASPTextDomain );
        echo "</label></th>\n\t\t\t\t\t\t<td>\n\t\t\t\t\t\t\t<p><input id=\"CSVFile\" name=\"CSVFile\" type=\"file\" size=\"50\" />\n\t\t\t\t\t\t\t<br />";
        echo "<s";
        echo "trong>";
        _e( "Format:", ASPTextDomain );
        echo "</strong> ";
        _e( "Enable, Website Name, Parameter 1, Parameter 2, Username, Password", ASPTextDomain );
        echo "\t\t\t\t\t\t\t<br />";
        _e( "Enable value can be 1 or 0 (1 = Enable, 0 = Disable).", ASPTextDomain );
        echo "</p>\n\t\t\t\t\t\t</td>\n\t\t\t\t\t</tr>\n\n\t\t\t\t</table>\n\n\t\t\t\t<input name=\"action\" type=\"hidden\" value=\"ASPImportAccounts\" />\n\n\t\t\t\t<p class=\"submit\"><input class=\"button-primary\" name=\"submit\" type=\"submit\" value=\"";
        _e( "Import Accounts", ASPTextDomain );
        echo "\" /></p>\n\n\t\t\t</form>\n\n\t\t\t<hr class=\"ASPSpacer\" />\n\t\t\t<p></p>\n\n\t\t\t<form method=\"post\" action=\"\">\n\n\t\t\t\t<h3>";
        _e( "Bookmarking Report", ASPTextDomain );
        echo "</h3>\n\n\t\t\t\t<p align=\"right\" style=\"margin-top: -35px;\">";
        echo "<s";
        echo "trong><a href=\"javascript:void(0)\" onclick=\"document.location.reload(true);\">";
        _e( "Refresh", ASPTextDomain );
        echo "</a></strong></p>\n\n\t\t\t\t<table class=\"form-table\">\n\n\t\t\t\t\t<tr valign=\"top\">\n\t\t\t\t\t\t<td>\n\t\t\t\t\t\t\t<p><textarea name=\"\" cols=\"60\" rows=\"14\" style=\"width: 99%;\" wrap=\"off\" readonly>";
        print $BookmarkingLog;
        echo "</textarea></p>\n\t\t\t\t\t\t\t";
        echo "<s";
        echo "pan class=\"description\">Showing last 100 Kb from report file.</span>\n\t\t\t\t\t\t</td>\n\t\t\t\t\t</tr>\n\n\t\t\t\t</table>\n\n\t\t\t\t<input name=\"action\" type=\"hidden\" value=\"ASPDeleteReport\" />\n\n\t\t\t\t<p class=\"submit\"><input style=\"color: red;\" class=\"button-primary\" name=\"submit\" type=\"submit\" value=\"";
        _e( "Delete Report", ASPTextDomain );
        echo "\" onclick=\"return ASPDeleteReport();\" /></p>\n\n\t\t\t</form>\n\n\t\t</div>\n\n\n\t\t";
        echo "<s";
        echo "cript type=\"text/javascript\">\n\n\t\t";
        if ( isset( $_POST['ASPTabActive'] ) )
        {
            print "ASPTab('{$_POST['ASPTabActive']}');";
        }
        echo "\n\n\t\tvar ASPSack = new sack(\"";
        bloginfo( "wpurl" );
        echo "/wp-admin/admin-ajax.php\");\n\t\tvar ASPSettingsForm = document.getElementById('ASPSettings');\n\t\tvar ASPSettingsElmStatus = document.getElementById('LicenseKeyStatus');\n\n\t\tfunction ASPLicenseCheckHelper()\n\t\t{\n\t\t\tif (ASPSack.responseStatus && ASPSack.responseStatus[0] == 200)\n\t\t\t{\n\t\t\t\tASPSettingsElmStatus.innerHTML = ASPSack.response ? ASPSack.response : '";
        _e( "Your license key is valid.", ASPTextDomain );
        echo "';\n\t\t\t}\n\t\t\telse\n\t\t\t{\n\t\t\t\tASPSettingsElmStatus.innerHTML = ASPSack.responseStatus[1];\n\t\t\t}\n\t\t}\n\n\t\tfunction ASPLicenseCheck()\n\t\t{\n\t\t\tif (ASPSettingsForm.LicenseKey.value)\n\t\t\t{\n\t\t\t\tASPSettingsElmStatus.innerHTML = '";
        _e( "Checking...", ASPTextDomain );
        echo "';\n\n\t\t\t\tASPSack.setVar('license_key', ASPSettingsForm.LicenseKey.value);\n\t\t\t\tASPSack.requestFile = '";
        print $ASPLicenseFile;
        echo "';\n\t\t\t\tASPSack.method = 'POST';\n\t\t\t\tASPSack.onError = function() { ASPSettingsElmStatus.innerHTML = '";
        _e( "AJAX Error!", ASPTextDomain );
        echo "'; };\n\t\t\t\tASPSack.onCompletion = ASPLicenseCheckHelper;\n\t\t\t\tASPSack.runAJAX();\n\t\t\t}\n\t\t\telse\n\t\t\t{\n\t\t\t\tASPSettingsElmStatus.innerHTML = '";
        _e( "Please enter your license key.", ASPTextDomain );
        echo "';\n\t\t\t}\n\n\t\t\treturn false;\n\t\t}\n\n\n\n\t\tfunction ASPTable()\n\t\t{\n\t\t\tvar d = document;\n\t\t\tvar $ = function (elm) { return d.getElementById(elm); };\n\n\t\t\tvar Table = $('ASPAccounts');\n\t\t\tvar TableBody = $('ASPAccountsBody');\n\t\t\tvar Services = ";
        print phptojsarray( array_combine( array_keys( $ASPServices ), array_keys( $ASPServices ) ) );
        echo "\n\n\t\t\tthis.AddRow = AddRow;\n\t\t\tthis.RemoveRow = RemoveRow;\n\t\t\tthis.AddSelectOptions = AddSelectOptions;\n\t\t\tthis.SelectOnChange = SelectOnChange;\n\n\n\t\t\tfunction AddRow(enabled, service, extra, extra1, username, password)\n\t\t\t{\n\t\t\t\tvar enabled = (typeof enabled == 'undefined') ? '' : enabled;\n\t\t\t\tvar service = (typeof service == 'undefined') ? '' : service;\n\t\t\t\tvar extra = (typeof extra == 'undefined')";
        echo " ? '' : extra;\n\t\t\t\tvar extra1 = (typeof extra1 == 'undefined') ? '' : extra1;\n\t\t\t\tvar username = (typeof username == 'undefined') ? '' : username;\n\t\t\t\tvar password = (typeof password == 'undefined') ? '' : password;\n\n\t\t\t\tvar Index = Table.rows.length;\n\n\n//\t\t\t\tvar Row = Table.insertRow(-1);\n\t\t\t\tvar Row = TableBody.insertRow(-1);\n\n\t\t\t\tRow.id = 'ASPRow' + Index;\n\n\t\t\t\tRow.insertCell(-1).innerHTML = '<i";
        echo "nput name=\"enabled[' + Index + ']\" type=\"checkbox\" value=\"1\"' + (enabled ? \"checked='checked'\" : \"\") + ' />';\n\t\t\t\tRow.insertCell(-1).innerHTML = '";
        echo "<s";
        echo "elect id=\"ASPSelect' + Index + '\" name=\"service[' + Index + ']\" onchange=\"ASPTableObj.SelectOnChange(this, ' + Index + ')\"></select>';\n\t\t\t\tRow.insertCell(-1).innerHTML = '<div id=\"extra[' + Index + ']\"></div>';\n\t\t\t\tRow.insertCell(-1).innerHTML = '<div id=\"extra1[' + Index + ']\"></div>';\n\t\t\t\tRow.insertCell(-1).innerHTML = '<input class=\"ASPAccountsText\" name=\"username[' + Index + ']\" type=\"text\" value=\"'";
        echo " + username + '\" />';\n\t\t\t\tRow.insertCell(-1).innerHTML = '<input class=\"ASPAccountsText\" name=\"password[' + Index + ']\" type=\"text\" value=\"' + password + '\" />';\n\t\t\t\tRow.insertCell(-1).innerHTML = '<a style=\"color: red;\" href=\"javascript:void(0)\" onclick=\"ASPTableObj.RemoveRow(\\'' + Row.id + '\\')\">";
        _e( "Delete", ASPTextDomain );
        echo "</a>';\n\n\n\t\t\t\tAddSelectOptions('ASPSelect' + Index, Services, service);\n\t\t\t\tSelectOnChange($('ASPSelect' + Index), Index, extra, extra1);\n\t\t\t}\n\n\t\t\tfunction RemoveRow(RowID)\n\t\t\t{\n\t\t\t\tTable.deleteRow($(RowID).rowIndex);\n\t\t\t}\n\n\t\t\tfunction AddSelectOptions(SelectID, SelectOptions, SelectedOption)\n\t\t\t{\n\t\t\t\tvar SelectElm = $(SelectID);\n\n\t\t\t\tfor (key in SelectOptions)\n\t\t\t\t{\n\t\t\t\t\tSelectElm.options[SelectElm";
        echo ".length] = new Option(SelectOptions[key], key);\n\t\t\t\t}\n\n\t\t\t\tfor (var i = 0; i < SelectElm.options.length; i++)\n\t\t\t\t{\n\t\t\t\t\tif (SelectElm.options[i].value == SelectedOption)\n\t\t\t\t\t{\n\t\t\t\t\t\tSelectElm.options[i].selected = true;\n\t\t\t\t\t}\n\t\t\t\t}\n\t\t\t}\n\n\t\t\tfunction SelectOnChange(SelectElm, Index, extra, extra1)\n\t\t\t{\n\t\t\t\tvar extra = (typeof extra == 'undefined') ? '' : extra;\n\t\t\t\tvar extra1 = (typeof extra1 == ";
        echo "'undefined') ? '' : extra1;\n\n\t\t\t\tvar extraElm = $('extra[' + Index + ']');\n\t\t\t\tvar extra1Elm = $('extra1[' + Index + ']');\n\n\t\t\t\textraElm.innerHTML = '';\n\t\t\t\textra1Elm.innerHTML = '';\n\n\t\t\t\tvar dewfun = ";
        print phptojsarray( $dewfun );
        echo "\t\t\t\tvar dzone = ";
        print phptojsarray( $dzone );
        echo "\t\t\t\tvar kaboomit = ";
        print phptojsarray( $kaboomit );
        echo "\t\t\t\tvar misterWong = ";
        print phptojsarray( $misterWong );
        echo "\t\t\t\tvar newsvine = ";
        print phptojsarray( $newsvine );
        echo "\t\t\t\tvar propeller = ";
        print phptojsarray( $propeller );
        echo "\t\t\t\tvar shoutwire = ";
        print phptojsarray( $shoutwire );
        echo "\t\t\t\tvar sphinn = ";
        print phptojsarray( $sphinn );
        echo "\n\t\t\t\tvar service = SelectElm.options[SelectElm.selectedIndex].value;\n\n\n\t\t\t\tswitch (service)\n\t\t\t\t{\n\t\t\t\t\tcase 'buddymarks' :\n\t\t\t\t\t\textra = (extra == '') ? '";
        _e( "Category", ASPTextDomain );
        echo "' : extra;\n\t\t\t\t\t\textraElm.innerHTML = '<input class=\"ASPAccountsText\" name=\"extra[' + Index + ']\" type=\"text\" value=\"' + extra + '\" />';\n\t\t\t\t\tbreak;\n\n\t\t\t\t\tcase 'dewfun' :\n\t\t\t\t\t\textraElm.innerHTML = '";
        echo "<s";
        echo "elect id=\"ASPExtra' + Index + '\" name=\"extra[' + Index + ']\"></select>';\n\t\t\t\t\t\tAddSelectOptions('ASPExtra' + Index, dewfun, extra);\n\t\t\t\t\tbreak;\n\n\t\t\t\t\tcase 'dzone' :\n\t\t\t\t\t\textraElm.innerHTML = '";
        echo "<s";
        echo "elect id=\"ASPExtra' + Index + '\" name=\"extra[' + Index + ']\"></select>';\n\t\t\t\t\t\tAddSelectOptions('ASPExtra' + Index, dzone, extra);\n\t\t\t\t\tbreak;\n\n\t\t\t\t\tcase 'kaboomit' :\n\t\t\t\t\t\textraElm.innerHTML = '";
        echo "<s";
        echo "elect id=\"ASPExtra' + Index + '\" name=\"extra[' + Index + ']\"></select>';\n\t\t\t\t\t\tAddSelectOptions('ASPExtra' + Index, kaboomit, extra);\n\t\t\t\t\tbreak;\n\n\t\t\t\t\tcase 'misterWong' :\n\t\t\t\t\t\textraElm.innerHTML = '";
        echo "<s";
        echo "elect id=\"ASPExtra' + Index + '\" name=\"extra[' + Index + ']\"></select>';\n\t\t\t\t\t\tAddSelectOptions('ASPExtra' + Index, misterWong, extra);\n\t\t\t\t\tbreak;\n\n\t\t\t\t\tcase 'newsvine' :\n\t\t\t\t\t\textraElm.innerHTML = '";
        echo "<s";
        echo "elect id=\"ASPExtra' + Index + '\" name=\"extra[' + Index + ']\"></select>';\n\t\t\t\t\t\tAddSelectOptions('ASPExtra' + Index, newsvine, extra);\n\t\t\t\t\tbreak;\n\n\t\t\t\t\tcase 'pligg' :\n\t\t\t\t\t\textra = (extra == '') ? '";
        _e( "Website URL", ASPTextDomain );
        echo "' : extra;\n\t\t\t\t\t\textra1 = (extra1 == '') ? '";
        _e( "Category", ASPTextDomain );
        echo "' : extra1;\n\t\t\t\t\t\textraElm.innerHTML = '<input class=\"ASPAccountsText\" name=\"extra[' + Index + ']\" type=\"text\" value=\"' + extra + '\" />';\n\t\t\t\t\t\textra1Elm.innerHTML = '<input class=\"ASPAccountsText\" name=\"extra1[' + Index + ']\" type=\"text\" value=\"' + extra1 + '\" />';\n\t\t\t\t\tbreak;\n\n\t\t\t\t\tcase 'propeller' :\n\t\t\t\t\t\textraElm.innerHTML = '";
        echo "<s";
        echo "elect id=\"ASPExtra' + Index + '\" name=\"extra[' + Index + ']\"></select>';\n\t\t\t\t\t\tAddSelectOptions('ASPExtra' + Index, propeller, extra);\n\t\t\t\t\tbreak;\n\n\t\t\t\t\tcase 'scuttle' :\n\t\t\t\t\t\textra = (extra == '') ? '";
        _e( "Website URL", ASPTextDomain );
        echo "' : extra;\n\t\t\t\t\t\textra1 = (extra1 == '') ? '";
        _e( "Tag Separator", ASPTextDomain );
        echo "' : extra1;\n\t\t\t\t\t\textraElm.innerHTML = '<input class=\"ASPAccountsText\" name=\"extra[' + Index + ']\" type=\"text\" value=\"' + extra + '\" />';\n\t\t\t\t\t\textra1Elm.innerHTML = '<input class=\"ASPAccountsText\" name=\"extra1[' + Index + ']\" type=\"text\" value=\"' + extra1 + '\" />';\n\t\t\t\t\tbreak;\n\n\t\t\t\t\tcase 'scuttlePLUS' :\n\t\t\t\t\t\textra = (extra == '') ? '";
        _e( "Website URL", ASPTextDomain );
        echo "' : extra;\n\t\t\t\t\t\textraElm.innerHTML = '<input class=\"ASPAccountsText\" name=\"extra[' + Index + ']\" type=\"text\" value=\"' + extra + '\" />';\n\t\t\t\t\tbreak;\n\n\t\t\t\t\tcase 'sphinn' :\n\t\t\t\t\t\textraElm.innerHTML = '";
        echo "<s";
        echo "elect id=\"ASPExtra' + Index + '\" name=\"extra[' + Index + ']\"></select>';\n\t\t\t\t\t\tAddSelectOptions('ASPExtra' + Index, sphinn, extra);\n\t\t\t\t\tbreak;\n\n\t\t\t\t\tcase 'shoutwire' :\n\t\t\t\t\t\textraElm.innerHTML = '";
        echo "<s";
        echo "elect id=\"ASPExtra' + Index + '\" name=\"extra[' + Index + ']\"></select>';\n\t\t\t\t\t\tAddSelectOptions('ASPExtra' + Index, shoutwire, extra);\n\t\t\t\t\tbreak;\n\t\t\t\t}\n\t\t\t}\n\n\t\t}\n\n\t\tvar ASPTableObj = new ASPTable;\n\n\t\t";
        if ( !empty( $settings['Accounts'] ) )
        {
            foreach ( $settings['Accounts'] as $Account )
            {
                if ( !array_key_exists( $Account['service'], $ASPServices ) )
                {
                    continue;
                }
                print "ASPTableObj.AddRow('{$Account['enabled']}', '{$Account['service']}', '{$Account['extra']}', '{$Account['extra1']}', '{$Account['username']}', '{$Account['password']}');\n";
            }
        }
        echo "\n\n\n\t\tfunction ASPAccountsToggle(CheckboxElm)\n\t\t{\n\t\t\tvar Status = CheckboxElm.checked;\n\t\t\tvar Elms = CheckboxElm.form.elements;\n\n\t\t\tfor (var i = 0; i < Elms.length; i++)\n\t\t\t{\n\t\t\t\tif (Elms[i].name.match(/enabled/))\n\t\t\t\t{\n\t\t\t\t\tElms[i].checked = Status;\n\t\t\t\t}\n\t\t\t}\n\t\t}\n\n\n\n\t\tfunction ASPAccountsDeleteAll(elm)\n\t\t{\n\t\t\tif (confirm('Are you sure you want to delete all accounts?'))\n\t\t\t{\n\t\t\t\telm.form.action.va";
        echo "lue = 'ASPDeleteAccounts';\n\n\t\t\t\treturn true;\n\t\t\t}\n\t\t\telse\n\t\t\t{\n\t\t\t\treturn false;\n\t\t\t}\n\t\t}\n\n\n\n\t\tfunction ASPDeleteReport()\n\t\t{\n\t\t\tif (confirm('Are you sure you want to delete bookmarking report?'))\n\t\t\t{\n\t\t\t\treturn true;\n\t\t\t}\n\t\t\telse\n\t\t\t{\n\t\t\t\treturn false;\n\t\t\t}\n\t\t}\n\n\t\t</script>\n\n\t";
    }
}

function aspadminremote( )
{
    if ( $_POST['action'] == "ASPRemoteBookmarking" )
    {
        if ( !empty( $_POST['ASPRemoteURL'] ) && !empty( $_POST['ASPRemoteTitle'] ) )
        {
            $tempArgs = array( "link" => $_POST['ASPRemoteURL'], "title" => $_POST['ASPRemoteTitle'], "extended" => empty( $_POST['ASPRemoteDescription'] ) ? $_POST['ASPRemoteTitle'] : $_POST['ASPRemoteDescription'], "tags" => empty( $_POST['ASPRemoteTags'] ) ? "Uncategorized" : $_POST['ASPRemoteTags'] );
            $args = array( $tempArgs );
            if ( $timestamp = wp_next_scheduled( "ASPRemoteBookmarkHook", $args ) )
            {
                wp_unschedule_event( $timestamp, "ASPRemoteBookmarkHook", $args );
            }
            wp_schedule_single_event( time( ), "ASPRemoteBookmarkHook", $args );
            print "<div class=\"updated\"><p>".__( "Bookmarking process queued for", ASPTextDomain )." \"<strong>{$_POST['ASPRemoteURL']}</strong>\".</p></div>";
            unset( $_POST['ASPRemoteURL'] );
            unset( $_POST['ASPRemoteTitle'] );
            unset( $_POST['ASPRemoteDescription'] );
            unset( $_POST['ASPRemoteTags'] );
        }
        else
        {
            print "<div class=\"error\"><p>".__( "Please enter remote URL and Title.", ASPTextDomain )."</p></div>";
        }
    }
    echo "\n\t\t<div class=\"wrap\">\n\n\t\t\t<h2>";
    print ASPName;
    echo "</h2>\n\n\t\t\t<h3>";
    _e( "Remote Bookmarking", ASPTextDomain );
    echo "</h3>\n\n\t\t\t<p>";
    _e( "You can auto bookmark remote URL by entering the required details in the following form.", ASPTextDomain );
    echo "</p>\n\n\t\t\t<form method=\"post\" action=\"\">\n\n\t\t\t\t<table class=\"form-table\">\n\n\t\t\t\t\t<tr valign=\"top\">\n\t\t\t\t\t<th scope=\"row\"><label for=\"ASPRemoteURL\">";
    _e( "URL", ASPTextDomain );
    echo "</label></th>\n\t\t\t\t\t\t<td>\n\t\t\t\t\t\t\t<input id=\"ASPRemoteURL\" name=\"ASPRemoteURL\" type=\"text\" value=\"";
    if ( !empty( $_POST['ASPRemoteURL'] ) )
    {
        print $_POST['ASPRemoteURL'];
    }
    echo "\" size=\"75\" />\n\t\t\t\t\t\t</td>\n\t\t\t\t\t</tr>\n\n\t\t\t\t\t<tr valign=\"top\">\n\t\t\t\t\t<th scope=\"row\"><label for=\"ASPRemoteTitle\">";
    _e( "Title", ASPTextDomain );
    echo "</label></th>\n\t\t\t\t\t\t<td>\n\t\t\t\t\t\t\t<input id=\"ASPRemoteTitle\" name=\"ASPRemoteTitle\" type=\"text\" value=\"";
    if ( !empty( $_POST['ASPRemoteTitle'] ) )
    {
        print $_POST['ASPRemoteTitle'];
    }
    echo "\" size=\"75\" />\n\t\t\t\t\t\t</td>\n\t\t\t\t\t</tr>\n\n\t\t\t\t\t<tr valign=\"top\">\n\t\t\t\t\t<th scope=\"row\"><label for=\"ASPRemoteDescription\">";
    _e( "Description", ASPTextDomain );
    echo "</label></th>\n\t\t\t\t\t\t<td>\n\t\t\t\t\t\t\t<textarea id=\"ASPRemoteDescription\" name=\"ASPRemoteDescription\" cols=\"60\" rows=\"2\" style=\"width: 98%;\">";
    if ( !empty( $_POST['ASPRemoteDescription'] ) )
    {
        print $_POST['ASPRemoteDescription'];
    }
    echo "</textarea>\n\t\t\t\t\t\t</td>\n\t\t\t\t\t</tr>\n\n\t\t\t\t\t<tr valign=\"top\">\n\t\t\t\t\t<th scope=\"row\"><label for=\"ASPRemoteTags\">";
    _e( "Tags", ASPTextDomain );
    echo "</label></th>\n\t\t\t\t\t\t<td>\n\t\t\t\t\t\t\t<textarea id=\"ASPRemoteTags\" name=\"ASPRemoteTags\" cols=\"60\" rows=\"2\" style=\"width: 98%;\">";
    if ( !empty( $_POST['ASPRemoteTags'] ) )
    {
        print $_POST['ASPRemoteTags'];
    }
    echo "</textarea>\n\t\t\t\t\t\t\t";
    _e( "Enter comma separated tags. (e.g. tag1, tag2, tag3)", ASPTextDomain );
    echo "\t\t\t\t\t\t</td>\n\t\t\t\t\t</tr>\n\n\t\t\t\t</table>\n\n\t\t\t\t<input name=\"action\" type=\"hidden\" value=\"ASPRemoteBookmarking\" />\n\n\t\t\t\t<p class=\"submit\"><input class=\"button-primary\" name=\"submit\" type=\"submit\" value=\"";
    _e( "Submit", ASPTextDomain );
    echo "\" /></p>\n\n\t\t\t</form>\n\n\t\t</div>\n\n\t";
}

function aspadminsitelist( )
{
    global $ASPServices;
    global $ASPServicesStandby;
    global $ASPServicesRemoved;
    echo "\n\t\t<div class=\"wrap\">\n\n\t\t\t";
    echo "<s";
    echo "tyle>\n\t\t\t\tspan.ASPSites\n\t\t\t\t{\n\t\t\t\t\tdisplay: inline-block;\n\t\t\t\t\twidth: 150px;\n\t\t\t\t\tpadding-bottom: 20px;\n\t\t\t\t\tline-height: 1;\n\t\t\t\t}\n\t\t\t\tspan.ASPSites img\n\t\t\t\t{\n\t\t\t\t\tfloat: left;\n\t\t\t\t\tpadding-right: 5px;\n\t\t\t\t}\n\t\t\t\tspan.ASPSites a\n\t\t\t\t{\n\t\t\t\t\ttext-decoration: none;\n\t\t\t\t}\n\t\t\t</style>\n\n\t\t\t<h2>";
    print ASPName;
    echo "</h2>\n\n\t\t\t<h3>";
    _e( "Active Sites", ASPTextDomain );
    echo "</h3>\n\n\t\t\t<p>\n\t\t\t\t";
    foreach ( $ASPServices as $Name => $URL )
    {
        echo "\t\t\t\t\t";
        echo "<s";
        echo "pan class=\"ASPSites\"><img src=\"";
        print ASPURL;
        echo "/images/active.gif\" alt=\"\" /> <a target=\"_blank\" href=\"http://";
        print $URL;
        echo "\">";
        print ucfirst( $Name );
        echo "</a></span>\n\t\t\t\t";
    }
    echo "\t\t\t</p>\n\n\t\t\t<h3>";
    _e( "Standby Sites", ASPTextDomain );
    echo "</h3>\n\n\t\t\t<p>\n\t\t\t\t";
    foreach ( $ASPServicesStandby as $Name => $URL )
    {
        echo "\t\t\t\t\t";
        echo "<s";
        echo "pan class=\"ASPSites\"><img src=\"";
        print ASPURL;
        echo "/images/standby.gif\" alt=\"\" /> <a target=\"_blank\" href=\"http://";
        print $URL;
        echo "\">";
        print ucfirst( $Name );
        echo "</a></span>\n\t\t\t\t";
    }
    echo "\t\t\t</p>\n\n\t\t\t<h3>";
    _e( "Removed Sites", ASPTextDomain );
    echo "</h3>\n\n\t\t\t<p>\n\t\t\t\t";
    foreach ( $ASPServicesRemoved as $Name => $URL )
    {
        echo "\t\t\t\t\t";
        echo "<s";
        echo "pan class=\"ASPSites\"><img src=\"";
        print ASPURL;
        echo "/images/removed.gif\" alt=\"\" /> <a target=\"_blank\" href=\"http://";
        print $URL;
        echo "\">";
        print ucfirst( $Name );
        echo "</a></span>\n\t\t\t\t";
    }
    echo "\t\t\t</p>\n\n\t\t</div>\n\n\t";
}

function aspadminstatistics( )
{
    global $wpdb;
    global $ASPServices;
    $ASPStatsTable = $wpdb->prefix.CYSTATS_TABLE_STATISTICS_RAW;
    $ASPLimit = "LIMIT 10";
    $ASPSites = implode( "|", array_values( $ASPServices ) );
    $ASPSitesDomain = "TRIM(LEADING 'www.' FROM SUBSTRING_INDEX(SUBSTRING_INDEX(referer, '/', 3), '/', -1))";
    $resultsBestWebsite = "";
    $resultsBestPost = "";
    $resultsBestPostLink = "";
    if ( !defined( "CYSTATS_TABLE_STATISTICS_RAW" ) )
    {
        print "<div class=\"error\"><p>".__( "This module requires <strong><a target=\"_blank\" href=\"http://wordpress.org/extend/plugins/cystats/\">CyStat</a></strong> plugin, please Activate/Install it from Plugins menu.", ASPTextDomain )."</p></div>";
    }
    echo "\n\t\t<div class=\"wrap\">\n\n\t\t\t<h2>";
    print ASPName;
    echo "</h2>\n\n\t\t\t";
    echo "<s";
    echo "tyle type=\"text/css\">\n\n\t\t\t.ASPStatsBlock\n\t\t\t{\n\t\t\t\tfloat: left;\n\t\t\t\twidth: 48%;\n\t\t\t\tmargin: 0% 1% 20px 0%;\n\t\t\t}\n\n\t\t\t.ASPStatsTable\n\t\t\t{\n\t\t\t\twidth: 100%;\n\t\t\t}\n\n\t\t\t.ASPStatsTable tbody th\n\t\t\t{\n\t\t\t\tfont-weight: normal;\n\t\t\t}\n\n\t\t\t</style>\n\n\t\t\t<h3>";
    _e( "Statistics", ASPTextDomain );
    echo "</h3>\n\n\t\t\t<p>";
    _e( "These reports are generated by using statistics data from <a target=\"_blank\" href=\"http://wordpress.org/extend/plugins/cystats/\">CyStat</a> plugin.", ASPTextDomain );
    echo "</p>\n\n\n\t\t\t<div class=\"ASPStatsBlock\">\n\n\t\t\t\t<p>";
    echo "<s";
    echo "trong>";
    _e( "Best Bookmarking Websites", ASPTextDomain );
    echo "</strong></p>\n\n\t\t\t\t<table class=\"widefat ASPStatsTable\">\n\n\t\t\t\t\t<thead>\n\t\t\t\t\t<tr>\n\t\t\t\t\t\t<th>";
    _e( "Website", ASPTextDomain );
    echo "</th>\n\t\t\t\t\t\t<th>";
    _e( "Hits", ASPTextDomain );
    echo "</th>\n\t\t\t\t\t</tr>\n\t\t\t\t\t</thead>\n\n\t\t\t\t\t<tbody>\n\n\t\t\t\t\t";
    $results = get_results( "SELECT count(*) AS ASPHits, {$ASPSitesDomain} AS ASPWebsite FROM {$ASPStatsTable} WHERE {$ASPSitesDomain} REGEXP '{$ASPSites}' AND pageid > 0 GROUP BY ASPWebsite ORDER BY ASPHits DESC {$ASPLimit}", ARRAY_A );
    if ( !empty( $results ) )
    {
        $resultsBestWebsite = $results[0]['ASPWebsite'];
        foreach ( $results as $result )
        {
            print "\n\t\t\t\t\t\t\t\t<tr>\n\t\t\t\t\t\t\t\t\t<th><a href='http://{$result['ASPWebsite']}'>{$result['ASPWebsite']}</a></th>\n\t\t\t\t\t\t\t\t\t<th>{$result['ASPHits']}</th>\n\t\t\t\t\t\t\t\t</tr>";
        }
    }
    else
    {
        print "<tr><td colspan=\"2\">No data found.</td></tr>";
    }
    echo "\n\t\t\t\t\t</tbody>\n\n\t\t\t\t</table>\n\n\t\t\t</div>\n\n\n\t\t\t<div class=\"ASPStatsBlock\">\n\n\t\t\t\t<p>";
    echo "<s";
    echo "trong>";
    _e( "Best Bookmarked Posts", ASPTextDomain );
    echo "</strong></p>\n\n\t\t\t\t<table class=\"widefat ASPStatsTable\">\n\n\t\t\t\t\t<thead>\n\t\t\t\t\t<tr>\n\t\t\t\t\t\t<th>";
    _e( "Post", ASPTextDomain );
    echo "</th>\n\t\t\t\t\t\t<th>";
    _e( "Hits", ASPTextDomain );
    echo "</th>\n\t\t\t\t\t</tr>\n\t\t\t\t\t</thead>\n\n\t\t\t\t\t<tbody>\n\n\t\t\t\t\t";
    $results = get_results( "SELECT count(*) AS ASPHits, pageid FROM {$ASPStatsTable} WHERE {$ASPSitesDomain} REGEXP '{$ASPSites}' AND pageid > 0 GROUP BY pageid ORDER BY ASPHits DESC {$ASPLimit}", ARRAY_A );
    if ( !empty( $results ) )
    {
        $resultsBestPost = $results[0]['pageid'];
        foreach ( $results as $result )
        {
            $post = get_post( $result['pageid'] );
            $permalink = get_permalink( $result['pageid'] );
            print "\n\t\t\t\t\t\t\t\t<tr>\n\t\t\t\t\t\t\t\t\t<th><a href='{$permalink}'>{$post->post_title}</a></th>\n\t\t\t\t\t\t\t\t\t<th>{$result['ASPHits']}</th>\n\t\t\t\t\t\t\t\t</tr>";
        }
    }
    else
    {
        print "<tr><td colspan=\"2\">No data found.</td></tr>";
    }
    echo "\n\t\t\t\t\t</tbody>\n\n\t\t\t\t</table>\n\n\t\t\t</div>\n\n\n\t\t\t<div class=\"ASPStatsBlock\">\n\n\t\t\t\t<p>";
    echo "<s";
    echo "trong>";
    _e( "Best Bookmarking Website : ", ASPTextDomain );
    print "<a href='http://{$resultsBestWebsite}'>{$resultsBestWebsite}</a>";
    echo "</strong></p>\n\n\t\t\t\t<table class=\"widefat ASPStatsTable\">\n\n\t\t\t\t\t<thead>\n\t\t\t\t\t<tr>\n\t\t\t\t\t\t<th>";
    _e( "Post", ASPTextDomain );
    echo "</th>\n\t\t\t\t\t\t<th>";
    _e( "Hits", ASPTextDomain );
    echo "</th>\n\t\t\t\t\t</tr>\n\t\t\t\t\t</thead>\n\n\t\t\t\t\t<tbody>\n\n\t\t\t\t\t";
    $results = get_results( "SELECT count(*) AS ASPHits, pageid FROM {$ASPStatsTable} WHERE referer REGEXP '{$resultsBestWebsite}' AND pageid > 0 GROUP BY pageid ORDER BY ASPHits DESC {$ASPLimit}", ARRAY_A );
    if ( !empty( $results ) )
    {
        foreach ( $results as $result )
        {
            $post = get_post( $result['pageid'] );
            $permalink = get_permalink( $result['pageid'] );
            print "\n\t\t\t\t\t\t\t\t<tr>\n\t\t\t\t\t\t\t\t\t<th><a href='{$permalink}'>{$post->post_title}</a></th>\n\t\t\t\t\t\t\t\t\t<th>{$result['ASPHits']}</th>\n\t\t\t\t\t\t\t\t</tr>";
        }
    }
    else
    {
        print "<tr><td colspan=\"2\">No data found.</td></tr>";
    }
    echo "\n\t\t\t\t\t</tbody>\n\n\t\t\t\t</table>\n\n\t\t\t</div>\n\n\n\t\t\t<div class=\"ASPStatsBlock\">\n\n\t\t\t\t";
    if ( !empty( $resultsBestPost ) )
    {
        $resultsBestPostTitle = get_post( $resultsBestPost );
        $resultsBestPostPermalink = get_permalink( $resultsBestPost );
        $resultsBestPostLink = "<a href='{$resultsBestPostPermalink}'>{$resultsBestPostTitle->post_title}</a>";
    }
    echo "\n\t\t\t\t<p>";
    echo "<s";
    echo "trong>";
    _e( "Best Bookmarked Post : ".$resultsBestPostLink, ASPTextDomain );
    echo "</strong></p>\n\n\t\t\t\t<table class=\"widefat ASPStatsTable\">\n\n\t\t\t\t\t<thead>\n\t\t\t\t\t<tr>\n\t\t\t\t\t\t<th>";
    _e( "Website", ASPTextDomain );
    echo "</th>\n\t\t\t\t\t\t<th>";
    _e( "Hits", ASPTextDomain );
    echo "</th>\n\t\t\t\t\t</tr>\n\t\t\t\t\t</thead>\n\n\t\t\t\t\t<tbody>\n\n\t\t\t\t\t";
    $results = get_results( "SELECT count(*) AS ASPHits, {$ASPSitesDomain} AS ASPWebsite FROM {$ASPStatsTable} WHERE pageid = '{$resultsBestPost}' AND {$ASPSitesDomain} REGEXP '{$ASPSites}' GROUP BY ASPWebsite ORDER BY ASPHits DESC {$ASPLimit}", ARRAY_A );
    if ( !empty( $results ) )
    {
        foreach ( $results as $result )
        {
            print "\n\t\t\t\t\t\t\t\t<tr>\n\t\t\t\t\t\t\t\t\t<th><a href='http://{$result['ASPWebsite']}'>{$result['ASPWebsite']}</a></th>\n\t\t\t\t\t\t\t\t\t<th>{$result['ASPHits']}</th>\n\t\t\t\t\t\t\t\t</tr>";
        }
    }
    else
    {
        print "<tr><td colspan=\"2\">No data found.</td></tr>";
    }
    echo "\n\t\t\t\t\t</tbody>\n\n\t\t\t\t</table>\n\n\t\t\t</div>\n\n\n\t\t</div>\n\n\t";
}

function aspadminuninstall( )
{
    if ( $_POST['action'] == "ASPUninstall" )
    {
        if ( delete_option( ASPOptions ) )
        {
            print "<div class=\"updated\"><p><strong>".__( "Plugin settings deleted successfully.", ASPTextDomain )."</strong></p></div>";
        }
        else
        {
            print "<div class=\"error\"><p><strong>".__( "Plugin settings deletion failed.", ASPTextDomain )."</strong></p></div>";
        }
    }
    echo "\n\t\t<div class=\"wrap\">\n\n\t\t\t<h2>";
    print ASPName;
    echo "</h2>\n\n\t\t\t<h3>";
    _e( "Uninstall Settings", ASPTextDomain );
    echo "</h3>\n\n\t\t\t<p>";
    _e( "Please note that all settings of this plugin will be deleted. You can then reactivate the plugin to restore default settings.", ASPTextDomain );
    echo "</p>\n\n\t\t\t<form method=\"post\" action=\"\">\n\n\t\t\t\t<input name=\"action\" type=\"hidden\" value=\"ASPUninstall\" />\n\n\t\t\t\t<p class=\"submit\"><input class=\"button-primary\" name=\"submit\" type=\"submit\" value=\"";
    _e( "Delete Settings", ASPTextDomain );
    echo "\" onclick=\"return ASPUninstall();\"  /></p>\n\n\t\t\t</form>\n\n\n\t\t\t";
    echo "<s";
    echo "cript type=\"text/javascript\">\n\n\t\t\t\tfunction ASPUninstall()\n\t\t\t\t{\n\t\t\t\t\tif (confirm('Are you sure you want to delete plugin settings?'))\n\t\t\t\t\t{\n\t\t\t\t\t\treturn true;\n\t\t\t\t\t}\n\t\t\t\t\telse\n\t\t\t\t\t{\n\t\t\t\t\t\treturn false;\n\t\t\t\t\t}\n\t\t\t\t}\n\n\t\t\t</script>\n\n\t\t</div>\n\n\t";
}

function aspadminmenu( )
{
    $page = add_menu_page( ASPName, "ASP", 9, __FILE__, "ASPAdmin" );
    add_submenu_page( __FILE__, ASPName, "ASP", 9, __FILE__, "ASPAdmin" );
    add_submenu_page( __FILE__, ASPName." &rsaquo; Remote Bookmarking", "Remote Bookmarking", 9, "auto-social-poster-remote", "ASPAdminRemote" );
    add_submenu_page( __FILE__, ASPName." &rsaquo; Site List", "Site List", 9, "auto-social-poster-sitelist", "ASPAdminSiteList" );
    add_submenu_page( __FILE__, ASPName." &rsaquo; Statistics", "Statistics", 9, "auto-social-poster-statistics", "ASPAdminStatistics" );
    add_submenu_page( __FILE__, ASPName." &rsaquo; Uninstall", "Uninstall", 9, "auto-social-poster-uninstall", "ASPAdminUninstall" );
    add_action( "admin_print_scripts-{$page}", create_function( "", "wp_print_scripts(array(\"sack\"));" ) );
}

function aspmanagepostscolumns( $defaults )
{
    $settings = get_option( ASPOptions );
    if ( $settings['DisplayManagePostColumn'] )
    {
        $defaults['ASP'] = "ASP";
    }
    return $defaults;
}

function aspmanagepostscustomcolumn( $ColumnName, $PostID )
{
    global $post;
    $settings = get_option( ASPOptions );
    $SubmitValue = in_array( $PostID, $settings['Posted'] ) ? __( "Resubmit", ASPTextDomain ) : __( "Submit", ASPTextDomain );
    if ( $ColumnName == "ASP" && $post->post_status == "publish" )
    {
        print "<a href='admin.php?page=".plugin_basename( __FILE__ )."&ASPPostID={$PostID}'>{$SubmitValue}</a>";
    }
}

function aspcheckpostvalues( $Post, $default = false )
{
    if ( !empty( $default ) )
    {
        $Post = array_merge( $default, $Post );
    }
    foreach ( $Post as $key => $value )
    {
        if ( is_array( $value ) )
        {
            $Post[$key] = $value;
            continue;
        }
        $value = trim( $value );
        $value = preg_replace( "/\\s{2,}/", " ", $value );
        $Post[$key] = $value;
    }
    return $Post;
}

function phptojsarray( $array )
{
    if ( !is_array( $array ) )
    {
        return $array;
    }
    $string = "";
    foreach ( $array as $key => $val )
    {
        $string .= "'".$key."' : '".$val."', ";
    }
    return "{".rtrim( $string, ", " )."};"."\n";
}

function aspremoveurlquery( $url, $key )
{
    $url = preg_replace( "/(.*)(\\?|&)".$key."=[^&]+?(&)(.*)/i", "$1$2$4", $url."&" );
    $url = substr( $url, 0, 0 - 1 );
    return $url;
}

error_reporting( E_ALL ^ E_NOTICE );
ini_set( "display_errors", false );
ini_set( "log_errors", true );
ini_set( "error_log", ASPPathReports.ASPFileErrorLog );
$ASPSettings = get_option( ASPOptions );
$ASPServices = array( "a1webmarks" => "a1-webmarks.com", "bibsonomy" => "bibsonomy.org", "blinklist" => "blinklist.com", "blogmarks" => "blogmarks.net", "buddymarks" => "buddymarks.com", "connectedy" => "connectedy.com", "delicious" => "delicious.com", "dzone" => "dzone.com", "faves" => "faves.com", "google" => "google.com/bookmarks", "kaboomit" => "ka-boom-it.com", "koolontheweb" => "koolontheweb.com", "linkagogo" => "linkagogo.com", "linkatopia" => "linkatopia.com", "linkroll" => "linkroll.com", "linkswarm" => "linkswarm.com", "misterWong" => "mister-wong.com", "mybookmarks" => "mybookmarks.com", "myweb" => "myweb.yahoo.com", "newsvine" => "newsvine.com", "pligg" => "pligg.com", "scuttle" => "scuttle.org", "scuttlePLUS" => "scuttleplus.org", "searchles" => "searchles.com", "sphinn" => "sphinn.com", "tweako" => "tweako.com" );
$ASPServicesStandby = array( "misterWong" => "mister-wong.com" );
$ASPServicesRemoved = array( "backflip" => "backflip.com", "delirious" => "de.lirio.us", "dewfun" => "dewfun.com", "excites" => "excites.com", "feedmelinks" => "feedmelinks.com", "furl" => "furl.net", "getboo" => "getboo.com", "magnolia" => "ma.gnolia.com", "markaboo" => "markaboo.com", "propeller" => "propeller.com", "simpy" => "simpy.com", "shoutwire" => "shoutwire.com", "taggly" => "taggly.com", "twine" => "twine.com", "xilinus" => "my.xilinus.com" );
add_action( "init", "ASPInit" );
add_action( "ASPBookmarkHook", "ASPBookmark", 10, 2 );
add_action( "ASPRemoteBookmarkHook", "ASPRemoteBookmark" );
if ( !empty( $ASPSettings['BookmarkActive'] ) )
{
	file_put_contents('settingme.txt' ,$ASPSettings['BookmarkActive'] );
    add_action( "publish_post", "ASPPublishPost" );
    add_action( "publish_page", "ASPPublishPost" );
}
add_filter( "the_content", "ASPTheContent" );
add_action( "admin_menu", "ASPAdminMenu" );
add_filter( "manage_posts_columns", "ASPManagePostsColumns" );
add_action( "manage_posts_custom_column", "ASPManagePostsCustomColumn", 10, 2 );
add_filter( "manage_pages_columns", "ASPManagePostsColumns" );
add_action( "manage_pages_custom_column", "ASPManagePostsCustomColumn", 10, 2 );
if ( !function_exists( "array_combine" ) )
{
}
?>
