<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {


	public function index()
	{
		$this->load->helper('path');
		echo set_realpath($_SERVER['DOCUMENT_ROOT'] . '/../cloud');
	}

}

/* End of file Test.php */
/* Location: ./application/controllers/Test.php */