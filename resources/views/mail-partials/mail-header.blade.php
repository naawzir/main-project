<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <!--  -->
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

    <title>{{ $title }}</title>

    <style type="text/css">

        .ReadMsgBody {
            width: 100%; 
            background-color: #ffffff;
        }

        .ExternalClass {
            width: 100%; 
            background-color: #ffffff;
        }

        body {
            width: 100%; 
            background-color: #ffffff; 
            margin:0; 
            padding:0; 
            -webkit-font-smoothing: antialiased;
            font-family: Helvetica, sans-serif;
        }

        table {
            border-collapse: collapse;
        }

        .blockquote {
            background-color: #FFFFFF;
            padding: 10px;
            border-left: 4px solid #DDD;
            margin: 0;
        }

        h2 {
            margin: 0;
            color: #000;
            font-weight: normal;
            font-size: 1.2em;
            line-height: 1.5em;
        }

        h3 {
            font-weight: normal;
        }

        .action {
        	background-color: #f0b62c;
        }

        .action-btn{
			width: 30%;
        }

        .action-btn a{
        	color: #fff;
            font-weight: bold;
            text-decoration: none;
        }

        .button {
        	display: inline-block;
		    vertical-align: middle;
		    margin: 0 0 1rem 0;
		    font-family: inherit;
		    padding: 0.85em 1em;
		    -webkit-appearance: none;
		    border: 1px solid transparent;
		    border-radius: 0;
		    -webkit-transition: background-color 0.25s ease-out, color 0.25s ease-out;
		    transition: background-color 0.25s ease-out, color 0.25s ease-out;
		    font-size: 0.9rem;
		    line-height: 1;
		    text-align: center;
		    cursor: pointer;       	
        }

        @media only screen and (max-width: 640px)  {
            
            body[yahoo] .deviceWidth {
                width:440px!important; 
                padding:0;
            }
            
            body[yahoo] .center {
                text-align: center!important;
            }
        }

        @media only screen and (max-width: 479px) {
            
            body[yahoo] .deviceWidth {
                width:280px!important; 
                padding:0;
            }

            body[yahoo] .center {
                text-align: center!important;
            }
        }

        @media screen and (min-width: 601px) {

            .container {
                width: 600px!important;
            }
        }

    </style>

</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" yahoo="fix" style="font-family: helvetica, Sans-serif" bgcolor="#ffffff">

<!-- Wrapper -->
<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" bgcolor="#ffffff" style="max-width: 600px;">
    <tr>
        <td width="100%" valign="top" bgcolor="#ffffff" style="padding-top:20px; max-width: 600px;" align="center">
            <table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="deviceWidth" style="margin:0 auto;" bgcolor="white">
    <tr>
        <td width="100%" bgcolor="#ffffff" align="center">

            <!-- Logo -->
            <table border="0" cellpadding="0" cellspacing="0" align="center" width="100%" class="deviceWidth">
                <tr>
                    <td style="padding:10px" align="center" width="100%">
                        <a href="https://www.tcp-ltd.co.uk"><img src="{{ asset('img/TCP_mail.png') }}" alt="" border="0" style="display: block;" align="center" /></a>
                    </td>
                </tr>
            </table>
            <!-- End Logo -->

        </td>
    </tr>
</table>  

<table width="600"  class="deviceWidth" border="0" cellpadding="0" cellspacing="0" align="center"  style="margin:0 auto;">
    <tr>
        <td style="font-size: 13px; color: #959595; font-weight: normal; text-align: left; font-family: Georgia, Times, serif; line-height: 24px; vertical-align: top; padding:0">
            <table width="100%">
                <tr>
                    <td valign="middle" style="padding-left:20px; padding-top:10px; padding-bottom:10px; font-size: 14px; color: #fff; font-weight: bold; font-family:Helvetica, sans-serif;" bgcolor="#f1b347">{{ $title }}</td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<!-- End One Column -->                                                            

<!-- One Column -->
<table width="600"  class="deviceWidth" border="0" cellpadding="0" cellspacing="0" align="center"  style="margin:0 auto;">
    <tr>
        <td style="font-size: 13px; color: #959595; font-weight: normal; text-align: left; font-family: Georgia, Times, serif; line-height: 24px; vertical-align: top; padding:0">
            <table width="100%">
                <tr>
                    <td style="font-size: 13px; color: #646363; font-weight: normal; text-align: left; font-family: Helvetica, sans-serif; line-height: 20px; vertical-align: top; padding:20px;" bgcolor="#f8f8f8">

                	@yield('content')
                	@yield('footer')