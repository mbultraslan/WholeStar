<?php

class Url {
	
				public function getRewrites() {
					return $this->rewrite;
				}

				public function resetRewrites() {
					$this->rewrite = array();

					return $this;
				}
			

	private $domain;

	private $ssl;

	private $rewrite = array();



	public function __construct($domain, $ssl = '') {

		$this->domain = $domain;

		$this->ssl = $ssl;

	}



	public function addRewrite($rewrite) {

		$this->rewrite[] = $rewrite;

	}



	public function link($route, $args = '', $connection = 'NONSSL') {
    if ('NONSSL' == $connection) {
        $url = $this->url;
    } else {
        $url = $this->ssl;  
    }



		 $url .= 'index.php?route=' . $route;





		if ($args) {

			$url .= str_replace('&', '&amp;', '&' . ltrim($args, '&'));

		}



		foreach ($this->rewrite as $rewrite) {

			$url = $rewrite->rewrite($url);

		}



		return $url;

	}

}

