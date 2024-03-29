<?php
defined('BASEPATH') or exit('No direct script access allowed');
 
class Laporan extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('status') != "login") {
			redirect(base_url("Login"));
		}
		$this->load->model('m_master');
		$this->load->model('m_laporan');
		$this->load->model('m_fungsi');
	}

	function Rekap_omset()
	{

		$data = array(
			'judul' => "REKAP OMSET"
		);

		$this->load->view('header', $data);
		$this->load->view('Laporan/v_rekap_omset', $data);
		$this->load->view('footer');
	}

	function load_rekap_omset()
	{
		$html   = '';

		$th_hub = $this->input->post('th_hub');
		if($th_hub){
			$tahun  = $th_hub;
		}else{
			$tahun  = date('Y');
		}
		
		$query  = $this->db->query("SELECT b.id_hub,e.nm_hub,b.id_pelanggan,d.nm_pelanggan,sum(c.qty*price_inc)total_hub, YEAR(b.tgl_po)th
		from trs_po b 
		JOIN trs_po_detail c ON b.no_po=c.no_po 
		JOIN m_pelanggan d ON d.id_pelanggan=b.id_pelanggan
		JOIN m_hub e ON e.id_hub=b.id_hub
		where YEAR(b.tgl_po) in ('$tahun') 
		group by b.id_pelanggan,d.nm_pelanggan,b.id_hub,e.nm_hub,YEAR(b.tgl_po)
		ORDER BY id_hub,id_pelanggan
		")->result();

		$html .='<div style="padding-bottom:20px;font-weight:bold">';
		$html .='<table class="table table-bordered table-striped">
		<thead class="color-tabel">
			<tr>
				<th style="text-align:center">NO</th>
				<th style="text-align:center">Nama HUB</th>
				<th style="text-align:center">Nama Customer</th>
				<th style="text-align:center">OMSET</th>
				<th style="text-align:center">SISA PLAFON</th>
				<th style="text-align:center">TAHUN</th>
			</tr>
		</thead>';
		$i            = 0;
		$total        = 0;
		$total_rata   = 0;
		$sisa_hub     = 0;
		if($query)
		{
			foreach($query as $r){
				$i++;
				$html .= '</tr>
					<td style="text-align:center">'.$i.'</td>
					<td style="text-align:left">'.$r->nm_hub.'</td>
					<td style="text-align:left">'.$r->nm_pelanggan.'</td>
					<td style="text-align:right">'.number_format($r->total_hub, 0, ",", ".").'</td>
					<td style="text-align:right">'.number_format(4800000000-$r->total_hub, 0, ",", ".").'</td>
					<td style="text-align:right">'.$tahun.'</td>
				</tr>';
				$total    += $r->total_hub;
				$sisa_hub += 4800000000-$r->total_hub;
			}
			
			$html .='<tr>
					<th style="text-align:center" colspan="3" >Total</th>
					<th style="text-align:right">'.number_format($total, 0, ",", ".").'</th>
					<th style="text-align:right">'.number_format($sisa_hub, 0, ",", ".").'</th>
					<th style="text-align:right"></th>
				</tr>
				';
			
			$html .='</table>
			</div>';
		}else{
			$html .='<tr>
				<th style="text-align:center" colspan="4" >Data Kosong</th>
			</tr>
			';
		
		$html .='</table>
		</div>';
		}

		echo $html;
		
	}

	function load_data()
	{
		$jenis = $this->uri->segment(3);
		$data = array();

		if ($jenis == "rekap_omset") {
			
			$th_hub = $this->input->post('th_hub');
			if($th_hub){
				$tahun  = $th_hub;
			}else{
				$tahun  = date('Y');
			}
			
			$query = $this->m_master->query("SELECT b.id_hub,e.nm_hub,b.id_pelanggan,d.nm_pelanggan,sum(c.qty*price_inc)total_hub, YEAR(b.tgl_po)th
			from trs_po b 
			JOIN trs_po_detail c ON b.no_po=c.no_po 
			JOIN m_pelanggan d ON d.id_pelanggan=b.id_pelanggan
			JOIN m_hub e ON e.id_hub=b.id_hub
			where YEAR(b.tgl_po) in ('$tahun') 
			group by b.id_pelanggan,d.nm_pelanggan,b.id_hub,e.nm_hub,YEAR(b.tgl_po)
			ORDER BY id_hub,id_pelanggan")->result();
			$i = 1;
			foreach ($query as $r) {
				$row          = array();
				$row[]        = '<div class="text-center">'.$i.'</div>';
				$row[]        = $r->nm_hub;
				$row[]        = $r->nm_pelanggan; 
				$row[]        = '<div class="text-right"><b>'. number_format($r->total_hub, 0, ",", "."). '</b></div>';
				$row[]        = '<div class="text-right"><b>'. number_format(4800000000-$r->total_hub, 0, ",", "."). '</b></div>';
				$row[]        = '<div class="text-center">'.$tahun.'</div>';

				$id_hub       = $r->id_hub;
				$cekpo    = $this->db->query("SELECT * FROM trs_po WHERE id_hub='$id_hub'")->num_rows();

				$data[] = $row;
				$i++;
			}
		}

		$output = array(
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}

	function Laporan_Stok()
	{

		$query = $this->m_master->query("SELECT * FROM m_produk ORDER BY id_produk ");

		$html = '';


		if ($query->num_rows() > 0) {

			$html .= '<table width="100%" border="0" cellspacing="0" style="font-size:14px;font-family: ;">
                        <tr style="font-weight: bold;">
                            <td colspan="4" align="center">
                              <h1> Laporan Stok</h1>
                              
                            </td>
                        </tr>
                 </table><br>';

			$html .= '<table width="100%" border="1" cellspacing="0" style="font-size:14px;font-family: ;">
                        <tr>
                            <th align="center">ID Produk</th>
                            <th align="center">Nama Produk</th>
                            <th align="center">Satuan</th>
                            <th align="center">Stok</th>
                        </tr>';
			$tot_stok = 0;
			foreach ($query->result() as $r) {
				$html .= '
                            <tr>
                                <td align="center">' . $r->id_produk . '</td>
                                <td align="center">' . $r->nm_produk . '</td>
                                <td align="center">' . $r->satuan . '</td>
                                <td align="center">' . number_format($r->stok, 0, ",", ".") . '</td>
                            </tr>';

				$tot_stok += $r->stok;
			}
			$html .= '
                            <tr style="background-color: #959a9a">
                                <td align="right" colspan="3">Total</td>
                                <td align="center">' . number_format($tot_stok, 0, ",", ".") . '</td>
                            </tr>';
			$html .= '
                 </table>';
		} else {
			$html .= '<h1> Data Kosong </h1>';
		}

		$judul = "Laporan Stok";

		if (/*$ctk*/'1' == '0') {
			echo $html;
		} else {
			header("Content-type: application/octet-stream");
			header("Content-Disposition: attachment; filename=$judul.xls");
			header("Pragma: no-cache");
			header("Expires: 0");
			$data['prev'] = $html;
			$this->load->view('view_excel', $data);
		}
	}

	function lap_cost($periode, $id_kategori, $ctk)
	{
		$data_tgl = $this->db->query("SELECT DATE_FORMAT(tanggal,'%d') day,tanggal FROM `tr_pemakaian` WHERE id_kategori='$id_kategori' AND DATE_FORMAT(tanggal,'%Y-%m') = '$periode' GROUP BY tanggal");

		$nm_kategori = $this->db->query("SELECT nm_kategori from m_kategori where id = '$id_kategori' ")->row("nm_kategori");
		$html = '';


		if ($data_tgl->num_rows() > 0) {
			$colspan = $data_tgl->num_rows() + 2;
			$tot_cost = 0;

			$html .= '<table width="100%" border="0" cellspacing="0" style="font-size:14px;font-family: ;">
                        <tr style="font-weight: bold;">
                            <td colspan="' . $colspan . '" align="center"><h1> COST ' . $nm_kategori . '</h1></td>
                        </tr>
                        <tr style="font-weight: bold;">
                            <td style="color:blue" colspan="2"><h2>' . $this->m_fungsi->periode_indonesia($periode . "-01") . '</h2>
                            <td colspan="' . $data_tgl->num_rows() . '" align="center"></td>
                        </tr>
                 </table>';

			$html .= '<table width="100%" border="1" cellspacing="0" style="font-size:14px;font-family: ;">
                        <tr style="font-weight: bold;">
                            <td>Tanggal</td>
                            <td>Harga Satuan</td>';
			foreach ($data_tgl->result() as $r) {
				$html .= '<td>' . $r->day . '</td>';
			}
			$html .= '
                             <td>Total Cost</td>
                        </tr>
                        <tr style="background-color:yellow;font-weight: bold;">
                            <td>Hasil Rewinder</td>
                            <td></td>';
			$tot_rewinder = 0;
			foreach ($data_tgl->result() as $r) {
				$rewinder =  $this->db->query("SELECT nominal FROM `m_rewinder` WHERE  tanggal = '" . $r->tanggal . "' ")->row();
				$html .= '<td align="right">' . number_format($rewinder->nominal, 2, ",", ".") . '</td>';
				$tot_rewinder += $rewinder->nominal;
			}
			$html .= '
                            <td align="right">' . number_format($tot_rewinder, 2, ",", ".") . '</td>
                        </tr>';

			$sub_kategori = $this->db->query("SELECT * FROM `m_sub_kategori` WHERE id_kategori='$id_kategori' and status='1' ORDER BY nm_sub_kategori ")->result();

			$tot_pemakaian = 0;
			foreach ($sub_kategori as $sub) {
				$html .= '
                             <tr style="font-weight: bold;">
                                <td>' . $sub->nm_sub_kategori . '</td>
                                <td></td>';
				foreach ($data_tgl->result() as $r) {

					$html .= '<td></td>';
				}
				$html .= '
                                <td></td>
                             </tr>';


				$produk = $this->db->query("SELECT * FROM `m_produk` WHERE id_kategori='$id_kategori' AND id_sub_kategori='" . $sub->id . "' and status='1' ORDER BY nm_produk ")->result();

				foreach ($produk as $pro) {
					$harga = $this->db->query("SELECT ifnull(harga,0) harga FROM `m_harga` WHERE id_produk='" . $pro->id . "' AND DATE_FORMAT(tanggal,'%Y-%m') = '$periode' ORDER BY id DESC limit 1");

					$html .= '
                                     <tr>
                                        <td style="padding-left:10px"> * ' . $pro->nm_produk . '</td>
                                        <td>' . ($harga->num_rows() > 0 ? number_format($harga->row()->harga, 2, ",", ".") : 0) . '</td>';

					$tot_cost_samping = 0;
					foreach ($data_tgl->result() as $r) {
						$pemakaian = $this->db->query("SELECT ifnull(nominal,0) nominal,ifnull(harga,0) harga FROM `tr_pemakaian` WHERE id_produk='" . $pro->id . "' AND tanggal = '" . $r->tanggal . "' limit 1");



						if ($pro->id == '55') {
							$jum_pemakaian = 0;
						} else if ($pro->id == '56') {
							$pemakaian = $this->db->query("SELECT sum(ifnull(nominal,0)) nominal,ifnull(harga,0) harga FROM `tr_pemakaian` WHERE id_produk in (53) AND tanggal = '" . $r->tanggal . "' limit 1");


							$pemakaian1 = $this->db->query("SELECT sum(ifnull(nominal,0)) nominal,ifnull(harga,0) harga FROM `tr_pemakaian` WHERE id_produk in (54) AND tanggal = '" . $r->tanggal . "' limit 1");


							$jum_pemakaian = ($pemakaian->num_rows() > 0 ? $pemakaian->row()->harga : 0) * ($pemakaian->num_rows() > 0 ? $pemakaian->row()->nominal : 0);

							$jum_pemakaian1 = ($pemakaian1->num_rows() > 0 ? $pemakaian1->row()->harga : 0) * ($pemakaian1->num_rows() > 0 ? $pemakaian1->row()->nominal : 0);

							$jum_pemakaian = ($jum_pemakaian + $jum_pemakaian1) * 0.03;
						} else {

							$jum_pemakaian = ($pemakaian->num_rows() > 0 ? $pemakaian->row()->harga : 0) * ($pemakaian->num_rows() > 0 ? $pemakaian->row()->nominal : 0);
						}

						$html .= '<td align="right">' . number_format(round($jum_pemakaian), 2, ",", ".") . '</td>';

						$tot_cost_samping += $jum_pemakaian;
					}


					$html .= '
                                        <td align="right">' . number_format($tot_cost_samping, 2, ",", ".") . '</td>
                                     </tr>';
				}

				if ($id_kategori == '3') {

					$html .= '
                                 <tr style="font-weight: bold;">
                                    <td>Total </td>
                                    <td></td>';
					foreach ($data_tgl->result() as $r) {
						$pemakaian = $this->db->query("SELECT SUM(nominal) nominal FROM ( SELECT  ((nominal) * (harga)) nominal FROM tr_pemakaian a WHERE id_sub_kategori = '" . $sub->id . "' AND tanggal ='" . $r->tanggal . "' and id_produk <> 55)z")->row("nominal");

						$pemakaian1 = $this->db->query("SELECT SUM(nominal) nominal FROM ( SELECT  ((nominal) * (harga))nominal FROM tr_pemakaian a WHERE id_sub_kategori = '" . $sub->id . "' AND tanggal ='" . $r->tanggal . "' and id_produk in (53,54) )z")->row("nominal");

						if ($sub->id == '17') {
							$pemakaian = $pemakaian + ($pemakaian1 * 0.03);
						}
						$html .= '<td align="right">' . number_format(round($pemakaian), 2, ",", ".") . '</td>';
					}
					$html .= '
                                    <td></td>
                                 </tr>
                                 <tr style="background-color:#3cd7ea;font-weight: bold;">
                                    <td>BIAYA ' . $sub->nm_sub_kategori . ' / KG PAPER</td>
                                    <td></td>';
					foreach ($data_tgl->result() as $r) {
						$pemakaian = $this->db->query("SELECT SUM(nominal) nominal FROM ( SELECT  ((nominal) * (harga)) nominal FROM tr_pemakaian a WHERE id_sub_kategori = '" . $sub->id . "' AND tanggal ='" . $r->tanggal . "' and id_produk <> '55' )z")->row("nominal");

						$rewinder = $this->db->query("SELECT * FROM m_rewinder WHERE  tanggal ='" . $r->tanggal . "'")->row("nominal");

						if ($sub->id == '17') {
							# code...
							$biaya =  ($pemakaian / $rewinder) + (($pemakaian * 0.03) / $rewinder);
						} else {
							$biaya =  ($pemakaian / $rewinder);
						}

						$html .= '<td align="right">' . number_format($biaya, 2, ",", ".") . '</td>';
					}
					$html .= '
                                    <td></td>
                                 </tr>';
				}
			}
			if ($id_kategori != '3') {
				$html .= '
                         <tr style="font-weight: bold;">
                            <td>Total ' . $nm_kategori . '</td>
                            <td></td>';
				$tot_cost = 0;
				foreach ($data_tgl->result() as $r) {

					$tot_pemakaian = $this->db->query("SELECT  SUM(pemakaian) pemakaian FROM (
                                      SELECT (nominal * harga) pemakaian FROM tr_pemakaian a WHERE tanggal ='" . $r->tanggal . "' AND id_kategori = '$id_kategori' )z
                                        ")->row("pemakaian");



					$html .= '<td align="right">' . number_format($tot_pemakaian, 2, ",", ".") . '</td>';
					$tot_cost += $tot_pemakaian;
				}

				//total cost samping
				$tot_cost_samping_bawah = $this->db->query("SELECT sum(total) total FROM ( SELECT ((nominal) * (harga)) total  FROM `tr_pemakaian` 
                                 WHERE DATE_FORMAT(tanggal,'%Y-%m') = '$periode' AND id_kategori = '$id_kategori' AND id_produk NOT IN (28,29,30,31,32,33) )z")->row("total");
				$html .= '
                            <td align="right">' . number_format($tot_cost_samping_bawah, 2, ",", ".") . '</td>
                        </tr>
                         <tr style="font-weight: bold;background-color:#3cd7ea">
                            <td>BIAYA ' . $nm_kategori . ' /KG PAPER </td>
                            <td></td>';
				foreach ($data_tgl->result() as $r) {
					$rewinder =  $this->db->query("SELECT nominal FROM `m_rewinder` WHERE  tanggal = '" . $r->tanggal . "' ")->row();
					$tot_bahan_baku =  $this->db->query("SELECT  SUM(pemakaian) nominal FROM (
                                      SELECT (nominal * harga) pemakaian FROM tr_pemakaian a WHERE tanggal ='" . $r->tanggal . "' AND id_kategori = '$id_kategori' )z
                                        ")->row("nominal");


					$biaya = $tot_bahan_baku / $rewinder->nominal;
					$html .= '<td align="right">' . number_format($biaya, 2, ",", ".") . '</td>';
				}
				$html .= '
                            <td></td>
                        </tr>';
			}

			$html .= '
                    </table>';
		} else {
			$html .= '<h1> Data Kosong </h1>';
		}

		$kategori = $this->db->query("SELECT nm_kategori from m_kategori WHERE id='$id_kategori' ")->row("nm_kategori");

		$kategori = str_replace(",", "", $kategori);
		$judul = "Laporan Cost " . $kategori . " periode " . $periode;

		if ($ctk == '0') {
			echo $html;
		} else {
			header("Content-type: application/octet-stream");
			header("Content-Disposition: attachment; filename=$judul.xls");
			header("Pragma: no-cache");
			header("Expires: 0");
			$data['prev'] = $html;
			$this->load->view('view_excel', $data);
		}
	}
}
