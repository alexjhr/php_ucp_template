<?php
namespace App\Helpers;

class HttpClient
{
	private $is_json;
	private $curl;

	public function __construct($url)
	{
		$this->is_json = false;
		$this->curl = curl_init();

		curl_setopt($this->curl, CURLOPT_URL, $url);
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($this->curl, CURLOPT_TIMEOUT_MS, 3000);
	}

	public function json()
	{
		$this->is_json = true;
		return $this;
	}

	public function get()
	{
		// Fetch service data
		$response = curl_exec($this->curl);
		curl_close($this->curl);

		if (!$response) {
			return false;
		}

		return $this->is_json ? json_decode($response) : $response;
	}
}
