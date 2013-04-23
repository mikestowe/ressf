<?php
namespace ressf\base;

trait validators
{
    private $baseTags = array(
        'desktop',
        'tablet',
        'mobile',
        'tv',
        'game'
    );
    
    public function isDesktop()
    {
        return $this->detect == 'desktop';
    }
    
    public function isTablet()
    {
        return $this->checkUserAgent(
            array(
                'ipad',
            )
        );
    }
    
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
    
    public function isTv()
    {
        return $this->checkUserAgent(
            array(
                'vizio',
                'lg',
            )
        );
    }
    
    public function isGame()
    {
        return $this->checkUserAgent(
            array(
                'xbox',
            )
        );
    }
}
