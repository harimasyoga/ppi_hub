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

				<div class="col-md-4">
					<div class="card card-info card-outline">
						<div class="card-header" style="padding:12px">
							<h3 class="card-title" style="font-weight:bold;font-size:18px">COR</h3>
						</div>
						<!-- <div id="card-body-cor" style="overflow:auto;white-space:nowrap"> -->
						<div id="card-body-cor">
							<div style="padding:20px;text-align:center;font-size:8px;background:#bbb">
								<i class="fas fa-3x fa-sync-alt fa-spin"></i>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="card card-info card-outline">
						<div class="card-header" style="padding:12px">
							<h3 class="card-title" style="font-weight:bold;font-size:18px">FLEXO</h3>
						</div>
						<div id="card-body-flexo">
							<div style="padding:20px;text-align:center;font-size:8px;background:#bbb">
								<i class="fas fa-3x fa-sync-alt fa-spin"></i>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="card card-info card-outline">
						<div class="card-header" style="padding:12px">
							<h3 class="card-title" style="font-weight:bold;font-size:18px">FINISHING</h3>
						</div>
						<div id="card-body-finishing">
							<div style="padding:20px;text-align:center;font-size:8px;background:#bbb">
								<i class="fas fa-3x fa-sync-alt fa-spin"></i>
							</div>
						</div>
					</div>
				</div>

				<div class="col-md-4">
					<div class="card card-secondary card-outline">
						<div class="card-header" style="padding:12px">
							<h3 class="card-title" style="font-weight:bold;font-size:18px">TIME LINE</h3>
						</div>
						<div class="card-body row" style="padding:6px 7px">
							<div class="col-md-12">
								<div id="list-timeline">TIMELINE</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-8">
					<div class="card card-secondary card-outline">
						<div class="card-header" style="padding:12px">
							<h3 class="card-title" style="font-weight:bold;font-size:18px">PRODUKSI</h3>
						</div>
						<div class="card-body row" style="padding:6px 7px">
							<div class="col-md-12">
								<div id="list-produksi">LIST</div>
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
			<div class="modal-body" style="overflow:auto;white-space:nowrap"></div>
		</div>
	</div>
</div>

<script type="text/javascript">

	$(document).ready(function ()
	{
		loadGudang('cor', '', '')
		$('.select2').select2({
			dropdownAutoWidth: true
		})
	})

	function loadGudang(opsi, id_pelanggan, id_produk)
	{
		$(".bgtd").attr('style', 'padding:6px;border-width:0 0 1px;background:#fff')
		$.ajax({
			url: '<?php echo base_url('Logistik/loadGudang')?>',
			type: "POST",
			data: ({
				opsi, id_pelanggan, id_produk
			}),
			success: function(res){
				data = JSON.parse(res)
				console.log(data)

				let htmlGG = ''
				let bgTd = ''
				htmlGG += `<table class="table table-bordered" style="margin:0;border:0">
					<thead>`
						if(data.data.length == 0){
							htmlGG += `<tr><td style="padding:6px;border-width:0 0 1px;font-weight:bold">PRODUKSI KOSONG!</td></tr>`
						}else{
							data.data.forEach(loadHtml)
							function loadHtml(r, index) {
								if(data.opsi == opsi && id_pelanggan == r.gd_id_pelanggan && id_produk == r.gd_id_produk){
									bgTd = ';background:#ffd700;color:#000;font-weight:bold'
								}else{
									bgTd = ';background:#fff'
								}

								htmlGG += `<tr>
									<td class="bgtd" style="padding:6px;border-width:0 0 1px${bgTd}">
										<a href="javascript:void(0)" style="color:#212529" onclick="loadGudang('${opsi}', ${r.gd_id_pelanggan}, ${r.gd_id_produk})">
											${r.nm_pelanggan} - ${r.nm_produk}
										</a>
										<span id="h_span_${opsi}_${r.gd_id_pelanggan}_${r.gd_id_produk}" class="bg-primary" style="vertical-align:top;font-weight:bold;padding:2px 4px;font-size:12px;border-radius:4px">${r.jml}</span>
									</td>
								</tr>`
							}
						}
					htmlGG += `</thead>
				</table>`
				$("#card-body-"+data.opsi).html(htmlGG)

				if(opsi == 'cor' && id_pelanggan == '' && id_produk == ''){
					loadGudang('flexo', '', '')
				}else if(opsi == 'flexo' && id_pelanggan == '' && id_produk == ''){
					loadGudang('finishing', '', '')
				}

				if(data.opsi != '' && data.id_pelanggan != '' && data.id_pelanggan != ''){
					loadListProduksiPlan(data.opsi, data.id_pelanggan, data.id_produk)
				}
			}
		})
	}

	function loadListProduksiPlan(opsi, id_pelanggan, id_produk)
	{
		$("#list-produksi").html(`<div style="padding:6px;text-align:center;font-size:6px">
			<i class="fas fa-3x fa-sync-alt fa-spin"></i>
		</div>`)
		$("#list-timeline").html('TIMELINE')
		$.ajax({
			url: '<?php echo base_url('Logistik/loadListProduksiPlan')?>',
			type: "POST",
			data: ({
				opsi, id_pelanggan, id_produk
			}),
			success: function(res){
				$("#list-produksi").html(res)
			}
		})
	}

	function clickHasilProduksiPlan(opsi, id_pelanggan, id_produk, no_po, i)
	{
		$.ajax({
			url: '<?php echo base_url('Logistik/clickHasilProduksiPlan')?>',
			type: "POST",
			data: ({
				opsi, id_pelanggan, id_produk, no_po, i
			}),
			success: function(res){
				$("#isi-list-gudang-"+i).html(res)
				timeline(opsi, id_pelanggan, id_produk, no_po)
			}
		})
	}

	function timeline(opsi, id_pelanggan, id_produk, no_po)
	{
		$("#list-timeline").html(`<div style="padding:6px;text-align:center;font-size:6px">
			<i class="fas fa-3x fa-sync-alt fa-spin"></i>
		</div>`)
		$.ajax({
			url: '<?php echo base_url('Logistik/timeline')?>',
			type: "POST",
			data: ({
				opsi, id_pelanggan, id_produk, no_po
			}),
			success: function(res){
				$("#list-timeline").html(res)
			}
		})
	}

	function hitungGudang(id_gudang)
	{
		let rp = new Intl.NumberFormat('id-ID', {styles: 'currency', currency: 'IDR'})
		let good = $("#good-"+id_gudang).val().split('.').join('');
		(good < 0 || good == 0 || good == '') ? good = 0 : good = good;
		$("#good-"+id_gudang).val(rp.format(good));

		let reject = $("#reject-"+id_gudang).val().split('.').join('');
		(reject < 0 || reject == 0 || reject == '') ? reject = 0 : reject = reject;
		$("#reject-"+id_gudang).val(rp.format(reject));
	}

	function simpanGudang(id_gudang, opsi, id_pelanggan, id_produk, no_po, i)
	{
		let good = $("#good-"+id_gudang).val().split('.').join('')
		let reject = $("#reject-"+id_gudang).val().split('.').join('')
		$("#simpan_gudang"+id_gudang).prop("disabled", true)
		$.ajax({
			url: '<?php echo base_url('Logistik/simpanGudang')?>',
			type: "POST",
			data: ({
				id_gudang, good, reject, opsi, id_pelanggan, id_produk, no_po, i
			}),
			success: function(res){
				data = JSON.parse(res)
				console.log(data)
				if(data.data){
					clickHasilProduksiPlan(opsi, id_pelanggan, id_produk, no_po, i)
					let i_jml = 0;
					let h_jml = 0;
					(data.i_span == null) ? i_jml = 0 : i_jml = data.i_span.i_jml;
					(data.h_span == null) ? h_jml = 0 : h_jml = data.h_span.h_jml;
					$("#i_span"+i).html(i_jml);
					$("#h_span_"+opsi+"_"+id_pelanggan+"_"+id_produk).html(h_jml);
				}else{
					swal(data.msg, "", "error")
					$("#simpan_gudang"+id_gudang).prop("disabled", false)
				}
			}
		})
	}

</script>
