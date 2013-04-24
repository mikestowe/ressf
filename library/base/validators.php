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
        'phone',
        'tablet',
        'mobile',
        'tv',
        'game'
    );
    
    /**
     * Desktop Validator
     * @return bool
     */
    public function isDesktop()
    {
        return $this->detect == 'desktop';
    }
    
    /**
     * Phone Validator
     * @return bool
     */
    public function isPhone()
    {
        return $this->checkUserAgent(
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
                'webOS'
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
        return $this->checkUserAgent(
            array(
                'ipad',
                'kindle',
                'android(?!.*mobile)',
            )
        );
    }
    
    /**
     * Mobile Validator
     * @return bool
     */
    public function isMobile()
    {
        return $this->isPhone() || $this->isTablet();
    }
    
    /**
     * TV Validator
     * @return bool
     */
    public function isTv()
    {
        return $this->checkUserAgent(
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
        return $this->checkUserAgent(
            array(
                'xbox',
                'psp',
                'playstation',
                'nintendo',
            )
        );
    }
}
