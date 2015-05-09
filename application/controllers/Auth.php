<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('form');
	}

	public function index()
	{
		redirect('auth/login');
	}

	public function login()
	{
		// Jika user sudah login, redirect ke cloud
		if($this->session->userdata('user_id') !== NULL) redirect('cloud');

		// Validasi form login
		$this->load->library('form_validation');
		$this->form_validation->set_rules('username', 'Username','required');
		$this->form_validation->set_rules('password', 'Password', 'required');
		$this->form_validation->set_message('required', 'Required');

		// Jika validasi berhasil
		if($this->form_validation->run())
		{
			// Ambil data dari model
			$this->load->model('Auth_model');
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			$user = $this->Auth_model->login($username, $password);

			// Jika gagal log in
			if(!$user)
			{
				$data['error'] = 'Wrong username or password.';
				$this->load->view('auth/header', array('title'=>'Log In | SimpleCloud'));
				$this->load->view('auth/login', $data);
				$this->load->view('auth/footer');
			}

			// Jika berhasil
			else
			{
				// Set php session
				$user_sess = array(
					'user_id' => $user->id,
					'username' => $user->username,
					'is_admin' => $user->is_admin
				);
				$this->session->set_userdata($user_sess);

				redirect('cloud');
			}
		}

		// Jika validasi gagal
		else
		{
			$this->load->view('auth/header', array('title'=>'Log In | SimpleCloud'));
			$this->load->view('auth/login');
			$this->load->view('auth/footer');
		}
	}

	public function register()
	{
		// Jika user sudah login, redirect ke cloud
		if($this->session->userdata('user_id') !== NULL) redirect('cloud');

		// Validasi form registrasi
		$this->load->library('form_validation');
		$this->form_validation->set_rules('username', 'Username','required|is_unique[users.username]');
		$this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');

		// Jika validasi berhasil
		if($this->form_validation->run())
		{
			// Ambil data dari model
			$this->load->model('Auth_model');
			$user = array(
				'username' => $this->input->post('username'),
				'password' => $this->input->post('password'),
			);
			if ($this->input->post('username') == 'admin')
			{
				if ($this->input->post('password') == 'adminadmin')
				{
					$user['is_admin'] = 1;
				}
				else
				{
					$user['is_admin'] = 0;
				}
			}
			else 
			{
				$user['is_admin'] = 0;
			}
			// Jika berhasil register, redirect ke halaman login
			if($this->Auth_model->register($user)){
				$this->load->model('Cloud_model');
				$this->Cloud_model->create_folder('', '', $this->Auth_model->check_user($user['username']));
				redirect('auth/login');
			}

			// Jika tidak berhasil register
			else $this->load->view('auth/register', array('error' => 'Error creating user'));
		}

		// Jika validasi gagal
		else
		{
			$this->load->view('auth/register');
		}

	}

	public function logout()
	{
		// Destroy php session
		$this->session->sess_destroy();

		redirect('auth/login');
	}

}