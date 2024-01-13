<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6" style="font-family:Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;" >
					<!-- <h1><b>Data Logistik</b> </h1> -->
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
		<div class="card shadow mb-3">
			<div class="card-header" style="font-family:Cambria;" >
				<h3 class="card-title" style="color:#4e73df;"><b><?= $judul ?></b></h3>

				<div class="card-tools">
					<button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
						<i class="fas fa-minus"></i></button>
				</div>
			</div>
			<div class="card-body">
				<?php if (!in_array($this->session->userdata('username'), ['bumagda'])) { ?>
				<a href="<?= base_url('Logistik/Invoice_add')?>" class="btn btn-info"><i class="fa fa-plus"></i> <b>Tambah Data</b></a>
					<br>
				<?php } ?>
				<br>
				<table id="datatable" class="table table-bordered table-striped table-scrollable" width="100%">
					<thead>
						<tr>
							<th style="text-align: center; width:5%">No</th>
							<th style="text-align: center; width:10%">Tanggal</th>
							<th style="text-align: center; width:10%">No Invoice</th>
							<th style="text-align: center; width:10%">No SJ</th>
							<th style="text-align: center; width:10%">Kepada</th>
							<th style="text-align: center; width:10%">Perusahaan</th>
							<th style="text-align: center; width:10%">Jatuh Tempo</th>
							<th style="text-align: center; width:5%">Admin</th>
							<th style="text-align: center; width:5%">Owner</th>
							<th style="text-align: center; width:15%">Total</th>
							<th style="text-align: center; width:10%;">Aksi</th>
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


<!-- modal keterangan -->
<div class="modal fade" id="modal_acc">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="judul2"></h4>
			</div>
			<div class="modal-body">
				<table border="0">
					<tr>
						<td width="35%" ><h3>No PO</h3></td>
						<td width="10%" ><h3> : </h3></td>
						<td width="55%"  id="nopo_ket"></td>
					</tr>
					<tr>
						<td width="35%" ><h3>Status</h3></td>
						<td width="10%" ><h3> : </h3></td>
						<td width="55%"  id="status_acc"></td>
					</tr>
					<tr>
						<td><h3>Tanggal Verifikasi</h3></td>
						<td><h3> : </h3></td>
						<td id="tgl_ket"></td>
					</tr>
					<tr id='alasan' ></tr>
					
				</table>
			</div>
		</div>
	</div>
</div>
<!-- end modal keterangan -->

<script type="text/javascript">
	rowNum = 0;
	$(document).ready(function() {
		load_data();
		// getMax();
		$('.select2').select2({
			containerCssClass: "wrap",
			placeholder: '--- Pilih ---',
			dropdownAutoWidth: true
		});
	});

	status = "insert";
	$(".tambah_data").click(function(event) 
	{
		kosong();
		$("#modalForm").modal("show");
		$("#judul").html('<h3> Form Tambah Data</h3>');
		status = "insert";
		$("#status").val("insert");
		$("#id_produk0").val("").prop("disabled", true).html(`<option value="">-- Pilih --</option>`);
	});

	function close_modal(){
		$('#modalForm').modal('hide');
	}

	function setProduk(cek,pelanggan,id) 
	{
		if(cek=='new'){
			clearRow();
		}
		if (status == 'insert' ){

			$("#id_produk"+id).val("").prop("disabled", false);
			if (pelanggan!=''){
				option = "";
				$.ajax({
					type: 'POST',
					url: "<?= base_url(); ?>Logistik/load_produk",
					data: { idp: pelanggan, kd: '' },
					dataType: 'json',
					beforeSend: function() {
						swal({
						title: 'loading ...',
						allowEscapeKey    : false,
						allowOutsideClick : false,
						onOpen: () => {
							swal.showLoading();
						}
						})
					},
					success:function(data){			
						if(data.message == "Success"){						
							option = "<option>-- Pilih --</option>";
							$.each(data.data, function(index, val) {
							option += "<option value='"+val.id_produk+"'>"+val.nm_produk+"</option>";
							});
		
							$('#id_produk'+id).html(option);
							swal.close();
						}else{	
							option += "<option value=''></option>";
							$('#id_produk'+id).html(option);						
							$("#txt_detail_produk"+id).html("");	
							swal.close();
						}
					}
				});
			}
		}
		
	}

	function load_data() 
	{


		var table = $('#datatable').DataTable();

		table.destroy();

		tabel = $('#datatable').DataTable({

			"processing": true,
			"pageLength": true,
			"paging": true,
			"ajax": {
				"url": '<?= base_url(); ?>Logistik/load_data/Invoice',
				"type": "POST",
				// data  : ({tanggal:tanggal,tanggal_akhir:tanggal_akhir,id_kategori:id_kategori1,id_sub_kategori:id_sub_kategori1}), 
			},
			"aLengthMenu": [
				[10, 15, 20, 25, -1],
				[10, 15, 20, 25, "Semua"] // change per page values here
			],		

			responsive: true,
			"pageLength": 10,
			"language": {
				"emptyTable": "Tidak ada data.."
			}
		});

	}

	function reloadTable() 
	{
		table = $('#datatable').DataTable();
		tabel.ajax.reload(null, false);
	}
	
	function simpan() 
	{
		// show_loading();
		swal({
			title: 'loading ...',
			allowEscapeKey    : false,
			allowOutsideClick : false,
			onOpen: () => {
				swal.showLoading();
			} 
		})
		id_pelanggan    = $("#id_pelanggan").val();
		kode_po         = $("#kode_po").val();
		// eta             = $("#eta").val();
		sales           = $("#id_sales").val();

		if (id_pelanggan == '' || kode_po == '' || sales=='' ) {
			// toastr.info('Harap Lengkapi Form');
			
			swal.close();
			swal({
				title               : "Cek Kembali",
				html                : "Harap Lengkapi Form Dahulu",
				type                : "info",
				confirmButtonText   : "OK"
			});
			// close_loading();
			return;
		}

		arr_produk = [];
		for (var i = 0; i <= rowNum; i++) {

			produk   = $("#id_produk" + i).val();
			qty      = $("#qty" + i).val();
			p11      = $("#p11" + i).val();
			eta_item = $("#eta_item" + i).val();

			if (produk == '' || qty == '' || qty == '0' || eta_item == '') {
				// toastr.info('Harap Lengkapi Form');
				// return;
				
				swal.close();
				swal({
					title               : "Cek Kembali",
					html                : "Harap Lengkapi Form Dahulu",
					type                : "info",
					confirmButtonText   : "OK"
				});
				// close_loading();
				return;
			}

			arr_produk.push(produk);
		}
		let findDuplicates = arr => arr.filter((item, index) => arr.indexOf(item) != index)

		if (findDuplicates(arr_produk).length > 0) {
			// toastr.info('Tidak boleh ada produk yang sama');
			// return;
			
			swal.close();
			swal({
				title               : "Cek Kembali",
				html                : "Tidak boleh ada produk yang sama",
				type                : "info",
				confirmButtonText   : "OK"
			});
			// close_loading();
			return;
		}

		// console.log($('#myForm').serialize());

		$.ajax({
			url        : '<?= base_url(); ?>Logistik/insert',
			type       : "POST",
			data       : $('#myForm').serialize(),
			dataType   : "JSON",
			success: function(data) {
				if (data) {
					// toastr.success('Berhasil Disimpan');
					swal.close();
					swal({
						title               : "Data",
						html                : "Berhasil Disimpan",
						type                : "success",
						confirmButtonText   : "OK"
					});
					// close_loading();
					kosong();
					$("#modalForm").modal("hide");
				} else {
					// toastr.error('Gagal Simpan');
					swal.close();
					swal({
						title               : "Cek Kembali",
						html                : "Gagal Simpan",
						type                : "error",
						confirmButtonText   : "OK"
					});
					// close_loading();
					return;
				}
				reloadTable();
			},
			error: function(jqXHR, textStatus, errorThrown) {
				// toastr.error('Terjadi Kesalahan');
				
				swal.close();
				swal({
					title               : "Cek Kembali",
					html                : "Terjadi Kesalahan",
					type                : "error",
					confirmButtonText   : "OK"
				});
				// close_loading();
				return;
			}
		});

	}

	function kosong(c = '') 
	{
		$("#tgl_po").val("<?= date('Y-m-d') ?>");

		if (c != 's') {
			// getMax();

		}
		$("#btn-print").hide();

		$("#id_pelanggan").select2("val", "");
		$('#id_pelanggan').val("").trigger('change');		
		$("#id_pelanggan").prop("disabled", false);

		$("#kode_po").val("");
		// $("#eta").val("");

		$("#txt_kota").val("");
		$("#txt_no_telp").val("");
		$("#txt_fax").val("");
		$("#txt_top").val("");
		$("#txt_marketing").val("");

		clearRow();
		status = 'insert';
		$("#status").val(status);

		$("#btn-simpan").show();
		// $("#btn-simpan-plan").show();
		$("#btn-verif_acc").hide();
		$("#btn-verif_hold").hide();
		$("#btn-verif_r").hide();

		$(".btn-tambah-produk").show();
		$('#removeRow').show();
		$("#header_del").show();
	}

	function btn_verif(data)
	{
		
		$(".btn-verif").hide()
		// $("#btn-simpan-plan").hide()

		if (data[0].status == 'Open' || data[0].status == 'Reject') {
			if ('<?= $this->session->userdata('level') ?>' == 'Admin'){
				$(".btn-verif").show()
			}

			if ('<?= $this->session->userdata('level') ?>' == 'Marketing' && ( data[0].status_app1 == 'N' || data[0].status_app1 == 'H' || data[0].status_app1 == 'R'  ) ) 
			{
				$(".btn-verif").show()
			}

			if ('<?= $this->session->userdata('level') ?>' == 'PPIC' && data[0].status_app1 == 'Y' && ( data[0].status_app2 == 'N' || data[0].status_app2 == 'H' || data[0].status_app2 == 'R' ) ) 
			{
				$(".btn-verif").show()
				// $("#btn-simpan-plan").show()
			}

			if ('<?= $this->session->userdata('level') ?>' == 'Owner' && data[0].status_app1 == 'Y' && data[0].status_app2 == 'Y'  && ( data[0].status_app3 == 'N' || data[0].status_app3 == 'H' || data[0].status_app3 == 'R' ) ) 
			{
				$(".btn-verif").show()
			}
		}
		

	}

	var no_po = ''

	function tampil_edit(id, act) 
	{
		// kosong('s');
		kosong();
		var cek = '<?= $this->session->userdata('level') ?>';
		$(".btn-tambah-produk").hide();
		$("#btn-print").show();
		
		$("#status").val("update");
		status    = 'update';

		$("#modalForm").modal("show");
		if (act == 'detail') {
			$("#judul").html('<h3> Detail Data</h3>');
			$("#btn-simpan").hide();
		} else {
			$("#judul").html('<h3> Form Edit Data</h3>');
			$("#btn-simpan").show();
		} 

		status = "update";

		$.ajax({
				url: '<?= base_url('Logistik/get_edit'); ?>',
				type: 'POST',
				data: {
					id       : id,
					jenis    : "trs_po",
					field    : 'id'
				},
				dataType: "JSON",
			})
			.done(function(data) {
				
				btn_verif(data)
				no_po = data[0].no_po

				$("#no_po").val(data[0].no_po);
				$("#tgl_po").val(data[0].tgl_po);
				
				$('#id_pelanggan').val(data[0].id_pelanggan).trigger('change');

				kodepo    = (data[0].kode_po == '' ) ? '-' : data[0].kode_po ;
				
				$("#kode_po").val(kodepo);
				// $("#eta").val(eta); 
				
				$("#header_del").hide();

				if (cek !='PPIC')
				{
					$("#header_p11").show();
				}else{
					$("#header_p11").hide();
				}

				$.each(data, function(index, value) {
					eta       = (value.eta == '' ) ? '-' : value.eta ;
					
					$("#detail-hapus-0").hide();
					$("#detail-hapus-"+index).hide();
					$("#btn-hapus-"+index).hide();
					$("#eta_item"+index).val(eta); 
					
					if (cek !='PPIC')
					{
						$("#p11_det"+index).show();
						$("#id_pelanggan").prop("disabled", false);
						$("#kode_po").prop("disabled", false);
						$("#eta_item"+index).prop("disabled", false); 

						$('#subs'+index).hide();
						$('#subs_i'+index).hide();
						$('#subs_hitung'+index).hide();
						$('#subs_hasil_hitung'+index).hide();
						
					}else{
						$("#p11_det"+index).hide();
						$("#id_pelanggan").prop("disabled", true);
						$("#kode_po").prop("disabled", true);
						$("#eta_item"+index).prop("disabled", true); 

						$('#subs'+index).show();
						$('#subs_i'+index).show();
						$('#subs_hitung'+index).show();
						$('#subs_hasil_hitung'+index).show();
						
					}
					
					
					
					var opt_produk = $("<option selected></option>").val(value.id_produk).text(value.nm_produk);

					var opt_ppn = $("<option selected></option>").val(value.ppn).text(value.ppn);
					
					$('#id_produk'+index).append(opt_produk).trigger('change');
					$("#qty"+index).val(format_angka(value.qty));

					if(value.cek_rm==1)
					{
						$('#cek_rm'+index).prop('checked', true);
					}else{
						$('#cek_rm'+index).prop('checked', false);

					}

					$('#ppn'+index).append(opt_ppn).trigger('change');
					// $("#ppn"+index).val(value.ppn);
					$('#price_inc'+index).val(format_angka(value.price_inc));
					$('#price_exc'+index).val(format_angka(value.price_exc));
					$("#eta_ket"+index).val(value.eta_ket); 
					
					$("#p11"+index).val(value.p11);

					if (act == 'detail') {
						$("#id_pelanggan").prop("disabled", true);
						$("#qty"+index).prop("disabled", true);
						// $("#qty_dec"+index).prop("disabled", true);
						$('#cek_rm'+index).prop("disabled", true);
						$("#id_produk"+index).prop("disabled", true);
						$("#ppn"+index).prop("disabled", true);
						$("#price_inc"+index).prop("disabled", true);
						$("#price_exc"+index).prop("disabled", true);
						$("#eta_ket"+index).prop("disabled", true);
					} else {
						$("#id_pelanggan").prop("disabled", false);
						$("#qty"+index).prop("disabled", false);
						// $("#qty_dec"+index).prop("disabled", false);
						$('#cek_rm'+index).prop("disabled", false);
						$("#id_produk"+index).prop("disabled", false);
						$("#ppn"+index).prop("disabled", false);
						$("#price_inc"+index).prop("disabled", false);
						$("#price_exc"+index).prop("disabled", false);
						$("#eta_ket"+index).prop("disabled", false);
					}
					
					if (index != (data.length) - 1) {
						addRow();
					}
					// console.log(index, data.length);
				});
			})
	}

	function deleteData(id,no) 
	{
		// let cek = confirm("Apakah Anda Yakin?");
		swal({
			title: "INVOICE",
			html: "<p> Apakah Anda yakin ingin menghapus file ini ?</p><br>"
			+"<strong>" +no+ " </strong> ",
			type               : "question",
			showCancelButton   : true,
			confirmButtonText  : '<b>Hapus</b>',
			cancelButtonText   : '<b>Batal</b>',
			confirmButtonClass : 'btn btn-success',
			cancelButtonClass  : 'btn btn-danger',
			cancelButtonColor  : '#d33'
		}).then(() => {

		// if (cek) {
			$.ajax({
				url   : '<?= base_url(); ?>Logistik/hapus',
				type  : "POST",
				data: ({
					id       : id,
					no_inv   : no,
					field    : 'id',
					jenis    : 'invoice'
				}),
				beforeSend: function() {
					swal({
					title: 'loading ...',
					allowEscapeKey    : false,
					allowOutsideClick : false,
					onOpen: () => {
						swal.showLoading();
					}
					})
				},
				success: function(data) {
					// toastr.success('Data Berhasil Di Hapus');
					swal({
						title               : "Data",
						html                : "Data Berhasil Di Hapus",
						type                : "success",
						confirmButtonText   : "OK"
					});
					reloadTable();
				},
				error: function(jqXHR, textStatus, errorThrown) {
					// toastr.error('Terjadi Kesalahan');
					swal({
						title               : "Cek Kembali",
						html                : "Terjadi Kesalahan",
						type                : "error",
						confirmButtonText   : "OK"
					});
					return;
				}
			});
		// }

		});


	}

	function setDetailProduk(kd,id) 
	{
		
		if(kd!=''){
			// show_loading();
			html_produk="";
			$.ajax({
				type        : 'POST',
				url         : "<?= base_url(); ?>Logistik/load_produk_1",
				data        : { idp: '', kd: kd },
				dataType    : 'json',
				beforeSend: function() {
					swal({
						title: 'loading ...',
						allowEscapeKey    : false,
						allowOutsideClick : false,
						onOpen: () => {
							swal.showLoading();
						}
					})
				},
				success:function(val){			
							
						(val.kategori =='K_BOX')? uk = val.ukuran : uk = val.ukuran_sheet;

						if (val.sambungan == 'G'){
							$join = 'Glue';
						} else if (val.sambungan == 'S'){
							$join = 'Stitching';
						}else if (val.sambungan == 'D'){
							$join = 'Die Cut';
						}else {
							$join = '-';
						}
						var qty_po = $("#qty"+id).val()
						
						html_produk = `
						<table class='table' border='0' style='font-size:12px'>
						<tr> 
							<tr style=list-style:none;> 
								<td><b>Nama Item : </b>${ val.nm_produk }</td>
								<td><b>Ukuran Box : </b>${ uk }</td>
								<td><b>Kualitas : </b>${ val.kualitas }</td>
							</tr>
							<tr style=list-style:none;>
								<td><b>Kode MC </b>: ${val.kode_mc}</td>
								<td><b>Ukuran Sheet </b>: <input type="hidden" class="input-border-none" name="p_sheet[${id}]" id="p_sheet${id}" value="${val.ukuran_sheet_p}" > ${val.ukuran_sheet}</td>
								<td><b>Flute </b>: <input type="hidden" class="input-border-none" name="fl[${id}]" id="fl${id}" value="${val.flute}" > ${val.flute}</td>
									
							</tr>
							<tr style=list-style:none;> 
								<td><b>Jenis Item </b>: ${val.jenis_produk}</td>
								<td><b>Creasing : </b>${ val.creasing }-${ val.creasing2 }-${ val.creasing3 }</td> 
								<td><b>Toleransi : </b>${ val.toleransi_kirim }</td> 
							</tr> 
							<tr style=list-style:none;> 
								<td><b>RM : <input type="text" class="input-border-none" name="rm[${id}]" id="rm${id}" readonly >
								</b>
								</td> 

								<td><b>BB : <input type="text" class="input-border-none" name="bb[${id}]" id="bb${id}" value="${val.berat_bersih}" readonly ></b>
								</td> 

								<td><b>Ton : <input type="text" class="input-border-none" name="ton[${id}]" id="ton${id}" readonly >
								</b></td>  
							</tr> 
							<tr style=list-style:none;>
								<td><b>Tipe Box </b>: ${val.tipe_box}</td>
								<td><b>Joint </b>: ${$join}</td>
								<td><b>Toleransi </b>: ${val.toleransi_kirim} %</td>
							</tr>`;
							<?php if ($this->session->userdata('level') != "PPIC"){ ?>
							html_produk += `
							<tr style=list-style:none;> 
								<td>
									<b>Harga / Kg : 
									</b>
									<input type="text" class="input-border-none" name="hrg_kg[${id}]" id="hrg_kg${id}" readonly >
								</td> 
								<td colspan="2">
									<b>QTY PO : 
									</b> ${qty_po}
								</td> 
							</tr> `;
							<?php } ?>

							html_produk += `</table>`;

						on_load(kd,id);

						if(status=='update'){
							
							var inc= $('#price_inc'+id).val();
							var qty= $('#qty'+id).val();

							hitung_p11(inc,'price_inc'+id);
							Hitung_rm(qty,'qty'+id);
						}
	
						$('#txt_detail_produk'+id).html(html_produk);	
						// close_loading();
						swal.close();
					
				}
			});
		}

	}

	var rowNum = 0;

	function addRow() 
	{

		var b = $('#bucket').val();

		if (b == -1) {
			b = 0;
			rowNum = 0;
		}
		var s           = $('#qty' + b).val();
		var ppn         = $('#ppn' + b).val();
		var price_inc   = $('#price_inc' + b).val();
		var price_exc   = $('#price_exc' + b).val();
		var p11         = $('#p11' + b).val();
		var ss          = $('#id_produk' + b).val();
		var user_lev    = "<?= $this->session->userdata('level') ?>";

		var idp         = $('#id_pelanggan').val();
		setProduk('addrow',idp,rowNum+1);
			
		if (s != '0' && s != '' && ss != '' && ppn != '' && price_inc != '' && price_exc != '' && price_inc != '0' && price_exc != '0') {
			$('#removeRow').show();
			rowNum++;
			// if (rowNum <= 4) {
				var x = rowNum + 1;

				td_harga = ''

				if ('<?= $this->session->userdata('level') ?>' != 'PPIC') {
					td_harga = `
						<td>
							<input type="text" name="price_exc[${rowNum}]" id="price_exc${rowNum}"  class="angka form-control" onkeyup="ubah_angka(this.value,this.id),Hitung_price(this.value,this.id)" onchange="hitung_p11(this.value,this.id)" value="0" >

						 
						</td>
						<td>
							<input type="text" name="price_inc[${rowNum}]" id="price_inc${rowNum}"  class="angka form-control" onkeyup="ubah_angka(this.value,this.id),Hitung_price(this.value,this.id)" onchange="hitung_p11(this.value,this.id)" value="0" >

						</td>
					`
					hitung = ``;
					p11_tambahan = `
						<td id="p11_det${rowNum}">
							<input type="text" name="p11[${rowNum}]" id="p11${rowNum}"  class="angka form-control" readonly value="0">
						 
						</td>
					`;
					coll=``;
				}else{
					p11_tambahan = ``;
					coll=`colspan="5"`;
					hitung = `
					<td width="10%" id="subs${ rowNum }" name="subs[${ rowNum }]">
							<select id="tl_al${ rowNum }" name="tl_al[${ rowNum }]" class="form-control select2" onchange="ayoBerhitung(${ rowNum })">
								<option value="">-</option>
								<option value="M">M</option>
								<option value="K">K</option>
								<option value="MC">MC</option>
								<option value="MN">MN</option>
							</select>

							<select id="bmf${ rowNum }" name="bmf[${ rowNum }]" class="form-control select2" onchange="ayoBerhitung(${ rowNum })">
								<option value="">-</option>
								<option value="M">M</option>
								<option value="K">K</option>
								<option value="MC">MC</option>
								<option value="MN">MN</option>
							</select>
							<select id="bl${ rowNum }" name="bl[${ rowNum }]" class="form-control select2" onchange="ayoBerhitung(${ rowNum })">
								<option value="">-</option>
								<option value="M">M</option>
								<option value="K">K</option>
								<option value="MC">MC</option>
								<option value="MN">MN</option>
							</select>
							<select id="cmf${ rowNum }" name="cmf[${ rowNum }]" class="form-control select2" onchange="ayoBerhitung(${ rowNum })">
								<option value="">-</option>
								<option value="M">M</option>
								<option value="K">K</option>
								<option value="MC">MC</option>
								<option value="MN">MN</option>
							</select>
							<select id="cl${ rowNum }" name="cl[${ rowNum }]" class="form-control select2" onchange="ayoBerhitung(${ rowNum })">
								<option value="">-</option>
								<option value="M">M</option>
								<option value="K">K</option>
								<option value="MC">MC</option>
								<option value="MN">MN</option>
							</select>

						</td>
						<td width="10%" id="subs_i${ rowNum }" name="subs_i[${ rowNum }]">
							<input type="text" id="tl_al_i${ rowNum }" name="tl_al_i[${ rowNum }]"  class="form-control angka" autocomplete="off" placeholder="TL/AL">

							<input type="text" id="bmf_i${ rowNum }" name="bmf_i[${ rowNum }]" class="form-control angka" autocomplete="off" placeholder="B.MF">

							<input type="text" id="bl_i${ rowNum }" name="bl_i[${ rowNum }]" class="form-control angka" autocomplete="off" placeholder="B.L">

							<input type="text" id="cmf_i${ rowNum }" name="cmf_i[${ rowNum }]" class="form-control angka" autocomplete="off" placeholder="C.MF">
							
							<input type="text" id="cl_i${ rowNum }" name="cl_i[${ rowNum }]" class="form-control angka" autocomplete="off" placeholder="C.L">
						</td>
						<td width="10%" id="subs_hitung${ rowNum }" name="subs_hitung[${ rowNum }]">
							Lebar Sheet : <input type="text" id="ii_lebar${ rowNum }" name="ii_lebar[${ rowNum }]" class="form-control angka" autocomplete="off" placeholder="LEBAR SHEET" onkeyup="ubah_angka(this.value,this.id)" onchange="ayoBerhitung(${ rowNum })">

							Qty Plan : <input type="text" id="qty_plan${ rowNum }" name="qty_plan[${ rowNum }]" class="form-control angka" autocomplete="off" placeholder="QTY PLAN" onkeyup="ubah_angka(this.value,this.id)" onchange="ayoBerhitung(${ rowNum })">

							Lebar Roll : <input type="text" id="i_lebar_roll${ rowNum }" name="i_lebar_roll[${ rowNum }]" class="form-control angka" autocomplete="off" onkeyup="ubah_angka(this.value,this.id)" placeholder="LEBAR ROLL" onchange="ayoBerhitung(${ rowNum })">
							
							Out : <input type="text" id="out_plan${ rowNum }" name="out_plan[${ rowNum }]" class="form-control angka" autocomplete="off"  onkeyup="ubah_angka(this.value,this.id)" placeholder="OUT" onchange="ayoBerhitung(${ rowNum })">
						</td>
						
						<td width="20%" id="subs_hasil_hitung${ rowNum }" name="subs_hasil_hitung[${ rowNum }]">
							trim : <input type="number" id="trim${ rowNum }" name="trim[${ rowNum }]" class="form-control" autocomplete="off" placeholder="TRIM" disabled>
							coff : <input type="number" id="c_off${ rowNum }" name="c_off[${ rowNum }]" class="form-control" autocomplete="off" placeholder="NUM OF CUT" disabled>
							rm : <input type="number" id="rm_plan${ rowNum }" name="rm_plan[${ rowNum }]" class="form-control" autocomplete="off" placeholder="RM PLAN" disabled>
							ton : <input type="number" id="ton_plan${ rowNum }" name="ton_plan[${ rowNum }]" class="form-control" autocomplete="off" placeholder="TONASE PLAN" disabled>
						
						</td>
					`;
				}
				

				// if (user_lev == 'Owner' || user_lev == 'Admin') 
				// {
					
				// }else{
					// p11_tambahan = ``;
				// }
					// coll=`colspan="5"`;
				

				$('#table-produk').append(
					`<tr id="itemRow${ rowNum }">
						<td id="detail-hapus-${ rowNum }">
							<div class="text-center">
							<a class="btn btn-danger"  id="btn-hapus-${ rowNum }" onclick="removeRow(${ rowNum })"><i class="far fa-trash-alt" style="color:#fff"></i> </a>
							</div>
						</td>
						<td>
							<select class="form-control select2" style="width: 150px;" name="id_produk[${ rowNum }]" id="id_produk${ rowNum }" style="width: 100%;" onchange="setDetailProduk(this.value,${ rowNum })">
							</select>
						</td>
						<td>
							<input type="text" name="qty[${ rowNum }]" id="qty${ rowNum }"  class="angka form-control" value="0" onkeyup="ubah_angka(this.value,this.id)"  onchange="Hitung_rm(this.value,this.id)">

							<br>
							<input class="form-control" type="checkbox" name="cek_rm[${ rowNum }]" id="cek_rm${ rowNum }" onclick="cekrm(this.id)" value="0">

						</td>

						<td>
							<select class="form-control select2" name="ppn[${ rowNum }]" id="ppn${ rowNum }">
								<option value="PP">PP</option>
								<option value="NP">NP</option>
							</select>
						</td>
						${ td_harga }
						${ p11_tambahan }
						<td ${ coll } id="txt_detail_produk${ rowNum }"> 
						</td>
					</tr>
					<tr style="width: 100%" id="item_tambahan${ rowNum }">
						<td width="20%">
							<div class="text-center">
								ETA
							</div>
						</td>
						<td width="20%">
							<input class="form-control" type="date" name="eta_item[${ rowNum }]" id="eta_item${ rowNum }">
						</td>
						<td width="10%">
							<textarea class="form-control" name="eta_ket[${ rowNum }]" id="eta_ket${ rowNum }" placeholder="KET. ETA" rows="3" style="resize:none"></textarea>
						</td>
						${ hitung }

					</tr>				
					`);
				$('.select2').select2({
					placeholder: '--- Pilih ---',
					dropdownAutoWidth: true
				});
				$('#bucket').val(rowNum);
				$('#qty' + rowNum).focus();
			// } else {
			// 	// toastr.info('Maksimal 5 Produk');
			// 	swal({
			// 			title               : "Cek Kembali",
			// 			html                : "Maksimal 5 Produk",
			// 			type                : "info",
			// 			confirmButtonText   : "OK"
			// 		});
			// 	return;
			// }
		} else {
			// toastr.info('Isi form diatas terlebih dahulu');
			// return;
			swal({
					title               : "Cek Kembali",
					html                : "Isi form diatas terlebih dahulu",
					type                : "info",
					confirmButtonText   : "OK"
				});
			return;
		}
	}

	function removeRow(e) 
	{
		if (rowNum > 0) {
			jQuery('#itemRow' + e).remove();
			jQuery('#item_tambahan' + e).remove();
			rowNum--;
		} else {
			// toastr.error('Baris pertama tidak bisa dihapus');
			// return;

			swal({
					title               : "Cek Kembali",
					html                : "Baris pertama tidak bisa dihapus",
					type                : "error",
					confirmButtonText   : "OK"
				});
			return;
		}
		$('#bucket').val(rowNum);
	}

	function clearRow() 
	{
		var bucket = $('#bucket').val();
		for (var e = bucket; e > 0; e--) {
			jQuery('#itemRow' + e).remove();
			jQuery('#item_tambahan' + e).remove();
			rowNum--;
		}

		$('#removeRow').hide();
		$('#bucket').val(rowNum);
		$('#id_produk0').val('').trigger('change');
		$('#qty0').val('');
		$('#p110').val('0');
		$('#price_inc0').val('');
		$('#price_exc0').val('');
		$('#txt_detail_produk0').html('');
		$("#btn-hapus-0").show();
		$("#detail-hapus-0").show();
		$("#p11_det0").show();

		$("#qty0").prop("disabled", false);
		$("#id_produk0").prop("disabled", false);
		$("#price_inc0").prop("disabled", false);
		$("#price_exc0").prop("disabled", false);		
		$("#ppn0").prop("disabled", false);
		$('#cek_rm0').prop("disabled", false);
		$('#cek_rm0').prop('checked', false);
		$("#eta_ket0").prop("disabled", false);

		$("#cek_rm0").val(0);		
		$("#eta_ket0").val(0);		
		$("#tl_al0").val('');		
		$("#bmf0").val('');		
		$("#bl0").val('');		
		$("#cmf0").val('');		
		$("#cl0").val('');		
		$("#tl_al_i0").val(0);		
		$("#bmf_i0").val(0);		
		$("#bl_i0").val(0);		
		$("#cmf_i0").val(0);		
		$("#cl_i0").val(0);		
		$("#ii_lebar0").val(0);		
		$("#qty_plan0").val(0);		
		$("#i_lebar_roll0").val(0);		
		$("#out_plan0").val(0);		
		$("#trim0").val(0);		
		$("#c_off0").val(0);		
		$("#rm_plan0").val(0);		
		$("#ton_plan0").val(0);	
	
		$("#tl_al0").prop("disabled", true);		
		$("#bmf0").prop("disabled", true);		
		$("#bl0").prop("disabled", true);		
		$("#cmf0").prop("disabled", true);		
		$("#cl0").prop("disabled", true);		
		$("#tl_al_i0").prop("disabled", true);		
		$("#bmf_i0").prop("disabled", true);		
		$("#bl_i0").prop("disabled", true);		
		$("#cmf_i0").prop("disabled", true);		
		$("#cl_i0").prop("disabled", true);		
		$("#ii_lebar0").prop("disabled", true);		
		$("#qty_plan0").prop("disabled", true);		
		$("#i_lebar_roll0").prop("disabled", true);		
		$("#out_plan0").prop("disabled", true);		
		$("#trim0").prop("disabled", true);		
		$("#c_off0").prop("disabled", true);		
		$("#rm_plan0").prop("disabled", true);		
		$("#ton_plan0").prop("disabled", true);		
		
		<?php if ($this->session->userdata('level') != "PPIC"){ ?>
			$('#subs0').hide();
			$('#subs_i0').hide();
			$('#subs_hitung0').hide();
			$('#subs_hasil_hitung0').hide();
		<?php } ?>
	}

	function Hitung_price(val,id) 
	{
		var cek = id.substr(0,9);
		var id2 = id.substr(9,1);
		var isi = val.split('.').join('');
		
		if(cek=='price_exc')
		{
			inc = Math.trunc(isi *1.11);
			$('#price_inc'+id2).val(format_angka(inc));

			// $('#price_exc_rp'+id2).val(format_angka(val));
			// $('#price_inc_rp'+id2).val(format_angka(inc));
		}else {
			exc = Math.trunc(isi /1.11);
			$('#price_exc'+id2).val(format_angka(exc));

			// $('#price_exc_rp'+id2).val(format_angka(exc));
			// $('#price_inc_rp'+id2).val(format_angka(val));
		}
	}
	
	function Cetak() 
	{
		no_invoice = $("#no_invoice").val();
		var url = "<?= base_url('Logistik/Cetak_Invoice'); ?>";
		window.open(url + '?no_invoice=' + no_invoice, '_blank');
	}

	function acc_inv(user,acc,no_inv) 
	{
		if (acc=='N')
		{
			var html = 'VERIFIKASI'
			var icon = '<i class="fas fa-check"></i>'
		}else{
			var html = 'BATAL VERIFIKASI'
			var icon = '<i class="fas fa-lock"></i>'

		}
		swal({
			title: html,
			html: "<p> Apakah Anda yakin ?</p><br>",
			type                : "question",
			showCancelButton    : true,
			confirmButtonText   : '<b>'+icon+' '+html+'</b>',
			cancelButtonText    : '<b><i class="fas fa-undo"></i> Batal</b>',
			confirmButtonClass  : 'btn btn-success',
			cancelButtonClass   : 'btn btn-danger',
			confirmButtonColor  : '#28a745',			
			cancelButtonColor   : '#d33'			
		}).then(() => {

				$.ajax({
					url: '<?= base_url(); ?>Logistik/prosesData',
					data: ({
						no_inv    : no_inv,
						user      : user,
						acc       : acc,
						jenis     : 'verif_inv'
					}),
					type: "POST",
					beforeSend: function() {
						swal({
							title: 'loading ...',
							allowEscapeKey    : false,
							allowOutsideClick : false,
							onOpen: () => {
								swal.showLoading();
							}
						})
					},
					success: function(data) {
						// toastr.success('Data Berhasil Diproses');
						swal({
							title               : "Data",
							html                : "Data Berhasil Diproses",
							type                : "success",
							confirmButtonText   : "OK"
						});
						
						// setTimeout(function(){ location.reload(); }, 1000);
						// location.href = "<?= base_url()?>Logistik/Invoice";
						// location.href = "<?= base_url()?>Logistik/Invoice_edit?id="+id+"&statuss=Y&no_inv="+no_inv+"&acc=1";
						reloadTable()
						
						
					},
					error: function(jqXHR, textStatus, errorThrown) {
						// toastr.error('Terjadi Kesalahan');
						swal({
							title               : "Cek Kembali",
							html                : "Terjadi Kesalahan",
							type                : "error",
							confirmButtonText   : "OK"
						});
						return;
					}
				});
		
		});


	}


</script>
