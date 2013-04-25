<?php
/**
 * ressf
 * The open source RESS Framework for content manipulation
 * 
 * 5.3 Version - 
 * This version of ressf is designed to be backwards compatible
 * for users not runnning PHP 5.4.  There are no warranties expressed
 * or implied, and users should understand that this version is not 
 * updated as frequently or tested as thoroughly.  As such, it is 
 * highly recommended to upgrade to the 5.4 version as soon as
 * practical.
 * 
 * @author  Mike Stowe
 * @link    http://www.mikestowe.com
 * @link    https://github.com/mikestowe/ressf
 * @license GPL
 */

namespace ressf\plugins;
use ressf\base\validators as baseValidators;

/**
 * User Defined Validators Trait
 * @package  ressf
 * @category ressf/plugins
 */
class validators extends baseValidators
{
    protected $ressf;
    
    public function __construct($ressf)
    {
        $this->ressf = $ressf;
    }
    
    /**
     * Custom Validator Tags
     * @param array
     */
    public $tags = array(
        
    );
}