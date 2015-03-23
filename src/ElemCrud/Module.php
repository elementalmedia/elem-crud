<?php
/**
 * Elemental Classes
 *
 * @link      
 * @copyright Copyright (c) 
 * @license   
 */

namespace ElemCrud;

use ElemCrud\Controller\AbstractCrudController;
use Zend\Mvc\Router\Http\TreeRouteStack;
use Zend\Http\Request;

class Module{
    
    protected $classname;
    protected $namespace;
    protected $controller;
    protected $module;
    protected $config; 
    protected $configRoutes;
    protected $routes;
    protected $defaultRoute;
    
    public function __construct(AbstractCrudController $controller){
        $this->setController($controller);
    }
    
    public function setController(AbstractCrudController $controller){
        $this->controller = $controller;
        $this->initProperties();
    }
    
    protected function initProperties(){
        $this->initNamespace();
        $this->initName();
        $this->initModule();
        $this->initConfig();
        $this->initConfigRoutes();
        $this->initRoutes();
        $this->initDefaultRoute();
    }
    
    protected function initNamespace(){
        $namespace = get_class($this->controller);
        $namespace = explode('\\',$namespace);
        $namespace = reset($namespace);
        $this->namespace = $namespace;
    }
    
    protected function initClassname(){
        $this->classname = $this->namespace.'\\Module'; //Stablished by Elemental Media convention at March 16th 2015
    }
    
    protected function initModule(){
        $class = $this->classname;
        $this->module = new $class();
    } 
    
    protected function initConfig(){
        $this->config = $this->module->getConfig();
    }
    
    protected function initConfigRoutes(){
        $this->configRoutes = $this->config['router']['routes']; //Stablished by Elemental convention at March 16th 2015
    }
    
    /**
     * Initializes a TreeRouteStack what to query for Module routing
     * @link http://framework.zend.com/manual/2.0/en/modules/zend.mvc.routing.html
     * @see last pharagraf before "Router Types" Section
     */
    protected function initRoutes(){
        $this->routes = new TreeRouteStack();
        $this->routes->addRoutes($this->configRoutes); 
    }
    
    private function getRouteNameFromUri($uri){
        $request = new Request();
        $request->setUri('\/'.preg_replace('/$\/+/','',$uri));
        $match = $this->routes->match($request);
        return $match->getMatchedRouteName();
    }
    
    protected function getRoute($routeName){
        // If does not exist any route matching the lowercase module name, we'll look for a route patch match
        if (!$this->routes->hasRoute($routeName))
            $routeName = $this->getRouteNameFromUrl($routeName);
        
        // If no url matched the module name as path, we'll do a last try, looking for a child route
        if (!$this->routes->hasRoute($routeName)){
            // First we'll locate (through assemble, cause exist no direct way to get a child route)...
            $uri = $this->routes->assemble($params = array(), $routeName);
            //..to later build the real route name matching from url, as we previously done
            $routeName = $this->getRouteNameFromUri($uri);
        }
        return $this->routes->getRoute($routeName);
    }
    
    /**
     * Retrieves the route intended to be the default according to just one convention:
     * - Modules default route path has to match the lowercase name for the module
     * 
     */
    protected function initDefaultRoute(){
        $routeName = strtolower($this->namespace);
        $this->defaultRoute = $this->getRoute($routeName);
    }
    
 /**
     * @return the $name
     */
    public function getName()
    {
        return $this->name;
    }

 /**
     * @return the $namespace
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

 /**
     * @return the $controller
     */
    public function getController()
    {
        return $this->controller;
    }

 /**
     * @return the $config
     */
    public function getConfig()
    {
        return $this->config;
    }

 /**
     * @return the $routes
     */
    public function getRoutes()
    {
        return $this->routes;
    }

 /**
     * @return the $defaultRoute
     */
    public function getDefaultRoute()
    {
        return $this->defaultRoute;
    }

    
    
    
}