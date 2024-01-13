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
			margin: ;
		}
		
	</style>
	<?php $bigsize ="font-size:30px;" ?>
	<section class="content" style="padding-bottom:3px">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
						<div class="card card-secondary card-outline" style="padding-bottom:20px">
							<div class="card-header">
								<h3 class="card-title" style="font-weight:bold;font-style:italic;"><?= $menu ?></h3>
							</div>
							<br>
							<form id="hitung" >
								<div class="card-body row" style="font-weight:bold; <?=$bigsize?>">
									<div class="col-md-3" style="margin-bottom:3px">
									Type :
										<select id="type" name="type" class="form-control select2" onchange="ayoBerhitung(), cek_type(this.value)">
											<option value="b">Box</option>
											<option value="s">Sheet</option>
										</select>
									</div>
									
									<div class="col-md-12">&nbsp;</div>
									<br>
									
									<div class="col-md-3" style="margin-bottom:3px">
									Flute :
										<select id="flute" name="flute" class="form-control select2" onchange="ayoBerhitung(),cek_flute(this.value)" style="<?=$bigsize?>">
											<option value="BCF">BCF</option>
											<option value="BF">BF</option>
											<option value="CF">CF</option>
										</select>
									</div>
									
									<div class="col-md-12">&nbsp;</div>
									
									<div class="col-md-3" style="margin-bottom:3px">
									Ukuran : <br>
									</div>
									<div class="col-md-12"></div>

									<div class="col-md-3" style="margin-bottom:3px;">
										<input type="text" class="form-control angka" id="l_panjang" placeholder="P" maxlength="4" onkeyup="ubah_angka(this.value,this.id)" autocomplete="off" onchange="ayoBerhitung()" style="<?=$bigsize?>">
									</div>
									<div class="col-md-3" style="margin-bottom:3px;">
										<input type="text" class="form-control angka" id="l_lebar" placeholder="L" maxlength="4" onkeyup="ubah_angka(this.value,this.id)" autocomplete="off" onchange="ayoBerhitung()" style="<?=$bigsize?>">									
									</div>
									<div class="col-md-3" style="margin-bottom:3px;<?=$bigsize?>">
										<input type="text" class="form-control angka" id="l_tinggi" placeholder="T" maxlength="4" onkeyup="ubah_angka(this.value,this.id)" autocomplete="off" onchange="ayoBerhitung()" style="<?=$bigsize?>">				
									</div>
									
									<div class="col-md-12">&nbsp;</div>
									
									<div class="col-md-3" style="margin-bottom:3px;">
									Ukuran Sheet : <br>
									</div>
									<div class="col-md-12"></div>

									<div class="col-md-3" style="margin-bottom:3px;">
										<input type="text" class="form-control angka" id="p_sheet" placeholder="P" onkeyup="ubah_angka(this.value,this.id)" autocomplete="off" style="<?=$bigsize?>" readonly>
										
									</div>
									<div class="col-md-3" style="margin-bottom:3px;<?=$bigsize?>">
										<input type="text" class="form-control angka" id="l_sheet" placeholder="L" onkeyup="ubah_angka(this.value,this.id)" autocomplete="off" style="<?=$bigsize?>" readonly>									
									</div>
									
									<div class="col-md-12">&nbsp;</div>
									<br>
									<div class="col-md-3" style="margin-bottom:3px;">
									Gramature : 
										<input type="text" id="tl_al_i" name="tl_al_i"  class="form-control angka" autocomplete="off" placeholder="TL/AL" onchange="ayoBerhitung()" style="<?=$bigsize?>">

										<input type="text" id="bmf_i" name="bmf_i" class="form-control angka" autocomplete="off" placeholder="B.MF" onchange="ayoBerhitung()" style="<?=$bigsize?>">

										<input type="text" id="bl_i" name="bl_i" class="form-control angka" autocomplete="off" placeholder="B.L" onchange="ayoBerhitung()" style="<?=$bigsize?>">

										<input type="text" id="cmf_i" name="cmf_i" class="form-control angka" autocomplete="off" placeholder="C.MF" onchange="ayoBerhitung()" style="<?=$bigsize?>">

										<input type="text" id="cl_i" name="cl_i" class="form-control angka" autocomplete="off" placeholder="C.L" onchange="ayoBerhitung()" style="<?=$bigsize?>">
									</div>

									
									<div class="col-md-12">&nbsp;</div>

									<div class="col-md-3" style="margin-bottom:3px">
									BB : <br>
									</div>
									<div class="col-md-12"></div>

									<div class="col-md-3" style="margin-bottom:3px">
										<input type="text" class="form-control angka" id="bb" placeholder="BB" autocomplete="off" style="<?=$bigsize?>" readonly>
										
									</div>

									<div class="col-md-12">&nbsp;</div>

									<div class="col-md-3" style="margin-bottom:3px">
									Harga / kg : 
										<input type="text" class="form-control angka" id="hrg_kg" placeholder="Harga" onkeyup="ubah_angka(this.value,this.id),hitung_inc_exc(this.value)" autocomplete="off" style="<?=$bigsize?>" >
									</div>
									<div class="col-md-3" style="margin-bottom:3px">
									Include : 
										<input type="text" class="form-control angka" id="include" placeholder="Include" onkeyup="ubah_angka(this.value,this.id),Hitung_price(this.value,this.id)" autocomplete="off" style="<?=$bigsize?>" >									
									</div>
									<div class="col-md-3" style="margin-bottom:3px">
									Exclude : 
										<input type="text" class="form-control angka" id="exclude" placeholder="Exclude" onkeyup="ubah_angka(this.value,this.id),Hitung_price(this.value,this.id)" autocomplete="off" style="<?=$bigsize?>" >			
									</div>
									<div class="col-md-12"></div>

									<div class="col-md-12">&nbsp;</div>

									<div class="col-md-3" style="margin-bottom:3px">
									QTY : 
										<input type="text" class="form-control angka" id="qty" placeholder="Qty" onkeyup="ubah_angka(this.value,this.id),Hitung_rm(this.value)" autocomplete="off" style="<?=$bigsize?>">
									</div>
									<div class="col-md-3" style="margin-bottom:3px">
									RM : 
										<input type="text" class="form-control angka" id="rm" placeholder="rm" onkeyup="ubah_angka(this.value,this.id)" autocomplete="off" style="<?=$bigsize?>"readonly>									
									</div>
									<div class="col-md-3" style="margin-bottom:3px">
									Tonase : 
										<input type="text" class="form-control angka" id="ton" placeholder="Tonase" onkeyup="ubah_angka(this.value,this.id)" autocomplete="off" style="<?=$bigsize?>"readonly>			
									</div>
									<div class="col-md-12"></div>

									<div class="col-md-12">&nbsp;</div>

									<input class="btn btn-danger" type="reset" name="" id="" value="RESET" onclick="kosong()">
									
									<!-- <input class="btn btn-success" type="button" name="" id="" value="COBA API" onclick="Coba_api()"> -->
									
								</div>
							</form>
							<br/>
						</div>
					
				</div>

			</div>
		</div>
	</section>
</div>


<script type="text/javascript">

	$(document).ready(function ()
	{
		$('.select2').select2({
			containerCssClass: "wrap",
			placeholder: '--- Pilih ---',
			dropdownAutoWidth: true
		});
	})

	function cek_type(vall)
	{
		if(vall=='b')
		{
			$("#l_panjang").prop("disabled",false)
			$("#l_lebar").prop("disabled",false)
			$("#l_tinggi").prop("disabled",false)
		}else{

			$("#l_panjang").prop("disabled",false)
			$("#l_lebar").prop("disabled",false)
			$("#l_tinggi").prop("disabled",true)

		}
	}
	
	function cek_flute(vall)
	{
		if(vall=='BCF')
		{
			// $("#tl_al").prop("disabled",false)
			// $("#bmf").prop("disabled",false)
			// $("#bl").prop("disabled",false)
			// $("#cmf").prop("disabled",false)
			// $("#cl").prop("disabled",false)

			$("#tl_al_i").prop("disabled",false)
			$("#bmf_i").prop("disabled",false)
			$("#bl_i").prop("disabled",false)
			$("#cmf_i").prop("disabled",false)
			$("#cl_i").prop("disabled",false)

			$("#tl_al_i").val('')
			$("#bmf_i").val('')
			$("#bl_i").val('')
			$("#cmf_i").val('')
			$("#cl_i").val('')

		}else if(vall=='BF')
		{
			// $("#tl_al").prop("disabled",false)
			// $("#bmf").prop("disabled",false)
			// $("#bl").prop("disabled",false)
			// $("#cmf").prop("disabled",true)
			// $("#cl").prop("disabled",true)

			$("#tl_al_i").prop("disabled",false)
			$("#bmf_i").prop("disabled",false)
			$("#bl_i").prop("disabled",false)
			$("#cmf_i").prop("disabled",true)
			$("#cl_i").prop("disabled",true)
			
			$("#cmf_i").val(0)
			$("#cl_i").val(0)
			$("#tl_al_i").val('')
			$("#bmf_i").val('')
			$("#bl_i").val('')

		}else{

			// $("#tl_al").prop("disabled",false)
			// $("#bmf").prop("disabled",true)
			// $("#bl").prop("disabled",true)
			// $("#cmf").prop("disabled",false)
			// $("#cl").prop("disabled",false)

			$("#tl_al_i").prop("disabled",false)
			$("#bmf_i").prop("disabled",true)
			$("#bl_i").prop("disabled",true)
			$("#cmf_i").prop("disabled",false)
			$("#cl_i").prop("disabled",false)
			
			$("#bmf_i").val(0)
			$("#bl_i").val(0)
			$("#tl_al_i").val('')
			$("#cmf_i").val('')
			$("#cl_i").val('')
		}
	}

	function ayoBerhitung()
	{
		var type          = $("#type").val()
		var flute         = $("#flute").val()
		var l_panjang_1   = $("#l_panjang").val()
		var l_lebar_1     = $("#l_lebar").val()
		var l_tinggi_1    = $("#l_tinggi").val()

		var l_panjang_2   = (l_panjang_1=='') ? "0" : l_panjang_1
		var l_lebar_2     = (l_lebar_1=='') ? "0" : l_lebar_1
		var l_tinggi_2    = (l_tinggi_1=='') ? "0" : l_tinggi_1

		
		var l_panjang     = parseFloat(l_panjang_2.split('.').join(''))
		var l_lebar       = parseFloat(l_lebar_2.split('.').join(''))
		var l_tinggi      = parseFloat(l_tinggi_2.split('.').join(''))
		// var tl_al         = $("#tl_al").val()
		// var bmf           = $("#bmf").val()
		// var bl            = $("#bl").val()
		// var cmf           = $("#cmf").val()
		// var cl            = $("#cl").val()
		var tl_al_i       = parseFloat( $("#tl_al_i").val() )
		var bmf_i         = parseFloat( $("#bmf_i").val() )
		var bl_i          = parseFloat( $("#bl_i").val() )
		var cmf_i         = parseFloat( $("#cmf_i").val() )
		var cl_i          = parseFloat( $("#cl_i").val() )

		// hitung sheet
		var p_sheet       = ''
		var l_sheet       = ''
		var bb            = 0

		if(type == "b")
		{
			if(l_panjang == '' || l_panjang == 0 || l_lebar == '' || l_lebar == 0 || l_tinggi == 0 || l_tinggi == ''){
				p_sheet = 0 ;
				l_sheet = 0 ;
			}else{
				if(flute == ""){
					p_sheet = 0 ;
					l_sheet = 0 ;
				} else if(flute == "BCF"){
					p_sheet = 2 * (l_panjang + l_lebar) + 61;
					l_sheet = l_lebar + l_tinggi + 23;

				} else if(flute == "CF") {
					p_sheet = 2 * (l_panjang + l_lebar) + 43;
					l_sheet = l_lebar + l_tinggi + 13;

				} else if(flute == "BF") {
					p_sheet = 2 * (l_panjang + l_lebar) + 39;
					l_sheet = l_lebar + l_tinggi + 9;

				} else {
					p_sheet = 0;
					l_sheet = 0;
				}
			}
		}else{
			if(l_panjang == '' || l_panjang == 0 || l_lebar == '' || l_lebar == 0){
				p_sheet = 0;
				l_sheet = 0;
			}else{
				p_sheet = l_panjang;
				l_sheet = l_lebar;
			}
		}
		document.getElementById('p_sheet').value = format_angka(p_sheet);
		document.getElementById('l_sheet').value = format_angka(l_sheet);

		// berat box
		if(flute == 'BF'){
			bb =  parseFloat((parseInt(tl_al_i) + (parseFloat(bmf_i)*1.36) + parseInt(bl_i)) / 1000 * p_sheet / 1000 * l_sheet / 1000) ;

			bb = (isNaN(bb)) ? 0 : bb ;

		}else if(flute == 'CF'){
			bb = parseFloat(parseInt(tl_al_i) + (parseFloat(cmf_i)*1.46) + parseInt(cl_i)) / 1000 * p_sheet / 1000 * l_sheet / 1000 ;
			

		}else if(flute == 'BCF'){

			bb = parseFloat(parseInt(tl_al_i) + (parseFloat(bmf_i)*1.36) + parseInt(bl_i) + (parseFloat(cmf_i)*1.46) + parseInt(cl_i)) / 1000 * p_sheet / 1000 * l_sheet / 1000 ;

			bb = (isNaN(bb)) ? 0 : bb ;

		}else{
			bb = 0
		}
		// harga / kg

		if(type == "b")
		{
			var bb = bb.toFixed(4)
		}else{
			var bb = bb.toFixed(3)
		}
		
		$('#bb').val(String(bb).split('.').join(','))

	}

	function Hitung_price(val,cek) 
	{
		var isi   = val.split('.').join('')
		var bb    = $('#bb').val()
		var bb_ok = String(bb).split(',').join('.')

		if(cek=='exclude')
		{
			var exc    = String($("#exclude").val()).split('.').join('')
			var inc    = Math.trunc(isi *1.11)
			$('#include').val(format_angka(inc));
			
		} else {
			var inc    = String($("#include").val()).split('.').join('')
			var exc    = Math.trunc(isi /1.11)
			$('#exclude').val(format_angka(exc));
			
		}
		
		hrg_kg   = Math.trunc(exc / parseFloat(bb_ok)); 
		$('#hrg_kg').val(format_angka(hrg_kg));	

		

	}

	function Coba_api() 
	{
		$.ajax({
			url: '<?= base_url('Coba_api/coba_api'); ?>',
			type: 'GET',
			dataType: "JSON",
			success: function(data) {
				console.log(data);
				
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
	
	function hitung_inc_exc(vall)
	{
		var bb_ok   = String($("#bb").val()).split(',').join('.')
		var hrg_kg  = String(vall).split('.').join('')
		var exc     = parseFloat(hrg_kg)*parseFloat(bb_ok)
		var inc     = parseFloat(exc) *1.11

		$('#exclude').val(format_angka(exc));	
		$('#include').val(format_angka(inc));	
	}

	function Hitung_rm(qty) 
	{
		var qty       = String($("#qty").val()).split('.').join('')
		var p_sheet   = String($("#p_sheet").val()).split('.').join('')
		var l_sheet   = String($("#l_sheet").val()).split('.').join('')
		var bb        = String($("#bb").val()).split(',').join('.')
			// hitung out
					
		out = Math.trunc(1800/l_sheet);
		if(out >= 5){
			out = 5;
		}

		rm       = Math.ceil(p_sheet * qty / out / 1000);
		ton      = Math.ceil(qty * bb);

		$('#rm').val(format_angka(rm));	
		$('#ton').val(format_angka(ton));
		
	}

	function kosong()
	{
		$("#l_panjang").val(0)
		$("#l_lebar").val(0)
		$("#l_tinggi").val(0)		
		// $("#tl_al").val('')
		// $("#bmf").val('')
		// $("#bl").val('')
		// $("#cmf").val('')
		// $("#cl").val('')
		$("#tl_al_i").val('')
		$("#bmf_i").val('')
		$("#bl_i").val('')
		$("#cmf_i").val('')
		$("#cl_i").val('')
		$("#hrg_kg").val('')
		$("#bb").val(0)
		$("#p_sheet").val('')
		$("#l_sheet").val('')		
		$('#flute').val("BCF").trigger('change');

	}

</script>
