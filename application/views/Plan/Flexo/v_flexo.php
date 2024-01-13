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

	<section class="content">
		<div class="card card-list-so">
			<div class="card-header">
				<h3 class="card-title">Flexo</h3>
				<div class="card-tools">
					<button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
					<i class="fas fa-minus"></i></button>
				</div>
			</div>
			<div class="card-body">

				<?php if(in_array($this->session->userdata('level'), ['Admin','PPIC','User'])) { ?>
					<a href="<?php echo base_url('Plan/Flexo/Add')?>" class="btn btn-info"><i class="fa fa-plus"></i> <b>Tambah Data</b></a>
					<br><br>
				<?php } ?>

				<table id="datatable" class="table table-bordered table-striped" width="100%">
					<thead>
						<tr>
							<th style="width:5%">#</th>
							<th style="width:20%">TANGGAL</th>
							<th style="width:20%">SHIFT</th>
							<th style="width:20%">MESIN</th>
							<th style="width:20%">JUMLAH</th>
							<th style="width:15%">AKSI</th>
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

			<div class="modal-body" style="overflow:auto;white-space:nowrap">
				
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function () {
		load_data()
	});

	function reloadTable() {
		table = $('#datatable').DataTable();
		tabel.ajax.reload(null, false);
	}

	function load_data() {
		let table = $('#datatable').DataTable();
		table.destroy();
		tabel = $('#datatable').DataTable({
			"processing": true,
			"pageLength": true,
			"paging": true,
			"ajax": {
				"url": '<?php echo base_url('Plan/LoaDataFlexo')?>',
				"type": "POST",
			},
			"aLengthMenu": [
				[5, 10, 15, 20, -1],
				[5, 10, 15, 20, "Semua"]
			],	
			responsive: true,
			"pageLength": 10,
			"language": {
				"emptyTable": "Tidak ada data.."
			}
		})
	}
</script>
