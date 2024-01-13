<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<!-- <h1><b>Data Master</b></h1> -->
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<!-- <li class="breadcrumb-item active"><a href="#"><?= $judul ?></a></li> -->
					</ol>
				</div>
			</div>
		</div><!-- /.container-fluid -->
	</section>

	<!-- Main content -->
	<section class="content">
		<!-- Default box -->
		<div class="card">
			<div class="card-header" style="font-family:Cambria">
				<h3 class="card-title" style="color:#4e73df;"><b><?= $judul ?></b></h3>

				<div class="card-tools">
					<button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
						<i class="fas fa-minus"></i></button>
				</div>
			</div>
			<div class="card-body">

				<button type="button" style="font-family:Cambria;" class="tambah_data btn  btn-info pull-right"><i class="fa fa-plus"></i>&nbsp;&nbsp;<b>Tambah Data</b></button>
				<br><br>

				<table id="datatable" class="table table-bordered table-striped" width="100%">
					<thead>
						<tr>
							<th style="text-align: center; width:5%">NO.</th>
							<th style="text-align: center; width:20%">PERMINTAAN</th>
							<th style="text-align: center; width:20%">TGL MASUK</th>
							<th style="text-align: center; width:35%">SUPPLIER</th>
							<th style="text-align: center; width:10%">BERAT BERSIH</th>
							<th style="text-align: center; width:10%">AKSI</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
		<!-- /.card -->
	</section>
	<!-- /.content -->
</div>
<!-- /.content-wrapper -->

<div class="modal fade" id="modalForm">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="judul"></h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form role="form" method="post" id="myForm">
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">INPUT</label>
						<div class="col-sm-10">
							<select id="plh_input" class="form-control select2" onchange="plhInput()"></select>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">PERMINTAAN</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="pimpinan" placeholder="-" autocomplete="off" maxlength="25" oninput="this.value = this.value.toUpperCase()">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">SUPPLIER</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="supplier" placeholder="-" autocomplete="off" maxlength="25" oninput="this.value = this.value.toUpperCase()">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">ALAMAT</label>
						<div class="col-sm-10">
							<textarea class="form-control" id="alamat" placeholder="-" oninput="this.value = this.value.toUpperCase()"></textarea>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">NO POLISI</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="nopol" placeholder="-" autocomplete="off" maxlength="25" oninput="this.value = this.value.toUpperCase()">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">TGL MASUK</label>
						<div class="col-sm-10">
							<input type="datetime-local" class="form-control" id="tgl_masuk">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">TGL KELUAR</label>
						<div class="col-sm-10">
							<input type="datetime-local" class="form-control" id="tgl_keluar">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">BARANG</label>
						<div class="col-sm-10">
							<input type="text" class="angka form-control" id="nm_barang" placeholder="-" autocomplete="off" maxlength="25">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">BERAT TRUK</label>
						<div class="col-sm-10">
							<input type="text" class="angka form-control" id="bb_truk" placeholder="-" autocomplete="off" maxlength="11">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">BERAT KOTOR</label>
						<div class="col-sm-10">
							<input type="text" class="angka form-control" id="bb_kotor" placeholder="-" autocomplete="off" maxlength="11">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">BERAT BERSIH</label>
						<div class="col-sm-10">
							<input type="text" class="angka form-control" id="bb_bersih" placeholder="-" autocomplete="off" maxlength="11">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">POTONGAN</label>
						<div class="col-sm-10">
							<input type="text" class="angka form-control" id="potongan" placeholder="KG" autocomplete="off" maxlength="11">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">CATATAN</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="catatan" placeholder="-" autocomplete="off" maxlength="25" oninput="this.value = this.value.toUpperCase()">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">PENIMBANG</label>
						<div class="col-sm-10">
							<select id="nm_penimbang" class="form-control select2"></select>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">SOPIR</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="nm_supir" placeholder="-" autocomplete="off" maxlength="25" oninput="this.value = this.value.toUpperCase()">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">KETERANGAN</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="keterangan" placeholder="-" autocomplete="off" maxlength="25" oninput="this.value = this.value.toUpperCase()">
						</div>
					</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" id="btn-simpan" onclick="simpan()"><i class="fas fa-save"></i> Simpan</button>
				<button type="button" class="btn btn-danger" data-dismiss="modalForm" onclick="close_modal();" ><i class="fa fa-times-circle"></i> <b> Batal</b></button>
			</div>
			</form>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$(".select2").select2()
		load_data()
	});

	$(".tambah_data").click(function(event) {
		kosong()
		$("#modalForm").modal("show")
		$("#judul").html('<h3> Form Tambah Data</h3>')
	});

	function close_modal(){
		$('#modalForm').modal('hide');
	}

	function load_data() {
		var table = $('#datatable').DataTable();
		table.destroy();
		tabel = $('#datatable').DataTable({
			"processing": true,
			"pageLength": true,
			"paging": true,
			"ajax": {
				"url": '<?php echo base_url(); ?>Logistik/load_data/Timbangan',
				"type": "POST",
			},
			responsive: true,
			"pageLength": 10,
			"language": {
				"emptyTable": "Tidak ada data.."
			}
		});
	}

	function reloadTable() {
		table = $('#datatable').DataTable();
		tabel.ajax.reload(null, false);
	}

	function simpan() {
		let pimpinan = $("#pimpinan").val()
		let supplier = $("#supplier").val()
		let alamat = $("#alamat").val()
		let nopol = $("#nopol").val()
		let tgl_masuk = $("#tgl_masuk").val()
		let tgl_keluar = $("#tgl_keluar").val()
		let nm_barang = $("#nm_barang").val()
		let bb_kotor = $("#bb_kotor").val()
		let bb_truk = $("#bb_truk").val()
		let bb_bersih = $("#bb_bersih").val()
		let potongan = $("#potongan").val()
		let catatan = $("#catatan").val()
		let nm_penimbang = $("#nm_penimbang").val()
		let nm_supir = $("#nm_supir").val()
		let keterangan = $("#keterangan").val()
		console.log("pimpinan : ", pimpinan)
		console.log("supplier : ", supplier)
		console.log("alamat : ", alamat)
		console.log("nopol : ", nopol)
		console.log("tgl_masuk : ", tgl_masuk)
		console.log("tgl_keluar : ", tgl_keluar)
		console.log("nm_barang : ", nm_barang)
		console.log("bb_kotor : ", bb_kotor)
		console.log("bb_truk : ", bb_truk)
		console.log("bb_bersih : ", bb_bersih)
		console.log("potongan : ", potongan)
		console.log("catatan : ", catatan)
		console.log("nm_penimbang : ", nm_penimbang)
		console.log("nm_supir : ", nm_supir)
		console.log("keterangan : ", keterangan)
	}

	function kosong() {
		$("#plh_input").html(`<option value="">PILIH</option><option value="MANUAL">MANUAL</option><option value="CORR">CORR</option>`)
		$("#pimpinan").val("")
		$("#supplier").val("")
		$("#alamat").val("")
		$("#nopol").val("")
		$("#tgl_masuk").val("")
		$("#tgl_keluar").val("")
		$("#nm_barang").val("")
		$("#bb_kotor").val("")
		$("#bb_truk").val("")
		$("#bb_bersih").val("")
		$("#potongan").val("")
		$("#catatan").val("")
		$("#nm_penimbang").html(`<option value="">PILIH</option><option value="1">Feri S</option><option value="2">DWI J</option>`)
		$("#nm_supir").val("")
		$("#keterangan").val("")
		// $('input[type=text]').prop('disabled', true);
		$("#btn-simpan").show().prop("disabled", false);
	}

	function plhInput() {
		let plh_input = $("#plh_input").val()
		if(plh_input == 'CORR'){
			loadSJTimbangan()
		}else{
			kosong()
		}
	}

	function loadSJTimbangan() {
		console.log('test')
	}

	function tampil_edit(id, act) {
		status = 'update';
		$("#modalForm").modal("show");
		if (act == 'detail') {
			$("#judul").html('<h3> Detail Data</h3>');
			$("#btn-simpan").hide();
		} else {
			$("#judul").html('<h3> Form Edit Data</h3>');
			$("#btn-simpan").show();
		}

	}

	function deleteData(id) {

	}
</script>
