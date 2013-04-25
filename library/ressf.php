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
require_once('base/validators.php');
require_once('base/extenders.php');
require_once('plugins/validators.php');
require_once('plugins/extenders.php');

use ressf\plugins\extenders;
use ressf\plugins\validators;

/**
 * @package  ressf
 * @category ressf
 */
class ressf {
    
    /**
     * Extenders Class
     * @var ressf\plugins\extenders
     */
    private $extendersClass;
    
    /**
     * Validators Class
     * @var ressf\plugins\validators
     */
    private $validatorsClass;
    
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
     * Tags
     * @var array
     */
    private $tags;
    
    /**
     * Extenders
     * @var array
     */
    private $extenders;
    
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
        $this->extendersClass = new extenders($this);
        $this->validatorsClass = new validators($this);
        
        $this->tags = array_merge($this->validatorsClass->baseTags, $this->validatorsClass->tags);
        $this->extenders = array_merge($this->extendersClass->baseExtenders, $this->extendersClass->extenders);
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
                $this->getExtendersClass()->{'set' . ucfirst($matches[1][$i])}($matches[2][$i]);
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
        return $ressf->getValidatorsClass()->$function($params);
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
    
    /**
     * Get the Validators Class
     * @return ressf\plugins\validators;
     */
    public function getValidatorsClass()
    {
        return $this->validatorsClass;
    }
    
    /**
     * Get the Extenders Class
     * @return ressf\plugins\extenders
     */
    public function getExtendersClass()
    {
        return $this->extendersClass;
    }
    
    /**
     * Return the View
     * @return string
     */
    public function getView()
    {
        return $this->view;
    }
    
    /**
     * Set the View
     * @param string
     * @return \ressf
     */
     public function setView($view)
     {
         $this->view = $view;
         return $this;
     }
    
    /**
     * Set Kill Process Switch
     * @param bool
     * @return \ressf
     */
    public function setKillProcess($bool = true)
    {
        $this->killProcess = $bool;
        return $this;
    }
    
    /**
     * Set an Extender
     * @param string
     * @param mixed
     * @return \ressf
     */
    public function setExtenders($key, $value)
    {
        $this->extenders[$key] = $value;
        return $this;
    }
    
    /**
     * Get the Extender
     * @param string
     * @return mixed
     */
    public function getExtenders($key)
    {
        return $this->extenders[$key];
    }
    
}