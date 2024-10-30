<?php

if(!function_exists('wtt_get_data')){
    function wtt_get_data() {
    
       
        $clid = get_option( 'lmscode_options1' );
        $clintid= get_option( 'lmscode_options2' );
        
        
        $endpoint = 'https://api.jdoodle.com/v1/execute';
         
        $body = [
            'clientId'     => $clid,
            'clientSecret' => $clintid,
            'script'       => stripslashes(trim(sanitize_text_field($_POST['code']))),
            'language'     => sanitize_text_field($_POST['lang']),
            'stdin'        => sanitize_text_field($_POST['inp']),
            'versionIndex' => '0',
        ];
         
        $body = wp_json_encode( $body );
         
        $options = [
            'body'        => $body,
            'headers'     => [
                'Content-Type' => 'application/json',
            ],
            'data_format' => 'body',
        ];
        $apiResponse=wp_remote_post( $endpoint, $options ) ;
        $apiBody     = json_decode( wp_remote_retrieve_body( $apiResponse ) );
         wp_send_json_success($apiBody);
     
    }
}



add_action( 'wp_ajax_nopriv_wtt_get_data', 'wtt_get_data' );
add_action( 'wp_ajax_wtt_get_data', 'wtt_get_data' );

?>

<?php

add_shortcode( 'lms_code', 'wttlmscode_shortcode' );

function wttlmscode_shortcode( $atts ) {
    
 $lmscodepram = shortcode_atts( array(
 'script' => '#',
 'language' => 'php'
 ), $atts );
 
$output = '<form id="lmscode-form" action="#" method="post">
<select name="language" id="lang" class="lang-select">
    <option value="python3">python</option>
    <option value="php">PHP</option>
    <option value="java">Java</option>
    <option value="c">C</option>
    <option value="cpp">c++</option>
    <option value="ruby">Ruby</option>
</select>
<br><br>
<p class="lmscode-txt">Here right your code:</p>

<textarea class="prism-live line-numbers language-php fill prism-live-source" name="script" id="code" cols="30" rows="10"></textarea>
<input type="hidden" name="action" value="wtt_get_data"></input>
<p class="lmscode-txt">If have any input, write here:</p>

<input class="lmscode-input" type="text" name="inp" id="inp" placeholder="input value ">
<input class="code-run-btn"  type="submit" name="submit" value="Run">
<p id="lmscode-output"  class="lmscode-result"></p>
<p id="lmscode-cpu"  class="lmscode-result"></p>
<p id="lmscode-memory"  class="lmscode-result"></p>
<div id="picoutput"></div>
</form>
';



 return $output;
}


?>