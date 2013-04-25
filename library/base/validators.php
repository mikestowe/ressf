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

namespace ressf\base;
use \ressf;

/**
 * Base Validator Trait
 * @package  ressf
 * @category ressf/base
 */
class validators
{
    /**
     * Base Validators
     * @var array
     */
    public $baseTags = array(
        'desktop',
        'mobile',
        'tablet',
        'tv',
        'game'
    );
    
    /**
     * Desktop Validator
     * @return bool
     */
    public function isDesktop()
    {
        if (!isset($this)) {
            return ressf::convertStatic(__FUNCTION__, array());
        }
        
        return $this->ressf->detect() == 'desktop';
    }
    
    /**
     * Phone Validator
     * @return bool
     */
    public function isMobile()
    {
        return ressf::checkUserAgent(
            array(
                'mobile',
                'iphone',
                'ipod',
                'android(?=.*mobile)',
                'blackberry',
                'iemobile',
                'windows mobile',
                'windows ce',
                'symbian',
                'hpwOS',
                'webOS',
                'fennec',
                'minimo',
                'opera mini',
                'opera mobi',
                'blazer',
                'dolfin',
                'dolphin',
                'skyfire',
                'zune',
            )
        );
    }
    
    /**
     * Tablet Validator
     * @return bool
     */
    public function isTablet()
    {
        return ressf::checkUserAgent(
            array(
                'ipad',
                'kindle',
                'android(?!.*mobile)',
            )
        );
    }
    
    /**
     * TV Validator
     * @return bool
     */
    public function isTv()
    {
        return ressf::checkUserAgent(
            array(
                'tv', // smart-tv, appletv, googletv
                'roku',
                'dvp',
                'rca',
                'vizio',
                'lg',
            )
        );
    }
    
    /**
     * Game Validator
     * @return bool
     */
    public function isGame()
    {
        return ressf::checkUserAgent(
            array(
                'xbox',
                'psp',
                'playstation',
                'nintendo',
            )
        );
    }
}
