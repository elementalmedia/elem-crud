<?php
/**
 * Elemental Classes
 *
 * @link      
 * @copyright Copyright (c) 
 * @license   
 */

namespace ElemCrud\Controller;

use Zend\Mvc\Controller\AbstractActionController;

abstract class AbstractCrudController extends AbstractActionController
{
    protected $module;
    
    public function __construct()
    {
        $this->initModule();
    }
    
    
    protected function initModule(){
        $this->module = new Module($this);
    }
    
    public function module(){
        return $this->module;
    }
    
    public function createAction()
    {
    
    }
    
    public function readAction()
    {
    }
    
    public function updateAction()
    {
    }
    
    public function updateMultiple()
    {
    
    }
    
    public function deleteAction()
    {
    
    }
    
    public function deleteMultipleAction()
    {
    }
    
    protected function redirectToModule(){
        $this->redirect()->toRoute($this->module()->getDefaultRoute());
    }
}