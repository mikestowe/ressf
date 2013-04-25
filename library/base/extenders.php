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
 * Base Extender Trait
 * @package  ressf
 * @category ressf/base
 */
class extenders
{
    /**
     * Base Extenders (default)
     * @var array
     */
    public $baseExtenders = array(
        'apcCache' => false,
    );
    
    /**
     * Set Cache Extender
     * @param string
     * @return void
     */
    public function setApcCache($cache = 'false') {
        $base = $this->ressf;
        
        $base->setExtenders('cache', array(
            'doCache' => ($cache != 'false' && $cache != '0'),
            'md5'     => $base->detect() . '_' . md5($base->getView()),
        ));
        
        ressf::addAction('beforeRender', function() use ($base) {
            $config = $base->getExtenders('cache');
            if ($config['doCache']) {
                $cachedView = apc_fetch($config['md5'], $isCached);
                if ($isCached) {
                    $base->setView($cachedView);
                    $base->setKillProcess(true);
                }
            }
        });
        
        ressf::addAction('afterRender', function() use ($base) {
            $config = $base->getExtenders('cache');
            if ($config['doCache']) {
                apc_add($config['md5'], $base->getView());
            }
        });
    }
}
