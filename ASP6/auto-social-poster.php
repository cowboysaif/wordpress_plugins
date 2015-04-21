<?php
	/*
	Plugin Name: ASP 6
	Plugin URI: http://google.com
	Description: HACK !!! UGLY TERRIBLE HACK !!!
	Author: Saif Taifur
	Version: 0.1
	Author URI: mailto:cowboysaif@hotmail.com
	*/

function ASPActivate( )
{
    $settings = get_option( ASPOptions );
    $ASPOptionsKeys = array( "LicenseKey" => "", "YahooKey" => "", "Tags" => "Post", "TagsFallback" => 1, "TagsLimit" => 0, "PostTags" => "WordPress", "PostTagsManualStarter" => "Tags:", "PostTagsManualSeparator" => ",", "PostTagsManualHide" => 1, "PresetTags" => "", "YahooTagsLimit" => 3, "BookmarkActive" => 1, "BookmarkTestMode" => 0, "BookmarkScuttleLinks" => 0, "BookmarkScuttleCharLimit" => 255, "ConditionsTags" => array( ), "ConditionsCategories" => array( ), "AccountLimit" => 0, "ReportsLog" => 1, "ReportsEmail" => 1, "ReportsCURL" => 0, "ReportsPageContent" => 0, "DisplayManagePostColumn" => 1, "Accounts" => array( ), "Posted" => array( ) );
    if ( !empty( $settings ) )
    {
        $temp = array_merge( $ASPOptionsKeys, $settings );
        update_option( ASPOptions, $temp );
    }
    else
    {
        add_option( ASPOptions, $ASPOptionsKeys );
    }
}

function ASPPluginLinks( $links, $file )
{
    static $filePlugin = NULL;
    if ( !$filePlugin )
    {
        $filePlugin = plugin_basename( __FILE__ );
    }
    if ( $file == $filePlugin )
    {
        $setup = "<a href=\"admin.php?page=".dirname( plugin_basename( __FILE__ ) )."/auto-social-poster-include.php\">Settings</a>";
        array_push( $links, $setup );
    }
    return $links;
}

function ASPionCubeLoader( )
{
    if ( extension_loaded( "ionCube Loader" ) )
    {
        return TRUE;
    }
    else if ( !extension_loaded( "ionCube Loader" ) && function_exists( "dl" ) )
    {
        $__oc = strtolower( substr( php_uname( ), 0, 3 ) );
        $__ln = "/ioncube/ioncube_loader_".$__oc."_".substr( phpversion( ), 0, 3 ).( $__oc == "win" ? ".dll" : ".so" );
        $__oid = $__id = realpath( ini_get( "extension_dir" ) );
        $__here = dirname( __FILE__ );
        if ( 1 < strlen( $__id ) && $__id[1] == ":" )
        {
            $__id = str_replace( "\\", "/", substr( $__id, 2 ) );
            $__here = str_replace( "\\", "/", substr( $__here, 2 ) );
        }
        $__rd = str_repeat( "/..", substr_count( $__id, "/" ) ).$__here."/";
        $__i = strlen( $__rd );
        while ( $__i-- )
        {
            if ( $__rd[$__i] == "/" )
            {
                $__lp = substr( $__rd, 0, $__i ).$__ln;
                if ( file_exists( $__oid.$__lp ) )
                {
                    $__ln = $__lp;
                    break;
                }
            }
        }
        return dl( $__ln );
    }
    else if ( !extension_loaded( "ionCube Loader" ) )
    {
        return FALSE;
    }
}

define( "ASPOptions", "AutoSocialPoster-Gold" );
define( "ASPName", "Auto Social Poster" );
define( "ASPPath", dirname( __FILE__ ) );
define( "ASPURL", get_bloginfo( "wpurl" )."/wp-content/plugins/".dirname( plugin_basename( __FILE__ ) ) );
define( "ASPPathReports", ASPPath."/reports/" );
define( "ASPTextDomain", "autosocialposter" );
define( "ASPFileLog", "log.txt" );
define( "ASPFileErrorLog", "errorLog.txt" );
define( "ASPFileCURLLog", "errorCURL.txt" );
$ASPVersion = "6.0";
$ASPOptionsKeys = array( "LicenseKey" => "", "YahooKey" => "", "Tags" => "", "TagsFallback" => "", "TagsLimit" => "", "PostTags" => "", "PostTagsManualStarter" => "", "PostTagsManualSeparator" => "", "PostTagsManualHide" => "", "PresetTags" => "", "YahooTagsLimit" => "", "BookmarkActive" => "", "BookmarkTestMode" => "", "BookmarkScuttleLinks" => "", "BookmarkScuttleCharLimit" => "", "ConditionsTags" => array( ), "ConditionsCategories" => array( ), "AccountLimit" => "", "ReportsLog" => "", "ReportsEmail" => "", "ReportsCURL" => "", "ReportsPageContent" => "", "DisplayManagePostColumn" => "" );
register_activation_hook( __FILE__, "ASPActivate" );
add_filter( "plugin_action_links", "ASPPluginLinks", 10, 2 );


    require_once( dirname( __FILE__ )."/auto-social-poster-include.php" );


?>
