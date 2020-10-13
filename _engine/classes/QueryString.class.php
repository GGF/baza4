<?php

class QueryString{
    private $data = array();
    public function __construct($arr){
        $this->data = $arr;
    }
    
    public function set($key, $val){
        $q = new QueryString($this->data);
        $q->data[$key] = $val;
        if(func_num_args() > 2){
            $for_remove =  array_slice(func_get_args(), 2);
            foreach($for_remove as $val) 
                $q->remove($val);
        }
        return $q;
    }
    
    public function combine($query_string){
        $q = new QueryString($this->data);
        parse_str($query_string, $params);
        $q->data = array_merge($q->data, $params);
        return $q;
    }
    
    public function remove($key){
        $q = new QueryString($this->data);
        unset ($q->data[$key]);
        return $q;
    }
    
    public function __toString(){
        return http_build_query($this->data);
    }
}
?>