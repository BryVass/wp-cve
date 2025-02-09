var $uifm = jQuery.noConflict();

if (typeof $uifm === 'undefined') {
	$uifm = jQuery;
}
var rocketfm = rocketfm || null;
if (!$uifm.isFunction(rocketfm)) {
	(function($, window) {
		window.rocketfm = rocketfm = $.rocketfm = function() {
			var uifmvariable = [];
			uifmvariable.innerVars = {};
			uifmvariable.externalVars = {};

			var cur_form_obj = null;

			var validators = {
				letters: {
					regex: /^[A-Za-z][A-Za-z\s]*$/,
				},
				numbers: {
					regex: /^(\s*\d+)+\s*$/,
				},
				numletter: {
					regex: /^[A-Za-z0-9-.,:;\s][A-Za-z0-9\s-.,:;]*$/,
				},
				postcode: {
					regex: /^.{3,}$/,
				},
				email: {
					regex: /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,8}$/,
				},
				phone: {
					regex: /^[2-9]\d{2}-\d{3}-\d{4}$/,
				},
			};

			arguments.callee.setAccounting = function(obj) {
			};

			arguments.callee.initialize = function() {
			};
			arguments.callee.setExternalVars = function() {
				uifmvariable.externalVars['fm_loadmode'] = rockfm_vars._uifmvar['fm_loadmode'] || '';
				uifmvariable.externalVars['is_demo'] = rockfm_vars._uifmvar['is_demo'] || 0;
				uifmvariable.externalVars['is_dev'] = rockfm_vars._uifmvar['is_dev'] || 0;
			};
			arguments.callee.getExternalVars = function(name) {
				if (uifmvariable.externalVars[name]) {
					return uifmvariable.externalVars[name];
				} else {
					return '';
				}
			};
			arguments.callee.setInnerVariable = function(name, value) {
				uifmvariable.innerVars[name] = value;
			};
			arguments.callee.setInnerVariable_byform = function(idform, name, value) {
				if (typeof uifmvariable.innerVars['var_form' + idform] == 'undefined') {
					uifmvariable.innerVars['var_form' + idform] = {};
				}
				uifmvariable.innerVars['var_form' + idform][name] = value;
			};
			arguments.callee.getInnerVariable = function(name) {
				if (uifmvariable.innerVars[name]) {
					return uifmvariable.innerVars[name];
				} else {
					return '';
				}
			};
			arguments.callee.getInnerVariable_byform = function(idform, name) {
				if (uifmvariable.innerVars['var_form' + idform]) {
					return uifmvariable.innerVars['var_form' + idform][name];
				} else {
					return '';
				}
			};
			arguments.callee.dumpvar3 = function(object) {
				return JSON.stringify(object, null, 2);
			};
			arguments.callee.dumpvar2 = function(object) {
				return JSON.stringify(object);
			};

			arguments.callee.dumpvar = function(object) {
				var seen = [];
				var json = JSON.stringify(object, function(key, val) {
					if (val != null && typeof val == 'object') {
						if (seen.indexOf(val) >= 0) return;
						seen.push(val);
					}
					return val;
				});
				return seen;
			};

			arguments.callee.showLogMessage = function(msg) {
				console.log(msg);
			};
			arguments.callee.validate_processValidation = function(value, type_val) {
				var isValid = false;
				if (value.length) {
					switch (parseInt(type_val)) {
						case 1:
							if (value.length && validators['letters'].regex.test(value)) {
								isValid = true;
							}
							break;
						case 2:
							if (value.length && validators['numletter'].regex.test(value)) {
								isValid = true;
							}
							break;
						case 3:
							if (value.length && validators['numbers'].regex.test(value)) {
								isValid = true;
							}
							break;
						case 4:
							value = $.trim(value);
							if (value.length && validators['email'].regex.test(value)) {
								isValid = true;
							}
							break;
						case 6:
							let field_obj = this.getInnerVariable('cur_field_obj'),
								customval = decodeURIComponent(field_obj.attr('data-val-cval_regex'));
							let regex = new RegExp(customval);
							if (value.length && regex.exec(value) !== null) {
								isValid = true;
							}

							break;
						case 5:
						default:
							if (value.length) {
								isValid = true;
							}
							break;
					}
				}
				return isValid;
			};

			arguments.callee.validate_applyPopOverOpt = function(element) {

				var tmp_cur_fm_obj = this.getInnerVariable('cur_form_obj') || 'body';

				var cus_placement;
				switch (parseInt($(element).data('val-pos'))) {
					case 1:
						cus_placement = 'right';
						break;
					case 2:
						cus_placement = 'bottom';
						break;
					case 3:
						cus_placement = 'left';
						break;
					case 0:
					default:
						cus_placement = 'top';
						break;
				}

				var options = {

					animation: false,
					html: true,
					placement: cus_placement,
					content: $(element).data('val-custxt') || 'Ops... this is required',
					trigger: 'manual',
					container: tmp_cur_fm_obj,
				};
				return options;
			};
			arguments.callee.validate_addInvalidFields = function(value) {
				var temp;
				temp = this.getInnerVariable('val_invalid_fields');
				temp.push(value);
				this.setInnerVariable('val_invalid_fields', temp);
			};

			arguments.callee.validate_field = function(el) {
				var field_id, field_type, field_value, val_type, val_custtext, val_pos, val_tip, val_tip_col, val_tip_bg, field_pop;
				field_id = el.attr('id');
				field_type = el.attr('data-typefield');
				val_type = el.data('val-type') || 0;
				val_pos = el.data('val-pos');
				val_tip = el.data('tip_col');
				val_tip_col = el.data('tip_col');
				val_tip_bg = el.data('tip_bg');
				this.setInnerVariable('cur_form_obj', el.closest('.rockfm-form'));

				this.setInnerVariable('cur_field_obj', el);

				var tmp_theme_type;
				var tempvar;
				var searchInput;

				switch (parseInt(field_type)) {
					case 6:
					case 7:
					case 15:
					case 28:

					case 29:

					case 30:

						field_value = el.find('.rockfm-txtbox-inp-val').val();
						field_pop = el.find('.rockfm-txtbox-inp-val');
						if (this.validate_processValidation(field_value, val_type)) {
							el.removeClass('rockfm-required');
							field_pop.removeClass('rockfm-val-error');

							field_pop.sfdc_popover('destroy');
						} else {
							el.addClass('rockfm-required');
							if (!field_pop.hasClass('rockfm-val-error')) {
								field_pop.addClass('rockfm-val-error');
							}

							field_pop
								.sfdc_popover('destroy')
								.sfdc_popover(this.validate_applyPopOverOpt(el))
								.sfdc_popover('show');
						}
						break;
					case 8:
					case 9:
					case 10:
					case 11:
					case 12:

					case 13:
					case 23:
					case 24:
					case 25:
					case 26:
					case 43:

						switch (parseInt(field_type)) {
							case 8:

								tmp_theme_type = el.find('.rockfm-input2-wrap').attr('data-theme-type');

								switch (parseInt(tmp_theme_type)) {
									case 1:
										tempvar = el.find('.rockfm-inp2-rdo');

										searchInput = tempvar
											.map(function(index) {
												if (
													$(this)
														.parent()
														.hasClass('checked')
												) {
													return $(this).val();
												} else {
													return null;
												}
											})
											.toArray();

										break;
									default:
										tempvar = el.find('.rockfm-inp2-rdo');

										searchInput = tempvar
											.map(function(index) {
												if ($(this).is(':checked')) {
													return $(this).val();
												} else {
													return null;
												}
											})
											.toArray();

										break;
								}

								if (searchInput[0]) {
									field_value = '1';
								} else {
									field_value = '';
								}
								field_pop = el.find('.rockfm-input2-wrap');
								break;
							case 9:
								tmp_theme_type = el.find('.rockfm-input2-wrap').attr('data-theme-type');

								switch (parseInt(tmp_theme_type)) {
									case 1:
										tempvar = el.find('.rockfm-inp2-chk');

										searchInput = tempvar
											.map(function(index) {
												if (
													$(this)
														.parent()
														.hasClass('checked')
												) {
													return $(this).val();
												} else {
													return null;
												}
											})
											.toArray();

										break;
									default:
										tempvar = el.find('.rockfm-inp2-chk');

										searchInput = tempvar
											.map(function(index) {
												if ($(this).is(':checked')) {
													return $(this).val();
												} else {
													return null;
												}
											})
											.toArray();

										break;
								}

								if (searchInput[0]) {
									field_value = '1';
								} else {
									field_value = '';
								}
								field_pop = el.find('.rockfm-input2-wrap');
								break;
							case 10:
								if (el.find('.rockfm-input2-wrap select option:selected').attr('data-uifm-inp-val').length > 0) {
									field_value = '1';
								} else {
									field_value = '';
								}
								field_pop = el.find('.rockfm-input2-wrap');
								break;
							case 11:
								if (el.find('.rockfm-input2-wrap select option:selected').attr('data-uifm-inp-val').length > 0) {
									field_value = '1';
								} else {
									field_value = '';
								}
								field_pop = el.find('.rockfm-input2-wrap');
								break;
							case 12:
								if (el.find('.rockfm-fileupload-wrap .fileinput-filename').html().length > 0) {
									field_value = '1';
								} else {
									field_value = '';
								}
								field_pop = el.find('.rockfm-fileupload-wrap');
								break;
							case 13:
								if (el.find('.rockfm-fileupload-wrap .fileinput-preview').html().length > 0) {
									field_value = '1';
								} else {
									field_value = '';
								}
								field_pop = el.find('.rockfm-fileupload-wrap .fileinput-preview');
								break;
							case 23:
								field_value = el.find('.rockfm-colorpicker-wrap input').val();
								field_pop = el.find('.rockfm-colorpicker-wrap');
								break;
							case 24:
								field_value = el.find('.rockfm-input7-datepic input').val();
								field_pop = el.find('.rockfm-input7-datepic');
								break;
							case 25:
								field_value = el.find('.rockfm-input7-timepic input').val();
								field_pop = el.find('.rockfm-input7-timepic');
								break;
							case 26:
								field_value = el.find('.rockfm-input7-datetimepic input').val();
								field_pop = el.find('.rockfm-input7-datetimepic');
								break;
							case 43:
								field_value = el.find('.flatpickr-input').val();
								field_pop = el.find('.uifm-input-flatpickr');
								break;
						}

						if (this.validate_processValidation(field_value, val_type)) {
							el.removeClass('rockfm-required');
							field_pop.removeClass('rockfm-val-error');

							field_pop.sfdc_popover('destroy');
						} else {

							el.addClass('rockfm-required');
							if (!field_pop.hasClass('rockfm-val-error')) {
								field_pop.addClass('rockfm-val-error');
							}

							field_pop
								.sfdc_popover('destroy')
								.sfdc_popover(this.validate_applyPopOverOpt(el))
								.sfdc_popover('show');
						}
						break;
					case 0:
						break;
					default:
				}
			};
			arguments.callee.validate_enableHighlight = function(el) {
				try {
					var first_el = el
						.find('.rockfm-required')
						.not('.rockfm-conditional-hidden')
						.not('.rockfm-cond-hidden-children')
						.eq(0);
					var type = first_el.attr('data-typefield');
					var field_inp;
					switch (parseInt(type)) {
						case 6:
						case 15:
						case 28:
						case 29:
						case 30:
							field_inp = first_el.find('.rockfm-txtbox-inp-val');
							field_inp.focus();
							break;
						case 7:
							field_inp = first_el.find('.rockfm-txtbox-inp-val');
							field_inp.focus();
							break;
						case 8:
						case 9:
						case 10:
						case 11:
							field_inp = first_el.find('.rockfm-input2-wrap');
							break;
						case 12:
							field_inp = first_el.find('.rockfm-fileupload-wrap');
							break;
						case 13:
							field_inp = first_el.find('.rockfm-fileupload-wrap');
							break;

						case 23:
							field_inp = first_el.find('.rockfm-colorpicker-wrap');
							break;
						case 24:
							field_inp = first_el.find('.rockfm-input7-datepic');
							break;
						case 25:
							field_inp = first_el.find('.rockfm-input7-timepic');
							break;
						case 26:
							field_inp = first_el.find('.rockfm-input7-datetimepic');
							break;
						case 43:
							field_inp = first_el.find('.uifm-input-flatpickr');
							break;
						case 0:
						default:
							return;
							break;
					}
					var tmp_top;
					tmp_top = parseFloat(field_inp.first().offset().top) - 100;
					if (String(uifmvariable.externalVars['fm_loadmode']) === 'iframe') {
						if ('parentIFrame' in window) {
							parentIFrame.scrollTo(0, tmp_top);
						}
					} else {
						$('html,body').animate(
							{
								scrollTop: tmp_top,
							},
							'slow'
						);
					}
				} catch (ex) {
					console.error('validate_enableHighlight : ', ex.message + ' - ' + type);
				}
			};
			arguments.callee.validate_form = function(el_form) {
				var el, valid;
				cur_form_obj = el_form;
				el_form
					.find('.rockfm-required')
					.not('.rockfm-conditional-hidden')
					.not('.rockfm-cond-hidden-children')
					.on('click change keyup focus keypress', function() {
						rocketfm.validate_field($(this));
					});

				el_form
					.find('.rockfm-required')
					.not('.rockfm-conditional-hidden')
					.not('.rockfm-cond-hidden-children')
					.each(function(index, element) {
						rocketfm.validate_field($(element));
					});

				el_form
					.find('.rockfm-required')
					.not('.rockfm-conditional-hidden')
					.not('.rockfm-cond-hidden-children')
					.find('.rockfm-colorpicker-wrap')
					.colorpicker()
					.on('changeColor', function(ev) {
						var tmp_fld = $(this).closest('.rockfm-field');
						rocketfm.validate_field(tmp_fld);
					});

				if (
					parseInt(
						el_form
							.find('.rockfm-required')
							.not('.rockfm-conditional-hidden')
							.not('.rockfm-cond-hidden-children').length
					) > 0
				) {
					valid = false;
					this.validate_enableHighlight(el_form);
				} else {
					valid = true;
				}
				return {
					isValid: valid,
					error: '',
				};
			};

			arguments.callee.action_refreshevents = function() {
				$('.uiform_modal_general').on('hidden.bs.modal', function() {
					rocketfm.modal_onclose();
				});

				$('.uiform_modal_general').on('shown.bs.modal', function() {
					rocketfm.modal_resizeWhenIframe();
				});
				$('.uiform-pg-order-cont').on('click', function() {
					$(this)
						.find('.uiform-pg-radio-btn')
						.find('input')
						.prop('checked', true);
				});
			};

			arguments.callee.submitForm_showMessage = function(el, response, obj_btn) {
				var msg_error = '<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Error! Form was not submitted.</div>';
				var form_id = el
					.parent()
					.find('._rockfm_form_id')
					.val();
				var msg = '';
				var tmp_msg = el.parent().find('.rockfm-alert-container');
				tmp_msg.html('');

				var tmp_redirect_st = 0;
				var tmp_redirect_url = '';

				if (response) {
					var arrJson = (JSON && JSON.parse(response)) || $.parseJSON(response);
					if (parseInt(arrJson.success) === 1) {
						if (el.find('.uiform-sticky-sidebar-box').length) {
							el.find('.uiform-sticky-sidebar-box')
								.data('uiform_stickybox')
								.destroy();
						}

						if (parseInt(arrJson.payment_st) === 1) {
							msg = decodeURIComponent(arrJson.show_message);
							tmp_msg.append(msg);
							tmp_msg.find('.uiform-pg-summbox-amount').html(zgfm_front_cost.format_money(el, tmp_msg.find('.uiform-pg-summbox-amount').html()));

							rocketfm.action_refreshevents();
						} else {

							if (parseInt(arrJson.sm_redirect_st) === 1) {
								tmp_redirect_st = 1;
								tmp_redirect_url = decodeURIComponent(arrJson.sm_redirect_url);
							} else {
								msg = decodeURIComponent(arrJson.show_message);
								tmp_msg.append('<div class="rockfm-alert-inner" >' + msg + '</div>');
							}
						}

						if (tmp_redirect_st != 1) {
							el.hide();
						}

						try {
							if (parseInt($('.g-recaptcha').length) > 0) {
								delete zgfm_recaptcha_elems['recaptcha_' + form_id];
								$.each(zgfm_recaptcha_elems, function(index, value) {
									grecaptcha.reset(zgfm_recaptcha_elems[index]);
								});
							}
						} catch (err) {}
					} else {
						msg = decodeURIComponent(arrJson.form_error_msg) || msg_error;
						tmp_msg.append('<div class="rockfm-alert-inner" >' + msg + '</div>');
					}
				} else {
					msg = msg_error;
					tmp_msg.append('<div class="rockfm-alert-inner" >' + msg + '</div>');
				}

				if (tmp_redirect_st === 1) {
					rocketfm.redirect_tourl(tmp_redirect_url);
					return false;
				} else {
					if (msg) {
						tmp_msg.show();
					}

					$('.popover').sfdc_popover('hide');
					if ($('.uiform-main-form [data-toggle="tooltip"]').length) {
						$('.uiform-main-form [data-toggle="tooltip"]').tooltip('destroy');
					}
					obj_btn.removeAttr('disabled').html(obj_btn.attr('data-val-btn'));
				}

				jQuery(document).trigger('zgfm.form.after_submit', {});

				if (String(uifmvariable.externalVars['fm_loadmode']) === 'iframe') {
					if ('parentIFrame' in window) {
						parentIFrame.scrollTo(0, tmp_msg.offset().top);
						parentIFrame.size(100); 
						parentIFrame.autoResize();
					}
				} else {
					$('html,body').animate(
						{
							scrollTop: tmp_msg.offset().top,
						},
						'slow'
					);
				}
			};
			arguments.callee.submitForm_submit = function(el) {
				var tmp_math_calculation = '';
				var tmp_mathcalc_enable = el.find('._rockfm_form_calc_math_enable').val();
				if (parseInt(tmp_mathcalc_enable) === 1) {
					tmp_math_calculation = zgfm_front_calc.costest_calc_getTotal(el) || 0;
				}

				formId = parseInt(el.find('._rockfm_form_id').val());

				isMockingSubmit = 'no';
				if (rockfm_vars.hasOwnProperty('forms') && rockfm_vars.forms.hasOwnProperty(formId) && rockfm_vars.forms[formId].hasOwnProperty('is_mocking_submit')) {
					isMockingSubmit = rockfm_vars.forms[formId]['is_mocking_submit'];
				}

				if (String(isMockingSubmit) === 'yes') {
					var tmp_msg = el.parent().find('.rockfm-alert-container');
					tmp_msg.html('');
					tmp_msg.append('<div class="rockfm-alert-inner" ><div class="rockfm-alert rockfm-alert-success"><b>Success!</b> Form was submitted successfully</div></div>');
					$('html,body').animate(
						{
							scrollTop: tmp_msg.offset().top,
						},
						'slow'
					);
					tmp_msg.show();
					el.hide();

					return;
				}

				if (el.find('._rockfm_type_submit') && parseInt(el.find('._rockfm_type_submit').val()) === 1) {
					var obj_btn = el.find('.rockfm-submitbtn .rockfm-txtbox-inp-val');
					if (el.find('.rockfm-fileupload-wrap').length) {
						var options = {
							url: rockfm_vars.ajaxurl,
							beforeSend: function() {},
							type: 'POST',
							beforeSubmit: function(formData, formObject, formOptions) {
								formData.push({ name: 'zgfm_security', value: rockfm_vars.ajax_nonce });
								formData.push({ name: 'zgfm_calc_math', value: tmp_math_calculation });
								formData.push({ name: 'zgfm_is_demo', value: uifmvariable.externalVars['is_demo'] });
							},
							beforeSerialize: function(form, options) {
								el.find('.rockfm-conditional-hidden', form).remove();
								el.find('.rockfm-cond-hidden-children', form).remove();
								obj_btn.attr('disabled', 'disabled').html(obj_btn.attr('data-val-subm') + ' <i class="sfdc-glyphicon sfdc-glyphicon-refresh sfdc-gly-spin"></i>');
							},

							uploadProgress: function(event, position, total, percentComplete) {},
							success: function() {},
							complete: function(response) {
								obj_btn.removeAttr('disabled');
								rocketfm.submitForm_showMessage(el, response.responseText, obj_btn);
							},
							error: function() {
								console.log('errors');
							},
						};
						el.ajaxForm(options);
						el.submit();
					} else {
						var data = el.uifm_serialize();

						$.ajax({
							type: 'post',
							url: rockfm_vars.ajaxurl,
							data: data + '&zgfm_is_demo=' + uifmvariable.externalVars['is_demo'] + '&zgfm_security=' + rockfm_vars.ajax_nonce + '&zgfm_calc_math=' + tmp_math_calculation,
							async: true,
							dataType: 'html',

							beforeSend: function() {
								obj_btn.attr('disabled', 'disabled').html(obj_btn.attr('data-val-subm') + ' <i class="sfdc-glyphicon sfdc-glyphicon-refresh sfdc-gly-spin"></i>');
							},
							success: function(response) {
								obj_btn.removeAttr('disabled');
								rocketfm.submitForm_showMessage(el, response, obj_btn);
							},
						});
					}
				} else {
					el.find('.rockfm-conditional-hidden').remove();
					el.find('.rockfm-cond-hidden-children').remove();
					el.submit();
				}
			};
			arguments.callee.captcha_validate = function() {
				var el_form = this.getInnerVariable('val_curform_obj');
				var captcha_obj = $(el_form).find('.rockfm-inp6-captcha');
				var el_field = captcha_obj.closest('.rockfm-field');
				var obj_btn = $(el_form).find('.rockfm-submitbtn .rockfm-txtbox-inp-val');
				$.ajax({
					type: 'POST',
					url: rockfm_vars.ajaxurl,
					dataType: 'json',
					data: {
						action: 'rocket_front_valcaptcha',
						zgfm_security: rockfm_vars.ajax_nonce,
						'rockfm-code': el_field.find('.rockfm-inp6-captcha-code').val(),
						'rockfm-inpcode': el_field.find('.rockfm-inp6-captcha-inputcode').val(),
					},
					beforeSend: function() {
						rocketfm.submit_changeModbutton(el_form, true);
					},
					success: function(response) {
						try {
							rocketfm.submit_changeModbutton(el_form, false);
							if (typeof response == 'object') {
								if (response.success === true) {
									rocketfm.captcha_response(true);
								} else {
									rocketfm.captcha_response(false);
								}
							} else {
								rocketfm.captcha_response(false);
							}
						} catch (ex) {
							rocketfm.captcha_response(false);
						}
					},
				});
			};

			arguments.callee.captcha_response = function(success) {
				var temp = this.getInnerVariable('val_curform_obj');
				if (success === true) {
					rocketfm.submitForm_submit(temp);
				} else {
					var tmp_captcha = $(temp).find('.rockfm-inp6-captcha-inputcode');
					var hidePopover = function() {
						tmp_captcha.sfdc_popover('hide');
					};
					tmp_captcha
						.sfdc_popover('destroy')
						.sfdc_popover(rocketfm.validate_applyPopOverOpt(tmp_captcha))
						.focus(hidePopover)
						.sfdc_popover('show');

					if (String(uifmvariable.externalVars['fm_loadmode']) === 'iframe') {
						if ('parentIFrame' in window) {
							parentIFrame.scrollTo(0, tmp_captcha.offset().top - 40);
						}
					} else {
						$('html,body').animate(
							{
								scrollTop: tmp_captcha.offset().top - 40,
							},
							'slow'
						);
					}
				}
			};

			arguments.callee.submit_changeModbutton = function(form_obj, load) {
				var obj_btn, obj_btn2;

				if (parseInt($(form_obj).find('.rockfm-submitbtn .rockfm-txtbox-inp-val').length) > 0) {
					obj_btn = $(form_obj).find('.rockfm-submitbtn .rockfm-txtbox-inp-val');

					if (load === true) {
						obj_btn.attr('disabled', 'disabled').html(obj_btn.attr('data-val-subm') + ' <i class="sfdc-glyphicon sfdc-glyphicon-refresh gly-spin"></i>');
					} else {
						obj_btn.removeAttr('disabled').html(obj_btn.attr('data-val-btn'));
					}
				} else if (parseInt($(form_obj).find('.rockfm-wizardbtn .rockfm-btn-wiznext').length) > 0) {
					obj_btn = $(form_obj).find('.rockfm-wizardbtn .rockfm-btn-wizprev');
					obj_btn2 = $(form_obj).find('.rockfm-wizardbtn .rockfm-btn-wiznext');

					var tab_cur_index = form_obj.find('.uiform-steps li.uifm-current').index();

					var tab_next_obj = form_obj.find('.uiform-steps li.uifm-current').next();
					var tab_next_index = tab_next_obj.index();

					var tmp_lbl;
					if (parseFloat(tab_cur_index) < parseFloat(tab_next_index)) {
						tmp_lbl = obj_btn2.attr('data-value-next');
					} else {
						tmp_lbl = obj_btn2.attr('data-value-last');
					}

					if (load === true) {
						obj_btn.attr('disabled', 'disabled');
						obj_btn2
							.attr('disabled', 'disabled')
							.find('.rockfm-inp-lbl')
							.html(tmp_lbl + ' <i class="sfdc-glyphicon sfdc-glyphicon-refresh gly-spin"></i>');
					} else {
						obj_btn.removeAttr('disabled');
						obj_btn2
							.removeAttr('disabled')
							.find('.rockfm-inp-lbl')
							.html(tmp_lbl);
					}
				} else {
				}
			};

			arguments.callee.recaptchav3_validate = function() {
				var form_obj = this.getInnerVariable('val_curform_obj');

				grecaptcha.execute(form_obj.attr('data-zgfm-recaptchav3-sitekey'), { action: 'submit' }).then(function(token) {
					$.ajax({
						type: 'POST',
						url: rockfm_vars.ajaxurl,
						dataType: 'json',
						data: {
							action: 'rocket_front_checkrecaptchav3',
							zgfm_security: rockfm_vars.ajax_nonce,
							zgfm_token: token,
							form_id: form_obj.find('._rockfm_form_id').val(),
						},
						beforeSend: function() {
							rocketfm.submit_changeModbutton(form_obj, true);
						},
						success: function(response) {
							try {
								rocketfm.submit_changeModbutton(form_obj, false);
								if (typeof response == 'object') {
									if (response.success === true) {
										rocketfm.recaptchav3_response(true);
									} else {
										rocketfm.recaptchav3_response(false);
									}
								} else {
									rocketfm.recaptchav3_response(false);
								}
							} catch (ex) {
								rocketfm.recaptchav3_response(false);
							}
						},
						error: function(jqXHR, textStatus, errorThrown) {
							rocketfm.recaptchav3_response(false);
						},
					});
				});
			};

			arguments.callee.recaptcha_validate = function() {
				var form_obj = this.getInnerVariable('val_curform_obj');
				var field_id = form_obj
					.find('.g-recaptcha')
					.closest('.rockfm-recaptcha')
					.attr('data-idfield');
				var form_id = this.getInnerVariable('submitting_form_id');
				var response = grecaptcha.getResponse(zgfm_recaptcha_elems['recaptcha_' + form_id]);

				$.ajax({
					type: 'POST',
					url: rockfm_vars.ajaxurl,
					dataType: 'json',
					data: {
						action: 'rocket_front_checkrecaptcha',
						zgfm_security: rockfm_vars.ajax_nonce,
						'rockfm-uid-field': field_id,
						'rockfm-code-recaptcha': response,
						form_id: form_obj.find('._rockfm_form_id').val(),
					},
					beforeSend: function() {
						rocketfm.submit_changeModbutton(form_obj, true);
					},
					success: function(response) {
						try {
							rocketfm.submit_changeModbutton(form_obj, false);
							if (typeof response == 'object') {
								if (response.success === true) {
									rocketfm.recaptcha_response(true);
								} else {
									rocketfm.recaptcha_response(false);
								}
							} else {
								rocketfm.recaptcha_response(false);
							}
						} catch (ex) {
							rocketfm.recaptcha_response(false);
						}
					},
					error: function(jqXHR, textStatus, errorThrown) {
						rocketfm.recaptcha_response(false);
					},
				});
			};

			arguments.callee.captcha_refreshImage = function(element) {
				var el = $(element);
				var el_data = el.data('rkver');
				var el_url = el.data('rkurl');
				var obj_field = el.closest('.rockfm-field');

				$.ajax({
					type: 'POST',
					url: rockfm_vars.ajaxurl,
					dataType: 'json',
					data: {
						action: 'rocket_front_refreshcaptcha',
						zgfm_security: rockfm_vars.ajax_nonce,
						rkver: el_data,
					},
					success: function(response) {
						obj_field.find('.rockfm-inp6-captcha-img').attr('src', el_url + response.rkver);
						el.attr('data-rkver', response.rkver);
						obj_field.find('.rockfm-inp6-captcha-code').val(response.code);
					},
				});
			};

			arguments.callee.recaptcha_response = function(success) {
				var temp = this.getInnerVariable('val_curform_obj');
				if (success === true) {
					rocketfm.submitForm_submit(temp);
				} else {
					var tmp_captcha = $(temp).find('.rockfm-input5-wrap');
					var hidePopover = function() {
						tmp_captcha.sfdc_popover('hide');
					};
					tmp_captcha
						.sfdc_popover('destroy')
						.sfdc_popover(rocketfm.validate_applyPopOverOpt(tmp_captcha))
						.focus(hidePopover)
						.sfdc_popover('show');


					if (String(uifmvariable.externalVars['fm_loadmode']) === 'iframe') {
						if ('parentIFrame' in window) {
							parentIFrame.scrollTo(0, tmp_captcha.offset().top - 40);
						}
					} else {
						$('html,body').animate(
							{
								scrollTop: tmp_captcha.offset().top - 40,
							},
							'slow'
						);
					}
				}
			};

			arguments.callee.loadform_init = function() {
				var obj_form_list = $('.rockfm-form-container');
				var obj_form, obj_form_id, tmp_form_main;

				obj_form_list.each(function(i) {
					obj_form = $(this).find('.rockfm-form');
					if (!obj_form.hasClass('rockfm-form-mloaded')) {
						obj_form_id = obj_form.find('._rockfm_form_id').val();
						var data_form_main = [];

						tmp_form_main = obj_form.find('.rockfm_main_data').val();
						data_form_main = (JSON && JSON.parse(tmp_form_main)) || $.parseJSON(tmp_form_main);

						rocketfm.setInnerVariable_byform(obj_form_id, 'price_format_st', data_form_main.price_format_st);
						rocketfm.setInnerVariable_byform(obj_form_id, 'price_sep_decimal', data_form_main.price_sep_decimal);
						rocketfm.setInnerVariable_byform(obj_form_id, 'price_sep_thousand', data_form_main.price_sep_thousand);
						rocketfm.setInnerVariable_byform(obj_form_id, 'price_sep_precision', data_form_main.price_sep_precision);

						rocketfm.setInnerVariable('_data_main', data_form_main);

						obj_form.addClass('rockfm-form-mloaded');

						if (obj_form.find('.rockfm-input4-slider').length) {
							var rockfm_slider_d = obj_form.find('.rockfm-input4-slider');
							rockfm_slider_d.each(function(i) {
								$(this).bootstrapSlider();
								$(this)
									.parent()
									.parent()
									.find('.slider-tick-label')
									.hide();
								$(this)
									.parent()
									.parent()
									.find('.rockfm-input4-number')
									.text($(this).val());

								obj_form.find('.rockfm-input4-slider').on('slide', function(slideEvt) {
									$(this)
										.parent()
										.parent()
										.find('.rockfm-input4-number')
										.text(slideEvt.value);

									$(this)
										.parent()
										.parent()
										.find('.slider-tick-label')
										.show();
								});
							});
						}

						if (obj_form.find('.rockfm-input4-spinner').length) {
							var spinners = obj_form.find('.rockfm-input4-spinner'),
								s_min,
								s_max,
								s_step,
								s_value;
							spinners.each(function(i) {
								(s_min = $(this).attr('data-rockfm-min')), (s_max = $(this).attr('data-rockfm-max')), (s_step = $(this).attr('data-rockfm-step')), (s_value = $(this).attr('data-rockfm-value'));

								let s_decimals = $(this).attr('data-rockfm-decimal') || 0;

								$(this).TouchSpin({
									verticalbuttons: true,
									min: parseFloat(s_min),
									max: parseFloat(s_max),
									step: parseFloat(s_step),
									verticalupclass: 'sfdc-glyphicon sfdc-glyphicon-plus',
									verticaldownclass: 'sfdc-glyphicon sfdc-glyphicon-minus',
									initval: parseFloat(s_value),
									decimals: parseFloat(s_decimals),
								});
							});
						}
						if (obj_form.find('.rockfm-input15-switch').length) {
							var rockfm_switch_d = obj_form.find('.rockfm-input15-switch');

							rockfm_switch_d.each(function(i) {
								$(this).bootstrapSwitchZgpb({
									onText: $(this).attr('data-uifm-txt-yes'),
									offText: $(this).attr('data-uifm-txt-no'),
								});
							});
						}
						if (obj_form.find('.rockfm-input17-wrap .uifm-dcheckbox-item').length) {
							obj_form.find('.rockfm-input17-wrap .uifm-dcheckbox-item').uiformDCheckbox();
						}

						if (obj_form.find('.rockfm-input17-wrap .uifm-dradiobtn-item').length) {
							obj_form.find('.rockfm-input17-wrap .uifm-dradiobtn-item').uiformDCheckbox();
						}
						if (obj_form.find('.g-recaptcha').length) {
							if (parseInt(obj_form.find('.g-recaptcha').length) > 0) {
								var rockfm_rcha_d = obj_form.find('.g-recaptcha');
								rockfm_rcha_d.each(function(i) {
									$(this).attr('id', 'zgfm_recaptcha_obj_' + obj_form.find('._rockfm_form_id').val());
								});
							}

							if (parseInt(obj_form.find('.g-recaptcha').length) > 1) {
								var rockfm_rcha_d = obj_form.find('.g-recaptcha');
								rockfm_rcha_d.each(function(i) {
									if (parseInt(i) != 0) {
										$(this)
											.removeClass('g-recaptcha')
											.html('ReCaptcha is loaded once. Remove this field');
									}
								});
							}

							if (!$('#zgfm_form_lib_recaptcha').length) {
								var rockfm_recaptcha = document.createElement('script');
								rockfm_recaptcha.type = 'text/javascript';
								rockfm_recaptcha.async = true;
								rockfm_recaptcha.id = 'zgfm_form_lib_recaptcha';
								rockfm_recaptcha.defer = 'defer';
								rockfm_recaptcha.src = 'https://www.google.com/recaptcha/api.js?onload=zgfm_recaptcha_onloadCallback&render=explicit';
								var s = document.getElementsByTagName('script')[0];
								s.parentNode.insertBefore(rockfm_recaptcha, s);
							}
						}

						if (parseInt(obj_form.attr('data-zgfm-recaptchav3-active')) === 1) {
							let siteKey = obj_form.attr('data-zgfm-recaptchav3-sitekey');
							var rockfm_recaptcha = document.createElement('script');
							rockfm_recaptcha.type = 'text/javascript';
							rockfm_recaptcha.async = true;
							rockfm_recaptcha.id = 'zgfm_form_lib_recaptchav3';
							rockfm_recaptcha.defer = 'defer';
							rockfm_recaptcha.src = 'https://www.google.com/recaptcha/api.js?render=' + siteKey;
							var s = document.getElementsByTagName('script')[0];
							s.parentNode.insertBefore(rockfm_recaptcha, s);
						}

						if (obj_form.find('.rockfm-captcha').length) {
							if (parseInt(obj_form.find('.rockfm-captcha').length) > 1) {
								var rockfm_capcha_d = obj_form.find('.rockfm-captcha');
								rockfm_capcha_d.each(function(i) {
									if (parseInt(i) != 0) {
										$(this)
											.find('.rockfm-inp6-captcha')
											.removeClass('rockfm-inp6-captcha')
											.html('Captcha is loaded once. Remove this field');
									}
								});
							}
							var rockfm_capcha_refobj = obj_form.find('.rockfm-captcha .rockfm-inp6-wrap-refrescaptcha a');
							rocketfm.captcha_refreshImage(rockfm_capcha_refobj);
						}


						if (obj_form.find('.rockfm-input7-datepic').length) {
							var rockfm_datepic_d = obj_form.find('.rockfm-input7-datepic');
							var rkfm_datepic_tmp1, rkfm_datepic_tmp2;
							rockfm_datepic_d.each(function(i) {
								$(this).datetimepicker({
									format: 'L',
								});
								rkfm_datepic_tmp1 = $(this).attr('data-rkfm-language');
								if (rkfm_datepic_tmp1) {
									$(this)
										.data('DateTimePicker')
										.locale(rkfm_datepic_tmp1);
								}
								rkfm_datepic_tmp2 = $(this).attr('data-rkfm-showformat');
								if (rkfm_datepic_tmp2) {
									$(this)
										.data('DateTimePicker')
										.dayViewHeaderFormat(rkfm_datepic_tmp2);
									$(this)
										.data('DateTimePicker')
										.format(rkfm_datepic_tmp2);
								}
							});
						}

						if (obj_form.find('.uifm-input-flatpickr').length) {
							var rockfm_datepic_d = obj_form.find('.uifm-input-flatpickr');
							var rkfm_datepic_tmp1, rkfm_datepic_tmp2;
							var flatpick_instance;
							rockfm_datepic_d.each(function(i) {
								var tmp = {};

								if (parseInt($(this).attr('data-rkfm-enabletime')) === 1) {
									tmp['enableTime'] = true;
								} else {
									tmp['enableTime'] = false;
								}

								if (parseInt($(this).attr('data-rkfm-nocalendar')) === 1) {
									tmp['noCalendar'] = true;
								} else {
									tmp['noCalendar'] = false;
								}

								if (parseInt($(this).attr('data-rkfm-time24hr')) === 1) {
									tmp['time_24hr'] = true;
								} else {
									tmp['time_24hr'] = false;
								}

								if (parseInt($(this).attr('data-rkfm-altinput')) === 1) {
									tmp['altInput'] = true;
								} else {
									tmp['altInput'] = false;
								}

								if (String($(this).attr('data-rkfm-altformat')).length > 0) {
									tmp['altFormat'] = $(this).attr('data-rkfm-altformat');
								} else {
									tmp['altFormat'] = 'F j, Y';
								}

								if (String($(this).attr('data-rkfm-dateformat')).length > 0) {
									tmp['dateFormat'] = $(this).attr('data-rkfm-dateformat');
								} else {
									tmp['dateFormat'] = 'Y-m-d';
								}

								tmp['locale'] = $(this).attr('data-rkfm-language');

								if (String($(this).attr('data-rkfm-mindate')).length > 0) {
									tmp['minDate'] = $(this).attr('data-rkfm-mindate');
								}

								if (String($(this).attr('data-rkfm-maxdate')).length > 0) {
									tmp['maxDate'] = $(this).attr('data-rkfm-maxdate');
								}

								if (String($(this).attr('data-rkfm-defaultdate')).length > 0) {
									tmp['defaultDate'] = $(this).attr('data-rkfm-defaultdate');
								}

								tmp['allowInput'] = true;

								if (parseInt($(this).attr('data-rkfm-isinline')) === 1) {
									tmp['inline'] = true;
								} else {
									tmp['wrap'] = true;
								}

								tmp['onChange'] = function(selectedDates, dateStr, instance) {
									$(instance.element)
										.find('input')
										.val(dateStr);
								};

								flatpick_instance = $(this).flatpickr(tmp);
								$(this).data('zgfm_flatpicker', flatpick_instance);
							});
						}

						if (obj_form.find('.rockfm-input7-timepic').length) {
							var rockfm_timepic_d = obj_form.find('.rockfm-input7-timepic');
							rockfm_timepic_d.each(function(i) {
								$(this).datetimepicker({
									format: 'LT',
								});
							});
						}
						if (obj_form.find('.rockfm-input7-datetimepic').length) {
							var rockfm_datetm_d = obj_form.find('.rockfm-input7-datetimepic');
							var rkfm_datetm_tmp1, rkfm_datetm_tmp2;
							rockfm_datetm_d.each(function(i) {
								$(this).datetimepicker({ minDate: new Date() });
								rkfm_datetm_tmp1 = $(this).attr('data-rkfm-language');
								if (rkfm_datetm_tmp1) {
									$(this)
										.data('DateTimePicker')
										.locale(rkfm_datetm_tmp1);
								}
								rkfm_datetm_tmp2 = $(this).attr('data-rkfm-showformat');
								if (rkfm_datetm_tmp2) {
									$(this)
										.data('DateTimePicker')
										.dayViewHeaderFormat(rkfm_datetm_tmp2);
								}
							});

						}

						if (obj_form.find('.rockfm-input-ratingstar').length) {
							var rockfm_rstar_d = obj_form.find('.rockfm-input-ratingstar');
							rockfm_rstar_d.each(function(i) {
								$(this).rating({
									starCaptions:
										{
											1: $(this).attr('data-uifm-txt-star1') || 'very bad',
											2: $(this).attr('data-uifm-txt-star2') || 'bad',
											3: $(this).attr('data-uifm-txt-star3') || 'ok',
											4: $(this).attr('data-uifm-txt-star4') || 'good',
											5: $(this).attr('data-uifm-txt-star5'),
										} || 'very good',
									clearCaption: $(this).attr('data-uifm-txt-norate'),
									starCaptionClasses: { 1: 'text-danger', 2: 'text-warning', 3: 'text-info', 4: 'text-primary', 5: 'text-success' },
								});
							});
						}

						var tmp_load_obj;

						if (obj_form.find('.rockfm-input2-sel-styl1').length) {
							tmp_load_obj = obj_form.find('.rockfm-input2-sel-styl1');
							tmp_load_obj.each(function(i) {
								$(this).selectpicker({
									noneSelectedText: $(this)
										.parent()
										.attr('data-theme-stl1-txtnosel'),
									noneResultsText: $(this)
										.parent()
										.attr('data-theme-stl1-txtnomatch'),
									countSelectedText: $(this)
										.parent()
										.attr('data-theme-stl1-txtcountsel'),
								});
							});
						}

						if (obj_form.find('.rockfm-input2-sel-styl2').length) {
							tmp_load_obj = obj_form.find('.rockfm-input2-sel-styl2');
							tmp_load_obj.each(function(i) {
								$(this).select2({
									placeholder: 'Select an option',
									theme: 'classic',
									width: '100%',
								});
							});
						}

						if (obj_form.find('.rockfm-input2-chk-styl1').length) {
							tmp_load_obj = obj_form.find('.rockfm-input2-chk-styl1');
							var tmp_chk_icon;
							tmp_load_obj.each(function(i) {
								tmp_chk_icon = $(this).attr('data-chk-icon');
								$(this).checkradios({
									checkbox: {
										iconClass: tmp_chk_icon,
									},
									radio: {
										iconClass: tmp_chk_icon,
									},
									onChange: function(checked, $element, $realElement) {
										if (checked) {
										} else {
										}
									},
								});
							});
						}

						if (obj_form.find('.rockfm-colorpicker-wrap').length) {
							var rockfm_cpicker_d = obj_form.find('.rockfm-colorpicker-wrap');
							rockfm_cpicker_d.each(function(i) {
								$(this).colorpicker();
							});
						}
						if (obj_form.find('[data-rockfm-gfont]').length) {
							var rockfm_tmp = obj_form.find('[data-rockfm-gfont]');
							var rockfm_uniq_font = [];
							rockfm_tmp.each(function(i) {
								if ($.inArray($(this).attr('data-rockfm-gfont'), rockfm_uniq_font) === -1) {
									var atImport = '@import url(//fonts.googleapis.com/css?family=' + $(this).attr('data-rockfm-gfont');
									$('<style>')
										.append(atImport)
										.appendTo('head');
									rockfm_uniq_font.push($(this).attr('data-rockfm-gfont'));
								}
							});
						}

						if (obj_form.find('.rockfm-clogic-fcond').length) {
							obj_form.zgfm_logicfrm(
								obj_form
									.parent()
									.find('.rockfm_clogic_data')
									.val()
							);
							obj_form.data('zgfm_logicfrm').setData();
							obj_form.data('zgfm_logicfrm').refreshfields();
						}

						if (obj_form.find('.rockfm_main_data')) {
							obj_form.zgpb_datafrm(obj_form.find('.rockfm_main_data').val());
						} else {
							obj_form.zgpb_datafrm();
						}

						if ($('.uiform-main-form [data-toggle="tooltip"]').length) {
							$('.uiform-main-form [data-toggle="tooltip"]').tooltip({
								selector: '',
								placement: 'top',
								container: obj_form,
								html: true,
							});
						}

						if (obj_form.find('.uiform-sticky-sidebar-box').length && parseInt(obj_form.find('._rockfm_sticky_st').val()) === 1) {
							zgfm_front_cost.costest_sticky_init(obj_form);
						} else {
						}

						if (obj_form.find('.uiform-stickybox-symbol').length) {
							obj_form.find('.uiform-stickybox-symbol').html(decodeURIComponent(obj_form.find('._rockfm_form_price_symbol').val()));
						}

						if (obj_form.find('.uiform-stickybox-currency').length) {
							obj_form.find('.uiform-stickybox-currency').html(decodeURIComponent(obj_form.find('._rockfm_form_price_currency').val()));
						}

						if (obj_form.find('.rockfm-costest-field').length) {
							zgfm_front_cost.costest_listenEvents(obj_form);

							zgfm_front_cost.costest_refresh(obj_form);
						}

						obj_form.find('input, textarea').placeholder();

						$.each(obj_form.find('.rockfm-conditional-hidden'), function(i, val) {
							$(this)
								.find('.rockfm-field')
								.addClass('rockfm-cond-hidden-children');
						});

						if (String(uifmvariable.externalVars['fm_loadmode']) === 'iframe') {
							if ('parentIFrame' in window) {
								parentIFrame.size(); 
							}
						}

						if (parseInt(obj_form.data('zgpb_datafrm').getData('onload_scroll')) === 1) {
							if (String(uifmvariable.externalVars['fm_loadmode']) === 'iframe') {
								if ('parentIFrame' in window) {
									parentIFrame.scrollTo(0, obj_form.offset().top);
								}
							} else {
								$('html,body').animate(
									{
										scrollTop: obj_form.offset().top,
									},
									'slow'
								);
							}
						}
						wp.hooks.applyFilters('zgfmfront.initForm_loadAddLibs');

						zgfm_front_helper.load_form_init_events(obj_form);

						jQuery(document).trigger('zgfm.form.init_loaded', { form: obj_form });

						obj_form.on('click', '.rockfm-submitbtn.rockfm-field [type="button"],.rockfm-submitbtn.rockfm-field [type="submit"]', function(e) {
							e.preventDefault();
							var obj_form_alt = $(this).closest('.rockfm-form');
							rocketfm.setInnerVariable('submitting_form_id', obj_form_alt.find('._rockfm_form_id').val());

							rocketfm.submitForm_process(obj_form_alt, e);
						});
					}
				});

			};

			arguments.callee.submitForm_process = function(obj_form, e) {
				rocketfm.submitForm_process_beforeVal(
					function(data) {
						if (data.is_valid === true) {
							rocketfm.submitForm_process_validation(e, obj_form, function(data) {
								if (data.is_valid === true) {
									rocketfm.submitForm_submit(obj_form);
								}
							});
						}
					},
					function(error) {
						console.log('error ' + error.test);
					}
				);
			};

			arguments.callee.submitForm_process_validation = function(e, obj_form, callback) {
				var el_form = obj_form;
				this.setInnerVariable('val_curform_obj', el_form);
				var res_val = this.validate_form(el_form);

				var events = rocketfm.getInnerVariable('submit_form_events');

				if (res_val.isValid) {
					if (el_form.find('.g-recaptcha').length) {
						this.recaptcha_validate();
					} else if (el_form.find('.rockfm-inp6-captcha').length) {
						this.captcha_validate();
					} else {
						if (zgfm_front_helper.event_isDefined_toEl(document, 'additional_validation.form', events)) {
							jQuery(document).trigger('zgfm.form.additional_validation', [callback]);
						} else {
							callback({
								is_valid: true,
							});
						}
					}
				}
			};

			arguments.callee.submitForm_process_beforeVal = function(callback, errorCallback) {
				if (false) {
					errorCallback({ test: 'test1' });
				} else {
					var eventos = $(document).getZgfmEvents();

					rocketfm.setInnerVariable('submit_form_events', eventos);

					if (zgfm_front_helper.event_isDefined_toEl(document, 'before_submit.form', eventos)) {
						jQuery(document).trigger('zgfm.form.before_submit', [callback]);
					} else {
						callback({
							is_valid: true,
						});
					}
				}
			};

			arguments.callee.previewfield_removeAllPopovers = function() {
				var tmp_popover = $('.uiform-main-form [aria-describedby^=popover]');
				if (tmp_popover) {
					$.each(tmp_popover, function(index, element) {
						$(element).sfdc_popover('destroy');
					});
				}
			};

			arguments.callee.refresh_fields = function(el) {
				let obj_form = this.getInnerVariable('val_curform_obj');
				if (obj_form.find('.rockfm-input17-wrap .uifm-dcheckbox-item').length) {
					obj_form.find('.rockfm-input17-wrap .uifm-dcheckbox-item').uiformDCheckbox('_refresh');
				}

				if (obj_form.find('.rockfm-input17-wrap .uifm-dradiobtn-item').length) {
					obj_form.find('.rockfm-input17-wrap .uifm-dradiobtn-item').uiformDCheckbox('_refresh');
				}
			};

			arguments.callee.wizard_nextButton = function(el) {
				var el_form = $(el).closest('.rockfm-form');
				this.setInnerVariable('val_curform_obj', el_form);

				rocketfm.setInnerVariable('submitting_form_id', el_form.find('._rockfm_form_id').val());

				var objform = $(el).closest('.rockfm-form');
				var objtabs = objform.find('.uiform-steps li');
				var tabs_num = objtabs.length;
				var tab_cur_index = objform.find('.uiform-steps li.uifm-current').index();

				var tab_next_obj = objform.find('.uiform-steps li.uifm-current').next();
				var tab_next_index = tab_next_obj.index();
				var gotab_next;
				var gotab_next_cont;
				var gotab_cur;
				var gotab_cur_cont;

				gotab_cur = objtabs.eq(tab_cur_index);
				gotab_cur_cont = $(gotab_cur)
					.find('a')
					.attr('data-tab-href');
				var obj_cur_form = objform.find(gotab_cur_cont);
				var res_val = this.validate_form(obj_cur_form);

				rocketfm.setInnerVariable('form_cur_obj', obj_cur_form);

				var events = rocketfm.getInnerVariable('submit_form_events');
				if (!events) {
					var eventos = $(document).getZgfmEvents();
					rocketfm.setInnerVariable('submit_form_events', eventos);
				}

				rocketfm.wizard_nextButton_validate(obj_cur_form, res_val, function(data) {
					if (data.is_valid === true) {
						rocketfm.previewfield_removeAllPopovers();

						if (parseInt(el_form.data('zgpb_datafrm').getData('onload_scroll')) === 1) {
							if (String(uifmvariable.externalVars['fm_loadmode']) === 'iframe') {
								if ('parentIFrame' in window) {
									parentIFrame.scrollTo(0, el_form.offset().top);
								}
							} else {
								$('html,body').animate(
									{
										scrollTop: el_form.offset().top,
									},
									'slow'
								);
							}
						}

						gotab_cur.removeClass('uifm-current').addClass('uifm-complete');
						objform.find(gotab_cur_cont).hide();
						gotab_next = objtabs.eq(tab_next_index);
						gotab_next.removeClass('uifm-disabled').addClass('uifm-current');
						gotab_next_cont = $(gotab_next)
							.find('a')
							.attr('data-tab-href');
						objform.find(gotab_next_cont).show();

						var tmp_nex_cur_form = objform.find(gotab_next_cont);
						tmp_nex_cur_form.show();

						if (parseFloat(tab_cur_index) < parseFloat(tab_next_index)) {
							var tab_next2_obj_index = tab_next_obj.next().index();
							objform.find('.rockfm-btn-wizprev').removeAttr('disabled');

							if (parseFloat(tab_next2_obj_index) > 0 && parseFloat(tab_next2_obj_index) > parseFloat(tab_next_index)) {
							} else {
								var wiznext_lasttxt = tmp_nex_cur_form.find('.rockfm-btn-wiznext').attr('data-value-last') || 'finish';
								tmp_nex_cur_form
									.find('.rockfm-btn-wiznext')
									.find('.rockfm-inp-lbl')
									.html(wiznext_lasttxt);
							}
						} else {
							var obj_btn = el_form.find('.rockfm-btn-wiznext');
							obj_btn.html(obj_btn.html() + ' <i class="sfdc-glyphicon sfdc-glyphicon-refresh gly-spin"></i>');
							obj_btn.attr('disabled', true);
							rocketfm.submitForm_submit(el_form);
						}
					}
				});

				if (String(uifmvariable.externalVars['fm_loadmode']) === 'iframe') {
					if ('parentIFrame' in window) {
						parentIFrame.size(); 
					}
				}

				this.refresh_fields();
			};

			arguments.callee.wizard_nextButton_validate = function(el_form, res_val, callback) {
				var events = rocketfm.getInnerVariable('submit_form_events');

				if (res_val.isValid) {
					if (el_form.find('.g-recaptcha').length) {
						this.recaptcha_validate();
					} else if (el_form.find('.rockfm-inp6-captcha').length) {
						this.captcha_validate();
					} else {
						if (zgfm_front_helper.event_isDefined_toEl(document, 'form.wizbtn_additional_validation', events)) {
							jQuery(document).trigger('zgfm.form.wizbtn_additional_validation', [callback]);
						} else {
							callback({
								is_valid: true,
							});
						}
					}
				}
			};

			arguments.callee.wizard_prevButton = function(el) {
				var objform = $(el).closest('.rockfm-form');
				var objtabs = objform.find('.uiform-steps li');
				var tabs_num = objtabs.length;
				var tab_cur_index = objform.find('.uiform-steps li.uifm-current').index();

				var tab_prev_obj = objform.find('.uiform-steps li.uifm-current').prev();
				var tab_prev_index = tab_prev_obj.index();
				var gotab_prev;
				var gotab_prev_cont;
				var gotab_cur;
				var gotab_cur_cont;
				if (tab_prev_obj) {
					gotab_cur = objtabs.eq(tab_cur_index);
					gotab_cur
						.removeClass('uifm-current')
						.removeClass('uifm-complete')
						.addClass('uifm-disabled');

					gotab_cur_cont = $(gotab_cur)
						.find('a')
						.attr('data-tab-href');
					objform.find(gotab_cur_cont).hide();
					gotab_prev = objtabs.eq(tab_prev_index);
					gotab_prev
						.removeClass('uifm-disabled')
						.removeClass('uifm-complete')
						.addClass('uifm-current');

					gotab_prev_cont = $(gotab_prev)
						.find('a')
						.attr('data-tab-href');
					objform.find(gotab_prev_cont).show();
				}

				if (parseFloat(tab_cur_index) > parseFloat(tab_prev_index)) {
					var tab_prev2_obj_index = tab_prev_obj.prev().index();
					if (parseFloat(tab_prev2_obj_index) >= 0 && parseFloat(tab_prev2_obj_index) < parseFloat(tab_prev_index)) {
					} else {
						this.previewfield_removeAllPopovers();

						var wiznext_ntxt =
							objform
								.find('#uifm-step-tab-' + tab_prev_index)
								.find('.rockfm-btn-wiznext')
								.attr('data-value-next') || 'next';
						objform.find('.rockfm-btn-wiznext .rockfm-inp-lbl').html(wiznext_ntxt);
						objform.find('.rockfm-btn-wizprev').attr('disabled', 'disabled');
					}
				}

				if (String(uifmvariable.externalVars['fm_loadmode']) === 'iframe') {
					if ('parentIFrame' in window) {
						parentIFrame.size(); 
					}
				}

				$('.popover').sfdc_popover('hide');

				this.refresh_fields();
			};
			arguments.callee.payment_checkSelectedRdo = function(objwrap) {
				var $return;
				if (objwrap.find(".uiform-pg-order-cont input[type='radio']:checked").length > 0) {
					objwrap.find('.uiform-pg-content').sfdc_popover('destroy');
					$return = true;
				} else {
					objwrap
						.find('.uiform-pg-content')
						.sfdc_popover({
							animation: false,
							html: true,
							placement: 'top',
							trigger: 'manual',
							content: objwrap.find('.uifm_pg_msg_selectpay').val() || 'this is required',
						})
						.sfdc_popover('show');
					$return = false;
				}

				return $return;
			};
			arguments.callee.redirect_tourl = function(redirect) {
				if (window.event) {
					window.event.returnValue = false;
					window.location = redirect;
				} else {
					location.href = redirect;
				}
			};
			arguments.callee.payment_completebtn = function(el) {
				var objwrap = $(el).closest('.uiform-pg-main-page');
				$(document).on('change', objwrap.find(".uiform-pg-order-cont input[type='radio']"), function(e) {
					rocketfm.payment_checkSelectedRdo(objwrap);
				});

				if (rocketfm.payment_checkSelectedRdo(objwrap)) {
					var optchecked = objwrap.find(".uiform-pg-order-cont input[type='radio']:checked").first();
					var opt_type = optchecked.attr('data-type');

					switch (parseInt(opt_type)) {
						case 1:

							objwrap.find('.uiform-pg-complete-box a').prop('disabled', true);
							objwrap.find('.uiform-pg-complete-box a').html('<i class="fa fa-shopping-cart"></i> <i class="sfdc-glyphicon sfdc-glyphicon-refresh sfdc-gly-spin"></i>');
							var data = objwrap.find('.uifm_offline_form').serialize();
							$.ajax({
								type: 'POST',
								url: rockfm_vars.ajaxurl,
								dataType: 'html',
								data: data + '&action=rocket_front_saveofflinemode' + '&zgfm_security=' + rockfm_vars.ajax_nonce,
								success: function(response) {
									if (response) {
										var arrJson = (JSON && JSON.parse(response)) || $.parseJSON(response);
										if (parseInt(arrJson.success) === 1) {
											if (arrJson.return_url) {
												rocketfm.redirect_tourl(arrJson.return_url);
											} else {
												var msg = '';
												var el_form = $(el).closest('.rockfm-form-container');
												msg = decodeURIComponent(arrJson.show_message);
												el_form.find('.uiform-pg-main-page').hide();

												if (msg) {
													var tmp_msg = el_form.find('.rockfm-alert-container');
													tmp_msg.html('');
													tmp_msg.append('<div class="rockfm-alert-inner" >' + msg + '</div>');
													tmp_msg.show();

													if (String(uifmvariable.externalVars['fm_loadmode']) === 'iframe') {
														if ('parentIFrame' in window) {
															parentIFrame.scrollTo(0, tmp_msg.offset().top);
														}
													} else {
														$('html,body').animate(
															{
																scrollTop: tmp_msg.offset().top,
															},
															'slow'
														);
													}
												}
											}
										}
									}
								},
							});

							break;
						case 2:
							objwrap.find('.uifm_paypal_form').submit();
							objwrap.find('.uiform-pg-complete-box a').prop('disabled', true);
							objwrap.find('.uiform-pg-complete-box a').html('<i class="fa fa-shopping-cart"></i> <i class="sfdc-glyphicon sfdc-glyphicon-refresh gly-spin"></i>');
							break;
					}
				}
			};

			arguments.callee.modal_resizeWhenIframe = function() {
				if (String(uifmvariable.externalVars['fm_loadmode']) === 'iframe') {
					if ('parentIFrame' in window) {
						var height = $('.uiform_modal_general')
							.find('.sfdc-modal-body')
							.height();
						parentIFrame.size(parseFloat(height) + 300); 
					}
				}
			};
			arguments.callee.modal_onclose = function() {
				if (String(uifmvariable.externalVars['fm_loadmode']) === 'iframe') {
					if ('parentIFrame' in window) {
						parentIFrame.size(); 
					}
				}
			};
			arguments.callee.payment_seeSummary = function(element) {
				var main_container = $(element).closest('.rockfm-form-container');
				var form_id = main_container.find('._rockfm_form_id').val();
				var form_r_id = main_container.find('._uifm_pg_record_id').val();
				$.ajax({
					type: 'POST',
					url: rockfm_vars.ajaxurl,
					dataType: 'html',
					data: {
						action: 'rocket_front_payment_seesummary',
						zgfm_security: rockfm_vars.ajax_nonce,
						form_r_id: form_r_id,
						form_id: form_id,
					},
					success: function(response) {
						var arrJson = (JSON && JSON.parse(response)) || $.parseJSON(response);
						main_container.find('.uiform_modal_general').sfdc_modal('show');

						main_container
							.find('.uiform_modal_general')
							.find('.sfdc-modal-body')
							.html(decodeURIComponent(arrJson.show_summary));
						main_container
							.find('.uiform_modal_general')
							.find('.sfdc-modal-title')
							.html(arrJson.show_summary_title);
					},
				});
			};
			arguments.callee.payment_seeInvoice = function(element) {
				var main_container = $(element).closest('.rockfm-form-container');
				var form_id = main_container.find('._rockfm_form_id').val();
				var form_r_id = main_container.find('._uifm_pg_record_id').val();
				$.ajax({
					type: 'POST',
					url: rockfm_vars.ajaxurl,
					dataType: 'html',
					data: {
						action: 'rocket_front_payment_seeinvoice',
						zgfm_security: rockfm_vars.ajax_nonce,
						form_r_id: form_r_id,
						form_id: form_id,
					},
					success: function(response) {
						var arrJson = (JSON && JSON.parse(response)) || $.parseJSON(response);
						main_container.find('.uiform_modal_general').sfdc_modal('show');

						main_container
							.find('.uiform_modal_general')
							.find('.sfdc-modal-body')
							.html(decodeURIComponent(arrJson.show_summary));
						main_container
							.find('.uiform_modal_general')
							.find('.sfdc-modal-title')
							.html(arrJson.show_summary_title);
					},
				});
			};
			arguments.callee.genpdf_inforecord = function(rec_id) {
				try {
					$('body').append("<iframe src='" + rockfm_vars.url_site + '?uifm_costestimator_api_handler&zgfm_action=uifm_est_api_handler&uifm_action=show_record&uifm_mode=pdf&id=' + rec_id + "' style='display: none;' ></iframe>");
				} catch (ex) {
					console.error(' genpdf_inforecord : ', ex.message);
					var uifm_iframeform = function(url) {
						var object = this;
						object.time = new Date().getTime();
						object.form = $('<form action="' + url + '" target="iframe' + object.time + '" method="post" style="display:none;" id="form' + object.time + '"></form>');

						object.addParameter = function(parameter, value) {
							$("<input type='hidden' />")
								.attr('name', parameter)
								.attr('value', value)
								.appendTo(object.form);
						};

						object.send = function() {
							var iframe = $('<iframe data-time="' + object.time + '" style="display:none;" id="iframe' + object.time + '"></iframe>');
							$('body').append(iframe);
							$('body').append(object.form);
							object.form.submit();
							iframe.load(function() {
								$('#form' + $(this).data('time')).remove();
								$(this).remove();
							});
						};
					};
					var tmpSend = new uifm_iframeform(rockfm_vars.url_site + '?uifm_costestimator_api_handler&zgfm_action=uifm_est_api_handler&uifm_action=show_record&uifm_mode=pdf&id=' + rec_id);
					tmpSend.send();
				}
			};

			arguments.callee.genpdf_infoinvoice = function(rec_id) {
				try {
					$('body').append("<iframe src='" + rockfm_vars.url_site + '?uifm_costestimator_api_handler&zgfm_action=uifm_est_api_handler&uifm_action=show_invoice&uifm_mode=pdf&id=' + rec_id + "' style='display: none;' ></iframe>");
				} catch (ex) {
					console.error(' genpdf_inforecord : ', ex.message);
					var uifm_iframeform = function(url) {
						var object = this;
						object.time = new Date().getTime();
						object.form = $('<form action="' + url + '" target="iframe' + object.time + '" method="post" style="display:none;" id="form' + object.time + '"></form>');

						object.addParameter = function(parameter, value) {
							$("<input type='hidden' />")
								.attr('name', parameter)
								.attr('value', value)
								.appendTo(object.form);
						};

						object.send = function() {
							var iframe = $('<iframe data-time="' + object.time + '" style="display:none;" id="iframe' + object.time + '"></iframe>');
							$('body').append(iframe);
							$('body').append(object.form);
							object.form.submit();
							iframe.load(function() {
								$('#form' + $(this).data('time')).remove();
								$(this).remove();
							});
						};
					};
					var tmpSend = new uifm_iframeform(rockfm_vars.url_site + '?uifm_costestimator_api_handler&zgfm_action=uifm_est_api_handler&uifm_action=show_invoice&uifm_mode=pdf&id=' + rec_id);
					tmpSend.send();
				}
			};
		};
	})($uifm, window);
}

(function($) {
	var rCRLF = /\r?\n/g,
		rsubmitterTypes = /^(?:submit|button|image|reset|file)$/i,
		rsubmittable = /^(?:input|select|textarea|keygen)/i;
	var rcheckableType = /^(?:checkbox|radio)$/i;

	$.fn.getZgfmEvents = function() {
		if (typeof $._data == 'function') {
			return $._data(this.get(0), 'events') || {};
		} else if (typeof this.data == 'function') {
			return this.data('events') || {};
		}
		return {};
	};

	$.fn.removeCss = function() {
		var removedCss = $.makeArray(arguments);
		return this.each(function() {
			var e$ = $(this);
			var style = e$.attr('style');
			if (typeof style !== 'string') return;
			style = $.trim(style);
			var styles = style.split(/;+/);
			var sl = styles.length;
			for (var l = removedCss.length, i = 0; i < l; i++) {
				var r = removedCss[i];
				if (!r) continue;
				for (var j = 0; j < sl; ) {
					var sp = $.trim(styles[j]);
					if (!sp || (sp.indexOf(r) === 0 && $.trim(sp.substring(r.length)).indexOf(':') === 0)) {
						styles.splice(j, 1);
						sl--;
					} else {
						j++;
					}
				}
			}
			if (styles.length === 0) {
				e$.removeAttr('style');
			} else {
				e$.attr('style', styles.join(';'));
			}
		});
	};
	$.fn.extend({
		uifm_serialize: function() {
			return $.param(this.uifm_serializeArray());
		},
		uifm_serializeArray: function() {
			return this.map(function() {
				var elements = $.prop(this, 'elements');
				var exclude_array = [];
				var exclude_fields = $(this)
					.closest('.rockfm-form')
					.find('.rockfm-conditional-hidden :input,.rockfm-conditional-hidden select');
				exclude_array = $.map(exclude_fields, function(n, i) {
					return $(n).attr('name');
				});
				var new_elements = [];
				$.each(elements, function(i, val) {
					if (parseInt($.inArray($(val).attr('name'), exclude_array)) < 0) {
						new_elements.push(val);
					}
				});
				return new_elements ? $.makeArray(new_elements) : this;
			})
				.filter(function() {
					var type = this.type;
					return this.name && !$(this).is(':disabled') && rsubmittable.test(this.nodeName) && !rsubmitterTypes.test(type) && (this.checked || !rcheckableType.test(type));
				})
				.map(function(i, elem) {
					var val = $(this).val();
					return val == null
						? null
						: $.isArray(val)
						? $.map(val, function(val) {
								return { name: elem.name, value: val.replace(rCRLF, '\r\n') };
						  })
						: { name: elem.name, value: val.replace(rCRLF, '\r\n') };
				})
				.get();
		},
	});

	$('.uiform_modal_general').on('hidden.bs.modal', function() {
		rocketfm.modal_onclose();
	});

	$('.uiform_modal_general').on('shown.bs.modal', function() {
		rocketfm.modal_resizeWhenIframe();
	});
})($uifm);

var zgfm_recaptcha_elems = {};
var zgfm_recaptcha_onloadCallback = function() {
	var tmp_sitekey;
	var tmp_form_id;

	$uifm('.g-recaptcha').each(function(i) {
		tmp_sitekey = $uifm(this).attr('data-sitekey');
		tmp_form_id = $uifm(this)
			.closest('.rockfm-form')
			.find('._rockfm_form_id')
			.val();

		zgfm_recaptcha_elems['recaptcha_' + tmp_form_id] = grecaptcha.render('zgfm_recaptcha_obj_' + tmp_form_id, {
			sitekey: tmp_sitekey,
		});
	});
};

(function($) {
	var zgfmLogicFrm = function(element, options) {
		var cur_form_obj = $(element);
		var obj_main = this;
		var logical_fields = [];
		var fields_cond = [];
		var fields_fire = [];
		var cur_field_fire_value;
		var cur_field_fire_id;

		logical_fields = (JSON && JSON.parse(options)) || $.parseJSON(options);

		this.publicMethod = function() {};

		var privateMethod = function() {};

		var runExtraTasks = function(field) {
			var obj_form = $(field).closest('.rockfm-form');
			zgfm_front_cost.costest_refresh(obj_form);
		};

		this.setData = function() {
			this.processData();
		};

		this.processData = function() {
			fields_cond = logical_fields.cond;
			fields_fire = logical_fields.fire;
		};

		this.getValueFieldFire = function(element) {
			cur_field_fire_value = $(element).val();
		};

		this.getValueFieldById = function(id_field, input) {
			var getrow = cur_form_obj.find('#rockfm_' + id_field);
			var tmp_theme_type;
			var response = {
				value_field: null,
				input_field: null,
			};
			if (getrow) {
				var type = getrow.attr('data-typefield');
				var tempvar;
				var searchInput;
				switch (parseInt(type)) {
					case 8:

						tmp_theme_type = getrow.find('.rockfm-input2-wrap').attr('data-theme-type');

						switch (parseInt(tmp_theme_type)) {
							case 1:
								tempvar = getrow.find('.rockfm-inp2-rdo');

								searchInput = tempvar
									.map(function(index) {
										if (
											$(this)
												.parent()
												.hasClass('checked')
										) {
											return $(this).val();
										} else {
											return null;
										}
									})
									.toArray();

								response['value_field'] = searchInput[0];
								response['input_field'] = input;

								break;
							default:
								tempvar = getrow.find('.rockfm-inp2-rdo');

								searchInput = tempvar
									.map(function(index) {
										if ($(this).is(':checked')) {
											return $(this).val();
										} else {
											return null;
										}
									})
									.toArray();

								response['value_field'] = searchInput[0];
								response['input_field'] = input;
								break;
						}

						break;
					case 9:
						tmp_theme_type = getrow.find('.rockfm-input2-wrap').attr('data-theme-type');

						switch (parseInt(tmp_theme_type)) {
							case 1:
								tempvar = getrow.find('.rockfm-inp2-chk');

								searchInput = tempvar
									.map(function(index) {
										if (
											$(this)
												.parent()
												.hasClass('checked')
										) {
											return $(this).val();
										} else {
											return null;
										}
									})
									.toArray();

								var tmp_found_val = '';
								if ($.inArray(input, searchInput) != -1) {
									tmp_found_val = input;
								} else {
									tmp_found_val = '';
								}

								response['value_field'] = tmp_found_val;
								response['input_field'] = input;
								break;
							default:
								tempvar = getrow.find('.rockfm-inp2-chk');
								searchInput = tempvar
									.map(function(index) {
										if ($(this).is(':checked')) {
											return $(this).val();
										} else {
											return null;
										}
									})
									.toArray();

								response['value_field'] = searchInput;
								response['input_field'] = input;
								break;
						}

						break;
					case 41:
						tempvar = getrow.find('.uifm-dcheckbox-item-chkst');

						searchInput = tempvar
							.map(function(index) {
								if ($(this).hasClass('uifm-dcheckbox-checked')) {
									return index;
								} else {
									return null;
								}
							})
							.toArray();

						response['value_field'] = searchInput;
						response['input_field'] = input;

						break;
					case 42:
						tempvar = getrow.find('.uifm-dcheckbox-item-chkst');

						searchInput = tempvar
							.map(function(index) {
								if ($(this).hasClass('uifm-dcheckbox-checked')) {
									return index;
								} else {
									return null;
								}
							})
							.toArray();

						response['value_field'] = searchInput[0];
						response['input_field'] = input;
						break;
					case 10:
						tmp_theme_type = getrow.find('.rockfm-input2-wrap').attr('data-theme-type');

						switch (parseInt(tmp_theme_type)) {
							case 1:
								tempvar = getrow.find('.rockfm-input2-sel-styl1');
								response['value_field'] = tempvar.selectpicker('val');
								response['input_field'] = input;
								break;
							case 2:
								tempvar = getrow.find('.rockfm-input2-sel-styl2');
								response['value_field'] = tempvar.val();
								response['input_field'] = input;
								break;
							default:
								tempvar = getrow.find('.uifm-input2-opt-main');
								response['value_field'] = tempvar.val();
								response['input_field'] = input;
								break;
						}

						break;
					case 11:
						tmp_theme_type = getrow.find('.rockfm-input2-wrap').attr('data-theme-type');

						switch (parseInt(tmp_theme_type)) {
							case 1:
								tempvar = getrow.find('.rockfm-input2-sel-styl1');
								response['value_field'] = tempvar.selectpicker('val');
								response['input_field'] = input;
								break;
							case 2:
								searchInput = $.map(getrow.find('.rockfm-input2-sel-styl2 option:selected'), function(elem) {
									return $(elem).attr('value');
								});

								response['value_field'] = searchInput;
								response['input_field'] = input;
								break;
							default:
								searchInput = $.map(getrow.find('.uifm-input2-opt-main option:selected'), function(elem) {
									return $(elem).attr('value');
								});

								response['value_field'] = searchInput;
								response['input_field'] = input;
								break;
						}

						break;
					case 16:
						tempvar = getrow.find('.rockfm-input4-slider');
						response['value_field'] = tempvar.val();
						response['input_field'] = input;
						break;
					case 18:
						tempvar = getrow.find('.rockfm-input4-spinner');
						response['value_field'] = tempvar.val();
						response['input_field'] = input;
						break;
					case 40:

						var uifm_fld_value = getrow.find('.rockfm-input15-switch').bootstrapSwitchZgpb('state');
						var tmp_val = 0;
						if (uifm_fld_value) {
							tmp_val = 1;
						} else {
							tmp_val = 0;
						}
						tempvar = getrow.find('.rockfm-input15-switch');
						response['value_field'] = tmp_val;
						response['input_field'] = input;

						break;
				}
			}

			return response;
		};

		this.refreshfields = function() {
			var found = fields_cond;
			for (var i in found) {
				this.processFieldCond(found[i].field_cond);
			}
		};

		this.triggerConditional = function(element, id) {
			obj_main.refreshfields();
		};

		this.enableFields = function(element) {
			element.removeClass('rockfm-conditional-hidden');

			element.find('.rockfm-cond-hidden-children').removeClass('rockfm-cond-hidden-children');
		};

		this.disableFields = function(element) {
			element.addClass('rockfm-conditional-hidden');

			element.find('.rockfm-field').addClass('rockfm-cond-hidden-children');
		};

		this.processFieldCond = function(field_cond) {
			var getElement;
			getElement = cur_form_obj.find('#rockfm_' + field_cond);
			var foundsource = this.findFieldCond(field_cond);
			if (!foundsource) {
				return;
			}

			var required = parseInt(foundsource.req_match);
			var action = parseInt(foundsource.action);
			var list_source = foundsource.list;

			var match_count = 0;
			var fire_temp;
			var flag_firevisible;

			for (var i in list_source) {
				fire_temp = String(list_source[i].field_fire);
				if (cur_form_obj.find('#rockfm_' + fire_temp).is(':visible') || String(cur_form_obj.find('#rockfm_' + fire_temp).css('display')) === 'block') {
					flag_firevisible = true;
				} else {
					flag_firevisible = false;
				}
				if (flag_firevisible === true) {
					if (this.calculateMatchs(list_source[i].field_fire, list_source[i].minput, list_source[i].mtype) === true) {
						match_count++;
					}
				}
			}

			if (required > 0 && required <= match_count) {
				if (action === 1) {

					this.enableFields(getElement);
					getElement.show();
				} else if (action === 2) {

					this.disableFields(getElement);
					getElement.hide();
				}
			} else {
				if (action === 1) {

					this.disableFields(getElement);
					getElement.hide();
				} else if (action === 2) {

					this.enableFields(getElement);
					getElement.show();
				}
			}
		};

		this.calculateMatchs = function(field_fire, input, mtype) {
			var response;
			var fire_input = this.getValueFieldById(field_fire, input);
			switch (parseInt(mtype)) {
				case 1:
					if ($.isArray(fire_input.value_field)) {
						for (var i in fire_input.value_field) {
							if (String(fire_input.value_field[i]) === String(fire_input.input_field)) {
								response = true;
								break;
							} else {
								response = false;
							}
						}
					} else if ($.isNumeric(fire_input.value_field)) {
						if (parseFloat(fire_input.value_field) === parseFloat(fire_input.input_field)) {
							response = true;
						} else {
							response = false;
						}
					} else {
						if (String(fire_input.value_field) === String(fire_input.input_field)) {
							response = true;
						} else {
							response = false;
						}
					}

					break;
				case 2:
					if ($.isNumeric(fire_input.value_field)) {
						if (parseFloat(fire_input.value_field) != parseFloat(fire_input.input_field)) {
							response = true;
						} else {
							response = false;
						}
					} else {
						if (String(fire_input.value_field) != String(fire_input.input_field)) {
							response = true;
						} else {
							response = false;
						}
					}

					break;
				case 3:
					if (parseFloat(fire_input.value_field) >= parseFloat(fire_input.input_field)) {
						response = true;
					} else {
						response = false;
					}
					break;
				case 4:
					if (parseFloat(fire_input.value_field) <= parseFloat(fire_input.input_field)) {
						response = true;
					} else {
						response = false;
					}
					break;
			}
			return response;
		};

		this.findFieldFire = function(needle) {
			for (var i in fields_fire) {
				if (String(fields_fire[i].field_fire) === String(needle)) {
					return fields_fire[i].list;
				}
			}
		};

		this.findFieldCond = function(needle) {
			for (var i in fields_cond) {
				if (String(fields_cond[i].field_cond) === String(needle)) {
					return fields_cond[i];
				}
			}
		};
	};

	$.fn.zgfm_logicfrm = function(options) {
		return this.each(function() {
			var element = $(this);

			if (element.data('zgfm_logicfrm')) return;

			var myplugin = new zgfmLogicFrm(this, options);

			element.data('zgfm_logicfrm', myplugin);
		});
	};
})($uifm);

(function($) {
	var zgpbDataFrm = function(element, options) {
		var cur_form_obj = $(element);
		var obj = this;

		var zgfmvariable = [];
		zgfmvariable.innerVars = {};

		var form_options = {};
		var defaults = {
			submit_ajax: '1',
			add_css: '',
			add_js: '',
			onload_scroll: '0',
			preload_noconflict: '0',
			pdf_charset: 'UTF-8',
			pdf_font: '2',
		};
		if (options) {
			form_options = (JSON && JSON.parse(options)) || $.parseJSON(options);
		} else {
			form_options = {};
		}

		var settings = $.extend(true, {}, defaults, form_options);

		this.setInnerVariable = function(name, value) {
			zgfmvariable.innerVars[name] = value;
		};

		this.getInnerVariable = function(name) {
			if (zgfmvariable.innerVars[name]) {
				return zgfmvariable.innerVars[name];
			} else {
				return '';
			}
		};

		this.getData = function(name) {
			try {
				return settings[name];
			} catch (err) {
				return '';
			}
		};
		this.setData = function(name, value) {
			settings[name] = value;
		};

		this.publicMethod = function() {};

		var privateMethod = function() {};

		this.showData = function() {};
	};

	$.fn.zgpb_datafrm = function(options) {
		return this.each(function() {
			var element = $(this);

			if (element.data('zgpb_datafrm')) return;

			var myplugin = new zgpbDataFrm(this, options);

			element.data('zgpb_datafrm', myplugin);
		});
	};
})($uifm);

if (typeof $uifm === 'undefined') {
	$uifm = jQuery;
}
var zgfm_front_calc = zgfm_front_calc || null;
if (!$uifm.isFunction(zgfm_front_calc)) {
	(function($, window) {
		'use strict';

		var zgfm_front_calc = function() {
			var zgfm_variable = [];
			zgfm_variable.innerVars = {};
			zgfm_variable.externalVars = {};

			this.initialize = function() {};

			this.calc_field_get = function(form_id, field_id, action, option) {
				rocketfm.setInnerVariable('cur_form_id', form_id);
				rocketfm.setInnerVariable('cur_form_obj', $('#rockfm_form_' + form_id));

				var tmp_f_obj = $('#rockfm_form_' + form_id).find('#rockfm_' + field_id);
				var tmp_f_type = tmp_f_obj.attr('data-typefield');
				var result;

				if (tmp_f_obj.hasClass('rockfm-conditional-hidden')) {
					return 0;
				}

				switch (parseInt(tmp_f_type)) {
					case 6:
					case 7:
					case 28:
					case 29:
					case 30:
						switch (String(action)) {
							case 'value':
								result = tmp_f_obj.find('.rockfm-txtbox-inp-val').val() || '';

								switch (option) {
									case 'char':
										result = String(result);
										break;
									case 'num':
									default:
										result = parseFloat(result);
										break;
								}

								break;
						}
						break;
					case 8:
					case 9:
					case 10:
					case 11:

						var tmp_theme_type;
						switch (String(action)) {
							case 'value':
								switch (parseInt(tmp_f_type)) {
									case 10:
									case 11:
										tmp_f_obj.find('select option:selected').each(function() {
											result = $(this).attr('data-uifm-inp-val') || '';
										});
										break;
									case 8:
										tmp_f_obj.find('input[type=radio]:checked').each(function() {
											result = $(this).attr('data-uifm-inp-val') || '';
										});
										break;
									case 9:
										tmp_f_obj.find('input[type=checkbox]:checked').each(function() {
											result = $(this).attr('data-uifm-inp-val') || '';
										});

										break;
								}

								switch (option) {
									case 'char':
										result = String(result);
										break;
									case 'num':
									default:
										result = parseFloat(result);
										break;
								}
								break;
							case 'optprice':
								var tmp_field_inp;

								switch (parseInt(tmp_f_type)) {
									case 10:
									case 11:

										tmp_theme_type = tmp_f_obj.find('.rockfm-input2-wrap').attr('data-theme-type');

										switch (parseInt(tmp_theme_type)) {
											case 1:
												tmp_field_inp = tmp_f_obj.find('.rockfm-input2-sel-styl1');
												result = tmp_field_inp.find('select [data-opt-index="' + option + '"]').attr('data-uifm-inp-price');
												break;
											case 2:
												tmp_field_inp = tmp_f_obj.find('.rockfm-input2-sel-styl2');
												result = tmp_field_inp.find('select [data-opt-index="' + option + '"]').attr('data-uifm-inp-price');
												break;
											default:
												result = tmp_f_obj.find('.uifm-input2-opt-main [data-opt-index="' + option + '"]').attr('data-uifm-inp-price');
												break;
										}

										break;
									case 8:
									case 9:
										result = tmp_f_obj
											.find('.rockfm-input2-wrap [data-opt-index="' + option + '"]')
											.find('input')
											.attr('data-uifm-inp-price');
										break;
								}

								result = parseFloat(result);
								break;
							case 'price':
								tmp_theme_type = tmp_f_obj.find('.rockfm-input2-wrap').attr('data-theme-type');

								var uifm_fld_price = 0;
								var price_sum = 0;
								switch (parseInt(tmp_f_type)) {
									case 8:
										tmp_f_obj.find('input[type=radio]:checked').each(function() {
											uifm_fld_price = $(this).attr('data-uifm-inp-price') || 0;
											price_sum += parseFloat(uifm_fld_price);
										});
										break;
									case 9:
										tmp_f_obj.find('input[type=checkbox]:checked').each(function() {
											uifm_fld_price = $(this).attr('data-uifm-inp-price') || 0;
											price_sum += parseFloat(uifm_fld_price);
										});

										break;

									case 10:
									case 11:
										tmp_f_obj.find('select option:selected').each(function() {
											uifm_fld_price = $(this).attr('data-uifm-inp-price') || 0;

											price_sum += parseFloat(uifm_fld_price);
										});
										break;
								}

								result = parseFloat(price_sum);
								break;

							case 'optIsChecked':
								tmp_theme_type = tmp_f_obj.find('.rockfm-input2-wrap').attr('data-theme-type');
								var tmp_ischecked = false;

								switch (parseInt(tmp_f_type)) {
									case 10:
									case 11:
										tmp_theme_type = tmp_f_obj.find('.rockfm-input2-wrap').attr('data-theme-type');

										switch (parseInt(tmp_theme_type)) {
											case 1:
												tmp_field_inp = tmp_f_obj.find('.rockfm-input2-sel-styl1');

												tmp_field_inp.find('select [data-opt-index="' + option + '"]:selected').each(function() {
													tmp_ischecked = true;
												});

												break;
											case 2:
												tmp_field_inp = tmp_f_obj.find('.rockfm-input2-sel-styl2');

												tmp_field_inp.find('select [data-opt-index="' + option + '"]:selected').each(function() {
													tmp_ischecked = true;
												});

												break;
											default:
												tmp_f_obj.find('.rockfm-input2-wrap select [data-opt-index="' + option + '"]:selected').each(function() {
													tmp_ischecked = true;
												});

												break;
										}
										break;
									case 8:
										tmp_theme_type = tmp_f_obj.find('.rockfm-input2-wrap').attr('data-theme-type');
										switch (parseInt(tmp_theme_type)) {
											case 1:
												tmp_f_obj
													.find('.rockfm-input-container [data-opt-index="' + option + '"]')
													.find('.checked')
													.each(function() {
														tmp_ischecked = true;
													});
												break;
											default:
												tmp_f_obj
													.find('.rockfm-input-container [data-opt-index="' + option + '"]')
													.find('input[type=radio]:checked')
													.each(function() {
														tmp_ischecked = true;
													});
												break;
										}
										break;
									case 9:
										tmp_theme_type = tmp_f_obj.find('.rockfm-input2-wrap').attr('data-theme-type');
										switch (parseInt(tmp_theme_type)) {
											case 1:
												tmp_f_obj
													.find('.rockfm-input-container [data-opt-index="' + option + '"]')
													.find('.checked')
													.each(function() {
														tmp_ischecked = true;
													});
												break;
											default:
												tmp_f_obj
													.find('.rockfm-input-container [data-opt-index="' + option + '"]')
													.find('input[type=checkbox]:checked')
													.each(function() {
														tmp_ischecked = true;
													});
												break;
										}

										break;
								}

								result = tmp_ischecked;

								break;
							case 'optIsUnchecked':
								tmp_theme_type = tmp_f_obj.find('.rockfm-input2-wrap').attr('data-theme-type');

								var tmp_ischecked = false;

								switch (parseInt(tmp_f_type)) {
									case 10:
									case 11:
										tmp_theme_type = tmp_f_obj.find('.rockfm-input2-wrap').attr('data-theme-type');

										switch (parseInt(tmp_theme_type)) {
											case 1:
												tmp_field_inp = tmp_f_obj.find('.rockfm-input2-sel-styl1');

												tmp_field_inp.find('select [data-opt-index="' + option + '"]:selected').each(function() {
													tmp_ischecked = true;
												});

												break;
											case 2:
												tmp_field_inp = tmp_f_obj.find('.rockfm-input2-sel-styl2');

												tmp_field_inp.find('select [data-opt-index="' + option + '"]:selected').each(function() {
													tmp_ischecked = true;
												});

												break;
											default:
												tmp_f_obj.find('.rockfm-input2-wrap select [data-opt-index="' + option + '"]:selected').each(function() {
													tmp_ischecked = true;
												});

												break;
										}

										break;
									case 8:
										tmp_theme_type = tmp_f_obj.find('.rockfm-input2-wrap').attr('data-theme-type');
										switch (parseInt(tmp_theme_type)) {
											case 1:
												tmp_f_obj
													.find('.rockfm-input-container [data-opt-index="' + option + '"]')
													.find('.checked')
													.each(function() {
														tmp_ischecked = true;
													});
												break;
											default:
												tmp_f_obj
													.find('.rockfm-input-container [data-opt-index="' + option + '"]')
													.find('input[type=radio]:checked')
													.each(function() {
														tmp_ischecked = true;
													});
												break;
										}
										break;
									case 9:
										tmp_theme_type = tmp_f_obj.find('.rockfm-input2-wrap').attr('data-theme-type');
										switch (parseInt(tmp_theme_type)) {
											case 1:
												tmp_f_obj
													.find('.rockfm-input-container [data-opt-index="' + option + '"]')
													.find('.checked')
													.each(function() {
														tmp_ischecked = true;
													});
												break;
											default:
												tmp_f_obj
													.find('.rockfm-input-container [data-opt-index="' + option + '"]')
													.find('input[type=checkbox]:checked')
													.each(function() {
														tmp_ischecked = true;
													});
												break;
										}

										break;
								}

								if (tmp_ischecked) {
									result = false;
								} else {
									result = true;
								}
								break;
							case 'isChecked':
								tmp_theme_type = tmp_f_obj.find('.rockfm-input2-wrap').attr('data-theme-type');

								var tmp_ischecked = false;

								switch (parseInt(tmp_f_type)) {
									case 10:
									case 11:
										tmp_theme_type = tmp_f_obj.find('.rockfm-input2-wrap').attr('data-theme-type');
										switch (parseInt(tmp_theme_type)) {
											case 1:

											default:
												tmp_f_obj.find('.rockfm-input2-wrap option:checked').each(function() {
													tmp_ischecked = true;
												});
												break;
										}
										break;
									case 8:
										tmp_theme_type = tmp_f_obj.find('.rockfm-input2-wrap').attr('data-theme-type');
										switch (parseInt(tmp_theme_type)) {
											case 1:
												var tempvar = tmp_f_obj.find('.rockfm-inp2-rdo');
												var searchInput = tempvar
													.map(function(index) {
														if (
															$(this)
																.parent()
																.hasClass('checked')
														) {
															return index;
														} else {
															return null;
														}
													})
													.toArray();

												if (searchInput.length) {
													tmp_ischecked = true;
												}

												break;
											default:
												tmp_f_obj.find('.rockfm-inp2-rdo:checked').each(function() {
													tmp_ischecked = true;
												});
												break;
										}
										break;
									case 9:
										tmp_theme_type = tmp_f_obj.find('.rockfm-input2-wrap').attr('data-theme-type');
										switch (parseInt(tmp_theme_type)) {
											case 1:
												var tempvar = tmp_f_obj.find('.rockfm-inp2-chk');
												var searchInput = tempvar
													.map(function(index) {
														if (
															$(this)
																.parent()
																.hasClass('checked')
														) {
															return index;
														} else {
															return null;
														}
													})
													.toArray();

												if (searchInput.length) {
													tmp_ischecked = true;
												}
												break;
											default:
												tmp_f_obj.find('.rockfm-inp2-chk:checked').each(function() {
													tmp_ischecked = true;
												});
												break;
										}

										break;
								}

								result = tmp_ischecked;

								break;
							case 'isUnchecked':
								tmp_theme_type = tmp_f_obj.find('.rockfm-input2-wrap').attr('data-theme-type');

								var tmp_ischecked = false;

								switch (parseInt(tmp_f_type)) {
									case 10:
									case 11:

										break;
									case 8:
										tmp_theme_type = tmp_f_obj.find('.rockfm-input2-wrap').attr('data-theme-type');
										switch (parseInt(tmp_theme_type)) {
											case 1:
												var tempvar = tmp_f_obj.find('.rockfm-inp2-rdo');
												var searchInput = tempvar
													.map(function(index) {
														if (
															$(this)
																.parent()
																.hasClass('checked')
														) {
															return index;
														} else {
															return null;
														}
													})
													.toArray();

												if (searchInput.length) {
													tmp_ischecked = true;
												}

												break;
											default:
												tmp_f_obj.find('.rockfm-inp2-rdo:checked').each(function() {
													tmp_ischecked = true;
												});
												break;
										}
										break;
									case 9:
										tmp_theme_type = tmp_f_obj.find('.rockfm-input2-wrap').attr('data-theme-type');
										switch (parseInt(tmp_theme_type)) {
											case 1:
												var tempvar = tmp_f_obj.find('.rockfm-inp2-chk');
												var searchInput = tempvar
													.map(function(index) {
														if (
															$(this)
																.parent()
																.hasClass('checked')
														) {
															return index;
														} else {
															return null;
														}
													})
													.toArray();

												if (searchInput.length) {
													tmp_ischecked = true;
												}
												break;
											default:
												tmp_f_obj.find('.rockfm-inp2-chk:checked').each(function() {
													tmp_ischecked = true;
												});
												break;
										}

										break;
								}

								if (tmp_ischecked) {
									result = false;
								} else {
									result = true;
								}
								break;
						}
						break;
					case 41:
					case 42:

						switch (String(action)) {
							case 'optprice':
								result = tmp_f_obj.find('.rockfm-input17-wrap [data-inp17-opt-index="' + option + '"]').attr('data-opt-price');
								result = parseFloat(result);
								break;
							case 'price':
								var uifm_fld_price = 0;
								var price_sum = 0;
								tmp_f_obj.find('.rockfm-input-container input[type=checkbox]:checked').each(function() {
									switch (parseInt(tmp_f_type)) {
										case 41:
											uifm_fld_price = $(this)
												.closest('.uifm-dcheckbox-item')
												.uiformDCheckbox('get_totalCost');
											break;
										case 42:
											uifm_fld_price = $(this)
												.closest('.uifm-dradiobtn-item')
												.uiformDCheckbox('get_totalCost');
											break;
									}

									price_sum += parseFloat(uifm_fld_price);
								});
								result = parseFloat(price_sum);
								break;
							case 'optIsChecked':
								var tmp_ischecked = false;
								tmp_f_obj
									.find('.rockfm-input-container [data-inp17-opt-index="' + option + '"]')
									.find('input[type=checkbox]:checked')
									.each(function() {
										tmp_ischecked = true;
									});

								result = tmp_ischecked;

								break;
							case 'optIsUnchecked':
								var tmp_ischecked = false;
								tmp_f_obj
									.find('.rockfm-input-container [data-inp17-opt-index="' + option + '"]')
									.find('input[type=checkbox]:checked')
									.each(function() {
										tmp_ischecked = true;
									});

								if (tmp_ischecked) {
									result = false;
								} else {
									result = true;
								}
								break;
							case 'isChecked':
								var tmp_ischecked = false;
								tmp_f_obj.find('.rockfm-input-container input[type=checkbox]:checked').each(function() {
									tmp_ischecked = true;
								});
								result = tmp_ischecked;
								break;
							case 'isUnchecked':
								var tmp_ischecked = false;
								tmp_f_obj.find('.rockfm-input-container input[type=checkbox]:checked').each(function() {
									tmp_ischecked = true;
								});

								if (tmp_ischecked) {
									result = false;
								} else {
									result = true;
								}
								break;
						}

						break;
					case 16:
						switch (String(action)) {
							case 'value':
								result = tmp_f_obj.find('.rockfm-input4-slider').bootstrapSlider('getValue') || 0;
								result = parseFloat(result);

								break;
							case 'price':
								var tmp_price = tmp_f_obj.find('.rockfm-input4-slider').attr('data-uifm-inp-price') || 0;
								var tmp_value = tmp_f_obj.find('.rockfm-input4-slider').bootstrapSlider('getValue');
								result = parseFloat(tmp_value) * parseFloat(tmp_price);
								result = parseFloat(result);

								break;
						}
						break;
					case 18:

						switch (String(action)) {
							case 'value':
								result = tmp_f_obj.find('.rockfm-input4-spinner').val() || 0;
								result = parseFloat(result);

								break;
							case 'price':
								var tmp_price = tmp_f_obj.find('.rockfm-input4-spinner').attr('data-uifm-inp-price') || 0;
								var tmp_value = tmp_f_obj.find('.rockfm-input4-spinner').val();
								result = parseFloat(tmp_value) * parseFloat(tmp_price);
								result = parseFloat(result);

								break;
						}

						break;
					case 24:
						switch (String(action)) {
							case 'value':
								try {
									var result_tmp = tmp_f_obj
										.find('.rockfm-input7-datepic')
										.data('DateTimePicker')
										.date()
										.toDate();
									result = result_tmp.getMonth() + 1 + '/' + result_tmp.getDate() + '/' + result_tmp.getFullYear();
								} catch (err) {
									result = '';
								}
								break;
						}
						break;

					case 26:
						switch (String(action)) {
							case 'value':
								try {
									var result_tmp = tmp_f_obj
										.find('.rockfm-input7-datetimepic')
										.data('DateTimePicker')
										.date()
										.toDate();
									result = result_tmp.getMonth() + 1 + '/' + result_tmp.getDate() + '/' + result_tmp.getFullYear() + ' ' + result_tmp.getHours() + ':' + result_tmp.getMinutes() + ':' + result_tmp.getSeconds();
								} catch (err) {
									result = '';
								}

								break;
						}

						break;
					case 40:
						switch (String(action)) {
							case 'value':
								if (tmp_f_obj.find('.rockfm-input15-switch').bootstrapSwitchZgpb('state')) {
									result = 1;
								} else {
									result = 0;
								}
								result = parseFloat(result);

								break;
							case 'price':
								var tmp_price;
								if (tmp_f_obj.find('.rockfm-input15-switch').bootstrapSwitchZgpb('state')) {
									result = tmp_f_obj.find('.rockfm-input15-switch').attr('data-uifm-inp-price') || 0;
								} else {
									result = 0;
								}
								result = parseFloat(result);

								break;
						}
						break;
					default:
						result = '';
				}

				return result;
			};

			this.costest_calc_math_process = function(obj_form) {
				var tmp_mathcalc_enable = obj_form.find('._rockfm_form_calc_math_enable').val();
				if (parseInt(tmp_mathcalc_enable) === 1) {
					var tmp_var_0;
					tmp_var_0 = zgfm_front_calc.costest_calc_getTotal(obj_form);

					rocketfm.setInnerVariable('calc_cur_total', tmp_var_0);
				}

				zgfm_front_calc.costest_calc_output(obj_form);
			};

			this.costest_calc_getTotal = function(obj_form) {
				var tmp_form_id = obj_form.find('._rockfm_form_id').val();


				var tmp_vars_str = _zgfm_front_vars.form[tmp_form_id]['calc']['vars_str'];

				var tmp_total_cost = 0;
				var function_name;
				var tmp_var_val;
				var function_name_obj;
				var tmp_vars_arr = tmp_vars_str.split(',');


				for (var i in tmp_vars_arr) {
					function_name = 'zgfm_' + String(tmp_form_id) + '_calculation_cont' + tmp_vars_arr[i];

					function_name_obj = window[function_name];

					tmp_var_val = function_name_obj();


					if (String(tmp_vars_arr[i]) === '0') {
						tmp_total_cost = tmp_var_val;

						obj_form.find('.zgfm-f-calc-var' + tmp_vars_arr[i] + '-lbl').html(zgfm_front_cost.format_money(obj_form, tmp_var_val));
					} else {
						if (isNaN(tmp_var_val)) {
							obj_form.find('.zgfm-f-calc-var' + tmp_vars_arr[i] + '-lbl').html(tmp_var_val);
						} else {
							obj_form.find('.zgfm-f-calc-var' + tmp_vars_arr[i] + '-lbl').html(zgfm_front_cost.format_money(obj_form, tmp_var_val));
						}
					}

					obj_form.find('._zgfm_avars_calc_' + tmp_vars_arr[i]).val(tmp_var_val);
				}

				return tmp_total_cost;
			};

			this.costest_calc_output = function(obj_form) {
				var tmp_total = rocketfm.getInnerVariable('calc_cur_total');

				if (parseInt(obj_form.attr('data-zgfm-price-tax-st')) === 1 && obj_form.find('.uiform-stickybox-tax').length) {
					obj_form.find('.uiform-stickybox-subtotal').html(zgfm_front_cost.format_money(obj_form, tmp_total));
					var tmp_tax = parseFloat(obj_form.attr('data-zgfm-price-tax-val'));
					var tmp_tax_val = (tmp_tax / 100) * parseFloat(tmp_total);
					obj_form.find('.uiform-stickybox-tax').html(zgfm_front_cost.format_money(obj_form, tmp_tax_val));
					obj_form.find('.uiform-stickybox-total').html(zgfm_front_cost.format_money(obj_form, tmp_tax_val + tmp_total));
				} else {
					obj_form.find('.uiform-stickybox-total').html(zgfm_front_cost.format_money(obj_form, tmp_total));
				}
			};
		};
		window.zgfm_front_calc = zgfm_front_calc = $.zgfm_front_calc = new zgfm_front_calc();
	})($uifm, window);
}

if (typeof $uifm === 'undefined') {
	$uifm = jQuery;
}
var zgfm_front_cost = zgfm_front_cost || null;
if (!$uifm.isFunction(zgfm_front_cost)) {
	(function($, window) {
		'use strict';

		var zgfm_front_cost = function() {
			var zgfm_variable = [];
			zgfm_variable.innerVars = {};
			zgfm_variable.externalVars = {};

			this.initialize = function() {};

			this.costest_sticky_init = function(obj_form) {
				var sm_pos_lbl = obj_form.find('.uiform-sticky-sidebar-box').attr('data-sticky-pos'),
					sm_box_sd_width = obj_form.find('.uiform-sticky-sidebar-box').attr('data-sticky-width') || 400;

				obj_form.find('.uiform-sticky-sidebar-box').uiform_stickybox({
					enable: 1,
					orientation: sm_pos_lbl,
					form_container: obj_form.find('.uiform-main-form'),
					main_container: obj_form.closest('.rockfm-form-container'),
					sticky: {
						width: sm_box_sd_width,
						height: '200',
					},
					resp_orientation: 1,
					backend: 0,
				});
				if (obj_form.find('.rockfm-costest-field').length) {
					zgfm_front_cost.costest_fillSticky(obj_form);
				}

				if (obj_form.find('.uiform-stickybox-inp-price').length) {
					var rockfm_tmp_price = obj_form.find('.uiform-stickybox-inp-price');

					rockfm_tmp_price.each(function(i) {
						$(this).html(zgfm_front_cost.format_money(obj_form, $(this).html()));
					});
				}
			};

			this.costest_listenEvents = function(obj_form) {
				var objsToSearch = obj_form.find('.rockfm-costest-field');
			};
			this.costest_summbox_linkPopUp = function(element) {
				var el = $(element);
				var main_container = el.closest('.rockfm-form-container');

				main_container.find('.uiform_modal_general').sfdc_modal('show');

				var result;
				var obj_form = el.closest('.rockfm-form');
				result = zgfm_front_cost.costest_fillSummBox(obj_form, true);

				var str_content = '';

				var tmp_heading = obj_form.find('.uiform-sticky-sidebar-box-content').clone();
				tmp_heading.find('.uiform-stickybox-summary').after("<div class='space10'></div>");
				tmp_heading.find('.uiform-stickybox-summary').remove();
				tmp_heading.find('.uiform-stickybox-summary-link').remove();
				tmp_heading.find('p:first').css('font-weight', 'bold');
				str_content += tmp_heading.html();
				str_content += result[0];
				main_container
					.find('.uiform_modal_general')
					.find('.sfdc-modal-body')
					.html('<div id="rockfm_show_summary_link">' + str_content + '</div>');
				main_container
					.find('.uiform_modal_general')
					.find('.sfdc-modal-title')
					.html(main_container.find('._rockfm_sticky_cpt_modal_title').val());
			};

			this.format_money = function(obj_form, amount) {
				var tmp_amount;
				var obj_form_id = obj_form.find('._rockfm_form_id').val();
				var tmp_cur_format_st, tmp_cur_decimal, tmp_cur_thousand, tmp_cur_precision;
				tmp_cur_format_st = rocketfm.getInnerVariable_byform(obj_form_id, 'price_format_st');

				tmp_cur_decimal = rocketfm.getInnerVariable_byform(obj_form_id, 'price_sep_decimal');
				tmp_cur_thousand = rocketfm.getInnerVariable_byform(obj_form_id, 'price_sep_thousand');
				tmp_cur_precision = rocketfm.getInnerVariable_byform(obj_form_id, 'price_sep_precision');

				if (parseInt(tmp_cur_format_st) === 1) {
					tmp_amount = accounting.formatMoney(amount, '', parseInt(tmp_cur_precision), tmp_cur_thousand, tmp_cur_decimal);
				} else {
					tmp_amount = parseFloat(amount);
				}

				return tmp_amount;
			};

			this.costest_fillSticky = function(obj_form) {
				var result;
				result = zgfm_front_cost.costest_fillSummBox(obj_form, false);

				var tmp_total = result[1];
				rocketfm.setInnerVariable('calc_cur_total', tmp_total);

				obj_form.find('.uiform-stickybox-summary-list').html(result[0]);


				if (obj_form.find('.uiform-stickybox-summary').length) {
					if (result[2] >= result[3]) {
						obj_form.find('.uiform-stickybox-summary-link').show();
					} else {
						obj_form.find('.uiform-stickybox-summary-link').hide();
					}
				} else {
					obj_form.find('.uiform-stickybox-summary-link').show();
				}

				zgfm_front_calc.costest_calc_math_process(obj_form);
			};

			this.costest_refresh = function(obj_form) {

				zgfm_front_cost.costest_fillSticky(obj_form);

				if (obj_form.find('.uiform-sticky-sidebar-box').length && parseInt(obj_form.find('._rockfm_sticky_st').val()) === 1) {
				} else {
					zgfm_front_calc.costest_calc_math_process(obj_form);
				}

				this.variables_refreshOnFront(obj_form);
			};

			this.variables_refreshOnFront = function(obj_form) {
				var tmp_f_arr = $('.zgfm-recfvar-obj');
				var tmp_f_obj, tmp_f_obj_type, tmp_f_atr1;
				var tmp_output, tmp_uifm_fld_price;
				if (tmp_f_arr.length) {
					$.each(tmp_f_arr, function(key, value) {
						tmp_f_obj = $('#rockfm_' + $(this).attr('data-zgfm-id'));
						tmp_f_obj_type = parseInt(tmp_f_obj.attr('data-typefield'));
						tmp_f_atr1 = parseInt($(this).attr('data-zgfm-atr'));

						switch (tmp_f_obj_type) {
							case 6:
							case 7:
							case 28:
							case 29:
							case 30:
								switch (tmp_f_atr1) {
									case 1:
										tmp_output = tmp_f_obj.find('.rockfm-txtbox-inp-val').val();
										break;
								}
								break;
							case 10:
							case 11:

								switch (tmp_f_atr1) {
									case 0:
										tmp_output = tmp_f_obj.find('.rockfm-label').html();

										break;
									case 1:
										tmp_uifm_fld_price = [];
										if (tmp_f_obj.find('select option:selected').length) {
											tmp_f_obj.find('select option:selected').each(function() {
												let uifm_fld_price = $(this).attr('data-uifm-inp-val') || 0;
												tmp_uifm_fld_price.push(uifm_fld_price);
											});
										}

										tmp_output = tmp_uifm_fld_price.join(',');
										break;
									case 2:
										tmp_uifm_fld_price = 0;
										if (tmp_f_obj.find('select option:selected').length) {
											tmp_f_obj.find('select option:selected').each(function() {
												let uifm_fld_price = $(this).attr('data-uifm-inp-price') || 0;
												tmp_uifm_fld_price += parseFloat(uifm_fld_price);
											});
										}

										tmp_output = zgfm_front_cost.format_money(obj_form, tmp_uifm_fld_price);
										break;
								}
								break;
							case 8:

								switch (tmp_f_atr1) {
									case 0:
										tmp_output = tmp_f_obj.find('.rockfm-label').html();

										break;
									case 1:
										tmp_uifm_fld_price = [];
										if (tmp_f_obj.find('input[type=radio]:checked').length) {
											tmp_f_obj.find('input[type=radio]:checked').each(function() {
												let uifm_fld_price = $(this).attr('data-uifm-inp-val') || 0;
												tmp_uifm_fld_price.push(uifm_fld_price);
											});
										}

										tmp_output = tmp_uifm_fld_price.join(',');
										break;
									case 2:
										tmp_uifm_fld_price = 0;
										if (tmp_f_obj.find('input[type=radio]:checked').length) {
											tmp_f_obj.find('input[type=radio]:checked').each(function() {
												let uifm_fld_price = $(this).attr('data-uifm-inp-price') || 0;
												tmp_uifm_fld_price += parseFloat(uifm_fld_price);
											});
										}

										tmp_output = zgfm_front_cost.format_money(obj_form, tmp_uifm_fld_price);
										break;
								}
								break;
							case 9:

								switch (tmp_f_atr1) {
									case 0:
										tmp_output = tmp_f_obj.find('.rockfm-label').html();

										break;
									case 1:
										tmp_uifm_fld_price = [];
										if (tmp_f_obj.find('input[type=checkbox]:checked').length) {
											tmp_f_obj.find('input[type=checkbox]:checked').each(function() {
												let uifm_fld_price = $(this).attr('data-uifm-inp-val') || 0;
												tmp_uifm_fld_price.push(uifm_fld_price);
											});
										}

										tmp_output = tmp_uifm_fld_price.join(',');
										break;
									case 2:
										tmp_uifm_fld_price = 0;
										if (tmp_f_obj.find('input[type=checkbox]:checked').length) {
											tmp_f_obj.find('input[type=checkbox]:checked').each(function() {
												let uifm_fld_price = $(this).attr('data-uifm-inp-price') || 0;
												tmp_uifm_fld_price += parseFloat(uifm_fld_price);
											});
										}

										tmp_output = zgfm_front_cost.format_money(obj_form, tmp_uifm_fld_price);
										break;
								}
								break;
							case 16:

								switch (tmp_f_atr1) {
									case 0:
										tmp_output = tmp_f_obj.find('.rockfm-label').html();

										break;
									case 1:

										tmp_output = tmp_f_obj.find('.rockfm-input4-slider').bootstrapSlider('getValue');

										break;
									case 2:
										let uifm_fld_value = tmp_f_obj.find('.rockfm-input4-slider').bootstrapSlider('getValue');
										let uifm_fld_price = tmp_f_obj.find('.rockfm-input4-slider').attr('data-uifm-inp-price') || 0;
										tmp_output = parseFloat(uifm_fld_value) * parseFloat(uifm_fld_price);

										break;
								}
								break;
							case 18:

								switch (tmp_f_atr1) {
									case 0:
										tmp_output = tmp_f_obj.find('.rockfm-label').html();

										break;
									case 1:

										tmp_output = tmp_f_obj.find('.rockfm-input4-spinner').val();

										break;
									case 2:
										let uifm_fld_value = tmp_f_obj.find('.rockfm-input4-spinner').val();
										let uifm_fld_price = tmp_f_obj.find('.rockfm-input4-spinner').attr('data-uifm-inp-price') || 0;
										tmp_output = parseFloat(uifm_fld_value) * parseFloat(uifm_fld_price);

										break;
								}
								break;
							case 40:

								switch (tmp_f_atr1) {
									case 0:
										tmp_output = tmp_f_obj.find('.rockfm-label').html();

										break;
									case 1:

										tmp_output = tmp_f_obj.find('.rockfm-input15-switch').bootstrapSwitchZgpb('state');
										if (tmp_output) {
											tmp_output = 1;
										} else {
											tmp_output = 0;
										}
										break;
									case 2:
										tmp_output = tmp_f_obj.find('.rockfm-input15-switch').bootstrapSwitchZgpb('state');
										if (tmp_output) {
											tmp_output = tmp_f_obj.find('.rockfm-input15-switch').attr('data-uifm-inp-price') || 0;
										} else {
											tmp_output = 0;
										}

										break;
								}
								break;
							default:
								switch (tmp_f_atr1) {
									case 0:
										tmp_output = tmp_f_obj.find('.rockfm-label').html();

										break;
									case 1:

										if (tmp_f_obj.find('input').length) {
											tmp_output = tmp_f_obj.find('input').val();
										}

										if (tmp_f_obj.find('textarea').length) {
											tmp_output = tmp_f_obj.find('textarea').val();
										}

										break;
								}
								break;
						}

						$(this).html(tmp_output);
					});
				}
			};

			this.costest_removetags = function(obj) {
				var $dictionable = obj.clone();
				$dictionable.find('a').remove(); 
				$dictionable.find('div').remove(); 
				return $dictionable.text(); 
			};

			this.costest_fillSummBox = function(obj_form, show_all_rows) {
				var price_sum = 0,
					uifm_fld_price,
					uifm_fld_type,
					uifm_summ_list = '';


				var zgfm_data_main = rocketfm.getInnerVariable('_data_main');

				var uifm_price_symbol = decodeURIComponent(zgfm_data_main['price_currency_symbol']) || '';
				var uifm_price_code = zgfm_data_main['price_currency'] || '';

				var tmp_uifm_summ_list = '';
				var tmp_uifm_summ_list_inner;

				var tmp_uifm_summ_row_count = 0;
				var uifm_summ_row_total = parseInt(obj_form.find('._rockfm_shortcode_summ_data').attr('data-zgfm-rows')) || 5;

				var zgfm_hide_cur_code = parseInt(obj_form.find('._rockfm_shortcode_summ_data').attr('data-zgfm-hidecurcode')) || 0;
				if (zgfm_hide_cur_code === 1) {
					uifm_price_code = '';
				}

				var zgfm_hide_cur_symbol = parseInt(obj_form.find('._rockfm_shortcode_summ_data').attr('data-zgfm-hidecursymbol')) || 0;

				if (zgfm_hide_cur_symbol === 1) {
					uifm_price_symbol = '';
				}

				uifm_price_symbol = uifm_price_symbol + ' ';
				uifm_price_code = ' ' + uifm_price_code;

				var tmp_uifm_fld_price;
				var uifm_fld_value;
				var uifm_fld_sub_total;
				obj_form.find('.rockfm-costest-field:not(.rockfm-conditional-hidden)').each(function() {
					uifm_fld_type = $(this).attr('data-typefield');
					switch (parseInt(uifm_fld_type)) {
						case 8:
							if ($(this).find('input[type=radio]:checked').length) {
								tmp_uifm_summ_list = '';
								if ($(this).find('.rockfm-label').length && String(zgfm_front_cost.costest_removetags($(this).find('.rockfm-label'))).replace(/ /g, '').length > 0) {
									tmp_uifm_summ_list += '<span class="uiform-sbox-summ-fld-title">' + zgfm_front_cost.costest_removetags($(this).find('.rockfm-label')) + ': </span>';
								} else if (
									String(
										$(this)
											.find('.rockfm-fld-data-field_name')
											.html()
									).length > 0
								) {
									tmp_uifm_summ_list +=
										'<span class="uiform-sbox-summ-fld-title">' +
										$(this)
											.find('.rockfm-fld-data-field_name')
											.html() +
										': </span>';
								}
								tmp_uifm_summ_list += '<span class="uiform-sbox-summ-fld-row">';
								tmp_uifm_summ_list += '<ul>';
								tmp_uifm_summ_list_inner = '';
								$(this)
									.find('input[type=radio]:checked')
									.each(function() {
										uifm_fld_price = $(this).attr('data-uifm-inp-price') || 0;
										price_sum += parseFloat(uifm_fld_price);
										tmp_uifm_summ_list_inner += '<li>' + $(this).attr('data-uifm-inp-label');
										if (parseFloat(uifm_fld_price) > 0) {
											tmp_uifm_summ_list_inner += ' : <div class="uifm-sbox-summ-fld-symbol">' + uifm_price_symbol + '</div><div class="uifm-sbox-summ-fld-price">' + zgfm_front_cost.format_money(obj_form, uifm_fld_price) + '</div><div class="uifm-sbox-summ-fld-pricecode"> ' + uifm_price_code + '</div>';
										}
										tmp_uifm_summ_list_inner += '</li>';
									});
								tmp_uifm_summ_list += tmp_uifm_summ_list_inner;
								tmp_uifm_summ_list += '</ul>';
								tmp_uifm_summ_list += '</span>';

								if (show_all_rows || tmp_uifm_summ_row_count < uifm_summ_row_total) {
									uifm_summ_list += tmp_uifm_summ_list;
								}

								if (tmp_uifm_summ_row_count < uifm_summ_row_total) {
									tmp_uifm_summ_row_count++;
								}
							}
							break;
						case 9:
							if ($(this).find('input[type=checkbox]:checked').length) {
								tmp_uifm_summ_list = '';
								if ($(this).find('.rockfm-label').length && String(zgfm_front_cost.costest_removetags($(this).find('.rockfm-label'))).replace(/ /g, '').length > 0) {
									tmp_uifm_summ_list += '<span class="uiform-sbox-summ-fld-title">' + zgfm_front_cost.costest_removetags($(this).find('.rockfm-label')) + ': </span>';
								} else if (
									String(
										$(this)
											.find('.rockfm-fld-data-field_name')
											.html()
									).length > 0
								) {
									tmp_uifm_summ_list +=
										'<span class="uiform-sbox-summ-fld-title">' +
										$(this)
											.find('.rockfm-fld-data-field_name')
											.html() +
										': </span>';
								}
								tmp_uifm_summ_list += '<span class="uiform-sbox-summ-fld-row">';
								tmp_uifm_summ_list += '<ul>';
								tmp_uifm_summ_list_inner = '';
								$(this)
									.find('input[type=checkbox]:checked')
									.each(function() {
										uifm_fld_price = $(this).attr('data-uifm-inp-price') || 0;
										price_sum += parseFloat(uifm_fld_price);
										tmp_uifm_summ_list_inner += '<li>' + $(this).attr('data-uifm-inp-label');
										if (parseFloat(uifm_fld_price) > 0) {
											tmp_uifm_summ_list_inner += ' : <div class="uifm-sbox-summ-fld-symbol">' + uifm_price_symbol + '</div><div class="uifm-sbox-summ-fld-price">' + zgfm_front_cost.format_money(obj_form, uifm_fld_price) + '</div><div class="uifm-sbox-summ-fld-pricecode"> ' + uifm_price_code + '</div>';
										}
										tmp_uifm_summ_list_inner += '</li>';
									});
								tmp_uifm_summ_list += tmp_uifm_summ_list_inner;
								tmp_uifm_summ_list += '</ul>';
								tmp_uifm_summ_list += '</span>';

								if (show_all_rows || tmp_uifm_summ_row_count < uifm_summ_row_total) {
									uifm_summ_list += tmp_uifm_summ_list;
								}

								if (tmp_uifm_summ_row_count < uifm_summ_row_total) {
									tmp_uifm_summ_row_count++;
								}
							}
							break;
						case 10:
						case 11:
							if ($(this).find('select option:selected').length) {
								tmp_uifm_summ_list = '';
								if ($(this).find('.rockfm-label').length && String(zgfm_front_cost.costest_removetags($(this).find('.rockfm-label'))).replace(/ /g, '').length > 0) {
									tmp_uifm_summ_list += '<span class="uiform-sbox-summ-fld-title">' + zgfm_front_cost.costest_removetags($(this).find('.rockfm-label')) + ': </span>';
								} else if (
									String(
										$(this)
											.find('.rockfm-fld-data-field_name')
											.html()
									).length > 0
								) {
									tmp_uifm_summ_list +=
										'<span class="uiform-sbox-summ-fld-title">' +
										$(this)
											.find('.rockfm-fld-data-field_name')
											.html() +
										': </span>';
								}
								tmp_uifm_summ_list += '<span class="uiform-sbox-summ-fld-row">';
								tmp_uifm_summ_list += '<ul>';
								tmp_uifm_summ_list_inner = '';

								tmp_uifm_fld_price = 0;

								$(this)
									.find('select option:selected')
									.each(function() {
										uifm_fld_price = $(this).attr('data-uifm-inp-price') || 0;
										price_sum += parseFloat(uifm_fld_price);
										tmp_uifm_fld_price += parseFloat(uifm_fld_price);
										tmp_uifm_summ_list_inner += '<li>' + $(this).text();
										if (parseFloat(uifm_fld_price) > 0) {
											tmp_uifm_summ_list_inner += ' : <div class="uifm-sbox-summ-fld-symbol">' + uifm_price_symbol + '</div><div class="uifm-sbox-summ-fld-price">' + zgfm_front_cost.format_money(obj_form, uifm_fld_price) + '</div><div class="uifm-sbox-summ-fld-pricecode"> ' + uifm_price_code + '</div>';
										}
										tmp_uifm_summ_list_inner += '</li>';
									});

								tmp_uifm_summ_list += tmp_uifm_summ_list_inner;
								tmp_uifm_summ_list += '</ul>';
								tmp_uifm_summ_list += '</span>';

								if (show_all_rows || tmp_uifm_summ_row_count < uifm_summ_row_total) {
									uifm_summ_list += tmp_uifm_summ_list;
								}

								if (tmp_uifm_summ_row_count < uifm_summ_row_total) {
									tmp_uifm_summ_row_count++;
								}

								$(this)
									.find('.rockfm-inp2-opt-price-lbl')
									.show();
								$(this)
									.find('.rockfm-inp2-opt-price-lbl .uiform-stickybox-inp-price')
									.html(zgfm_front_cost.format_money(obj_form, tmp_uifm_fld_price));
							} else {
								$(this)
									.find('.rockfm-inp2-opt-price-lbl')
									.hide();
							}
							break;
						case 16:
							tmp_uifm_summ_list = '';
							tmp_uifm_summ_list += '<span class="uiform-sbox-summ-fld-row">';

							tmp_uifm_summ_list_inner = '';

							tmp_uifm_fld_price = 0;

							uifm_fld_value = $(this)
								.find('.rockfm-input4-slider')
								.bootstrapSlider('getValue');

							uifm_fld_price =
								$(this)
									.find('.rockfm-input4-slider')
									.attr('data-uifm-inp-price') || 0;
							uifm_fld_sub_total = parseFloat(uifm_fld_value) * parseFloat(uifm_fld_price);
							price_sum += parseFloat(uifm_fld_sub_total);
							tmp_uifm_fld_price += parseFloat(uifm_fld_sub_total);
							if (parseFloat(uifm_fld_sub_total) > 0) {
								if ($(this).find('.rockfm-label').length && String(zgfm_front_cost.costest_removetags($(this).find('.rockfm-label'))).replace(/ /g, '').length > 0) {
									tmp_uifm_summ_list += '<span class="uiform-sbox-summ-fld-title2">' + zgfm_front_cost.costest_removetags($(this).find('.rockfm-label')) + ': </span>';
								} else if (
									String(
										$(this)
											.find('.rockfm-fld-data-field_name')
											.html()
									).length > 0
								) {
									tmp_uifm_summ_list +=
										'<span class="uiform-sbox-summ-fld-title">' +
										$(this)
											.find('.rockfm-fld-data-field_name')
											.html() +
										': </span>';
								}

								tmp_uifm_summ_list_inner += ' <span class="uiform-sbox-summ-fld-price"><ul><li>' + '  <div class="uifm-sbox-summ-fld-symbol">' + uifm_price_symbol + '</div><div class="uifm-sbox-summ-fld-price">' + zgfm_front_cost.format_money(obj_form, uifm_fld_sub_total) + '</div><div class="uifm-sbox-summ-fld-pricecode"> ' + uifm_price_code + '</div>' + '</li></ul></span>';
							}

							tmp_uifm_summ_list += tmp_uifm_summ_list_inner;
							tmp_uifm_summ_list += '</span>';

							if (show_all_rows || (parseFloat(uifm_fld_sub_total) > 0 && tmp_uifm_summ_row_count < uifm_summ_row_total)) {
								uifm_summ_list += tmp_uifm_summ_list;
							}

							if (parseFloat(uifm_fld_sub_total) > 0 && tmp_uifm_summ_row_count < uifm_summ_row_total) {
								tmp_uifm_summ_row_count++;
							}

							$(this)
								.find('.rockfm-inp4-opt-price-lbl')
								.show();
							$(this)
								.find('.rockfm-inp4-opt-price-lbl .uiform-stickybox-inp-price')
								.html(zgfm_front_cost.format_money(obj_form, tmp_uifm_fld_price));
							break;
						case 18:
							tmp_uifm_summ_list = '';
							tmp_uifm_summ_list += '<span class="uiform-sbox-summ-fld-row">';

							tmp_uifm_summ_list_inner = '';

							tmp_uifm_fld_price = 0;

							uifm_fld_value = $(this)
								.find('.rockfm-input4-spinner')
								.val();
							uifm_fld_price =
								$(this)
									.find('.rockfm-input4-spinner')
									.attr('data-uifm-inp-price') || 0;
							uifm_fld_sub_total = parseFloat(uifm_fld_value) * parseFloat(uifm_fld_price);
							price_sum += parseFloat(uifm_fld_sub_total);
							tmp_uifm_fld_price += parseFloat(uifm_fld_sub_total);
							if (parseFloat(uifm_fld_sub_total) > 0) {
								if ($(this).find('.rockfm-label').length && String(zgfm_front_cost.costest_removetags($(this).find('.rockfm-label'))).replace(/ /g, '').length > 0) {
									tmp_uifm_summ_list += '<span class="uiform-sbox-summ-fld-title2">' + zgfm_front_cost.costest_removetags($(this).find('.rockfm-label')) + ': </span>';
								} else if (
									String(
										$(this)
											.find('.rockfm-fld-data-field_name')
											.html()
									).length > 0
								) {
									tmp_uifm_summ_list +=
										'<span class="uiform-sbox-summ-fld-title">' +
										$(this)
											.find('.rockfm-fld-data-field_name')
											.html() +
										': </span>';
								}

								tmp_uifm_summ_list_inner += ' <span class="uiform-sbox-summ-fld-price"><ul><li>' + '  <div class="uifm-sbox-summ-fld-symbol">' + uifm_price_symbol + '</div><div class="uifm-sbox-summ-fld-price">' + zgfm_front_cost.format_money(obj_form, uifm_fld_sub_total) + '</div><div class="uifm-sbox-summ-fld-pricecode"> ' + uifm_price_code + '</div>' + '</li></ul></span>';
							}


							tmp_uifm_summ_list += tmp_uifm_summ_list_inner;

							tmp_uifm_summ_list += '</span>';

							if (show_all_rows || (parseFloat(uifm_fld_sub_total) > 0 && tmp_uifm_summ_row_count < uifm_summ_row_total)) {
								uifm_summ_list += tmp_uifm_summ_list;
							}

							if (parseFloat(uifm_fld_sub_total) > 0 && tmp_uifm_summ_row_count < uifm_summ_row_total) {
								tmp_uifm_summ_row_count++;
							}

							$(this)
								.find('.rockfm-inp4-opt-price-lbl')
								.show();
							$(this)
								.find('.rockfm-inp4-opt-price-lbl .uiform-stickybox-inp-price')
								.html(zgfm_front_cost.format_money(obj_form, tmp_uifm_fld_price));
							break;
						case 40:
							tmp_uifm_summ_list = '';
							tmp_uifm_summ_list += '<span class="uiform-sbox-summ-fld-row">';
							if ($(this).find('.rockfm-label').length && String(zgfm_front_cost.costest_removetags($(this).find('.rockfm-label'))).replace(/ /g, '').length > 0) {
								tmp_uifm_summ_list += '<span class="uiform-sbox-summ-fld-title2">' + zgfm_front_cost.costest_removetags($(this).find('.rockfm-label')) + ': </span>';
							} else if (
								String(
									$(this)
										.find('.rockfm-fld-data-field_name')
										.html()
								).length > 0
							) {
								tmp_uifm_summ_list +=
									'<span class="uiform-sbox-summ-fld-title">' +
									$(this)
										.find('.rockfm-fld-data-field_name')
										.html() +
									': </span>';
							}

							tmp_uifm_summ_list_inner = '';

							tmp_uifm_fld_price = 0;

							uifm_fld_value = $(this)
								.find('.rockfm-input15-switch')
								.bootstrapSwitchZgpb('state');
							if (uifm_fld_value) {
								uifm_fld_price =
									$(this)
										.find('.rockfm-input15-switch')
										.attr('data-uifm-inp-price') || 0;
							} else {
								uifm_fld_price = 0;
							}
							price_sum += parseFloat(uifm_fld_price);
							tmp_uifm_fld_price += parseFloat(uifm_fld_price);
							if (parseFloat(uifm_fld_price) > 0) {
								tmp_uifm_summ_list_inner += ' : <div class="uifm-sbox-summ-fld-symbol">' + uifm_price_symbol + '</div><div class="uifm-sbox-summ-fld-price">' + zgfm_front_cost.format_money(obj_form, uifm_fld_price) + '</div><div class="uifm-sbox-summ-fld-pricecode"> ' + uifm_price_code + '</div>';
							}


							tmp_uifm_summ_list += tmp_uifm_summ_list_inner;

							tmp_uifm_summ_list += '</span>';

							if (show_all_rows || tmp_uifm_summ_row_count < uifm_summ_row_total) {
								if (uifm_fld_value) {
									uifm_summ_list += tmp_uifm_summ_list;
								}
							}

							if (tmp_uifm_summ_row_count < uifm_summ_row_total) {
								tmp_uifm_summ_row_count++;
							}

							$(this)
								.find('.rockfm-inp15-opt-price-lbl')
								.show();
							$(this)
								.find('.rockfm-inp15-opt-price-lbl .uiform-stickybox-inp-price')
								.html(zgfm_front_cost.format_money(obj_form, tmp_uifm_fld_price));
							break;
						case 41:
							if ($(this).find('input[type=checkbox]:checked').length) {
								tmp_uifm_summ_list = '';
								if ($(this).find('.rockfm-label').length && String(zgfm_front_cost.costest_removetags($(this).find('.rockfm-label'))).replace(/ /g, '').length > 0) {
									tmp_uifm_summ_list += '<span class="uiform-sbox-summ-fld-title">' + zgfm_front_cost.costest_removetags($(this).find('.rockfm-label')) + ': </span>';
								} else if (
									String(
										$(this)
											.find('.rockfm-fld-data-field_name')
											.html()
									).length > 0
								) {
									tmp_uifm_summ_list +=
										'<span class="uiform-sbox-summ-fld-title">' +
										$(this)
											.find('.rockfm-fld-data-field_name')
											.html() +
										': </span>';
								}
								tmp_uifm_summ_list += '<span class="uiform-sbox-summ-fld-row">';
								tmp_uifm_summ_list += '<ul>';
								tmp_uifm_summ_list_inner = '';
								$(this)
									.find('input[type=checkbox]:checked')
									.each(function() {
										uifm_fld_price = $(this)
											.closest('.uifm-dcheckbox-item')
											.uiformDCheckbox('get_totalCost');
										price_sum += parseFloat(uifm_fld_price);
										tmp_uifm_summ_list_inner +=
											'<li>' +
											$(this)
												.closest('.uifm-dcheckbox-item')
												.uiformDCheckbox('get_labelOpt');
										if (parseFloat(uifm_fld_price) > 0) {
											tmp_uifm_summ_list_inner += ' : <div class="uifm-sbox-summ-fld-symbol">' + uifm_price_symbol + '</div><div class="uifm-sbox-summ-fld-price">' + zgfm_front_cost.format_money(obj_form, uifm_fld_price) + '</div><div class="uifm-sbox-summ-fld-pricecode"> ' + uifm_price_code + '</div>';
										}
										tmp_uifm_summ_list_inner += '</li>';
									});
								tmp_uifm_summ_list += tmp_uifm_summ_list_inner;
								tmp_uifm_summ_list += '</ul>';
								tmp_uifm_summ_list += '</span>';

								if (show_all_rows || tmp_uifm_summ_row_count < uifm_summ_row_total) {
									uifm_summ_list += tmp_uifm_summ_list;
								}

								if (tmp_uifm_summ_row_count < uifm_summ_row_total) {
									tmp_uifm_summ_row_count++;
								}
							}
							break;
						case 42:
							if ($(this).find('input[type=checkbox]:checked').length) {
								tmp_uifm_summ_list = '';
								if ($(this).find('.rockfm-label').length && String(zgfm_front_cost.costest_removetags($(this).find('.rockfm-label'))).replace(/ /g, '').length > 0) {
									tmp_uifm_summ_list += '<span class="uiform-sbox-summ-fld-title">' + zgfm_front_cost.costest_removetags($(this).find('.rockfm-label')) + ': </span>';
								} else if (
									String(
										$(this)
											.find('.rockfm-fld-data-field_name')
											.html()
									).length > 0
								) {
									tmp_uifm_summ_list +=
										'<span class="uiform-sbox-summ-fld-title">' +
										$(this)
											.find('.rockfm-fld-data-field_name')
											.html() +
										': </span>';
								}
								tmp_uifm_summ_list += '<span class="uiform-sbox-summ-fld-row">';
								tmp_uifm_summ_list += '<ul>';
								tmp_uifm_summ_list_inner = '';
								$(this)
									.find('input[type=checkbox]:checked')
									.each(function() {
										uifm_fld_price = $(this)
											.closest('.uifm-dradiobtn-item')
											.uiformDCheckbox('get_totalCost');
										price_sum += parseFloat(uifm_fld_price);
										tmp_uifm_summ_list_inner +=
											'<li>' +
											$(this)
												.closest('.uifm-dradiobtn-item')
												.uiformDCheckbox('get_labelOpt');
										if (parseFloat(uifm_fld_price) > 0) {
											tmp_uifm_summ_list_inner += ' : <div class="uifm-sbox-summ-fld-symbol">' + uifm_price_symbol + '</div><div class="uifm-sbox-summ-fld-price">' + zgfm_front_cost.format_money(obj_form, uifm_fld_price) + '</div><div class="uifm-sbox-summ-fld-pricecode"> ' + uifm_price_code + '</div>';
										}
										tmp_uifm_summ_list_inner += '</li>';
									});
								tmp_uifm_summ_list += tmp_uifm_summ_list_inner;
								tmp_uifm_summ_list += '</ul>';
								tmp_uifm_summ_list += '</span>';

								if (show_all_rows || tmp_uifm_summ_row_count < uifm_summ_row_total) {
									uifm_summ_list += tmp_uifm_summ_list;
								}

								if (tmp_uifm_summ_row_count < uifm_summ_row_total) {
									tmp_uifm_summ_row_count++;
								}
							}
							break;
					}
				});

				var returnVars = [uifm_summ_list, price_sum, tmp_uifm_summ_row_count, uifm_summ_row_total];
				return returnVars;
			};
		};
		window.zgfm_front_cost = zgfm_front_cost = $.zgfm_front_cost = new zgfm_front_cost();
	})($uifm, window);
}

if (typeof $uifm === 'undefined') {
	$uifm = jQuery;
}
var zgfm_front_evts = zgfm_front_evts || null;
if (!$uifm.isFunction(zgfm_front_evts)) {
	(function($, window) {
		'use strict';

		var zgfm_front_evts = function() {
			var zgfm_variable = [];
			zgfm_variable.innerVars = {};
			zgfm_variable.externalVars = {};

			this.initialize = function() {
				this.global_events();
			};

			this.global_events = function() {};

			this.refresh_fieldDynBoxes = function() {
				var obj = $('.rockfm-dyncheckbox');

				$.each(obj, function(key, value) {
					let tmp_wrap = $(this).find('.rockfm-input17-wrap');
					let tmp_wrap_w = tmp_wrap.width();

					let tmp_wrap_canvas = $(this).find('.rockfm-input17-wrap canvas');
				});
			};
		};
		window.zgfm_front_evts = zgfm_front_evts = $.zgfm_front_evts = new zgfm_front_evts();
	})($uifm, window);
}

if (typeof $uifm === 'undefined') {
	$uifm = jQuery;
}
var zgfm_front_helper = zgfm_front_helper || null;
if (!$uifm.isFunction(zgfm_front_helper)) {
	(function($, window) {
		'use strict';

		var zgfm_front_helper = function() {
			var zgfm_variable = [];
			zgfm_variable.innerVars = {};
			zgfm_variable.externalVars = {};

			this.initialize = function() {};

			var runExtraTasks = function(field) {
				var obj_form = $(field).closest('.rockfm-form');
				zgfm_front_cost.costest_refresh(obj_form);
			};

			this.triggerEvent_before = function() {};

			this.triggerEvent_after = function() {};

			this.event_isDefined_toEl = function(el, search_evt, list_events) {
				var flag = false;
				try {
					$.each(list_events, function(i, event) {
						if (String(i) === 'zgfm') {
							$.each(event, function(i2, handler) {
								if ($.isPlainObject(handler)) {
									$.each(handler, function(i3, handler3) {
										if (String(i3) === 'namespace') {
											if ($.isPlainObject(handler3)) {
												$.each(handler3, function(i4, handler4) {
												});
											} else {
												if (String(handler3) === String(search_evt)) {
													throw true;
												}
											}
										}
									});
								} else {
								}
							});
						}
					});
				} catch (e) {
					flag = e;
				}

				return flag;
			};

			this.load_cssfiles = function(id) {
				var uifm_loadcssfile = function(cssFilesArr) {
					for (var i in cssFilesArr) {
						if (!document.getElementById(cssFilesArr[i].id)) {
							var fileref = document.createElement('link');
							fileref.setAttribute('rel', 'stylesheet');
							fileref.setAttribute('type', 'text/css');
							fileref.setAttribute('id', cssFilesArr[i].id);
							fileref.setAttribute('media', 'all');
							fileref.setAttribute('href', cssFilesArr[i].href);
							document.getElementsByTagName('head')[0].appendChild(fileref);
						}
					}
				};

				var uifm_cssFiles = [{ id: 'uifm_b_css_form_' + id, href: rockfm_vars.url_plugin + '/assets/frontend/css/rockfm_form' + id + '.css?' + Math.round(+new Date() / 1000) }];
				uifm_loadcssfile(uifm_cssFiles);
			};

			this.load_form_init_events = function(obj_form) {
				var tmp_field;
				var tmp_field_id;
				var tmp_field_inp;
				var tmp_action;

				var tmp_theme_type;
				var all_fields = obj_form.find('.rockfm-field');

				$.each(all_fields, function() {
					tmp_field = $(this);
					if (tmp_field.length) {
						switch (parseInt(tmp_field.attr('data-typefield'))) {
							case 6:
							case 7:
							case 28:
							case 29:
							case 30:
								tmp_field_inp = tmp_field.find('.rockfm-txtbox-inp-val');
								break;
							case 8:
								tmp_theme_type = tmp_field.find('.rockfm-input2-wrap').attr('data-theme-type');

								switch (parseInt(tmp_theme_type)) {
									case 1:
										tmp_field_inp = tmp_field.find('.checkradios-radio');
										break;
									default:
										tmp_field_inp = tmp_field.find('.rockfm-inp2-rdo');
										break;
								}
								break;
							case 9:
								tmp_theme_type = tmp_field.find('.rockfm-input2-wrap').attr('data-theme-type');

								switch (parseInt(tmp_theme_type)) {
									case 1:
										tmp_field_inp = tmp_field.find('.checkradios-checkbox');
										break;
									default:
										tmp_field_inp = tmp_field.find('.rockfm-inp2-chk');
										break;
								}
								break;
							case 10:
							case 11:

								tmp_theme_type = tmp_field.find('.rockfm-input2-wrap').attr('data-theme-type');

								switch (parseInt(tmp_theme_type)) {
									case 1:
										tmp_field_inp = tmp_field.find('.rockfm-input2-sel-styl1');
										break;
									case 2:
										tmp_field_inp = tmp_field.find('.rockfm-input2-sel-styl2');
										break;
									default:
										tmp_field_inp = tmp_field.find('.uifm-input2-opt-main');
										break;
								}

								break;
							case 16:
								tmp_field_inp = tmp_field.find('.rockfm-input4-slider');
								break;
							case 18:
								tmp_field_inp = tmp_field.find('.rockfm-input4-spinner');
								break;
							case 24:
								tmp_field_inp = tmp_field.find('.rockfm-input7-datepic');
								break;
							case 26:
								tmp_field_inp = tmp_field.find('.rockfm-input7-datetimepic');
								break;
							case 40:
								tmp_field_inp = tmp_field.find('.rockfm-input15-switch');
								break;
							case 41:
								tmp_field_inp = tmp_field.find('.uifm-dcheckbox-item');
								break;
							case 42:
								tmp_field_inp = tmp_field.find('.uifm-dradiobtn-item');
								break;
							case 43:
								tmp_field_inp = tmp_field.find('.uifm-input-flatpickr');
								break;
						}

						switch (parseInt(tmp_field.attr('data-typefield'))) {
							case 6:
							case 7:
							case 28:
							case 29:
							case 30:
								tmp_action = 'change keyup';

								tmp_field_inp.on(tmp_action, function(e) {
									if (e) {
										e.preventDefault();
									}

									if (String(rocketfm.getExternalVars('fm_loadmode')) === 'iframe') {
										if ('parentIFrame' in window) {
											parentIFrame.size(); 
										}
									}

									runExtraTasks($(this));
								});

								break;
							case 8:
							case 9:

								tmp_theme_type = tmp_field.find('.rockfm-input2-wrap').attr('data-theme-type');

								switch (parseInt(tmp_theme_type)) {
									case 1:
										tmp_action = 'click change';
										break;
									default:
										tmp_action = 'change';
										break;
								}

								tmp_field_inp.on(tmp_action, function(e) {
									if (e) {
										e.preventDefault();
									}

									wp.hooks.applyFilters('zgfmfront.events_before');

									tmp_field_id = $(this).attr('data-idfield');
									if (obj_form.find('.rockfm-clogic-fcond').length) {
										obj_form.data('zgfm_logicfrm').triggerConditional(e.target, tmp_field_id);
									}
									if (String(rocketfm.getExternalVars('fm_loadmode')) === 'iframe') {
										if ('parentIFrame' in window) {
											parentIFrame.size(); 
										}
									}
									if (
										$(this)
											.closest('.rockfm-field')
											.hasClass('rockfm-required')
									) {
										rocketfm.validate_field($(this).closest('.rockfm-field'));
									}
									if (
										$(this)
											.closest('.rockfm-field')
											.hasClass('rockfm-costest-field')
									) {
										zgfm_front_cost.costest_refresh(obj_form);
									}

									runExtraTasks($(this));

									wp.hooks.applyFilters('zgfmfront.events_after');
								});

								break;
							case 10:
							case 11:

								switch (parseInt(tmp_theme_type)) {
									case 1:
										tmp_field_inp.on('changed.bs.select', function(e) {
											if (e) {
												e.preventDefault();
											}
											wp.hooks.applyFilters('zgfmfront.events_before');

											tmp_field_id = $(this).attr('data-idfield');

											if (obj_form.find('.rockfm-clogic-fcond').length) {
												obj_form.data('zgfm_logicfrm').triggerConditional(e.target, tmp_field_id);
											}
											if (String(rocketfm.getExternalVars('fm_loadmode')) === 'iframe') {
												if ('parentIFrame' in window) {
													parentIFrame.size(); 
												}
											}
											if (
												$(this)
													.closest('.rockfm-field')
													.hasClass('rockfm-costest-field')
											) {
												zgfm_front_cost.costest_refresh(obj_form);
											}
											runExtraTasks($(this));

											wp.hooks.applyFilters('zgfmfront.events_after');
										});
										break;
									default:
										tmp_field_inp.on('change', function(e) {
											if (e) {
												e.preventDefault();
											}
											wp.hooks.applyFilters('zgfmfront.events_before');

											tmp_field_id = $(this).attr('data-idfield');
											if (obj_form.find('.rockfm-clogic-fcond').length) {
												obj_form.data('zgfm_logicfrm').triggerConditional(e.target, tmp_field_id);
											}
											if (String(rocketfm.getExternalVars('fm_loadmode')) === 'iframe') {
												if ('parentIFrame' in window) {
													parentIFrame.size(); 
												}
											}
											if (
												$(this)
													.closest('.rockfm-field')
													.hasClass('rockfm-costest-field')
											) {
												zgfm_front_cost.costest_refresh(obj_form);
											}
											runExtraTasks($(this));

											wp.hooks.applyFilters('zgfmfront.events_after');
										});
								}

								break;
							case 16:
								tmp_field_inp.on('slideStop', function(e) {
									if (e) {
										e.preventDefault();
									}
									wp.hooks.applyFilters('zgfmfront.events_before');

									tmp_field_id = $(this).attr('data-idfield');
									if (obj_form.find('.rockfm-clogic-fcond').length) {
										obj_form.data('zgfm_logicfrm').triggerConditional(e.target, tmp_field_id);
									}

									if (String(rocketfm.getExternalVars('fm_loadmode')) === 'iframe') {
										if ('parentIFrame' in window) {
											parentIFrame.size(); 
										}
									}
									if (
										$(this)
											.closest('.rockfm-field')
											.hasClass('rockfm-costest-field')
									) {
										zgfm_front_cost.costest_refresh(obj_form);
									}
									runExtraTasks($(this));

									wp.hooks.applyFilters('zgfmfront.events_after');
								});
								break;
							case 18:
								tmp_field_inp.on('change keyup', function(e) {
									if (e) {
										e.preventDefault();
									}
									wp.hooks.applyFilters('zgfmfront.events_before');

									tmp_field_id = $(this).attr('data-idfield');
									if (obj_form.find('.rockfm-clogic-fcond').length) {
										obj_form.data('zgfm_logicfrm').triggerConditional(e.target, tmp_field_id);
									}

									if (String(rocketfm.getExternalVars('fm_loadmode')) === 'iframe') {
										if ('parentIFrame' in window) {
											parentIFrame.size(); 
										}
									}
									if (
										$(this)
											.closest('.rockfm-field')
											.hasClass('rockfm-costest-field')
									) {
										zgfm_front_cost.costest_refresh(obj_form);
									}
									runExtraTasks($(this));

									wp.hooks.applyFilters('zgfmfront.events_after');
								});
								break;
							case 24:
								tmp_field_inp.on('dp.change', function(e) {
									if (e) {
										e.preventDefault();
									}
									wp.hooks.applyFilters('zgfmfront.events_before');

									if (String(rocketfm.getExternalVars('fm_loadmode')) === 'iframe') {
										if ('parentIFrame' in window) {
											parentIFrame.size(); 
										}
									}
									if (
										$(this)
											.closest('.rockfm-field')
											.hasClass('rockfm-costest-field')
									) {
										zgfm_front_cost.costest_refresh(obj_form);
									}
									runExtraTasks($(this));

									wp.hooks.applyFilters('zgfmfront.events_after');
								});
								break;
							case 26:
								tmp_field_inp.on('dp.change', function(e) {
									if (e) {
										e.preventDefault();
									}
									wp.hooks.applyFilters('zgfmfront.events_before');

									if (String(rocketfm.getExternalVars('fm_loadmode')) === 'iframe') {
										if ('parentIFrame' in window) {
											parentIFrame.size(); 
										}
									}
									if (
										$(this)
											.closest('.rockfm-field')
											.hasClass('rockfm-costest-field')
									) {
										zgfm_front_cost.costest_refresh(obj_form);
									}
									runExtraTasks($(this));

									wp.hooks.applyFilters('zgfmfront.events_after');
								});
								break;
							case 40:
								tmp_field_inp.on('switchChange.bootstrapSwitchZgpb', function(e) {
									if (e) {
										e.preventDefault();
									}
									wp.hooks.applyFilters('zgfmfront.events_before');

									tmp_field_id = $(this).attr('data-idfield');
									if (obj_form.find('.rockfm-clogic-fcond').length) {
										obj_form.data('zgfm_logicfrm').triggerConditional(e.target, tmp_field_id);
									}
									if (String(rocketfm.getExternalVars('fm_loadmode')) === 'iframe') {
										if ('parentIFrame' in window) {
											parentIFrame.size(); 
										}
									}
									if (
										$(this)
											.closest('.rockfm-field')
											.hasClass('rockfm-costest-field')
									) {
										zgfm_front_cost.costest_refresh(obj_form);
									}
									runExtraTasks($(this));

									wp.hooks.applyFilters('zgfmfront.events_after');
								});
								break;
							case 41:
							case 42:
								tmp_field_inp.on('click', function(e) {
									if (e) {
										e.preventDefault();
									}
									wp.hooks.applyFilters('zgfmfront.events_before');

									tmp_field_id = $(this).attr('data-idfield');

									if (obj_form.find('.rockfm-clogic-fcond').length) {
										obj_form.data('zgfm_logicfrm').triggerConditional(e.target, tmp_field_id);
									}
									if (String(rocketfm.getExternalVars('fm_loadmode')) === 'iframe') {
										if ('parentIFrame' in window) {
											parentIFrame.size(); 
										}
									}

									runExtraTasks($(this));

									wp.hooks.applyFilters('zgfmfront.events_after');
								});

								break;
						}
					}
				});
			};
		};
		window.zgfm_front_helper = zgfm_front_helper = $.zgfm_front_helper = new zgfm_front_helper();

		const { addFilter } = wp.hooks;
		addFilter('zgfmfront.events_before', 'zgfm_front_helper/triggerEvent_before', zgfm_front_helper.triggerEvent_before);
		addFilter('zgfmfront.events_after', 'zgfm_front_helper/triggerEvent_after', zgfm_front_helper.triggerEvent_after);
	})($uifm, window);
}

(function($) {
	var uiformStickybox = function(element, options) {
		var elem = $(element);
		var defaults = {
			enable: 1,
			orientation: 'bottomout',
			form_container: $('.uiform-main-form'),
			main_container: $('.uiform-preview-base'),
			sticky: {
				width: '200',
				height: '200',
			},
			resp_orientation: 1,
			backend: 0,
		};
		var settings = $.extend({}, defaults, options);
		var data = {
			tmp_type: 1,
			sidebar_obj: elem,
			mainwrap_obj: null,
			sidebar_obj_minh: 50,
			formc_obj: null,
			formc_obj_tempwidth: '',
			formc_obj_width: '',
			stickyTop_sec: parseInt(settings.backend) === 1 ? settings.form_container.find('.uifm-sticky-top-section') : settings.form_container.find('.uiform-sticky-top-section'),
			stickyBot_sec: parseInt(settings.backend) === 1 ? settings.form_container.find('.uifm-sticky-bottom-section') : settings.form_container.find('.uiform-sticky-bottom-section'),
			stickyTopout_sec: parseInt(settings.backend) === 1 ? settings.main_container.find('.uifm-sticky-topout-section') : settings.main_container.find('.uiform-sticky-topout-section'),
			stickyBotout_sec: parseInt(settings.backend) === 1 ? settings.main_container.find('.uifm-sticky-bottomout-section') : settings.main_container.find('.uiform-sticky-bottomout-section'),
			stickyTop2: null,
			stickyHeight: elem.outerHeight(true),
			win: $(window),
			breakPoint: '',
			marg: parseInt(elem.css('margin-top'), 10),
		};

		this.publicMethod = function() {};

		var privateMethod = function() {};
		this.updateData = function(data) {
			settings = $.extend({}, defaults, data);
		};

		this.destroy = function() {
			data.win.unbind();
		};

		this.init = function() {
			data.mainwrap_obj = settings.main_container;
			data.formc_obj = settings.form_container;

			data.formc_obj_width = data.formc_obj.css('width').replace(/[^-\d\.]/g, '');
			if (parseFloat(data.formc_obj_width) < 1) {
				data.formc_obj_width = data.mainwrap_obj.parent().width();
			}

			data.stickyTop2 = data.formc_obj.offset().top;
			data.breakPoint = elem.outerWidth(true) + data.formc_obj.outerWidth(true);
			buildsticky();
		};

		var buildsticky = function() {
			buildOrientation();
			if (parseInt(settings.backend) === 0) {
				switch (settings.orientation) {
					case 'right':
					case 'left':
						stick();
						if (parseInt(settings.enable)) {
							data.win.bind({
								scroll: stick,
								resize: function() {
									buildOrientation();
									stick();
								},
							});
						}
						break;
				}
			}
		};
		function getBoxWidth(showExt) {
			var tmpwidth;
			switch (parseInt(data.tmp_type)) {
				case 1:
				case 2:
					if (showExt) {
						tmpwidth = settings.sticky.width + 'px';
					} else {
						tmpwidth = settings.sticky.width;
					}
					break;
				case 0:
				case 4:
				case 3:
				case 5:
					if (showExt) {
						tmpwidth = '100%';
					} else {
						tmpwidth = data.sidebar_obj.css('width', '100%').width();
					}

					break;
			}
			return tmpwidth;
		}
		function getBoxHeight() {
			var tmpheight;
			tmpheight = data.sidebar_obj.height();
			return tmpheight;
		}

		function setFixedSidebar() {
			switch (parseInt(data.tmp_type)) {
				case 1:
				case 2:
					data.sidebar_obj.css({
						position: 'absolute',
						top: 0,
					});

					break;
				case 3:
				case 5:
					if (parseInt(settings.backend) === 1) {
						data.sidebar_obj.css({
							position: 'absolute',
							bottom: 0,
						});
					} else {
					}
					break;
				case 4:
					if (parseInt(settings.backend) === 1) {
						data.sidebar_obj.css({
							position: 'absolute',
							top: 0,
						});
					} else {
					}
					break;
				case 0:
				default:
					if (parseInt(settings.backend) === 1) {
						data.sidebar_obj.css({
							position: 'absolute',
							top: 0,
						});
					} else {
					}
					break;
			}
		}

		function checkSidebarOnDefaultPos() {
			if ($(data.stickyBotout_sec).html().length != 0) {
				data.sidebar_obj.insertBefore(data.formc_obj);
			}
		}

		function checkOrientation() {
			data.sidebar_obj.css('display', 'block');

			data.stickyHeight = data.sidebar_obj.outerHeight(true);

			switch (parseInt(data.tmp_type)) {
				case 1:

					data.sidebar_obj.css('margin-left', data.formc_obj.outerWidth(true));

					checkSidebarOnDefaultPos();

					if ($(window).width() <= 700) {
					} else {
						if ($(data.stickyTop_sec).html().length != 0) {
							data.sidebar_obj.insertBefore(data.formc_obj);
							data.stickyTop2 = data.formc_obj.position().top;
						} else if ($(data.stickyBot_sec).html().length != 0) {
							data.sidebar_obj.insertBefore(data.formc_obj);
							data.stickyTop2 = data.formc_obj.position().top;
						} else {
							data.stickyTop2 = data.formc_obj.offset().top;
						}
					}

					break;
				case 2:
					data.formc_obj.css('margin-left', data.sidebar_obj.outerWidth(true));

					checkSidebarOnDefaultPos();

					if ($(window).width() <= 700) {
					} else {
						if ($(data.stickyTop_sec).html().length != 0) {
							data.sidebar_obj.insertBefore(data.formc_obj);
							data.stickyTop2 = data.formc_obj.position().top;
						}
						if ($(data.stickyBot_sec).html().length != 0) {
							data.sidebar_obj.insertBefore(data.formc_obj);
							data.stickyTop2 = data.formc_obj.position().top;
						}

						data.stickyTop2 = data.formc_obj.offset().top;
					}
					break;
				case 3:
					data.formc_obj.removeCss('margin-left');
					data.sidebar_obj.removeCss('margin-left');
					data.sidebar_obj.removeCss('float');
					data.sidebar_obj.removeCss('top');

					if ($(data.stickyBot_sec).html().length === 0) {
						data.sidebar_obj.appendTo(data.stickyBot_sec);
						data.stickyTop2 = data.stickyBot_sec.position().top + data.stickyBot_sec.outerHeight(true);
					}

					break;
				case 4:
					data.formc_obj.removeCss('margin-left');
					data.sidebar_obj.removeCss('margin-left');
					data.sidebar_obj.removeCss('float');

					if ($(data.stickyTopout_sec).html().length === 0) {
						data.sidebar_obj.appendTo(data.stickyTopout_sec);
					}
					if ($(data.stickyBotout_sec).html().length != 0) {
						data.sidebar_obj.insertBefore(data.formc_obj);
					}
					break;
				case 5:
					data.formc_obj.removeCss('margin-left');
					data.sidebar_obj.removeCss('margin-left');
					data.sidebar_obj.removeCss('float');
					data.sidebar_obj.removeCss('top');

					if ($(data.stickyBotout_sec).html().length === 0) {
						data.sidebar_obj.appendTo(data.stickyBotout_sec);
						data.stickyTop2 = data.stickyBotout_sec.position().top + data.stickyBotout_sec.outerHeight(true);
					}

					break;
				case 0:
				default:
					data.formc_obj.removeCss('margin-left');
					data.sidebar_obj.removeCss('margin-left');
					data.sidebar_obj.removeCss('float');

					if ($(data.stickyTop_sec).html().length === 0) {
						data.sidebar_obj.appendTo(data.stickyTop_sec);
					}
					if ($(data.stickyBot_sec).html().length != 0) {
						data.sidebar_obj.insertBefore(data.formc_obj);
					}
					break;
			}
		}

		function setPositionSidebar() {
			switch (parseInt(data.tmp_type)) {
				case 1:
					if (parseInt(settings.backend) === 1) {
						data.sidebar_obj.css({
							position: 'absolute',
							float: 'right',
						});
					} else {
						data.sidebar_obj.css({
							position: 'absolute',
						});
					}

					break;
				case 2:
					if (parseInt(settings.backend) === 1) {
						data.sidebar_obj.css({
							position: 'absolute',
							float: 'left',
						});
					} else {
						data.sidebar_obj.css({
							position: 'absolute',
						});
					}

					break;
				case 3:
					if (parseInt(settings.backend) === 1) {
						data.sidebar_obj.css({
							position: 'static',
						});
					} else {
						data.sidebar_obj.css({
							position: 'static',
						});
					}

					break;
				case 0:
				default:
					if (parseInt(settings.backend) === 1) {
						data.sidebar_obj.css({
							position: 'static',
						});
					} else {
						data.sidebar_obj.css({
							position: 'static',
						});
					}

					break;
			}
		}

		function checkFormContent() {
			data.formc_obj.removeCss('width');
			data.formc_obj.removeCss('margin');
			data.formc_obj.removeCss('margin-left');
			data.formc_obj.removeCss('margin-right');
			data.sidebar_obj.removeCss('margin');
			data.sidebar_obj.removeCss('margin-left');
			data.sidebar_obj.removeCss('margin-right');

			switch (parseInt(data.tmp_type)) {
				case 1:
				case 2:
					var main_container_width = data.mainwrap_obj.css('width').replace(/[^-\d\.]/g, '');
					if (parseFloat(main_container_width) < 1) {
						main_container_width = data.mainwrap_obj.parent().width();
					}

					if (parseInt(settings.backend) === 1) {
						data.formc_obj_tempwidth = parseFloat(main_container_width) - parseFloat(getBoxWidth(false)) - 30;
					} else {
						data.formc_obj_tempwidth = parseFloat(main_container_width) - parseFloat(getBoxWidth(false));
					}

					data.formc_obj.css('width', data.formc_obj_tempwidth);
					if (data.formc_obj_tempwidth < 450) {
						data.formc_obj_tempwidth = 450;
					}
					break;
				case 3:

					break;
				case 0:
				default:

					break;
			}
		}

		function checkFormatSidebar() {
			switch (parseInt(data.tmp_type)) {
				case 1:
				case 2:
					data.sidebar_obj.removeCss('min-height');
					data.sidebar_obj.css('min-height', getBoxHeight() + 'px');
					data.sidebar_obj.css('width', getBoxWidth(true));
					break;
				case 3:

					data.sidebar_obj.css({
						height: 'auto',
						'min-height': '50px',
						width: getBoxWidth(true),
					});
					break;
				case 4:
					data.sidebar_obj.css({
						height: 'auto',
						'min-height': '50px',
						width: getBoxWidth(true),
					});
					break;
				case 5:

					data.sidebar_obj.css({
						height: 'auto',
						'min-height': '50px',
						width: getBoxWidth(true),
					});
					break;
				case 0:
				default:
					data.sidebar_obj.css({
						height: 'auto',
						'min-height': '50px',
						width: getBoxWidth(true),
					});
					break;
			}
		}

		function setLimitedSidebar(diff) {
			var new_diff = diff;
			if (parseFloat(diff) < 0) {
				new_diff = 0;
			}

			data.sidebar_obj.css({
				top: new_diff,
			});
		}

		function calculateLimits() {
			switch (parseInt(data.tmp_type)) {
				case 0:
				case 1:
				case 2:
					return {
						limit: $(data.formc_obj).offset().top + $(data.formc_obj).outerHeight() - data.stickyHeight,
						windowTop: data.win.scrollTop(),
						stickyTop: data.stickyTop2 - data.marg,
					};
					break;
				case 4:
					return {
						limit: $(data.mainwrap_obj).offset().top + $(data.mainwrap_obj).outerHeight() - data.stickyHeight,
						windowTop: data.win.scrollTop(),
						stickyTop: data.stickyTop2 - data.marg,
					};
					break;
				case 3:
				case 5:
					return {
						limit: $(data.formc_obj).offset().top + $(data.formc_obj).outerHeight() - data.stickyHeight,
						windowTop: data.win.scrollTop(),
						stickyTop: data.stickyTop2 - data.marg,
					};
					break;
			}
		}

		var stick = function() {
			switch (settings.orientation) {
				case 'right':
				case 'left':
					var tops = calculateLimits();
					var hitBreakPoint;
					switch (parseInt(data.tmp_type)) {
						case 0:
						case 1:
						case 2:
							hitBreakPoint = tops.stickyTop < tops.windowTop;
							break;
						case 4:
							hitBreakPoint = tops.stickyTop < tops.windowTop;
							break;
						case 3:
						case 5:
							hitBreakPoint = tops.windowTop < tops.stickyTop && data.stickyTop2 - data.win.height() > tops.windowTop;
							break;
					}

					if (hitBreakPoint) {
						setFixedSidebar();
						checkOrientation();

						switch (parseInt(data.tmp_type)) {
							case 1:
							case 2:
								var diff = tops.windowTop - tops.stickyTop;
								data.sidebar_obj.css({
									top: diff,
								});

								break;
						}
					} else {
						setPositionSidebar();
					}

					switch (parseInt(data.tmp_type)) {
						case 1:
						case 2:
							if (tops.limit < tops.windowTop) {
								var diff = tops.limit - tops.stickyTop;
								setLimitedSidebar(diff);
							} else {
							}
							break;
					}

					break;
			}
		};
		var buildOrientation = function() {
			switch (settings.orientation) {
				case 'right':
					if ($(window).width() <= 700) {
						if (parseInt(settings.resp_orientation) === 2) {
							data.tmp_type = 3;
						} else {
							data.tmp_type = 0;
						}
					} else {
						data.tmp_type = 1;
					}

					break;
				case 'left':
					if ($(window).width() <= 700) {
						if (parseInt(settings.resp_orientation) === 2) {
							data.tmp_type = 3;
						} else {
							data.tmp_type = 0;
						}
					} else {
						data.tmp_type = 2;
					}

					break;
				case 'bottom':
					data.tmp_type = 3;

					break;
				case 'topout':
					data.tmp_type = 4;

					break;
				case 'bottomout':
					data.tmp_type = 5;

					break;
				case 'top':
				default:
					data.tmp_type = 0;

					break;
			}

			checkFormatSidebar();
			checkFormContent();
			setPositionSidebar();
			checkOrientation();
		};
	};

	$.fn.uiform_stickybox = function(options) {
		return this.each(function() {
			var element = $(this);

			if (element.data('uiform_stickybox')) return;

			var myplugin = new uiformStickybox(this, options);

			element.data('uiform_stickybox', myplugin);

			myplugin.init();
		});
	};
})($uifm);

(function() {
	var __slice = [].slice;

	(function($, window) {
		'use strict';
		var uiformDCheckbox;
		uiformDCheckbox = (function() {
			var uifm_dchkbox_var = [];
			uifm_dchkbox_var.innerVars = {};
			var _this_obj;
			function uiformDCheckbox(element, options) {
				if (options == null) {
					options = {};
				}
				_this_obj = this;
				this.$element = $(element);
				this.options = $.extend(
					{},
					$.fn.uiformDCheckbox.defaults,
					{
						baseGalleryId: this.$element.data('gal-id'),
						opt_laymode:
							$(element)
								.parent()
								.attr('data-opt-laymode') || 1,
						opt_checked: this.$element.data('opt-checked'),
						opt_isradiobtn: this.$element.data('opt-isrdobtn'),
						opt_qtyMax: this.$element.data('opt-qtymax'),
						opt_qtySt: this.$element.data('opt-qtyst'),
						opt_price: this.$element.data('opt-price'),
						opt_label: this.$element.data('opt-label'),
						opt_thopt_showhvrtxt:
							$(element)
								.parent()
								.attr('data-thopt-showhvrtxt') || 0,
						opt_thopt_showcheckb:
							$(element)
								.parent()
								.attr('data-thopt-showcheckb') || 0,
						opt_thopt_zoom:
							$(element)
								.parent()
								.attr('data-thopt-zoom') || 0,
						opt_thopt_height:
							$(element)
								.parent()
								.attr('data-thopt-height') || 100,
						opt_thopt_width:
							$(element)
								.parent()
								.attr('data-thopt-width') || 100,
						backend: this.$element.data('backend') || 0,
						baseClass: this.$element.data('base-class'),
					},
					options
				);


				this.$element.find('.uifm-dcheckbox-item-viewport').attr('height', this.options.opt_thopt_height);
				this.$element.find('.uifm-dcheckbox-item-viewport').attr('width', this.options.opt_thopt_width);

				this.$opt_gal_btn_show = this.$element.find('.uifm-dcheckbox-item-showgallery');

				this.$opt_gal_links_a = this.$element.find('.uifm-dcheckbox-item-gal-imgs a');

				this.$opt_gal_box = this.$element.find('.uifm-dcheckbox-item-viewport');

				this.$opt_gal_next_img = this.$element.find('.uifm-dcheckbox-item-nextimg');
				this.$opt_gal_prev_img = this.$element.find('.uifm-dcheckbox-item-previmg');

				var tmp_imglist = this.$element.find('.uifm-dcheckbox-item-gal-imgs a img');
				if (parseInt(tmp_imglist.length) < 2) {
					this.$opt_gal_next_img.removeClass('uifm-dcheckbox-item-nextimg').hide();
					this.$opt_gal_prev_img.removeClass('uifm-dcheckbox-item-previmg').hide();
				}

				this.$opt_gal_checkbox = this.$element.find('.uifm-dcheckbox-item-chkst');
				this.$inp_checkbox = this.$element.find('.uifm-dcheckbox-item-chkval');
				this.$inp_checkbox_max = this.$element.find('.uifm-dcheckbox-item-qty-num');
				this.$spinner_wrapper = this.$element.find('.uifm-dcheckbox-item-qty-wrap') || null;

				this.$spinner_buttons = this.$element.find('.uifm-dcheckbox-item-qty-wrap button') || null;

				this.$element.on(
					'init.uiformDCheckbox',
					(function(_this) {
						return function() {
							return _this.options.onInit.apply(element, arguments);
						};
					})(this)
				);

				if (parseInt(_this_obj.options.backend) === 1) {
					this.$canvas_parent = this.$element.closest('.uifm-input17-wrap').width();
				} else {
					this.$canvas_parent = this.$element.closest('.rockfm-input17-wrap').width();
				}

				if (parseInt(this.options.opt_laymode) === 2) {
					this._mod2_initPreview();
				} else {
					if (parseInt(this.options.opt_thopt_zoom) === 0) {
						this.$element.find('.uifm-dcheckbox-item-showgallery').hide();
					} else {
						this.$element.find('.uifm-dcheckbox-item-showgallery').show();
					}
				}

				switch (parseInt(this.options.opt_thopt_showhvrtxt)) {
					case 1:
						this.$element.tooltip();
						break;
					case 0:
					case 2:
					case 3:
						this.$element.find('.uifm-dcheckbox-item-showgallery').hide();
						break;
				}

				if (parseInt(this.options.opt_thopt_showcheckb) === 0) {
					this.$opt_gal_checkbox.hide();
				} else {
					this.$opt_gal_checkbox.show();
				}

				this.$element.on(
					'switchChange.uiformDCheckbox',
					(function(_this) {
						return function() {
							return _this.options.onSwitchChange.apply(element, arguments);
						};
					})(this)
				);


				if (parseInt(_this_obj.options.backend) === 0) {
					this._elementHandlers();
					this._handleHandlers();
				}
				this._elementHandlers2();

				this._galleryHandlers();


				this._get_items();
				this._refresh();
			}

			uiformDCheckbox.prototype._constructor = uiformDCheckbox;

			uiformDCheckbox.prototype._refresh = function() {
				if (parseInt(_this_obj.options.backend) === 1) {
					this.$canvas_parent = this.$element.closest('.uifm-input17-wrap').width();
				} else {
					this.$canvas_parent = this.$element.closest('.rockfm-input17-wrap').width();
				}

				this._enableCheckboxVal(this.$opt_gal_checkbox, this);
				this._setValToChkBoxInput(this);
				this._get_items();
			};

			uiformDCheckbox.prototype._mod2_initPreview = function() {
				this.$element.find('.uifm-dcheckbox-item-nextimg').hide();
				this.$element.find('.uifm-dcheckbox-item-previmg').hide();
				this.$element.find('.uifm-dcheckbox-item-showgallery').hide();

				if (parseInt(this.options.opt_checked) === 0) {
					this._mode2_get_img(this.$element, 2);
				} else {
					this._mode2_get_img(this.$element, 0);
				}
			};

			uiformDCheckbox.prototype._get_items = function() {
				var _this = this;
				if (this.$element.length) {
					var tmp_num_elems = this.$element;
					tmp_num_elems.each(function(i) {
						if (parseInt(_this.options.opt_laymode) === 2) {
							if (parseInt(_this.options.opt_checked) === 1) {
								_this._mode2_get_img(_this.$element, 0);
							} else {
								_this._mode2_get_img(_this.$element, 2);
							}
						} else {
							_this._getImageToCanvas($(this), 0, _this);
						}
					});
				}
			};

			uiformDCheckbox.prototype._getImageToCanvas = function(obj, opt, _this) {
				var ctx = obj.find('canvas')[0].getContext('2d');
				var tmp_can_width = parseInt(this.options.opt_thopt_width);
				var tmp_can_height = parseInt(this.options.opt_thopt_height);

				var aspectRatio = tmp_can_width / tmp_can_height;

				var closestParentDiv = this.$canvas_parent;

				var new_width, new_height;
				if (tmp_can_width > closestParentDiv) {
					if (parseInt(closestParentDiv) > 0) {
						new_width = closestParentDiv;
					} else {
						new_width = tmp_can_width;
					}

					new_height = new_width / aspectRatio;
				} else {
					new_width = tmp_can_width;
					new_height = tmp_can_height;
				}

				var img = new Image();
				img.onload = function() {
					ctx.drawImage(img, 0, 0, new_width, new_height);
				};
				var getImgIndex = obj.find('canvas').attr('data-uifm-nro');
				switch (parseInt(opt)) {
					case 1:
						img.src = _this._getPrevImageGallery(obj, getImgIndex);
						break;
					case 2:
						img.src = _this._getNextImageGallery(obj, getImgIndex);
						break;
					default:
					case 0:
						img.src = _this._getImageGallery(obj, getImgIndex);
						break;
				}

				this.$element.find('.uifm-dcheckbox-item-viewport').attr('height', new_height);
				this.$element.find('.uifm-dcheckbox-item-viewport').attr('width', new_width);
			};

			uiformDCheckbox.prototype._getImageGallery = function(obj, _index) {
				var objimgs = obj.find('.uifm-dcheckbox-item-gal-imgs a img');
				var objcanvas = obj.find('canvas');
				if (objimgs.eq(_index).length) {
					objcanvas.attr('data-uifm-nro', _index);
					return objimgs.eq(_index).attr('src');
				} else {
					objcanvas.attr('data-uifm-nro', 0);
					return objimgs.eq(0).attr('src');
				}
			};

			uiformDCheckbox.prototype._getPrevImageGallery = function(obj, _index) {
				var objimgs = obj.find('.uifm-dcheckbox-item-gal-imgs a img');
				var objcanvas = obj.find('canvas');
				var newIndex = parseInt(_index) - 1;
				if (objimgs.eq(newIndex).length) {
					objcanvas.attr('data-uifm-nro', newIndex);
					return objimgs.eq(newIndex).attr('src');
				} else {
					objcanvas.attr('data-uifm-nro', 0);
					return objimgs.eq(0).attr('src');
				}
			};

			uiformDCheckbox.prototype._mode2_get_img = function(obj, _index) {
				var ctx = obj.find('canvas')[0].getContext('2d');
				var tmp_can_width = parseInt(this.options.opt_thopt_width);
				var tmp_can_height = parseInt(this.options.opt_thopt_height);

				var aspectRatio = tmp_can_width / tmp_can_height;

				var closestParentDiv = this.$canvas_parent;

				var new_width, new_height;
				if (tmp_can_width > closestParentDiv) {
					new_width = closestParentDiv;
					new_height = new_width / aspectRatio;
				} else {
					new_width = tmp_can_width;
					new_height = tmp_can_height;
				}

				var img = new Image();
				img.onload = function() {
					ctx.drawImage(img, 0, 0, new_width, new_height);
				};

				var objimgs = obj.find('.uifm-dcheckbox-item-gal-imgs a img');
				var objcanvas = obj.find('canvas');
				var newIndex = parseInt(_index);
				if (objimgs.eq(newIndex).length) {
					objcanvas.attr('data-uifm-nro', newIndex);
					img.src = objimgs.eq(newIndex).attr('src');
				} else {
					objcanvas.attr('data-uifm-nro', 0);
					img.src = objimgs.eq(0).attr('src');
				}

				this.$element.find('.uifm-dcheckbox-item-viewport').attr('height', new_height);
				this.$element.find('.uifm-dcheckbox-item-viewport').attr('width', new_width);
			};

			uiformDCheckbox.prototype._getNextImageGallery = function(obj, _index) {
				var objimgs = obj.find('.uifm-dcheckbox-item-gal-imgs a img');
				var objcanvas = obj.find('canvas');
				var newIndex = parseInt(_index) + 1;
				if (objimgs.eq(newIndex).length) {
					objcanvas.attr('data-uifm-nro', newIndex);
					return objimgs.eq(newIndex).attr('src');
				} else {
					objcanvas.attr('data-uifm-nro', 0);
					return objimgs.eq(0).attr('src');
				}
			};

			uiformDCheckbox.prototype._setInnerVariable = function(name, value) {
				uifm_dchkbox_var.innerVars[name] = value;
			};
			uiformDCheckbox.prototype._getInnerVariable = function(name) {
				if (uifm_dchkbox_var.innerVars[name]) {
					return uifm_dchkbox_var.innerVars[name];
				} else {
					return '';
				}
			};
			uiformDCheckbox.prototype.optChecked = function(value) {
				if (typeof value === 'undefined') {
					return this.options.opt_checked;
				}
				this.options.opt_checked = value;
				return this.$element;
			};
			uiformDCheckbox.prototype.man_optChecked = function(value) {
				this.optChecked(value);
				this._enableCheckboxVal(this.$opt_gal_checkbox, this);
				this._setValToChkBoxInput(this);
				return this.$element;
			};

			uiformDCheckbox.prototype.man_mod2_refresh = function() {
				this._mod2_initPreview();
			};

			uiformDCheckbox.prototype.optQtySt = function(value) {
				if (typeof value === 'undefined') {
					return this.options.opt_qtySt;
				}
				this.options.opt_qtySt = value;
				return this.$element;
			};
			uiformDCheckbox.prototype.man_optQtySt = function(value) {
				this.optQtySt(value);
				if (value && parseInt(this.options.opt_checked)) {
					this.$spinner_wrapper.show();
				} else {
					this.$spinner_wrapper.hide();
				}
				return this.$element;
			};
			uiformDCheckbox.prototype.refreshImgs = function() {
				if (parseInt(this.options.opt_laymode) === 2) {
					this._mod2_initPreview();
				} else {
					this._getImageToCanvas(this.$element, 0, this);
				}

				return this.$element;
			};
			uiformDCheckbox.prototype.optQtyMax = function(value) {
				if (typeof value === 'undefined') {
					return this.options.opt_qtyMax;
				}
				this.options.opt_qtyMax = value;
				return this.$element;
			};
			uiformDCheckbox.prototype.man_optQtyMax = function(value) {
				this.optQtyMax(value);
				this.$inp_checkbox_max.val(value);

				return this.$element;
			};
			uiformDCheckbox.prototype.onInit = function(value) {
				if (typeof value === 'undefined') {
					return this.options.onInit;
				}
				if (!value) {
					value = $.fn.uiformDCheckbox.defaults.onInit;
				}
				this.options.onInit = value;
				return this.$element;
			};

			uiformDCheckbox.prototype.onSwitchChange = function(value) {
				if (typeof value === 'undefined') {
					return this.options.onSwitchChange;
				}
				if (!value) {
					value = $.fn.uiformDCheckbox.defaults.onSwitchChange;
				}

				this.options.onSwitchChange = value;
				return this.$element;
			};

			uiformDCheckbox.prototype.get_totalCost = function() {
				var total;
				var input_spinner = this.$element.find('.uifm-dcheckbox-item-qty-num');
				total = parseFloat(input_spinner.val()) * parseFloat(this.options.opt_price);
				return total;
			};
			uiformDCheckbox.prototype.get_labelOpt = function() {
				return this.options.opt_label;
			};
			uiformDCheckbox.prototype.onCostCalcProcess = function() {
				var obj_form = this.$element.closest('.rockfm-form');
				zgfm_front_cost.costest_refresh(obj_form);

				return this.$element;
			};

			uiformDCheckbox.prototype.destroy = function() {
				var $form;
				$form = this.$element.closest('form');
				if ($form.length) {
					$form.off('reset.uiformDCheckbox').removeData('uifm-dynamic-checkbox');
				}
				this.$container
					.children()
					.not(this.$element)
					.remove();
				this.$element
					.unwrap()
					.unwrap()
					.off('.uiformDCheckbox')
					.removeData('uifm-dynamic-checkbox');
				return this.$element;
			};

			uiformDCheckbox.prototype._elementHandlers = function() {
				return this.$element.on({
					'change.uiformDCheckbox': (function(_this) {
						return function(e, checked) {
							e.preventDefault();
							e.stopImmediatePropagation();
							_this.onCostCalcProcess();
							return _this.$element;
						};
					})(this),
					'hover.uiformDCheckbox': (function(_this) {
						return function(e) {
							e.preventDefault();
						};
					})(this),
					'focus.uiformDCheckbox': (function(_this) {
						return function(e) {
							e.preventDefault();
						};
					})(this),
					'blur.uiformDCheckbox': (function(_this) {
						return function(e) {
							e.preventDefault();
						};
					})(this),
					'keydown.uiformDCheckbox': (function(_this) {})(this),
				});
			};
			uiformDCheckbox.prototype._elementHandlers2 = function() {
				return this.$element.on({
					'mouseover.uiformDCheckbox': (function(_this) {
						return function(e) {
							e.preventDefault();

							if (parseInt(_this.options.opt_laymode) === 2) {
								if (parseInt(_this.options.opt_checked) === 0) {
									_this._mode2_get_img(_this.$element, 1);
								}
							}
						};
					})(this),
					'mouseout.uiformDCheckbox': (function(_this) {
						return function(e) {
							e.preventDefault();

							if (parseInt(_this.options.opt_laymode) === 2) {
								if (parseInt(_this.options.opt_checked) === 1) {
									_this._mode2_get_img(_this.$element, 0);
								} else {
									_this._mode2_get_img(_this.$element, 2);
								}
							}
						};
					})(this),
				});
			};
			uiformDCheckbox.prototype._galleryHandlers = function() {
				this.$opt_gal_next_img.on(
					'click.uiformDCheckbox',
					(function(_this) {
						return function(e) {
							e.preventDefault();
							if (parseInt(_this.options.opt_isradiobtn) === 1) {
								_this._getImageToCanvas($(this).closest('.uifm-dradiobtn-item'), 2, _this);
							} else {
								_this._getImageToCanvas($(this).closest('.uifm-dcheckbox-item'), 2, _this);
							}
						};
					})(this)
				);

				this.$opt_gal_prev_img.on(
					'click.uiformDCheckbox',
					(function(_this) {
						return function(e) {
							e.preventDefault();
							if (parseInt(_this.options.opt_isradiobtn) === 1) {
								_this._getImageToCanvas($(this).closest('.uifm-dradiobtn-item'), 1, _this);
							} else {
								_this._getImageToCanvas($(this).closest('.uifm-dcheckbox-item'), 1, _this);
							}
						};
					})(this)
				);
			};

			uiformDCheckbox.prototype._handleHandlers = function() {
				this.$opt_gal_btn_show.on(
					'click.uiformDCheckbox',
					(function(_this) {
						return function(e) {
							e.preventDefault();

							var borderless = true;

							$('#' + _this.options.baseGalleryId).data('useBootstrapModal', !borderless);
							$('#' + _this.options.baseGalleryId).data('container', '#' + _this.options.baseGalleryId);
							$('#' + _this.options.baseGalleryId).toggleClass('blueimp-gallery-controls', borderless);
							blueimp.Gallery(_this.$opt_gal_links_a, $('#' + _this.options.baseGalleryId).data());
						};
					})(this)
				);

				this.$opt_gal_checkbox.on(
					'click.uiformDCheckbox',
					(function(_this) {
						return function(e) {
							e.preventDefault();

							if (parseInt(_this.options.opt_isradiobtn) === 1) {
								var tmp_index = $(this)
									.closest('.uifm-dradiobtn-item')
									.attr('data-inp17-opt-index');

								if (parseInt(_this_obj.options.backend) === 1) {
									var tmp_container = $(this).closest('.uifm-input17-wrap');
								} else {
									var tmp_container = $(this).closest('.rockfm-input17-wrap');
								}

								var tmp_radiobtn_items = tmp_container.find('.uifm-dradiobtn-item');

								var tmp_item_index;
								tmp_radiobtn_items.each(function(i) {
									tmp_item_index = $(this).attr('data-inp17-opt-index');

									if (parseInt(tmp_item_index) === parseInt(tmp_index)) {

										$(this).uiformDCheckbox('man_optChecked', 1);
									} else {

										$(this).uiformDCheckbox('man_optChecked', 0);
									}

									if (parseInt(_this.options.opt_laymode) === 2) {
										$(this).uiformDCheckbox('man_mod2_refresh');
									}
								});
							} else {
								_this._gen_optChecked(this, _this);
								_this._enableCheckboxVal(this, _this);
								_this._setValToChkBoxInput(_this);
							}

							return _this.$element.trigger('change.uiformDCheckbox');
						};
					})(this)
				);

				this.$opt_gal_box.on(
					'click.uiformDCheckbox',
					(function(_this) {
						return function(e) {
							e.preventDefault();

							if (parseInt(_this.options.opt_isradiobtn) === 1) {
								var tmp_index = $(this)
									.closest('.uifm-dradiobtn-item')
									.attr('data-inp17-opt-index');

								if (parseInt(_this_obj.options.backend) === 1) {
									var tmp_container = $(this).closest('.uifm-input17-wrap');
								} else {
									var tmp_container = $(this).closest('.rockfm-input17-wrap');
								}
								var tmp_radiobtn_items = tmp_container.find('.uifm-dradiobtn-item');

								var tmp_item_index;
								tmp_radiobtn_items.each(function(i) {
									tmp_item_index = $(this).attr('data-inp17-opt-index');

									if (parseInt(tmp_item_index) === parseInt(tmp_index)) {
										$(this).uiformDCheckbox('man_optChecked', 1);
									} else {
										$(this).uiformDCheckbox('man_optChecked', 0);
									}

									if (parseInt(_this.options.opt_laymode) === 2) {
										$(this).uiformDCheckbox('man_mod2_refresh');
									}
								});
							} else {
								_this._gen_optChecked(_this.$opt_gal_checkbox, _this);
								_this._enableCheckboxVal(_this.$opt_gal_checkbox, _this);
								_this._setValToChkBoxInput(_this);
							}

							return _this.$element.trigger('change.uiformDCheckbox');
						};
					})(this)
				);

				this.$inp_checkbox_max.on(
					'keyup',
					(function(_this) {
						return function(e) {
							e.preventDefault();
							_this._setValToChkBoxInput(_this);
							return _this.$element.trigger('change.uiformDCheckbox');
						};
					})(this)
				);

				this.$spinner_buttons.on(
					'click.uiformDCheckbox',
					(function(_this) {
						return function(e) {
							e.preventDefault();
							_this._spinnerCounter(this, _this);
							_this._setValToChkBoxInput(_this);
							return _this.$element.trigger('change.uiformDCheckbox');
						};
					})(this)
				);

			};

			uiformDCheckbox.prototype._spinnerCounter = function(el, _this) {
				var objbtn = $(el);
				var input_spinner = _this.$element.find('.uifm-dcheckbox-item-qty-num');
				var input_visible_spinner = _this.$element.find('.uifm-dfield-input');
				if (_this.$element.find('.uifm-dcheckbox-item-qty-wrap button').hasClass('dcheckbox-disabled')) {
					_this.$element.find('.uifm-dcheckbox-item-qty-wrap button').removeClass('dcheckbox-disabled');
				}

				if (objbtn.attr('data-value') == 'increase') {
					if (input_spinner.attr('data-max') == undefined || parseInt(input_spinner.val()) < parseInt(input_spinner.attr('data-max'))) {
						input_visible_spinner.text(parseInt(input_spinner.val()) + 1);
						input_spinner.val(parseInt(input_spinner.val()) + 1);
						if (parseInt(input_spinner.val()) === parseInt(input_spinner.attr('data-max'))) {
							objbtn.addClass('dcheckbox-disabled');
						}
					} else {
						objbtn.addClass('dcheckbox-disabled');
					}
				} else {
					if (input_spinner.attr('data-min') == undefined || parseInt(input_spinner.val()) > parseInt(input_spinner.attr('data-min'))) {
						input_visible_spinner.text(parseInt(input_spinner.val()) - 1);
						input_spinner.val(parseInt(input_spinner.val()) - 1);
						if (parseInt(input_spinner.val()) === parseInt(input_spinner.attr('data-min'))) {
							objbtn.addClass('dcheckbox-disabled');
						}
					} else {
						objbtn.addClass('dcheckbox-disabled');
					}
				}
			};
			uiformDCheckbox.prototype._gen_optChecked = function(el, _this) {
				var objbtn = $(el);
				if (objbtn.hasClass('uifm-dcheckbox-checked')) {
					_this.optChecked(0);
				} else {
					_this.optChecked(1);
				}
			};
			uiformDCheckbox.prototype._setValToChkBoxInput = function(_this) {
				_this.$inp_checkbox.val(_this.$inp_checkbox_max.val());
			};
			uiformDCheckbox.prototype._enableCheckboxVal = function(el, _this) {
				var objbtn = $(el);
				if (parseInt(this.options.opt_checked) === 0) {
					if (parseInt(this.options.opt_isradiobtn) === 1) {
						objbtn.removeClass('uifm-dcheckbox-checked').html('<i class="fa fa-circle-o"></i>');
					} else {
						objbtn.removeClass('uifm-dcheckbox-checked').html('<i class="fa fa-square-o"></i>');
					}
					_this.$inp_checkbox.prop('checked', false);
					if (_this.$spinner_wrapper && parseInt(_this.options.opt_qtySt) === 1) {
						_this.$spinner_wrapper.hide();
					}
				} else {
					if (parseInt(this.options.opt_isradiobtn) === 1) {
						objbtn.addClass('uifm-dcheckbox-checked').html('<i class="fa fa-check-circle-o"></i>');
					} else {
						objbtn.addClass('uifm-dcheckbox-checked').html('<i class="fa fa-check-square-o"></i>');
					}
					_this.$inp_checkbox.prop('checked', true);
					if (_this.$spinner_wrapper && parseInt(_this.options.opt_qtySt) === 1) {
						_this.$spinner_wrapper.show();
					}
				}
			};

			uiformDCheckbox.prototype._getClasses = function(classes) {
				var c, cls, _i, _len;
				if (!$.isArray(classes)) {
					return ['' + this.options.baseClass + '-' + classes];
				}
				cls = [];
				for (_i = 0, _len = classes.length; _i < _len; _i++) {
					c = classes[_i];
					cls.push('' + this.options.baseClass + '-' + c);
				}
				return cls;
			};

			return uiformDCheckbox;
		})();
		$.fn.uiformDCheckbox = function() {
			var args, option, ret;
			(option = arguments[0]), (args = 2 <= arguments.length ? __slice.call(arguments, 1) : []);
			ret = this;
			this.each(function() {
				var $this, data;
				$this = $(this);
				data = $this.data('uifm-dynamic-checkbox');
				if (!data) {
					$this.data('uifm-dynamic-checkbox', (data = new uiformDCheckbox(this, option)));
				}
				if (typeof option === 'string') {
					return (ret = data[option].apply(data, args));
				}
			});
			return ret;
		};
		$.fn.uiformDCheckbox.Constructor = uiformDCheckbox;
		return ($.fn.uiformDCheckbox.defaults = {
			backend: '1',
			opt_isradiobtn: '0',
			baseClass: 'uifm-dynamic-checkbox',
			onInit: function() {},
			onSwitchChange: function() {},
		});
	})(window.$uifm, window);
}.call(this));

(function(factory) {
	if (typeof define === 'function' && define.amd) {
		define(['jquery'], factory);
	} else if (typeof module === 'object' && module.exports) {
		factory(require('jquery'));
	} else {
		factory(jQuery);
	}
})(function($) {
	var debugMode = false;

	var isOperaMini = Object.prototype.toString.call(window.operamini) === '[object OperaMini]';
	var isInputSupported = 'placeholder' in document.createElement('input') && !isOperaMini && !debugMode;
	var isTextareaSupported = 'placeholder' in document.createElement('textarea') && !isOperaMini && !debugMode;
	var valHooks = $.valHooks;
	var propHooks = $.propHooks;
	var hooks;
	var placeholder;
	var settings = {};

	if (isInputSupported && isTextareaSupported) {
		placeholder = $.fn.placeholder = function() {
			return this;
		};

		placeholder.input = true;
		placeholder.textarea = true;
	} else {
		placeholder = $.fn.placeholder = function(options) {
			var defaults = { customClass: 'placeholder' };
			settings = $.extend({}, defaults, options);

			return this.filter((isInputSupported ? 'textarea' : ':input') + '[' + (debugMode ? 'placeholder-x' : 'placeholder') + ']')
				.not('.' + settings.customClass)
				.not(':radio, :checkbox, [type=hidden]')
				.bind({
					'focus.placeholder': clearPlaceholder,
					'blur.placeholder': setPlaceholder,
				})
				.data('placeholder-enabled', true)
				.trigger('blur.placeholder');
		};

		placeholder.input = isInputSupported;
		placeholder.textarea = isTextareaSupported;

		hooks = {
			get: function(element) {
				var $element = $(element);
				var $passwordInput = $element.data('placeholder-password');

				if ($passwordInput) {
					return $passwordInput[0].value;
				}

				return $element.data('placeholder-enabled') && $element.hasClass(settings.customClass) ? '' : element.value;
			},
			set: function(element, value) {
				var $element = $(element);
				var $replacement;
				var $passwordInput;

				if (value !== '') {
					$replacement = $element.data('placeholder-textinput');
					$passwordInput = $element.data('placeholder-password');

					if ($replacement) {
						clearPlaceholder.call($replacement[0], true, value) || (element.value = value);
						$replacement[0].value = value;
					} else if ($passwordInput) {
						clearPlaceholder.call(element, true, value) || ($passwordInput[0].value = value);
						element.value = value;
					}
				}

				if (!$element.data('placeholder-enabled')) {
					element.value = value;
					return $element;
				}

				if (value === '') {
					element.value = value;

					if (element != safeActiveElement()) {
						setPlaceholder.call(element);
					}
				} else {
					if ($element.hasClass(settings.customClass)) {
						clearPlaceholder.call(element);
					}

					element.value = value;
				}
				return $element;
			},
		};

		if (!isInputSupported) {
			valHooks.input = hooks;
			propHooks.value = hooks;
		}

		if (!isTextareaSupported) {
			valHooks.textarea = hooks;
			propHooks.value = hooks;
		}

		$(function() {
			$(document).delegate('form', 'submit.placeholder', function() {
				var $inputs = $('.' + settings.customClass, this).each(function() {
					clearPlaceholder.call(this, true, '');
				});

				setTimeout(function() {
					$inputs.each(setPlaceholder);
				}, 10);
			});
		});

		$(window).bind('beforeunload.placeholder', function() {
			var clearPlaceholders = true;

			try {
				if (document.activeElement.toString() === 'javascript:void(0)') {
					clearPlaceholders = false;
				}
			} catch (exception) {}

			if (clearPlaceholders) {
				$('.' + settings.customClass).each(function() {
					this.value = '';
				});
			}
		});
	}

	function args(elem) {
		var newAttrs = {};
		var rinlinejQuery = /^jQuery\d+$/;

		$.each(elem.attributes, function(i, attr) {
			if (attr.specified && !rinlinejQuery.test(attr.name)) {
				newAttrs[attr.name] = attr.value;
			}
		});

		return newAttrs;
	}

	function clearPlaceholder(event, value) {
		var input = this;
		var $input = $(this);

		if (input.value === $input.attr(debugMode ? 'placeholder-x' : 'placeholder') && $input.hasClass(settings.customClass)) {
			input.value = '';
			$input.removeClass(settings.customClass);

			if ($input.data('placeholder-password')) {
				$input = $input
					.hide()
					.nextAll('input[type="password"]:first')
					.show()
					.attr('id', $input.removeAttr('id').data('placeholder-id'));

				if (event === true) {
					$input[0].value = value;

					return value;
				}

				$input.focus();
			} else {
				input == safeActiveElement() && input.select();
			}
		}
	}

	function setPlaceholder(event) {
		var $replacement;
		var input = this;
		var $input = $(this);
		var id = input.id;

		if (event && event.type === 'blur' && $input.hasClass(settings.customClass)) {
			return;
		}

		if (input.value === '') {
			if (input.type === 'password') {
				if (!$input.data('placeholder-textinput')) {
					try {
						$replacement = $input.clone().prop({ type: 'text' });
					} catch (e) {
						$replacement = $('<input>').attr($.extend(args(this), { type: 'text' }));
					}

					$replacement
						.removeAttr('name')
						.data({
							'placeholder-enabled': true,
							'placeholder-password': $input,
							'placeholder-id': id,
						})
						.bind('focus.placeholder', clearPlaceholder);

					$input
						.data({
							'placeholder-textinput': $replacement,
							'placeholder-id': id,
						})
						.before($replacement);
				}

				input.value = '';
				$input = $input
					.removeAttr('id')
					.hide()
					.prevAll('input[type="text"]:first')
					.attr('id', $input.data('placeholder-id'))
					.show();
			} else {
				var $passwordInput = $input.data('placeholder-password');

				if ($passwordInput) {
					$passwordInput[0].value = '';
					$input
						.attr('id', $input.data('placeholder-id'))
						.show()
						.nextAll('input[type="password"]:last')
						.hide()
						.removeAttr('id');
				}
			}

			$input.addClass(settings.customClass);
			$input[0].value = $input.attr(debugMode ? 'placeholder-x' : 'placeholder');
		} else {
			$input.removeClass(settings.customClass);
		}
	}

	function safeActiveElement() {
		try {
			return document.activeElement;
		} catch (exception) {}
	}
});
