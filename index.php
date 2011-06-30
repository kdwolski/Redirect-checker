<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Strict//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>HTTP Redirect Tool | Follow HTTP redirects and their response codes</title>
        <link rel="stylesheet" type="text/css" href="style.css">
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function(){
                loadingimage     = new Image(220,19);
                loadingimage.src = "loading.gif";
                $('#urlform').submit(function(event){
                    event.preventDefault();
                    getredirects();
                });
            });
            function getredirects(){
                $("#results").empty().append("<img class='loading' src='loading.gif'/>");
                $.post('follow.php',$("#urlform").serialize(), updatediv);
            }
            function updatediv(content){
                $("#results").empty().hide().append(content).fadeIn(500);
            }
        </script>
    </head>
    <body>
        <div id="wrapper">
            <img src="header.png" alt="The page redirect checker"/>
            <form id="urlform" method="POST" action="">
                <input type="text" name="url"/>
            </form>
            <div id="results">
                <h2>What this tool does</h2>
                <p>This tool will help you see what redirect leads
                to the final page. Enter your URL in the page above and you will
                see how it gets redirected to the final destination.The response codes will help you make sure that your website
                is using SEO-friendly 301 redirects.</p>
                <h2>SEO-friendly redirects</h2>
                <p>If you want to move content around, use 301 redirects to tell the search engines where it was moved. The 301
                Moved Permanently response code tells the search engine crawlers that the content was moved somewhere for good. This
                means they should update their listings to match the new address.</p>
                <h2>Canonical URLs</h2>
                <p>It is important to have all variations of a site's URL point to the same address. Google might mistakenly see two
                URLs displaying the same page as duplicate content and penalise you for it. Although search engines are getting better
                at telling duplicate content from genuine content, you should make sure to stick to a single URL for each page. A user
                typing <i>www.cats.com</i>, <i>cats.com</i> or <i>cats.com/index.php</i> should always be redirected to a single URL, <i>cats.com</i> for example.</p>
            </div>
            <div class="floatfix"></div>
        </div>
        <div class="footer">Made by <a href="http://nicolasbouliane.com/" title="Nicolas Bouliane">Nicolas Bouliane</a></div>
    </body>
</html>
