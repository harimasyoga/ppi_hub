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
										<a class="d-block w-100 link-h-wo" style="font-weight:bold;padding:6px" data-toggle="collapse" href="#collapseCustomer" onclick="fsDataAllCustFlexo()">
											LIST SEMUA PLAN FLEXO
										</a>
									</div>
									<div id="collapseCustomer" class="collapse" data-parent="#accordion-customer">
										<div id="tampil-all-cust-header"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="card card-info card-outline">
						<div class="card-header">
							<h3 class="card-title" style="font-weight:bold;font-style:italic">PLAN FLEXO</h3>
						</div>
						<div class="card-body row" style="padding-bottom:5px;font-weight:bold">
							<div class="col-md-12" style="padding:0">
								<a href="<?php echo base_url('Plan/Finishing')?>" class="btn btn-sm btn-info"><i class="fa fa-arrow-left"></i> <b>Kembali</b></a>
								<a href="<?php echo base_url('Plan/Finishing/Add')?>" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> <b>Add</b></a>
							</div>
						</div>
						<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
							<div class="col-md-1"></div>
							<div class="col-md-11" style="font-size:small;font-style:italic;color:#f00">
								* [ TYPE ] NO. WO | TGL FLEXO | ITEM | CUSTOMER
							</div>
						</div>
						<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
							<div class="col-md-1 p-0">PLAN FLEXO</div>
							<div class="col-md-11">
								<select id="plan_flexo" class="form-control select2" onchange="plhPlanFlexo('')"></select>
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
							<div id="accordion-finishing">
								<div class="card m-0" style="border-radius:0">
									<div class="card-header bg-gradient-secondary" style="padding:0;border-radius:0">
										<a class="d-block w-100 link-h-wo" style="font-weight:bold;padding:6px" data-toggle="collapse" href="#collapsefinishing" onclick="loadDataAllPlanFinishing()">
											LIST SEMUA PLAN FINISHING
										</a>
									</div>
									<div id="collapsefinishing" class="collapse" data-parent="#accordion-finishing">
										<div id="tampil-all-plan-finishing-header"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="col-md-12">
					<div id="list-plan-finishing"></div>
					<div id="riwayat-fs"></div>
				</div>
				
				<div class="col-md-12">
					<div id="list-input-finishing"></div>
				</div>

				<div class="col-md-7">
					<div id="card-produksi" style="display:none">
						<!-- <div class="card card-danger card-outline" style="padding-bottom:20px">
							<div class="card-header">
								<h3 class="card-title" style="font-weight:bold;font-style:italic">DOWNTIME</h3>
							</div>
							<div id="dt-pilih"></div>
							<div id="dt-select"></div>
							<div style="overflow:auto;white-space:nowrap">
								<div id="dt-load-data"></div>
							</div>
						</div> -->

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
								<div class="col-md-2">HASIL FLX.</div>
								<div class="col-md-10">
									<input type="number" id="good_flexo" style="font-weight:bold" class="form-control" disabled>
								</div>
							</div>
							<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
								<div class="col-md-2">GOOD</div>
								<div class="col-md-10">
									<input type="number" id="good_fs" class="form-control" onkeyup="hitungProduksiFinishing()">
								</div>
							</div>
							<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
								<div class="col-md-2">R. FINISH</div>
								<div class="col-md-10">
									<input type="number" id="bad_fs" class="form-control" onkeyup="hitungProduksiFinishing()">
								</div>
							</div>
							<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
								<div class="col-md-2">R. BAHAN</div>
								<div class="col-md-10">
									<input type="number" id="bad_b_fs" class="form-control" onkeyup="hitungProduksiFinishing()">
								</div>
							</div>
							<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
								<div class="col-md-2">TOTAL</div>
								<div class="col-md-10">
									<input type="number" id="total_fs" class="form-control" onkeyup="hitungProduksiFinishing()" disabled>
								</div>
							</div>
							<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
								<div class="col-md-2">KET</div>
								<div class="col-md-10">
									<textarea id="ket_fs" class="form-control" style="resize:none" rows="2"></textarea>
								</div>
							</div>
							<div class="card-body row" style="padding:20px 20px 5px;font-weight:bold">
								<div class="col-md-2">TGL PROD.</div>
								<div class="col-md-10">
									<input type="date" id="tgl_fs" class="form-control">
								</div>
							</div>
							<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
								<div class="col-md-2">START</div>
								<div class="col-md-10">
									<input type="time" id="start_fs" class="form-control">
								</div>
							</div>
							<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
								<div class="col-md-2">END</div>
								<div class="col-md-10">
									<input type="time" id="end_fs" class="form-control">
								</div>
							</div>

							<?php if($this->session->userdata('level') == 'Admin' || $this->session->userdata('level') == 'PPIC' || $this->session->userdata('level') == 'Finishing' || $this->session->userdata('level') == 'User') { ?>
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
							<h3 class="card-title" style="font-weight:bold;font-style:italic">FINISHING</h3>
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
							<div class="col-md-2">JOINT</div>
							<div class="col-md-10">
								<select id="joint" class="form-control select2"></select>
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
						<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
							<div class="col-md-12">KELUAR FLEXO</div>
						</div>
						<div class="card-body row" style="padding:0 20px 20px;font-weight:bold">
							<div class="col-md-2">TGL</div>
							<div class="col-md-4">
								<input type="text" id="tgl_flexo" class="form-control pr-0" placeholder="TANGGAL" disabled>
							</div>
							<div class="col-md-2">QTY</div>
							<div class="col-md-4">
								<input type="text" id="qty_flexo" class="form-control" placeholder="QTY FLEXO" disabled>
							</div>
						</div>
						<div id="btn-add-plan-finishing"></div>
					</div>
				</div>

				<input type="hidden" id="ehid_finishing" value="">
				<input type="hidden" id="ehid_flexo" value="">
				<input type="hidden" id="ehid_plan_cor" value="">

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
	const urlTglFs = '<?= $tgl?>'
	const urlShiftFs = '<?= $shift?>'
	const urlJointFs = '<?= $joint?>'
	let inputDtProd = ''

	$(document).ready(function ()
	{
		$("#tgl").val(urlTglFs).prop('disabled', true)
		$("#shift").html(`<option value="${urlShiftFs}">${urlShiftFs}</option>`).prop('disabled', true)
		$("#joint").html(`<option value="${urlJointFs}">${urlJointFs}</option>`).prop('disabled', true)
		loadDataPlanFinishing(urlTglFs, urlShiftFs, urlJointFs)

		$("#card-produksi").hide()
		$("#list-input-finishing").load("<?php echo base_url('Plan/destroyPlanFinishing') ?>")
		$("#plan_flexo").html('<option value="">PILIH</option>').prop("disabled", true)
		$('.select2').select2({
			dropdownAutoWidth: true
		})
	})

	function kosong()
	{
		$("input[type=text]").val("")
		$("#kirim").val("")
		$("#tgl_cor").val("")
		$("#tgl_flexo").val("")

		$("#tgl").val(urlTglFs).prop('disabled', true)
		$("#shift").html(`<option value="${urlShiftFs}">${urlShiftFs}</option>`).prop('disabled', true)
		$("#joint").html(`<option value="${urlJointFs}">${urlJointFs}</option>`).prop('disabled', true)
		$("#btn-ganti-tgl").html("")
	}

	function fsDataAllCustFlexo()
	{
		$.ajax({
			url: '<?php echo base_url('Plan/fsDataAllCustFlexo')?>',
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
				$("#tampil-all-cust-header").html(res)
				swal.close()
			}
		})
	}

	function onClickHeaderPlanFlexo(id_pelanggan)
	{
		$.ajax({
			url: '<?php echo base_url('Plan/onClickHeaderPlanFlexo')?>',
			type: "POST",
			data: ({
				id_pelanggan
			}),
			success: function(res){
				$("#tampil-all-flexo-isi-"+id_pelanggan).html(res)
			}
		})
	}

	function onclickHeaderIsiPlanFlexo(id_plan, id_flexo, id_pelanggan)
	{
		$.ajax({
			url: '<?php echo base_url('Plan/onclickHeaderIsiPlanFlexo')?>',
			type: "POST",
			data: ({
				id_plan, id_flexo, id_pelanggan
			}),
			success: function(res){
				$("#tampil-all-ppfflan-isi-"+id_plan+"-"+id_flexo+"-"+id_pelanggan).html(res)
			}
		})
	}

	function loadDataAllPlanFinishing()
	{
		$.ajax({
			url: '<?php echo base_url('Plan/loadDataAllPlanFinishing')?>',
			type: "POST",
			data: ({
				urlTglFs, urlShiftFs, urlJointFs
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
				$("#tampil-all-plan-finishing-header").html(res)
				swal.close()
			}
		})
	}

	function loadDataPlanFinishing(uTgl, uShift, uJoint)
	{
		$("#tgl").val(urlTglFs).prop('disabled', true)
		$("#shift").html(`<option value="${urlShiftFs}">${urlShiftFs}</option>`).prop('disabled', true)
		$("#joint").html(`<option value="${urlJointFs}">${urlJointFs}</option>`).prop('disabled', true)
		$.ajax({
			url: '<?php echo base_url('Plan/loadDataPlanFinishing')?>',
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
				uTgl, uShift, uJoint
			}),
			success: function(res){
				data = JSON.parse(res)
				if(data.length == 0){
					window.location.href = '<?php echo base_url('Plan/Finishing')?>'
				}else{
					loadPlanFlexo('not')
					plhPlanFlexo('')
				}
			}
		})
	}

	function loadPlanFlexo(opsi = '')
	{
		let joint = $("#joint").val()
		$.ajax({
			url: '<?php echo base_url('Plan/loadPlanFlexo')?>',
			type: "POST",
			data: ({ opsi: '', joint, urlTglFs, urlShiftFs, urlJointFs }),
			success: function(res){
				data = JSON.parse(res)
				let htmlPlanFlexo = ''
				let kategori = ''
					htmlPlanFlexo += `<option value="">PILIH</option>`
				data.plan_flexo.forEach(laodPlanFlexo);
				function laodPlanFlexo(r, index) {
					(r.kategori == 'K_BOX') ? kategori = '[ BOX ]' : kategori = '[ SHEET ]';
					htmlPlanFlexo += `<option value="${r.id_flexo}"
						op-id-plan-cor="${r.id_plan}"
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
						op-tgl-flexo="${r.tgl_prod_f}"
						op-qty-flexo="${r.good_flexo_p}"
					>
						${kategori} ${r.no_wo} | ${r.tgl_flexo} | ${r.nm_produk} | ${r.nm_pelanggan}
					</option>`
				}
				$("#plan_flexo").html(htmlPlanFlexo).prop('disabled', false)
				if(opsi !='not'){
					swal.close()
				}
			}
		})
	}

	function plhPlanFlexo(opsi = '')
	{
		$("#tgl").val(urlTglFs).prop('disabled', true)
		$("#shift").html(`<option value="${urlShiftFs}">${urlShiftFs}</option>`).prop('disabled', true)
		$("#joint").html(`<option value="${urlJointFs}">${urlJointFs}</option>`).prop('disabled', true)
		let rupiah = new Intl.NumberFormat('id-ID', {styles: 'currency', currency: 'IDR'});
		let opIdPlanCor = ''; let opNoWo = ''; let opNoPo = ''; let opCustomer = ''; let opKodeMc = ''; let opItem = ''; let opUkBox = ''; let opUkSheet = ''; let opCreasing1 = ''; let opCreasing2 = ''; let opCreasing3 = ''; let opKualitas = ''; let opFlute = ''; let opTipeBox = ''; let opSambungan = ''; let opBbBox = ''; let opLbBox = ''; let opPanjangPlan = ''; let opLebarPlan = ''; let opOrderSo = ''; let opKirim = ''; let opTglCor = ''; let opQtyCor = ''; let opTglFlexo = ''; let opQtyFlexo = ''

		$.ajax({
			url: '<?php echo base_url('Plan/loadPlanFlexo')?>',
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
			data: ({ opsi, joint: '', urlTglFs, urlShiftFs, urlJointFs }),
			success: function(res){
				data = JSON.parse(res)
				console.log(data)
				if(data.opsi != ""){
					opNoWo = data.plan_flexo.no_wo
					opIdPlanCor = data.plan_flexo.id_plan
					opNoPo = data.plan_flexo.kode_po
					opCustomer = data.plan_flexo.nm_pelanggan
					opKodeMc = data.plan_flexo.kode_mc
					opItem = data.plan_flexo.nm_produk
					opUkBox = data.plan_flexo.ukuran
					opUkSheet = data.plan_flexo.ukuran_sheet
					opCreasing1 = data.plan_flexo.creasing
					opCreasing2 = data.plan_flexo.creasing2
					opCreasing3 = data.plan_flexo.creasing3
					opKualitas = data.plan_flexo.kualitas
					opFlute = data.plan_flexo.flute
					opTipeBox = data.plan_flexo.tipe_box
					opSambungan = data.plan_flexo.sambungan
					opBbBox = data.plan_flexo.berat_bersih
					opLbBox = data.plan_flexo.luas_bersih
					opPanjangPlan = data.plan_flexo.panjang_plan
					opLebarPlan = data.plan_flexo.lebar_plan
					opOrderSo = data.plan_flexo.qty_so
					opKirim = data.plan_flexo.tgl_kirim_plan
					opTglCor = data.plan_flexo.tgl_prod_p
					opQtyCor = data.plan_flexo.good_cor_p
					opTglFlexo = data.plan_flexo.tgl_prod_f
					opQtyFlexo = data.plan_flexo.good_flexo_p

					let tms = '';
					(data.plan_flexo.total_prod_fs == 0 && data.plan_flexo.status_fs == 'Open') ? tms = false : tms = true;
					$("#tgl").val(urlTglFs).prop("disabled", tms)
					let optShift = `<option value="${urlShiftFs}">${urlShiftFs}</option><option value="1">1</option><option value="2">2</option><option value="3">3</option>`
					let opJoint = `<option value="${urlJointFs}">${urlJointFs}</option>`;
					$("#shift").html(optShift).prop("disabled", tms)
					$("#joint").html(opJoint).prop("disabled", tms)

					let htmlBtnGantiTgl = '';
					(data.plan_flexo.total_prod_fs == 0 && data.plan_flexo.status_fs == 'Open' && (urlAuth == 'Admin' || urlAuth == 'PPIC' || urlAuth == 'User')) ?
						htmlBtnGantiTgl = `<div class="card-body row" style="padding:0 20px 5px">
							<div class="col-md-2"></div>
							<div class="col-md-10">
								<button class="btn btn-sm btn-warning" style="font-weight:bold" onclick="btnGantiTglFinishing(${data.plan_flexo.id_fs})">GANTI</button>
							</div>
						</div>` : htmlBtnGantiTgl = '';
					$("#btn-ganti-tgl").html(htmlBtnGantiTgl)

					$("#ehid_finishing").val(data.plan_flexo.id_fs)
					$("#ehid_flexo").val(data.plan_flexo.id_flexo)
					$("#ehid_plan_cor").val(data.plan_flexo.id_plan)

					if(data.plan_flexo.total_prod_fs != 0){
						// inputDtProd = 'inputDowntimeProduksi'
						$("#card-produksi").show()
					}else if(data.urutDtProd == null){
						// inputDtProd = ''
						$("#card-produksi").hide()
						$("#btn-aksi-produksi").html(``)
					}else if(data.plan_flexo.id_fs == data.urutDtProd.id_fs){
						if(data.plan_flexo.total_prod_flexo != 0 && data.plan_flexo.status_flexo == 'Close'){
							// inputDtProd = 'inputDowntimeProduksi'
							$("#card-produksi").show()
						}else{
							// inputDtProd = ''
							$("#card-produksi").hide()
							$("#btn-aksi-produksi").html(``)
						}
					}else{
						// inputDtProd = ''
						$("#card-produksi").hide()
						$("#btn-aksi-produksi").html(``)
					}

					$("#good_cor").val(rupiah.format(data.plan_flexo.good_cor_p))
					$("#good_flexo").val(rupiah.format(data.plan_flexo.good_flexo_p))
					$("#good_fs").val(data.plan_flexo.good_fs_p)
					$("#bad_fs").val(data.plan_flexo.bad_fs_p)
					$("#bad_b_fs").val(data.plan_flexo.bad_bahan_fs_p)
					$("#total_fs").val(data.plan_flexo.total_prod_fs)
					$("#ket_fs").val(data.plan_flexo.ket_fs_p)
					$("#tgl_fs").val(data.plan_flexo.tgl_pord_fs)
					$("#start_fs").val(data.plan_flexo.start_time_fs)
					$("#end_fs").val(data.plan_flexo.end_time_fs)
					
					$("#btn-aksi-produksi").html(`<div class="card-body row" style="padding:20px 20px 0;font-weight:bold">
						<div class="col-md-12">
							<button type="button" class="btn btn-success btn-block" onclick="produksiPlanFinishing(${data.plan_flexo.id_fs})"><i class="fa fa-save"></i> <b>SIMPAN</b></button>
						</div>
					</div>`)

					let txtPlanFs = ''
					let onclickSelesaiFs = ''
					if(data.plan_flexo.total_prod_fs == 0 && data.plan_flexo.status_fs == 'Open'){
						txtPlanFs = 'SIMPAN'
						onclickSelesaiFs = 'disabled'
					}else if(data.plan_flexo.total_prod_fs != 0 && data.plan_flexo.status_fs == 'Open'){
						txtPlanFs = 'UPDATE'
						onclickSelesaiFs = `onclick="addRencanaFinishing(${data.plan_flexo.id_fs})"`
					}else{
						txtPlanFs = 'UPDATE'
						onclickSelesaiFs = 'disabled'
					}

					let onClickDonePlanFlexo = ''
					if(data.plan_flexo.total_prod_fs != 0 && data.plan_flexo.status_fs == 'Close' && data.plan_flexo.status_stt_f == 'Open'){
						onClickDonePlanFlexo = `onclick="clickDonePlanCorFlexoFs(${data.plan_flexo.id_plan_flexo})"`
					}else{
						onClickDonePlanFlexo = 'disabled'
					}

					if(urlAuth == 'Admin' || urlAuth == 'PPIC' || urlAuth == 'Flexo' || urlAuth == 'User'){
						if((data.plan_flexo.total_prod_fs == 0 || data.plan_flexo.total_prod_fs != 0) && data.plan_flexo.status_fs == 'Open'){
							$("#btn-aksi-produksi").html(`<div class="card-body row" style="padding:20px 20px 0;font-weight:bold">
								<div class="col-md-12">
									<button type="button" class="btn btn-success btn-block" onclick="produksiPlanFinishing(${data.plan_flexo.id_fs})"><i class="fa fa-save"></i> <b>${txtPlanFs}</b></button>
								</div>
							</div>`)
						}else{
							$("#btn-aksi-produksi").html('')
						}

						if(urlAuth == 'Admin' || urlAuth == 'PPIC' || urlAuth == 'User'){
							$("#btn-add-plan-finishing").html(`<div class="card-body row" style="padding:0 20px 17px;font-weight:bold">
								<div class="col-md-6">
									<button type="button" class="btn btn-primary btn-block" style="margin-bottom:3px" ${onclickSelesaiFs}><i class="fa fa-check"></i> <b>SELESAI FINISHING</b></button>
								</div>
								<div class="col-md-6">
									<button type="button" class="btn btn-dark btn-block" style="margin-bottom:3px" ${onClickDonePlanFlexo}><i class="fa fa-check"></i> <b>SELESAI FLEXO</b></button>
								</div>
							</div>`)
						}else{
							$("#btn-add-plan-finishing").html(``)
						}
					}else{
						$("#btn-aksi-produksi").html(``)
						$("#btn-add-plan-finishing").html(``)
					}
				}else{
					opIdPlanCor = $('#plan_flexo option:selected').attr('op-id-plan-cor')
					opNoWo = $('#plan_flexo option:selected').attr('op-no-wo')
					opNoPo = $('#plan_flexo option:selected').attr('op-no-po')
					opCustomer = $('#plan_flexo option:selected').attr('op-customer')
					opKodeMc = $('#plan_flexo option:selected').attr('op-kode-mc')
					opItem = $('#plan_flexo option:selected').attr('op-item')
					opUkBox = $('#plan_flexo option:selected').attr('op-uk-box')
					opUkSheet = $('#plan_flexo option:selected').attr('op-uk-sheet')
					opCreasing1 = $('#plan_flexo option:selected').attr('op-creasing-1')
					opCreasing2 = $('#plan_flexo option:selected').attr('op-creasing-2')
					opCreasing3 = $('#plan_flexo option:selected').attr('op-creasing-3')
					opKualitas = $('#plan_flexo option:selected').attr('op-kualitas')
					opFlute = $('#plan_flexo option:selected').attr('op-flute')
					opTipeBox = $('#plan_flexo option:selected').attr('op-tipe-box')
					opSambungan = $('#plan_flexo option:selected').attr('op-sambungan')
					opBbBox = $('#plan_flexo option:selected').attr('op-bb-box')
					opLbBox = $('#plan_flexo option:selected').attr('op-lb-box')
					opPanjangPlan = $('#plan_flexo option:selected').attr('op-panjang-plan')
					opLebarPlan = $('#plan_flexo option:selected').attr('op-lebar-plan')
					opOrderSo = $('#plan_flexo option:selected').attr('op-order-so')
					opKirim = $('#plan_flexo option:selected').attr('op-kirim')
					opTglCor = $('#plan_flexo option:selected').attr('op-tgl-cor')
					opQtyCor = $('#plan_flexo option:selected').attr('op-qty-cor')
					opTglFlexo = $('#plan_flexo option:selected').attr('op-tgl-flexo')
					opQtyFlexo = $('#plan_flexo option:selected').attr('op-qty-flexo')
					let idx_flexo = $('#plan_flexo').val();

					$("#btn-ganti-tgl").html("")
					$("#ehid_finishing").val("")
					$("#ehid_flexo").val(idx_flexo)
					$("#ehid_plan_cor").val(opIdPlanCor)

					$("#good_cor").val("")
					$("#good_flexo").val("")
					$("#good_fs").val("")
					$("#bad_fs").val("")
					$("#bad_b_fs").val("")
					$("#total_fs").val("")
					$("#ket_fs").val("")
					$("#tgl_fs").val("")
					$("#start_fs").val("")
					$("#end_fs").val("")

					$("#card-produksi").hide()

					if(urlAuth == 'Admin' || urlAuth == 'PPIC' || urlAuth == 'User'){
						$("#btn-aksi-produksi").html('')
						$("#btn-add-plan-finishing").html(`<div class="card-body row" style="padding:0 20px 20px;font-weight:bold">
							<div class="col-md-12">
								<button type="button" class="btn btn-success btn-block" onclick="addRencanaFinishing('add')"><i class="fa fa-plus"></i> <b>ADD FINISHING</b></button>
							</div>
						</div>`)
					}else{
						$("#btn-aksi-produksi").html('')
						$("#btn-add-plan-finishing").html('')
					}

				}

				(opTglCor == 'null') ? opTglCor = '' : opTglCor = opTglCor;
				(opTglFlexo == 'null') ? opTglFlexo = '' : opTglFlexo = opTglFlexo;
				(opPanjangPlan == undefined) ? opPanjangPlan = '' : opPanjangPlan = rupiah.format(opPanjangPlan);
				(opLebarPlan == undefined) ? opLebarPlan = '' : opLebarPlan = rupiah.format(opLebarPlan);
				(opOrderSo == undefined) ? opOrderSo = '' : opOrderSo = rupiah.format(opOrderSo);
				(opQtyCor == undefined) ? opQtyCor = '' : opQtyCor = rupiah.format(opQtyCor);
				(opQtyFlexo == undefined) ? opQtyFlexo = '' : opQtyFlexo = rupiah.format(opQtyFlexo);

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
				$("#tgl_flexo").val(opTglFlexo)
				$("#qty_flexo").val(opQtyFlexo)

				loadListPlanFinishing('','','','')
			}
		})
	}

	function addRencanaFinishing(opsi)
	{
		let tgl = $("#tgl").val()
		let shift = $("#shift").val()
		let joint = $("#joint").val()
		let plan_flexo = $('#plan_flexo').val()
		let opIdPlanCor = $('#ehid_plan_cor').val()
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
		let tgl_flexo = $("#tgl_flexo").val()
		let qty_flexo = $("#qty_flexo").val().split('.').join('')

		$.ajax({
			url: '<?php echo base_url('Plan/addRencanaFinishing')?>',
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
				tgl, shift, joint, plan_flexo , opIdPlanCor,no_wo ,no_po ,customer ,kode_mc ,item ,uk_box ,uk_sheet ,creasing_1 ,creasing_2 ,creasing_3 ,kualitas ,flute ,tipe_box ,sambungan ,bb_box ,lb_box ,panjang_plan ,lebar_plan ,order_so ,kirim ,tgl_cor ,qty_cor, tgl_flexo, qty_flexo, opsi
			}),
			success: function(res){
				data = JSON.parse(res)
				console.log(data)
				if(opsi == 'add'){
					if(data.data){
						kosong()
						loadPlanFlexo('')
						ListInputFinishing()
					}else{
						swal(data.isi, "", "error")
					}
				}else{
					if(data.data == true && data.insertGudang == true){
						plhPlanFlexo(opsi)
					}else{
						swal(data.isi, "", "error")
					}
				}
			}
		})
	}

	function ListInputFinishing()
	{
		$.ajax({
			url: '<?php echo base_url('Plan/ListInputFinishing')?>',
			type: "POST",
			success: function(res){
				$("#list-input-finishing").html(res)
				swal.close()
			}
		})
	}

	function hapusCartFinishing(rowid){
		$.ajax({
			url: '<?php echo base_url('Plan/hapusCartFinishing')?>',
			type: "POST",
			data: ({
				rowid
			}),
			success: function(res){
				ListInputFinishing()
			}
		})
	}

	function simpanCartFinishing()
	{
		let tgl = $("#tgl").val()
		let shift = $("#shift").val()
		let joint = $("#joint").val()
		$("#simpan-cart-fs").prop("disabled", true)
		$.ajax({
			url: '<?php echo base_url('Plan/simpanCartFinishing')?>',
			type: "POST",
			success: function(res){
				data = JSON.parse(res)
				window.location.href = '<?php echo base_url('Plan/Finishing/List')?>'+'/'+tgl+'/'+shift+'/'+joint
			}
		})
	}

	function loadListPlanFinishing(tf, sf, mf, opsi = '')
	{
		let idx_flexo = $("#ehid_flexo").val()
		let idx_plan_cor = $("#ehid_plan_cor").val()
		let tglF = ''
		let shiftF = ''
		let jointF = ''
		let hidplan = ''
		if(tf == '' && sf == '' && mf == ''){
			tglF = urlTglFs
			shiftF = urlShiftFs
			jointF = urlJointFs
			opsi = opsi
			hidplan = $("#ehid_finishing").val()
		}else{
			tglF = tf
			shiftF = sf
			jointF = mf
			opsi = opsi
			hidplan = ''
		}
		
		$.ajax({
			url: '<?php echo base_url('Plan/loadListPlanFinishing')?>',
			type: "POST",
			data: ({
				tglF, shiftF, jointF, opsi, hidplan
			}),
			success: function(res){
				if(tf == '' && sf == '' && mf == '' && opsi == ''){
				// 	(inputDtProd == 'inputDowntimeProduksi') ? plhDowntime() : loadDataAllPlanFinishing();
					riwayatFinishing(idx_plan_cor, idx_flexo)
					$("#list-plan-finishing").html(res)
				}else{
					$("#tampil-all-ffss-isi-"+tf.split('-').join('')+sf+mf).html(res);
					swal.close()
				}
			}
		})
	}

	function hapusPlanFinishing(id_fs){
		swal({
			title: "Apakah Kamu Yakin?",
			text: "",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#C00",
			confirmButtonText: "Delete"
		}).then(function(result) {
			$.ajax({
				url: '<?php echo base_url('Plan/hapusPlanFinishing')?>',
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
					id_fs
				}),
				success: function(res){
					data = JSON.parse(res)
					if(data.data){
						loadDataPlanFinishing(urlTglFs, urlShiftFs, urlJointFs)
					}else{
						swal(data.msg, "", "error")
					}
				}
			})
		});
	}

	function btnGantiTglFinishing(id_fs)
	{
		let tgl = $("#tgl").val()
		let shift = $("#shift").val()
		let joint = $("#joint").val()
		$.ajax({
			url: '<?php echo base_url('Plan/btnGantiTglFinishing')?>',
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
				tgl, shift, joint, id_fs
			}),
			success: function(res){
				data = JSON.parse(res)
				console.log(data)
				if(data.data){
					loadDataPlanFinishing(urlTglFs, urlShiftFs, urlJointFs)
				}else{
					swal(data.msg, "", "error")
				}
			}
		})
	}

	function onChangeNourutFinishing(i)
	{
		$("#card-produksi").hide()
		$("#ehid_finishing").val("")
		$("#ehid_plan_cor").val("")
		let no_urut = $("#lp-nourut-fs-"+i).val();
		(no_urut < 0 || no_urut == "") ? no_urut = 0 : no_urut = no_urut;
		$("#lp-nourut-fs-"+i).val(no_urut)
		
		$.ajax({
			url: '<?php echo base_url('Plan/onChangeNourutFinishing')?>',
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
					loadListPlanFinishing('','','','')
				}else{
					swal(data.msg, "", "error")
				}
			}
		})
	}

	function hitungProduksiFinishing()
	{
		let rp = new Intl.NumberFormat('id-ID', {styles: 'currency', currency: 'IDR'})
		let good_fs = $("#good_fs").val().split('.').join('');
		(good_fs < 0 || good_fs == 0 || good_fs == '') ? good_fs = 0 : good_fs = good_fs;
		$("#good_fs").val(rp.format(good_fs));

		let bad_fs = $("#bad_fs").val().split('.').join('');
		(bad_fs < 0 || bad_fs == 0 || bad_fs == '') ? bad_fs = 0 : bad_fs = bad_fs;
		$("#bad_fs").val(rp.format(bad_fs));

		let bad_b_fs = $("#bad_b_fs").val().split('.').join('');
		(bad_b_fs < 0 || bad_b_fs == 0 || bad_b_fs == '') ? bad_b_fs = 0 : bad_b_fs = bad_b_fs;
		$("#bad_b_fs").val(rp.format(bad_b_fs));

		let hitung = parseInt(good_fs) + parseInt(bad_fs) + parseInt(bad_b_fs);
		$("#total_fs").val(rp.format(hitung));

	}

	function produksiPlanFinishing(id_fs)
	{
		let good_flexo = $("#good_flexo").val().split('.').join('')
		let good_fs = $("#good_fs").val().split('.').join('')
		let bad_fs = $("#bad_fs").val().split('.').join('')
		let bad_b_fs = $("#bad_b_fs").val().split('.').join('')
		let total_fs = $("#total_fs").val().split('.').join('')
		let ket_fs = $("#ket_fs").val()
		let tgl_fs = $("#tgl_fs").val()
		let start_fs = $("#start_fs").val()
		let end_fs = $("#end_fs").val()

		if(good_fs < 0 || good_fs == 0 || good_fs == '' || total_fs < 0 || total_fs == 0 || total_fs == ''){
			swal("DATA PRODUKSI TIDAK BOLEH KOSONG!", "", "error")
			return
		}
		if(tgl_fs == '' || start_fs == '' || end_fs == ''){
			swal("TANGGAL/START/END TIDAK BOLEH KOSONG!", "", "error")
			return
		}

		$.ajax({
			url: '<?php echo base_url('Plan/produksiPlanFinishing')?>',
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
				id_fs, good_flexo, good_fs, bad_fs, bad_b_fs, total_fs, ket_fs, tgl_fs, start_fs, end_fs
			}),
			success: function(res){
				data = JSON.parse(res)
				if(data.data){
					loadPlanFlexo('not')
					plhPlanFlexo(id_fs)
				}else{
					swal(data.msg, "", "error");
				}
			}
		})
	}

	function clickDonePlanCorFlexoFs(id_plan_flexo)
	{
		$.ajax({
			url: '<?php echo base_url('Plan/clickDonePlanCorFlexoFs')?>',
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
			data: ({ id_plan_flexo }),
			success: function(res){
				data = JSON.parse(res)
				console.log(data)
				if(data.data){
					loadDataPlanFinishing(urlTglFs, urlShiftFs, urlJointFs)
				}else{
					swal(data.msg, "", "error")
				}
			}
		})
	}

	function riwayatFinishing(id_plan = '', id_flexo = '')
	{
		$("#riwayat-fs").html(``)
		$.ajax({
			url: '<?php echo base_url('Plan/riwayatFinishing')?>',
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
				id_plan, id_flexo
			}),
			success: function(res){
				$("#riwayat-fs").html(res)
				// swal.close()
				loadDataAllPlanFinishing()
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

</script>
