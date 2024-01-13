<?php
class M_plan extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
		$this->load->model('m_master');
		$this->load->model('m_transaksi');
		$this->load->model('m_plan');
	}

	function loadPlanWo()
	{
		$opsi = $_POST["opsi"];
		if($opsi != ''){
			$query = $this->db->query("SELECT *,(SELECT COUNT(a.no_plan) FROM plan_cor a
			-- WHERE a.id_wo=w.id) AS jml_plan,w.qty AS qtyPoWo,w.status AS statusWo,i.kategori AS kategoriItems,w.creasing2 AS creasing2wo,w.tgl_wo,i.creasing2 AS creasing2mproduk FROM plan_cor pl
			WHERE a.id_wo=w.id) AS jml_plan,po.qty AS qtyPoWo,w.status AS statusWo,i.kategori AS kategoriItems,w.creasing2 AS creasing2wo,w.tgl_wo,i.creasing2 AS creasing2mproduk FROM plan_cor pl
			INNER JOIN m_produk i ON pl.id_produk=i.id_produk
			INNER JOIN m_pelanggan l ON pl.id_pelanggan=l.id_pelanggan
			INNER JOIN m_sales m ON l.id_sales=m.id_sales
			INNER JOIN trs_wo w ON pl.id_wo=w.id
			INNER JOIN trs_so_detail s ON pl.id_so_detail=s.id
			INNER JOIN trs_po_detail po ON s.kode_po=po.kode_po
			WHERE pl.id_plan='$opsi'");
		}else{
			$no_plan = $_POST["urlNoPlan"];
			($no_plan != '') ? $whereNotExists = "AND NOT EXISTS (SELECT * FROM plan_cor pp WHERE pp.no_wo=w.no_wo AND pp.no_plan='$no_plan')" : $whereNotExists = '' ;
			$query = $this->db->query("SELECT (SELECT COUNT(a.no_plan) FROM plan_cor a
			WHERE a.id_wo=w.id) AS jml_plan,w.*,i.*,s.*,o.tgl_po,o.total_qty,p.nm_pelanggan,p.alamat,p.prov,p.kab,m.nm_sales,s.id AS idSoDetail,w.id AS idWo,w.creasing2 AS creasing2wo,i.kategori AS kategoriItems FROM trs_wo w
			INNER JOIN m_pelanggan p ON w.id_pelanggan=p.id_pelanggan
			INNER JOIN m_sales m ON p.id_sales=m.id_sales
			INNER JOIN m_produk i ON w.id_produk=i.id_produk
			INNER JOIN trs_po o ON w.no_po=o.no_po AND w.kode_po=o.kode_po
			INNER JOIN trs_so_detail s ON w.no_po=s.no_po AND w.kode_po=s.kode_po AND w.id_pelanggan=s.id_pelanggan AND w.id_produk=s.id_produk
			WHERE w.status='Open' $whereNotExists
			AND w.no_so=s.id
			GROUP BY w.id,w.id_pelanggan,w.id_produk,p.id_pelanggan,i.id_produk,s.id
			ORDER BY p.nm_pelanggan");
		}
		return $query;
	}

	function addDtProd()
	{
		$opsi = $_POST["opsi"];
		if($opsi != ''){
			$getNoPlan = $this->db->query("SELECT*FROM plan_cor WHERE id_plan='$opsi'")->row();
			return $this->db->query("SELECT*FROM plan_cor
			WHERE no_plan='$getNoPlan->no_plan' AND total_cor_p='0' AND no_urut_plan!='0'
			ORDER BY no_urut_plan ASC LIMIT 1")->row();
		}else{
			return false;
		}
	}

	function loadDataPlan()
	{
		$tgl_plan = $_POST["tgl_plan"];
		$shift = $_POST["shift"];
		$mesin = $_POST["mesin"];
		
		return $this->db->query("SELECT wo.status AS statusWo,pl.* FROM plan_cor pl
		INNER JOIN trs_wo wo ON pl.id_wo=wo.id
		WHERE pl.tgl_plan='$tgl_plan' AND pl.shift_plan='$shift' AND pl.machine_plan='$mesin'")->result();
	}

	function simpanCartItem()
	{
		$tgl_plan = $_POST["tgl_plan"];
		$machine_plan = $_POST["machine_plan"];
		$shift_plan = $_POST["shift_plan"];
		$cekPlan = $this->db->query("SELECT*FROM plan_cor WHERE tgl_plan='$tgl_plan' AND shift_plan='$shift_plan' AND machine_plan='$machine_plan' GROUP BY no_plan");
		if($_POST['no_plan'] == '' && $cekPlan->num_rows() == 0){
			$plan_no = $this->m_fungsi->urut_transaksi('PLAN');
			$bln = $this->m_master->get_romawi(date('m'));
			$tahun = date('Y');
		}

		foreach($this->cart->contents() as $r){
			// UPDATE SCORE WO
			if(($r["options"]["kategori"] == 'K_SHEET')){
				$this->db->set("p1_sheet", $r["options"]["panjang_plan"]);
			}else{
				$this->db->set("kupingan", $r["options"]["kupingan"]);
				$this->db->set("p1", $r["options"]["p1"]);
				$this->db->set("l1", $r["options"]["l1"]);
				$this->db->set("p2", $r["options"]["p2"]);
				$this->db->set("l2", $r["options"]["l2"]);
			}

			$this->db->set("flap1", $r["options"]["creasing_wo1"]);
			$this->db->set("creasing2", $r["options"]["creasing_wo2"]);
			$this->db->set("flap2", $r["options"]["creasing_wo3"]);
			$this->db->set("edit_time", date("Y:m:d H:i:s"));
			$this->db->set("edit_user", $this->session->userdata('username'));
			$this->db->where("id", $r["options"]["id_wo"]);
			$updateScoreWO = $this->db->update("trs_wo");

			// INSERT PLAN COR
			if($_POST['no_plan'] == ''){
				if($cekPlan->num_rows() == 0){
					$noplan = 'PLAN/'.$tahun.'/'.$bln.'/'.$plan_no;
				}else{
					$noplan = $cekPlan->row()->no_plan;
				}
			}else{
				$noplan = $_POST['no_plan'];
			}

			$id_produk = $r["options"]["id_produk"];
			$item = $this->db->query("SELECT*FROM m_produk WHERE id_produk='$id_produk'")->row();
			$kualitas = explode("/", $r["options"]["kualitas_isi_plan"]);
			$BF = $kualitas[1] * 1.36;
			$CF = $kualitas[1] * 1.46;
			if($item->flute == "BCF"){
				$getNilaiFlute = ($kualitas[0] + ($kualitas[1] * 1.36) + $kualitas[2] + ($kualitas[3] * 1.46) + $kualitas[4]) / 1000;
			} else if($item->flute == "CF") {
				$getNilaiFlute = ($kualitas[0] + $CF + $kualitas[2]) / 1000;
			} else if($item->flute == "BF") {
				$getNilaiFlute = ($kualitas[0] + $BF + $kualitas[2]) / 1000;
			} else {
				$getNilaiFlute = 0;
			}
			if($item->kategori == "K_BOX"){
				if($item->flute == "BCF"){
					$ruk_p = 2 * ($item->l_panjang + $item->l_lebar) + 61;
					$ruk_l = $item->l_lebar + $item->l_tinggi + 23;
				} else if($item->flute == "CF") {
					$ruk_p = 2 * ($item->l_panjang + $item->l_lebar) + 43;
					$ruk_l = $item->l_lebar + $item->l_tinggi + 13;
				} else if($item->flute == "BF") {
					$ruk_p = 2 * ($item->l_panjang + $item->l_lebar) + 39;
					$ruk_l = $item->l_lebar + $item->l_tinggi + 9;
				} else {
					$ruk_p = 0;
					$ruk_l = 0;
				}
				$opsiRound = 4;
			}else{
				$ruk_p = $item->l_panjang;
				$ruk_l = $item->l_lebar;
				$opsiRound = 3;
			}
			$h_panjang = $ruk_p / 1000;
			$h_lebar = $ruk_l / 1000;
			$nilaiBeratBersih = round($getNilaiFlute * $h_panjang * $h_lebar, $opsiRound);

			$data = array(
				'no_plan' => $noplan,
				'id_so_detail' => $r["options"]["id_so_detail"],
				'id_wo' => $r["options"]["id_wo"],
				'id_produk' => $r["options"]["id_produk"],
				'id_pelanggan' => $r["options"]["id_pelanggan"],
				'no_wo' => $r["options"]["no_wo"],
				'no_so' => $r["options"]["no_so"],
				'pcs_plan' => $r["options"]["pcs_plan"],
				'tgl_plan' => $r["options"]["tgl_plan"],
				'machine_plan' => $r["options"]["machine_plan"],
				'shift_plan' => $r["options"]["shift_plan"],
				'tgl_kirim_plan' => $r["options"]["tgl_kirim_plan"],
				'next_plan' => $r["options"]["next_plan"],
				'lebar_roll_p' => $r["options"]["lebar_roll_p"],
				'panjang_plan' => $r["options"]["panjang_plan"],
				'lebar_plan' => $r["options"]["lebar_plan"],
				'out_plan' => $r["options"]["out_plan"],
				'trim_plan' => $r["options"]["trim_plan"],
				'c_off_p' => $r["options"]["c_off_p"],
				'rm_plan' => $r["options"]["rm_plan"],
				'tonase_plan' => $r["options"]["tonase_plan"],
				'material_plan' => $r["options"]["material_plan"],
				'kualitas_plan' => $r["options"]["kualitas_plan"],
				'kualitas_isi_plan' => $r["options"]["kualitas_isi_plan"],
				'bb_plan_p' => $nilaiBeratBersih,
				'status_plan' => 'Open',
				'good_cor_p' => 0,
				'bad_cor_p' => 0,
				'total_cor_p' => 0,
				'ket_plan' => '',
				'add_user' => $this->session->userdata('username'),
			);
			$insertPlanCor = $this->db->insert('plan_cor', $data);
		}

		return array(
			'updateScoreWO' => $updateScoreWO,
			'insertPlanCor' => $insertPlanCor,
			'noplan' => $noplan
		);
	}

	function addRencanaPlan()
	{
		$next = $_POST["next_plan"];
		$id_plan = $_POST['opsi'];
		if($_POST["opsi"] != 'add'){
			$cekFlexo = $this->db->query("SELECT*FROM plan_flexo WHERE id_plan_cor='$id_plan'");
			if($cekFlexo->num_rows() > 1){
				$updateScoreWO = false; $updatePlanCor = false;
				$msg = 'PLAN FLEXO LEBIH DARI SATU!';
			}else{
				if(($_POST["kategori"] == 'K_SHEET')){
					$this->db->set("p1_sheet", $_POST["panjang_plan"]);
				}else{
					$this->db->set("kupingan", $_POST["kupingan"]);
					$this->db->set("p1", $_POST["p1"]);
					$this->db->set("l1", $_POST["l1"]);
					$this->db->set("p2", $_POST["p2"]);
					$this->db->set("l2", $_POST["l2"]);
				}
				// UPDATE SCORE WO
				$this->db->set("flap1", $_POST["creasing_wo1"]);
				$this->db->set("creasing2", $_POST["creasing_wo2"]);
				$this->db->set("flap2", $_POST["creasing_wo3"]);
				$this->db->set("edit_time", date("Y:m:d H:i:s"));
				$this->db->set("edit_user", $this->session->userdata('username'));
				$this->db->where("id", $_POST["id_wo"]);
				$updateScoreWO = $this->db->update("trs_wo");

				$id_produk = $_POST['id_produk'];
				$item = $this->db->query("SELECT*FROM m_produk WHERE id_produk='$id_produk'")->row();
				$kualitas = explode("/", $_POST["kualitas_isi_plan"]);
				$BF = $kualitas[1] * 1.36;
				$CF = $kualitas[1] * 1.46;
				if($item->flute == "BCF"){
					$getNilaiFlute = ($kualitas[0] + ($kualitas[1] * 1.36) + $kualitas[2] + ($kualitas[3] * 1.46) + $kualitas[4]) / 1000;
				} else if($item->flute == "CF") {
					$getNilaiFlute = ($kualitas[0] + $CF + $kualitas[2]) / 1000;
				} else if($item->flute == "BF") {
					$getNilaiFlute = ($kualitas[0] + $BF + $kualitas[2]) / 1000;
				} else {
					$getNilaiFlute = 0;
				}
				if($item->kategori == "K_BOX"){
					if($item->flute == "BCF"){
						$ruk_p = 2 * ($item->l_panjang + $item->l_lebar) + 61;
						$ruk_l = $item->l_lebar + $item->l_tinggi + 23;
					} else if($item->flute == "CF") {
						$ruk_p = 2 * ($item->l_panjang + $item->l_lebar) + 43;
						$ruk_l = $item->l_lebar + $item->l_tinggi + 13;
					} else if($item->flute == "BF") {
						$ruk_p = 2 * ($item->l_panjang + $item->l_lebar) + 39;
						$ruk_l = $item->l_lebar + $item->l_tinggi + 9;
					} else {
						$ruk_p = 0;
						$ruk_l = 0;
					}
					$opsiRound = 4;
				}else{
					$ruk_p = $item->l_panjang;
					$ruk_l = $item->l_lebar;
					$opsiRound = 3;
				}
				$h_panjang = $ruk_p / 1000;
				$h_lebar = $ruk_l / 1000;
				$nilaiBeratBersih = round($getNilaiFlute * $h_panjang * $h_lebar, $opsiRound);

				$data = array(
					'tgl_plan' => $_POST["tgl_plan"],
					'tgl_kirim_plan' => $_POST["tgl_kirim_plan"],
					'shift_plan' => $_POST["shift_plan"],
					'machine_plan' => $_POST["machine_plan"],
					'no_wo' => $_POST["no_wo"],
					'no_so' => $_POST["no_so"],
					'panjang_plan' => $_POST["panjang_plan"],
					'lebar_plan' => $_POST["lebar_plan"],
					'out_plan' => $_POST["out_plan"],
					'lebar_roll_p' => $_POST["lebar_roll_p"],
					'material_plan' => $_POST["material_plan"],
					'kualitas_plan' => $_POST["kualitas_plan"],
					'kualitas_isi_plan' => $_POST["kualitas_isi_plan"],
					'trim_plan' => $_POST["trim_plan"],
					'c_off_p' => $_POST["c_off_p"],
					'rm_plan' => $_POST["rm_plan"],
					'tonase_plan' => $_POST["tonase_plan"],
					'bb_plan_p' => $nilaiBeratBersih,
					'next_plan' => $_POST["next_plan"],
					'edit_time' => date('Y-m-d H:i:s'),
					'edit_user' => $this->session->userdata('username'),
				);
				$this->db->where('id_plan', $_POST['opsi']);
				$this->db->where('id_so_detail', $_POST['id_so_detail']);
				$this->db->where('id_wo', $_POST['id_wo']);
				$this->db->where('id_produk', $_POST['id_produk']);
				$this->db->where('id_pelanggan', $_POST['id_pelanggan']);
				$updatePlanCor = $this->db->update('plan_cor', $data);
				$msg = 'BERHASIL EDIT!';
			}
		}
		return array(
			'updateScoreWO' => $updateScoreWO,
			'updatePlanCor' => $updatePlanCor,
			'msg' => $msg,
		);
	}

	function produksiRencanaPlan()
	{
		$this->db->set('good_cor_p', $_POST["good_cor_p"]);
		$this->db->set('bad_cor_p', $_POST["bad_cor_p"]);
		$this->db->set('total_cor_p', $_POST["total_cor_p"]);
		$this->db->set('ket_plan', strtoupper($_POST["ket_plan"]));
		$this->db->set('tgl_prod_p', $_POST["tgl_cor"]);
		$this->db->set('start_time_p', $_POST["start_cor"]);
		$this->db->set('end_time_p', $_POST["end_cor"]);
		$this->db->where('id_plan', $_POST["id_plan"]);

		if($_POST["total_cor_p"] != 0){
			$id_plan = $_POST["id_plan"];
			$cekPlan = $this->db->query("SELECT*FROM plan_cor WHERE id_plan='$id_plan'")->row();
			if($cekPlan->status_plan == 'Close'){
				$result = array(
					'data' => false,
					'msg' => 'PLAN SUDAH SELESAI!',
				);
			}else{
				$result = $this->db->update('plan_cor');
			}
		}else{
			$result = $this->db->update('plan_cor');
		}

		return $result;
	}

	function hapusPlan()
	{
		$id_plan = $_POST["id_plan"];
		$cekFlexo = $this->db->query("SELECT*FROM plan_flexo WHERE id_plan_cor='$id_plan'");
		if($cekFlexo->num_rows() > 0){
			$data = false;
			$msg = 'PLAN COR SUDAH ADA DI PLAN FLEXO!';
		}else{
			$this->db->where('id_plan', $_POST["id_plan"]);
			$data = $this->db->delete('plan_cor');
			$msg = 'SLAY!';
		}

		return array(
			'data' => $data,
			'msg' => $msg,
		);
	}

	function selesaiPlan()
	{
		$id_plan = $_POST["id_plan"];
		$data = $this->db->query("SELECT pl.*,i.kategori FROM plan_cor pl
		INNER JOIN m_produk i ON pl.id_produk=i.id_produk
		WHERE pl.id_plan='$id_plan'")->row();

		if($data->kategori == 'K_SHEET'){
			$dataGudang = [
				'gd_id_pelanggan' => $data->id_pelanggan,
				'gd_id_produk' => $data->id_produk,
				'gd_id_trs_wo' => $data->id_wo,
				'gd_id_plan_cor' => $data->id_plan,
				'gd_id_plan_flexo' => null,
				'gd_id_plan_finishing' => null,
				'gd_hasil_plan' => $data->good_cor_p,
				'gd_berat_box' => $data->bb_plan_p,
				'gd_good_qty' => 0,
				'gd_reject_qty' => 0,
				'gd_cek_spv' => 'Open',
				'gd_status' => 'Open',
				'add_time' => date("Y:m:d H:i:s"),
				'add_user' => $this->session->userdata('username'),
			];
			$insertGudang = $this->db->insert('m_gudang', $dataGudang);
		}else{
			$dataGudang = [];
			$insertGudang = true;
		}

		$this->db->set('status_plan', 'Close');
		$this->db->where('id_plan', $_POST["id_plan"]);
		$statusPlan = $this->db->update('plan_cor');

		return [
			'id_plan' => $id_plan,
			'data' => $data,
			'dataGudang' => $dataGudang,
			'insertGudang' => $insertGudang,
			'statusPlan' => $statusPlan,
		];
	}

	function selesaiPlanWO()
	{
		$id_wo = $_POST["id_wo"];
		$cekWo = $this->db->query("SELECT*FROM plan_cor WHERE id_wo='$id_wo'");
		$i = 0;
		foreach($cekWo->result() as $r){
			if($r->status_plan == 'Open'){
				$i++;
				$data = false;
				$msg = 'PLAN COR LEBIH DARI SATU MASIH OPEN! CEK KEMBALI!'; 
			}
		}

		if($i == 0){
			$this->db->set('status', 'Close');
			$this->db->where('id', $_POST["id_wo"]);
			$data = $this->db->update('trs_wo');
			$msg = 'OK!';
		}

		return [
			'i' => $i,
			'data' => $data,
			'msg' => $msg,
			'id_wo' => $id_wo,
		];
	}

	function riwayatPlan()
	{
		$id_wo = $_POST["id_wo"];
		$id_so = $_POST["id_so"];
		$id_pelanggan = $_POST["id_pelanggan"];
		$id_produk = $_POST["id_produk"];

		return $this->db->query("SELECT COUNT(dt.id_plan_cor) AS jmlDt,SUM(dt.durasi_mnt_dt) AS jmlDtDurasi,p.*,so.qty_so FROM plan_cor p
		INNER JOIN trs_so_detail so ON p.id_so_detail=so.id
		LEFT JOIN plan_cor_dt dt ON p.id_plan=dt.id_plan_cor
		WHERE p.id_so_detail='$id_so' AND p.id_wo='$id_wo' AND p.id_produk='$id_produk' AND p.id_pelanggan='$id_pelanggan'
		GROUP BY p.tgl_plan,p.id_plan");
	}

	function onChangeNourutPlan()
	{
		$no_urut = $_POST["no_urut"];
		$id_plan = $_POST["i"];

		$noPoPlan = $this->db->query("SELECT no_plan FROM plan_cor WHERE id_plan='$id_plan'")->row();

		$cekNoUrutPlan = $this->db->query("SELECT*FROM plan_cor WHERE no_urut_plan='$no_urut' AND no_plan='$noPoPlan->no_plan'");
		if($cekNoUrutPlan->num_rows() == 0){
			$this->db->set('no_urut_plan', $no_urut);
			$this->db->where('id_plan', $id_plan);
			$data = $this->db->update("plan_cor");
			$msg = 'BERHASIL EDIT NO URUT!';
		}else{
			$data = false;
			$msg = 'NO URUT SUDAH ADA!';
		}

		return array(
			'data' => $data,
			'msg' => $msg,
			'no_plan' => $noPoPlan,
			'urut_plan' => $cekNoUrutPlan->row(),
		);
	}

	function editListPlan()
	{
		$next = $_POST["next"];
		$id_plan = $_POST["id_plan"];
		$cekFlexo = $this->db->query("SELECT*FROM plan_flexo WHERE id_plan_cor='$id_plan'");
		if($_POST["opsi"] == 'edit'){
			if($cekFlexo->num_rows() > 1){
				$data = false; $wo = false;
				$msg = 'PLAN FLEXO LEBIH DARI SATU!';
			}else{
				$item = $this->db->query("SELECT*FROM plan_cor p INNER JOIN m_produk i ON p.id_produk=i.id_produk WHERE p.id_plan='$id_plan'")->row();
				$kualitas = explode("/", $_POST["kualitas_isi"]);
				$BF = $kualitas[1] * 1.36;
				$CF = $kualitas[1] * 1.46;
				if($item->flute == "BCF"){
					$getNilaiFlute = ($kualitas[0] + ($kualitas[1] * 1.36) + $kualitas[2] + ($kualitas[3] * 1.46) + $kualitas[4]) / 1000;
				} else if($item->flute == "CF") {
					$getNilaiFlute = ($kualitas[0] + $CF + $kualitas[2]) / 1000;
				} else if($item->flute == "BF") {
					$getNilaiFlute = ($kualitas[0] + $BF + $kualitas[2]) / 1000;
				} else {
					$getNilaiFlute = 0;
				}
				if($item->kategori == "K_BOX"){
					if($item->flute == "BCF"){
						$ruk_p = 2 * ($item->l_panjang + $item->l_lebar) + 61;
						$ruk_l = $item->l_lebar + $item->l_tinggi + 23;
					} else if($item->flute == "CF") {
						$ruk_p = 2 * ($item->l_panjang + $item->l_lebar) + 43;
						$ruk_l = $item->l_lebar + $item->l_tinggi + 13;
					} else if($item->flute == "BF") {
						$ruk_p = 2 * ($item->l_panjang + $item->l_lebar) + 39;
						$ruk_l = $item->l_lebar + $item->l_tinggi + 9;
					} else {
						$ruk_p = 0;
						$ruk_l = 0;
					}
					$opsiRound = 4;
				}else{
					$ruk_p = $item->l_panjang;
					$ruk_l = $item->l_lebar;
					$opsiRound = 3;
				}
				$h_panjang = $ruk_p / 1000;
				$h_lebar = $ruk_l / 1000;
				$nilaiBeratBersih = round($getNilaiFlute * $h_panjang * $h_lebar, $opsiRound);

				$this->db->set("material_plan", $_POST["material"]);
				$this->db->set("kualitas_plan", $_POST["kualitas"]);
				$this->db->set("kualitas_isi_plan", $_POST["kualitas_isi"]);
				$this->db->set("panjang_plan", $_POST["panjang_plan"]);
				$this->db->set("lebar_plan", $_POST["lebar_plan"]);
				$this->db->set("lebar_roll_p", $_POST["lebar_roll_p"]);
				$this->db->set("out_plan", $_POST["out_plan"]);
				$this->db->set("trim_plan", $_POST["trim_plan"]);
				$this->db->set("c_off_p", $_POST["c_off_p"]);
				$this->db->set("rm_plan", $_POST["rm_plan"]);
				$this->db->set("tonase_plan", $_POST["tonase_plan"]);
				$this->db->set("bb_plan_p", $nilaiBeratBersih);
				$this->db->set("tgl_kirim_plan", $_POST["tglkirim"]);
				$this->db->set("next_plan", $_POST["next"]);
				$this->db->where("id_plan", $_POST["id_plan"]);
				$data = $this->db->update("plan_cor");

				$this->db->set("flap1", $_POST["creasing_wo1"]);
				$this->db->set("creasing2", $_POST["creasing_wo2"]);
				$this->db->set("flap2", $_POST["creasing_wo3"]);
				$this->db->where("id", $_POST["id_wo"]);
				$wo = $this->db->update("trs_wo");
				$msg = 'BERHASIL EDIT';
			}
		}else{
			if($cekFlexo->num_rows() > 1){
				$data = false; $wo = false;
				$msg = 'PLAN FLEXO SUDAH LEBIH DARI SATU!';
			}else{
				$this->db->set("tgl_kirim_plan", $_POST["tglkirim"]);
				$this->db->set("next_plan", $_POST["next"]);
				$this->db->where("id_plan", $_POST["id_plan"]);
				$data = $this->db->update("plan_cor");
				$wo = true;
				$msg = 'BERHASIL EDIT';
			}
		}

		return array(
			'data' => $data,
			'wo' => $wo,
			'msg' => $msg
		);
	}

	function simpanDowntime()
	{
		$id_plan = $_POST["id_plan"];
		$id_flexo = $_POST["id_flexo"];
		$id_dt = $_POST["id_dt"];
		$durasi = $_POST["durasi"];
		$ket = $_POST["ket"];

		if($id_dt == "" || $durasi == "" || $durasi == 0 || $durasi < 0){
			$result = false;
			$msg = 'HARAP LENGKAPI DATA DOWNTIME!';
		}else{
			if($id_plan != ''){
				$cek = $this->db->query("SELECT*FROM plan_cor_dt WHERE id_plan_cor='$id_plan' AND id_m_downtime='$id_dt'");
			}else{
				$cek = $this->db->query("SELECT*FROM plan_flexo_dt WHERE id_plan_flexo='$id_flexo' AND id_m_downtime='$id_dt'");
			}
			if($cek->num_rows() > 0){
				$result = false;
				$msg = 'DATA DOWNTIME SUDAH ADA!';
			}else{
				$this->db->set('id_m_downtime', $id_dt);
				$this->db->set('durasi_mnt_dt', $durasi);
				$this->db->set('ket_plan_dt', strtoupper($ket));
				$this->db->set('add_time', date("Y:m:d H:i:s"));
				$this->db->set('add_user', $this->session->userdata('username'));
				if($id_plan != ''){
					$this->db->set('id_plan_cor', $id_plan);
					$result = $this->db->insert('plan_cor_dt');
				}else{
					$this->db->set('id_plan_flexo', $id_flexo);
					$result = $this->db->insert('plan_flexo_dt');
				}
				$msg = 'BERHASIL!';
			}
		}
		return array(
			'result' => $result,
			'msg' => $msg,
		);
	}

	function hapusDowntimePlan()
	{
		$this->db->where('id_plan_dt', $_POST["id_dt"]);
		if($_POST["id_plan"] != ''){
			$delete = $this->db->delete('plan_cor_dt');
		}else{
			$delete = $this->db->delete('plan_flexo_dt');
		}
		return $delete;
	}

	function changeEditDt()
	{
		$durasi = $_POST["durasi"];
		if($durasi == 0 || $durasi == ""){
			$result = false;
			$msg = 'DURASI TIDAK BOLEH KOSONG!';
		}else{
			$this->db->set('durasi_mnt_dt', $durasi);
			$this->db->set('ket_plan_dt', strtoupper($_POST["ket"]));
			$this->db->set('edit_time', date("Y-m-d H:i:s"));
			$this->db->set('edit_user', $this->session->userdata('username'));
			if($_POST["id_plan"] != ''){
				$this->db->where('id_plan_dt', $_POST["id_plan"]);
				$result = $this->db->update('plan_cor_dt');
			}else{
				$this->db->where('id_plan_dt', $_POST["id_flexo"]);
				$result = $this->db->update('plan_flexo_dt');
			}
			$msg = "BERHASIL EDIT!";
		}

		return array(
			'data' => $result,
			'msg' => $msg,
		);
	}

	function btnGantiTglPlan()
	{
		$tgl = $_POST["tgl"];
		$shift = $_POST["shift"];
		$mesin = $_POST["mesin"];
		$id_plan = $_POST["id_plan"];

		if($tgl == "" || $shift == "" || $mesin == ""){
			$result = false;
			$msg = 'CEK KEMBALI!';
		}else{
			$cekIDPlan = $this->db->query("SELECT id_so_detail,id_wo,id_produk,id_pelanggan FROM plan_cor WHERE id_plan='$id_plan'")->row();
			$cekIDProduk = $this->db->query("SELECT*FROM plan_cor
			WHERE id_so_detail='$cekIDPlan->id_so_detail' AND id_wo='$cekIDPlan->id_wo' AND id_produk='$cekIDPlan->id_produk' AND id_pelanggan='$cekIDPlan->id_pelanggan' 
			AND tgl_plan='$tgl' AND shift_plan='$shift' AND machine_plan='$mesin'");
			if($cekIDProduk->num_rows() > 0){
				$result = false;
				$msg = 'WO SUDAH ADA DI TANGGAL TERSEBUT!';
			}else{
				$cekIDPlanProduksi = $this->db->query("SELECT * FROM plan_cor WHERE id_plan='$id_plan' AND total_cor_p!='0'");
				if($cekIDPlanProduksi->num_rows() > 0){
					$result = false;
					$msg = 'PLAN SUDAH TERPRODUKSI!';
				}else{
					$cekFlexo = $this->db->query("SELECT*FROM plan_flexo WHERE id_plan_cor='$id_plan' ORDER BY tgl_flexo ASC LIMIT 1")->row();
					if($tgl >= $cekFlexo->tgl_flexo){
						$result = false;
						$msg = 'TGL PLAN COR LEBIH DARI TGL FLEXO!';
					}else{
						$cekPlan = $this->db->query("SELECT*FROM plan_cor
						WHERE tgl_plan='$tgl' AND shift_plan='$shift' AND machine_plan='$mesin' LIMIT 1");
						if($cekPlan->num_rows() > 0){
							$noplan = $cekPlan->row()->no_plan;
						}else{
							$plan_no = $this->m_fungsi->urut_transaksi('PLAN');
							$bln = $this->m_master->get_romawi(date('m'));
							$tahun = date('Y');
							$noplan = 'PLAN/'.$tahun.'/'.$bln.'/'.$plan_no;
						}
						$this->db->set('tgl_plan', $tgl);
						$this->db->set('shift_plan', $shift);
						$this->db->set('machine_plan', $mesin);
						$this->db->set('no_urut_plan', 0);
						$this->db->set('no_plan', $noplan);
						$this->db->set('edit_time', date("Y-m-d H:i:s"));
						$this->db->set('edit_user', $this->session->userdata('username'));
						$this->db->where('id_plan', $id_plan);
						$result = $this->db->update('plan_cor');
						$msg = 'BERHASIL EDIT!';
					}
				}
			}
		}
		return array(
			'data' => $result,
			'msg' => $msg,
		);
	}

	//

	function loadPlanCor()
	{ //
		$mesin = $_POST["mesin"];
		$opsi = $_POST["opsi"];
		if($opsi != ''){
			$query = $this->db->query("SELECT f.*,i.*,pc.*,c.nm_pelanggan,s.qty_so,s.kode_po FROM plan_flexo f
			INNER JOIN plan_cor pc ON f.id_plan_cor=pc.id_plan
			INNER JOIN trs_so_detail s ON pc.id_so_detail=s.id
			INNER JOIN m_produk i ON pc.id_produk=i.id_produk
			INNER JOIN m_pelanggan c ON pc.id_pelanggan=c.id_pelanggan
			WHERE id_flexo='$opsi'")->row();
		}else{
			$tgl = $_POST["urlTglF"];
			$shift = $_POST["urlShiftF"];
			$uMesin = $_POST["urlMesinF"];
			($tgl != '' || $shift != '' || $uMesin != '') ? $whereNotExists = "AND NOT EXISTS (SELECT*FROM plan_flexo f WHERE f.id_plan_cor=p.id_plan AND f.tgl_flexo='$tgl' AND f.shift_flexo='$shift' AND f.mesin_flexo='$uMesin')" : $whereNotExists = '' ;
			$query = $this->db->query("SELECT p.*,i.*,c.nm_pelanggan,s.qty_so,s.kode_po FROM plan_cor p
			INNER JOIN m_produk i ON p.id_produk=i.id_produk
			INNER JOIN m_pelanggan c ON p.id_pelanggan=c.id_pelanggan
			INNER JOIN trs_so_detail s ON p.id_so_detail=s.id
			WHERE p.status_flexo_plan='Open' AND next_plan='$mesin' $whereNotExists")->result();
		}

		return $query;
	}

	function editPlanFlexo()
	{
		$id_flexo = $_POST["id_flexo"];
		$cekFinishing = $this->db->query("SELECT*FROM plan_finishing WHERE id_plan_flexo='$id_flexo'");
		if($cekFinishing->num_rows() > 0){
			$data = false;
			$msg = 'PLAN FLEXO SUDAH ADA DI PLAN FINISHING!';
		}else{
			$this->db->set('next_flexo', $_POST["editNextFlexo"]);
			$this->db->set('edit_time', date("Y-m-d H:i:s"));
			$this->db->set('edit_user', $this->session->userdata('username'));
			$this->db->where('id_flexo', $_POST["id_flexo"]);
			$data = $this->db->update('plan_flexo');
			$msg = 'hapus';
		}

		return [
			'data' => $data,
			'msg' => $msg,
		];
	}

	function hapusPlanFlexo()
	{
		$id_flexo = $_POST["id_flexo"];
		$cekFinishing = $this->db->query("SELECT*FROM plan_finishing WHERE id_plan_flexo='$id_flexo'");
		if($cekFinishing->num_rows() > 0){
			$data = false;
			$msg = 'PLAN FLEXO SUDAH ADA DI PLAN FINISHING!';
		}else{
			$this->db->where('id_flexo', $_POST["id_flexo"]);
			$data = $this->db->delete('plan_flexo');
			$msg = 'hapus';
		}

		return [
			'data' => $data,
			'msg' => $msg,
		];
	}

	function simpanCartFlexo()
	{
		foreach($this->cart->contents() as $r){
			$data = array(
				'id_plan_cor' => $r["id"],
				'tgl_flexo' => $r["options"]["tgl"],
				'shift_flexo' => $r["options"]["shift"],
				'mesin_flexo' => $r["options"]["mesin"],
				'good_flexo_p' => 0,
				'bad_flexo_p' => 0,
				'bad_bahan_f_p' => 0,
				'total_prod_flexo' => 0,
				'next_flexo' => $r["options"]["next_flexo"],
				'add_user' => $this->session->userdata('username'),
			);

			$insertPlanFlexo = $this->db->insert('plan_flexo', $data);
		}

		return array(
			'insertPlanFlexo' => $insertPlanFlexo,
		);
	}

	function loadDataPlanFlexo()
	{
		$tgl = $_POST["uTgl"];
		$shift = $_POST["uShift"];
		$mesin = $_POST["uMesin"];

		return $this->db->query("SELECT * FROM plan_flexo
		WHERE tgl_flexo='$tgl' AND shift_flexo='$shift' AND mesin_flexo='$mesin'")->result();
	}

	function produksiPlanFlexo()
	{
		$this->db->set('good_flexo_p', $_POST["good_flexo"]);
		$this->db->set('bad_flexo_p', $_POST["bad_flexo"]);
		$this->db->set('bad_bahan_f_p', $_POST["bad_b_flexo"]);
		$this->db->set('total_prod_flexo', $_POST["total_flexo"]);
		$this->db->set('ket_flexo_p', strtoupper($_POST["ket_flexo"]));
		$this->db->set('tgl_prod_f', $_POST["tgl_flexo"]);
		$this->db->set('start_time_f', $_POST["start_flexo"]);
		$this->db->set('end_time_f', $_POST["end_flexo"]);
		$this->db->set('edit_time', date("Y-m-d H:i:s"));
		$this->db->set('edit_user', $this->session->userdata('username'));
		$this->db->where('id_flexo', $_POST["id_flexo"]);

		if($_POST["total_flexo"] != 0){
			$id_flexo = $_POST["id_flexo"];
			$cekPlan = $this->db->query("SELECT*FROM plan_flexo WHERE id_flexo='$id_flexo'")->row();
			if($_POST["good_flexo"] > $_POST["good_cor"]){
				$result = array(
					'data' => false,
					'msg' => 'PROD. FLEXO LEBIH BESAR DARI PROD. COR!',
				);
			}else if($cekPlan->status_flexo == 'Close'){
				$result = array(
					'data' => false,
					'msg' => 'PLAN FLEXO SUDAH SELESAI!',
				);
			}else{
				$data = $this->db->update('plan_flexo');
				$result = array(
					'data' => $data,
					'msg' => 'OK!',
				);
			}
		}else{
			$result = array(
				'data' => false,
				'msg' => 'HASIL PROD. FLEXO KOSONG!',
			);
		}

		return $result;
	}

	function btnGantiTglFlexo()
	{
		$tgl = $_POST["tgl"];
		$shift = $_POST["shift"];
		$mesin = $_POST["mesin"];
		$id_flexo = $_POST["id_flexo"];

		if($tgl == "" || $shift == "" || $mesin == ""){
			$result = false;
			$msg = 'CEK KEMBALI!';
		}else{
			$cekIDPlan = $this->db->query("SELECT id_plan_cor FROM plan_flexo WHERE id_flexo='$id_flexo'")->row();
			$cekIDProduk = $this->db->query("SELECT*FROM plan_flexo
			WHERE id_plan_cor='$cekIDPlan->id_plan_cor'
			AND tgl_flexo='$tgl' AND shift_flexo='$shift' AND mesin_flexo='$mesin'");
			if($cekIDProduk->num_rows() > 0){
				$result = false;
				$msg = 'PLAN FLEXO SUDAH ADA DI TANGGAL TERSEBUT!';
			}else{
				$cekIDPlanProduksi = $this->db->query("SELECT * FROM plan_flexo WHERE id_flexo='$id_flexo' AND total_prod_flexo!='0'");
				if($cekIDPlanProduksi->num_rows() > 0){
					$result = false;
					$msg = 'PLAN FLEXO SUDAH TERPRODUKSI!';
				}else{
					$cekPlanCor = $this->db->query("SELECT*FROM plan_cor WHERE id_plan='$cekIDPlan->id_plan_cor'")->row();
					$cekPlanFinishing = $this->db->query("SELECT*FROM plan_finishing WHERE id_plan_cor='$cekIDPlan->id_plan_cor' AND id_plan_flexo='$id_flexo'")->row();
					if($tgl < $cekPlanCor->tgl_plan){
						$result = false;
						$msg = 'TGL PLAN FLEXO TIDAK BOLEH KURANG DARI TANGGAL PLAN COR!';
					}else if($tgl > $cekPlanFinishing->tgl_fs){
						$result = false;
						$msg = 'TGL PLAN FLEXO TIDAK BOLEH LEBIH DARI TANGGAL PLAN FINISHING!';
					}else{
						$this->db->set('tgl_flexo', $tgl);
						$this->db->set('shift_flexo', $shift);
						$this->db->set('mesin_flexo', $mesin);
						$this->db->set('no_urut_flexo', 0);
						$this->db->set('edit_time', date("Y-m-d H:i:s"));
						$this->db->set('edit_user', $this->session->userdata('username'));
						$this->db->where('id_flexo', $id_flexo);
						$result = $this->db->update('plan_flexo');
						$msg = 'BERHASIL EDIT!';
					}
				}
			}
		}
		return array(
			'data' => $result,
			'msg' => $msg,
		);
	}

	function onChangeNourutFlexo()
	{
		$no_urut = $_POST["no_urut"];
		$id_plan = $_POST["i"];

		$noFlexo = $this->db->query("SELECT tgl_flexo,shift_flexo,mesin_flexo FROM plan_flexo WHERE id_flexo='$id_plan'")->row();

		$cekNoUrutFlexo = $this->db->query("SELECT*FROM plan_flexo WHERE no_urut_flexo='$no_urut' AND tgl_flexo='$noFlexo->tgl_flexo' AND shift_flexo='$noFlexo->shift_flexo' AND mesin_flexo='$noFlexo->mesin_flexo'");
		if($cekNoUrutFlexo->num_rows() == 0){
			$this->db->set('no_urut_flexo', $no_urut);
			$this->db->where('id_flexo', $id_plan);
			$data = $this->db->update("plan_flexo");
			$msg = 'BERHASIL EDIT NO URUT!';
		}else{
			$data = false;
			$msg = 'NO URUT SUDAH ADA!';
		}

		return array(
			'data' => $data,
			'msg' => $msg,
			'no_plan' => $noFlexo,
			'urut_plan' => $cekNoUrutFlexo->row(),
		);
	}

	function riwayatFlexo()
	{
		$id_plan = $_POST["id_plan"];

		return $this->db->query("SELECT COUNT(dt.id_plan_flexo) AS jmlDt,SUM(dt.durasi_mnt_dt) AS jmlDtDurasi,c.pcs_plan,f.* FROM plan_flexo f
		INNER JOIN plan_cor c ON f.id_plan_cor=c.id_plan
		LEFT JOIN plan_flexo_dt dt ON f.id_flexo=dt.id_plan_flexo
		WHERE f.id_plan_cor='$id_plan'
		GROUP BY f.tgl_flexo,f.id_plan_cor");
	}

	function addRencanaFlexo(){
		$id_flexo = $_POST["opsi"];
		if($id_flexo != 'add'){
			$cekFlexo = $this->db->query("SELECT*FROM plan_flexo WHERE id_flexo='$id_flexo' AND status_flexo='Close'");
			if($cekFlexo->num_rows() == 0){
				$dataFlexo = $this->db->query("SELECT fx.*,pl.* FROM plan_flexo fx
				INNER JOIN plan_cor pl ON fx.id_plan_cor=pl.id_plan
				WHERE fx.id_flexo='$id_flexo'")->row();

				if($dataFlexo->next_flexo == 'GUDANG'){
					$dataGudang = [
						'gd_id_pelanggan' => $dataFlexo->id_pelanggan,
						'gd_id_produk' => $dataFlexo->id_produk,
						'gd_id_trs_wo' => $dataFlexo->id_wo,
						'gd_id_plan_cor' => $dataFlexo->id_plan,
						'gd_id_plan_flexo' => $dataFlexo->id_flexo,
						'gd_id_plan_finishing' => null,
						'gd_hasil_plan' => $dataFlexo->good_flexo_p,
						'gd_berat_box' => $dataFlexo->bb_plan_p,
						'gd_good_qty' => 0,
						'gd_reject_qty' => 0,
						'gd_cek_spv' => 'Open',
						'gd_status' => 'Open',
						'add_time' => date("Y:m:d H:i:s"),
						'add_user' => $this->session->userdata('username'),
					];
					$insertGudang = $this->db->insert('m_gudang', $dataGudang);
				}else{
					$dataGudang = [];
					$insertGudang = true;
				}

				$this->db->set('status_flexo', 'Close');
				$this->db->where('id_flexo', $id_flexo);
				$data = $this->db->update('plan_flexo');
				$isi = 'PLAN FLEXO SELESAI!';
			}else{
				$dataGudang = [];
				$insertGudang = true;
				$data = false;
				$isi = 'PLAN FLEXO SUDAH SELESAI!';
			}
		}
		return array(
			'data' => $data,
			'isi' => $isi,
			'dataGudang' => $dataGudang,
			'insertGudang' => $insertGudang,
		);
	}

	function clickDonePlanCorFlexo()
	{
		$id_plan_cor = $_POST["id_plan_cor"];
		$cekPlanCor = $this->db->query("SELECT w.status AS status_wo,p.* FROM plan_cor p INNER JOIN trs_wo w ON p.id_wo=w.id WHERE p.id_plan='$id_plan_cor'")->row();
		if($cekPlanCor->status_wo == 'Open'){
			$i = 1;
			$data = false;
			$msg = 'WO BELUM CLOSE!';
		}else{
			$cekPlanFlexo = $this->db->query("SELECT*FROM plan_flexo WHERE id_plan_cor='$id_plan_cor'");
			$i = 0;
			foreach($cekPlanFlexo->result() as $r){
				if($r->status_flexo == 'Open'){
					$i++;
					$data = false;
					$msg = 'PLAN FLEXO LEBIH DARI SATU MASIH OPEN! CEK KEMBALI!';
				}
			}
			if($i == 0){
				$this->db->set('status_flexo_plan', 'Close');
				$this->db->where('id_plan', $_POST["id_plan_cor"]);
				$data = $this->db->update('plan_cor');
				$msg = 'OK!';
			}
		}

		return [
			'i' => $i,
			'data' => $data,
			'msg' => $msg,
		];
	}

	//

	function loadDataPlanFinishing()
	{ //
		$tgl = $_POST["uTgl"];
		$shift = $_POST["uShift"];
		$joint = $_POST["uJoint"];

		($joint == 'STITCHING') ? $wNextFlexo = "AND joint_fs LIKE '%$joint%'": $wNextFlexo = "AND joint_fs='$joint'";
		return $this->db->query("SELECT * FROM plan_finishing
		WHERE tgl_fs='$tgl' AND shift_fs='$shift' $wNextFlexo")->result();
	}

	function loadPlanFlexo()
	{
		$joint = $_POST["joint"];
		$opsi = $_POST["opsi"];
		if($opsi != ''){
			$query = $this->db->query("SELECT fs.*,i.*,pc.*,f.*,so.qty_so,so.kode_po,c.nm_pelanggan FROM plan_finishing fs
			INNER JOIN plan_flexo f ON fs.id_plan_flexo=f.id_flexo
			INNER JOIN plan_cor pc ON fs.id_plan_cor=pc.id_plan
			INNER JOIN trs_so_detail so ON pc.id_so_detail=so.id
			INNER JOIN m_produk i ON pc.id_produk=i.id_produk
			INNER JOIN m_pelanggan c ON pc.id_pelanggan=c.id_pelanggan
			WHERE fs.id_fs='$opsi'")->row();
		}else{
			$tgl = $_POST["urlTglFs"];
			$shift = $_POST["urlShiftFs"];
			$uJoint = $_POST["urlJointFs"];
			($joint == 'STITCHING') ? $wNextFlexo = "AND f.next_flexo LIKE '%$joint%'": $wNextFlexo = "AND f.next_flexo='$joint'";
			($tgl != '' || $shift != '' || $uJoint != '') ? $whereNotExists = "AND NOT EXISTS (SELECT*FROM plan_finishing fs WHERE fs.id_plan_cor=c.id_plan AND fs.id_plan_flexo=f.id_flexo AND fs.tgl_fs='$tgl' AND fs.shift_fs='$shift' AND fs.joint_fs='$uJoint')" : $whereNotExists = '' ;
			$query = $this->db->query("SELECT f.*,c.*,i.*,p.nm_pelanggan,s.qty_so,s.kode_po FROM plan_flexo f
			INNER JOIN plan_cor c ON f.id_plan_cor=c.id_plan
			INNER JOIN m_produk i ON c.id_produk=i.id_produk
			INNER JOIN m_pelanggan p ON c.id_pelanggan=p.id_pelanggan
			INNER JOIN trs_so_detail s ON c.id_so_detail=s.id
			WHERE f.status_stt_f='Open' $wNextFlexo $whereNotExists")->result();
		}

		return $query;
	}

	function addRencanaFinishing(){
		$id_fs = $_POST["opsi"];
		if($id_fs != 'add'){
			$cekFlexo = $this->db->query("SELECT*FROM plan_finishing WHERE id_fs='$id_fs' AND status_fs='Close'");
			if($cekFlexo->num_rows() == 0){
				$dataFinishing = $this->db->query("SELECT fs.*,fx.*,pl.* FROM plan_finishing fs
				INNER JOIN plan_flexo fx ON fs.id_plan_cor=fx.id_plan_cor AND fs.id_plan_flexo=fx.id_flexo
				INNER JOIN plan_cor pl ON fx.id_plan_cor=pl.id_plan
				WHERE fs.id_fs='$id_fs'")->row();

				$dataGudang = [
					'gd_id_pelanggan' => $dataFinishing->id_pelanggan,
					'gd_id_produk' => $dataFinishing->id_produk,
					'gd_id_trs_wo' => $dataFinishing->id_wo,
					'gd_id_plan_cor' => $dataFinishing->id_plan,
					'gd_id_plan_flexo' => $dataFinishing->id_flexo,
					'gd_id_plan_finishing' => $dataFinishing->id_fs,
					'gd_hasil_plan' => $dataFinishing->good_fs_p,
					'gd_berat_box' => $dataFinishing->bb_plan_p,
					'gd_good_qty' => 0,
					'gd_reject_qty' => 0,
					'gd_cek_spv' => 'Open',
					'gd_status' => 'Open',
					'add_time' => date("Y:m:d H:i:s"),
					'add_user' => $this->session->userdata('username'),
				];
				$insertGudang = $this->db->insert('m_gudang', $dataGudang);

				$this->db->set('status_fs', 'Close');
				$this->db->where('id_fs', $id_fs);
				$data = $this->db->update('plan_finishing');
				$isi = 'PLAN FINISHING SELESAI!';
			}else{
				$insertGudang = true;
				$data = false;
				$isi = 'PLAN FINISHING SUDAH SELESAI!';
			}
		}
		return array(
			'data' => $data,
			'isi' => $isi,
			'insertGudang' => $insertGudang,
		);
	}

	function hapusPlanFinishing()
	{
		// $id_flexo = $_POST["id_flexo"];
		// $cekFinishing = $this->db->query("SELECT*FROM plan_finishing WHERE id_plan_flexo='$id_flexo'");
		// if($cekFinishing->num_rows() > 0){
		// 	$data = false;
		// 	$msg = 'PLAN FLEXO SUDAH ADA DI PLAN FINISHING!';
		// }else{
			$this->db->where('id_fs', $_POST["id_fs"]);
			$data = $this->db->delete('plan_finishing');
			$msg = 'hapus';
		// }

		return [
			'data' => $data,
			'msg' => $msg,
		];
	}

	function simpanCartFinishing()
	{
		foreach($this->cart->contents() as $r){
			$data = array(
				'id_plan_cor' => $r["options"]["id_plan_cor"],
				'id_plan_flexo' => $r["id"],
				'tgl_fs' => $r["options"]["tgl"],
				'shift_fs' => $r["options"]["shift"],
				'joint_fs' => $r["options"]["joint"],
				'good_fs_p' => 0,
				'bad_fs_p' => 0,
				'bad_bahan_fs_p' => 0,
				'total_prod_fs' => 0,
				'add_user' => $this->session->userdata('username'),
			);
			$insertPlanFinishing = $this->db->insert('plan_finishing', $data);
		}

		return array(
			'insertPlanFinishing' => $insertPlanFinishing,
		);
	}

	function produksiPlanFinishing()
	{
		$this->db->set('good_fs_p', $_POST["good_fs"]);
		$this->db->set('bad_fs_p', $_POST["bad_fs"]);
		$this->db->set('bad_bahan_fs_p', $_POST["bad_b_fs"]);
		$this->db->set('total_prod_fs', $_POST["total_fs"]);
		$this->db->set('ket_fs_p', strtoupper($_POST["ket_fs"]));
		$this->db->set('tgl_pord_fs', $_POST["tgl_fs"]);
		$this->db->set('start_time_fs', $_POST["start_fs"]);
		$this->db->set('end_time_fs', $_POST["end_fs"]);
		$this->db->set('edit_time', date("Y-m-d H:i:s"));
		$this->db->set('edit_user', $this->session->userdata('username'));
		$this->db->where('id_fs', $_POST["id_fs"]);

		if($_POST["total_fs"] != 0){
			$id_fs = $_POST["id_fs"];
			$cekPlan = $this->db->query("SELECT*FROM plan_finishing WHERE id_fs='$id_fs'")->row();
			if($_POST["good_fs"] > $_POST["good_flexo"]){
				$result = array(
					'data' => false,
					'msg' => 'PROD. FINISHING LEBIH BESAR DARI PROD. FLEXO!',
				);
			}else if($cekPlan->status_fs == 'Close'){
				$result = array(
					'data' => false,
					'msg' => 'PLAN FINISHING SUDAH SELESAI!',
				);
			}else{
				$data = $this->db->update('plan_finishing');
				$result = array(
					'data' => $data,
					'msg' => 'OK!',
				);
			}
		}else{
			$result = array(
				'data' => false,
				'msg' => 'HASIL PROD. FINISHING KOSONG!',
			);
		}
		return $result;
	}

	function btnGantiTglFinishing()
	{
		$tgl = $_POST["tgl"];
		$shift = $_POST["shift"];
		$joint = $_POST["joint"];
		$id_fs = $_POST["id_fs"];

		if($tgl == "" || $shift == "" || $joint == ""){
			$result = false;
			$msg = 'CEK KEMBALI!';
		}else{
			$cekIDPlan = $this->db->query("SELECT id_plan_cor,id_plan_flexo FROM plan_finishing WHERE id_fs='$id_fs'")->row();
			($joint == 'STITCHING') ? $whereJoint = "AND joint_fs LIKE '%$joint%'": $whereJoint = "AND joint_fs='$joint'";
			$cekIDProduk = $this->db->query("SELECT*FROM plan_finishing
			WHERE id_plan_cor='$cekIDPlan->id_plan_cor' AND id_plan_flexo='$cekIDPlan->id_plan_flexo' AND tgl_fs='$tgl' AND shift_fs='$shift' $whereJoint");
			if($cekIDProduk->num_rows() > 0){
				$result = false;
				$msg = 'PLAN FINISHING SUDAH ADA DI TANGGAL TERSEBUT!';
			}else{
				$cekIDPlanProduksi = $this->db->query("SELECT * FROM plan_finishing WHERE id_fs='$id_fs' AND total_prod_fs!='0'");
				if($cekIDPlanProduksi->num_rows() > 0){
					$result = false;
					$msg = 'PLAN FINISHING SUDAH TERPRODUKSI!';
				}else{
					$cekPlanCor = $this->db->query("SELECT*FROM plan_flexo WHERE id_plan_cor='$cekIDPlan->id_plan_cor' AND id_flexo='$cekIDPlan->id_plan_flexo'")->row();
					if($tgl < $cekPlanCor->tgl_flexo){
						$result = false;
						$msg = 'TGL PLAN FINISHING TIDAK BOLEH KURANG DARI TANGGAL PLAN FLEXO!';
					}else{
						$this->db->set('tgl_fs', $tgl);
						$this->db->set('shift_fs', $shift);
						// $this->db->set('joint_fs', $joint);
						$this->db->set('no_urut_fs', 0);
						$this->db->set('edit_time', date("Y-m-d H:i:s"));
						$this->db->set('edit_user', $this->session->userdata('username'));
						$this->db->where('id_fs', $id_fs);
						$result = $this->db->update('plan_finishing');
						$msg = 'BERHASIL EDIT!';
					}
				}
			}
		}
		return array(
			'data' => $result,
			'msg' => $msg,
		);
	}

	function onChangeNourutFinishing()
	{
		$no_urut = $_POST["no_urut"];
		$id_finishing = $_POST["i"];

		$noFinishing = $this->db->query("SELECT tgl_fs,shift_fs,joint_fs FROM plan_finishing WHERE id_fs='$id_finishing'")->row();

		$cekNoUrutFinishing = $this->db->query("SELECT*FROM plan_finishing WHERE no_urut_fs='$no_urut' AND tgl_fs='$noFinishing->tgl_fs' AND shift_fs='$noFinishing->shift_fs' AND joint_fs='$noFinishing->joint_fs'");
		if($cekNoUrutFinishing->num_rows() == 0){
			$this->db->set('no_urut_fs', $no_urut);
			$this->db->where('id_fs', $id_finishing);
			$data = $this->db->update("plan_finishing");
			$msg = 'BERHASIL EDIT NO URUT!';
		}else{
			$data = false;
			$msg = 'NO URUT SUDAH ADA!';
		}

		return array(
			'data' => $data,
			'msg' => $msg,
			'no_plan' => $noFinishing,
			'urut_plan' => $cekNoUrutFinishing->row(),
		);
	}

	function clickDonePlanCorFlexoFs()
	{
		$id_plan_flexo = $_POST["id_plan_flexo"];
		$cekPlanCor = $this->db->query("SELECT w.status AS status_wo FROM plan_cor p
		INNER JOIN trs_wo w ON p.id_wo=w.id
		INNER JOIN plan_flexo f ON p.id_plan=f.id_plan_cor WHERE f.id_flexo='$id_plan_flexo'")->row();
		if($cekPlanCor->status_wo == 'Open'){
			$i = 1;
			$data = false;
			$msg = 'WO BELUM CLOSE!';
		}else{
			$cekPlanFinishing = $this->db->query("SELECT*FROM plan_finishing WHERE id_plan_flexo='$id_plan_flexo'");
			$i = 0;
			foreach($cekPlanFinishing->result() as $r){
				if($r->status_fs == 'Open'){
					$i++;
					$data = false;
					$msg = 'PLAN FINISHING LEBIH DARI SATU MASIH OPEN! CEK KEMBALI!';
				}
			}
			if($i == 0){
				$this->db->set('status_stt_f', 'Close');
				$this->db->where('id_flexo', $_POST["id_plan_flexo"]);
				$data = $this->db->update('plan_flexo');
				$msg = 'OK!';
			}
		}

		return [
			'i' => $i,
			'data' => $data,
			'msg' => $msg,
		];
	}

	function riwayatFinishing()
	{
		$id_plan = $_POST["id_plan"];
		$id_flexo = $_POST["id_flexo"];

		return $this->db->query("SELECT c.pcs_plan,fs.* FROM plan_finishing fs
		INNER JOIN plan_flexo f ON fs.id_plan_cor=f.id_plan_cor AND fs.id_plan_flexo=f.id_flexo
		INNER JOIN plan_cor c ON f.id_plan_cor=c.id_plan
		-- LEFT JOIN plan_flexo_dt dt ON f.id_flexo=dt.id_plan_flexo
		WHERE fs.id_plan_cor='$id_plan' AND fs.id_plan_flexo='$id_flexo'");
	}
}
