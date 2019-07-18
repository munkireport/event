<?php

use Symfony\Component\Yaml\Yaml;

/**
 * Event module class
 *
 * @package munkireport
 * @author AvB
 **/
class Event_controller extends Module_controller
{
    private $conf;
    
    public function __construct()
    {
        if (! $this->authorized()) {
            $out['items'] = array();
            $out['reload'] = true;
            $out['error'] = 'Session expired: please login';
            $obj = new View();
            $obj->view('json', array('msg' => $out));

            die();
        }
        // Add local config
        configAppendFile(__DIR__ . '/config.php', 'event');
        $this->module_path = dirname(__FILE__) .'/';

        try {
            $this->conf = Yaml::parseFile(conf('event')['config_path']);
        } catch (\Exception $e) {
           $this->conf = [];
        }
    }

    public function index()
    {
        echo "You've loaded the Event module!";
    }
    
    private function hasFilter()
    {
        return array_key_exists('filter', $this->conf) && 
            is_array($this->conf['filter']);
    }
    
    private function createFilter($query, $filter)
    {
      foreach ($filter as $module => $types) {
        if($types){
          $where[] = ['module', $module];
          foreach ($types as $type) {
            $where[] = ['type', '<>', $type];
          }
          $query->where(function($query) use ($where){
            $query->where($where);
          }, NULL, NULL, 'AND NOT'); // <- little hack to get NOT (Subquery)
        }
        else{
          $query->where('module', '<>', $module);
        }
      }
      
      return $query;
    }

    /**
     * Get Event
     *
     * @author AvB
     **/
    public function get($limit = 0)
    {
        $queryobj = Event_model::select(
                'event.serial_number', 'module', 'type', 'msg', 'data',
                'event.timestamp', 'machine.computer_name'
            )
            ->join('machine', 'machine.serial_number', '=', 'event.serial_number') 
            ->filter()
            ->orderBy('event.timestamp', 'desc');
        if($limit){
            $queryobj->limit($limit);
        }
        if($this->hasFilter()){
          $this->createFilter($queryobj, $this->conf['filter']);
        }
        // dumpQuery($queryobj);
        $obj = new View();
        $obj->view('json', [
          'msg' => [
            'error' => '', 
            'items' => $queryobj->get()->toArray(),
          ]
        ]);
    }
} // END class Event_controller
