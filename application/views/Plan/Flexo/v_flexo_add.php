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
					<div id="list-input-flexo"></div>
					<div id="riwayat-flexo"></div>
				</div>

				<div class="col-md-7">
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
								<input type="date" id="tgl" class="form-control" onchange="plhShiftMesin()">
							</div>
						</div>
						<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
							<div class="col-md-2">SHIFT</div>
							<div class="col-md-10">
								<select id="shift" class="form-control select2" onchange="plhShiftMesin()">
									<option value="">PILIH</option>
									<option value="1">1</option>
									<option value="2">2</option>
									<option value="3">3</option>
								</select>
							</div>
						</div>
						<div class="card-body row" style="padding:0 20px 20px;font-weight:bold">
							<div class="col-md-2">MESIN</div>
							<div class="col-md-10">
								<select id="mesin" class="form-control select2" onchange="plhShiftMesin()">
									<option value="">PILIH</option>
									<option value="FLEXO1">FLEXO 1</option>
									<option value="FLEXO2">FLEXO 2</option>
									<option value="FLEXO3">FLEXO 3</option>
									<option value="FLEXO4">FLEXO 4</option>
								</select>
							</div>
						</div>
						<div class="card-body row" style="padding:0 20px 5px;font-weight:bold">
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
						<div class="card-body row" style="padding:0 20px 20px;font-weight:bold">
							<div class="col-md-12">
								<button type="button" class="btn btn-success btn-block" onclick="addRencanaFlexo()"><i class="fa fa-plus"></i> <b>ADD FLEXO</b></button>
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
				<div id="modal-body-isi"></div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">

	$(document).ready(function ()
	{
		$("#list-input-flexo").load("<?php echo base_url('Plan/destroyPlanFlexo') ?>")
		$("#plan_cor").html('<option value="">PILIH</option>').prop("disabled", true)
		$('.select2').select2({
			dropdownAutoWidth: true
		})
	})

	function kosong()
	{
		$("input[type=text]").val("")
		$("#kirim").val("")
		$("#tgl_cor").val("")
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
				urlTglF: '', urlShiftF: '', urlMesinF: ''
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

	function loadListPlanFlexo(tf, sf, mf, opsi = '')
	{
		let tglF = tf
		let shiftF = sf
		let mesinF = mf
		let hidplan = ''
		
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
				}
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

	function plhShiftMesin()
	{
		let tgl = $("#tgl").val()
		let shift = $("#shift").val()
		let mesin = $("#mesin").val()
		if(tgl == "" || shift == "" || mesin == ""){
			$("#plan_cor").html('<option value="">PILIH</option>').prop("disabled", true)
		}else{
			$("#tgl").val(tgl).prop('disabled', true)
			$("#shift").html(`<option value="${shift}">${shift}</option>`).prop('disabled', true)
			$("#mesin").html(`<option value="${mesin}">${mesin}</option>`).prop('disabled', true)
			loadPlanCor('')
		}
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
			data: ({ mesin, opsi, urlTglF: '', urlShiftF: '', urlMesinF: '' }),
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
				swal.close()
			}
		})
	}

	function plhPlanCor(opsi = '')
	{
		let rupiah = new Intl.NumberFormat('id-ID', {styles: 'currency', currency: 'IDR'});
		let opNoWo = $('#plan_cor option:selected').attr('op-no-wo')
		let opNoPo = $('#plan_cor option:selected').attr('op-no-po')
		let opCustomer = $('#plan_cor option:selected').attr('op-customer')
		let opKodeMc = $('#plan_cor option:selected').attr('op-kode-mc')
		let opItem = $('#plan_cor option:selected').attr('op-item')
		let opUkBox = $('#plan_cor option:selected').attr('op-uk-box')
		let opUkSheet = $('#plan_cor option:selected').attr('op-uk-sheet')
		let opCreasing1 = $('#plan_cor option:selected').attr('op-creasing-1')
		let opCreasing2 = $('#plan_cor option:selected').attr('op-creasing-2')
		let opCreasing3 = $('#plan_cor option:selected').attr('op-creasing-3')
		let opKualitas = $('#plan_cor option:selected').attr('op-kualitas')
		let opFlute = $('#plan_cor option:selected').attr('op-flute')
		let opTipeBox = $('#plan_cor option:selected').attr('op-tipe-box')
		let opSambungan = $('#plan_cor option:selected').attr('op-sambungan')
		let opBbBox = $('#plan_cor option:selected').attr('op-bb-box')
		let opLbBox = $('#plan_cor option:selected').attr('op-lb-box')
		let opPanjangPlan = $('#plan_cor option:selected').attr('op-panjang-plan')
		let opLebarPlan = $('#plan_cor option:selected').attr('op-lebar-plan')
		let opOrderSo = $('#plan_cor option:selected').attr('op-order-so')
		let opKirim = $('#plan_cor option:selected').attr('op-kirim')
		let opTglCor = $('#plan_cor option:selected').attr('op-tgl-cor');
		let opQtyCor = $('#plan_cor option:selected').attr('op-qty-cor');
		
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
		
		// G S GS DS D
		let optSambungan = ''
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

		$("#next_flexo").html(optSambungan)

		let idx_id_plan_cor = $('#plan_cor').val()
		console.log(idx_id_plan_cor)
		riwayatFlexo(idx_id_plan_cor)
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
				swal.close()
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

	function addRencanaFlexo()
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

		if(plan_cor == ""){
			toastr.error('<b>PILIH PLAN COR DAHULU!</b>');
			return
		}
		if(next_flexo == ""){
			toastr.error('<b>PILIH NEXT FLEXO DAHULU!</b>');
			return
		}

		$.ajax({
			url: '<?php echo base_url('Plan/addRencanaFlexo')?>',
			type: "POST",
			data: ({
				tgl, shift, mesin, plan_cor ,no_wo ,no_po ,customer ,kode_mc ,item ,uk_box ,uk_sheet ,creasing_1 ,creasing_2 ,creasing_3 ,kualitas ,flute ,tipe_box ,sambungan ,bb_box ,lb_box ,panjang_plan ,lebar_plan ,order_so ,kirim ,tgl_cor ,qty_cor, next_flexo, opsi: 'add'
			}),
			success: function(res){
				data = JSON.parse(res)
				if(data.data){
					ListInputFlexo()
					kosong()
					loadPlanCor('')
				}else{
					swal(data.isi, "", "error")
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

</script>
