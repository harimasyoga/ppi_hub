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

	<section class="content">
		<div class="card card-list-so">
			<div class="card-header">
				<h3 class="card-title">Surat Jalan</h3>
				<div class="card-tools">
					<button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
					<i class="fas fa-minus"></i></button>
				</div>
			</div>

			<div class="card-body">

				<?php if(in_array($this->session->userdata('level'), ['Admin', 'Gudang'])) { ?>
					<a href="<?php echo base_url('Logistik/Surat_Jalan/Add')?>" class="btn btn-info"><i class="fa fa-plus"></i> <b>Tambah Data</b></a>
					<br><br>
				<?php } ?>
				
				<table id="datatable" class="table table-bordered table-striped" width="100%">
					<thead>
						<tr>
							<th>#</th>
							<th>CUSTOMER</th>
							<th>TIPE</th>
							<th>ITEM</th>
							<th>JUMLAH</th>
							<th>AKSI</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
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
			<div class="modal-body" style="overflow:auto;white-space:nowrap"></div>
		</div>
	</div>
</div>

<script type="text/javascript">
	status ="insert";

	$(document).ready(function () {
		// load_data()
	});

	// function reloadTable() {
	// 	table = $('#datatable').DataTable();
	// 	tabel.ajax.reload(null, false);
	// }

	// function load_data() {
	// 	let table = $('#datatable').DataTable();
	// 	table.destroy();
	// 	tabel = $('#datatable').DataTable({
	// 		"processing": true,
	// 		"pageLength": true,
	// 		"paging": true,
	// 		"ajax": {
	// 			"url": '<?php echo base_url('Logistik/LoaDataGudang')?>',
	// 			"type": "POST",
	// 		},
	// 		"aLengthMenu": [
	// 			[5, 10, 15, 20, -1],
	// 			[5, 10, 15, 20, "Semua"]
	// 		],	
	// 		responsive: true,
	// 		"pageLength": 10,
	// 		"language": {
	// 			"emptyTable": "Tidak ada data.."
	// 		}
	// 	})
	// }

</script>
