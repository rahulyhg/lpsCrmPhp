<!DOCTYPE html>
<!--
 Author S. Brinta <brrinta@gmail.com>
 Email: i@brinta.me
 Web: https://brinta.me
 Do not edit file without permission of author
 All right reserved by S. Brinta <brrinta@gmail.com>
 Created on: Sep 8, 2018 11:00:42 AM
-->
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <link rel="stylesheet" type="text/css" href="<?= property_url() ?>app-assets/css/vendors.css">
        <title><?= $title ?></title>

    </head>
    <body>
        <?php
        // put your code here
        ?>
        <script>
            var api_url = "https://triposcert.vantiv.com/api/v1";
            var test = {
                AccountID: "1052100",
                AccountToken: "64E6ADBCF6BF6BB3337724E6E3D480E2A0EFE8D480C1787F8B00296747CC282884CBAA01",
                ApplicationID: "9151",
                AcceptorID: "3928907"
            };
            var data = {
                "tp-application-id": test.ApplicationID,
                "tp-express-account-token": test.AccountToken,
                "tp-express-acceptor-id": test.AcceptorID,
                "tp-express-account-id": test.AccountID,
                "tp-application-name":"Express.ONE"
            };
            $.ajax({
                url: api_url,
                type: 'POST',
                data: data,
                dataType: 'JSON',
                success: function (data, textStatus, jqXHR) {
                    console.log(data);
                }, error: function (jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR, textStatus, errorThrown);
                }
            });
        </script>
    </body>
</html>
