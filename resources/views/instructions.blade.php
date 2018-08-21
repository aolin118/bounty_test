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
                <div class="row">
                    <div class="col-12 text-center">
                        <img src="{{ asset('images/logo.png') }}" class="img-fluid airdrop-logo mb-4">
                        <h1 class="text-accent">BCoin Bounty Program</h1>
                    </div>
                </div>
                <div class="row mb-0 mt-4">
                    <div class="col-12 col-lg-3 ml-auto step-container">
                        <div class="card border-0 mb-3 h-100">
                            <div class="card-header bg-secondary text-white">Telegram - 4 BCT</div>
                            <div class="card-body text-center">
                                <img src="{{ asset('images/telegram.png') }}" class="img-fluid mb-3">
                                <div>Join our Telegram Community</div>
                                <a href="#" class="btn btn-outline-primary mt-4" data-toggle="modal" data-target="#telegram-modal">More Info</a>

                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-3 step-container">
                        <div class="card border-0 mb-3 h-100">
                            <div class="card-header bg-secondary text-white">Twitter - 2 BCT</div>
                            <div class="card-body text-center">
                                <img src="{{ asset('images/twitter.png') }}" class="img-fluid mb-3">
                                <div>Follow us and retweet our Tweet</div>
                                <a href="#" class="btn btn-outline-primary mt-4" data-toggle="modal" data-target="#twitter-modal">More Info</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-3 mr-auto step-container">
                        <div class="card border-0 mb-3 h-100">
                            <div class="card-header bg-secondary text-white">YouTube - 2 BCT</div>
                            <div class="card-body text-center">
                                <img src="{{ asset('images/youtube.png') }}" class="img-fluid mb-3">
                                <div>Like our video and subscribe to our channel</div>
                                <a href="#" class="btn btn-outline-primary mt-4" data-toggle="modal" data-target="#youtube-modal">More Info</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-1 mt-0">
                    <div class="col-12 col-lg-3 ml-auto step-container">
                        <div class="card border-0 mb-3 h-100">
                            <div class="card-header bg-secondary text-white">Reddit - 2 BCT</div>
                            <div class="card-body text-center">
                                <img src="{{ asset('images/reddit.png') }}" class="img-fluid mb-3">
                                <div>Be a part of our Reddit Community</div>
                                <a href="#" class="btn btn-outline-primary mt-4" data-toggle="modal" data-target="#reddit-modal">More Info</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-3 mr-auto step-container">
                        <div class="card border-0 mb-3 h-100">
                            <div class="card-header bg-secondary text-white">Medium - 2 BCT</div>
                            <div class="card-body text-center">
                                <img src="{{ asset('images/medium.png') }}" class="img-fluid mb-3">
                                <div>Follow our Medium and clap for our article</div>
                                <a href="#" class="btn btn-outline-primary mt-4" data-toggle="modal" data-target="#medium-modal">More Info</a>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row mt-4">
                    <div class="col-12 text-center text-muted" style="font-size: 14px">
                        You will be awarded 1 SCC for completing the steps above.
                    </div>
                </div>
            </div>
        </div>

        <div id="telegram-modal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-body row">
                        <div class="col-12 text-right">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="col-12 text-center">
                            <img src="{{ asset('images/telegram.png') }}" class="img-fluid">
                            <h3 class="text-center mt-4">Telegram Reward Structure</h3>
                            @if (!$user->telegram()->exists())
                            <a href="http://t.me/BCoin_Bounty_Bot?start={{ $user->unique_link }}" target="_blank" class="btn btn-primary"><i class="fab fa-telegram-plane mr-2"></i>Register your Telegram</a>
                            @endif
                        </div>
                        <div class="col-12 mt-4">
                            <ul>
                                <li>Join our <a href="https://t.me/bcoinsg_EN" target="_blank">English Telegram Group</a> or <a href="https://t.me/bcoinsg_CN" target="_blank">Chinese Telegram Group</a></li>
                                <li>Join our <a href="https://t.me/bcoinsg" target="_blank">Telegram Channel</a></li>
                                <li><span class="text-danger">Reward is capped for the first 100,000 unique users across both the BCoin English and Chinese Telegram Groups.</span></li>
                            </ul>
                        </div>
                        @if ($user->telegram()->exists())
                        <div class="col-12 text-center mt-2 mb-2">
                            <button type="button" class="btn btn-success" id="telegram-verify-btn" onclick="telegramVerify()">Verify Completion</button>
                            <p id="telegram-error" class="text-danger my-2"><img src="{{ asset('images/loading.gif') }}" class="img-fluid loading"></p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div id="twitter-modal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal" role="document">
                <div class="modal-content">
                    <div class="modal-body row">
                        <div class="col-12 text-right">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="col-12 text-center">
                            <img src="{{ asset('images/twitter.png') }}" class="img-fluid">
                            <h3 class="text-center mt-4">Twitter Reward Structure</h3>
                            @if (!$user->twitter()->exists())
                            <a href="{{ $authURL['twitter'] }}" class="btn btn-primary"><i class="fab fa-twitter mr-2"></i>Sign In with Twitter</a>
                            @endif
                        </div>
                        <div class="col-12 mt-4">
                            <ul>
                                <li>Follow our <a href="https://twitter.com/Bcoin_sg" target="_blank">Twitter</a></li>
                                <li>Like and retweet our <a href="#" target="_blank">Tweet</a></li>
                            </ul>
                        </div>
                        @if ($user->twitter()->exists())
                        <div class="col-12 text-center my-4">
                            <button type="button" class="btn btn-success" onclick="twitterVerify()">Verify Completion</button>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div id="youtube-modal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal" role="document">
                <div class="modal-content">
                    <div class="modal-body row">
                        <div class="col-12 text-right">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="col-12 text-center">
                            <img src="{{ asset('images/youtube.png') }}" class="img-fluid">
                            <h3 class="text-center mt-4">YouTube Reward Structure</h3>
                            @if (!$user->youtube()->exists())
                            <a href="{{ $authURL['youtube'] }}" class="btn btn-danger"><i class="fab fa-youtube mr-2"></i>Sign In with YouTube</a>
                            @endif
                        </div>
                        <div class="col-12 mt-4">
                            <ul>
                                <li>Like our <a href="#" target="_blank">Video</a></li>
                                <li>Subscribe to our <a href="https://www.youtube.com/channel/UCfD4r29eHpn_XTtqrKh3Xig" target="_blank">Channel</a></li>
                            </ul>
                        </div>
                        @if ($user->youtube()->exists())
                        <div class="col-12 text-center my-4">
                            <button type="button" class="btn btn-success" onclick="youtubeVerify()">Verify Completion</button>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div id="reddit-modal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal" role="document">
                <div class="modal-content">
                    <div class="modal-body row">
                        <div class="col-12 text-right">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="col-12 text-center">
                            <img src="{{ asset('images/reddit.png') }}" class="img-fluid">
                            <h3 class="text-center mt-4">Reddit Reward Structure</h3>
                            @if (!$user->reddit()->exists())
                            <a href="{{ $authURL['reddit'] }}" class="btn btn-reddit"><i class="fab fa-reddit-alien mr-2"></i>Sign In with Reddit</a>
                            @endif
                        </div>
                        <div class="col-12 mt-4">
                            <ul>
                                <li>Subscribe to our <a href="https://www.reddit.com/user/bcoinsg" target="_blank">Subrredit</a></li>
                                <li>Upvote our <a href="#" target="_blank">Pinned Post</a></li>
                            </ul>
                        </div>
                        @if ($user->reddit()->exists())
                        <div class="col-12 text-center my-4">
                            <button type="button" class="btn btn-success" onclick="redditVerify()">Verify Completion</button>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div id="medium-modal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal" role="document">
                <div class="modal-content">
                    <div class="modal-body row">
                        <div class="col-12 text-right">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="col-12 text-center">
                            <img src="{{ asset('images/medium.png') }}" class="img-fluid">
                            <h3 class="text-center mt-4">Medium Reward Structure</h3>
                            @if (!$user->medium()->exists())
                            <a href="{{ $authURL['medium'] }}" class="btn btn-medium"><i class="fab fa-medium-m mr-2"></i>Sign In with Medium</a>
                            @endif
                        </div>
                        <div class="col-12 mt-4">
                            <ul>
                                <li>Follow our <a href="https://medium.com/@bcoinsg" target="_blank">Medium</a></li>
                                <li>Clap once for our <a href="#" target="_blank">article</a></li>
                            </ul>
                        </div>
                        @if ($user->medium()->exists())
                        <div class="col-12 text-center my-4">
                            <button type="button" class="btn btn-success" onclick="mediumVerify()">Verify Completion</button>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <script type="text/javascript" src="{{ asset('js/jquery.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/jquery.oauthpopup.js') }}"></script>

        <script>

            function telegramVerify() {
                $("#telegram-error").show();
                $("#telegram-verify-btn").prop('disabled', true);
                $.ajax({
                    url: "{{ route('bounty-telegram-verify') }}",
                    type: 'GET',
                    success: function(result) {
                        if (result == "true") {
                            location.reload();
                        } else {
                            $("#telegram-verify-btn").prop('disabled', false);
                            $("#telegram-error").html(result);
                        }
                    }
                });
            }
        </script>
    </body>
</html>