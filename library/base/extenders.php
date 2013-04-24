<?php
/**
 * ressf
 * The open source RESS Framework for content manipulation
 * 
 * @author  Mike Stowe
 * @link    http://www.mikestowe.com
 * @link    https://github.com/mikestowe/ressf
 * @license GPL
 */

namespace ressf\base;

/**
 * Base Extender Trait
 * @package  ressf
 * @category ressf/base
 */
trait extenders
{
    /**
     * Base Extenders (default)
     * @var array
     */
    private $baseExtenders = array(
        'apcCache' => false,
    );
    
    /**
     * Set Cache Extender
     * @param string
     * @return void
     */
    public function setApcCache($cache = 'false') {
        $base = $this;
        
        $this->extenders['cache'] = array(
            'doCache' => ($cache != 'false' && $cache != '0'),
            'md5'     => $base->detect() . '_' . md5($base->view),
        );
        
        self::addAction('beforeRender', function() use ($base) {
            if ($base->extenders['cache']['doCache']) {
                $cachedView = apc_fetch($base->extenders['cache']['md5'], $isCached);
                if ($isCached) {
                    $base->view = $cachedView;
                    $base->killProcess = true;
                }
            }
        });
        
        self::addAction('afterRender', function() use ($base) {
            if ($base->extenders['cache']['doCache']) {
                apc_add($base->extenders['cache']['md5'], $base->view);
            }
        });
    }
}
