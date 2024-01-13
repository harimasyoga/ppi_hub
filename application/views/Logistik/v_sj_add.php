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

	<section class="content" style="padding-bottom:30px">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<div class="card card-secondary card-outline">
						<div class="card-header" style="padding:12px">
							<h3 class="card-title" style="font-weight:bold;font-size:18px">GUDANG</h3>
						</div>
						<div class="card-body card-body-gudang" style="padding:6px">
							<div style="padding:10px;text-align:center">
								<i class="fas fa-2x fa-sync-alt fa-spin"></i>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="card card-info card-outline">
						<input type="hidden" id="hidden-card-body-rk">
						<div class="card-header" style="padding:12px">
							<h3 class="card-title" style="font-weight:bold;font-size:18px">RENCANA KIRIM</h3> &nbsp;&nbsp;<span style="font-style:italic">- <?php echo strtoupper($this->m_fungsi->getHariIni(date('Y-m-d'))) ?>, <?php echo strtoupper($this->m_fungsi->tanggal_format_indonesia(date('Y-m-d'))) ?></span>
						</div>
						<div class="card-body" style="padding:0">
							<div class="card-body-rk" style="padding:6px;overflow:auto;white-space:nowrap"></div>
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="card card-success card-outline">
						<div class="card-header" style="padding:12px">
							<h3 class="card-title" style="font-weight:bold;font-size:18px">PENGIRIMAN</h3>
						</div>
						<div class="card-body" style="padding:6px">
							<input type="date" id="tgl_kirim" class="form-control" style="width:200px;margin-bottom:6px" value="<?php echo date('Y-m-d')?>" onchange="listPengiriman()">
							<div class="card-body-pengiriman" style="overflow:auto;white-space:nowrap"></div>
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
			<div class="modal-body" style="overflow:auto;white-space:nowrap"></div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$("#hidden-card-body-rk").load("<?php echo base_url('Logistik/destroyGudang') ?>")
		loadPilihanSJ()
		$('.select2').select2({
			dropdownAutoWidth: true
		})
	})

	function loadPilihanSJ() {
		$.ajax({
			url: '<?php echo base_url('Logistik/loadPilihanSJ') ?>',
			type: "POST",
			success: function(res) {
				$(".card-body-gudang").html(res)
				listRencanaKirim()
			}
		})
	}

	function pilihanSJ(opsi) {
		$(".gd-link-pilihan").removeClass('terpilih')
		$.ajax({
			url: '<?php echo base_url('Logistik/pilihanSJ') ?>',
			type: "POST",
			data: ({ opsi }),
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
			success: function(res) {
				$(".plh-"+opsi).addClass('terpilih')
				$("#tampilPilihan-"+opsi).html(res)
				swal.close()
			}
		})
	}

	function loadSJItems(gd_id_pelanggan) {
		$(".iitemss").html('')
		$.ajax({
			url: '<?php echo base_url('Logistik/loadSJItems') ?>',
			type: "POST",
			data: ({ gd_id_pelanggan }),
			success: function(res) {
				$("#tampilItems-"+gd_id_pelanggan).html(res)
			}
		})
	}

	function loadSJPO(gd_id_pelanggan, gd_id_produk) {
		$.ajax({
			url: '<?php echo base_url('Logistik/loadSJPO') ?>',
			type: "POST",
			data: ({ gd_id_pelanggan, gd_id_produk }),
			success: function(res) {
				$("#tampilPO-"+gd_id_pelanggan+"-"+gd_id_produk).html(res)
			}
		})
	}

	function loadSJIsiGudang(i, gd_id_pelanggan, gd_id_produk, kode_po) {
		$.ajax({
			url: '<?php echo base_url('Logistik/loadSJIsiGudang') ?>',
			type: "POST",
			data: ({ gd_id_pelanggan, gd_id_produk, kode_po }),
			success: function(res) {
				$("#tampilGudang-"+i+"-"+gd_id_pelanggan+"-"+gd_id_produk).html(res)
			}
		})
	}

	function hitungSJTonase(id_gudang) {
		let rp = new Intl.NumberFormat('id-ID', {styles: 'currency', currency: 'IDR'})
		let qty = $("#hidden-qty-"+id_gudang).val()
		let muat = $("#inp-muat-"+id_gudang).val().split('.').join('');
		(muat < 0 || muat == 0 || muat == '' || muat == undefined || muat.length >= 7) ? muat = 0 : muat = muat;
		(parseInt(muat) > parseInt(qty)) ? muat = 0 : muat = muat;
		$("#inp-muat-"+id_gudang).val(rp.format(muat));

		let sisa = parseInt(qty) - parseInt(muat);
		(sisa < 0 || sisa == 0 || sisa == '') ? sisa = 0 : sisa = sisa;
		$(".hitung-sisa-"+id_gudang).html(rp.format(Math.round(sisa)))

		let gd_berat_box = $("#hidden-bb-"+id_gudang).val()
		let hitung = parseInt(muat) * parseFloat(gd_berat_box);
		(hitung < 0 || hitung == 0 || hitung == '') ? hitung = 0 : hitung = hitung;
		$(".hitung-tonase-"+id_gudang).html(rp.format(Math.round(hitung)))
		$("#hidden-hitung-tonase-"+id_gudang).val(rp.format(Math.round(hitung)))
	}

	function addCartRKSJ(id_gudang) {
		let muat = $("#inp-muat-"+id_gudang).val().split('.').join('')
		let tonase = $("#hidden-hitung-tonase-"+id_gudang).val().split('.').join('')
		let idPelanggan = $("#hidden-id-pelanggan-"+id_gudang).val()
		let idProduk = $("#hidden-id-produk-"+id_gudang).val()
		let nmPelanggan = $("#hidden-nm-pelanggan-"+id_gudang).val()
		let nmProduk = $("#hidden-nm-produk-"+id_gudang).val()
		let kodePo = $("#hidden-kode-po-"+id_gudang).val()
		let bb = $("#hidden-bb-"+id_gudang).val()
		let qty = $("#hidden-qty-"+id_gudang).val()

		$.ajax({
			url: '<?php echo base_url('Logistik/addCartRKSJ')?>',
			type: "POST",
			data: ({
				id_gudang, muat, tonase, idPelanggan, idProduk, nmPelanggan, nmProduk, kodePo, bb, qty
			}),
			success: function(res){
				data = JSON.parse(res)
				if(data.data){
					toastr.success('<b>BERHASIL!</b>');
					listRencanaKirim()
				}else{
					swal(data.isi, "", "error")
					$("#inp-muat-"+id_gudang).val(0)
					$(".hitung-tonase-"+id_gudang).html('0')
					$("#hidden-hitung-tonase-"+id_gudang).val(0)
					return
				}
			}
		})
	}

	function listRencanaKirim() {
		$.ajax({
			url: '<?php echo base_url('Logistik/listRencanaKirim')?>',
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
				$(".card-body-rk").html(res)
				listPengiriman()
			}
		})
	}

	function hapusCartRKSJ(rowid){
		$.ajax({
			url: '<?php echo base_url('Logistik/hapusCartRKSJ')?>',
			type: "POST",
			data: ({
				rowid
			}),
			success: function(res){
				listRencanaKirim()
			}
		})
	}

	function simpanCartRKSJ() {
		$("#simpan_rk").prop('disabled', true)
		$.ajax({
			url: '<?php echo base_url('Logistik/simpanCartRKSJ')?>',
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
				data = JSON.parse(res)
				$(".card-body-rk").html('')
				$("#hidden-card-body-rk").load("<?php echo base_url('Logistik/destroyGudang') ?>")
				loadPilihanSJ()
				swal.close()
			}
		})
	}

	function hitungListRK(id_rk) {
		let rp = new Intl.NumberFormat('id-ID', {styles: 'currency', currency: 'IDR'})

		let sisa = $("#rk-hidden-sisa-"+id_rk).val()
		let qty_muat_lama = $("#rk-hidden-muat-lama-"+id_rk).val()
		let qty_muat = $("#rk-qty-muat-"+id_rk).val().split('.').join('');
		(qty_muat < 0 || qty_muat == 0 || qty_muat == '' || qty_muat == undefined || qty_muat.length >= 7) ? qty_muat = 0 : qty_muat = qty_muat;
		(parseInt(qty_muat) > (parseInt(sisa) + parseInt(qty_muat_lama))) ? qty_muat = 0 : qty_muat = qty_muat;
		$("#rk-qty-muat-"+id_rk).val(rp.format(qty_muat))

		let sisa_baru = (parseInt(sisa) + parseInt(qty_muat_lama)) - parseInt(qty_muat);
		(sisa_baru < 0 || sisa_baru == 0 || sisa_baru == '' || sisa_baru == undefined || sisa_baru.length >= 7) ? sisa_baru = 0 : sisa_baru = sisa_baru;
		$(".rk-span-sisa-"+id_rk).html(rp.format(sisa_baru))
		
		let bb = $("#rk-hidden-bb-"+id_rk).val()
		let tonase = parseInt(qty_muat) * parseFloat(bb);
		(tonase < 0 || tonase == 0 || tonase == '') ? tonase = 0 : tonase = tonase;
		$(".rk-span-ton-"+id_rk).html(rp.format(Math.round(tonase)))
		$("#rk-hidden-h-ton-"+id_rk).val(Math.round(tonase))
	}

	function editListUrutRK(id_rk) {
		let urut = $("#rk-urut-"+id_rk).val()
		$.ajax({
			url: '<?php echo base_url('Logistik/editListUrutRK')?>',
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
			data: ({ id_rk, urut }),
			success: function(res){
				data = JSON.parse(res)
				if(data.data){
					loadPilihanSJ()
				}else{
					swal(data.msg, '', 'error')
				}
			}
		})
	}

	function editListRencanaKirim(id_rk) {
		let muat = $("#rk-qty-muat-"+id_rk).val().split('.').join('')
		let tonase= $("#rk-hidden-h-ton-"+id_rk).val()
		$.ajax({
			url: '<?php echo base_url('Logistik/editListRencanaKirim')?>',
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
			data: ({ id_rk, muat, tonase }),
			success: function(res){
				data = JSON.parse(res)
				if(data.data){
					loadPilihanSJ()
					swal.close()
				}else{
					swal(data.msg, '', 'error')
				}
			}
		})
	}

	function hapusListRencanaKirim(id_rk) {
		$.ajax({
			url: '<?php echo base_url('Logistik/hapusListRencanaKirim')?>',
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
			data: ({ id_rk }),
			success: function(res){
				data = JSON.parse(res)
				if(data.data){
					loadPilihanSJ()
					swal.close()
				}else{
					swal('terjadi kesalahan!', '', 'error')
				}
			}
		})
	}

	function selesaiMuat(urut) {
		$.ajax({
			url: '<?php echo base_url('Logistik/selesaiMuat')?>',
			type: "POST",
			data: ({ urut }),
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
				listRencanaKirim()
				// listPengiriman()
			}
		})
	}

	function listPengiriman() {
		let tgl_kirim = $("#tgl_kirim").val()
		$.ajax({
			url: '<?php echo base_url('Logistik/listPengiriman')?>',
			type: "POST",
			data: ({ tgl_kirim }),
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
				$(".card-body-pengiriman").html(res)
				swal.close()
			}
		})
	}

	function btnBatalPengiriman(tgl, urut) {
		$.ajax({
			url: '<?php echo base_url('Logistik/btnBatalPengiriman')?>',
			type: "POST",
			data: ({ tgl, urut }),
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
				listRencanaKirim()
			}
		})
	}

	function addPengirimanNoPlat(tgl, urut){
		let plat = $("#pp-noplat-"+urut).val()
		$.ajax({
			url: '<?php echo base_url('Logistik/addPengirimanNoPlat')?>',
			type: "POST",
			data: ({ tgl, urut, plat }),
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
				listRencanaKirim()
			}
		})
	}

	function editPengirimanNoSJ(id_pl) {
		let no_surat = $("#pp-nosj-"+id_pl).val()
		if(no_surat.length == 0){
			swal('NO. SJ TIDAK BOLEH KOSONG!', '', 'error')
			return
		}else if(no_surat.length < 3){
			swal('NO. SJ MINIMAL TIGA DIGIT!', '', 'error')
			return
		}else if(no_surat.length > 4){
			swal('NO. SJ MAKSIMAL EMPAT DIGIT!', '', 'error')
			return
		}
		$.ajax({
			url: '<?php echo base_url('Logistik/editPengirimanNoSJ')?>',
			type: "POST",
			data: ({ id_pl, no_surat }),
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
				listRencanaKirim()
			}
		})
	}
</script>
