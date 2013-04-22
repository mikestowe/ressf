<?php
namespace ressf;

trait extenders
{
    private $extenders = array(
        'cache' => false,
        'cacheType' => 'apc',
    );
    
    public function setCache($cache = 'false')
    {
        $this->extenders['cache'] = ($cache != 'false' && $cache != '0');
    }
    
    public function setCacheType($type = 'apc')
    {
        $this->extenders['cacheType'] = $type;
    }
    
}
?>
