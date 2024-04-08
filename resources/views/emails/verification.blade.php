<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Document</title>
    <style>
        .mail-container{
            background: url("<?php echo asset('images/login-bg.png') ?>");
            background-size: cover;
            background-attachment: fixed;
            background-repeat: no-repeat;
        }

        p{
            font-size: 18px;
            text-align: left;
        }

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
    </style>
</head>
<body>
    <div class="d-flex flex-column align-items-center justify-content-center mail-container py-5">
        <div style="width:650px;border: 2px solid #000;border-radius: 15px;background: #fff" class="px-5">
            <div class="w-100 d-flex align-items-center justify-content-center">
                <div class="logo d-flex align-items-center mt-5">
                    <img src="{{ asset('images/logo/logo-black.png') }}" alt="">
                    <div class="ms-2">
                        <h4 class="mb-0">Owner Gate</h4>
                        <span>Real Estate</span>
                    </div>
                </div>
            </div>
            <?php 
                // $name = 'Don';
                // $link = 'http://ownergate.com/reset'
            ?>

            <p class="mb-5 mt-5">Hi {{ $name }}</p>
            
            <p class="mb-5 text-left">Thanks for signing up with Owner Gate.</p>

            <p class="mb-5 text-left">We need a little more information to complete your registration, including a confirmation of your email address.
            </p>

            <p class="mb-5 text-left">Click the link below to confirm your email address and use this code for verification:</p>

            <div class="code d-flex align-items-center justify-content-center mb-5">
                {{ $code }}    
            </div>
    
            <p class="mb-5 text-left"><a href="{{ $link }}">{{ $link }}</a></p>

    
            <p class="mb-5 text-left">If you have problems, please paste the above URL into your web browser.
            </p>
    
            <p class="mb-0 text-left">Thanks,</p>
            <p class="mb-5 text-left text-primary">Owner Gate Team</p>
    
        </div>
    </div>
</body>
</html>