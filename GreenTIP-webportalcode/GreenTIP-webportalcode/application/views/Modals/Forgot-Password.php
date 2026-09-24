<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
</div>
<?= form_open('', array('class' => 'form-horizontal ajax_form login-form', 'id' => 'login-form')) ?>
<div class="alert ajax_report" role="alert" style="display:none;">
    <span class="close">x</span>
    <span class="ajax_message"></span>
</div>
<div class="form-group">
	 <label for="email">Email Address*</label>
    <input placeholder="Email Address" class="form-control" data-val="true" data-val-required="The Email field is required." id="Email" name="Email" required="required" type="email" value="<?php echo set_value('Email'); ?>"/>
    <?php echo form_error('Email'); ?>
</div>
<div class="submit-btn">
    <button type="submit" class="btn btn-success-data">Submit</button>
</div>
<div class="sign-btn">
    <p class="form-control-static">Not have an account?<span class="sign-up">
        <a title='Sign Up' href="javascript:void(0)" data-href="<?php echo base_url('ajax/signup') ?>" data-toggle='modal' class='registerModal'>Sign Up</a>
        </span></p>
</div>
</form>
<script src="<?php echo assets_url('js', 'jquery.form.js'); ?>"></script>

