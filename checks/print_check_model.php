<html><head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
        <link rel="stylesheet" href="css/bootstrap.css">
        <link rel="stylesheet" href="css/advsearch.css" type="text/css" media="screen" />
        <title>Check</title>
        <style type="text/css">
            body {
                font-size: 12px;
                font-family:Georgia, "Times New Roman", Times, serif;
            }
            #main_check {
                position:absolute;
                left:9px;
                top:14px;
                width:846px;
                height:304px;
                z-index:1;
                border-color:#FFFFFF;
                background-color: #FFFFFF;
                font-size: 11px;
            }
            #dealer_div {
                position: absolute;
                left: 46px;
                top: 25px;
                width: 150px;
                height: 58px;
                z-index: 2;
                font-size: 11px;
            }
            #logo_image_div {
                position: absolute;
                left: 272px;
                top: 25px;
                width: 170px;
                height: 58px;
                z-index: 3;
            }
            #dealer_bank_account_div {
                position: absolute;
                left: 513px;
                top: 25px;
                width: 143px;
                height: 58px;
                z-index: 4;
                font-size:11px
            }
            #check_number_div {
                position: absolute;
                left: 702px;
                top: 23px;
                width: 101px;
                height: 18px;
                z-index: 5;
                font-size:11px
            }
            #transit_code_div {
                position: absolute;
                left: 751px;
                top: 44px;
                width: 72px;
                height: 18px;
                z-index: 6;
                font-size: 8px
            }
            #check_date_div {
                position: absolute;
                left: 703px;
                top: 65px;
                width: 101px;
                height: 18px;
                z-index: 7;
                font-size:11px
            }
            #payee_div {
                position:absolute;
                left:24px;
                top:118px;
                width:133px;
                height:26px;
                z-index:8;
                font-size:12px
            }
            #payee_div {
                position: absolute;
                left: 117px;
                top: 103px;
                width: 562px;
                height: 18px;
                z-index: 9;
                font-size:11px
            }
            #payee_address1_div {
                position: absolute;
                left: 123px;
                top: 184px;
                width: 574px;
                height: 18px;
                z-index: 12;
                font-size:11px
            }
            #payee_address2_div {
                position: absolute;
                left: 123px;
                top: 204px;
                width: 574px;
                height: 18px;
                z-index: 13;
                font-size:11px
            }
            #payee_address3_div {
                position: absolute;
                left: 123px;
                top: 224px;
                width: 574px;
                height: 18px;
                z-index: 14;
                font-size:11px
            }
            #check_amount_div {
                position: absolute;
                left: 717px;
                top: 100px;
                width: 101px;
                height: 18px;
                z-index: 9;
                font-size:12px
            }
            #check_amount_letter_div {
                position: absolute;
                left: 27px;
                top: 135px;
                width: 665px;
                height: 18px;
                z-index: 10;
                font-size:12px
            }
            #dollars_div {
                position: absolute;
                left: 703px;
                top: 135px;
                width: 50px;
                height: 15px;
                z-index: 12;
                font-size:11px
            }
            #payee_name_address_div {
                position:absolute;
                left:62px;
                top:197px;
                width:143px;
                height:64px;
                z-index:11;
                font-size:11px
            }
            #memo_title_div {
                position: absolute;
                left: 27px;
                top: 249px;
                width: 41px;
                height: 15px;
                z-index: 14;
                font-size:11px
            }
            #memo_div {
                position: absolute;
                left: 90px;
                top: 248px;
                width: 300px;
                height: 18px;
                z-index: 15;
                font-size:11px
            }
            #signature_image_div {
                position: absolute;
                left: 499px;
                top: 231px;
                width: 229px;
                height: 33px;
                z-index: 16;
                text-align: center;
            }
            #account_number_div {
                position: absolute;
                left: 171px;
                top: 292px;
                width: 604px;
                height: 20px;
                z-index: 17;
                font-size: 24px;
                padding-top: 5px;
            }
            #payee_name_title_div {
                position: absolute;
                left: 27px;
                top: 162px;
                width: 92px;
                height: 15px;
                z-index: 18;
                font-size:11px
            }
            #payee_address_title_div {
                position: absolute;
                left: 27px;
                top: 184px;
                width: 92px;
                height: 13px;
                z-index: 18;
                font-size:11px
            }
            #separator {
                position:absolute;
                left:9px;
                top:382px;
                width:870px;
                height:2px;
                z-index:19;
            }
            #payee_name_div {
                position: absolute;
                left: 123px;
                top: 162px;
                width: 574px;
                height: 18px;
                z-index: 9;
                font-size:11px
            }
            #payee_title_div {
                position: absolute;
                left: 27px;
                top: 95px;
                width: 74px;
                height: 15px;
                z-index: 20;
                font-size:11px
            }
            #apDiv2 {
                position:absolute;
                left:13px;
                top:403px;
                width:850px;
                height:80px;
                z-index:21;
                font-size:11px
            }
            #apDiv1 {
                position: absolute;
                left: 18px;
                top: 354px;
                width: 60px;
                height: 18px;
                z-index: 22;
                font-size:11px
            }
            #apDiv3 {
                position: absolute;
                left: 59px;
                top: 11px;
                width: 782px;
                height: 23px;
                z-index: 16;
                font-size:11px
            }
            #apDiv4 {
                position: absolute;
                left: 18px;
                top: 379px;
                width: 53px;
                height: 18px;
                z-index: 24;
                font-size:11px
            }
            #apDiv5 {
                position: absolute;
                left: 73px;
                top: 380px;
                width: 250px;
                height: 18px;
                z-index: 17;
                font-size:11px
            }
            #apDiv6 {
                position: absolute;
                left: 698px;
                top: 100px;
                width: 12px;
                height: 16px;
                z-index: 26;
                font-size:11px
            }
            #apDiv7 {
                position: absolute;
                left: 447px;
                top: 263px;
                width: 330px;
                height: 1px;
                z-index: 27;
                background-color: #000000;
                font-size:11px
            }
            #apDiv8 {
                position: absolute;
                left: 75px;
                top: 264px;
                width: 330px;
                height: 1px;
                z-index: 27;
                background-color: #000000;
                font-size:11px
            }
            hr {
                position:absolute;
                left:11px;
                top:339px;
                border:none;
                border-top:1px dotted #000;
                color:#fff;
                background-color:#fff;
                height:1px;
                width:850px;
            }
            #save_div {
                position: absolute;
                left: 9px;
                top: 491px;
                width: 870px;
                height: 25px;
                text-align:right;
                z-index: 21;
            }
            #apDiv9 {
                position:absolute;
                left:93px;
                top:119px;
                width:591px;
                height:1px;
                z-index:28;
                background-color: #000000;
                font-size:11px
            }
            #apDiv10 {
                position:absolute;
                left:27px;
                top:151px;
                width:663px;
                height:1px;
                z-index:29;
                background-color: #000000;
                font-size:11px
            }
            #apDiv11 {
                position:absolute;
                left:346px;
                top:354px;
                width:53px;
                height:18px;
                z-index:30;
                font-size:11px
            }
            #apDiv12 {
                position:absolute;
                left:408px;
                top:354px;
                width:87px;
                height:18px;
                z-index:31;
                font-size:11px
            }
            #apDiv13 {
                position:absolute;
                left:650px;
                top:354px;
                width:39px;
                height:18px;
                z-index:32;
                font-size:11px
            }
            #apDiv14 {
                position:absolute;
                left:694px;
                top:354px;
                width:61px;
                height:18px;
                z-index:33;
                font-size:11px
            }
            #apDiv15 {
                position:absolute;
                left:18px;
                top:671px;
                width:60px;
                height:18px;
                z-index:34;
                font-size:11px
            }
            #apDiv16 {
                position:absolute;
                left:18px;
                top:696px;
                width:53px;
                height:18px;
                z-index:35;
                font-size:11px
            }
            #apDiv17 {
                position:absolute;
                left:73px;
                top:697px;
                width:250px;
                height:18px;
                z-index:36;
                font-size:11px
            }
            #apDiv18 {
                position:absolute;
                left:346px;
                top:675px;
                width:53px;
                height:18px;
                z-index:37;
                font-size:11px
            }
            #apDiv19 {
                position:absolute;
                left:408px;
                top:675px;
                width:87px;
                height:18px;
                z-index:38;
                font-size:11px
            }
            #apDiv20 {
                position:absolute;
                left:650px;
                top:668px;
                width:39px;
                height:18px;
                z-index:39;
                font-size:11px
            }
            #apDiv21 {
                position:absolute;
                left:694px;
                top:668px;
                width:61px;
                height:18px;
                z-index:40;
                font-size:11px
            }
        </style>
    </head><body>
        <div id="main_check"></div>
        <div id="apDiv8"></div>
        <div id="dealer_div">'.$company_div.'</div>
        <div id="logo_image_div">'.$logo_image_div.'</div>
        <div id="dealer_bank_account_div">'.$company_bank_account_div.'</div>
        <div id="check_number_div">'.$check_number_div.'</div>
        <div id="transit_code_div">'.$transit_code_div.'</div>
        <div id="check_date_div">'.$check_date.'</div>
        <div id="payee_title_div"><strong>Pay To The Order of</strong></div>
        <div id="payee_div">'.$payee.'</div>
        <div id="check_amount_div">'.$check_amount.'</div>
        <div id="check_amount_letter_div">'.$check_amount_letter.'</div>
        <div id="dollars_div"><strong>Dollars</strong></div>
        <div id="payee_name_title_div"><strong>Payee Name</strong></div>
        <div id="payee_name_div">'.$payee_name2.'</div>
        <div id="payee_address_title_div"><strong>Payee Address</strong></div>
        <div id="payee_address1_div">'.$payee_address1.'</div>
        <div id="apDiv6"><strong>$</strong></div>
        <div id="apDiv7"></div>
        <div id="payee_address2_div">'.$payee_address2.'</div>
        <div id="payee_address3_div">'.$payee_address3.'</div>
        <div id="memo_title_div"><strong>Memo:</strong></div>
        <div id="memo_div">'.$memo.'</div>
        <div id="signature_image_div">'.$signature_image_div.'</div>
        <div id="account_number_div">'.$account_image.'</div>
        <hr>
        <div id="apDiv9"></div>
        <div id="apDiv10"></div>
        <div id="apDiv11"><strong>Amount:</strong></div>
        <div id="apDiv12">'.$check_amount.'</div>
        <div id="apDiv13"><strong>Date:</strong></div>
        <div id="apDiv14">'.$check_date.'</div>
        <div id="apDiv1"><strong>'.$check_number.'</strong></div>
        <div id="apDiv4"><strong>Pay to:</strong></div>
        <div id="apDiv5">'.$payee.'</div>
        <div id="apDiv15"><strong>'.$check_number.'</strong></div>
        <div id="apDiv16"><strong>Pay to:</strong></div>
        <div id="apDiv17">'.$payee.'</div>
        <div id="apDiv18"><strong>Amount:</strong></div>
        <div id="apDiv19">'.$check_amount.'</div>
        <div id="apDiv20"><strong>Date:</strong></div>
        <div id="apDiv21">'.$check_date.'</div>
        <div/>
    </body></html>