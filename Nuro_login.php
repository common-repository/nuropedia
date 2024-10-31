


    
<div class="bgimg">

<div class="header">
    <img src="<?php echo plugin_dir_url(__file__) . 'images/nuronics_logo.svg' ?>"
    alt="Nuronics Logo" />
</div>

<div class="nuroSection">

<div class="header">
    <img src="<?php echo plugin_dir_url(__file__) . 'images/logo_1.svg' ?>"
    width="42px" height="45px"  alt="Nuronics Logo" />
</div>

<div class="formdiv">
    <h4 class="logins">Login</h4>
    <form class="formform" method="POST">
        <!-- Email input -->
        <p class="text-danger" id="wrongEmail" style='display: none;'> your email or password is incorrect please try again </p>
        <div class="form-outline mb-4 ">
            <label class="form-label" for="form2Example1">Email</label>
            <input type="text" id="form2Example1" class="form-control" name='email'/>
        

            
        </div>

        <!-- Password input -->
        <div class="form-outline mb-4">
            <label class="form-label" for="form2Example2">Password</label>
            <input type="password" id="form2Example2" class="form-control" name='password'/>
            
        </div>

        <div class="row mb-4">
            <div class="col-6 d-flex justify-content-center">
                <!-- Checkbox -->
                <button type="submit" onclick="loginBtn()" class=" custombtn mb-3">Login</button>
            </div>

            <div class="col-6 d-flex justify-content-center">
                <a class="whiteBtn mb-3 pt-1" style="color:#007bff ; text-align: center; " href="https://nuropedia.nuronics.com/api/register"> Create account  </a>
                
                
            </div>
        </div>
    </form>
</div>


</div>
<p id='admin_url' style='display: none;'><?php echo admin_url('admin-ajax.php')?></p>
</div>






