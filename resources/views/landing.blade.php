<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>BCoin Bounty Program</title>
        <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/fontawesome.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/style.css') }}" rel="stylesheet">
        <link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">
        <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">
    </head>
    <body>
        <div id="main" class="hero-gradient-dark gradient-primary">
            <div class="container">
                <form method="POST" id="bounty-form">
                    @csrf
                    <div class="row">
                        <div class="col-12 text-center">
                            <img src="{{ asset('images/logo.png') }}" class="img-fluid airdrop-logo mb-4">
                            <h1 class="text-accent">BCoin Bounty Program</h1>
                        </div>
                    </div>
                    <div class="divider"></div>
                    <div class="row mt-4">
                        <div class="col-12 text-center mb-2">
                            <h4 class="text-white">Enter your Ethereum address to begin</h4>
                        </div>
                        <div class="col-10 col-lg-6 text-center mx-auto">
                            <input type="text" class="form-control text-center" pattern="(0x)?[0-9a-zA-Z]{40}" title="Invalid Ethereum Address" id="eth_address" name="eth_address" placeholder="Ethereum Address" required>
                            <small class="form-text text-muted px-3 mt-2">You can create a wallet from <a href="https://metamask.io/">MetaMask</a> or <a href="https://www.myetherwallet.com/">MyEtherWallet</a>. <span style="color: #CE2D4F">Do not use your exchange wallet address!</span></small>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-12 text-center">
                            @if($errors->any())
                            <p class="text-danger mb-4">{{$errors->first()}}</p>
                            @endif
                            @if (isset($referrer))
                            <input type="hidden" name="referrer" id="referrer" value="{{ $referrer }}">
                            @endif
                            <button type="submit" class="btn btn-success" id="submit-btn">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <script type="text/javascript" src="{{ asset('js/jquery.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
        <script>
            $('#bounty-form').submit(function(event){
                $('#bounty-form').attr('action', "{{ URL::to('/') }}/a/" + $('#eth_address').val());

                if($("#referrer").length == 0) {
                    event.preventDefault();
                    window.location.href = "{{ URL::to('/') }}/a/" + $('#eth_address').val();
                    return false;
                }
            });
        </script>
    </body>
</html>