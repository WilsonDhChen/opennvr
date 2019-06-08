<?php
class Request{

    protected $uri;

	public function __construct($uri=null)
    {
        if (empty($uri)) {
            $this->uri = $uri;
        }

    }

    public function uri($uri)
    {
        $this->uri = $uri;
        return $this;
    }

    public function post($data=null){
		return $this->send($this->uri, $data, 'POST');
		
	}
	
	public function get($data=null){
		return $this->send($this->uri, $data, 'GET');
	}

	protected function send($uri,$data=array(),$method='GET'){

		$data = http_build_query($data); 
		$opts = array ( 
			'http' => array ( 
						'method' => $method, 
						'header'=> "Content-type: application/x-www-form-urlencoded\r\n" . 
								   "Content-Length: " . strlen($data) . "\r\n", 
						'content' => $data 
			),
		);
		$context = stream_context_create($opts); 
		$html = file_get_contents($uri, false, $context);
		return $html;
		
	}			
	
}


?>