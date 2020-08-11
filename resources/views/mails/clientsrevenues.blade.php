<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>A Simple Responsive HTML Email</title>
<style type="text/css">
body {margin: 0; padding: 0; min-width: 100%!important;}
img {height: auto;}
.content {width: 100%; max-width: 600px;}
.header {padding: 40px 30px 20px 30px;}
.innerpadding {padding: 30px 30px 30px 30px;}
.borderbottom {border-bottom: 1px solid #f2eeed;}
.subhead {font-size: 15px; color: #ffffff; font-family: sans-serif; letter-spacing: 10px;}
.h1, .h2, .bodycopy {color: #153643; font-family: sans-serif;}
.h1 {font-size: 33px; line-height: 38px; font-weight: bold;}
.h2 {padding: 0 0 15px 0; font-size: 24px; line-height: 28px; font-weight: bold;}
.bodycopy {font-size: 16px; line-height: 22px;}
.button {text-align: center; font-size: 18px; font-family: sans-serif; font-weight: bold; padding: 0 30px 0 30px;}
.button a {color: #ffffff; text-decoration: none;}
.footer {padding: 20px 30px 15px 30px;}
.footercopy {font-family: sans-serif; font-size: 14px; color: #ffffff;}
.footercopy a {color: #ffffff; text-decoration: underline;}

@media only screen and (max-width: 550px), screen and (max-device-width: 550px) {
body[yahoo] .hide {display: none!important;}
body[yahoo] .buttonwrapper {background-color: transparent!important;}
body[yahoo] .button {padding: 0px!important;}
body[yahoo] .button a {background-color: #e05443; padding: 15px 15px 13px!important;}
body[yahoo] .unsubscribe {display: block; margin-top: 20px; padding: 10px 50px; background: #2f3942; border-radius: 5px; text-decoration: none!important; font-weight: bold;}
}



</style>
</head>

<body yahoo bgcolor="#f8fafc">
<table width="100%" bgcolor="#f8fafc" border="0" cellpadding="0" cellspacing="0">
<tr>
<td>  
    <table bgcolor="#ffffff" class="content" align="center" cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td bgcolor="#ffffff" class="header">
        <table class="col425" align="center" border="0" cellpadding="0" cellspacing="0" style="width: 100%; max-width: 425px;">  
            <tr>
            <td height="70">
                <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td class="subhead" align="center" style="padding: 0 0 0 3px;">
                    <img src="https://www.vozdigital.cl/img/logo.png" alt="" srcset="">
                    </td>
                </tr>
                </table>
            </td>
            </tr>
        </table>
        </td>
    </tr>
    <tr>
        <td class="innerpadding borderbottom">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
            <td align="center" class="h2">
                Consumos diarios!
            </td>
            </tr>
            <tr>
            <td align="center" class="bodycopy">
                Se hace entrega de {{ $data['revenues_date'] }}, si tiene algún problema contáctese con el
                <a href="mailto:noc@vozdigital.cl" style="color:rgb(17,85,204)" target="_blank"><span style="color:rgb(5,99,193)">servicio técnico</span></a>.
            </td>
            </tr>
        </table>
        </td>
    </tr>
    <tr>
        <td class="innerpadding borderbottom bodycopy" align="center">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <th>Llamadas</th>
                    <th>Minutos</th>
                </tr>
                <tr align="center">
                    <td>{{ number_format($data['calls'], 0, ',', '.' ) }}</td>
                    <td>{{ number_format($data['minutes'], 0, ',', '.' ) }}</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td class="innerpadding borderbottom">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
            <td align="center" class="bodycopy">
                <b>Encuentra en nosotros el respaldo que tu empresa necesita.</b><br/>
                <span style="font-size: small;">Nos mueve, nos motiva, nos gusta la comunicación</span>
            </td>
            </tr>
        </table>
        </td>
    </tr>
    <tr>
        <td class="footer" bgcolor="#B47EB6">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
            <td align="center" class="footercopy">
                Copyright © 2020 - Vozdigital<br/>
            </td>
            </tr>
        </table>
        </td>
    </tr>
    </table>
    </td>
</tr>
</table>
</body>
</html>