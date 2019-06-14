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
    
    private function createFilter($filter)
    {
      $filterData = [
        'sql' => '',
        'values' => []
      ];
      foreach ($filter as $module => $types) {
        $filterData['values'][] = $module;
        $filterData['sql'] .= " AND NOT (module = ?";
        if($types){
          $inArray = [];
          foreach ($types as $type) {
            $filterData['values'][] = $type;
            $inArray[] = '?';
          }
          $inString = implode(',', $inArray);
          $filterData['sql'] .= " AND type IN ($inString)";
        }
        $filterData['sql'] .= ")";
      }
      
      return $filterData;
    }

    /**
     * Get Event
     *
     * @author AvB
     **/
    public function get($limit = 0)
    {
        $queryobj = new Reportdata_model();
        $limit = $limit ? sprintf('LIMIT %d', $limit) : '';
        $out['items'] = array();
        $out['error'] = '';
        if($this->hasFilter()){
          $filter = $this->createFilter($this->conf['filter']);
        }else{
          $filter = ['sql' => '','values' => []];
        }
        $sql = "SELECT m.serial_number, module, type, msg, data, m.timestamp,
					machine.computer_name
				FROM event m 
				LEFT JOIN reportdata USING (serial_number) 
				LEFT JOIN machine USING (serial_number) 
				".get_machine_group_filter('WHERE').$filter['sql']."
				ORDER BY m.timestamp DESC
				$limit";
        
        $stmt = $queryobj->prepare($sql);
        $queryobj->execute($stmt, $filter['values']);

      //  print_r($stmt->fetchAll(PDO::FETCH_ASSOC));



        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $obj) {
            $out['items'][] = $obj;
        }
        
        $obj = new View();
        $obj->view('json', array('msg' => $out));
    }
} // END class Event_controller
