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
                            <h2 class="mb-4" style="color: #786251">Õpet’s Twitter bounty program ended on 22nd May 2018. Thank you for your interest in Õpet. Please continue to follow us on Twitter as we calculate your tokens earned!</h2>
                        </div>
                        <div class="col-12 my-4" style="font-size: 14px">
                            The tokens will be distributed 2-3 weeks after the end date above. Thank you for your patience!
                        </div>
                </form>
            </div>
        </div>

        <script type="text/javascript" src="{{ asset('js/jquery.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    </body>
</html>
