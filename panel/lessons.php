<?php
include '../servletoperations.php';
 ?>
<html lang="tr">
<head>
  <?php
    $title = 'Ders Listesi';
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
                        <h4 class="page-title">Ders Listesi</h4> </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                        <ol class="breadcrumb">
                            <li><a href="index.php">Anasayfa</a></li>
                            <li class="active">Ders Listesi</li>
                        </ol>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <div class="white-box">
                <?
                if($_GET['action'] == 'edit'){
                  $lesson = getLesson($_GET['id']);
                ?>
                  <form action="lessons.php?action=editPost&id=<?=$lesson->id?>" method="post">
                    <div class="form-group">
                      <label for="link">Adı :</label>
                      <input type="text" class="form-control form-control-line" id="name" name="name" value="<?=$lesson->name?>" />
                    </div>
                    <br/>
            				<button type="submit" class="btn btn-default">Kaydet</button>
                  </form>
                <?
                  $message = '';
                }elseif ($_GET['action'] == 'editPost') {
                  lessonDuzenle($_GET['id'], $_POST['name']);
                  $message = 'İşlem Başarılı';
                }elseif ($_GET['action'] == 'add') {
                  ?>
                    <form action="lessons.php?action=addPost" method="post">
                      <div class="form-group">
                        <label for="link">Adı :</label>
                        <input type="text" class="form-control form-control-line" id="name" name="name" value="" />
                      </div>
                      <br/>
              				<button type="submit" class="btn btn-default">Ekle</button>
                    </form>
                  <?
                }elseif ($_GET['action'] == 'addPost') {
                  $result = lessonEkle($_POST['name']);
                  if($result == 0){
                    $message = 'Varolan bir ders eklemek istediniz';
                  }else {
                      $message = 'İşlem Başarılı';
                  }
                }
                ?>
                  <div><?=$message?></div>
                </div>
                  <!--row -->
                  <div class="row">
                      <div class="col-sm-12">
                          <div class="white-box">
                              <h3 class="box-title">Dersler</h3>
                              <a href="lessons.php?action=add">Yeni Ekle</a>
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
                                            $dersler = getLessonList();
                                            foreach ($dersler as $ders) {
                                            ?>
                                            <tr>
                                                <td class="txt-oflo"><? echo $ders->id;?></td>
                                                <td><? echo $ders->name;?></td>
                                                <td>
                                                    <a href="lessons.php?action=edit&id=<?=$ders->id?>">Düzenle</a>
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
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->
    <?php include 'inc/footer.php'; ?>
</body>

</html>
