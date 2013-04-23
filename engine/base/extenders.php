<?php
namespace ressf\base;

trait extenders
{
    private $baseExtenders = array(
        'cache' => false,
        'cacheType' => 'apc',
    );
    
    public function setCache($cache = 'false') {
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
    
    
    
    
    public function sestCache($cache = 'false')
    {
        $this->extenders['cache'] = ($cache != 'false' && $cache != '0');
    }
    
    public function setCacheType($type = 'apc')
    {
        $this->extenders['cacheType'] = $type;
    }
    
}
