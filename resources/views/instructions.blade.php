<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>SCC Airdrop</title>
        <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/fontawesome.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/style.css') }}" rel="stylesheet">
        <link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">
        <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">
    </head>
    <body>
        <div id="main" class="hero-gradient-dark gradient-primary">
            <div class="container">
                <form method="POST" action="{{ route('airdrop-post') }}">
                    @csrf
                    <div class="row">
                        <div class="col-12 text-center">
                            <img src="{{ asset('images/logo.png') }}" class="img-fluid airdrop-logo mb-4">
                            <h1 class="text-white my-4">Source Code Chain Airdrop</h1>
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-4 col-lg-2 offset-lg-3 steps">
                            <div class="ml-2 numberCircle">1</div>
                        </div>
                        <div class="col-7 col-lg-7 instructions text-white">
                            <div>Join our <a href="https://t.me/SCCgroup" target="_blank">Telegram Group</a></div>
                        </div>
                    </div>
                    <div class="divider"></div>
                    <div class="row mb-1">
                        <div class="col-4 col-lg-2 offset-lg-3 steps">
                            <div class="ml-2 numberCircle">2</div>
                        </div>
                        <div class="col-7 col-lg-7 instructions text-white">
                            <div>Register with our <a href="http://t.me/SCC_Airdrop_Bot?start={{ $user->unique_link }}" target="_blank">Telegram Bot</a></div>
                        </div>
                    </div>

                    <div class="divider"></div>
                    <div class="row mt-4">
                        <div class="col-12 text-center">
                            @if($errors->any())
                            <p class="text-danger mb-4">{{$errors->first()}}</p>
                            @endif
                            <input type="hidden" name="eth_address" id="eth_address" value="{{ $user->eth_address }}">
                            <input type="hidden" name="complete" id="complete" value="true">
                            <button type="submit" class="btn btn-success" id="submit-btn">Next</button>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-12 text-center text-muted" style="font-size: 14px">
                            You will be awarded 1 SCC for completing the steps above.
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <script type="text/javascript" src="{{ asset('js/jquery.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    </body>
</html>