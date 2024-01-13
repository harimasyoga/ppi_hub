<div class="content-wrapper">
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
			<div class="col-sm-6">
				<!-- <h1><b>Data Plan</b></h1> -->
			</div>
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
				<!-- <li class="breadcrumb-item active" ><a href="#">Corrugator</a></li> -->
				</ol>
			</div>
			</div>
		</div>
	</section>

	<style>
		/* Chrome, Safari, Edge, Opera */
		input::-webkit-outer-spin-button,
		input::-webkit-inner-spin-button {
			-webkit-appearance: none;
			margin: 0;
		}
	</style>

	<?php
		$queryNoPlan = $this->db->query("SELECT no_plan FROM plan_cor WHERE tgl_plan='$tgl_plan' AND shift_plan='$shift' AND machine_plan='$mesin' GROUP BY no_plan")->row();
		$urlNoPlan = $queryNoPlan->no_plan;
	?>

	<section class="content" style="padding-bottom:30px">
		<div class="container-fluid">

			<div class="row">
				<?php if(in_array($this->session->userdata('level'), ['Admin','PPIC','User'])) { ?>
					<div class="col-md-12">
						<div class="card">
							<div class="card-body p-0">
								<div id="accordion-customer">
									<div class="card m-0" style="border-radius:0">
										<div class="card-header bg-gradient-secondary" style="padding:0;border-radius:0">
											<a class="d-block w-100 link-h-wo" style="font-weight:bold;padding:6px" data-toggle="collapse" href="#collapseCustomer" onclick="loadDataAllWO('')">LIST SEMUA WO CUSTOMER</a>
										</div>
										<div id="collapseCustomer" class="collapse" data-parent="#accordion-customer">
											<div id="tampil-all-wo-header"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="col-md-12">
						<div class="card card-info card-outline">
							<div class="card-header">
								<h3 class="card-title" style="font-weight:bold;font-style:italic">WO</h3>
							</div>
							<div class="card-body row" style="padding-bottom:5px;font-weight:bold">
								<div class="col-md-12" style="padding:0">
									<a href="<?php echo base_url('Plan/Corrugator')?>" class="btn btn-sm btn-info"><i class="fa fa-arrow-left"></i> <b>Kembali</b></a>
									<a href="<?php echo base_url('Plan/Corrugator/Add')?>" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> <b>Add</b></a>
								</div>
							</div>
							<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
								<div class="col-md-1"></div>
								<div class="col-md-11" style="font-size:small;font-style:italic;color:#f00">
									* [ TYPE ] NO. WO | ETA SO | ITEM | CUSTOMER
								</div>
							</div>
							<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
								<div class="col-md-1">NO. WO</div>
								<div class="col-md-11">
									<select id="no_wo" class="form-control select2" onchange="plhNoWo('')"></select>
								</div>
							</div>
							<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
								<div class="col-md-1">TGL. WO</div>
								<div class="col-md-11">
									<input type="text" id="tgl_wo" class="form-control" autocomplete="off" placeholder="TGL. WO" disabled>
								</div>
							</div>
							<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
								<div class="col-md-1">PANJANG</div>
								<div class="col-md-1">
									<input type="text" id="txt-wo-kup" class="form-control" placeholder="KUP" disabled>
								</div>
								<div class="col-md-1">
									<input type="text" id="txt-wo-p1" class="form-control" placeholder="P1" disabled>
								</div>
								<div class="col-md-1">
									<input type="text" id="txt-wo-l1" class="form-control" placeholder="L1" disabled>
								</div>
								<div class="col-md-1">
									<input type="text" id="txt-wo-p2" class="form-control" placeholder="P2" disabled>
								</div>
								<div class="col-md-1">
									<input type="text" id="txt-wo-l2" class="form-control" placeholder="L2" disabled>
								</div>
								<div class="col-md-1">
									<input type="text" id="txt-wo-pjgwo" class="form-control" style="font-weight:bold" placeholder="PJG" disabled>
								</div>
								<div class="col-md-5"></div>
							</div>
							<div class="card-body row" style="padding:0 20px 20px;font-weight:bold">
								<div class="col-md-1">SCORE</div>
								<div class="col-md-1">
									<input type="text" id="txt-wo-s1" class="form-control" placeholder="S1" disabled>
								</div>
								<div class="col-md-1">
									<input type="text" id="txt-wo-s2" class="form-control" placeholder="S2" disabled>
								</div>
								<div class="col-md-1">
									<input type="text" id="txt-wo-s3" class="form-control" placeholder="S3" disabled>
								</div>
								<div class="col-md-1">
									<input type="text" id="txt-wo-score" class="form-control" style="font-weight:bold" placeholder="SCORE" disabled>
								</div>
								<div class="col-md-7"></div>
							</div>
						</div>
					</div>
				<?php } ?>

				<div class="col-md-12">
					<div class="card">
						<div class="card-body p-0">
							<div id="accordion-plan">
								<div class="card m-0" style="border-radius:0">
									<div class="card-header bg-gradient-secondary" style="padding:0;border-radius:0">
										<a class="d-block w-100 link-h-wo" style="font-weight:bold;padding:6px" data-toggle="collapse" href="#collapsePlanc" onclick="loadDataAllPlan()">LIST SEMUA PLAN</a>
									</div>
									<div id="collapsePlanc" class="collapse" data-parent="#accordion-plan">
										<div id="tampil-all-plan-header"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- <div class="col-md-12 plh-tanggal-plan-col12" style="display:none">
					<div class="card card-primary card-tabs">
						<div id="plh-tanggal-plan-header"></div>
						<div id="plh-tanggal-plan-body"></div>
					</div>
				</div> -->

				<div class="col-md-12">
					<div id="tampil-list-input"></div>
					<div id="riwayat-plan"></div>
					<div id="list-rencana-plan"></div>
				</div>

				<div class="col-md-7">
					<div id="card-produksi" style="display:none">
						<div class="card card-danger card-outline" style="padding-bottom:20px">
							<div class="card-header">
								<h3 class="card-title" style="font-weight:bold;font-style:italic">DOWNTIME</h3>
							</div>
							<div id="dt-pilih"></div>
							<div id="dt-select"></div>
							<div style="overflow:auto;white-space:nowrap">
								<div id="dt-load-data"></div>
							</div>
						</div>

						<div class="card card-success card-outline" style="padding-bottom:20px">
							<div class="card-header">
								<h3 class="card-title" style="font-weight:bold;font-style:italic">HASIL PRODUKSI</h3>
							</div>
							<div class="card-body row" style="padding-bottom:5px;font-weight:bold">
								<div class="col-md-2">ORDER</div>
								<div class="col-md-10">
									<input type="number" id="order_cor" style="font-weight:bold" class="form-control" disabled>
								</div>
							</div>
							<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
								<div class="col-md-2">GOOD</div>
								<div class="col-md-10">
									<input type="number" id="good_cor" class="form-control" onkeyup="hitungProduksi()">
								</div>
							</div>
							<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
								<div class="col-md-2">REJECT</div>
								<div class="col-md-10">
									<input type="number" id="bad_cor" class="form-control" onkeyup="hitungProduksi()">
								</div>
							</div>
							<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
								<div class="col-md-2">TOTAL</div>
								<div class="col-md-10">
									<input type="number" id="total_cor" class="form-control" onkeyup="hitungProduksi()" disabled>
								</div>
							</div>
							<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
								<div class="col-md-2">KET</div>
								<div class="col-md-10">
									<textarea id="ket_cor" class="form-control" style="resize:none" rows="2"></textarea>
								</div>
							</div>
							<div class="card-body row" style="padding:20px 20px 5px;font-weight:bold">
								<div class="col-md-2">TGL PROD.</div>
								<div class="col-md-10">
									<input type="date" id="tgl_cor" class="form-control">
								</div>
							</div>
							<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
								<div class="col-md-2">START</div>
								<div class="col-md-10">
									<input type="time" id="start_cor" class="form-control">
								</div>
							</div>
							<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
								<div class="col-md-2">END</div>
								<div class="col-md-10">
									<input type="time" id="end_cor" class="form-control">
								</div>
							</div>

							<?php if(in_array($this->session->userdata('level'), ['Admin','PPIC','Corrugator','User'])) { ?>
								<div id="btn-aksi-produksi"></div>
							<?php } ?>
						</div>
					</div>

					<div id="list-plan"></div>
					<!-- <div id="list-rencana-plan"></div> -->

					<div class="card card-secondary card-outline" style="padding-bottom:20px">
						<div class="card-header">
							<h3 class="card-title" style="font-weight:bold;font-style:italic">ITEM</h3>
						</div>
						<div class="card-body row" style="padding-bottom:5px;font-weight:bold">
							<div class="col-md-2">KODE MC</div>
							<div class="col-md-10">
								<input type="text" id="kode_mc" class="form-control" autocomplete="off" placeholder="KODE MC" disabled>
							</div>
						</div>
						<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
							<div class="col-md-2">ITEM</div>
							<div class="col-md-10">
								<input type="text" id="item" class="form-control" autocomplete="off" placeholder="NAMA ITEM" disabled>
							</div>
						</div>
						<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
							<div class="col-md-2">UK. BOX</div>
							<div class="col-md-4">
								<input type="text" id="uk_box" class="form-control" autocomplete="off" placeholder="UKURAN BOX" disabled>
							</div>
							<div class="col-md-2">UK. SHEET</div>
							<div class="col-md-4">
								<input type="text" id="uk_sheet" class="form-control" autocomplete="off" placeholder="UKURAN SHEET" disabled>
							</div>
						</div>
						<div class="card-body row" style="padding:0 20px 2px;font-weight:bold">
							<div class="col-md-2">CREASING</div>
							<div class="col-md-3" style="margin-bottom:3px">
								<input type="number" id="creasing_1" class="form-control" autocomplete="off" placeholder="0" disabled>
							</div>
							<div class="col-md-3" style="margin-bottom:3px">
								<input type="number" id="creasing_2" class="form-control" autocomplete="off" placeholder="0" disabled>
							</div>
							<div class="col-md-3" style="margin-bottom:3px">
								<input type="number" id="creasing_3" class="form-control" autocomplete="off" placeholder="0" disabled>
							</div>
							<div class="col-md-1" style="padding:0"></div>
						</div>
						<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
							<div class="col-md-2">KUALITAS</div>
							<div class="col-md-10">
								<input type="text" id="kualitas" class="form-control" autocomplete="off" placeholder="KUALITAS" disabled>
							</div>
						</div>
						<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
							<div class="col-md-2">FLUTE</div>
							<div class="col-md-10">
								<input type="text" id="flute" class="form-control" autocomplete="off" placeholder="FLUTE" disabled>
							</div>
						</div>
						<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
							<div class="col-md-2">TIPE BOX</div>
							<div class="col-md-4">
								<input type="text" id="tipe_box" class="form-control" autocomplete="off" placeholder="TIPE BOX" disabled>
							</div>
							<div class="col-md-2" style="padding-right:0">SAMBUNGAN</div>
							<div class="col-md-4">
								<input type="text" id="sambungan" class="form-control" autocomplete="off" placeholder="SAMBUNGAN" disabled>
							</div>
						</div>
						<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
							<div class="col-md-2" style="padding-right:0">BB</div>
							<div class="col-md-4">
								<input type="text" id="bb_box" class="form-control" autocomplete="off" placeholder="BERAT BOX" disabled>
							</div>
							<div class="col-md-2" style="padding-right:0">LB</div>
							<div class="col-md-4">
								<input type="text" id="lb_box" class="form-control" autocomplete="off" placeholder="LUAS BOX" disabled>
							</div>
						</div>
					</div>
				</div>

				<div class="col-md-5">
					<div class="card card-info card-outline">
						<div class="card-header">
							<h3 class="card-title" style="font-weight:bold;font-style:italic">PLAN</h3>
						</div>
						<div class="card-body row" style="padding-bottom:5px;font-weight:bold">
							<div class="col-md-2">TGL</div>
							<div class="col-md-10">
								<input type="date" id="tgl" class="form-control">
							</div>
						</div>
						<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
							<div class="col-md-2">SHIFT</div>
							<div class="col-md-10">
								<select id="shift" class="form-control select2"></select>
							</div>
						</div>
						<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
							<div class="col-md-2">MESIN</div>
							<div class="col-md-10">
								<select id="mesin" class="form-control select2"></select>
							</div>
						</div>
						<div id="btn-ganti-tgl"></div>

						<div id="group_ganti_kualitas">
							<?php if(in_array($this->session->userdata('level'), ['Admin','PPIC','User'])) {
								$gKualitas = 'style="padding:20px 20px 5px;font-weight:bold"';
								$dis = '';
							}else{
								$gKualitas = 'style="display:none"';
								$dis = 'disabled';
							} ?>
							<input type="hidden" id="input_material_plan" value="">
							<input type="hidden" id="input_kualitas_plan" value="">
							<input type="hidden" id="input_kualitas_plan_isi" value="">
							<input type="hidden" id="h_ikpi" value="">
							<div class="card-body row" <?= $gKualitas ?>>
								<div class="col-md-2">GANTI</div>
								<div class="col-md-10">
									<select id="g_kualitas" class="form-control select2" onchange="ayoBerhitung()">
										<option value="PO">KUALITAS SESUAI PO</option>
										<option value="GANTI">GANTI KUALITAS</option>
									</select>
								</div>
							</div>

							<div id="group_plh_kualitas" style="display:none">
								<div class="card-body row" style="padding:0 20px 2px;font-weight:bold">
									<div class="col-md-2">TL/AL</div>
									<div class="col-md-5" style="margin-bottom:3px">
										<select id="tl_al" class="form-control select2" onchange="ayoBerhitung()">
											<option value="">-</option><option value="M">M</option><option value="K">K</option><option value="MC">MC</option><option value="MN">MN</option>
										</select>
									</div>
									<div class="col-md-5" style="margin-bottom:3px"><input type="number" id="tl_al_i" class="form-control angka" autocomplete="off" onkeyup="ayoBerhitung()"></div>
								</div>
								<div class="card-body row" style="padding:0 20px 2px;font-weight:bold">
									<div class="col-md-2">B.MF</div>
									<div class="col-md-5" style="margin-bottom:3px">
										<select id="bmf" class="form-control select2" onchange="ayoBerhitung()">
											<option value="">-</option><option value="M">M</option><option value="K">K</option><option value="MC">MC</option><option value="MN">MN</option>
										</select>
									</div>
									<div class="col-md-5" style="margin-bottom:3px"><input type="number" id="bmf_i" class="form-control angka" autocomplete="off" onkeyup="ayoBerhitung()"></div>
								</div>
								<div class="card-body row" style="padding:0 20px 2px;font-weight:bold">
									<div class="col-md-2">BC</div>
									<div class="col-md-5" style="margin-bottom:3px">
										<select id="bl" class="form-control select2" onchange="ayoBerhitung()">
											<option value="">-</option><option value="M">M</option><option value="K">K</option><option value="MC">MC</option><option value="MN">MN</option>
										</select>
									</div>
									<div class="col-md-5" style="margin-bottom:3px"><input type="number" id="bl_i" class="form-control angka" autocomplete="off" onkeyup="ayoBerhitung()"></div>
								</div>
								<div class="card-body row" style="padding:0 20px 2px;font-weight:bold">
									<div class="col-md-2">C.MF</div>
									<div class="col-md-5" style="margin-bottom:3px">
										<select id="cmf" class="form-control select2" onchange="ayoBerhitung()">
											<option value="">-</option><option value="M">M</option><option value="K">K</option><option value="MC">MC</option><option value="MN">MN</option>
										</select>
									</div>
									<div class="col-md-5" style="margin-bottom:3px"><input type="number" id="cmf_i" class="form-control angka" autocomplete="off" onkeyup="ayoBerhitung()"></div>
								</div>
								<div class="card-body row" style="padding:0 20px 2px;font-weight:bold">
									<div class="col-md-2">B/C.L</div>
									<div class="col-md-5" style="margin-bottom:3px">
										<select id="cl" class="form-control select2" onchange="ayoBerhitung()">
											<option value="">-</option><option value="M">M</option><option value="K">K</option><option value="MC">MC</option><option value="MN">MN</option>
										</select>
									</div>
									<div class="col-md-5" style="margin-bottom:3px"><input type="number" id="cl_i" class="form-control angka" autocomplete="off" onkeyup="ayoBerhitung()"></div>
								</div>
								<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
									<div class="col-md-2" style="padding:0"></div>
									<div class="col-md-10">
										<input type="text" id="group_tmpl_kualitas" class="form-control" autocomplete="off" placeholder="GANTI KUALITAS" disabled>
									</div>
								</div>
							</div>
							<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
								<div class="col-md-2">#</div>
								<div class="col-md-10">
									<input type="text" id="info-substance" class="form-control" disabled>
								</div>
							</div>
						</div>

						<div class="card-body row" style="padding-bottom:5px;font-weight:bold">
							<div class="col-md-2" style="padding:0 2px 0 0">
								<input type="number" id="kupingan" class="form-control" autocomplete="off" placeholder="KUP" onkeyup="ayoBerhitung()">
							</div>
							<div class="col-md-2" style="padding:0 2px 0 0">
								<input type="number" id="p1" class="form-control" autocomplete="off" placeholder="P1" onkeyup="ayoBerhitung()">
							</div>
							<div class="col-md-2" style="padding:0 2px 0 0">
								<input type="number" id="l1" class="form-control" autocomplete="off" placeholder="L1" onkeyup="ayoBerhitung()">
							</div>
							<div class="col-md-2" style="padding:0 2px 0 0">
								<input type="number" id="p2" class="form-control" autocomplete="off" placeholder="P2" onkeyup="ayoBerhitung()">
							</div>
							<div class="col-md-2" style="padding:0 2px 0 0">
								<input type="number" id="l2" class="form-control" autocomplete="off" placeholder="L2" onkeyup="ayoBerhitung()">
							</div>
							<div class="col-md-1" style="padding:6px 8px 0">+</div>
							<div class="col-md-1" style="padding:6px 0 0"><span class="plus-lima">0</span></div>
						</div>
						<div class="card-body row" style="padding:0 20px 20px;font-weight:bold">
							<div class="col-md-2" style="padding:0">PANJANG</div>
							<div class="col-md-10">
								<input type="number" id="ii_panjang" class="form-control" autocomplete="off" <?=$dis?> placeholder="PANJANG SHEET" onkeyup="ayoBerhitung()">
							</div>
						</div>
						<div class="card-body row" style="padding:0 20px 2px;font-weight:bold">
							<div class="col-md-2">SCORE</div>
							<div class="col-md-3" style="margin-bottom:3px">
								<input type="number" id="creasing_wo_1" class="form-control" <?=$dis?> autocomplete="off" placeholder="0" onkeyup="ayoBerhitung()">
							</div>
							<div class="col-md-3" style="margin-bottom:3px">
								<input type="number" id="creasing_wo_2" class="form-control" <?=$dis?> autocomplete="off" placeholder="0" onkeyup="ayoBerhitung()">
							</div>
							<div class="col-md-3" style="margin-bottom:3px">
								<input type="number" id="creasing_wo_3" class="form-control" <?=$dis?> autocomplete="off" placeholder="0" onkeyup="ayoBerhitung()">
							</div>
							<div class="col-md-1 p-0"></div>
						</div>
						<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
							<div class="col-md-2">LEBAR</div>
							<div class="col-md-10">
								<input type="number" id="ii_lebar" class="form-control" autocomplete="off" <?=$dis?> placeholder="LEBAR SHEET" onkeyup="ayoBerhitung()">
							</div>
						</div>
						<div class="card-body row" style="padding:20px 20px 5px;font-weight:bold">
							<div class="col-md-2" style="padding-right:0">L. ROLL</div>
							<div class="col-md-10">
								<input type="number" id="i_lebar_roll" class="form-control" autocomplete="off" <?=$dis?> placeholder="LEBAR ROLL" onkeyup="ayoBerhitung()">
							</div>
						</div>
						<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
							<div class="col-md-2">OUT</div>
							<div class="col-md-10">
								<input type="number" id="out_plan" class="form-control" autocomplete="off" <?=$dis?> placeholder="OUT" onkeyup="ayoBerhitung()">
							</div>
						</div>
						<br/>
						<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
							<div class="col-md-2">QTY</div>
							<div class="col-md-10">
								<input type="number" id="qty_plan" class="form-control" autocomplete="off" placeholder="QTY PLAN" disabled>
							</div>
						</div>
						<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
							<div class="col-md-2">TRIM</div>
							<div class="col-md-10">
								<input type="number" id="trim" class="form-control" autocomplete="off" placeholder="TRIM" disabled>
							</div>
						</div>
						<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
							<div class="col-md-2" style="padding-right:0">C.OFF</div>
							<div class="col-md-10">
								<input type="number" id="c_off" class="form-control" autocomplete="off" placeholder="NUM OF CUT" disabled>
							</div>
						</div>
						<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
							<div class="col-md-2">RM</div>
							<div class="col-md-10">
								<input type="number" id="rm" class="form-control" autocomplete="off" placeholder="RM PLAN" disabled>
							</div>
						</div>
						<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
							<div class="col-md-2">TON</div>
							<div class="col-md-10">
								<input type="number" id="ton" class="form-control" autocomplete="off" placeholder="TONASE PLAN" disabled>
							</div>
						</div>

						<br/>
						<div class="card-body row" style="padding:0 20px 2px;font-weight:bold">
							<div class="col-md-2" style="padding-right:0">KIRIM</div>
							<div class="col-md-5" style="margin-bottom:3px">
								<input type="date" id="kirim" class="form-control" <?=$dis?>>
							</div>
							<div class="col-md-5" style="margin-bottom:3px">
								<input type="date" id="eta_so_plan" class="form-control" disabled>
							</div>
						</div>
						<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
							<div class="col-md-2">NEXT</div>
							<div class="col-md-10">
								<select id="next_flexo" class="form-control select2" <?=$dis?>>
									<option value="">PILIH</option>
								</select>
							</div>
						</div>

						<br/>
						<?php if(in_array($this->session->userdata('level'), ['Admin','PPIC','User'])) { ?>
							<div id="btn-aksi-plan"></div>
						<?php } ?>
					</div>
				</div>

				<input type="hidden" id="ehkategori" value="">
				<input type="hidden" id="ehno_plan" value="">
				<input type="hidden" id="ehid_plan" value="">
				<input type="hidden" id="ehid_so_detail" value="">
				<input type="hidden" id="ehid_wo" value="">
				<input type="hidden" id="ehid_produk" value="">
				<input type="hidden" id="ehid_pelanggan" value="">
				<input type="hidden" id="ehno_wo" value="">
				<input type="hidden" id="ehno_so" value="">
				<input type="hidden" id="ehurut_so" value="">
				<input type="hidden" id="ehrpt" value="">
				<input type="hidden" id="ehpcs_plan" value="">
			</div>

			<div class="row">
				<div class="col-md-7">
					<div class="card card-secondary card-outline" style="padding-bottom:20px">
						<div class="card-header">
							<h3 class="card-title" style="font-weight:bold;font-style:italic">PO</h3>
						</div>
						<div class="card-body row" style="padding-bottom:5px;font-weight:bold">
							<div class="col-md-2" style="padding-right: 0;">CUSTOMER</div>
							<div class="col-md-10">
								<input type="text" id="customer" class="form-control" autocomplete="off" placeholder="CUSTOMER" disabled>
							</div>
						</div>
						<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
							<div class="col-md-2" style="padding-right: 0;">SALES</div>
							<div class="col-md-10">
								<input type="text" id="sales" class="form-control" autocomplete="off" placeholder="SALES" disabled>
							</div>
						</div>
						<div class="card-body row" style="padding:0 20px 20px;font-weight:bold">
							<div class="col-md-2" style="padding-right: 0;">ALAMAT</div>
							<div class="col-md-10">
								<textarea id="alamat" class="form-control" rows="2" style="resize:none" placeholder="ALAMAT" disabled></textarea>
							</div>
						</div>
						<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
							<div class="col-md-2">TGL. PO</div>
							<div class="col-md-10">
								<input type="text" id="tgl_po" class="form-control" autocomplete="off" placeholder="TANGGAL PO" disabled>
							</div>
						</div>
						<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
							<div class="col-md-2">NO. PO</div>
							<div class="col-md-10">
								<input type="text" id="no_po" class="form-control" autocomplete="off" placeholder="NO. PO" disabled>
							</div>
						</div>
						<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
							<div class="col-md-2">KODE PO</div>
							<div class="col-md-10">
								<input type="text" id="kode_po" class="form-control" autocomplete="off" placeholder="KODE PO" disabled>
							</div>
						</div>
						<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
							<div class="col-md-2">QTY PO</div>
							<div class="col-md-10">
								<input type="number" id="qty_po" class="form-control" autocomplete="off" placeholder="QTY PO" disabled>
							</div>
						</div>
					</div>
				</div>

				<div class="col-md-5">
					<div class="card card-secondary card-outline" style="padding-bottom:20px">
						<div class="card-header">
							<h3 class="card-title" style="font-weight:bold;font-style:italic">SO</h3>
						</div>
						<div class="card-body row" style="padding-bottom:5px;font-weight:bold">
							<div class="col-md-2" style="padding-right:0">ETA. SO</div>
							<div class="col-md-10">
								<input type="text" id="eta_so" class="form-control" autocomplete="off" placeholder="ETA. SO" disabled>
							</div>
						</div>
						<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
							<div class="col-md-2">NO. SO</div>
							<div class="col-md-10">
								<input type="text" id="no_so" class="form-control" autocomplete="off" placeholder="NO. SO" disabled>
							</div>
						</div>
						<div class="card-body row" style="padding:0 20px 20px;font-weight:bold">
							<div class="col-md-2">QTY SO</div>
							<div class="col-md-10">
								<input type="number" id="qty_so" class="form-control" autocomplete="off" placeholder="QTY SO" disabled>
							</div>
						</div>
						<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
							<div class="col-md-2">RM</div>
							<div class="col-md-10">
								<input type="number" id="rm_so" class="form-control" autocomplete="off" placeholder="RM SO" disabled>
							</div>
						</div>
						<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
							<div class="col-md-2" style="padding-right:0">TONASE</div>
							<div class="col-md-10">
								<input type="number" id="ton_so" class="form-control" autocomplete="off" placeholder="TONASE SO" disabled>
							</div>
						</div>
						<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
							<div class="col-md-2">KET</div>
							<div class="col-md-10">
								<textarea class="form-control" id="ket_so" rows="2" style="resize:none" placeholder="KETERANGAN SO" disabled></textarea>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>
	</section>
</div>

<div class="modal fade" id="modalForm">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="judul"></h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" style="overflow:auto;white-space:nowrap">
				<div id="show-list-plh-item"></div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	const urlAuth = '<?= $this->session->userdata('level')?>';
	const urlTgl_plan = '<?= $tgl_plan ?>';
	const urlShift = '<?= $shift ?>';
	const urlMesin = '<?= $mesin ?>';
	const urlNoPlan = '<?= $urlNoPlan ?>';
	let inputDtProd = '';
	let optionsDay = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };

	$(document).ready(function ()
	{
		$("#ehno_plan").val(urlNoPlan)
		$("#tgl").val(urlTgl_plan).prop("disabled", true)
		$("#shift").html(`<option value="${urlShift}">${urlShift}</option>`).prop("disabled", true)
		$("#mesin").html(`<option value="${urlMesin}">${urlMesin}</option>`).prop("disabled", true)
		loadData(urlTgl_plan, urlShift, urlMesin)

		$("#list-rencana-plan").load("<?php echo base_url('Plan/destroyPlan') ?>")
		$("#no_wo").prop("disabled", true).html(`<option value="">PILIH</option>`)

		$('.select2').select2({
			dropdownAutoWidth: true
		})
	})

	function loadDataAllWO(opsi = '')
	{
		$.ajax({
			url: '<?php echo base_url('Plan/loadDataAllWO')?>',
			type: "POST",
			beforeSend: function() {
				swal({
					title: 'Loading',
					allowEscapeKey: false,
					allowOutsideClick: false,
					onOpen: () => {
						swal.showLoading();
					}
				});
			},
			success: function(res){
				$("#tampil-all-wo-header").html(res)
				if(opsi != 'noLoading'){
					swal.close()
				}
			}
		})
	}

	function onClickHeaderWO(id_pelanggan)
	{
		$("#tampil-all-wo-isi-"+id_pelanggan).html('')
		$.ajax({
			url: '<?php echo base_url('Plan/onClickHeaderWO')?>',
			type: "POST",
			data: ({
				id_pelanggan
			}),
			success: function(res){
				$("#tampil-all-wo-isi-"+id_pelanggan).html(res)
			}
		})
	}

	function onClickPlhWo(id_wo)
	{
		$("#tampil-isi-wo-"+id_wo).html('')
		$.ajax({
			url: '<?php echo base_url('Plan/onClickPlhWo')?>',
			type: "POST",
			data: ({
				id_wo
			}),
			success: function(res){
				$("#tampil-isi-wo-"+id_wo).html(res)
			}
		})
	}

	function kosong()
	{
		loadPlanWo('')
		$("#tgl_wo").val("")
		$("#card-produksi").hide()

		$("#kode_mc").val("")
		$("#item").val("")
		$("#uk_box").val("")
		$("#uk_sheet").val("")
		$("#creasing_1").val("")
		$("#creasing_2").val("")
		$("#creasing_3").val("")
		$("#kualitas").val("")
		$("#flute").val("")
		$("#tipe_box").val("")
		$("#sambungan").val("")
		$("#bb_box").val("")
		$("#lb_box").val("")

		$("#tgl").val(urlTgl_plan).prop("disabled", true)
		$("#shift").html(`<option value="${urlShift}">${urlShift}</option>`).prop("disabled", true)
		$("#mesin").html(`<option value="${urlMesin}">${urlMesin}</option>`).prop("disabled", true)
		
		$("#btn-ganti-tgl").html('')
		
		$("#g_kualitas").val('PO')
		$("#group_plh_kualitas").hide()
		$("#info-substance").val("")

		$("#ii_panjang").val("")
		$("#creasing_wo_1").val("")
		$("#creasing_wo_2").val("")
		$("#creasing_wo_3").val("")
		$("#ii_lebar").val("")
		$("#i_lebar_roll").val("")
		$("#out_plan").val("")
		$("#qty_plan").val("")
		$("#trim").val("")
		$("#c_off").val("")
		$("#rm").val("")
		$("#ton").val("")
		$("#kirim").val("")
		$("#eta_so_plan").val("")
		$("#next_flexo").html('<option value="">PILIH</option>')
		$("#btn-aksi-plan").html(`<div class="card-body row" style="padding:0 20px 20px;font-weight:bold">
			<div class="col-md-12">
				<button type="button" class="btn btn-success btn-block" onclick="addRencanaPlan('add')"><i class="fa fa-plus"></i> <b>ADD PLAN</b></button>
			</div>
		</div>`)

		$("#ehkategori").val("")
		$("#ehno_plan").val(urlNoPlan)
		$("#ehid_plan").val("")
		$("#ehid_so_detail").val("")
		$("#ehid_wo").val("")
		$("#ehid_produk").val("")
		$("#ehid_pelanggan").val("")
		$("#ehno_wo").val("")
		$("#ehno_so").val("")
		$("#ehurut_so").val("")
		$("#ehrpt").val("")
		$("#ehpcs_plan").val("")

		$("#customer").val("")
		$("#sales").val("")
		$("#alamat").val("")
		$("#tgl_po").val("")
		$("#no_po").val("")
		$("#kode_po").val("")
		$("#qty_po").val("")
		$("#eta_so").val("")
		$("#no_so").val("")
		$("#qty_so").val("")
		$("#rm_so").val("")
		$("#ton_so").val("")
		$("#ket_so").val("")
	}

	function loadDataAllPlan()
	{
		$.ajax({
			url: '<?php echo base_url('Plan/loadDataAllPlan')?>',
			type: "POST",
			data: ({
				urlTgl_plan, urlShift, urlMesin
			}),
			beforeSend: function() {
				swal({
					title: 'Loading',
					allowEscapeKey: false,
					allowOutsideClick: false,
					onOpen: () => {
						swal.showLoading();
					}
				});
			},
			success: function(res){
				$("#tampil-all-plan-header").html(res)
				swal.close()
			}
		})
	}

	function loadData(tgl_plan, shift, mesin)
	{
		$.ajax({
			url: '<?php echo base_url('Plan/loadDataPlan')?>',
			type: "POST",
			data: ({
				tgl_plan, shift, mesin
			}),
			beforeSend: function() {
				swal({
					title: 'Loading',
					allowEscapeKey: false,
					allowOutsideClick: false,
					onOpen: () => {
						swal.showLoading();
					}
				});
			},
			success: function(res){
				data = JSON.parse(res)
				if(data.planCor.length == 0){
					window.location.href = '<?php echo base_url('Plan/Corrugator')?>'
				}else{
					loadDataAllWO('noLoading')
					loadPlanWo('')
					plhNoWo('')
				}
			}
		})
	}

	function loadInputList(tp, sp, mp, i)
	{
		$(".all-hal").html('')
		let tgl_plan = ''
		let shift = ''
		let machine = ''
		let hidplan = ''
		let opsi = ''
		if(tp == '' && sp == '' && mp == ''){
			tgl_plan = urlTgl_plan
			shift = urlShift
			machine = urlMesin
			hidplan = $("#ehid_plan").val();
			opsi = ''
		}else{
			tgl_plan = tp
			shift = sp
			machine = mp
			hidplan = ''
			opsi = 'pilihan'
		}
		
		$.ajax({
			url: '<?php echo base_url('Plan/loadInputList')?>',
			type: "POST",
			data: ({
				tgl_plan, shift, machine, hidplan, opsi, urlNoPlan
			}),
			success: function(res){
				if(tp == '' && sp == '' && mp == ''){
					$("#tampil-list-input").html(res)
					$('.select2').select2();
					(inputDtProd == 'inputDowntimeProduksi') ? plhDowntime() : loadDataAllPlan();
				}else{
					$("#tampil-all-plan-isi-"+i+sp+mp).html(res)
				}
			}
		})
	}

	function btnGantiTglPlan(id_plan)
	{
		let tgl = $("#tgl").val()
		let shift = $("#shift").val()
		let mesin = $("#mesin").val()
		$.ajax({
			url: '<?php echo base_url('Plan/btnGantiTglPlan')?>',
			type: "POST",
			beforeSend: function() {
				swal({
					title: 'Loading',
					allowEscapeKey: false,
					allowOutsideClick: false,
					onOpen: () => {
						swal.showLoading();
					}
				});
			},
			data: ({
				tgl, shift, mesin, id_plan
			}),
			success: function(res){
				data = JSON.parse(res)
				if(data.data){
					loadData(urlTgl_plan, urlShift, urlMesin)
				}else{
					swal(data.msg, "", "error")
				}
			}
		})
	}

	function onChangeNourutPlan(i)
	{
		$("#ehid_plan").val("")
		let no_urut = $("#lp-nourut-"+i).val();
		(no_urut < 0 || no_urut == "") ? no_urut = 0 : no_urut = no_urut;
		$("#lp-nourut-"+i).val(no_urut)
		
		$.ajax({
			url: '<?php echo base_url('Plan/onChangeNourutPlan')?>',
			type: "POST",
			beforeSend: function() {
				swal({
					title: 'Loading',
					allowEscapeKey: false,
					allowOutsideClick: false,
					onOpen: () => {
						swal.showLoading();
					}
				});
			},
			data: ({
				no_urut, i
			}),
			success: function(res){
				data = JSON.parse(res)
				if(data.data){
					kosong()
					riwayatPlan(0, 0, 0, 0)
					loadInputList('','','','')
				}else{
					swal(data.msg, "", "error")
				}
			}
		})
	}

	function onChangeEditPlan(i)
	{
		let rupiah = new Intl.NumberFormat('id-ID', {styles: 'currency', currency: 'IDR'});

		let lpSm1 = $("#lp-sm1-"+i).val()
		let lpSi1 = $("#lp-si1-"+i).val()
		let lpSm2 = $("#lp-sm2-"+i).val()
		let lpSi2 = $("#lp-si2-"+i).val()
		let lpSm3 = $("#lp-sm3-"+i).val()
		let lpSi3 = $("#lp-si3-"+i).val()
		let lpSm4 = $("#lp-sm4-"+i).val()
		let lpSi4 = $("#lp-si4-"+i).val()
		let lpSm5 = $("#lp-sm5-"+i).val()
		let lpSi5 = $("#lp-si5-"+i).val()
		
		let kategori = $("#hlp-kategori-"+i).val()
		let pjg_sheet = $("#lp-pjgs-"+i).val().split('.').join('');
		(pjg_sheet == 0 || pjg_sheet < 0) ? $("#lp-pjgs-"+i).val(0).attr('style', 'color:#f00;font-weight:bold') : $("#lp-pjgs-"+i).val(rupiah.format(pjg_sheet)).attr('style', 'color:#ff0066;font-weight:bold');

		let lbr_sheet = $("#lp-lbrs-"+i).val().split('.').join('')
		let score1 = $("#lp-scr1-"+i).val()
		let score2 = $("#lp-scr2-"+i).val()
		let score3 = $("#lp-scr3-"+i).val()
		let hitungScore = parseInt(score1) + parseInt(score2) + parseInt(score3);
		(score1 == 0 || score1 < 0 || score1 == '') ? $("#lp-scr1-"+i).val(0) : $("#lp-scr1-"+i).val();
		(score2 == 0 || score2 < 0 || score2 == '') ? $("#lp-scr2-"+i).val(0) : $("#lp-scr2-"+i).val();
		(score3 == 0 || score3 < 0 || score3 == '') ? $("#lp-scr3-"+i).val(0) : $("#lp-scr3-"+i).val();
		if(kategori == 'K_BOX'){
			if(hitungScore == lbr_sheet){
				$("#lp-scr1-"+i).attr('style', 'color:#495057;font-weight:normal')
				$("#lp-scr2-"+i).attr('style', 'color:#495057;font-weight:normal')
				$("#lp-scr3-"+i).attr('style', 'color:#495057;font-weight:normal');
				(lbr_sheet == 0 || lbr_sheet < 0) ? $("#lp-lbrs-"+i).val(0).attr('style', 'color:#f00;font-weight:bold') : $("#lp-lbrs-"+i).val(rupiah.format(lbr_sheet)).attr('style', 'color:#ff0066;font-weight:bold');
			}else{
				$("#lp-scr1-"+i).attr('style', 'color:#f00;font-weight:bold')
				$("#lp-scr2-"+i).attr('style', 'color:#f00;font-weight:bold')
				$("#lp-scr3-"+i).attr('style', 'color:#f00;font-weight:bold');
				(lbr_sheet == 0 || lbr_sheet < 0) ? $("#lp-lbrs-"+i).val(0).attr('style', 'color:#f00;font-weight:bold') : $("#lp-lbrs-"+i).val(rupiah.format(lbr_sheet)).attr('style', 'color:#f00;font-weight:normal');
			}
		}else if(kategori == 'K_SHEET'){
			if(score1 != 0 || score2 != 0 || score3 != 0){
				if(hitungScore == lbr_sheet){
					$("#lp-scr1-"+i).attr('style', 'color:#495057;font-weight:normal')
					$("#lp-scr2-"+i).attr('style', 'color:#495057;font-weight:normal')
					$("#lp-scr3-"+i).attr('style', 'color:#495057;font-weight:normal');
					(lbr_sheet == 0 || lbr_sheet < 0) ? $("#lp-lbrs-"+i).val(0).attr('style', 'color:#f00;font-weight:bold') : $("#lp-lbrs-"+i).val(rupiah.format(lbr_sheet)).attr('style', 'color:#ff0066;font-weight:bold');
				}else{
					$("#lp-scr1-"+i).attr('style', 'color:#f00;font-weight:bold')
					$("#lp-scr2-"+i).attr('style', 'color:#f00;font-weight:bold')
					$("#lp-scr3-"+i).attr('style', 'color:#f00;font-weight:bold');
					(lbr_sheet == 0 || lbr_sheet < 0) ? $("#lp-lbrs-"+i).val(0).attr('style', 'color:#f00;font-weight:bold') : $("#lp-lbrs-"+i).val(rupiah.format(lbr_sheet)).attr('style', 'color:#f00;font-weight:normal');
				}
			}else{
				$("#lp-scr1-"+i).attr('style', 'color:#495057;font-weight:normal')
				$("#lp-scr2-"+i).attr('style', 'color:#495057;font-weight:normal')
				$("#lp-scr3-"+i).attr('style', 'color:#495057;font-weight:normal');
				(lbr_sheet == 0 || lbr_sheet < 0) ? $("#lp-lbrs-"+i).val(0).attr('style', 'color:#f00;font-weight:bold') : $("#lp-lbrs-"+i).val(rupiah.format(lbr_sheet)).attr('style', 'color:#ff0066;font-weight:bold');
			}
		}

		let qty_so = $("#lp-pcs-plan-"+i).val()
		
		let out_plan = $("#lp-out-"+i).val();
		(out_plan == 0 || out_plan < 0) ? $("#lp-out-"+i).val(0).attr('style', 'color:#f00;font-weight:bold') : $("#lp-out-"+i).val(out_plan).attr('style', 'color:#000;font-weight:normal');

		let lbr_i = $("#lp-lbri-"+i).val().split('.').join('');
		(lbr_i == 0 || lbr_i < 0) ? $("#lp-lbri-"+i).val(0).attr('style', 'color:#f00;font-weight:bold') : $("#lp-lbri-"+i).val(rupiah.format(lbr_i)).attr('style', 'color:#000;font-weight:bold');

		let flute = $("#hlp-flute-"+i).val();
		let kualitas_isi = $("#hlp-kua-isi-p-"+i).val();
		let spltKualitas = kualitas_isi.split("/");
		let ton = 0;
		if(flute == 'BF'){
			if(lpSi1 == "" || lpSi1 == 0 || lpSi2 == "" || lpSi2 == 0 || lpSi5 == "" || lpSi5 == 0){
				ton = 0
			}else{
				ton = parseFloat((parseInt(lpSi1) + (parseFloat(lpSi2)*1.36) + parseInt(lpSi5)) / 1000 * pjg_sheet / 1000 * lbr_sheet / 1000 * qty_so)
			}
		}else if(flute == 'CF'){
			if(lpSi1 == "" || lpSi1 == 0 || lpSi4 == "" || lpSi4 == 0 || lpSi5 == "" || lpSi5 == 0){
				ton = 0
			}else{
				ton = parseFloat((parseInt(lpSi1) + (parseFloat(lpSi4)*1.46) + parseInt(lpSi5)) / 1000 * pjg_sheet / 1000 * lbr_sheet / 1000 * qty_so)
			}
		}else if(flute == 'BCF'){
			if(lpSi1 == "" || lpSi1 == 0 || lpSi2 == "" || lpSi2 == 0 || lpSi3 == "" || lpSi3 == 0 || lpSi4 == "" || lpSi4 == 0 || lpSi5 == "" || lpSi5 == 0){
				ton = 0
			}else{
				ton = parseFloat((parseInt(lpSi1) + (parseFloat(lpSi2)*1.36) + parseInt(lpSi3) + (parseFloat(lpSi4)*1.46) + parseInt(lpSi5)) / 1000 * pjg_sheet / 1000 * lbr_sheet / 1000 * qty_so)
			}
		}else{
			ton = 0
		}

		let trim = 0;
		let c_off = 0;
		let rm = 0;
		(lbr_i == '' || out_plan == '' || lbr_i == 0 || out_plan == 0) ? trim = 0 : trim = Math.round(lbr_i - (lbr_sheet * out_plan));
		(out_plan == '' || out_plan == 0) ? c_off = 0 : c_off = Math.round(qty_so / out_plan);
		(c_off == '' || c_off == 0) ? rm = 0 : rm = Math.round((c_off * pjg_sheet) / 1000);

		(trim < 0 || trim == 0) ? $("#lp-trimp-"+i).val(0).attr('style', 'color:#f00;font-weight:bold') : $("#lp-trimp-"+i).val(trim).attr('style', 'color:#000;font-weight:normal');
		(c_off < 0 || isNaN(c_off) || c_off == 0) ? $("#lp-coffp-"+i).val(0).attr('style', 'color:#f00;font-weight:bold') : $("#lp-coffp-"+i).val(rupiah.format(c_off)).attr('style', 'color:#000;font-weight:normal');
		(rm < 0 || isNaN(rm) || rm == 0) ? $("#lp-rmp-"+i).val(0).attr('style', 'color:#f00;font-weight:bold') : $("#lp-rmp-"+i).val(rupiah.format(rm)).attr('style', 'color:#000;font-weight:normal');
		(ton < 0 || ton == 0) ? $("#lp-tonp-"+i).val(0).attr('style', 'color:#f00;font-weight:bold') : $("#lp-tonp-"+i).val(rupiah.format(Math.round(ton))).attr('style', 'color:#000;font-weight:normal');
	}

	function editListPlan(i, id_wo, opsi)
	{
		let flute =  $("#hlp-flute-"+i).val()
		let lpSm1 = $("#lp-sm1-"+i).val()
		let lpSi1 = $("#lp-si1-"+i).val()
		let lpSm2 = $("#lp-sm2-"+i).val()
		let lpSi2 = $("#lp-si2-"+i).val()
		let lpSm3 = $("#lp-sm3-"+i).val()
		let lpSi3 = $("#lp-si3-"+i).val()
		let lpSm4 = $("#lp-sm4-"+i).val()
		let lpSi4 = $("#lp-si4-"+i).val()
		let lpSm5 = $("#lp-sm5-"+i).val()
		let lpSi5 = $("#lp-si5-"+i).val();
		let material = ''; let kualitas = ''; let kualitas_isi = '';
		if(flute == 'BF'){
			if(lpSm1 == "" || lpSi1 == "" || lpSi1 == 0 || lpSm2 == "" || lpSi2 == "" || lpSi2 == 0 || lpSm5 == "" || lpSi5 == "" || lpSi5 == 0){
				toastr.error('<b>CEK KEMBALI KUALITAS!</b>')
				material = ''; kualitas = ''; kualitas_isi = '';
				return
			}else{
				material = `${lpSm1}/${lpSm2}/${lpSm5}`
				kualitas = `${lpSm1}${lpSi1}/${lpSm2}${lpSi2}/${lpSm5}${lpSi5}`
				kualitas_isi = `${lpSi1}/${lpSi2}/${lpSi5}`
			}
		}
		if(flute == 'CF'){
			if(lpSm1 == "" || lpSi1 == "" || lpSi1 == 0 || lpSm4 == "" ||  lpSi4 == "" || lpSi4 == 0 || lpSm5 == "" || lpSi5 == "" || lpSi5 == 0){
				toastr.error('<b>CEK KEMBALI KUALITAS!</b>')
				material = ''; kualitas = ''; kualitas_isi = '';
				return
			}else{
				material = `${lpSm1}/${lpSm4}/${lpSm5}`
				kualitas = `${lpSm1}${lpSi1}/${lpSm4}${lpSi4}/${lpSm5}${lpSi5}`
				kualitas_isi = `${lpSi1}/${lpSi4}/${lpSi5}`
			}
		}
		if(flute == 'BCF'){
			if(lpSm1 == "" || lpSi1 == "" || lpSi1 == 0 || lpSm2 == "" || lpSi2 == "" || lpSi2 == 0 || lpSm3 == "" || lpSi3 == "" || lpSi3 == 0 || lpSm4 == "" || lpSi4 == "" || lpSi4 == 0 || lpSm5 == "" || lpSi5 == "" || lpSi5 == 0){
				toastr.error('<b>CEK KEMBALI KUALITAS!</b>')
				material = ''; kualitas = ''; kualitas_isi = '';
				return
			}else{
				material = `${lpSm1}/${lpSm2}/${lpSm3}/${lpSm4}/${lpSm5}`
				kualitas = `${lpSm1}${lpSi1}/${lpSm2}${lpSi2}/${lpSm3}${lpSi3}/${lpSm4}${lpSi4}/${lpSm5}${lpSi5}`
				kualitas_isi = `${lpSi1}/${lpSi2}/${lpSi3}/${lpSi4}/${lpSi5}`
			}
		}

		let panjang_plan = $("#lp-pjgs-"+i).val().split('.').join('')
		let lebar_plan = $("#lp-lbrs-"+i).val().split('.').join('')
		let kategori =  $("#hlp-kategori-"+i).val()
		let creasing_wo1 =  $("#lp-scr1-"+i).val()
		let creasing_wo2 =  $("#lp-scr2-"+i).val()
		let creasing_wo3 =  $("#lp-scr3-"+i).val()
		let hitungScore = parseInt(creasing_wo1) + parseInt(creasing_wo2) + parseInt(creasing_wo3);
		if(kategori == 'K_BOX'){
			if(creasing_wo1 == 0 || creasing_wo2 == 0 || creasing_wo3 == 0 || creasing_wo1 == '' || creasing_wo2 == '' || creasing_wo3 == ''){
				toastr.error('<b>SCORE BOX TIDAK BOLEH KOSONG!</b>');
				return
			}
		}
		if(kategori == 'K_BOX'){
			if(hitungScore != lebar_plan){
				toastr.error('<b>SCORE BEDA DENGAN LEBAR SHEET!</b>');
				return
			}
		}
		if(kategori == 'K_SHEET'){
			if(creasing_wo1 != 0 || creasing_wo2 != 0 || creasing_wo3 != 0){
				if(hitungScore != lebar_plan){
					toastr.error('<b>SCORE BEDA DENGAN LEBAR SHEET!</b>');
					return
				}
			}
		}

		let lebar_roll_p = $("#lp-lbri-"+i).val().split('.').join('')
		let out_plan = $("#lp-out-"+i).val()
		let trim_plan = $("#lp-trimp-"+i).val().split('.').join('')
		let c_off_p = $("#lp-coffp-"+i).val().split('.').join('')
		let rm_plan = $("#lp-rmp-"+i).val().split('.').join('')
		let tonase_plan = $("#lp-tonp-"+i).val().split('.').join('')
		if(panjang_plan == '' || panjang_plan == 0 || lebar_plan == '' || lebar_plan == 0 || lebar_roll_p == '' || lebar_roll_p == 0 || out_plan == '' || out_plan == 0 || trim_plan == '' || trim_plan == 0 || c_off_p == '' || c_off_p == 0 || rm_plan == '' || rm_plan == 0 || tonase_plan == '' || tonase_plan == 0){
			toastr.error('<b>HITUNG DATA KOSONG!</b>');
			return
		}
		if(panjang_plan < 0 || lebar_plan < 0 || lebar_roll_p < 0 || out_plan < 0 || trim_plan < 0 || c_off_p < 0 || rm_plan < 0 || tonase_plan < 0){
			toastr.error('<b>HITUNG KURANG!</b>');
			return
		}

		let tglkirim = $("#lp-tglkirim-"+i).val()
		let next = $("#lp-next-"+i).val()
		if(tglkirim == ""){
			toastr.error('<b>TANGGAL KIRIM KOSONG!</b>');
			return
		}
		if(next == ""){
			toastr.error('<b>NEXT KOSONG!</b>');
			return
		}

		$.ajax({
			url: '<?php echo base_url('Plan/editListPlan')?>',
			type: "POST",
			beforeSend: function() {
				swal({
					title: 'Loading',
					allowEscapeKey: false,
					allowOutsideClick: false,
					onOpen: () => {
						swal.showLoading();
					}
				});
			},
			data: ({
				id_plan: i, id_wo, material, kualitas, kualitas_isi, panjang_plan, lebar_plan, kategori, creasing_wo1, creasing_wo2, creasing_wo3, lebar_roll_p, out_plan, trim_plan, c_off_p, rm_plan, tonase_plan, tglkirim, next, opsi
			}),
			success: function(res){
				data = JSON.parse(res)
				if(data.data){
					toastr.success(`<b>${data.msg}</b>`)
					loadData(urlTgl_plan, urlShift, urlMesin)
				}else{
					toastr.error(`<b>${data.msg}</b>`)
					swal.close()
				}
			}
		})
	}

	function hapusPlan(id_plan)
	{
		swal({
			title: "Apakah Kamu Yakin?",
			text: "",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#C00",
			confirmButtonText: "Delete"
		}).then(function(result) {
			$.ajax({
				url: '<?php echo base_url('Plan/hapusPlan')?>',
				type: "POST",
				beforeSend: function() {
					swal({
						title: 'Loading',
						allowEscapeKey: false,
						allowOutsideClick: false,
						onOpen: () => {
							swal.showLoading();
						}
					});
				},
				data: ({
					id_plan
				}),
				success: function(res){
					data = JSON.parse(res)
					if(data.data){
						loadData(urlTgl_plan, urlShift, urlMesin)
					}else{
						swal(data.msg, "", "error")
					}
				}
			})
		});
	}

	function addRencanaPlan(opsi = '')
	{
		let tgl_plan = $("#tgl").val()
		let machine_plan = $("#mesin").val()
		let shift_plan = $("#shift").val()
		if(tgl_plan == '' || machine_plan == '' || shift_plan == ''){
			toastr.error('<b>PILIH PLAN!</b>');
			return
		};

		let customer = $("#customer").val()
		let nm_produk = $("#item").val();
		let id_plan = $("#ehid_plan").val();
		let id_so_detail = (opsi == 'add') ? $('#no_wo option:selected').attr('id-so') : $("#ehid_so_detail").val() ;
		let id_wo = (opsi == 'add') ? $('#no_wo option:selected').attr('id-wo') : $("#ehid_wo").val() ;
		let id_produk = (opsi == 'add') ? $('#no_wo option:selected').attr('id-produk') : $("#ehid_produk").val() ;
		let id_pelanggan = (opsi == 'add') ? $('#no_wo option:selected').attr('id-pelanggan') : $("#ehid_pelanggan").val() ;
		let no_wo = (opsi == 'add') ? $('#no_wo option:selected').attr('no-wo') : $("#ehno_wo").val() ;
		if(id_so_detail == undefined || id_wo == undefined || id_produk == undefined || id_pelanggan == undefined || no_wo == undefined){
			toastr.error('<b>PILIH NO. WO!</b>');
			return
		};
		
		let kode_po = $("#kode_po").val();
		let no_so = (opsi == 'add') ? $('#no_wo option:selected').attr('no-so') : $("#ehno_so").val();
		let urut_so = (opsi == 'add') ? $('#no_wo option:selected').attr('urut-so') : $("#ehurut_so").val();
		let rpt = (opsi == 'add') ? $('#no_wo option:selected').attr('rpt') : $("#ehrpt").val();
		(urut_so == undefined) ? urut_so = '' : urut_so = urut_so;
		(rpt == undefined) ? rpt = '' : rpt = rpt;
		(urut_so.length == 1 ) ? urut_so = '.0'+urut_so : urut_so = urut_so;
		(rpt.length == 1 ) ? rpt = '.0'+rpt : rpt = rpt;
		(no_so == undefined) ? no_so = '' : no_so = no_so+urut_so+rpt;
		if(urut_so == '' || rpt == '' || no_so == ''){
			toastr.error('<b>NO. SO KOSONG!</b>');
			return
		}
		
		let panjang_plan = $("#ii_panjang").val().split('.').join('');
		let lebar_plan = $("#ii_lebar").val().split('.').join('')

		let creasing_wo1 = $("#creasing_wo_1").val()
		let creasing_wo2 = $("#creasing_wo_2").val()
		let creasing_wo3 = $("#creasing_wo_3").val()

		let hitungScore = parseInt(creasing_wo1) + parseInt(creasing_wo2) + parseInt(creasing_wo3);
		let kategori = $("#ehkategori").val()

		if(kategori == 'K_BOX'){
			if(creasing_wo1 == 0 || creasing_wo2 == 0 || creasing_wo3 == 0 || creasing_wo1 == '' || creasing_wo2 == '' || creasing_wo3 == ''){
				toastr.error('<b>SCORE BOX TIDAK BOLEH KOSONG!</b>');
				return
			}
		}
		if(kategori == 'K_BOX'){
			if(hitungScore != lebar_plan){
				toastr.error('<b>SCORE BEDA DENGAN LEBAR SHEET!</b>');
				return
			}
		}
		if(kategori == 'K_SHEET'){
			if(creasing_wo1 != 0 || creasing_wo2 != 0 || creasing_wo3 != 0){
				if(hitungScore != lebar_plan){
					toastr.error('<b>SCORE BEDA DENGAN LEBAR SHEET!</b>');
					return
				}
			}
		}

		let lebar_roll_p = $("#i_lebar_roll").val().split('.').join('')
		let out_plan = $("#out_plan").val()
		let trim_plan = $("#trim").val().split('.').join('')
		let c_off_p = $("#c_off").val().split('.').join('')
		let rm_plan = $("#rm").val().split('.').join('')
		let tonase_plan = $("#ton").val().split('.').join('')

		let kupingan = $("#kupingan").val()
		let p1 = $("#p1").val()
		let l1 = $("#l1").val()
		let p2 = $("#p2").val()
		let l2 = $("#l2").val()
		let panjangWO = parseInt(kupingan) + parseInt(p1) + parseInt(l1) + parseInt(p2) + parseInt(l2) + 5;

		if(panjang_plan == '' || panjang_plan == 0 || lebar_plan == '' || lebar_plan == 0 || lebar_roll_p == '' || lebar_roll_p == 0 || out_plan == '' || out_plan == 0 || trim_plan == '' || trim_plan == 0 || c_off_p == '' || c_off_p == 0 || rm_plan == '' || rm_plan == 0 || tonase_plan == '' || tonase_plan == 0){
			toastr.error('<b>HITUNG DATA KOSONG!</b>');
			return
		}
		if(panjang_plan < 0 || lebar_plan < 0 || lebar_roll_p < 0 || out_plan < 0 || trim_plan < 0 || c_off_p < 0 || rm_plan < 0 || tonase_plan < 0){
			toastr.error('<b>HITUNG KURANG!</b>');
			return
		}
		if(kategori == 'K_BOX'){
			if(panjang_plan != panjangWO){
				toastr.error('<b>PANJANG WO BEDA DENGAN PANJANG SHEET!</b>');
				return
			}
		}

		let pcs_plan = (opsi == 'add') ? $('#no_wo option:selected').attr('qty-so') : $("#ehpcs_plan").val();
		(pcs_plan == undefined) ? pcs_plan = '' : pcs_plan = pcs_plan.split('.').join('');
		let kualitas_plan = $("#input_kualitas_plan").val();
		let kualitas_isi_plan = $("#input_kualitas_plan_isi").val()
		let material_plan = $("#input_material_plan").val()
		if(kualitas_plan == '' || kualitas_isi_plan == '' || material_plan == ''){
			toastr.error('<b>KUALITAS KOSONG!</b>');
			return
		}

		let tgl_kirim_plan = $("#kirim").val()
		let next_plan = $("#next_flexo").val()
		if(tgl_kirim_plan == '' || next_plan == ''){
			toastr.error('<b>TGL KIRIM / NEXT PLAN KOSONG!</b>');
			return
		}

		$.ajax({
			url: '<?php echo base_url('Plan/addRencanaPlan')?>',
			type: "POST",
			beforeSend: function() {
				swal({
					title: 'Loading',
					allowEscapeKey: false,
					allowOutsideClick: false,
					onOpen: () => {
						swal.showLoading();
					}
				});
			},
			data: ({
				id_plan, id_so_detail, id_wo, id_produk, id_pelanggan, no_wo, no_so, pcs_plan, tgl_plan, machine_plan, shift_plan, tgl_kirim_plan, next_plan, lebar_roll_p, out_plan, trim_plan, c_off_p, rm_plan, tonase_plan, kualitas_plan, kualitas_isi_plan, material_plan, panjang_plan, lebar_plan,creasing_wo1, creasing_wo2, creasing_wo3, customer, nm_produk, kode_po, kupingan, p1, l1, p2, l2, panjangWO, kategori, opsi
			}),
			success: function(res){
				data = JSON.parse(res)
				if(opsi == 'add'){	
					if(data.data){
						toastr.success(`<b>BERHASIL TAMBAH!</b>`)
						$("#no_wo").val("")
						listRencanaPlan()
					}else{
						swal(data.isi, "", "error")
						return
					}
				}else{
					if(data.updatePlanCor){
						toastr.success(`<b>${data.msg}</b>`)
						listRencanaPlan()
					}else{
						toastr.error(`<b>${data.msg}</b>`)
						swal.close()
					}
				}
			}
		})
	}

	function selesaiPlan(id_plan)
	{
		let good_cor_p = $("#good_cor").val()
		let bad_cor_p = $("#bad_cor").val()
		let total_cor_p = $("#total_cor").val()
		let ket_plan = $("#ket_cor").val()
		let tgl_cor = $("#tgl_cor").val()
		let start_cor = $("#start_cor").val()
		let end_cor = $("#end_cor").val()
		if(good_cor_p < 0 || good_cor_p == 0 || good_cor_p == '' || total_cor_p < 0 || total_cor_p == 0 || total_cor_p == '' || tgl_cor == '' || start_cor == '' || end_cor == ''){
			swal("DATA PRODUKSI KOSONG!", "", "error")
			return
		}
		$.ajax({
			url: '<?php echo base_url('Plan/selesaiPlan')?>',
			type: "POST",
			beforeSend: function() {
				swal({
					title: 'Loading',
					allowEscapeKey: false,
					allowOutsideClick: false,
					onOpen: () => {
						swal.showLoading();
					}
				});
			},
			data: ({
				id_plan
			}),
			success: function(res){
				data = JSON.parse(res)
				if(data.insertGudang == true && data.statusPlan == true){
					loadPlanWo('')
					plhNoWo(id_plan)
				}else{
					swal("Ada Kesalahan!", "", "error")
				}
			}
		})
	}

	function hitungProduksi()
	{
		let rp = new Intl.NumberFormat('id-ID', {styles: 'currency', currency: 'IDR'})
		let good = $("#good_cor").val().split('.').join('');
		(good < 0 || good == 0 || good == '') ? good = 0 : good = good;
		$("#good_cor").val(rp.format(good));
		let bad = $("#bad_cor").val().split('.').join('');
		(bad < 0 || bad == 0 || bad == '') ? bad = 0 : bad = bad;
		$("#bad_cor").val(rp.format(bad));
		let hitung = parseInt(good) + parseInt(bad);
		$("#total_cor").val(rp.format(hitung));
	}

	function produksiRencanaPlan(id_plan)
	{
		let good_cor_p = $("#good_cor").val().split('.').join('')
		let bad_cor_p = $("#bad_cor").val().split('.').join('')
		let total_cor_p = $("#total_cor").val().split('.').join('')
		let ket_plan = $("#ket_cor").val()
		let tgl_cor = $("#tgl_cor").val()
		let start_cor = $("#start_cor").val()
		let end_cor = $("#end_cor").val()
		if(good_cor_p < 0 || good_cor_p == 0 || good_cor_p == '' || total_cor_p < 0 || total_cor_p == 0 || total_cor_p == ''){
			swal("DATA PRODUKSI TIDAK BOLEH KOSONG!", "", "error")
			return
		}
		if(tgl_cor == '' || start_cor == '' || end_cor == ''){
			swal("TANGGAL/START/END TIDAK BOLEH KOSONG!", "", "error")
			return
		}

		$.ajax({
			url: '<?php echo base_url('Plan/produksiRencanaPlan')?>',
			type: "POST",
			beforeSend: function() {
				swal({
					title: 'Loading',
					allowEscapeKey: false,
					allowOutsideClick: false,
					onOpen: () => {
						swal.showLoading();
					}
				});
			},
			data: ({
				id_plan, good_cor_p, bad_cor_p, total_cor_p, ket_plan, tgl_cor, start_cor, end_cor
			}),
			success: function(res){
				data = JSON.parse(res)
				if(data){
					loadPlanWo('')
					plhNoWo(id_plan)
				}else{
					swal(data.msg, "", "error");
				}
			}
		})
	}

	function hapusCartItem(rowid)
	{
		$.ajax({
			url: '<?php echo base_url('Plan/hapusCartItem')?>',
			type: "POST",
			beforeSend: function() {
				swal({
					title: 'Loading',
					allowEscapeKey: false,
					allowOutsideClick: false,
					onOpen: () => {
						swal.showLoading();
					}
				});
			},
			data: ({
				rowid
			}),
			success: function(res){
				listRencanaPlan()
			}
		})
	}

	function listRencanaPlan()
	{
		$.ajax({
			url: '<?php echo base_url('Plan/listRencanaPlan')?>',
			beforeSend: function() {
				swal({
					title: 'Loading',
					allowEscapeKey: false,
					allowOutsideClick: false,
					onOpen: () => {
						swal.showLoading();
					}
				});
			},
			type: "POST",
			success: function(res){
				plhNoWo('')
				$("#list-rencana-plan").html(res);
			}
		})
	}

	function selesaiPlanWO(id_plan, id_wo)
	{
		$.ajax({
			url: '<?php echo base_url('Plan/selesaiPlanWO')?>',
			type: "POST",
			beforeSend: function() {
				swal({
					title: 'Loading',
					allowEscapeKey: false,
					allowOutsideClick: false,
					onOpen: () => {
						swal.showLoading();
					}
				});
			},
			data: ({
				id_wo
			}),
			success: function(res){
				data = JSON.parse(res)
				if(data.data){
					loadData(urlTgl_plan, urlShift, urlMesin)
				}else{
					swal(data.msg, "", "error")
				}
			}
		})
	}

	function simpanCartItem()
	{
		let tgl_plan = $("#tgl").val()
		let machine_plan = $("#mesin").val()
		let shift_plan = $("#shift").val()
		let no_plan = $("#ehno_plan").val()
		$("#simpan-cart-cor").prop("disabled", true)
		$.ajax({
			url: '<?php echo base_url('Plan/simpanCartItem')?>',
			type: "POST",
			data: ({
				no_plan, tgl_plan, machine_plan, shift_plan
			}),
			beforeSend: function() {
				swal({
					title: 'Loading',
					allowEscapeKey: false,
					allowOutsideClick: false,
					onOpen: () => {
						swal.showLoading();
					}
				});
			},
			success: function(res){
				$("#list-rencana-plan").load("<?php echo base_url('Plan/destroyPlan') ?>")
				loadData(urlTgl_plan, urlShift, urlMesin)
			}
		})
	}

	function showCartitem(rowid, opsi)
	{
		$("#show-list-plh-item").html(`. . .`)
		$("#modalForm").modal("show")
		$.ajax({
			url: '<?php echo base_url('Plan/showCartitem')?>',
			type: "POST",
			beforeSend: function() {
				swal({
					title: 'Loading',
					allowEscapeKey: false,
					allowOutsideClick: false,
					onOpen: () => {
						swal.showLoading();
					}
				});
			},
			data: ({ rowid, opsi }),
			success: function(res){
				$("#show-list-plh-item").html(res)
				swal.close()
			}
		})
	}

	function loadPlanWo(opsi = '')
	{
		$("#no_wo").prop("disabled", true).html(`<option value="">PILIH</option>`)
		$.ajax({
			url: '<?php echo base_url('Plan/loadPlanWo')?>',
			type: "POST",
			beforeSend: function() {
				swal({
					title: 'Loading',
					allowEscapeKey: false,
					allowOutsideClick: false,
					onOpen: () => {
						swal.showLoading();
					}
				});
			},
			data: ({
				opsi, urlNoPlan
			}),
			success: function(res){
				data = JSON.parse(res)
				let htmlWO = ''
				let kategori = ''
					htmlWO += `<option value="">PILIH</option>`
				data.wo.forEach(loadWo);
				function loadWo(r, index) {
					(r.kategoriItems == 'K_BOX') ? kategori = '[ BOX ]' : kategori = '[ SHEET ]';
					htmlWO += `<option value="${r.idWo}"
						id-wo="${r.idWo}"
						id-so="${r.idSoDetail}"
						id-pelanggan="${r.id_pelanggan}"
						id-produk="${r.id_produk}"
						tgl-wo="${r.tgl_wo}"
						no-wo="${r.no_wo}"
						no-so="${r.no_so}"
						urut-so="${r.urut_so}"
						rpt="${r.rpt}"
						eta-so="${r.eta_so}"
						tgl-po="${r.tgl_po}"
						no-po="${r.no_po}"
						kode-po="${r.kode_po}"
						qty-po="${r.total_qty}"
						customer="${r.nm_pelanggan}"
						nm-sales="${r.nm_sales}"
						alamat="${r.alamat}"
						provinsi="${r.prov}"
						kabupaten="${r.kab}"
						item="${r.nm_produk}"
						kode-mc="${r.kode_mc}"
						uk-box="${r.ukuran}"
						uk-sheet="${r.ukuran_sheet}"
						panjang-s="${r.ukuran_sheet_p}"
						p1="${r.p1}"
						l1="${r.l1}"
						p2="${r.p2}"
						l2="${r.l2}"
						kupingan="${r.kupingan}"
						panjangwo="${r.p1_sheet}"
						jumlah-plan="${r.jml_plan}"
						lebar-s="${r.ukuran_sheet_l}"
						creasing-1="${r.creasing}"
						creasing-2="${r.creasing2}"
						creasing-3="${r.creasing3}"
						material="${r.material}"
						kualitas="${r.kualitas}"
						kualitas-isi="${r.kualitas_isi}"
						flute="${r.flute}"
						kategori="${r.kategoriItems}"
						tipe-box="${r.tipe_box}"
						sambungan="${r.sambungan}"
						berat-box="${r.berat_bersih}"
						luas-box="${r.luas_bersih}"
						qty-so="${r.qty_so}"
						rm-so="${r.rm}"
						ton-so="${r.ton}"
						ket-so="${r.ket_so}"
						creasing-wo1="${r.flap1}"
						creasing-wo2="${r.creasing2wo}"
						creasing-wo3="${r.flap2}"
					>
						${kategori} ${r.no_wo} | ${r.eta_so} | ${r.nm_produk} | ${r.nm_pelanggan}
					</option>`
				}

				$("#no_wo").prop("disabled", false).html(htmlWO)
			}
		})
	}

	function plhNoWo(opsi = '')
	{
		$("#dt-pilih").html('')
		$("#dt-select").html('')
		$("#dt-load-data").html('')
		let id_wo = ''; let id_so = ''; let id_pelanggan = ''; let id_produk = ''; let tgl_wo = ''; let no_wo = ''; let no_so = ''; let urut_so = ''; let rpt = ''; let eta_so = ''; let tgl_po = ''; let no_po = ''; let kode_po = ''; let qty_po = ''; let customer = ''; let nm_sales = ''; let item = ''; let kode_mc = ''; let uk_box = ''; let uk_sheet = ''; let panjang_s = ''; let lebar_s = ''; let creasing_1 = ''; let creasing_2 = ''; let creasing_3 = ''; let material = ''; let kualitas = ''; let kualitas_isi = ''; let flute = ''; let tipe_box = ''; let sambungan = ''; let berat_box = ''; let luas_box = ''; let qty_so = ''; let rm_so = ''; let ton_so = ''; let ket_so = ''; let creasing_wo1 = ''; let creasing_wo2 = ''; let creasing_wo3 = ''; let panjang_plan = ''; let lebar_plan = ''; let out_plan = ''; let lebar_roll_p = ''; let trim_plan = ''; let c_off_p = ''; let rm_plan = ''; let tonase_plan = ''; let kategori = ''; let jumlahPlan = ''; let kupingan = ''; let p1 = ''; let l1 = ''; let p2 = ''; let l2 = '' 

		$.ajax({
			url: '<?php echo base_url('Plan/loadPlanWo')?>',
			type: "POST",
			data: ({ opsi, urlNoPlan }),
			beforeSend: function() {
				swal({
					title: 'Loading',
					allowEscapeKey: false,
					allowOutsideClick: false,
					onOpen: () => {
						swal.showLoading();
					}
				});
			},
			success: function(res){
				data = JSON.parse(res)
				if(data.data){
					id_wo = data.wo.id_wo
					id_so = data.wo.id_so_detail
					id_pelanggan = data.wo.id_pelanggan
					id_produk = data.wo.id_produk
					tgl_wo = data.wo.tgl_wo
					no_wo = data.wo.no_wo
					no_so = data.wo.no_so
					urut_so = data.wo.urut_so
					rpt = data.wo.rpt
					eta_so = data.wo.eta_so
					tgl_po = data.wo.tgl_po
					no_po = data.wo.no_po
					kode_po = data.wo.kode_po
					qty_po = data.wo.qtyPoWo
					customer = data.wo.nm_pelanggan
					nm_sales = data.wo.nm_sales
					alamat = data.wo.alamat
					provinsi = data.wo.prov
					kabupaten = data.wo.kab
					item = data.wo.nm_produk
					kode_mc = data.wo.kode_mc
					uk_box = data.wo.ukuran
					uk_sheet = data.wo.ukuran_sheet
					panjang_s = data.wo.panjang_plan
					lebar_s = data.wo.lebar_plan
					creasing_1 = data.wo.creasing
					creasing_2 = data.wo.creasing2mproduk
					creasing_3 = data.wo.creasing3
					material = data.wo.material_plan
					kualitas = data.wo.kualitas
					kualitas_plan = data.wo.kualitas_plan
					kualitas_isi = data.wo.kualitas_isi
					kualitas_isi_plan = data.wo.kualitas_isi_plan
					flute = data.wo.flute
					kategori = data.wo.kategoriItems
					tipe_box = data.wo.tipe_box
					sambungan = data.wo.sambungan
					berat_box = data.wo.berat_bersih
					luas_box = data.wo.luas_bersih
					qty_so = data.wo.qty_so
					pcs_plan = data.wo.pcs_plan
					rm_so = data.wo.rm
					ton_so = data.wo.ton
					ket_so = data.wo.ket_so
					creasing_wo1 = data.wo.flap1
					creasing_wo2 = data.wo.creasing2wo
					creasing_wo3 = data.wo.flap2
					out_plan = data.wo.out_plan
					lebar_roll_p = data.wo.lebar_roll_p
					trim_plan = data.wo.trim_plan
					c_off_p = data.wo.c_off_p
					rm_plan = data.wo.rm_plan
					tonase_plan = data.wo.tonase_plan
					tgl_kirim_plan = data.wo.tgl_kirim_plan
					next_plan = data.wo.next_plan
					kupingan = data.wo.kupingan;
					p1 = data.wo.p1;
					l1 = data.wo.l1;
					p2 = data.wo.p2;
					l2 = data.wo.l2;
					jumlahPlan = data.wo.jml_plan;

					if(data.wo.total_cor_p != 0){
						inputDtProd = 'inputDowntimeProduksi'
						$("#card-produksi").show()
					}else if(data.urutDtProd == null){
						inputDtProd = ''
						$("#card-produksi").hide()
						$("#btn-aksi-produksi").html(``)
					}else if(data.wo.id_plan == data.urutDtProd.id_plan){
						inputDtProd = 'inputDowntimeProduksi'
						$("#card-produksi").show()
					}else{
						inputDtProd = ''
						$("#card-produksi").hide()
						$("#btn-aksi-produksi").html(``)
					}

					let tms = '';
					(data.wo.status_plan == 'Open' && data.wo.total_cor_p == 0) ? tms = false : tms = true;
					$("#tgl").val(urlTgl_plan).prop("disabled", tms)
					let optShift = ''
					let optMesin = '';
					if(urlShift == 1){
						optShift = `<option value="1">1</option><option value="2">2</option><option value="3">3</option>`
					}else if(urlShift == 2){
						optShift = `<option value="2">2</option><option value="1">1</option><option value="3">3</option>`
					}else{
						optShift = `<option value="3">3</option><option value="2">2</option><option value="1">1</option>`
					}
					(urlMesin == 'CORR1') ? optMesin = `<option value="CORR1">CORR 1</option><option value="CORR2">CORR 2</option>` : optMesin = `<option value="CORR2">CORR 2</option><option value="CORR1">CORR 1</option>`;
					$("#shift").html(optShift).prop("disabled", tms)
					$("#mesin").html(optMesin).prop("disabled", tms)

					let htmlBtnGantiTgl = '';
					(data.wo.status_plan == 'Open' && data.wo.total_cor_p == 0 && (urlAuth == 'Admin' || urlAuth == 'PPIC' || urlAuth == 'User')) ?
						htmlBtnGantiTgl = `<div class="card-body row" style="padding:0 20px 5px">
							<div class="col-md-2"></div>
							<div class="col-md-10">
								<button class="btn btn-sm btn-warning" style="font-weight:bold" onclick="btnGantiTglPlan(${data.wo.id_plan})">GANTI</button>
							</div>
						</div>` : htmlBtnGantiTgl = '';
					$("#btn-ganti-tgl").html(htmlBtnGantiTgl)

					$("#ehid_plan").val(data.wo.id_plan)
					$("#ehid_so_detail").val(id_so)
					$("#ehid_wo").val(id_wo)
					$("#ehid_produk").val(id_produk)
					$("#ehid_pelanggan").val(id_pelanggan)
					$("#ehno_wo").val(no_wo)
					$("#ehno_so").val(no_so)
					$("#ehurut_so").val(urut_so)
					$("#ehrpt").val(rpt)
					$("#ehpcs_plan").val(pcs_plan)

					$("#no_wo").prop("disabled", true).html(`<option value="">PILIH</option>`)
					loadPlanWo('')

					$("#info-substance").val(kualitas_plan)

					let btnAddRencanaPlan = ''; let btnDiss = ''; let btnDissProd = ''; btnTxtProd = ''; let produksiRencanaPlan = '';
					let selesaiPlan = '';let btnAksiWo = '';
					if(data.wo.status_plan == 'Open'){
						(data.wo.total_cor_p != 0) ? btnAddRencanaPlan = '' : btnAddRencanaPlan = `onclick="addRencanaPlan(${data.wo.id_plan})"`;
						(data.wo.total_cor_p != 0) ? btnDiss = 'disabled' : btnDiss = '';
						(data.wo.total_cor_p != 0) ? selesaiPlan = `onclick="selesaiPlan(${data.wo.id_plan})"` : selesaiPlan = 'disabled';
						btnAksiWo = '';
						
						produksiRencanaPlan = `onclick="produksiRencanaPlan(${data.wo.id_plan})"`;
						btnDissProd = '';
						(data.wo.total_cor_p != 0) ? btnTxtProd = 'UPDATE' : btnTxtProd = 'SIMPAN';
					}else{
						btnAddRencanaPlan = ``;
						btnDiss = 'disabled';
						selesaiPlan = 'disabled';

						(data.wo.statusWo == 'Close') ?
						btnAksiWo = `<div class="col-md-12">
							<button type="button" class="btn btn-dark btn-block" disabled><i class="fas fa-check"></i> <b>SELESAI WO</b></button>
						</div>` :
						btnAksiWo = `<div class="col-md-12">
							<button type="button" class="btn btn-dark btn-block" onclick="selesaiPlanWO(${data.wo.id_plan}, ${data.wo.id_wo})"><i class="fas fa-check"></i> <b>SELESAI WO</b></button>
						</div>`;

						produksiRencanaPlan = '';
						btnDissProd = 'disabled';
						btnTxtProd = 'SIMPAN';
					}
					$("#btn-aksi-plan").html(`<div class="card-body row" style="padding:0 20px 20px;font-weight:bold">
						<div class="col-md-6">
							<button type="button" class="btn btn-warning btn-block" style="margin-bottom:5px" ${btnAddRencanaPlan} ${btnDiss}><i class="fa fa-plus"></i> <b>EDIT PLAN</b></button>
						</div>
						<div class="col-md-6">
							<button type="button" class="btn btn-primary btn-block" style="margin-bottom:5px" ${selesaiPlan}><i class="fas fa-check"></i> <b>SELESAI PLAN</b></button>
						</div>
						${btnAksiWo}
					</div>`);

					(data.wo.status_plan == 'Close') ? 
					$("#btn-aksi-produksi").html(``) :
					$("#btn-aksi-produksi").html(`<div class="card-body row" style="padding:20px 20px 0;font-weight:bold">
						<div class="col-md-12">
							<button type="button" class="btn btn-success btn-block" ${produksiRencanaPlan} ${btnDissProd}><i class="fa fa-save"></i> <b>${btnTxtProd}</b></button>
						</div>
					</div>`);

					$("#kirim").val(tgl_kirim_plan)

					$("#order_cor").val(new Intl.NumberFormat('id-ID', {styles: 'currency', currency: 'IDR'}).format(data.wo.qty_so))
					$("#good_cor").val(data.wo.good_cor_p)
					$("#bad_cor").val(data.wo.bad_cor_p)
					$("#total_cor").val(data.wo.total_cor_p)
					$("#ket_cor").val(data.wo.ket_plan)
					$("#tgl_cor").val(data.wo.tgl_prod_p)
					$("#start_cor").val(data.wo.start_time_p)
					$("#end_cor").val(data.wo.end_time_p)

					$("#downtime_cor").val("")
					hitungProduksi()
				}else{
					id_wo = $('#no_wo option:selected').attr('id-wo')
					id_so = $('#no_wo option:selected').attr('id-so')
					id_pelanggan = $('#no_wo option:selected').attr('id-pelanggan')
					id_produk = $('#no_wo option:selected').attr('id-produk')
					tgl_wo = $('#no_wo option:selected').attr('tgl-wo')
					no_wo = $('#no_wo option:selected').attr('no-wo')
					no_so = $('#no_wo option:selected').attr('no-so')
					urut_so = $('#no_wo option:selected').attr('urut-so')
					rpt = $('#no_wo option:selected').attr('rpt')
					eta_so = $('#no_wo option:selected').attr('eta-so')
					tgl_po = $('#no_wo option:selected').attr('tgl-po')
					no_po = $('#no_wo option:selected').attr('no-po')
					kode_po = $('#no_wo option:selected').attr('kode-po')
					qty_po = $('#no_wo option:selected').attr('qty-po')
					customer = $('#no_wo option:selected').attr('customer')
					nm_sales = $('#no_wo option:selected').attr('nm-sales')
					alamat = $('#no_wo option:selected').attr('alamat')
					provinsi = $('#no_wo option:selected').attr('provinsi')
					kabupaten = $('#no_wo option:selected').attr('kabupaten')
					item = $('#no_wo option:selected').attr('item')
					kode_mc = $('#no_wo option:selected').attr('kode-mc')
					uk_box = $('#no_wo option:selected').attr('uk-box')
					uk_sheet = $('#no_wo option:selected').attr('uk-sheet')
					panjang_s = $('#no_wo option:selected').attr('panjang-s')
					lebar_s = $('#no_wo option:selected').attr('lebar-s')
					creasing_1 = $('#no_wo option:selected').attr('creasing-1')
					creasing_2 = $('#no_wo option:selected').attr('creasing-2')
					creasing_3 = $('#no_wo option:selected').attr('creasing-3')
					material = $('#no_wo option:selected').attr('material')
					kualitas = $('#no_wo option:selected').attr('kualitas')
					kualitas_isi = $('#no_wo option:selected').attr('kualitas-isi')
					kualitas_isi_plan = $('#no_wo option:selected').attr('kualitas-isi')
					flute = $('#no_wo option:selected').attr('flute')
					kategori = $('#no_wo option:selected').attr('kategori')
					tipe_box = $('#no_wo option:selected').attr('tipe-box')
					sambungan = $('#no_wo option:selected').attr('sambungan')
					berat_box = $('#no_wo option:selected').attr('berat-box')
					luas_box = $('#no_wo option:selected').attr('luas-box')
					qty_so = $('#no_wo option:selected').attr('qty-so')
					rm_so = $('#no_wo option:selected').attr('rm-so')
					ton_so = $('#no_wo option:selected').attr('ton-so')
					ket_so = $('#no_wo option:selected').attr('ket-so')
					creasing_wo1 = $('#no_wo option:selected').attr('creasing-wo1')
					creasing_wo2 = $('#no_wo option:selected').attr('creasing-wo2')
					creasing_wo3 = $('#no_wo option:selected').attr('creasing-wo3')
					out_plan = 0
					lebar_roll_p = 0
					trim_plan = 0 
					c_off_p = 0 
					rm_plan = 0 
					tonase_plan = 0 
					tgl_kirim_plan = ''
					next_plan = ''
					inputDtProd = ''
					kupingan = $('#no_wo option:selected').attr('kupingan')
					p1 = $('#no_wo option:selected').attr('p1')
					l1 = $('#no_wo option:selected').attr('l1')
					p2 = $('#no_wo option:selected').attr('p2')
					l2 = $('#no_wo option:selected').attr('l2')
					jumlahPlan = $('#no_wo option:selected').attr('jumlah-plan')

					$("#tgl").val(urlTgl_plan).prop("disabled", true)
					$("#shift").val(urlShift).prop("disabled", true)
					$("#mesin").val(urlMesin).prop("disabled", true)
					$("#btn-ganti-tgl").html(``)

					$("#ehid_plan").val("");$("#ehid_so_detail").val("");$("#ehid_wo").val("");$("#ehid_produk").val("");$("#ehid_pelanggan").val("");$("#ehno_wo").val("");
					$("#ehno_so").val("");$("#ehurut_so").val("");$("#ehrpt").val("");$("#ehpcs_plan").val("")

					$("#info-substance").val(kualitas);
					$("#btn-aksi-plan").html(`<div class="card-body row" style="padding:0 20px 20px;font-weight:bold">
						<div class="col-md-12">
							<button type="button" class="btn btn-success btn-block" onclick="addRencanaPlan('add')"><i class="fa fa-plus"></i> <b>ADD PLAN</b></button>
						</div>
					</div>`)

					let inputHari = 0;
					let kirimEtaSO = '';
					(provinsi == 11 || kabupaten == 12 || kabupaten == 16) ? inputHari = 2 : inputHari = 1;
					(eta_so == undefined) ? kirimEtaSO = '' : kirimEtaSO = new Date(new Date(eta_so).getTime() - (inputHari * 24 * 3600 * 1000)).toISOString().slice(0, 10);
					$("#kirim").val(kirimEtaSO)

					$("#card-produksi").hide();
					$("#order_cor").val("");$("#good_cor").val("");$("#bad_cor").val("");$("#total_cor").val("");$("#ket_cor").val("");$("#tgl_cor").val();$("#start_cor").val("");$("#end_cor").val("");
					$("#btn-aksi-produksi").html(``);
				}

				(id_wo == undefined || id_so == undefined || id_pelanggan == undefined || id_produk == undefined) ? riwayatPlan(0, 0, 0, 0) : riwayatPlan(id_wo, id_so, id_pelanggan, id_produk);

				let rupiah = new Intl.NumberFormat('id-ID', {styles: 'currency', currency: 'IDR'});

				(tgl_wo == undefined || tgl_wo == '') ? tgl_wo = '' : tgl_wo = new Date(tgl_wo).toLocaleDateString("id-ID", optionsDay).toUpperCase();
				$("#tgl_wo").val(tgl_wo)
				$("#customer").val(customer)
				$("#sales").val(nm_sales)
				$("#alamat").val(alamat);

				(tgl_po == undefined) ? tgl_po = '' : tgl_po = new Date(tgl_po).toLocaleDateString("id-ID", optionsDay).toUpperCase();
				$("#tgl_po").val(tgl_po)
				$("#no_po").val(no_po)
				$("#kode_po").val(kode_po);
				(qty_po == undefined) ? $("#qty_po").val(qty_po) : $("#qty_po").val(rupiah.format(Math.round(qty_po)));
				
				(eta_so == undefined) ? txtEtaSo = '' : txtEtaSo = new Date(eta_so).toLocaleDateString("id-ID", optionsDay).toUpperCase();
				$("#eta_so").val(txtEtaSo);
				$("#eta_so_plan").val(eta_so);

				(urut_so == undefined) ? urut_so = '' : urut_so = urut_so;
				(rpt == undefined) ? rpt = '' : rpt = rpt;
				(urut_so.length == 1 ) ? urut_so = '.0'+urut_so : urut_so = urut_so;
				(rpt.length == 1 ) ? rpt = '.0'+rpt : rpt = rpt;
				(no_so == undefined) ? no_so = '' : no_so = no_so+urut_so+rpt;
				$("#no_so").val(no_so);

				(qty_so == undefined) ? $("#qty_so").val(qty_so) :  $("#qty_so").val(rupiah.format(Math.round(qty_so)));
				(rm_so == undefined) ? $("#rm_so").val(rm_so) :  $("#rm_so").val(rupiah.format(Math.round(rm_so)));
				(ton_so == undefined) ? $("#ton_so").val(ton_so) :  $("#ton_so").val(rupiah.format(Math.round(ton_so)));
				$("#ket_so").val((ket_so == "") ? '-' : ket_so);

				$("#kode_mc").val(kode_mc);
				$("#item").val(item)
				$("#uk_box").val(uk_box)
				$("#uk_sheet").val(uk_sheet)
				$("#creasing_1").val(creasing_1)
				$("#creasing_2").val(creasing_2)
				$("#creasing_3").val(creasing_3)
				$("#kualitas").val(kualitas)
				$("#flute").val(flute)
				$("#tipe_box").val(tipe_box)
				if(sambungan == 'G'){
					sambungan = 'GLUE'
				}else if(sambungan == 'S'){
					sambungan = 'STICHING'
				}else if(sambungan == 'D'){
					sambungan = 'DIE CUT'
				}else if(sambungan == '-'){
					sambungan = '-'
				}else{
					sambungan = ''
				}
				$("#sambungan").val(sambungan)
				$("#bb_box").val(berat_box)
				$("#lb_box").val(luas_box)

				$("#ehkategori").val(kategori)

				$("#creasing_wo_1").val(creasing_wo1).prop('disabled', (jumlahPlan > 1) ? true : false);
				$("#creasing_wo_2").val(creasing_wo2).prop('disabled', (jumlahPlan > 1) ? true : false);
				$("#creasing_wo_3").val(creasing_wo3).prop('disabled', (jumlahPlan > 1) ? true : false);
				let totcreasingwo = parseInt(creasing_wo1) + parseInt(creasing_wo2) + parseInt(creasing_wo3);
				(isNaN(totcreasingwo)) ? totcreasingwo = '' : totcreasingwo = totcreasingwo;
				(creasing_wo1 == 0 || creasing_wo2 == 0 || creasing_wo3 == 0) ? lebar_s = lebar_s : lebar_s = totcreasingwo;
				(lebar_s == undefined) ? $("#ii_lebar").val(lebar_s).prop('disabled', (jumlahPlan > 1) ? true : false) : $("#ii_lebar").val(rupiah.format(Math.round(lebar_s))).prop('disabled', (jumlahPlan > 1) ? true : false);

				$("#kupingan").val(kupingan)
				$("#p1").val(p1)
				$("#l1").val(l1)
				$("#p2").val(p2)
				$("#l2").val(l2)

				$("#txt-wo-kup").val(kupingan)
				$("#txt-wo-p1").val(p1)
				$("#txt-wo-l1").val(l1)
				$("#txt-wo-p2").val(p2)
				$("#txt-wo-l2").val(l2)
				
				$("#txt-wo-s1").val(creasing_wo1)
				$("#txt-wo-s2").val(creasing_wo2)
				$("#txt-wo-s3").val(creasing_wo3);
				(totcreasingwo == 0) ? totcreasingwo = lebar_s : totcreasingwo = totcreasingwo;
				$("#txt-wo-score").val(totcreasingwo);

				let panjangwo = (opsi != '') ? data.wo.panjang_plan : $('#no_wo option:selected').attr('panjangwo');
				if(kategori == 'K_BOX'){
					panjang_s = parseInt(p1) + parseInt(l1) + parseInt(p2) + parseInt(l2) + parseInt(kupingan) + 5
					$("#kupingan").val(kupingan).prop('disabled', (jumlahPlan > 1) ? true : false)
					$("#p1").val(p1).prop('disabled', (jumlahPlan > 1) ? true : false)
					$("#l1").val(l1).prop('disabled', (jumlahPlan > 1) ? true : false)
					$("#p2").val(p2).prop('disabled', (jumlahPlan > 1) ? true : false)
					$("#l2").val(l2).prop('disabled', (jumlahPlan > 1) ? true : false)
					$(".plus-lima").html('5')
				}else if(kategori == 'K_SHEET'){
					panjang_s = panjangwo
					$("#kupingan").val(0).prop('disabled', true)
					$("#p1").val(0).prop('disabled', true)
					$("#l1").val(0).prop('disabled', true)
					$("#p2").val(0).prop('disabled', true)
					$("#l2").val(0).prop('disabled', true)
					$(".plus-lima").html('0')
				}else{
					panjang_s = undefined
					$("#kupingan").val(0).prop('disabled', true)
					$("#p1").val(0).prop('disabled', true)
					$("#l1").val(0).prop('disabled', true)
					$("#p2").val(0).prop('disabled', true)
					$("#l2").val(0).prop('disabled', true)
					$(".plus-lima").html('0')
				}
				$("#txt-wo-pjgwo").val(panjang_s);
				
				(panjang_s == undefined) ? $("#ii_panjang").val(panjang_s).prop('disabled', (jumlahPlan > 1) ? true : false) : $("#ii_panjang").val(rupiah.format(Math.round(panjang_s))).prop('disabled', (jumlahPlan > 1) ? true : false);
				(qty_so == undefined) ? $("#qty_plan").val(qty_so) : $("#qty_plan").val(rupiah.format(Math.round(qty_so)));

				$("#input_material_plan").val(material)
				$("#input_kualitas_plan").val(kualitas)
				$("#input_kualitas_plan_isi").val(kualitas_isi);

				$("#i_lebar_roll").val(lebar_roll_p);
				$("#out_plan").val(out_plan)

				$("#trim").val(trim_plan)
				$("#c_off").val(c_off_p)
				$("#rm").val(rm_plan)
				$("#ton").val(tonase_plan)

				$("#h_ikpi").val(kualitas_isi_plan)

				$("#g_kualitas").html(`<option value="PO">KUALITAS SESUAI PO</option><option value="GANTI">GANTI KUALITAS</option>`)

				$("#tl_al").html(`<option value="">-</option><option value="M">M</option><option value="K">K</option><option value="MC">MC</option><option value="MN">MN</option>`).prop('disabled', true)
				$("#tl_al_i").val("").prop('disabled', true)
				$("#bmf").html(`<option value="">-</option><option value="M">M</option><option value="K">K</option><option value="MC">MC</option><option value="MN">MN</option>`).prop('disabled', true)
				$("#bmf_i").val("").prop('disabled', true)
				$("#bl").html(`<option value="">-</option><option value="M">M</option><option value="K">K</option><option value="MC">MC</option><option value="MN">MN</option>`).prop('disabled', true)
				$("#bl_i").val("").prop('disabled', true)
				$("#cmf").html(`<option value="">-</option><option value="M">M</option><option value="K">K</option><option value="MC">MC</option><option value="MN">MN</option>`).prop('disabled', true)
				$("#cmf_i").val("").prop('disabled', true)
				$("#cl").html(`<option value="">-</option><option value="M">M</option><option value="K">K</option><option value="MC">MC</option><option value="MN">MN</option>`).prop('disabled', true)
				$("#cl_i").val("").prop('disabled', true)
				$("#group_plh_kualitas").hide();

				(next_plan == '') ? next_plan = `` : next_plan = `<option value="${next_plan}">${next_plan}</option>` ;
				if(kategori == 'K_BOX'){
					$("#next_flexo").html(`${next_plan}<option value="">PILIH</option><option value="FLEXO1">FLEXO 1</option><option value="FLEXO2">FLEXO 2</option><option value="FLEXO3">FLEXO 3</option><option value="FLEXO4">FLEXO 4</option>`)
				}else{
					$("#next_flexo").html(`${next_plan}<option value="">PILIH</option><option value="GUDANG">GUDANG</option>`)
				}

				loadInputList('','','','')
				ayoBerhitung()
			}
		})
	}

	function riwayatPlan(id_wo, id_so, id_pelanggan, id_produk)
	{
		$("#riwayat-plan").html(``)
		$.ajax({
			url: '<?php echo base_url('Plan/riwayatPlan')?>',
			type: "POST",
			beforeSend: function() {
				swal({
					title: 'Loading',
					allowEscapeKey: false,
					allowOutsideClick: false,
					onOpen: () => {
						swal.showLoading();
					}
				});
			},
			data: ({
				id_wo, id_so, id_pelanggan, id_produk
			}),
			success: function(res){
				$("#riwayat-plan").html(res)
				// swal.close()
			}
		})
	}

	function ayoBerhitung()
	{
		let g_kualitas = $("#g_kualitas").val()
		let tl_al = $("#tl_al").val()
		let tl_al_i = $("#tl_al_i").val()
		let bmf = $("#bmf").val()
		let bmf_i = $("#bmf_i").val()
		let bl = $("#bl").val()
		let bl_i = $("#bl_i").val()
		let cmf = $("#cmf").val()
		let cmf_i = $("#cmf_i").val()
		let cl = $("#cl").val()
		let cl_i = $("#cl_i").val()
		let rupiah = new Intl.NumberFormat('id-ID', {styles: 'currency', currency: 'IDR'});

		let flute = $("#flute").val()
		let qty_so = $("#qty_so").val().split('.').join('');

		let kupingan = $("#kupingan").val()
		let p1 = $("#p1").val()
		let l1 = $("#l1").val()
		let p2 = $("#p2").val()
		let l2 = $("#l2").val()

		let panjang_s = $("#ii_panjang").val().split('.').join('');
		let panjangPlusWO = parseInt(p1) + parseInt(l1) + parseInt(p2) + parseInt(l2) + parseInt(kupingan) + 5;
		let kategori = $("#ehkategori").val()
		
		if(kategori == 'K_BOX'){
			if(panjang_s == panjangPlusWO){
				(panjang_s == 0 || panjang_s < 0 || panjang_s == '') ? $("#ii_panjang").val(0).attr('style', 'border-color:#d00') : $("#ii_panjang").val(rupiah.format(panjang_s)).attr('style', 'border-color:#ced4da');
				$("#kupingan").attr('style', 'border-color:#ced4da')
				$("#p1").attr('style', 'border-color:#ced4da')
				$("#l1").attr('style', 'border-color:#ced4da')
				$("#p2").attr('style', 'border-color:#ced4da')
				$("#l2").attr('style', 'border-color:#ced4da')
			}else{
				(panjang_s == 0 || panjang_s < 0 || panjang_s == '') ? $("#ii_panjang").val(0).attr('style', 'border-color:#d00') : $("#ii_panjang").val(rupiah.format(panjang_s)).attr('style', 'border-color:#d00');
				$("#kupingan").attr('style', 'border-color:#d00')
				$("#p1").attr('style', 'border-color:#d00')
				$("#l1").attr('style', 'border-color:#d00')
				$("#p2").attr('style', 'border-color:#d00')
				$("#l2").attr('style', 'border-color:#d00')
			}
		}else if(kategori == 'K_SHEET'){
			(panjang_s == 0 || panjang_s < 0 || panjang_s == '') ? $("#ii_panjang").val(0).attr('style', 'border-color:#d00') : $("#ii_panjang").val(rupiah.format(panjang_s)).attr('style', 'border-color:#ced4da');
			$("#kupingan").attr('style', 'border-color:#ced4da')
			$("#p1").attr('style', 'border-color:#ced4da')
			$("#l1").attr('style', 'border-color:#ced4da')
			$("#p2").attr('style', 'border-color:#ced4da')
			$("#l2").attr('style', 'border-color:#ced4da')
		}else{
			(panjang_s == 0 || panjang_s < 0 || panjang_s == '') ? $("#ii_panjang").val(0).attr('style', 'border-color:#d00') : $("#ii_panjang").val(rupiah.format(panjang_s)).attr('style', 'border-color:#d00');
			$("#kupingan").attr('style', 'border-color:#d00').prop('disabled', false)
			$("#p1").attr('style', 'border-color:#d00').prop('disabled', false)
			$("#l1").attr('style', 'border-color:#d00').prop('disabled', false)
			$("#p2").attr('style', 'border-color:#d00').prop('disabled', false)
			$("#l2").attr('style', 'border-color:#d00').prop('disabled', false)
		}
		// (panjang_s == 0 || panjang_s < 0) ? $("#ii_panjang").val(0).attr('style', 'border-color:#d00') : $("#ii_panjang").val(rupiah.format(panjang_s)).attr('style', 'border-color:#ced4da');
		
		let creasing_wo1 = $("#creasing_wo_1").val()
		let creasing_wo2 = $("#creasing_wo_2").val()
		let creasing_wo3 = $("#creasing_wo_3").val();
		let hitungScore = parseInt(creasing_wo1) + parseInt(creasing_wo2) + parseInt(creasing_wo3);
		(creasing_wo1 == 0 || creasing_wo1 < 0 || creasing_wo1 == '') ? $("#creasing_wo_1").val(0) : $("#creasing_wo_1").val();
		(creasing_wo2 == 0 || creasing_wo2 < 0 || creasing_wo2 == '') ? $("#creasing_wo_2").val(0) : $("#creasing_wo_2").val();
		(creasing_wo3 == 0 || creasing_wo3 < 0 || creasing_wo3 == '') ? $("#creasing_wo_3").val(0) : $("#creasing_wo_3").val();

		let lebar_s = $("#ii_lebar").val().split('.').join('');
		(lebar_s == 0 || lebar_s < 0) ? $("#ii_lebar").val(0).attr('style', 'border-color:#d00') : $("#ii_lebar").val(rupiah.format(lebar_s)).attr('style', 'border-color:#ced4da');

		if(kategori == 'K_BOX'){
			if(hitungScore == lebar_s){
				$("#creasing_wo_1").attr('style', 'border-color:#ced4da')
				$("#creasing_wo_2").attr('style', 'border-color:#ced4da')
				$("#creasing_wo_3").attr('style', 'border-color:#ced4da')
				$("#ii_lebar").attr('style', 'border-color:#ced4da')
			}else{
				$("#creasing_wo_1").attr('style', 'border-color:#d00')
				$("#creasing_wo_2").attr('style', 'border-color:#d00')
				$("#creasing_wo_3").attr('style', 'border-color:#d00')
				$("#ii_lebar").attr('style', 'border-color:#d00')
			}
		}else if(kategori == 'K_SHEET'){
			if(creasing_wo1 != 0 || creasing_wo2 != 0 || creasing_wo3 != 0){
				if(hitungScore == lebar_s){
					$("#creasing_wo_1").attr('style', 'border-color:#ced4da')
					$("#creasing_wo_2").attr('style', 'border-color:#ced4da')
					$("#creasing_wo_3").attr('style', 'border-color:#ced4da')
					$("#ii_lebar").attr('style', 'border-color:#ced4da')
				}else{
					$("#creasing_wo_1").attr('style', 'border-color:#d00')
					$("#creasing_wo_2").attr('style', 'border-color:#d00')
					$("#creasing_wo_3").attr('style', 'border-color:#d00')
					$("#ii_lebar").attr('style', 'border-color:#d00')
				}
			}else{
				$("#creasing_wo_1").attr('style', 'border-color:#ced4da')
				$("#creasing_wo_2").attr('style', 'border-color:#ced4da')
				$("#creasing_wo_3").attr('style', 'border-color:#ced4da')
				$("#ii_lebar").attr('style', 'border-color:#ced4da')
			}
		}else{
			$("#creasing_wo_1").attr('style', 'border-color:#d00')
			$("#creasing_wo_2").attr('style', 'border-color:#d00')
			$("#creasing_wo_3").attr('style', 'border-color:#d00')
			$("#ii_lebar").attr('style', 'border-color:#d00')
		}
		
		let ton = 0
		let material = ''
		let kualitas = ''
		let kualitas_isi = ''
		let editMaterial = $("#input_material_plan").val()
		let editKualitas = $("#input_kualitas_plan").val()
		let editKualitasIsi = $("#input_kualitas_plan_isi").val()

		if(g_kualitas == 'PO'){
			$("#tl_al").html(`<option value="">-</option><option value="M">M</option><option value="K">K</option><option value="MC">MC</option><option value="MN">MN</option>`).prop('disabled', true)
			$("#tl_al_i").val("").prop('disabled', true)
			$("#bmf").html(`<option value="">-</option><option value="M">M</option><option value="K">K</option><option value="MC">MC</option><option value="MN">MN</option>`).prop('disabled', true)
			$("#bmf_i").val("").prop('disabled', true)
			$("#bl").html(`<option value="">-</option><option value="M">M</option><option value="K">K</option><option value="MC">MC</option><option value="MN">MN</option>`).prop('disabled', true)
			$("#bl_i").val("").prop('disabled', true)
			$("#cmf").html(`<option value="">-</option><option value="M">M</option><option value="K">K</option><option value="MC">MC</option><option value="MN">MN</option>`).prop('disabled', true)
			$("#cmf_i").val("").prop('disabled', true)
			$("#cl").html(`<option value="">-</option><option value="M">M</option><option value="K">K</option><option value="MC">MC</option><option value="MN">MN</option>`).prop('disabled', true)
			$("#cl_i").val("").prop('disabled', true)
			$("#group_plh_kualitas").hide()

			editMaterial = $("#input_material_plan").val()
			editKualitas = $("#input_kualitas_plan").val()
			editKualitasIsi = $("#input_kualitas_plan_isi").val()
			$("#group_tmpl_kualitas").val(editKualitas)

			kualitas_isi = $("#h_ikpi").val();
			(kualitas_isi === undefined) ? kualitas_isi = '0/0/0/0/0' : kualitas_isi = kualitas_isi;
			let spltKualitas = kualitas_isi.split("/");
			if(flute == 'BF'){
				ton = parseFloat((parseInt(spltKualitas[0]) + (parseFloat(spltKualitas[1])*1.36) + parseInt(spltKualitas[2])) / 1000 * panjang_s / 1000 * lebar_s / 1000 * qty_so)
			}else if(flute == 'CF'){
				ton = parseFloat((parseInt(spltKualitas[0]) + (parseFloat(spltKualitas[1])*1.46) + parseInt(spltKualitas[2])) / 1000 * panjang_s / 1000 * lebar_s / 1000 * qty_so)
			}else if(flute == 'BCF'){
				ton = parseFloat((parseInt(spltKualitas[0]) + (parseFloat(spltKualitas[1])*1.36) + parseInt(spltKualitas[2]) + (parseFloat(spltKualitas[3])*1.46) + parseInt(spltKualitas[4])) / 1000 * panjang_s / 1000 * lebar_s / 1000 * qty_so)
			}else{
				ton = 0
			}
		}else if(g_kualitas == 'GANTI'){
			$("#tl_al").prop('disabled', false)
			$("#tl_al_i").prop('disabled', false)
			$("#cl").prop('disabled', false)
			$("#cl_i").prop('disabled', false)
			if(flute == 'BF'){
				$("#bmf").prop('disabled', false)
				$("#bmf_i").prop('disabled', false)
				$("#bl").prop('disabled', true)
				$("#bl_i").prop('disabled', true)
				$("#cmf").prop('disabled', true)
				$("#cmf_i").prop('disabled', true)
				if(tl_al == '' || tl_al_i == '' || bmf == '' || bmf_i == '' || cl == '' || cl_i == '' || tl_al == 0 || tl_al_i == 0 || bmf == 0 || bmf_i == 0 || cl == 0 || cl_i == 0){
					$("#group_tmpl_kualitas").val("")
					ton = 0
				}else{
					editMaterial = tl_al+'/'+bmf+'/'+cl
					editKualitas = tl_al+tl_al_i+'/'+bmf+bmf_i+'/'+cl+cl_i
					editKualitasIsi = tl_al_i+'/'+bmf_i+'/'+cl_i
					$("#group_tmpl_kualitas").val(editKualitas)
					ton = parseFloat((parseInt(tl_al_i) + (parseFloat(bmf_i)*1.36) + parseInt(cl_i)) / 1000 * panjang_s / 1000 * lebar_s / 1000 * qty_so)
				} 
			}else if(flute == 'CF'){
				$("#bmf").prop('disabled', true)
				$("#bmf_i").prop('disabled', true)
				$("#bl").prop('disabled', true)
				$("#bl_i").prop('disabled', true)
				$("#cmf").prop('disabled', false)
				$("#cmf_i").prop('disabled', false)
				if(tl_al == '' || tl_al_i == '' || cmf == '' || cmf_i == '' || cl == '' || cl_i == '' || tl_al == 0 || tl_al_i == 0 || cmf == 0 || cmf_i == 0 || cl == 0 || cl_i == 0){
					$("#group_tmpl_kualitas").val("")
					ton = 0
				}else{
					editMaterial = tl_al+'/'+cmf+'/'+cl
					editKualitas = tl_al+tl_al_i+'/'+cmf+cmf_i+'/'+cl+cl_i
					editKualitasIsi = tl_al_i+'/'+cmf_i+'/'+cl_i
					$("#group_tmpl_kualitas").val(editKualitas)
					ton = parseFloat((parseInt(tl_al_i) + (parseFloat(cmf_i)*1.46) + parseInt(cl_i)) / 1000 * panjang_s / 1000 * lebar_s / 1000 * qty_so);
				}
			}else if(flute == 'BCF'){
				$("#bmf").prop('disabled', false)
				$("#bmf_i").prop('disabled', false)
				$("#bl").prop('disabled', false)
				$("#bl_i").prop('disabled', false)
				$("#cmf").prop('disabled', false)
				$("#cmf_i").prop('disabled', false)
				if(tl_al == '' || tl_al_i == '' || bmf == '' || bmf_i == '' || bl == '' || bl_i == '' || cmf == '' || cmf_i == '' || cl == '' || cl_i == '' || tl_al == 0 || tl_al_i == 0 || bmf == 0 || bmf_i == 0 || bl == 0 || bl_i == 0 || cmf == 0 || cmf_i == 0 || cl == 0 || cl_i == 0){
					$("#group_tmpl_kualitas").val("")
					ton = 0
				}else{
					editMaterial = tl_al+'/'+bmf+'/'+bl+'/'+cmf+'/'+cl
					editKualitas = tl_al+tl_al_i+'/'+bmf+bmf_i+'/'+bl+bl_i+'/'+cmf+cmf_i+'/'+cl+cl_i
					editKualitasIsi = tl_al_i+'/'+bmf_i+'/'+bl_i+'/'+cmf_i+'/'+cl_i
					$("#group_tmpl_kualitas").val(editKualitas)
					ton = parseFloat((parseInt(tl_al_i) + (parseFloat(bmf_i)*1.36) + parseInt(bl_i) + (parseFloat(cmf_i)*1.46) + parseInt(cl_i)) / 1000 * panjang_s / 1000 * lebar_s / 1000 * qty_so)
				} 
			}else{
				$("#tl_al").prop('disabled', true)
				$("#tl_al_i").prop('disabled', true)
				$("#bmf").prop('disabled', true)
				$("#bmf_i").prop('disabled', true)
				$("#bl").prop('disabled', true)
				$("#bl_i").prop('disabled', true)
				$("#cmf").prop('disabled', true)
				$("#cmf_i").prop('disabled', true)
				$("#cl").prop('disabled', true)
				$("#cl_i").prop('disabled', true)
				$("#group_tmpl_kualitas").val("")
			}
			$("#group_plh_kualitas").show()
		}else{
			$("#g_kualitas").html(`<option value="PO">KUALITAS SESUAI PO</option><option value="GANTI">GANTI KUALITAS</option>`)
			$("#group_plh_kualitas").hide()
		}

		material = editMaterial
		kualitas = editKualitas
		kualitas_isi = editKualitasIsi
		$("#input_kualitas_plan").val(kualitas)
		$("#input_kualitas_plan_isi").val(kualitas_isi)
		$("#input_material_plan").val(material)

		let i_lebar_roll = $("#i_lebar_roll").val().split('.').join('');
		(i_lebar_roll == '' || i_lebar_roll == 0 || i_lebar_roll < 0) ? $("#i_lebar_roll").val(0).attr('style', 'border-color:#d00') : $("#i_lebar_roll").val(rupiah.format(i_lebar_roll)).attr('style', 'border-color:#ced4da');
		let out_plan = $("#out_plan").val().split('.').join('');
		(out_plan == '' || out_plan == 0 || out_plan < 0) ? $("#out_plan").val(0).attr('style', 'border-color:#d00') : $("#out_plan").val(rupiah.format(out_plan)).attr('style', 'border-color:#ced4da');

		let trim = $("#trim").val();
		let c_off = $("#c_off").val();
		let rm = $("#rm").val();
		
		(i_lebar_roll == '' || out_plan == '' || i_lebar_roll == 0 || out_plan == 0) ? trim = 0 : trim = Math.round(i_lebar_roll - (lebar_s * out_plan));
		(out_plan == '' || out_plan == 0) ? c_off = 0 : c_off = Math.round(qty_so / out_plan);
		(c_off == '' || c_off == 0) ? rm = 0 : rm = Math.round((c_off * panjang_s) / 1000);

		(trim < 0 || trim == 0) ? $("#trim").val(0).attr('style', 'border-color:#d00') : $("#trim").val(trim).attr('style', 'border-color:#ced4da');
		(c_off < 0 || isNaN(c_off) || c_off == 0) ? $("#c_off").val(0).attr('style', 'border-color:#d00') : $("#c_off").val(rupiah.format(c_off)).attr('style', 'border-color:#ced4da');
		(rm < 0 || isNaN(rm) || rm == 0) ? $("#rm").val(0).attr('style', 'border-color:#d00') : $("#rm").val(rupiah.format(rm)).attr('style', 'border-color:#ced4da');
		(ton < 0 || ton == 0) ? $("#ton").val(0).attr('style', 'border-color:#d00') : $("#ton").val(rupiah.format(Math.round(ton))).attr('style', 'border-color:#ced4da');
	}

	function plhDowntime()
	{
		$("#dt-pilih").html('')
		$("#dt-select").html('')
		$("#dt-load-data").html('')
		let id_plan = $("#ehid_plan").val()
		$.ajax({
			url: '<?php echo base_url('Plan/plhDowntime')?>',
			type: "POST",
			beforeSend: function() {
				swal({
					title: 'Loading',
					allowEscapeKey: false,
					allowOutsideClick: false,
					onOpen: () => {
						swal.showLoading();
					}
				});
			},
			data: ({
				id_plan, id_flexo: ''
			}),
			success: function(res){
				data = JSON.parse(res)
				let html = ''
				html += `<div class="card-body row" style="padding:20px 20px 5px;font-weight:bold">
					<div class="col-md-2">DOWNTIME</div>
					<div class="col-md-10">`;
						if(data.data == 0){
							html += `<select id="downtime_cor" class="form-control select2" onchange="downtime()">
								<option value="">PILIH</option>
								<option value="OP">OPERASIONAL</option>
								<option value="MT">TEKNIK</option>
								<option value="M">MATERIAL</option>
								<option value="N">MANAGEMENT</option>
							</select>`;
						}else{
							html += `<select class="form-control select2" disabled>
								<option value="">PILIH</option>
							</select>`;
						}
					html += `</div>
				</div>`;

				$("#dt-pilih").html(html)
				downtime()
				$('.select2').select2()
			}
		})
	}

	function downtime()
	{
		$("#dt-select").html('')
		$("#dt-load-data").html('')
		let downtime = $("#downtime_cor").val();
		(downtime == undefined) ? downtime = "" : downtime = downtime;
		let id_plan = $("#ehid_plan").val()

		$.ajax({
			url: '<?php echo base_url('Plan/downtime')?>',
			type: "POST",
			beforeSend: function() {
				swal({
					title: 'Loading',
					allowEscapeKey: false,
					allowOutsideClick: false,
					onOpen: () => {
						swal.showLoading();
					}
				});
			},
			data: ({
				downtime, id_plan, id_flexo: ''
			}),
			success: function(res){
				$("#dt-select").html(res)
				$('.select2').select2()
				loadDataDowntime()
			}
		})
	}

	function loadDataDowntime()
	{
		$("#dt-load-data").html('')
		let id_plan = $("#ehid_plan").val()
		$.ajax({
			url: '<?php echo base_url('Plan/loadDataDowntime')?>',
			type: "POST",
			beforeSend: function() {
				swal({
					title: 'Loading',
					allowEscapeKey: false,
					allowOutsideClick: false,
					onOpen: () => {
						swal.showLoading();
					}
				});
			},
			data: ({
				id_plan, id_flexo: ''
			}),
			success: function(res){
				$("#dt-load-data").html(res)
				loadDataAllPlan()
			}
		})
	}

	function onKeyDTDurasi(opsi)
	{
		let durasi = $("#dt-durasi").val().split('.').join('');
		(durasi < 0 || durasi == "" || durasi == 0) ? $("#dt-durasi").val(0) : $("#dt-durasi").val(durasi);
	}

	function simpanDowntime()
	{
		let id_plan = $("#ehid_plan").val()
		let id_dt = $("#dt-plh-downtime").val()
		let durasi = $("#dt-durasi").val()
		let ket = $("#dt-keterangan").val()
		
		$.ajax({
			url: '<?php echo base_url('Plan/simpanDowntime')?>',
			type: "POST",
			beforeSend: function() {
				swal({
					title: 'Loading',
					allowEscapeKey: false,
					allowOutsideClick: false,
					onOpen: () => {
						swal.showLoading();
					}
				});
			},
			data: ({
				id_plan, id_flexo: '', id_dt, durasi, ket
			}),
			success: function(res){
				data = JSON.parse(res)
				if(data.result){
					downtime()
				}else{
					swal(data.msg, "", "error")
				}
			}
		})
	}

	function hapusDowntimePlan(id_dt)
	{
		let id_plan = $("#ehid_plan").val()
		$.ajax({
			url: '<?php echo base_url('Plan/hapusDowntimePlan')?>',
			type: "POST",
			beforeSend: function() {
				swal({
					title: 'Loading',
					allowEscapeKey: false,
					allowOutsideClick: false,
					onOpen: () => {
						swal.showLoading();
					}
				});
			},
			data: ({
				id_dt, id_plan, id_flexo: ''
			}),
			success: function(res){
				data = JSON.parse(res)
				downtime()
			}
		})
	}

	function changeEditDt(i)
	{
		let ket = $("#dt-ket-plan-"+i).val()
		let durasi = $("#dt-durasi-plan-"+i).val()
		$.ajax({
			url: '<?php echo base_url('Plan/changeEditDt')?>',
			type: "POST",
			beforeSend: function() {
				swal({
					title: 'Loading',
					allowEscapeKey: false,
					allowOutsideClick: false,
					onOpen: () => {
						swal.showLoading();
					}
				});
			},
			data: ({
				id_plan: i, id_flexo: '', ket, durasi
			}),
			success: function(res){
				data = JSON.parse(res)
				if(data.data){
					toastr.success(`<b>${data.msg}</b>`)
					downtime()
				}else{
					swal(data.msg, "", "error")
				}
			}
		})
	}


</script>
