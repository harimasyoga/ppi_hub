

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Data Transaksi </h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item active" ><a href="#"><?= $judul ?></a></li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title"><?= $judul ?></h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fas fa-minus"></i></button>
          </div>
        </div>
        <div class="card-body">

          <button type="button" class="tambah_data btn  btn-outline-primary pull-right" >Tambah Data</button>
          <br><br>

         

          <table id="datatable" class="table table-bordered table-striped" width="100%">
            <thead>
            <tr>
              <th style="width:5%">No</th>
              <th style="width:%">No SJ</th>
              <th style="width:%">Tgl SJ</th>
              <th style="width:%">Status</th>
              <th style="width:%">No PO</th>
              <th style="width:%">Kode MC</th>
              <th style="width:%">Total Qty</th>
              <th style="width:%">ID Pelanggan</th>
              <th style="width:%">Nama Pelanggan</th>

              <th style="width:10%">Aksi</th>
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
          <table width="100%" border="0">
            <tr>
              <td width="15%">No Surat Jalan</td>
              <td>

                <input type="hidden" class="form-control" value="trs_surat_jalan" name="jenis" id="jenis" >
                <input type="hidden" class="form-control" value="" name="tgl_po" id="tgl_po" >
                <input type="hidden" class="form-control" value="" name="status" id="status" >
                <input type="text" class="form-control" name="no_surat_jalan" id="no_surat_jalan" readonly>
              </td>
              <td width="15%"></td>
              <td width="15%">Kode PO</td>
              <td width="30%">
                <input type="text" class="form-control" name="kode_po"  id="kode_po" readonly >
              </td>
            </tr>
            <tr>
              <td width="15%">Tgl Surat Jalan</td>
              <td><input type="date" class="form-control" name="tgl_surat_jalan"  id="tgl_surat_jalan" value="<?= date('Y-m-d') ?>" readonly></td>
              <td width="15%"></td>
              <td width="15%" >
                Pelanggan
              </td>
              <td >
                <input type="text" class="form-control" name="pelanggan"  id="pelanggan" value="" readonly>
              </td>
            </tr>
            <tr>
              <td width="15%">NO PO</td>
              <td>
                <select class="form-control select2" name="no_po" id="no_po" style="width: 100%;" onchange="setDetailPO()">
                  <option value="">Pilih</option>
                  <?php foreach ($getPO as $r): ?>
                    <option value="<?= $r->no_po ?>" detail="<?= $r->no_po ?>"><?= $r->no_po ?></option>
                  <?php endforeach ?>
                </select>
                
              </td>
              <td width="15%"></td>
              <td width="15%" >
                No PKB
              </td>
              <td >
                <input type="text" class="form-control" name="no_pkb"  id="no_pkb" value="" >
              </td>
            </tr>
            <tr>
              <td width="15%"></td>
              <td></td>
              <td width="15%"></td>
              <td width="15%" >
                No Kendaraan
              </td>
              <td >
                <input type="text" class="form-control" name="no_kendaraan"  id="no_kendaraan" value="" >
              </td>
            </tr>

            
          </table>
        </div>

        <div class="form-group row">
          <table class="table" id="table-produk" style="width: 80%;display: ;" align="center" >
            <thead>
                <tr>
                    <th>Nama Produk</th>
                    <th width="10%">Qty PO</th>
                    <th width="10%">Qty SJ</th>
                    <th width="10%" style="display:">Qty</th>
                    <th width="50%">Detail Produk</th>
                </tr>
            </thead>
            <tbody>
              <!-- <tr id="itemRow0">
                  <td>
                    <input type="text" id="id_produk0" class="angka form-control" readonly>

                  </td>
                  <td>
                      <input type="text" name="qty_po[0]" id="qty_po0" class="angka form-control" value='0'  readonly>
                  </td>
                  <td>
                      <input type="text" name="qty_sj[0]" id="qty_sj0" class="angka form-control" value='0'  readonly>
                  </td>
                  <td>
                      <input type="text" name="qty[0]" id="qty0" class="angka form-control" value='0' onkeyup="hitung()" onchange="hitung()" >
                  </td>
                  <td id="txt_detail_produk0">
                      
                  </td>

                  
              </tr> -->
            </tbody>
          </table>  
        </div>
        <div class="form-group row">
          <label class="col-sm-2 col-form-label"></label>
          <div class="col-sm-4">
            <!-- <button type="button" onclick="addRow()" class="btn-tambah-produk btn  btn-outline-primary">Tambah Produk</button> -->
          <input type="hidden" name="bucket" id="bucket" value="0">  
          </div>
        </div>


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="btn-simpan" onclick="simpan()"><i class="fas fa-save"></i> Simpan</button>
        <button type="button" class="btn btn-outline-secondary" id="btn-print" onclick="Cetak()" style="display:none"><i class="fas fa-print"></i> Print</button>
      </div>
      </form>
        <input type="hidden" name="bucket" id="bucket" value="0">
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<script type="text/javascript">
  rowNum = 0;
  $(document).ready(function () {
     load_data();
     getMax();
     $('.select2').select2();
  });

  status ="insert";
  $(".tambah_data").click(function(event) {
    kosong();
    $("#modalForm").modal("show");
    

    $("#judul").html('<h3> Form Tambah Data</h3>');
    status = "insert";
    $("#status").val("insert");
  });


  function load_data() {
    

    var table = $('#datatable').DataTable();

    table.destroy();

    tabel = $('#datatable').DataTable({

      "processing": true,
      "pageLength": true,
      "paging": true,
      "ajax": {
        "url": '<?php echo base_url(); ?>Transaksi/load_data/trs_surat_jalan',
        "type": "POST",
        // data  : ({tanggal:tanggal,tanggal_akhir:tanggal_akhir,id_kategori:id_kategori1,id_sub_kategori:id_sub_kategori1}), 
      },
      responsive: true,
      "pageLength": 25,
      "language": {
        "emptyTable": "Tidak ada data.."
      }
    });

  }

  function reloadTable() {
    table = $('#datatable').DataTable();
    tabel.ajax.reload(null, false);
  }

  function simpan(){
    no_po = $("#no_pkb").val();
    no_pkb = $("#no_po").val();
    no_kendaraan = $("#no_kendaraan").val();

    if (no_po == '' || no_po == null || no_pkb == ''  || no_kendaraan == '' ) {
      toastr.info('Harap Lengkapi Form');
      return;
    }

      $.ajax({
          url : '<?php echo base_url(); ?>Transaksi/insert',
          type: "POST",
          data: $('#myForm').serialize(),
          dataType: "JSON",
          success: function(data)
          {           
              if (data) {
                toastr.success('Berhasil Disimpan'); 
                kosong();
                $("#modalForm").modal("hide");

                setTimeout(function(){ location.reload(); }, 1000);
              }else{
                toastr.error('Gagal Simpan'); 
              }
              // reloadTable();
          },
          error: function (jqXHR, textStatus, errorThrown)
          {
             toastr.error('Terjadi Kesalahan '); 
          }
      });
     
  }

  function kosong(c =''){
     $("#tgl_surat_jalan").val("<?= date('Y-m-d') ?>");

     if (c != 's') {
       getMax();
      
     }

     
     $('#no_po').val('').trigger('change');

     $("#btn-print").hide();
    
     
     status = 'insert';
     $("#status").val(status);
     $("#kode_po").val("");

     $('#no_po').prop('disabled',false);

     $("#btn-simpan").show();
     
     $(".btn-tambah-produk").show();

     $("#kode_po").val('');
       $("#pelanggan").val('');
       $("#id_produk0").val('');
       $("#qty0").val('');
       $("#qty_sj0").val('');
       $("#qty_po0").val('');

       $("#txt_detail_produk0").html('');
  }

  function hitung(){
    toastr.clear();

    qty_sj = parseFloat($("#qty_sj0").val());
    qty_po = parseFloat($("#qty_po0").val());

    qty_sisa = qty_po - qty_sj;

    qty = parseFloat($("#qty0").val());

    if (qty > qty_sisa) {
      toastr.info('QTY tidak boleh lebih dari '+qty_sisa); 

      qty = qty_sisa;
      $("#qty0").val(qty) 
    }

  }


  function tampil_edit(id,act){
    kosong('s');
    $(".btn-tambah-produk").hide();
    
    $("#btn-print").show();

    $("#status").val("update");
    $("#modalForm").modal("show");
    if (act =='detail') {
      $("#judul").html('<h3> Detail Data</h3>');
      $("#btn-simpan").hide();
      status = 'detail';
    }else{
      $("#judul").html('<h3> Form Edit Data</h3>');
      $("#btn-simpan").show();
      status = 'update';
    }

    status = "update";

         $.ajax({
              url: '<?php echo base_url('Transaksi/get_edit'); ?>',
              type: 'POST',
              data: {id: id,jenis : "trs_surat_jalan",field:'id'},
              dataType: "JSON",
          })
          .done(function(data) {
            $("#no_surat_jalan").val(data.no_surat_jalan);
            $("#tgl_surat_jalan").val(data.tgl_surat_jalan);

            
            $('#no_po').prop('disabled',true);
            $('#no_po').append('<option value="'+data.no_po+'" detail="'+data.no_po+'">'+data.no_po+'</option>');

            // $('#no_po').val(data.no_po);
            $('#no_po').val(data.no_po).trigger('change');
             
             $("#kode_po").val(data.kode_po);
             $("#no_kendaraan").val(data.no_kendaraan);
             $("#no_pkb").val(data.no_pkb);
              
              
          }) 

  }

  function getMax(){
    $.ajax({
          url: '<?php echo base_url('Transaksi/getMax'); ?>',
          type: 'POST',
          data: {table : "trs_surat_jalan",fieald:'no_surat_jalan'},
          dataType: "JSON",
          success : function(data){
            $("#no_surat_jalan").val("SJ-"+data.tahun+"-"+"000000"+data.no);
          },
          error: function (jqXHR, textStatus, errorThrown)
          {
             toastr.error('Terjadi Kesalahan'); 
          }
      });
      
  }


  function deleteData(id){
    let cek = confirm("Apakah Anda Yakin?");

    if (cek) {
      $.ajax({
        url   : '<?php echo base_url(); ?>Transaksi/batal',
        data  : ({id:id,jenis:'trs_surat_jalan',field:'id'}),
        type  : "POST",
        success : function(data){
          toastr.success('Data Berhasil Di Hapus'); 

          setTimeout(function(){ location.reload(); }, 1000);
          
          reloadTable();
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
           toastr.error('Terjadi Kesalahan'); 
        }
      });
    }
    
   
  }

  $("#id_pelanggan").change(function(){
    if ($("#id_pelanggan").val() == "") {
      return;
    }

    arr_detail = $('#id_pelanggan option:selected').attr('detail');

    if (typeof arr_detail === 'undefined'){
        return;
    }

    arr_detail = arr_detail.split("|");
    // console.log(arr_detail);

    $("#txt_kota").html(": "+arr_detail[0]);
    $("#txt_no_telp").html(": "+arr_detail[1]);
    $("#txt_fax").html(": "+arr_detail[2]);
    $("#txt_top").html(": "+arr_detail[3]);

  });

  function setDetailPO() {
    id = $("#no_po").val();

    if (id == "") {
      return;
    }
    $("#table-produk tbody").empty()

    if (status != 'insert') {
      jenis = 'SJView'
    }else{
      jenis = 'SJ'
    }

    $.ajax({
        url: '<?php echo base_url('Transaksi/get_edit'); ?>',
        type: 'POST',
        data: {id: id,jenis,field:'no_po'},
        dataType: "JSON",
    })
    .done(function(data) {
       $("#kode_po").val(data.header[0].kode_po);
       $("#pelanggan").val(data.header[0].id_pelanggan+' || '+data.header[0].nm_pelanggan);

       bucket = 0;
       $.each(data.header,function(index, value){
        sisa = parseFloat(value.qty) - parseFloat(value.qty_sj);

        detail = '<ul>'+
                    '<li>Nama Produk : '+value.nm_produk+'</li>'+
                    '<li>Ukuran : '+value.ukuran+'</li>'+
                    '<li>Flute : '+value.flute+'</li>'+
                    '<li>kualitas : '+value.kualitas+'</li>'+
                    '<li>Warna : '+value.nm_produk+'</li>'+
                  '</ul>';
         if (status != 'insert') {
          s_readonly = "readonly";
         }else{
          s_readonly = "";
         }

         $("#table-produk tbody").append(
              `<tr id="itemRow${bucket}">
                  <td>
                    <input type="text" id="id_produk${bucket}" name="id_produk[]" class="angka form-control" value="${value.kode_mc}" readonly>

                  </td>
                  <td>
                      <input type="text" name="qty_po[${bucket}]" id="qty_po${bucket}" class="angka form-control" value='${value.qty}'  readonly>
                  </td>
                  <td >
                      <input type="text" name="qty_sj[${bucket}]" id="qty_sj${bucket}" class="angka form-control" value='${value.qty_sj}'  readonly>
                  </td>
                  <td>
                      <input type="text" name="qty[${bucket}]" id="qty${bucket}" class="angka form-control" value='${sisa}' onkeyup="hitung()" onchange="hitung()" ${s_readonly}>
                  </td>
                  <td id="txt_detail_produk${bucket}" style="font-size:12px">
                      ${detail}
                  </td>

                  
              </tr>`
          )
          bucket++;
       });
    }) 
  }


    function Cetak(){
      no_surat_jalan = $("#no_surat_jalan").val();
      var url    = "<?php echo base_url('Transaksi/Cetak_SuratJalan'); ?>";
      window.open(url+'?no_surat_jalan='+no_surat_jalan, '_blank');
    }

</script>