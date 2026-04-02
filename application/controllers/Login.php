<?php

#[\AllowDynamicProperties] 
class Login extends CI_Controller{

	public $log
	public $benchmark
	public $hooks
	public $config
	public $utf8
	public $uri
	public $exceptions
	public $router
	public $output
	public $security
	public $input
	public $lang
	public $load
	public $db
	public $session
	public $form_validation
	public $cart
	public $m_fungsi
	public $m_login
	public $m_master

	function __construct(){
		parent::__construct();		
		$this->load->model('m_login');
		$this->load->model('m_master');

	}
	// cek
	function index(){
		if($this->session->userdata('status') == "login"){
			redirect(base_url("Master"));
		}else{
			$this->load->view('v_login'/*,$data*/);
			}

		}

	function aksi_login(){
		$username = $this->input->post('Username');
		$password_ = $this->input->post('Password');
		// $p = mysql_escape_string($password_);
		$password = base64_encode($password_);
		$cek = $this->m_login->cek_login($username,$password);
		if($cek->num_rows() > 0){
			
			foreach ($cek->result() as $dt) {
				$data_session['status'] = "login";
				$data_session['id'] = $dt->id;
				$data_session['username'] = $dt->username;
				$data_session['password'] = $dt->password;
				$data_session['nm_user'] = $dt->nm_user;
				$data_session['level'] = $dt->level;

				$this->session->set_userdata($data_session);
			}
			

			$this->session->set_flashdata('msg', '<div class="alert alert-success">
 <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <strong>Login Berhasil!</strong> Selamat Bekerja
</div>');

			redirect(base_url("Master"));

		}else{

			$this->session->set_flashdata('msg', '<div class="alert alert-danger">
			 <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			  Salah Username atau Password!
			</div>');

		redirect(base_url('login'));
		
			
		}
		
	}

	function aksi_login_member(){
		$username = $this->input->post('username');
		$password_ = $this->input->post('password');
		$password = base64_encode($password_);
		$cek = $this->m_login->cek_login_member($username,$password);
		if($cek->num_rows() > 0){
			// echo "string"; 
			exit();
			foreach ($cek->result() as $dt) {
				$data_session['status'] = "login";
				$data_session['username'] = $dt->username;
				$data_session['password'] = $dt->password;
				$data_session['level'] = $dt->level;
				$data_session['foto'] = $dt->foto;

				$this->session->set_userdata($data_session);
			}
			

			$this->session->set_flashdata('msg', '<div class="alert alert-success">
 <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <strong>Login Berhasil!</strong> Selamat Bekerja
</div>');

			redirect(base_url("Master"));

		}else{
			

			$this->session->set_flashdata('msg', '<div class="alert alert-danger">
			  <center> Incorrect Username Or Password! </center>
			</div>');

		redirect(base_url('web/login'));
		
			
		}
		
	}

	function logout(){
		$this->session->sess_destroy();
		redirect(base_url('login'));
	}

}

?>