<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
    <head>
        <title>Arabic Reputation System based on Twitter</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta name="description" content="Arabic Reputation System based on Twitter" />
        <meta name="keywords" content="Arabic, Reputation, System, Twitter"/>
        <link rel="stylesheet" href="css/style.css" type="text/css" media="screen"/>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
        <script src="jquery-css-transform.js" type="text/javascript"></script>
        <script src="jquery-animate-css-rotate-scale.js" type="text/javascript"></script>
        <style>
            *{
                margin:0;
                padding:0;
                background:#f9f9f9;
            }
            .title{
                width:80%;
                height:144px;
                position:absolute;
                top:0px;
                left:0px;
                background:transparent url(images/title.png) no-repeat top left;
            }
            a.back{
                background:transparent url(back.png) no-repeat top left;
                position:fixed;
                width:150px;
                height:27px;
                outline:none;
                bottom:0px;
                left:0px;
            }
        </style>
    </head>
    <body>
        <div>
           <div class="title"></div>
        </div>
	<form method="post" name="form1" onsubmit="return validate()" action="results.php">
        <div class="menu">
            <div class="item">
                <a class="link icon_find"></a>
                <div class="item_content">
                    <h2>Search</h2>
                    <p>
                        <input type="text" name="txtSearch" class="i" / >
                       <a><input type="submit" value="Go" width="5" height="5" /></a>
                    </p>
                </div>
            </div>
           
        </div>
        </form >
        <script>
            $('.item').hover(
                function(){
                    var $this = $(this);
                    expand($this);
                },
                function(){
                    var $this = $(this);
                    collapse($this);
                }
            );
            function expand($elem){
                var angle = 0;
                var t = setInterval(function () {
                    if(angle == 1440){
                        clearInterval(t);
                        return;
                    }
                    angle += 40;
                    $('.link',$elem).stop().animate({rotate: '+=-40deg'}, 0);
                },10);
                $elem.stop().animate({width:'268px'}, 1000)
                .find('.item_content').fadeIn(400,function(){
                    $(this).find('p').stop(true,true).fadeIn(600);
                });
            }
            function collapse($elem){
                var angle = 1440;
                var t = setInterval(function () {
                    if(angle == 0){
                        clearInterval(t);
                        return;
                    }
                    angle -= 40;
                    $('.link',$elem).stop().animate({rotate: '+=40deg'}, 0);
                },10);
                $elem.stop().animate({width:'52px'}, 1000)
                .find('.item_content').stop(true,true).fadeOut().find('p').stop(true,true).fadeOut();
            }
	function validate() {
		var a = document.forms["form1"]["txtSearch"].value;

		if (a == null || a == "") {
			alert("Please Enter search text");
			return false;
		}
}
        </script>
		<div></div><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
		
			</div>
		</div>
    </body>
</html>
