<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Plan extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('status') != "login") {
			redirect(base_url("Login"));
		}
		$this->load->model('m_plan');
	}

	function Corrugator()
	{
		$data_header = array(
			'judul' => "Plan Corrugator",
		);

		$this->load->view('header',$data_header);

		$jenis = $this->uri->segment(3);
		if($jenis == 'Add'){
			if(in_array($this->session->userdata('level'), ['Admin','PPIC','User'])){
				$this->load->view('Plan/Cor/v_corr_add');
			}else{
				$this->load->view('home');
			}
		}else if($jenis == 'List'){
			if(in_array($this->session->userdata('level'), ['Admin','PPIC','Corrugator','User'])){
				$data = array(
					"tgl_plan" => $this->uri->segment(4),
					"shift" => $this->uri->segment(5),
					"mesin" => $this->uri->segment(6),
				);
				$this->load->view('Plan/Cor/v_corr_plan', $data);
			}else{
				$this->load->view('home');
			}
		}else{
			if(in_array($this->session->userdata('level'), ['Admin','PPIC','Corrugator','User'])){
				$this->load->view('Plan/Cor/v_corr');
			}else{
				$this->load->view('home');
			}
		}

		$this->load->view('footer');
	}

	function loadDataAllPlan()
	{
		$urlTglPlan = $_POST["urlTgl_plan"];
		$urlShift = $_POST["urlShift"];
		$urlMesin = $_POST["urlMesin"];
		$html = '';

		$tglPlan = $this->db->query("SELECT tgl_plan,p.shift_plan,p.machine_plan,
		(SELECT COUNT(lp.tgl_plan) FROM plan_cor lp
		WHERE p.tgl_plan=lp.tgl_plan AND p.shift_plan=lp.shift_plan AND p.machine_plan=lp.machine_plan GROUP BY lp.tgl_plan) AS jml_plan,
		(SELECT COUNT(lp.tgl_plan) FROM plan_cor lp
		WHERE p.tgl_plan=lp.tgl_plan AND p.shift_plan=lp.shift_plan AND p.machine_plan=lp.machine_plan AND lp.total_cor_p!='0' AND lp.status_plan='Open' GROUP BY lp.tgl_plan) AS prod_plan,
		(SELECT COUNT(lp.tgl_plan) FROM plan_cor lp
		INNER JOIN trs_wo w ON lp.id_wo=w.id
		WHERE p.tgl_plan=lp.tgl_plan AND p.shift_plan=lp.shift_plan AND p.machine_plan=lp.machine_plan AND lp.total_cor_p!='0' AND lp.status_plan='Close' AND w.status='Open' GROUP BY lp.tgl_plan) AS selesai_plan,
		(SELECT COUNT(lp.tgl_plan) FROM plan_cor lp
		INNER JOIN trs_wo w ON lp.id_wo=w.id
		WHERE p.tgl_plan=lp.tgl_plan AND p.shift_plan=lp.shift_plan AND p.machine_plan=lp.machine_plan AND lp.total_cor_p!='0' AND lp.status_plan='Close' AND w.status='Close' GROUP BY lp.tgl_plan) AS wo_plan
		FROM plan_cor p
		INNER JOIN trs_wo ww ON p.id_wo=ww.id
		WHERE ww.status='Open'
		GROUP BY p.tgl_plan,p.shift_plan,p.machine_plan");
		$html .='<div id="accordiontglplan">
			<div style="padding:6px;font-weight:bold">
				[SHIFT.MESIN] HARI, TANGGAL <span class="bg-light" style="vertical-align:top;padding:2px 4px;font-size:12px">JUMLAH PLAN</span><span class="bg-success" style="vertical-align:top;padding:2px 4px;font-size:12px">PRODUKSI</span><span class="bg-primary" style="vertical-align:top;padding:2px 4px;font-size:12px">SELESAI</span><span class="bg-dark" style="vertical-align:top;padding:2px 4px;font-size:12px">CLOSE WO</span>
			</div>';
			foreach($tglPlan->result() as $r){
				if($r->jml_plan == $r->wo_plan){
					$html .='';
				}else if($urlTglPlan == $r->tgl_plan && $urlShift == $r->shift_plan && $urlMesin == $r->machine_plan){
					$html .='';
				}else{
					$exTgl = explode("-", $r->tgl_plan);
					$tglPlan = $exTgl[2].$exTgl[1].$exTgl[0];

					($r->machine_plan == 'CORR1') ? $mesin = '1' : $mesin = '2';
					$jmlPlan = '<span class="bg-light" style="vertical-align:top;font-weight:bold;padding:2px 4px;font-size:12px">'.$r->jml_plan.'</span>';
					($r->prod_plan == null) ? $prodPlan = '' : $prodPlan = '<span class="bg-success" style="vertical-align:top;font-weight:bold;padding:2px 4px;font-size:12px">'.$r->prod_plan.'</span>';
					($r->selesai_plan == null) ? $selesaiPlan = '' : $selesaiPlan = '<span class="bg-primary" style="vertical-align:top;font-weight:bold;padding:2px 4px;font-size:12px">'.$r->selesai_plan.'</span>';
					($r->wo_plan == null) ? $woPlan = '' : $woPlan = '<span class="bg-dark" style="vertical-align:top;font-weight:bold;padding:2px 4px;font-size:12px">'.$r->wo_plan.'</span>';

					$html .='<div class="card m-0" style="border-radius:0">
						<div class="card-header bg-gradient-info" style="padding:0;border-radius:0">
							<a class="d-block w-100 link-h-wo" style="font-weight:bold;padding:6px" data-toggle="collapse" href="#collapseHeaderPlan'.$tglPlan.''.$r->shift_plan.''.$r->machine_plan.'")" onclick="loadInputList('."'".$r->tgl_plan."'".','."'".$r->shift_plan."'".','."'".$r->machine_plan."'".','."'".$tglPlan."'".')">
								['.$r->shift_plan.'.'.$mesin.'] '.strtoupper($this->m_fungsi->getHariIni($r->tgl_plan)).', '.strtoupper($this->m_fungsi->tanggal_format_indonesia($r->tgl_plan)).' '.$jmlPlan.''.$prodPlan.''.$selesaiPlan.''.$woPlan.'
							</a>
						</div>
						<div id="collapseHeaderPlan'.$tglPlan.''.$r->shift_plan.''.$r->machine_plan.'" class="collapse" data-parent="#accordiontglplan">
							<div id="tampil-all-plan-isi-'.$tglPlan.''.$r->shift_plan.''.$r->machine_plan.'"></div>
						</div>
					</div>';
				}

			}
		$html .='</div>';

		echo $html;
	}

	function loadDataAllWO()
	{
		$html = '';

		$allWo = $this->db->query("SELECT w.id_pelanggan,c.nm_pelanggan,COUNT(w.id_pelanggan) AS jmlWO FROM trs_wo w
		INNER JOIN m_pelanggan c ON w.id_pelanggan=c.id_pelanggan
		WHERE w.status='Open'
		GROUP BY w.id_pelanggan
		ORDER BY c.nm_pelanggan");
		$html .='<div id="accordion">
			<div style="padding:6px;font-weight:bold">
				CUSTOMER <span class="bg-light" style="vertical-align:top;padding:2px 4px;font-size:12px">JUMLAH WO</span>
			</div>';
			foreach($allWo->result() as $r){
				$html .='<div class="card m-0" style="border-radius:0">
					<div class="card-header bg-gradient-info" style="padding:0;border-radius:0">
						<a class="d-block w-100 link-h-wo" style="font-weight:bold;padding:6px" data-toggle="collapse" href="#collapseHeaderWO'.$r->id_pelanggan.'" onclick="onClickHeaderWO('."'".$r->id_pelanggan."'".')">'.$r->nm_pelanggan.' <span class="bg-light" style="vertical-align:top;font-weight:bold;padding:2px 4px;font-size:12px">'.$r->jmlWO.'</span></a>
					</div>
					<div id="collapseHeaderWO'.$r->id_pelanggan.'" class="collapse" data-parent="#accordion">
						<div id="tampil-all-wo-isi-'.$r->id_pelanggan.'"></div>
					</div>
				</div>';
			}
		$html .='</div>';

		echo $html;
	}

	function onClickHeaderWO()
	{
		$html = '';
		$id_pelanggan = $_POST["id_pelanggan"];
		
		$getWO = $this->db->query("SELECT w.id AS id_wo,w.no_wo,w.kategori,i.nm_produk,w.qty,
		(SELECT COUNT(p.id_plan) FROM plan_cor p WHERE p.id_wo=w.id GROUP BY p.id_wo) AS jml_plan,
		(SELECT COUNT(p.id_plan) FROM plan_cor p WHERE p.id_wo=w.id AND p.total_cor_p!='0' AND p.status_plan='Open' GROUP BY p.id_wo) AS prod_plan,
		(SELECT COUNT(p.id_plan) FROM plan_cor p WHERE p.id_wo=w.id AND p.total_cor_p!='0' AND p.status_plan='Close' GROUP BY p.id_wo) AS ok_plan,
		(SELECT SUM(good_cor_p) FROM plan_cor p WHERE p.id_wo=w.id GROUP BY p.id_wo) AS good_cor,
		(SELECT SUM(bad_cor_p) FROM plan_cor p WHERE p.id_wo=w.id GROUP BY p.id_wo) AS bad_cor,
		(SELECT SUM(total_cor_p) FROM plan_cor p WHERE p.id_wo=w.id GROUP BY p.id_wo) AS total_cor
		FROM trs_wo w
		INNER JOIN m_produk i ON w.id_produk=i.id_produk
		WHERE w.id_pelanggan='$id_pelanggan' AND w.status='Open'
		ORDER BY w.kategori,w.no_wo,i.nm_produk");

		$html .= '<div class="card-body" style="padding:6px">
			<div id="accordion'.$id_pelanggan.'">
			[ TIPE ] NO WO | ITEM <span class="bg-dark" style="font-weight:bold;vertical-align:top;padding:2px 4px;font-size:12px">QTY SO</span><span class="bg-light" style="font-weight:bold;vertical-align:top;padding:2px 4px;font-size:12px">JUMLAH PLAN</span><span class="bg-success" style="font-weight:bold;vertical-align:top;padding:2px 4px;font-size:12px">PRODUKSI</span><span class="bg-primary" style="font-weight:bold;vertical-align:top;padding:2px 4px;font-size:12px">SELESAI</span>';
				foreach($getWO->result() as $r){
					($r->kategori == 'K_BOX') ? $kat = 'BOX' : $kat = 'SHEET';
					$qtySO = '<span class="bg-dark" style="vertical-align:top;font-weight:bold;padding:2px 4px;font-size:12px">'.number_format($r->qty,0,',','.').'</span>';
					($r->jml_plan == null) ? $jml = '0' : $jml = $r->jml_plan;
					$jmlPlan = '<span class="bg-light" style="vertical-align:top;font-weight:bold;padding:2px 4px;font-size:12px">'.$jml.'</span>';
					($r->prod_plan == null) ? $prod = '' : $prod = '<span class="bg-success" style="vertical-align:top;font-weight:bold;padding:2px 4px;font-size:12px">'.$r->prod_plan.'</span>';
					($r->ok_plan == null) ? $ok = '' : $ok = '<span class="bg-primary" style="vertical-align:top;font-weight:bold;padding:2px 4px;font-size:12px">'.$r->ok_plan.'</span>';
					$html .='<div class="card m-0" style="border-radius:0">
						<div class="card-header" style="padding:0;border-radius:0">
							<a class="d-block w-100 link-i-wo" style="font-weight:bold;padding:6px" data-toggle="collapse" href="#collapseIsiWO'.$r->id_wo.'" onclick="onClickPlhWo('."'".$r->id_wo."'".')">
								[ '.$kat.' ] '.$r->no_wo.' | '.$r->nm_produk.' '.$qtySO.''.$jmlPlan.''.$prod.''.$ok.'
							</a>
						</div>
						<div id="collapseIsiWO'.$r->id_wo.'" class="collapse" data-parent="#accordion'.$id_pelanggan.'">
							<div id="tampil-isi-wo-'.$r->id_wo.'" style="overflow:auto;white-space:nowrap"></div>
						</div>
					</div>';
				}
			$html .= '</div>
		</div>';

		echo $html;
	}

	function onClickPlhWo()
	{
		$id_wo = $_POST["id_wo"];
		$html = '';

		$getData = $this->db->query("SELECT pl.*,i.kode_mc,i.flute,so.eta_so,so.qty_so,wo.kategori,wo.flap1,wo.creasing2,wo.flap2 FROM plan_cor pl
		INNER JOIN trs_so_detail so ON pl.id_so_detail=so.id
		INNER JOIN trs_wo wo ON pl.id_wo=wo.id
		INNER JOIN m_produk i ON pl.id_produk=i.id_produk
		WHERE pl.id_wo='$id_wo'
		GROUP BY pl.id_plan
		ORDER BY pl.tgl_plan,pl.id_plan");

		if($getData->num_rows() == 0){
			$html .='';
		}else{
			$html.='<table class="table table-bordered" style="border:0;text-align:center">
				<thead>
					<tr>
						<th style="padding:6px;position:sticky;left:0;background:#fff">#</th>
						<th style="padding:6px">TGL PLAN</th>
						<th style="padding:6px">ETA SO</th>
						<th style="padding:6px">KODE MC</th>
						<th style="padding:6px">KUALITAS</th>
						<th style="padding:6px">PJG</th>
						<th style="padding:6px">LEBAR</th>
						<th style="padding:6px">SCORE</th>
						<th style="padding:6px">OUT</th>
						<th style="padding:6px">FT</th>
						<th style="padding:6px">L.ROLL</th>
						<th style="padding:6px">TRIM</th>
						<th style="padding:6px">HASIL</th>
						<th style="padding:6px">DT. COR</th>
						<th style="padding:6px">C.OFF</th>
						<th style="padding:6px 12px">RM</th>
						<th style="padding:6px 12px">KG</th>
						<th style="padding:6px">TGL KIRIM</th>
						<th style="padding:6px">PLAN FLEXO</th>
						<th style="padding:6px">FLEXO</th>
						<th style="padding:6px">WASTE PCS</th>
						<th style="padding:6px">WASTE KG</th>
						<th style="padding:6px">WASTE TRIM</th>
						<th style="padding:6px">WASTE %</th>
					</tr>
				</thead>';

				$i = 0;
				$sumGood = 0;
				$sumBad = 0;
				$sunGoodBad = 0;
				$html .='<div id="accordion-tf">';
				foreach($getData->result() as $r){
					$i++;

					if($r->kategori == 'K_BOX'){
						$score = $r->flap1.'-'.$r->creasing2.'-'.$r->flap2;
					}else{
						if($r->flap1 != 0 && $r->creasing2 != 0 && $r->flap2 != 0){
							$score = $r->flap1.'-'.$r->creasing2.'-'.$r->flap2;
						}else{
							$score = '-';
						}
					}

					if($r->total_cor_p != 0 && $r->status_plan == 'Open'){
						$borBot = ';border-bottom:1px solid #28a746';
					}else if($r->total_cor_p != 0 && $r->status_plan == 'Close'){
						$borBot = ';border-bottom:1px solid #007bff';
					}else{
						$borBot = '';
					}

					($r->total_cor_p > 0) ? $c_off = number_format($r->good_cor_p / $r->out_plan,0,',','.') : $c_off = '-';
					($r->total_cor_p > 0) ? $rm = number_format((round($r->good_cor_p / $r->out_plan) * $r->panjang_plan) / 1000, 0,',','.') : $rm = '-';
					($r->total_cor_p > 0) ? $rmTrim = ((round($r->good_cor_p / $r->out_plan) * $r->panjang_plan) / 1000) : $rmTrim = 0;
					$expKP = explode("/", $r->kualitas_isi_plan);
					if($r->flute == "BF"){
						$ton = ($expKP[0] + ($expKP[1]*1.36) + $expKP[2]) / 1000 * $r->panjang_plan / 1000 * $r->lebar_plan / 1000 * $r->good_cor_p;
						$wasteKG = ($expKP[0] + ($expKP[1]*1.36) + $expKP[2]) / 1000 * $r->panjang_plan / 1000 * $r->lebar_plan / 1000 * $r->bad_cor_p;
						$wasteTrim = $r->trim_plan / 1000 * $rmTrim * ($expKP[0]/1000 + ($expKP[1]*1.36)/1000 + $expKP[2]/1000) + $wasteKG;
						$wastePersen = ($wasteKG == 0 || $wasteTrim == 0) ? 0 : (($wasteKG+$wasteTrim) / $ton) * 100;
					}else if($r->flute == "CF"){
						$ton = ($expKP[0] + ($expKP[1]*1.46) + $expKP[2]) / 1000 * $r->panjang_plan / 1000 * $r->lebar_plan / 1000 * $r->good_cor_p;
						$wasteKG = ($expKP[0] + ($expKP[1]*1.46) + $expKP[2]) / 1000 * $r->panjang_plan / 1000 * $r->lebar_plan / 1000 * $r->bad_cor_p;
						$wasteTrim = $r->trim_plan / 1000 * $rmTrim * ($expKP[0]/1000 + ($expKP[1]*1.46)/1000 + $expKP[2]/1000) + $wasteKG;
						$wastePersen = ($wasteKG == 0 || $wasteTrim == 0) ? 0 : (($wasteKG+$wasteTrim) / $ton) * 100;
					}else if($r->flute == "BCF"){
						$ton = ($expKP[0] + ($expKP[1]*1.36) + $expKP[2] + ($expKP[3]*1.46) + $expKP[4]) / 1000 * $r->panjang_plan / 1000 * $r->lebar_plan / 1000 * $r->good_cor_p;
						$wasteKG = ($expKP[0] + ($expKP[1]*1.36) + $expKP[2] + ($expKP[3]*1.46) + $expKP[4]) / 1000 * $r->panjang_plan / 1000 * $r->lebar_plan / 1000 * $r->bad_cor_p;
						$wasteTrim = $r->trim_plan / 1000 * $rmTrim * ($expKP[0]/1000 + ($expKP[1]*1.36)/1000 + $expKP[2]/1000 + ($expKP[3]*1.46)/1000 + $expKP[4]/1000) + $wasteKG;
						$wastePersen = ($wasteKG == 0 || $wasteTrim == 0) ? 0 : (($wasteKG+$wasteTrim) / $ton) * 100;
					}else{
						$ton = 0;
						$wasteKG = 0;
						$wasteTrim = 0;
						$wastePersen = 0;
					}
					($ton == 0) ? $ton = '-' : $ton = number_format($ton,0,',','.');
					($wasteKG == 0) ? $wasteKG = '-' : $wasteKG = number_format($wasteKG,0,',','.');
					($wasteTrim == 0) ? $wasteTrim = '-' : $wasteTrim = number_format($wasteTrim,0,',','.');
					($wastePersen == 0) ? $wastePersen = '-' : $wastePersen = number_format($wastePersen, 2).' %';

					$id_plan = $r->id_plan;
					$getDt = $this->db->query("SELECT COUNT(id_plan_cor) AS jml_dt,SUM(durasi_mnt_dt) AS durasi_dt FROM plan_cor_dt WHERE id_plan_cor='$id_plan'
					GROUP BY id_plan_cor");
					($getDt->num_rows() == 0) ? $jml_dt = '-' : $jml_dt = $getDt->row()->jml_dt;
					($getDt->num_rows() == 0) ? $durasi_dt = '' : $durasi_dt = ' ( '.$getDt->row()->durasi_dt.'" ) ';

					$getPF = $this->db->query("SELECT*FROM plan_flexo WHERE id_plan_cor='$r->id_plan'");
					if($getPF->num_rows() == 0){
						$tgl_flexo = '-';
						$mesinFlexo = '-';
					}else if($getPF->num_rows() == 1){
						$tgl_flexo = $this->m_fungsi->tglPlan($getPF->row()->tgl_flexo);
						$mesinFlexo = $getPF->row()->mesin_flexo;
					}else{
						$tgl_flexo = '<a data-toggle="collapse" href="#collapsePlanFlexo-'.$r->id_plan.'">'.$getPF->num_rows().'</a>';
						$mesinFlexo = '<a data-toggle="collapse" href="#collapsePlanFlexo-'.$r->id_plan.'">'.$getPF->num_rows().'</a>';
					}

					($r->machine_plan == 'CORR1') ? $mesin = 1 : $mesin = 2;
					$hariIni = substr($this->m_fungsi->getHariIni($r->tgl_plan),0,3);

					$html.='<tr class="h-tmpl-list-plan">
						<td style="padding:3px;position:sticky;left:0;background:#fff'.$borBot.'">
							<a href="'.base_url('Plan/Corrugator/List').'/'.$r->tgl_plan.'/'.$r->shift_plan.'/'.$r->machine_plan.'" class="btn btn-xs bg-gradient-dark" style="padding:0 4px;margin-right:5px">
								<i class="fa fa-arrow-right"></i>
							</a>
						</td>
						<td style="padding:6px'.$borBot.'">['.$r->shift_plan.'.'.$mesin.'] '.strtoupper($hariIni).', '.strtoupper($this->m_fungsi->tglIndSkt($r->tgl_plan)).'</td>
						<td style="padding:6px'.$borBot.'">'.$this->m_fungsi->tglPlan($r->eta_so).'</td>
						<td style="padding:6px'.$borBot.'">'.$r->kode_mc.'</td>
						<td style="padding:6px'.$borBot.'">'.$r->kualitas_plan.'</td>
						<td style="padding:6px'.$borBot.';font-weight:bold;color:#ff0066">'.number_format($r->panjang_plan,0,',','.').'</td>
						<td style="padding:6px'.$borBot.';font-weight:bold;color:#ff0066">'.number_format($r->lebar_plan,0,',','.').'</td>
						<td style="padding:6px'.$borBot.'">'.$score.'</td>
						<td style="padding:6px'.$borBot.'">'.$r->out_plan.'</td>
						<td style="padding:6px'.$borBot.'">'.$r->flute.'</td>
						<td style="padding:6px'.$borBot.';font-weight:bold">'.number_format($r->lebar_roll_p,0,',','.').'</td>
						<td style="padding:6px'.$borBot.'">'.number_format($r->trim_plan,0,',','.').'</td>
						<td style="padding:6px'.$borBot.';color:#f00;text-align:right">'.number_format($r->good_cor_p,0,',','.').'</td>
						<td style="padding:6px'.$borBot.'">'.$jml_dt.''.$durasi_dt.'</td>
						<td style="padding:6px'.$borBot.'">'.$c_off.'</td>
						<td style="padding:6px'.$borBot.'">'.$rm.'</td>
						<td style="padding:6px'.$borBot.'">'.$ton.'</td>
						<td style="padding:6px'.$borBot.';color:#f00">'.$this->m_fungsi->tglPlan($r->tgl_kirim_plan).'</td>
						<td style="padding:6px'.$borBot.'">'.$tgl_flexo.'</td>
						<td style="padding:6px'.$borBot.'">'.$mesinFlexo.'</td>
						<td style="padding:6px'.$borBot.';text-align:right">'.number_format($r->bad_cor_p,0,',','.').'</td>
						<td style="padding:6px'.$borBot.';text-align:right">'.$wasteKG.'</td>
						<td style="padding:6px'.$borBot.';background:#ff0;text-align:right">'.$wasteTrim.'</td>
						<td style="padding:6px'.$borBot.'">'.$wastePersen.'</td>
					</tr>';

					$html .='<tr>
						<td style="padding:0;border:0;text-align:right" colspan="18"></td>
						<td style="padding:0;border:0" colspan="2">
							<div id="collapsePlanFlexo-'.$r->id_plan.'" class="collapse" data-parent="#accordion-tf">';
								foreach($getPF->result() as $jmlF){
									$html .= $this->m_fungsi->tglPlan($jmlF->tgl_flexo).' - '.$jmlF->mesin_flexo.'<br>';
								}
							$html .='</div>
						</td>
					</tr>';

					$sumGood += $r->good_cor_p;
					$sumBad += $r->bad_cor_p;
					$sunGoodBad += $r->total_cor_p;
				}
				$html .='</div>';

				if($getData->num_rows() > 1){
					$html.='<tr>
						<td style="border:0;padding:6px;font-weight:bold;text-align:right" colspan="12">TOTAL PRODUKSI</td>
						<td style="border:0;padding:6px;font-weight:bold;text-align:right">'.number_format($sumGood).'</td>
					</tr>';
				}

				$hasilSOGod = $sumGood - $getData->row()->qty_so;
				($hasilSOGod > 0) ? $hasilSOGod = '+'.number_format($hasilSOGod) : $hasilSOGod = number_format($hasilSOGod);
				$html.='<tr>
					<td style="border:0;padding:6px;font-weight:bold;text-align:right" colspan="12">QTY SO ( '.number_format($getData->row()->qty_so).' )</td>
					<td style="border:0;padding:6px;font-weight:bold;text-align:right">'.$hasilSOGod.'</td>
				</tr>';
			}

		$html.='</table>';

		echo $html;
	}

	function LoaDataCor()
	{
		$data = array();
		$query = $this->db->query("SELECT COUNT(p.id_plan) AS jml,p.* FROM plan_cor p GROUP BY tgl_plan DESC,shift_plan,machine_plan")->result();
		$i = 0;
		foreach ($query as $r) {
			$i++;
			$row = array();
			$row[] = '<div style="text-align:center">'.$i.'</div>';
			$row[] = strtoupper($this->m_fungsi->tanggal_format_indonesia($r->tgl_plan));
			$row[] = '<div style="text-align:center">'.$r->shift_plan.'</div>';
			$row[] = '<div style="text-align:center">'.$r->machine_plan.'</div>';
			$row[] = $r->no_plan;
			$row[] = '<div style="text-align:center">'.$r->jml.'</div>';

			$link = base_url('Plan/Corrugator/List/'.$r->tgl_plan.'/'.$r->shift_plan.'/'.$r->machine_plan);
			if(in_array($this->session->userdata('level'), ['Admin','PPIC','User'])){
				$btnPrint = '
				<a href="'.$link.'" title="Edit"><button type="button" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></button></a>
				<a target="_blank" class="btn btn-sm btn-success" href="'.base_url("Plan/laporanPlanCor?no_plan=".$r->no_plan."").'" title="Cetak Plan" ><i class="fas fa-print"></i></a>
				<a target="_blank" class="btn btn-sm btn-primary" href="'.base_url("Plan/laporanISOCor?no_plan=".$r->no_plan."").'" title="Cetak SO" ><i class="fas fa-print"></i></a>';
			}else if($this->session->userdata('level') == 'Corrugator'){
				$btnPrint = '<a href="'.$link.'" title="Edit"><button type="button" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></button></a>';
			}else{
				$btnPrint = '';
			}
			$row[] = $btnPrint;

			$data[] = $row;
		}

		$output = array(
			"data" => $data,
		);
		echo json_encode($output);
	}

	function loadPlanWo()
	{
		$getWo = $this->m_plan->loadPlanWo();
		if($_POST['opsi'] != ''){
			$result = $getWo->row();
			$data = true;
			
			$opsi = $_POST['opsi'];
			$getNoPlan = $this->db->query("SELECT*FROM plan_cor WHERE id_plan='$opsi'")->row();
			$urutDtProd = $this->db->query("SELECT*FROM plan_cor
			WHERE no_plan='$getNoPlan->no_plan' AND total_cor_p='0' AND no_urut_plan!='0'
			ORDER BY no_urut_plan ASC LIMIT 1")->row();
		}else{
			$result = $getWo->result();
			$data = false;
			$getNoPlan = false;
			$urutDtProd = false;
		}
		echo json_encode(array(
			'data' => $data,
			'opsi' => $_POST['opsi'],
			'wo' => $result,
			'getNoPlan' => $getNoPlan,
			'urutDtProd' => $urutDtProd,
		));
	}

	function destroyPlan()
	{
		$this->cart->destroy();
	}

	function hapusCartItem()
	{
		$data = array(
			'rowid' => $_POST['rowid'],
			'qty' => 0,
		);
		$this->cart->update($data);
	}

	function addRencanaPlan()
	{
		$opsi = $_POST["opsi"];
		if($opsi == 'add'){
			$data = array(
				'id' => $_POST['id_wo'],
				'name' => $_POST['id_wo'],
				'price' => 0,
				'qty' => 1,
				'options' => array(
					'id_so_detail' => $_POST["id_so_detail"],
					'id_wo' => $_POST["id_wo"],
					'id_produk' => $_POST["id_produk"],
					'nm_produk' => $_POST["nm_produk"],
					'id_pelanggan' => $_POST["id_pelanggan"],
					'customer' => $_POST["customer"],
					'no_wo' => $_POST["no_wo"],
					'no_so' => $_POST["no_so"],
					'kode_po' => $_POST["kode_po"],
					'pcs_plan' => $_POST["pcs_plan"],
					'tgl_plan' => $_POST["tgl_plan"],
					'machine_plan' => $_POST["machine_plan"],
					'shift_plan' => $_POST["shift_plan"],
					'tgl_kirim_plan' => $_POST["tgl_kirim_plan"],
					'next_plan' => $_POST["next_plan"],
					'lebar_roll_p' => $_POST["lebar_roll_p"],
					'panjang_plan' => $_POST["panjang_plan"],
					'lebar_plan' => $_POST["lebar_plan"],
					'out_plan' => $_POST["out_plan"],
					'trim_plan' => $_POST["trim_plan"],
					'c_off_p' => $_POST["c_off_p"],
					'rm_plan' => $_POST["rm_plan"],
					'tonase_plan' => $_POST["tonase_plan"],
					'material_plan' => $_POST["material_plan"],
					'kualitas_plan' => $_POST["kualitas_plan"],
					'kualitas_isi_plan' => $_POST["kualitas_isi_plan"],
					'creasing_wo1' => $_POST["creasing_wo1"],
					'creasing_wo2' => $_POST["creasing_wo2"],
					'creasing_wo3' => $_POST["creasing_wo3"],
					'kategori' => $_POST["kategori"],
					'kupingan' => $_POST["kupingan"],
					'p1' => $_POST["p1"],
					'l1' => $_POST["l1"],
					'p2' => $_POST["p2"],
					'l2' => $_POST["l2"],
					'panjangWO' => $_POST["panjangWO"],
				)
			);

			$tgl_plan = $_POST["tgl_plan"];
			$id_so_detail = $_POST["id_so_detail"];
			$id_wo = $_POST["id_wo"];
			$id_produk = $_POST["id_produk"];
			$id_pelanggan = $_POST["id_pelanggan"];
			$machine_plan = $_POST["machine_plan"];
			$shift_plan = $_POST["shift_plan"];
			$cekHariPlan = $this->db->query("SELECT*FROM plan_cor
			WHERE id_so_detail='$id_so_detail' AND id_wo='$id_wo' AND id_produk='$id_produk' AND id_pelanggan='$id_pelanggan' AND tgl_plan='$tgl_plan' AND shift_plan='$shift_plan' AND machine_plan='$machine_plan'")->num_rows();
			if($cekHariPlan > 0){
				echo json_encode(array('data' => false, 'isi' => 'PLAN SUDAH ADA DI TGL / SHIFT / MESIN YANG SAMA!'));
				return;
			}else{
				if($this->cart->total_items() != 0){
					foreach($this->cart->contents() as $r){
						if($r['id'] == $_POST["id_wo"]){
							echo json_encode(array('data' => false, 'isi' => 'WO SUDAH MASUK LIST PLAN!'));
							return;
						}
					}
					$this->cart->insert($data);
					echo json_encode(array('data' => true, 'opsi' => $opsi, 'isi' => $data));
				}else{
					$this->cart->insert($data);
					echo json_encode(array('data' => true, 'opsi' => $opsi, 'isi' => $data));
				}
			}
		}else{
			$result = $this->m_plan->addRencanaPlan();
			echo json_encode($result);
		}
	}

	function listRencanaPlan()
	{
		$html = '';
		if($this->cart->total_items() != 0){
			$html .='<div class="card card-success card-outline">
			<div class="card-header">
				<h3 class="card-title" style="font-weight:bold;font-style:italic">LIST PLAN BARU</h3>
			</div>
			<div style="overflow:auto;white-space:nowrap;padding-bottom:10px">
				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>#</th>
							<th style="padding:12px 6px">ITEM</th>
							<th style="padding:12px 6px">NO. PO</th>
							<th style="padding:12px 6px">CUSTOMER</th>
							<th style="padding:12px 6px">SUBSTANCE</th>
							<th style="padding:12px 6px">PJG</th>
							<th style="padding:12px 6px">LBR</th>
							<th style="padding:12px 6px">SCORE</th>
							<th style="padding:12px 6px">OUT</th>
							<th style="padding:12px 6px">L.ROLL</th>
							<th style="padding:12px 6px">TRIM</th>
							<th style="padding:12px 6px">ORDER</th>
							<th style="padding:12px 6px">C.OFF</th>
							<th style="padding:12px 6px">RM</th>
							<th style="padding:12px 6px">KG</th>
							<th style="padding:12px 6px">TGL KIRIM</th>
							<th style="padding:12px 6px">NEXT</th>
							<th style="padding:12px 6px">AKSI</th>
						</tr>
					</thead>
					<tbody>';
		}

		$i = 0;
		foreach($this->cart->contents() as $r){
			$i++;

			if($r['options']['creasing_wo1'] == 0 || $r['options']['creasing_wo2'] == 0 || $r['options']['creasing_wo3'] == 0){
				$score = '-';
			}else{
				$score = $r['options']['creasing_wo1'].' - '.$r['options']['creasing_wo2'].' - '.$r['options']['creasing_wo3'];
			}
			$html .='<tr>
				<td style="padding:6px;text-align:center">'.$i.'</td>
				<td style="padding:6px"><a href="javascript:void(0)" onclick="showCartitem('."'".$r['id']."'".','."'input'".')">'.$r['options']['nm_produk'].'<a></td>
				<td style="padding:6px">'.$r['options']['kode_po'].'</td>
				<td style="padding:6px">'.$r['options']['customer'].'</td>
				<td style="padding:6px">'.$r['options']['kualitas_plan'].'</td>
				<td style="padding:6px;text-align:center">'.number_format($r['options']['panjang_plan']).'</td>
				<td style="padding:6px;text-align:center">'.number_format($r['options']['lebar_plan']).'</td>
				<td style="padding:6px">'.$score.'</td>
				<td style="padding:6px;text-align:center">'.number_format($r['options']['out_plan']).'</td>
				<td style="padding:6px;text-align:center">'.number_format($r['options']['lebar_roll_p']).'</td>
				<td style="padding:6px;text-align:center">'.number_format($r['options']['trim_plan']).'</td>
				<td style="padding:6px;text-align:center">'.number_format($r['options']['pcs_plan']).'</td>
				<td style="padding:6px;text-align:center">'.number_format($r['options']['c_off_p']).'</td>
				<td style="padding:6px;text-align:center">'.number_format($r['options']['rm_plan']).'</td>
				<td style="padding:6px;text-align:center">'.number_format($r['options']['tonase_plan']).'</td>
				<td style="padding:6px;text-align:center">'.strtoupper($this->m_fungsi->tglPlan($r['options']['tgl_kirim_plan'])).'</td>
				<td style="padding:6px">'.$r['options']['next_plan'].'</td>
				<td style="padding:0">
					<button class="btn btn-xs btn-danger" onclick="hapusCartItem('."'".$r['rowid']."'".')"><i class="fas fa-times"></i> BATAL</button>
				</td>
			</tr>';
		}

		if($this->cart->total_items() != 0){
			$html .= '</tbody>
					</table>
				<button class="btn btn-sm btn-primary" id="simpan-cart-cor" style="margin-left:20px" onclick="simpanCartItem()"><i class="fas fa-save"></i> SIMPAN</button>
			</div>';
		}

		echo $html;	
	}

	function simpanCartItem()
	{
		$result = $this->m_plan->simpanCartItem();
		echo json_encode($result);
	}

	function showCartitem()
	{
		$html = '';
		$id_plan = $_POST["rowid"];
		$opsi = $_POST["opsi"];
		if($opsi == 'input'){
			if($this->cart->total_items() != 0){
				foreach($this->cart->contents() as $r){
					if($r['id'] == $_POST["rowid"]){
						$tgl_plan = $this->m_fungsi->tanggal_format_indonesia($r['options']['tgl_plan']); $shift_plan = $r['options']['shift_plan']; $machine_plan = $r['options']['machine_plan']; $no_wo = $r['options']['no_wo']; $panjang_plan = number_format($r['options']['panjang_plan']); $lebar_plan = number_format($r['options']['lebar_plan']); $lebar_roll_p = number_format($r['options']['lebar_roll_p']); $out_plan = number_format($r['options']['out_plan']); $pcs_plan = number_format($r['options']['pcs_plan']); $trim_plan = number_format($r['options']['trim_plan']); $c_off_p = number_format($r['options']['c_off_p']); $rm_plan = number_format($r['options']['rm_plan']); $tonase_plan = number_format($r['options']['tonase_plan']); $tgl_kirim_plan = $this->m_fungsi->tanggal_format_indonesia($r['options']['tgl_kirim_plan']); $next_plan = $r['options']['next_plan'];
						$good_cor_p = ''; $bad_cor_p = ''; $total_cor_p = ''; $ket_plan = ''; $start_time_p = ''; $end_time_p = ''; $downtime = 0;
					}
				}
			}
		}else{
			$plan = $this->db->query("SELECT*FROM plan_cor WHERE id_plan='$id_plan'")->row();
			$downtime = $this->db->query("SELECT*FROM plan_cor_dt dt
			INNER JOIN m_downtime md ON dt.id_m_downtime=md.id_downtime
			WHERE dt.id_plan_cor='$id_plan'");
			$tgl_plan = $this->m_fungsi->tanggal_format_indonesia($plan->tgl_plan); $shift_plan = $plan->shift_plan; $machine_plan = $plan->machine_plan; $no_wo = $plan->no_wo; $panjang_plan = number_format($plan->panjang_plan); $lebar_plan = number_format($plan->lebar_plan); $lebar_roll_p = number_format($plan->lebar_roll_p); $out_plan = number_format($plan->out_plan); $pcs_plan = number_format($plan->pcs_plan); $trim_plan = number_format($plan->trim_plan); $c_off_p = number_format($plan->c_off_p); $rm_plan = number_format($plan->rm_plan); $tonase_plan = number_format($plan->tonase_plan); $tgl_kirim_plan = $this->m_fungsi->tanggal_format_indonesia($plan->tgl_kirim_plan); $next_plan = $plan->next_plan;
			$good_cor_p = number_format($plan->good_cor_p); $bad_cor_p = number_format($plan->bad_cor_p); $total_cor_p = number_format($plan->total_cor_p); $ket_plan = $plan->ket_plan; $start_time_p = ($plan->start_time_p == null) ? '-' : substr($plan->start_time_p,0,5); $end_time_p = ($plan->end_time_p == null) ? '-' : substr($plan->end_time_p,0,5);
		}

		$html .= '<div class="row">
			<div class="col-md-6">

				<div class="card card-secondary card-outline" style="padding-bottom:20px">
					<div class="card-header" style="margin-bottom:15px">
						<h3 class="card-title" style="font-weight:bold;font-style:italic">RINCIAN</h3>
					</div>
					<div class="card-body row" style="padding:2px 20px;font-weight:bold">
						<div class="col-md-2">TANGGAL</div>
						<div class="col-md-10"><input type="text" class="form-control" value="'.$tgl_plan.'" disabled></div>
					</div>
					<div class="card-body row" style="padding:2px 20px;font-weight:bold">
						<div class="col-md-2">SHIFT</div>
						<div class="col-md-10"><input type="text" class="form-control" value="'.$shift_plan.'" disabled></div>
					</div>
					<div class="card-body row" style="padding:2px 20px;font-weight:bold">
						<div class="col-md-2">MESIN</div>
						<div class="col-md-10"><input type="text" class="form-control" value="'.$machine_plan.'" disabled></div>
					</div>
					<div class="card-body row" style="padding:2px 20px;font-weight:bold">
						<div class="col-md-2">NO. WO</div>
						<div class="col-md-10"><input type="text" class="form-control" value="'.$no_wo.'" disabled></div>
					</div>
				</div>';

				$hasilProd ='<div class="card card-success card-outline" style="padding-bottom:20px">
					<div class="card-header">
						<h3 class="card-title" style="font-weight:bold;font-style:italic">HASIL PRODUKSI</h3>
					</div>
					<div class="card-body row" style="padding-bottom:5px;font-weight:bold">
						<div class="col-md-2">GOOD</div>
						<div class="col-md-10"><input type="text" class="form-control" value="'.$good_cor_p.'" disabled></div>
					</div>
					<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
						<div class="col-md-2">REJECT</div>
						<div class="col-md-10"><input type="text" class="form-control" value="'.$bad_cor_p.'" disabled></div>
					</div>
					<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
						<div class="col-md-2">TOTAL</div>
						<div class="col-md-10"><input type="text" class="form-control" value="'.$total_cor_p.'" disabled></div>
					</div>
					<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
						<div class="col-md-2">KET</div>
						<div class="col-md-10"><textarea class="form-control" style="resize:none" rows="2" disabled>'.$ket_plan.'</textarea></div>
					</div>
					<div class="card-body row" style="padding:20px 20px 5px;font-weight:bold">
						<div class="col-md-2">START</div>
						<div class="col-md-10"><input type="text" class="form-control" value="'.$start_time_p.'" disabled></div>
					</div>
					<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
						<div class="col-md-2">END</div>
						<div class="col-md-10"><input type="text" class="form-control" value="'.$end_time_p.'" disabled></div>
					</div>
				</div>';

				if($opsi == 'input'){
					if($this->cart->total_items() != 0){
						foreach($this->cart->contents() as $r){
							if($r['id'] == $_POST["rowid"]){
								$html .='';
							}
						}
					}
				}else{
					if($downtime->num_rows() == 0){
						$html .='';
					}else{
						$html .= '<div class="card card-danger card-outline" style="padding-bottom:20px">
							<div class="card-header">
								<h3 class="card-title" style="font-weight:bold;font-style:italic">DOWNTIME</h3>
							</div>
							<div style="overflow:auto;white-space:nowrap">
								<table class="table table-bordered" style="margin:0;border:0">
									<thead>
										<tr>
											<th style="text-align:center">#</th>
											<th>KODE</th>
											<th>-</th>
											<th style="padding:12px 54px 12px 12px">KETERANGAN</th>
											<th style="text-align:center">(M)</th>
										</tr>
									</thead>';
									$data = $this->db->query("SELECT*FROM plan_cor_dt p
									INNER JOIN m_downtime d ON p.id_m_downtime=d.id_downtime
									WHERE p.id_plan_cor='$id_plan'");

									if($data->num_rows() == 0){
										$html .= '<tr>
											<td style="padding:6px;text-align:center" colspan="5">DOWNTIME KOSONG</td>
										</tr>';
									}else{
										$i = 0;
										$sumMntDt = 0;
										foreach($data->result() as $r){
											$i++;
											$html .= '<tr class="h-tmpl-list-plan">
												<td style="padding:6px;text-align:center">'.$i.'</td>
												<td style="padding:6px;text-align:center">'.$r->kode_d.'</td>
												<td style="padding:6px">'.$r->keterangan.'</td>
												<td style="padding:6px">'.$r->ket_plan_dt.'</td>
												<td style="padding:6px;text-align:center">'.$r->durasi_mnt_dt.'</td>
											</tr>';
											$sumMntDt += $r->durasi_mnt_dt;
										}
										if($data->num_rows() != 1){
											$html .='<tr>
												<td style="border:0;padding:6px;background:#fff;font-weight:bold;text-align:right" colspan="4">TOTAL DOWNTIME(M)</td>
												<td style="border:0;padding:6px;background:#fff;font-weight:bold;text-align:center">'.number_format($sumMntDt).'</td>
											</tr>';
										}
									}
								$html .= '</table>
							</div>
						</div>';
					}
					$html .= $hasilProd;
				}

			$html .='</div>
			<div class="col-md-6">
				<div class="card card-info card-outline" style="padding-bottom:20px">
					<div class="card-header" style="margin-bottom:15px">
						<h3 class="card-title" style="font-weight:bold;font-style:italic">PLAN</h3>
					</div>
					<div class="card-body row" style="padding:2px 20px;font-weight:bold">
						<div class="col-md-2" style="padding:0">PANJANG</div>
						<div class="col-md-10"><input type="text" class="form-control" value="'.$panjang_plan.'" disabled></div>
					</div>
					<div class="card-body row" style="padding:2px 20px;font-weight:bold">
						<div class="col-md-2">LEBAR</div>
						<div class="col-md-10"><input type="text" class="form-control" value="'.$lebar_plan.'" disabled></div>
					</div>
					<div class="card-body row" style="padding:2px 20px;font-weight:bold">
						<div class="col-md-2" style="padding-right:0">L. ROLL</div>
						<div class="col-md-10"><input type="text" class="form-control" value="'.$lebar_roll_p.'" disabled></div>
					</div>
					<div class="card-body row" style="padding:2px 20px;font-weight:bold">
						<div class="col-md-2">OUT</div>
						<div class="col-md-10"><input type="text" class="form-control" value="'.$out_plan.'" disabled></div>
					</div>
					<br/>
					<div class="card-body row" style="padding:2px 20px;font-weight:bold">
						<div class="col-md-2">QTY</div>
						<div class="col-md-10"><input type="text" class="form-control" value="'.$pcs_plan.'" disabled></div>
					</div>
					<div class="card-body row" style="padding:2px 20px;font-weight:bold">
						<div class="col-md-2">TRIM</div>
						<div class="col-md-10"><input type="text" class="form-control" value="'.$trim_plan.'" disabled></div>
					</div>
					<div class="card-body row" style="padding:2px 20px;font-weight:bold">
						<div class="col-md-2" style="padding-right:0">C.OFF</div>
						<div class="col-md-10"><input type="text" class="form-control" value="'.$c_off_p.'" disabled></div>
					</div>
					<div class="card-body row" style="padding:2px 20px;font-weight:bold">
						<div class="col-md-2">RM</div>
						<div class="col-md-10"><input type="text" class="form-control" value="'.$rm_plan.'" disabled></div>
					</div>
					<div class="card-body row" style="padding:2px 20px;font-weight:bold">
						<div class="col-md-2">TON</div>
						<div class="col-md-10"><input type="text" class="form-control" value="'.$tonase_plan.'" disabled></div>
					</div>
					<br/>
					<div class="card-body row" style="padding:2px 20px;font-weight:bold">
						<div class="col-md-2" style="padding-right:0">KIRIM</div>
						<div class="col-md-10"><input type="text" class="form-control" value="'.$tgl_kirim_plan.'" disabled></div>
					</div>
					<div class="card-body row" style="padding:2px 20px;font-weight:bold">
						<div class="col-md-2">NEXT</div>
						<div class="col-md-10"><input type="text" class="form-control" value="'.$next_plan.'" disabled></div>
					</div>
				</div>
			</div>
		</div>';

		echo $html;
	}

	function loadDataPlan()
	{
		$getData = $this->m_plan->loadDataPlan();
		echo json_encode(array(
			'data' => true,
			'planCor' => $getData,
		));
	}

	function produksiRencanaPlan()
	{
		$result = $this->m_plan->produksiRencanaPlan();
		echo json_encode($result);
	}

	function hapusPlan()
	{
		$result = $this->m_plan->hapusPlan();
		echo json_encode($result);
	}

	function selesaiPlan()
	{
		$result = $this->m_plan->selesaiPlan();
		echo json_encode($result);
	}

	function selesaiPlanWO()
	{
		$result = $this->m_plan->selesaiPlanWO();
		echo json_encode($result);
	}

	function laporanISOCor()
	{
		$html = '';
		$no_plan = $_GET['no_plan'];

		$html .= '<table style="margin:0;padding:0;font-size:10px;text-align:center;border-collapse:collapse;color:#000;width:100%">
			<thead>
				<tr>
					<th style="width:5%;border:1px solid #000;border-width:1px 0 0 1px" rowspan="2">
						<img src="'.base_url('assets/gambar/ppi.png').'" alt="PPI" width="70" height="60">
					</th>
					<th style="width:45%;border:1px solid #000;border-width:1px 0 0;font-weight:bold;text-align:left;font-size:16px;padding-left:10px">PT. PRIMA PAPER INDONESIA</th>
					<th style="width:35%;border:1px solid #000;border-width:1px 1px 0 0;font-weight:bold;text-align:right;font-size:14px;padding-right:10px">CORRUGATORs PLAN</th>
					<th style="width:15%;border:1px solid #000;border-width:1px 1px 0;text-align:left;font-weight:bold" rowspan="2">
						<table style="margin:0;padding:0;font-size:10px;font-weight:normal;text-align:left;border-collapse:collapse">
							<tr>
								<td>No</td>
								<td style="padding:0 5px">:</td>
								<td>FR-PPIC-01</td>
							</tr>
							<tr>
								<td>Tgl Terbit</td>
								<td style="padding:0 5px">:</td>
								<td>27 Sep 2022</td>
							</tr>
							<tr>
								<td>Rev</td>
								<td style="padding:0 5px">:</td>
								<td>00</td>
							</tr>
							<tr>
								<td>Hal</td>
								<td style="padding:0 5px">:</td>
								<td>1</td>
							</tr>
						</table>
					</th>
				</tr>
				<tr>
					<th style="vertical-align:top;font-style:italic;font-weight:normal;text-align:left;padding-left:10px">Dusun Timang Kulon, Desa Wonokerto, Wonogiri</th>
					<th></th>
				</tr>
			</thead>
		</table>';

		$query = $this->db->query("SELECT l.*,m.*,p.*,m.kategori AS kategoriItem,w.kode_po AS no_po,w.flap1,w.creasing2 AS creasing2wo,w.flap2 FROM plan_cor l
		INNER JOIN m_produk m ON l.id_produk=m.id_produk
		INNER JOIN m_pelanggan p ON l.id_pelanggan=p.id_pelanggan
		INNER JOIN trs_wo w ON l.id_wo=w.id
		WHERE no_plan='$no_plan'
		ORDER BY l.no_urut_plan");

		$html .= '<table style="margin:0;padding:0;font-size:10px;text-align:center;border-collapse:collapse;color:#000;width:100%">';
		$html .= '<thead>
			<tr>
				<th style="width:2%;border:0;padding:0"></th>
				<th style="width:18%;border:0;padding:0"></th>
				<th style="width:4%;border:0;padding:0"></th>
				<th style="width:9%;border:0;padding:0"></th>
				<th style="width:2%;border:0;padding:0"></th>
				<th style="width:4%;border:0;padding:0"></th>
				<th style="width:8%;border:0;padding:0"></th>
				<th style="width:4%;border:0;padding:0"></th>
				<th style="width:4%;border:0;padding:0"></th>
				<th style="width:2%;border:0;padding:0"></th>
				<th style="width:2%;border:0;padding:0"></th>
				<th style="width:4%;border:0;padding:0"></th>
				<th style="width:4%;border:0;padding:0"></th>
				<th style="width:4%;border:0;padding:0"></th>
				<th style="width:4%;border:0;padding:0"></th>
				<th style="width:4%;border:0;padding:0"></th>
				<th style="width:4%;border:0;padding:0"></th>
				<th style="width:3%;border:0;padding:0"></th>
				<th style="width:14%;border:0;padding:0"></th>
			</tr>
			<tr text-rotate="-90">
				<th text-rotate="0" style="font-weight:bold;border:1px solid #000" rowspan="4">ID</th>
				<th text-rotate="0" style="font-weight:bold;border:1px solid #000;border-width:1px 1px 0;text-align:left">NO. SO/WO</th>
				<th text-rotate="0" style="font-weight:bold;border:1px solid #000" rowspan="4">TGL KIRIM</th>
				<th text-rotate="0" style="font-weight:bold;border:1px solid #000" rowspan="4">KUALITAS SHEET</th>
				<th style="font-weight:bold;border:1px solid #000" rowspan="4">FLUTE</th>
				<th text-rotate="0" style="font-weight:bold;border:1px solid #000" rowspan="4">LEBAR ROLL</th>
				<th text-rotate="0" style="font-weight:bold;border:1px solid #000" rowspan="4">SCORING LINE</th>
				<th text-rotate="0" style="font-weight:bold;border:1px solid #000" rowspan="2" colspan="2">SHEET SIZE</th>
				<th style="font-weight:bold;border:1px solid #000" rowspan="4">OUT</th>
				<th style="font-weight:bold;border:1px solid #000" rowspan="4">TRIM</th>
				<th text-rotate="0" style="font-weight:bold;border:1px solid #000" rowspan="2" colspan="5">RENCANA PRODUKSI</th>
				<th text-rotate="0" style="font-weight:bold;border:1px solid #000" rowspan="2">WAKTU</th>
				<th style="font-weight:bold;border:1px solid #000" rowspan="4">NEXT</th>
				<th text-rotate="0" style="font-weight:bold;border:1px solid #000" rowspan="4">KET</th>
			</tr>
			<tr>
				<th style="font-weight:bold;border:1px solid #000;border-width:0 1px;text-align:left">CUSTOMER</th>
			</tr>
			<tr>
				<th style="font-weight:bold;border:1px solid #000;border-width:0 1px;text-align:left">NO. PO</th>
				<th style="font-weight:bold;border:1px solid #000;border-width:0 1px">P</th>
				<th style="font-weight:bold;border:1px solid #000;border-width:0 1px">L</th>
				<th style="font-weight:bold;border:1px solid #000" rowspan="2">COUNT PCS</th>
				<th style="font-weight:bold;border:1px solid #000" rowspan="2">NUM OF CUT</th>
				<th style="font-weight:bold;border:1px solid #000" rowspan="2">RM</th>
				<th style="font-weight:bold;border:1px solid #000" rowspan="2">TON</th>
				<th style="font-weight:bold;border:1px solid #000" rowspan="2">BAD SHEET</th>
				<th style="font-weight:bold;border:1px solid #000;border-width:0 1px">START</th>
			</tr>
			<tr>
				<th style="font-weight:bold;border:1px solid #000;border-width:0 1px 1px;text-align:left">ITEM</th>
				<th style="font-weight:bold;border:1px solid #000;border-width:0 1px 1px">mm</th>
				<th style="font-weight:bold;border:1px solid #000;border-width:0 1px 1px">mm</th>
				<th style="font-weight:bold;border:1px solid #000;border-width:0 1px 1px">END</th>
			</tr>
			<tr>
				<th style="padding:2px 0;font-weight:bold;text-align:left" colspan="2">PERIODE : '.strtoupper($this->m_fungsi->tanggal_format_indonesia($query->row()->tgl_plan)).'</th>
				<th style="padding:2px 0;font-weight:bold;text-align:left" colspan="2">MACHINE : '.$query->row()->machine_plan.'</th>
				<th style="padding:2px 0;font-weight:bold;text-align:left" colspan="3">SHIFT : '.$query->row()->shift_plan.'</th>
			</tr>
		</thead>
		<tbody>';

		$i = 0;
		foreach($query->result() as $r){
			$i++;

			$expKualitas = explode("/", $r->kualitas);
			if($r->flute == 'BCF'){
				if($expKualitas[1] == 'M125' && $expKualitas[2] == 'M125' && $expKualitas[3] == 'M125'){
					$kualitas = $expKualitas[0].'/'.$expKualitas[1].'x3/'.$expKualitas[4];
				}else if($expKualitas[1] == 'K125' && $expKualitas[2] == 'K125' && $expKualitas[3] == 'K125'){
					$kualitas = $expKualitas[0].'/'.$expKualitas[1].'x3/'.$expKualitas[4];
				}else if($expKualitas[1] == 'M150' && $expKualitas[2] == 'M150' && $expKualitas[3] == 'M150'){
					$kualitas = $expKualitas[0].'/'.$expKualitas[1].'x3/'.$expKualitas[4];
				}else if($expKualitas[1] == 'K150' && $expKualitas[2] == 'K150' && $expKualitas[3] == 'K150'){
					$kualitas = $expKualitas[0].'/'.$expKualitas[1].'x3/'.$expKualitas[4];
				}else{
					$kualitas = $r->kualitas;
				}
			}else{
				$kualitas = $r->kualitas;
			}

			if($r->kategoriItem == 'K_BOX'){
				$score = $r->flap1.' - '.$r->creasing2wo.' - '.$r->flap2;
			}else if($r->kategoriItem == 'K_SHEET'){
				if($r->flap1 != 0 && $r->creasing2wo != 0 && $r->flap2 != 0){
					$score = $r->flap1.' - '.$r->creasing2wo.' - '.$r->flap2;
				}else{
					$score = '-';
				}
			}else{
				$score = '-';
			}

			if($r->next_plan == 'GUDANG'){
				$nextPlan = 'G. B. <br>JADI';
			}else{
				$nextPlan = $r->next_plan;
			}

			$id_plan = $r->id_plan;
			$cekDowntime = $this->db->query("SELECT*FROM plan_cor_dt dt
			INNER JOIN m_downtime md ON dt.id_m_downtime=md.id_downtime
			WHERE dt.id_plan_cor='$id_plan'");
			$txtKet = '';
			if($cekDowntime->num_rows() == 0){
				$txtKet .= $r->ket_plan;
			}else{
				$txtKet .= $r->ket_plan.'<br>';
				foreach($cekDowntime->result() as $dt){
					$txtKet .= '('.$dt->kode_d.'-'.$dt->durasi_mnt_dt.'") ';
				}
			}

			($r->start_time_p == null) ? $start_time_p = '' : $start_time_p = substr($r->start_time_p,0,5);
			($r->end_time_p == null) ? $end_time_p = '' : $end_time_p = substr($r->end_time_p,0,5);
			($r->flute == 'BCF') ? $flute = 'BC' : $flute = $r->flute;

			// number_format($r->pcs_plan) number_format($r->c_off_p) number_format($r->rm_plan) number_format($r->tonase_plan) number_format($r->bad_cor_p)
			($r->total_cor_p > 0) ? $c_off = number_format($r->good_cor_p / $r->out_plan,0,',','.') : $c_off = '';
			($r->total_cor_p > 0) ? $rm = number_format((round($r->good_cor_p / $r->out_plan) * $r->panjang_plan) / 1000, 0,',','.') : $rm = '';
			$expKP = explode("/", $r->kualitas_isi_plan);
			if($r->flute == "BF"){
				$ton = ($expKP[0] + ($expKP[1]*1.36) + $expKP[2]) / 1000 * $r->panjang_plan / 1000 * $r->lebar_plan / 1000 * $r->good_cor_p;
			}else if($r->flute == "CF"){
				$ton = ($expKP[0] + ($expKP[1]*1.46) + $expKP[2]) / 1000 * $r->panjang_plan / 1000 * $r->lebar_plan / 1000 * $r->good_cor_p;
			}else if($r->flute == "BCF"){
				$ton = ($expKP[0] + ($expKP[1]*1.36) + $expKP[2] + ($expKP[3]*1.46) + $expKP[4]) / 1000 * $r->panjang_plan / 1000 * $r->lebar_plan / 1000 * $r->good_cor_p;
			}else{
				$ton = 0;
			}
			($r->good_cor_p == 0) ? $good_cor_p = '' : $good_cor_p = number_format($r->good_cor_p,0,',','.');
			($ton == 0) ? $ton = '' : $ton = number_format($ton,0,',','.');
			($r->bad_cor_p == 0) ? $bad_cor_p = '' : $bad_cor_p = number_format($r->bad_cor_p,0,',','.');
			$html .= '
				<tr>
					<td style="border:1px solid #000" rowspan="4">'.$i.'</td>
					<td style="border:1px solid #000;text-align:left">'.$r->kode_mc.'</td>
					<td style="border:1px solid #000" rowspan="4">'.$this->m_fungsi->tglPlan($r->tgl_kirim_plan).'</td>
					<td style="border:1px solid #000" rowspan="4">'.$kualitas.'</td>
					<td style="border:1px solid #000" rowspan="4">'.$flute.'</td>
					<td style="border:1px solid #000;font-weight:bold" rowspan="4">'.$r->lebar_roll_p.'</td>
					<td style="border:1px solid #000" rowspan="4">'.$score.'</td>
					<td style="border:1px solid #000;font-weight:bold;color:#f00" rowspan="4">'.$r->panjang_plan.'</td>
					<td style="border:1px solid #000;font-weight:bold;color:#f00" rowspan="4">'.$r->lebar_plan.'</td>
					<td style="border:1px solid #000" rowspan="4">'.number_format($r->out_plan).'</td>
					<td style="border:1px solid #000" rowspan="4">'.number_format($r->trim_plan,0,',','.').'</td>
					<td style="border:1px solid #000;border-bottom:1px dotted #000" rowspan="2">'.$good_cor_p.'</td>
					<td style="border:1px solid #000;border-bottom:1px dotted #000" rowspan="2">'.$c_off.'</td>
					<td style="border:1px solid #000;border-bottom:1px dotted #000" rowspan="2">'.$rm.'</td>
					<td style="border:1px solid #000;border-bottom:1px dotted #000" rowspan="2">'.$ton.'</td>
					<td style="border:1px solid #000;border-bottom:1px dotted #000" rowspan="2">'.$bad_cor_p.'</td>
					<td style="border:1px solid #000;border-bottom:1px dotted #000" rowspan="2">'.$start_time_p.'</td>
					<td style="border:1px solid #000" rowspan="4">'.$nextPlan.'</td>
					<td style="border:1px solid #000;text-align:left;vertical-align:top" rowspan="4">'.$txtKet.'</td>
				</tr>
				<tr>
					<td style="border:1px solid #000;border-width:0 1px;text-align:left">'.$r->nm_pelanggan.'</td>
				</tr>
				<tr>
					<td style="border:1px solid #000;border-width:0 1px;text-align:left">'.$r->no_po.'</td>
					<td style="border:1px solid #000;border-width:0 1px 1px" rowspan="2"></td>
					<td style="border:1px solid #000;border-width:0 1px 1px" rowspan="2"></td>
					<td style="border:1px solid #000;border-width:0 1px 1px" rowspan="2"></td>
					<td style="border:1px solid #000;border-width:0 1px 1px" rowspan="2"></td>
					<td style="border:1px solid #000;border-width:0 1px 1px" rowspan="2"></td>
					<td style="border:1px solid #000;border-width:0 1px 1px" rowspan="2">'.$end_time_p.'</td>
				</tr>
				<tr>
					<td style="border:1px solid #000;border-width:0 1px 1px;text-align:left">'.$r->nm_produk.'</td>
				</tr>';
		}
		
		$html .= '</tbody></table>';

		$html .= '<table style="margin:0;padding:0;font-size:10px;text-align:center;border-collapse:collapse;color:#000;width:100%">
			<tr>
				<td style="border:0;width:40%"></td>
				<td style="border:0;width:12%"></td>
				<td style="border:0;width:12%"></td>
				<td style="border:0;width:12%"></td>
				<td style="border:0;width:12%"></td>
				<td style="border:0;width:12%"></td>
			</tr>
			<tr>
				<td style="font-weight:bold;text-align:left">DISTRIBUSI : PPIC - PRODUKSI - BAGIAN TINTA - PREPARATION</td>
			</tr>
			<tr>
				<td style="font-weight:bold;text-align:left" rowspan="3">TOTAL PCS :</td>
				<td style="border:1px solid #000;font-weight:bold" colspan="5">OTORISASI</td>
			</tr>
			<tr>
				<td style="border:1px solid #000;border-width:1px 0 1px 1px;font-weight:bold" colspan="3">PPIC</td>
				<td style="border:1px solid #000;border-width:1px 1px 1px 0;font-weight:bold" colspan="2">PRODUKSI</td>
			</tr>
			<tr>
				<td style="border:1px solid #000">Mengetahui</td>
				<td style="border:1px solid #000">Dibuat Oleh</td>
				<td style="border:1px solid #000">Mengetahui</td>
				<td style="border:1px solid #000">Diperiksa</td>
				<td style="border:1px solid #000">Dibuat Oleh</td>
			</tr>
			<tr>
				<td style="font-weight:bold;text-align:left" rowspan="3">TOTAL TONASE :</td>
				<td style="border:1px solid #000;border-width:0 1px;padding:7px"></td>
				<td style="border:1px solid #000;border-width:0 1px;padding:7px"></td>
				<td style="border:1px solid #000;border-width:0 1px;padding:7px"></td>
				<td style="border:1px solid #000;border-width:0 1px;padding:7px"></td>
				<td style="border:1px solid #000;border-width:0 1px;padding:7px"></td>
			</tr>
			<tr>
				<td style="border:1px solid #000;border-width:0 1px;padding:7px"></td>
				<td style="border:1px solid #000;border-width:0 1px;padding:7px"></td>
				<td style="border:1px solid #000;border-width:0 1px;padding:7px"></td>
				<td style="border:1px solid #000;border-width:0 1px;padding:7px"></td>
				<td style="border:1px solid #000;border-width:0 1px;padding:7px"></td>
			</tr>
			<tr>
				<td style="border:1px solid #000;border-width:0 1px;padding:7px"></td>
				<td style="border:1px solid #000;border-width:0 1px;padding:7px"></td>
				<td style="border:1px solid #000;border-width:0 1px;padding:7px"></td>
				<td style="border:1px solid #000;border-width:0 1px;padding:7px"></td>
				<td style="border:1px solid #000;border-width:0 1px;padding:7px"></td>
			</tr>
			<tr>
				<td></td>
				<td style="border:1px solid #000">KABAG</td>
				<td style="border:1px solid #000">Adm. PPC</td>
				<td style="border:1px solid #000">KABAG</td>
				<td style="border:1px solid #000">KASI</td>
				<td style="border:1px solid #000">Operator</td>
			</tr>';
		$html .= '</table>';
		
		$jdlTgl = str_replace('/','', $this->m_fungsi->tglPlan($query->row()->tgl_plan));
		$judul = 'PLANCOR-'.$jdlTgl.'.'.$query->row()->shift_plan.'.'.$query->row()->machine_plan.'.ISO';
		$this->m_fungsi->newMpdf($judul, '', $html, 1, 3, 1, 3, 'L', 'A4', $judul.'.pdf');
		// echo $html;
	}

	function laporanPlanCor()
	{
		$no_plan = $_GET['no_plan'];
		$html = '';

		$data = $this->db->query("SELECT*FROM plan_cor a 
		INNER JOIN m_produk b ON a.id_produk=b.id_produk
		INNER JOIN m_pelanggan c ON a.id_pelanggan=c.id_pelanggan
		INNER JOIN trs_so_detail d ON a.id_so_detail=d.id
		INNER JOIN trs_wo e ON a.id_wo=e.id
		WHERE no_plan='$no_plan'
		ORDER BY a.no_urut_plan");

		if ($data->num_rows() > 0) {
			$html .= '<table style="margin:0;padding:0;font-size:10px;text-align:center;border-collapse:collapse;color:#000;width:100%">
				<thead>
					<tr>
						<th style="border:0;width:2%"></th>
						<th style="border:0;width:9%"></th>
						<th style="border:0;width:9%"></th>
						<th style="border:0;width:10%"></th>
						<th style="border:0;width:2%"></th>
						<th style="border:0;width:2%"></th>
						<th style="border:0;width:2%"></th>
						<th style="border:0;width:2%"></th>
						<th style="border:0;width:2%"></th>
						<th style="border:0;width:2%"></th>
						<th style="border:0;width:2%"></th>
						<th style="border:0;width:2%"></th>
						<th style="border:0;width:2%"></th>
						<th style="border:0;width:2%"></th>
						<th style="border:0;width:4%"></th>
						<th style="border:0;width:4%"></th>
						<th style="border:0;width:3%"></th>
						<th style="border:0;width:3%"></th>
						<th style="border:0;width:3%"></th>
						<th style="border:0;width:2%"></th>
						<th style="border:0;width:2%"></th>
						<th style="border:0;width:4%"></th>
						<th style="border:0;width:4%"></th>
						<th style="border:0;width:4%"></th>
						<th style="border:0;width:4%"></th>
						<th style="border:0;width:4%"></th>
						<th style="border:0;width:4%"></th>
						<th style="border:0;width:5%"></th>
					</tr>
					<tr>
						<th style="font-size:16px;padding-bottom:10px" colspan="28">PLAN '.$data->row()->machine_plan.'</th>
					</tr>
					<tr>
						<th style="border:1px solid #000;text-align:center">NO</th>
						<th style="border:1px solid #000;text-align:center">ITEM</th>
						<th style="border:1px solid #000;text-align:center">NO PO</th>
						<th style="border:1px solid #000;text-align:center">CUSTOMER</th>
						<th style="border:1px solid #000;text-align:center" colspan="2">TL/AL</th>
						<th style="border:1px solid #000;text-align:center" colspan="2">B MF</th>
						<th style="border:1px solid #000;text-align:center" colspan="2">B.L </th>
						<th style="border:1px solid #000;text-align:center" colspan="2">C.MF</th>
						<th style="border:1px solid #000;text-align:center" colspan="2">C.L </th>
						<th style="border:1px solid #000;text-align:center">PJG</th>
						<th style="border:1px solid #000;text-align:center">LBR</th>
						<th style="border:1px solid #000;text-align:center" colspan="3">SCORE</th>
						<th style="border:1px solid #000;text-align:center">OT</th>
						<th style="border:1px solid #000;text-align:center">FT</th>
						<th style="border:1px solid #000;text-align:center">LBR ROLL</th>
						<th style="border:1px solid #000;text-align:center">TRIM</th>
						<th style="border:1px solid #000;text-align:center">ORDER</th>
						<th style="border:1px solid #000;text-align:center">C.OFF</th>
						<th style="border:1px solid #000;text-align:center">RM</th>
						<th style="border:1px solid #000;text-align:center">KG</th>
						<th style="border:1px solid #000;text-align:center">TGL KIRIM</th>
					</tr>
					<tr>
						<th style="border:1px solid #000;border-width:1px 0 1px 1px;padding:7px 0;font-size:14px" colspan="16">PLAN '.strtoupper($this->m_fungsi->getHariIni($data->row()->tgl_plan)).' '.strtoupper($this->m_fungsi->tanggal_format_indonesia($data->row()->tgl_plan)).'</th>
						<th style="border:1px solid #000;border-width:1px 1px 1px 0;padding:7px 0" colspan="12"></th>
					</tr>
				</thead>
				<tbody>';
					$no = 0;
					$sumCoff = 0;
					$sumRM = 0;
					$sumKG = 0;
					foreach ($data->result() as $r) {
						$no++;
						$substance = explode("/", $r->material_plan);
						$gramature = explode("/", $r->kualitas_isi_plan);
						if( $r->flute =='BCF'){
							$s1 = $substance[0]; $s2 = $substance[1]; $s3 = $substance[2]; $s4 = $substance[3]; $s5 = $substance[4];
							$grm1 = $gramature[0]; $grm2 = $gramature[1]; $grm3 = $gramature[2]; $grm4 = $gramature[3]; $grm5 = $gramature[4];
						}else if( $r->flute =='BF'){
							$s1 = $substance[0]; $s2 = $substance[1]; $s3 = $substance[2]; $s4 = 0; $s5 = 0;
							$grm1 = $gramature[0]; $grm2 = $gramature[1]; $grm3 = $gramature[2]; $grm4 = 0; $grm5 = 0;
						}else if( $r->flute =='CF'){
							$s1 = $substance[0]; $s2 = 0; $s3 = 0; $s4 = $substance[1]; $s5 = $substance[2];
							$grm1 = $gramature[0]; $grm2 = 0; $grm3 = 0; $grm4 = $gramature[1]; $grm5 = $gramature[2];
						}
						($r->flute == 'BCF') ? $flute = 'BC' : $flute = $r->flute;
						$html .= '<tr>
							<td style="padding:3px 0;border:1px solid #000">'.$no.'</td>
							<td style="padding:3px 0;border:1px solid #000;text-align:left">'.$r->nm_produk.'</td>
							<td style="padding:3px 0;border:1px solid #000;text-align:left">'.$r->no_po.'</td>
							<td style="padding:3px 0;border:1px solid #000;text-align:left">'.$r->nm_pelanggan.'</td>
							<td style="padding:3px 0;border:1px solid #000;color:red"><b>'.$s1.'</b></td>
							<td style="padding:3px 0;border:1px solid #000">'.$grm1.'</td>
							<td style="padding:3px 0;border:1px solid #000">'.$s2.'</td>
							<td style="padding:3px 0;border:1px solid #000">'.$grm2.'</td>
							<td style="padding:3px 0;border:1px solid #000">'.$s3.'</td>
							<td style="padding:3px 0;border:1px solid #000">'.$grm3.'</td>
							<td style="padding:3px 0;border:1px solid #000">'.$s4.'</td>
							<td style="padding:3px 0;border:1px solid #000">'.$grm4.'</td>
							<td style="padding:3px 0;border:1px solid #000">'.$s5.'</td>
							<td style="padding:3px 0;border:1px solid #000">'.$grm5.'</td>
							<td style="padding:3px 2px 3px 0;border:1px solid #000;text-align:right;color:#ff0066;font-weight:bold">'.number_format($r->panjang_plan, 0, ",", ".").'</td>
							<td style="padding:3px 2px 3px 0;border:1px solid #000;text-align:right;color:#ff0066;font-weight:bold">'.number_format($r->lebar_plan, 0, ",", ".").'</td>
							<td style="padding:3px 0;border:1px solid #000">'.number_format($r->flap1, 0, ",", ".").'</td>
							<td style="padding:3px 0;border:1px solid #000">'.number_format($r->flap1, 0, ",", ".").'</td>
							<td style="padding:3px 0;border:1px solid #000">'.number_format($r->flap1, 0, ",", ".").'</td>
							<td style="padding:3px 0;border:1px solid #000">'.$r->out_plan.'</td>
							<td style="padding:3px 0;border:1px solid #000">'.$flute .' </td>
							<td style="padding:3px 0;border:1px solid #000;font-weight:bold">'.number_format($r->lebar_roll_p, 0, ",", ".").'</td>
							<td style="padding:3px 2px 3px 0;border:1px solid #000;text-align:right">'.number_format($r->trim_plan, 0, ",", ".").'</td>
							<td style="padding:3px 2px 3px 0;border:1px solid #000;text-align:right;color:red;font-weight:bold">'.number_format($r->pcs_plan, 0, ",", ".").'</td>
							<td style="padding:3px 2px 3px 0;border:1px solid #000;text-align:right">'.number_format($r->c_off_p, 0, ",", ".").'</td>
							<td style="padding:3px 2px 3px 0;border:1px solid #000;text-align:right">'.number_format($r->rm_plan, 0, ",", ".").'</td>
							<td style="padding:3px 2px 3px 0;border:1px solid #000;text-align:right">'.number_format($r->tonase_plan, 0, ",", ".").'</td>
							<td style="padding:3px 0;border:1px solid #000;color:red;font-weight:bold">'. $this->m_fungsi->tglPlan($r->tgl_kirim_plan).'</td>
						</tr>';

						$sumCoff += $r->c_off_p;
						$sumRM += $r->rm_plan;
						$sumKG += $r->tonase_plan;
					}
					$html .='<tr>
						<td style="background:#ddd;padding:5px 0;font-weight:bold;border:1px solid #000" colspan="24"></td>
						<td style="background:#ddd;padding:5px 0;font-weight:bold;color:#f00;border:1px solid #000">'.number_format($sumCoff,0,',','.').'</td>
						<td style="background:#ddd;padding:5px 0;font-weight:bold;color:#f00;border:1px solid #000">'.number_format($sumRM,0,',','.').'</td>
						<td style="background:#ddd;padding:5px 0;font-weight:bold;color:#f00;border:1px solid #000">'.number_format($sumKG,0,',','.').'</td>
						<td style="background:#ddd;padding:5px 0;font-weight:bold;border:1px solid #000"></td>
					</tr>';
					$html .='
				</tbody>
			</table>';
		}else{
			$html .= '';
		}

		$jdlTgl = str_replace('/','', $this->m_fungsi->tglPlan($data->row()->tgl_plan));
		$judul = 'PLANCOR-'.$jdlTgl.'.'.$data->row()->shift_plan.'.'.$data->row()->machine_plan;
		$this->m_fungsi->newMpdf($judul, '', $html, 1, 3, 1, 3, 'L', 'A4', $judul.'.pdf');
	}

	function riwayatPlan()
	{
		$html = '';
		$result = $this->m_plan->riwayatPlan();
		
		if($result->num_rows() == 0){
			$html .='';
		}else{
			$html .='<div class="card card-warning card-outline">
				<div class="card-header">
					<h3 class="card-title" style="font-weight:bold;font-style:italic">RIWAYAT PLAN</h3>
				</div>
				<div style="overflow:auto;white-space:nowrap">
					<table class="table table-bordered table-striped" style="border:0;text-align:center">
						<thead>
							<tr>
								<th style="padding:12px 6px">#</th>
								<th style="padding:12px 6px;text-align:left">TGL PLAN</th>
								<th style="padding:12px 6px">HASIL</th>
								<th style="padding:12px 6px">REJECT</th>
								<th style="padding:12px 6px">TOTAL</th>
								<th style="padding:12px 6px">DOWNTIME(m)</th>
								<th style="padding:12px 6px">START</th>
								<th style="padding:12px 6px">END</th>
							</tr>
						</thead>';
						$i = 0;
						$good_cor_p = 0;
						foreach($result->result() as $r){
							$i++;

							if($r->jmlDt == 0){
								$txtDowtime = '-';
							}else{
								$txtDowtime = $r->jmlDt.' ( '.number_format($r->jmlDtDurasi).' )';
							}

							($r->start_time_p == null) ? $start_time_p = '-' : $start_time_p = substr($r->start_time_p,0,5);
							($r->end_time_p == null) ? $end_time_p = '-' : $end_time_p = substr($r->end_time_p,0,5);

							($r->machine_plan == 'CORR1') ? $machine_plan = '1' : $machine_plan = '2' ;
							$html .= '<tr>
								<td style="padding:6px">'.$i.'</td>
								<td style="padding:6px;text-align:left">
									<a href="javascript:void(0)" onclick="showCartitem('."'".$r->id_plan."'".','."'riwayat'".')">
										['.$r->shift_plan.'.'.$machine_plan.'] '.strtoupper($this->m_fungsi->getHariIni($r->tgl_plan)).', '.strtoupper($this->m_fungsi->tanggal_format_indonesia($r->tgl_plan)).'	
									<a>
								</td>
								<td style="padding:6px;text-align:right">'.number_format($r->good_cor_p).'</td>
								<td style="padding:6px;text-align:right">'.number_format($r->bad_cor_p).'</td>
								<td style="padding:6px;text-align:right">'.number_format($r->total_cor_p).'</td>
								<td style="padding:6px;text-align:right">'.$txtDowtime.'</td>
								<td style="padding:6px">'.$start_time_p.'</td>
								<td style="padding:6px">'.$end_time_p.'</td>
							</tr>';
							$good_cor_p += $r->good_cor_p;
						}
						if($result->num_rows() > 1){
							$html .='<tr>
								<td style="border:0;padding:6px;background:#fff;font-weight:bold;text-align:right" colspan="2">TOTAL PRODUKSI</td>
								<td style="border:0;padding:6px;background:#fff;font-weight:bold;text-align:right">'.number_format($good_cor_p).'</td>
							</tr>';
						}
						$jmlGood = $good_cor_p - $result->row()->qty_so;
						$html .='<tr>
							<td style="border:0;padding:6px;background:#fff;font-weight:bold;text-align:right" colspan="2">QTY SO - ( '.number_format($result->row()->qty_so).' )</td>
							<td style="border:0;padding:6px;background:#fff;font-weight:bold;text-align:right">'.number_format($jmlGood).'</td>
						</tr>';
					$html .='</table>
				</div>
			</div>';
		}

		echo $html;
	}

	function loadInputList()
	{
		$urlTgl_plan = $_POST["tgl_plan"];
		$urlShift = $_POST["shift"];
		$urlMesin = $_POST["machine"];
		$id_plan = $_POST["hidplan"];
		$urlNoPlan = $_POST["urlNoPlan"];
		$opsi = $_POST["opsi"];
		$html = '';

		if($opsi != 'pilihan'){
			$tglPlan = $this->db->query("SELECT tgl_plan,p.shift_plan,p.machine_plan,
			(SELECT COUNT(lp.tgl_plan) FROM plan_cor lp
			WHERE p.tgl_plan=lp.tgl_plan AND p.shift_plan=lp.shift_plan AND p.machine_plan=lp.machine_plan GROUP BY lp.tgl_plan) AS jml_plan,
			(SELECT COUNT(lp.tgl_plan) FROM plan_cor lp
			WHERE p.tgl_plan=lp.tgl_plan AND p.shift_plan=lp.shift_plan AND p.machine_plan=lp.machine_plan AND lp.total_cor_p!='0' AND lp.status_plan='Open' GROUP BY lp.tgl_plan) AS prod_plan,
			(SELECT COUNT(lp.tgl_plan) FROM plan_cor lp
			WHERE p.tgl_plan=lp.tgl_plan AND p.shift_plan=lp.shift_plan AND p.machine_plan=lp.machine_plan AND lp.total_cor_p!='0' AND lp.status_plan='Close' GROUP BY lp.tgl_plan) AS selesai_plan,
			(SELECT COUNT(lp.tgl_plan) FROM plan_cor lp
			INNER JOIN trs_wo w ON lp.id_wo=w.id
			WHERE p.tgl_plan=lp.tgl_plan AND p.shift_plan=lp.shift_plan AND p.machine_plan=lp.machine_plan AND lp.total_cor_p!='0' AND lp.status_plan='Close' AND w.status='Close' GROUP BY lp.tgl_plan) AS wo_plan
			FROM plan_cor p
			INNER JOIN trs_wo ww ON p.id_wo=ww.id
			WHERE p.no_plan='$urlNoPlan'
			GROUP BY p.tgl_plan,p.shift_plan,p.machine_plan")->row();
			($tglPlan->machine_plan == 'CORR1') ? $mch = '1' : $mch = '2';
			($tglPlan->prod_plan == null) ? $prodPlan = '' : $prodPlan = '<span class="bg-success" style="vertical-align:top;font-weight:bold;padding:2px 4px;font-size:12px">'.$tglPlan->prod_plan.'</span>';
			($tglPlan->selesai_plan == null) ? $selesaiPlan = '' : $selesaiPlan = '<span class="bg-primary" style="vertical-align:top;font-weight:bold;padding:2px 4px;font-size:12px">'.$tglPlan->selesai_plan.'</span>';
			($tglPlan->wo_plan == null) ? $woPlan = '' : $woPlan = '<span class="bg-dark" style="vertical-align:top;font-weight:bold;padding:2px 4px;font-size:12px">'.$tglPlan->wo_plan.'</span>';
			$html .= '<div class="card card-primary card-outline">
			<div class="card-header">
				<h3 class="card-title" style="font-weight:bold;font-style:italic">LIST -</h3>&nbsp;
				<i>['.$urlShift.'.'.$mch.'] '.strtoupper($this->m_fungsi->getHariIni($urlTgl_plan)).', '.strtoupper($this->m_fungsi->tanggal_format_indonesia($urlTgl_plan)).'<span class="bg-light" style="vertical-align:top;font-weight:bold;padding:2px 4px;font-size:12px">'.$tglPlan->jml_plan.'</span>'.$prodPlan.''.$selesaiPlan.''.$woPlan.'</i>
			</div>';
		}

		if($opsi == 'pilihan'){
			$btnPindahHal = '<a href="'.base_url('Plan/Corrugator/List').'/'.$urlTgl_plan.'/'.$urlShift.'/'.$urlMesin.'" class="btn btn-xs bg-gradient-dark" style="padding:0 4px">
				<i class="fa fa-arrow-right"></i>
			</a>';
			$left1 = 0;
			$left2 = '40px';
		}else{
			$btnPindahHal = '#';
			$left1 = 0;
			$left2 = '30px';
		}
		$html .= '<div style="overflow:auto;white-space:nowrap">
			<table class="table table-bordered" style="border:0;text-align:center">
				<thead>
					<tr>
						<th style="padding:6px 12px;position:sticky;left:'.$left1.';background:#fff">'.$btnPindahHal.'</th>
						<th style="padding:6px">STATUS</th>
						<th style="padding:6px;position:sticky;left:'.$left2.';background:#fff">ITEM</th>
						<th style="padding:6px">KODE MC</th>
						<th style="padding:6px">NO.WO</th>
						<th style="padding:6px">CUSTOMER</th>
						<th style="padding:6px" colspan="2">TL/AL</th>
						<th style="padding:6px" colspan="2">B MF</th>
						<th style="padding:6px" colspan="2">BC</th>
						<th style="padding:6px" colspan="2">C.MF</th>
						<th style="padding:6px" colspan="2">B/C.L</th>
						<th style="padding:6px 22px">PJG</th>
						<th style="padding:6px 22px">LBR</th>
						<th style="padding:6px" colspan="3">SCORE</th>
						<th style="padding:6px">OUT</th>
						<th style="padding:6px">FT</th>
						<th style="padding:6px">L. ROLL</th>
						<th style="padding:6px">TRIM</th>
						<th style="padding:6px">ORDER</th>
						<th style="padding:6px">HASIL</th>
						<th style="padding:6px">START</th>
						<th style="padding:6px">END</th>
						<th style="padding:6px">C.OFF</th>
						<th style="padding:6px 22px">RM</th>
						<th style="padding:6px 22px">KG</th>
						<th style="padding:6px">TGL KIRIM</th>
						<th style="padding:6px">PLAN FLEXO</th>
						<th style="padding:6px 24px">NEXT</th>
						<th style="padding:6px">AKSI</th>
					</tr>
				</thead>';

		$data = $this->db->query("SELECT p.*,i.nm_produk,i.kode_mc,w.kode_po,l.nm_pelanggan,i.kategori,i.flute,w.flap1,w.creasing2,w.flap2,w.status AS statusWo FROM plan_cor p
		INNER JOIN m_produk i ON p.id_produk=i.id_produk
		INNER JOIN trs_wo w ON p.id_wo=w.id
		INNER JOIN trs_so_detail s ON p.id_so_detail=s.id
		INNER JOIN m_pelanggan l ON p.id_pelanggan=l.id_pelanggan
		WHERE p.tgl_plan='$urlTgl_plan' AND p.shift_plan='$urlShift' AND p.machine_plan='$urlMesin'
		GROUP BY p.id_plan
		ORDER BY p.no_urut_plan,l.nm_pelanggan,p.id_plan");

		foreach($data->result() as $r){
			$id = $r->id_plan;
			$exMatPlan = explode("/", $r->material_plan);
			$exKistPlan = explode("/", $r->kualitas_isi_plan);
			if($r->flute == "BF"){
				$dis2 = ''; $dis3 = 'disabled'; $dis4 = 'disabled';
				$vMatPlan1 = $exMatPlan[0];
				$vkisPlan1 = $exKistPlan[0];
				$vMatPlan2 = $exMatPlan[1];
				$vkisPlan2 = $exKistPlan[1];
				$vMatPlan3 = '';
				$vkisPlan3 = '';
				$vMatPlan4 = '';
				$vkisPlan4 = '';
				$vMatPlan5 = $exMatPlan[2];
				$vkisPlan5 = $exKistPlan[2];
			}else if($r->flute == "CF"){
				$dis2 = 'disabled'; $dis3 = 'disabled'; $dis4 = '';
				$vMatPlan1 = $exMatPlan[0];
				$vkisPlan1 = $exKistPlan[0];
				$vMatPlan2 = '';
				$vkisPlan2 = '';
				$vMatPlan3 = '';
				$vkisPlan3 = '';
				$vMatPlan4 = $exMatPlan[1];
				$vkisPlan4 = $exKistPlan[1];
				$vMatPlan5 = $exMatPlan[2];
				$vkisPlan5 = $exKistPlan[2];
			}else{
				$dis2 = ''; $dis3 = ''; $dis4 = '';
				$vMatPlan1 = $exMatPlan[0];
				$vkisPlan1 = $exKistPlan[0];
				$vMatPlan2 = $exMatPlan[1];
				$vkisPlan2 = $exKistPlan[1];
				$vMatPlan3 = $exMatPlan[2];
				$vkisPlan3 = $exKistPlan[2];
				$vMatPlan4 = $exMatPlan[3];
				$vkisPlan4 = $exKistPlan[3];
				$vMatPlan5 = $exMatPlan[4];
				$vkisPlan5 = $exKistPlan[4];
			}

			if($opsi == 'pilihan'){
				$onKeyUpEdiPlan = 'disabled';
				$ePlhS = 'disabled';
			}else{
				$onKeyUpEdiPlan = 'onkeyup="onChangeEditPlan('."'".$id."'".')"';
				$ePlhS = '';
			}

			if($id_plan == $r->id_plan){
				$bgTd = 'class="h-tlp-td"';
			}else{
				$bgTd = 'class="h-tlpf-td"';
			}

			$htmlSub ='<td '.$bgTd.' style="padding:6px">
				<select class="form-control inp-kosong" id="lp-sm1-'.$id.'" '.$ePlhS.'>
					<option value="'.$vMatPlan1.'">'.$vMatPlan1.'</option><option value="">-</option><option value="M">M</option><option value="K">K</option><option value="MC">MC</option><option value="MN">MN</option>
				</select></td>
			<td '.$bgTd.' style="padding:6px"><input type="number" id="lp-si1-'.$id.'" class="form-control inp-kosong" value="'.$vkisPlan1.'" '.$onKeyUpEdiPlan.'></td>
			<td '.$bgTd.' style="padding:6px">
				<select class="form-control inp-kosong" id="lp-sm2-'.$id.'" '.$dis2.' '.$ePlhS.'>
					<option value="'.$vMatPlan2.'">'.$vMatPlan2.'</option><option value="">-</option><option value="M">M</option><option value="K">K</option><option value="MC">MC</option><option value="MN">MN</option>
				</select></td>
			<td '.$bgTd.' style="padding:6px"><input type="number" id="lp-si2-'.$id.'" class="form-control inp-kosong" value="'.$vkisPlan2.'" '.$dis2.' '.$onKeyUpEdiPlan.'></td>
			<td '.$bgTd.' style="padding:6px">
				<select class="form-control inp-kosong" id="lp-sm3-'.$id.'" '.$dis3.' '.$ePlhS.'>
					<option value="'.$vMatPlan3.'">'.$vMatPlan3.'</option><option value="">-</option><option value="M">M</option><option value="K">K</option><option value="MC">MC</option><option value="MN">MN</option>
				</select></td>
			<td '.$bgTd.' style="padding:6px"><input type="number" id="lp-si3-'.$id.'" class="form-control inp-kosong" value="'.$vkisPlan3.'" '.$dis3.' '.$onKeyUpEdiPlan.'></td>
			<td '.$bgTd.' style="padding:6px">
				<select class="form-control inp-kosong" id="lp-sm4-'.$id.'" '.$dis4.' '.$ePlhS.'>
					<option value="'.$vMatPlan4.'">'.$vMatPlan4.'</option><option value="">-</option><option value="M">M</option><option value="K">K</option><option value="MC">MC</option><option value="MN">MN</option>
				</select></td>
			<td '.$bgTd.' style="padding:6px"><input type="number" id="lp-si4-'.$id.'" class="form-control inp-kosong" value="'.$vkisPlan4.'" '.$dis4.' '.$onKeyUpEdiPlan.'></td>
			<td '.$bgTd.' style="padding:6px">
				<select class="form-control inp-kosong" id="lp-sm5-'.$id.'" '.$ePlhS.'>
					<option value="'.$vMatPlan5.'">'.$vMatPlan5.'</option><option value="">-</option><option value="M">M</option><option value="K">K</option><option value="MC">MC</option><option value="MN">MN</option>
				</select></td>
			<td '.$bgTd.' style="padding:6px"><input type="number" id="lp-si5-'.$id.'" class="form-control inp-kosong" value="'.$vkisPlan5.'" '.$onKeyUpEdiPlan.'></td>';

			if(in_array($this->session->userdata('level'), ['Admin','PPIC','Corrugator','User'])){
				if($r->status_plan == 'Open' && $r->total_cor_p == 0){
					if($opsi == 'pilihan' || $this->session->userdata('level') == 'Corrugator'){
						$btnAksiHapus = '<span class="bg-danger" style="padding:2px 4px;border-radius:4px;display:block">HAPUS</span>';
						(in_array($this->session->userdata('level'), ['Admin','PPIC','User']) && $id_plan != 'add') ? $btnAksiEdit = '<a href="javascript:void(0)" style="font-weight:bold" onclick="editListPlan('."'".$r->id_plan."'".', '."'".$r->id_wo."'".','."'edit'".')">EDIT<a>' : $btnAksiEdit = '-';
						$aksiNoUrut = 'disabled';
						$dis = 'disabled';
					}else{
						$btnAksiHapus = '<a href="javascript:void(0)" onclick="hapusPlan('."".$r->id_plan."".')" href="" class="bg-danger" style="padding:2px 4px;border-radius:4px;display:block">HAPUS</a>';
						$btnAksiEdit = '<a href="javascript:void(0)" style="font-weight:bold" onclick="editListPlan('."'".$r->id_plan."'".', '."'".$r->id_wo."'".','."'edit'".')">EDIT<a>';
						$aksiNoUrut = 'onkeyup="onChangeNourutPlan('."'".$id."'".')"';
						$dis = '';
					}
				}else if($r->status_plan == 'Open' && $r->total_cor_p != 0){
					$btnAksiHapus = '<span class="bg-success" style="padding:2px 4px;border-radius:4px;display:block">PRODUKSI</span>';
					(in_array($this->session->userdata('level'), ['Admin','PPIC','User']) && $id_plan != 'add') ? $btnAksiEdit = '<a href="javascript:void(0)" style="font-weight:bold" onclick="editListPlan('."'".$r->id_plan."'".', '."'".$r->id_wo."'".','."'kirimnext'".')">EDIT<a>' : $btnAksiEdit = '-';
					$aksiNoUrut = 'disabled';
					$dis = 'disabled';
				}else if($r->status_plan == 'Close' && $r->statusWo == 'Open'){
					$btnAksiHapus = '<span class="bg-primary" style="padding:2px;border-radius:4px;display:block">SELESAI</span>';
					(in_array($this->session->userdata('level'), ['Admin','PPIC','User']) && $id_plan != 'add') ? $btnAksiEdit = '<a href="javascript:void(0)" style="font-weight:bold" onclick="editListPlan('."'".$r->id_plan."'".', '."'".$r->id_wo."'".','."'kirimnext'".')">EDIT<a>' : $btnAksiEdit = '-';
					$aksiNoUrut = 'disabled';
					$dis = 'disabled';
				}else if($r->status_plan == 'Close' && $r->statusWo == 'Close'){
					$btnAksiHapus = '<span class="bg-dark" style="padding:2px 4px;border-radius:4px;display:block">WO</span>';
					(in_array($this->session->userdata('level'), ['Admin','PPIC','User']) && $id_plan != 'add') ? $btnAksiEdit = '<a href="javascript:void(0)" style="font-weight:bold" onclick="editListPlan('."'".$r->id_plan."'".', '."'".$r->id_wo."'".','."'kirimnext'".')">EDIT<a>' : $btnAksiEdit = '-';
					$aksiNoUrut = 'disabled';
					$dis = 'disabled';
				}else{
					$btnAksiHapus = `-`;
					$btnAksiEdit = '-';
					$aksiNoUrut = 'disabled';
					$dis = 'disabled';
				}
			}else{
				$btnAksiHapus = '-';
				$btnAksiEdit = '-';
				$aksiNoUrut = 'disabled';
				$dis = 'disabled';
			}

			if($opsi == 'pilihan'){
				$btnLink = $r->nm_produk;
			}else{
				$btnLink = '<a href="javascript:void(0)" onclick="plhNoWo('."".$r->id_plan."".')" title="'."".$r->no_wo."".'">'.$r->nm_produk.'</a>';
			}

			if($r->kategori == 'K_BOX'){
				($r->status_plan == 'Open' && $r->total_cor_p != 0) ? $disNext = '' : $disNext = 'disabled'; 
				$next = '<select class="form-control inp-kosong2" id="lp-next-'.$id.'" '.$disNext.'>
					<option value="'.$r->next_plan.'">'.$r->next_plan.'</option>
					<option value="">-</option>
					<option value="FLEXO1">FLEXO1</option>
					<option value="FLEXO2">FLEXO2</option>
					<option value="FLEXO3">FLEXO3</option>
					<option value="FLEXO4">FLEXO4</option>
				</select>';
			}else{
				$next = $r->next_plan.'<input type="hidden" id="lp-next-'.$id.'" value="'.$r->next_plan.'">';
			}

			($r->start_time_p == null) ? $start_time_p = '-' : $start_time_p = 	substr($r->start_time_p, 0, 5);
			($r->end_time_p == null) ? $end_time_p = '-' : $end_time_p = substr($r->end_time_p, 0, 5);

			$getPF = $this->db->query("SELECT*FROM plan_flexo WHERE id_plan_cor='$r->id_plan'");
			if($getPF->num_rows() == 0){
				$tgl_flexo = '-';
			}else if($getPF->num_rows() == 1){
				$tgl_flexo = $this->m_fungsi->tglPlan($getPF->row()->tgl_flexo);
			}else{
				$tgl_flexo = $getPF->num_rows();
			}

			$html .= '<tr class="h-tmpl-list-plan">
				<td '.$bgTd.' style="position:sticky;left:'.$left1.';padding:6px 3px">
					<input type="number" class="form-control inp-kosong2" id="lp-nourut-'.$id.'" value="'.$r->no_urut_plan.'" '.$aksiNoUrut.' tabindex="1">
				</td>
				<td '.$bgTd.' style="padding:4px 3px 3px;font-weight:normal">'.$btnAksiHapus.'</td>
				<td '.$bgTd.' style="position:sticky;left:'.$left2.';padding:6px;text-align:left">'.$btnLink.'</td>
				<td '.$bgTd.' style="padding:6px;text-align:left">'.$r->kode_mc.'</td>
				<td '.$bgTd.' style="padding:6px;text-align:left">'.$r->no_wo.'</td>
				<td '.$bgTd.' style="padding:6px;text-align:left">'.$r->nm_pelanggan.'</td>
				'.$htmlSub.'
				<td '.$bgTd.' style="padding:6px">
					<input type="number" class="form-control inp-kosong2" id="lp-pjgs-'.$id.'" style="font-weight:bold;color:#ff0066" value="'.$r->panjang_plan.'" '.$onKeyUpEdiPlan.' disabled>
				</td>
				<td '.$bgTd.' style="padding:6px">
					<input type="number" class="form-control inp-kosong2" id="lp-lbrs-'.$id.'" style="font-weight:bold;color:#ff0066" value="'.$r->lebar_plan.'" '.$onKeyUpEdiPlan.' disabled>
				</td>
				<td '.$bgTd.' style="padding:6px" id="lptd-scr1-'.$id.'">
					<input type="number" class="form-control inp-kosong" id="lp-scr1-'.$id.'" value="'.$r->flap1.'" '.$onKeyUpEdiPlan.' disabled>
				</td>
				<td '.$bgTd.' style="padding:6px" id="lptd-scr2-'.$id.'">
					<input type="number" class="form-control inp-kosong" id="lp-scr2-'.$id.'" value="'.$r->creasing2.'" '.$onKeyUpEdiPlan.' disabled>
				</td>
				<td '.$bgTd.' style="padding:6px" id="lptd-scr3-'.$id.'">
					<input type="number" class="form-control inp-kosong" id="lp-scr3-'.$id.'" value="'.$r->flap2.'" '.$onKeyUpEdiPlan.' disabled>
				</td>
				<td '.$bgTd.' style="padding:6px">
					<input type="number" class="form-control inp-kosong2" id="lp-out-'.$id.'" value="'.$r->out_plan.'" '.$onKeyUpEdiPlan.' '.$dis.'>
				</td>
				<td '.$bgTd.' style="padding:6px">'.$r->flute.'</td>
				<td '.$bgTd.' style="padding:6px">
					<input type="number" class="form-control inp-kosong2" id="lp-lbri-'.$id.'" style="font-weight:bold;color:#000" value="'.$r->lebar_roll_p.'" '.$onKeyUpEdiPlan.' '.$dis.'>
				</td>
				<td '.$bgTd.' style="padding:6px;text-align:right">
					<input type="number" class="form-control inp-kosong2" id="lp-trimp-'.$id.'" value="'.$r->trim_plan.'" disabled>
				</td>
				<td '.$bgTd.' style="padding:6px;text-align:right">
					<input type="hidden" class="form-control inp-kosong2" id="lp-pcs-plan-'.$id.'" value="'.$r->pcs_plan.'">
					'.number_format($r->pcs_plan).'
				</td>
				<td '.$bgTd.' style="padding:6px;text-align:right">'.number_format($r->good_cor_p).'</td>
				<td '.$bgTd.' style="padding:6px;text-align:right">'.$start_time_p.'</td>
				<td '.$bgTd.' style="padding:6px;text-align:right">'.$end_time_p.'</td>
				<td '.$bgTd.' style="padding:6px;text-align:right">
					<input type="number" class="form-control inp-kosong2" id="lp-coffp-'.$id.'" value="'.$r->c_off_p.'" disabled>
				</td>
				<td '.$bgTd.' style="padding:6px;text-align:right">
					<input type="number" class="form-control inp-kosong2" id="lp-rmp-'.$id.'" value="'.$r->rm_plan.'" disabled>
				</td>
				<td '.$bgTd.' style="padding:6px;text-align:right">
					<input type="number" class="form-control inp-kosong2" id="lp-tonp-'.$id.'" value="'.$r->tonase_plan.'" disabled>
				</td>
				<td '.$bgTd.' style="padding:2px">
					<input type="date" class="form-control inp-kosong2" style="font-weight:bold;color:#f00" id="lp-tglkirim-'.$id.'" value="'.$r->tgl_kirim_plan.'" disabled>
				</td>
				<td '.$bgTd.' style="padding:6px">'.$tgl_flexo.'</td>
				<td '.$bgTd.' style="padding:6px">'.$next.'</td>
				<td '.$bgTd.' style="padding:6px">
					<input type="hidden" id="hlp-flute-'.$id.'" value="'.$r->flute.'">
					<input type="hidden" id="hlp-kategori-'.$id.'" value="'.$r->kategori.'">
					<input type="hidden" id="hlp-kua-isi-p-'.$id.'" value="'.$r->kualitas_isi_plan.'">
					'.$btnAksiEdit.'
				</td>
			</tr>';
		}

		$html .= '</table>
			</div>
		</div>';
		echo $html;
	}
	
	function btnGantiTglPlan()
	{
		$result = $this->m_plan->btnGantiTglPlan();
		echo json_encode($result);
	}

	function onChangeNourutPlan()
	{
		$result = $this->m_plan->onChangeNourutPlan();
		echo json_encode($result);
	}

	function editListPlan()
	{
		$result = $this->m_plan->editListPlan();
		echo json_encode($result);
	}

	function plhDowntime()
	{
		$id_plan = $_POST["id_plan"];
		$id_flexo = $_POST["id_flexo"];
		if($id_plan != ''){
			$cek = $this->db->query("SELECT*FROM plan_cor WHERE id_plan='$id_plan' AND status_plan='Close'");
		}else{
			$cek = $this->db->query("SELECT*FROM plan_flexo WHERE id_flexo='$id_flexo' AND status_flexo='Close'");
		}

		echo json_encode(array(
			'data' => $cek->num_rows()
		));
	}

	function downtime()
	{
		$html = '';
		$downtime = $_POST["downtime"];
		$id_plan = $_POST["id_plan"];
		$id_flexo = $_POST["id_flexo"];
		if($id_plan != ''){
			if($downtime == 'OP'){
				$where = "WHERE kode_h='C.01' AND kode_d LIKE 'OP%'";
			}else if($downtime == 'MT'){
				$where = "WHERE kode_h='C.02' AND kode_d LIKE 'MT%'";
			}else if($downtime == 'M'){
				$where = "WHERE kode_h='C.03' AND kode_d LIKE 'M%'";
			}else if($downtime == 'N'){
				$where = "WHERE kode_h='C.04' AND kode_d LIKE 'N%'";
			}
		}else{
			if($downtime == 'OP'){
				$where = "WHERE kode_h='F.01' AND kode_d LIKE 'OP%'";
			}else if($downtime == 'MT'){
				$where = "WHERE kode_h='F.02' AND kode_d LIKE 'MT%'";
			}else if($downtime == 'N'){
				$where = "WHERE kode_h='F.04' AND kode_d LIKE 'N%'";
			}
		}

		if($downtime == ""){
			$html .= '';
		}else{
			if($id_plan != ''){
				$cekPlan = $this->db->query("SELECT*FROM plan_cor WHERE id_plan='$id_plan' AND status_plan='Close'");
			}else{
				$cekPlan = $this->db->query("SELECT*FROM plan_flexo WHERE id_flexo='$id_flexo' AND status_flexo='Close'");
			}
			if($cekPlan->num_rows() == 1){
				$html .='<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
					<div class="col-md-2"></div>
					<div class="col-md-10" style="color:#f00;font-style:italic">PLAN SUDAH DICLOSE!</div>
				</div>';
			}else{
				$html .='<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
					<div class="col-md-2"></div>
					<div class="col-md-10" style="color:#f00;font-style:italic;font-size:12px">* KODE | KETERANGAN</div>
				</div>
				<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
					<div class="col-md-2"></div>
					<div class="col-md-10">
						<select class="form-control select2" id="dt-plh-downtime">
							<option value="">PILIH</option>';
							$data = $this->db->query("SELECT*FROM m_downtime $where ORDER BY kode_d");
							foreach($data->result() as $r){
								$html .='<option value="'.$r->id_downtime.'">'.$r->kode_d.' | '.$r->keterangan.'</option>';
							}
						$html .= '</select>
					</div>
				</div>
				<div class="card-body row" style="padding:0 20px 5px;font-weight:bold ">
					<div class="col-md-2 p-0">DURASI</div>
					<div class="col-md-2">
						<input type="number" id="dt-durasi" class="form-control" onkeyup="onKeyDTDurasi()">
					</div>
					<div class="col-md-8" style="font-style:italic;font-size:12px">MENIT</div>
				</div>
				<div class="card-body row" style="padding:0 20px 5px;font-weight:bold ">
					<div class="col-md-2 p-0">KETERANGAN</div>
					<div class="col-md-10">
						<textarea class="form-control" id="dt-keterangan" style="resize:none" rows="2"></textarea>
					</div>
				</div>
				<div class="card-body row" style="padding:0 20px 5px;font-weight:bold ">
					<div class="col-md-2"></div>
					<div class="col-md-10">
						<button class="btn btn-sm btn-success" style="font-weight:bold" onclick="simpanDowntime()"><i class="fas fa-plus"></i> ADD</button>
					</div>
				</div>';
			}
		}

		echo $html;
	}

	function simpanDowntime()
	{
		$result = $this->m_plan->simpanDowntime();
		echo json_encode($result);
	}

	function loadDataDowntime()
	{
		$id_plan = $_POST["id_plan"];
		$id_flexo = $_POST["id_flexo"];
		$html = '';

		$html .= '<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
			<div class="col-md-12" style="padding:0">
				<table class="table table-bordered" style="margin:20px 0 0;border:0">
					<thead>
						<tr>
							<th style="text-align:center">#</th>
							<th>KODE</th>
							<th>-</th>
							<th style="padding:12px 54px 12px 12px">KETERANGAN</th>
							<th style="text-align:center">(M)</th>
						</tr>
					</thead>';
					if($id_plan != ''){
						$data = $this->db->query("SELECT*FROM plan_cor_dt p
						INNER JOIN m_downtime d ON p.id_m_downtime=d.id_downtime
						WHERE p.id_plan_cor='$id_plan'");
					}else{
						$data = $this->db->query("SELECT*FROM plan_flexo_dt p
						INNER JOIN m_downtime d ON p.id_m_downtime=d.id_downtime
						WHERE p.id_plan_flexo='$id_flexo'");
					}

					if($data->num_rows() == 0){
						$html .= '<tr>
							<td style="padding:6px;text-align:center" colspan="5">DOWNTIME KOSONG</td>
						</tr>';
					}else{
						$i = 0;
						$sumMntDt = 0;
						foreach($data->result() as $r){
							$i++;
							$id = $r->id_plan_dt;

							if($id_plan != ''){
								$cekPlan = $this->db->query("SELECT*FROM plan_cor WHERE id_plan='$id_plan' AND status_plan='Close'");
							}else{
								$cekPlan = $this->db->query("SELECT*FROM plan_flexo WHERE id_flexo='$id_flexo' AND status_flexo='Close'");
							}
							if($cekPlan->num_rows() == 1){
								$onClickDt = 'style="color:#666;cursor:default" disabled';
								$onChangeDt = 'disabled';
							}else{
								$onClickDt = 'style="color:#f00" onclick="hapusDowntimePlan('."'".$id."'".')"';
								$onChangeDt = 'onchange="changeEditDt('."'".$id."'".')"';
							}
							$html .= '<tr class="h-tmpl-list-plan">
								<td style="padding:6px;text-align:center;vertical-align:middle">'.$i.'</td>
								<td style="padding:6px;text-align:center;vertical-align:middle">'.$r->kode_d.'</td>
								<td style="padding:6px;vertical-align:middle">'.$r->keterangan.'&nbsp
									<a href="javascript:void(0)" '.$onClickDt.'><i class="fas fa-times-circle"></i><a>
								</td>
								<td style="padding:3px">
									<textarea type="text" id="dt-ket-plan-'.$id.'" class="form-control inp-kosong2" style="text-align:left;resize:none;font-size:13px" '.$onChangeDt.'>'.$r->ket_plan_dt.'</textarea>
								</td>
								<td style="padding:6px;text-align:center;vertical-align:middle">
									<input type="number" id="dt-durasi-plan-'.$id.'" class="form-control inp-kosong" value="'.$r->durasi_mnt_dt.'" '.$onChangeDt.'>
								</td>
							</tr>';
							$sumMntDt += $r->durasi_mnt_dt;
						}

						if($data->num_rows() != 1){
							$html .='<tr>
								<td style="border:0;padding:6px;background:#fff;font-weight:bold;text-align:right" colspan="4">TOTAL DOWNTIME(M)</td>
								<td style="border:0;padding:6px;background:#fff;font-weight:bold;text-align:center">'.number_format($sumMntDt).'</td>
							</tr>';
						}
					}
				$html .= '</table>
			</div>
		</div>';

		echo $html;
	}

	function hapusDowntimePlan()
	{
		$result = $this->m_plan->hapusDowntimePlan();
		echo json_encode($result);
	}

	function changeEditDt()
	{
		$result = $this->m_plan->changeEditDt();
		echo json_encode($result);
	}

	function clickPlhTgl()
	{
		$tgl_plan = $_POST["tgl_plan"];
		$html = '';
		$html .= '<div class="card-body">
			'.$tgl_plan.'
		</div>';
		echo $html;
	}

	//

	function Flexo()
	{
		$data_header = array(
			'judul' => "Plan Flexo",
		);

		$this->load->view('header', $data_header);

		$jenis = $this->uri->segment(3);
		if($jenis == 'Add'){
			if(in_array($this->session->userdata('level'), ['Admin','PPIC','User'])){
				$this->load->view('Plan/Flexo/v_flexo_add');
			}else{
				$this->load->view('home');
			}
		}else if($jenis == 'List'){
			if(in_array($this->session->userdata('level'), ['Admin','PPIC','Flexo','User'])){
				$data = array(
					"tgl_flexo" => $this->uri->segment(4),
					"shift" => $this->uri->segment(5),
					"mesin" => $this->uri->segment(6),
				);
				$this->load->view('Plan/Flexo/v_flexo_plan', $data);
			}else{
				$this->load->view('home');
			}
		}else{
			if(in_array($this->session->userdata('level'), ['Admin','PPIC','Flexo','User'])){
				$this->load->view('Plan/Flexo/v_flexo');
			}else{
				$this->load->view('home');
			}
		}

		$this->load->view('footer');
	}

	function LoaDataFlexo()
	{
		$data = array();
		$query = $this->db->query("SELECT COUNT(id_flexo) AS jml,f.* FROM plan_flexo f GROUP BY tgl_flexo DESC,shift_flexo,mesin_flexo")->result();
		$i = 0;
		foreach ($query as $r) {
			$i++;
			$row = array();
			$row[] = '<div style="text-align:center">'.$i.'</div>';
			$row[] = strtoupper($this->m_fungsi->tanggal_format_indonesia($r->tgl_flexo));
			$row[] = '<div style="text-align:center">'.$r->shift_flexo.'</div>';
			$row[] = '<div style="text-align:center">'.$r->mesin_flexo.'</div>';
			$row[] = '<div style="text-align:center">'.$r->jml.'</div>';

			$link = base_url('Plan/Flexo/List/'.$r->tgl_flexo.'/'.$r->shift_flexo.'/'.$r->mesin_flexo);
			$printLapFlexo = base_url('Plan/laporanPlanFlexo?tgl='.$r->tgl_flexo.'&shift='.$r->shift_flexo.'&mesin='.$r->mesin_flexo.'');
			$printLap = base_url('Plan/laporanISOFlexo?tgl='.$r->tgl_flexo.'&shift='.$r->shift_flexo.'&mesin='.$r->mesin_flexo.'');
			if(in_array($this->session->userdata('level'), ['Admin','PPIC','User'])){
				$btnPrint = '
				<a href="'.$link.'" title="Edit"><button type="button" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></button></a>
				<a href="'.$printLapFlexo.'" target="_blank" class="btn btn-sm btn-success" title="CETAK PLAN"><i class="fas fa-print"></i></a>
				<a href="'.$printLap.'" target="_blank" class="btn btn-sm btn-primary" title="CETAK FLEXO"><i class="fas fa-print"></i></a>';
			}else if($this->session->userdata('level') == 'Flexo'){
				$btnPrint = '<a href="'.$link.'" title="Edit"><button type="button" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></button></a>';
			}else{
				$btnPrint = '';
			}
			$row[] = $btnPrint;

			$data[] = $row;
		}

		$output = array(
			"data" => $data,
		);
		echo json_encode($output);
	}

	function loadPlanCor()
	{
		$opsi = $_POST['opsi'];
		$result = $this->m_plan->loadPlanCor();
		if($opsi != ''){
			$getNoFlexo = $this->db->query("SELECT*FROM plan_flexo WHERE id_flexo='$opsi'")->row();
			$urutDtProd = $this->db->query("SELECT id_flexo,no_urut_flexo FROM plan_flexo WHERE tgl_flexo='$getNoFlexo->tgl_flexo' AND shift_flexo='$getNoFlexo->shift_flexo' AND mesin_flexo='$getNoFlexo->mesin_flexo'
			AND total_prod_flexo='0' AND no_urut_flexo!='0' ORDER BY no_urut_flexo ASC LIMIT 1")->row();
		}else{
			$getNoFlexo = false;
			$urutDtProd = false;
		}
		echo json_encode(array(
			'plan_cor' => $result,
			'opsi' => $opsi,
			'getNoFlexo' => $getNoFlexo,
			'urutDtProd' => $urutDtProd,
		));
	}

	function hapusPlanFlexo()
	{
		$result = $this->m_plan->hapusPlanFlexo();
		echo json_encode($result);
	}

	function btnGantiTglFlexo()
	{
		$result = $this->m_plan->btnGantiTglFlexo();
		echo json_encode($result);
	}

	function loadDataAllPlanCor()
	{
		$html = '';
		$allPlanCor = $this->db->query("SELECT COUNT(p.id_plan) AS jml_plan,c.nm_pelanggan,p.* FROM plan_cor p
		INNER JOIN m_pelanggan c ON p.id_pelanggan=c.id_pelanggan
		WHERE p.next_plan!='GUDANG' AND p.status_flexo_plan='Open'
		GROUP BY p.id_pelanggan
		ORDER BY c.nm_pelanggan");
		$html .='<div id="accordion">
			<div style="padding:6px;font-weight:bold">
				CUSTOMER <span class="bg-light" style="vertical-align:top;padding:2px 4px;font-size:12px">JUMLAH PLAN COR</span>
			</div>';
			foreach($allPlanCor->result() as $r){
				$html .='<div class="card m-0" style="border-radius:0">
					<div class="card-header bg-gradient-info" style="padding:0;border-radius:0">
						<a class="d-block w-100 link-h-wo" style="font-weight:bold;padding:6px" data-toggle="collapse" href="#collapseHeaderPlanCor'.$r->id_pelanggan.'" onclick="onClickHeaderPlanCor('."'".$r->id_pelanggan."'".')">
							'.$r->nm_pelanggan.' <span class="bg-light" style="vertical-align:top;font-weight:bold;padding:2px 4px;font-size:12px">'.$r->jml_plan.'</span>
						</a>
					</div>
					<div id="collapseHeaderPlanCor'.$r->id_pelanggan.'" class="collapse" data-parent="#accordion">
						<div id="tampil-all-flexo-isi-'.$r->id_pelanggan.'"></div>
					</div>
				</div>';
			}
		$html .='</div>';

		echo $html;
	}

	function loadDataAllPlanFlexo()
	{
		$urlTglF = $_POST["urlTglF"];
		$urlShiftF = $_POST["urlShiftF"];
		$urlMesinF = $_POST["urlMesinF"];
		$html = '';

		$allPlanFlexo = $this->db->query("SELECT f.tgl_flexo,f.shift_flexo,f.mesin_flexo,COUNT(f.id_flexo) AS jml_plan_flexo,
		(SELECT COUNT(o.id_flexo) FROM plan_flexo o WHERE o.tgl_flexo=f.tgl_flexo AND o.shift_flexo=f.shift_flexo AND o.mesin_flexo=f.mesin_flexo GROUP BY o.tgl_flexo,o.shift_flexo,o.mesin_flexo) AS jml_plan_flexo,
		(SELECT COUNT(o.id_flexo) FROM plan_flexo o WHERE o.tgl_flexo=f.tgl_flexo AND o.shift_flexo=f.shift_flexo AND o.mesin_flexo=f.mesin_flexo AND o.total_prod_flexo!='0' AND o.status_flexo='Open' GROUP BY o.tgl_flexo,o.shift_flexo,o.mesin_flexo) AS jml_prod,
		(SELECT COUNT(o.id_flexo) FROM plan_flexo o WHERE o.tgl_flexo=f.tgl_flexo AND o.shift_flexo=f.shift_flexo AND o.mesin_flexo=f.mesin_flexo AND o.total_prod_flexo!='0' AND o.status_flexo='Close' GROUP BY o.tgl_flexo,o.shift_flexo,o.mesin_flexo) AS done_prod,
		(SELECT COUNT(o.id_flexo) FROM plan_flexo o INNER JOIN plan_cor c ON o.id_plan_cor=c.id_plan WHERE o.tgl_flexo=f.tgl_flexo AND o.shift_flexo=f.shift_flexo AND o.mesin_flexo=f.mesin_flexo AND o.total_prod_flexo!='0' AND o.status_flexo='Close' AND c.status_flexo_plan='Close' GROUP BY o.tgl_flexo,o.shift_flexo,o.mesin_flexo) AS close_plan_cor
		FROM plan_flexo f
		INNER JOIN plan_cor cc ON f.id_plan_cor=cc.id_plan
		WHERE f.status_flexo='Open' OR cc.status_flexo_plan='Open'
		GROUP BY f.tgl_flexo,f.shift_flexo,f.mesin_flexo");
		$html .='<div id="accordion-h-plan-flexo">
			<div style="padding:6px;font-weight:bold">
				[SHIFT.MESIN] HARI, TANGGAL <span class="bg-light" style="vertical-align:top;padding:2px 4px;font-size:12px">JML PLAN FLEXO</span><span class="bg-success" style="vertical-align:top;padding:2px 4px;font-size:12px">PRODUKSI</span><span class="bg-primary" style="vertical-align:top;padding:2px 4px;font-size:12px">SELESAI</span><span class="bg-dark" style="vertical-align:top;padding:2px 4px;font-size:12px">CLOSE PLAN COR</span>
			</div>';
			foreach($allPlanFlexo->result() as $r){
				if($urlTglF == $r->tgl_flexo && $urlShiftF == $r->shift_flexo && $urlMesinF == $r->mesin_flexo){
					$html .='';
				}else{
					$sTgl = str_replace('-', '', $r->tgl_flexo);
					$mesin = str_replace('FLEXO', '', $r->mesin_flexo);

					$jml_plan = '<span class="bg-light" style="vertical-align:top;padding:2px 4px;font-size:12px">'.$r->jml_plan_flexo.'</span>';
					($r->jml_prod == null) ? $jml_prod = '' : $jml_prod = '<span class="bg-success" style="vertical-align:top;padding:2px 4px;font-size:12px">'.$r->jml_prod.'</span>';
					($r->done_prod == null) ? $done_prod = '' : $done_prod = '<span class="bg-primary" style="vertical-align:top;padding:2px 4px;font-size:12px">'.$r->done_prod.'</span>';
					($r->close_plan_cor == null) ? $close_plan_cor = '' : $close_plan_cor = '<span class="bg-dark" style="vertical-align:top;padding:2px 4px;font-size:12px">'.$r->close_plan_cor.'</span>';

					$html .='<div class="card m-0" style="border-radius:0">
						<div class="card-header bg-gradient-info" style="padding:0;border-radius:0">
							<a class="d-block w-100 link-h-wo" style="font-weight:bold;padding:6px" data-toggle="collapse" href="#collapseHeaderPlanFlexo'.$sTgl.''.$r->shift_flexo.''.$r->mesin_flexo.'" onclick="loadListPlanFlexo('."'".$r->tgl_flexo."'".','."'".$r->shift_flexo."'".','."'".$r->mesin_flexo."'".','."'pilihan'".')">
								['.$r->shift_flexo.'.'.$mesin.'] '.strtoupper($this->m_fungsi->getHariIni($r->tgl_flexo)).', '.strtoupper($this->m_fungsi->tanggal_format_indonesia($r->tgl_flexo)).' '.$jml_plan.''.$jml_prod.''.$done_prod.''.$close_plan_cor.'
							</a>
						</div>
						<div id="collapseHeaderPlanFlexo'.$sTgl.''.$r->shift_flexo.''.$r->mesin_flexo.'" class="collapse" data-parent="#accordion-h-plan-flexo">
							<div id="tampil-all-fflexo-isi-'.$sTgl.''.$r->shift_flexo.''.$r->mesin_flexo.'"></div>
						</div>
					</div>';
				}
			}
		$html .='</div>';

		echo $html;
	}

	function onClickHeaderPlanCor()
	{
		$id_pelanggan = $_POST["id_pelanggan"];
		$html = '';

		$data = $this->db->query("SELECT (SELECT COUNT(f.id_plan_cor) FROM plan_flexo f WHERE f.id_plan_cor=p.id_plan GROUP BY f.id_plan_cor) AS jml_flexo,
		(SELECT COUNT(f.id_plan_cor) FROM plan_flexo f WHERE f.id_plan_cor=p.id_plan AND f.total_prod_flexo!='0' AND f.status_flexo='Open' GROUP BY f.id_plan_cor) AS prod,
		(SELECT COUNT(f.id_plan_cor) FROM plan_flexo f WHERE f.id_plan_cor=p.id_plan AND f.total_prod_flexo!='0' AND f.status_flexo='Close' GROUP BY f.id_plan_cor) AS done,
		i.nm_produk,l.nm_pelanggan,p.* FROM plan_cor p
		INNER JOIN m_produk i ON p.id_produk=i.id_produk
		INNER JOIN m_pelanggan l ON p.id_pelanggan=l.id_pelanggan
		WHERE p.id_pelanggan='$id_pelanggan' AND p.status_flexo_plan='Open'
		ORDER BY p.tgl_plan,p.shift_plan,p.machine_plan");
		$html .='<div class="card-body" style="padding:6px">
			<div id="accordion-isi-plan">
			TGL PLAN | NO. WO | ITEM <span class="bg-dark" style="vertical-align:top;font-weight:bold;padding:2px 4px;font-size:12px">HASIL COR</span><span class="bg-light" style="vertical-align:top;font-weight:bold;padding:2px 4px;font-size:12px">JML PLAN FLEXO</span><span class="bg-success" style="vertical-align:top;font-weight:bold;padding:2px 4px;font-size:12px">PRODUKSI</span><span class="bg-primary" style="vertical-align:top;font-weight:bold;padding:2px 4px;font-size:12px">SELESAI</span>';
				foreach($data->result() as $r){
					($r->jml_flexo == null) ? $jml_flexo = 0 : $jml_flexo = $r->jml_flexo;
					($r->prod == null) ? $prod = '' : $prod = '<span class="bg-success" style="vertical-align:top;font-weight:bold;padding:2px 4px;font-size:12px">'.$r->prod.'</span>';
					($r->done == null) ? $done = '' : $done = '<span class="bg-primary" style="vertical-align:top;font-weight:bold;padding:2px 4px;font-size:12px">'.$r->done.'</span>';
					$hariIni = substr($this->m_fungsi->getHariIni($r->tgl_plan),0,3);

					$html .='<div class="card m-0" style="border-radius:0">
						<div class="card-header" style="padding:0;border-radius:0">
							<a class="d-block w-100 link-i-wo" style="font-weight:bold;padding:6px" data-toggle="collapse" href="#collapseHeaderIsiPlanCor'.$r->id_plan.'-'.$r->id_pelanggan.'" onclick="onclickHeaderIsiPlanCor('."'".$r->id_plan."'".','."'".$r->id_pelanggan."'".')">
							'.strtoupper($hariIni).', '.strtoupper($this->m_fungsi->tglIndSkt($r->tgl_plan)).' | '.$r->no_wo.' | '.$r->nm_produk.' <span class="bg-dark" style="vertical-align:top;font-weight:bold;padding:2px 4px;font-size:12px">'.number_format($r->good_cor_p,0,',','.').'</span><span class="bg-light" style="vertical-align:top;font-weight:bold;padding:2px 4px;font-size:12px">'.$jml_flexo.'</span>'.$prod.''.$done.'
							</a>
						</div>
						<div id="collapseHeaderIsiPlanCor'.$r->id_plan.'-'.$r->id_pelanggan.'" class="collapse" data-parent="#accordion-isi-plan">
							<div id="tampil-all-pplan-isi-'.$r->id_plan.'-'.$r->id_pelanggan.'"></div>
						</div>
					</div>';
				}
			$html .='</div>
		</div>';

		echo $html;
	}

	function onclickHeaderIsiPlanCor()
	{
		$id_plan = $_POST["id_plan"];
		$id_pelanggan = $_POST["id_pelanggan"];
		$html = '';

		$data = $this->db->query("SELECT i.*,f.*,c.* FROM plan_flexo f
		INNER JOIN plan_cor c ON f.id_plan_cor=c.id_plan
		INNER JOIN m_produk i ON c.id_produk=i.id_produk
		WHERE c.id_pelanggan='$id_pelanggan' AND c.id_plan='$id_plan'
		ORDER BY tgl_flexo,shift_flexo,mesin_flexo");

		if($data->num_rows() == 0){
			$html .='';
		}else{
			$html .= '<div style="overflow:auto;white-space:nowrap;padding-bottom:5px">
				<table class="table table-bordered" style="text-align:center;border:0">
					<thead>
						<tr>
							<th style="padding:6px;position:sticky;left:0;background:#fff">#</th>
							<th style="padding:6px">TGL FLEXO</th>
							<th style="padding:6px">KODE.MC</th>
							<th style="padding:6px">KUALITAS</th>
							<th style="padding:6px">PANJANG</th>
							<th style="padding:6px">LEBAR</th>
							<th style="padding:6px">BB</th>
							<th style="padding:6px">FT</th>
							<th style="padding:6px">JOINT</th>
							<th style="padding:6px">ORDER</th>
							<th style="padding:6px">TGL KIRIM</th>
							<th style="padding:6px">KL.CR TGL</th>
							<th style="padding:6px">KL.CR QTY</th>
							<th style="padding:6px">HASIL</th>
							<th style="padding:6px">R. FLEXO</th>
							<th style="padding:6px">R. BAHAN</th>
							<th style="padding:6px">DOWNTIME</th>
							<th style="padding:6px">KETERANGAN</th>
							<th style="padding:6px">NEXT FINISHING</th>
						</tr>
					</thead>';
					$sumGood = 0;
					foreach($data->result() as $r){
						if($r->sambungan == 'G'){
							$sambungan = 'GLUE';
						}else if($r->sambungan == 'D'){
							$sambungan = 'DIECUT';
						}else if($r->sambungan == 'S'){
							$sambungan = 'STICHING';
						}else if($r->sambungan == 'GS'){
							$sambungan = 'GLUE STICHING';
						}else if($r->sambungan == 'DS'){
							$sambungan = 'DOUBLE STICHING';
						}else{
							$sambungan = '-';
						}
						($r->tgl_prod_p == null) ? $tgl_prod_p = '-' : $tgl_prod_p = $this->m_fungsi->tglPlan($r->tgl_prod_p);
						($r->good_cor_p == 0) ? $good_cor_p = '-' : $good_cor_p = number_format($r->good_cor_p,0,',','.');

						$getDt = $this->db->query("SELECT COUNT(id_plan_flexo) AS jml_dt,SUM(durasi_mnt_dt) AS durasi_dt FROM plan_flexo_dt WHERE id_plan_flexo='$r->id_flexo'
						GROUP BY id_plan_flexo");
						($getDt->num_rows() == 0) ? $jml_dt = '-' : $jml_dt = $getDt->row()->jml_dt;
						($getDt->num_rows() == 0) ? $durasi_dt = '' : $durasi_dt = ' ( '.$getDt->row()->durasi_dt.'" ) ';

						if($r->total_prod_flexo != 0 && $r->status_flexo == 'Open'){
							$borBot = ';border-bottom:1px solid #28a746';
						}else if($r->total_prod_flexo != 0 && $r->status_flexo == 'Close'){
							$borBot = ';border-bottom:1px solid #007bff';
						}else{
							$borBot = '';
						}

						$mesin = str_replace('FLEXO', '', $r->mesin_flexo);
						$hariIni = substr($this->m_fungsi->getHariIni($r->tgl_flexo),0,3);
						
						$html .='<tr class="h-tmpl-list-plan">
							<td style="padding:6px'.$borBot.';position:sticky;left:0;background:#fff">
								<a href="'.base_url('Plan/Flexo/List/').$r->tgl_flexo.'/'.$r->shift_flexo.'/'.$r->mesin_flexo.'" class="btn btn-xs bg-gradient-dark" style="padding:0 4px">
								<i class="fa fa-arrow-right"></i>
							</a>
							</td>
							<td style="padding:6px'.$borBot.'">['.$r->shift_flexo.'.'.$mesin.'] '.strtoupper($hariIni).', '.strtoupper($this->m_fungsi->tglIndSkt($r->tgl_flexo)).'</td>
							<td style="padding:6px'.$borBot.'">'.$r->kode_mc.'</td>
							<td style="padding:6px'.$borBot.'">'.$r->kualitas.'</td>
							<td style="padding:6px'.$borBot.';font-weight:bold;color:#ff0066">'.number_format($r->panjang_plan,0,',','.').'</td>
							<td style="padding:6px'.$borBot.';font-weight:bold;color:#ff0066">'.number_format($r->lebar_plan,0,',','.').'</td>
							<td style="padding:6px'.$borBot.'">'.$r->berat_bersih.'</td>
							<td style="padding:6px'.$borBot.'">'.$r->flute.'</td>
							<td style="padding:6px'.$borBot.'">'.$sambungan.'</td>
							<td style="padding:6px'.$borBot.';font-weight:bold">'.number_format($r->pcs_plan,0,',','.').'</td>
							<td style="padding:6px'.$borBot.';color:#f00">'.$this->m_fungsi->tglPlan($r->tgl_kirim_plan).'</td>
							<td style="padding:6px'.$borBot.'">'.$tgl_prod_p.'</td>
							<td style="padding:6px'.$borBot.'">'.$good_cor_p.'</td>
							<td style="padding:6px'.$borBot.'">'.number_format($r->good_flexo_p,0,',','.').'</td>
							<td style="padding:6px'.$borBot.'">'.number_format($r->bad_flexo_p,0,',','.').'</td>
							<td style="padding:6px'.$borBot.'">'.number_format($r->bad_bahan_f_p,0,',','.').'</td>
							<td style="padding:6px'.$borBot.'">'.$jml_dt.''.$durasi_dt.'</td>
							<td style="padding:6px'.$borBot.'">'.$r->ket_flexo_p.'</td>
							<td style="padding:6px'.$borBot.'">'.$r->next_flexo.'</td>
						</tr>';
						$sumGood += $r->good_flexo_p;
					}

					if($data->num_rows() > 1){
						$html .='<tr>
							<td style="border:0;background:#fff;padding:6px;font-weight:bold;text-align:right" colspan="13">TOTAL PRODUKSI</td>
							<td style="border:0;background:#fff;padding:6px;font-weight:bold;text-align:right">'.number_format($sumGood,0,",",".").'</td>
						</tr>';
					}

					$html .='
				</table>
			</div>';
		}


		echo $html;
	}

	function destroyPlanFlexo()
	{
		$this->cart->destroy();
	}

	function hapusCartFlexo()
	{
		$data = array(
			'rowid' => $_POST['rowid'],
			'qty' => 0,
		);
		$this->cart->update($data);
	}

	function addRencanaFlexo()
	{
		$data = array(
			'id' => $_POST["plan_cor"],
			'name' => $_POST["plan_cor"],
			'price' => 0,
			'qty' => 1,
			'options' => array(
				'tgl' => $_POST["tgl"],
				'shift' => $_POST["shift"],
				'mesin' => $_POST["mesin"],
				'no_wo' => $_POST["no_wo"],
				'no_po' => $_POST["no_po"],
				'customer' => $_POST["customer"],
				'kode_mc' => $_POST["kode_mc"],
				'item' => $_POST["item"],
				'uk_box' => $_POST["uk_box"],
				'uk_sheet' => $_POST["uk_sheet"],
				'creasing_1' => $_POST["creasing_1"],
				'creasing_2' => $_POST["creasing_2"],
				'creasing_3' => $_POST["creasing_3"],
				'kualitas' => $_POST["kualitas"],
				'flute' => $_POST["flute"],
				'tipe_box' => $_POST["tipe_box"],
				'sambungan' => $_POST["sambungan"],
				'bb_box' => $_POST["bb_box"],
				'lb_box' => $_POST["lb_box"],
				'panjang_plan' => $_POST["panjang_plan"],
				'lebar_plan' => $_POST["lebar_plan"],
				'order_so' => $_POST["order_so"],
				'kirim' => $_POST["kirim"],
				'tgl_cor' => $_POST["tgl_cor"],
				'qty_cor' => $_POST["qty_cor"],
				'next_flexo' => $_POST["next_flexo"],
			)
		);

		if($_POST["opsi"] == 'add'){
			$id_plan_cor = $_POST["plan_cor"];
			$tgl = $_POST["tgl"];
			$mesin = $_POST["mesin"];
			$shift = $_POST["shift"];
			$cekPlanCor = $this->db->query("SELECT*FROM plan_cor WHERE id_plan='$id_plan_cor'")->row();
			$cekHariPlan = $this->db->query("SELECT*FROM plan_flexo WHERE id_plan_cor='$id_plan_cor' AND tgl_flexo='$tgl' AND shift_flexo='$shift' AND mesin_flexo='$mesin'")->num_rows();
			if($tgl < $cekPlanCor->tgl_plan){
				echo json_encode(array('data' => false, 'isi' => 'TGL PLAN FLEXO TIDAK BOLEH KURANG DARI TANGGAL PLAN COR!', 'cekPlanCor' => $cekPlanCor)); return;
			}else if($cekHariPlan > 0){
				echo json_encode(array('data' => false, 'isi' => 'PLAN FLEXO SUDAH ADA DI TGL / SHIFT / MESIN YANG SAMA!', 'cekHariPlan' => $cekHariPlan)); return;
			}else{
				if($id_plan_cor == ""){
					echo json_encode(array('data' => false, 'isi' => '<b>PILIH PLAN COR DAHULU!</b>')); return;
				}else if($_POST["next_flexo"] == ""){
					echo json_encode(array('data' => false, 'isi' => '<b>PILIH NEXT FLEXO DAHULU!</b>!')); return;
				}else if($this->cart->total_items() != 0){
					foreach($this->cart->contents() as $r){
						if($r['id'] == $_POST["plan_cor"]){
							echo json_encode(array('data' => false, 'isi' => 'PLAN COR SUDAH MASUK LIST PLAN FLEXO!')); return;
						}
					}
					$this->cart->insert($data);
					echo json_encode(array('data' => true, 'isi' => $data));
				}else{
					$this->cart->insert($data);
					echo json_encode(array('data' => true, 'isi' => $data));
				}
			}
		}else{
			$result = $this->m_plan->addRencanaFlexo();
			echo json_encode($result);
		}
	}

	function ListInputFlexo()
	{
		$html = '';
		if($this->cart->total_items() != 0){
			$html .= '<div class="card card-success card-outline">
				<div class="card-header">
					<h3 class="card-title" style="font-weight:bold;font-style:italic">LIST PLAN FLEXO BARU</h3>
				</div>
				<div style="overflow:auto;white-space:nowrap;padding-bottom:15px">
					<table class="table table-bordered table-striped" style="text-align:center">
						<thead>
							<tr>
								<th style="padding:12px 6px">#</th>
								<th style="padding:12px 6px">KODE.MC</th>
								<th style="padding:12px 6px">NO.WO</th>
								<th style="padding:12px 6px">CUSTOMER</th>
								<th style="padding:12px 6px">ITEM</th>
								<th style="padding:12px 6px">KUALITAS</th>
								<th style="padding:12px 6px">PANJANG</th>
								<th style="padding:12px 6px">LEBAR</th>
								<th style="padding:12px 6px">BB</th>
								<th style="padding:12px 6px">FT</th>
								<th style="padding:12px 6px">JOINT</th>
								<th style="padding:12px 6px">ORDER</th>
								<th style="padding:12px 6px">TGL KIRIM</th>
								<th style="padding:12px 6px">KL.CR TGL</th>
								<th style="padding:12px 6px">KL.CR QTY</th>
								<th style="padding:12px 6px">TONASE</th>
								<th style="padding:12px 6px">NEXT FLEXO</th>
								<th style="padding:12px 6px">AKSI</th>
							</tr>
						</thead>';}
						$i = 0;
						foreach($this->cart->contents() as $r){
							$i++;
							($r['options']['tgl_cor'] != "") ? $tglHasil = $this->m_fungsi->tglPlan($r['options']['tgl_cor']) : $tglHasil = '-';
							($r['options']['qty_cor'] > 0) ? $hasil = number_format($r['options']['qty_cor'],0,',','.') : $hasil = 0;
							($r['options']['qty_cor'] > 0) ? $ton = number_format($r['options']['bb_box'] * $r['options']['qty_cor'],0,',','.') : $ton = 0 ;
							$html.='<tr>
								<td style="padding:6px">'.$i.'</td>
								<td style="padding:6px;text-align:left">'.$r['options']['kode_mc'].'</td>
								<td style="padding:6px;text-align:left">'.$r['options']['no_wo'].'</td>
								<td style="padding:6px;text-align:left">'.$r['options']['customer'].'</td>
								<td style="padding:6px;text-align:left">'.$r['options']['item'].'</td>
								<td style="padding:6px">'.$r['options']['kualitas'].'</td>
								<td style="padding:6px">'.$r['options']['panjang_plan'].'</td>
								<td style="padding:6px">'.$r['options']['lebar_plan'].'</td>
								<td style="padding:6px">'.$r['options']['bb_box'].'</td>
								<td style="padding:6px">'.$r['options']['flute'].'</td>
								<td style="padding:6px">'.$r['options']['sambungan'].'</td>
								<td style="padding:6px">'.number_format($r['options']['order_so'],0,',','.').'</td>
								<td style="padding:6px">'.$this->m_fungsi->tglPlan($r['options']['kirim']).'</td>
								<td style="padding:6px">'.$tglHasil.'</td>
								<td style="padding:6px">'.$hasil.'</td>
								<td style="padding:6px">'.$ton.'</td>
								<td style="padding:6px;text-align:left">'.$r['options']['next_flexo'].'</td>
								<td style="padding:3px 6px">
									<button class="btn btn-xs btn-danger" onclick="hapusCartFlexo('."'".$r['rowid']."'".')"><i class="fas fa-times"></i> BATAL</button>
								</td>
							</tr>';
						}
						if($this->cart->total_items() != 0){
						$html .='
					</table>
					<button class="btn btn-sm btn-primary" id="simpan-cart-fx" style="margin-left:20px" onclick="simpanCartFlexo()"><i class="fas fa-save"></i> SIMPAN</button>
				</div>
			</div>';
		}
		echo $html;
	}

	function simpanCartFlexo()
	{
		$result = $this->m_plan->simpanCartFlexo();
		echo json_encode($result);
	}

	function loadDataPlanFlexo()
	{
		$result = $this->m_plan->loadDataPlanFlexo();
		echo json_encode($result);
	}

	function produksiPlanFlexo()
	{
		$result = $this->m_plan->produksiPlanFlexo();
		echo json_encode($result);
	}

	function onChangeNourutFlexo()
	{
		$result = $this->m_plan->onChangeNourutFlexo();
		echo json_encode($result);
	}

	function clickDonePlanCorFlexo()
	{
		$result = $this->m_plan->clickDonePlanCorFlexo();
		echo json_encode($result);
	}

	function loadListPlanFlexo()
	{ //
		$urlTglF = $_POST["tglF"];
		$urlShiftF = $_POST["shiftF"];
		$urlMesinF = $_POST["mesinF"];
		$id_flexo = $_POST["opsi"];
		$html = '';

		if($id_flexo != 'pilihan'){
			$ff = $this->db->query("SELECT f.tgl_flexo,f.shift_flexo,f.mesin_flexo,
			(SELECT COUNT(o.id_flexo) FROM plan_flexo o WHERE o.tgl_flexo=f.tgl_flexo AND o.shift_flexo=f.shift_flexo AND o.mesin_flexo=f.mesin_flexo GROUP BY o.tgl_flexo,o.shift_flexo,o.mesin_flexo) AS jml_plan_flexo,
			(SELECT COUNT(o.id_flexo) FROM plan_flexo o WHERE o.tgl_flexo=f.tgl_flexo AND o.shift_flexo=f.shift_flexo AND o.mesin_flexo=f.mesin_flexo AND o.total_prod_flexo!='0' AND o.status_flexo='Open' GROUP BY o.tgl_flexo,o.shift_flexo,o.mesin_flexo) AS jml_prod,
			(SELECT COUNT(o.id_flexo) FROM plan_flexo o WHERE o.tgl_flexo=f.tgl_flexo AND o.shift_flexo=f.shift_flexo AND o.mesin_flexo=f.mesin_flexo AND o.total_prod_flexo!='0' AND o.status_flexo='Close' GROUP BY o.tgl_flexo,o.shift_flexo,o.mesin_flexo) AS done_prod,
			(SELECT COUNT(o.id_flexo) FROM plan_flexo o INNER JOIN plan_cor c ON o.id_plan_cor=c.id_plan WHERE o.tgl_flexo=f.tgl_flexo AND o.shift_flexo=f.shift_flexo AND o.mesin_flexo=f.mesin_flexo AND o.total_prod_flexo!='0' AND o.status_flexo='Close' AND c.status_flexo_plan='Close' GROUP BY o.tgl_flexo,o.shift_flexo,o.mesin_flexo) AS close_plan_cor
			FROM plan_flexo f
			WHERE f.tgl_flexo='$urlTglF' AND f.shift_flexo='$urlShiftF' AND f.mesin_flexo='$urlMesinF'
			GROUP BY f.tgl_flexo,f.shift_flexo,f.mesin_flexo")->row();

			$jml_plan = '<span class="bg-light" style="font-weight:bold;vertical-align:top;padding:2px 4px;font-size:12px">'.$ff->jml_plan_flexo.'</span>';
			($ff->jml_prod == null) ? $jml_prod = '' : $jml_prod = '<span class="bg-success" style="font-weight:bold;vertical-align:top;padding:2px 4px;font-size:12px">'.$ff->jml_prod.'</span>';
			($ff->done_prod == null) ? $done_prod = '' : $done_prod = '<span class="bg-primary" style="font-weight:bold;vertical-align:top;padding:2px 4px;font-size:12px">'.$ff->done_prod.'</span>';
			($ff->close_plan_cor == null) ? $close_plan_cor = '' : $close_plan_cor = '<span class="bg-dark" style="font-weight:bold;vertical-align:top;padding:2px 4px;font-size:12px">'.$ff->close_plan_cor.'</span>';
			$mesin = str_replace('FLEXO', '', $ff->mesin_flexo);

			$html .= '<div class="card card-primary card-outline">
			<div class="card-header">
				<h3 class="card-title" style="font-weight:bold;font-style:italic">LIST PLAN FLEXO - </h3>
				&nbsp;<i>['.$ff->shift_flexo.'.'.$mesin.'] '.strtoupper($this->m_fungsi->getHariIni($ff->tgl_flexo)).', '.strtoupper($this->m_fungsi->tanggal_format_indonesia($ff->tgl_flexo)).' '.$jml_plan.''.$jml_prod.''.$done_prod.''.$close_plan_cor.'</i>
			</div>';
		}

		if($id_flexo == 'pilihan'){
			$keHal = '<a href="'.base_url('Plan/Flexo/List').'/'.$urlTglF.'/'.$urlShiftF.'/'.$urlMesinF.'" class="btn btn-xs bg-gradient-dark" style="padding:0 4px">
				<i class="fa fa-arrow-right"></i>
			</a>';
			$left = ';left:40px';
		}else{
			$keHal = '#';
			$left = ';left:32px';
		}
		
		$html .= '<div style="overflow:auto;white-space:nowrap;padding-bottom:5px">
				<table class="table table-bordered" style="text-align:center">
					<thead>
						<tr>
							<th style="padding:6px 12px;background:#fff;position:sticky;left:0">'.$keHal.'</th>
							<th style="padding:6px">STATUS</th>
							<th style="padding:6px">KODE.MC</th>
							<th style="padding:6px;background:#fff;position:sticky'.$left.'">NO.WO</th>
							<th style="padding:6px">CUSTOMER</th>
							<th style="padding:6px">ITEM</th>
							<th style="padding:6px">KUALITAS</th>
							<th style="padding:6px">PANJANG</th>
							<th style="padding:6px">LEBAR</th>
							<th style="padding:6px">BB</th>
							<th style="padding:6px">FT</th>
							<th style="padding:6px">JOINT</th>
							<th style="padding:6px">ORDER</th>
							<th style="padding:6px">HASIL</th>
							<th style="padding:6px">START</th>
							<th style="padding:6px 12px">END</th>
							<th style="padding:6px">TGL KIRIM</th>
							<th style="padding:6px">KL.CR TGL</th>
							<th style="padding:6px">KL.CR QTY</th>
							<th style="padding:6px">TONASE</th>
							<th style="padding:6px">NEXT FINISHING</th>
							<th style="padding:6px">AKSI</th>
						</tr>
					</thead>';

					$data = $this->db->query("SELECT f.*,i.*,pc.*,so.qty_so,c.nm_pelanggan FROM plan_flexo f
					INNER JOIN plan_cor pc ON f.id_plan_cor=pc.id_plan
					INNER JOIN trs_so_detail so ON pc.id_so_detail=so.id
					INNER JOIN m_produk i ON pc.id_produk=i.id_produk
					INNER JOIN m_pelanggan c ON pc.id_pelanggan=c.id_pelanggan
					WHERE f.tgl_flexo='$urlTglF' AND f.shift_flexo='$urlShiftF' AND f.mesin_flexo='$urlMesinF'
					ORDER BY f.no_urut_flexo,c.nm_pelanggan,f.id_flexo");
					foreach($data->result() as $r){
						if($_POST["hidplan"] == $r->id_flexo){
							$bgTd = 'class="h-tlp-td"';
						}else{
							$bgTd = 'class="h-tlpf-td"';
						}

						if($r->sambungan == 'G'){
							$sambungan = 'GLUE';
						}else if($r->sambungan == 'D'){
							$sambungan = 'DIECUT';
						}else if($r->sambungan == 'S'){
							$sambungan = 'STICHING';
						}else if($r->sambungan == 'GS'){
							$sambungan = 'GLUE STICHING';
						}else if($r->sambungan == 'DS'){
							$sambungan = 'DOUBLE STICHING';
						}else{
							$sambungan = '-';
						}

						if($r->tgl_prod_p != "" && $r->good_cor_p != 0){
							$tgl_prod_p = $this->m_fungsi->tglPlan($r->tgl_prod_p);
							$good_cor_p = number_format($r->good_cor_p,0,",",".");
							$ton = number_format($r->berat_bersih * $r->good_cor_p,0,",",".");
						}else{
							$tgl_prod_p = '-';
							$good_cor_p = '-';
							$ton = '-';
						}

						if($r->status_flexo == 'Open' && $r->total_prod_flexo == 0){
							if($id_flexo == 'pilihan'){
								$statusF = '<span class="bg-danger" style="padding:2px 4px;border-radius:4px;display:block">HAPUS</span>';
							}else{
								if(in_array($this->session->userdata('level'), ['Admin','PPIC','User'])){
									$statusF = '<a href="javascript:void(0)" onclick="hapusPlanFlexo('."".$r->id_flexo."".')" href="" class="bg-danger" style="padding:2px 4px;border-radius:4px;display:block">HAPUS</a>';
								}else{
									$statusF = '<span class="bg-danger" style="padding:2px 4px;border-radius:4px;display:block">HAPUS</span>';
								}
							}
						}else if($r->status_flexo == 'Open' && $r->total_prod_flexo != 0){
							$statusF = '<span class="bg-success" style="padding:2px 4px;border-radius:4px;display:block">PRODUKSI</span>';
						}else if($r->status_flexo == 'Close' && $r->status_flexo_plan == 'Open' && $r->total_prod_flexo != 0){
							$statusF = '<span class="bg-primary" style="padding:2px 4px;border-radius:4px;display:block">SELESAI</span>';
						}else{
							$statusF = '<span class="bg-dark" style="padding:2px 4px;border-radius:4px;display:block">SELESAI</span>';
						}

						($id_flexo == 'pilihan') ? $plhPlanCor = $r->no_wo : $plhPlanCor = '<a href="javascript:void(0)" onclick="plhPlanCor('."".$r->id_flexo."".')" title="'."".$r->no_wo."".'">'.$r->no_wo.'</a>';

						if($id_flexo == 'pilihan'){
							$ubahNoUrut = 'disabled';
						}else{
							if(in_array($this->session->userdata('level'), ['Admin','PPIC','User'])){
								($r->total_prod_flexo != 0) ? $ubahNoUrut = 'disabled' : $ubahNoUrut = 'onkeyup="onChangeNourutFlexo('."'".$r->id_flexo."'".')"';
							}else{
								$ubahNoUrut = 'disabled';
							}
						}

						if($r->total_prod_flexo > 0){
							$hasilF = number_format($r->good_flexo_p,0,',','.');
							$startF = substr($r->start_time_f,0,5);
							$endF = substr($r->end_time_f,0,5);
						}else{
							$hasilF = '-';
							$startF = '-';
							$endF = '-';
						}

						if($id_flexo == 'pilihan'){
							$btnEdit = '-';
							$optNextFlexo = $r->next_flexo;
						}else{
							if($r->status_flexo == 'Open'){
								if(in_array($this->session->userdata('level'), ['Admin','PPIC','User'])){
									$btnEdit = '<a href="javascript:void(0)" style="font-weight:bold" onclick="editPlanFlexo('."".$r->id_flexo."".')" title="EDIT">EDIT</a>';
									$disOptNF = '';
								}else{
									$btnEdit = '-';
									$disOptNF = 'disabled';
								}
							}else{
								$btnEdit = '-';
								$disOptNF = 'disabled';
							}
							
							$optNextFlexo = '<select class="form-control inp-kosong2" id="edit-nextflexo-'.$r->id_flexo.'" '.$disOptNF.'>';
							if($r->next_flexo == 'GLUE'){
								$optNextFlexo .= '<option value="'.$r->next_flexo.'">'.$r->next_flexo.'</option><option value="GUDANG">GUDANG</option>';
							}else if($r->next_flexo == 'GUDANG'){
								$optNextFlexo .= '<option value="'.$r->next_flexo.'">'.$r->next_flexo.'</option><option value="GLUE">GLUE</option>';
							}else{
								$optNextFlexo .= '<option value="'.$r->next_flexo.'">'.$r->next_flexo.'</option>';
							}
							$optNextFlexo .= '</select>';
						}

						$html .='<tr class="h-tmpl-list-plan">
							<td '.$bgTd.' style="padding:6px;position:sticky;left:0">
								<input type="number" class="form-control inp-kosong2" id="lp-nourut-'.$r->id_flexo.'" value="'.$r->no_urut_flexo.'" '.$ubahNoUrut.'>
							</td>
							<td '.$bgTd.' style="padding:3px">'.$statusF.'</td>
							<td '.$bgTd.' style="padding:6px;text-align:left">'.$r->kode_mc.'</td>
							<td '.$bgTd.' style="padding:6px;text-align:left;position:sticky'.$left.'">'.$plhPlanCor.'</td>
							<td '.$bgTd.' style="padding:6px;text-align:left">'.$r->nm_pelanggan.'</td>
							<td '.$bgTd.' style="padding:6px;text-align:left">'.$r->nm_produk.'</td>
							<td '.$bgTd.' style="padding:6px">'.$r->kualitas_plan.'</td>
							<td '.$bgTd.' style="padding:6px;color:#f00;font-weight:bold">'.number_format($r->panjang_plan,0,",",".").'</td>
							<td '.$bgTd.' style="padding:6px;color:#f00;font-weight:bold">'.number_format($r->lebar_plan,0,",",".").'</td>
							<td '.$bgTd.' style="padding:6px">'.$r->berat_bersih.'</td>
							<td '.$bgTd.' style="padding:6px">'.$r->flute.'</td>
							<td '.$bgTd.' style="padding:6px">'.$sambungan.'</td>
							<td '.$bgTd.' style="padding:6px;font-weight:bold">'.number_format($r->qty_so,0,",",".").'</td>
							<td '.$bgTd.' style="padding:6px">'.$hasilF.'</td>
							<td '.$bgTd.' style="padding:6px">'.$startF.'</td>
							<td '.$bgTd.' style="padding:6px">'.$endF.'</td>
							<td '.$bgTd.' style="padding:6px;color:#f00;font-weight:bold">'.$this->m_fungsi->tglPlan($r->tgl_kirim_plan).'</td>
							<td '.$bgTd.' style="padding:6px">'.$tgl_prod_p.'</td>
							<td '.$bgTd.' style="padding:6px">'.$good_cor_p.'</td>
							<td '.$bgTd.' style="padding:6px">'.$ton.'</td>
							<td '.$bgTd.' style="padding:6px">'.$optNextFlexo.'</td>
							<td '.$bgTd.' style="padding:6px">'.$btnEdit.'</td>
						</tr>';
					}

					$html .='
				</table>
			</div>
		</div>';

		echo $html;
	}

	function editPlanFlexo()
	{
		$result = $this->m_plan->editPlanFlexo();
		echo json_encode($result);
	}

	function riwayatFlexo()
	{
		$html = '';
		$result = $this->m_plan->riwayatFlexo();
		
		if($result->num_rows() == 0){
			$html .='';
		}else{
			$html .='<div class="card card-warning card-outline">
				<div class="card-header">
					<h3 class="card-title" style="font-weight:bold;font-style:italic">RIWAYAT FLEXO</h3>
				</div>
				<div style="overflow:auto;white-space:nowrap">
					<table class="table table-bordered table-striped" style="border:0;text-align:center">
						<thead>
							<tr>
								<th style="padding:12px 6px">#</th>
								<th style="padding:12px 6px;text-align:left">TGL FLEXO</th>
								<th style="padding:12px 6px">HASIL</th>
								<th style="padding:12px 6px">REJECT FLEXO</th>
								<th style="padding:12px 6px">REJECT BAHAN</th>
								<th style="padding:12px 6px">TOTAL</th>
								<th style="padding:12px 6px">DOWNTIME(m)</th>
								<th style="padding:12px 6px">START</th>
								<th style="padding:12px 6px">END</th>
							</tr>
						</thead>';
						$i = 0;
						$sumGood = 0;
						foreach($result->result() as $r){
							$i++;

							if($r->jmlDt == 0){
								$txtDowtime = '-';
							}else{
								$txtDowtime = $r->jmlDt.' ( '.number_format($r->jmlDtDurasi).' )';
							}

							($r->start_time_f == null) ? $start_time_f = '-' : $start_time_f = substr($r->start_time_f,0,5);
							($r->end_time_f == null) ? $end_time_f = '-' : $end_time_f = substr($r->end_time_f,0,5);

							$mesin = str_replace('FLEXO', '', $r->mesin_flexo);
							$html .= '<tr>
								<td style="padding:6px">'.$i.'</td>
								<td style="padding:6px;text-align:left">
									<a href="javascript:void(0)" onclick="showRiwayat('."'".$r->id_plan_cor."'".','."'".$r->id_flexo."'".','."''".','."'flexo'".')">
									['.$r->shift_flexo.'.'.$mesin.'] '.strtoupper($this->m_fungsi->getHariIni($r->tgl_flexo)).', '.strtoupper($this->m_fungsi->tanggal_format_indonesia($r->tgl_flexo)).'
								</td>
								<td style="padding:6px;text-align:right">'.number_format($r->good_flexo_p).'</td>
								<td style="padding:6px;text-align:right">'.number_format($r->bad_flexo_p).'</td>
								<td style="padding:6px;text-align:right">'.number_format($r->bad_bahan_f_p).'</td>
								<td style="padding:6px;text-align:right">'.number_format($r->total_prod_flexo).'</td>
								<td style="padding:6px;text-align:right">'.$txtDowtime.'</td>
								<td style="padding:6px">'.$start_time_f.'</td>
								<td style="padding:6px">'.$end_time_f.'</td>
							</tr>';
							$sumGood += $r->good_flexo_p;
						}

						$html .='<tr>
							<td style="border:0;background:#fff;padding:6px;font-weight:bold;text-align:right" colspan="2">TOTAL PRODUKSI</td>
							<td style="border:0;background:#fff;padding:6px;font-weight:bold;text-align:right">'.number_format($sumGood,0,",",".").'</td>
						</tr>';

					$html .='</table>
				</div>
			</div>';
		}
		echo $html;
	}

	function showRiwayat()
	{
		$html = '';
		$id_plan = $_POST["id_plan"];
		$id_flexo = $_POST["id_flexo"];
		$id_fs = $_POST["id_fs"];
		$opsi = $_POST["opsi"];

		if($opsi == 'flexo'){
			$plan = $this->db->query("SELECT f.*,c.* FROM plan_flexo f
			INNER JOIN plan_cor c ON f.id_plan_cor=c.id_plan
			WHERE f.id_flexo='$id_flexo' AND f.id_plan_cor='$id_plan'")->row();
			$downtime = $this->db->query("SELECT*FROM plan_flexo_dt dt
			INNER JOIN m_downtime md ON dt.id_m_downtime=md.id_downtime
			WHERE dt.id_plan_flexo='$id_flexo'");
			
			$tgl_plan = $this->m_fungsi->tanggal_format_indonesia($plan->tgl_flexo);
			$shift_plan = $plan->shift_flexo;
			$txt_mesin = 'MESIN';
			$machine_plan = $plan->mesin_flexo;
			$no_wo = $plan->no_wo;

			$hasil_cor = number_format($plan->good_cor_p,0,",",".");
			$hasil_flexo = '';
			$good = number_format($plan->good_flexo_p,0,",",".");
			$reject_prod = number_format($plan->bad_flexo_p,0,",",".");
			$reject_bahan = number_format($plan->bad_bahan_f_p,0,",",".");
			$total = number_format($plan->total_prod_flexo,0,",",".");
			$ket = $plan->ket_flexo_p;
			$tgl_prod = $plan->tgl_prod_f;
			$start_time = $plan->start_time_f;
			$end_time = $plan->end_time_f;

			$tgl_f_cor = '';
			$qty_f_cor = '';
			$next = $plan->next_plan;
		}else{
			$plan = $this->db->query("SELECT o.*,f.*,c.* FROM plan_finishing o
			INNER JOIN plan_flexo f ON o.id_plan_cor=f.id_plan_cor AND o.id_plan_flexo=f.id_flexo
			INNER JOIN plan_cor c ON f.id_plan_cor=c.id_plan
			WHERE o.id_fs='$id_fs' AND o.id_plan_flexo='$id_flexo' AND o.id_plan_cor='$id_plan'")->row();
			$downtime = $this->db->query("SELECT*FROM m_downtime WHERE id_downtime='0'"); // BELUM
			
			$tgl_plan = $this->m_fungsi->tanggal_format_indonesia($plan->tgl_fs);
			$shift_plan = $plan->shift_fs;
			$txt_mesin = 'JOINT';
			$machine_plan = $plan->joint_fs;
			$no_wo = $plan->no_wo;

			$hasil_cor = number_format($plan->good_cor_p,0,",",".");
			$hasil_flexo = number_format($plan->good_flexo_p,0,",",".");
			$good = number_format($plan->good_fs_p,0,",",".");
			$reject_prod = number_format($plan->bad_fs_p,0,",",".");
			$reject_bahan = number_format($plan->bad_bahan_fs_p,0,",",".");
			$total = number_format($plan->total_prod_fs,0,",",".");
			$ket = $plan->ket_fs_p;
			$tgl_prod = $plan->tgl_pord_fs;
			$start_time = $plan->start_time_fs;
			$end_time = $plan->end_time_fs;

			$tgl_f_cor = $plan->tgl_prod_f;
			$qty_f_cor = $plan->good_flexo_p;
			$next = '';
		}

		$panjang = number_format($plan->panjang_plan,0,",",".");
		$lebar = number_format($plan->lebar_plan,0,",",".");
		$order = number_format($plan->pcs_plan,0,",",".");
		$kirim = $plan->tgl_kirim_plan;
		$tgl_p_cor = $plan->tgl_prod_p;
		$qty_p_cor = $plan->good_cor_p;

		$html .= '<div class="row">
			<div class="col-md-6">

				<div class="card card-secondary card-outline" style="padding-bottom:20px">
					<div class="card-header" style="margin-bottom:15px">
						<h3 class="card-title" style="font-weight:bold;font-style:italic">RINCIAN</h3>
					</div>
					<div class="card-body row" style="padding:2px 20px;font-weight:bold">
						<div class="col-md-2">TANGGAL</div>
						<div class="col-md-10"><input type="text" class="form-control" value="'.$tgl_plan.'" disabled></div>
					</div>
					<div class="card-body row" style="padding:2px 20px;font-weight:bold">
						<div class="col-md-2">SHIFT</div>
						<div class="col-md-10"><input type="text" class="form-control" value="'.$shift_plan.'" disabled></div>
					</div>
					<div class="card-body row" style="padding:2px 20px;font-weight:bold">
						<div class="col-md-2">'.$txt_mesin.'</div>
						<div class="col-md-10"><input type="text" class="form-control" value="'.$machine_plan.'" disabled></div>
					</div>
					<div class="card-body row" style="padding:2px 20px;font-weight:bold">
						<div class="col-md-2">NO. WO</div>
						<div class="col-md-10"><input type="text" class="form-control" value="'.$no_wo.'" disabled></div>
					</div>
				</div>';

				if($downtime->num_rows() == 0){
					$html .='';
				}else{
					$html .= '<div class="card card-danger card-outline" style="padding-bottom:20px">
						<div class="card-header">
							<h3 class="card-title" style="font-weight:bold;font-style:italic">DOWNTIME</h3>
						</div>
						<div style="overflow:auto;white-space:nowrap">
							<table class="table table-bordered" style="margin:0;border:0">
								<thead>
									<tr>
										<th style="text-align:center">#</th>
										<th>KODE</th>
										<th>-</th>
										<th style="padding:12px 54px 12px 12px">KETERANGAN</th>
										<th style="text-align:center">(M)</th>
									</tr>
								</thead>';
								$data = $this->db->query("SELECT*FROM plan_flexo_dt p
								INNER JOIN m_downtime d ON p.id_m_downtime=d.id_downtime
								WHERE p.id_plan_flexo='$id_flexo'");
								if($data->num_rows() == 0){
									$html .= '<tr>
										<td style="padding:6px;text-align:center" colspan="5">DOWNTIME KOSONG</td>
									</tr>';
								}else{
									$i = 0;
									$sumMntDt = 0;
									foreach($data->result() as $r){
										$i++;
										$html .= '<tr class="h-tmpl-list-plan">
											<td style="padding:6px;text-align:center">'.$i.'</td>
											<td style="padding:6px;text-align:center">'.$r->kode_d.'</td>
											<td style="padding:6px">'.$r->keterangan.'</td>
											<td style="padding:6px">'.$r->ket_plan_dt.'</td>
											<td style="padding:6px;text-align:center">'.$r->durasi_mnt_dt.'</td>
										</tr>';
										$sumMntDt += $r->durasi_mnt_dt;
									}
									if($data->num_rows() != 1){
										$html .='<tr>
											<td style="border:0;padding:6px;background:#fff;font-weight:bold;text-align:right" colspan="4">TOTAL DOWNTIME(M)</td>
											<td style="border:0;padding:6px;background:#fff;font-weight:bold;text-align:center">'.number_format($sumMntDt).'</td>
										</tr>';
									}
								}
							$html .= '</table>
						</div>
					</div>';
				}
				
				if($opsi == 'finishing'){
					$h_good_flexo = '<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
						<div class="col-md-2">HASIL FX.</div>
						<div class="col-md-10">
							<input type="text" style="font-weight:bold" class="form-control" value="'.$hasil_flexo.'" disabled>
						</div>
					</div>';
				}else{
					$h_good_flexo = '';
				}
				$html .='<div class="card card-success card-outline" style="padding-bottom:20px">
					<div class="card-header">
						<h3 class="card-title" style="font-weight:bold;font-style:italic">HASIL PRODUKSI FLEXO</h3>
					</div>
					<div class="card-body row" style="padding-bottom:5px;font-weight:bold">
						<div class="col-md-2">HASIL COR.</div>
						<div class="col-md-10">
							<input type="text" style="font-weight:bold" class="form-control" value="'.$hasil_cor.'" disabled>
						</div>
					</div>
					'.$h_good_flexo.'
					<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
						<div class="col-md-2">GOOD</div>
						<div class="col-md-10">
							<input type="text" class="form-control" value="'.$good.'" disabled>
						</div>
					</div>
					<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
						<div class="col-md-2">R. FLEXO</div>
						<div class="col-md-10">
							<input type="text" class="form-control" value="'.$reject_prod.'" disabled>
						</div>
					</div>
					<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
						<div class="col-md-2">R. BAHAN</div>
						<div class="col-md-10">
							<input type="text" class="form-control" value="'.$reject_bahan.'" disabled>
						</div>
					</div>
					<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
						<div class="col-md-2">TOTAL</div>
						<div class="col-md-10">
							<input type="text" class="form-control" value="'.$total.'" disabled>
						</div>
					</div>
					<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
						<div class="col-md-2">KET</div>
						<div class="col-md-10">
							<textarea class="form-control" style="resize:none" value="'.$ket.'" rows="2" disabled></textarea>
						</div>
					</div>
					<div class="card-body row" style="padding:20px 20px 5px;font-weight:bold">
						<div class="col-md-2">TGL PROD.</div>
						<div class="col-md-10">
							<input type="text" class="form-control" value="'.$tgl_prod.'" disabled>
						</div>
					</div>
					<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
						<div class="col-md-2">START</div>
						<div class="col-md-10">
							<input type="time" class="form-control" value="'.$start_time.'" disabled>
						</div>
					</div>
					<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
						<div class="col-md-2">END</div>
						<div class="col-md-10">
							<input type="time" class="form-control" value="'.$end_time.'" disabled>
						</div>
					</div>
				</div>';
			$html .='</div>';
		
			if($opsi == 'finishing'){
				$keluarFlexo = '<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
					<div class="col-md-12">KELUAR FLEXO</div>
				</div>
				<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
					<div class="col-md-2">TGL</div>
					<div class="col-md-4">
						<input type="text" class="form-control pr-0" value="'.$tgl_f_cor.'" disabled>
					</div>
					<div class="col-md-2">QTY</div>
					<div class="col-md-4">
						<input type="text" class="form-control"COR" value="'.$qty_f_cor.'" disabled>
					</div>
				</div>';
			}else{
				$keluarFlexo = '<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
					<div class="col-md-2">NEXT</div>
					<div class="col-md-10">
						<input type="text" class="form-control" value="'.$next.'" disabled>
					</div>
				</div>';
			}
			$html .='<div class="col-md-6">
				<div class="card card-info card-outline" style="padding-bottom:20px">
					<div class="card-header" style="margin-bottom:15px">
						<h3 class="card-title" style="font-weight:bold;font-style:italic">FLEXO</h3>
					</div>
					<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
						<div class="col-md-2 p-0">PANJANG</div>
						<div class="col-md-4">
							<input type="text" class="form-control" style="font-weight:bold;color:#f00" value="'.$panjang.'" disabled>
						</div>
						<div class="col-md-2 pr-0">LEBAR</div>
						<div class="col-md-4">
							<input type="text" class="form-control" style="font-weight:bold;color:#f00" value="'.$lebar.'" disabled>
						</div>
					</div>
					<div class="card-body row" style="padding:0 20px 20px;font-weight:bold">
						<div class="col-md-2">ORDER</div>
						<div class="col-md-4">
							<input type="text" class="form-control" style="font-weight:bold" value="'.$order.'" disabled>
						</div>
						<div class="col-md-2">KIRIM</div>
						<div class="col-md-4">
							<input type="text" class="form-control pr-0" value="'.$kirim.'" disabled>
						</div>
					</div>
					<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
						<div class="col-md-12">KELUAR COR</div>
					</div>
					<div class="card-body row" style="padding:0 20px 20px;font-weight:bold">
						<div class="col-md-2">TGL</div>
						<div class="col-md-4">
							<input type="text" class="form-control pr-0" value="'.$tgl_p_cor.'" disabled>
						</div>
						<div class="col-md-2">QTY</div>
						<div class="col-md-4">
							<input type="text" class="form-control"COR" value="'.$qty_p_cor.'" disabled>
						</div>
					</div>
					'.$keluarFlexo.'
				</div>
			</div>';
		$html .='</div>';

		echo $html;
	}

	function laporanISOFlexo()
	{
		$html = '';
		$tgl_flexo = $_GET["tgl"];
		$shift_flexo = $_GET["shift"];
		$mesin_flexo = $_GET["mesin"];
		
		$html .= '<table style="margin:0;padding:0;font-size:10px;text-align:center;border-collapse:collapse;color:#000;width:100%">
			<thead>
				<tr>
					<th style="width:5%;border:1px solid #000;border-width:1px 0 0 1px" rowspan="2">
						<img src="'.base_url('assets/gambar/ppi.png').'" alt="PPI" width="70" height="60">
					</th>
					<th style="width:45%;border:1px solid #000;border-width:1px 0 0;font-weight:bold;text-align:left;font-size:16px;padding-left:10px">PT. PRIMA PAPER INDONESIA</th>
					<th style="width:35%;border:1px solid #000;border-width:1px 1px 0 0;font-weight:bold;text-align:right;font-size:14px;padding-right:10px">FLEXOs PLAN</th>
					<th style="width:15%;border:1px solid #000;border-width:1px 1px 0;text-align:left;font-weight:bold" rowspan="2">
						<table style="margin:0;padding:0;font-size:10px;font-weight:normal;text-align:left;border-collapse:collapse">
							<tr>
								<td>No</td>
								<td style="padding:0 5px">:</td>
								<td>FR-PPIC-01</td>
							</tr>
							<tr>
								<td>Tgl Terbit</td>
								<td style="padding:0 5px">:</td>
								<td>27 Sep 2022</td>
							</tr>
							<tr>
								<td>Rev</td>
								<td style="padding:0 5px">:</td>
								<td>00</td>
							</tr>
							<tr>
								<td>Hal</td>
								<td style="padding:0 5px">:</td>
								<td>1</td>
							</tr>
						</table>
					</th>
				</tr>
				<tr>
					<th style="vertical-align:top;font-style:italic;font-weight:normal;text-align:left;padding-left:10px">Dusun Timang Kulon, Desa Wonokerto, Wonogiri</th>
					<th></th>
				</tr>
			</thead>
		</table>';

		$data = $this->db->query("SELECT f.*,i.*,p.tgl_kirim_plan,p.pcs_plan,p.tgl_prod_p,p.good_cor_p,s.kode_po,c.nm_pelanggan FROM plan_flexo f
		INNER JOIN plan_cor p ON f.id_plan_cor=p.id_plan
		INNER JOIN trs_so_detail s ON p.id_so_detail=s.id
		INNER JOIN m_produk i ON p.id_produk=i.id_produk
		INNER JOIN m_pelanggan c ON p.id_pelanggan=c.id_pelanggan
		WHERE f.tgl_flexo='$tgl_flexo' AND f.shift_flexo='$shift_flexo' AND f.mesin_flexo='$mesin_flexo'
		ORDER BY f.no_urut_flexo,f.id_flexo");

		$html .= '<table style="margin:0;padding:0;font-size:10px;text-align:center;border-collapse:collapse;color:#000;width:100%">
			<thead>
				<tr>
					<th style="border:0;width:2%"></th>
					<th style="border:0;width:18%"></th>
					<th style="border:0;width:5%"></th>
					<th style="border:0;width:8%"></th>
					<th style="border:0;width:2%"></th>
					<th style="border:0;width:4%"></th>
					<th style="border:0;width:5%"></th>
					<th style="border:0;width:5%"></th>
					<th style="border:0;width:5%"></th>
					<th style="border:0;width:5%"></th>
					<th style="border:0;width:5%"></th>
					<th style="border:0;width:5%"></th>
					<th style="border:0;width:5%"></th>
					<th style="border:0;width:5%"></th>
					<th style="border:0;width:17%"></th>
					<th style="border:0;width:4%"></th>
				</tr>
				<tr text-rotate="-90">
					<th text-rotate="0" style="border:1px solid #000" rowspan="4">ID</th>
					<th text-rotate="0" style="border-top:1px solid #000;text-align:left">NO.SO/WO</th>
					<th text-rotate="0" style="border:1px solid #000" rowspan="4">TGL KIRIM</th>
					<th text-rotate="0" style="border:1px solid #000" rowspan="4">KUALITAS BOX</th>
					<th style="border:1px solid #000" rowspan="4">FLUTE</th>
					<th text-rotate="0" style="border:1px solid #000" rowspan="4">BERAT<br>BOX<br>(KG)</th>
					<th text-rotate="0" style="border:1px solid #000" rowspan="2">WAKTU</th>
					<th text-rotate="0" style="border:1px solid #000" rowspan="4">ORDER<br>(PCS)</th>
					<th text-rotate="0" style="border:1px solid #000" rowspan="4">HASIL<br>(PCS)</th>
					<th text-rotate="0" style="border:1px solid #000" rowspan="4">TONASE<br>(KG)</th>
					<th text-rotate="0" style="border:1px solid #000" rowspan="2" colspan="2">REJECT</th>
					<th text-rotate="0" style="border:1px solid #000" rowspan="2" colspan="2">DOWNTIME</th>
					<th text-rotate="0" style="border:1px solid #000" rowspan="4">KET</th>
					<th style="border:1px solid #000" rowspan="4">NEXT</th>
				</tr>
				<tr>
					<th style="border:0;text-align:left">CUSTOMER</th>
				</tr>
				<tr>
					<th style="border:0;text-align:left">NO. PO</th>
					<th style="border:1px solid #000">START</th>
					<th style="border:1px solid #000" rowspan="2">FLEXO</th>
					<th style="border:1px solid #000" rowspan="2">BAHAN</th>
					<th style="border:1px solid #000" rowspan="2">SETTING</th>
					<th style="border:1px solid #000" rowspan="2">LAIN2</th>
				</tr>
				<tr>
					<th style="border-bottom:1px solid #000;text-align:left">ITEM</th>
					<th style="border:1px solid #000">END</th>
				</tr>
				<tr>
					<th style="padding:2px 0;font-weight:bold;text-align:left" colspan="2">PERIODE : '.strtoupper($this->m_fungsi->tanggal_format_indonesia($tgl_flexo)).'</th>
					<th style="padding:2px 0;font-weight:bold;text-align:left" colspan="2">MACHINE : '.$mesin_flexo.'</th>
					<th style="padding:2px 0;font-weight:bold;text-align:left" colspan="3">SHIFT : '.$shift_flexo.'</th>
				</tr>
			</thead>
			<tbody>';
				$i = 0;
				foreach($data->result() as $r){
					$i++;
					$expKualitas = explode("/", $r->kualitas);
					if($r->flute == 'BCF'){
						if($expKualitas[1] == 'M125' && $expKualitas[2] == 'M125' && $expKualitas[3] == 'M125'){
							$kualitas = $expKualitas[0].'/'.$expKualitas[1].'x3/'.$expKualitas[4];
						}else if($expKualitas[1] == 'K125' && $expKualitas[2] == 'K125' && $expKualitas[3] == 'K125'){
							$kualitas = $expKualitas[0].'/'.$expKualitas[1].'x3/'.$expKualitas[4];
						}else if($expKualitas[1] == 'M150' && $expKualitas[2] == 'M150' && $expKualitas[3] == 'M150'){
							$kualitas = $expKualitas[0].'/'.$expKualitas[1].'x3/'.$expKualitas[4];
						}else if($expKualitas[1] == 'K150' && $expKualitas[2] == 'K150' && $expKualitas[3] == 'K150'){
							$kualitas = $expKualitas[0].'/'.$expKualitas[1].'x3/'.$expKualitas[4];
						}else{
							$kualitas = $r->kualitas;
						}
					}else{
						$kualitas = $r->kualitas;
					}

					($r->flute == 'BCF') ? $flute = 'BC' : $flute = $r->flute;

					if($r->tgl_prod_p != "" && $r->good_cor_p != 0){
						$good_cor_p = number_format($r->good_cor_p,0,",",".");
						$ton = number_format($r->berat_bersih * $r->good_cor_p,0,",",".");
						$bad_flexo_p = $r->bad_flexo_p;
						$bad_bahan_f_p = $r->bad_bahan_f_p;
					}else{
						$good_cor_p = '';
						$ton = '';
						$bad_flexo_p = '';
						$bad_bahan_f_p = '';
					}

					$cekDowntime = $this->db->query("SELECT*FROM plan_flexo_dt dt
					INNER JOIN m_downtime md ON dt.id_m_downtime=md.id_downtime
					WHERE dt.id_plan_flexo='$r->id_flexo'");
					$txtKet = '';
					if($cekDowntime->num_rows() == 0){
						$txtKet .= $r->ket_flexo_p;
					}else{
						$txtKet .= $r->ket_flexo_p.'<br/>';
						foreach($cekDowntime->result() as $dt){
							$txtKet .= '('.$dt->kode_d.'-'.$dt->durasi_mnt_dt.'") ';
						}
					}

					$html .='<tr>
						<td style="border:1px solid #000" rowspan="4">'.$i.'</td>
						<td style="border:1px solid #000;border-width:1px 0;text-align:left">'.$r->kode_mc.'</td>
						<td style="border:1px solid #000" rowspan="4">'.$this->m_fungsi->tglPlan($r->tgl_kirim_plan).'</td>
						<td style="border:1px solid #000" rowspan="4">'.$kualitas.'</td>
						<td style="border:1px solid #000" rowspan="4">'.$flute.'</td>
						<td style="border:1px solid #000" rowspan="4">'.$r->berat_bersih.'</td>
						<td style="border:1px solid #000" rowspan="2"></td>
						<td style="border:1px solid #000" rowspan="4">'.number_format($r->pcs_plan).'</td>
						<td style="border:1px solid #000" rowspan="4">'.$good_cor_p.'</td>
						<td style="border:1px solid #000" rowspan="4">'.$ton.'</td>
						<td style="border:1px solid #000" rowspan="4">'.$bad_flexo_p.'</td>
						<td style="border:1px solid #000" rowspan="4">'.$bad_bahan_f_p.'</td>
						<td style="border:1px solid #000" rowspan="4"></td>
						<td style="border:1px solid #000" rowspan="4"></td>
						<td style="border:1px solid #000;text-align:left;vertical-align:top" rowspan="4">'.$txtKet.'</td>
						<td style="border:1px solid #000" rowspan="4">'.$r->next_flexo.'</td>
					</tr>
					<tr>
						<td style="border:0;text-align:left">'.$r->nm_pelanggan.'</td>
					</tr>
					<tr>
						<td style="border:0;text-align:left">'.$r->kode_po.'</td>
						<td style="border:1px solid #000" rowspan="2"></td>
					</tr>
					<tr>
						<td style="border-bottom:1px solid #000;text-align:left">'.$r->nm_produk.'</td>
					</tr>';
				}
				$html .='
			</tbody>
		</table>';

		$html .= '<table style="margin:0;padding:0;font-size:10px;text-align:center;border-collapse:collapse;color:#000;width:100%">
			<tr>
				<td style="border:0;width:40%"></td>
				<td style="border:0;width:12%"></td>
				<td style="border:0;width:12%"></td>
				<td style="border:0;width:12%"></td>
				<td style="border:0;width:12%"></td>
				<td style="border:0;width:12%"></td>
			</tr>
			<tr>
				<td style="font-weight:bold;text-align:left">DISTRIBUSI : PPIC - PRODUKSI - BAGIAN TINTA - PREPARATION</td>
			</tr>
			<tr>
				<td style="font-weight:bold;text-align:left" rowspan="3">TOTAL PCS :</td>
				<td style="border:1px solid #000;font-weight:bold" colspan="5">OTORISASI</td>
			</tr>
			<tr>
				<td style="border:1px solid #000;border-width:1px 0 1px 1px;font-weight:bold" colspan="3">PPIC</td>
				<td style="border:1px solid #000;border-width:1px 1px 1px 0;font-weight:bold" colspan="2">PRODUKSI</td>
			</tr>
			<tr>
				<td style="border:1px solid #000">Mengetahui</td>
				<td style="border:1px solid #000">Dibuat Oleh</td>
				<td style="border:1px solid #000">Mengetahui</td>
				<td style="border:1px solid #000">Diperiksa</td>
				<td style="border:1px solid #000">Dibuat Oleh</td>
			</tr>
			<tr>
				<td style="font-weight:bold;text-align:left" rowspan="3">TOTAL TONASE :</td>
				<td style="border:1px solid #000;border-width:0 1px;padding:7px"></td>
				<td style="border:1px solid #000;border-width:0 1px;padding:7px"></td>
				<td style="border:1px solid #000;border-width:0 1px;padding:7px"></td>
				<td style="border:1px solid #000;border-width:0 1px;padding:7px"></td>
				<td style="border:1px solid #000;border-width:0 1px;padding:7px"></td>
			</tr>
			<tr>
				<td style="border:1px solid #000;border-width:0 1px;padding:7px"></td>
				<td style="border:1px solid #000;border-width:0 1px;padding:7px"></td>
				<td style="border:1px solid #000;border-width:0 1px;padding:7px"></td>
				<td style="border:1px solid #000;border-width:0 1px;padding:7px"></td>
				<td style="border:1px solid #000;border-width:0 1px;padding:7px"></td>
			</tr>
			<tr>
				<td style="border:1px solid #000;border-width:0 1px;padding:7px"></td>
				<td style="border:1px solid #000;border-width:0 1px;padding:7px"></td>
				<td style="border:1px solid #000;border-width:0 1px;padding:7px"></td>
				<td style="border:1px solid #000;border-width:0 1px;padding:7px"></td>
				<td style="border:1px solid #000;border-width:0 1px;padding:7px"></td>
			</tr>
			<tr>
				<td></td>
				<td style="border:1px solid #000">KABAG</td>
				<td style="border:1px solid #000">Adm. PPC</td>
				<td style="border:1px solid #000">KABAG</td>
				<td style="border:1px solid #000">KASI</td>
				<td style="border:1px solid #000">Operator</td>
			</tr>
			';
		$html .= '</table>';
		
		$judulTglFlexo = str_replace('/', '', $this->m_fungsi->tglPlan($tgl_flexo));
		$judul = 'PLANFLEXO-'.$judulTglFlexo.'.'.$shift_flexo.'.'.$mesin_flexo.'.ISO';
		$this->m_fungsi->newMpdf($judul, '', $html, 1, 3, 1, 3, 'L', 'A4', $judul.'.pdf');
		// echo $html;
	}

	function laporanPlanFlexo()
	{ // 
		$html = '';
		$tgl_flexo = $_GET["tgl"];
		$shift_flexo = $_GET["shift"];
		$mesin_flexo = $_GET["mesin"];
		$html .= '<table style="margin:0;padding:0;font-size:10px;text-align:center;border-collapse:collapse;color:#000;width:100%">
			<thead>
				<tr>
					<th style="border:0;width:2%"></th>
					<th style="border:0;width:6%"></th>
					<th style="border:0;width:7%"></th>
					<th style="border:0;width:7%"></th>
					<th style="border:0;width:14%"></th>
					<th style="border:0;width:7%"></th>
					<th style="border:0;width:4%"></th>
					<th style="border:0;width:4%"></th>
					<th style="border:0;width:4%"></th>
					<th style="border:0;width:2%"></th>
					<th style="border:0;width:6%"></th>
					<th style="border:0;width:5%"></th>
					<th style="border:0;width:6%"></th>
					<th style="border:0;width:5%"></th>
					<th style="border:0;width:5%"></th>
					<th style="border:0;width:5%"></th>
					<th style="border:0;width:11%"></th>
				</tr>
				<tr>
					<th style="font-size:16px;padding-bottom:10px" colspan="17">PLAN '.$mesin_flexo.'</th>
				</tr>
				<tr>
					<th style="border:1px solid #000;padding:10px 0">NO</th>
					<th style="border:1px solid #000;padding:10px 0">KODE MC</th>
					<th style="border:1px solid #000;padding:10px 0">NO.PO</th>
					<th style="border:1px solid #000;padding:10px 0">CUSTOMER</th>
					<th style="border:1px solid #000;padding:10px 0">ITEM</th>
					<th style="border:1px solid #000;padding:10px 0">KUALITAS</th>
					<th style="border:1px solid #000;padding:10px 0;color:#f00">PJG</th>
					<th style="border:1px solid #000;padding:10px 0;color:#f00">LBR</th>
					<th style="border:1px solid #000;padding:10px 0">BB</th>
					<th style="border:1px solid #000;padding:10px 0">FT</th>
					<th style="border:1px solid #000;padding:10px 0">JOINT</th>
					<th style="border:1px solid #000;padding:10px 0">ORDER</th>
					<th style="border:1px solid #000;padding:10px 0;color:#f00">TGL KIRIM</th>
					<th style="border:1px solid #000;padding:10px 0" colspan="2">KELUAR COR</th>
					<th style="border:1px solid #000;padding:10px 0">TONASE</th>
					<th style="border:1px solid #000;padding:10px 0">KETERANGAN</th>
				</tr>
				<tr>
					<th style="border:1px solid #000;border-width:1px 0 1px 1px;padding:7px 0;font-size:14px" colspan="10">PLAN '.strtoupper($this->m_fungsi->getHariIni($tgl_flexo)).' '.strtoupper($this->m_fungsi->tanggal_format_indonesia($tgl_flexo)).'</th>
					<th style="border:1px solid #000;border-width:1px 1px 1px 0;padding:7px 0" colspan="3"></th>
					<th style="border:1px solid #000;padding:7px 0">TGL</th>
					<th style="border:1px solid #000;padding:7px 0">QTY</th>
					<th style="border:1px solid #000;padding:7px 0" colspan="2"></th>
				</tr>
			</thead>
			<tbody>';

				$data = $this->db->query("SELECT f.*,i.*,p.tgl_kirim_plan,p.kualitas_plan,p.panjang_plan,p.lebar_plan,p.pcs_plan,p.tgl_prod_p,p.good_cor_p,s.kode_po,c.nm_pelanggan FROM plan_flexo f
				INNER JOIN plan_cor p ON f.id_plan_cor=p.id_plan
				INNER JOIN trs_so_detail s ON p.id_so_detail=s.id
				INNER JOIN m_produk i ON p.id_produk=i.id_produk
				INNER JOIN m_pelanggan c ON p.id_pelanggan=c.id_pelanggan
				WHERE f.tgl_flexo='$tgl_flexo' AND f.shift_flexo='$shift_flexo' AND f.mesin_flexo='$mesin_flexo'
				ORDER BY f.no_urut_flexo,f.id_flexo");
				$i = 0;
				$sumOrder = 0;
				foreach($data->result() as $r){
					$i++;
					$expKualitas = explode("/", $r->kualitas_plan);
					if($r->flute == 'BCF'){
						if($expKualitas[1] == 'M125' && $expKualitas[2] == 'M125' && $expKualitas[3] == 'M125'){
							$kualitas = $expKualitas[0].'/'.$expKualitas[1].'x3/'.$expKualitas[4];
						}else if($expKualitas[1] == 'K125' && $expKualitas[2] == 'K125' && $expKualitas[3] == 'K125'){
							$kualitas = $expKualitas[0].'/'.$expKualitas[1].'x3/'.$expKualitas[4];
						}else if($expKualitas[1] == 'M150' && $expKualitas[2] == 'M150' && $expKualitas[3] == 'M150'){
							$kualitas = $expKualitas[0].'/'.$expKualitas[1].'x3/'.$expKualitas[4];
						}else if($expKualitas[1] == 'K150' && $expKualitas[2] == 'K150' && $expKualitas[3] == 'K150'){
							$kualitas = $expKualitas[0].'/'.$expKualitas[1].'x3/'.$expKualitas[4];
						}else{
							$kualitas = $r->kualitas;
						}
					}else{
						$kualitas = $r->kualitas;
					}

					if($r->sambungan == 'G'){
						$sambungan = 'GLUE';
					}else if($r->sambungan == 'D'){
						$sambungan = 'DIECUT';
					}else if($r->sambungan == 'S'){
						$sambungan = 'STICHING';
					}else if($r->sambungan == 'GS'){
						$sambungan = 'GLUE STICHING';
					}else if($r->sambungan == 'DS'){
						$sambungan = 'DOUBLE STICHING';
					}else{
						$sambungan = '-';
					}

					if($r->tgl_prod_p != "" && $r->good_cor_p != 0){
						$tgl_prod_p = $this->m_fungsi->tglPlan($r->tgl_prod_p);
						$good_cor_p = number_format($r->good_cor_p,0,",",".");
						$ton = number_format($r->berat_bersih * $r->good_cor_p,0,",",".");
					}else{
						$tgl_prod_p = '-';
						$good_cor_p = '-';
						$ton = '-';
					}
					($r->flute == 'BCF') ? $flute = 'BC' : $flute = $r->flute;

					$html .='<tr>
						<td style="border:1px solid #000">'.$i.'</td>
						<td style="border:1px solid #000;text-align:left">'.$r->kode_mc.'</td>
						<td style="border:1px solid #000;text-align:left">'.$r->kode_po.'</td>
						<td style="border:1px solid #000;text-align:left">'.$r->nm_pelanggan.'</td>
						<td style="border:1px solid #000;text-align:left">'.$r->nm_produk.'</td>
						<td style="border:1px solid #000">'.$kualitas.'</td>
						<td style="border:1px solid #000;font-weight:bold;color:#f00">'.number_format($r->panjang_plan,0,',','.').'</td>
						<td style="border:1px solid #000;font-weight:bold;color:#f00">'.number_format($r->lebar_plan,0,',','.').'</td>
						<td style="border:1px solid #000">'.$r->berat_bersih.'</td>
						<td style="border:1px solid #000">'.$flute.'</td>
						<td style="border:1px solid #000">'.$sambungan.'</td>
						<td style="border:1px solid #000;font-weight:bold">'.number_format($r->pcs_plan,0,',','.').'</td>
						<td style="border:1px solid #000;font-weight:bold;color:#f00">'.$this->m_fungsi->tglPlan($r->tgl_kirim_plan).'</td>
						<td style="border:1px solid #000">'.$tgl_prod_p.'</td>
						<td style="border:1px solid #000">'.$good_cor_p.'</td>
						<td style="border:1px solid #000">'.$ton.'</td>
						<td style="border:1px solid #000"></td>
					</tr>';

					$sumOrder += $r->pcs_plan;
				}

				$html .='<tr>
					<td style="background:#ddd;border:1px solid #000" colspan="11"></td>
					<td style="background:#ddd;border:1px solid #000;padding:6px 0;color:#f00;font-size:11px;font-weight:bold">'.number_format($sumOrder,0,',','.').'</td>
					<td style="background:#ddd;border:1px solid #000" colspan="5"></td>
				</tr>';

				$html .= '
			</tbody>
		</table>';

		$judulTglFlexo = str_replace('/', '', $this->m_fungsi->tglPlan($tgl_flexo));
		$judul = 'PLANFLEXO-'.$judulTglFlexo.'.'.$shift_flexo.'.'.$mesin_flexo;
		$this->m_fungsi->newMpdf($judul, '', $html, 1, 3, 1, 3, 'L', 'A4', $judul.'.pdf');
	}

	//

	function Finishing()
	{
		$data_header = array(
			'judul' => "Plan Finishing",
		);

		$this->load->view('header', $data_header);

		$jenis = $this->uri->segment(3);
		if($jenis == 'Add'){
			if(in_array($this->session->userdata('level'), ['Admin','PPIC','User'])){
				$this->load->view('Plan/Finishing/v_finishing_add');
			}else{
				$this->load->view('home');
			}
		}else if($jenis == 'List'){
			if(in_array($this->session->userdata('level'), ['Admin','PPIC','Finishing','User'])){
				$data = array(
					"tgl" => $this->uri->segment(4),
					"shift" => $this->uri->segment(5),
					"joint" => $this->uri->segment(6),
				);
				$this->load->view('Plan/Finishing/v_finishing_plan', $data);
			}else{
				$this->load->view('home');
			}
		}else{
			if(in_array($this->session->userdata('level'), ['Admin','PPIC','Finishing','User'])){
				$this->load->view('Plan/Finishing/v_finishing');
			}else{
				$this->load->view('home');
			}
		}

		$this->load->view('footer');
	}

	function LoaDataFinishing()
	{
		$data = array();
		$query = $this->db->query("SELECT COUNT(id_fs) AS jml,f.* FROM plan_finishing f GROUP BY tgl_fs DESC,shift_fs,joint_fs")->result();
		$i = 0;
		foreach ($query as $r) {
			$i++;
			$row = array();
			$row[] = '<div style="text-align:center">'.$i.'</div>';
			$row[] = strtoupper($this->m_fungsi->tanggal_format_indonesia($r->tgl_fs));
			$row[] = '<div style="text-align:center">'.$r->shift_fs.'</div>';
			$row[] = '<div style="text-align:center">'.$r->joint_fs.'</div>';
			$row[] = '<div style="text-align:center">'.$r->jml.'</div>';

			$link = base_url('Plan/Finishing/List/'.$r->tgl_fs.'/'.$r->shift_fs.'/'.$r->joint_fs);
			$printLapFinishing = base_url('Plan/laporanPlanFinishing?tgl='.$r->tgl_fs.'&shift='.$r->shift_fs.'&joint='.$r->joint_fs.'');
			// $printLap = base_url('Plan/laporanISOFlexo?tgl='.$r->tgl_flexo.'&shift='.$r->shift_flexo.'&mesin='.$r->mesin_flexo.'');
			// <a href="#" target="_blank" class="btn btn-sm btn-success" title="CETAK PLAN"><i class="fas fa-print"></i></a>
			// <a href="#" target="_blank" class="btn btn-sm btn-primary" title="CETAK FLEXO"><i class="fas fa-print"></i></a>
			if(in_array($this->session->userdata('level'), ['Admin','PPIC','User'])){
				$btnPrint = '
				<a href="'.$link.'" title="Edit"><button type="button" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></button></a>
				<a href="'.$printLapFinishing.'" target="_blank" class="btn btn-sm btn-success" title="CETAK PLAN"><i class="fas fa-print"></i></a>';
			}else if($this->session->userdata('level') == 'Flexo'){
				$btnPrint = '<a href="'.$link.'" title="Edit"><button type="button" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></button></a>';
			}else{
				$btnPrint = '';
			}
			$row[] = $btnPrint;

			$data[] = $row;
		}

		$output = array(
			"data" => $data,
		);
		echo json_encode($output);
	}

	function loadPlanFlexo()
	{
		$opsi = $_POST['opsi'];
		$result = $this->m_plan->loadPlanFlexo();
		if($opsi != ''){
			$getNoFinishing = $this->db->query("SELECT*FROM plan_finishing WHERE id_fs='$opsi'")->row();
			$urutDtProd = $this->db->query("SELECT id_fs,no_urut_fs FROM plan_finishing WHERE tgl_fs='$getNoFinishing->tgl_fs' AND shift_fs='$getNoFinishing->shift_fs'
			AND total_prod_fs='0' AND no_urut_fs!='0' ORDER BY no_urut_fs ASC LIMIT 1")->row();
		}else{
			$getNoFinishing = false;
			$urutDtProd = false;
		}
		echo json_encode(array(
			'plan_flexo' => $result,
			'urutDtProd' => $urutDtProd,
			'getNoFinishing' => $getNoFinishing,
			'opsi' => $opsi,
		));
	}

	function fsDataAllCustFlexo()
	{
		$html = '';
		$allPlanCor = $this->db->query("SELECT COUNT(p.id_flexo) AS jml_plan,c.id_pelanggan,c.nm_pelanggan,p.* FROM plan_flexo p
		INNER JOIN plan_cor r ON p.id_plan_cor=r.id_plan
		INNER JOIN m_pelanggan c ON r.id_pelanggan=c.id_pelanggan
		WHERE p.status_stt_f='Open' AND p.next_flexo!='GUDANG'
		GROUP BY c.id_pelanggan
		ORDER BY c.nm_pelanggan");
		$html .='<div id="accordion-h-cust">
			<div style="padding:6px;font-weight:bold">
				CUSTOMER <span class="bg-light" style="vertical-align:top;padding:2px 4px;font-size:12px">JUMLAH PLAN FLEXO</span>
			</div>';
			foreach($allPlanCor->result() as $r){
				$html .='<div class="card m-0" style="border-radius:0">
					<div class="card-header bg-gradient-info" style="padding:0;border-radius:0">
						<a class="d-block w-100 link-h-wo" style="font-weight:bold;padding:6px" data-toggle="collapse" href="#collapseHeaderPlanCor'.$r->id_pelanggan.'" onclick="onClickHeaderPlanFlexo('."'".$r->id_pelanggan."'".')">
							'.$r->nm_pelanggan.' <span class="bg-light" style="vertical-align:top;font-weight:bold;padding:2px 4px;font-size:12px">'.$r->jml_plan.'</span>
						</a>
					</div>
					<div id="collapseHeaderPlanCor'.$r->id_pelanggan.'" class="collapse" data-parent="#accordion-h-cust">
						<div id="tampil-all-flexo-isi-'.$r->id_pelanggan.'"></div>
					</div>
				</div>';
			}
		$html .='</div>';

		echo $html;
	}

	function onClickHeaderPlanFlexo()
	{
		$id_pelanggan = $_POST["id_pelanggan"];
		$html = '';

		$data = $this->db->query("SELECT (SELECT COUNT(s.id_fs) FROM plan_finishing s WHERE s.id_plan_cor=f.id_plan_cor AND s.id_plan_flexo=f.id_flexo) AS jml_fs,
		(SELECT COUNT(s.id_fs) FROM plan_finishing s WHERE s.id_plan_cor=f.id_plan_cor AND s.id_plan_flexo=f.id_flexo AND s.total_prod_fs!='0' AND s.status_fs='Open') AS prod,
		(SELECT COUNT(s.id_fs) FROM plan_finishing s WHERE s.id_plan_cor=f.id_plan_cor AND s.id_plan_flexo=f.id_flexo AND s.total_prod_fs!='0' AND s.status_fs='Close') AS done,
		i.nm_produk,l.id_pelanggan,l.nm_pelanggan,p.id_plan,p.no_wo,f.* FROM plan_flexo f
		INNER JOIN plan_cor p ON f.id_plan_cor=p.id_plan
		INNER JOIN m_produk i ON p.id_produk=i.id_produk
		INNER JOIN m_pelanggan l ON p.id_pelanggan=l.id_pelanggan
		WHERE l.id_pelanggan='$id_pelanggan' AND f.status_stt_f='Open' AND f.next_flexo!='GUDANG'
		ORDER BY f.tgl_flexo,f.shift_flexo,f.mesin_flexo");
		$html .='<div class="card-body" style="padding:6px">
			<div id="accordion-isi-planff">
			TGL PLAN | NO. WO | ITEM <span class="bg-dark" style="vertical-align:top;font-weight:bold;padding:2px 4px;font-size:12px">HASIL FLEXO</span><span class="bg-light" style="vertical-align:top;font-weight:bold;padding:2px 4px;font-size:12px">JML PLAN FINISHING</span><span class="bg-success" style="vertical-align:top;font-weight:bold;padding:2px 4px;font-size:12px">PRODUKSI</span><span class="bg-primary" style="vertical-align:top;font-weight:bold;padding:2px 4px;font-size:12px">SELESAI</span>';
				foreach($data->result() as $r){
					($r->jml_fs == 0) ? $jml_fs = 0 : $jml_fs = $r->jml_fs;
					($r->prod == 0) ? $prod = '' : $prod = '<span class="bg-success" style="vertical-align:top;font-weight:bold;padding:2px 4px;font-size:12px">'.$r->prod.'</span>';
					($r->done == 0) ? $done = '' : $done = '<span class="bg-primary" style="vertical-align:top;font-weight:bold;padding:2px 4px;font-size:12px">'.$r->done.'</span>';
					$hariIni = substr($this->m_fungsi->getHariIni($r->tgl_flexo),0,3);

					// '.strtoupper($this->m_fungsi->getHariIni($r->tgl_plan)).', '.strtoupper($this->m_fungsi->tanggal_format_indonesia($r->tgl_plan)).' | '.$r->no_wo.' | '.$r->nm_produk.' <span class="bg-dark" style="vertical-align:top;font-weight:bold;padding:2px 4px;font-size:12px">'.number_format($r->good_cor_p,0,',','.').'</span><span class="bg-light" style="vertical-align:top;font-weight:bold;padding:2px 4px;font-size:12px">'.$jml_fs.'</span>'.$prod.''.$done.'

					$html .='<div class="card m-0" style="border-radius:0">
						<div class="card-header" style="padding:0;border-radius:0">
							<a class="d-block w-100 link-i-wo" style="font-weight:bold;padding:6px" data-toggle="collapse" href="#collapseHeaderIsiPlanFlexo'.$r->id_plan.'-'.$r->id_flexo.'-'.$r->id_pelanggan.'" onclick="onclickHeaderIsiPlanFlexo('."'".$r->id_plan."'".','."'".$r->id_flexo."'".','."'".$r->id_pelanggan."'".')">
								'.strtoupper($hariIni).', '.strtoupper($this->m_fungsi->tglIndSkt($r->tgl_flexo)).' | '.$r->no_wo.' | '.$r->nm_produk.'
								<span class="bg-dark" style="vertical-align:top;font-weight:bold;padding:2px 4px;font-size:12px">'.number_format($r->good_flexo_p,0,',','.').'</span><span class="bg-light" style="vertical-align:top;font-weight:bold;padding:2px 4px;font-size:12px">'.$jml_fs.'</span>'.$prod.''.$done.'
							</a>
						</div>
						<div id="collapseHeaderIsiPlanFlexo'.$r->id_plan.'-'.$r->id_flexo.'-'.$r->id_pelanggan.'" class="collapse" data-parent="#accordion-isi-planff">
							<div id="tampil-all-ppfflan-isi-'.$r->id_plan.'-'.$r->id_flexo.'-'.$r->id_pelanggan.'"></div>
						</div>
					</div>';
				}
			$html .='</div>
		</div>';

		echo $html;
	}

	function onclickHeaderIsiPlanFlexo()
	{
		$id_plan = $_POST["id_plan"];
		$id_flexo = $_POST["id_flexo"];
		$id_pelanggan = $_POST["id_pelanggan"];
		$html = '';

		$data = $this->db->query("SELECT i.*,fs.*,f.*,c.* FROM plan_finishing fs
		INNER JOIN plan_flexo f ON fs.id_plan_cor=f.id_plan_cor AND fs.id_plan_flexo=f.id_flexo
		INNER JOIN plan_cor c ON f.id_plan_cor=c.id_plan
		INNER JOIN m_produk i ON c.id_produk=i.id_produk
		WHERE c.id_pelanggan='$id_pelanggan' AND fs.id_plan_cor='$id_plan' AND fs.id_plan_flexo='$id_flexo'
		ORDER BY fs.tgl_fs,fs.shift_fs,fs.joint_fs");

		if($data->num_rows() == 0){
			$html .='';
		}else{
			$html .= '<div style="overflow:auto;white-space:nowrap;padding-bottom:5px">
				<table class="table table-bordered" style="text-align:center;border:0">
					<thead>
						<tr>
							<th style="padding:6px;position:sticky;left:0;background:#fff">#</th>
							<th style="padding:6px">TGL FINISHING</th>
							<th style="padding:6px">KODE.MC</th>
							<th style="padding:6px">KUALITAS</th>
							<th style="padding:6px">PANJANG</th>
							<th style="padding:6px">LEBAR</th>
							<th style="padding:6px">BB</th>
							<th style="padding:6px">FT</th>
							<th style="padding:6px">JOINT</th>
							<th style="padding:6px">ORDER</th>
							<th style="padding:6px">TGL KIRIM</th>
							<th style="padding:6px">KL.CR TGL</th>
							<th style="padding:6px">KL.CR QTY</th>
							<th style="padding:6px">KL.FX TGL</th>
							<th style="padding:6px">KL.FX QTY</th>
							<th style="padding:6px">HASIL</th>
							<th style="padding:6px">R. FINISHING</th>
							<th style="padding:6px">R. BAHAN</th>
							<th style="padding:6px">KETERANGAN</th>
						</tr>
					</thead>';
					$sumGood = 0;
					foreach($data->result() as $r){
						if($r->sambungan == 'G'){
							$sambungan = 'GLUE';
						}else if($r->sambungan == 'D'){
							$sambungan = 'DIECUT';
						}else if($r->sambungan == 'S'){
							$sambungan = 'STICHING';
						}else if($r->sambungan == 'GS'){
							$sambungan = 'GLUE STICHING';
						}else if($r->sambungan == 'DS'){
							$sambungan = 'DOUBLE STICHING';
						}else{
							$sambungan = '-';
						}

						($r->tgl_prod_p == null) ? $tgl_prod_p = '-' : $tgl_prod_p = $this->m_fungsi->tglPlan($r->tgl_prod_p);
						($r->good_cor_p == 0) ? $good_cor_p = '-' : $good_cor_p = number_format($r->good_cor_p,0,',','.');
						($r->tgl_prod_f == null) ? $tgl_prod_f = '-' : $tgl_prod_f = $this->m_fungsi->tglPlan($r->tgl_prod_f);
						($r->good_flexo_p == 0) ? $good_flexo_p = '-' : $good_flexo_p = number_format($r->good_flexo_p,0,',','.');

						// $getDt = $this->db->query("SELECT COUNT(id_plan_flexo) AS jml_dt,SUM(durasi_mnt_dt) AS durasi_dt FROM plan_flexo_dt WHERE id_plan_flexo='$r->id_flexo'
						// GROUP BY id_plan_flexo");
						// ($getDt->num_rows() == 0) ? $jml_dt = '-' : $jml_dt = $getDt->row()->jml_dt;
						// ($getDt->num_rows() == 0) ? $durasi_dt = '' : $durasi_dt = ' ( '.$getDt->row()->durasi_dt.'" ) ';

						if($r->total_prod_fs != 0 && $r->status_fs == 'Open'){
							$borBot = ';border-bottom:1px solid #28a746';
						}else if($r->total_prod_fs != 0 && $r->status_fs == 'Close'){
							$borBot = ';border-bottom:1px solid #007bff';
						}else{
							$borBot = '';
						}

						if($r->joint_fs == 'GLUE'){
							$joint_fs = 'G';
						}else if($r->joint_fs == 'STITCHING'){
							$joint_fs = 'S';
						}else if($r->joint_fs == 'DIECUT'){
							$joint_fs = 'D';
						}else{
							$joint_fs = '-';
						}

						$hariIni = substr($this->m_fungsi->getHariIni($r->tgl_fs),0,3);
						
						$html .='<tr class="h-tmpl-list-plan">
							<td style="padding:6px'.$borBot.';position:sticky;left:0;background:#fff">
								<a href="'.base_url('Plan/Finishing/List/').$r->tgl_fs.'/'.$r->shift_fs.'/'.$r->joint_fs.'" class="btn btn-xs bg-gradient-dark" style="padding:0 4px">
								<i class="fa fa-arrow-right"></i>
							</a>
							</td>
							<td style="padding:6px'.$borBot.'">['.$r->shift_fs.'.'.$joint_fs.'] '.strtoupper($hariIni).', '.strtoupper($this->m_fungsi->tglIndSkt($r->tgl_fs)).'</td>
							<td style="padding:6px'.$borBot.'">'.$r->kode_mc.'</td>
							<td style="padding:6px'.$borBot.'">'.$r->kualitas.'</td>
							<td style="padding:6px'.$borBot.';font-weight:bold;color:#ff0066">'.number_format($r->panjang_plan,0,",",".").'</td>
							<td style="padding:6px'.$borBot.';font-weight:bold;color:#ff0066">'.number_format($r->lebar_plan,0,",",".").'</td>
							<td style="padding:6px'.$borBot.'">'.$r->berat_bersih.'</td>
							<td style="padding:6px'.$borBot.'">'.$r->flute.'</td>
							<td style="padding:6px'.$borBot.'">'.$sambungan.'</td>
							<td style="padding:6px'.$borBot.';font-weight:bold">'.number_format($r->pcs_plan,0,',','.').'</td>
							<td style="padding:6px'.$borBot.';color:#f00">'.$this->m_fungsi->tglPlan($r->tgl_kirim_plan).'</td>
							<td style="padding:6px'.$borBot.'">'.$tgl_prod_p.'</td>
							<td style="padding:6px'.$borBot.'">'.$good_cor_p.'</td>
							<td style="padding:6px'.$borBot.'">'.$tgl_prod_f.'</td>
							<td style="padding:6px'.$borBot.'">'.$good_flexo_p.'</td>
							<td style="padding:6px'.$borBot.'">'.number_format($r->good_fs_p,0,",",".").'</td>
							<td style="padding:6px'.$borBot.'">'.number_format($r->bad_fs_p,0,",",".").'</td>
							<td style="padding:6px'.$borBot.'">'.number_format($r->bad_bahan_fs_p,0,",",".").'</td>
							<td style="padding:6px'.$borBot.'">'.$r->ket_fs_p.'</td>
						</tr>';
						$sumGood += $r->good_fs_p;
					}

					if($data->num_rows() > 1){
						$html .='<tr>
							<td style="border:0;background:#fff;padding:6px;font-weight:bold;text-align:right" colspan="15">TOTAL PRODUKSI</td>
							<td style="border:0;background:#fff;padding:6px;font-weight:bold;text-align:right">'.number_format($sumGood,0,",",".").'</td>
						</tr>';
					}

					$html .='
				</table>
			</div>';
		}

		echo $html;
	}

	function loadDataAllPlanFinishing()
	{
		$urlTglF = $_POST["urlTglFs"];
		$urlShiftF = $_POST["urlShiftFs"];
		$urlJointF = $_POST["urlJointFs"];
		$html = '';

		$allPlanFinishing = $this->db->query("SELECT f.tgl_fs,f.shift_fs,f.joint_fs,
		(SELECT COUNT(o.id_fs) FROM plan_finishing o WHERE f.tgl_fs=o.tgl_fs AND f.shift_fs=o.shift_fs AND f.joint_fs=o.joint_fs
		GROUP BY o.tgl_fs,o.shift_fs,o.joint_fs) AS jml_plan_fs,
		(SELECT COUNT(o.id_fs) FROM plan_finishing o WHERE f.tgl_fs=o.tgl_fs AND f.shift_fs=o.shift_fs AND f.joint_fs=o.joint_fs AND o.total_prod_fs!='0' AND o.status_fs='Open'
		GROUP BY o.tgl_fs,o.shift_fs,o.joint_fs) AS jml_prod,
		(SELECT COUNT(o.id_fs) FROM plan_finishing o WHERE f.tgl_fs=o.tgl_fs AND f.shift_fs=o.shift_fs AND f.joint_fs=o.joint_fs AND o.total_prod_fs!='0' AND o.status_fs='Close'
		GROUP BY o.tgl_fs,o.shift_fs,o.joint_fs) AS done_prod,
		(SELECT COUNT(o.id_fs) FROM plan_finishing o
		INNER JOIN plan_flexo fx ON o.id_plan_flexo=fx.id_flexo AND o.id_plan_cor=fx.id_plan_cor WHERE f.tgl_fs=o.tgl_fs AND f.shift_fs=o.shift_fs AND f.joint_fs=o.joint_fs AND o.total_prod_fs!='0' AND o.status_fs='Close' AND fx.status_stt_f='Close'
		GROUP BY o.tgl_fs,o.shift_fs,o.joint_fs) AS close_plan_flexo
		FROM plan_finishing f
		INNER JOIN plan_flexo fx ON f.id_plan_cor=fx.id_plan_cor AND f.id_plan_flexo=fx.id_flexo
		WHERE f.status_fs='Open' OR fx.status_stt_f='Open'
		GROUP BY f.tgl_fs,f.shift_fs,f.joint_fs");
		$html .='<div id="accordion-h-plan-finishing">
			<div style="padding:6px;font-weight:bold">
				[SHIFT.JOINT] HARI, TANGGAL <span class="bg-light" style="vertical-align:top;padding:2px 4px;font-size:12px">JML PLAN FINISHING</span><span class="bg-success" style="vertical-align:top;padding:2px 4px;font-size:12px">PRODUKSI</span><span class="bg-primary" style="vertical-align:top;padding:2px 4px;font-size:12px">SELESAI</span><span class="bg-dark" style="vertical-align:top;padding:2px 4px;font-size:12px">CLOSE PLAN FLEXO</span>
			</div>';
			foreach($allPlanFinishing->result() as $r){

				if($urlTglF == $r->tgl_fs && $urlShiftF == $r->shift_fs && $urlJointF == $r->joint_fs){
					$html .='';
				}else{
					$sTgl = str_replace('-', '', $r->tgl_fs);

					$jml_plan = '<span class="bg-light" style="vertical-align:top;padding:2px 4px;font-size:12px">'.$r->jml_plan_fs.'</span>';
					($r->jml_prod == null) ? $jml_prod = '' : $jml_prod = '<span class="bg-success" style="vertical-align:top;padding:2px 4px;font-size:12px">'.$r->jml_prod.'</span>';
					($r->done_prod == null) ? $done_prod = '' : $done_prod = '<span class="bg-primary" style="vertical-align:top;padding:2px 4px;font-size:12px">'.$r->done_prod.'</span>';
					($r->close_plan_flexo == null) ? $close_plan_flexo = '' : $close_plan_flexo = '<span class="bg-dark" style="vertical-align:top;padding:2px 4px;font-size:12px">'.$r->close_plan_flexo.'</span>';

					if($r->joint_fs == 'GLUE'){
						$joint_fs = 'G';
					}else if($r->joint_fs == 'STITCHING'){
						$joint_fs = 'S';
					}else if($r->joint_fs == 'DIECUT'){
						$joint_fs = 'D';
					}else{
						$joint_fs = '-';
					}

					$html .='<div class="card m-0" style="border-radius:0">
						<div class="card-header bg-gradient-info" style="padding:0;border-radius:0">
							<a class="d-block w-100 link-h-wo" style="font-weight:bold;padding:6px" data-toggle="collapse" href="#collapseHeaderPlanFs'.$sTgl.''.$r->shift_fs.''.$r->joint_fs.'" onclick="loadListPlanFinishing('."'".$r->tgl_fs."'".','."'".$r->shift_fs."'".','."'".$r->joint_fs."'".','."'pilihan'".')">
								['.$r->shift_fs.'.'.$joint_fs.'] '.strtoupper($this->m_fungsi->getHariIni($r->tgl_fs)).', '.strtoupper($this->m_fungsi->tanggal_format_indonesia($r->tgl_fs)).' '.$jml_plan.''.$jml_prod.''.$done_prod.''.$close_plan_flexo.'
							</a>
						</div>
						<div id="collapseHeaderPlanFs'.$sTgl.''.$r->shift_fs.''.$r->joint_fs.'" class="collapse" data-parent="#accordion-h-plan-finishing">
							<div id="tampil-all-ffss-isi-'.$sTgl.''.$r->shift_fs.''.$r->joint_fs.'"></div>
						</div>
					</div>';
				}
			}
		$html .='</div>';

		echo $html;
	}

	function loadDataPlanFinishing()
	{
		$result = $this->m_plan->loadDataPlanFinishing();
		echo json_encode($result);
	}

	function destroyPlanFinishing()
	{
		$this->cart->destroy();
	}

	function hapusCartFinishing()
	{
		$data = array(
			'rowid' => $_POST['rowid'],
			'qty' => 0,
		);
		$this->cart->update($data);
	}

	function produksiPlanFinishing()
	{
		$result = $this->m_plan->produksiPlanFinishing();
		echo json_encode($result);
	}

	function hapusPlanFinishing()
	{
		$result = $this->m_plan->hapusPlanFinishing();
		echo json_encode($result);
	}

	function btnGantiTglFinishing()
	{
		$result = $this->m_plan->btnGantiTglFinishing();
		echo json_encode($result);
	}

	function onChangeNourutFinishing()
	{
		$result = $this->m_plan->onChangeNourutFinishing();
		echo json_encode($result);
	}

	function clickDonePlanCorFlexoFs()
	{
		$result = $this->m_plan->clickDonePlanCorFlexoFs();
		echo json_encode($result);
	}

	function addRencanaFinishing()
	{
		$data = array(
			'id' => $_POST["plan_flexo"],
			'name' => $_POST["plan_flexo"],
			'price' => 0,
			'qty' => 1,
			'options' => array(
				'id_plan_cor' => $_POST["opIdPlanCor"],
				'tgl' => $_POST["tgl"],
				'shift' => $_POST["shift"],
				'joint' => $_POST["joint"],
				'no_wo' => $_POST["no_wo"],
				'no_po' => $_POST["no_po"],
				'customer' => $_POST["customer"],
				'kode_mc' => $_POST["kode_mc"],
				'item' => $_POST["item"],
				'uk_box' => $_POST["uk_box"],
				'uk_sheet' => $_POST["uk_sheet"],
				'creasing_1' => $_POST["creasing_1"],
				'creasing_2' => $_POST["creasing_2"],
				'creasing_3' => $_POST["creasing_3"],
				'kualitas' => $_POST["kualitas"],
				'flute' => $_POST["flute"],
				'tipe_box' => $_POST["tipe_box"],
				'sambungan' => $_POST["sambungan"],
				'bb_box' => $_POST["bb_box"],
				'lb_box' => $_POST["lb_box"],
				'panjang_plan' => $_POST["panjang_plan"],
				'lebar_plan' => $_POST["lebar_plan"],
				'order_so' => $_POST["order_so"],
				'kirim' => $_POST["kirim"],
				'tgl_cor' => $_POST["tgl_cor"],
				'qty_cor' => $_POST["qty_cor"],
				'tgl_flexo' => $_POST["tgl_flexo"],
				'qty_flexo' => $_POST["qty_flexo"],
			)
		);

		if($_POST["opsi"] == 'add'){
			$id_plan_flexo = $_POST["plan_flexo"];
			$id_plan_cor = $_POST["opIdPlanCor"];
			$tgl = $_POST["tgl"];
			$shift = $_POST["shift"];
			$joint = $_POST["joint"];
			$cekPlanFlexo = $this->db->query("SELECT*FROM plan_flexo WHERE id_flexo='$id_plan_flexo'")->row();
			$cekHariPlan = $this->db->query("SELECT*FROM plan_finishing WHERE id_plan_cor='$id_plan_cor' AND id_plan_flexo='$id_plan_flexo' AND tgl_fs='$tgl' AND shift_fs='$shift' AND joint_fs='$joint'")->num_rows();
			if($_POST["plan_flexo"] == ""){
				echo json_encode(array('data' => false, 'isi' => '<b>PILIH PLAN FLEXO DAHULU!</b>')); return;
			}else if($tgl < $cekPlanFlexo->tgl_flexo){
				echo json_encode(array('data' => false, 'isi' => 'TGL PLAN FINISHING TIDAK BOLEH KURANG DARI TANGGAL PLAN FLEXO!', 'cekPlanFlexo' => $cekPlanFlexo)); return;
			}else if($cekHariPlan > 0){
				echo json_encode(array('data' => false, 'isi' => 'PLAN FINISHING SUDAH ADA DI TGL / SHIFT YANG SAMA!', 'cekHariPlan' => $cekHariPlan)); return;
			}else{
				if($this->cart->total_items() != 0){
					foreach($this->cart->contents() as $r){
						if($r['id'] == $_POST["plan_flexo"]){
							echo json_encode(array('data' => false, 'isi' => 'PLAN FLEXO SUDAH MASUK LIST PLAN FINISHING!')); return;
						}
					}
					$this->cart->insert($data);
					echo json_encode(array('data' => true, 'isi' => $data));
				}else{
					$this->cart->insert($data);
					echo json_encode(array('data' => true, 'isi' => $data));
				}
			}
		}else{
			$result = $this->m_plan->addRencanaFinishing();
			echo json_encode($result);
		}
	}

	function ListInputFinishing()
	{
		$html = '';
		if($this->cart->total_items() != 0){
			$html .= '<div class="card card-success card-outline">
				<div class="card-header">
					<h3 class="card-title" style="font-weight:bold;font-style:italic">LIST PLAN FINISHING BARU</h3>
				</div>
				<div style="overflow:auto;white-space:nowrap;padding-bottom:15px">
					<table class="table table-bordered table-striped" style="text-align:center">
						<thead>
							<tr>
								<th style="padding:12px 6px">#</th>
								<th style="padding:12px 6px">KODE.MC</th>
								<th style="padding:12px 6px">NO.WO</th>
								<th style="padding:12px 6px">CUSTOMER</th>
								<th style="padding:12px 6px">ITEM</th>
								<th style="padding:12px 6px">KUALITAS</th>
								<th style="padding:12px 6px">PANJANG</th>
								<th style="padding:12px 6px">LEBAR</th>
								<th style="padding:12px 6px">BB</th>
								<th style="padding:12px 6px">FT</th>
								<th style="padding:12px 6px">JOINT</th>
								<th style="padding:12px 6px">ORDER</th>
								<th style="padding:12px 6px">TGL KIRIM</th>
								<th style="padding:12px 6px">KL.CR TGL</th>
								<th style="padding:12px 6px">KL.CR QTY</th>
								<th style="padding:12px 6px">KL.FX TGL</th>
								<th style="padding:12px 6px">KL.FX QTY</th>
								<th style="padding:12px 6px">AKSI</th>
							</tr>
						</thead>';}
						$i = 0;
						foreach($this->cart->contents() as $r){
							$i++;
							($r['options']['tgl_cor'] != "") ? $tglHasil = $this->m_fungsi->tglPlan($r['options']['tgl_cor']) : $tglHasil = '-';
							($r['options']['qty_cor'] > 0) ? $hasil = number_format($r['options']['qty_cor'],0,',','.') : $hasil = 0;
							($r['options']['tgl_flexo'] != "") ? $tglHasilFs = $this->m_fungsi->tglPlan($r['options']['tgl_flexo']) : $tglHasilFs = '-';
							($r['options']['qty_flexo'] > 0) ? $hasilFs = number_format($r['options']['qty_flexo'],0,',','.') : $hasilFs = 0;
							$html.='<tr>
								<td style="padding:6px">'.$i.'</td>
								<td style="padding:6px;text-align:left">'.$r['options']['kode_mc'].'</td>
								<td style="padding:6px;text-align:left">'.$r['options']['no_wo'].'</td>
								<td style="padding:6px;text-align:left">'.$r['options']['customer'].'</td>
								<td style="padding:6px;text-align:left">'.$r['options']['item'].'</td>
								<td style="padding:6px">'.$r['options']['kualitas'].'</td>
								<td style="padding:6px">'.$r['options']['panjang_plan'].'</td>
								<td style="padding:6px">'.$r['options']['lebar_plan'].'</td>
								<td style="padding:6px">'.$r['options']['bb_box'].'</td>
								<td style="padding:6px">'.$r['options']['flute'].'</td>
								<td style="padding:6px">'.$r['options']['sambungan'].'</td>
								<td style="padding:6px">'.number_format($r['options']['order_so'],0,',','.').'</td>
								<td style="padding:6px">'.$this->m_fungsi->tglPlan($r['options']['kirim']).'</td>
								<td style="padding:6px">'.$tglHasil.'</td>
								<td style="padding:6px">'.$hasil.'</td>
								<td style="padding:6px">'.$tglHasilFs.'</td>
								<td style="padding:6px">'.$hasilFs.'</td>
								<td style="padding:3px 6px">
									<button class="btn btn-xs btn-danger" onclick="hapusCartFinishing('."'".$r['rowid']."'".')"><i class="fas fa-times"></i> BATAL</button>
								</td>
							</tr>';
						}
						if($this->cart->total_items() != 0){
						$html .='
					</table>
					<button class="btn btn-sm btn-primary" id="simpan-cart-fs" style="margin-left:20px" onclick="simpanCartFinishing()"><i class="fas fa-save"></i> SIMPAN</button>
				</div>
			</div>';
		}
		echo $html;
	}

	function simpanCartFinishing()
	{
		$result = $this->m_plan->simpanCartFinishing();
		echo json_encode($result);
	}

	function loadListPlanFinishing()
	{
		$tglF = $_POST["tglF"];
		$shiftF = $_POST["shiftF"];
		$jointF = $_POST["jointF"];
		$id_finishing = $_POST["opsi"];
		$html = '';

		if($id_finishing != 'pilihan'){
			$ff = $this->db->query("SELECT f.tgl_fs,f.shift_fs,f.joint_fs,
			(SELECT COUNT(o.id_fs) FROM plan_finishing o WHERE f.tgl_fs=o.tgl_fs AND f.shift_fs=o.shift_fs AND f.joint_fs=o.joint_fs
			GROUP BY o.tgl_fs,o.shift_fs,o.joint_fs) AS jml_plan_fs,
			(SELECT COUNT(o.id_fs) FROM plan_finishing o WHERE f.tgl_fs=o.tgl_fs AND f.shift_fs=o.shift_fs AND f.joint_fs=o.joint_fs AND o.total_prod_fs!='0' AND o.status_fs='Open'
			GROUP BY o.tgl_fs,o.shift_fs,o.joint_fs) AS jml_prod,
			(SELECT COUNT(o.id_fs) FROM plan_finishing o WHERE f.tgl_fs=o.tgl_fs AND f.shift_fs=o.shift_fs AND f.joint_fs=o.joint_fs AND o.total_prod_fs!='0' AND o.status_fs='Close'
			GROUP BY o.tgl_fs,o.shift_fs,o.joint_fs) AS done_prod,
			(SELECT COUNT(o.id_fs) FROM plan_finishing o
			INNER JOIN plan_flexo fx ON o.id_plan_flexo=fx.id_flexo AND o.id_plan_cor=fx.id_plan_cor WHERE f.tgl_fs=o.tgl_fs AND f.shift_fs=o.shift_fs AND f.joint_fs=o.joint_fs AND o.total_prod_fs!='0' AND o.status_fs='Close' AND fx.status_stt_f='Close'
			GROUP BY o.tgl_fs,o.shift_fs,o.joint_fs) AS close_plan_flexo
			FROM plan_finishing f
			WHERE f.tgl_fs='$tglF' AND shift_fs='$shiftF' AND joint_fs='$jointF'
			GROUP BY f.tgl_fs,f.shift_fs,f.joint_fs")->row();

			$jml_plan = '<span class="bg-light" style="font-weight:bold;vertical-align:top;padding:2px 4px;font-size:12px">'.$ff->jml_plan_fs.'</span>';
			($ff->jml_prod == null) ? $jml_prod = '' : $jml_prod = '<span class="bg-success" style="font-weight:bold;vertical-align:top;padding:2px 4px;font-size:12px">'.$ff->jml_prod.'</span>';
			($ff->done_prod == null) ? $done_prod = '' : $done_prod = '<span class="bg-primary" style="font-weight:bold;vertical-align:top;padding:2px 4px;font-size:12px">'.$ff->done_prod.'</span>';
			($ff->close_plan_flexo == null) ? $close_plan_flexo = '' : $close_plan_flexo = '<span class="bg-dark" style="font-weight:bold;vertical-align:top;padding:2px 4px;font-size:12px">'.$ff->close_plan_flexo.'</span>';

			if($ff->joint_fs == 'GLUE'){
				$joint_fs = 'G';
			}else if($ff->joint_fs == 'STITCHING'){
				$joint_fs = 'S';
			}else if($ff->joint_fs == 'DIECUT'){
				$joint_fs = 'D';
			}else{
				$joint_fs = '-';
			}

			$html .= '<div class="card card-primary card-outline">
			<div class="card-header" style="font-style:italic">
				<h3 class="card-title" style="font-weight:bold">LIST PLAN FINISHING - </h3>
				&nbsp;['.$ff->shift_fs.'.'.$joint_fs.'] '.strtoupper($this->m_fungsi->getHariIni($ff->tgl_fs)).', '.strtoupper($this->m_fungsi->tanggal_format_indonesia($ff->tgl_fs)).' '.$jml_plan.''.$jml_prod.''.$done_prod.''.$close_plan_flexo.'
			</div>';
		}

		if($id_finishing == 'pilihan'){
			$keHal = '<a href="'.base_url('Plan/Finishing/List').'/'.$tglF.'/'.$shiftF.'/'.$jointF.'" class="btn btn-xs bg-gradient-dark" style="padding:0 4px">
				<i class="fa fa-arrow-right"></i>
			</a>';
			$left = ';left:40px';
		}else{
			$keHal = '#';
			$left = ';left:32px';
		}
		
		$html .= '<div style="overflow:auto;white-space:nowrap;padding-bottom:5px">
				<table class="table table-bordered" style="text-align:center">
					<thead>
						<tr>
							<th style="padding:6px 12px;background:#fff;position:sticky;left:0">'.$keHal.'</th>
							<th style="padding:6px">STATUS</th>
							<th style="padding:6px">KODE.MC</th>
							<th style="padding:6px;background:#fff;position:sticky'.$left.'">NO.WO</th>
							<th style="padding:6px">CUSTOMER</th>
							<th style="padding:6px">ITEM</th>
							<th style="padding:6px">KUALITAS</th>
							<th style="padding:6px">PANJANG</th>
							<th style="padding:6px">LEBAR</th>
							<th style="padding:6px">BB</th>
							<th style="padding:6px">FT</th>
							<th style="padding:6px">JOINT</th>
							<th style="padding:6px">ORDER</th>
							<th style="padding:6px">HASIL</th>
							<th style="padding:6px">START</th>
							<th style="padding:6px 12px">END</th>
							<th style="padding:6px">TGL KIRIM</th>
							<th style="padding:6px">KL.CR TGL</th>
							<th style="padding:6px">KL.CR QTY</th>
							<th style="padding:6px">KL.FX TGL</th>
							<th style="padding:6px">KL.FX QTY</th>
						</tr>
					</thead>';

					$data = $this->db->query("SELECT fs.*,i.*,pc.*,f.*,so.qty_so,c.nm_pelanggan FROM plan_finishing fs
					INNER JOIN plan_flexo f ON fs.id_plan_flexo=f.id_flexo
					INNER JOIN plan_cor pc ON fs.id_plan_cor=pc.id_plan
					INNER JOIN trs_so_detail so ON pc.id_so_detail=so.id
					INNER JOIN m_produk i ON pc.id_produk=i.id_produk
					INNER JOIN m_pelanggan c ON pc.id_pelanggan=c.id_pelanggan
					WHERE fs.tgl_fs='$tglF' AND fs.shift_fs='$shiftF' AND fs.joint_fs='$jointF'
					ORDER BY fs.no_urut_fs,c.nm_pelanggan,fs.id_fs");
					foreach($data->result() as $r){
						if($_POST["hidplan"] == $r->id_fs){
							$bgTd = 'class="h-tlp-td"';
						}else{
							$bgTd = 'class="h-tlpf-td"';
						}

						if($r->sambungan == 'G'){
							$sambungan = 'GLUE';
						}else if($r->sambungan == 'D'){
							$sambungan = 'DIECUT';
						}else if($r->sambungan == 'S'){
							$sambungan = 'STICHING';
						}else if($r->sambungan == 'GS'){
							$sambungan = 'GLUE STICHING';
						}else if($r->sambungan == 'DS'){
							$sambungan = 'DOUBLE STICHING';
						}else{
							$sambungan = '-';
						}

						if($r->total_prod_fs > 0){
							$hasilF = number_format($r->good_fs_p,0,',','.');
							$startF = substr($r->start_time_fs,0,5);
							$endF = substr($r->end_time_fs,0,5);
						}else{
							$hasilF = '-';
							$startF = '-';
							$endF = '-';
						}

						if($r->tgl_prod_p != "" && $r->good_cor_p != 0){
							$tgl_prod_p = $this->m_fungsi->tglPlan($r->tgl_prod_p);
							$good_cor_p = number_format($r->good_cor_p,0,",",".");
						}else{
							$tgl_prod_p = '-';
							$good_cor_p = '-';
						}

						if($r->tgl_prod_f != "" && $r->good_flexo_p != 0){
							$tgl_prod_f = $this->m_fungsi->tglPlan($r->tgl_prod_p);
							$good_flexo_p = number_format($r->good_flexo_p,0,",",".");
						}else{
							$tgl_prod_f = '-';
							$good_flexo_p = '-';
						}

						if($r->status_fs == 'Open' && $r->total_prod_fs == 0){
							if($id_finishing == 'pilihan'){
								$statusF = '<span class="bg-danger" style="padding:2px 4px;border-radius:4px;display:block">HAPUS</span>';
							}else{
								if(in_array($this->session->userdata('level'), ['Admin','PPIC','User'])){
									$statusF = '<a href="javascript:void(0)" onclick="hapusPlanFinishing('."".$r->id_fs."".')" href="" class="bg-danger" style="padding:2px 4px;border-radius:4px;display:block">HAPUS</a>';
								}else{
									$statusF = '<span class="bg-danger" style="padding:2px 4px;border-radius:4px;display:block">HAPUS</span>';
								}
							}
						}else if($r->status_fs == 'Open' && $r->total_prod_fs != 0){
							$statusF = '<span class="bg-success" style="padding:2px 4px;border-radius:4px;display:block">PRODUKSI</span>';
						}else if($r->status_fs == 'Close' && $r->status_stt_f == 'Open' && $r->total_prod_fs != 0){
							$statusF = '<span class="bg-primary" style="padding:2px 4px;border-radius:4px;display:block">SELESAI</span>';
						}else{
							$statusF = '<span class="bg-dark" style="padding:2px 4px;border-radius:4px;display:block">SELESAI</span>';
						}

						($id_finishing == 'pilihan') ? $plhPlanFlexo = $r->no_wo : $plhPlanFlexo = '<a href="javascript:void(0)" onclick="plhPlanFlexo('."".$r->id_fs."".')" title="'."".$r->no_wo."".'">'.$r->no_wo.'</a>';

						if($id_finishing == 'pilihan'){
							$ubahNoUrut = 'disabled';
						}else{
							if(in_array($this->session->userdata('level'), ['Admin','PPIC','User'])){
								($r->total_prod_fs != 0) ? $ubahNoUrut = 'disabled' : $ubahNoUrut = 'onkeyup="onChangeNourutFinishing('."'".$r->id_fs."'".')"';
							}else{
								$ubahNoUrut = 'disabled';
							}
						}

						$html .='<tr class="h-tmpl-list-plan">
							<td '.$bgTd.' style="padding:6px;position:sticky;left:0">
								<input type="number" class="form-control inp-kosong2" id="lp-nourut-fs-'.$r->id_fs.'" value="'.$r->no_urut_fs.'" '.$ubahNoUrut.'>
							</td>
							<td '.$bgTd.' style="padding:3px">'.$statusF.'</td>
							<td '.$bgTd.' style="padding:6px;text-align:left">'.$r->kode_mc.'</td>
							<td '.$bgTd.' style="padding:6px;text-align:left;position:sticky'.$left.'">'.$plhPlanFlexo.'</td>
							<td '.$bgTd.' style="padding:6px;text-align:left">'.$r->nm_pelanggan.'</td>
							<td '.$bgTd.' style="padding:6px;text-align:left">'.$r->nm_produk.'</td>
							<td '.$bgTd.' style="padding:6px">'.$r->kualitas_plan.'</td>
							<td '.$bgTd.' style="padding:6px;color:#f00;font-weight:bold">'.number_format($r->panjang_plan,0,",",".").'</td>
							<td '.$bgTd.' style="padding:6px;color:#f00;font-weight:bold">'.number_format($r->lebar_plan,0,",",".").'</td>
							<td '.$bgTd.' style="padding:6px">'.$r->berat_bersih.'</td>
							<td '.$bgTd.' style="padding:6px">'.$r->flute.'</td>
							<td '.$bgTd.' style="padding:6px">'.$sambungan.'</td>
							<td '.$bgTd.' style="padding:6px;font-weight:bold">'.number_format($r->qty_so,0,",",".").'</td>
							<td '.$bgTd.' style="padding:6px">'.$hasilF.'</td>
							<td '.$bgTd.' style="padding:6px">'.$startF.'</td>
							<td '.$bgTd.' style="padding:6px">'.$endF.'</td>
							<td '.$bgTd.' style="padding:6px;color:#f00;font-weight:bold">'.$this->m_fungsi->tglPlan($r->tgl_kirim_plan).'</td>
							<td '.$bgTd.' style="padding:6px">'.$tgl_prod_p.'</td>
							<td '.$bgTd.' style="padding:6px">'.$good_cor_p.'</td>
							<td '.$bgTd.' style="padding:6px">'.$tgl_prod_f.'</td>
							<td '.$bgTd.' style="padding:6px">'.$good_flexo_p.'</td>
						</tr>';
					}

					$html .='
				</table>
			</div>
		</div>';

		echo $html;
	}

	function laporanPlanFinishing()
	{
		$tgl = $_GET["tgl"];
		$shift = $_GET["shift"];
		$joint = $_GET["joint"];
		$html = '';

		$html .= '<table style="margin:0;padding:0;font-size:10px;text-align:center;border-collapse:collapse;color:#000;width:100%">
			<thead>
				<tr>
					<th style="border:0;width:2%"></th>
					<th style="border:0;width:6%"></th>
					<th style="border:0;width:10%"></th>
					<th style="border:0;width:10%"></th>
					<th style="border:0;width:8%"></th>
					<th style="border:0;width:4%"></th>
					<th style="border:0;width:4%"></th>
					<th style="border:0;width:4%"></th>
					<th style="border:0;width:2%"></th>
					<th style="border:0;width:6%"></th>
					<th style="border:0;width:5%"></th>
					<th style="border:0;width:6%"></th>
					<th style="border:0;width:5%"></th>
					<th style="border:0;width:5%"></th>
					<th style="border:0;width:5%"></th>
					<th style="border:0;width:5%"></th>
					<th style="border:0;width:13%"></th>
				</tr>
				<tr>
					<th style="font-size:16px;padding-bottom:10px" colspan="17">PLAN FINISHING '.$joint.'</th>
				</tr>
				<tr>
					<th style="border:1px solid #000;padding:8px 0">NO</th>
					<th style="border:1px solid #000;padding:8px 0">KODE MC</th>
					<th style="border:1px solid #000;padding:8px 0">CUSTOMER</th>
					<th style="border:1px solid #000;padding:8px 0">ITEM</th>
					<th style="border:1px solid #000;padding:8px 0">KUALITAS</th>
					<th style="border:1px solid #000;padding:8px 0;color:#f00">PJG</th>
					<th style="border:1px solid #000;padding:8px 0;color:#f00">LBR</th>
					<th style="border:1px solid #000;padding:8px 0">BB</th>
					<th style="border:1px solid #000;padding:8px 0">FT</th>
					<th style="border:1px solid #000;padding:8px 0">JOINT</th>
					<th style="border:1px solid #000;padding:8px 0">ORDER</th>
					<th style="border:1px solid #000;padding:8px 0;color:#f00">TGL KIRIM</th>
					<th style="border:1px solid #000;padding:8px 0" colspan="2">KELUAR COR</th>
					<th style="border:1px solid #000;padding:8px 0" colspan="2">KELUAR FLEXO</th>
					<th style="border:1px solid #000;padding:8px 0">KETERANGAN</th>
				</tr>
				<tr>
					<th style="border:1px solid #000;border-width:1px 0 1px 1px;padding:7px 0;font-size:14px" colspan="9">PLAN '.strtoupper($this->m_fungsi->getHariIni($tgl)).' '.strtoupper($this->m_fungsi->tanggal_format_indonesia($tgl)).'</th>
					<th style="border:1px solid #000;border-width:1px 1px 1px 0" colspan="3"></th>
					<th style="border:1px solid #000">TGL</th>
					<th style="border:1px solid #000">QTY</th>
					<th style="border:1px solid #000">TGL</th>
					<th style="border:1px solid #000">QTY</th>
					<th style="border:1px solid #000"></th>
				</tr>
			</thead>
			<tbody>';

			($joint == 'STITCHING') ? $whereJoint = "AND fs.joint_fs LIKE '%$joint%'": $whereJoint = "AND fs.joint_fs='$joint'";
			$data = $this->db->query("SELECT fs.*,i.*,pc.*,f.*,so.qty_so,so.kode_po,c.nm_pelanggan FROM plan_finishing fs
			INNER JOIN plan_flexo f ON fs.id_plan_flexo=f.id_flexo
			INNER JOIN plan_cor pc ON fs.id_plan_cor=pc.id_plan
			INNER JOIN trs_so_detail so ON pc.id_so_detail=so.id
			INNER JOIN m_produk i ON pc.id_produk=i.id_produk
			INNER JOIN m_pelanggan c ON pc.id_pelanggan=c.id_pelanggan
			WHERE fs.tgl_fs='$tgl' AND fs.shift_fs='$shift' $whereJoint
			ORDER BY fs.no_urut_fs,c.nm_pelanggan");
			$i = 0;
			$sumQtySo = 0;
			foreach($data->result() as $r){
				$i++;
				$expKualitas = explode("/", $r->kualitas_plan);
				if($r->flute == 'BCF'){
					if($expKualitas[1] == 'M125' && $expKualitas[2] == 'M125' && $expKualitas[3] == 'M125'){
						$kualitas = $expKualitas[0].'/'.$expKualitas[1].'x3/'.$expKualitas[4];
					}else if($expKualitas[1] == 'K125' && $expKualitas[2] == 'K125' && $expKualitas[3] == 'K125'){
						$kualitas = $expKualitas[0].'/'.$expKualitas[1].'x3/'.$expKualitas[4];
					}else if($expKualitas[1] == 'M150' && $expKualitas[2] == 'M150' && $expKualitas[3] == 'M150'){
						$kualitas = $expKualitas[0].'/'.$expKualitas[1].'x3/'.$expKualitas[4];
					}else if($expKualitas[1] == 'K150' && $expKualitas[2] == 'K150' && $expKualitas[3] == 'K150'){
						$kualitas = $expKualitas[0].'/'.$expKualitas[1].'x3/'.$expKualitas[4];
					}else{
						$kualitas = $r->kualitas;
					}
				}else{
					$kualitas = $r->kualitas;
				}

				($r->flute == 'BCF') ? $flute = 'BC' : $flute = $r->flute;

				if($r->tgl_prod_p != "" && $r->good_cor_p != 0){
					$tgl_prod_p = $this->m_fungsi->tglPlan($r->tgl_prod_p);
					$good_cor_p = number_format($r->good_cor_p,0,",",".");
				}else{
					$tgl_prod_p = '-';
					$good_cor_p = '-';
				}

				if($r->tgl_prod_f != "" && $r->good_flexo_p != 0){
					$tgl_prod_f = $this->m_fungsi->tglPlan($r->tgl_prod_p);
					$good_flexo_p = number_format($r->good_flexo_p,0,",",".");
				}else{
					$tgl_prod_f = '-';
					$good_flexo_p = '-';
				}

				$html.='<tr>
					<td style="border:1px solid #000">'.$i.'</td>
					<td style="border:1px solid #000;text-align:left">'.$r->kode_mc.'</td>
					<td style="border:1px solid #000;text-align:left">'.$r->nm_pelanggan.'</td>
					<td style="border:1px solid #000;text-align:left">'.$r->nm_produk.'</td>
					<td style="border:1px solid #000">'.$kualitas.'</td>
					<td style="border:1px solid #000;font-weight:bold;color:#f00">'.number_format($r->panjang_plan,0,",",".").'</td>
					<td style="border:1px solid #000;font-weight:bold;color:#f00">'.number_format($r->lebar_plan,0,",",".").'</td>
					<td style="border:1px solid #000">'.$r->berat_bersih.'</td>
					<td style="border:1px solid #000">'.$flute.'</td>
					<td style="border:1px solid #000">'.$joint.'</td>
					<td style="border:1px solid #000;font-weight:bold">'.number_format($r->qty_so,0,",",".").'</td>
					<td style="border:1px solid #000;font-weight:bold;color:#f00">'.$this->m_fungsi->tglPlan($r->tgl_kirim_plan).'</td>
					<td style="border:1px solid #000">'.$tgl_prod_p.'</td>
					<td style="border:1px solid #000">'.$good_cor_p.'</td>
					<td style="border:1px solid #000">'.$tgl_prod_f.'</td>
					<td style="border:1px solid #000">'.$good_flexo_p.'</td>
					<td style="border:1px solid #000;text-align:left">'.$r->ket_fs_p.'</td>
				</tr>';

				$sumQtySo += $r->qty_so;
			}

			$html .='<tr>
				<td style="border:1px solid #000" colspan="10"></td>
				<td style="border:1px solid #000;padding:8px 0;color:#f00;font-weight:bold">'.number_format($sumQtySo,0,",",".").'</td>
				<td style="border:1px solid #000" colspan="6"></td>
			</tr>';

			$html .='</tbody>
		</table>';

		$judulTglFlexo = str_replace('/', '', $this->m_fungsi->tglPlan($tgl));
		$judul = 'PLANFINISHING-'.$judulTglFlexo.'.'.$shift.'.'.$joint;
		$this->m_fungsi->newMpdf($judul, '', $html, 1, 3, 1, 3, 'L', 'A4', $judul.'.pdf');
	}

	function riwayatFinishing()
	{
		$html = '';
		$result = $this->m_plan->riwayatFinishing();
		
		if($result->num_rows() == 0){
			$html .='';
		}else{
			$html .='<div class="card card-warning card-outline">
				<div class="card-header">
					<h3 class="card-title" style="font-weight:bold;font-style:italic">RIWAYAT FINISHING</h3>
				</div>
				<div style="overflow:auto;white-space:nowrap">
					<table class="table table-bordered table-striped" style="border:0;text-align:center">
						<thead>
							<tr>
								<th style="padding:12px 6px">#</th>
								<th style="padding:12px 6px;text-align:left">TGL FINISHING</th>
								<th style="padding:12px 6px">HASIL</th>
								<th style="padding:12px 6px">REJECT FINISHING</th>
								<th style="padding:12px 6px">REJECT BAHAN</th>
								<th style="padding:12px 6px">TOTAL</th>
								<th style="padding:12px 6px">START</th>
								<th style="padding:12px 6px">END</th>
							</tr>
						</thead>';
						$i = 0;
						$sumGood = 0;
						foreach($result->result() as $r){
							$i++;

						// 	if($r->jmlDt == 0){
						// 		$txtDowtime = '-';
						// 	}else{
						// 		$txtDowtime = $r->jmlDt.' ( '.number_format($r->jmlDtDurasi).' )';
						// 	}

							($r->start_time_fs == null) ? $start_time_fs = '-' : $start_time_fs = substr($r->start_time_fs,0,5);
							($r->end_time_fs == null) ? $end_time_fs = '-' : $end_time_fs = substr($r->end_time_fs,0,5);

							if($r->joint_fs == 'GLUE'){
								$joint_fs = 'G';
							}else if($r->joint_fs == 'STITCHING'){
								$joint_fs = 'S';
							}else if($r->joint_fs == 'DIECUT'){
								$joint_fs = 'D';
							}else{
								$joint_fs = '-';
							}

							$html .= '<tr>
								<td style="padding:6px">'.$i.'</td>
								<td style="padding:6px;text-align:left">
								<a href="javascript:void(0)" onclick="showRiwayat('."'".$r->id_plan_cor."'".','."'".$r->id_plan_flexo."'".','."'".$r->id_fs."'".','."'finishing'".')">
									['.$r->shift_fs.'.'.$joint_fs.'] '.strtoupper($this->m_fungsi->getHariIni($r->tgl_fs)).', '.strtoupper($this->m_fungsi->tanggal_format_indonesia($r->tgl_fs)).'
								</a>
								</td>
								<td style="padding:6px;text-align:right">'.number_format($r->good_fs_p,0,",",".").'</td>
								<td style="padding:6px;text-align:right">'.number_format($r->bad_fs_p,0,",",".").'</td>
								<td style="padding:6px;text-align:right">'.number_format($r->bad_bahan_fs_p,0,",",".").'</td>
								<td style="padding:6px;text-align:right">'.number_format($r->total_prod_fs,0,",",".").'</td>
								<td style="padding:6px">'.$start_time_fs.'</td>
								<td style="padding:6px">'.$end_time_fs.'</td>
							</tr>';
							$sumGood += $r->good_fs_p;
						}

						if($result->num_rows() > 1){
							$html .='<tr>
								<td style="border:0;background:#fff;padding:6px;text-align:right;font-weight:bold" colspan="2">TOTAL PORDUKSI</td>
								<td style="border:0;background:#fff;padding:6px;text-align:right;font-weight:bold">'.number_format($sumGood,0,",",".").'</td>
							</tr>';
						}

					$html .='</table>
				</div>
			</div>';
		}
		echo $html;
	}

	//

	function Cetak_plan2()
	{
		$no_plan             = $_GET['no_plan'];
		
		$header    = $this->db->query("SELECT * from plan_cor where no_plan= '$no_plan' order by id_plan LIMIT 1");

		if ($header->num_rows() > 0) {

			$head = $header->row();
			
			$html = '<table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-size:14px;font-family: ;">
                            
					<tr style="font-weight: bold;">
						<td colspan="15" align="center">
						<b> ( ' . $this->m_fungsi->tanggal_format_indonesia($head->tgl_plan) . ' - ' . $head->no_plan . ' )</b>
						</td>
					</tr>
			</table><br>';

			$html .= '<table width="100%" border="1" cellspacing="1" cellpadding="3" style="border-collapse:collapse;font-size:12px;font-family: ;">
				<thead>
				<tr style="background-color: #cccccc">
					<th width="2%" align="center">No</th>
					<th width="5%" align="center">Item</th>
					<th width="5%" align="center">No PO</th>
					<th width="5%" align="center">Customer</th>
					<th width="8%" align="center" colspan="2" >TL/AL</th>
					<th width="7%" align="center" colspan="2" >B MF	</th>
					<th width="7%" align="center" colspan="2" >B.L </th>
					<th width="7%" align="center" colspan="2" >C.MF	</th>
					<th width="7%" align="center" colspan="2" >C.L </th>
					<th width="5%" align="center">pjg</th>
					<th width="3%" align="center">lbr</th>
					<th width="5%" align="center" colspan="3" >Score</th>
					<th width="3%" align="center">Out</th>
					<th width="3%" align="center">Fl </th>
					<th width="5%" align="center">Lbr Roll</th>
					<th width="3%" align="center">Trim</th>
					<th width="5%" align="center">Qty</th>
					<th width="5%" align="center">C.off</th>
					<th width="5%" align="center">RM</th>
					<th width="5%" align="center">KG</th>
					<th width="5%" align="center">TGL KIRIM</th>
				</tr>
				</thead>
				';
			$no = 1;
			$data    = $this->db->query("SELECT*FROM plan_cor a 
			JOIN m_produk b ON a.id_produk=b.id_produk
			JOIN m_pelanggan c ON a.id_pelanggan=c.id_pelanggan
			JOIN trs_so_detail d ON a.id_so_detail=d.id
			JOIN trs_wo e ON a.id_wo=e.id
			where no_plan = '$no_plan' ")->result();
 
			foreach ($data as $r) {

				$substance = explode("/", $r->material_plan);
				$gramature = explode("/", $r->kualitas_isi_plan);

				if( $r->flute =='BCF'){

					$s1 = $substance[0];
					$s2 = $substance[1];
					$s3 = $substance[2];
					$s4 = $substance[3];
					$s5 = $substance[4];

					$grm1 = $gramature[0];
					$grm2 = $gramature[1];
					$grm3 = $gramature[2];
					$grm4 = $gramature[3];
					$grm5 = $gramature[4];

				}else if( $r->flute =='BF'){
					
					$s1 = $substance[0];
					$s2 = $substance[1];
					$s3 = $substance[2];
					$s4 = 0;
					$s5 = 0;
					
					$grm1 = $gramature[0];
					$grm2 = $gramature[1];
					$grm3 = $gramature[2];
					$grm4 = 0;
					$grm5 = 0;

				}else if( $r->flute =='CF'){
					
					$s1 = $substance[0];
					$s2 = 0;
					$s3 = 0;
					$s4 = $substance[1];
					$s5 = $substance[2];
					
					$grm1 = $gramature[0];
					$grm2 = 0;
					$grm3 = 0;
					$grm4 = $gramature[1];
					$grm5 = $gramature[2];

				}
				$bold= 'font-weight:bold';
				$html .= '
					<tbody>
                        <tr>
                            <td align="center">'.$no.'</td>
                            <td align="center">'. $r->nm_produk .'</td>
                            <td align="center">'. $r->no_po .'</td>
                            <td align="center">'. $r->nm_pelanggan .'</td>
                            <td width="3%" align="center" style="color:red"><b>'. $s1.'</b></td>
                            <td width="3%" align="center">'. $grm1.'</td>
                            <td width="3%" align="center" style="color:red"><b>'. $s2.'</b></td>
                            <td width="3%" align="center">'. $grm2.'</td>
                            <td width="3%" align="center" style="color:red"><b>'. $s3.'</b></td>
                            <td width="3%" align="center">'. $grm3.'</td>
                            <td width="3%" align="center" style="color:red"><b>'. $s4.'</b></td>
                            <td width="3%" align="center">'. $grm4.'</td>
                            <td width="3%" align="center" style="color:red"><b>'. $s5.'</b></td>
                            <td width="3%" align="center">'. $grm5.'</td>
                            <td align="center" style="color:red;'.$bold.'">'. number_format($r->panjang_plan, 0, ",", ".") .'</td>
                            <td align="center" style="color:red;'.$bold.'">'. number_format($r->lebar_plan, 0, ",", ".") .'</td>

                            <td width="3%" align="center">'. number_format($r->flap1, 0, ",", ".") .'</td>
                            <td width="3%" align="center">'. number_format($r->flap1, 0, ",", ".") .'</td>
                            <td width="3%" align="center">'. number_format($r->flap1, 0, ",", ".") .'</td>

                            <td align="center">'. $r->out_plan .'</td>
                            <td align="center">'. $r->flute .' </td>
                            <td align="center">'. number_format($r->lebar_roll_p, 0, ",", ".") .'</td>
                            <td align="center">'. number_format($r->trim_plan, 0, ",", ".") .'</td>
                            <td align="center" style="color:red;'.$bold.'">'. number_format($r->pcs_plan, 0, ",", ".") .'</td>
                            <td align="center">'. number_format($r->c_off_p, 0, ",", ".") .'</td>
                            <td align="center">'. number_format($r->rm_plan, 0, ",", ".") .'</td>
                            <td align="center">'. number_format($r->tonase_plan, 0, ",", ".") .'</td>
                            <td align="center" style="color:red;'.$bold.'">'. $this->m_fungsi->tanggal_ind($r->tgl_kirim_plan) .'</td>
                        </tr>
					</tbody>
					';

						$no++;
			}
			$html .='</table>';

		} else {
			$html .= '<h1> Data Kosong </h1>';
		}

		// $this->m_fungsi->_mpdf($html);
		
		$this->m_fungsi->template_kop('CORRUGATOR PLAN','P-'.$this->m_fungsi->tanggal_ind($r->tgl_plan).'-'.$no_plan,$html,'L','1');
		// $this->m_fungsi->mPDFP($html);
	}

}
