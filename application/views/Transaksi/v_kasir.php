

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <!-- <h1>Data Transaksi </h1> -->
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <!-- <li class="breadcrumb-item active" ><a href="#"><?= $judul ?></a></li> -->
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

     <div class="row">
        <div class="col-md-5">
          <div class="card direct-chat direct-chat-warning">
            <div class="card-header">
              <input type="text" class="form-control" name="cariproduk" id="cariproduk" placeholder="Masukan Kode Produk" onkeypress="return getProduk(event)">
              <!-- <div class="input-group input-group">
                <input type="text" class="form-control" id="cariproduk" placeholder="Masukan Kode Produk" onkeypress="return getProduk(event)">
                <span class="input-group-append">
                  <button type="button" class="btn btn-info btn-flat">Go!</button>
                </span>
              </div> -->
            </div>
            <!-- /.card-header -->
            <div class="card-body" style="height: 400px">
              <div class="" style="height: 400px">
                <form id="myForm">
                 <table border="0" width="100%" id="table-produk">
                  <thead style="color:#fff;background-color: #4b545c">
                    <tr>
                      <td align="center" width="5%"></td>
                      <td align="center" width="30%">Produk</td>
                      <td align="center" width="15%">Harga</td>
                      <td align="center" width="15%">Qty</td>
                      <td align="center" width="20%">Sub Total</td>
                      <td align="center" width="15%">X</td>
                    </tr>

                  </thead>
                  <tbody>

                  </tbody>
                </table>
                
                <input type="hidden" id="bucket" value="0">

              </div>
              <!--/.direct-chat-messages-->

            </div>
            <!-- /.card-body -->
            <div class="card-footer" style="background-color: #4b545c">
              
              <table  width="100%" style="color:#fff">
                <tr>
                  <td width="30%">Pelanggan</td>
                  <td align="center" width="2%">:</td>
                  <td align="right" width="22%"><input type="text" name="pelanggan" id="pelanggan" placeholder="Pelanggan / Kode Member" class="form-control" onkeypress="return getMember(event)" onkeyup="hitung()"></td>
                  <td align="right" width="22%">Nama Member</td>
                  <td align="center" width="2%">:</td>
                  <td align="right" width="22%"><p id="txt-nm-member" style="margin-top:5px"></p> <input type="hidden" name="nm_pelanggan" id="nm_pelanggan"></td>
                </tr> 
                <tr>
                  <td width="30%">Total Item</td>
                  <td align="center" width="2%">:</td>
                  <td align="right" width="22%"><p id="txt-total-item" style="margin-top:5px">0</p></td>
                  <td align="right" width="22%">Total</td>
                  <td align="center" width="2%">:</td>
                  <td align="right" width="22%"><p id="txt-grand-total" style="margin-top:5px">0</p></td>
                </tr>
                <tr>
                  <td width="30%"><input type="checkbox" id="diskon_member" disabled name="diskon_member" value="cek" onclick="diskon()"> Disc. Member (<?= $setting->diskon_member ?>%)
                    <input type="hidden" class="form-control" id="dsc_member1" name="dsc_member1" placeholder="0" onkeyup="hitung()">
                  </td>
                  <td align="center">:</td>
                  <td align="right"><p id="txt-pot-member" style="margin-top:5px"></p></td>
                  <td align="right">Grand Total</td>
                  <td align="center">:</td>
                  <td align="right"><p id="txt-grand-total1" style="margin-top:5px">0</p></td>
                </tr>
                <tr style="border-top:1px solid">
                  <td width="30%">Cash</td>
                  <td align="center">:</td>
                  <td align="right"><input type="text" class="angka form-control" id="cash" name="cash" placeholder="0" onkeyup="hitung()"></td>
                  <td align="right">Kembali</td>
                  <td align="center">:</td>
                  <td align="right"><p id="txt-kembali" style="margin-top:5px">0</p></td>
                </tr>
                <tr>
                  <td align="right">
                    <button type="button" onclick="baru()" class="btn btn-block bg-gradient-info btn-sm">Baru</button>
                  </td>
                  <td width="1%">
                  </td>
                  <td >
                    <button type="button" onclick="clearRow()" class="btn btn-block bg-gradient-danger btn-sm">Batal</button>
                  </td>
                  <td colspan="3"><button type="button" id="btn-bayar" disabled class="btn btn-block bg-gradient-success btn-sm" onclick="checkout()">Bayar</button></td>
                </tr>
              </table>
            </div>
            <!-- /.card-footer-->
          </div>
          <!--/.direct-chat -->
        </div>
        <!-- /.col -->
        </form>


        <div class="col-md-7">
          <div class="card direct-chat direct-chat-warning">
            <div class="card-header">
              <div class="btn-group w-100 mb-2">
                <a class="btn btn-info active" href="javascript:void(0)" data-filter="all"> Semua </a>
                <a class="btn btn-info" href="javascript:void(0)" data-filter="1"> Produk </a>
                <a class="btn btn-info" href="javascript:void(0)" data-filter="2"> Perawatan </a>
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body" style="height: 620px">
              <div class="" style="height: 620px">
                 <div>
                  <div class="filter-container p-0 row">

                    <?php foreach ($produk as $r): ?>
                      <div class="filtr-item col-sm-2" data-category="1" data-sort="black sample">
                        <?php 
                          if ($r->gambar == '') {
                            $r->gambar = "noimage.png";
                          }
                         ?>
                        <a href="javascript:void(0)" onclick="addToCart('<?= $r->stok ?>','<?= $r->harga ?>','<?= $r->id_produk ?>','<?= $r->nm_produk ?>','<?= $r->diskon ?>','<?= $r->id_promosi ?>')">
                          <img src="<?= base_url('assets/gambar/produk/').$r->gambar ?>" class="img-fluid mb-2" />
                        </a>  <?= $r->nm_produk ?>
                      </div>  
                    <?php endforeach ?>

                    <?php foreach ($perawatan as $r): ?>
                      <div class="filtr-item col-sm-2" data-category="2" data-sort="black sample">
                        <a href="javascript:void(0)" onclick="addToCart('99999','<?= $r->harga ?>','<?= $r->id_perawatan ?>','<?= $r->nm_perawatan ?>','0','')">
                          <img src="<?= base_url('assets/gambar/produk/noimage.png') ?>" class="img-fluid mb-2" />
                        </a><?= $r->nm_perawatan ?>
                      </div>  
                    <?php endforeach ?>
                    
                  </div>
                </div>
              </div>
              <!--/.direct-chat-messages-->

            </div>
            <!-- /.card-body -->
           
          </div>
          <!--/.direct-chat -->
        </div>
        <!-- /.col -->
      </div>

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->



<script type="text/javascript">
  var produkSource = [];
  var dsc_member = 'Y';
  var rowNum = 0;
  var arr_prod=[];

  $(function () {
    $('.select2').select2();

    $(document).on('click', '[data-toggle="lightbox"]', function(event) {
      event.preventDefault();
      $(this).ekkoLightbox({
        alwaysShowClose: true
      });
    });

    $('.filter-container').filterizr({gutterPixels: 3});
    $('.btn[data-filter]').on('click', function() {
      $('.btn[data-filter]').removeClass('active');
      $(this).addClass('active');
    });

    no = 0;
    <?php foreach ($produk as $r) { ?>
        produkSource[no] = '<?= $r->id_produk ?>';
        no++;
    <?php } ?>

    /*$('#cariproduk').typeahead({
        source: produkSource,
    });*/

  })
  

  function addToCart(stok,harga,kode,nm,dsc_produk,id_promosi){

    if (parseInt(stok) <= 0 ) {
      toastr.error(nm + ' Stok Habis'); 
    }else{
      var jj = arr_prod.findIndex(x => x.kode==kode);

       if(jj === -1){
            arr_prod.push({
                kode : kode
            });

            var b = $('#bucket').val();
            xx = rowNum + 1;
            nm1 = nm;
            if (parseFloat(dsc_produk) > 0 ) {
              nm = nm + ' ('+dsc_produk+'%)';
            }
            $('#table-produk').append(''+
                '<tr id="itemRow'+rowNum+'">'+
                   '<td align="center"></td>'+
                   '<td>'+nm+' <input type="hidden" class="form-control" name="produk['+rowNum+']" id="produk'+rowNum+'" value="'+kode+'|'+nm1+'" style="margin:5px"></td>'+
                   '<td align="right"><p style="margin-top:15px" id="txt-harga'+rowNum+'"> '+formatMoney(harga)+' </p><input type="hidden" class="angka form-control" name="harga['+rowNum+']" id="harga'+rowNum+'" value="'+harga+'" style="margin:5px"><input type="hidden" class="form-control" name="dsc_produk['+rowNum+']" id="dsc_produk'+rowNum+'" value="'+dsc_produk+'" style="margin:5px"></td>'+
                   '<td><input type="text" class="angka form-control" name="qty['+rowNum+']" id="qty'+rowNum+'" value="1" style="margin:5px" onkeyup="hitung()"><input type="hidden" class="angka form-control" name="stok['+rowNum+']" id="stok'+rowNum+'" value="'+stok+'" style="margin:5px">'+
                    '<input type="hidden" class="form-control" name="id_promosi['+rowNum+']" id="id_promosi'+rowNum+'" value="'+id_promosi+'" style="margin:5px"></td>'+
                   '<td align="right"><p style="margin-top:15px" id="txt-sub-total'+rowNum+'"> '+formatMoney(harga)+' </p></td>'+
                   '<td align="center"><button type="button" class="btn btn-danger btn-sm" onclick="removeRow('+rowNum+')"><i class="fa fa-trash"></i> </button></td>'+
                '</tr>)');

            $('#bucket').val(rowNum);
            rowNum++;
            hitung();
       }

    }
  }

  function checkout(){
    $.ajax({
        url: '<?php echo base_url('Transaksi/checkout'); ?>',
        type: "POST",
        data: $('#myForm').serialize(),
        dataType: "JSON",
        success: function(data)
        {           
            if (data.status) {
              clearRow();
              printContent(data.id_penjualan);
            }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
          alert('error');
        }
    });
  }

  function diskon() { 
      // var all = document.getElementById('diskon_member').value;       
      if($("#diskon_member").is(':checked')){ 
          dsc_member = 'Y';
          hitung();
      }   
      else{
          dsc_member = '';
          hitung();
      }
  }   

  function getProduk(e){
    if (e.keyCode == 13) {
      id = $("#cariproduk").val();

      $.ajax({
            url: '<?php echo base_url('Transaksi/get_edit'); ?>',
            type: 'POST',
            data: {id: id,jenis : "getproduk"},
            dataType: "JSON",
        })
        .done(function(data) {
            if (data == null) {
              toastr.error('Kode Produk Tidak Ditemukan'); 
            }else{
              addToCart(data.stok, data.harga , data.id_produk , data.nm_produk , data.diskon, data.id_promosi );
              $("#cariproduk").val("");
            }
        }) 
    }
  }   

  function getMember(e){
    if (e.keyCode == 13) {
      id = $("#pelanggan").val();

      $.ajax({
            url: '<?php echo base_url('Transaksi/get_edit'); ?>',
            type: 'POST',
            data: {id: id,jenis : "getMember"},
            dataType: "JSON",
        })
        .done(function(data) {
            if (data == null) {
              toastr.error('Kode Pelanggan Tidak Ditemukan'); 
              $("#txt-nm-member").html("");
              $("#nm_pelanggan").val("");
              $("#diskon_member").prop("disabled",true);
              $('#diskon_member').prop('checked', false);
            }else{
              $("#txt-nm-member").html(data.nm_pelanggan);
              $("#nm_pelanggan").val(data.nm_pelanggan);
              $("#diskon_member").prop("disabled",false);
              $('#diskon_member').prop('checked', true);
            }
            hitung();
        }) 
    }
  }

  function hitung(a){
    if (a == 'r') {
      bucket = parseInt($("#bucket").val()) - 1;
    }else{

      bucket = $("#bucket").val();
    }

    var total_item = 0;
    var grand_total = 0;

    for (var e = 0; e <= bucket; e++) {
      qty = $("#qty"+e).val();
      stok = $("#stok"+e).val();
      produk = $("#produk"+e).val();
      dsc_produk = $("#dsc_produk"+e).val();
      harga = $("#harga"+e).val();

      if (parseInt(qty) > parseInt(stok) ) {
        toastr.error('Stok '+produk+' tinggal '+stok); 
        $("#qty"+e).val(stok);
        qty = stok;
      }

      if (parseFloat(dsc_produk) > 0 ) {
        harga = harga - ((harga / 100) * dsc_produk);
      }

      sub_total = parseInt(qty) * parseInt(harga);
      $("#txt-sub-total"+e).html(formatMoney(sub_total));

      total_item += parseInt(qty);
      grand_total += parseInt(sub_total);
    }


    grand_total = (isNaN(grand_total)) ? 0 : grand_total;

    // if (dsc_member == 'Y' ) {
    if ($('#diskon_member').is(':checked')) {
      dsc = '<?= $setting->diskon_member ?>';
      
      potongan = (parseInt(dsc) / 100) * grand_total;
      grand_total1= grand_total- potongan;

      $("#dsc_member1").val(dsc);
    }else{
      potongan = 0;
      grand_total1= grand_total;
      $("#dsc_member1").val(0);
    }

    $("#txt-pot-member").html('- '+ formatMoney(potongan));  
    $("#txt-grand-total1").html(formatMoney(grand_total1));
    $("#txt-total-item").html(formatMoney(total_item));
    $("#txt-grand-total").html(formatMoney(grand_total));

    //kembali
    cash = $("#cash").val();
    kembali = parseInt(cash)-grand_total1;
    $("#txt-kembali").html(formatMoney(kembali));
    pelanggan = $("#pelanggan").val();

    if (total_item > 0 && parseInt(kembali) >= 0 && pelanggan != "") {
      $("#btn-bayar").prop("disabled",false);
    }else{
      $("#btn-bayar").prop("disabled",true);
    }
  }

  function removeRow(e) 
  {  
        if (rowNum >0) {
            jQuery('#itemRow'+e).remove();
            rowNum--;
            arr_prod.shift(e);
        }
        
        $('#bucket').val(rowNum);
        hitung('r');
  }

  function clearRow() 
  {
        var bucket = $('#bucket').val();
        for (var e = bucket; e >= 0; e--) {            
            jQuery('#itemRow'+e).remove();            
            rowNum--;
            arr_prod.shift(e);
        }

        $('#removeRow').hide();
        $('#cash').val("");
        $('#pelanggan').val("");
        $('#nm_pelanggan').val("");
        $('#txt-nm-member').html("");
        $('#bucket').val(rowNum);
        $("#diskon_member").prop("disabled",true);
        $('#diskon_member').prop('checked', false);
        hitung();
  }

  function formatMoney(amount, decimalCount = 0, decimal = ".", thousands = ",") {
    try {
      decimalCount = Math.abs(decimalCount);
      decimalCount = isNaN(decimalCount) ? 2 : decimalCount;

      const negativeSign = amount < 0 ? "-" : "";

      let i = parseInt(amount = Math.abs(Number(amount) || 0).toFixed(decimalCount)).toString();
      let j = (i.length > 3) ? i.length % 3 : 0;

      return negativeSign + (j ? i.substr(0, j) + thousands : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands) + (decimalCount ? decimal + Math.abs(amount - i).toFixed(decimalCount).slice(2) : "");
    } catch (e) {
      console.log(e)
    }
  }

  function baru(){
    location.reload();
  }

  function printContent(id_penjualan){
    link = "<?=base_url('Transaksi/print_invoice')?>?id="+id_penjualan;

    var left = (screen.width - 380) / 2;
    var top = (screen.height - 550) / 4;

    var myWindow = window.open(link, "", "width=380, height=550, top=" + top + ", left=" + left);
  } 

</script>