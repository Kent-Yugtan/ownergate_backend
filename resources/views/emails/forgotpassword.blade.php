<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Document</title>
    <style>
        .code{
            width: 380.7px;
            height: 112.42px;
            top: 515.21px;
            left: 769.65px;
            border-radius: 8.22px;
            background: #E6E6E6;
            font-size: 74px;
            font-weight: 400;
        }
        .mail-container{
            background: url("<?php echo asset('images/login-bg.png') ?>");
            background-size: cover;
            background-attachment: fixed;
            background-repeat: no-repeat;
        }
    </style>
</head>
<body>
    <div class="d-flex flex-column align-items-center justify-content-center mail-container py-5">
        <div style="width:650px;border: 2px solid #000;border-radius: 15px;background: #fff" class="d-flex flex-column align-items-center justify-content-center">
            <div class="logo d-flex align-items-center mt-5">
                <img src="{{ asset('images/logo/logo-black.png') }}" alt="">
                <div class="ms-2">
                    <h4 class="mb-0">Owner Gate</h4>
                    <span>Real Estate</span>
                </div>
            </div>
            <?php 
                    // $name = 'Don';
                    // $code = 1234;
                    // $expiry = 10;
            ?>
    
            <h1 class="text-center text-primary my-5">Hi {{ $name }}!</h1>
    
            <p class="mb-0 text-center">You recently requested resetting your password.</p>
            <p class="mb-5 text-center">Use your secret code below for resetting your password:</p>
    
            <div class="code d-flex align-items-center justify-content-center">
                {{ $code }}    
            </div>
    
            <p class="mb-0 mt-5 text-center">If you did not request a password reset, please ignore this email.</p>
            <p class="mb-5 text-center">This password reset Code is only valid for the next {{ $expiry }} minutes.:</p>
    
            <p class="mb-0 text-center">Thanks,</p>
            <p class="mb-5 text-center text-primary">Owner Gate Team</p>
    
        </div>
    </div>
</body>
</html>