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
     * Tablet Validator
     * @return bool
     */
    public function isTablet()
    {
        return $this->checkUserAgent(
            array(
                'ipad',
            )
        );
    }
    
    /**
     * Mobile Validator
     * @return bool
     */
    public function isMobile()
    {
        return $this->checkUserAgent(
            array(
                'iphone',
                'ipod',
                'android',
                'windows mobile',
                'windows ce',
                'symbian',
            )
        );
    }
    
    /**
     * TV Validator
     * @return bool
     */
    public function isTv()
    {
        return $this->checkUserAgent(
            array(
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
            )
        );
    }
}
