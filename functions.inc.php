<?php

function generateLink($url, $label, $class) {
   $link = '<a href="' . $url . '" class="' . $class . '">';
   $link .= $label;
   $link .= '</a>';
   return $link;
}


function outputPostRow($number)  {
    include("travel-data.inc.php");
	$content = "";
	$content .= '<div class="row"><div class="col-md-4">';
	$img = '<img src="images/'.${'thumb'.$number}.'" alt="'.${'title'.$number}.'" class="img-responsive"/>';
	$content .=generateLink('post.php?id=1',$img,'');
	$content .='</div>';
	$content .='<div class="col-md-8"><h2>'.${'title'.$number}.'</h2>';
	$content .='<div class="details">Posted by ';
	$content .=generateLink('user.php?id=2',${'userName'.$number},'');
	$content .='<span class="pull-right">'.${'date'.$number}.'</span>';
	$content .='<p class="ratings">';
	$content .=constructRating(${'reviewsRating'.$number});
	$content .=' '.${'reviewsNum'.$number}.' Reviews</p></div>';
	$content .='<p class="excerpt">'.${'excerpt'.$number}.'</p>';
	$content .='<p>'.generateLink('post.php?id=1','Read more','btn btn-primary btn-sm').'</p>';
	$content .='</div></div><hr/>';
	echo $content;
}

/*
  Function constructs a string containing the <img> tags necessary to display
  star images that reflect a rating out of 5
*/
function constructRating($rating) {
    $imgTags = "";
    
    // first output the gold stars
    for ($i=0; $i < $rating; $i++) {
        $imgTags .= '<img src="images/star-gold.svg" width="16" />';
    }
    
    // then fill remainder with white stars
    for ($i=$rating; $i < 5; $i++) {
        $imgTags .= '<img src="images/star-white.svg" width="16" />';
    }    
    
    return $imgTags;    
}

?>