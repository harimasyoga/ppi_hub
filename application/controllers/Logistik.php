<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Logistik extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('status') != "login") {
			redirect(base_url("Login"));
		}
		$this->load->model('m_master');
		$this->load->model('m_logistik');
	}

	public function Invoice()
	{
		$data = array(
			'judul' => "Invoice",
		);
		$this->load->view('header', $data);
		$this->load->view('Logistik/v_invoice');
		$this->load->view('footer');
	}
	
	public function Invoice_add()
	{
		$data = array(
			'judul' => "Invoice Baru",
		);
		$this->load->view('header', $data);
		$this->load->view('Logistik/v_invoice_add');
		$this->load->view('footer');
	}
	
	public function Invoice_edit()
	{
		$id       = $_GET['id'];
		$no_inv   = $_GET['no_inv'];

		$data = array(
			'judul' 	 => "Edit Invoice",
			'id'    	 => $id,
			'no_inv'     => $no_inv,
		);
		$this->load->view('header', $data);
		$this->load->view('Logistik/v_invoice_edit');
		$this->load->view('footer');
	}

	public function Timbangan()
	{
		$data = array(
			'judul' => "Timbangan",
		);
		$this->load->view('header', $data);
		$this->load->view('Logistik/v_timbangan');
		$this->load->view('footer');
	}

	// public function Surat_Jalan()
	// {
	// 	$data = array(
	// 		'judul' => "Surat Jalan",
	// 	);
	// 	$this->load->view('header', $data);
	// 	$this->load->view('Logistik/v_surat_jln');
	// 	$this->load->view('footer');
	// }
	
	public function Surat_Jalan_add()
	{
		$data = array(
			'judul' => "Surat Jalan Baru",
		);
		$this->load->view('header', $data);
		$this->load->view('Logistik/v_surat_jln_add');
		$this->load->view('footer');
	}
	
	public function Surat_Jalan_edit()
	{
		$id       = $_GET['id'];
		$no_inv   = $_GET['no_inv'];

		$data = array(
			'judul' 	 => "Edit Surat Jalan",
			'id'    	 => $id,
			'no_inv'     => $no_inv,
		);
		$this->load->view('header', $data);
		$this->load->view('Logistik/v_surat_jln_edit');
		$this->load->view('footer');
	}

	function load_produk()
    {
        
		$pl = $this->input->post('idp');
		$kd = $this->input->post('kd');

        if($pl !='' && $kd ==''){
            $cek ="where no_customer = '$pl' ";
        }else if($pl =='' && $kd !=''){
            $cek ="where id_produk = '$kd' ";
        }else {
            $cek ="";
        }

        $query = $this->db->query("SELECT * FROM m_produk $cek order by id_produk ")->result();

            if (!$query) {
                $response = [
                    'message'	=> 'not found',
                    'data'		=> [],
                    'status'	=> false,
                ];
            }else{
                $response = [
                    'message'	=> 'Success',
                    'data'		=> $query,
                    'status'	=> true,
                ];
            }
            $json = json_encode($response);
            print_r($json);
    }

	function load_no_inv()
    {
        
		$type   = $this->input->post('type');
		$pajak  = $this->input->post('pajak');

		($type=='roll')? $type_ok=$type : $type_ok='SHEET_BOX';
		
		($pajak=='nonppn')? $pajak_ok='non' : $pajak_ok='ppn';
		
		$type   = $this->m_fungsi->tampil_no_urut($type_ok.'_'.$pajak_ok);
        echo json_encode($type);
    }

	function load_data_1()
	{
		$id       = $this->input->post('id');
		$no_inv   = $this->input->post('no_inv');

		$queryh   = "SELECT*FROM invoice_header where id='$id' and no_invoice='$no_inv'";
		$queryd   = "SELECT*FROM invoice_detail where no_invoice='$no_inv' ORDER BY TRIM(no_surat) ";

		$header   = $this->db->query($queryh)->row();
		$detail    = $this->db->query($queryd)->result();

		$data = ["header" => $header, "detail" => $detail];

        echo json_encode($data);
	}

	function edit_timbangan()
	{
		$id       = $_POST["id"];
		$data     = $this->db->query("SELECT * FROM m_jembatan_timbang WHERE id_timbangan='$id'")->row();

		echo json_encode(array(
			'hub' => $data,
		));
	}

	function load_data()
	{
		// $db2 = $this->load->database('database_simroll', TRUE);
		$jenis        = $this->uri->segment(3);
		$data         = array();

		if ($jenis == "Invoice") {
			$query = $this->db->query("SELECT * FROM invoice_header ORDER BY tgl_invoice desc,no_invoice")->result();

			$i               = 1;
			foreach ($query as $r) {

				$queryd = $this->db->query("SELECT  CASE WHEN type='roll' THEN
					SUM(harga*weight)
				ELSE
					SUM(harga*hasil)
				END AS jumlah
				FROM invoice_detail 
				WHERE no_invoice='$r->no_invoice' ")->row();

				$result_sj = $this->db->query("SELECT * FROM invoice_detail WHERE no_invoice='$r->no_invoice' GROUP BY no_surat ORDER BY no_surat");
				if($result_sj->num_rows() == '1'){
					$no_sj = $result_sj->row()->no_surat;
				}else{					
					$no_sj_result    = '';
					foreach($result_sj->result() as $row){
						$no_sj_result .= $row->no_surat.'<br>';
					}
					$no_sj = $no_sj_result;
				}

				$ppn11        = 0.11 * $queryd->jumlah;
				$pph22        = 0.011 * $queryd->jumlah;
				if($r->pajak=='ppn')
				{
					if($r->inc_exc=='Include')
					{
						$nominal    = 0;
					}else if($r->inc_exc=='Exclude')
					{				
						$nominal    = $ppn11;
					}else{
						$nominal    = 0;
					}

				}else if($r->pajak=='ppn_pph') {
					if($r->inc_exc=='Include')
					{
						$nominal    = 0;
					}else if($r->inc_exc=='Exclude')
					{				
						$nominal    = $ppn11 + $pph22;
					}else{
						$nominal    = 0;
					}

				}else{
					if($r->inc_exc=='Include')
					{
						$nominal    = 0;
					}else if($r->inc_exc=='Exclude')
					{
						$nominal    = $ppn11;
					}else{
						$nominal    = 0;
					}
				}

				if($r->acc_admin=='N')
                {
                    $btn1   = 'btn-warning';
                    $i1     = '<i class="fas fa-lock"></i>';
                } else {
                    $btn1   = 'btn-success';
                    $i1     = '<i class="fas fa-check-circle"></i>';
                }

				if($r->acc_owner=='N')
                {
                    $btn2   = 'btn-warning';
                    $i2     = '<i class="fas fa-lock"></i>';
                } else {
                    $btn2   = 'btn-success';
                    $i2     = '<i class="fas fa-check-circle"></i>';
                }

				$total    = $queryd->jumlah + $nominal;

				$id       = "'$r->id'";
				$no_inv   = "'$r->no_invoice'";
				$print    = base_url("laporan/print_invoice_v2?no_invoice=") . $r->no_invoice;

				$row = array();
				$row[] = '<div class="text-center">'.$i.'</div>';
				$row[] = '<div class="text-center">'.$this->m_fungsi->tanggal_ind($r->tgl_invoice).'</div>';
				$row[] = $r->no_invoice;
				$row[] = $no_sj;
				$row[] = $r->kepada;
				$row[] = $r->nm_perusahaan;
				$row[] = '<div class="text-center" style="font-weight:bold;color:#f00">'.$this->m_fungsi->tanggal_format_indonesia($r->tgl_jatuh_tempo).'</div>';
				
				if (in_array($this->session->userdata('username'), ['karina']))
				{
					$urll1 = "onclick=acc_inv(`admin`,'$r->acc_admin','$r->no_invoice')";
					$urll2 = '';

				} else if (in_array($this->session->userdata('username'), ['bumagda']))
				{
					$urll1 = '';
					$urll2 = "onclick=acc_inv(`owner`,'$r->acc_owner','$r->no_invoice')";
				} else {
					$urll1 = '';
					$urll2 = '';
				}


				$row[] = '<div class="text-center">
				<button '.$urll1.' type="button" title="VERIFIKASI DATA" style="text-align: center;" class="btn btn-sm '.$btn1.' ">'.$i1.'</button>
				<div>';
					
				$row[] ='<div class="text-center"><a style="text-align: center;" class="btn btn-sm '.$btn2.' " '.$urll2.' title="VERIFIKASI DATA" >
				<b>'.$i2.' </b> </a><div>';

				$row[] = '<div class="text-right"><b>'.number_format($total, 0, ",", ".").'</b></div>';

				$aksi = "";

				if (in_array($this->session->userdata('level'), ['Admin','Keuangan1']))
				{
					if ($r->acc_owner == "N") {
						$aksi = '
							<a class="btn btn-sm btn-warning" href="' . base_url("Logistik/Invoice_edit?id=" .$r->id ."&no_inv=" .$r->no_invoice ."") . '" title="EDIT DATA" >
								<b><i class="fa fa-edit"></i> </b>
							</a> 

							<button type="button" title="DELETE"  onclick="deleteData(' . $id . ',' . $no_inv . ')" class="btn btn-danger btn-sm">
								<i class="fa fa-trash-alt"></i>
							</button> 

							<a target="_blank" class="btn btn-sm btn-danger" href="' . base_url("Logistik/Cetak_Invoice?no_invoice=" . $r->no_invoice . "") . '" title="CETAK" ><b><i class="fa fa-print"></i> </b></a>
							
							';
					} else {
						$aksi = '
						<a target="_blank" class="btn btn-sm btn-danger" href="' . base_url("Logistik/Cetak_Invoice?no_invoice=" . $r->no_invoice . "") . '" title="CETAK" ><b><i class="fa fa-print"></i> </b></a>
						';
					}
				} else {
					$aksi = '';
				}
				$row[] = '<div class="text-center">'.$aksi.'</div>';
				$data[] = $row;

				$i++;
			}
		}else if ($jenis == "Timbangan") {
			$query = $this->db->query("SELECT * FROM m_jembatan_timbang ORDER BY date_masuk")->result();

			$i               = 1;
			foreach ($query as $r) {

				$print    = base_url("Logistik/printTimbangan?id=") . $r->id_timbangan;
				$id       = "'$r->id_timbangan'";

				$row = array();
				$row[] = '<div class="text-center">'.$i.'</div>';
				$row[] = '<div class="text-center">'.$r->permintaan.'</div>';
				$row[] = '<div class="text-center">'.$this->m_fungsi->tanggal_ind(substr($r->date_masuk,0,10)).'<br>( '.substr($r->date_masuk,11).' )</div>';
				$row[] = $r->suplier;
				$row[] = '<div class="text-right">'.number_format($r->berat_bersih).'</div>';
				$aksi = "";

				if (in_array($this->session->userdata('level'), ['Admin','User']))
				{
						$aksi = '
							<a class="btn btn-sm btn-warning" onclick="tampil_edit('."'".$r->id_timbangan."'".','."'detail'".')" title="EDIT DATA" >
								<b><i class="fa fa-edit"></i> </b>
							</a> 

							<button type="button" title="DELETE"  onclick="deleteData(' . $id . ')" class="btn btn-danger btn-sm">
								<i class="fa fa-trash-alt"></i>
							</button> 

							<a target="_blank" class="btn btn-sm btn-danger" href="' . base_url("Logistik/printTimbangan?id=" . $r->id_timbangan . "") . '" title="CETAK" ><b><i class="fa fa-print"></i> </b></a>
							
							';
					
				} else {
					$aksi = '';
				}
				$row[] = '<div class="text-center">'.$aksi.'</div>';
				$data[] = $row;

				$i++;
			}
		}else{

		}

		$output = array(
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}

	function prosesData()
	{
		$jenis    = $_POST['jenis'];
		$result   = $this->m_logistik->$jenis();

		echo json_encode($result);
	}
	
	function load_sj($searchTerm="")
	{
		// ASLI
		$db2        = $this->load->database('database_simroll', TRUE);
		$type_po    = $this->input->post('type_po');
		$tgl        = $this->input->post('tgl_sj');
		$stat       = $this->input->post('stat');
		
		if ($type_po == 'roll')
		{
			$tbl1          = 'pl';
			$tbl2          = 'm_timbangan';
			$perusahaan    = 'm_perusahaan';
			$where_po      = '';
			$join_po       = '';
		}else{
			if ($type_po == 'box')
			{				
				$where_po    = 'and d.po ="box"';
			}else{
				$where_po    = 'and d.po is null';
			}
			
			$tbl1          = 'pl_box';
			$tbl2          = 'm_box';
			$perusahaan    = 'm_perusahaan2';

			$join_po       = 'JOIN po_box_master d ON a.no_po=d.no_po and b.ukuran=d.ukuran';
		}

		if($stat == 'add')
		{
			$where_status = 'and a.no_pl_inv = "0" ';
		}else{
			$where_status = '';

		}

		$query = $db2->query("SELECT DATE_FORMAT(a.tgl, '%d-%m-%Y')tgll,a.*,c.id as id_perusahaan, c.nm_perusahaan as nm_perusahaan , c.pimpinan as pimpinan, c.alamat as alamat_perusahaan, c.no_telp as no_telp FROM $tbl1 a
			JOIN $tbl2 b ON a.id = b.id_pl
			LEFT JOIN $perusahaan c ON a.id_perusahaan=c.id
			$join_po
			WHERE a.tgl = '$tgl' 
			-- and a.id_perusahaan not in ('210','217') 
			$where_status $where_po 
			GROUP BY a.tgl,a.id_perusahaan
			ORDER BY a.tgl,a.id_perusahaan,a.no_pl_inv")->result();

		if (!$query) {
			$response = [
				'message'	=> 'not found',
				'data'		=> [],
				'status'	=> false,
			];
		}else{
			$response = [
				'message'	=> 'Success',
				'data'		=> $query,
				'status'	=> true,
			];
		}
		$json = json_encode($response);
		print_r($json);
    }

	function list_item()
	{
		// ASLI
		$tgl_sj           = $this->input->post('tgl_sj');
		$id_perusahaan    = $this->input->post('id_perusahaan');
		$type_po          = $this->input->post('type_po');
		$tgl              = $this->input->post('tgl_sj');
		$db2              = $this->load->database('database_simroll', TRUE);
		
		if ($type_po == 'roll')
		{
			$query = $db2->query("SELECT c.nm_perusahaan,a.id_pl,b.id,a.nm_ker,a.g_label,a.width,COUNT(a.roll) AS qty,SUM(weight)-SUM(seset) AS weight,b.no_po,b.no_po_sj,b.no_surat
			FROM m_timbangan a 
			INNER JOIN pl b ON a.id_pl = b.id 
			LEFT JOIN m_perusahaan c ON b.id_perusahaan=c.id
			WHERE b.no_pl_inv = '0' AND b.tgl='$tgl_sj' AND b.id_perusahaan='$id_perusahaan'
			GROUP BY b.no_po,a.nm_ker,a.g_label,a.width 
			ORDER BY a.g_label,b.no_surat,b.no_po,a.nm_ker DESC,a.g_label,a.width ")->result();
		}else{
			if ($type_po == 'box')
			{				
				$where_po    = 'and d.po ="box"';
			}else{
				$where_po    = 'and d.po is null';
			}
			$query = $db2->query("SELECT b.id as id_pl, a.qty, a.qty_ket, b.tgl, b.id_perusahaan, c.nm_perusahaan, b.no_surat, b.no_po, b.no_kendaraan, d.item, d.kualitas, d.ukuran2,d.ukuran, 
			d.flute, d.po
			FROM m_box a 
			JOIN pl_box b ON a.id_pl = b.id 
			LEFT JOIN m_perusahaan2 c ON b.id_perusahaan=c.id
			JOIN po_box_master d ON b.no_po=d.no_po and a.ukuran=d.ukuran
			WHERE b.no_pl_inv = '0' AND b.tgl = '$tgl_sj' AND b.id_perusahaan='$id_perusahaan' $where_po
			ORDER BY b.tgl desc ")->result();
		}
		
		if (!$query) {
			$response = [
				'message'	=> 'not found',
				'data'		=> [],
				'status'	=> false,
			];
		}else{
			$response = [
				'message'	=> 'Success',
				'data'		=> $query,
				'status'	=> true,
			];
		}
		$json = json_encode($response);
		print_r($json);
    }

	function Insert_inv()
	{

		if($this->session->userdata('username'))
		{

			$c_no_inv_kd   = $this->input->post('no_inv_kd');
			$c_no_inv      = $this->input->post('no_inv');
			$c_no_inv_tgl  = $this->input->post('no_inv_tgl');
			$cek_inv       = $this->input->post('cek_inv');

			$no_inv_ok     = $c_no_inv_kd.''.$c_no_inv.''.$c_no_inv_tgl;
			$query_cek_no  = $this->db->query("SELECT*FROM invoice_header where no_invoice='$no_inv_ok' ")->num_rows();

			if($query_cek_no>0)
			{
				echo json_encode(array("status" => "3","id" => '0'));
			}else{
				
				$c_type_po   = $this->input->post('type_po');
				$c_pajak     = $this->input->post('pajak');
				$asc         = $this->m_logistik->save_invoice();
		
				if($asc){
		
					($c_type_po=='roll')? $type_ok=$c_type_po : $type_ok='SHEET_BOX';
			
					($c_pajak=='nonppn')? $pajak_ok='non' : $pajak_ok='ppn';
			
					$no_urut    = $this->m_fungsi->tampil_no_urut($type_ok.'_'.$pajak_ok);
					$kode_ok    = $type_ok.'_'.$pajak_ok;

					if($cek_inv =='baru')
					{
						$this->db->query("UPDATE m_urut set no_urut=$no_urut+1 where kode='$kode_ok' ");
					}else{
						
						if($c_no_inv == $no_urut)
						{
							$this->db->query("UPDATE m_urut set no_urut=$no_urut+1 where kode='$kode_ok' ");
						}
					}
		
					echo json_encode(array("status" =>"1","id" => $asc));
		
				}else{
					echo json_encode(array("status" => "2","id" => $asc));
		
				}

			}

		}

		
		
	}

	function update_inv()
	{

		if($this->session->userdata('username'))
		{
			$c_no_inv_kd   = $this->input->post('no_inv_kd');
			$c_no_inv      = $this->input->post('no_inv');
			$c_no_inv_tgl  = $this->input->post('no_inv_tgl');
			$cek_inv       = $this->input->post('cek_inv2');
			$no_inv_old    = $this->input->post('no_inv_old');
			$c_type_po     = $this->input->post('type_po2');
			$c_pajak       = $this->input->post('pajak2');

			($c_type_po=='roll')? $type_ok=$c_type_po : $type_ok='SHEET_BOX';
			
			($c_pajak=='nonppn')? $pajak_ok='non' : $pajak_ok='ppn';
	
			$no_urut         = $this->m_fungsi->tampil_no_urut($type_ok.'_'.$pajak_ok);

			$no_inv_ok       = $c_no_inv_kd.''.$c_no_inv.''.$c_no_inv_tgl;

			$query_cek_no    = $this->db->query("SELECT*FROM invoice_header where no_invoice='$no_inv_ok' and no_invoice <> '$no_inv_old' ")->num_rows();

			if($query_cek_no>0)
			{
				echo json_encode(array("status" => "3","id" => '0'));
			}else if($c_no_inv>$no_urut)
			{
				echo json_encode(array("status" => "4","id" => $no_urut));
			}else{
				
				$asc = $this->m_logistik->update_invoice();
		
				if($asc){
		
					echo json_encode(array("status" =>"1","id" => $asc));
		
				}else{
					echo json_encode(array("status" => "2","id" => $asc));
		
				}

			}

		}

		
		
	}
	
	function get_edit()
	{
		$id    = $this->input->post('id');
		$jenis    = $this->input->post('jenis');
		$field    = $this->input->post('field');

		if ($jenis == "trs_po") {
			$header =  $this->m_master->get_data_one($jenis, $field, $id)->row();
			// $data = $this->m_master->get_data_one("trs_po_detail", "no_po", $header->no_po)->result();
			$data = $this->db->query("SELECT * FROM trs_po a 
                    JOIN trs_po_detail b ON a.no_po = b.no_po
                    JOIN m_pelanggan c ON a.id_pelanggan=c.id_pelanggan
                    LEFT JOIN m_kab d ON c.kab=d.kab_id
                    LEFT JOIN m_produk e ON b.id_produk=e.id_produk
					WHERE a.no_po = '".$header->no_po."'
				")->result();

		} else if ($jenis == "trs_so_detail") {
			$data =  $this->m_master->query(
				"SELECT * 
                FROM trs_so_detail a
                JOIN m_produk b ON a.id_produk=b.id_produk
                JOIN m_pelanggan c ON a.id_pelanggan=c.id_pelanggan
                WHERE id = '$id' "
			)->row();
		} else if ($jenis == "trs_wo") {
			// $header =  $this->m_master->get_data_one($jenis, $field, $id)->row();
			$header =  $this->db->query("SELECT a.* ,CONCAT(b.no_so,'.',urut_so,'.',rpt) as no_so_1 from $jenis a LEFT JOIN trs_so_detail b ON a.no_so = b.id WHERE a.id='$id' ")->row();
			$detail = $this->m_master->get_data_one("trs_wo_detail", "no_wo", $header->no_wo)->row();

			$data = ["header" => $header, "detail" => $detail];
		} else if ($jenis == "SJ") {
			$header =  $this->m_master->query("SELECT a.*,IFNULL(qty_sj,0)qty_sj FROM trs_po_detail a 
                                    LEFT JOIN 
                                    (
                                    SELECT no_po,kode_mc,SUM(qty) AS qty_sj FROM `trs_surat_jalan` WHERE STATUS <> 'Batal' GROUP BY no_po,kode_mc
                                    )AS t_sj
                                    ON a.`no_po` = t_sj.no_po
                                    AND a.kode_mc = t_sj.kode_mc
                                    WHERE a.no_po ='$id' AND (a.qty - ifnull(qty_sj,0)) <> 0")->result();

			$data = ["header" => $header, "detail" => ""];
		} else if ($jenis == "SJView") {
			$header =  $this->m_master->query("SELECT a.*,IFNULL(qty_sj,0)qty_sj FROM trs_po_detail a 
                                    LEFT JOIN 
                                    (
                                    SELECT no_po,kode_mc,SUM(qty) AS qty_sj FROM `trs_surat_jalan` WHERE STATUS <> 'Batal' GROUP BY no_po,kode_mc
                                    )AS t_sj
                                    ON a.`no_po` = t_sj.no_po
                                    AND a.kode_mc = t_sj.kode_mc
                                    WHERE a.no_po ='$id' ")->result();

			$data = ["header" => $header, "detail" => ""];
		} else {
			$data =  $this->m_master->get_data_one($jenis, $field, $id)->row();
		}
		echo json_encode($data);
	}
	
	function hapus()
	{
		$jenis    = $_POST['jenis'];
		$field    = $_POST['field'];
		$id       = $_POST['id'];

		if ($jenis == "invoice") {
			$no_inv          = $_POST['no_inv'];
			
			// ubah no pl
			$query_cek = $this->db->query("SELECT*FROM invoice_detail where no_invoice ='$no_inv'")->result();

			foreach( $query_cek as $row)
			{
				$db2            = $this->load->database('database_simroll', TRUE);

				if($row->type=='roll'){
					$update_no_pl   = $db2->query("UPDATE pl set no_pl_inv = 0 where id ='$row->id_pl'");					
				}else{
					$update_no_pl   = $db2->query("UPDATE pl_box set no_pl_inv = 0 where id ='$row->id_pl'");					

				}
			}

			if($update_no_pl)
			{

				$result          = $this->m_master->query("DELETE FROM invoice_header WHERE  $field = '$id'");

				$result          = $this->m_master->query("DELETE FROM invoice_detail WHERE  no_invoice = '$no_inv'");
			}
			
			
			
		} else {

			$result = $this->m_master->query("DELETE FROM $jenis WHERE  $field = '$id'");
		}

		echo json_encode($result);
	}

	function Cetak_Invoice()
	{
        $no_invoice = $_GET['no_invoice'];
        $ctk = 0;
        $html = '';

		//////////////////////////////////////// K O P ////////////////////////////////////////

        $data_detail = $this->db->query("SELECT * FROM invoice_header WHERE no_invoice='$no_invoice'")->row();
		$ppnpph = $data_detail->pajak;

		$html .= '<table cellspacing="0" style="font-size:11px;color:#000;border-collapse:collapse;vertical-align:top;width:100%;text-align:center;font-weight:bold;font-family:"Trebuchet MS", Helvetica, sans-serif">';

        if($ppnpph == 'nonppn'){
            $html .= '<tr>
                <th style="border:0;height:92px"></th>
            </tr>
            <tr>
                <td style="background:#ddd;border:1px solid #000;padding:6px;font-size:14px !important">INVOICE</td>
            </tr>';
            $html .= '</table>';
        }else{
            $html .= '<tr>
                <th style="border:0;width:15%;height:0"></th>
                <th style="border:0;width:55%;height:0"></th>
                <th style="border:0;width:25%;height:0"></th>
            </tr>

            <tr>
				<td rowspan="3" align="center">
					<img src="' . base_url() . 'assets/gambar/ppi.png"  width="80" height="70" />
				</td>
		   
                <td style="font-size:20px;" align="left">PT. PRIMA PAPER INDONESIA</td>

            </tr>
            <tr>
                <td style="font-size:11px" align="left">Dusun Timang Kulon, Desa Wonokerto, Kec.Wonogiri, Kab.Wonogiri</td>
                <td></td>
            </tr>
            <tr>
                <td style="font-size:11px;" align="left">WONOGIRI - JAWA TENGAH - INDONESIA Kode Pos 57615</td>
                <td style=""></td>
            </tr>
			<tr><td>&nbsp;<br></td></tr>';
            $html .= '</table>';

            $html .= '<table cellspacing="0" style="font-size:11px;color:#000;border-collapse:collapse;vertical-align:top;width:100%;text-align:center;font-weight:bold;font-family:"Trebuchet MS", Helvetica, sans-serif">
            <tr>
                <th style="height:0"></th>
            </tr>
            <tr>
                <td style="background:#ddd;border:1px solid #000;padding:6px;font-size:14px !important">INVOICE</td>
            </tr>';
            $html .= '</table>';
        }       

		//////////////////////////////////////// D E T A I L //////////////////////////////////////

        $html .= '<table cellspacing="0" style="font-size:11px;color:#000;border-collapse:collapse;vertical-align:top;width:100%;font-family:"Trebuchet MS", Helvetica, sans-serif">
        <tr>
            <th style="border:0;padding:2px 0;height:0;width:14%"></th>
            <th style="border:0;padding:2px 0;height:0;width:1%"></th>
            <th style="border:0;padding:2px 0;height:0;width:40%"></th>
            <th style="border:0;padding:2px 0;height:0;width:12%"></th>
            <th style="border:0;padding:2px 0;height:0;width:1%"></th>
            <th style="border:0;padding:2px 0;height:0;width:32%"></th>
        </tr>';

        $html .= '
        <tr>
            <td colspan="3"></td>
            <td style="padding:3px 0 20px;font-weight:bold">NOMOR</td>
            <td style="padding:3px 0 20px;font-weight:bold">:</td>
            <td style="padding:3px 0 20px;font-weight:bold">'.$data_detail->no_invoice.'</td>
        </tr>
        <tr>
            <td style="padding:3px 0">Nama Perusahaan</td>
            <td style="padding:3px 0">:</td>
            <td style="padding:0 3px 0 0;line-height:1.8">'.$data_detail->nm_perusahaan.'</td>
            <td style="padding:3px 0;font-weight:bold">Jatuh Tempo</td>
            <td style="padding:3px 0">:</td>
            <td style="padding:3px 0;font-weight:bold;color:#f00">'.$this->m_fungsi->tanggal_format_indonesia($data_detail->tgl_jatuh_tempo).'</td>
        </tr>';

		$html .= '<tr>
			<td style="padding:3px 0">Alamat</td>
			<td style="padding:3px 0">:</td>
			<td style="padding:0 3px 0 0;line-height:1.8">'.$data_detail->alamat_perusahaan.'</td>
			<td style="padding:3px 0">No. PO</td>
			<td style="padding:3px 0">:</td>
			<td style="padding:0;line-height:1.8">';

			// KONDISI JIKA LEBIH DARI 1 PO
			$result_po = $this->db->query("SELECT * FROM invoice_detail WHERE no_invoice='$no_invoice' GROUP BY no_po ORDER BY no_po");
			if($result_po->num_rows() == '1'){
				$html .= $result_po->row()->no_po;;
			}else{
				foreach($result_po->result() as $r){
					$html .= $r->no_po.'<br/>';
				}
			}
		$html .= '</td>
		</tr>';

        $html .= '<tr>
            <td style="padding:3px 0">Kepada</td>
            <td style="padding:3px 0">:</td>
            <td style="padding:0 3px 0 0;line-height:1.8">'.$data_detail->kepada.'</td>
            <td style="padding:3px 0">No. Surat Jalan</td>
            <td style="padding:3px 0">:</td>
            <td style="padding:0;line-height:1.8">';

			// KONDISI JIKA LEBIH DARI 1 SURAT JALAN
			$result_sj = $this->db->query("SELECT * FROM invoice_detail WHERE no_invoice='$no_invoice' GROUP BY no_surat ORDER BY no_surat");
			if($result_sj->num_rows() == '1'){
				$html .= $result_sj->row()->no_surat;;
			}else{
				foreach($result_sj->result() as $r){
					$html .= $r->no_surat.'<br/>';
				}
			}
		$html .= '</td>
		</tr>';

        $html .= '</table>';

		/////////////////////////////////////////////// I S I ///////////////////////////////////////////////

        $html .= '<table cellspacing="0" style="font-size:11px;color:#000;border-collapse:collapse;vertical-align:top;width:100%;font-family:"Trebuchet MS", Helvetica, sans-serif">
        <tr>
            <th style="border:0;height:15px;width:30%"></th>
            <th style="border:0;height:15px;width:10%"></th>
            <th style="border:0;height:15px;width:15%"></th>
            <th style="border:0;height:15px;width:7%"></th>
            <th style="border:0;height:15px;width:10%"></th>
            <th style="border:0;height:15px;width:8%"></th>
            <th style="border:0;height:15px;width:20%"></th>
        </tr>';

        $html .= '<tr>
            <td style="border:1px solid #000;border-width:2px 0;padding:5px 0;text-align:center;font-weight:bold">NAMA BARANG</td>
            <td style="border:1px solid #000;border-width:2px 0;padding:5px 0;text-align:center;font-weight:bold">SATUAN</td>
            <td style="border:1px solid #000;border-width:2px 0;padding:5px 0;text-align:center;font-weight:bold">JUMLAH</td>
            <td style="border:1px solid #000;border-width:2px 0;padding:5px 0;text-align:center;font-weight:bold" colspan="2">HARGA</td>
            <td style="border:1px solid #000;border-width:2px 0;padding:5px 0;text-align:center;font-weight:bold" colspan="2">TOTAL</td>
        </tr>';
		$html .= '<tr>
			<td style="border:0;padding:20px 0 0" colspan="7"></td>
		</tr>';
		
		if($data_detail->type== 'roll')
		{
			$sqlLabel = $this->db->query("SELECT*FROM invoice_detail WHERE no_invoice='$no_invoice' GROUP BY nm_ker DESC,g_label ASC,no_po");
			// TAMPILKAN DULU LABEL
			$totalHarga = 0;
			foreach($sqlLabel->result() as $label){

				if($label->nm_ker == 'MH'){
					$jnsKertas = 'KERTAS MEDIUM';
				}else if($label->nm_ker == 'WP'){
					$jnsKertas = 'KERTAS COKLAT';
				}else if($label->nm_ker == 'BK'){
					$jnsKertas = 'KERTAS B-KRAFT';
				}else if($label->nm_ker == 'MEDIUM LINER'){
					$jnsKertas = 'KERTAS MEDIUM LINER';
				}else if($label->nm_ker == 'MH COLOR'){
					$jnsKertas = 'KERTAS MEDIUM COLOR';
				}else if($label->nm_ker == 'MN'){
					$jnsKertas = 'KERTAS MEDIUM NON SPEK';
				}else{
					$jnsKertas = '';
				}
				$html .= '<tr>
					<td style="border:0;padding:5px 0" colspan="7">'.$jnsKertas.' ROLL '.$label->g_label.' GSM</td>
				</tr>';

				// TAMPILKAN ITEMNYA
				$weightNmLbPo = 0;
				$sqlWidth = $this->db->query("SELECT*FROM invoice_detail
				WHERE no_invoice='$label->no_invoice' AND nm_ker='$label->nm_ker' AND g_label='$label->g_label' AND no_po='$label->no_po'
				ORDER BY width ASC");
				foreach($sqlWidth->result() as $items){
					// BERAT SESETAN
					$qty        = $items->qty - $items->retur_qty;
					$fixBerat   = $items->weight - $items->seset;
					$html .= '<tr>
						<td style="border:0;padding:5px 0">LB '.round($items->width,2).' = '.$qty.' ROLL</td>
						<td style="border:0;padding:5px 0;text-align:center">KG</td>
						<td style="border:0;padding:5px 0;text-align:right">'.number_format($fixBerat, 0, ",", ".").'</td>
						<td style="border:0;padding:5px 0" colspan="4"></td>
					</tr>';

					// TOTAL BERAT PER GSM - LABEL - PO
					$weightNmLbPo += $fixBerat;
				}

				// CARI HARGANYA
				$sqlHargaPo = $this->db->query("SELECT*FROM invoice_detail
				WHERE no_invoice='$label->no_invoice' AND nm_ker='$label->nm_ker' AND g_label='$label->g_label' AND no_po='$label->no_po'")->row();
				// PERKALIAN ANTARA TOTAL BERAT DAN HARGA PO
				$weightXPo = round($weightNmLbPo * $sqlHargaPo->harga);
				$html .= '<tr>
					<td style="border:0;padding:5px 0" colspan="2"></td>
					<td style="border-top:1px solid #000;padding:5px 0;text-align:right">'.number_format($weightNmLbPo, 0, ",", ".").'</td>
					<td style="border-top:1px solid #000;padding:5px 0 0 15px;text-align:right">Rp</td>
					<td style="border-top:1px solid #000;padding:5px 0;text-align:right">'.number_format($sqlHargaPo->harga, 0, ",", ".").'</td>
					<td style="border:0;padding:5px 0 0 15px;text-align:right">Rp</td>
					<td style="border:0;padding:5px 0;text-align:right">'.number_format($weightXPo, 0, ",", ".").'</td>
				</tr>';

				$totalHarga += $weightXPo;
			}

		}else{

			$sqlLabel = $this->db->query("SELECT*FROM invoice_detail WHERE no_invoice='$no_invoice' GROUP BY nm_ker DESC,g_label ASC,no_po");
			// TAMPILKAN DULU LABEL
			$totalHarga = 0;
			foreach($sqlLabel->result() as $label){

				$ukuran         = str_replace("X","x",$label->g_label);
				$total_harga    = round(($label->qty - $label->retur_qty) * $label->harga);

				$html .= '<tr>
					<td style="padding:5px 0">'.$label->nm_ker.' &nbsp;'.$ukuran.' &nbsp;'. $label->kualitas.'</td>
					<td style="padding:5px 0;text-align:center"> PCS</td>
					<td style="solid #000;padding:5px 0;text-align:right">'. number_format(($label->qty-$label->retur_qty), 0, ",", ".").'</td>
					<td style="solid #000;padding:5px 0 0 15px;text-align:right">Rp</td>
					<td style="solid #000;padding:5px 0;text-align:right">'. number_format($label->harga, 0, ",", ".").'</td>
					<td style="padding:5px 0 0 15px;text-align:right">Rp</td>
					<td style="padding:5px 0;text-align:right">'.number_format($total_harga, 0, ",", ".") .'</td>
				</tr>';


				$totalHarga += $total_harga;
			}
			

		}
		
		
		// T O T A L //
		$html .= '<tr>
			<td style="border:0;padding:20px 0 0" colspan="7"></td>
		</tr>';

        // RUMUS
		if($ppnpph == 'ppn'){ // PPN 10 %
			if($data_detail->inc_exc=='Include')
			{
				$terbilang = round($totalHarga);
			}else if($data_detail->inc_exc=='Exclude')
			{
				$terbilang = round($totalHarga + (0.11 * $totalHarga));
			}else{
				$terbilang = '';
			}


			$rowspan = 3;
		}else if($ppnpph == 'ppn_pph'){ // PPH22

			if($data_detail->inc_exc=='Include')
			{
				$terbilang = round($totalHarga + (0.011 * $totalHarga));
			}else if($data_detail->inc_exc=='Exclude')
			{
				$terbilang = round($totalHarga + (0.11 * $totalHarga) + (0.011 * $totalHarga));
			}else{
				$terbilang = '';
			}
			
			$rowspan = 4;
		}else{ // NON
			$terbilang = $totalHarga;
			$rowspan = 2;
		}

		$html .= '<tr>
			<td style="border-width:2px 0;border:1px solid;font-weight:bold;padding:5px 0;line-height:1.8;text-transform:uppercase" colspan="3" rowspan="'.$rowspan.'">Terbilang :<br/><b><i>'.$this->m_fungsi->terbilang($terbilang).'</i></b></td>

			<td style="border-top:2px solid #000;font-weight:bold;padding:5px 0 0 15px" colspan="2">Sub Total</td>

			<td style="border-top:2px solid #000;font-weight:bold;padding:5px 0 0 15px">Rp</td>

			<td style="border-top:2px solid #000;font-weight:bold;padding:5px 0;text-align:right">'.number_format($totalHarga, 0, ",", ".").'</td>
		</tr>';

		// PPN - PPH22
		$ppn11 = 0.11 * $totalHarga;
        $pph22 = 0.011 * $totalHarga;
		if($data_detail->pajak=='ppn')
		{
			if($data_detail->inc_exc=='Include')
			{
				$nominal = 'KB';
			}else if($data_detail->inc_exc=='Exclude')
			{				
				$nominal = number_format($ppn11, 0, ",", ".");
			}else{
				$nominal = '';
			}

		}else{
			if($data_detail->inc_exc=='Include')
			{
				$nominal = 'KB';
			}else if($data_detail->inc_exc=='Exclude')
			{
				$nominal = number_format($ppn11, 0, ",", ".") ;
			}else{
				$nominal = '';
			}
		}
		$txtppn11 = '<tr>
				<td style="border:0;font-weight:bold;padding:5px 0 0 15px" colspan="2">Ppn 11%</td>
				<td style="border:0;font-weight:bold;padding:5px 0 0 15px">Rp</td>
				<td style="border:0;font-weight:bold;padding:5px 0;text-align:right">'.$nominal.'</td>
			</tr>';

		if($ppnpph == 'ppn'){ // PPN 10 %
			$html .= $txtppn11;
		}else if($ppnpph == 'ppn_pph'){ // PPH22
			// pph22
			$html .= $txtppn11.'<tr>
				<td style="border:0;font-weight:bold;padding:5px 0 0 15px" colspan="2">Pph 22</td>
				<td style="border:0;font-weight:bold;padding:5px 0 0 15px">Rp</td>
				<td style="border:0;font-weight:bold;padding:5px 0;text-align:right">'.number_format($pph22, 0, ",", ".").'</td>
			</tr>';
		}else{
			$html .= '';
		}

		$html .= '<tr>
			<td style="border-bottom:2px solid #000;font-weight:bold;padding:5px 0 0 15px" colspan="2">Total</td>
			<td style="border-bottom:2px solid #000;font-weight:bold;padding:5px 0 0 15px">Rp</td>
			<td style="border-bottom:2px solid #000;font-weight:bold;padding:5px 0;text-align:right">'.number_format($terbilang, 0, ",", ".").'</td>
		</tr>';

		//////////////////////////////////////////////// T T D ////////////////////////////////////////////////
		
		$html .= '<tr>
			<td style="border:0;padding:20px 0 0" colspan="7"></td>
		</tr>';

		if($data_detail->bank=='BNI')
		{
			if($data_detail->pajak=='nonppn')
			{
				$norek='5758699099';
			}else{
				$norek='5758699690';
			}
		}else{
			if($data_detail->pajak=='nonppn')
			{
				$norek='078 795 5758';
			}else{
				$norek='078 027 5758';
			}
		}
		$html .= '<tr>
			<td style="border:0;padding:5px" colspan="3"></td>
			<td style="border:0;padding:5px;text-align:center" colspan="4">Wonogiri, '.$this->m_fungsi->tanggal_format_indonesia($data_detail->tgl_invoice).'</td> 
		</tr>
		<tr>
			<td style="border:0;padding:0 0 15px;line-height:1.8" colspan="3">Pembayaran Full Amount ditransfer ke :<br/>'.$data_detail->bank.' '.$norek.' (CABANG SOLO)<br/>A.n PT. PRIMA PAPER INDONESIA</td>
			<td style="border:0;padding:0" colspan="4"></td>
		</tr>
		<tr>
			<td style="border:0;padding:0;line-height:1.8" colspan="3">* Harap bukti transfer di email ke</td>
			<td style="border-bottom:1px solid #000;padding:0" colspan="4"></td>
		</tr>
		<tr>
			<td style="border:0;padding:0;line-height:1.8" colspan="3">primapaperin@gmail.com / bethppi@yahoo.co.id</td>
			<td style="border:0;padding:0;line-height:1.8;text-align:center" colspan="4">Finance</td>
		</tr>
		';

        $html .= '</table>';

        // $this->m_fungsi->newPDF($html,'P',77,0);
		$this->m_fungsi->_mpdf_hari('P', 'A4', 'INVOICE', $html, 'INVOICE.pdf', 5, 5, 5, 10);
		// echo $html;

    }
	
	// function Cetak_Invoice2()
	// {
    //     $no_invoice = $_GET['no_invoice'];
    //     $ctk = 0;
    //     $html = '';

	// 	//////////////////////////////////////// K O P ////////////////////////////////////////

    //     $data_detail = $this->db->query("SELECT * FROM invoice_header WHERE no_invoice='$no_invoice'")->row();
	// 	$ppnpph = $data_detail->pajak;

	// 	$html .= '<table cellspacing="0" style="font-size:11px;color:#000;border-collapse:collapse;vertical-align:top;width:100%;text-align:center;font-weight:bold;font-family:"Trebuchet MS", Helvetica, sans-serif" border="0">';

    //     if($ppnpph == 'nonppn'){
    //         $html .= '<tr>
    //             <th style="border:0;height:92px"></th>
    //         </tr>
    //         <tr>
    //             <td style="background:#ddd;border:1px solid #000;padding:6px;font-size:14px !important">INVOICE</td>
    //         </tr>';
    //         $html .= '</table>';
    //     }else{
    //         $html .= '<tr>
    //             <th style="border:0;width:55%;height:0"></th>
    //             <th style="border:0;width:15%;height:0"></th>
    //             <th style="border:0;width:25%;height:0"></th>
    //         </tr>

    //         <tr>
		   
    //             <td style="font-size:20px;" align="left">IPUNG IRAWAN</td>

    //         </tr>
    //         <tr>
    //             <td style="font-size:11px" align="left">SURAKARTA</td>
    //             <td></td>
    //         </tr>
    //         <tr>
    //             <td style="font-size:11px;" align="left"></td>
    //             <td style=""></td>
    //         </tr>
	// 		<tr><td>&nbsp;<br></td></tr>';
    //         $html .= '</table>';

    //         $html .= '<table cellspacing="0" style="font-size:11px;color:#000;border-collapse:collapse;vertical-align:top;width:100%;text-align:center;font-weight:bold;font-family:"Trebuchet MS", Helvetica, sans-serif">
    //         <tr>
    //             <th style="height:0"></th>
    //         </tr>
    //         <tr>
    //             <td style="background:#ddd;border:1px solid #000;padding:6px;font-size:14px !important">INVOICE</td>
    //         </tr>';
    //         $html .= '</table>';
    //     }       

	// 	//////////////////////////////////////// D E T A I L //////////////////////////////////////

    //     $html .= '<table cellspacing="0" style="font-size:11px;color:#000;border-collapse:collapse;vertical-align:top;width:100%;font-family:"Trebuchet MS", Helvetica, sans-serif">
    //     <tr>
    //         <th style="border:0;padding:2px 0;height:0;width:14%"></th>
    //         <th style="border:0;padding:2px 0;height:0;width:1%"></th>
    //         <th style="border:0;padding:2px 0;height:0;width:40%"></th>
    //         <th style="border:0;padding:2px 0;height:0;width:12%"></th>
    //         <th style="border:0;padding:2px 0;height:0;width:1%"></th>
    //         <th style="border:0;padding:2px 0;height:0;width:32%"></th>
    //     </tr>';

    //     $html .= '
    //     <tr>
    //         <td style="padding:3px 0">Nama Perusahaan</td>
    //         <td style="padding:3px 0">:</td>
    //         <td style="padding:0 3px 0 0;line-height:1.8">PT. Lintas Data Prima</td>
    //         <td style="padding:3px 0;">Tgl Invoice</td>
    //         <td style="padding:3px 0">:</td>
    //         <td style="padding:3px 0;;color:#f00">03 Januari 2024</td>
    //     </tr>';

	// 	$html .= '<tr>
	// 		<td style="padding:3px 0">Alamat</td>
	// 		<td style="padding:3px 0">:</td>
	// 		<td style="padding:0 3px 0 0;line-height:1.8">Jl. Mangesti Raya Jl. Springville Residence No.1, Dusun II, Waru, Kec. Baki, Kabupaten Sukoharjo, Jawa Tengah 57556</td>
	// 		<td style="padding:3px 0">No. Invoice</td>
	// 		<td style="padding:3px 0">:</td>
	// 		<td style="padding:0;line-height:1.8">035/IPKN/I/24';

	// 	$html .= '</td>
	// 	</tr>';


	// 		// KONDISI JIKA LEBIH DARI 1 SURAT JALAN
	// 		$result_sj = $this->db->query("SELECT * FROM invoice_detail WHERE no_invoice='$no_invoice' GROUP BY no_surat ORDER BY no_surat");
	// 		if($result_sj->num_rows() == '1'){
	// 			$html .= $result_sj->row()->no_surat;;
	// 		}else{
	// 			foreach($result_sj->result() as $r){
	// 				$html .= $r->no_surat.'<br/>';
	// 			}
	// 		}
	// 	$html .= '</td>
	// 	</tr>';

    //     $html .= '</table>';

	// 	/////////////////////////////////////////////// I S I ///////////////////////////////////////////////

    //     $html .= '<table cellspacing="0" style="font-size:11px;color:#000;border-collapse:collapse;vertical-align:top;width:100%;font-family:"Trebuchet MS", Helvetica, sans-serif">
    //     <tr>
    //         <th style="border:0;height:15px;width:30%"></th>
    //         <th style="border:0;height:15px;width:10%"></th>
    //         <th style="border:0;height:15px;width:15%"></th>
    //         <th style="border:0;height:15px;width:7%"></th>
    //         <th style="border:0;height:15px;width:10%"></th>
    //         <th style="border:0;height:15px;width:8%"></th>
    //         <th style="border:0;height:15px;width:20%"></th>
    //     </tr>';

    //     $html .= '<tr>
    //         <td style="border:1px solid #000;border-width:2px 0;padding:5px 0;text-align:center;font-weight:bold">NAMA BARANG</td>
    //         <td style="border:1px solid #000;border-width:2px 0;padding:5px 0;text-align:center;font-weight:bold">JUMLAH</td>			
    //         <td style="border:1px solid #000;border-width:2px 0;padding:5px 0;text-align:center;font-weight:bold">SATUAN</td>
    //         <td style="border:1px solid #000;border-width:2px 0;padding:5px 0;text-align:center;font-weight:bold" colspan="2">HARGA</td>
    //         <td style="border:1px solid #000;border-width:2px 0;padding:5px 0;text-align:center;font-weight:bold" colspan="2">TOTAL</td>
    //     </tr>';
	// 	$html .= '<tr>
	// 		<td style="border:0;padding:20px 0 0" colspan="7"></td>
	// 	</tr>';
		
	// 		$sqlLabel = $this->db->query("SELECT*FROM invoice_detail WHERE no_invoice='$no_invoice' GROUP BY nm_ker DESC,g_label ASC,no_po");
	// 		// TAMPILKAN DULU LABEL
	// 		$totalHarga = 0;
	// 		foreach($sqlLabel->result() as $label){

	// 			$ukuran         = str_replace("X","x",$label->g_label);
	// 			$total_harga    = round(($label->qty - $label->retur_qty) * $label->harga);

	// 			$html .= '<tr>
	// 				<td style="padding:5px 0">Kabel FO 48 core1</td>
	// 				<td style="solid #000;padding:5px 0;text-align:right">500</td>
	// 				<td style="padding:5px 0;text-align:center"> Meter</td>
	// 				<td style="solid #000;padding:5px 0 0 15px;text-align:right">Rp</td>
	// 				<td style="solid #000;padding:5px 0;text-align:right">'. number_format(8000, 0, ",", ".").'</td>
	// 				<td style="padding:5px 0 0 15px;text-align:right">Rp</td>
	// 				<td style="padding:5px 0;text-align:right">'.number_format(4000000, 0, ",", ".") .'</td>
	// 			</tr>';


	// 			$totalHarga = 4000000;
	// 		}
			

		
		
		
	// 	// T O T A L //
	// 	$html .= '<tr>
	// 		<td style="border:0;padding:20px 0 0" colspan="7"></td>
	// 	</tr>';

    //     // RUMUS
	// 	// PPN 10 %
	// 			$terbilang = round($totalHarga);
	// 			// $terbilang = round($totalHarga + (0.11 * $totalHarga));


	// 		$rowspan = 3;
		

	// 	$html .= '<tr>
	// 		<td style="border-width:2px 0;border:1px solid;font-weight:bold;padding:5px 0;line-height:1.8;text-transform:uppercase" colspan="3" rowspan="'.$rowspan.'">Terbilang :<br/><b><i>'.$this->m_fungsi->terbilang($terbilang).'</i></b></td>

	// 		<td style="border-top:2px solid #000;font-weight:bold;padding:5px 0 0 15px" colspan="2">Sub Total</td>

	// 		<td style="border-top:2px solid #000;font-weight:bold;padding:5px 0 0 15px">Rp</td>

	// 		<td style="border-top:2px solid #000;font-weight:bold;padding:5px 0;text-align:right">'.number_format($totalHarga, 0, ",", ".").'</td>
	// 	</tr>';

	// 	// PPN - PPH22
	// 			$nominal = 0;
	// 	$txtppn11 = '<tr>
	// 			<td style="border:0;font-weight:bold;padding:5px 0 0 15px" colspan="2">Ppn 11%</td>
	// 			<td style="border:0;font-weight:bold;padding:5px 0 0 15px">Rp</td>
	// 			<td style="border:0;font-weight:bold;padding:5px 0;text-align:right">'.$nominal.'</td>
	// 		</tr>';

	// 	if($ppnpph == 'ppn'){ // PPN 10 %
	// 		$html .= $txtppn11;
	// 	}else if($ppnpph == 'ppn_pph'){ // PPH22
	// 		// pph22
	// 		$html .= $txtppn11.'<tr>
	// 			<td style="border:0;font-weight:bold;padding:5px 0 0 15px" colspan="2">Pph 22</td>
	// 			<td style="border:0;font-weight:bold;padding:5px 0 0 15px">Rp</td>
	// 			<td style="border:0;font-weight:bold;padding:5px 0;text-align:right">'.number_format($pph22, 0, ",", ".").'</td>
	// 		</tr>';
	// 	}else{
	// 		$html .= '';
	// 	}

	// 	$html .= '<tr>
	// 		<td style="border-bottom:2px solid #000;font-weight:bold;padding:5px 0 0 15px" colspan="2">Total</td>
	// 		<td style="border-bottom:2px solid #000;font-weight:bold;padding:5px 0 0 15px">Rp</td>
	// 		<td style="border-bottom:2px solid #000;font-weight:bold;padding:5px 0;text-align:right">'.number_format($terbilang, 0, ",", ".").'</td>
	// 	</tr>';

	// 	//////////////////////////////////////////////// T T D ////////////////////////////////////////////////
		
	// 	$html .= '<tr>
	// 		<td style="border:0;padding:20px 0 0" colspan="7"></td>
	// 	</tr>';

    //     $html .= '</table>';

    //     // $this->m_fungsi->newPDF($html,'P',77,0);
	// 	$this->m_fungsi->_mpdf_hari2('P', 'A4', 'INVOICE', $html, 'INVOICE.pdf', 5, 5, 5, 10);
	// 	// echo $html;

    // }

	public function coba_api()
	{
		$curl = curl_init();

		curl_setopt_array($curl, array(
		CURLOPT_URL => "https://api.rajaongkir.com/starter/province?id=12",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "GET",
		CURLOPT_HTTPHEADER => array(
			"key: c479d0aa6880c0337184539462eeec6f"
		),
		));

		$response   = curl_exec($curl);
		$err        = curl_error($curl);

		curl_close($curl);

		if ($err) {
			echo "cURL Error #:" . $err;
		} else {
			// echo $response;
			echo json_encode($response);
		}
	}

	//

	function Gudang()
	{
		$data_header = array(
			'judul' => "Gudang",
		);

		$this->load->view('header', $data_header);

		$jenis = $this->uri->segment(3);
		if($jenis == 'Add'){
			if(in_array($this->session->userdata('level'), ['Admin','Gudang'])){
				$this->load->view('Logistik/v_gudang_add');
			}else{
				$this->load->view('home');
			}
		}else{
			if(in_array($this->session->userdata('level'), ['Admin', 'Gudang'])){
				$this->load->view('Logistik/v_gudang');
			}else{
				$this->load->view('home');
			}
		}


		$this->load->view('footer');
	}

	function LoaDataGudang()
	{
		$data = array();
		$query = $this->db->query("SELECT i.kategori,p.nm_pelanggan,i.nm_produk,SUM(gd_good_qty) AS qty,g.* FROM m_gudang g
		INNER JOIN m_pelanggan p ON g.gd_id_pelanggan=p.id_pelanggan
		INNER JOIN m_produk i ON g.gd_id_produk=i.id_produk
		WHERE g.gd_cek_spv='Close' AND g.gd_status='Open'
		GROUP BY g.gd_id_pelanggan,g.gd_id_produk
		ORDER BY p.nm_pelanggan,i.nm_produk")->result();
		$i = 0;
		foreach ($query as $r) {
			$i++;
			$row = array();
			($r->kategori == 'K_BOX') ? $kategori = 'BOX' : $kategori = 'SHEET';
			$row[] = '<div style="text-align:center">'.$i.'</div>';
			$row[] = '<a href="javascript:void(0)" style="color:#212529" onclick="rincianDataGudang('."'".$r->gd_id_pelanggan."'".','."'".$r->gd_id_produk."'".','."'".$r->nm_pelanggan."'".','."'".$r->nm_produk."'".')">'.$r->nm_pelanggan.'</a>';
			$row[] = '<a href="javascript:void(0)" style="color:#212529" onclick="rincianDataGudang('."'".$r->gd_id_pelanggan."'".','."'".$r->gd_id_produk."'".','."'".$r->nm_pelanggan."'".','."'".$r->nm_produk."'".')">'.$kategori.'</a>';
			$row[] = '<a href="javascript:void(0)" style="color:#212529" onclick="rincianDataGudang('."'".$r->gd_id_pelanggan."'".','."'".$r->gd_id_produk."'".','."'".$r->nm_pelanggan."'".','."'".$r->nm_produk."'".')">'.$r->nm_produk.'</a>';

			// KIRIMAN
			$queryKiriman = $this->db->query("SELECT 
			(SELECT SUM(r.qty_muat) FROM m_rencana_kirim r
			WHERE g.id_gudang=r.id_gudang AND g.gd_id_pelanggan=r.id_pelanggan AND g.gd_id_produk=r.id_produk AND rk_status='Close' AND id_pl_box IS NOT NULL
			GROUP BY id_pelanggan,id_produk) AS qty_muat,
			g.* FROM m_gudang g
			WHERE g.gd_cek_spv='Close' AND g.gd_status='Open' AND gd_id_pelanggan='$r->gd_id_pelanggan' AND g.gd_id_produk='$r->gd_id_produk'");
			$kiriman = 0;
			foreach($queryKiriman->result() as $kir){
				$kiriman += ($kir->qty_muat == null) ? 0 : $kir->qty_muat;
			}
			$row[] = '<div style="text-align:right"><a href="javascript:void(0)" style="color:#212529;font-weight:bold" onclick="rincianDataGudang('."'".$r->gd_id_pelanggan."'".','."'".$r->gd_id_produk."'".','."'".$r->nm_pelanggan."'".','."'".$r->nm_produk."'".')">'.number_format($r->qty - $kiriman,0,",",".").'</a></div>';
			$data[] = $row;
		}

		$output = array(
			"data" => $data,
		);
		echo json_encode($output);
	}

	function rincianDataGudang()
	{
		$gd_id_pelanggan = $_POST["gd_id_pelanggan"];
		$gd_id_produk = $_POST["gd_id_produk"];
		$html = '';

		$html .= '<table style="margin:0;border:0">
			<tr style="background:#dee2e6">
				<th style="padding:6px;text-align:center;border:1px solid #bbb">PLAN</th>
				<th style="padding:6px;text-align:center;border:1px solid #bbb">QTY GUDANG</th>
				<th style="padding:6px;text-align:center;border:1px solid #bbb">BB</th>
				<th style="padding:6px;text-align:center;border:1px solid #bbb">TONASE</th>
			</tr>';

			$getKodePO = $this->db->query("SELECT w.kode_po,g.* FROM m_gudang g
			INNER JOIN trs_wo w ON g.gd_id_trs_wo=w.id
			WHERE g.gd_id_pelanggan='$gd_id_pelanggan' AND g.gd_id_produk='$gd_id_produk' AND g.gd_cek_spv='Close'
			GROUP BY w.kode_po,g.gd_status");
			$sumAllQty = 0;
			$sumAllTon = 0;
			foreach($getKodePO->result() as $r){
				($r->gd_status == 'Open') ? $close = 'onclick="closeGudang('."'".$r->kode_po."'".','."'".$r->id_gudang."'".')"' : $close = 'disabled' ;
				$html .='<tr>
					<td style="background:#dee2e6;padding:6px;font-weight:bold;border:1px solid #bbb" colspan="3">'.$r->kode_po.'</td>
					<td style="background:#dee2e6;padding:6px;font-weight:bold;border:1px solid #bbb;text-align:center">
						<button type="button" class="btn btn-xs btn-danger" style="font-weight:bold" '.$close.'>close</button>
					</td>
				</tr>';

				$getIsi = $this->db->query("SELECT w.kode_po,g.*,c.* FROM m_gudang g
				INNER JOIN plan_cor c ON g.gd_id_plan_cor=c.id_plan
				INNER JOIN trs_wo w ON g.gd_id_trs_wo=w.id
				WHERE w.kode_po='$r->kode_po' AND g.gd_id_produk='$r->gd_id_produk' AND g.gd_cek_spv='Close'");
				$sumIsiQty = 0;
				$sumIsiTon = 0;
				foreach($getIsi->result() as $isi){
					if($isi->gd_id_plan_cor != null && $isi->gd_id_plan_flexo == null && $isi->gd_id_plan_finishing == null){
						$rcr = ';border-radius:4px';
						$rfx = '';
						$rfs = '';
					}else if($isi->gd_id_plan_cor != null && $isi->gd_id_plan_flexo != null && $isi->gd_id_plan_finishing == null){
						$rcr = ';border-radius:4px 0 0 4px';
						$rfx = ';border-radius:0 4px 4px 0';
						$rfs = '';
					}else{
						$rcr = ';border-radius:4px 0 0 4px';
						$rfx = '';
						$rfs = ';border-radius:0 4px 4px 0';
					}
					$shift = $isi->shift_plan;
					$mesin = str_replace('CORR', '', $isi->machine_plan);
					($isi->gd_id_plan_flexo == null) ? $fx = '' : $fx = '<span class="bg-secondary" style="vertical-align:top;font-weight:bold;padding:2px 4px;font-size:12px'.$rfx.'">FX</span>';
					($isi->gd_id_plan_finishing == null) ? $fs = '' : $fs = '<span class="bg-secondary" style="vertical-align:top;font-weight:bold;padding:2px 4px;font-size:12px'.$rfs.'">FS</span>';
					$bb = round($isi->gd_good_qty * $isi->gd_berat_box);
					$html .='<tr>
						<td style="border:1px solid #dee2e6;padding:6px">['.$shift.'.'.$mesin.'] '.substr($this->m_fungsi->getHariIni($isi->tgl_plan),0,3).', '.$this->m_fungsi->tglIndSkt($isi->tgl_plan).' <span class="bg-secondary" style="vertical-align:top;font-weight:bold;padding:2px 4px;font-size:12px'.$rcr.'">CR</span>'.$fx.''.$fs.'</td>
						<td style="border:1px solid #dee2e6;padding:6px;text-align:right">'.number_format($isi->gd_good_qty,0,",",".").'</td>
						<td style="border:1px solid #dee2e6;padding:6px;text-align:right">'.$isi->gd_berat_box.'</td>
						<td style="border:1px solid #dee2e6;padding:6px;text-align:right">'.number_format($bb,0,",",".").'</td>
					</tr>';

					$getKiriman = $this->db->query("SELECT*FROM m_rencana_kirim r
					INNER JOIN pl_box p ON r.id_pl_box=p.id AND r.rk_urut=p.no_pl_urut
					WHERE r.rk_kode_po='$isi->kode_po' AND r.id_gudang='$isi->id_gudang'
					AND r.rk_status='Close' AND r.id_pl_box IS NOT NULL");
					if($getKiriman->num_rows() == 0){
						$html .='';
						$qtyAkhir = ($isi->gd_status == 'Open') ? $isi->gd_good_qty : 0;
					}else{
						$qty_kirim = 0;
						foreach($getKiriman->result() as $kir){
							$html .='<tr>
								<td style="border:1px solid #dee2e6;padding:6px"><b>-</b> '.$this->m_fungsi->tglIndSkt($kir->tgl).' - '.$kir->no_surat.' - '.$kir->no_kendaraan.'</td>
								<td style="border:1px solid #dee2e6;padding:6px;text-align:right">-'.number_format($kir->qty_muat,0,",",".").'</td>
								<td style="border:1px solid #dee2e6;padding:6px;text-align:right" colspan="2"></td>
							</tr>';
							$qty_kirim += $kir->qty_muat;
						}

						$qtyAkhir = ($isi->gd_status == 'Open') ? $isi->gd_good_qty - $qty_kirim : 0;
						$html .='<tr>
							<td style="border:1px solid #dee2e6;padding:6px;text-align:right;font-weight:bold">SISA</td>
							<td style="border:1px solid #dee2e6;padding:6px;text-align:right;font-weight:bold">'.number_format($isi->gd_good_qty - $qty_kirim,0,",",".").'</td>
							<td style="border:1px solid #dee2e6;padding:6px" colspan="2"></td>
						</tr>';
					}

					$sumIsiQty += $qtyAkhir;
					$sumIsiTon += $bb;
				}

				if($getIsi->num_rows() != 1){
					$html .='<tr>
						<td style="border:1px solid #dee2e6;padding:6px;text-align:right;font-weight:bold">TOTAL</td>
						<td style="border:1px solid #dee2e6;padding:6px;text-align:right;font-weight:bold">'.number_format($sumIsiQty,0,",",".").'</td>
						<td style="border:1px solid #dee2e6;padding:6px" colspan="2"></td>
					</tr>';
				}

				$sumAllQty += $sumIsiQty;
				$sumAllTon += $sumIsiTon;
			}

			// if($getKodePO->num_rows() != 1 || $getIsi->num_rows() == 1){
				$html.='<tr style="background:#dee2e6">
					<td style="padding:6px;text-align:right;font-weight:bold;border:1px solid #a9a9a9">TOTAL KESELURUHAN</td>
					<td style="padding:6px;text-align:right;font-weight:bold;border:1px solid #a9a9a9">'.number_format($sumAllQty,0,",",".").'</td>
					<td style="padding:6px;border:1px solid #a9a9a9" colspan="2"></td>
				</tr>';
			// }

		$html .= '</table>';

		echo $html;
	}

	function loadGudang()
	{
		$result = $this->m_logistik->loadGudang();
		echo json_encode($result);
	}

	function closeGudang()
	{
		$result = $this->m_logistik->closeGudang();
		echo json_encode($result);
	}

	function simpanGudang()
	{
		$result = $this->m_logistik->simpanGudang();
		echo json_encode($result);
	}

	function plhListPlan()
	{
		$html = '';
		$opsi = $_POST["opsi"];
		$id_pelanggan = $_POST["id_pelanggan"];
		if($opsi == 'cor'){
			$where = "WHERE g.gd_id_plan_cor!='0' AND g.gd_id_plan_flexo IS NULL AND g.gd_id_plan_finishing IS NULL";
		}else if($opsi == 'flexo'){
			$where = "WHERE g.gd_id_plan_cor!='0' AND g.gd_id_plan_flexo!='0' AND g.gd_id_plan_finishing IS NULL";
		}else if($opsi == 'finishing'){
			$where = "WHERE g.gd_id_plan_cor!='0' AND g.gd_id_plan_flexo!='0' AND g.gd_id_plan_finishing!='0'";
		}else{
			$where = "";
		}

		$data = $this->db->query("SELECT p.nm_pelanggan,g.* FROM m_gudang g
		INNER JOIN m_pelanggan p ON g.gd_id_pelanggan=p.id_pelanggan
		$where
		GROUP BY p.nm_pelanggan");

		$html .= '<table class="table table-bordered" style="margin:0;border:0">
			<thead>';
				foreach($data->result() as $r){
					if($id_pelanggan == $r->gd_id_pelanggan){
						$bgTd = 'class="h-tlp-td"';
					}else{
						$bgTd = 'class="h-tlpf-td"';
					}

					$html .= '<tr>
						<td '.$bgTd.' style="padding:6px;border-width:0 0 1px">
							<a href="javascript:void(0)" onclick="plhListPlan('."'".$opsi."'".','."'".$r->gd_id_pelanggan."'".')">'.$r->nm_pelanggan.'</a>
						</td>
					</tr>';
				}
			$html .= '</thead>
		</table>';

		echo $html;
	}

	function loadListProduksiPlan()
	{
		$html = '';
		$opsi = $_POST["opsi"];
		$id_pelanggan = $_POST["id_pelanggan"];
		$id_produk = $_POST["id_produk"];
		$data = $this->m_logistik->loadListProduksiPlan();

		if($data->num_rows() == 0){
			$html .='LIST';
		}else{
			$html .= '<div id="accordion">
				<div class="card m-0" style="border-radius:0">';
					$i = 0;
					foreach($data->result() as $r){
						$i++;
						$html .= '<div class="card-header" style="padding:0;border-radius:0">
							<a class="d-block w-100" style="font-weight:bold;padding:6px" data-toggle="collapse" href="#collapse'.$i.'" onclick="clickHasilProduksiPlan('."'".$opsi."'".','."'".$r->gd_id_pelanggan."'".','."'".$r->gd_id_produk."'".','."'".$r->kode_po."'".','."'".$i."'".')">
								'.$r->kode_po.' <span id="i_span'.$i.'" class="bg-secondary" style="vertical-align:top;font-weight:bold;padding:2px 4px;font-size:12px;border-radius:4px">'.$r->jml_gd.'</span>
							</a>
						</div>
						<div id="collapse'.$i.'" class="collapse" data-parent="#accordion">
							<div id="isi-list-gudang-'.$i.'" style="padding:3px"></div>
						</div>';
					}
				$html .= '</div>
			</div>';
		}

		echo $html;
	}

	function clickHasilProduksiPlan()
	{
		$html = '';
		$opsi = $_POST["opsi"];
		$id_pelanggan = $_POST["id_pelanggan"];
		$id_produk = $_POST["id_produk"];
		$no_po = $_POST["no_po"];
		$i = $_POST["i"];
		$data = $this->m_logistik->clickHasilProduksiPlan();

		$html .='<div style="overflow:auto;white-space:nowrap">
			<table class="table table-bordered" style="margin:0;border:0;text-align:center">
				<thead>
					<tr>
						<th style="background:#dee2e6;border-bottom:1px solid #bec2c6;padding:6px">PLAN</th>
						<th style="background:#dee2e6;border-bottom:1px solid #bec2c6;padding:6px">HASIL COR</th>
						<th style="background:#dee2e6;border-bottom:1px solid #bec2c6;padding:6px 25px">GOOD</th>
						<th style="background:#dee2e6;border-bottom:1px solid #bec2c6;padding:6px 18px">REJECT</th>
						<th style="background:#dee2e6;border-bottom:1px solid #bec2c6;padding:6px">AKSI</th>
					</tr>
				</thead>';
				foreach($data->result() as $r){
					// gd_good_qty  gd_reject_qty  gd_cek_spv
					
					if($opsi == 'cor'){
						$shift = $r->shift_plan;
						$mesin = str_replace('CORR', '', $r->machine_plan);
						$tgl = $r->tgl_plan;
					}else if($opsi == 'flexo'){
						$shift = $r->shift_flexo;
						$mesin = str_replace('FLEXO', '', $r->mesin_flexo);
						$tgl = $r->tgl_flexo;
					}else{
						$shift = $r->shift_fs;
						$mesin = substr($r->joint_fs,0,1);
						$tgl = $r->tgl_fs;
					}

					if($r->gd_cek_spv == 'Open'){
						$btnAksi = '<button type="button" id="simpan_gudang'.$r->id_gudang.'" class="btn btn-sm btn-success btn-block" style="font-weight:bold" onclick="simpanGudang('."'".$r->id_gudang."'".','."'".$opsi."'".','."'".$id_pelanggan."'".','."'".$id_produk."'".','."'".$no_po."'".','."'".$i."'".')">SIMPAN</button>';
						$disabledInput = '';
					}else{
						$btnAksi = '<button type="button" class="btn btn-sm btn-secondary btn-block" style="font-weight:bold;cursor:default" disabled)">SIMPAN</button>';
						$disabledInput = 'disabled';
					}

					$html .= '<tr>
						<td style="padding:6px;text-align:left">['.$shift.'.'.$mesin.'] '.substr($this->m_fungsi->getHariIni($tgl),0,3).', '.$this->m_fungsi->tglIndSkt($tgl).'</td>
						<td style="padding:6px">'.number_format($r->gd_hasil_plan,0,",",".").'</td>
						<td style="padding:6px">
							<input type="number" class="form-control" id="good-'.$r->id_gudang.'" autocomplete="off" value="'.$r->gd_good_qty.'" onkeyup="hitungGudang('."'".$r->id_gudang."'".')" '.$disabledInput.'>
						</td>
						<td style="padding:6px">
							<input type="number" class="form-control" id="reject-'.$r->id_gudang.'" autocomplete="off" value="'.$r->gd_reject_qty.'" onkeyup="hitungGudang('."'".$r->id_gudang."'".')" '.$disabledInput.'>
						</td>
						<td style="padding:6px">'.$btnAksi.'</td>
					</tr>';
				}
			$html .= '</table>
		</div>';

		echo $html;
	}

	function timeline()
	{
		$html = '';
		$opsi = $_POST["opsi"];
		$id_pelanggan = $_POST["id_pelanggan"];
		$id_produk = $_POST["id_produk"];
		$no_po = $_POST["no_po"];

		if($opsi == 'cor'){
			$tgl = $this->db->query("SELECT*FROM m_gudang g
			INNER JOIN plan_cor c ON g.gd_id_plan_cor=c.id_plan
			INNER JOIN trs_wo w ON g.gd_id_trs_wo=w.id
			WHERE g.gd_id_pelanggan='$id_pelanggan' AND g.gd_id_produk='$id_produk' AND w.kode_po='$no_po'
			AND g.gd_id_plan_cor IS NOT NULL AND g.gd_id_plan_flexo IS NULL AND g.gd_id_plan_finishing IS NULL
			GROUP BY c.tgl_plan");
		}else if($opsi == 'flexo'){
			$tgl = $this->db->query("SELECT*FROM m_gudang g
			INNER JOIN plan_flexo fx ON g.gd_id_plan_cor=fx.id_plan_cor AND g.gd_id_plan_flexo=fx.id_flexo
			INNER JOIN trs_wo w ON g.gd_id_trs_wo=w.id
			WHERE g.gd_id_pelanggan='$id_pelanggan' AND g.gd_id_produk='$id_produk' AND w.kode_po='$no_po'
			AND g.gd_id_plan_cor IS NOT NULL AND g.gd_id_plan_flexo IS NOT NULL AND g.gd_id_plan_finishing IS NULL
			GROUP BY fx.tgl_flexo");
		}else if($opsi == 'finishing'){
			$tgl = $this->db->query("SELECT*FROM m_gudang g
			INNER JOIN plan_finishing fs ON g.gd_id_plan_cor=fs.id_plan_cor AND g.gd_id_plan_flexo=fs.id_plan_flexo AND g.gd_id_plan_finishing=fs.id_fs
			INNER JOIN trs_wo w ON g.gd_id_trs_wo=w.id
			WHERE g.gd_id_pelanggan='$id_pelanggan' AND g.gd_id_produk='$id_produk' AND w.kode_po='$no_po'
			AND g.gd_id_plan_cor IS NOT NULL AND g.gd_id_plan_flexo IS NOT NULL AND g.gd_id_plan_finishing IS NOT NULL
			GROUP BY fs.tgl_fs");
		}else{
			$tgl = '';
		}

		if($tgl == ''){
			$html .='kosong';
		}else{
			$html .='<div class="timeline">';
				$i = 0;
				foreach($tgl->result() as $r){
					$i++;

					if($opsi == 'cor'){
						$tglList = $r->tgl_plan;
						$list = $this->db->query("SELECT*FROM m_gudang g
						INNER JOIN plan_cor c ON g.gd_id_plan_cor=c.id_plan
						INNER JOIN trs_wo w ON g.gd_id_trs_wo=w.id
						INNER JOIN m_produk p ON g.gd_id_produk=p.id_produk
						WHERE g.gd_id_pelanggan='$r->gd_id_pelanggan' AND g.gd_id_produk='$r->gd_id_produk' AND w.kode_po='$r->kode_po' AND c.tgl_plan='$r->tgl_plan'
						AND g.gd_id_plan_cor IS NOT NULL AND g.gd_id_plan_flexo IS NULL AND g.gd_id_plan_finishing IS NULL
						ORDER BY c.tgl_plan");
					}else if($opsi == 'flexo'){
						$tglList = $r->tgl_flexo;
						$list = $this->db->query("SELECT*FROM m_gudang g
						INNER JOIN plan_cor c ON g.gd_id_plan_cor=c.id_plan
						INNER JOIN plan_flexo fx ON g.gd_id_plan_cor=fx.id_plan_cor AND g.gd_id_plan_flexo=fx.id_flexo
						INNER JOIN trs_wo w ON g.gd_id_trs_wo=w.id
						INNER JOIN m_produk p ON g.gd_id_produk=p.id_produk
						WHERE g.gd_id_pelanggan='$r->gd_id_pelanggan' AND g.gd_id_produk='$r->gd_id_produk' AND w.kode_po='$r->kode_po' AND fx.tgl_flexo='$r->tgl_flexo'
						AND g.gd_id_plan_cor IS NOT NULL AND g.gd_id_plan_flexo IS NOT NULL AND g.gd_id_plan_finishing IS NULL
						ORDER BY fx.tgl_flexo");
					}else if($opsi == 'finishing'){
						$tglList = $r->tgl_fs;
						$list = $this->db->query("SELECT*FROM m_gudang g
						INNER JOIN plan_cor c ON g.gd_id_plan_cor=c.id_plan
						INNER JOIN plan_finishing fs ON g.gd_id_plan_cor=fs.id_plan_cor AND g.gd_id_plan_flexo=fs.id_plan_flexo AND g.gd_id_plan_finishing=fs.id_fs
						INNER JOIN trs_wo w ON g.gd_id_trs_wo=w.id
						INNER JOIN m_produk p ON g.gd_id_produk=p.id_produk
						WHERE g.gd_id_pelanggan='$r->gd_id_pelanggan' AND g.gd_id_produk='$r->gd_id_produk' AND w.kode_po='$r->kode_po' AND fs.tgl_fs='$r->tgl_fs'
						AND g.gd_id_plan_cor IS NOT NULL AND g.gd_id_plan_flexo IS NOT NULL AND g.gd_id_plan_finishing IS NOT NULL
						ORDER BY fs.tgl_fs");
					}else{
						$tglList = '';
						$list = '';
					}

					$html .='<div class="time-label" style="margin-right:0">
						<span class="bg-gradient-red">'.$i.'. '.substr($this->m_fungsi->getHariIni($tglList),0,3).', '.$this->m_fungsi->tglIndSkt($tglList).'</span>
					</div>';

					$l = 0;
					foreach($list->result() as $r2){
						$l++;

						if($opsi == 'cor'){
							$shift = $r2->shift_plan;
							$txtMesin = 'MESIN';
							$mesin = str_replace('CORR', '', $r2->machine_plan);
						}else if($opsi == 'flexo'){
							$shift = $r2->shift_flexo;
							$txtMesin = 'MESIN';
							$mesin = str_replace('FLEXO', '', $r2->mesin_flexo);
						}else{
							$shift = $r2->shift_fs;
							$txtMesin = 'JOINT';
							$mesin = $r->joint_fs;
						}

						$expKualitas = explode("/", $r2->kualitas_plan);
						if($r2->flute == 'BCF'){
							if($expKualitas[1] == 'M125' && $expKualitas[2] == 'M125' && $expKualitas[3] == 'M125'){
								$kualitas = $expKualitas[0].'/'.$expKualitas[1].'x3/'.$expKualitas[4];
							}else if($expKualitas[1] == 'K125' && $expKualitas[2] == 'K125' && $expKualitas[3] == 'K125'){
								$kualitas = $expKualitas[0].'/'.$expKualitas[1].'x3/'.$expKualitas[4];
							}else if($expKualitas[1] == 'M150' && $expKualitas[2] == 'M150' && $expKualitas[3] == 'M150'){
								$kualitas = $expKualitas[0].'/'.$expKualitas[1].'x3/'.$expKualitas[4];
							}else if($expKualitas[1] == 'K150' && $expKualitas[2] == 'K150' && $expKualitas[3] == 'K150'){
								$kualitas = $expKualitas[0].'/'.$expKualitas[1].'x3/'.$expKualitas[4];
							}else{
								$kualitas = $r2->kualitas;
							}
						}else{
							$kualitas = $r2->kualitas;
						}

						($r2->gd_cek_spv == 'Close') ? $bgBlue = 'bg-blue' : $bgBlue = 'bg-secondary';
						$html .='<div style="margin-right:5px">
							<i class="fas '.$bgBlue.'">'.$l.'</i>
							<div class="timeline-item mr-0">
								<h3 class="timeline-header p-0">
									<table style="width:100%">
										<tr>
											<th colspan="3" style="background:#dee2e6;padding:10px;border-bottom:1px solid #bec2c6">DETAIL</th>
										</tr>
										<tr>
											<th style="padding:5px">NO.WO</th>
											<th>:</th>
											<th style="padding:5px">'.$r2->no_wo.'</th>
										</tr>
										<tr>
											<th style="padding:5px">KD.MC</th>
											<th>:</th>
											<th style="padding:5px">'.$r2->kode_mc.'</th>
										</tr>
										<tr>
											<th colspan="3" style="background:#dee2e6;padding:10px;border:1px solid #bec2c6;border-width:1px 0">PRODUKSI</th>
										</tr>
										<tr>
											<th style="padding:5px">SHIFT</th>
											<th>:</th>
											<th style="padding:5px">'.$shift.'</th>
										</tr>
										<tr>
											<th style="padding:5px">'.$txtMesin.'</th>
											<th>:</th>
											<th style="padding:5px">'.$mesin.'</th>
										</tr>
										<tr>
											<th style="padding:5px">KUALITAS</th>
											<th>:</th>
											<th style="padding:5px">'.$kualitas.'</th>
										</tr>
										<tr>
											<th style="padding:5px">BB</th>
											<th>:</th>
											<th style="padding:5px">'.$r2->bb_plan_p.'</th>
										</tr>
										<tr>
											<th style="padding:5px">HASIL</th>
											<th>:</th>
											<th style="padding:5px">'.number_format($r2->gd_hasil_plan,0,",",".").'</th>
										</tr>
										<tr>
											<th style="padding:5px">TONASE</th>
											<th>:</th>
											<th style="padding:5px">'.($r2->gd_hasil_plan * $r2->bb_plan_p).'</th>
										</tr>';
										if($r2->gd_cek_spv == 'Close'){
											$html .='<tr>
												<th colspan="3" style="background:#dee2e6;padding:10px;border:1px solid #bec2c6;border-width:1px 0">GUDANG</th>
											</tr>
											<tr>
												<th style="padding:5px">GOOD</th>
												<th>:</th>
												<th style="padding:5px">'.number_format($r2->gd_good_qty,0,",",".").'</th>
											</tr>
											<tr>
												<th style="padding:5px">REJECT</th>
												<th>:</th>
												<th style="padding:5px">'.number_format($r2->gd_reject_qty,0,",",".").'</th>
											</tr>
											<tr>
												<th style="padding:5px">TONASE</th>
												<th>:</th>
												<th style="padding:5px">'.($r2->gd_good_qty * $r2->bb_plan_p).'</th>
											</tr>';
										}
									$html .='</table>
								</h3>
							</div>
						</div>';
					}
				}
				$html .='<div>
					<i class="fas fa-clock bg-gray"></i>
				</div>
			</div>';
		}

		echo $html;
	}

	//

	function Surat_Jalan()
	{
		$data = array(
			'judul' => "Surat Jalan",
		);
		$this->load->view('header', $data);

		$jenis = $this->uri->segment(3);
		if($jenis == 'Add'){
			if(in_array($this->session->userdata('level'), ['Admin','Gudang'])){
				$this->load->view('Logistik/v_sj_add');
			}else{
				$this->load->view('home');
			}
		}else{
			if(in_array($this->session->userdata('level'), ['Admin', 'Gudang'])){
				// $this->load->view('Logistik/v_sj');
				$this->load->view('Logistik/v_sj_add');
			}else{
				$this->load->view('home');
			}
		}

		$this->load->view('footer');
	}

	function loadPilihanSJ()
	{
		$html = '';
		$html .='<div id="gudangPilihan">
			<a class="gd-link-pilihan plh-tgl_kirim" style="margin-bottom:6px;font-weight:bold" data-toggle="collapse" href="#pilihan-tgl_kirim" onclick="pilihanSJ('."'tgl_kirim'".')">TANGGAL KIRIM</a>
			<div id="pilihan-tgl_kirim" class="collapse" data-parent="#gudangPilihan"><div id="tampilPilihan-tgl_kirim" style="overflow:auto;white-space:nowrap"></div></div>
			<a class="gd-link-pilihan plh-customer" style="font-weight:bold" data-toggle="collapse" href="#pilihan-customer" onclick="pilihanSJ('."'customer'".')">CUSTOMER</a>
			<div id="pilihan-customer" class="collapse" data-parent="#gudangPilihan"><div id="tampilPilihan-customer"></div></div>
		</div>';
		// }
		echo $html;
	}

	function pilihanSJ()
	{
		$opsi = $_POST["opsi"];
		$html = '';

		if($opsi == "tgl_kirim"){
			$getCustomer = $this->db->query("SELECT p.nm_pelanggan,w.kode_po,i.kategori,i.nm_produk,g.*,c.*,fx.*,fs.* FROM m_gudang g
			INNER JOIN m_pelanggan p ON g.gd_id_pelanggan=p.id_pelanggan
			INNER JOIN m_produk i ON g.gd_id_produk=i.id_produk
			INNER JOIN plan_cor c ON g.gd_id_plan_cor=c.id_plan
			LEFT JOIN plan_flexo fx ON g.gd_id_plan_flexo=fx.id_flexo
			LEFT JOIN plan_finishing fs ON g.gd_id_plan_finishing=fs.id_fs
			INNER JOIN trs_wo w ON g.gd_id_trs_wo=w.id
			WHERE g.gd_cek_spv='Close' AND g.gd_status='Open'
			ORDER BY c.tgl_kirim_plan,p.nm_pelanggan,w.kode_po,i.nm_produk");
		}else{
			$getCustomer = $this->db->query("SELECT p.nm_pelanggan,g.* FROM m_gudang g
			INNER JOIN m_pelanggan p ON g.gd_id_pelanggan=p.id_pelanggan
			WHERE g.gd_cek_spv='Close' AND g.gd_status='Open'
			GROUP BY g.gd_id_pelanggan
			ORDER BY p.nm_pelanggan");
		}
		
		if($getCustomer->num_rows() == 0){
			$html .='GUDANG KOSONG!';
		}else{
			if($opsi == "tgl_kirim"){
				$html .= '<table style="margin-top:6px;border:1px solid #dee2e6">
					<tr style="background:#dee2e6">
						<th style="padding:6px;border:1px solid #bbb;text-align:center">ID</th>
						<th style="padding:6px;border:1px solid #bbb">TGL KIRIM</th>
						<th style="padding:6px;border:1px solid #bbb">CUSTOMER</th>
						<th style="padding:6px;border:1px solid #bbb">NO. PO</th>
						<th style="padding:6px;border:1px solid #bbb">ITEM</th>
						<th style="padding:6px;border:1px solid #bbb">PLAN</th>
						<th style="padding:6px 12px;text-align:center;border:1px solid #bbb">QTY</th>
						<th style="padding:6px;text-align:center;border:1px solid #bbb">BB</th>
						<th style="padding:6px 20px;text-align:center;border:1px solid #bbb">MUAT</th>
						<th style="padding:6px;text-align:center;border:1px solid #bbb">TONASE</th>
						<th style="padding:6px;text-align:center;border:1px solid #bbb">AKSI</th>
					</tr>';
					foreach($getCustomer->result() as $isi){
						if($isi->gd_id_plan_cor != null && $isi->gd_id_plan_flexo == null && $isi->gd_id_plan_finishing == null){
							$rcr = ';border-radius:4px';
							$rfx = '';
							$rfs = '';
							$shift = $isi->shift_plan;
							$mesin = str_replace('CORR', '', $isi->machine_plan);
						}else if($isi->gd_id_plan_cor != null && $isi->gd_id_plan_flexo != null && $isi->gd_id_plan_finishing == null){
							$rcr = ';border-radius:4px 0 0 4px';
							$rfx = ';border-radius:0 4px 4px 0';
							$rfs = '';
							$shift = $isi->shift_flexo;
							$mesin = str_replace('FLEXO', '', $isi->mesin_flexo);
						}else{
							$rcr = ';border-radius:4px 0 0 4px';
							$rfx = '';
							$rfs = ';border-radius:0 4px 4px 0';
							$shift = $isi->shift_fs;
							$mesin = substr($isi->joint_fs,0,1);
						}
						
						($isi->gd_id_plan_flexo == null) ? $fx = '' : $fx = '<span class="bg-secondary" style="vertical-align:top;font-weight:bold;padding:2px 4px;font-size:12px'.$rfx.'">FX</span>';
						($isi->gd_id_plan_finishing == null) ? $fs = '' : $fs = '<span class="bg-secondary" style="vertical-align:top;font-weight:bold;padding:2px 4px;font-size:12px'.$rfs.'">FS</span>';
						($isi->kategori == "K_BOX") ? $kategori = '[BOX] ' : $kategori = '[SHEET] ';
		
						$btnAksi = '<button type="button" id="simpan_muat'.$isi->id_gudang.'" class="btn btn-sm btn-success btn-block" style="font-weight:bold" onclick="addCartRKSJ('."'".$isi->id_gudang."'".')"><i class="fas fa-plus"></i> ADD</button>';
		
						$rk = $this->db->query("SELECT SUM(qty_muat) AS muat FROM m_rencana_kirim WHERE id_gudang='$isi->id_gudang' GROUP BY id_gudang");
						($rk->num_rows() == 0) ? $qty = $isi->gd_good_qty : $qty = $isi->gd_good_qty - $rk->row()->muat;
		
						$html .='<tr>
							<td style="border:1px solid #dee2e6;padding:6px;text-align:center">'.$isi->id_gudang.'</td>
							<td style="border:1px solid #dee2e6;padding:6px">'.substr($this->m_fungsi->getHariIni($isi->tgl_kirim_plan),0,3).', '.$this->m_fungsi->tglIndSkt($isi->tgl_kirim_plan).'</td>
							<td style="border:1px solid #dee2e6;padding:6px">'.$isi->nm_pelanggan.'</td>
							<td style="border:1px solid #dee2e6;padding:6px">'.$isi->kode_po.'</td>
							<td style="border:1px solid #dee2e6;padding:6px">'.$kategori.''.$isi->nm_produk.'</td>
							<td style="border:1px solid #dee2e6;padding:6px">['.$shift.'.'.$mesin.'] '.substr($this->m_fungsi->getHariIni($isi->tgl_plan),0,3).', '.$this->m_fungsi->tglIndSkt($isi->tgl_plan).' <span class="bg-secondary" style="vertical-align:top;font-weight:bold;padding:2px 4px;font-size:12px'.$rcr.'">CR</span>'.$fx.''.$fs.'</td>
							<td style="border:1px solid #dee2e6;padding:6px;text-align:right;font-weight:bold">
								<span class="hitung-sisa-'.$isi->id_gudang.'">'.number_format($qty,0,",",".").'</span>
							</td>
							<td style="border:1px solid #dee2e6;padding:6px;text-align:right">'.$isi->gd_berat_box.'</td>
							<td style="border:1px solid #dee2e6;padding:6px">
								<input type="number" class="form-control" style="height:100%;width:70px;text-align:right;padding:4px 6px" id="inp-muat-'.$isi->id_gudang.'" onkeyup="hitungSJTonase('."'".$isi->id_gudang."'".')">
							</td>
							<td style="border:1px solid #dee2e6;padding:6px;text-align:right;font-weight:bold">
								<span class="hitung-tonase-'.$isi->id_gudang.'">0</span>
								<input type="hidden" id="hidden-hitung-tonase-'.$isi->id_gudang.'" value="">
								<input type="hidden" id="hidden-id-pelanggan-'.$isi->id_gudang.'" value="'.$isi->gd_id_pelanggan.'">
								<input type="hidden" id="hidden-id-produk-'.$isi->id_gudang.'" value="'.$isi->gd_id_produk.'">
								<input type="hidden" id="hidden-nm-pelanggan-'.$isi->id_gudang.'" value="'.$isi->nm_pelanggan.'">
								<input type="hidden" id="hidden-nm-produk-'.$isi->id_gudang.'" value="'.$isi->nm_produk.'">
								<input type="hidden" id="hidden-kode-po-'.$isi->id_gudang.'" value="'.$isi->kode_po.'">
								<input type="hidden" id="hidden-bb-'.$isi->id_gudang.'" value="'.$isi->gd_berat_box.'">
								<input type="hidden" id="hidden-qty-'.$isi->id_gudang.'" value="'.$qty.'">
							</td>
							<td style="border:1px solid #dee2e6;padding:3px 6px">'.$btnAksi.'</td>
						</tr>';
					}
				$html .='</table>';
			}else{
				$html .='<div id="gudangCustomer" style="margin-top:6px">';
					$i = 0;
					foreach($getCustomer->result() as $cust){
						$i++;
						$html .='<a class="gd-link-customer" style="font-weight:bold" data-toggle="collapse" href="#customer-'.$cust->gd_id_pelanggan.'" onclick="loadSJItems('."'".$cust->gd_id_pelanggan."'".')">
							'.$i.'. '.$cust->nm_pelanggan.'
						</a>
						<div id="customer-'.$cust->gd_id_pelanggan.'" class="pilihan" data-parent="#gudangCustomer">
							<div id="tampilItems-'.$cust->gd_id_pelanggan.'" class="iitemss"></div>
						</div>';
					}
				$html .='</div>';
			}
		}
		echo $html;
	}

	function loadSJItems()
	{
		$gd_id_pelanggan = $_POST["gd_id_pelanggan"];
		$html = '';
		$html .='<div id="gudangItems">';
			$getItems = $this->db->query("SELECT i.kategori,i.nm_produk,g.* FROM m_gudang g
			INNER JOIN m_produk i ON g.gd_id_produk=i.id_produk
			WHERE g.gd_id_pelanggan='$gd_id_pelanggan' AND g.gd_cek_spv='Close' AND g.gd_status='Open'
			GROUP BY g.gd_id_produk
			ORDER BY i.kategori,i.nm_produk");
			foreach($getItems->result() as $items){
				($items->kategori == "K_BOX") ? $kategori = 'BOX' : $kategori = 'SHEET';
				$html .='<a class="gd-link-items" style="font-weight:bold" data-toggle="collapse" href="#items-'.$items->gd_id_pelanggan.'-'.$items->gd_id_produk.'" onclick="loadSJPO('."'".$items->gd_id_pelanggan."'".','."'".$items->gd_id_produk."'".')">
					['.$kategori.'] '.$items->nm_produk.'
				</a>
				<div id="items-'.$items->gd_id_pelanggan.'-'.$items->gd_id_produk.'" class="collapse" data-parent="#gudangItems">
					<div id="tampilPO-'.$items->gd_id_pelanggan.'-'.$items->gd_id_produk.'"></div>
				</div>';
			}
		$html .='</div>';
		echo $html;
	}

	function loadSJPO()
	{
		$gd_id_pelanggan = $_POST["gd_id_pelanggan"];
		$gd_id_produk = $_POST["gd_id_produk"];
		$html = '';
		$html .='<div id="gudangPOs">';
			$getPOs = $this->db->query("SELECT w.kode_po,g.* FROM m_gudang g
			INNER JOIN trs_wo w ON g.gd_id_trs_wo=w.id
			WHERE g.gd_id_pelanggan='$gd_id_pelanggan' AND g.gd_id_produk='$gd_id_produk' AND g.gd_cek_spv='Close' AND g.gd_status='Open'
			GROUP BY w.kode_po");
			$i = 0;
			foreach($getPOs->result() as $po){
				$i++;
				$html .='<a class="gd-link-pos" style="font-weight:bold" data-toggle="collapse" href="#po'.$i.'-'.$po->gd_id_pelanggan.'-'.$po->gd_id_produk.'" onclick="loadSJIsiGudang('."'".$i."'".','."'".$po->gd_id_pelanggan."'".','."'".$po->gd_id_produk."'".','."'".$po->kode_po."'".')">
					> '.$po->kode_po.'
				</a>
				<div id="po'.$i.'-'.$po->gd_id_pelanggan.'-'.$po->gd_id_produk.'" class="collapse" data-parent="#gudangPOs">
					<div id="tampilGudang-'.$i.'-'.$po->gd_id_pelanggan.'-'.$po->gd_id_produk.'" style="overflow:auto;white-space:nowrap"></div>
				</div>';
			}
		$html .='</div>';
		echo $html;
	}

	function loadSJIsiGudang()
	{
		$gd_id_pelanggan = $_POST["gd_id_pelanggan"];
		$gd_id_produk = $_POST["gd_id_produk"];
		$kode_po = $_POST["kode_po"];
		$html = '';

		$getIsi = $this->db->query("SELECT p.nm_pelanggan,i.nm_produk,w.kode_po,g.*,c.*,fx.*,fs.* FROM m_gudang g
		INNER JOIN plan_cor c ON g.gd_id_plan_cor=c.id_plan
		LEFT JOIN plan_flexo fx ON g.gd_id_plan_flexo=fx.id_flexo
		LEFT JOIN plan_finishing fs ON g.gd_id_plan_finishing=fs.id_fs
		INNER JOIN m_pelanggan p ON g.gd_id_pelanggan=p.id_pelanggan
		INNER JOIN m_produk i ON g.gd_id_produk=i.id_produk
		INNER JOIN trs_wo w ON g.gd_id_trs_wo=w.id
		WHERE w.kode_po='$kode_po' AND g.gd_id_pelanggan='$gd_id_pelanggan' AND g.gd_id_produk='$gd_id_produk' AND g.gd_cek_spv='Close' AND g.gd_status='Open'");
		$html .= '<table style="margin-bottom:6px;border:1px solid #dee2e6">
			<tr style="background:#dee2e6">
				<th style="padding:6px;border:1px solid #bbb;text-align:center">ID</th>
				<th style="padding:6px;border:1px solid #bbb">TGL KIRIM</th>
				<th style="padding:6px;border:1px solid #bbb">PLAN</th>
				<th style="padding:6px 12px;text-align:center;border:1px solid #bbb">QTY</th>
				<th style="padding:6px;text-align:center;border:1px solid #bbb">BB</th>
				<th style="padding:6px 20px;text-align:center;border:1px solid #bbb">MUAT</th>
				<th style="padding:6px;text-align:center;border:1px solid #bbb">TONASE</th>
				<th style="padding:6px;text-align:center;border:1px solid #bbb">AKSI</th>
			</tr>';
			foreach($getIsi->result() as $isi){
				if($isi->gd_id_plan_cor != null && $isi->gd_id_plan_flexo == null && $isi->gd_id_plan_finishing == null){
					$rcr = ';border-radius:4px';
					$rfx = '';
					$rfs = '';
					$shift = $isi->shift_plan;
					$mesin = str_replace('CORR', '', $isi->machine_plan);
				}else if($isi->gd_id_plan_cor != null && $isi->gd_id_plan_flexo != null && $isi->gd_id_plan_finishing == null){
					$rcr = ';border-radius:4px 0 0 4px';
					$rfx = ';border-radius:0 4px 4px 0';
					$rfs = '';
					$shift = $isi->shift_flexo;
					$mesin = str_replace('FLEXO', '', $isi->mesin_flexo);
				}else{
					$rcr = ';border-radius:4px 0 0 4px';
					$rfx = '';
					$rfs = ';border-radius:0 4px 4px 0';
					$shift = $isi->shift_fs;
					$mesin = substr($isi->joint_fs,0,1);
				}
				
				($isi->gd_id_plan_flexo == null) ? $fx = '' : $fx = '<span class="bg-secondary" style="vertical-align:top;font-weight:bold;padding:2px 4px;font-size:12px'.$rfx.'">FX</span>';
				($isi->gd_id_plan_finishing == null) ? $fs = '' : $fs = '<span class="bg-secondary" style="vertical-align:top;font-weight:bold;padding:2px 4px;font-size:12px'.$rfs.'">FS</span>';

				$btnAksi = '<button type="button" id="simpan_muat'.$isi->id_gudang.'" class="btn btn-sm btn-success btn-block" style="font-weight:bold" onclick="addCartRKSJ('."'".$isi->id_gudang."'".')"><i class="fas fa-plus"></i> ADD</button>';

				$rk = $this->db->query("SELECT SUM(qty_muat) AS muat FROM m_rencana_kirim WHERE id_gudang='$isi->id_gudang' GROUP BY id_gudang");
				($rk->num_rows() == 0) ? $qty = $isi->gd_good_qty : $qty = $isi->gd_good_qty - $rk->row()->muat;

				$html .='<tr>
					<td style="border:1px solid #dee2e6;padding:6px;text-align:center">'.$isi->id_gudang.'</td>
					<td style="border:1px solid #dee2e6;padding:6px">'.substr($this->m_fungsi->getHariIni($isi->tgl_kirim_plan),0,3).', '.$this->m_fungsi->tglIndSkt($isi->tgl_kirim_plan).'</td>
					<td style="border:1px solid #dee2e6;padding:6px">['.$shift.'.'.$mesin.'] '.substr($this->m_fungsi->getHariIni($isi->tgl_plan),0,3).', '.$this->m_fungsi->tglIndSkt($isi->tgl_plan).' <span class="bg-secondary" style="vertical-align:top;font-weight:bold;padding:2px 4px;font-size:12px'.$rcr.'">CR</span>'.$fx.''.$fs.'</td>
					<td style="border:1px solid #dee2e6;padding:6px;text-align:right;font-weight:bold">
						<span class="hitung-sisa-'.$isi->id_gudang.'">'.number_format($qty,0,",",".").'</span>
					</td>
					<td style="border:1px solid #dee2e6;padding:6px;text-align:right">'.$isi->gd_berat_box.'</td>
					<td style="border:1px solid #dee2e6;padding:6px">
						<input type="number" class="form-control" style="height:100%;width:70px;text-align:right;padding:4px 6px" id="inp-muat-'.$isi->id_gudang.'" onkeyup="hitungSJTonase('."'".$isi->id_gudang."'".')">
					</td>
					<td style="border:1px solid #dee2e6;padding:6px;text-align:right;font-weight:bold">
						<span class="hitung-tonase-'.$isi->id_gudang.'">0</span>
						<input type="hidden" id="hidden-hitung-tonase-'.$isi->id_gudang.'" value="">
						<input type="hidden" id="hidden-id-pelanggan-'.$isi->id_gudang.'" value="'.$gd_id_pelanggan.'">
						<input type="hidden" id="hidden-id-produk-'.$isi->id_gudang.'" value="'.$gd_id_produk.'">
						<input type="hidden" id="hidden-nm-pelanggan-'.$isi->id_gudang.'" value="'.$isi->nm_pelanggan.'">
						<input type="hidden" id="hidden-nm-produk-'.$isi->id_gudang.'" value="'.$isi->nm_produk.'">
						<input type="hidden" id="hidden-kode-po-'.$isi->id_gudang.'" value="'.$kode_po.'">
						<input type="hidden" id="hidden-bb-'.$isi->id_gudang.'" value="'.$isi->gd_berat_box.'">
						<input type="hidden" id="hidden-qty-'.$isi->id_gudang.'" value="'.$qty.'">
					</td>
					<td style="border:1px solid #dee2e6;padding:3px 6px">'.$btnAksi.'</td>
				</tr>';
			}
		$html .='</table>';

		echo $html;
	}

	function destroyGudang()
	{
		$this->cart->destroy();
	}

	function hapusCartRKSJ()
	{
		$data = array(
			'rowid' => $_POST['rowid'],
			'qty' => 0,
		);
		$this->cart->update($data);
	}

	function addCartRKSJ()
	{
		$data = array(
			'id' => $_POST['id_gudang'],
			'name' => $_POST['id_gudang'],
			'price' => 0,
			'qty' => 1,
			'options' => array(
				'nm_pelanggan' => $_POST["nmPelanggan"],
				'nm_produk' => $_POST["nmProduk"],
				'id_pelanggan' => $_POST["idPelanggan"],
				'id_produk' => $_POST["idProduk"],
				'id_gudang' => $_POST["id_gudang"],
				'qty' => $_POST["qty"],
				'qty_muat' => $_POST["muat"],
				'rk_tonase' => $_POST["tonase"],
				'rk_kode_po' => $_POST["kodePo"],
				'rk_bb' => $_POST["bb"],
			)
		);

		$id_gudang = $_POST['id_gudang'];
		$cekGudang = $this->db->query("SELECT*FROM m_gudang WHERE id_gudang='$id_gudang' AND gd_cek_spv='Close' AND gd_status='Close'");
		if($cekGudang->num_rows() != 0){
			echo json_encode(array('data' => false, 'isi' => 'STOK GUDANG DI SUDAH DICLOSE!'));
			return;
		}else if($_POST["muat"] == 0 || $_POST["muat"] == '' || !preg_match("/^[0-9]*$/", $_POST["muat"])){
			echo json_encode(array('data' => false, 'isi' => 'MUAT TIDAK BOLEH KOSONG!'));
			return;
		}else if($_POST["muat"] > $_POST["qty"]){
			echo json_encode(array('data' => false, 'isi' => 'MUAT LEBIH BESAR DARI STOK GUDANG!'));
			return;
		}else if($_POST["tonase"] == 0){
			echo json_encode(array('data' => false, 'isi' => 'MUAT TIDAK BOLEH KOSONG!'));
			return;
		}else if($this->cart->total_items() != 0){
			foreach($this->cart->contents() as $r){
				if($r['id'] == $_POST["id_gudang"]){
					echo json_encode(array('data' => false, 'isi' => 'DATA GUDANG SUDAH MASUK RENCANA KIRIM!'));
					return;
				}
			}
			$this->cart->insert($data);
			echo json_encode(array('data' => true, 'isi' => $data));
		}else{
			$this->cart->insert($data);
			echo json_encode(array('data' => true, 'isi' => $data));
		}
	}

	function listRencanaKirim()
	{
		$html = '';
		$html .='<table>
			<tr style="background:#dee2e6">
				<th style="padding:6px;border:1px solid #bbb;text-align:center">#</th>
				<th style="padding:6px;border:1px solid #bbb">CUSTOMER <span class="bg-secondary" style="vertical-align:top;font-weight:bold;padding:2px 4px;font-size:11px;border-radius:4px">ID</span></th>
				<th style="padding:6px;border:1px solid #bbb">NO. PO</th>
				<th style="padding:6px;border:1px solid #bbb">ITEM</th>
				<th style="padding:6px;border:1px solid #bbb;text-align:center">SISA</th>
				<th style="padding:6px;border:1px solid #bbb;text-align:center">MUAT</th>
				<th style="padding:6px;border:1px solid #bbb;text-align:center">BB</th>
				<th style="padding:6px;border:1px solid #bbb;text-align:center">TONASE</th>
				<th style="padding:6px;border:1px solid #bbb;text-align:center" colspan="2">AKSI</th>
			</tr>';

		$cekRK = $this->db->query("SELECT*FROM m_rencana_kirim WHERE rk_status='Open' GROUP BY rk_urut");

		if($this->cart->total_items() == 0){
			if($cekRK->num_rows() == 0){
				$html .= '<tr>
					<td style="padding:6px;border:1px solid #dee2e6;font-weight:bold" colspan="9">BELUM ADA RENCANA KIRIM!</td>
				</tr>';
			}
		}else{
			$sumTon = 0;
			foreach($this->cart->contents() as $r){
				$sisa = $r['options']['qty'] - $r['options']['qty_muat'];
				$html .='<tr>
					<td style="border:1px solid #dee2e6;padding:6px;text-align:center">'.$r['id'].'</td>
					<td style="border:1px solid #dee2e6;padding:6px">'.$r['options']['nm_pelanggan'].'</td>
					<td style="border:1px solid #dee2e6;padding:6px">'.$r['options']['rk_kode_po'].'</td>
					<td style="border:1px solid #dee2e6;padding:6px">'.$r['options']['nm_produk'].'</td>
					<td style="border:1px solid #dee2e6;padding:6px;text-align:right">'.number_format($sisa,0,",",".").'</td>
					<td style="border:1px solid #dee2e6;padding:6px;text-align:right">'.number_format($r['options']['qty_muat'],0,",",".").'</td>
					<td style="border:1px solid #dee2e6;padding:6px;text-align:right">'.$r['options']['rk_bb'].'</td>
					<td style="border:1px solid #dee2e6;padding:6px;text-align:right;font-weight:bold">'.number_format($r['options']['rk_tonase'],0,",",".").'</td>
					<td style="border:1px solid #dee2e6;padding:6px;text-align:center" colspan="2">
						<button type="button" class="btn btn-sm btn-danger btn-block" onclick="hapusCartRKSJ('."'".$r['rowid']."'".')">batal</button>
					</td>
				</tr>';
				$sumTon += $r['options']['rk_tonase'];
			}

			if($this->cart->total_items() != 1){
				$html .='<tr style="background:#dee2e6">
					<td style="padding:6px;font-weight:bold;text-align:right;border:1px solid #bbb" colspan="7">TOTAL</td>
					<td style="padding:6px;font-weight:bold;text-align:right;border:1px solid #bbb">'.number_format($sumTon,0,",",".").'</td>
					<td style="border:1px solid #bbb" colspan="2"></td>
				</tr>';
			}
		}

		if($this->cart->total_items() != 0){
			$html .= '<tr>
				<td style="padding:6px" colspan="10">
					<button type="button" id="simpan_rk" class="btn btn-sm btn-primary" style="font-weight:bold" onclick="simpanCartRKSJ()"><i class="fas fa-save"></i> SIMPAN</button>
				</td>
			</tr>';
		}
		
		if($cekRK->num_rows() != 0){
			foreach($cekRK->result() as $rk){
				if($rk->rk_urut == 0){
					$txtUrut = '-';
					$btnPengiriman = '';
				}else{
					$txtUrut = 'RENCANA KIRIM '.$rk->rk_urut;
					($this->session->userdata('level') == 'Admin') ? $btnPengiriman = '<button type="button" id="btn-fix-kirim" class="btn btn-xs btn-primary" style="font-weight:bold" onclick="selesaiMuat('."'".$rk->rk_urut."'".')">SELESAI MUAT</button> - ' : $btnPengiriman = '';
				}
				$html .='<tr>
					<td style="background:#333;color:#fff;padding:6px" colspan="10">'.$btnPengiriman.''.$txtUrut.'</td>
				</tr>';

				$date = date('Y-m-d');
				$getIsi = $this->db->query("SELECT p.nm_pelanggan,i.kategori,i.nm_produk,r.* FROM m_rencana_kirim r
				INNER JOIN m_pelanggan p ON r.id_pelanggan=p.id_pelanggan
				INNER JOIN m_produk i ON r.id_produk=i.id_produk
				WHERE r.rk_urut='$rk->rk_urut' AND r.rk_tgl='$date' ORDER BY p.nm_pelanggan,r.rk_kode_po,r.id_gudang,i.nm_produk");
				$sumTonn = 0;
				foreach($getIsi->result() as $isi){
					$gudang = $this->db->query("SELECT*FROM m_gudang WHERE id_gudang='$isi->id_gudang'")->row();
					$muat = $this->db->query("SELECT SUM(qty_muat) AS qty_muat FROM m_rencana_kirim WHERE id_gudang='$isi->id_gudang' GROUP BY id_gudang")->row();
					$siissa = $gudang->gd_good_qty - $muat->qty_muat;
					($isi->kategori == "K_BOX") ? $kategori = '[BOX] ' : $kategori = '[SHEET] ';
					$html .='<tr>
						<td style="border:1px solid #dee2e6;padding:6px">
							<input type="number" class="form-control" style="height:100%;width:30px;text-align:center;padding:4px" id="rk-urut-'.$isi->id_rk.'" value="'.$isi->rk_urut.'" onkeyup="editListUrutRK('."'".$isi->id_rk."'".')">
						</td>
						<td style="border:1px solid #dee2e6;padding:6px">'.$isi->nm_pelanggan.' <span class="bg-secondary" style="vertical-align:top;font-weight:bold;padding:2px 4px;font-size:11px;border-radius:4px">'.$isi->id_gudang.'</span></td>
						<td style="border:1px solid #dee2e6;padding:6px">'.$isi->rk_kode_po.'</td>
						<td style="border:1px solid #dee2e6;padding:6px">'.$kategori.''.$isi->nm_produk.'</td>
						<td style="border:1px solid #dee2e6;padding:6px;text-align:right;font-weight:bold">
							<span class="rk-span-sisa-'.$isi->id_rk.'">'.number_format($siissa).'</span>
						</td>
						<td style="border:1px solid #dee2e6;padding:6px;text-align:right">
							<input type="hidden" id="rk-hidden-bb-'.$isi->id_rk.'" value="'.$isi->rk_bb.'">
							<input type="hidden" id="rk-hidden-sisa-'.$isi->id_rk.'" value="'.$siissa.'">
							<input type="hidden" id="rk-hidden-muat-lama-'.$isi->id_rk.'" value="'.$isi->qty_muat.'">
							<input type="number" class="form-control" style="height:100%;width:70px;text-align:right;padding:4px 6px;font-weight:bold" id="rk-qty-muat-'.$isi->id_rk.'" value="'.$isi->qty_muat.'" onkeyup="hitungListRK('."'".$isi->id_rk."'".')">
						</td>
						<td style="border:1px solid #dee2e6;padding:6px;text-align:right">'.$isi->rk_bb.'</td>
						<td style="border:1px solid #dee2e6;padding:6px;text-align:right;font-weight:bold">
							<input type="hidden" id="rk-hidden-h-ton-'.$isi->id_rk.'" value="'.$isi->rk_tonase.'">
							<span class="rk-span-ton-'.$isi->id_rk.'">'.number_format($isi->rk_tonase).'</span>
						</td>
						<td style="border:1px solid #dee2e6;padding:3px;text-align:center">
							<button type="button" id="btn-edit-rk" class="btn btn-sm btn-warning" style="font-weight:bold" onclick="editListRencanaKirim('."'".$isi->id_rk."'".')">EDIT</button>
						</td>
						<td style="border:1px solid #dee2e6;padding:3px;text-align:center">
							<button type="button" id="btn-hapus-rk" class="btn btn-sm btn-danger" style="font-weight:bold" onclick="hapusListRencanaKirim('."'".$isi->id_rk."'".')">HAPUS</button>
						</td>
					</tr>';
					$sumTonn += $isi->rk_tonase;
				}

				if($getIsi->num_rows() > 1){
					$html .='<tr style="background:#dee2e6">
						<td style="border:1px solid #bbb;padding:6px;font-weight:bold;text-align:right" colspan="7">TOTAL</td>
						<td style="border:1px solid #bbb;padding:6px;font-weight:bold;text-align:right">'.number_format($sumTonn,0,",",".").'</td>
						<td style="border:1px solid #bbb;padding:6px" colspan="2"></td>
					</tr>';
				}
			}
		}

		$html .='</table>';

		echo $html;
	}

	function simpanCartRKSJ()
	{
		$result = $this->m_logistik->simpanCartRKSJ();
		echo json_encode($result);
	}

	function editListUrutRK()
	{
		$result = $this->m_logistik->editListUrutRK();
		echo json_encode($result);
	}

	function editListRencanaKirim()
	{
		$result = $this->m_logistik->editListRencanaKirim();
		echo json_encode($result);
	}

	function hapusListRencanaKirim()
	{
		$result = $this->m_logistik->hapusListRencanaKirim();
		echo json_encode($result);
	}

	function selesaiMuat()
	{
		$result = $this->m_logistik->selesaiMuat();
		echo json_encode($result);
	}

	function listPengiriman()
	{
		$html = '';
		$tgl = $_POST["tgl_kirim"];
		$tglNow = date('Y-m-d');

		$getUrut = $this->db->query("SELECT tgl,no_pl_urut,no_kendaraan FROM pl_box WHERE tgl='$tgl' GROUP BY no_pl_urut");
		if($getUrut->num_rows() == 0){
			$html .='<b>TIDAK ADA DATA PENGIRIMAN!</b>';
		}else{
			$html .='<table>
				<tr style="background:#dee2e6">
					<th style="padding:6px;border:1px solid #bbb">CUSTOMER <span class="bg-secondary" style="vertical-align:top;font-weight:bold;padding:2px 4px;font-size:11px;border-radius:4px">ID</span></th>
					<th style="padding:6px;border:1px solid #bbb">ITEM</th>
					<th style="padding:6px;border:1px solid #bbb">UKURAN</th>
					<th style="padding:6px;border:1px solid #bbb;text-align:center">FLUTE</th>
					<th style="padding:6px;border:1px solid #bbb">SUBSTANCE</th>
					<th style="padding:6px;border:1px solid #bbb;text-align:center">QTY</th>
					<th style="padding:6px;border:1px solid #bbb;text-align:center">BB</th>
					<th style="padding:6px;border:1px solid #bbb;text-align:center">TONASE</th>
				</tr>';

				foreach($getUrut->result() as $urut){
					($tglNow == $urut->tgl && $this->session->userdata('level') == 'Admin') ? $btnBtl = '<button type="button" class="btn btn-xs btn-danger" style="font-weight:bold" onclick="btnBatalPengiriman('."'".$urut->tgl."'".','."'".$urut->no_pl_urut."'".')">BATAL</button> - ' : $btnBtl = '' ;
					($tglNow == $urut->tgl && $this->session->userdata('level') == 'Admin') ? $editPL = 'onchange="addPengirimanNoPlat('."'".$urut->tgl."'".','."'".$urut->no_pl_urut."'".')"' : $editPL = 'disabled';
					$html .='<tr>
						<td style="background:#333;color:#fff;padding:6px;font-weight:bold">'.$btnBtl.''.$urut->no_pl_urut.'</td>
						<td style="background:#333;color:#fff;padding:6px;text-align:right;font-weight:bold" colspan="5">NO. PLAT :</td>
						<td style="background:#333;color:#fff;padding:6px;text-align:right" colspan="2">
							<input type="text" class="form-control" id="pp-noplat-'.$urut->no_pl_urut.'" style="height:100%;width:100px;text-align:center;padding:2px 4px;font-weight:bold" placeholder="-" autocomplete="off" oninput="this.value=this.value.toUpperCase()" value="'.$urut->no_kendaraan.'" '.$editPL.'>
						</td>
					</tr>';

					$getSJnPO = $this->db->query("SELECT*FROM pl_box WHERE tgl='$urut->tgl' AND no_pl_urut='$urut->no_pl_urut'
					GROUP BY id_perusahaan,no_surat,no_po,no_pl_urut
					ORDER BY no_surat");
					$no = 0;
					$sumAll = 0;
					foreach($getSJnPO->result() as $sjpo){
						$no++;
						$noSJ = explode('/', $sjpo->no_surat);
						($noSJ[4] == 'A') ? $pajak = '<span style="background:#f8f9fa;height:100%;padding:0 4px;border-radius:2px;font-size:12px;font-weight:bold">PPN</span>' : $pajak = '<span style="background:#f8f9fa;height:100%;padding:0 4px;border-radius:2px;font-size:12px;font-weight:bold">NON</span>' ;
						($this->session->userdata('level') == 'Admin' && $noSJ[0] != 000) ?
							$btnPrint = '<a target="_blank" class="btn btn-xs btn-success" style="font-weight:bold" href="'.base_url("Logistik/printSuratJalan?jenis=".$sjpo->no_pkb."&top=100&ctk=0").'" title="'.$sjpo->no_surat.'" >PRINT</a>' :
							$btnPrint = '<span style="background:#6c757d;padding:2px 4px;border-radius:2px;color:#fff;font-size:12px;font-weight:bold">PRINT</span>';

						($this->session->userdata('level') == 'Admin' && $sjpo->cetak_sj == 'not') ? $eNoSj = 'onchange="editPengirimanNoSJ('."'".$sjpo->id."'".')"' : $eNoSj = 'disabled';
						$html .='<tr style="background:#dee2e6">
							<td style="padding:4px 6px;border:1px solid #bbb;font-weight:bold;display:flex">
								'.$this->m_fungsi->angkaRomawi($no).' - NO. SURAT JALAN : &nbsp;<input type="number" class="form-control" id="pp-nosj-'.$sjpo->id.'" style="height:100%;width:50px;text-align:center;padding:2px 4px" value="'.$noSJ[0].'" '.$eNoSj.'>/'.$noSJ[1].'/'.$noSJ[2].'/'.$noSJ[3].'/'.$noSJ[4].'&nbsp;'.$pajak.'
							</td>
							<td style="padding:6px;border:1px solid #bbb;font-weight:bold">NO. PO : '.$sjpo->no_po.'</td>
							<td style="padding:6px;border:1px solid #bbb;font-weight:bold" colspan="6">'.$btnPrint.'</td>
						</tr>';

						$getItems = $this->db->query("SELECT r.*,i.*,p.nm_pelanggan FROM m_rencana_kirim r
						INNER JOIN m_produk i ON r.id_produk=i.id_produk
						INNER JOIN m_pelanggan p ON r.id_pelanggan=p.id_pelanggan
						WHERE r.rk_tgl='$sjpo->tgl' AND r.rk_urut='$sjpo->no_pl_urut' AND r.rk_kode_po='$sjpo->no_po' AND r.id_pelanggan='$sjpo->id_perusahaan'
						ORDER BY i.nm_produk");
						$sumItems = 0;
						foreach($getItems->result() as $item){
							($item->kategori == "K_BOX") ? $ukuran = $item->ukuran : $ukuran = $item->ukuran_sheet;
							$expKualitas = explode("/", $item->kualitas);
							if($item->flute == 'BCF'){
								if($expKualitas[1] == 'M125' && $expKualitas[2] == 'M125' && $expKualitas[3] == 'M125'){
									$kualitas = $expKualitas[0].'/'.$expKualitas[1].'x3/'.$expKualitas[4];
								}else if($expKualitas[1] == 'K125' && $expKualitas[2] == 'K125' && $expKualitas[3] == 'K125'){
									$kualitas = $expKualitas[0].'/'.$expKualitas[1].'x3/'.$expKualitas[4];
								}else if($expKualitas[1] == 'M150' && $expKualitas[2] == 'M150' && $expKualitas[3] == 'M150'){
									$kualitas = $expKualitas[0].'/'.$expKualitas[1].'x3/'.$expKualitas[4];
								}else if($expKualitas[1] == 'K150' && $expKualitas[2] == 'K150' && $expKualitas[3] == 'K150'){
									$kualitas = $expKualitas[0].'/'.$expKualitas[1].'x3/'.$expKualitas[4];
								}else{
									$kualitas = $item->kualitas;
								}
							}else{
								$kualitas = $item->kualitas;
							}
							$tonase = $item->rk_bb * $item->qty_muat;

							$html .='<tr>
								<td style="padding:6px;border:1px solid #dee2e6">'.$item->nm_pelanggan.' <span class="bg-secondary" style="vertical-align:top;font-weight:bold;padding:2px 4px;font-size:11px;border-radius:4px">'.$item->id_gudang.'</span></td>
								<td style="padding:6px;border:1px solid #dee2e6">'.$item->nm_produk.'</td>
								<td style="padding:6px;border:1px solid #dee2e6">'.$ukuran.'</td>
								<td style="padding:6px;border:1px solid #dee2e6;text-align:center">'.$item->flute.'</td>
								<td style="padding:6px;border:1px solid #dee2e6">'.$kualitas.'</td>
								<td style="padding:6px;border:1px solid #dee2e6;font-weight:bold;text-align:right">'.number_format($item->qty_muat,0,",",".").'</td>
								<td style="padding:6px;border:1px solid #dee2e6">'.$item->rk_bb.'</td>
								<td style="padding:6px;border:1px solid #dee2e6;font-weight:bold;text-align:right">'.number_format($tonase,0,",",".").'</td>
							</tr>';
							$sumItems += $tonase;
						}
						$sumAll += $sumItems;
					}
					if($getSJnPO->num_rows() > 1 || ($getSJnPO->num_rows() == 1 && $getItems->num_rows() > 1)){
						$html .='<tr style="background:#dee2e6">
							<td style="padding:6px;border:1px solid #bbb;font-weight:bold;text-align:right" colspan="7">TOTAL</td>
							<td style="padding:6px;border:1px solid #bbb;font-weight:bold;text-align:right">'.number_format($sumAll,0,",",".").'</td>
						</tr>';
					}
				}

			$html .='</table>';
		}

		echo $html;
	}

	function btnBatalPengiriman()
	{
		$result = $this->m_logistik->btnBatalPengiriman();
		echo json_encode($result);
	}

	function addPengirimanNoPlat()
	{
		$result = $this->m_logistik->addPengirimanNoPlat();
		echo json_encode($result);
	}

	function editPengirimanNoSJ()
	{
		$result = $this->m_logistik->editPengirimanNoSJ();
		echo json_encode($result);
	}

	function printSuratJalan(){
        $jenis = $_GET['jenis'];
        $top = $_GET['top'];
        $ctk = $_GET['ctk'];
        $html = '';

		// UPDATE CETAK
		$this->db->query("UPDATE pl_box SET cetak_sj='acc' WHERE no_pkb='$jenis'");

        $data_pl = $this->db->query("SELECT h.id_hub,h.nm_hub,h.alamat AS alamat_hub,b.nm_pelanggan,b.attn,b.alamat_kirim,b.no_telp,a.* FROM pl_box a
		INNER JOIN m_pelanggan b ON a.id_perusahaan=b.id_pelanggan
		INNER JOIN trs_po p ON a.no_po=p.kode_po
		LEFT JOIN m_hub h ON p.id_hub=h.id_hub
		WHERE a.no_pkb='$jenis'
		GROUP BY a.no_pkb")->row();

        // KOP
		if($data_pl->id_hub != null && $data_pl->kop_sj == null){
			$pt = $data_pl->nm_hub	;
			$rowspan = 2;
			$alamat = '<tr>
				<td style="font-size:14px;height:71px">'.$data_pl->alamat_hub.'</td>
			</tr>';
		}else{
			$pt = 'PT. PRIMA PAPER INDONESIA';
			$rowspan = 4;
			$alamat = '<tr>
				<td style="font-size:14px">Dusun Timang Kulon, Desa Wonokerto, Kec.Wonogiri, Kab.Wonogiri</td>
			</tr>
			<tr>
				<td style="font-size:14px;padding:0">WONOGIRI - JAWA TENGAH - INDONESIA Kode Pos 57615</td>
			</tr>
			<tr>
				<td style="font-size:12px !important;padding:0 0 18px">http://primapaperindonesia.com</td>
			</tr>';
		}

		// PPN
        $kop = '<table style="font-size:11px;color:#000;border-collapse:collapse;vertical-align:top;width:100%;text-align:center;font-weight:bold;font-family:Arial !important">
            <tr>
                <th style="width:25% !important;height:'.$top.'px"></th>
                <th style="width:75% !important;height:'.$top.'px"></th>
            </tr>
            <tr>
                <td style="background:url('.base_url().'assets/gambar/logo_ppi_sj.png)center no-repeat" rowspan="'.$rowspan.'"></td>
                <td style="font-size:32px">'.$pt.'</td>
            </tr>
			'.$alamat.'
        </table>
        <table cellspacing="0" style="font-size:18px;color:#000;border-collapse:collapse;vertical-align:top;width:100%;text-align:center;font-weight:bold;font-family:Arial !important">
            <tr>
                <th style="width:15% !important;height:8px"></th>
            </tr>
            <tr>
                <td style="border-top:2px solid #000;padding:10px 0 5px;text-decoration:underline">SURAT JALAN</td>
            </tr>
        </table>';
		// NON PPN
        $gak_kop = '<table cellspacing="0" style="font-size:18px;color:#000;border-collapse:collapse;vertical-align:top;width:100%;text-align:center;font-weight:bold;font-family:Arial !important">
            <tr>
                <th style="width:15% !important;height:150px"></th>
            </tr>
            <tr>
                <td style="border-top:2px solid #000;padding:10px 0 5px;text-decoration:underline">SURAT JALAN</td>
            </tr>
        </table>';

        if($data_pl->pajak == 'non'){
            $html .= $gak_kop;
        }else{
            $html .= $kop;
        }
        
		// DETAIL
        $html .= '<table cellspacing="0" style="font-size:11px !important;color:#000;border-collapse:collapse;vertical-align:top;width:100%;font-family:Arial !important">
            <tr>
                <th style="width:15% !important;height:8px"></th>
                <th style="width:1% !important;height:8px"></th>
                <th style="width:28% !important;height:8px"></th>
                <th style="width:15% !important;height:8px"></th>
                <th style="width:1% !important;height:8px"></th>
                <th style="width:40% !important;height:8px"></th>
            </tr>';
			if($data_pl->tgl == "0000-00-00" || $data_pl->tgl == "0001-00-00" || $data_pl->tgl == ""){
				$kett_tgll = "";
			}else{
				$kett_tgll = $this->m_fungsi->tanggal_format_indonesia($data_pl->tgl);
			}
			$html .= '<tr>
				<td style="padding:5px 0">TANGGAL</td>
				<td style="text-align:center;padding:5px 0">:</td>
				<td style="padding:5px 0">'.$kett_tgll.'</td>
				<td style="padding:5px 0">KEPADA</td>
				<td style="text-align:center;padding:5px 0">:</td>
				<td style="padding:5px 0">'.$data_pl->nm_pelanggan.'</td>
			</tr>
			<tr>
				<td style="padding:5px 0">NO. SURAT JALAN</td>
				<td style="text-align:center;padding:5px 0">:</td>
				<td style="padding:5px 0">'.$data_pl->no_surat.'</td>
				<td style="padding:5px 0">ATTN</td>
				<td style="text-align:center;padding:5px 0">:</td>
				<td style="padding:5px 0">'.$data_pl->attn.'</td>
			</tr>
			<tr>
				<td style="padding:5px 0">NO. SO</td>
				<td style="text-align:center;padding:5px 0">:</td>
				<td style="padding:5px 0">'.$data_pl->no_so.'</td>
				<td style="padding:5px 0">ALAMAT</td>
				<td style="text-align:center;padding:5px 0">:</td>
				<td style="padding:5px 0">'.$data_pl->alamat_kirim.'</td>
			</tr>
			<tr>
				<td style="padding:5px 0">NO. PKB</td>
				<td style="text-align:center;padding:5px 0">:</td>
				<td style="padding:5px 0">'.$data_pl->no_pkb.'</td>
				<td style="padding:5px 0">NO. TELP / HP</td>
				<td style="text-align:center;padding:5px 0">:</td>
				<td style="padding:5px 0">'.$data_pl->no_telp.'</td>
			</tr>
			<tr>
				<td style="padding:5px 0">NO. KENDARAAN</td>
				<td style="text-align:center;padding:5px 0">:</td>
				<td style="padding:5px 0">'.$data_pl->no_kendaraan.'</td>
				<td style="padding:5px 0"></td>
				<td style="text-align:center;padding:5px 0"></td>
				<td style="padding:5px 0"></td>
			</tr>';
        $html .= '</table>';

		// ISI
        $html .= '<table cellspacing="0" style="font-size:11px !important;color:#000;border-collapse:collapse;text-align:center;width:100%;font-family:Arial !important">
			<tr>
				<th style="width:5% !important;height:15px"></th>
				<th style="width:25% !important;height:15px"></th>
				<th style="width:30% !important;height:15px"></th>
				<th style="width:8% !important;height:15px"></th>
				<th style="width:13% !important;height:15px"></th>
				<th style="width:19% !important;height:15px"></th>
			</tr>
			<tr>
				<td style="border:1px solid #000;padding:5px 0">NO</td>
				<td style="border:1px solid #000;padding:5px 0">NO. PO</td>
				<td style="border:1px solid #000;padding:5px 0">ITEM DESCRIPTION</td>
				<td style="border:1px solid #000;padding:5px 0">FLUTE</td>
				<td style="border:1px solid #000;padding:5px 0">QTY</td>
				<td style="border:1px solid #000;padding:5px 0">KETERANGAN</td>
			</tr>';

			// AMBIL DATA
			$data_detail = $this->db->query("SELECT r.*,p.*,i.*,SUM(r.qty_muat) AS muat FROM m_rencana_kirim r
			INNER JOIN pl_box p ON r.id_pl_box=p.id
			INNER JOIN m_produk i ON r.id_produk=i.id_produk
			WHERE p.no_pkb='$jenis'
			GROUP BY r.id_pelanggan,r.id_produk,r.rk_kode_po");
			$no = 0;
			$sumQty = 0;
			foreach ($data_detail->result() as $data ) {
				$no++;
				$expKualitas = explode("/", $data->kualitas);
				if($data->flute == 'BCF'){
					if($expKualitas[1] == 'M125' && $expKualitas[2] == 'M125' && $expKualitas[3] == 'M125'){
						$kualitas = $expKualitas[0].'/'.$expKualitas[1].'x3/'.$expKualitas[4];
					}else if($expKualitas[1] == 'K125' && $expKualitas[2] == 'K125' && $expKualitas[3] == 'K125'){
						$kualitas = $expKualitas[0].'/'.$expKualitas[1].'x3/'.$expKualitas[4];
					}else if($expKualitas[1] == 'M150' && $expKualitas[2] == 'M150' && $expKualitas[3] == 'M150'){
						$kualitas = $expKualitas[0].'/'.$expKualitas[1].'x3/'.$expKualitas[4];
					}else if($expKualitas[1] == 'K150' && $expKualitas[2] == 'K150' && $expKualitas[3] == 'K150'){
						$kualitas = $expKualitas[0].'/'.$expKualitas[1].'x3/'.$expKualitas[4];
					}else{
						$kualitas = $data->kualitas;
					}
				}else{
					$kualitas = $data->kualitas;
				}
				if($data->kategori == 'K_BOX'){
					$ukuran = $data->nm_produk.'. '.strtolower(str_replace(' ', '', $data->ukuran)).'. '.$kualitas;
					$qty_ket = 'PCS';
				}else{
					$ukuran = $data->ukuran_sheet.'. '.$kualitas;
					$qty_ket = 'LEMBAR';
				}
				($data->flute == "BCF") ? $flute = 'BC' : $flute = $data->flute;
				$html .= '<tr>
					<td style="border:1px solid #000;padding:5px 0">'.$no.'</td>
					<td style="border:1px solid #000;padding:5px 0">'.$data->rk_kode_po.'</td>
					<td style="border:1px solid #000;padding:5px 2px">'.$ukuran.'</td>
					<td style="border:1px solid #000;padding:5px 0">'.$flute.'</td>
					<td style="border:1px solid #000;padding:5px 0">'.number_format($data->muat).' '.$qty_ket.'</td>
					<td style="border:1px solid #000;padding:5px 0"></td>
				</tr>';
				$sumQty += $data->muat;
			}

			// TAMBAH KOTAK KOSONG
			if($data_detail->num_rows() == 1) {
				$xx = 5;
			}else if($data_detail->num_rows() == 2){
				$xx = 4;
			}else if($data_detail->num_rows() == 3){
				$xx = 3;
			}else if($data_detail->num_rows() == 4){
				$xx = 2;
			}else if($data_detail->num_rows() == 5){  
				$xx = 1;
			}
			
			// TAMBAH KOTAK KOSONG
			if($data_detail->num_rows() <= 5) {
				for($i = 0; $i < $xx; $i++){
					$html .= '<tr>
						<td style="border:1px solid #000;padding:23px 0 0"></td>
						<td style="border:1px solid #000;padding:23px 0 0"></td>
						<td style="border:1px solid #000;padding:23px 0 0"></td>
						<td style="border:1px solid #000;padding:23px 0 0"></td>
						<td style="border:1px solid #000;padding:23px 0 0"></td>
						<td style="border:1px solid #000;padding:23px 0 0"></td>
					</tr>';
				}
			}
			
			// TOTAL
			$html .= '<tr>
				<td style="border:1px solid #000;padding:5px 0" colspan="4">TOTAL</td>
				<td style="border:1px solid #000;padding:5px 0">'.number_format($sumQty).' '.$qty_ket.'</td>
				<td style="border:1px solid #000;padding:5px 0"></td>
			</tr>';

        $html .= '</table>';

        // TTD
        $html .= '<table cellspacing="0" style="font-size:11px;color:#000;border-collapse:collapse;text-align:center;width:100%;font-family:Arial !important">
			<tr>
				<th style="width:14% !important;height:35px"></th>
				<th style="width:14% !important;height:35px"></th>
				<th style="width:14% !important;height:35px"></th>
				<th style="width:15% !important;height:35px"></th>
				<th style="width:15% !important;height:35px"></th>
				<th style="width:14% !important;height:35px"></th>
				<th style="width:14% !important;height:35px"></th>
			</tr>
			<tr>
				<td style="border:1px solid #000;padding:5px 0">DIBUAT</td>
				<td style="border:1px solid #000;padding:5px 0" colspan="2">DIKELUARKAN OLEH</td>
				<td style="border:1px solid #000;padding:5px 0">DI KETAHUI</td>
				<td style="border:1px solid #000;padding:5px 0">DI SETUJUI</td>
				<td style="border:1px solid #000;padding:5px 0">SOPIR</td>
				<td style="border:1px solid #000;padding:5px 0">DITERIMA</td>
			</tr>
			<tr>
				<td style="border:1px solid #000;height:80px"></td>
				<td style="border:1px solid #000;height:80px"></td>
				<td style="border:1px solid #000;height:80px"></td>
				<td style="border:1px solid #000;height:80px"></td>
				<td style="border:1px solid #000;height:80px"></td>
				<td style="border:1px solid #000;height:80px"></td>
				<td style="border:1px solid #000;height:80px"></td>
			</tr>
			<tr>
				<td style="border:1px solid #000;padding:5px 0">ARGA <br>ADMIN</td>
				<td style="border:1px solid #000;padding:5px 0">DION<br>PPIC</td>
				<td style="border:1px solid #000;padding:5px 0">BP. SUMARTO<br>SPV GUDANG</td>
				<td style="border:1px solid #000;padding:5px 0"></td>
				<td style="border:1px solid #000;padding:5px 0"></td>
				<td style="border:1px solid #000"></td>
				<td style="border:1px solid #000"></td>
			</tr>
			<tr>
				<td style="height:30px" colspan="7"></td>
			</tr>
			<tr>
				<td style="font-weight:normal;text-align:left;padding:3px 0" colspan="4">NOTE :</td>
				<td style="border:1px solid #000;padding:5px 0;font-weight:bold;font-size:12" colspan="3">PERHATIAN</td>
			</tr>
			<tr>
				<td style="font-weight:normal;text-align:left;padding:3px 0 3px 40px">WHITE</td>
				<td style="font-weight:normal;text-align:left;padding:3px 0" colspan="3" >: PEMBELI / CUSTOMER</td>
				<td style="border:1px solid #000;font-size:13px;line-height:2;font-weight:bold" colspan="3" rowspan="5">KLAIM BARANG KURANG / RUSAK<br/>TIDAK DI TERIMA SETELAH TRUK / SOPIR<br/>KELUAR LOKASI BONGKAR</td>
			</tr>
			<tr>
				<td style="font-weight:normal;text-align:left;padding:3px 0 3px 40px">PINK</td>
				<td style="font-weight:normal;text-align:left;padding:3px 0">: FINANCE</td>
			</tr>
			<tr>
				<td style="font-weight:normal;text-align:left;padding:3px 0 3px 40px">YELLOW</td>
				<td style="font-weight:normal;text-align:left;padding:3px 0">: ACC</td>
			</tr>
			<tr>
				<td style="font-weight:normal;text-align:left;padding:3px 0 3px 40px">GREEN</td>
				<td style="font-weight:normal;text-align:left;padding:3px 0">: ADMIN</td>
			</tr>
			<tr>
				<td style="font-weight:normal;text-align:left;padding:3px 0 3px 40px">BLUE</td>
				<td style="font-weight:normal;text-align:left;padding:3px 0">: EXPEDISI</td>
			</tr>';
        $html .= '</table>';

        // CETAK
		$judul = 'SURAT JALAN';
        if($ctk == '0') {
			$this->m_fungsi->newMpdf($judul, '', $html, 1, 10, 1, 10, 'P', 'A4', '');
        }else{
            echo $html;
        }
    }

	//

	function printTimbangan(){
		$html = '';
		$id = $_GET["id"];
		// https://simcorrppi.com/Logistik/printTimbangan?id=1

		$data = $this->db->query("SELECT*FROM m_jembatan_timbang WHERE id_timbangan='$id'")->row();

		$html .= '<!DOCTYPE html>
		<html lang="en">
		<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<link rel="icon" type="image/png" href="'.base_url('assets/gambar/ppi.png').'">
			<title>TIMBANGAN - '.$data->id_timbangan.'</title>
		</head>
		<body style="font-family: Verdana, Geneva, Tahoma, sans-serif">
		
			<table style="text-align:center;border-collapse:collapse;width:100%;border-bottom:2px solid #000">
				<tr>
					<td style="font-weight:bold">PT. PRIMA PAPER INDONESIA</td>
				</tr>
				<tr>
					<td style="font-size:10px">Timang Kulon, Wonokerto</td>
				</tr>
				<tr>
					<td style="font-size:10px;padding-bottom:15px">WONOGIRI</td>
				</tr>
			</table>
			<table style="margin-bottom:16px;font-size:12px;border-collapse:collapse">
				<tr>
					<td style="padding:2px 0">Suplier</td>
					<td style="padding:0 4px 0 20px">:</td>
					<td style="padding:2px 0">'.$data->suplier.'</td>
				</tr>
				<tr>
					<td style="padding:2px 0">Alamat</td>
					<td style="padding:0 4px 0 20px">:</td>
					<td style="padding:2px 0">'.$data->alamat.'</td>
				</tr>
				<tr>
					<td style="padding:2px 0">No. Polisi</td>
					<td style="padding:0 4px 0 20px">:</td>
					<td style="padding:2px 0">'.$data->no_polisi.'</td>
				</tr>
				<tr>
					<td style="padding:2px 0">Masuk</td>
					<td style="padding:0 4px 0 20px">:</td>
					<td style="padding:2px 0">'.$this->m_fungsi->tanggal_ind(substr($data->date_masuk,0,10)).' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; '.substr($data->date_masuk,11).'</td>
				</tr>
				<tr>
					<td style="padding:2px 0">Keluar</td>
					<td style="padding:0 4px 0 20px">:</td>
					<td style="padding:2px 0">'.$this->m_fungsi->tanggal_ind(substr($data->date_keluar,0,10)).' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; '.substr($data->date_keluar,11).'</td>
				</tr>
				<tr>
					<td style="padding:2px 0">Barang</td>
					<td style="padding:0 4px 0 20px">:</td>
					<td style="padding:2px 0">'.$data->nm_barang.'</td>
				</tr>
			</table>
			<table style="text-align:center;border-collapse:collapse;width:100%" border="1">
				<tr>
					<td style="border:0;width:2%;"></td>
					<td style="border:0;width:30%;font-size:13px">BERAT KOTOR</td>
					<td style="border:0;width:3%;"></td>
					<td style="border:0;width:30%;font-size:13px">BERAT TRUK</td>
					<td style="border:0;width:3%;"></td>
					<td style="border:0;width:30%;font-size:13px">BERAT BERSIH</td>
					<td style="border:0;width:2%;"></td>
				</tr>
				<tr>
					<td style="border:0"></td>
					<td style="padding:4px 0;font-weight:bold;font-size:17px">'.number_format($data->berat_kotor).'</td>
					<td style="border:0"></td>
					<td style="padding:4px 0;font-weight:bold;font-size:17px">'.number_format($data->berat_truk).'</td>
					<td style="border:0"></td>
					<td style="padding:4px 0;font-weight:bold;font-size:17px">'.number_format($data->berat_bersih).'</td>
					<td style="border:0"></td>
				</tr>
				<tr>
					<td style="border:0"></td>
					<td style="border:0;text-align:left;font-size:14px">POTONGAN :</td>
					<td style="border:0"></td>
					<td style="border:0;font-size:14px">'.number_format($data->potongan).' KG</td>
					<td style="border:0"></td>
					<td style="border:0"></td>
					<td style="border:0"></td>
				</tr>
				<tr>
					<td style="border:0;padding:8px 0 23px;font-size:11px;text-align:left" colspan="7">Catatan : '.$data->catatan.'</td>
				</tr>
			</table>
			<table style="width:100%;margin-bottom:5px;text-align:center;border-collapse:collapse;font-size:11px" border="1">
				<tr>
					<td style="border-bottom:0;padding-top:3px;width:32%">PENIMBANG</td>
					<td style="border:0;width:2%"></td>
					<td style="border-bottom:0;padding-top:3px;width:32%">SATPAM</td>
					<td style="border:0;width:2%"></td>
					<td style="border-bottom:0;padding-top:3px;width:32%">SOPIR</td>
				</tr>
				<tr>
					<td style="border-top:0;border-bottom:0;padding:43px 0"></td>
					<td style="border:0"></td>
					<td style="border-top:0;border-bottom:0;padding:43px 0"></td>
					<td style="border:0"></td>
					<td style="border-top:0;border-bottom:0;padding:43px 0"></td>
				</tr>
				<tr>
					<td style="border-top:0;padding-bottom:3px;">'.$data->nm_penimbang.'</td>
					<td style="border:0"></td>
					<td style="border-top:0;padding-bottom:3px;">(. . . . . . . . . .)</td>
					<td style="border:0"></td>
					<td style="border-top:0">'.$data->nm_sopir.'</td>
				</tr>
			</table>
			<table style="width:100%;border-top:2px solid #000">
				<tr>
					<td style="text-align:right;font-size:12px">'.$data->keterangan.'</td>
				</tr>
			</table>

		</body>
		</html>';

		// echo $html;
		$judul = 'TIMBANGAN';
		$this->m_fungsi->newMpdf($judul, '', $html, 6, 3, 3, 3, 'P', 'TT', $judul.'.pdf');
	}

}
