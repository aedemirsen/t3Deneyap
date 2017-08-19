<?php
include '../servletoperations.php';
 ?>
<html lang="tr">
<head>
  <?php
    $title = 'Deneyap Listesi';
    include_once 'inc/header.php';
  ?>
</head>
<body>
    <!-- Preloader -->
    <div class="preloader">
        <div class="cssload-speeding-wheel"></div>
    </div>
    <div id="wrapper">
        <?php include 'inc/topMenu.php' ?>
        <?php include 'inc/leftMenu.php'; ?>
        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row bg-title">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title">Deneyap Listesi</h4> </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                        <ol class="breadcrumb">
                            <li><a href="index.php">Anasayfa</a></li>
                            <li class="active">Deneyap Listesi</li>
                        </ol>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <div class="white-box">
                <?
                if($_GET['action'] == 'edit'){
                  $deneyap = getDeneyap($_GET['id']);
                ?>
                  <form action="deneyapListesi.php?action=editPost&id=<?=$deneyap->id?>" method="post">
                    <div class="form-group">
                      <label for="link">Adı :</label>
                      <input type="text" class="form-control form-control-line" id="name" name="name" value="<?=$deneyap->name?>" />
                    </div>
                    <br/>
            				<button type="submit" class="btn btn-default">Kaydet</button>
                  </form>
                <?
                  $message = '';
                }elseif ($_GET['action'] == 'editPost') {
                  deneyapDuzenle($_GET['id'], $_POST['name']);
                  $message = 'İşlem Başarılı';
                }elseif ($_GET['action'] == 'add') {
                  ?>
                    <form action="deneyapListesi.php?action=addPost" method="post">
                      <div class="form-group">
                        <label for="link">Adı :</label>
                        <input type="text" class="form-control form-control-line" id="name" name="name" value="" />
                      </div>
                      <br/>
              				<button type="submit" class="btn btn-default">Ekle</button>
                    </form>
                  <?
                }elseif ($_GET['action'] == 'addPost') {
                  $result = deneyapEkle($_POST['name']);
                  if($result == 0){
                    $message = 'Varolan bir deneyap eklemek istediniz';
                  }else {
                      $message = 'İşlem Başarılı';
                  }
                }
                ?>
                  <div><?=$message?></div>
                  <!--row -->
                  <div class="row">
                      <div class="col-sm-12">
                          <div class="white-box">
                              <h3 class="box-title">Deneyaplar</h3>
                              <a href="deneyapListesi.php?action=add">Yeni Ekle</a>
                              <div class="table-responsive">
                                  <table class="table ">
                                      <thead>
                                          <tr>
                                              <th>ID</th>
                                              <th>ADI</th>
                                              <th>DÜZENLE</th>
                                          </tr>
                                      </thead>
                                      <tbody>
                                          <?php
                                            $deneyaplar = getDeneyapList();
                                            foreach ($deneyaplar as $deneyap) {
                                            ?>
                                            <tr>
                                                <td class="txt-oflo"><? echo $deneyap->id;?></td>
                                                <td><? echo $deneyap->name;?></td>
                                                <td>
                                                    <a href="deneyapListesi.php?action=edit&id=<?=$deneyap->id?>">Düzenle</a>
                                                </td>
                                            </tr>
                                          <?
                                            }
                                          ?>
                                      </tbody>
                                  </table>
                                </div>
                          </div>
                      </div>
                  </div>
                  <!-- /.row -->
                </div>
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->
    <?php include 'inc/footer.php'; ?>
</body>

</html>
