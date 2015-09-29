<?php
//
// +-----------------------------------+
// |          Enom API v 1.0.2         |
// |      http://www.SysTurn.com       |
// +-----------------------------------+
//
//
//   This program is free software; you can redistribute it and/or modify
//   it under the terms of the ISLAMIC RULES and GNU Lesser General Public
//   License either version 2, or (at your option) any later version.
//
//   ISLAMIC RULES should be followed and respected if they differ
//   than terms of the GNU LESSER GENERAL PUBLIC LICENSE
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of the license with this software;
//   If not, please contact support @ S y s T u r n .com to receive a copy.
//

/*
 CHANGES LOG:-
 ============
 - 17 August 2006 : Edited function _returnError() so the $error variable will not be constructed unless debugging is enabled.
 - 17 August 2006 : Added Inline documentation
*/


/**
 * EnomInterface class
 *
 * This class contains all the main functions that open connection
 * to Enom web servers, send command and queries and finlly parse
 * the response. It also contain the global error handler.
 *
 * @author   Bakr Alsharif <bakr AT systurn DOT com>
 * @website  http://systurn.com/
 * @version  1.0.2   17 August 2006
 */
Class EnomInterface
{
  /**
  * Connects to Enom's live server or test server
  */
  var $isLive;
  
  /**
  * Use SSL while connectins or HTTP connections
  */
  var $useSSL;
  
  /**
  * Enom account username
  */
  var $user;
  
  /**
  * Enom account password
  */
  var $pass;
  
  
  /**
  * Command parameters Array
  */
  var $params = array();
  
  /**
  * String containing the request being sent to enom's server
  */
  var $rawRequest = '';
  
  /**
  * String containing the response returned from enom's server, including response Headers
  */
  var $rawResponse = '';
  
  /**
  * Array holds the parsed response as key=>value
  */
  var $response = array();
  
  /**
  * Error flag. If an error occured while prepairing for request, or if enom's server
  * returned an error, this variable will be true. Otherwise if succeeded
  * it will be false.
  */
  var $isError = false;
  
  /**
  * Debugging flag, if set to true, error messages will be printed on screen
  */
  var $debug = false;
  
  /**
  * A flag, if set to true, the script will exit if any error occured
  */
  var $dieOnError = false;
  
  
  
  /**
  * CONSTRUCTOR Function
  *
  * Valid Enom account username and password must be used.
  *
  * @param      string      $user
  * @param      string      $pass
  * @param      bool        $use_ssl        if true, SSL connections will be used (Requires libcurl). default iif not passed is true
  * @param      bool        $is_live        if true, Connections will be made to Enom's live server. Otherwise, it will be made to Enom's test server. default if not passed is false
  * @see        _sslProcess
  */
  function EnomInterface($user, $pass, $use_ssl = true, $is_live = false)
  {
    $this->user = $user;
    $this->pass = $pass;
    $this->useSSL = $use_ssl;
    $this->isLive = $is_live;
  }
  
  
  /**
  * Add new or edit a previousely added command parameter
  *
  * the value will be urlencoded before it's being stored in $this->params array
  *
  * @return     void
  * @param      string      $name       Parameter name
  * @param      string      $value      Parameter value    
  * @access     public
  * @see        _setCommand
  */
  function addParam($name, $value)
  {
    $this->params[$name] = urlencode($value);
  }
  
  
  /**
  * Process the request to enom's servers
  *
  * This function will built the command request string then it connects to enom's live or test server
  * to execute the command, get the response and parse it, to finally store the parsed response as key=>value
  * pairs in $this->response array. If SSL connection is enabled, _sslProcess() will be called.
  *
  * @return     bool        returns true on success, false if any error occured
  * @access     public
  * @see        _sslProcess
  */
  function process()
  {
    if( empty($this->user) || empty($this->pass) )
    {
      $this->_addError('You must specify Username and Password.');
      return $this->_returnError();
    }
    if( !count($this->params) )
    {
      $this->_addError('You must set request params first before calling process().');
      return $this->_returnError();
    }

    $this->rawRequest = 'UID=' . $this->user . '&PW=' . $this->pass . '&ResponseType=Text';
    
    foreach($this->params as $key=>$val)
    {
      $this->rawRequest .= "&$key=$val";
    }
    
    if($this->isLive)
    {
      $host = 'reseller.enom.com';
    }
    else
    {
      $host = 'resellertest.enom.com';
    }
    
    if($this->useSSL)
    {
      return $this->_sslProcess('https://' . $host . '/interface.asp?' . $this->rawRequest);
    }
    
    $this->rawRequest = 'GET /interface.asp?' . $this->rawRequest . " HTTP/1.0\r\n\r\n";
    
    $socket = @fsockopen($host, 80, $errno, $errstr);
    
    if(!$socket)
    {
      $this->_addError("Could not connect to Server - Error($errno) $errstr");
      return $this->_returnError();
    }
    
    fputs($socket, $this->rawRequest);
    
    $in_head = true;
    $got_values = false;
    while( ($line = fgets($socket, 2048)) )
    {
      $this->rawResponse .= $line;

      if( $in_head && ("\n" == $line || "\r\n" == $line) )
      {
        $in_head = false;
        continue;
      }
      
      if( !$in_head )
      {
        if( '' == trim($line) || ';' == substr($line, 0, 1) ) continue;
        list($key, $val) = explode('=', $line);
        $this->response[ trim($key) ] = trim($val);

        if( 'Done' == trim($key) ) $got_values = true;
      }
    }
    fclose($socket);
    
    if( !$got_values )
    {
      $this->_addError('Could not find any return values from the server');
      return $this->_returnError();
    }
    
    return true;
  }
  
  /**
  * Process a HTTPS request to enom's servers
  * 
  * This function requires libcurl
  * 
  * @return     bool                    returns true on success, false if any error occured
  * @param      string      $url        The url which should be built in process() function
  * @access     private
  * @see        process
  * @see        _curlHeaders
  */
  function _sslProcess($url)
  {
    if( !function_exists('curl_init') )
    {
      $this->_addError('Could not connect to Server though SSL - libcurl is not supported');
      return $this->_returnError();
    }

    $version = curl_version();
    $supports_insecure = false;
    #insecure key is supported by curl since version 7.10
    if( preg_match('/libcurl\/([^ $]+)/', $version, $m) )
    {
        $parts = explode('.', $m[1]);
        if( $parts[0] > 7 || ($parts[0] = 7 && $parts[1] >= 10) )
        {
          $supports_insecure = true;
        }
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    if ($supports_insecure)
    {
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADERFUNCTION, array($this, '_curlHeaders'));
    $this->_curlHeaders(false);

    $body = curl_exec($ch);
    $errno = curl_errno($ch);
    $errstr = curl_error($ch);
    curl_close ($ch);
    if( $errstr )
    {
      $this->_addError("Could not connect to Server - Error($errno) $errstr");
      return $this->_returnError();
    }
    $this->rawResponse = $this->_curlHeaders(true) . "\r\n" . $body;
    $lines = explode("\n", $body);

    $got_values = false;
    foreach($lines as $line)
    {
      if( '' == trim($line) || ';' == substr($line, 0, 1) ) continue;
      list($key, $val) = explode('=', $line);
      $this->response[ trim($key) ] = trim($val);

      if( 'Done' == trim($key) ) $got_values = true;
    }

    if( !$got_values )
    {
      $this->_addError('Could not find any return values from the server');
      return $this->_returnError();
    }

    return true;
  }

  /**
  * Collects response headers from libcurl
  * 
  * @return     string          return a string containing the collected headers
  * @access     private
  * @see        _sslProcess
  */
  function _curlHeaders()
  {
    static $headers = '';

    $args = func_get_args();
    if (count($args) == 1)
    {
      $return = '';
      if($args[0] == true) $return = $headers;
      $headers = '';
      return $return;
    }

    if (trim($args[1]) != '') $headers .= $args[1];
    return strlen($args[1]);
  }
  
  
  /**
  * Adds a custom error to $this->response array
  * 
  * This function can be used to add a custom error to $this->response array.
  * For example if a non valid domain name is detected, you can add your custom error, and do not process the request
  * 
  * @return     void
  * @param      string      $error        The error message
  * @access     private
  */
  function _addError($error)
  {
    $this->response['ErrCount'] = '1';
    $this->response['Err1'] = $error;
  }
  
  
  /**
  * Returns false whnen an error occures
  * 
  * If any error occured, we do return $this->_returnError() instead of returning false.
  * This function will trigger a warnning. If debugging is enabled a detaild error message will be pronted on screen.
  * If die on error is enabled, the script will exit and stop execution.
  * 
  * @return     bool        always return false
  * @access     private
  */
  function _returnError()
  {
    $this->isError = true;

    trigger_error("Failed while executing command: <i>{$this->params['Command']}</i>", E_USER_WARNING);

    if($this->debug)
    {
        $error = '';
        $error .= "<pre>Failed while executing command: <i>{$this->params['Command']}</i>\r\n\r\n<b><u>Params Sent</u></b>\r\n";
        $error .= print_r($this->params, true);
        $error .= "\r\n<b><u>Found Errors:</u></b>\r\n";
        $error .= '<ul>';
        for($i=1; $i<=$this->response['ErrCount']; $i++)
        {
            $error .= '<li>' . $this->response[ ('Err' . $i) ] . '</li>';
        }
        $error .= '</ul>';
        $error .= "\r\n<b><u>Request sent to server:</u></b>\r\n{$this->rawRequest}\r\n";
        $error .= "\r\n<b><u>Server Reply:</u></b>\r\n";
        $error .= htmlentities($this->rawResponse);
        $error .= '</pre>';
        
        echo $error;
    }

    if($this->dieOnError) exit;

    return false;
  }
  
  
  /**
  * Initialize new command request to enom's servers
  * 
  * This function will prepair for new request, and set the Command parameter.
  * Should be called only inside the EnomService class functions, to initialize new command execution.
  * 
  * @return     void
  * @param      string      $command        The command to be executed
  * @access     private
  * @see        addParam
  */
  function _setCommand($command)
  {
    $this->params = array();
    $this->rawRequest = '';
    $this->rawResponse = '';
    $this->response = array();
    $this->isError = false;
    $this->addParam('Command', $command);
  }
  
  
  /**
  * Check if the passed SLD (Second-level domain) is valid or not
  * 
  * Enom says in it's API documentations, that valid SLD must meet the following requirements:
  * 
  *     - Must be composed of the letters a through z, the numbers 0 through 9, and the hyphen (-) character.
  *     - Some foreign character sets can display onscreen, but resolve to alphanumeric plus hyphen characters in the underlying code.
  *     - must not begin or end with the hyphen character.
  *     - must not contain spaces.
  *     - must not contain special characters other than the hyphen character.
  *     - The third and fourth characters must not both be hyphens unless it is an encoded international-character domain name.
  *     - must contain 2 to 63 characters, inclusive.
  * 
  * @param      string      $sld
  * @return     bool                    Return true if valid, Otherwise it return false 
  * @access     private
  * @see        _setParseDomain
  */
  function _isValidSLD($sld)
  {
    if( preg_match('/^[a-z0-9]+[a-z0-9\-]*[a-z0-9]+$/i', $sld) && strlen($sld) < 64 && substr($sld, 2, 2) != '--' )
    {
      return true;
    }
    else
    {
      return false;
    }
  }

  
  /**
  * Parse the passed domain name and set the SLD and TLD parameters
  * 
  * This function will check first if the passed domain name is valid or not, and returns false if not valid,
  * Otherwise it will set the TLD and SLD parameters to the parsed matches.
  * 
  * @param      string      $domainName
  * @return     bool                    Return true if valid, Otherwise it return false 
  * @access     private
  * @see        _isValidSLD
  * @see        addParam
  */
  function _setParseDomain($domainName)
  {
    if ( !preg_match('/^([a-z0-9]+[a-z0-9\-]*[a-z0-9]+)\.([a-z]+[a-z\.]*[a-z]+)$/i', $domainName, $parts) || !$this->_isValidSLD($parts[1]) )
    {
      return false;
    }
    $this->addParam('SLD', $parts[1]);
    $this->addParam('TLD', $parts[2]);
    return true;
  }
}
?>