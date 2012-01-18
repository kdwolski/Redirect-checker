<?php
if (isset($_POST['urls'])){
   
	//grab all the urls and place them into an array
	$text = trim($_POST['urls']);
	$textAr = explode("\n", $text);
	$textAr = array_filter($textAr, 'trim'); // remove any extra \r characters left behind
	
	foreach ($textAr as $line) {
	 $url = urldecode($line);
    //Validate the URL
        if ( validateUrl($url) ){
            //Append http to the url if it's not already there
                if ( substr($url,0,7) != 'http://')
                    $url = 'http://' . $url;
                    
            //Fetch the redirects list
                $redirects = redirects($url);
			
            //Prepare the list of redirects for display
                $output = "<ul class='redirects'>";
				$output .= '<p class="tested-url">Checking: ' . $url . "</p>";
                //Append each redirect to the list
                    $i = 1; //Used to count redirects
                    $icon_class= ""; //make 404s stand out :)
                    foreach ( $redirects as $redirect ){
                    	
						if( strpos($redirect['response'], '404') > 0 ||  strpos($redirect['response'], '500') > 0){
							$row_color="red";
							$icon_class= "skull";
						}else if(strpos($redirect['response'], '301') > 0){
							$row_color="gray";
							
						}else if(strpos($redirect['response'], '200') > 0){
							$row_color="green";
							$icon_class= "finish-line";
						}else{
							$row_color="gray";
						}
						
                        $output .=
                            '<li class="' .$row_color . '">' .
                                '<h2>Step ' . $i . '</h2>' .
                                '<div class="location">' . $redirect['location'] . '<a href="' . $redirect['location'] . '" rel="nofollow" target="_blank" title="Visit this link"><img class="externallink" src="external-link.gif"/></a></div>' .
                                '<div class="response ' . $icon_class . '">' . $redirect['response'] . '</div>' .
                                '<div class="floatfix"></div>' .
                            '</li>';
                        $i++;
                    }

                $output .= '</ul>';
               // $output .= "<p class='subtle'>This is the path web browsers and search engine crawlers follow to get to the page. The response code tells search engines where the content went. Beware of URL's that lead to nowhere and make sure permanently redirected content uses the 301 Moved Permanently response code so that search engines know which URL to index.</p>";
        }
        else{
            $output = '<p class="invalid-url"><span>' . $url . '</span> is not a valid URL!</p>';
        }

        echo $output;
}
}
//==============================================================================
    //Used to validate URLs
    function validateUrl($url){
        $regexUrl = '/^(http\:\/\/)?([a-zA-Z0-9\.\-]+(\:[a-zA-Z0-9\.&amp;%\$\-]+)*@)*((25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9])\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[0-9])|localhost|([a-zA-Z0-9\-]+\.)*[a-zA-Z0-9\-]+\.(com|edu|gov|int|mil|net|org|biz|arpa|info|name|pro|aero|coop|museum|[a-zA-Z]{2}))(\:[0-9]+)*(\/($|[a-zA-Z0-9\.\,\?\'\\\+&amp;%\$#\=~_\-]+))*$/i';
        if( preg_match($regexUrl,$url) )
            return true;
        else
            return false;
    }

    function redirects($url){
        $headers = get_headers($url,1);//The returned HTTP headers
        $output = array();//The list of redirects

        //The first value in the array is the requested URL and its response code
            $output[0] = array('location'=>$url,'response'=>$headers[0]);

        //Counts the number of redirects
            $i = 0;

        //Append each redirect to the array if there are any
            if ( isset($headers['Location']) ){
                //If Location is an array, append each url and its response code to $output
                    if ( is_array($headers['Location']) ){
                        do{
                            $i++;
                            $redirect = array('location'=>$headers['Location'][$i-1],'response'=>$headers[$i]);
                            $output[$i] = $redirect;
                        }while(array_key_exists($i, $headers));
                    }
                //If Location is a value, append its url and response code to $output
                    elseif ( isset($headers['Location']) ){
                        $i++;
                        $redirect = array('location'=>$headers['Location'],'response'=>$headers[$i]);
                        $output[$i] = $redirect;
                    }
            }

        //Return the array
            return $output;
    }
//==============================================================================
?>