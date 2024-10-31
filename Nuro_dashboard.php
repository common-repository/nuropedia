
<?php 
/* Getting authorization for Nuropedia gradient color effects  */

$token=Nuro_Token();

$url = "https://nuropedia.nuronics.com/api/get_color";
$headers = array(
    'Authorization' => 'token '.$token
);

$options = [
  'methods' => 'get',
  'headers'   => $headers,
];

$response = wp_remote_get( $url, $options);
$body = wp_remote_retrieve_body( $response );
$first_color = json_decode($body)->{"first_color"};
$second_color = json_decode($body)->{"second_color"};      

?>


<?php 

/* Getting authorization for Nuropedia user  */

    $token=Nuro_Token();

    $url = "https://nuropedia.nuronics.com/api/details";
    $headers = array(
        'Authorization' => 'token '.$token
    );

    $options = [
      'methods' => 'get',
      'headers'   => $headers,
    ];

    $response = wp_remote_get( $url, $options);
    $body = wp_remote_retrieve_body( $response );
    $name = json_decode($body)->{"name"};
    $description = json_decode($body)->{"description"};
    $plan = json_decode($body)->{"plan"};
    $info = json_decode($body)->{"info"};
    $status = Nuro_Status();

?>

    <script src="<?php echo plugin_dir_url(__file__) . 'js/popper.min.js' ?>" ></script>

    
    <div id='loader' style='display: none;'>
        <img src="<?php echo plugin_dir_url(__file__) . 'images/loading_3.gif' ?>" />
        <p>Scraping please wait</p>
    </div>

<div class="bgimg">

<div class="header">
    <img src="<?php echo plugin_dir_url(__file__) . 'images/nuronics_logo.svg' ?>"
    alt="Nuronics Logo" />
</div>

<div class="nuroSection">

<div class="nuroLogo ">

    <div class="row">
    
    <?php 
        if($status == "true"){
            echo  '<h4 class="col-9">STATUS:  <span class="statusText"><i>ACTIVE</i></span></h4>';
        }
        else{
            echo  '<h4 class="col-9">STATUS:  <span class="statusRed"><i>INACTIVE</i></span></h4>';
        }
    ?>

    <div class="col-3">
        <button onclick="logout()" class=" whiteBtn  ">Logout</button>
    </div>

    </div>

</div>

<div class="formdiv row">
    
        <div class="innerForm border-right border-dark col-6 ">
            <h5 class="pt-4 pl-2 pb-2">You're connected as <b><?php echo esc_attr($name) ?></b> </h5>
            <div class="card pt-4">
                <div class="row mb-1">
                    <h5 class="col-7"> <b>CURRENT PLAN</b> </h5>

                    <h6 class="col-5"> 
                    <img src="<?php echo plugin_dir_url(__file__) . 'images/Group2348.svg' ?>" width="16px" height="16px" alt="" />    
                    <b><?php echo esc_attr($plan) ?></b></h6>
                </div>
                
                <p class="nuroh4 pt-3" ><?php echo esc_attr($description) ?></p>
                <?php
                    if ($plan == "None") {
                        echo '<p style="color:#606060;"><i> <a href="https://nuropedia.nuronics.com/billing"> Click here to choose a plan </a></i></p>'; 
                    } else {
                        echo '<p style="color:#606060;"><i>' . esc_attr($info) . '</i></p>'; 
                    }
                ?>
            </div>

            <?php 
                $status=Nuro_Status() ;
        
                if($plan != "None"){
                    if($status == "false"){
                        echo  '<button onclick="activate()" class="custombtn">Activate</button>';
                    }
                    else{
                        echo  '
                        <button onclick="deactivate()" class="custombtn">Disconnect</button>
                        <button onclick="rescrape()" class="rescrapBtn">Crawl site again</button>'  ;  
                        
                    }
                }


            ?>
            
        </div>
        <div class="innerForm col-6 row">
            
            <div class="colorclass col-6">
                <p class="nuroh4"><b>customize plugin</b> </p>
                <div class="row">
                    <div class="col-6">
                        <p class="nuroh5">Color 1</p>
                        <input id="id_left_color" type="text" value="<?php echo esc_attr($first_color) ?>" class="colorbtn">
                    </div>
                    <div class="col-6">
                        <p class="nuroh5">Color 2</p>
                        <input id="id_right_color" type="text" value="<?php echo esc_attr($second_color) ?>" class="colorbtn">
                    </div>
                </div>
            </div>

            <div class="col-6">
                <div class="heading-area-main ">
                    <div class="heading-area" id="id-heading-area">
                        <img src="<?php echo plugin_dir_url(__file__) . 'images/Group 23.svg' ?>" width="40px" height="40px" alt="" />
                        <div class="heading-area-text">
                            <span class='nuroh1'>Chat with</span><br>
                            <span class='nuroh4'>nuropedia</span>
                        </div>
                        <i class="fa-sharp fa-solid fa-angle-down alignRight"></i>
                    </div>
                </div>

                <button onclick="cusUpdate()" class="custombtn mt-3 mb-0">Update</button>
            </div>
            
            
            <div class="row mb-2">

                <div class="col-12"> <p class="nuroh4"><b>Choose from our gallery</b> </p> </div>
                
                <img class="col-6 hoverImg" onclick="changeColor1()" src="<?php echo plugin_dir_url(__file__) . 'images/sampleBot1.svg' ?>" alt="" width="264px" height="80px">
                <img class="col-6 hoverImg" onclick="changeColor2()" src="<?php echo plugin_dir_url(__file__) . 'images/sampleBot2.svg' ?>" alt="" width="264px" height="80px">
                <img class="col-6 hoverImg" onclick="changeColor3()" src="<?php echo plugin_dir_url(__file__) . 'images/sampleBot3.svg' ?>" alt="" width="264px" height="80px">
                <img class="col-6 hoverImg" onclick="changeColor4()" src="<?php echo plugin_dir_url(__file__) . 'images/sampleBot4.svg' ?>" alt="" width="264px" height="80px">

            </div>


        </div>

</div>


</div>
<p id='admin_url' style='display: none;'><?php echo admin_url('admin-ajax.php')?></p>

</div>








