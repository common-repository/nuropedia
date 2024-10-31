
    function logout()
        {
        document.cookie =  "nuro_token=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/";
        var url= document.getElementById('admin_url').innerText;
        data={
              "action": 'logout',
            }

        jQuery.ajax({
            url: url,
            type:'post',
            data:data,
            beforeSend: function () {
                        },
            complete: function () {
                        },
            success:function(result){
                console.log(result);
                if(result["data"]=='success'){
                    location.reload();
                }
                else{
                    alert("unable to logout");
                }

            },
            error: function(){
                alert('failure');
            }
        });
    }

    function activate(){
        console.log("clicked on activate");
        var url= document.getElementById('admin_url').innerText;;
        data={
              "action": 'activate',
            }

        jQuery.ajax({
            url: url,
            type:'post',
            data:data,
            beforeSend: function () {
                            jQuery("#loader").show();
                        },
            complete: function () {
                            jQuery("#loader").hide();
                        },
            success:function(result){
                console.log(result);
                if(result["data"]=='success'){
                    location.reload();
                }
                else{
                    alert("unable to scrape website");
                }

            },
            error: function(){
                alert('failure');
            }
        });
    }

    function rescrape(){
        activate();
    }

    function deactivate(){
        console.log("clicked on deactivate");
        var url= document.getElementById('admin_url').innerText;;
        data={
              "action": 'deactivate',
            }

        jQuery.ajax({
            url: url,
            type:'post',
            data:data,
            success:function(result){
                console.log(result);
                if(result["data"]=='success'){
                    location.reload();
                }
                else{
                    alert("unable to deactivate website");
                }

            },
            error: function(){
                alert('failure');
            }
        });
    }


    function changeColor(left_color,right_color){
        xx=document.getElementsByClassName("heading-area-main");
        xx[0].style.backgroundImage="linear-gradient(to right,"+ left_color+","+right_color;

        yy=document.getElementsByClassName("heading-area");
        yy[0].style.backgroundImage="linear-gradient(to right,"+ left_color+","+right_color;

        data={
              "action": 'change_color',
              "color1": left_color,
              "color2": right_color}
        console.log(data);

        var url= document.getElementById('admin_url').innerText;;
        console.log(url);
        jQuery.ajax({
            url: url,
            type:'post',
            data:data,
            success:function(result){
                console.log("sucess");
                console.log(result);
                console.log(result['data']['status'],'<-----------');
                if(result['data']['status'] != 1){
                    alert('not a valid color hexcode');
                }
            },
            error: function(){
                alert('failure');
            }
        });

        left_color_obj = document.getElementById("id_left_color");
        right_color_obj = document.getElementById("id_right_color");
        left_color_obj.value=left_color;
        right_color_obj.value=right_color;
    }

    left_color_obj = document.getElementById("id_left_color");
    right_color_obj = document.getElementById("id_right_color");

    left_color = left_color_obj.value;
    right_color = right_color_obj.value;
    console.log(left_color,right_color);

    xx=document.getElementsByClassName("heading-area-main");
    xx[0].style.backgroundImage="linear-gradient(to right,"+ left_color+","+right_color;

    yy=document.getElementsByClassName("heading-area");
    yy[0].style.backgroundImage="linear-gradient(to right,"+ left_color+","+right_color;


      // console.log(yy);
      function changeColor1(){
        changeColor("#6460FB","#3499FF");
      }

      function changeColor2(){
        changeColor("#F650A0","#FF9897");
        
      }

      function changeColor3(){
        changeColor("#00B8BA","#00AE40");
      }

      function changeColor4(){
        changeColor("#EA4D2C","#FFA62E");
      }

      function cusUpdate(){
        left_color_obj = document.getElementById("id_left_color");
        right_color_obj = document.getElementById("id_right_color");

        left_color = left_color_obj.value;
        right_color = right_color_obj.value;
        console.log(left_color,right_color);
        changeColor(left_color,right_color);
      }
      