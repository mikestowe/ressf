<?php
namespace ressf\base;

trait validators
{
    /**
     * Base Validators
     * @var array
     */
    private $baseTags = array(
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
            return self::convertStatic(__FUNCTION__, array());
        }
        
        return $this->detect == 'desktop';
    }
    
    /**
     * Phone Validator
     * @return bool
     */
    public function isMobile()
    {
        return self::checkUserAgent(
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
        return self::checkUserAgent(
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
        return self::checkUserAgent(
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
        return self::checkUserAgent(
            array(
                'xbox',
                'psp',
                'playstation',
                'nintendo',
            )
        );
    }
}
