<?php
    class Response
    {
        public $id, $url, $crw_id, $crw_method, $crw_strip;
        public $code = false;
        public $body, $stripped_body;
        function Response($id, $url, $crw_id, $crw_method, $crw_strip)
        {
            $this->id = $id;
            $this->url = $url;
            $this->crw_id = $crw_id;
            $this->crw_method = $crw_method;
            $this->crw_strip = $crw_strip;
        }
        
        function set_code($code)
        {
            $this->code = $code;
        }
    }
    
    class News
    {
        public $title, $url;
        function News($title, $url)
        {
            $this->title = $title;
            $this->url = $url;
        }
    }
?>