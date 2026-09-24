<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
</div>
<?= form_open('', array('class' => 'form-horizontal ajax_form login-form', 'id' => 'register-form')) ?>
<div class="alert ajax_report" role="alert" style="display:none;">
    <span class="close">x</span>
    <span class="ajax_message"></span>
</div>
<div class="form-group">
    <label for="fname">First Name*</label>
    <input type="text" class="form-control" id="fname" placeholder="First Name" name="firstname" required>
</div>

<div class="form-group">
    <label for="lastname">Last Name*</label>
    <input type="text" class="form-control" id="lname" placeholder="Last Name" name="lastname" required>
</div>

<div class="form-group">
    <label for="email">Email Address*</label>
    <input type="email" class="form-control" id="email" placeholder="Email Address" name="email" required>
</div>

<div class="form-group">
    <label for="pwd">Password*</label>
    <input type="password" class="form-control" id="pwd" placeholder="Password" name="password" required>
</div>

<div class="row">
    <div class="col-sm-12">
        <p class="policy-data"><a href="<?php echo base_url('page/terms-and-conditions');?>" target="_blank">Terms & conditions </a> | <a target="_blank" href="<?php echo base_url('page/privacy-policy');?>">Privacy policy </a></p>
    </div>
</div>
<div class="submit-btn">
    <button type="submit" class="btn btn-success-data">Submit</button>
</div>
<div class="sign-btn">
    <p class="form-control-static">Already registered?<span class="sign-up">
           <a title="Login?" href="javascript:void(0)" data-href="<?php echo base_url('ajax/login') ?>"  data-toggle="modal" class="loginModal">Login</a>
        </span></p>
</div>
</form>
<script src="<?php echo assets_url('js', 'jquery.form.js'); ?>"></script>