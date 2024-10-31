<?php

/* Getting authorization for Nuropedia gradient color effects  */
ob_start();
$token = Nuro_Token();

$url = "https://nuropedia.nuronics.com/api/get_color";
$headers = array(
  'Authorization' => 'token ' . $token
);

$options = [
  'methods' => 'get',
  'headers' => $headers,
];

$response = wp_remote_get($url, $options);
$body = wp_remote_retrieve_body($response);
$first_color = json_decode($body)->{"first_color"};
$second_color = json_decode($body)->{"second_color"};

?>

<button id="chat-activate" style="position: fixed !important;" class="chat-activate-btn position-absolute bottom-0 end-0 me-4 mb-4" onclick="toggleChat()">
  <img src="<?php echo plugin_dir_url(__FILE__) .'images/chat-icon.svg' ?>" width="38px" height="25px"/>
</button>
<!-- Chat Popup Screen -->
<section id="chat-area" style="position: fixed !important;" hidden class="chat-area position-absolute bottom-0 end-0 me-5 mb-3 rounded-3 overflow-hidden">
  <section class="nuro-header">
    <img src="<?php echo plugin_dir_url(__FILE__) . 'images/nuropedia-logo.svg' ?>" alt="Chat with Nuropedia"
     width="192px" height="70px" />
    <button class="close-btn" style="color: white; font-size: 1.4rem;" onclick="toggleChat()">
      _
    </button>
  </section>
  <!-- ================ Main Chat Screens ================ -->
  <!-- Message Thread Chat Screen -->
  <div class="nuro-chat" id="chat-ui">
    <!-- Chat thread view -->

    <!-- Incoming Message from Nuropedia -->
    <!-- <div class="d-flex">
      <div class="nuro-chat-thread">
        <img src="" height="16" class="chat-icon" />
        <p class="nuro-text">
          👋🏻Hi, How can I help you?
        </p>
      </div>
    </div> -->

    <!-- ************************************************************ -->
    <!-- Outgoing Message from User -->


    <!-- Text input field -->
    <div class="text-form">
      <input type="text" id="nuroTypeInput" class="text-input" placeholder="Type your query" />
      <button class="send-btn" id="nuroSubmit" type="button">
        <img src="<?php echo plugin_dir_url(__FILE__) . 'images/send-btn.svg' ?>" alt="send" height="16" />
      </button>
    </div>
  </div>
  <!-- Calendar UI for Scheduling Screen -->

  <form class="nuro-calendar mt-3" id="nuroEmailForm" style="display: none;">
    <!--  -->
    <label for="name" class="form-label mb-0">Name</label>
    <input type="text" class="form-control mb-2" required id="nuroInputName4"/>
    <!--  -->
    <label for="email" class="form-label mb-0">Email Address</label>
    <input type="email" class="form-control mb-2" required id="nuroEmail4"/>
    <!--  -->
    <div class="d-flex flex-row mt-3">
      <p value="Submit" id="nuroEmailSubmit" class="btn w-45 mx-auto text-white nuro-bg mr-2">
        Submit
      </p>
      <p id="nuroEmailCancel" class="btn w-45 mx-auto text-white nuro-bg">
        Cancel
      </p>
    </div>
    <img src="<?php echo plugin_dir_url(__FILE__) . 'images/preloader.svg' ?>" width="50 px" style ="display: none;" id="nuroPreLoader2"
      style="height: 4rem; margin: auto" />
  </form>

  <form class="nuro-calendar mt-3" id="cal-ui" style="display: none;">
    
    <label for="schedule" class="form-label mb-0">Schedule your appointment</label>
    <input type="date" required id="nuroInputDate" class="date-picker" />
    <!-- <input type="text" class="time-pickable date-picker" id="nuroInputTime" style="display:none; position: relative;" placeholder="HH:MM" required readonly> -->
    <select id="nuroInputTime" style="display:none; position: relative;" readonly required class="date-picker custom-select">
        <option value="" disabled selected>HH:MM</option>
    </select>
    <div class="d-flex flex-row mt-3">
      <p value="Submit" id="nuroCalButton" onclick="nuroSchedule()" class="btn w-45 mx-auto text-white nuro-bg mr-2">
        Submit
      </p>
      <p id="nuroCancelButton" onclick="nuroExit()" class="btn w-45 mx-auto text-white nuro-bg">
        Cancel
      </p>
    </div>

    <img src="<?php echo plugin_dir_url(__FILE__) . 'images/preloader.svg' ?>" width="50 px" style ="display: none;" id="nuroPreLoader"
      style="height: 4rem; margin: auto" />
  </form>

  <form class="nuro-calendar mt-3" id="cal-ui-cancel" style="display: none;">
    
    <label for="schedule" class="form-label mb-0">Select a date to cancel meeting</label>

    <select id="nuroInputTimeCan" class="date-picker">
      <option disabled value="">YYYY-MM-DD at HH:MM</option>
    </select>


    <div class="d-flex flex-row mt-3">
      <p value="Submit" id="nuroCalButton" onclick="nuroCancel()" class="btn w-45 mx-auto text-white nuro-bg mr-2">
        Submit
      </p>
      <p id="nuroCancelButton" onclick="nuroExit2()" class="btn w-45 mx-auto text-white nuro-bg">
        Cancel
      </p>
    </div>

    <img src="<?php echo plugin_dir_url(__FILE__) . 'images/preloader.svg' ?>" width="50 px" style ="display: none;" id="nuroLoaderCancel"
      style="height: 4rem; margin: auto" />
  </form>

  <form class="nuro-calendar mt-3" id="cal-ui-reschedule" style="display: none;">
    
    <label for="schedule" class="form-label mb-0">Old appointment datetime</label>
    <select id="nuroInputTimeCan1" class="date-picker">
      <option disabled value="">YYYY-MM-DD at HH:MM</option>
    </select>

    <br>
    <br>
    <label for="schedule" class="form-label mb-0">New appointment datetime</label>
    <input type="date" required id="nuroInputDate2" class="date-picker" />
    <select id="nuroInputTime2" style="display:none; position: relative;" readonly required class="date-picker custom-select">
        <option value="" disabled selected>HH:MM</option>
    </select>
    
    <div class="d-flex flex-row mt-3">
      <p value="Submit" id="nuroCalButton1" onclick="nuroReschedule()" class="btn w-45 mx-auto text-white nuro-bg mr-2">
        Submit
      </p>
      <p id="nuroCancelButton1" onclick="nuroExit1()" class="btn w-45 mx-auto text-white nuro-bg">
        Cancel
      </p>
    </div>
    <img src="<?php echo plugin_dir_url(__FILE__) . 'images/preloader.svg' ?>" width="50 px" style ="display: none;" id="nuroPreLoader1"
      style="height: 4rem; margin: auto" />
  </form>
</section>


<button type="button" id="nuroCurrUrl" style="display: none;" value=<?php echo plugin_dir_url(__FILE__) ?>></button>
<button type="button" id="id_left_color" style="display: none;" value=<?php echo esc_attr($first_color) ?>></button>
<button type="button" id="id_right_color" style="display: none;" value=<?php echo esc_attr($second_color) ?>></button>
<p id='wp_rest' style='display: none;'>
  <?php echo wp_create_nonce('wp_rest'); ?>
</p>
<p id='nuro_send_ques' style='display: none;'>
  <?php echo get_rest_url(null, 'nuro/send-chat'); ?>
</p>
<p id='nuro_get_chat' style='display: none;'>
  <?php echo get_rest_url(null, 'nuro/get-chat'); ?>
</p>
<p id='nuro_send_calendar' style='display: none;'>
  <?php echo get_rest_url(null, 'nuro/send_calendar'); ?>
</p>
<p id='nuro_send_cancel' style='display: none;'>
  <?php echo get_rest_url(null, 'nuro/send_cancel'); ?>
</p>
<p id='nuro_send_reschedule' style='display: none;'>
  <?php echo get_rest_url(null, 'nuro/send_reschedule'); ?>
</p>
<p id='nuro_has_email' style='display: none;'>
  <?php echo get_rest_url(null, 'nuro/has_email'); ?>
</p>
<p id='nuro_set_email' style='display: none;'>
  <?php echo get_rest_url(null, 'nuro/set_email'); ?>
</p>




