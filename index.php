<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Commonly</title>
        <meta name="description" content="Commonly, shows you which twitter follows, 2 people have">
        <meta name="author" content="Mangamaui">
        <link href="commonly_style.css" rel="stylesheet"/>
        <link rel="icon" type="image/png" href="images/commonly.png">
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
        <script src="script.js"></script>
        <!--[if lt IE 9]>
        <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
    </head>
    <body>
        <div id="twirl"></div>
        <div id="container">
            <header>
                <h1>Commonly</h1>
                <h2>Ever wondered who your common friends are?</h2>
            </header>

            <div id="content">
                <div id="lookupform">
                    <div class="form">
                        <h3>Let's see which people, person X and person Y both are following on twitter, shall we?</h3>
                        <form>
                            <div class="left">
                                <div id="twitterpic1" class="parentPic"></div>
                                <input type="text" id="twittername1" name="twittername1" placeholder="person x"/>
                            </div>
                            <div class="right">
                                <div id="twitterpic2" class="parentPic"> </div>
                                <input type="text" id="twittername2" name="twittername2" placeholder="person y"/>
                            </div>
                            <input id="button" type="submit" value="Look up"/>
                            <div class="loader"></div>
                        </form>
                    </div>
                </div>
                <div id="results">
                </div>
            </div>
        </div>
        <div id="footer">
            Project by <a href="http://twitter.com/Mangamaui">Mangamaui</a>
        </div>
        <script type="text/javascript">

            var _gaq = _gaq || [];
            _gaq.push(['_setAccount', 'UA-39177882-1']);
            _gaq.push(['_trackPageview']);

            (function() {
                var ga = document.createElement('script');
                ga.type = 'text/javascript';
                ga.async = true;
                ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                var s = document.getElementsByTagName('script')[0];
                s.parentNode.insertBefore(ga, s);
            })();

        </script>
    </body>
</html>
