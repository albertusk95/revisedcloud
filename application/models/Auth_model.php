<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model {

	public function get_value($idvalue) {
		
			$this->db->select('*');
			$this->db->from('users');
			$this->db->where('id',$idvalue);
			return $this->db->get();
	
	}

	public function tambah_trackrecord($today_date, $today_month, $today_year, $today_jam, $today_menit, $today_detik, $user_id, $username_value, $file_folder_name, $data_type)
	{
		$data = array (
			'id' => $user_id,
			'username' => $username_value,
			'file_folder' => $file_folder_name,
			'tanggal' => $today_date,
			'bulan' => $today_month,
			'tahun' => $today_year,
			'jam' => $today_jam,
			'menit' => $today_menit,
			'detik' => $today_detik,
			'tipe_data' => $data_type
		);
		$this->db->insert('track_record',$data);
	}
	
	public function tambah_user($username, $password) {
     
		$newuser  = $username;
        $passnewuser = $password;
		
        $data = array (
			'username' => $newuser,
			'password' => $passnewuser
        );  
		$data['password'] = sha1($data['password']);
		if ($newuser == 'admin')
		{
			if ($passnewuser == 'adminadmin')
			{
				$data['is_admin'] = 1;
			}
			else 
			{
				$data['is_admin'] = 0;
			}
		}
		else 
		{
			$data['is_admin'] = 0;
		}
        $this->db->insert('users',$data);
    }

	public function check_user($username)
	{
		// Ambil data dari database
		$q = $this
				->db
				->where('username', $username)
				->limit(1)
				->get('users');

		// Jika ditemukan
		if($q->num_rows() > 0){
			return $q->row()->id;
		}

		// Jika tidak ditemukan
		return false;
	}

	public function login($username, $password)
	{
		// Ambil data dari database
		$q = $this
				->db
				->where('username', $username)
				->where('password', sha1($password))
				->limit(1)
				->get('users');

		// Jika ditemukan
		if($q->num_rows() > 0){

			// Return data user
			return $q->row();
		}

		// Jika tidak ditemukan
		return false;
	}

	public function register($user)
	{
		// Enkripsi password
		$user['password'] = sha1($user['password']);

		// Input data ke database
		$this->db->insert('users', $user);

		// Cek jika data berhasil dimasukkan
		if($this->check_user($user['username'])!==false) return true;
		return false;
	}

}

/* End of file Admin_model.php */
/* Location: ./application/models/Admin_model.php */