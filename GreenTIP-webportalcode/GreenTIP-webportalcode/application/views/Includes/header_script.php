<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo isset($title) ? $title : ADMIN_COMPANY; ?></title>
    <meta name="google" content="notranslate">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap -->
    <?php if (empty($_REQUEST)) { ?>
        <!--<link href="<?php /*echo assets_url('css', 'loaders.min.css'); */?>" rel="stylesheet">-->
    <?php } ?>
    <link rel="shortcut icon" href="<?php echo assets_url('favicons', 'favicon.ico'); ?>" type="image/x-icon" />
    <link href="<?php echo assets_url('css', 'bootstrap.min.css'); ?>" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Lato&display=swap" rel="stylesheet">

    <link href="<?php echo assets_url('css', 'style.css'); ?>" rel="stylesheet">
    <link href="<?php echo assets_url('css', 'media.css'); ?>" rel="stylesheet">
    <link href="<?php echo assets_url('css', 'developer.css'); ?>" rel="stylesheet">

    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

    <script src="<?php echo assets_url('js', 'jquery.min.js'); ?>"></script>
    <script src="<?php echo assets_url('js', 'bootstrap.min.js'); ?>"></script>

    <!-- Toastr Popups -->
    <!--<link rel="stylesheet" type="text/css" href="<?php /*echo assets_url('css', 'toastr.min.css'); */?>">-->

    <script type="text/javascript">
        var SiteUrl = '<?php echo base_url(); ?>';
        var BASE_URL = "<?php echo base_url(); ?>";
        var templateassets = "<?php echo assets_url('', ''); ?>";
    </script>
   <!-- <style>
        #loader-container{display:block;position:fixed;z-index:100;left:0;top:0;width:100vw;height:100vh;background:#EEE;overflow:hidden}#loader-container .loader-inner{text-align:center;position:absolute;top:40vh;left:0;width:100vw;transform:scale(1)}#loader-container .loader-inner div{background-color:#1349A2}
    </style>-->

</head>
<body>
<main>
<!--<div id="loader-container" style="display:none">
    <div id="loader">
        <div class="loader-inner line-scale">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>
</div>-->
<!--<script type="text/javascript">
    var loadersign = document.getElementById('loader-container');
    loadersign.style.display = "block";
    window.onload = function () {
        loadersign.style.display = "none";
    }
</script>-->
