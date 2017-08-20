<?php
include '../servletoperations.php';
 ?>
<html lang="tr">
<head>
  <?php
    $title = '404';
    include_once 'inc/header.php';
  ?>
</head>

<body>
    <!-- Preloader -->
    <div class="preloader">
        <div class="cssload-speeding-wheel"></div>
    </div>
    <section id="wrapper" class="error-page">
        <div class="error-box">
            <div class="error-body text-center">
                <h1>404</h1>
                <h3 class="text-uppercase">Page Not Found !</h3>
                <p class="text-muted m-t-30 m-b-30">YOU SEEM TO BE TRYING TO FIND HIS WAY HOME</p>
                <a href="index.html" class="btn btn-info btn-rounded waves-effect waves-light m-b-40">Back to home</a> </div>
            
        </div>
    </section>
    <!-- jQuery -->
    <?php include 'inc/footer.php'; ?>
    <script type="text/javascript">
    $(function() {
        $(".preloader").fadeOut();
    });
    </script>
</body>

</html>
