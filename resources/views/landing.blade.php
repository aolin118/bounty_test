<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>BCoin.sg Bounty Program</title>
        <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/fontawesome.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/style.css') }}" rel="stylesheet">
        <link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">
        <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">
    </head>
    <body>
        <div id="main" class="hero-gradient-dark gradient-primary">
            <div class="container">
                <form method="POST" id="bounty-form" action="{{ route('bounty-submit-post') }}">
                    @csrf
                    <div class="row">
                        <div class="col-12 text-center">
                            <a href="https://www.bcoinsg.io/" target="_blank"><img src="{{ asset('images/logo.png') }}" class="img-fluid airdrop-logo mb-4"></a>
                            <h1 class="text-accent">BCoin Bounty Program</h1>
                        </div>
                    </div>
                    <div class="divider"></div>
                    <div class="row mt-4">
                        <div class="col-12 text-center mb-2">
                            <h4 class="text-white">Enter your Email address to begin</h4>
                        </div>
                        <div class="col-10 col-lg-6 text-center mx-auto">
                            <input type="email" class="form-control text-center" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" title="Invalid Email" id="email" name="email" placeholder="Email Address" required>
                            <small class="form-text text-muted px-3 mt-2">Kindly register the same email address used to register for your BCoin.sg Exchange trading account. <span style="color: #CE2D4F">Reminder: Bounty tokens will be credited to your BCoin.sg Exchange account with the same email address.</span></small>
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
                    <div class="row terms-container">
                        <div class="col-10 mx-auto text-center">
                            <div class="card border-0 mb-3 h-100">
                                <div class="card-header bg-secondary text-white">Terms & Conditions</div>
                                <div class="card-body text-left">
                                    <ul class="my-0">
                                        <li><small>Only the first 50,000 successful registrants who complete the tasks for each particular program would be entitled to receive the reward for that program.</small></li>
                                        <li><small>Each person is only entitled to receive the full bounty reward by completing all the necessary tasks once.</small></li>
                                        <li><small>Any users attempting to conduct malicious activity such as signing up with bots, using multiple accounts, using fake accounts, or using compromised accounts will be immediately banned from the program, and have their credentials blacklisted, barring them from taking part in any future online programs.</small></li>
                                        <li><small>Tokens will only be distributed after the token sale has ended.</small></li>
                                        <li><small>The management reserves the right to amend the terms and conditions and award its participants accordingly.</small></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <script type="text/javascript" src="{{ asset('js/jquery.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    </body>
</html>