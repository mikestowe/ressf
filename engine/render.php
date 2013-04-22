<?php
require_once('validators.php');
require_once('extenders.php');

class ressf {
    use ressf\validators;
    use ressf\extenders;
    
    // Display type
    private $detect = null;
    
    /**
     * Detect Screen Type
     * @return sting
     */
    public function detect()
    {
        if (!$this->detect) {
            $tags = $this->tags;
            array_shift($tags); // remove desktop
            
            foreach ($this->tags as $tag) {
                if ($this->{'is' . ucfirst($tag)}()) {
                    $this->detect = $tag;
                    break;
                }
            }
            
            if (!$this->detect) {
                $this->detect = 'desktop';
            }
        }
        
        return $this->detect;
    }
    
    /**
     * Render View
     * @param string
     * @return string
     */
    public function render($view)
    {
        // Handle Extenders
        $view = $this->handleExtenders($view);
        
        // Get from Cache
        if ($this->extenders['cache'] && $this->extenders['cacheType'] == 'apc') {
            die('cache');
            $cacheKey = $this->detect() . '_' . md5($view);
            $cachedView = apc_fetch($cacheKey, $isCached);
            
            if ($isCached) {
                return $cachedView;
            }
        }
        
        // Handle Tags
        $tags = $this->tags;
        unset($tags[array_search($this->detect(), $tags)]);
        
        foreach($tags as $tag) {
            $view = preg_replace('/\['.$tag.'\].+\[\/'.$tag.'\]/', '', $view);
        }
        
        $view = preg_replace('/\[(\/)?' . $this->detect() . '\]/', '', $view);
        
        // Save in Cache
        if ($this->extenders['cache'] && $this->extenders['cacheType'] == 'apc') {
            apc_add($cacheKey, $view);
        }
        
        return $view;
    }
    
    /**
     * Handle Extenders
     * @param string
     * @return string
     */
    private function handleExtenders($view)
    {
        preg_match_all('/\[ressf:([a-z]+)=([^\]]+)]/', $view, $matches);
        
        for ($i = 0; $i < count($matches[0]); $i++) {
            $this->{'set' . ucfirst($matches[1][$i])}($matches[2][$i]);
            $view = str_replace($matches[0][$i], '', $view);
        }
        
        return $view;
    }
    
    /**
     * Check User Agent
     * @param string
     * @return bool
     */
    public function checkUserAgent($agent)
    {
        if (is_array($agent)) {
            $agent = implode('|', $agent);
        }
        
        if (!isset($_SERVER['HTTP_USER_AGENT'])) {
            return false;
        }
        
        return (bool) preg_match('/' . $agent . '/i', $_SERVER['HTTP_USER_AGENT']);
    }
    
    /**
     * Magic Call Method
     * @param string
     * @param array
     * @return mixed
     */
    public function __call($function, $params)
    {
        if (preg_match('/^is([A-Z][A-Za-z]+)$/', $function, $match)) {
            return $this->checkUserAgent($match[1]);
        }
    }
    
    
}
