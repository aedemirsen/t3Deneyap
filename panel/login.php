<?php
include '../servletoperations.php';
 ?>
<html lang="tr">
<head>
  <?php
    $title = 'Giriş';
    include_once 'inc/header.php';
  ?>
</head>

<body>
    <!-- Preloader -->
    <div class="preloader">
        <div class="cssload-speeding-wheel"></div>
    </div>
    <div class="container">
    <div class="row">
      <div class="col-lg-12">
          <div class="white-box">
        <?
        if ($_GET['action'] == 'loginPost') {
          $users = loginControl($_POST['email'], $_POST['password']);
          if(count($users) > 0){
            session_start();
            setSessionUser(array_values($users)[0]);
            header("Location: index.php");
            die();
          }else {
            $message = 'Email veya şifreniz ile örtüşen kullanıcı bulunamadı';
          }
        }
        ?>
        <div><?=$message?></div>
        <form action="login.php?action=loginPost" method="post">
          <div class="form-group">
            <label for="link">Email :</label>
            <input type="text" class="form-control form-control-line" id="email" name="email" />
          </div>
          <div class="form-group">
            <label for="link">Şifre :</label>
            <input type="password" class="form-control form-control-line" id="password" name="password" />
          </div>
          <br/>
          <button type="submit" class="btn btn-default">Giriş</button>
        </form>
  </div>
    </div>
  </div>
  </div>
    <!-- jQuery -->
    <?php include 'inc/footer.php'; ?>
    <script type="text/javascript">
    $(function() {
        $(".preloader").fadeOut();
    });
    </script>
</body>

</html>
