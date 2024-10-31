

    function loginBtn(){

        event.preventDefault();
        console.log("<?php echo admin_url('admin-ajax.php')?>");

        var emailDoc= document.getElementById("form2Example1");
        var passDoc= document.getElementById("form2Example2");
        data={
              "action": 'contact_us',
              "email":emailDoc.value,
              "password":passDoc.value}
        console.log(data);

        var url= document.getElementById('admin_url').innerText;
        jQuery.ajax({
            url: url,
            type:'post',
            data:data,
            success:function(result){
                console.log("sucess");
                console.log(result);
                if(result["data"]==0){
                    console.log("reloading");
                    location.reload();
                }
                else{
                    jQuery("#wrongEmail").show();
                }

            },
            error: function(){
                text="your email or password is incorrect please try again"
                jQuery("#wrongEmail").show();
            }
        });
        console.log("lol");
    };

    function createAccount(){
        window.location = "http://216.48.186.61:8015/register";
    }