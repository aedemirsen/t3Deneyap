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
                        <h4 class="page-title">Ders Programı</h4> </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                        <ol class="breadcrumb">
                            <li><a href="index.php">Anasayfa</a></li>
                            <li class="active">Ders Programı</li>
                        </ol>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <div class="white-box">
                <?
                if($_GET['action'] == 'edit'){
                  $schedule = getSchedule($_GET['id']);
                ?>
                  <form action="schedule.php?action=editPost&id=<?=$schedule->id?>" method="post">
                    <div class="form-group">
                      <label for="link">Deneyap :</label>
                      <select id="deneyap" name="deneyap" class="form-control form-control-line">
                        <?php
                          $deneyaplar = getDeneyapList();
                          foreach ($deneyaplar as $deneyap) {
                          ?>
                            <option value="<?=$deneyap->id?>" <? if($deneyap->id == $schedule->deneyapId) echo "selected";?> >
                                <?=$deneyap->name?>
                            </option>
                          <?
                          }
                          ?>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="link">Ders :</label>
                      <select id="ders" name="ders" class="form-control form-control-line">
                        <?php
                          $dersler = getLessonList();
                          foreach ($dersler as $ders) {
                          ?>
                            <option value="<?=$ders->id?>"  <? if($ders->id == $schedule->lessonId) echo "selected";?> >
                              <?=$ders->name?>
                            </option>
                          <?
                          }
                          ?>
                      </select>
                    </div>
                    <div class="form-group">
                        <label for="date">Tarih :</label>
                        <input type='date' id="date" name="date" class="form-control" value="<?=$schedule->date?>" />
                    </div>
                    <br/>
            				<button type="submit" class="btn btn-default">Kaydet</button>
                  </form>
                <?
                  $message = '';
                }elseif ($_GET['action'] == 'editPost') {
                  scheduleDuzenle($_GET['id'], $_POST['deneyap'], $_POST['ders'], $_POST['date']);
                  $message = 'İşlem Başarılı';
                }elseif ($_GET['action'] == 'add') {
                  ?>
                    <form action="schedule.php?action=addPost" method="post">
                      <div class="form-group">
                        <label for="link">Deneyap :</label>
                        <select id="deneyap" name="deneyap" class="form-control form-control-line">
                          <?php
                            $deneyaplar = getDeneyapList();
                            foreach ($deneyaplar as $deneyap) {
                            ?>
                              <<option value="<?=$deneyap->id?>"><?=$deneyap->name?></option>
                            <?
                            }
                            ?>
                        </select>
                      </div>
                      <div class="form-group">
                        <label for="link">Ders :</label>
                        <select id="ders" name="ders" class="form-control form-control-line">
                          <?php
                            $dersler = getLessonList();
                            foreach ($dersler as $ders) {
                            ?>
                              <<option value="<?=$ders->id?>"><?=$ders->name?></option>
                            <?
                            }
                            ?>
                        </select>
                      </div>
                      <div class="form-group">
                          <label for="date">Tarih :</label>
                          <input type='date' id="date" name="date" class="form-control" />
                      </div>
                      <br/>
              				<button type="submit" class="btn btn-default">Ekle</button>
                    </form>
                  <?
                }elseif ($_GET['action'] == 'addPost') {
                  $result = scheduleEkle( $_POST['deneyap'], $_POST['ders'], $_POST['date']);
                  if($result == 0){
                      $message = 'Varolan bir ders programı eklemek istediniz';
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
                              <h3 class="box-title">Ders Programları</h3>
                              <a href="schedule.php?action=add">Yeni Ekle</a>
                            <form action="schedule.php" method="post">
                                <?
                                $deneyapId = $_POST['deneyapId'];
                                $lessonId = $_POST['lessonId'];
                                $searchDate = $_POST['searchDate'];
                                ?>
                                <div class="col-sm-3">
                                  <select id="deneyapId" name="deneyapId" class="form-control form-control-line">
                                    <?php
                                      $deneyaplar = getDeneyapList();
                                      foreach ($deneyaplar as $deneyap) {
                                      ?>
                                      <option value="<?=$deneyap->id?>" <? if($deneyap->id == $deneyapId) echo "selected";?> >
                                          <?=$deneyap->name?>
                                      </option>
                                      <?
                                      }
                                      ?>
                                  </select>
                                </div>
                                <div class="col-sm-3">
                                  <select id="lessonId" name="lessonId" class="form-control form-control-line">
                                    <?php
                                      $dersler = getLessonList();
                                      foreach ($dersler as $ders) {
                                      ?>
                                      <option value="<?=$ders->id?>"  <? if($ders->id == $lessonId) echo "selected";?> >
                                        <?=$ders->name?>
                                      </option>
                                      <?
                                      }
                                      ?>
                                  </select>
                                </div>
                                <div class="col-sm-3">
                                  <input type='date' id="searchDate" name="searchDate" value="<?=$searchDate?>" class="form-control" />
                                </div>
                                <button type="submit" class="btn btn-default">Ara</button>
                              </form>
                              <div class="table-responsive">
                                  <table class="table ">
                                      <thead>
                                          <tr>
                                              <th>ID</th>
                                              <th>DENEYAP</th>
                                              <th>DERS</th>
                                              <th>TARİH</th>
                                              <th>DÜZENLE</th>
                                          </tr>
                                      </thead>
                                      <tbody>
                                          <?php
                                            $schedules = getScheduleList($deneyapId, $lessonId, $searchDate);
                                            foreach ($schedules as $schedule) {
                                            ?>
                                            <tr>
                                                <td class="txt-oflo"><? echo $schedule->id;?></td>
                                                <td><? echo $schedule->deneyapName;?></td>
                                                <td><? echo $schedule->lessonName;?></td>
                                                <td><? echo $schedule->date;?></td>
                                                <td>
                                                    <a href="schedule.php?action=edit&id=<?=$schedule->id?>">Düzenle</a>
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
