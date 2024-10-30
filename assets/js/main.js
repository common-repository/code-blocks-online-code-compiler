jQuery(document).ready(function($) {
    $('#lmscode-form').submit(function(e) {
        e.preventDefault();
        var code = $("#code").val();
        var inp = $("#inp").val();
        var lang = $("#lang").find(":selected").val();
        // alert(lang);
        // alert(code);

        $.ajax({
            data: { action: 'wtt_get_data', code: code, lang: lang, inp: inp },
            type: 'post',
            url: lms_code.ajaxurl,
            
            success: function(resp) {
                //console.log(resp);
                //console.log(resp.data.statusCode);
                $("#lmscode-output").empty().append("<b>Output: </b>" + resp.data.output);
                $("#lmscode-cpu").empty().append("<b>cpuTime: </b>" + resp.data.cpuTime);
                $("#lmscode-memory").empty().append("<b>Memory: </b>" + resp.data.memory);

            },
            error: function(){
   console.log("Some message to display when the error happens");
}
        })

    });
    // $("#lmscode-form").change(function() {
    //     var langcss = $("#lang").find(":selected").val();
    //     $("#code").toggleClass("language-" + langcss);

    // });

});