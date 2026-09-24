<?php $bgimg = base_url('assets/img//kassim_background_img.png'); ?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Email Template</title>
    <style>
        .kassimbaba-detils {
            background: #fedca0 url('<?php echo $bgimg;?>') no-repeat;
        }

        .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
            border-bottom: 1px solid #dddddd;
            line-height: 1.42857;
            padding: 8px;
            vertical-align: top;
        }
    </style>
</head>
<body style="margin:0; padding:0; background:#eee">
<table style="width: 600px; margin: auto;" border="0" width="600px" cellspacing="0" cellpadding="0" align="center">
    <tbody>
    <tr>
        <td class="kassimbaba-detils"
            style="padding: 0; vertical-align: top; font-family: Arial, Helvetica, sans-serif; text-align: center; position: relative;">
            <div style="margin: 0; padding: 25px 0;">
                <p style="margin: 5px 0;"><img src="<?php echo base_url(); ?>upload/siteimages/email_logo.png" alt=""/>
                </p>
            </div>
            <div
                style="background-color: #ffffff; padding: 25px; text-align: left; position: relative; margin: 0 30px 0; font-size: 14px; color: #848484;">
                <h4 style="margin: 0; color: #fab429;">Hello <?php echo $name; ?>,</h4>
                <?php echo $content; ?>
            </div>
        </td>
    </tr>
    </tbody>
</table>
</body>
</html>