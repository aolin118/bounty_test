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
                        <a href="https://www.bcoinsg.io/" target="_blank"><img src="{{ asset('images/logo.png') }}" class="img-fluid airdrop-logo mb-4"></a>
                        <h1 class="text-accent">BCoin Bounty Program</h1>
                    </div>
                </div>
                <div class="row mb-0 mt-4">
                    <div class="col-12 col-lg-9 mx-auto step-container">
                        <div class="card border-0 mb-3 h-100">
                            <div class="card-header bg-secondary text-white">User Information<a href="{{ route('bounty-logout') }}" class="float-right logout">Log Out</a></div>
                            <div class="card-body text-center">
                                <p><b>Email</b><br/>{{ $user->email }}</p>
                                <p class="mb-0"><b>Tokens Earned</b><br/></p>
                                <h4 class="text-success mb-0">{{ $awarded }} BCT</h4>
                                <p><small>Tokens will be distributed directly to your exchange wallet of the same registered email address.<br/><a href="https://www.bcoin.sg" target="_blank">Sign up for your BCoin account here!</a></small></p>
                                <p><b>Your Referral Link</b><br/><a href="{{ route('bounty-referral', $user->unique_link) }}" target="_blank">{{ route('bounty-referral', $user->unique_link) }}</a><br/><button type="button" class="btn btn-primary mt-2" id="copy-btn" data-clipboard-text="{{ route('bounty-referral', $user->unique_link) }}">Copy</button></p>
                                <small class="text-danger">You will get 2 BCT for every referral that completes at least 1 task.</small>

                                <p class="mt-4 mb-1 newsletter-container">
                                    <input type="checkbox" id="newsletterCheckBox" name="newsletterCheckBox" onclick="registerNewsletter();"<?php  if ($user->receive_newsletter == 1) echo "checked"; ?>>
                                    <small class="ml-2">I agree to receive promotional newsletters and materials from BCoin.sg.</small>
                                </p>

                                <p class="interest-container">
                                    <input type="checkbox" id="interestCheckBox" name="interestCheckBox" onclick="registerInterest();"<?php  if ($user->card_interest == 1) echo "checked"; ?>>
                                    <small class="ml-2">Yes, I am interested in getting early access to BCoin’s Prepaid Debit Card.</small>
                                </p>
                                <img src="{{ asset('images/diamond-card.png') }}" class="img-fluid diamond-card">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-0 mt-4">
                    <div class="col-12 col-lg-3 ml-auto step-container">
                        <div class="card border-0 mb-3 h-100">
                            <div class="card-header bg-secondary text-white">Telegram - 4 BCT</div>
                            <div class="card-body text-center">
                                <a href="http://t.me/BCoin_Bounty_Bot?start={{ $user->unique_link }}" target="_blank"><img src="{{ asset('images/telegram.png') }}" class="img-fluid mb-3"></a>
                                <div>Join our Telegram Community</div>
                                @if ($user->telegram_completed == 0)
                                <a href="#" class="btn btn-outline-primary mt-4" data-toggle="modal" data-target="#telegram-modal">More Info</a>
                                @else
                                <p class="text-success mt-4">BCT Awarded</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-3 step-container">
                        <div class="card border-0 mb-3 h-100">
                            <div class="card-header bg-secondary text-white">Twitter - 2 BCT</div>
                            <div class="card-body text-center">
                                <a href="https://twitter.com/BCoinsg" target="_blank"><img src="{{ asset('images/twitter.png') }}" class="img-fluid mb-3"></a>
                                <div>Follow us and retweet our Tweet</div>
                                @if ($user->twitter_completed == 0)
                                <a href="#" class="btn btn-outline-primary mt-4" data-toggle="modal" data-target="#twitter-modal">More Info</a>
                                @else
                                <p class="text-success mt-4">BCT Awarded</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-3 step-container">
                        <div class="card border-0 mb-3 h-100">
                            <div class="card-header bg-secondary text-white">YouTube - 2 BCT</div>
                            <div class="card-body text-center">
                                <a href="https://www.youtube.com/channel/UCfD4r29eHpn_XTtqrKh3Xig" target="_blank"><img src="{{ asset('images/youtube.png') }}" class="img-fluid mb-3"></a>
                                <div>Like our video and subscribe to our channel</div>
                                @if ($user->youtube_completed == 0)
                                <a href="#" class="btn btn-outline-primary mt-4" data-toggle="modal" data-target="#youtube-modal">More Info</a>
                                @else
                                <p class="text-success mt-4">BCT Awarded</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-3 mr-auto step-container">
                        <div class="card border-0 mb-3 h-100">
                            <div class="card-header bg-secondary text-white">Reddit - 2 BCT</div>
                            <div class="card-body text-center">
                                <a href="https://www.reddit.com/r/BCoinsg/" target="_blank"><img src="{{ asset('images/reddit.png') }}" class="img-fluid mb-3"></a>
                                <div>Be a part of our Reddit Community</div>
                                @if ($user->reddit_completed == 0)
                                <a href="#" class="btn btn-outline-primary mt-4" data-toggle="modal" data-target="#reddit-modal">More Info</a>
                                @else
                                <p class="text-success mt-4">BCT Awarded</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-1 mt-0">
                    <div class="col-12 col-lg-3 ml-auto step-container">
                        <div class="card border-0 mb-3 h-100">
                            <div class="card-header bg-secondary text-white">Medium - 2 BCT</div>
                            <div class="card-body text-center">
                                <a href="https://medium.com/bcoinsg" target="_blank"><img src="{{ asset('images/medium.png') }}" class="img-fluid mb-3"></a>
                                <div>Follow our Medium and clap for our article</div>
                                @if ($user->medium_completed == 0)
                                <a href="#" class="btn btn-outline-primary mt-4" data-toggle="modal" data-target="#medium-modal">More Info</a>
                                @else
                                <p class="text-success mt-4">BCT Awarded</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-3 step-container">
                        <div class="card border-0 mb-3 h-100">
                            <div class="card-header bg-secondary text-white">Facebook - 2 BCT</div>
                            <div class="card-body text-center">
                                <a href="https://www.facebook.com/BCoinsg/" target="_blank"><img src="{{ asset('images/facebook.png') }}" class="img-fluid mb-3"></a>
                                <div>Follow us on Facebook and share our post</div>
                                @if ($user->facebook_completed == 0)
                                <a href="#" class="btn btn-outline-primary mt-4" data-toggle="modal" data-target="#facebook-modal">More Info</a>
                                @else
                                <p class="text-success mt-4">BCT Awarded</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-3 step-container">
                        <div class="card border-0 mb-3 h-100">
                            <div class="card-header bg-secondary text-white">Instagram - 2 BCT</div>
                            <div class="card-body text-center">
                                <a href="https://www.instagram.com/bcoinsg" target="_blank"><img src="{{ asset('images/instagram.png') }}" class="img-fluid mb-3"></a>
                                <div>Follow us on Instagram<br/>&nbsp;</div>
                                @if ($user->instagram_completed == 0)
                                <a href="#" class="btn btn-outline-primary mt-4" data-toggle="modal" data-target="#instagram-modal">More Info</a>
                                @else
                                <p class="text-success mt-4">BCT Awarded</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-3 mr-auto step-container">
                        <div class="card border-0 mb-3 h-100">
                            <div class="card-header bg-secondary text-white">LinkedIn - 2 BCT</div>
                            <div class="card-body text-center">
                                <a href="https://www.linkedin.com/company/bcoinsg/" target="_blank"><img src="{{ asset('images/linkedin.png') }}" class="img-fluid mb-3"></a>
                                <div>Follow our LinkedIn Page<br/>&nbsp;</div>
                                @if ($user->linkedin_completed == 0)
                                <a href="#" class="btn btn-outline-primary mt-4" data-toggle="modal" data-target="#linkedin-modal">More Info</a>
                                @else
                                <p class="text-success mt-4">BCT Awarded</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-1 mt-0">
                    <div class="col-12 col-lg-12 step-container">
                        <div class="card border-0 mb-3 h-100">
                            <div class="card-header bg-secondary text-white">BCoin Creative Campaign</div>
                            <div class="card-body">
                                <img src="{{ asset('images/creative1.jpg') }}" class="img-fluid mb-3">
                                <h1 class="text-center text-blue-accent my-4"><strong>Current Theme: BCoin Token Features</strong></h1>
                                <img src="{{ asset('images/creative2.jpg') }}" class="img-fluid mt-4 mb-3">
                                <div class="text-center my-4">
                                    Program Start Date: 01 October 2018<br/>
                                    Submission Week: 7 to 13 October 2018<br/>
                                    Competition and Voting Week: 14 to 20 October 2018<br/>
                                </div>
                                <div>
                                    <br/>
                                    <h3 class="text-blue-accent"><strong>Campaign objective : To showcase features of the BCoin Token found below:</strong></h3>
                                    <ul>
                                        <li><span class="text-blue-accent"><u>Discounted Trading Fees</u></span><br/>A proven utility model, exchange-issued-tokens have proven their worth by reducing trade friction. Users can use BCT tokens in place of fees and receive up to 30% discount on all trading fees.</li>
                                        <li><span class="text-blue-accent"><u>Membership Benefits</u></span><br/>Owners of BCT tokens will receive additional  benefits – up to +30% off trading fees, as well as qualification of our Diamond/ Ruby/ Sapphire memberships (tiered according to the user’s personal usage and loyalty to the platform).</li>
                                        <li><span class="text-blue-accent"><u>Community Activation</u></span><br/>BCT tokens grants users exclusive access to BCoin.sg polling events and participation in the BCoin.sg Private Listings Clubs. Additionally, BCT is also used as a medium of payment for listing services.</li>
                                        <li><span class="text-blue-accent"><u>Trade Mining</u></span><br/>BCT tokens can be mined through trading fees at a rate of 120%, as a secondary distribution initiative and capped at 5% allocation of the total supply.</li>
                                    </ul>
                                </div>
                                <div class="text-center text-blue-accent mb-4">
                                    <strong>
                                        Top 3 winners for each activity earns additional BCT on top of their bounty payment:<br/>
                                        1st Prize Bonus: 500BCT (USD250)<br/>
                                        2nd Prize Bonus: 300BCT (USD150)<br/>
                                        3rd Prize Bonus: 200BCT (USD100)<br/>
                                    </strong>
                                </div>
                                <div>
                                    To qualify for the competition, your content must be based around the features of the BCoin token! So get your creative juices flowing and start creating content on any of the platforms featured below<br/>
                                    <br/>

                                    <br/>
                                    <h3 class="text-blue-accent"><strong>Activity #1: Infographics and Graphic Collaterals</strong></h3>
                                    <u>This campaign includes:</u><br/>
                                    Infographics, any illustrations, or designed content for use on Telegram (e.g. sticker packs), and other social media platforms.<br/>
                                    <br/>
                                    <u>Rewards:</u><br/>
                                    Excellent and detailed quality of work: 500 BCT (USD250)<br/>
                                    Good quality of work: 200 BCT (USD100)<br/>
                                    Normal quality of work: 100 BCT (USD50)<br/>
                                    Low quality of work: 20 BCT (USD10)<br/>
                                    <br/>
                                    <u>To Participate:</u><br/>
                                    <ol>
                                        <li>Materials created must be of a professional standard.</li>
                                        <li>Participants must submit the editable files along with the final copy.</li>
                                        <li>Participants must be the original creators of their pieces, plagiarising another person’s work is strictly prohibited.</li>
                                        <li>The participants would be credited with the work that they submitted.</li>
                                        <li>The rights to the use the submitted materials as promotional materials belong to BCoin.</li>
                                        <li>Once completed with the program, you’ll need to submit the completion form. The approval for the completion of the program would be made by the bounty manager only after the submission of all required deliverables.</li>
                                    </ol>

                                    <br/>
                                    <h3 class="text-blue-accent"><strong>Activity #2: Reddit</strong></h3>
                                    <u>Rewards:</u> 6 BCT/post (USD3)<br/>
                                    <br/>
                                    <u>To Participate:</u>
                                    <ol>
                                        <li>You must have a minimum of 50 Karma to participate</li>
                                        <li>Follow our <a href="https://www.reddit.com/r/bcoinsg" target="_blank">Subreddit</a></li>
                                        <li>Upvote all our existing posts and thread.</li>
                                        <li>Comment on our existing thread with at least one of his or her own comment. The comment must be creative, informative, relevant and constructive with the narrative. Basic and careless comments like “good project” would not be accepted.</li>
                                        <li>Do not start your own thread within our subreddit.</li>
                                        <li>Post one creative, informative, and relevant post or review about BCoin.sg on a relevant subreddit twice a week, e.g. /r/crypto, /r/cryptocurrency, /r/cryptomarkets  and the include a link to our subreddit.</li>
                                    </ol>

                                    <br/>
                                    <h3 class="text-blue-accent"><strong>Activity #3: Medium</strong></h3>
                                    <u>Rewards:</u><br/>
                                    100-200 Claps: 10 BCT (USD5)<br/>
                                    201-500 Claps: 20 BCT (USD10)<br/>
                                    501-1000 Claps: 100 BCT (USD50)<br/>
                                    1001-5000 Claps: 200 BCT (USD100)<br/>
                                    >5001 Claps: 500 BCT (USD250)<br/>
                                    <br/>
                                    <u>Rules for Participation:</u><br/>
                                    <ol>
                                        <li>You must have a minimum of 50 followers to participate.</li>
                                        <li>Follow our <a href="https://medium.com/bcoinsg" target="_blank">Medium page</a></li>
                                        <li>Written articles would need to be least 400 words long, containing useful, relevant and edifying information.</li>
                                        <li>The Medium account must be your own original account. Fake, dead, inactive, bot, suspended accounts or suspended articles will be disqualified.</li>
                                        <li>The reward would be based on the total number of claps achieved by the end of the bounty program.</li>
                                    </ol>
                                    
                                    <br/>
                                    <h3 class="text-blue-accent"><strong>Activity #4: YouTube</strong></h3>
                                    <u>This campaign includes:</u><br/>
                                    YouTube Reviews and Video Blogs about BCoin<br/>
                                    <br/>
                                    <u>Rewards:</u><br/>
                                    Low quality with minimal reach (400 views) to audience: 40 BCT (USD20)<br/>
                                    Normal quality with substantial reach (1500 views) to audience: 150 BCT (USD75)<br/>
                                    Good quality with large reach to audience: 300 BCT (USD150)<br/>
                                    Excellent quality with extensive reach to audience: 600 BCT (USD300)<br/>
                                    <br/>
                                    <u>To Participate:</u><br/>
                                    <ol>
                                        <li>Channels should be Blockchain and Cryptocurrency thematic and have an audience with the same interests.</li>
                                        <li>You’ll require a minimum of 300 subscribers on your channel.</li>
                                        <li>Videos would need to be least 2 minutes long, containing useful, relevant, and edifying information.</li>
                                        <li>Proof of ownership of your account is required and must be submitted to the bounty manager as and when requested. To show proof, your Bitcointalk profile must be added in the video description.</li>
                                    </ol>

                                    <br/>
                                    <h3 class="text-blue-accent"><strong>Activity #5: Blog/Forum</strong></h3>
                                    <u>This campaign includes:</u><br/>
                                    Blog articles, news reports, forum posts, platform/product/company/token/technology reviews<br/>
                                    <br/>
                                    <u>Rewards:</u><br/>
                                    Excellent quality with extensive reach to audience: 500 BCT (USD250)<br/>
                                    Good quality with large reach to audience: 200 BCT (USD100)<br/>
                                    Normal quality with substantial reach to audience: 100 BCT (USD50)<br/>
                                    Low quality with minimal reach to audience: 20 BCT (USD10)<br/>
                                    <br/>
                                    <u>To Participate:</u><br/>
                                    <ol>
                                        <li>Blogs, channels, forums and websites should be Blockchain and Cryptocurrency thematic and have an audience with the same interests.</li>
                                        <li>For your Personal blogs you are required to prove that you have a of minimum of 200 subscribers.</li>
                                        <li>Written articles would need to be least 400 words long, containing useful, relevant and edifying information.</li>
                                    </ol>
                                    <br/>
                                    To take part in the competition and have the chance to earn bonus tokens, users must submit their content via the form from the Bounty Website.<br/>
                                    <br/>
                                    After the submission week has ended, the best content submitted to the program would be shared on all of BCoin’s social media channels, and the community would be tasked to vote for the content that they think is best.<br/>
                                    <br/>
                                    During the competition week, the submissions would be collated into a poll that would be conducted across all social media platforms and on Telegram. <span class="text-blue-accent"><strong>Each vote, like, share and comment will count towards the scoring of the top submissions. Winners would be notified and announced on all BCoin channels.</strong></span><br/>
                                    <br/>
                                    <strong>General Conditions for All Campaigns</strong>
                                    <ul>
                                        <li>You must join and be a member of the <a href="https://t.me/bcoinsg_EN/" target="_blank">BCoin Telegram Group</a></li>
                                        <li>We would be pushing updates about our platform on these channels and users must be updated on our project, to potentially include our updates and developments in their respective bounty programs.</li>
                                        <li>Mentioning bounty or any part of this campaign is not allowed on any channel.</li>
                                        <li>Using multiple accounts, spam bots, personal spamming are strictly prohibited, and users attempting to do so will be banned.</li>
                                        <li>The management team reserve the rights to adjust the terms and conditions of the bounty campaign during the period of its holding. For all updates, keep an eye on this topic and the bounty telegram group.</li>
                                        <li>Once the program has reached the required number of participants, we will stop registration for some campaigns.</li>
                                        <li>The participant must write a creative, informative, and relevant post or review about our <a href="https://www.bcoin.sg/" target="_blank">BCoin Exchange</a> or about our <a href="https://www.bcoinsg.io/" target="_blank">Token Economics/Features</a> using the compulsory tag: BCoin, BCoin.sg</li>
                                        <li>
                                            Articles must be genuine, relevant, informative and creative. You can use official images, logos, graphics posted on the following:
                                            <ul>
                                                <li><a href="https://www.bcoinsg.io/" target="_blank">Website</a></li>
                                                <li><a href="https://www.reddit.com/r/BCoinsg/" target="_blank">Reddit</a></li>
                                                <li><a href="https://medium.com/bcoinsg" target="_blank">Medium</a></li>
                                                <li><a href="https://twitter.com/BCoinsg" target="_blank">Twitter</a></li>
                                                <li><a href="https://www.youtube.com/c/BCoinsg" target="_blank">YouTube</a></li>
                                            </ul>
                                        </li>
                                        <li>Content should provide links to the official website: <a href="https://www.bcoin.sg/" target="_blank">https://www.bcoin.sg/</a> or <a href="https://www.bcoinsg.io/" target="_blank">https://www.bcoinsg.io/</a> or to the <a href="https://www.scribd.com/document/387793884/BCoinWP-1-1-5" target="_blank">whitepaper</a></li>
                                        <li>Basic, careless, or plagiarised posts would not be accepted.</li>
                                        <li>All materials must be of a professional standard, low-quality articles will not be accepted.</li>
                                        <li>Once completed with the program, you’ll need to submit the completion form and inform the bounty manager of your participation. The approval for the completion of the bounty program would be made by the bounty manager only after submission of all the required deliverables.</li>
                                        <li>Proof of ownership of your account is required and must be submitted to the bounty manager as and when requested.</li>
                                        <li>Upon approval of the submissions, tokens would be transferred to the exchange wallet of the same registered email in phases after the end of the token sale.</li>
                                        <li>Once done with your content, please fill up all necessary details in the <a href="https://goo.gl/forms/1P49iDWDvgfl6AeZ2" target="_blank">submission form</a>.</li>
                                    </ul>
                                    <strong>Clause on the Maximum Payout Limit</strong><br/>
                                    Influencers with a substantial amount of followers and reach may request written contracts for an agreed sum from our bounty manager.<br/.>
                                    The decision on the accrual of BCT that is above the specified tiers will be considered individually, based on the number of views, level of engagement with the community, the quality of the content created, and other substantiating factors.<br/>
                                    <br/>
                                    Once done with your content, please fill up all necessary details in the submission form<br/>
                                    <a href="https://goo.gl/forms/1P49iDWDvgfl6AeZ2" class="btn btn-outline-primary mt-4" target="_blank">Submission Form</a>
                                    <a href="https://docs.google.com/spreadsheets/d/1b_kVTnWVt26ODdL19YsjDpMvfE6HwjDzCS61KXvZUCQ/edit?usp=sharing" class="btn btn-outline-primary mt-4" target="_blank">Submission Status</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if ($user->telegram_completed == 0)
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
                            <p id="telegram-error" class="text-danger my-2"></p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if ($user->twitter_completed == 0)
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
                            <a href="{{ $authURL['twitter'] }}" class="btn btn-primary" target="_blank"><i class="fab fa-twitter mr-2"></i>Sign In with Twitter</a>
                            @endif
                        </div>
                        <div class="col-12 mt-4">
                            <ul>
                                <li>Follow our <a href="https://twitter.com/BCoinsg" target="_blank">Twitter</a></li>
                                <li>Like and retweet our <a href="https://twitter.com/BCoinsg/status/1046700661901950976" target="_blank">Tweet</a></li>
                            </ul>
                        </div>
                        @if ($user->twitter()->exists())
                        <div class="col-12 text-center mt-2 mb-2">
                            <button type="button" class="btn btn-success" id="twitter-verify-btn" onclick="twitterVerify()">Verify Completion</button>
                            <p id="twitter-error" class="text-danger my-2"></p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if ($user->youtube_completed == 0)
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
                            <a href="{{ $authURL['youtube'] }}" class="btn btn-danger" target="_blank"><i class="fab fa-youtube mr-2"></i>Sign In with YouTube</a>
                            @endif
                        </div>
                        <div class="col-12 mt-4">
                            <ul>
                                <li>Like our <a href="https://www.youtube.com/watch?v=TtAUV7MUW5k" target="_blank">Video</a></li>
                                <li>Subscribe to our <a href="https://www.youtube.com/channel/UCfD4r29eHpn_XTtqrKh3Xig" target="_blank">Channel</a></li>
                            </ul>
                        </div>
                        @if ($user->youtube()->exists())
                        <div class="col-12 text-center mt-2 mb-2">
                            <button type="button" class="btn btn-success" id="youtube-verify-btn" onclick="youtubeVerify()">Verify Completion</button>
                            <p id="youtube-error" class="text-danger my-2"></p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if ($user->reddit_completed == 0)
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
                            <a href="{{ $authURL['reddit'] }}" class="btn btn-reddit" target="_blank"><i class="fab fa-reddit-alien mr-2"></i>Sign In with Reddit</a>
                            @endif
                        </div>
                        <div class="col-12 mt-4">
                            <ul>
                                <li>Subscribe to our <a href="https://www.reddit.com/r/BCoinsg/" target="_blank">Subreddit</a></li>
                                <li>Upvote our <a href="https://www.reddit.com/r/BCoinsg/comments/9cji2v/join_bcoins_bounty_program_trading_challenge/" target="_blank">Pinned Post</a></li>
                            </ul>
                        </div>
                        @if ($user->reddit()->exists())
                        <div class="col-12 text-center mt-2 mb-2">
                            <button type="button" class="btn btn-success" id="reddit-verify-btn" onclick="redditVerify()">Verify Completion</button>
                            <p id="reddit-error" class="text-danger my-2"></p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if ($user->medium_completed == 0)
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
                            <a href="{{ $authURL['medium'] }}" class="btn btn-medium" target="_blank"><i class="fab fa-medium-m mr-2"></i>Sign In with Medium</a>
                            @endif
                        </div>
                        <div class="col-12 mt-4">
                            <ul>
                                <li>Follow our <a href="https://medium.com/bcoinsg" target="_blank">Medium</a></li>
                            </ul>
                        </div>
                        @if ($user->medium()->exists())
                        <div class="col-12 text-center mt-2 mb-2">
                            <button type="button" class="btn btn-success" id="medium-verify-btn" onclick="mediumVerify()">Verify Completion</button>
                            <p id="medium-error" class="text-danger my-2"></p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if ($user->facebook_completed == 0)
        <div id="facebook-modal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal" role="document">
                <div class="modal-content">
                    <div class="modal-body row">
                        <div class="col-12 text-right">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="col-12 text-center">
                            <img src="{{ asset('images/facebook.png') }}" class="img-fluid">
                            <h3 class="text-center mt-4">Facebook Reward Structure</h3>
                        </div>
                        <div class="col-12 mt-4">
                            <ul>
                                <li>Follow our <a href="https://www.facebook.com/BCoinsg/" target="_blank">Facebook Page</a></li>
                                <li>Like and share our <a href="https://www.facebook.com/BCoinsg/photos/a.166929147349091/251717228870282/?type=3&theater" target="_blank">post</a></li>
                            </ul>
                        </div>
                        <div class="col-10 text-center mt-2 mb-2 mx-auto">
                            <form method="POST" action="{{ route('bounty-facebook-verify') }}">
                                @csrf
                                <input type="email" class="form-control text-center mb-2" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" title="Invalid Email" id="facebook-email" name="facebook-email" placeholder="Facebook Email Address" required>
                                <button type="submit" class="btn btn-success">Verify Completion</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if ($user->instagram_completed == 0)
        <div id="instagram-modal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal" role="document">
                <div class="modal-content">
                    <div class="modal-body row">
                        <div class="col-12 text-right">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="col-12 text-center">
                            <img src="{{ asset('images/instagram.png') }}" class="img-fluid">
                            <h3 class="text-center mt-4">Instagram Reward Structure</h3>
                        </div>
                        <div class="col-12 mt-4">
                            <ul>
                                <li>Follow us on <a href="https://www.instagram.com/bcoinsg" target="_blank">Instagram</a></li>
                                <li>Like our <a href="https://www.instagram.com/p/BoYv7DVAGUe" target="_blank">post</a></li>
                            </ul>
                        </div>
                        <div class="col-10 text-center mt-2 mb-2 mx-auto">
                            <form method="POST" action="{{ route('bounty-instagram-verify') }}">
                                @csrf
                                <input type="text" class="form-control text-center mb-2" id="instagram-id" name="instagram-id" placeholder="Instagram ID" required>
                                <button type="submit" class="btn btn-success">Verify Completion</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if ($user->linkedin_completed == 0)
        <div id="linkedin-modal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal" role="document">
                <div class="modal-content">
                    <div class="modal-body row">
                        <div class="col-12 text-right">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="col-12 text-center">
                            <img src="{{ asset('images/linkedin.png') }}" class="img-fluid">
                            <h3 class="text-center mt-4">LinkedIn Reward Structure</h3>
                        </div>
                        <div class="col-12 mt-4">
                            <ul>
                                <li>Follow our <a href="https://www.linkedin.com/company/bcoinsg/" target="_blank">LinkedIn Page</a></li>
                                <li>Like and share our <a href="https://www.linkedin.com/feed/update/urn:li:activity:6452467269405634560" target="_blank">post</a></li>
                            </ul>
                        </div>
                        <div class="col-10 text-center mt-2 mb-2 mx-auto">
                            <form method="POST" action="{{ route('bounty-linkedin-verify') }}">
                                @csrf
                                <input type="email" class="form-control text-center mb-2" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" title="Invalid Email" id="linkedin-email" name="linkedin-email" placeholder="LinkedIn Email Address" required>
                                <button type="submit" class="btn btn-success">Verify Completion</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <script type="text/javascript" src="{{ asset('js/jquery.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/clipboard.min.js') }}"></script>

        <script>
            function telegramVerify() {
                $("#telegram-error").html("<img src='{{ asset('images/loading.gif') }}'' class='img-fluid loading'>");
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

            function twitterVerify() {
                $("#twitter-error").html("<img src='{{ asset('images/loading.gif') }}'' class='img-fluid loading'>");
                $("#twitter-error").show();
                $("#twitter-verify-btn").prop('disabled', true);
                $.ajax({
                    url: "{{ route('bounty-twitter-verify') }}",
                    type: 'GET',
                    success: function(result) {
                        if (result == "true") {
                            location.reload();
                        } else {
                            $("#twitter-verify-btn").prop('disabled', false);
                            $("#twitter-error").html(result);
                        }
                    }
                });
            }

            function youtubeVerify() {
                $("#youtube-error").html("<img src='{{ asset('images/loading.gif') }}'' class='img-fluid loading'>");
                $("#youtube-error").show();
                $("#youtube-verify-btn").prop('disabled', true);
                $.ajax({
                    url: "{{ route('bounty-youtube-verify') }}",
                    type: 'GET',
                    success: function(result) {
                        if (result == "true") {
                            location.reload();
                        } else {
                            $("#youtube-verify-btn").prop('disabled', false);
                            $("#youtube-error").html(result);
                        }
                    }
                });
            }

            function redditVerify() {
                $("#reddit-error").html("<img src='{{ asset('images/loading.gif') }}'' class='img-fluid loading'>");
                $("#reddit-error").show();
                $("#reddit-verify-btn").prop('disabled', true);
                $.ajax({
                    url: "{{ route('bounty-reddit-verify') }}",
                    type: 'GET',
                    success: function(result) {
                        if (result == "true") {
                            location.reload();
                        } else {
                            $("#reddit-verify-btn").prop('disabled', false);
                            $("#reddit-error").html(result);
                        }
                    }
                });
            }

            function mediumVerify() {
                $("#medium-error").html("<img src='{{ asset('images/loading.gif') }}'' class='img-fluid loading'>");
                $("#medium-error").show();
                $("#medium-verify-btn").prop('disabled', true);
                $.ajax({
                    url: "{{ route('bounty-medium-verify') }}",
                    type: 'GET',
                    success: function(result) {
                        if (result == "true") {
                            location.reload();
                        } else {
                            $("#medium-verify-btn").prop('disabled', false);
                            $("#medium-error").html(result);
                        }
                    }
                });
            }

            var btn = document.getElementById('copy-btn');
            var clipboard = new ClipboardJS(btn);

            clipboard.on('success', function(e) {
                console.log(e);
                $("#copy-btn").html("Copied!");
                $("#copy-btn").prop('disabled', true);
            });
            clipboard.on('error', function(e) {
                console.log(e);
            });

            function registerNewsletter() {
                window.location.href = "{{ route('bounty-newsletter-change') }}";
            }

            function registerInterest() {
                window.location.href = "{{ route('bounty-interest-change') }}";
            }
        </script>
    </body>
</html>