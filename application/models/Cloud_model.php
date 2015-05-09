<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cloud_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();

		// Menjalankan helper file dari codeigniter
		$this->load->helper(array('file'));
	}

	private function _encode($str) {
		$str_encode = rtrim(base64_encode($str), '=');
		return $str_encode;
	}

	private function _decode($str) {
		$str = $str . '==';
		$str_decode = base64_decode($str);
		return $str_decode;
	}

	private function _sort_contents($data, $child, $sort_asc) {
		if($sort_asc==0) $sort_asc = SORT_DESC;
		else $sort_asc = SORT_ASC;
		foreach ($data as $key => $row) {
			if(is_string($row[$child])) $sort[$key] = array_map('strtolower', $row)[$child];
			else $sort[$key] = $row[$child];
		}
		array_multisort($sort, $sort_asc, $data);
		return $data;
	}

	private function _file_size($bytes, $decimals = 2) {
		$sz = ' kMGTP';
		$factor = floor((strlen($bytes) - 1) / 3);
		return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . ' ' . @$sz[$factor] . 'B';
	}

	private function _date_modified($timestamp){
		$second = time() - $timestamp;
		$minute = intval($second/60);
		$hour = intval($second/3600);
		$day = intval($second/86400);
		if($second < 60) {
			$modified = $second . ' second';
			if($second > 1) $modified .= 's';
			$modified .= ' ago';
		}
		else if($second < 3600){
			$modified = $minute . ' minute';
			if($minute > 1) $modified .= 's';
			$modified .= ' ago';
		}
		else if($second < 86400){
			$modified = $hour . ' hour';
			if($hour > 1) $modified .= 's';
			$modified .= ' ago';
		}
		else if($second < 2592000){
			$modified = $day . ' day';
			if($day > 1) $modified .= 's';
			$modified .= ' ago';
		}
		else $modified = date('j F Y', $timestamp);
		return $modified;
	}

	private function _file_type($name) {
		$extension = substr($name, strrpos($name, '.') + 1);
		$extensions = array(
			//Imageformats
			'jpg'=>1,'jpeg'=>1,'jpe'=>1,'gif'=>1,'png'=>1,'bmp'=>1,'tif'=>1,'tiff'=>1,'ico'=>1,
			//Videoformats
			'asf|asx'=>2,'wmv'=>2,'wmx'=>2,'wm'=>2,'avi'=>2,'divx'=>2,'flv'=>2,'mov'=>2,'qt'=>2,'mpeg'=>2,'mpg'=>2,'mpe'=>2,'mp4'=>2,'m4v'=>2,'ogv'=>2,'webm'=>2,'mkv'=>2,
			//Textformats
			'txt'=>3,'asc'=>3,'c'=>3,'cc'=>3,'h'=>3,'cpp'=>3,'csv'=>3,'tsv'=>3,'ics'=>3,'rtx'=>3,'css'=>3,'htm'=>3,'html'=>3,'php'=>3,'rtf'=>3,'js'=>3,
			//Audioformats
			'mp3'=>4,'m4a'=>4,'m4b'=>4,'ra'=>4,'ram'=>4,'wav'=>4,'ogg'=>4,'oga'=>4,'mid'=>4,'midi'=>4,'wma'=>4,'wax'=>4,'mka'=>4,
			//Application
			'exe'=>5,
			//Compressed
			'tar'=>6,'zip'=>6,'gz'=>6,'gzip'=>6,'rar'=>6,'7z'=>6,
			//Miscapplicationformats
			'pdf'=>7,'dot'=>7,'swf'=>7,'class'=>7,'doc'=>7,'pot'=>7,'pps'=>7,'ppt'=>7,'wri'=>7,'xla'=>7,'xls'=>7,'xlt'=>7,'xlw'=>7,'mdb'=>7,'mpp'=>7,'docx'=>7,'docm'=>7,'dotx'=>7,'dotm'=>7,'xlsx'=>7,'xlsm'=>7,'xlsb'=>7,'xltx'=>7,'xltm'=>7,'xlam'=>7,'pptx'=>7,'pptm'=>7,'ppsx'=>7,'ppsm'=>7,'potx'=>7,'potm'=>7,'ppam'=>7,'sldx'=>7,'sldm'=>7,'onetoc'=>7,'onetoc2'=>7,'onetmp'=>7,'onepkg'=>7,'odt'=>7,'odp'=>7,'ods'=>7,'odg'=>7,'odc'=>7,'odb'=>7,'odf'=>7,'wp'=>7,'wpd'=>7,'key'=>7,'numbers'=>7,'pages'=>7
		);
		$type = array('','Image','Video','Text','Audio','Application','Compressed','Document');
		if(isset($extensions[$extension])) return $type[$extensions[$extension]];
		else return 'Other';
	}

	private function _rrmdir($dir) {
		if (is_dir($dir)) {
			$objects = scandir($dir);
			foreach ($objects as $object) {
				if ($object != "." && $object != "..") {
					if (filetype($dir."/".$object) == "dir") $this->_rrmdir($dir."/".$object); else unlink($dir."/".$object);
				}
			}
			reset($objects);
			rmdir($dir);
			return true;
		}
	}

	public function get_contents($owner_id, $path_segments, $sort, $sort_asc)
	{
		$path = $this->get_path(1, $path_segments);
		$real_path = $this->get_path(0, $path_segments);

		// Menentukan directory yang akan discan
		$directory = FCPATH . '/../cloud/' . $owner_id . $real_path;
		if(!is_dir($directory)) return NOT_EXIST;

		// Men-scan file dan folder yang ada di directory
		$contents = get_dir_file_info($directory);

		// Jika file atau folder ada
		if(!empty($contents)){

			// Definisi array kosong
			$folders = array();
			$files = array();
			if($sort !== 'type') $contents = $this->_sort_contents($contents, $sort, $sort_asc);

			// Memasukkan data contents yang bertipe folder ke array folders
			foreach($contents as $content){
				$content['size'] = $this->_file_size($content['size']);
				$content['date'] = $this->_date_modified($content['date']);
				unset($content['server_path'], $content['relative_path']);
				if(is_dir($directory . '/' . $content['name'])){
					$content['url'] = site_url('cloud/folder' . $path . '/' . $this->_encode($content['name']));
					$content['type'] = 'Folder';
					array_push($folders, $content);
				}
				else if(is_file($directory . '/' . $content['name'])){
					$content['url'] = site_url('cloud/file' . $path . '/' . $this->_encode($content['name']));
					$content['type'] = $this->_file_type($content['name']);
					array_push($files, $content);
				}
			}
			$contents = array('files'=>$files, 'folders'=>$folders, 'path'=>$path, 'real_path'=>$real_path);
			if($sort == 'type'){
				$contents['folders'] = $this->_sort_contents($contents['folders'], $sort, $sort_asc);
				$contents['files'] = $this->_sort_contents($contents['files'], $sort, $sort_asc);
			}
			return $contents;
		}
		return NULL;
	}

	public function real_path_segments($path_segments)
	{
		$real_path_segments = array();
		foreach($path_segments as $key=>$path_segment){
			$real_path_segments[$key] = $this->_decode($path_segment);
		}
		return $real_path_segments;
	}

	public function get_path($encoded, $path_segments)
	{
		$path = '';
		$real_path = '';
		foreach ($path_segments as $key => $path_segment) {
			$path = $path . '/' . $path_segment;
			$real_path = $real_path . '/' . $this->_decode($path_segment);
		}
		return ($encoded ? $path : $real_path);
	}

	public function create_folder($owner_id, $path_segments, $folder_name){
		$this->load->helper('path');
		$path = $this->get_path(1, $path_segments);
		$real_path = $this->get_path(0, $path_segments);

		$directory = set_realpath('../cloud/' . $owner_id . $real_path);
		if(!is_dir($directory)) return NOT_EXIST;
		$new_folder = $directory . $folder_name;
		if(!is_dir($new_folder)){
			if(mkdir($new_folder, 0755, TRUE)) return true;
		}
		return false;
	}

	public function delete_folder($owner_id, $path_segments){
		$this->load->helper('path');
		$path = $this->get_path(1, $path_segments);
		$real_path = $this->get_path(0, $path_segments);

		$directory = set_realpath('../cloud/' . $owner_id . $real_path);
		if(!is_dir($directory)){
			return NOT_EXIST;
		}
		else {
			if($this->_rrmdir($directory)) return true;
		}
		return false;
	}

	public function delete_file($owner_id, $path_segments){
		$this->load->helper('path');
		$path = $this->get_path(1, $path_segments);
		$real_path = $this->get_path(0, $path_segments);

		$file = set_realpath('../cloud/' . $owner_id . $real_path);
		if(!is_file($file)){
			return NOT_EXIST;
		}
		else {
			if(unlink($file)) return true;
		}
		return false;
	}
}