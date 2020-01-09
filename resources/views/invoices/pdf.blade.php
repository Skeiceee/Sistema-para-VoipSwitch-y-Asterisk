<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body{
            color: rgb(67, 103, 132);
            font-family: sans-serif;
        }
        thead{
            background-color: rgb(67, 103, 132);
            color: rgb(255, 255, 255)
        }
    </style>
</head>
<body>
    <table style="width:100%;">
        <tr>
            <th style="width: 1em;"></th>
            <th style="text-align:left;">
                <img src="{{ public_path('img/logo.png') }}" alt="Vozdigital">
            </th>
            <th style="text-align:right;">
                <h1 style="font-weight: 700;">Invoice Support</h1>
            </th>
            <th style="width: 1em;"></th>
        </tr>
    </table>

    <hr style="margin-top: 1em;">

    <table style="width:100%;">
        <tr>
            <th style="width: 1em;"></th>
            <th style="text-align:left; width:35%">
                <h2 style="font-weight: 700;">Sociedad de Telecomunicaciones y Servicios SpA</h2>
                <div>Nos mueve, nos motiva, nos gusta la comunicación.</div>
            </th>
            <th style="text-align:right;">
                <table style="width: 100%">
                    <tr>
                        <th style="text-align: right; width: auto;">Date:</th>
                        <td style="text-align: left; width: 1px;">{{ $data['date'] }}</td>    
                    </tr>
                    <tr>
                        <th style="text-align: right; width: auto;">Invoice support N°:</th>    
                        <td style="text-align: left; width: auto;">{{ $data['invoice_support_n'] }}</td>       
                    </tr>
                    <tr>
                        <th style="text-align: right; width: auto;">Id. Customer:</th>  
                        <td style="text-align: left; width: auto;">{{ $data['id_customer'] }}</td>
                    </tr>
                </table>
            </th>
            <th style="width: 1em;"></th>
        </tr>
    </table>

    <hr style="margin-top: 1em; margin-bottom: 1em;">

    <table style="width:100%;">
        <tr>
            <th rowspan=5 style="width: 1em;"></th>
            <th style="text-align: right; width: 1px;">Customer:</th>
            <td style="text-align: left; width: auto;">{{ $data['customer'] }}</td>    
        </tr>
        <tr>
            <th style="text-align: right; width: 1px;">Address:</th>    
            <td style="text-align: left; width: auto;">{{ $data['address'] }}</td>    
        </tr>
        <tr>
            <th style="text-align: right; width: 1px;">City:</th>  
            <td style="text-align: left; width: auto;">{{ $data['city'] }}</td>    
        </tr>
        <tr>
            <th style="text-align: right; width: 1px;">Country:</th>  
            <td style="text-align: left; width: auto;">{{ $data['country']}}</td>    
        </tr>
        <tr>
            <th style="text-align: right; width: 1px;">Period:</th>  
            <td style="text-align: left; width: auto;">{{ $data['period'] }}</td>    
        </tr>
    </table>

    <table style="width:100%; margin-top: 1em;">
        <thead>
            <tr>
                <th>Description</th>
                <th>Calls</th>
                <th>Duration</th>
                <th>Amount in USD</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th>3</th>
                <td>Larry</td>
                <td>the Bird</td>
                <td>twitter</td>
            </tr>
            <tr>
                <th>3</th>
                <td>Larry</td>
                <td>the Bird</td>
                <td>twitter</td>
            </tr>
            <tr>
                <th>3</th>
                <td>Larry</td>
                <td>the Bird</td>
                <td>twitter</td>
            </tr>
        </tbody>
    </table>

    <table style="width:100%; margin-top: 1em; text-align: left;">
        <tr>
            <th rowspan=7 style="width: 1em;"></th>
            <th colspan=3 style="text-align: left;"><h3>Payment Wiring Instructions</h3></th>
        </tr>
        <tr>
            <th rowspan=6 style="width: 1em !important;"></th>
            <th style="margin: 0px; padding: 0px;">Beneficiary Name:</th>    
            <td style="margin: 0px; padding: 0px">Sociedad de telecomunicaciones y servicios SpA</td>    
        </tr>
        <tr>
            <th style="margin: 0px; padding: 0px;">Beneficiary Address:</th>  
            <td style="margin: 0px; padding: 0px">Condell 1190, oficina 81, Valparaíso, Región de Valparaíso, Chile</td>    
        </tr>
        <tr>
            <th style="margin: 0px; padding: 0px;">Account number:</th>  
            <td style="margin: 0px; padding: 0px">05-101-27244-02</td>    
        </tr>
        <tr>
            <th style="margin: 0px; padding: 0px;">Bank Name:</th>  
            <td style="margin: 0px; padding: 0px">Banco de Chile</td>    
        </tr>
        <tr>
            <th style="margin: 0px; padding: 0px;">Bank Address:</th>  
            <td style="margin: 0px; padding: 0px">Ahumada 251, Santiago, Región Metropolitana, Chile </td>    
        </tr>
        <tr>
            <th style="margin: 0px; padding: 0px;">Swift:</th>  
            <td style="margin: 0px; padding: 0px">BCHICLRM</td>    
        </tr>
    </table>

    <table style="width:100%; margin-top: 1em; text-align: left;">
        <tr>
            <th rowspan=3 style="width: 1em;"></th>
            <th colspan=3 style="text-align: left;"><h5><u>Intermediary bank</u></h5></th>
        </tr>
        <tr>
            <th rowspan=2 style="width: 1em !important;"></th>
            <th style="margin: 0px; padding: 0px;"><small>Bank Name:</small></th>    
            <td style="margin: 0px; padding: 0px">Sociedad de telecomunicaciones y servicios SpA</td>    
        </tr>
        <tr>
            <th style="margin: 0px; padding: 0px;"><small>Swift:</small></th>  
            <td style="margin: 0px; padding: 0px">Condell 1190, oficina 81, Valparaíso, Región de Valparaíso, Chile</td>    
        </tr>
    </table>

    <hr style="margin-top: 1em; margin-bottom: 1em;">

    <div style="text-align: center; margin-top: 1em; margin-bottom: 1em;">
        <h3 style="color: #000000; font-weight: 700;">Thanks for your confidence and preference</h3>
        <h3>Condell 1190, oficina 81, Valparaíso, Chile, administracion@vozdigital.cl</h3>
        <span><small>*This document hasn´t fiscal validity</small></span>
    </div>
</body>
</html>