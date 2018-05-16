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
                <form method="POST" action="{{ route('twitter-post') }}">
                    @csrf
                    <div class="row">
                        <div class="col-12 text-center">
                            <img src="{{ asset('images/bounty-twitter.png') }}" class="img-fluid twitter-bounty-logo mb-4">
                            <h1 class="text-gray my-4">Õpet Twitter Bounty Program</h1>
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-4 offset-lg-2 steps">
                            Step <div class="ml-2 numberCircle">1</div>
                        </div>
                        <div class="col-7 col-lg-5 instructions">
                            <div>Join our <a href="https://t.me/Opetfoundationgroup" target="_blank">Telegram group</a></div>
                        </div>
                    </div>
                    <div class="divider"></div>
                    <div class="row mb-1">
                        <div class="col-4 offset-lg-2 steps">
                            Step <div class="ml-2 numberCircle">2</div>
                        </div>
                        <div class="col-7 col-lg-5 instructions">
                            <div>Follow us on <a href="https://twitter.com/opetfoundation" target="_blank">Twitter</a></div>
                        </div>
                    </div>
                    <div class="divider"></div>
                    <div class="row mb-1">
                        <div class="col-4 offset-lg-2 steps">
                            Step <div class="ml-2 numberCircle">3</div>
                        </div>
                        <div class="col-7 col-lg-5 instructions">
                            <div>Retweet our <a href="https://twitter.com/opetfoundation/status/996288621203329024" target="_blank">Tweet</a></div>
                        </div>
                    </div>
                    <div class="divider"></div>
                    <div class="row mb-1">
                        <div class="col-4 offset-lg-2 steps">
                            Step <div class="ml-2 numberCircle">4</div>
                        </div>
                        <div class="col-7 col-lg-5 instructions">
                            <div>Like our <a href="https://www.youtube.com/watch?v=RzOF_qQYu9M" target="_blank">video</a> and subscribe to our <a href="https://www.youtube.com/channel/UCpUNGBzAFCZJGLIfdofzEpQ" target="_blank">channel</a></div>
                        </div>
                    </div>
                    <div class="divider"></div>
                    <div class="row mb-1">
                        <div class="col-4 offset-lg-2 steps">
                            Step <div class="ml-2 numberCircle">5</div>
                        </div>
                        <div class="col-7 col-lg-5 instructions">
                            <div>
                                Give us your <input type="text" class="form-control" id="twitter_username" name="twitter_username" placeholder="Twitter Username" required> and <input type="text" class="form-control" id="eth_address" name="eth_address" placeholder="Ethereum Address" required>
                            </div>
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
                    <div class="row mt-4">
                        <div class="col-12 text-center" style="font-size: 14px">
                            This bounty program ends on 22 May, 2018. <span style="font-weight:bolder; text-decoration: underline">Only the top 5,000 public Twitter accounts with the highest number of followers will receive the bounty. </span> You will receive 10 tokens only if you complete all 5 steps and meet the requirement above. The tokens will be distributed 2-3 weeks after the bounty program ends.
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <script type="text/javascript" src="{{ asset('js/jquery.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    </body>
</html>
