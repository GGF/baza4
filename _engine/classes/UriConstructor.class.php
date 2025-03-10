<?php

class UriConstructor {

    public $arr;

    public function __construct($arr = false) {
        $this->arr = $arr ? $arr : Ajax::pureGet();
    }

    public function put($key, $val) {
        $this->arr = array_replace_recursive($this->arr, array($key => $val));
        return $this;
    }

    public function remove($key) {
        unset($this->arr[$key]);
        return $this;
    }

    public function clear() {
        $this->arr = array();
        $this->put('level',$_REQUEST['level']);
        return $this;
    }

    public function set($lego_name, $action /* .... */) {
        if (isset($this->arr[$lego_name]) && !is_array($this->arr[$lego_name]))
            unset($this->arr[$lego_name]);
        $this->arr[$lego_name]['act'] = $action;
        $params = func_get_args();
        array_shift($params);
        array_shift($params);
        if (count($params) == 1 && is_array($params[0]))
            $params = $params[0];
        foreach ($params as $key => $one) {
            $this->arr[$lego_name][$action][$key] = $one;
        }
        return $this;
    }

    public function setAct(/* .... */) {
        $lego = lego_abstract::current();
        $params = array($lego->getName());
        $params = array_merge($params, func_get_args());
        return call_user_func_array(
                array($this, "set"),
                $params
        );
    }

    public function combine($params) {
        if (func_num_args() == 2)
            $params = join("=", func_get_args());
        if (is_string($params))
            parse_str($params, $params);
        $this->arr = array_merge($this->arr, $params);
        foreach ($this->arr as $key => $val)
            if ($val == '?')
                unset($this->arr[$key]);
        return $this;
    }

    public function url($path = false) {
        if (!$path)
            $path = $_SERVER['SCRIPT_NAME'];
        //$path = 
        //substr($_SERVER["REQUEST_URI"], 0, 
        //strpos($_SERVER["REQUEST_URI"], "?"));
        return $path . '?' . $this;
    }

    public function ajaxurl($legoname,$path = false) {
        if (!$path)
            $path = $_SERVER['SCRIPT_NAME'];
        //$path =
        //substr($_SERVER["REQUEST_URI"], 0,
        //strpos($_SERVER["REQUEST_URI"], "?"));
        return $path . '?' . $this . "&ajax=$legoname";
    }

    public function __toString() {
        return http_build_query($this->arr);
    }

    public function asArray() {
        return $this->arr;
    }

}

?>