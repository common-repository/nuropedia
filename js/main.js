//import * as TimePicker from "./TimePicker.js;"
var COOKIEKey = ''
var response;
var my_bookings = []
var reserved_bookings = []
var selectedDate = ""

async function toggleChat() {
    var chatActivate = document.getElementById("chat-activate");
    var chatArea = document.getElementById("chat-area");

    // Simulate an asynchronous operation (replace with your actual async code)
    await new Promise(resolve => setTimeout(resolve, 50));

    chatArea.hidden = !chatArea.hidden;

    // Toggle visibility of chatActivate based on chatArea's visibility
    chatActivate.hidden = !chatArea.hidden;

    if(chatArea.hidden == false){
        jQuery('#chat-ui').animate({ scrollTop: jQuery('#chat-ui').prop("scrollHeight") }, 500);
    }

}



//const popup = document.querySelector('.chat-popup');
//const chatBtn = document.querySelector('.chat-btn');
const submitBtn = document.getElementById('nuroSubmit');
const chatArea = document.querySelector('.nuro-chat');
const inputElm = document.getElementById('nuroTypeInput');
const currentUrl = document.getElementById('nuroCurrUrl').value;

// chatBtn.addEventListener('click', () => {
//     popup.classList.toggle('show');
// })
jQuery('#nuroTypeInput').keypress(function(event) {
    // Check if the pressed key is Enter (key code 13)
    if (event.which === 13) {
        // Call your function here
        userActivity();
    }
});

submitBtn.addEventListener('click', () => {
    userActivity()
});
async function userActivity(){
    let userInput = inputElm.value;
    if(userInput=='') 
        return;
    var img_path = currentUrl+"images/user-icon.svg";

    let temp = `<div class="d-flex justify-content-end">
                    <div class="user-chat-thread">
                    <img src="${img_path}" height="16" class="user-icon" />

                    <p class="user-text">
                        ${userInput}
                    </p>
                    </div>
                </div>`;

    chatArea.insertAdjacentHTML("beforeend", temp);
    jQuery('#chat-ui').animate({ scrollTop: jQuery('#chat-ui').prop("scrollHeight") }, 500);
    inputElm.value = '';

    intent = await checkIntent(userInput);

    console.log(response)


    console.log(response);
    response = JSON.parse(response["data"]);


    if (response["Calendar"] == 0) {
        result = response["bot"];

        var img_path = currentUrl+"images/chat-icon.svg";
        let temp1 = `<div class="d-flex">
                        <div class="nuro-chat-thread">
                        <img src="${img_path}" height="16" class="chat-icon" />
                        <p class="nuro-text">
                            ${result}
                        </p>
                        </div>
                    </div>`;

        chatArea.insertAdjacentHTML("beforeend", temp1);
        jQuery('#chat-ui').animate({ scrollTop: jQuery('#chat-ui').prop("scrollHeight") }, 500);
        inputElm.value = '';
    }
    else {

        reserved_bookings = cleanReservedDate(response["reserved_meetings"]);
        my_bookings = cleanDateTime(response["my_meetings"])

        //document.getElementById("idInputArea").style.display = "none";

        if (response["Calendar"] == 1 ) {
            
            var chatUI = document.getElementById("chat-ui");
            var calUI = document.getElementById("cal-ui");
            calUI.style.display = "block";
            chatUI.style.display = "none";
            dateField = document.getElementById('nuroInputDate')
            timeField = document.getElementById('nuroInputTime');
            timeField.style.display = "none";
            dateField.addEventListener("change", function() {
                // Check if the date field is not empty
                
                if (dateField.value != "") {
                    selectedDate = dateField.value;
                    timeField.style.display = "block";

                    timeDisplaySelector(timeField);

                }
                else{
                    timeField.value = ""
                    timeField.style.display = "none";
                }
            });
            //nuroInputDate
        }
        else if (response["Calendar"] == 2) {

            var chatUi = document.getElementById("chat-ui");
            var calUiCan = document.getElementById("cal-ui-cancel");
            calUiCan.style.display = "block";
            chatUi.style.display = "none";

            var selectElement = document.getElementById("nuroInputTimeCan");
  
            // Clear existing options, excluding the placeholder
            selectElement.innerHTML = `<option disabled value="">YYYY-MM-DD at HH:MM</option>`;
            
            // Add new options
            my_bookings.forEach(function(optionText) {
              var option = document.createElement("option");
              option.text = optionText;
              selectElement.add(option);
            });
        }
        else if (response["Calendar"] == 4) {

            var chatUiRe = document.getElementById("chat-ui");
            var calUiRe = document.getElementById("cal-ui-reschedule");
            calUiRe.style.display = "block";
            chatUiRe.style.display = "none";

            var selectElement = document.getElementById("nuroInputTimeCan1");
  
            // Clear existing options, excluding the placeholder
            selectElement.innerHTML = `<option disabled value="">YYYY-MM-DD at HH:MM</option>`;
            
            // Add new options
            my_bookings.forEach(function(optionText) {
              var option = document.createElement("option");
              option.text = optionText;
              selectElement.add(option);
            });

            dateField = document.getElementById('nuroInputDate2')
            timeField = document.getElementById('nuroInputTime2');
            timeField.style.display = "none";
            dateField.addEventListener("change", function() {
                // Check if the date field is not empty
                if (dateField.value != "") {
                    selectedDate = dateField.value;
                    timeField.style.display = "block";

                    timeDisplaySelector(timeField);
                }
                else{
                    timeField.value = ""
                    timeField.style.display = "none";
                }
            });
        }
    }
}

function cleanDateTime( myBookings){
    yy = []
    for( x in myBookings){
        date = myBookings[x]['date'];
        time = myBookings[x]['time'];
        time = time.substring(0, time.length - 3);
        datetime = date+' at '+time;
        console.log(datetime);
        yy.push(datetime);
    }
    return yy;
}
function cleanReservedDate( reservedDate){
    let empty_dict = {};
    for (let i = 0; i < reservedDate.length; i++) {
        let y = reservedDate[i];
        let date = y["date"];
        let time = y["time"];
        if (!(date in empty_dict)) {
            empty_dict[date] = [time.slice(0, -3)];
        } else {
            empty_dict[date].push(time.slice(0, -3));
        }
    }
    console.log(empty_dict);
    return empty_dict;
}
async function ajaxCall(question) {

    nonce = document.getElementById('wp_rest').innerText;
    send_ques = document.getElementById('nuro_send_ques').innerText;
    // event.preventDefault();

    return jQuery.ajax({
        method: 'post',
        url: send_ques,
        data: {
            "msg": question,
            "session_id": COOKIEKey
        },
    })

}


left_color_obj = document.getElementById("id_left_color");
right_color_obj = document.getElementById("id_right_color");

left_color = left_color_obj.value;
right_color = right_color_obj.value;

const root = document.querySelector(':root');
root.style.setProperty('--theme-color-one', left_color);
root.style.setProperty('--theme-color-two', right_color);

// Function to set or get the "nuro_token" cookie
async function manageCookie() {

    // Check if the "nuro_token" cookie exists
    const cookies = document.cookie.split(';');
    let nuroTokenValue = null;

    for (let i = 0; i < cookies.length; i++) {
        const cookie = cookies[i].trim();
        if (cookie.startsWith("nuro_token=")) {
            // Cookie exists, get its value
            nuroTokenValue = cookie.substring("nuro_token=".length, cookie.length);
            break;
        }
    }

    if (nuroTokenValue == null) {
        // Cookie already exists, you can use nuroTokenValue
        nuroTokenValue = generateUUID(); // Replace with your desired token value
        document.cookie = "nuro_token=" + nuroTokenValue+"; path=/";
        console.log(nuroTokenValue, " token value");
    } 
    return nuroTokenValue;
}

function getGmail() {
    return new Promise((resolve, reject) => {
        // Function to handle button click
        function handleButtonClick() {
            // Remove event listener to prevent multiple resolutions
            emailForm = document.getElementById("nuroEmailForm");
            if(emailForm.checkValidity()){
                emailForm.removeEventListener("click", handleButtonClick);
                var userName = document.getElementById("nuroInputName4").value;
                var email = document.getElementById("nuroEmail4").value;

                resolve({ email, userName });
            }
            else{
                alert("Enter valid details");
            }
            
            
        }

        function handleCancelClick(){
            document.getElementById("nuroEmailSubmit").removeEventListener("click", handleButtonClick);
            document.getElementById("nuroInputName4").value = "";
            document.getElementById("nuroEmail4").value = "";

            document.getElementById("nuroEmailForm").style.display = "none";
            document.getElementById("chat-ui").style.display = "block";
        }
        // Add event listener to the button
        document.getElementById("nuroEmailSubmit").addEventListener("click", handleButtonClick);
        document.getElementById("nuroEmailCancel").addEventListener("click", handleCancelClick);
    });
}
// Call the function to manage the cookie

function generateUUID() {
    const crypto = window.crypto || window.msCrypto; // For compatibility with older browsers
    if (crypto) {
        const array = new Uint16Array(2);
        crypto.getRandomValues(array);
        return `${array[0]}-${array[1]}`;
    } else {
        // Fallback for browsers that don't support crypto.getRandomValues
        console.warn("crypto.getRandomValues not supported.");
        return null;
    }
}


async function setChatHistory() {

    nonce = document.getElementById('wp_rest').innerText;
    url = document.getElementById('nuro_get_chat').innerText;
    var nuroTokenValue = await manageCookie();
    COOKIEKey = nuroTokenValue;
    //event.preventDefault();
    return jQuery.ajax({
        method: 'post',
        url: url,
        data: { "session_id": nuroTokenValue},
    })
}

async function onLoad() {
    response = await setChatHistory();
    console.log(response);
    data = JSON.parse(response["data"]);

    let i = 0;
    while (i < data.length) {

        var img_path = currentUrl+"images/user-icon.svg";
        let temp = `<div class="d-flex justify-content-end">
            <div class="user-chat-thread">
            <img src="${img_path}" height="16" class="user-icon" />
            <p class="user-text">
                ${data[i]["question"]}
            </p>
            </div>
        </div>`;
        chatArea.insertAdjacentHTML("beforeend", temp);

        var img_path = currentUrl+"images/chat-icon.svg";
        let temp1 = `<div class="d-flex">
                        <div class="nuro-chat-thread">
                        <img src="${img_path}" height="16" class="chat-icon" />
                        <p class="nuro-text">
                            ${data[i]["response"]}
                        </p>
                        </div>
                    </div>`;
        chatArea.insertAdjacentHTML("beforeend", temp1);

        i++;
    }
    //jQuery('#chat-ui').animate({ scrollTop: jQuery('#chat-ui').prop("scrollHeight") }, 500);
}
onLoad();


function ajaxCalendar(dateTime, nuroTokenValue) {
    nonce = document.getElementById('wp_rest').innerText;
    send_calendar = document.getElementById('nuro_send_calendar').innerText;
    event.preventDefault();

    return jQuery.ajax({
        method: 'post',
        url: send_calendar,
        data: {
            "msg": dateTime,
            "token" : nuroTokenValue
        },
    })
}

function ajaxCalendarCan(dateTime, nuroTokenValue) {
    nonce = document.getElementById('wp_rest').innerText;
    send_calendar = document.getElementById('nuro_send_cancel').innerText;
    event.preventDefault();
    
    return jQuery.ajax({
        method: 'post',
        url: send_calendar,
        data: {
            "msg": dateTime,
            "token" : nuroTokenValue
        },
    })
}

function ajaxReschedule(oldDate, newDate, token) {
    nonce = document.getElementById('wp_rest').innerText;
    send_reschedule = document.getElementById('nuro_send_reschedule').innerText;
    event.preventDefault();

    return jQuery.ajax({
        method: 'post',
        url: send_reschedule,
        data: {
            "oldDate": oldDate,
            "newDate": newDate,
            "token": token,
        },
    })
}

function ajaxHasEmail(nuroTokenValue){
    nonce = document.getElementById('wp_rest').innerText;
    has_email = document.getElementById('nuro_has_email').innerText;

    return jQuery.ajax({
        method: 'post',
        url: has_email,
        data:{
            "session_id": nuroTokenValue
        }
        
    })
}


async function nuroSchedule(){

    var chatUI = document.getElementById("chat-ui");
    var calUI = document.getElementById("cal-ui");


    const form = document.getElementById("cal-ui");

    //event.preventDefault();
    if (form.checkValidity() && document.getElementById('nuroInputTime').value!='') {
        //botResponse = await calendarFunction();


        // dateTime = document.getElementById('nuroInputDateTime').value;
        // dateTime = dateTime.replace('T', " at ");
        date = document.getElementById('nuroInputDate').value;
        time = document.getElementById('nuroInputTime').value;
    
        dateTime = date+' at '+time;

        console.log(dateTime, "datetime <----------------") 
        document.getElementById("nuroPreLoader").style.display = "block";
    
        const startTime = performance.now();
        response = await ajaxCalendar(dateTime, COOKIEKey);
        const endTime = performance.now();
        const timeTaken = endTime - startTime;
        console.log("time took for schedule nd cancel" + timeTaken + " milliseconds");
    
        document.getElementById("nuroPreLoader").style.display = "none";
        console.log(response);
        botResponse = JSON.parse(response["data"])["message"];

        console.log(botResponse, "< cal response")
        
        form.reset();
        calUI.style.display = "none";
        chatUI.style.display = "block";

        var img_path = currentUrl+"images/chat-icon.svg";
        var temp2 = `<div class="d-flex">
            <div class="nuro-chat-thread">
            <img src="${img_path}" height="16" class="chat-icon" />
            <p class="nuro-text">
                ${botResponse}
            </p>
            </div>
        </div>`;
        chatArea.insertAdjacentHTML("beforeend", temp2);
        jQuery('#chat-ui').animate({ scrollTop: jQuery('#chat-ui').prop("scrollHeight") }, 500);
        temp2 = null;

    } else {

        alert("Please fill in all required fields.");
    }

}

async function nuroCancel(){

    var chatUI = document.getElementById("chat-ui");
    var calUI = document.getElementById("cal-ui-cancel");


    const form = document.getElementById("cal-ui-cancel");

    //event.preventDefault();
    if (form.checkValidity()) {

        dateTime = document.getElementById('nuroInputTimeCan').value;
        // time = document.getElementById('nuroInputTimeCan').value;
    
        //dateTime = date+' at '+time;

        console.log(dateTime, "datetime <----------------") 
        document.getElementById("nuroLoaderCancel").style.display = "block";

        response = await ajaxCalendarCan(dateTime, COOKIEKey);
    
        document.getElementById("nuroLoaderCancel").style.display = "none";
        console.log(response);
        botResponse = JSON.parse(response["data"])["message"];

        console.log(botResponse, "< cal response")
        
        form.reset();
        calUI.style.display = "none";
        chatUI.style.display = "block";

        var img_path = currentUrl+"images/chat-icon.svg";
        var temp2 = `<div class="d-flex">
            <div class="nuro-chat-thread">
            <img src="${img_path}" height="16" class="chat-icon" />
            <p class="nuro-text">
                ${botResponse}
            </p>
            </div>
        </div>`;
        chatArea.insertAdjacentHTML("beforeend", temp2);
        jQuery('#chat-ui').animate({ scrollTop: jQuery('#chat-ui').prop("scrollHeight") }, 500);
        temp2 = null;

    } else {

        alert("Please fill in all required fields.");
    }

}

async function nuroReschedule(){

    var chatUiRe = document.getElementById("chat-ui");
    var calUiRe = document.getElementById("cal-ui-reschedule");

    const form = document.getElementById("cal-ui-reschedule");

    if (form.checkValidity() && document.getElementById('nuroInputTime2').value!="") {

        oldDateTime = document.getElementById('nuroInputTimeCan1').value;

        newDate = document.getElementById('nuroInputDate2').value;
        newTime = document.getElementById('nuroInputTime2').value;
        newDateTime = newDate+' at '+newTime
        //console.log(dateTime, email, name);
    
        document.getElementById("nuroPreLoader1").style.display = "block";

        const startTime = performance.now();
        response = await ajaxReschedule(oldDateTime, newDateTime, COOKIEKey);
        const endTime = performance.now();
        const timeTaken = endTime - startTime;
        console.log("time took for reschedule api " + timeTaken + " milliseconds");

        document.getElementById("nuroPreLoader1").style.display = "none";
        console.log(response);
    
        botResponse = JSON.parse(response["data"])["message"];


        console.log(botResponse, "< cal response")
        
        form.reset();
        calUiRe.style.display = "none";
        chatUiRe.style.display = "block";

        var img_path = currentUrl+"images/chat-icon.svg";
        let temp2 = `<div class="d-flex">
            <div class="nuro-chat-thread">
            <img src="${img_path}" height="16" class="chat-icon" />
            <p class="nuro-text">
                ${botResponse}
            </p>
            </div>
        </div>`;
        
        chatArea.insertAdjacentHTML("beforeend", temp2);
        jQuery('#chat-ui').animate({ scrollTop: jQuery('#chat-ui').prop("scrollHeight") }, 500);
    } else {

        alert("Please fill in all required fields.");
    }
}

function nuroExit(){
    var chatUI = document.getElementById("chat-ui");
    var calUI = document.getElementById("cal-ui");
    const form = document.getElementById("cal-ui");

    form.reset();
    calUI.style.display = "none";
    chatUI.style.display = "block";
}

function nuroExit1(){
    var chatUiRe = document.getElementById("chat-ui");
    var calUiRe = document.getElementById("cal-ui-reschedule");
    const form = document.getElementById("cal-ui-reschedule");

    form.reset();
    calUiRe.style.display = "none";
    chatUiRe.style.display = "block";
}

function nuroExit2(){
    var chatUI = document.getElementById("chat-ui");
    var calUI = document.getElementById("cal-ui-cancel");
    const form = document.getElementById("cal-ui-cancel");

    form.reset();
    calUI.style.display = "none";
    chatUI.style.display = "block";
}

var today = new Date().toISOString().split('T')[0];
console.log(today)
var datePickers = document.querySelectorAll('input[type="date"]');
datePickers.forEach(function(datePicker) {
  datePicker.min = today;
});



async function checkIntent(user_teext){
    // Assuming rescheduling_intents is your list of strings
    var calandar_intents = ["arrange a meeting", "set up a meeting", "plan a meeting",
    "organize a meeting", "fix a meeting", "book a meeting", "coordinate a meeting", "book an appointment",
    "convene a meeting", "secure a meeting", "calendar a meeting", "request a meeting",
    "call a meeting", "arrange an appointment", "set a meeting time", "establish a meeting",
    "schedule an appointment", "schedule", "schedule a meeting",
    "cancel", "cancel appointment", "cancel meeting", "cancel the meeting", "call off appointment", "call off meeting",
    "abort appointment", "abort meeting", "revoke appointment", "revoke meeting",
    "rescind appointment", "rescind meeting", "terminate appointment", "terminate meeting",
    "undo appointment", "undo meeting", "withdraw appointment", "withdraw meeting",
    "cancel a previously scheduled appointment", "cancel a previously scheduled meeting",
    "reschedule", "reschedule meeting", "move meeting", "move appointment", "reschedule appointment", "reschedule an appointment", "reschedule my meeting",
    "change meeting", "change appointment", "adjust meeting schedule",
    "adjust appointment schedule", "rearrange meeting", "rearrange appointment",
    "shift meeting", "shift appointment", "postpone meeting", "postpone appointment",
    "delay meeting", "delay appointment", "reallocate meeting", "realter appointment",
    "modify meeting time", "modify appointment time", "rebook meeting", "rebook appointment",
    "find a new meeting time", "find a new appointment time"];


    // Check if any of the rescheduling_intents are present in the text (case-insensitive)
    var isIntentPresent = calandar_intents.some(function(intents) {
        return user_teext.toLowerCase().includes(intents.toLowerCase());
    });

    console.log(isIntentPresent); // Output: true or false
    if(isIntentPresent == true){
        hasEmail = await ajaxHasEmail(COOKIEKey);
        
        hasEmail = JSON.parse(hasEmail["data"])["status"];
        console.log(hasEmail, '<- status');
        if(hasEmail == "False"){
            
            var chatUI = document.getElementById("chat-ui");
            var emailUI = document.getElementById("nuroEmailForm");
            emailUI.style.display = "block";
            chatUI.style.display = "none";
    
            const { email, userName } = await getGmail();
            console.log(email, userName)
            await nuroSetEmail(email, userName, COOKIEKey)

            loader1 = document.getElementById('nuroPreLoader2')
            loader1.style.display = "block";
            response = await ajaxCall(user_teext);
            loader1.style.display = "none";
    
            emailUI.style.display = "none";
            chatUI.style.display = "block";   
        }
        else{
            response = await ajaxCall(user_teext);    
        }
    }
    else{
        response = await ajaxCall(user_teext);
    }
    
    return Promise.resolve();

}


async function nuroSetEmail( email, userName, nuroTokenValue) {

    nonce = document.getElementById('wp_rest').innerText;
    send_email = document.getElementById('nuro_set_email').innerText;

    event.preventDefault();

    return jQuery.ajax({
        method: 'post',
        url: send_email,
        data: {
            "end_email": email,
            "end_user": userName,
            "session_id": nuroTokenValue
        },
    })

}









// functions for custom time picker


function numberToOption(number) {

	return `<option value="${number}">${number}</option>`;
}
//activate();




function timeDisplaySelector(timeDocument){
    bookedHour = []
    if(selectedDate in reserved_bookings){
        bookedHour = reserved_bookings[selectedDate];
    }


    let hourOptions = ['10:00', '10:30', '11:00', '11:30', '12:00', '12:30', '13:00', '13:30','14:00', '14:30', 
    '15:00', '15:30', '16:00', '16:30', '17:00', '17:30']
    
    for (let i = 0; i < bookedHour.length; i++) {
        let index = hourOptions.indexOf(bookedHour[i]);
        if (index !== -1) {
            hourOptions.splice(index, 1);
        }
    }
    hourOptions = hourOptions.map(numberToOption);
    hourOptions = ['<option value="" disabled selected>HH:MM</option>'].concat(hourOptions);
    timeDocument.innerHTML = hourOptions.join("");
}

























