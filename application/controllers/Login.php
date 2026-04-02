<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller{

    function __construct(){
        parent::__construct();		
        $this->load->model('m_login');
        $this->load->model('m_master');
    }

    function index(){
        if($this->session->userdata('status') == "login"){
            redirect(base_url("Master"));
        }else{
            $this->load->view('v_login');
        }
    }

    function aksi_login(){
        $username = $this->input->post('Username');
        $password_ = $this->input->post('Password');
        $password = base64_encode($password_);

        $cek = $this->m_login->cek_login($username,$password);

        if($cek->num_rows() > 0){
            foreach ($cek->result() as $dt) {
                $data_session = array(
                    'status' => "login",
                    'id' => $dt->id,
                    'username' => $dt->username,
                    'password' => $dt->password,
                    'nm_user' => $dt->nm_user,
                    'level' => $dt->level
                );

                $this->session->set_userdata($data_session);
            }

            redirect(base_url("Master"));
        }else{
            redirect(base_url('login'));
        }
    }

    function logout(){
        $this->session->sess_destroy();
        redirect(base_url('login'));
    }
}