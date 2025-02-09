<?php
/*
* File version: 2
*/
?>
<div class="container-fluid">
	<div class="row bump-down">
		<div class="col-md-12">
			<p class="section"><?php esc_html_e('The following information will help generate more traffic for your organization, the more the better. Your email address is not publicly available, instead a contact form will be embedded with your listing.', 'ldd-directory-lite'); ?></p>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				<label class="control-label" for=""><?php esc_html_e('Contact Name', 'ldd-directory-lite'); ?></label>
				<input type="text" id="f_contact_name" class="form-control" name="n_contact_name" value="<?php echo  esc_html(ldl_get_value('contact_name')); ?>">
				<p class="help-block"><?php esc_html_e("Name of person to contact", 'ldd-directory-lite'); ?></p>
				<?php echo wp_kses_post(ldl_get_error('contact_name')); ?>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label class="control-label" for=""><?php esc_html_e('Email', 'ldd-directory-lite'); ?></label>
				<input type="text" id="f_contact_email" class="form-control" name="n_contact_email" value="<?php echo  esc_html(ldl_get_value('contact_email')); ?>">
                <?php echo ldl_get_error('contact_email'); ?>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				<label class="control-label" for=""><?php esc_html_e('Phone', 'ldd-directory-lite'); ?></label>
				<input type="text" id="f_contact_phone" class="form-control" name="n_contact_phone" value="<?php echo  esc_html(ldl_get_value('contact_phone')); ?>">
                <?php echo wp_kses_post(ldl_get_error('contact_phone')); ?>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label class="control-label" for=""><?php esc_html_e('Fax', 'ldd-directory-lite'); ?></label>
				<input type="text" id="f_contact_fax" class="form-control" name="n_contact_fax" value="<?php echo esc_html(ldl_get_value('contact_fax')); ?>">
                <?php echo wp_kses_post(ldl_get_error('contact_fax')); ?>
			</div>
		</div>
	</div>
	<div class="row bump-down">
		<div class="col-md-6">
			<div class="form-group">
				<label class="control-label" for=""><?php esc_html_e('Website', 'ldd-directory-lite'); ?></label>
				<input type="text" id="f_url_website" class="form-control" name="n_url_website" value="<?php echo esc_html(ldl_get_value( 'url_website' )); ?>">
				<p class="help-block"><?php wp_kses_post(_e("Examples include; 'http://www.yoursite.com', 'mysite.org'", 'ldd-directory-lite')); ?></p>
                <?php echo wp_kses_post(ldl_get_error('url_website')); ?>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label class="control-label" for=""><?php esc_html_e('Facebook', 'ldd-directory-lite'); ?></label>
				<input type="text" id="f_url_facebook" class="form-control" name="n_url_facebook " value="<?php echo esc_html(ldl_get_value( 'url_facebook' )); ?>">
				<p class="help-block"><?php wp_kses_post(_e('Help locating and customizing your <a href="https://www.facebook.com/help/www/329992603752372" title="Your Facebook Web Address | Facebook Help Center">Facebook profile URL</a>', 'ldd-directory-lite')); ?></p>
                <?php echo wp_kses_post(ldl_get_error('url_facebook')); ?>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label class="control-label" for=""><?php esc_html_e('Twitter', 'ldd-directory-lite'); ?></label>
				<input type="text" id="f_url_twitter" class="form-control" name="n_url_twitter" value="<?php echo esc_html(ldl_get_value( 'url_twitter' )); ?>">
				<p class="help-block"><?php wp_kses_post(_e("This will always be similar to 'https://twitter.com/<strong>username</strong>'", 'ldd-directory-lite')); ?></p>
                <?php echo wp_kses_post(ldl_get_error('url_twitter')); ?>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label class="control-label" for=""><?php esc_html_e('Linkedin', 'ldd-directory-lite'); ?></label>
				<input type="text" id="f_url_linkedin" class="form-control" name="n_url_linkedin" value="<?php echo esc_html(ldl_get_value( 'url_linkedin' )); ?>">
				<p class="help-block"><?php wp_kses_post(_e('Help locating and customizing your <a href="http://help.linkedin.com/app/answers/detail/a_id/85/~/promoting-your-public-profile" title="Promoting Your Public Profile | LinkedIn Help Center">LinkedIn profile URL</a>', 'ldd-directory-lite')); ?></p>
                <?php echo wp_kses_post(ldl_get_error('url_linkedin')); ?>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label class="control-label" for=""><?php esc_html_e('Skype', 'ldd-directory-lite'); ?></label>
				<input type="text" id="f_contact_skype" class="form-control" name="n_contact_skype" value="<?php echo esc_html(ldl_get_value('contact_skype')); ?>">
				<p class="help-block"><?php esc_html_e("Your Skype Username", 'ldd-directory-lite'); ?></p>
				<?php echo wp_kses_post(ldl_get_error('contact_skype')); ?>
			</div>
		</div>
		
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label" for=""><?php esc_html_e('Instagram', 'ldd-directory-lite'); ?></label>
                        <input type="text" id="f_url_instagram" class="form-control" name="n_url_instagram" value="<?php echo esc_html(ldl_get_value( 'url_instagram' )); ?>">
                        <p class="help-block"><?php esc_html_e('https://www.instagram.com/?hl=en', 'ldd-directory-lite'); ?></p>
                        <?php echo wp_kses_post(ldl_get_error('url_instagram')); ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label" for=""><?php esc_html_e('YouTube', 'ldd-directory-lite'); ?></label>
                        <input type="text" id="f_url_youtube" class="form-control" name="n_url_youtube" value="<?php echo esc_html(ldl_get_value( 'url_youtube' )); ?>">
                        <p class="help-block"><?php esc_html_e('https://www.youtube.com/', 'ldd-directory-lite'); ?></p>
                        <?php echo wp_kses_post(ldl_get_error('url_youtube')); ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label" for=""><?php esc_html_e('Custom Link', 'ldd-directory-lite'); ?></label>
                        <input type="text" id="f_url_custom" class="form-control" name="n_url_custom" value="<?php echo esc_html(ldl_get_value( 'url_custom' )); ?>">
                        <p class="help-block"><?php esc_html_e('www.yourdomain.com', 'ldd-directory-lite'); ?></p>
                        <?php echo wp_kses_post(ldl_get_error('url_custom')); ?>
                    </div>
                </div>
                
	</div>
</div>

