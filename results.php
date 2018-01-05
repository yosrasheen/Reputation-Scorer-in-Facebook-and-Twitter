<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html dir="rtl">
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
<?php
include "pecl.php";
error_reporting(E_ALL);
error_reporting(0);
ini_set('display_errors', 'On');

	
class Reputation 
{
	/* arrays to hold the data from solr */
 /*================================================================================*/
	public $tweets=array();
	public $scorep=0;
	public $scoren=0;
	public $repScore=0;
	public $counter=0;
 /*================================================================================*/

	
   	/* first function to work constructor */ 
 /*================================================================================*/  	
	function Reputation() 	{
		
		/* check if text box has data */
		if (isset($_POST['txtSearch'])){
			/* get data from text box */
			$query = $_POST['txtSearch'];
			try{
				/* call search function from pecl.php */ 
				$results = search($query);
				
			}
			catch(Exception $e) {
				echo $e -> getMessage();
			}
		}
		/* call preprocess function to remove unwanted data  */
		

		$this->preprocess($results->response->docs);
	}
 /*================================================================================*/

	

   	/* A function to remove the unwanted content and get the exact tweet */ 
			/* get exact date from anchors */ 
			/* get writers from title */ 

/*================================================================================*/ 	
	function preprocess($all)	{
		/* check every tweet information from solr */
		foreach ($all as $one)
		{
			/* check if the extartced content has tweets */	
			//if ($this->extractTweet($one->content[0]) != null)
			if ($one->content[0] != null)
			{
				/* check if this tweet has a avalid date  */
				//if ($this->hasDate($this->prepareAnchors($one->anchor)) != null)
				//{	/* extract the needed data and put them in the arrays of the class */			
					//$this->tweets[]= $this->extractTweet($one->content[0]);
					$this->tweets[]= $one->content[0];
				//}
			}
		}
	}

 /*================================================================================*/
/* extract the exact tweet */
 /*================================================================================*/
	function extractTweet($tweet)	{
		/* the tweet is in between two quotes */
		/* remove the words before the first quotes and get the letter after it to be the first letter */
		$tweet =  substr(strstr($tweet,'"'), 1);
		/* get the place of the second quote */
		$snd=strcspn($tweet,'"');
		/* delete words after it and any extra spaces */
		$tweet = trim(substr($tweet,0,$snd)); // remove the text after the second "
		
		/* remove english */
		$tweet = preg_replace('/[a-zA-Z]/', "", $tweet);
		/* remove hashtag */
		$tweet = preg_replace('/#/', "", $tweet);
		$tweet = preg_replace('/[0-9]/', "", $tweet);
		$tweet = preg_replace('/\./', "", $tweet);
		$tweet = preg_replace("/\//", "", $tweet);
		$tweet = preg_replace("/\:/", "", $tweet);
			/* remove this symbol */
		/* if content is رمز there is no real tweet in this content */
		if (trim($tweet)=="الرمز") 
			$value=null;
		/* send the tweet after deleting extra words */
		return ($tweet);
	}
 /*================================================================================*/

/* each tweet has many anchors, all of them are set in only one array , anchors contain the date */
 /*================================================================================*/
	function prepareAnchors($anc)	{
		/* return null if there is no anchors , so no date, so it is a bad content no tweet */
		$str= null;		
		if ($anc == null) return null;
		else		
		{
			foreach ($anc as $a)
				/* make all anchors in one string */	
				$str = $str .(string)$a. " ";
			
			/* anchor has تجاهل has no tweet in it and no date */
			if (strpos($str,'تجاهل') != false)
				$str = null;	
		}
		return  $str;
	}	
 /*================================================================================*/



		/* Check if there is a date in the string */
/* solr gets three different date format , is any is found so there is a date in this tweet */ 
 /*================================================================================*/
	function hasDate($str)	{
		if ($str==null) return false;	
		$pattern1 = '/[٠-٩]* (يناير|فبراير|مارس|أبريل|إبريل|مايو|يونيو|يونيه|يوليو|يوليه|أغسطس|سبتمبر|أكتوبر|نوفمبر|ديسمبر)، [٠-٩]*/';
		$pattern2 = '/[٠-٩]* ';
		$pattern2 .= '(يناير|فبراير|مارس|أبريل|إبريل|مايو|يونيو|يونيه|يوليو|يوليه|أغسطس|سبتمبر|أكتوبر|نوفمبر|ديسمبر)/';
		$pattern3 = '/([0-9])* (Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) ([0-9])*/';
 
		if ($str== null)
			return false;
		else if (preg_match($pattern1, $str, $matches,  PREG_OFFSET_CAPTURE) == true)
			return true;
		else if (preg_match($pattern2, $str, $matches,  PREG_OFFSET_CAPTURE) == true)	
			return true;	
		else if (preg_match($pattern3, $str, $matches,  PREG_OFFSET_CAPTURE) == true)
			return true;
		else
			return false;
	}
 /*================================================================================*/


	/*===============print any array ====================================*/
 /*================================================================================*/
	function printArray($arr){
		echo "<ol>";
		foreach ($arr as $a) 
			echo "<li>".  $a. "</li>";
		echo "</ol>"	;
	}

 
	/*============================ sentiment analysis ============================  */
 /*============================================================================================== */
	function RepScore()
	{
	/* ==================== sentiment analysis based on the two files============== */
			
		$file1 = fopen("positivep.txt", "r");
		$file2 = fopen("positivew.txt", "r");
		$file3 = fopen("negativep.txt", "r");
		$file4 = fopen("negativew.txt", "r");

		$positivesp= array();
		$negativesp= array();
		$positivesw= array();
		$negativesw= array();

		// get it as array of lines
		while (!feof($file1)) {   $positivesp[] = fgets($file1);}
		fclose($file1);
		
		// get it as array of lines
		while (!feof($file2)) {   $positivesw[]= fgets($file2);}
		fclose($file2);
		
		while (!feof($file3)) {   $negativesp[]  = fgets($file3);}
		fclose($file3);
		
		// get it as array of lines
		while (!feof($file4)) {	   $negativesw[] = fgets($file4);}
		fclose($file4);
		
		$scorep=0;
		$scoren=0;
		$counter=count($this->tweets);
		
		echo "<ol>";
		foreach ($this->tweets as $tweet)// get each tweet
		{	
			/* remove hashtag and strange symbol*/
			$tweet = str_replace("🌹", "", $tweet);
			$tweet = str_replace("#", "", $tweet);

			/* show the tweet */			
			echo "<li>" . $tweet . "</li><br>";			
			
			/* remove ال */
			$tweet = str_replace("ال ", "", $tweet);
			
			
			// positive and negatives words to be shown
			$pos="";
			$neg="";

			/* get each word in the tweet */
			$word = explode(" ", $tweet);
			// loop in each word
			foreach ($word as $w)
			{
				/* get each word in the positives words */
				foreach ($positivesw as $p)
				{
					if ( ($p != null) && ( trim($p)==trim($w) )  )
					{	
						$pos = $pos . trim($p) . "     " ; 
						$scorep++;
						//echo "<br> There ".$p . "<br>";
					}
				}
				/* get each word in the negatives words */
				foreach ($negativesw as $n)
				{
					if ( ($n != null ) && (trim($n)==trim($w) )   )
					{	
						$neg = $neg . trim($n) . "     " ; 
						$scoren++;
						//echo "<br> here<br>";
					}
				}
			}


			foreach ($positivesp as $p)
			{	
				/* compare the string from positive phrases and the tweet*/
				if ( ($p != null ) &&(preg_match(' /'.trim($p).' /', ' '.trim($tweet.' ')) ) )
				{	
					$pos = $pos .  trim($p) . "     " ; 
					$scorep++;
					//echo "<br> There phrase	 ".$p . "<br>";
				}
				
			}

			foreach ($negativesp as $p)
			{	
				/* compare the string from positive phrases and the tweet*/
				if ( ($p != null ) && (preg_match("/".trim($p)."/", trim($tweet) ) ))
				{	
					$neg = $neg . trim($p) . "     " ;
					$scoren++;
					//echo "<br> phrase here<br>";
			
				}
			}
			
			echo "Positives: " . $pos; 
			echo "<br>Negatives: " . $neg;
			
		echo " <br><br>";
			
		
		}// end of big for
		echo "</ol>";

	/* =================reputation score calculation====================================*/
				
		$repScore = round(($scorep ) / ($scorep + $scoren),3);
		$this->repScore = $repScore;
		$this->scorep = $scorep;
		$this->scoren = $scoren;
		$this->counter = $counter;
	}

 }
/* ==========================================End of class ===============================================================*/
/*=======================================================================================================================*/
$product=$_POST["txtSearch"];

?>
    <body>
        <div>
           <div class="title"></div>
        </div>
			<div></div><br><br><br><br><br><br><br><br><br><br>
		<?php 
			echo '<div class="results">
			 <h1>النتائج للمنتج :'.$product.'</h1>
<h3>النتائج تتراوح ما بين الصفر والواحد الصحيح كلما اقترب الناتج من الرقم الصحيح دل على حسن سمعة هذا المنتج</h3>
			<div class="res">';
			
			
			/* check if the extartced content has tweets */	
			//if ($this->extractTweet($one->content[0]) != null)
			
				/* check if this tweet has a avalid date  */
				//if ($this->hasDate($this->prepareAnchors($one->anchor)) != null)
				//{	/* extract the needed data and put them in the arrays of the class */			
					//$this->tweets[]= $this->extractTweet($one->content[0]);
					
			
				$rep= new Reputation();
				$rep->RepScore();
				echo '<h2> عددالتغريدات :  ' .  $rep->counter . '</h2>';
				echo '<h2> عدد اﻷراء اﻹيجابية :  ' .  $rep->scorep . '</h2>';
				echo '<h2> عدد اﻷراء السلبية:  ' .  $rep->scoren. '</h2>';
				echo '<h2> تقييم السمعة: ' .  $rep->repScore . '</h2>';
				//echo '<h2> التغريدات المرتبطة بكلمة البحث: </h2><p>';
				//$rep->printArray($rep->tweets);
				echo '</p>';
			?>
			</div>
		</div>
    </body>
</html>
