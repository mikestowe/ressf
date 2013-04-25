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
require_once('base/validators.php');
require_once('base/extenders.php');
require_once('plugins/validators.php');
require_once('plugins/extenders.php');

/**
 * @package  ressf
 * @category ressf
 */
class ressf {
    use ressf\base\validators;
    use ressf\base\extenders;
    use ressf\plugins\validators;
    use ressf\plugins\extenders;
    
    /**
     *  Display type
     * @var string
     */
    private $detect = null;
    
    /**
     * Extender Actions
     * @var array
     */
    private $actions = array(
        'beforeRender' => array(),
        'afterRender'  => array(),
    );
    
    /**
     * View Contents
     * @var string
     */
    private $view;
    
    /**
     * Kill Process Switch
     * @var bool
     */
    private $killProcess = false;
    
    /**
     * Construct
     * @return \ressf
     */
    public function __construct()
    {
        $this->tags = array_merge($this->baseTags, $this->tags);
        $this->extenders = array_merge($this->baseExtenders, $this->extenders);
    }
    
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
        $this->view = $view;
        
        // Handle Extenders
        $this->handleExtenders('retrieve');
        $this->handleExtenders('beforeRender');
        
        // Output now and escape (used by cache)
        if ($this->killProcess) {
            return $this->view;
        }
        
        // Handle Tags
        $tags = $this->tags;
        unset($tags[array_search($this->detect(), $tags)]);
        
        foreach($tags as $tag) {
            $this->view = preg_replace('/\['.$tag.'\].+\[\/'.$tag.'\]/', '', $this->view);
        }
        
        $this->view = preg_replace('/\[(\/)?' . $this->detect() . '\]/', '', $this->view);
        
        // Handle Extenders
        $this->handleExtenders('afterRender');
        
        return $this->view;
    }
    
    /**
     * Handle Extenders
     * @param string
     * @return string
     */
    private function handleExtenders($action)
    {
        if ($action == 'retrieve') {
            preg_match_all('/\[ressf:([A-Za-z]+)=([^\]]+)]/', $this->view, $matches);
            
            for ($i = 0; $i < count($matches[0]); $i++) {
                $this->{'set' . ucfirst($matches[1][$i])}($matches[2][$i]);
                $this->view = str_replace($matches[0][$i], '', $this->view);
            }
        }
        
        if ($action == 'beforeRender') {
            foreach ($this->actions['beforeRender'] as $function) {
                $function();
            }
        }
        
        if ($action == 'afterRender') {
            foreach ($this->actions['afterRender'] as $function) {
                $function();
            }
        }
    }
    
    /**
     * Add Extender Action
     * @param string
     * @param mixed
     * @return \ressf
     */
    public function addAction($hook, $function)
    {
        $this->actions[$hook][] = $function;
        return $this;
    }
    
    /**
     * Check User Agent
     * @param string
     * @return bool
     */
    public static function checkUserAgent($agent, $textOnly = false)
    {
        if (is_array($agent)) {
            $agent = implode('|', $agent);
        }
        
        if (!isset($_SERVER['HTTP_USER_AGENT'])) {
            return false;
        }
        
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        
        if ($textOnly) {
            $userAgent = str_replace(array(' ', '.', '-', '/',), '', $userAgent);
        }
        
        return (bool) preg_match('/' . $agent . '/i', $userAgent);
    }
    
        
    /**
     * Allow methods to be called statically
     * @param string
     * @param array
     * @return mixed
     */
    static public function convertStatic($function, $params) {
        $ressf = new ressf();
        $ressf->detect();
        return $ressf->$function($params);
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
            return self::checkUserAgent($match[1], true);
        }
        
        throw new \Exception('The method "' . $function . '" does not exist.');
    }
}