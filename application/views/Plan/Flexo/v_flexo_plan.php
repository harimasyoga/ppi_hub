<div class="content-wrapper">
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<!-- <h1><b>Data Plan</b></h1> -->
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
					<!-- <li class="breadcrumb-item active" ><a href="#">Flexo</a></li> -->
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

	<section class="content">
		<div class="container-fluid">
			<div class="row">
				<?php if($this->session->userdata('level') == 'Admin' || $this->session->userdata('level') == 'PPIC' || $this->session->userdata('level') == 'User') { ?>
				<div class="col-md-12">
					<div class="card">
						<div class="card-body p-0">
							<div id="accordion-customer">
								<div class="card m-0" style="border-radius:0">
									<div class="card-header bg-gradient-secondary" style="padding:0;border-radius:0">
										<a class="d-block w-100 link-h-wo" style="font-weight:bold;padding:6px" data-toggle="collapse" href="#collapseCustomer" onclick="loadDataAllPlanCor()">
											LIST SEMUA PLAN COR
										</a>
									</div>
									<div id="collapseCustomer" class="collapse" data-parent="#accordion-customer">
										<div id="tampil-all-plan-header"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="card card-info card-outline">
						<div class="card-header">
							<h3 class="card-title" style="font-weight:bold;font-style:italic">PLAN CORR</h3>
						</div>
						<div class="card-body row" style="padding-bottom:5px;font-weight:bold">
							<div class="col-md-12" style="padding:0">
								<a href="<?php echo base_url('Plan/Flexo')?>" class="btn btn-sm btn-info"><i class="fa fa-arrow-left"></i> <b>Kembali</b></a>
								<a href="<?php echo base_url('Plan/Flexo/Add')?>" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> <b>Add</b></a>
							</div>
						</div>
						<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
							<div class="col-md-1"></div>
							<div class="col-md-11" style="font-size:small;font-style:italic;color:#f00">
								* [ TYPE ] NO. WO | TGL PLAN | ITEM | CUSTOMER
							</div>
						</div>
						<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
							<div class="col-md-1">PLAN COR</div>
							<div class="col-md-11">
								<select id="plan_cor" class="form-control select2" onchange="plhPlanCor('')"></select>
							</div>
						</div>

						<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
							<div class="col-md-1">NO. WO</div>
							<div class="col-md-11">
								<input type="text" id="no_wo" class="form-control" placeholder="NO. WO" disabled>
							</div>
						</div>
						<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
							<div class="col-md-1">NO. PO</div>
							<div class="col-md-11">
								<input type="text" id="no_po" class="form-control" placeholder="NO .PO" disabled>
							</div>
						</div>
						<div class="card-body row" style="padding:0 20px 20px;font-weight:bold">
							<div class="col-md-1 pr-0">CUSTOMER</div>
							<div class="col-md-11">
								<input type="text" id="customer" class="form-control" placeholder="CUSTOMER" disabled>
							</div>
						</div>
					</div>
				</div>
				<?php } ?>

				<div class="col-md-12">
					<div class="card">
						<div class="card-body p-0">
							<div id="accordion-flexo">
								<div class="card m-0" style="border-radius:0">
									<div class="card-header bg-gradient-secondary" style="padding:0;border-radius:0">
										<a class="d-block w-100 link-h-wo" style="font-weight:bold;padding:6px" data-toggle="collapse" href="#collapseflexo" onclick="loadDataAllPlanFlexo()">
											LIST SEMUA PLAN FLEXO
										</a>
									</div>
									<div id="collapseflexo" class="collapse" data-parent="#accordion-flexo">
										<div id="tampil-all-plan-flexo-header"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="col-md-12">
					<div id="list-plan-flexo"></div>
					<div id="riwayat-flexo"></div>
				</div>
				
				<div class="col-md-12">
					<div id="list-input-flexo"></div>
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
								<h3 class="card-title" style="font-weight:bold;font-style:italic">HASIL PRODUKSI FLEXO</h3>
							</div>
							<div class="card-body row" style="padding-bottom:5px;font-weight:bold">
								<div class="col-md-2">HASIL COR.</div>
								<div class="col-md-10">
									<input type="number" id="good_cor" style="font-weight:bold" class="form-control" disabled>
								</div>
							</div>
							<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
								<div class="col-md-2">GOOD</div>
								<div class="col-md-10">
									<input type="number" id="good_flexo" class="form-control" onkeyup="hitungProduksiFlexo()">
								</div>
							</div>
							<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
								<div class="col-md-2">R. FLEXO</div>
								<div class="col-md-10">
									<input type="number" id="bad_flexo" class="form-control" onkeyup="hitungProduksiFlexo()">
								</div>
							</div>
							<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
								<div class="col-md-2">R. BAHAN</div>
								<div class="col-md-10">
									<input type="number" id="bad_b_flexo" class="form-control" onkeyup="hitungProduksiFlexo()">
								</div>
							</div>
							<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
								<div class="col-md-2">TOTAL</div>
								<div class="col-md-10">
									<input type="number" id="total_flexo" class="form-control" onkeyup="hitungProduksiFlexo()" disabled>
								</div>
							</div>
							<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
								<div class="col-md-2">KET</div>
								<div class="col-md-10">
									<textarea id="ket_flexo" class="form-control" style="resize:none" rows="2"></textarea>
								</div>
							</div>
							<div class="card-body row" style="padding:20px 20px 5px;font-weight:bold">
								<div class="col-md-2">TGL PROD.</div>
								<div class="col-md-10">
									<input type="date" id="tgl_flexo" class="form-control">
								</div>
							</div>
							<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
								<div class="col-md-2">START</div>
								<div class="col-md-10">
									<input type="time" id="start_flexo" class="form-control">
								</div>
							</div>
							<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
								<div class="col-md-2">END</div>
								<div class="col-md-10">
									<input type="time" id="end_flexo" class="form-control">
								</div>
							</div>

							<?php if($this->session->userdata('level') == 'Admin' || $this->session->userdata('level') == 'PPIC' || $this->session->userdata('level') == 'Flexo' || $this->session->userdata('level') == 'User') { ?>
								<div id="btn-aksi-produksi"></div>
							<?php } ?>
						</div>
					</div>

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
						<div class="card-body row" style="padding:0 20px 20px;font-weight:bold">
							<div class="col-md-2">ITEM</div>
							<div class="col-md-10">
								<input type="text" id="item" class="form-control" autocomplete="off" placeholder="NAMA ITEM" disabled>
							</div>
						</div>
						<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
							<div class="col-md-2">UK. BOX</div>
							<div class="col-md-10">
								<input type="text" id="uk_box" class="form-control" autocomplete="off" placeholder="UKURAN BOX" disabled>
							</div>
						</div>
						<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
							<div class="col-md-2">UK. SHEET</div>
							<div class="col-md-10">
								<input type="text" id="uk_sheet" class="form-control" autocomplete="off" placeholder="UKURAN SHEET" disabled>
							</div>
						</div>
						<div class="card-body row" style="padding:0 20px 2px;font-weight:bold">
							<div class="col-md-2">CREASING</div>
							<div class="col-md-3" style="margin-bottom:3px">
								<input type="text" id="creasing_1" class="form-control" autocomplete="off" placeholder="0" disabled>
							</div>
							<div class="col-md-3" style="margin-bottom:3px">
								<input type="text" id="creasing_2" class="form-control" autocomplete="off" placeholder="0" disabled>
							</div>
							<div class="col-md-3" style="margin-bottom:3px">
								<input type="text" id="creasing_3" class="form-control" autocomplete="off" placeholder="0" disabled>
							</div>
							<div class="col-md-1" style="padding:0"></div>
						</div>
						<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
							<div class="col-md-2">KUALITAS</div>
							<div class="col-md-10">
								<input type="text" id="kualitas" class="form-control" autocomplete="off" placeholder="KUALITAS" disabled>
							</div>
						</div>
						<div class="card-body row" style="padding:0 20px 20px;font-weight:bold">
							<div class="col-md-2">FLUTE</div>
							<div class="col-md-10">
								<input type="text" id="flute" class="form-control" autocomplete="off" placeholder="FLUTE" disabled>
							</div>
						</div>
						<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
							<div class="col-md-2">TIPE BOX</div>
							<div class="col-md-10">
								<input type="text" id="tipe_box" class="form-control" autocomplete="off" placeholder="TIPE BOX" disabled>
							</div>
						</div>
						<div class="card-body row" style="padding:0 20px 20px;font-weight:bold">
							<div class="col-md-2" style="padding-right:0">SAMBUNGAN</div>
							<div class="col-md-10">
								<input type="text" id="sambungan" class="form-control" autocomplete="off" placeholder="SAMBUNGAN" disabled>
							</div>
						</div>
						<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
							<div class="col-md-2" style="padding-right:0">BB</div>
							<div class="col-md-10">
								<input type="text" id="bb_box" class="form-control" autocomplete="off" placeholder="BERAT BOX" disabled>
							</div>
						</div>
						<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
							<div class="col-md-2" style="padding-right:0">LB</div>
							<div class="col-md-10">
								<input type="text" id="lb_box" class="form-control" autocomplete="off" placeholder="LUAS BOX" disabled>
							</div>
						</div>
					</div>
				</div>

				<div class="col-md-5">
					<div class="card card-info card-outline">
						<div class="card-header">
							<h3 class="card-title" style="font-weight:bold;font-style:italic">FLEXO</h3>
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

						<div class="card-body row" style="padding:20px 20px 5px;font-weight:bold">
							<div class="col-md-2 p-0">PANJANG</div>
							<div class="col-md-4">
								<input type="text" id="panjang_plan" class="form-control" style="font-weight:bold;color:#f00" placeholder="PANJANG" disabled>
							</div>
							<div class="col-md-2 pr-0">LEBAR</div>
							<div class="col-md-4">
								<input type="text" id="lebar_plan" class="form-control" style="font-weight:bold;color:#f00" placeholder="LEBAR" disabled>
							</div>
						</div>
						<div class="card-body row" style="padding:0 20px 20px;font-weight:bold">
							<div class="col-md-2">ORDER</div>
							<div class="col-md-4">
								<input type="text" id="order_so" class="form-control" style="font-weight:bold" placeholder="ORDER" disabled>
							</div>
							<div class="col-md-2">KIRIM</div>
							<div class="col-md-4">
								<input type="text" id="kirim" class="form-control pr-0" placeholder="KIRIM" disabled>
							</div>
						</div>
						<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
							<div class="col-md-12">KELUAR COR</div>
						</div>
						<div class="card-body row" style="padding:0 20px 20px;font-weight:bold">
							<div class="col-md-2">TGL</div>
							<div class="col-md-4">
								<input type="text" id="tgl_cor" class="form-control pr-0" placeholder="TANGGAL" disabled>
							</div>
							<div class="col-md-2">QTY</div>
							<div class="col-md-4">
								<input type="text" id="qty_cor" class="form-control" placeholder="QTY COR" disabled>
							</div>
						</div>

						<div class="card-body row" style="padding:0 20px 20px;font-weight:bold">
							<div class="col-md-2">NEXT</div>
							<div class="col-md-10">
								<select id="next_flexo" class="form-control select2">
									<option value="">PILIH</option>
								</select>
							</div>
						</div>
						<div id="btn-add-plan-flexo"></div>
					</div>
				</div>

				<input type="hidden" id="ehid_flexo" value="">

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
				<div id="modal-body-isi"></div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	const urlAuth = '<?= $this->session->userdata('level')?>'
	const urlTglF = '<?= $tgl_flexo?>'
	const urlShiftF = '<?= $shift?>'
	const urlMesinF = '<?= $mesin?>'
	let inputDtProd = ''

	$(document).ready(function ()
	{
		$("#tgl").val(urlTglF).prop('disabled', true)
		$("#shift").html(`<option value="${urlShiftF}">${urlShiftF}</option>`).prop('disabled', true)
		$("#mesin").html(`<option value="${urlMesinF}">${urlMesinF}</option>`).prop('disabled', true)
		loadDataPlanFlexo(urlTglF, urlShiftF, urlMesinF)

		$("#card-produksi").hide()
		$("#list-input-flexo").load("<?php echo base_url('Plan/destroyPlanFlexo') ?>")
		$("#plan_cor").html('<option value="">PILIH</option>').prop("disabled", true)
		$('.select2').select2({
			dropdownAutoWidth: true
		})
	})

	function loadDataPlanFlexo(uTgl, uShift, uMesin)
	{
		$("#tgl").val(urlTglF).prop('disabled', true)
		$("#shift").html(`<option value="${urlShiftF}">${urlShiftF}</option>`).prop('disabled', true)
		$("#mesin").html(`<option value="${urlMesinF}">${urlMesinF}</option>`).prop('disabled', true)
		$.ajax({
			url: '<?php echo base_url('Plan/loadDataPlanFlexo')?>',
			type: "POST",
			data: ({
				uTgl, uShift, uMesin
			}),
			success: function(res){
				data = JSON.parse(res)
				if(data.length == 0){
					window.location.href = '<?php echo base_url('Plan/Flexo')?>'
				}else{
					loadPlanCor('not')
					plhPlanCor('')
				}
			}
		})
	}

	function kosong()
	{
		$("input[type=text]").val("")
		$("#kirim").val("")
		$("#tgl_cor").val("")

		$("#tgl").val(urlTglF).prop('disabled', true)
		$("#shift").html(`<option value="${urlShiftF}">${urlShiftF}</option>`).prop('disabled', true)
		$("#mesin").html(`<option value="${urlMesinF}">${urlMesinF}</option>`).prop('disabled', true)
		$("#btn-ganti-tgl").html("")

		$("#next_flexo").html('<option value="">PILIH</option>')
	}

	function loadDataAllPlanCor()
	{
		$.ajax({
			url: '<?php echo base_url('Plan/loadDataAllPlanCor')?>',
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
				$("#tampil-all-plan-header").html(res)
				swal.close()
			}
		})
	}

	function loadDataAllPlanFlexo()
	{
		$.ajax({
			url: '<?php echo base_url('Plan/loadDataAllPlanFlexo')?>',
			type: "POST",
			data: ({
				urlTglF, urlShiftF, urlMesinF
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
				$("#tampil-all-plan-flexo-header").html(res)
				swal.close()
			}
		})
	}

	function onClickHeaderPlanCor(id_pelanggan)
	{
		$.ajax({
			url: '<?php echo base_url('Plan/onClickHeaderPlanCor')?>',
			type: "POST",
			data: ({
				id_pelanggan
			}),
			success: function(res){
				$("#tampil-all-flexo-isi-"+id_pelanggan).html(res)
			}
		})
	}

	function onclickHeaderIsiPlanCor(id_plan, id_pelanggan)
	{
		$.ajax({
			url: '<?php echo base_url('Plan/onclickHeaderIsiPlanCor')?>',
			type: "POST",
			data: ({
				id_plan, id_pelanggan
			}),
			success: function(res){
				$("#tampil-all-pplan-isi-"+id_plan+"-"+id_pelanggan).html(res)
			}
		})
	}

	function loadPlanCor(opsi = '')
	{
		let mesin = $("#mesin").val()
		$.ajax({
			url: '<?php echo base_url('Plan/loadPlanCor')?>',
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
			data: ({ mesin, opsi: '', urlTglF, urlShiftF, urlMesinF }),
			success: function(res){
				data = JSON.parse(res)
				let htmlPlanCor = ''
				let kategori = ''
					htmlPlanCor += `<option value="">PILIH</option>`
				data.plan_cor.forEach(laodPlanCor);
				function laodPlanCor(r, index) {
					(r.kategori == 'K_BOX') ? kategori = '[ BOX ]' : kategori = '[ SHEET ]';
					htmlPlanCor += `<option value="${r.id_plan}"
						op-no-wo="${r.no_wo}"
						op-no-po="${r.kode_po}"
						op-customer="${r.nm_pelanggan}"
						op-kode-mc="${r.kode_mc}"
						op-item="${r.nm_produk}"
						op-uk-box="${r.ukuran}"
						op-uk-sheet="${r.ukuran_sheet}"
						op-creasing-1="${r.creasing}"
						op-creasing-2="${r.creasing2}"
						op-creasing-3="${r.creasing3}"
						op-kualitas="${r.kualitas}"
						op-flute="${r.flute}"
						op-tipe-box="${r.tipe_box}"
						op-sambungan="${r.sambungan}"
						op-bb-box="${r.berat_bersih}"
						op-lb-box="${r.luas_bersih}"
						op-panjang-plan="${r.panjang_plan}"
						op-lebar-plan="${r.lebar_plan}"
						op-order-so="${r.qty_so}"
						op-kirim="${r.tgl_kirim_plan}"
						op-tgl-cor="${r.tgl_prod_p}"
						op-qty-cor="${r.good_cor_p}"
					>
						${kategori} ${r.no_wo} | ${r.tgl_plan} | ${r.nm_produk} | ${r.nm_pelanggan}
					</option>`
				}
				$("#plan_cor").html(htmlPlanCor).prop('disabled', false)
				if(opsi != 'not'){
					swal.close()
				}
			}
		})
	}

	function plhPlanCor(opsi = '')
	{
		$("#tgl").val(urlTglF).prop('disabled', true)
		$("#shift").html(`<option value="${urlShiftF}">${urlShiftF}</option>`).prop('disabled', true)
		$("#mesin").html(`<option value="${urlMesinF}">${urlMesinF}</option>`).prop('disabled', true)
		let opNoWo = ''; let opNoPo = ''; let opCustomer = ''; let opKodeMc = ''; let opItem = ''; let opUkBox = ''; let opUkSheet = ''; let opCreasing1 = ''; let opCreasing2 = ''; let opCreasing3 = ''; let opKualitas = ''; let opFlute = ''; let opTipeBox = ''; let opSambungan = ''; let opBbBox = ''; let opLbBox = ''; let opPanjangPlan = ''; let opLebarPlan = ''; let opOrderSo = ''; let opKirim = ''; let opTglCor = ''; let opQtyCor = ''; let next_flexo = ''; let optSambungan = '';

		$.ajax({
			url: '<?php echo base_url('Plan/loadPlanCor')?>',
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
			data: ({ mesin: '', opsi, urlTglF, urlShiftF, urlMesinF }),
			success: function(res){
				data = JSON.parse(res)
				if(data.opsi != ''){
					opNoWo = data.plan_cor.no_wo
					opNoPo = data.plan_cor.kode_po
					opCustomer = data.plan_cor.nm_pelanggan
					opKodeMc = data.plan_cor.kode_mc
					opItem = data.plan_cor.nm_produk
					opUkBox = data.plan_cor.ukuran
					opUkSheet = data.plan_cor.ukuran_sheet
					opCreasing1 = data.plan_cor.creasing
					opCreasing2 = data.plan_cor.creasing2
					opCreasing3 = data.plan_cor.creasing3
					opKualitas = data.plan_cor.kualitas
					opFlute = data.plan_cor.flute
					opTipeBox = data.plan_cor.tipe_box
					opSambungan = data.plan_cor.sambungan
					opBbBox = data.plan_cor.berat_bersih
					opLbBox = data.plan_cor.luas_bersih
					opPanjangPlan = data.plan_cor.panjang_plan
					opLebarPlan = data.plan_cor.lebar_plan
					opOrderSo = data.plan_cor.qty_so
					opKirim = data.plan_cor.tgl_kirim_plan
					opTglCor = data.plan_cor.tgl_prod_p
					opQtyCor = data.plan_cor.good_cor_p
					next_flexo = data.plan_cor.next_flexo

					$("#ehid_flexo").val(data.plan_cor.id_flexo)

					if(data.plan_cor.total_prod_flexo != 0){
						inputDtProd = 'inputDowntimeProduksi'
						$("#card-produksi").show()
					}else if(data.urutDtProd == null){
						inputDtProd = ''
						$("#card-produksi").hide()
						$("#btn-aksi-produksi").html(``)
					}else if(data.plan_cor.id_flexo == data.urutDtProd.id_flexo){
						if(data.plan_cor.total_cor_p != 0 && data.plan_cor.status_plan == 'Close'){
							inputDtProd = 'inputDowntimeProduksi'
							$("#card-produksi").show()
						}else{
							inputDtProd = ''
							$("#card-produksi").hide()
							$("#btn-aksi-produksi").html(``)
						}
					}else{
						inputDtProd = ''
						$("#card-produksi").hide()
						$("#btn-aksi-produksi").html(``)
					}

					loadPlanCor('not')
					riwayatFlexo(data.getNoFlexo.id_plan_cor)

					let tms = '';
					(data.plan_cor.total_prod_flexo == 0 && data.plan_cor.status_flexo == 'Open') ? tms = false : tms = true;
					$("#tgl").val(urlTglF).prop("disabled", tms)
					let optShift = `<option value="${urlShiftF}">${urlShiftF}</option><option value="1">1</option><option value="2">2</option><option value="3">3</option>`
					let optMesin = `<option value="${urlMesinF}">${urlMesinF}</option><option value="FLEXO1">FLEXO 1</option><option value="FLEXO2">FLEXO 2</option><option value="FLEXO3">FLEXO 3</option><option value="FLEXO4">FLEXO 4</option>`;
					$("#shift").html(optShift).prop("disabled", tms)
					$("#mesin").html(optMesin).prop("disabled", tms)

					let htmlBtnGantiTgl = '';
					(data.plan_cor.total_prod_flexo == 0 && data.plan_cor.status_flexo == 'Open' && (urlAuth == 'Admin' || urlAuth == 'PPIC' || urlAuth == 'User')) ?
						htmlBtnGantiTgl = `<div class="card-body row" style="padding:0 20px 5px">
							<div class="col-md-2"></div>
							<div class="col-md-10">
								<button class="btn btn-sm btn-warning" style="font-weight:bold" onclick="btnGantiTglFlexo(${data.plan_cor.id_flexo})">GANTI</button>
							</div>
						</div>` : htmlBtnGantiTgl = '';
					$("#btn-ganti-tgl").html(htmlBtnGantiTgl)

					$("#downtime_cor").val("")
					$("#good_cor").val(new Intl.NumberFormat('id-ID', {styles: 'currency', currency: 'IDR'}).format(data.plan_cor.good_cor_p))
					$("#good_flexo").val(data.plan_cor.good_flexo_p)
					$("#bad_flexo").val(data.plan_cor.bad_flexo_p)
					$("#bad_b_flexo").val(data.plan_cor.bad_bahan_f_p)
					$("#total_flexo").val(data.plan_cor.total_prod_flexo)
					$("#ket_flexo").val(data.plan_cor.ket_flexo_p)
					$("#tgl_flexo").val(data.plan_cor.tgl_prod_f)
					$("#start_flexo").val(data.plan_cor.start_time_f)
					$("#end_flexo").val(data.plan_cor.end_time_f)

					let txtPlanFlexo = ''
					let onclickSelesaiFlexo = ''
					if(data.plan_cor.total_prod_flexo == 0 && data.plan_cor.status_flexo == 'Open'){
						txtPlanFlexo = 'SIMPAN'
						onclickSelesaiFlexo = 'disabled'
					}else if(data.plan_cor.total_prod_flexo != 0 && data.plan_cor.status_flexo == 'Open'){
						txtPlanFlexo = 'UPDATE'
						onclickSelesaiFlexo = `onclick="addRencanaFlexo(${data.plan_cor.id_flexo})"`
					}else{
						txtPlanFlexo = 'UPDATE'
						onclickSelesaiFlexo = 'disabled'
					}

					let onClickDonePlanCor = ''
					if(data.plan_cor.total_prod_flexo != 0 && data.plan_cor.status_flexo == 'Close' && data.plan_cor.status_flexo_plan == 'Open'){
						onClickDonePlanCor = `onclick="clickDonePlanCorFlexo(${data.plan_cor.id_plan_cor})"`
					}else{
						onClickDonePlanCor = 'disabled'
					}

					if(urlAuth == 'Admin' || urlAuth == 'PPIC' || urlAuth == 'Flexo' || urlAuth == 'User'){
						if((data.plan_cor.total_prod_flexo == 0 || data.plan_cor.total_prod_flexo != 0) && data.plan_cor.status_flexo == 'Open'){
							$("#btn-aksi-produksi").html(`<div class="card-body row" style="padding:20px 20px 0;font-weight:bold">
								<div class="col-md-12">
									<button type="button" class="btn btn-success btn-block" onclick="produksiPlanFlexo(${data.plan_cor.id_flexo})"><i class="fa fa-save"></i> <b>${txtPlanFlexo}</b></button>
								</div>
							</div>`)
						}else{
							$("#btn-aksi-produksi").html('')
						}

						if(urlAuth == 'Admin' || urlAuth == 'PPIC' || urlAuth == 'User'){
							$("#btn-add-plan-flexo").html(`<div class="card-body row" style="padding:0 20px 17px;font-weight:bold">
								<div class="col-md-6">
									<button type="button" class="btn btn-primary btn-block" style="margin-bottom:3px" ${onclickSelesaiFlexo}><i class="fa fa-check"></i> <b>SELESAI FLEXO</b></button>
								</div>
								<div class="col-md-6">
									<button type="button" class="btn btn-dark btn-block" style="margin-bottom:3px" ${onClickDonePlanCor}><i class="fa fa-check"></i> <b>SELESAI PLAN COR</b></button>
								</div>
							</div>`)
						}else{
							$("#btn-add-plan-flexo").html(``)
						}
					}else{
						$("#btn-aksi-produksi").html(``)
						$("#btn-add-plan-flexo").html(``)
					}

					if(next_flexo == 'GLUE'){
						optSambungan = `<option value="GLUE">GLUE</option>`
					}else if(next_flexo == 'STITCHING'){
						optSambungan = `<option value="STITCHING">STITCHING</option>`
					}else if(next_flexo == 'GGLUESTITCHING'){
						optSambungan = `<option value="GLUESTITCHING">GLUE STITCHING</option>`
					}else if(next_flexo == 'DDOUBLESTITCHING'){
						optSambungan = `<option value="DOUBLESTITCHING">DOUBLE STITCHING</option>`
					}else if(next_flexo == 'DIECUT'){
						optSambungan = `<option value="DIECUT">DIECUT</option>`
					}else if(next_flexo == 'GUDANG'){
						optSambungan = `<option value="GUDANG">GUDANG</option>`
					}else{
						optSambungan = `<option value="">PILIH</option>`
					}
					$("#next_flexo").html(optSambungan).prop('disabled', true)
				}else{
					opNoWo = $('#plan_cor option:selected').attr('op-no-wo')
					opNoPo = $('#plan_cor option:selected').attr('op-no-po')
					opCustomer = $('#plan_cor option:selected').attr('op-customer')
					opKodeMc = $('#plan_cor option:selected').attr('op-kode-mc')
					opItem = $('#plan_cor option:selected').attr('op-item')
					opUkBox = $('#plan_cor option:selected').attr('op-uk-box')
					opUkSheet = $('#plan_cor option:selected').attr('op-uk-sheet')
					opCreasing1 = $('#plan_cor option:selected').attr('op-creasing-1')
					opCreasing2 = $('#plan_cor option:selected').attr('op-creasing-2')
					opCreasing3 = $('#plan_cor option:selected').attr('op-creasing-3')
					opKualitas = $('#plan_cor option:selected').attr('op-kualitas')
					opFlute = $('#plan_cor option:selected').attr('op-flute')
					opTipeBox = $('#plan_cor option:selected').attr('op-tipe-box')
					opSambungan = $('#plan_cor option:selected').attr('op-sambungan')
					opBbBox = $('#plan_cor option:selected').attr('op-bb-box')
					opLbBox = $('#plan_cor option:selected').attr('op-lb-box')
					opPanjangPlan = $('#plan_cor option:selected').attr('op-panjang-plan')
					opLebarPlan = $('#plan_cor option:selected').attr('op-lebar-plan')
					opOrderSo = $('#plan_cor option:selected').attr('op-order-so')
					opKirim = $('#plan_cor option:selected').attr('op-kirim')
					opTglCor = $('#plan_cor option:selected').attr('op-tgl-cor')
					opQtyCor = $('#plan_cor option:selected').attr('op-qty-cor')
					next_flexo = ''

					$("#ehid_flexo").val("")

					$("#btn-ganti-tgl").html("")
					$("#card-produksi").hide()
					$("#good_cor").val("")
					$("#good_flexo").val("")
					$("#bad_flexo").val("")
					$("#bad_b_flexo").val("")
					$("#total_flexo").val("")
					$("#ket_flexo").val("")
					$("#tgl_flexo").val("")
					$("#start_flexo").val("")
					$("#end_flexo").val("")

					if(urlAuth == 'Admin' || urlAuth == 'PPIC' || urlAuth == 'User'){
						$("#btn-aksi-produksi").html('')
						$("#btn-add-plan-flexo").html(`<div class="card-body row" style="padding:0 20px 20px;font-weight:bold">
							<div class="col-md-12">
								<button type="button" class="btn btn-success btn-block" onclick="addRencanaFlexo('add')"><i class="fa fa-plus"></i> <b>ADD FLEXO</b></button>
							</div>
						</div>`)
					}else{
						$("#btn-aksi-produksi").html('')
						$("#btn-add-plan-flexo").html('')
					}

					if(opSambungan == 'G'){
						optSambungan = `<option value="GLUE">GLUE</option><option value="GUDANG">GUDANG</option>`
					}else if(opSambungan == 'S'){
						optSambungan = `<option value="STITCHING">STITCHING</option>`
					}else if(opSambungan == 'GS'){
						optSambungan = `<option value="GLUESTITCHING">GLUE STITCHING</option>`
					}else if(opSambungan == 'DS'){
						optSambungan = `<option value="DOUBLESTITCHING">DOUBLE STITCHING</option>`
					}else if(opSambungan == 'D'){
						optSambungan = `<option value="DIECUT">DIECUT</option>`
					}else{
						optSambungan = `<option value="">PILIH</option>`
					}
					$("#next_flexo").html(optSambungan).prop('disabled', false)
					
					inputDtProd = ''
					riwayatFlexo(0)
				}
			
				let rupiah = new Intl.NumberFormat('id-ID', {styles: 'currency', currency: 'IDR'});

				(opTglCor == 'null') ? opTglCor = '' : opTglCor = opTglCor;
				(opPanjangPlan == undefined) ? opPanjangPlan = '' : opPanjangPlan = rupiah.format(opPanjangPlan);
				(opLebarPlan == undefined) ? opLebarPlan = '' : opLebarPlan = rupiah.format(opLebarPlan);
				(opOrderSo == undefined) ? opOrderSo = '' : opOrderSo = rupiah.format(opOrderSo);
				(opQtyCor == undefined) ? opQtyCor = '' : opQtyCor = rupiah.format(opQtyCor);

				$("#no_wo").val(opNoWo)
				$("#no_po").val(opNoPo)
				$("#customer").val(opCustomer)
				$("#kode_mc").val(opKodeMc)
				$("#item").val(opItem)
				$("#uk_box").val(opUkBox)
				$("#uk_sheet").val(opUkSheet)
				$("#creasing_1").val(opCreasing1)
				$("#creasing_2").val(opCreasing2)
				$("#creasing_3").val(opCreasing3)
				$("#kualitas").val(opKualitas)
				$("#flute").val(opFlute)
				$("#tipe_box").val(opTipeBox)
				$("#sambungan").val(opSambungan)
				$("#bb_box").val(opBbBox)
				$("#lb_box").val(opLbBox)
				$("#panjang_plan").val(opPanjangPlan)
				$("#lebar_plan").val(opLebarPlan)
				$("#order_so").val(opOrderSo)
				$("#kirim").val(opKirim)
				$("#tgl_cor").val(opTglCor)
				$("#qty_cor").val(opQtyCor)

				loadListPlanFlexo('','','','')
			}
		})
	}

	function addRencanaFlexo(opsi)
	{
		let tgl = $("#tgl").val()
		let shift = $("#shift").val()
		let mesin = $("#mesin").val()
		let plan_cor = $('#plan_cor').val()
		let no_wo = $("#no_wo").val()
		let no_po = $("#no_po").val()
		let customer = $("#customer").val()
		let kode_mc = $("#kode_mc").val()
		let item = $("#item").val()
		let uk_box = $("#uk_box").val()
		let uk_sheet = $("#uk_sheet").val()
		let creasing_1 = $("#creasing_1").val()
		let creasing_2 = $("#creasing_2").val()
		let creasing_3 = $("#creasing_3").val()
		let kualitas = $("#kualitas").val()
		let flute = $("#flute").val()
		let tipe_box = $("#tipe_box").val()
		let sambungan = $("#sambungan").val()
		let bb_box = $("#bb_box").val()
		let lb_box = $("#lb_box").val()
		let panjang_plan = $("#panjang_plan").val().split('.').join('')
		let lebar_plan = $("#lebar_plan").val().split('.').join('')
		let order_so = $("#order_so").val().split('.').join('')
		let kirim = $("#kirim").val()
		let tgl_cor = $("#tgl_cor").val()
		let qty_cor = $("#qty_cor").val().split('.').join('')
		let next_flexo = $("#next_flexo").val()

		if(plan_cor == "" && opsi == 'add'){
			toastr.error('<b>PILIH PLAN COR DAHULU!</b>');
			return
		}

		$.ajax({
			url: '<?php echo base_url('Plan/addRencanaFlexo')?>',
			type: "POST",
			data: ({
				opsi, tgl, shift, mesin, plan_cor ,no_wo ,no_po ,customer ,kode_mc ,item ,uk_box ,uk_sheet ,creasing_1 ,creasing_2 ,creasing_3 ,kualitas ,flute ,tipe_box ,sambungan ,bb_box ,lb_box ,panjang_plan ,lebar_plan ,order_so ,kirim ,tgl_cor ,qty_cor, next_flexo
			}),
			success: function(res){
				data = JSON.parse(res)
				if(opsi == 'add'){
					if(data.data){
						ListInputFlexo()
						kosong()
						loadPlanCor('')
					}else{
						swal(data.isi, "", "error")
					}
				}else{
					if(data.data == true && data.insertGudang == true){
						plhPlanCor(opsi)
					}else{
						swal(data.isi, "", "error")
					}
				}
			}
		})
	}

	function clickDonePlanCorFlexo(id_plan_cor)
	{
		$.ajax({
			url: '<?php echo base_url('Plan/clickDonePlanCorFlexo')?>',
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
			data: ({ id_plan_cor }),
			success: function(res){
				data = JSON.parse(res)
				if(data.data){
					loadDataPlanFlexo(urlTglF, urlShiftF, urlMesinF)
				}else{
					swal(data.msg, "", "error")
				}
			}
		})
	}

	function ListInputFlexo()
	{
		$.ajax({
			url: '<?php echo base_url('Plan/ListInputFlexo')?>',
			type: "POST",
			success: function(res){
				$("#list-input-flexo").html(res)
			}
		})
	}

	function hapusPlanFlexo(id_flexo){
		swal({
			title: "Apakah Kamu Yakin?",
			text: "",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#C00",
			confirmButtonText: "Delete"
		}).then(function(result) {
			$.ajax({
				url: '<?php echo base_url('Plan/hapusPlanFlexo')?>',
				type: "POST",
				data: ({
					id_flexo
				}),
				success: function(res){
					data = JSON.parse(res)
					if(data.data){
						loadDataPlanFlexo(urlTglF, urlShiftF, urlMesinF)
					}else{
						swal(data.msg, "", "error")
					}
				}
			})
		});
	}

	function hapusCartFlexo(rowid){
		$.ajax({
			url: '<?php echo base_url('Plan/hapusCartFlexo')?>',
			type: "POST",
			data: ({
				rowid
			}),
			success: function(res){
				ListInputFlexo()
			}
		})
	}

	function simpanCartFlexo()
	{
		let tgl = $("#tgl").val()
		let shift = $("#shift").val()
		let mesin = $("#mesin").val()
		$("#simpan-cart-fx").prop("disabled", true)
		$.ajax({
			url: '<?php echo base_url('Plan/simpanCartFlexo')?>',
			type: "POST",
			success: function(res){
				window.location.href = '<?php echo base_url('Plan/Flexo/List')?>'+'/'+tgl+'/'+shift+'/'+mesin
			}
		})
	}

	function loadListPlanFlexo(tf, sf, mf, opsi = '')
	{
		let tglF = ''
		let shiftF = ''
		let mesinF = ''
		let hidplan = ''
		if(tf == '' && sf == '' && mf == ''){
			tglF = urlTglF
			shiftF = urlShiftF
			mesinF = urlMesinF
			opsi = opsi
			hidplan = $("#ehid_flexo").val()
		}else{
			tglF = tf
			shiftF = sf
			mesinF = mf
			opsi = opsi
			hidplan = ''
		}
		
		$.ajax({
			url: '<?php echo base_url('Plan/loadListPlanFlexo')?>',
			type: "POST",
			data: ({
				tglF, shiftF, mesinF, opsi, hidplan
			}),
			success: function(res){
				if(tf == '' && sf == '' && mf == '' && opsi == ''){
					(inputDtProd == 'inputDowntimeProduksi') ? plhDowntime() : loadDataAllPlanFlexo();
					$("#list-plan-flexo").html(res)
				}else{
					$("#tampil-all-fflexo-isi-"+tf.split('-').join('')+sf+mf).html(res);
					swal.close()
				}
			}
		})
	}

	function editPlanFlexo(id_flexo)
	{
		let editNextFlexo = $("#edit-nextflexo-"+id_flexo).val()
		$.ajax({
			url: '<?php echo base_url('Plan/editPlanFlexo')?>',
			type: "POST",
			data: ({ id_flexo, editNextFlexo }),
			success: function(res){
				data = JSON.parse(res)
				if(data.data){
					loadDataPlanFlexo(urlTglF, urlShiftF, urlMesinF)
				}else{
					swal(data.msg, "", "error")
				}
			}
		})
	}

	function hitungProduksiFlexo()
	{
		let rp = new Intl.NumberFormat('id-ID', {styles: 'currency', currency: 'IDR'})
		let good_flexo = $("#good_flexo").val().split('.').join('');
		(good_flexo < 0 || good_flexo == 0 || good_flexo == '') ? good_flexo = 0 : good_flexo = good_flexo;
		$("#good_flexo").val(rp.format(good_flexo));
		let bad_flexo = $("#bad_flexo").val().split('.').join('');
		(bad_flexo < 0 || bad_flexo == 0 || bad_flexo == '') ? bad_flexo = 0 : bad_flexo = bad_flexo;
		$("#bad_flexo").val(rp.format(bad_flexo));
		let bad_b_flexo = $("#bad_b_flexo").val().split('.').join('');
		(bad_b_flexo < 0 || bad_b_flexo == 0 || bad_b_flexo == '') ? bad_b_flexo = 0 : bad_b_flexo = bad_b_flexo;
		$("#bad_b_flexo").val(rp.format(bad_b_flexo));
		let hitung = parseInt(good_flexo) + parseInt(bad_flexo) + parseInt(bad_b_flexo);
		$("#total_flexo").val(rp.format(hitung));
	}

	function produksiPlanFlexo(id_flexo)
	{
		let good_cor = $("#good_cor").val().split('.').join('')
		let good_flexo = $("#good_flexo").val().split('.').join('')
		let bad_flexo = $("#bad_flexo").val().split('.').join('')
		let bad_b_flexo = $("#bad_b_flexo").val().split('.').join('')
		let total_flexo = $("#total_flexo").val().split('.').join('')
		let ket_flexo = $("#ket_flexo").val()
		let tgl_flexo = $("#tgl_flexo").val()
		let start_flexo = $("#start_flexo").val()
		let end_flexo = $("#end_flexo").val()

		if(good_flexo < 0 || good_flexo == 0 || good_flexo == '' || total_flexo < 0 || total_flexo == 0 || total_flexo == ''){
			swal("DATA PRODUKSI TIDAK BOLEH KOSONG!", "", "error")
			return
		}
		if(tgl_flexo == '' || start_flexo == '' || end_flexo == ''){
			swal("TANGGAL/START/END TIDAK BOLEH KOSONG!", "", "error")
			return
		}

		$.ajax({
			url: '<?php echo base_url('Plan/produksiPlanFlexo')?>',
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
				id_flexo, good_cor, good_flexo, bad_flexo, bad_b_flexo, total_flexo, ket_flexo, tgl_flexo, start_flexo, end_flexo
			}),
			success: function(res){
				data = JSON.parse(res)
				if(data.data){
					loadPlanCor('not')
					plhPlanCor(id_flexo)
				}else{
					swal(data.msg, "", "error");
				}
			}
		})
	}

	function btnGantiTglFlexo(id_flexo)
	{
		let tgl = $("#tgl").val()
		let shift = $("#shift").val()
		let mesin = $("#mesin").val()
		$.ajax({
			url: '<?php echo base_url('Plan/btnGantiTglFlexo')?>',
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
				tgl, shift, mesin, id_flexo
			}),
			success: function(res){
				data = JSON.parse(res)
				if(data.data){
					loadDataPlanFlexo(urlTglF, urlShiftF, urlMesinF)
				}else{
					swal(data.msg, "", "error")
				}
			}
		})
	}

	function onChangeNourutFlexo(i)
	{
		$("#card-produksi").hide()
		$("#ehid_flexo").val("")
		let no_urut = $("#lp-nourut-"+i).val();
		(no_urut < 0 || no_urut == "") ? no_urut = 0 : no_urut = no_urut;
		$("#lp-nourut-"+i).val(no_urut)
		
		$.ajax({
			url: '<?php echo base_url('Plan/onChangeNourutFlexo')?>',
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
					riwayatFlexo(0)
					loadListPlanFlexo('','','','')
				}else{
					swal(data.msg, "", "error")
				}
			}
		})
	}

	function riwayatFlexo(id_plan)
	{
		$("#riwayat-flexo").html(``)
		$.ajax({
			url: '<?php echo base_url('Plan/riwayatFlexo')?>',
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
				$("#riwayat-flexo").html(res)
				// swal.close()
			}
		})
	}

	function showRiwayat(id_plan = '', id_flexo = '', id_fs = '', opsi)
	{
		$("#modal-body-isi").html(`. . .`)
		$("#modalForm").modal("show")
		$.ajax({
			url: '<?php echo base_url('Plan/showRiwayat')?>',
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
			data: ({ id_plan, id_flexo, id_fs, opsi }),
			success: function(res){
				$("#modal-body-isi").html(res)
				swal.close()
			}
		})
	}

	function plhDowntime()
	{
		$("#dt-pilih").html('')
		$("#dt-select").html('')
		$("#dt-load-data").html('')
		let id_flexo = $("#ehid_flexo").val()
		$.ajax({
			url: '<?php echo base_url('Plan/plhDowntime')?>',
			type: "POST",
			data: ({
				id_plan: '', id_flexo
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
		let id_flexo = $("#ehid_flexo").val()

		$.ajax({
			url: '<?php echo base_url('Plan/downtime')?>',
			type: "POST",
			data: ({
				downtime, id_plan: '', id_flexo
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
		let id_flexo = $("#ehid_flexo").val()
		$.ajax({
			url: '<?php echo base_url('Plan/loadDataDowntime')?>',
			type: "POST",
			data: ({
				id_plan: '', id_flexo
			}),
			success: function(res){
				$("#dt-load-data").html(res)
				loadDataAllPlanFlexo()
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
		let id_flexo = $("#ehid_flexo").val()
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
				id_plan: '', id_flexo, id_dt, durasi, ket
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
		let id_flexo = $("#ehid_flexo").val()
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
				id_dt, id_plan: '', id_flexo
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
				id_plan: '', id_flexo: i, ket, durasi
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
