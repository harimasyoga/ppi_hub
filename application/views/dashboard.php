

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <!-- <h1>Blank Page</h1> -->
          </div>
          
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- /.col (LEFT) -->
          <div class="col-md-12">
            <div class="row">
              <!-- <div class="card-body">
                  <div align="center" style="font-family:Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;" class="">
                      <h1><strong>SISTEM INFORMASI PRODUKSI </strong> <br><br>
                  <img src="<?= base_url()?>assets/gambar/ppi.png" style="width: 40%;" /> 
                  </div>
              </div> -->

              <div class="col-md-12">
                <div class="card card-info card-outline">
                  <div class="card-header">
                    <h3 class="card-title" style="font-weight:bold;font-style:italic">REKAP HUB</h3>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-10">
                      </div>

                      <div class="col-md-2">
                        <select class="form-control select2" id="th_hub" name="th_hub" onchange="load_data_hub()">
                          <?php 
                          $thang        = date("Y");
                          $thang_maks   = $thang + 3 ;
                          $thang_min    = $thang - 3 ;
                          for ($th=$thang_min ; $th<=$thang_maks ; $th++)
                          { ?>

                            <?php if ($th==$thang) { ?>

                              <option selected value="<?= $th ?>"> <?= $thang ?> </option>
                              
                            <?php }else{ ?>
                                
                              <option value="<?= $th ?>"> <?= $th ?> </option>
                            <?php } ?>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                    
                    
                  </div>
                    <div style="padding:0 10px 20px;">
                      <div style="overflow:auto;white-space:nowrap" id="datatable_hub"></div>
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <script type="text/javascript">
    $(document).ready(function() {
      $(".select2").select2()
      load_data_hub()
    });

    function load_data_hub()
    {
      var th_hub = $('#th_hub').val();

      $.ajax({
        url   : '<?php echo base_url('Master/rekap_hub')?>',
        type  : "POST",
        data  : {th_hub},
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
          $("#datatable_hub").html(res)
          swal.close()
        }
      })
    }
  </script>

