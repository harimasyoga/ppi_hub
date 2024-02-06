<?php
defined('BASEPATH') or exit('No direct script access allowed');

class c_YjKbI2m extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('status') != "login") 
		{
			redirect(base_url("Login"));
		}
		$this->load->model('m_master');
		$this->load->model('m_transaksi');
	}

	function YjKbI2m()
	{
		$params       = (object)$this->input->post();

		/* LOGO */
		//$nmfile = "file_".time(); //nama file saya beri nama langsung dan diikuti fungsi time
		$config['upload_path']   = './assets/gambar_po/'; //path folder
		$config['allowed_types'] = 'gif|jpg|png|jpeg|bmp'; //type yang dapat diakses bisa anda sesuaikan
		$config['max_size']      = '1024'; //maksimum besar file 2M
		$config['max_width']     = 'none'; //lebar maksimum 1288 px
		$config['max_height']    = 'none'; //tinggi maksimu 768 px
		//$config['file_name'] = $nmfile; //nama yang terupload nantinya

		$this->load->library('upload',$config);
		$this->upload->initialize($config);

		if ($params->status == 'insert') 
		{
			if($_FILES['filefoto']['name'])
			{
				if ($this->upload->do_upload('filefoto'))
				{
					$gbrBukti = $this->upload->data();
					$filefoto = $gbrBukti['file_name'];
					// $filefoto    = $_FILES['filefoto']['name'];
					
				}else{
					$filefoto = 'foto.jpg';
				}
			} else {

				if($params->tgl_po<'2023-11-01')
				{
					$filefoto = 'foto.jpg';
				}else{
					$error = array('error' => $this->upload->display_errors());
					var_dump($error);
					exit;
				}
			}

		}else{

			if($_FILES['filefoto']['name'])
			{
				if ($this->upload->do_upload('filefoto'))
				{
					$gbrBukti = $this->upload->data();
					$filefoto = $gbrBukti['file_name'];
					// $filefoto    = $_FILES['filefoto']['name'];
					
				}else{
					$filefoto = 'foto.jpg';
				}
			} else {

				if($params->tgl_po<'2023-11-01')
				{
					$filefoto = 'foto.jpg';
				}else{
					$load_data = $this->db->query("SELECT*FROM trs_po where kode_po = '$params->kode_po' and no_po='$params->no_po' ")->row();

					$filefoto = $load_data->img_po;
				}
			}

		}

		/*END LOGO */
		echo json_encode($filefoto);

	}

	function YjKbI2m2()
	{
		$jenis    = $_POST['jenis'];
		$field    = $_POST['field'];
		$no_po    = $_POST['no'];

		// DELETE PO HUB

		$load_po    = $this->db->query("SELECT *from trs_po a 
		Join m_hub b ON a.id_hub=b.id_hub 
		Join akses_db_hub c ON b.nm_hub=c.nm_hub
		WHERE no_po='$no_po'")->row();

		$db_ppi_hub = '$'.$load_po->nm_db_hub;
		$db_ppi_hub = $this->load->database($load_po->nm_db_hub, TRUE);

		if ($jenis == "trs_po") {
			$result = $db_ppi_hub->query("DELETE FROM $jenis WHERE  $field = '$no_po'");
			$result = $db_ppi_hub->query("DELETE FROM trs_po_detail WHERE  $field = '$no_po'");
		} else {

			$result = $db_ppi_hub->query("DELETE FROM $jenis WHERE  $field = '$no_po'");
		}

		// Hapus File Foto
		unlink("assets/gambar_po/".$load_po->img_po);
		

		echo json_encode($result);
	}


}
