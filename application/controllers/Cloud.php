<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cloud extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		// User harus login untuk mengakses cloud
		if($this->session->userdata('user_id') == NULL) redirect('auth');
		$this->load->model('Cloud_model');
		$this->load->model('Auth_model');
	}

	private function _breadcrumbs($path_segments) {
		$url = '';
		$breadcrumbs = array();
		$real_path_segments = $this->Cloud_model->real_path_segments($path_segments);
		foreach($path_segments as $key=>$path){
			$url .= $path . '/';
			array_push($breadcrumbs, array('title'=> $real_path_segments[$key], 'url'=> site_url('cloud/folder/' . $url)));
		}
		return $breadcrumbs;
	}

	public function index()
	{
		redirect('cloud/folder');
	}

	public function folder()
	{
		// Olah data dari URL
		$this->load->helper('form');
		$user_id = $this->session->userdata('user_id');
		$url_segments = $this->uri->segments;
		unset($url_segments[1], $url_segments[2]);

		$delete = $this->input->get('delete');
		$sort = isset($_GET['sort']) ? $_GET['sort'] : 'name';
		$sort_asc = isset($_GET['asc']) ? $_GET['asc'] : 1;

		if($delete==1&&$this->Cloud_model->delete_folder($user_id, $url_segments)){
			$len = count($url_segments);
			$i = 0;
			foreach($url_segments as $key=>$segment){
				if($i==$len-1){
					$delete_name = base64_decode($segment);
					unset($url_segments[$key]);
				}
				$i++;
			}
			
			$this->load->model('Auth_model');
			$today = getdate();
			$today_date = $today[mday];
			$today_month = $today[mon];
			$today_year = $today[year];
			$today_jam = $today[hours];
			$today_menit = $today[minutes];
			$today_detik = $today[seconds];
			$username_value = $this->session->userdata('username');
			$data_type = 'delete_folder';
			$folder_deleted = $delete_name;
			
			$this->Auth_model->tambah_trackrecord($today_date, $today_month, $today_year, $today_jam, $today_menit, $today_detik, $user_id, $username_value, $folder_deleted, $data_type);
			
			$path = $this->Cloud_model->get_path(1, $url_segments);
			redirect('cloud/folder'.$path.'?delete_success=1&delete_name='.$delete_name);
		}

		// Ambil data dari model
		$contents = $this->Cloud_model->get_contents($user_id, $url_segments, $sort, $sort_asc);

		$data = array(
			'path' => $this->Cloud_model->get_path(1, $url_segments),
			'real_path' => $contents['real_path'],
			'folders' => $contents['folders'],
			'files' => $contents['files'],
			'breadcrumbs' => $this->_breadcrumbs($url_segments),
			'sort' => $sort,
			'asc' => $sort_asc
		);
		
		if ($this->session->userdata('is_admin') == 0)
		{
			$this->load->view('cloud/header', array('title'=>'Simple Cloud'));
			$this->load->view('cloud/folder', $data);
			$this->load->view('cloud/footer');
		}
		else 
		{
			$this->load->view('admin/cloud/header', array('title'=>'Simple Cloud'));
			$this->load->view('admin/cloud/folder', $data);
			$this->load->view('admin/cloud/footer');
		}
	}

	public function upload()
	{
		$this->load->helper(array('form', 'path'));
		$user_id = $this->session->userdata('user_id');
		$url_segments = $this->uri->segments;
		unset($url_segments[1], $url_segments[2]);

		//$config['upload_path']		= set_realpath($_SERVER['DOCUMENT_ROOT'] . '/../cloud/' . $user_id . $this->Cloud_model->get_path(0, $url_segments));
		$config['upload_path']		= set_realpath('C:/xampp/htdocs' . '/cloud/' . $user_id . $this->Cloud_model->get_path(0, $url_segments));
		
		$config['allowed_types']	= '*';
		$config['overwrite']		= TRUE;
		$config['max_size']			= 3000;
		$config['remove_spaces']	= FALSE;

		$error = '';

		if(isset($_FILES['userfile'])){
			if($this->input->post('filename') !== '') $config['file_name'] = $this->input->post('filename');
			$this->load->library('upload', $config);

			$file_data = $this->upload->data();
			$upload_success = $this->upload->do_upload();
			if ($upload_success)
			{
				$file_data = $this->upload->data();
				
				//mengambil data track record
				$this->load->model('Auth_model');
				$today = getdate();
				$today_date = $today[mday];
				$today_month = $today[mon];
				$today_year = $today[year];
				$today_jam = $today[hours];
				$today_menit = $today[minutes];
				$today_detik = $today[seconds];
				$username_value = $this->session->userdata('username');
				$data_type = 'upload_file';
				$file_name = $this->input->post('filename');
				$this->Auth_model->tambah_trackrecord($today_date, $today_month, $today_year, $today_jam, $today_menit, $today_detik, $user_id, $username_value, $file_name, $data_type);
			
				redirect('cloud/folder'.$this->Cloud_model->get_path(1, $url_segments).'?upload_success=1&upload_name='.$file_data['file_name']);
			}
			else
			{
				$error = 'File upload cancelled';
			}
		}
		$data_header = array(
			'title'=>'Simple Cloud | Upload',
			'error'=>$error
		);
		$data = array(
			'path'=>$this->Cloud_model->get_path(1, $url_segments),
			'max_size'=>$config['max_size'],
			'breadcrumbs' => $this->_breadcrumbs($url_segments)
		);
		
		if ($this->session->userdata('is_admin') == 0)
		{
			$this->load->view('cloud/header', $data_header);
			$this->load->view('cloud/upload', $data);
			$this->load->view('cloud/footer');
		}
		else 
		{
			$this->load->view('admin/cloud/header', $data_header);
			$this->load->view('admin/cloud/upload', $data);
			$this->load->view('admin/cloud/footer');
		}
	}

	public function new_folder()
	{
		$user_id = $this->session->userdata('user_id');
		$url_segments = $this->uri->segments;
		$folder_name = $this->input->post('foldername');
		unset($url_segments[1], $url_segments[2]);

		if($this->Cloud_model->create_folder($user_id, $url_segments, $folder_name))
			
		//mengambil data track record
			$this->load->model('Auth_model');
			$today = getdate();
			$today_date = $today[mday];
			$today_month = $today[mon];
			$today_year = $today[year];
			$today_jam = $today[hours];
			$today_menit = $today[minutes];
			$today_detik = $today[seconds];
			$username_value = $this->session->userdata('username');
			$data_type = 'new_folder';
			
			$this->Auth_model->tambah_trackrecord($today_date, $today_month, $today_year, $today_jam, $today_menit, $today_detik, $user_id, $username_value, $folder_name, $data_type);
			
			redirect('cloud/folder'.$this->Cloud_model->get_path(1, $url_segments).'?create_success=1&create_name='.$folder_name);;
	}

	public function file()
	{
		$this->load->helper('form');
		$user_id = $this->session->userdata('user_id');
		$url_segments = $this->uri->segments;
		unset($url_segments[1], $url_segments[2]);

		$delete = $this->input->get('delete');

		if($delete==1&&$this->Cloud_model->delete_file($user_id, $url_segments)){
			$len = count($url_segments);
			$i = 0;
			foreach($url_segments as $key=>$segment){
				if($i==$len-1){
					$delete_name = base64_decode($segment);
					unset($url_segments[$key]);
				}
				$i++;
			}
			
			$this->load->model('Auth_model');
			$today = getdate();
			$today_date = $today[mday];
			$today_month = $today[mon];
			$today_year = $today[year];
			$today_jam = $today[hours];
			$today_menit = $today[minutes];
			$today_detik = $today[seconds];
			$username_value = $this->session->userdata('username');
			$data_type = 'delete_file';
			$file_deleted = $delete_name;
			
			$this->Auth_model->tambah_trackrecord($today_date, $today_month, $today_year, $today_jam, $today_menit, $today_detik, $user_id, $username_value, $file_deleted, $data_type);
			
			
			$path = $this->Cloud_model->get_path(1, $url_segments);
			redirect('cloud/folder'.$path.'?delete_success=1&delete_name='.$delete_name);
		}
	}
	
	public function systemlog_page() //menampilkan halaman system log 
	{
		$this->load->helper("form");
		$this->load->view('admin/systemlog');
	}
	
	public function adduser_page() //menampilkan halaman adduser
	{
		$this->load->helper("form");
		$this->load->view('admin/adduser');
	}
	
	public function deleteuser_page() //menampilkan halaman deleteuser 
	{
		$this->load->helper("form");
		$this->load->view('admin/deleteuser');
	}
	
	public function cek_systemlog()
	{
		$this->load->library('form_validation');

        $this->form_validation->set_rules('noidlog', 'User ID', 'required|is_unique[users.id]');
        
		if ($this->form_validation->run() == FALSE) //berarti id terdapat di database
        {
			//mengambil data dari tabel users
			$this->load->model('Auth_model');
				$uid = $this->input->post('noidlog',TRUE);
				$result = $this->Auth_model->get_value($uid)->row();
				$data_array = array (
							'ID' => $result->id,
							'Username' => $result->username,
							'Password' => $result->password,
							'is_admin' => $result->is_admin
						);
			//mengambil data dari tabel track_record yang sesuai dgn yg didapat sebelumnya
			$this->load->view('admin/scriptsystemlog',$data_array);	
		}
		else 
		{
			echo "<script>alert('ID user tidak terdapat di dalam tabel');history.go(-1);</script>";
			redirect('cloud/folder');
		}
		
	}
	
	public function deleteuser($userid,$idvalue) 
	{		
			$path['path_1'] = $userid;
			$path['path_id'] = $idvalue;
			$this->load->view('admin/scriptdeleteuser',$path);
	}
	
	public function cek_deleteuser()
	{
        $this->load->library('form_validation');

        $this->form_validation->set_rules('notabeldelete', 'User ID', 'required|is_unique[users.id]');
        
		if ($this->form_validation->run() == FALSE) //berarti id terdapat di database
        {
				$this->load->model('Auth_model');
				$uid = $this->input->post('notabeldelete',TRUE);
				$result = $this->Auth_model->get_value($uid)->row();
				$data_array = array (
							'ID' => $result->id,
							'Username' => $result->username,
							'Password' => $result->password,
							'is_admin' => $result->is_admin
						);
				$data_array['id_value'] = $this->input->post('notabeldelete',TRUE);
				$this->load->view('admin/formdeleteuser',$data_array);							
		}
		
		else
		{
			echo "<script>alert('ID user tidak terdapat di dalam tabel');history.go(-1);</script>";
			redirect('cloud/folder');
		}
	}
	
	public function edituser_page() //menampilkan halaman edituser
	{
		$this->load->helper("form");
		$this->load->view('admin/edituser');
	}
	
	public function edituser($uservalue) 
	{
		$new_user = $this->input->post('idbaru', TRUE);
			
			$path1 = $uservalue;
			$path2 = $new_user;
			
			//sebenarnya rename ini tidak perlu, karena nama foldernya berupa ID user wkwkwk
			rename ($path1, $path2); //mengganti nama folder sesuai hasil edit user
			
			$this->load->view('admin/scriptedituser'); //mengedit di databasenya, nah ini yang perlu
	}
	
	public function cek_edituser()
	{
		$this->load->model('Auth_model');
		
		$this->load->library('form_validation');

        $this->form_validation->set_rules('notabeledit', 'User ID', 'required|is_unique[users.id]');
        
		if ($this->form_validation->run() == FALSE) //berarti id terdapat di database
        {
			$uid = $this->input->post('notabeledit',TRUE);
			$result = $this->Auth_model->get_value($uid)->row();
			$data_array = array (
						'ID' => $result->id,
						'Username' => $result->username,
						'Password' => $result->password,
						'Level' => $result->is_admin
			);
			$this->load->view('admin/formedituser',$data_array);
		}
		
		else 
		{
			echo "<script>alert('ID user tidak terdapat di dalam tabel');history.go(-1);</script>";
			$this->load->view('admin/edituser');
		}
	}
	
	public function cek_adduser()
	{
		$this->load->library('form_validation');

        $this->form_validation->set_rules('newuser', 'Username', 'required|is_unique[users.username]');
        $this->form_validation->set_rules('passnewuser', 'Password', 'required');
			
		if ($this->form_validation->run() == FALSE)
        {
			$this->load->view('admin/adduser');
		}
		else 
		{
			$this->load->model('Cloud_model');
			$username = $this->input->post('newuser');
			$password = $this->input->post('passnewuser');
			
			//menambah ke database
			$this->Auth_model->tambah_user($username, $password);  
			
			//membuat folder baru
			$this->Cloud_model->create_folder('', '', $this->Auth_model->check_user($username));
			
			echo "<script>alert('User baru berhasil ditambahkan');history.go(-1);</script>";
			
			redirect('cloud/folder');
			
		}
	}

}