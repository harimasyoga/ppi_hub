<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6" style="font-family:Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;" >
					<!-- <h1><b>Data Transaksi</b> </h1> -->
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
				<?php if (!in_array($this->session->userdata('level'), ['Marketing','PPIC','Owner','Keuangan1'])): ?>
					<button type="button" style="font-family:Cambria;" class="tambah_data btn  btn-info "><i class="fa fa-plus" ></i>&nbsp;&nbsp;<b>Tambah Data</b></button>
				<?php endif ?>
				<br><br>
					<table id="datatable" class="table table-bordered table-striped table-scrollable" width="100%">
						<thead>
							<tr>
								
								<?php if ($this->session->userdata('level') != "PPIC")  {?>

									<th style="text-align: center; width:3%">No</th>
									<th style="text-align: center; width:10%">No PO</th>
									<th style="text-align: center; width:20%">Tgl PO</th>
									<th style="text-align: center; width:5%">Status</th>
									<th style="text-align: center; width:10%">Kode PO</th>
									<!-- <th style="display:none;">Kode PO</th> -->
									<!-- <th style="text-align: center">Total Qty</th> -->
									<th style="text-align: center; width:10%">Customer</th>
									<th style="text-align: center; width:5%">Admin</th>
									<th style="text-align: center; width:5%">Mkt</th>
									<th style="text-align: center; width:7%">PPIC</th>
									<th style="text-align: center; width:5%">Owner</th>
									<th style="text-align: center; width:20%;">Aksi</th>
								<?php } else { ?>
									<th style="text-align: center; width:3%">No</th>
									<th style="text-align: center; width:10%">No PO</th>
									<th style="text-align: center; width:15%">Tgl PO</th>
									<th style="text-align: center; width:15%">Item</th>
									<th style="text-align: center; width:5%">Status</th>
									<th style="text-align: center; width:10%">Kode PO</th>
									<!-- <th style="display:none;">Kode PO</th> -->
									<!-- <th style="text-align: center">Total Qty</th> -->
									<th style="text-align: center; width:10%">Customer</th>
									<th style="text-align: center; width:5%">Admin</th>
									<th style="text-align: center; width:5%">Mkt</th>
									<th style="text-align: center; width:7%">PPIC</th>
									<th style="text-align: center; width:5%">Owner</th>
									<th style="text-align: center; width:10%;">Aksi</th>
								<?php }  ?>
								
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
	<div class="modal-dialog modal-full">
		<div class="modal-content">
			<div class="card-header" style="font-family:Cambria;" >
				<h4 class="card-title" style="color:#4e73df;" id="judul"></h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body">
				<form role="form" method="post" id="myForm" enctype="multipart/form-data">
					<!-- <div style="overflow-x:auto;"> -->

						<div class="card-body">
							<div class="col-md-12">
								<div class="card-body row" style="padding : 5px;font-weight:bold">
									<div class="col-md-2">No PO</div>
									<div class="col-md-3">
										<input type="hidden" class="form-control" value="trs_po" name="jenis" id="jenis">
										<input type="hidden" class="form-control" value="" name="status" id="status">
										<input type="text" class="form-control" name="no_po" id="no_po" value="AUTO" readonly>
									</div>

									<div class="col-md-2"></div>

									<div class="col-md-2">Nama Pelanggan</div>
									<div class="col-md-3">
										<select class="form-control select2" name="id_pelanggan" id="id_pelanggan" style="width: 100%;" onchange="setProduk('new',this.value,0)">
											<!-- <option value="">Pilih</option> -->
											<?php foreach ($pelanggan as $r) : ?>
												<option value="<?= $r->id_pelanggan ?>" detail="
												<?=$r->kab_name."|".$r->no_telp . "|" . $r->fax . "|" . $r->top . "|" . $r->nm_sales ?>">
													<?= $r->id_pelanggan . "|" . $r->nm_pelanggan ?>
												</option>
											<?php endforeach ?>
										</select>
									</div>
								</div>
								<div class="card-body row" style="padding : 5px;font-weight:bold">
									<div class="col-md-2">Tgl PO</div>
									<div class="col-md-3">
										<?php if (in_array($this->session->userdata('level'), ['Admin','User']))  { ?>
											<input type="date" class="form-control" name="tgl_po" id="tgl_po" onchange="pilih_hub(0)" value="<?= date('Y-m-d') ?>"> 
										<?php } else { ?>
											<input type="date" class="form-control" name="tgl_po" id="tgl_po" onchange="pilih_hub(0)" value="<?= date('Y-m-d') ?>" readonly> 
										<?php } ?>
									</div>

									<div class="col-md-2"></div>

									<div class="col-md-2">Kota</div>
									<div class="col-md-3">
										<input type="text" class="form-control" name="txt_kota" id="txt_kota" value="" readonly>
									</div>
								</div>
								<div class="card-body row" style="padding : 5px;font-weight:bold">
									<div class="col-md-2">Kode PO</div>
									<div class="col-md-3">
										<input type="text" class="form-control" name="kode_po" id="kode_po" onchange="cek_kode_po(this.value)" oninput="this.value = this.value.toUpperCase(), this.value = this.value.trim(); " >
									</div>

									<div class="col-md-2"></div>

									<div class="col-md-2">No Telepon</div>
									<div class="col-md-3">
										<input type="text" class="form-control" name="txt_no_telp" id="txt_no_telp" value="" readonly>
									</div>
								</div>
								<div class="card-body row" style="padding : 5px;font-weight:bold">
									<div class="col-md-2">Marketing</div>
									<div class="col-md-3">
										<input type="text" class="form-control" name="txt_marketing" id="txt_marketing" value="" readonly>
									</div>

									<div class="col-md-2"></div>

									<div class="col-md-2">FAX</div>
									<div class="col-md-3">
										<input type="text" class="form-control" name="txt_fax" id="txt_fax" value="" readonly>
									</div>
								</div>
								<div class="card-body row" style="padding : 5px;font-weight:bold">
									<div class="col-md-2">ATTN</div>
									<div class="col-md-3">
										<select class="form-control select2" name="id_hub" id="id_hub" style="width: 100%;" >
											<!-- <option value="">Pilih</option> -->
											<?php foreach ($hub as $r) : ?>
												<option value="<?= $r->id_hub ?>" detail="
												<?=$r->id_hub."|".$r->nm_hub ?>">
													<?= $r->id_hub . " | " . $r->nm_hub . " | <b>" . number_format($r->sisa_hub, 0, ",", ".") ."</b>" ?>
												</option>
											<?php endforeach ?>
										</select>
									</div>
									
									<div class="col-md-2"></div>

									<div class="col-md-2">TOP</div>
									<div class="col-md-3">
										<input type="text" class="form-control" name="txt_top" id="txt_top" value="" readonly>
									</div>
								</div>
								<div>
									<div class="card-body row" style="padding : 5px;font-weight:bold">
										<?php if ($this->session->userdata('level') != "PPIC")  {
													?>
											<div class="col-md-2">Upload PO</div>
											<div class="col-md-3">
												<div class="col-9">
													<input type="file" data-max-size="2048" name="filefoto" id="filefoto" accept=".jpg,.jpeg,.png">
												</div>
												<div id="div_preview_foto" style="display: none;">											
													<img id="preview_img" src="#" alt="Preview Foto" width="100" class="shadow-sm img-thumbnail"/>
													<span class="help-block"></span>
												</div>										
											</div>
											
										<?php } else { ?>
											<div class="col-md-5"></div>
											<div style="display: none;">
												<div class="col-9">
													<input type="file" data-max-size="2048" name="filefoto" id="filefoto" accept=".jpg,.jpeg,.png">
												</div>
												<div id="div_preview_foto" style="display: none;">											
													<img id="preview_img" src="#" alt="Preview Foto" width="100" class="shadow-sm img-thumbnail"/>
													<span class="help-block"></span>
												</div>										
											</div>
										
										<?php } ?>
										<div class="col-md-7"></div>
									</div>
								</div>
							</div>
						</div>
							
						<hr>

						<div style="overflow:auto;white-space:nowrap;" >
							<table class="table table-hover table-striped table-bordered table-scrollable table-condensed" id="table-produk" width="100%">
								<thead class="color-tabel">
									<tr>
										<th id="header_del">Delete</th>
										<th style="padding : 12px 20px" >Item</th>
										<th style="padding : 12px 40px" >Qty</th>
										<th style="padding : 12px 40px" >PPN</th>

										<?php if ($this->session->userdata('level') != "PPIC")  {
											?>
												<th style="padding : 12px 15px" >Price Exclude</th>
												<th style="padding : 12px 15px" >Price Include</th>
												<th style="padding : 12px 30px" id="header_p11" >P11</th>
												<th>Detail Item</th>
											
										<?php } else { ?>

												<th type="hidden"  id="header_p11" >P11</th>
												
												<th colspan="5">Detail Item</th>

										<?php } ?>
									</tr>
								</thead>
								<tbody>
									<tr id="itemRow0">
										<td id="detail-hapus-0">
											<div class="text-center">
												<a class="btn btn-danger" id="btn-hapus-0" onclick="removeRow(0)"><i class="far fa-trash-alt" style="color:#fff"></i> </a>
											</div>
										</td>
										<td>
											<select class="form-control select2 narrow wrap wrap" name="id_produk[0]" id="id_produk0" style="width: 100%;" onchange="setDetailProduk(this.value,0)">
											</select>
										</td>
										<td >
											<input type="text" name="qty[0]" id="qty0" class="angka form-control" value='0' onkeyup="ubah_angka(this.value,this.id)" onchange="Hitung_rm(this.value,this.id)">											
											<br>
											<input class="form-control" type="checkbox" name="cek_rm[0]" id="cek_rm0" onclick="cekrm(this.id)" value="0">
										</td>
										<td>
											<select class="form-control select2" name="ppn[0]" id="ppn0" >
												<option value="">-- Pilih --</option>
												<!-- <option value="KB">KB</option> -->
												<option value="PP">PP</option>
												<option value="NP">NP</option>
											</select>
										</td>
										<?php if ($this->session->userdata('level') != "PPIC"){ ?>
										
										<td style="padding : 12px 20px" >
											<input type="text" name="price_exc[0]" id="price_exc0" class="angka form-control" onkeyup="ubah_angka(this.value,this.id),Hitung_price(this.value,this.id)" onchange="hitung_p11(this.value,this.id)" value='0'>

										</td>
										<td style="padding : 12px 20px">
											<input type="text" name="price_inc[0]" id="price_inc0" class="angka form-control" onkeyup="ubah_angka(this.value,this.id),Hitung_price(this.value,this.id)" onchange="hitung_p11(this.value,this.id)" value='0'>

										</td>
										<td id="p11_det0">
											<input type="text" name="p11[0]" id="p110"  class="angka form-control" readonly value="0" >
										
										</td>
										<td id="txt_detail_produk0">
										</td>
										<?php }else{ ?>

										<td colspan="5" id="txt_detail_produk0">
										</td>
										<?php } ?>

											

										
										
									</tr>
									<tr id="item_tambahan0">
										<td>
											<div class="text-center">
												ETA
											</div>
										</td>
										<td>
											<input class="form-control" type="date" name="eta_item[0]" id="eta_item0">
										</td>
										<td>
											<textarea class="form-control" name="eta_ket[0]" id="eta_ket0" placeholder="KET. ETA" rows="3" style="resize:none"></textarea>
										</td>
										<td width="10%" id="subs0" name="subs[0]">
											<select id="tl_al0" name="tl_al[0]" class="form-control select2" onchange="ayoBerhitung(0)">
												<option value="">-</option>
												<option value="M">M</option>
												<option value="K">K</option>
												<option value="MC">MC</option>
												<option value="MN">MN</option>
											</select>

											<select id="bmf0" name="bmf[0]" class="form-control select2" onchange="ayoBerhitung(0)">
												<option value="">-</option>
												<option value="M">M</option>
												<option value="K">K</option>
												<option value="MC">MC</option>
												<option value="MN">MN</option>
											</select>
											<select id="bl0" name="bl[0]" class="form-control select2" onchange="ayoBerhitung(0)">
												<option value="">-</option>
												<option value="M">M</option>
												<option value="K">K</option>
												<option value="MC">MC</option>
												<option value="MN">MN</option>
											</select>
											<select id="cmf0" name="cmf[0]" class="form-control select2" onchange="ayoBerhitung(0)">
												<option value="">-</option>
												<option value="M">M</option>
												<option value="K">K</option>
												<option value="MC">MC</option>
												<option value="MN">MN</option>
											</select>
											<select id="cl0" name="cl[0]" class="form-control select2" onchange="ayoBerhitung(0)">
												<option value="">-</option>
												<option value="M">M</option>
												<option value="K">K</option>
												<option value="MC">MC</option>
												<option value="MN">MN</option>
											</select>

										</td>
										<td width="10%" id="subs_i0" name="subs_i[0]">
											<input type="text" id="tl_al_i0" name="tl_al_i[0]"  class="form-control angka" autocomplete="off" placeholder="TL/AL">

											<input type="text" id="bmf_i0" name="bmf_i[0]" class="form-control angka" autocomplete="off" placeholder="B.MF">

											<input type="text" id="bl_i0" name="bl_i[0]" class="form-control angka" autocomplete="off" placeholder="B.L">

											<input type="text" id="cmf_i0" name="cmf_i[0]" class="form-control angka" autocomplete="off" placeholder="C.MF">
											
											<input type="text" id="cl_i0" name="cl_i[0]" class="form-control angka" autocomplete="off" placeholder="C.L">
										</td>
										<td width="10%" id="subs_hitung0" name="subs_hitung[0]">
											Lebar Sheet : <input type="text" id="ii_lebar0" name="ii_lebar[0]" class="form-control angka" autocomplete="off" placeholder="LEBAR SHEET" onkeyup="ubah_angka(this.value,this.id)" onchange="ayoBerhitung(0)">

											Qty Plan : <input type="text" id="qty_plan0" name="qty_plan[0]" class="form-control angka" autocomplete="off" placeholder="QTY PLAN" onkeyup="ubah_angka(this.value,this.id)" onchange="ayoBerhitung(0)">

											Lebar Roll : <input type="text" id="i_lebar_roll0" name="i_lebar_roll[0]" class="form-control angka" autocomplete="off" onkeyup="ubah_angka(this.value,this.id)" placeholder="LEBAR ROLL" onchange="ayoBerhitung(0)">
											
											Out : <input type="text" id="out_plan0" name="out_plan[0]" class="form-control angka" autocomplete="off"  onkeyup="ubah_angka(this.value,this.id)" placeholder="OUT" onchange="ayoBerhitung(0)">
										</td>
										
										<td width="20%" id="subs_hasil_hitung0" name="subs_hasil_hitung[0]">
											trim : <input type="number" id="trim0" name="trim[0]" class="form-control" autocomplete="off" placeholder="TRIM" disabled>
											coff : <input type="number" id="c_off0" name="c_off[0]" class="form-control" autocomplete="off" placeholder="NUM OF CUT" disabled>
											rm : <input type="number" id="rm_plan0" name="rm_plan[0]" class="form-control" autocomplete="off" placeholder="RM PLAN" disabled>
											ton : <input type="number" id="ton_plan0" name="ton_plan[0]" class="form-control" autocomplete="off" placeholder="TONASE PLAN" disabled>
										
										</td>

									</tr>
								</tbody>
							</table>
						</div>

						<div style="justify-content: left; ">
							<!-- <label class="col-sm-2 col-form-label"></label> -->
							<div class="col-sm-4">
								<button type="button" onclick="addRow()" class="btn-tambah-produk btn  btn-success"><b><i class="fa fa-plus" ></i> Tambah Produk</b></button>
								<input type="hidden" name="bucket" id="bucket" value="0">
							</div>
						</div>
					
					<!-- </div> -->
					<div class="modal-footer">

						<button type="button" class="btn btn-success btn-verif" id="btn-verif_acc" style="display: none;" onclick="prosesData_acc('Y')"><i class="fas fa-check"></i> <b>Verifikasi</b></button>
						
						<button type="button" class="btn btn-warning btn-verif" id="btn-verif_hold" style="display: none;" onclick="prosesData_hold('H')"><i class="far fa-hand-paper"></i> <b>HOLD</b></button>

						<button type="button" class="btn btn-danger btn-verif" id="btn-verif_r" style="display: none;" onclick="prosesData_r('R')"><i class="fas fa-times"></i> <b>Reject</b></button>
						<!--<button type="button" class="btn btn-primary" id="btn-simpan-plan" onclick="simpan_plan()"><i class="fas fa-save"></i><b> Simpan Plan</b></button> -->
						
						<button type="button" class="btn btn-primary" id="btn-simpan" onclick="simpan()"><i class="fas fa-save"></i><b> Simpan</b></button>

						<button type="button" class="btn btn-danger" id="btn-print" onclick="Cetak()" style="display:none"><i class="fas fa-print"></i> <b>Print</b></button>

						<button type="button" class="btn btn-outline-danger" data-dismiss="modalForm" onclick="close_modal();" ><i class="fa fa-times-circle"></i> <b> Batal</b></button>
					</div>
				</form>			
			</div>
			<input type="hidden" name="bucket" id="bucket" value="0">
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<!-- /.modal -->



<!-- modal keterangan -->
<div class="modal fade" id="modalket">
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

<!-- Image Zoom HTML -->
<div id="mymodal-img" class="modal-img">
  <img class="modal-img-content" id="img01">
</div>
<!-- End Image Zoom HTML -->

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

	$("#filefoto").change(function() {
        readURL(this);
    });

	var modal = document.getElementById('mymodal-img');

	// Get the image and insert it inside the modal - use its "alt" text as a caption
	var img            = document.getElementById('preview_img');
	var modalImg       = document.getElementById("img01");
	img.onclick = function(){
		modal.style.display   = "block";
		modalImg.src          = this.src;
		modalImg.alt          = this.alt;
	}


	// When the user clicks on <span> (x), close the modal
	modal.onclick = function() {
		img01.className       += " out";
		setTimeout(function() {
			modal.style.display   = "none";
			img01.className       = "modal-img-content";
		}, 400);
		
	}    

	function readURL(input) {
		if (input.files && input.files[0]) {
		var reader = new FileReader();
		reader.onload = function(e) {
			$('#div_preview_foto').css("display","block");
			$('#preview_img').attr('src', e.target.result);
		}
		reader.readAsDataURL(input.files[0]);
		} else {
			$('#div_preview_foto').css("display","none");
			$('#preview_img').attr('src', '');
		}
	}

	status = "insert";
	$(".tambah_data").click(function(event) 
	{
		kosong();
		$('#filefoto').css("display","block");
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
					url: "<?= base_url(); ?>Transaksi/load_produk",
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

	function pilih_hub(id_hub)
	{
		var tgl_po = $('#tgl_po').val();
		$.ajax({
			type: 'POST',
			url: "<?= base_url(); ?>Transaksi/load_hub",
			data: { tgl_po },
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

					option += "<option value='"+val.id_hub+"'>"+val.id_hub+ " | " +val.nm_hub+ " | " + format_angka(val.sisa_hub) + "</option>";
					
					});

					
					if(id_hub==0)
					{
						$('#id_hub').html(option);

					}else{		
						$('#id_hub').html(option);
						$('#id_hub').val(id_hub).trigger('change');
					}
					swal.close();
				}else{	
					option += "<option value=''></option>";
					$('#id_hub').html(option);		
					swal.close();
				}
			}
		});
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
				"url": '<?= base_url(); ?>Transaksi/load_data/po',
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

	function simpan_plan() 
	{
		// var lebar_plan          = str_replace('.', '', $params->ii_lebar[$key])
		// var qty_plan            = str_replace('.', '', $params->qty_plan[$key])
		// var lebar_roll_p        = str_replace('.', '', $params->i_lebar_roll[$key])
		// var out_plan            = str_replace('.', '', $params->out_plan[$key])
		// var trim_plan           = str_replace('.', '', $params->trim[$key])
		// var c_off_p             = $params->c_off[$key]
		// var rm_plan             = $params->rm_plan[$key]
		// var tonase_plan         = $params->ton_plan[$key]
		// var material_plan       = $material_plan
		// var kualitas_isi_plan   = $kualitas_isi_plan
		// var kualitas_plan       = $kualitas_plan
		
		swal({
			title: "PO",
			html: "<p> <b> Simpan Plan Sementara ini ? </b></p>",
			type               : "question",
			showCancelButton   : true,
			confirmButtonText  : '<b>Ya</b>',
			cancelButtonText   : '<b>Batal</b>',
			confirmButtonClass : 'btn btn-success',
			cancelButtonClass  : 'btn btn-danger',
			cancelButtonColor  : '#d33'
		}).then(() => {
			swal({
				title: 'loading ...',
				allowEscapeKey    : false,
				allowOutsideClick : false,
				onOpen: () => {
					swal.showLoading();
				} 
			})

			$.ajax({
				url        : '<?= base_url(); ?>Transaksi/update_plan',
				type       : "POST",
				data       : $('#myForm').serialize(),
				dataType   : "JSON",
				success: function(data) {
					if (data) {
						
						swal.close();
						swal({
							title               : "Data",
							html                : "Berhasil Disimpan",
							type                : "success",
							confirmButtonText   : "OK"
						});
						
						kosong();
						$("#modalForm").modal("hide");
					} else {
						
						swal.close();
						swal({
							title               : "Cek Kembali",
							html                : "Gagal Simpan",
							type                : "error",
							confirmButtonText   : "OK"
						});
						return;
					}
					reloadTable();
				},
				error: function(jqXHR, textStatus, errorThrown) {
					
					
					swal.close();
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
	
	function simpan() 
	{
		var file_data   = $('#filefoto').prop('files')[0];
		var form_data   = new FormData();
		form_data.append('filefoto', file_data);
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
		id_hub          = $("#id_hub").val();

		if (id_pelanggan == '' || kode_po == '' || sales=='' || id_hub=='') {
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

		var form    = $('#myForm')[0];
		var data    = new FormData(form);
		
		$.ajax({
			url            : '<?= base_url(); ?>Transaksi/insert',
			type           : "POST",
			enctype        : 'multipart/form-data',
			data           : data,
			dataType       : "JSON",
			contentType    : false,
			cache          : false,
			timeout        : 600000,
			processData    : false,
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

		$("#id_hub").select2("val", 1);
		$('#id_hub').val(1).trigger('change');		

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
		$('#div_preview_foto').css("display","none");

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

		if (data.header.status == 'Open' || data.header.status == 'Reject') {
			if ('<?= $this->session->userdata('level') ?>' == 'Admin'){
				$(".btn-verif").show()
			}

			if ('<?= $this->session->userdata('level') ?>' == 'Marketing' && ( data.header.status_app1 == 'N' || data.header.status_app1 == 'H' || data.header.status_app1 == 'R'  ) ) 
			{
				$(".btn-verif").show()
			}

			if ('<?= $this->session->userdata('level') ?>' == 'PPIC' && data.header.status_app1 == 'Y' && ( data.header.status_app2 == 'N' || data.header.status_app2 == 'H' || data.header.status_app2 == 'R' ) ) 
			{
				$(".btn-verif").show()
				// $("#btn-simpan-plan").show()
			}

			if ('<?= $this->session->userdata('level') ?>' == 'Owner' && data.header.status_app1 == 'Y' && data.header.status_app2 == 'Y'  && ( data.header.status_app3 == 'N' || data.header.status_app3 == 'H' || data.header.status_app3 == 'R' ) ) 
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
			$('#filefoto').css("display","none");
		} else {
			$("#judul").html('<h3> Form Edit Data</h3>');
			$("#btn-simpan").show();
			$('#filefoto').css("display","block");
		} 

		status = "update";

		$.ajax({
				url: '<?= base_url('Transaksi/get_edit'); ?>',
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
				no_po = data.header.no_po

				$("#no_po").val(data.header.no_po);
				$("#tgl_po").val(data.header.tgl_po);
				pilih_hub(data.header.id_hub);
				
				$('#id_pelanggan').val(data.header.id_pelanggan).trigger('change');

				kodepo    = (data.header.kode_po == '' ) ? '-' : data.header.kode_po ;
				
				$("#kode_po").val(kodepo);
				
				$('#div_preview_foto').css("display","block");
				$('#preview_img').attr('src',data.url_foto);
				// $("#eta").val(eta); 
				
				$("#header_del").hide();

				if (cek !='PPIC')
				{
					$("#header_p11").show();
				}else{
					$("#header_p11").hide();
				}

				$.each(data.detail, function(index, value) {
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
					
					if (index != (data.detail.length) - 1) {
						addRow();
					}
					// console.log(index, data.length);
				});
			})
	}

	function getMax() 
	{
		$.ajax({
			url: '<?= base_url('Transaksi/getMax'); ?>',
			type: 'POST',
			data: {
				table: "trs_po",
				fieald: 'no_po'
			},
			dataType: "JSON",
			success: function(data) {
				$("#no_po").val("PO/" + data.tahun + "/" + data.bln + "/" + data.no);
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

	}

	function deleteData(id,no) 
	{
		// let cek = confirm("Apakah Anda Yakin?");
		swal({
			title: "PO",
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
				url: '<?= base_url(); ?>Transaksi/hapus',
				data: ({
					id: id,
					jenis: 'trs_po',
					field: 'no_po'
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
	
	function nonaktif(ket,id,no,time) 
	{
		if(ket==0)
		{
			var kett ='NON AKTIFKAN';
		}else{
			var kett ='AKTIFKAN';
		}
		swal({
			title: "PO",
			html: "<p> Apakah Anda yakin ingin "+kett+" PO ini ?</p><br>"
			+"<strong>" +no+ " </strong> <br>"
			+"<strong>" +time+ " </strong> ",
			type               : "question",
			showCancelButton   : true,
			confirmButtonText  : '<b>'+kett+'</b>',
			cancelButtonText   : '<b>Batal</b>',
			confirmButtonClass : 'btn btn-success',
			cancelButtonClass  : 'btn btn-danger',
			cancelButtonColor  : '#d33'
		}).then(() => {

		// if (cek) {
			$.ajax({
				url: '<?= base_url(); ?>Transaksi/Verifikasi_all',
				data: ({
					id: id,
					jenis: 'trs_po',
					field: 'no_po'
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

	function data_sementara(data,status,tgl,alasan,nopo){
		$("#modalket").modal("show");
		$("#judul2").html('<b>'+data+'</b>');
		$("#nopo_ket").html('<h3><b>'+nopo+'</b></h3>');
		$("#tgl_ket").html('<h3><b>'+tgl+'</b></h3>');
		
		if(status=='Y'){
			$("#judul2").attr({ 'style': "color:green;" });
			$("#status_acc").html('<h3><i class="fas fa-check"></i><b> ACC - '+data+'</b></h3>');
			$("#status_acc").attr({ 'style': "color:green;" });
			$('#alasan').html('');	
		}else if(status=='R'){
			$("#judul2").attr({ 'style': "color:red;" });
			$("#status_acc").html('<h3><i class="fas-times-circle"></i><b> REJECT - '+data+'</b></h3>');
			$("#status_acc").attr({ 'style': "color:red;" });

			html_produk=`<td width="35%" ><h3>Alasan</h3></td>
				<td width="10%" ><h3> : </h3></td>
				<td width="55%" ><h3> ${alasan} </h3></td>`;

			$('#alasan').html(html_produk);	
			
		}else if(status=='H'){
			$("#judul2").attr({ 'style': "color:red;" });
			$("#status_acc").html('<h3><i class="far fa-hand-paper"></i><b> HOLD - '+data+'</b></h3>');
			$("#status_acc").attr({ 'style': "color:red;" });

			html_produk=`<td width="35%" ><h3>Alasan</h3></td>
				<td width="10%" ><h3> : </h3></td>
				<td width="55%" ><h3> ${alasan} </h3></td>`;

			$('#alasan').html(html_produk);	
			 
		}else{
			$("#judul2").attr({ 'style': "color:yellow; text-shadow: 0 0 5px #000;" });
			$("#status_acc").html('<h3><i class="fas fa-lock"></i><b> BELUM ACC - '+data+'</b></h3>');
			$("#status_acc").attr({ 'style': "color:yellow; text-shadow: 0 0 5px #000;" });
			$('#alasan').html('');	
		}

		
	}

	function prosesData_acc(tipe) 
	{
		// let cek = confirm("Apakah Anda Yakin?");

		swal({
			title: "Verifikasi PO",
			html: "<p> Apakah Anda yakin untuk verifikasi file ini ?</p><br>",
			type                : "question",
			showCancelButton    : true,
			confirmButtonText   : '<b><i class="fas fa-check"></i> Verifikasi</b>',
			cancelButtonText    : '<b><i class="fas fa-undo"></i> Batal</b>',
			confirmButtonClass  : 'btn btn-success',
			cancelButtonClass   : 'btn btn-danger',
			confirmButtonColor  : '#28a745',			
			cancelButtonColor   : '#d33'			
		}).then(() => {

				$.ajax({
					url: '<?= base_url(); ?>Transaksi/prosesData',
					data: ({
						id: no_po,
						status: tipe,
						alasan: 'OK',
						jenis: 'verifPO'
					}),
					type: "POST",
					success: function(data) {
						toastr.success('Data Berhasil Diproses');
						// swal({
						// 	title               : "Data",
						// 	html                : "Data Berhasil Diproses",
						// 	type                : "success",
						// 	confirmButtonText   : "OK"
						// });
						$("#modalForm").modal("hide");
						reloadTable();
						// setTimeout(function(){ location.reload(); }, 1000);
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

	function prosesData_hold(tipe) 
	{
		// let cek = confirm("Apakah Anda Yakin?");

		swal({
            //title: 'PENDAFTARAN',
            text                : "Alasan di Hold : ",
            type                : 'info',
            input               : 'text',
            showCancelButton    : true,			
			confirmButtonClass : 'btn btn-warning',
			cancelButtonClass  : 'btn btn-secondary',
			confirmButtonColor  : '#ffc107',
			cancelButtonColor  : '#d33',
            confirmButtonText   : '<b><i class="far fa-hand-paper"></i> Hold </b>',
            cancelButtonText    : '<b><i class="fa fa-undo" ></i> Batal </b>'
        }).then(function(alasan) {

			if(alasan==''){
				swal({
					title               : "Alasan",
					html                : "Wajib Di Isi !",
					type                : "error",
					confirmButtonText   : "OK"
				});
				prosesData_hold(tipe);
				// return;
			}else{
				
				$.ajax({
					url: '<?= base_url(); ?>Transaksi/prosesData',
					data: ({
						id: no_po,
						status: tipe,
						alasan: alasan,
						jenis: 'verifPO'
					}),
					type: "POST",
					success: function(data) {
						toastr.success('<b>Data Berhasil Diproses</b>');
						// swal({
						// 	title               : "Data",
						// 	html                : "Data Berhasil Diproses",
						// 	type                : "success",
						// 	confirmButtonText   : "OK"
						// });
						reloadTable();
						$("#modalForm").modal("hide");
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
				
			}
		
		});


	}
	
	function prosesData_r(tipe) 
	{
		// let cek = confirm("Apakah Anda Yakin?");

		swal({
            //title: 'PENDAFTARAN',
            text                : "Alasan di Reject : ",
            type                : 'info',
            input               : 'text',
            showCancelButton    : true,			
			confirmButtonClass : 'btn btn-danger',
			cancelButtonClass  : 'btn btn-secondary',
			confirmButtonColor  : '#d33',
			cancelButtonColor  : '#d33',
            confirmButtonText   : '<b><i class="fas fa-times-circle"></i> Reject </b>',
            cancelButtonText    : '<b><i class="fas fa-undo"></i> Batal </b>'
        }).then(function(alasan) {

			if(alasan==''){
				swal({
					title               : "Alasan",
					html                : "Wajib Di Isi !",
					type                : "error",
					confirmButtonText   : "OK"
				});
				prosesData_r(tipe);
				// return;
			}else{

				$.ajax({
					url: '<?= base_url(); ?>Transaksi/prosesData',
					data: ({
						id: no_po,
						status: tipe,
						alasan: alasan,
						jenis: 'verifPO'
					}),
					type: "POST",
					success: function(data) {
						toastr.success('Data Berhasil Diproses');
						// swal({
						// 	title               : "Data",
						// 	html                : "Data Berhasil Diproses",
						// 	type                : "success",
						// 	confirmButtonText   : "OK"
						// });
						reloadTable();
						$("#modalForm").modal("hide");
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
			}
		
		});


	}

	$("#id_pelanggan").change(function() 
	{
		if ($("#id_pelanggan").val() == "") {
			return;
		}

		arr_detail = $('#id_pelanggan option:selected').attr('detail');

		if (typeof arr_detail === 'undefined') {
			return;
		}

		arr_detail = arr_detail.split("|");
		// console.log(arr_detail);

		// var kab_name  = (arr_detail[0] == '' ) ? '-' : arr_detail[0] ;
		var kab_name  = (arr_detail[0] == '' || arr_detail[0] == null ) ? '-' : arr_detail[0].trim() ;
		var telp      = (arr_detail[1] == '' ) ? '-' : arr_detail[1] ;
		var fax       = (arr_detail[2] == '' || arr_detail[2] == null ) ? '-' : arr_detail[2] ;
		var top       = (arr_detail[3] == '' || arr_detail[3] == null ) ? '-' : arr_detail[3] ;
		var sales     = (arr_detail[4] == '' || arr_detail[4] == null ) ? '-' : arr_detail[4] ;
		
		$("#txt_kota").val(kab_name);
		$("#txt_no_telp").val(telp);
		$("#txt_fax").val(fax);
		$("#txt_top").val(top);
		$("#txt_marketing").val(sales);

	});

	function setDetailProduk(kd,id) 
	{
		
		// if ($("#id_produk" + e).val() == "") {
		// 	return;
		// }

		// arr_detail = $('#id_produk' + e + ' option:selected').attr('detail');

		// if (typeof arr_detail === 'undefined') {
		// 	return;
		// }

		// arr_detail = arr_detail.split("|");
		// // console.log(arr_detail);
		if(kd!=''){
			// show_loading();
			html_produk="";
			$.ajax({
				type        : 'POST',
				url         : "<?= base_url(); ?>Transaksi/load_produk_1",
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
						}else if (val.sambungan == 'DS'){
							$join = 'Double Stiching';
						}else if (val.sambungan == 'GS'){
							$join = 'Glue Stiching';
						}else {
							$join = '-';
						}
						var qty_po              = $("#qty"+id).val()
						var monitoring_kirim    = Math.round((qty_po.split('.').join('')/45))*7
						var tgl_po              = $("#tgl_po").val()

						inputHari         = 45;
						var hariKedepan   = new Date(new Date(tgl_po).getTime() + (inputHari * 24 * 60 * 60 * 1000));

						var tgl_kirim = tanggal_format_indonesia(hariKedepan.toISOString().slice(0, 10));
						
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
							</tr>
							<tr style=list-style:none;>
								<td colspan="3"><b>Monitoring Pengiriman </b>: ${monitoring_kirim} Pcs / Minggu
								</td>
							</tr>`;
							<?php if ($this->session->userdata('level') != "PPIC"){ ?>
							html_produk += `
							<tr style=list-style:none;> 
								<td>
									<b>Harga / Kg : 
									</b>
									<input type="text" class="input-border-none" name="hrg_kg[${id}]" id="hrg_kg${id}" readonly >
								</td> 
								<td>
									<b>QTY PO : 
									</b> ${qty_po}
								</td> 
								<td>
									<b>Max Kirim PO: 
									</b> ${tgl_kirim}
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
							<select class="form-control select2" name="id_produk[${ rowNum }]" id="id_produk${ rowNum }"  onchange="setDetailProduk(this.value,${ rowNum })">
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
					<tr id="item_tambahan${ rowNum }">
						<td>
							<div class="text-center">
								ETA
							</div>
						</td>
						<td>
							<input class="form-control" type="date" name="eta_item[${ rowNum }]" id="eta_item${ rowNum }">
						</td>
						<td>
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
			exc = Math.round(isi /1.11);
			$('#price_exc'+id2).val(format_angka(exc));

			// $('#price_exc_rp'+id2).val(format_angka(exc));
			// $('#price_inc_rp'+id2).val(format_angka(val));
		}
	}
	

	function hitung_p11(val,id)
	{
		<?php if ($this->session->userdata('level') != "PPIC"){ ?>
		
		var kode_po   = $('#kode_po').val()
		var cek       = id.substr(0,9);
		var id2       = id.substr(9,1);
		var value     = (val=='') ? 0 : val;
		var value     = value.split('.').join('');

		if(cek=='price_exc')
		{
			var inc = $('#price_inc'+id2).val().split('.').join('');
			var exc = value;
		}else {
			var inc = value;
			var exc = $('#price_exc'+id2).val().split('.').join('');
		}

		var produk   = $('#id_produk'+id2).val();

		$.ajax({
			type        : 'POST',
			url         : "<?= base_url(); ?>Transaksi/load_produk_1",
			data        : { idp: '', kd: produk },
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
						hrg_kg   = Math.trunc(exc / val.berat_bersih); 
						$('#hrg_kg'+id2).val(format_angka(hrg_kg));	

						if(val.kategori=='K_SHEET')
						{
							if(val.flute=='BCF')
							{
								$.ajax({
									type        : 'POST',
									url         : "<?= base_url(); ?>Transaksi/cek_bcf",
									data        : {kd: val.kualitas },
									dataType    : 'json',
									success:function(data){		
										
										var subs    = val.kualitas.split("/");
										var subs2   = subs[1].substring(1,4);
										var subs3   = subs[2].substring(1,4);
										var subs4   = subs[3].substring(1,4);

										var cek1    = (subs2=='150') ? 300 : 0;
										var cek2    = (subs3=='150') ? 300 : 0;
										var cek3    = (subs4=='150') ? 300 : 0;

										var totsub      = cek1 + cek2 + cek3 + data.bcf;

										var rumus = totsub * (val.ukuran_sheet_p/1000) * (val.ukuran_sheet_l/1000);

										var selisih = inc - rumus;

										p11 = selisih / rumus * 100;
										if(status=='insert')
										{
											$('#p11'+id2).val( p11.toFixed(1)+' %');
										}else{
											$('#p11'+id2).val(''+ p11.toFixed(1)+' %');											
										}
										swal.close();


									}
								});
								
							} else {
								$.ajax({
									type        : 'POST',
									url         : "<?= base_url(); ?>Transaksi/cek_flute",
									data        : {kd: val.kualitas, flute : val.flute },
									dataType    : 'json',
									success:function(data2){		
										
										var subs    = val.kualitas.split("/");
										var subs2   = subs[1].substring(1,4);

										var cek1    = (subs2=='150') ? 300 : 0;

										var totsub  = cek1 + data2.flute;

										var rumus   = totsub * (val.ukuran_sheet_p/1000) * (val.ukuran_sheet_l/1000);

										var selisih = inc - rumus;

										p11         = selisih / rumus * 100;

										$('#p11'+id2).val(p11.toFixed(1)+' %');
										swal.close();

									}
								});
							}
						} else {
							p11 = exc/ val.berat_bersih;
							$('#p11'+id2).val( '- 0 %');
							swal.close();

						}
					
			}
		});
		<?php } else {?>
		<?php } ?>
	}

	function Hitung_rm(qty,id) 
	{
		var cek       = id.substr(0,3);
		var id2       = id.substr(3,1);
		var cek_rm    = $('#cek_rm'+id2).val();
		
		var produk    = $('#id_produk'+id2).val();
		var qty       = qty.split('.').join('');
		
		if(produk=='' || produk=='undefined' || produk=='-- Pilih --'){
			// toastr.error('Pilih Produk Dahulu');
			swal({
				title: "Produk Kosong",
				text: "Pilih Produk Dahulu !",
				type: "error",
				confirmButtonText: "OK"
			});
			
			$('#'+id).val(0);
			$('#'+id).focus();
			return;
		}else{
			// hitung out
			
			$.ajax({
				type        : 'POST',
				url         : "<?= base_url(); ?>Transaksi/load_produk_1",
				data        : { idp: '', kd: produk },
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
					
					out = Math.trunc(1800/val.ukuran_sheet_l);
					if(out >= 5){
						out = 5;
					}

					rm       = Math.ceil(val.ukuran_sheet_p * qty / out / 1000);
					ton      = Math.ceil(qty * val.berat_bersih);
					

					if(cek_rm==1)
					{

					}else{
						if(rm < 500 && status !=='update'){			
						// toastr.error(
						// 	'RM tidak boleh di Bawah 500, <br> Hubungi Marketing'
						// );

						swal({
							title               : "Cek Kembali",
							html                : " Tidak boleh di Bawah 500 ! <br> Hubungi Marketing </b> ",
							type                : "error",
							confirmButtonText   : "OK"
						});
						$("#"+id).val("0");
						$("#qty"+id2).val("0");
						return;
					}	

					}
					

					$('#rm'+id2).val(format_angka(rm));	
					$('#ton'+id2).val(format_angka(ton));
					swal.close();	
						
				}
			});

		}
		
	}

	function cek_kode_po(kode_po)
	{
		$.ajax({
				type        : 'POST',
				url         : "<?= base_url(); ?>Transaksi/cek_kode",
				data        : { kode_po },
				dataType    : 'json',
				success:function(val){		

					if(val.jum>0){
						swal({
							title               : "Cek Kembali",
							html                : "KODE PO SUDAH PERNAH DI PAKAI",
							type                : "error",
							confirmButtonText   : "OK"
						});
						$('#kode_po').val('');
						$('#kode_po').focus();
						return; 
					}
						
				}
			});
	}

	function Cetak() 
	{
		no_po = $("#no_po").val();
		var url = "<?= base_url('Transaksi/Cetak_PO'); ?>";
		window.open(url + '?no_po=' + no_po, '_blank');
	}

	function on_load(kd,id)
	{
		var no_po   = $("#no_po").val();
		var qty     = $('#qty'+id).val().split('.').join('');
		
		$("#ii_lebar0").prop("disabled", false);		
		$("#qty_plan0").prop("disabled", false);		
		$("#i_lebar_roll0").prop("disabled", false);		
		$("#out_plan0").prop("disabled", false);		
		
		$.ajax({
			type        : 'POST',
			url         : "<?= base_url(); ?>Transaksi/cek_plan_sementara",
			data        : { no_po : no_po, id_produk : kd },
			dataType    : 'json',
			success:function(val_cek){
				if(val_cek==0)
				{
					$.ajax({
						type        : 'POST',
						url         : "<?= base_url(); ?>Transaksi/load_produk_1",
						data        : { idp: '', kd: kd },
						dataType    : 'json',
						success:function(val2)
						{
							
							var substance = val2.material.split("/");				
							var gramature = val2.kualitas_isi.split("/");

							if(val2.flute=='BF')
							{
								var s1 = substance[0];
								var s2 = substance[1];
								var s3 = substance[2];
								var s4 = '';
								var s5 = '';
								
								var grm1 = gramature[0];
								var grm2 = gramature[1];
								var grm3 = gramature[2];
								var grm4 = '';
								var grm5 = '';

								$("#tl_al"+id).prop('disabled', false)
								$("#bmf"+id).prop('disabled', false)
								$("#bl"+id).prop('disabled', false)
								$("#cmf"+id).prop('disabled', true)
								$("#cl"+id).prop('disabled', true)

								$("#tl_al_i"+id).prop('disabled', false)
								$("#bmf_i"+id).prop('disabled', false)
								$("#bl_i"+id).prop('disabled', false)
								$("#cmf_i"+id).prop('disabled', true)
								$("#cl_i"+id).prop('disabled', true)

							}else if(val2.flute=='CF') 
							{
								
								var s1 = substance[0];
								var s2 = '';
								var s3 = '';
								var s4 = substance[1];
								var s5 = substance[2];
								
								var grm1 = gramature[0];
								var grm2 = '';
								var grm3 = '';
								var grm4 = gramature[1];
								var grm5 = gramature[2];
								
								$("#tl_al"+id).prop('disabled', false)
								$("#bmf"+id).prop('disabled', true)
								$("#bl"+id).prop('disabled', true)
								$("#cmf"+id).prop('disabled', false)
								$("#cl"+id).prop('disabled', false)

								$("#tl_al_i"+id).prop('disabled', false)
								$("#bmf_i"+id).prop('disabled', true)
								$("#bl_i"+id).prop('disabled', true)
								$("#cmf_i"+id).prop('disabled', false)
								$("#cl_i"+id).prop('disabled', false)

							}else if(val2.flute=='BCF') {
													
								var s1 = substance[0];
								var s2 = substance[1];
								var s3 = substance[2];
								var s4 = substance[3];
								var s5 = substance[4];

								var grm1 = gramature[0];
								var grm2 = gramature[1];
								var grm3 = gramature[2];
								var grm4 = gramature[3];
								var grm5 = gramature[4];

								$("#tl_al"+id).prop('disabled', false)
								$("#bmf"+id).prop('disabled', false)
								$("#bl"+id).prop('disabled', false)
								$("#cmf"+id).prop('disabled', false)
								$("#cl"+id).prop('disabled', false)

								$("#tl_al_i"+id).prop('disabled', false)
								$("#bmf_i"+id).prop('disabled', false)
								$("#bl_i"+id).prop('disabled', false)
								$("#cmf_i"+id).prop('disabled', false)
								$("#cl_i"+id).prop('disabled', false)

							}

							$("#tl_al"+id).val(s1).trigger('change');
							$("#bmf"+id).val(s2).trigger('change');
							$("#bl"+id).val(s3).trigger('change');
							$("#cmf"+id).val(s4).trigger('change');
							$("#cl"+id).val(s5).trigger('change');
							
							$("#tl_al_i"+id).val(grm1);
							$("#bmf_i"+id).val(grm2);
							$("#bl_i"+id).val(grm3);
							$("#cmf_i"+id).val(grm4);
							$("#cl_i"+id).val(grm5);

							out = Math.trunc(1800/val2.ukuran_sheet_l);
							if(out >= 5){
								out = 5;
							}

							rm_plan    = Math.ceil(val2.ukuran_sheet_p * qty / out / 1000);
							ton_plan   = Math.ceil(qty * val2.berat_bersih);
							c_off_plan = Math.ceil(qty/out)

							
							$("#ii_lebar"+id).val(format_angka(val2.ukuran_sheet_l));
							$("#qty_plan"+id).val(format_angka(qty));
							$("#i_lebar_roll"+id).val(0);
							$("#trim"+id).val(0);
							$("#c_off"+id).val(format_angka(c_off_plan));
							$("#out_plan"+id).val(out);
							$("#rm_plan"+id).val(format_angka(rm_plan));
							$("#ton_plan"+id).val(format_angka(ton_plan));

						}

					});
				}else{
							
					$.ajax({
					type        : 'POST',
					url         : "<?= base_url(); ?>Transaksi/plan_sementara",
					data        : { no_po : no_po, id_produk : kd },
					dataType    : 'json',
					success:function(val_plan){
						
							var substance = val_plan.material_plan.split("/");				
							var gramature = val_plan.kualitas_isi_plan.split("/");

							if(val_plan.flute=='BF')
							{
								var s1 = substance[0];
								var s2 = substance[1];
								var s3 = substance[2];
								var s4 = '';
								var s5 = '';
								
								var grm1 = gramature[0];
								var grm2 = gramature[1];
								var grm3 = gramature[2];
								var grm4 = '';
								var grm5 = '';

								$("#tl_al"+id).prop('disabled', false)
								$("#bmf"+id).prop('disabled', false)
								$("#bl"+id).prop('disabled', false)
								$("#cmf"+id).prop('disabled', true)
								$("#cl"+id).prop('disabled', true)

								$("#tl_al_i"+id).prop('disabled', false)
								$("#bmf_i"+id).prop('disabled', false)
								$("#bl_i"+id).prop('disabled', false)
								$("#cmf_i"+id).prop('disabled', true)
								$("#cl_i"+id).prop('disabled', true)

							}else if(val_plan.flute=='CF') 
							{
								
								var s1 = substance[0];
								var s2 = '';
								var s3 = '';
								var s4 = substance[1];
								var s5 = substance[2];
								
								var grm1 = gramature[0];
								var grm2 = '';
								var grm3 = '';
								var grm4 = gramature[1];
								var grm5 = gramature[2];
								
								$("#tl_al"+id).prop('disabled', false)
								$("#bmf"+id).prop('disabled', true)
								$("#bl"+id).prop('disabled', true)
								$("#cmf"+id).prop('disabled', false)
								$("#cl"+id).prop('disabled', false)

								$("#tl_al_i"+id).prop('disabled', false)
								$("#bmf_i"+id).prop('disabled', true)
								$("#bl_i"+id).prop('disabled', true)
								$("#cmf_i"+id).prop('disabled', false)
								$("#cl_i"+id).prop('disabled', false)

							}else if(val_plan.flute=='BCF') {
													
								var s1 = substance[0];
								var s2 = substance[1];
								var s3 = substance[2];
								var s4 = substance[3];
								var s5 = substance[4];

								var grm1 = gramature[0];
								var grm2 = gramature[1];
								var grm3 = gramature[2];
								var grm4 = gramature[3];
								var grm5 = gramature[4];

								$("#tl_al"+id).prop('disabled', false)
								$("#bmf"+id).prop('disabled', false)
								$("#bl"+id).prop('disabled', false)
								$("#cmf"+id).prop('disabled', false)
								$("#cl"+id).prop('disabled', false)

								$("#tl_al_i"+id).prop('disabled', false)
								$("#bmf_i"+id).prop('disabled', false)
								$("#bl_i"+id).prop('disabled', false)
								$("#cmf_i"+id).prop('disabled', false)
								$("#cl_i"+id).prop('disabled', false)

							}

							
							$("#tl_al"+id).val(s1).trigger('change');
							$("#bmf"+id).val(s2).trigger('change');
							$("#bl"+id).val(s3).trigger('change');
							$("#cmf"+id).val(s4).trigger('change');
							$("#cl"+id).val(s5).trigger('change');
							
							$("#tl_al_i"+id).val(grm1);
							$("#bmf_i"+id).val(grm2);
							$("#bl_i"+id).val(grm3);
							$("#cmf_i"+id).val(grm4);
							$("#cl_i"+id).val(grm5);

							$("#ii_lebar"+id).val(format_angka(val_plan.lebar_plan));
							$("#qty_plan"+id).val(format_angka(val_plan.qty_plan));
							$("#i_lebar_roll"+id).val(format_angka(val_plan.lebar_roll_p));
							$("#out_plan"+id).val(val_plan.out_plan);
							$("#trim"+id).val(format_angka(trim_plan));
							$("#c_off"+id).val(format_angka(c_off_p));
							$("#rm_plan"+id).val(format_angka(rm_plan));
							$("#ton_plan"+id).val(format_angka(tonase_plan));
					}
					});

										
				}
			}
		});
		
		
	}

	function ayoBerhitung(id)
	{
		if(status=='insert'){

		}else{

			// ambil
			var flute           = $("#fl"+id).val().split('.').join('')
			var p_sheet         = $("#p_sheet"+id).val().split('.').join('')

			var tl_al           = $("#tl_al"+id).val().split('.').join('')
			var bmf             = $("#bmf"+id).val().split('.').join('')
			var bl              = $("#bl"+id).val().split('.').join('')
			var cmf             = $("#cmf"+id).val().split('.').join('')
			var cl              = $("#cl"+id).val().split('.').join('')
			
			var tl_al_i         = $("#tl_al_i"+id).val().split('.').join('')
			var bmf_i           = $("#bmf_i"+id).val().split('.').join('')
			var bl_i            = $("#bl_i"+id).val().split('.').join('')
			var cmf_i           = $("#cmf_i"+id).val().split('.').join('')
			var cl_i            = $("#cl_i"+id).val().split('.').join('')

			var ii_lebar        = $("#ii_lebar"+id).val().split('.').join('')
			var qty_plan        = $("#qty_plan"+id).val().split('.').join('')
			var i_lebar_roll    = $("#i_lebar_roll"+id).val().split('.').join('')
			var out_plan        = $("#out_plan"+id).val().split('.').join('')
			var trim            = $("#trim"+id).val().split('.').join('')
			var c_off           = $("#c_off"+id).val().split('.').join('')
			var rm_plan         = $("#rm_plan"+id).val().split('.').join('')
			var ton_plan        = $("#ton_plan"+id).val().split('.').join('')

			// hitung

			
			
			if(flute == 'BF'){

				bb =  parseFloat((parseInt(tl_al_i) + (parseFloat(bmf_i)*1.36) + parseInt(bl_i)) / 1000 * p_sheet / 1000 * ii_lebar / 1000) ;

			}else if(flute == 'CF'){

				bb = parseFloat(parseInt(tl_al_i) + (parseFloat(cmf_i)*1.46) + parseInt(cl_i)) / 1000 * p_sheet / 1000 * ii_lebar / 1000 ;

			}else if(flute == 'BCF'){


				bb = parseFloat(parseInt(tl_al_i) + (parseFloat(bmf_i)*1.36) + parseInt(bl_i) + (parseFloat(cmf_i)*1.46) + parseInt(cl_i)) / 1000 * p_sheet / 1000 * ii_lebar / 1000 ;

			}else{
				bb = 0
			}

			trim_plan   = Math.round(i_lebar_roll - (ii_lebar * out_plan))
			c_off_p     = Math.round(qty_plan / out_plan)
			rm_plan     = Math.round(c_off_p * p_sheet / 1000)
			tonase_plan = Math.ceil(qty_plan * bb)

			// isi
			$("#trim"+id).val(format_angka(trim_plan));
			$("#c_off"+id).val(format_angka(c_off_p));
			$("#rm_plan"+id).val(format_angka(rm_plan));
			$("#ton_plan"+id).val(format_angka(tonase_plan));

		}


		
	}

	function cekrm(id)
	{
		var cekk    = id.substr(0,6);
		var id2     = id.substr(6,2);
		
		var cek     = $('#cek_rm'+id2).val();

		if (cek == 0) {
			$('#cek_rm'+id2).val(1);
			$("#qty"+id2).val(0);
			$('#rm'+id2).val(0);
		} else {
			$('#cek_rm'+id2).val(0);
			$("#qty"+id2).val(0);
			$('#rm'+id2).val(0);
		}
	}
	
</script>
