<?php
/*********************/
/*                   */
/*  Dezend for PHP5  */
/*         NWS       */
/*      Nulled.WS    */
/*                   */
/*********************/

class aspcurl
{

    public function aspcurl( $fileErrorName = false )
    {
        if ( !$this->fileCookie )
        {
            $this->redirect = true;
            $this->fileCookie = tempnam( ASPPath."/reports", "ASP" );
        }
        if ( $fileErrorName )
        {
            $this->fileError = fopen( $fileErrorName, "at" );
        }
        ++$this->used;
        $this->curl = curl_init( );
        curl_setopt( $this->curl, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $this->curl, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)" );
        curl_setopt( $this->curl, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $this->curl, CURLOPT_SSL_VERIFYHOST, false );
        curl_setopt( $this->curl, CURLOPT_COOKIEJAR, $this->fileCookie );
        curl_setopt( $this->curl, CURLOPT_COOKIEFILE, $this->fileCookie );
        curl_setopt( $this->curl, CURLOPT_HEADER, true );
        if ( $this->fileError )
        {
            curl_setopt( $this->curl, CURLOPT_VERBOSE, true );
            curl_setopt( $this->curl, CURLOPT_STDERR, $this->fileError );
        }
    }

    public function settimeout( $connect, $transfer )
    {
        curl_setopt( $this->curl, CURLOPT_CONNECTTIMEOUT, $connect );
        curl_setopt( $this->curl, CURLOPT_TIMEOUT, $transfer );
    }

    public function setredirect( $enable = true )
    {
        $this->redirect = $enable;
    }

    public function auth( $user, $pass )
    {
		
        $this->authInfo = "{$user}:{$pass}";
    }

    public function geterror( )
    {
        return curl_errno( $this->curl ) ? curl_error( $this->curl ) : false;
    }

    public function gethttpcode( )
    {
        return curl_getinfo( $this->curl, CURLINFO_HTTP_CODE );
    }

    public function checkredirect( $page )
    {
        if ( strpos( $page, "\r\n\r\n" ) !== false )
        {
            $temp = explode( "\r\n\r\n", $page, 2 );
			$page = $temp[1];
            $temp = explode( "\r\n\r\n", $page, 2 );
			$headers = $temp[0];
        }
        $code = getHttpCode( );
        if ( 300 < $code && $code < 310 )
        {
            preg_match( "#Location: ?(.*)#i", $headers, $match );
            $this->redirectURL = trim( $match[1] );
            if ( $this->redirectManual )
            {
                return get( $this->redirectURL );
            }
        }
        $this->redirectURL = "";
        return $page;
    }

    public function makequery( $data )
    {
        if ( is_array( $data ) )
        {
            $fields = array( );
            foreach ( $data as $key => $value )
            {
                $fields[] = $key."=".urlencode( $value );
            }
            $fields = implode( "&", $fields );
        }
        else
        {
            $fields = $data;
        }
        return $fields;
    }

    public function post( $url, $data, $ref = "" )
    {
        if ( 0 < $this->used )
        {
            ASPCurl( );
        }
        $fields = makeQuery( $data );
        curl_setopt( $this->curl, CURLOPT_URL, $url );
        curl_setopt( $this->curl, CURLOPT_POST, true );
        curl_setopt( $this->curl, CURLOPT_POSTFIELDS, $fields );
        curl_setopt( $this->curl, CURLOPT_HEADER, true );
        if ( $ref )
        {
            curl_setopt( $this->curl, CURLOPT_REFERER, $ref );
        }
        if ( $this->redirect )
        {
            $this->redirectManual = @!curl_setopt( @$this->curl, @CURLOPT_FOLLOWLOCATION, @true );
        }
        else
        {
            @curl_setopt( @$this->curl, @CURLOPT_FOLLOWLOCATION, @false );
            $this->redirectManual = false;
        }
        if ( $this->authInfo )
        {
            curl_setopt( $this->curl, CURLOPT_USERPWD, $this->authInfo );
        }
        $page = curl_exec( $this->curl );
		
        $error = curl_errno( $this->curl );
        if ( $error != CURLE_OK || empty( $page ) )
        {
            return false;
        }
        return checkRedirect( $page );
    }

    public function get( $url, $data = null, $ref = "" )
    {
        if ( 0 < $this->used )
        {
            ASPCurl( );
        }
        if ( !is_null( $data ) )
        {
            $fields = makeQuery( $data );
            $url .= "?".$fields;
        }
        curl_setopt( $this->curl, CURLOPT_URL, $url );
        if ( $ref )
        {
            curl_setopt( $this->curl, CURLOPT_REFERER, $ref );
        }
        if ( $this->redirect )
        {
            $this->redirectManual = @!curl_setopt( @$this->curl, @CURLOPT_FOLLOWLOCATION, @true );
        }
        else
        {
            @curl_setopt( @$this->curl, @CURLOPT_FOLLOWLOCATION, @false );
            $this->redirectManual = false;
        }
        if ( $this->authInfo )
        {
            curl_setopt( $this->curl, CURLOPT_USERPWD, $this->authInfo );
        }
        $page = curl_exec( $this->curl );
		
        $error = curl_errno( $this->curl );
        if ( $error != CURLE_OK || empty( $page ) )
        {
            return false;
        }
        return checkRedirect( $page );
    }

    public function close( )
    {
        curl_close( $this->curl );
        unlink( $this->fileCookie );
    }

}

?>
