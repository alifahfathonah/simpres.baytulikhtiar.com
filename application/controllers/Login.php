<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller
{
	public function __contruct()
	{
		parent::__contruct();
		$this->load->helper(array('url'));
	}
	
  function index($code = null)
	{
		$this->load->view('login2');
	}

  function index2()
  {
    $this->load->view('login');
  }
}