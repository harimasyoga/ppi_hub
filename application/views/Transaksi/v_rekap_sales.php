<div class="content-wrapper">
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
			<div class="col-sm-6" style="font-family:Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;" >
				<!-- <h1><b>Data Transaksi </b></h1> -->
			</div>
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
				<!-- <li class="breadcrumb-item active" ><a href="#"><?= $judul ?></a></li> -->
				</ol>
			</div>
			</div>
		</div>
	</section>
	
	<section class="content" style="padding-bottom:30px">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<div class="card card-info card-outline">
						<div class="card-header">
							<h3 class="card-title" style="font-weight:bold;font-style:italic">PILIH</h3>
						</div>
						<div class="card-body row" style="padding-bottom:17px;font-weight:bold">
							<div class="col-md-6" style="margin-bottom:3px">
								<button type="button" class="btn btn-block btn-info" onclick="pilihan('tgl','tanggal')"><b>TANGGAL</b></button>
							</div>
							<div class="col-md-6" style="margin-bottom:3px">
								<button type="button" class="btn btn-block btn-info" onclick="pilihan('all','semua')"><b>SEMUA</b></button>
							</div>
						</div>
					</div>

					<div class="card card-info card-outline">
						<div class="card-header">
							<h3 class="card-title" style="font-weight:bold;font-style:italic">REKAP</h3>
						</div>
						<div id="tampil-rincian"></div>
					</div>
				</div>

				<div class="col-md-12">
					<!-- <div class="card card-info card-outline"> -->
						<div class="col-md-12" id="tampil-data"></div>
					<!-- </div> -->
				</div>
			</div>
		</div>
	</section>
</div>

<div class="modal fade" id="modalFormDetail">
	<div class="modal-dialog modal-full">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="judul-detail"></h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" style="overflow:auto;white-space:nowrap">
				<div id="modal-detail-so"></div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function () {
		// load_data()
		$("#tampil-rincian").html(``)
		$("#tampil-data").html(``)
		$('.select2').select2({
			containerCssClass: "wrap",
			placeholder: '--- Pilih ---',
			dropdownAutoWidth: true
		});
	});

	// function reloadTable() {
	// 	table = $('#datatable').DataTable();
	// 	tabel.ajax.reload(null, false);
	// }

	function pilihan(ket ,opsi)
	{
		$("#tampil-data").html(``)
		if(ket == 'tgl' && opsi == 'tanggal'){
			$("#tampil-rincian").html(`
			<div class="card-body row" style="padding-bottom:20px;font-weight:bold">
				<div class="col-md-6" style="margin-bottom:4px">
					BULAN
				</div>
				<br>
				<div class="col-md-6" style="margin-bottom:4px">
					<input type="month" class="form-control " name="bulan" id="bulan">
				</div>
				<div class="col-md-12">
					<buton class="btn btn-block btn-info" onclick="tampil_data()">CARI</buton>
				</div>
			</div>
			`)
			$('.select2').select2();
		}else{
			$.ajax({
				url: '<?php echo base_url('Transaksi/hitung_rekap')?>',
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
					opsi
				}),
				success: function(res){
					$("#tampil-rincian").html(res)
					swal.close()
				}
			})
		}
	}

	function tampil_data()
	{
		var bulan = $("#bulan").val()
		$.ajax({
			url: '<?php echo base_url('Transaksi/hitung_rekap')?>',
			type: "POST",
			data: {bulan : bulan},
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
				$("#tampil-rincian").html(res)
				swal.close()
			}
		})
	}

	function kosong(){
		//
	}

	function tampilDataEtaPO(tgl)
	{
		// alert(tgl)
		$.ajax({
			url: '<?php echo base_url('Transaksi/tampilDataEtaPO')?>',
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
				tgl
			}),
			success: function(res){
				$("#tampil-data").html(res)
				swal.close()
			}
		})
	}

</script>
