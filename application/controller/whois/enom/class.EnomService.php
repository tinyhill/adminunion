<?php
//
// +-----------------------------------+
// |          Enom API v 1.0           |
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
 Supported Enom FUNCTIONS:-
 =====================
  - Check Domain Availability
  - Register Domain
  - Renew / Extend Domain
  - Transfer Domain
  - Get Domain NameServers
  - Set Domain NameServers
  - Get Domain Expiration Date
  - ID Protect Domain
  - Get Lock Status
  - Set Lock Status
  - Get Domain Contacts
  - Set Domain Contacts
  - Get Email Forwarders
  - Set Email Forwarders
  - Get Domain Hosts (Records)
  - Set Domain Hosts (Records)
  - Set Namy-My-Phone
  - Get Name-My_Phone
  - Get Domain Name ID
    
  
 CHANGES LOG:-
 ============
 - 14 August 2006 : Added support for command GetNameMyPhone
 - 14 August 2006 : Added support for command SetNameMyPhone
 - 15 August 2006 : Added support for command verifyDomain
 - 17 Augyst 2006: Removed function verify domain (the used command 'GetDomains' should be used to list available domains in your account not to verify a domain name)
 - 17 August 2006: Added support for command GetDomainNameID, this function also can be used to verify a domain name that it belongs to your enom account.
 - 17 August 2006: Added inline documentaion.
*/



// Includes the EnomInterface class if not yet included
require_once( dirname(__FILE__) . '/class.EnomInterface.php' );


/**
 * EnomService class
 *
 * This class contains all the functions which will translate their passed parametrs into commands to be executed on Enom's servers.
 *
 * @author   Bakr Alsharif <bakr AT systurn DOT com>
 * @website  http://systurn.com/
 * @version  1.0.2   17 August 2006
 * @extends  EnomInterface
 */
Class EnomService extends EnomInterface
{

  /**
  * Check domain availability
  * 
  * Executes the 'Check' command on Enom's servers to check domain availability.
  * Top-level domain name (extension) Permitted values are:
  *   - Any single TLD, for example, com
  *   - *  returns 11 of the most commonly used TLDs
  *   - *1 returns com, net, org, info, biz, us, ws
  *   - *2 returns com, net, org, info, biz, us
  *   - @  returns com, net, org
  *   - A comma-delimited list of TLDs to check, up to 30 names. Do not use with DomainSpinner.
  * 
  * @param      string      $sld        Second-level Domain
  * @param      string      $tld        Top-level Domain (also known as Domain Extension).
  * @param      bool        $spinner    If true, DomainSpinner (Domain Suggestion) will be enabled
  * @param      bool        $allowDash  If DomainSpinner is enabled and $allowDash set to true, Suggested domain names may contain Dashes
  * @param      string      $word1      If DomainSpinner is enabled and $word1 is set, Suggested domain names may contain this word
  * @param      string      $word2      If DomainSpinner is enabled and $word2 is set, Suggested domain names may contain this word
  * @param      string      $word3      If DomainSpinner is enabled and $word3 is set, Suggested domain names may contain this word
  * @return     array       An associative array containing the domain name as a key and a bool (true if domain is available, false otherwise) as a value. On error, it returns false
  * @access     public
  */
  function checkDomain($sld, $tld, $spinner = false, $allowDash = true, $word1 = '', $word2 = '', $word3 = '')
  {
    $this->_setCommand('Check');

    $this->addParam('SLD', $sld);
    if( strstr($tld, ',') )
    {
      $this->addParam('TLDList', $tld);
    }
    else
    {
      $this->addParam('TLD', $tld);
    }
    
    if( $spinner && !strstr($tld, ',') )
    {
        $this->addParam('DomainSpinner', '1');
        $this->addParam('AllowDash', $allowDash ? '1' : '0');
        if('' != $word1)
            $this->addParam('Word1', $word1);
        if('' != $word2)
            $this->addParam('Word2', $word2);
        if('' != $word3)
            $this->addParam('Word3', $word3);
    }

    if( !$this->process() || '0' != $this->response['ErrCount'] )
    {
      return $this->_returnError();
    }

    $result = array();
    
    if($spinner)
    {
        $i = 1;
        while( isset($this->response['SuggestedName' . $i]) )
        {
            $result[$this->response['SuggestedName' . $i]] = true;
            $i++;
        }
    }
    
    if( !isset($this->response['DomainCount']) || '1' == $this->response['DomainCount'] )
    {
      $result["$sld.$tld"] = ('210' == $this->response['RRPCode']) ? true : false;
      return $result;
    }

    for($i=1; $i<=$this->response['DomainCount']; $i++)
    {
      $result[ $this->response[('Domain'.$i)] ] = ('210' == $this->response[('RRPCode'.$i)]) ? true : false;
    }
    
    return $result;
  }

  
  /**
  * Register a new domain name
  * 
  * Executes the 'Purchase' command on Enom's servers to register a new domain.
  * Note that this command to not fail, it must meet the following requirements:
  *     - Your Enom account must have enough credits to cover the order amount.
  *     - The domain name must be valid and available.
  *     - Number of years must not be less than the minimum number of years required for the specified TLD.
  *     - Name Servers must be valid and registered.
  *     - Name servers for .us names must be located in the United States.
  *     - RegistrantJobTitle and RegistrantFax are required in the contacts array if RegistrantOrganizationName is set.
  * 
  * @param      string      $domainName     Must be a valid domain name, that is currently available
  * @param      int         $numYears       Some TLDs like .co.uk requires minimum of 2 years, Another may require 10 years
  * @param      array       $contacts       Associative array containing Contacts as key and value.
  * @param      array       $nameServers    If not set, Enom's Default name servers will be used instead.
  * @param      bool        $regLock        A flag that specifies if the domain should be locked or not. Default is true.
  * @return     long        Order ID, or false if failed.
  * @access     public
  * @see        renewDomain
  * @see        transferDomain
  */
  function registerDomain($domainName, $numYears, $contacts, $nameServers = null, $regLock = true)
  {
    $this->_setCommand('Purchase');

    if( !$this->_setParseDomain($domainName) )
    {
      $this->_addError("Invalid domain name $domainName");
      return $this->_returnError();
    }
    
    $this->addParam('NumYears', $numYears);
    
    if( !is_null($nameServers) )
    {
      for($i=0; $i<count($nameServers); $i++)
      {
        $this->addParam( 'NS'.($i+1) , $nameServers[$i] );
      }
    }
    else
    {
      $this->addParam('UseDNS', 'Default');
    }
    
    if( !$regLock )
    {
      $this->addParam('UnLockRegistrar', '1');
    }
    
    if( isset($contacts['EmailAddress']) )
    {
      $contacts = array('registrant'=>$contacts);
    }
    foreach($contacts as $type=>$info)
    {
      switch($type)
      {
        case 'registrant':
          $typeName = 'Registrant';
          break;

        case 'billing':
          $typeName = 'Billing';
          break;
          
        case 'auxbilling':
          $typeName = 'AuxBilling';
          break;

        case 'tech':
          $typeName = 'Tech';
          break;

        case 'admin':
          $typeName = 'Admin';
          break;

        default:
          break;
      }
      $this->addParam( $typeName . 'EmailAddress', $info['EmailAddress'] );
      $this->addParam( $typeName . 'Fax', $info['Fax'] );
      $this->addParam( $typeName . 'Phone', $info['Phone'] );
      $this->addParam( $typeName . 'Country', $info['Country'] );
      $this->addParam( $typeName . 'PostalCode', $info['PostalCode'] );
      if( $info['StateProvinceChoice'] == 'S' )
      {
        $this->addParam( $typeName . 'StateProvinceChoice', 'S' );
        $this->addParam( $typeName . 'StateProvince', $info['State'] );
      }
      elseif( $info['StateProvinceChoice'] == 'P' )
      {
        $this->addParam( $typeName . 'StateProvinceChoice', 'Province' );
        $this->addParam( $typeName . 'StateProvince', $info['Province'] );
      }
      else
      {
        $this->addParam( $typeName . 'StateProvinceChoice', 'Blank' );
        $this->addParam( $typeName . 'StateProvince', '' );
      }
      $this->addParam( $typeName . 'City', $info['City'] );
      $this->addParam( $typeName . 'Address1', $info['Address1'] );
      $this->addParam( $typeName . 'Address2', $info['Address2'] );
      $this->addParam( $typeName . 'LastName', $info['LastName'] );
      $this->addParam( $typeName . 'FirstName', $info['FirstName'] );
      $this->addParam( $typeName . 'JobTitle', $info['JobTitle'] );
      $this->addParam( $typeName . 'OrganizationName', $info['OrganizationName'] );
    }

    if( !$this->process() || '0' != $this->response['ErrCount'] )
    {
      return $this->_returnError();
    }
    elseif('200' != $this->response['RRPCode'])
    {
      $this->_addError("Invalid RRPCode Returned - ({$this->response['RRPCode']}) {$this->Response['RRPText']}");
      return $this->_returnError();
    }
    else
    {
      return $this->response['OrderID'];
    }
  }

  /**
  * Renew a domain name that belongs to your Enom account
  * 
  * Executes the 'Extend' command on Enom's servers to renew a domain name which was previously registered or transfered to your Enom account.
  * Note that this command to not fail, it must meet the following requirements:
  *     - Your Enom account must have enough credits to cover the order amount.
  *     - The domain name must be valid and active and belongs to your Enom account.
  *     - The new expiration date cannot be more than 10 years in the future.
  * 
  * @param      string      $domainName     Must be a valid and active domain name.
  * @param      int         $numYears       The new expiration date cannot be more than 10 years in the future.
  * @return     long        Renewal Order ID, or false if failed.
  * @access     public
  * @see        registerDomain
  * @see        transferDomain
  */
  function renewDomain($domainName, $numYears)
  {
    $this->_setCommand('Extend');

    if( !$this->_setParseDomain($domainName) )
    {
      $this->_addError("Invalid domain name $domainName");
      return $this->_returnError();
    }

    $this->addParam('NumYears', $numYears);
    
    if( !$this->process() || '0' != $this->response['ErrCount'] )
    {
      return $this->_returnError();
    }
    elseif('200' != $this->response['RRPCode'])
    {
      $this->_addError("Invalid RRPCode Returned - ({$this->response['RRPCode']}) {$this->Response['RRPText']}");
      return $this->_returnError();
    }
    else
    {
      return $this->response['OrderID'];
    }
  }

  /**
  * Transfer a domain name to your Enom account
  * 
  * Executes the 'TP_CreateOrder' command on Enom's servers to transfer a domain name to your Enom account.
  * Note that this command to not fail, it must meet the following requirements:
  *     - Your Enom account must have enough credits to cover the order amount.
  *     - The domain name must be valid and already registered and not locked.
  *     - To transfer EPP names, the query must include the authorization key from the Registrar.
  *       On enom.com, the authorization key is displayed at the bottom of the Contact/WhoIs Information page,
  *       accessed from the Domain Control Panel.
  *     - RegistrantJobTitle and RegistrantFax are required in the contacts array if RegistrantOrganizationName is set.
  *       If $contacts is not set, existing WhoIs contacts will be used when the transfer is complete
  *
  * @param      string      $domainName     Must be a valid domain name, that is currently available
  * @param      array       $contacts       Associative array containing Contacts as key and value.
  * @param      string      $authCode       Authorization key from the Registrar. Required for EPP names.
  * @param      bool        $regLock        A flag that specifies if the domain should be locked or not. Default is true.
  * @return     long        Transfer Order ID, or false if failed.
  * @access     public
  * @see        registerDomain
  * @see        renewDomain
  */
  function transferDomain($domainName, $contacts = null, $authCode = null, $regLock = true)
  {
    $this->_setCommand('TP_CreateOrder');

    if( !$this->_setParseDomain($domainName) )
    {
      $this->_addError("Invalid domain name $domainName");
      return $this->_returnError();
    }
    
    $this->addParam('TLD1', $this->params['TLD']);
    $this->addParam('SLD1', $this->params['SLD']);
    unset($this->params['TLD'], $this->params['SLD']);

    $this->addParam('OrderType', 'Autoverification');
    $this->addParam('DomainCount', '1');
    
    if( $regLock )
    {
      $this->addParam('Lock', '1');
    }
    
    if( !is_null($authCode) )
    {
      $this->addParam('AuthInfo1', $authCode);
    }

    if( is_null($contacts) )
    {
      $this->addParam('UseContacts', '1');
    }
    else
    {
      $this->addParam('UseContacts', '0');
      if( isset($contacts['EmailAddress']) )
      {
        $contacts = array('registrant'=>$contacts);
      }
      foreach($contacts as $type=>$info)
      {
        switch($type)
        {
          case 'registrant':
            $typeName = 'Registrant';
            break;

          case 'auxbilling':
            $typeName = 'AuxBilling';
            break;

          case 'tech':
            $typeName = 'Tech';
            break;

          case 'admin':
            $typeName = 'Admin';
            break;

          default:
            break;
        }
        $this->addParam( $typeName . 'EmailAddress', $info['EmailAddress'] );
        $this->addParam( $typeName . 'Fax', $info['Fax'] );
        $this->addParam( $typeName . 'Phone', $info['Phone'] );
        $this->addParam( $typeName . 'Country', $info['Country'] );
        $this->addParam( $typeName . 'PostalCode', $info['PostalCode'] );
        if( $info['StateProvinceChoice'] == 'S' )
        {
          $this->addParam( $typeName . 'StateProvinceChoice', 'S' );
          $this->addParam( $typeName . 'StateProvince', $info['State'] );
        }
        elseif( $info['StateProvinceChoice'] == 'P' )
        {
          $this->addParam( $typeName . 'StateProvinceChoice', 'Province' );
          $this->addParam( $typeName . 'StateProvince', $info['Province'] );
        }
        else
        {
          $this->addParam( $typeName . 'StateProvinceChoice', 'Blank' );
          $this->addParam( $typeName . 'StateProvince', '' );
        }
        $this->addParam( $typeName . 'City', $info['City'] );
        $this->addParam( $typeName . 'Address1', $info['Address1'] );
        $this->addParam( $typeName . 'Address2', $info['Address2'] );
        $this->addParam( $typeName . 'LastName', $info['LastName'] );
        $this->addParam( $typeName . 'FirstName', $info['FirstName'] );
        $this->addParam( $typeName . 'JobTitle', $info['JobTitle'] );
        $this->addParam( $typeName . 'OrganizationName', $info['OrganizationName'] );
      }
    }
    
    if( !$this->process() || '0' != $this->response['ErrCount'] )
    {
      return $this->_returnError();
    }
    elseif( !isset($this->response['transferorderid']) )
    {
      $this->_addError('No Transfer Order ID returned!');
      return $this->_returnError();
    }
    else
    {
      return $this->response['transferorderid'];
    }
  }

  
  /**
  * Get registrar lock status for a domain name
  * 
  * Executes the 'GetRegLock' command on Enom's servers.
  * 
  * @param      string      $domainName     Must be active and belongs to your Enom account.
  * @return     bool        True if not locked, false otherwise. You should check for $this->isError if returned false, to make sure it's not an error flag not the registrar lock status.
  * @access     public
  * @see        setRegistrarLock
  */
  function getRegistrarLock($domainName)
  {
    $this->_setCommand('GetRegLock');
    
    if( !$this->_setParseDomain($domainName) )
    {
      $this->_addError("Invalid domain name $domainName");
      return $this->_returnError();
    }
    
    if( !$this->process() || '0' != $this->response['ErrCount'] )
    {
      return $this->_returnError();
    }
    else
    {
      return (1 == $this->response['RegLock']) ? true : false;
    }
  }

  
  /**
  * Set registrar lock status for a domain name
  * 
  * Executes the 'SetRegLock' command on Enom's servers.
  * 
  * @param      string      $domainName         Must be active and belongs to your Enom account.
  * @param      bool        $unlockRegistrar    If set to true, the domain will be unlocked.
  * @return     bool        True if succeed, false on fail.
  * @access     public
  * @see        getRegistrarLock
  */
  function setRegistrarLock($domainName, $unlockRegistrar)
  {
    $this->_setCommand('SetRegLock');

    if( !$this->_setParseDomain($domainName) )
    {
      $this->_addError("Invalid domain name $domainName");
      return $this->_returnError();
    }
    
    if($unlockRegistrar)
    {
      $this->addParam('UnlockRegistrar', '1');
    }
    else
    {
      $this->addParam('UnlockRegistrar', '0');
    }

    if( !$this->process() || '0' != $this->response['ErrCount'] )
    {
      return $this->_returnError();
    }
    elseif('Failed' == $this->reponse['RegistrarLock'])
    {
      $this->_addError('Failed to change Registrar Lock status');
      return $this->_returnError();
    }
    else
    {
      return true;
    }
  }

  
  /**
  * Get expiry date for a domain name
  * 
  * Executes the 'GetDomainExp' command on Enom's servers, to retrive the expiration date
  * of a domain name that is active and belongs to your Enom account.
  * 
  * @param      string      $domainName         Must be active and belongs to your Enom account.
  * @return     string      Expiration date, or false on fail
  * @access     public
  * @see        getDomainNameID
  */
  function getExpiryDate($domainName)
  {
    $this->_setCommand('GetDomainExp');
    
    if( !$this->_setParseDomain($domainName) )
    {
      $this->_addError("Invalid domain name $domainName");
      return $this->_returnError();
    }
    
    if( !$this->process() || '0' != $this->response['ErrCount'] )
    {
      return $this->_returnError();
    }
    else
    {
      return $this->response['ExpirationDate'];
    }
  }

  
  /**
  * Add ID Protect service to a domain name
  * 
  * Executes the 'PurchaseServices' command on Enom's servers, to add the ID Protect service to an active
  * domain name that belongs to your Enom Account.
  * 
  * @param      string      $domainName         Must be active and belongs to your Enom account.
  * @param      int         $numYears           Number of years to subscribe to ID Protect. Permitted values are 1 to 10.
  * @access     public
  * @return     string      Order ID, or false if failed.
  */
  function addIDProtectService($domainName, $numYears)
  {
    $this->_setCommand('PurchaseServices');

    if( !$this->_setParseDomain($domainName) )
    {
      $this->_addError("Invalid domain name $domainName");
      return $this->_returnError();
    }

    $this->addParam('Service', 'WPPS');
    
    $this->addParam('NumYears', $numYears);

    if( !$this->process() || '0' != $this->response['ErrCount'] )
    {
      return $this->_returnError();
    }
    elseif( !isset($this->response['OrderID']) )
    {
      $this->_addError('No order ID returned!');
      return $this->_returnError();
    }
    {
      return $this->response['OrderID'];
    }
  }

  
  /**
  * Get domain contacts for a domain name.
  * 
  * Executes the 'GetContacts' command on Enom's servers, to retrive the contacts information
  * for a domain name that is active and belongs to your Enom account.
  * 
  * @param      string      $domainName         Must be active and belongs to your Enom account.
  * @return     array       Associative array containing contacts information.
  * @access     public
  * @see        setDomainContacts
  */
  function getDomainContacts($domainName)
  {
    $this->_setCommand('GetContacts');
    
    if( !$this->_setParseDomain($domainName) )
    {
      $this->_addError("Invalid domain name $domainName");
      return $this->_returnError();
    }

    if( !$this->process() || '0' != $this->response['ErrCount'] )
    {
      return $this->_returnError();
    }

    $contacts = array();
    foreach($this->response as $key=>$val)
    {
      if( !preg_match('/^(BILLING|REGISTRANT|AUXBILLING|TECH|ADMIN)([a-z0-9]+)$/i', $key, $match) ) continue;

      switch( strtolower($match[1]) )
      {
        case 'billing':
          $contacts['billing'][$match[2]] = $val;
          break;

        case 'registrant':
          $contacts['registrant'][$match[2]] = $val;
          break;

        case 'auxbilling':
          $contacts['auxbilling'][$match[2]] = $val;
          break;

        case 'tech':
          $contacts['tech'][$match[2]] = $val;
          break;

        case 'admin':
          $contacts['admin'][$match[2]] = $val;
          break;

        default:
          break;
      }
    }

    return $contacts;
  }

  
  /**
  * Set domain contacts for a domain name.
  * 
  * Executes the 'Contacts' command on Enom's servers, to set the contacts information
  * for a domain name that is active and belongs to your Enom account.
  * 
  * @param      string      $domainName         Must be active and belongs to your Enom account.
  * @param      array       $contacts           Associative array containing contacts information.
  * @return     bool        True if succeed and false if failed.
  * @access     public
  * @see        getDomainContacts
  */
  function setDomainContacts($domainName, $contacts)
  {
    $this->_setCommand('Contacts');

    if( !$this->_setParseDomain($domainName) )
    {
      $this->_addError("Invalid domain name $domainName");
      return $this->_returnError();
    }

    if( isset($contacts['EmailAddress']) )
    {
      $contacts = array('registrant'=>$contacts);
    }
    foreach($contacts as $type=>$info)
    {
      switch($type)
      {
        case 'registrant':
          $typeName = 'Registrant';
          break;

        case 'auxbilling':
          $typeName = 'AuxBilling';
          break;

        case 'tech':
          $typeName = 'Tech';
          break;

        case 'admin':
          $typeName = 'Admin';
          break;

        default:
          break;
      }
      $this->addParam( $typeName . 'EmailAddress', $info['EmailAddress'] );
      $this->addParam( $typeName . 'Fax', $info['Fax'] );
      $this->addParam( $typeName . 'Phone', $info['Phone'] );
      $this->addParam( $typeName . 'Country', $info['Country'] );
      $this->addParam( $typeName . 'PostalCode', $info['PostalCode'] );
      if( $info['StateProvinceChoice'] == 'S' )
      {
        $this->addParam( $typeName . 'StateProvinceChoice', 'S' );
        $this->addParam( $typeName . 'StateProvince', $info['State'] );
      }
      elseif( $info['StateProvinceChoice'] == 'P' )
      {
        $this->addParam( $typeName . 'StateProvinceChoice', 'Province' );
        $this->addParam( $typeName . 'StateProvince', $info['Province'] );
      }
      else
      {
        $this->addParam( $typeName . 'StateProvinceChoice', 'Blank' );
        $this->addParam( $typeName . 'StateProvince', '' );
      }
      $this->addParam( $typeName . 'City', $info['City'] );
      $this->addParam( $typeName . 'Address1', $info['Address1'] );
      $this->addParam( $typeName . 'Address2', $info['Address2'] );
      $this->addParam( $typeName . 'LastName', $info['LastName'] );
      $this->addParam( $typeName . 'FirstName', $info['FirstName'] );
      $this->addParam( $typeName . 'JobTitle', $info['JobTitle'] );
      $this->addParam( $typeName . 'OrganizationName', $info['OrganizationName'] );
    }

    if( !$this->process() || '0' != $this->response['ErrCount'] )
    {
      return $this->_returnError();
    }

    return true;
  }

  
  /**
  * Get name servers for a domain name.
  * 
  * Executes the 'GetDNS' command on Enom's servers, to retrive the name servers
  * for a domain name that is active and belongs to your Enom account.
  * 
  * @param      string      $domainName         Must be active and belongs to your Enom account.
  * @return     array       An array containing name servers. If using Enom's name servers, the array will be empty.
  * @access     public
  * @see        getNameServers
  */
  function getNameServers($domainName)
  {
    $this->_setCommand('GetDNS');

    if( !$this->_setParseDomain($domainName) )
    {
      $this->_addError("Invalid domain name $domainName");
      return $this->_returnError();
    }

    if( !$this->process() || '0' != $this->response['ErrCount'] )
    {
      return $this->_returnError();
    }
    
    $nameServers = array();
    
    if( 'default' == strToLower($this->response['UseDNS']) )
    {
      return $nameServers;
    }
    
    for($i=1; $i<=$this->response['NSCount']; $i++)
    {
      $nameServers[] = $this->response[ ('DNS'.$i) ];
    }
    
    return $nameServers;
  }

  
  /**
  * Set name servers for a domain name.
  * 
  * Executes the 'ModifyNS' command on Enom's servers, to set the name servers
  * for a domain name that is active and belongs to your Enom account.
  * 
  * @param      string      $domainName         Must be active and belongs to your Enom account.
  * @param      array       $nameservers        Array containing name servers. If not set, default Enom name servers will be used.
  * @return     bool        True if succeed and false if failed.
  * @access     public
  * @see        getNameServers
  */
  function setNameServers($domainName, $nameServers = null)
  {
    $this->_setCommand('ModifyNS');
    
    if( !$this->_setParseDomain($domainName) )
    {
      $this->_addError("Invalid domain name $domainName");
      return $this->_returnError();
    }

    if( !is_null($nameServers) )
    {
      for($i=0; $i<count($nameServers); $i++)
      {
        $this->addParam( 'NS'.($i+1) , $nameServers[$i] );
      }
    }
    else
    {
      $this->addParam('UseDNS', 'Default');
    }

    if( !$this->process() || '0' != $this->response['ErrCount'] )
    {
      return $this->_returnError();
    }

    return true;
  }

  
  /**
  * Get email forwarders for a domain name.
  * 
  * Executes the 'GetForwarding' command on Enom's servers, to get email forwarders
  * for a domain name that is active and belongs to your Enom account.
  * 
  * @param      string      $domainName         Must be active and belongs to your Enom account.
  * @return     array       Associative array containing 'Email account' as the key, and 'Forward To' as the value.
  * @access     public
  * @see        setEmailForwarders
  */
  function getEmailForwarders($domainName)
  {
    $this->_setCommand('GetForwarding');

    if( !$this->_setParseDomain($domainName) )
    {
      $this->_addError("Invalid domain name $domainName");
      return $this->_returnError();
    }

    if( !$this->process() || '0' != $this->response['ErrCount'] )
    {
      return $this->_returnError();
    }

    $emailForwarders = array();
    for($i=1; $i<=$this->response['EmailCount']; $i++)
    {
      $emailForwarders[ $this->response[('Username'.$i)] ] = $this->response[('ForwardTo'.$i)];
    }
    
    return $emailForwarders;
  }

  
  /**
  * Set email forwarders for a domain name.
  * 
  * Executes the 'Forwarding' command on Enom's servers, to set email forwarders
  * for a domain name that is active and belongs to your Enom account.
  * 
  * @param      string      $domainName         Must be active and belongs to your Enom account.
  * @param      array       $emailForwarders    Associative array containing 'Email account' as the key, and 'Forward To' as the value.
  * @return     bool        True if succeed and False if failed.
  * @access     public
  * @see        getEmailForwarders
  */
  function setEmailForwarders($domainName, $emailForwarders)
  {
    $this->_setCommand('Forwarding');

    if( !$this->_setParseDomain($domainName) )
    {
      $this->_addError("Invalid domain name $domainName");
      return $this->_returnError();
    }

    $i=0;
    foreach($emailForwarders as $address=>$forwardTo)
    {
      $this->addParam( 'Address' . ++$i, $address);
      $this->addParam( 'ForwardTo' . $i, $forwardTo);
    }


    if( !$this->process() || '0' != $this->response['ErrCount'] )
    {
      return $this->_returnError();
    }

    return true;
  }

  
  /**
  * Get domain hosts (records) for a domain name.
  * 
  * Executes the 'GetHosts' command on Enom's servers, to get domain hosts (records)
  * for a domain name that is active and belongs to your Enom account.
  * 
  * @param      string      $domainName         Must be active and belongs to your Enom account.
  * @return     array       Associative array containing all Hosts being set. Array keys are name, type, address and pref.
  * @access     public
  * @see        setDomainHosts
  */
  function getDomainHosts($domainName)
  {
    $this->_setCommand('GetHosts');

    if( !$this->_setParseDomain($domainName) )
    {
      $this->_addError("Invalid domain name $domainName");
      return $this->_returnError();
    }
    
    if( !$this->process() || '0' != $this->response['ErrCount'] )
    {
      return $this->_returnError();
    }
    if( !isset($this->response['HostCount']) )
    {
      $this->_addError('Couldn\'t parse returned values');
      return $this->_returnError();
    }
    $domainHosts = array();
    for($i=1; $i<=$this->response['HostCount']; $i++)
    {
      $domainHosts[] = array('name'=>$this->response[ ('HostName'.$i) ],
                             'type'=>$this->response[ ('RecordType'.$i) ],
                             'address'=>$this->response[ ('Address'.$i) ],
                             'pref'=>$this->response[ ('MXPref'.$i) ]
                            );
    }
    
    return $domainHosts;
  }

  
  /**
  * Set domain hosts (records) for a domain name.
  * 
  * Executes the 'SetHosts' command on Enom's servers, to set domain hosts (records)
  * for a domain name that is active and belongs to your Enom account.
  * 
  * @param      string      $domainName         Must be active and belongs to your Enom account.
  * @param      array       $domainHosts        Associative array containing all Hosts to set. Array keys are name, type, address and pref.
  * @return     bool        True if succeed and False if failed.
  * @access     public
  * @see        getDomainHosts
  */
  function setDomainHosts($domainName, $domainHosts)
  {
    $this->_setCommand('SetHosts');

    if( !$this->_setParseDomain($domainName) )
    {
      $this->_addError("Invalid domain name $domainName");
      return $this->_returnError();
    }

    for($i=1; $i<=count($domainHosts); $i++)
    {
      $this->addParam( 'HostName'.$i , $domainHosts[$i-1]['name'] );
      $this->addParam( 'RecordType'.$i , $domainHosts[$i-1]['type'] );
      $this->addParam( 'Address'.$i , $domainHosts[$i-1]['address'] );
      if( isset($domainHosts[$i-1]['pref']) )
      {
        $this->addParam( 'MXPref'.$i , $domainHosts[$i-1]['pref'] );
      }
    }
    
    if( !$this->process() || '0' != $this->response['ErrCount'] )
    {
      return $this->_returnError();
    }

    return true;
  }
  
  
  /**
  * Get the current name-my-phone settings.
  * 
  * Executes the 'GetDomainPhone' command on Enom's servers, to get current name-my-phone settings
  * for a domain name that is active and belongs to your Enom account.
  * 
  * @param      string      $domainName         Must be valid and belongs to your Enom account.
  * @return     array       Associative array containing name-my-phone settings.
  * @access     public
  * @see        setNameMyPhone
  */
  function getNameMyPhone($domainName)
  {
    $this->_setCommand('GetDomainPhone');
    
    if( !$this->_setParseDomain($domainName) )
    {
      $this->_addError("Invalid domain name $domainName");
      return $this->_returnError();
    }

    if( !$this->process() || '0' != $this->response['ErrCount'] )
    {
      return $this->_returnError();
    }

    return $this->response;
  }
  
  
  /**
  * Set name-my-phone settings for a domain name.
  * 
  * Executes the 'SetDomainPhone' command on Enom's servers, to set name-my-phone settings
  * for a domain name that belongs to your Enom account.
  * 
  * @param      string      $domainName     Must be valid and belongs to your Enom account.
  * @param      string      $phone          Phone number. Required format is +CountryCode.PhoneNumber, where CountryCode and PhoneNumber use only numeric characters.
  * @param      string      $hostname       Name for your host (default is phone).
  * @param      int         $serviceID      ID of service (default is AT and T).
  * @param      int         $templateID     Template used to view the service. (default is Western template).
  * @param      bool        $emailAlias     Flag to set up email forwarding alias.
  * @param      string      $ccEmail1       CC to email #1 (only if $emailAlias is enabled).
  * @param      string      $ccEmail2       CC to email #2 (only if $emailAlias is enabled).
  * @param      string      $ccEmail3       CC to email #3 (only if $emailAlias is enabled).
  * @return     bool        True if succeed and False if failed.
  * @access     public
  * @see        setNameMyPhone
  */
  function setNameMyPhone($domainName, $phone, $hostName = 'phone', $serviceID = NAMEMYPHONE_SERVICE_ATANDT, $templateID = NAMEMYPHONE_TEMPLATE_WESTERN, $emailAlias = false, $ccEmail1 = '', $ccEmail2 = '', $ccEmail3 = '')
  {
     $this->_setCommand('SetDomainPhone');

    if( !$this->_setParseDomain($domainName) )
    {
      $this->_addError("Invalid domain name $domainName");
      return $this->_returnError();
    }

    $this->addParam('Phone', $phone);
    $this->addParam('HostName', $hostName);
    $this->addParam('ServiceID', $serviceID);
    $this->addParam('TemplateID', $templateID);
    if($emailAlias)
    {
        $this->addParam('EmailAlias', 'on');
        if( '' != $ccEmail1 ) { $this->addParam('ccEmail1', $ccEmail1); }
        if( '' != $ccEmail2 ) { $this->addParam('ccEmail2', $ccEmail2); }
        if( '' != $ccEmail3 ) { $this->addParam('ccEmail3', $ccEmail3); }
    }
    else
    {
        $this->addParam('EmailAlias', 'off');
    }


    if( !$this->process() || '0' != $this->response['ErrCount'] )
    {
      return $this->_returnError();
    }

    return true;
  }
  
  
  /**
  * Get domain name ID for a domain name.
  * 
  * Executes the 'GetDomainNameID' command on Enom's servers, to get the domain name ID number
  * for a domain name that belongs to your Enom account. Can be used to verify that domain name
  * belongs to your Enom's account.
  * 
  * @param      string      $domainName     Must be valid and belongs to your Enom account.
  * @return     long        Domain name ID number, or false if failed.
  * @access     public
  */
  function getDomainNameID($domainName)
  {
    $this->_setCommand('GetDomainNameID');


    if( !$this->_setParseDomain($domainName) )
    {
      $this->_addError("Invalid domain name $domainName");
      return $this->_returnError();
    }

    if( !$this->process() || '0' != $this->response['ErrCount'] )
    {
      return false;
    }

    return $this->response['DomainNameID'];
  }

}

/* NameMyPhone Service & Template IDs Definitions */
define('NAMEMYPHONE_SERVICE_NEXTEL', '1');
define('NAMEMYPHONE_SERVICE_SPRINT', '2');
define('NAMEMYPHONE_SERVICE_VERIZON', '4');
define('NAMEMYPHONE_SERVICE_ATANDT', '5');
define('NAMEMYPHONE_SERVICE_VOICESTREAM', '8');
define('NAMEMYPHONE_SERVICE_QUEST', '11');
define('NAMEMYPHONE_SERVICE_CINGULAR', '12');
define('NAMEMYPHONE_SERVICE_TMOBILE', '13');
define('NAMEMYPHONE_SERVICE_OTHER', '99');
define('NAMEMYPHONE_TEMPLATE_WESTERN', '1');
define('NAMEMYPHONE_TEMPLATE_TECHNO', '2');
define('NAMEMYPHONE_TEMPLATE_MODERN', '3');
define('NAMEMYPHONE_TEMPLATE_LETTERHEAD', '4');
define('NAMEMYPHONE_TEMPLATE_HANDHELD', '5');
define('NAMEMYPHONE_TEMPLATE_BUSINESS', '6');

?>