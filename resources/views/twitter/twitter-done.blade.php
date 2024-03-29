<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Twitter Bounty Program</title>
        <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/fontawesome.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/style.css') }}" rel="stylesheet">
        <link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">
        <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">

    </head>
    <body>
        <div id="main" class="hero-gradient-dark gradient-primary">
            <div class="container">
                <form method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-12 text-center">
                            <img src="{{ asset('images/bounty-twitter.png') }}" class="img-fluid twitter-bounty-logo mb-4">
                            <h1 class="text-gray my-4">Õpet Twitter Bounty Program</h1>
                        </div>
                    </div>
                    <div class="row mb-1 text-center">
                        <div class="col-12">
                            <h2 class="mb-4" style="color: #786251">Thank you for your participation. Please follow our Telegram for up to date information on the bounty program. </h2>
                            <span>Your referral link is:</span>
                            <h3><a href="{{ $link }}">{{ $link }}</a></h3>
                        </div>
                        <div class="col-12 my-4" style="font-size: 14px">
                            You will receive 2 OPET for every referee that <span style="text-decoration: underline">COMPLETES</span> the bounty program with your link, regardless if the referee is awarded the bounty rewards.<br/>(You must complete <span style="text-decoration: underline">ALL</span> steps to be awarded tokens for your referrals.)
                        </div>
                </form>
            </div>
        </div>

        <script type="text/javascript" src="{{ asset('js/jquery.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    </body>
</html>
