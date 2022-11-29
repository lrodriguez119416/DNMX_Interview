
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <base href="<?php use Routes\Helpers; echo Helpers::getPath(); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="Public/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="Public/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="Public/plugins/bs-stepper/css/bs-stepper.min.css">
    <link rel="stylesheet" href="Public/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="Public/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="Public/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="Public/plugins/sweetalert2/sweetalert2.min.css">
    <link rel="stylesheet" href="Public/plugins/chart.js/Chart.min.css">
    <link rel="stylesheet" href="Public/css/zebra_form.css">
    <link rel="stylesheet" href="Public/css/adminlte.min.css">
    <link rel="stylesheet" href="Public/css/main.css">
    <script src="Public/plugins/jquery/jquery.min.js"></script>
    <script src="Public/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="Public/plugins/bs-stepper/js/bs-stepper.min.js"></script>
    <script src="Public/js/adminlte.min.js"></script>
    <script src="Public/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="Public/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="Public/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="Public/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="Public/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="Public/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="Public/plugins/sweetalert2/sweetalert2.all.min.js"></script>
    <script src="Public/plugins/sweetalert2/sweetalert2.min.js"></script>
    <script src="Public/plugins/chart.js/Chart.min.js"></script>
    <script src="Public/js/main.js"></script>
    <title>Test</title>
</head>
<body>
    <div class="wrapper">
        <?php use Routes\App; echo App::component(); ?>
    </div>
</body>
</html>