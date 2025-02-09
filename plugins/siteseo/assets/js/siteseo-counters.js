//Init tabs
jQuery(document).ready(function(){
    if(jQuery("#siteseo-ca-tabs .wrap-ca-list").length){
        jQuery("#siteseo-ca-tabs .hidden").removeClass("hidden");
        jQuery("#siteseo-ca-tabs").tabs();
    }
});

function siteseo_titles_counters(){
   
   const $ = jQuery;
   
    let elementTitleMeta = $("#siteseo_titles_title_meta");
	
    if(elementTitleMeta.length) {

        if($(".snippet-title-custom:visible").length > 0){
            elementTitleMeta = $(".snippet-title-custom");
        } else if ($(".snippet-title:visible").length > 0) {
            elementTitleMeta = $(".snippet-title");
        } else if ($(".snippet-title-default:visible").length > 0) {
            elementTitleMeta = $(".snippet-title-default");
        }

        var meta_title_val = elementTitleMeta.val();
        var meta_title_placeholder = $("#siteseo_titles_title_meta").attr(
            "placeholder"
        );

        $("#siteseo_titles_title_counters").after(
            '<div id="siteseo_titles_title_counters_val">/ 60</div>'
        ),
            meta_title_val.length > 0
                ? ($("#siteseo_titles_title_counters").text(
                    meta_title_val.length
                ),
                    $("#siteseo_titles_title_pixel").text(
                        pixelTitle(meta_title_val)
                    ))
                : meta_title_placeholder.length &&
                ($("#siteseo_titles_title_counters").text(
                    meta_title_placeholder.length
                ),
                    $("#siteseo_titles_title_pixel").text(
                        pixelTitle(meta_title_placeholder)
                    )),
            meta_title_val.length > 60
                ? $("#siteseo_titles_title_counters").css("color", "red")
                : meta_title_placeholder.length > 60 &&
                $("#siteseo_titles_title_counters").css("color", "red"),
            pixelTitle(meta_title_val) > 568
                ? $("#siteseo_titles_title_pixel").css("color", "red")
                : pixelTitle(meta_title_placeholder) > 568 &&
                $("#siteseo_titles_title_pixel").css("color", "red");

        if (meta_title_val.length) {
            var progress = Math.round((pixelTitle(meta_title_val) / 568) * 100);
        } else {
            var progress = Math.round(
                (pixelTitle(meta_title_placeholder) / 568) * 100
            );
        }

        if (progress >= 100) {
            progress = 100;
        }

        $("#siteseo_titles_title_counters_progress").attr(
            "aria-valuenow",
            progress
        ),
		
		$("#siteseo_titles_title_counters_progress").text(progress + "%"),
		$("#siteseo_titles_title_counters_progress").css(
			"width",
			progress + "%"
		),
		$(
			"#siteseo_titles_title_meta, #siteseo-tag-single-title, #siteseo-tag-single-site-title, #siteseo-tag-single-sep"
		).on("keyup paste change click", function (e) {
			var meta_title_val = $("#siteseo_titles_title_meta").val();
			if ($(".snippet-title-custom:visible").length > 0) {
				meta_title_val = $(".snippet-title-custom").text();
			} else if ($(".snippet-title:visible").length > 0) {
				meta_title_val = $(".snippet-title").text();
			} else if ($(".snippet-title-default:visible").length > 0) {
				meta_title_val = $(".snippet-title-default").text();
			}

			var meta_title_placeholder = $(
				"#siteseo_titles_title_meta"
			).attr("placeholder");

			$("#siteseo_titles_title_counters").css("color", "inherit"),
				$("#siteseo_titles_title_pixel").css("color", "inherit"),
				meta_title_val.length > 60 &&
				$("#siteseo_titles_title_counters").css(
					"color",
					"red"
				),
				pixelTitle(meta_title_val) > 568 &&
				$("#siteseo_titles_title_pixel").css("color", "red");

			if (meta_title_val.length == 0) {
				meta_title_placeholder.length > 60 &&
					$("#siteseo_titles_title_counters").css(
						"color",
						"red"
					),
					pixelTitle(meta_title_placeholder) > 568 &&
					$("#siteseo_titles_title_pixel").css(
						"color",
						"red"
					);
			}

			meta_title_val.length > 0
				? ($("#siteseo_titles_title_counters").text(
					meta_title_val.length
				),
					$("#siteseo_titles_title_pixel").text(
						pixelTitle(meta_title_val)
					))
				: meta_title_placeholder.length &&
				($("#siteseo_titles_title_counters").text(
					meta_title_placeholder.length
				),
					$("#siteseo_titles_title_pixel").text(
						pixelTitle(meta_title_placeholder)
					));

			if (meta_title_val.length) {
				var progress = Math.round(
					(pixelTitle(meta_title_val) / 568) * 100
				);
			} else {
				var progress = Math.round(
					(pixelTitle(meta_title_placeholder) / 568) * 100
				);
			}

			if (progress >= 100) {
				progress = 100;
			}

			$("#siteseo_titles_title_counters_progress").attr(
				"aria-valuenow",
				progress
			),
				$("#siteseo_titles_title_counters_progress").text(
					progress + "%"
				),
				$("#siteseo_titles_title_counters_progress").css(
					"width",
					progress + "%"
				);
		});
    }
}

function siteseo_meta_desc_counters(){
    const $ = jQuery;
    if ($("#siteseo_titles_desc_meta").length) {
        var meta_desc_val = $("#siteseo_titles_desc_meta").val();
        var meta_desc_placeholder = $("#siteseo_titles_desc_meta").attr(
            "placeholder"
        );

        $("#siteseo_titles_desc_counters").after(
            '<div id="siteseo_titles_desc_counters_val">/ 160</div>'
        ),
            meta_desc_val.length > 0
                ? ($("#siteseo_titles_desc_counters").text(
                    meta_desc_val.length
                ),
                    $("#siteseo_titles_desc_pixel").text(
                        pixelDesc(meta_desc_val)
                    ))
                : meta_desc_placeholder.length &&
                ($("#siteseo_titles_desc_counters").text(
                    meta_desc_placeholder.length
                ),
                    $("#siteseo_titles_desc_pixel").text(
                        pixelDesc(meta_desc_placeholder)
                    )),
            meta_desc_val.length > 160
                ? $("#siteseo_titles_desc_counters").css("color", "red")
                : meta_desc_placeholder.length > 160 &&
                $("#siteseo_titles_desc_counters").css("color", "red"),
            pixelDesc(meta_desc_val) > 940
                ? $("#siteseo_titles_desc_pixel").css("color", "red")
                : pixelDesc(meta_desc_placeholder) > 940 &&
                $("#siteseo_titles_desc_pixel").css("color", "red");

        if (meta_desc_val.length) {
            var progress = Math.round((pixelDesc(meta_desc_val) / 940) * 100);
        } else {
            var progress = Math.round(
                (pixelDesc(meta_desc_placeholder) / 940) * 100
            );
        }

        if (progress >= 100) {
            progress = 100;
        }

        $("#siteseo_titles_desc_counters_progress").attr(
            "aria-valuenow",
            progress
        ),
            $("#siteseo_titles_desc_counters_progress").text(progress + "%"),
            $("#siteseo_titles_desc_counters_progress").css(
                "width",
                progress + "%"
            ),
            $("#siteseo_titles_desc_meta, #siteseo-tag-single-excerpt").on(
                "keyup paste change click",
                function (e) {
                    var meta_desc_val = $("#siteseo_titles_desc_meta").val();
                    var meta_desc_placeholder = $(
                        "#siteseo_titles_desc_meta"
                    ).attr("placeholder");

                    $("#siteseo_titles_desc_counters").css(
                        "color",
                        "inherit"
                    ),
                        $("#siteseo_titles_desc_pixel").css(
                            "color",
                            "inherit"
                        ),
                        meta_desc_val.length > 160 &&
                        $("#siteseo_titles_desc_counters").css(
                            "color",
                            "red"
                        ),
                        pixelDesc(meta_desc_val) > 940 &&
                        $("#siteseo_titles_desc_pixel").css(
                            "color",
                            "red"
                        );

                    if (meta_desc_val.length == 0) {
                        meta_desc_placeholder.length > 160 &&
                            $("#siteseo_titles_desc_counters").css(
                                "color",
                                "red"
                            ),
                            pixelDesc(meta_desc_placeholder) > 940 &&
                            $("#siteseo_titles_desc_pixel").css(
                                "color",
                                "red"
                            );
                    }

                    meta_desc_val.length > 0
                        ? ($("#siteseo_titles_desc_counters").text(
                            meta_desc_val.length
                        ),
                            $("#siteseo_titles_desc_pixel").text(
                                pixelDesc(meta_desc_val)
                            ))
                        : meta_desc_placeholder.length &&
                        ($("#siteseo_titles_desc_counters").text(
                            meta_desc_placeholder.length
                        ),
                            $("#siteseo_titles_desc_pixel").text(
                                pixelDesc(meta_desc_placeholder)
                            )),
                        meta_desc_val.length > 0
                            ? ($(".snippet-description-custom").text(
                                e.target.value.substr(0, 160) + '...',
                            ),
                                $(".snippet-description").hide(),
                                $(".snippet-description-custom").css(
                                    "display",
                                    "inline"
                                ),
                                $(".snippet-description-default").hide())
                            : 0 == meta_desc_val.length &&
                            ($(".snippet-description-default").css(
                                "display",
                                "inline"
                            ),
                                $(".snippet-description-custom").hide(),
                                $(".snippet-description").hide());

                    if (meta_desc_val.length) {
                        var progress = Math.round(
                            (pixelDesc(meta_desc_val) / 940) * 100
                        );
                    } else {
                        var progress = Math.round(
                            (pixelDesc(meta_desc_placeholder) / 940) * 100
                        );
                    }

                    if (progress >= 100) {
                        progress = 100;
                    }

                    $("#siteseo_titles_desc_counters_progress").attr(
                        "aria-valuenow",
                        progress
                    ),
                        $("#siteseo_titles_desc_counters_progress").text(
                            progress + "%"
                        ),
                        $("#siteseo_titles_desc_counters_progress").css(
                            "width",
                            progress + "%"
                        );
                }
            ),
            $("#excerpt, .editor-post-excerpt textarea").keyup(function (e) {
                var meta_desc_val = $("#siteseo_titles_desc_meta").val();
                var meta_desc_placeholder = $(
                    "#siteseo_titles_desc_meta"
                ).attr("placeholder");

                0 == meta_desc_val.length &&
                    0 == $(".snippet-description-custom").val().length &&
                    ($(".snippet-description-custom").text(e.target.value),
                        $(".snippet-description").hide(),
                        $(".snippet-description-custom").css("display", "inline"),
                        $(".snippet-description-default").hide());

                if (meta_desc_val.length) {
                    var progress = meta_desc_val.length;
                } else {
                    var progress = meta_desc_placeholder.length;
                }
                if (progress >= 100) {
                    progress = 100;
                }

                $("#siteseo_titles_desc_counters_progress").attr(
                    "aria-valuenow",
                    progress
                ),
				$("#siteseo_titles_desc_counters_progress").text(
					progress + "%"
				),
				$("#siteseo_titles_desc_counters_progress").css(
					"width",
					progress + "%"
				);
            });
    }
}

function pixelTitle(e) {
    inputText = e;
    font = "20px Arial";
	
    canvas = document.createElement("canvas");
    context = canvas.getContext("2d");
    context.font = font;
    width = context.measureText(inputText).width;
    formattedWidth = Math.ceil(width);

    return formattedWidth;
}

function pixelDesc(e) {
    inputText = e;
    font = "14px Arial";
	
    canvas = document.createElement("canvas");
    context = canvas.getContext("2d");
    context.font = font;
    width = context.measureText(inputText).width;
    formattedWidth = Math.ceil(width);

    return formattedWidth;
}

function siteseo_is_valid_url(string) {
    var res = string.match(
        /(http(s)?:\/\/.)?(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/g
    );
    return res !== null;
}

function siteseo_social_img(social_slug) {
    const $ = jQuery;
    if ($("#siteseo_social_fb_title_meta").length) {
        $(".snippet-" + social_slug + "-img-alert").hide();
        var meta_img_val = $(
            "#siteseo_social_" + social_slug + "_img_meta"
        ).val();

        if (meta_img_val == "") {
            var meta_img_val = $(
                "#siteseo_social_" + social_slug + "_img_meta"
            ).attr("placeholder");
        }

        // Check valid URL
        if (siteseo_is_valid_url(meta_img_val) === true) {
            meta_img_val.length > 0
                ? ($(".snippet-" + social_slug + "-img-custom img").attr("src", meta_img_val),
                    $(".snippet-" + social_slug + "-img").hide(),
                    $(".snippet-" + social_slug + "-img-custom").show(),
                    $(".snippet-" + social_slug + "-img-default").hide())
                : 0 == meta_img_val.length &&
                ($(".snippet-" + social_slug + "-img-default").show(),
                    $(".snippet-" + social_slug + "-img-custom").show(),
                    $(".snippet-" + social_slug + "-img").hide());

            if (meta_img_val.length > 0) {
                // Check file URL
                $
                    .get(meta_img_val)
                    .done(function () {
                        // Extract filetype
                        var meta_img_filetype = meta_img_val
                            .split(/\#|\?/)[0]
                            .split(".")
                            .pop()
                            .trim();
                        var types = ["jpg", "jpeg", "gif", "png", "webp"];

                        if (types.indexOf(meta_img_filetype) == -1) {
                            $(".snippet-" + social_slug + "-img-alert.alert1").show();
                        } else {
                            // Extract image size
                            var tmp_img = new Image();
                            tmp_img.src = meta_img_val;
                            $(tmp_img).one("load", function () {
                                pic_real_width = parseInt(tmp_img.width);
                                pic_real_height = parseInt(tmp_img.height);

                                // Default minimum size
                                if (social_slug == "fb") {
                                    (min_width = 200), (min_height = 200);
                                } else {
                                    (min_width = 144), (min_height = 144);
                                }
                                if (
                                    pic_real_width < min_width ||
                                    pic_real_height < min_height
                                ) {
                                    $(
                                        ".snippet-" +
                                        social_slug +
                                        "-img-alert.alert2"
                                    ).show();
                                }
                                ratio_img = (
                                    pic_real_width / pic_real_height
                                ).toFixed(2);
                                $(
                                    ".snippet-" + social_slug + "-img-alert.alert4"
                                ).show();
                                $(
                                    ".snippet-" +
                                    social_slug +
                                    "-img-alert.alert4 span"
                                ).text(ratio_img);
                            });
                            // check filesize
                            fetch(meta_img_val)
                                .then(response => {
                                    const fileSize = Number(response.headers.get('Content-Length'));
                                    if ((fileSize / 1024) > 300) {
                                        $(".snippet-" + social_slug + "-img-alert.alert6").show();
                                        $(".snippet-" + social_slug + "-img-alert.alert6 span").text(Math.round(fileSize / 1024) + 'KB.');
                                    }
                                })
                                .catch(error => {
                                    console.error(error);
                                });
                        }
                    })
                    .fail(function () {
                        $(".snippet-" + social_slug + "-img-alert.alert3").show();
                    });
            }
        } else {
            $(".snippet-" + social_slug + "-img-alert.alert5").show();
        }
    }
}

async function siteseo_social() {
    const $ = jQuery;
    if ($("#siteseo_social_fb_title_meta").length) {
        // FACEBOOK
        $(
            "#siteseo_social_fb_title_meta, #siteseo-tag-single-title, #siteseo-tag-single-site-title, #siteseo-tag-single-sep"
        ).on("keyup paste change click", function (e) {
            var meta_fb_title_val = $("#siteseo_social_fb_title_meta").val();

            meta_fb_title_val.length > 0
                ? ($(".snippet-fb-title-custom").text(e.target.value),
                    $(".snippet-fb-title").hide(),
                    $(".snippet-fb-title-custom").show(),
                    $(".snippet-fb-title-default").hide())
                : 0 == meta_fb_title_val.length &&
                ($(".snippet-fb-title-default").show(),
                    $(".snippet-fb-title-custom").hide(),
                    $(".snippet-fb-title").hide());
        });

        $("#siteseo_social_fb_desc_meta").on(
            "keyup paste change click",
            function (e) {
                var meta_fb_desc_val = $("#siteseo_social_fb_desc_meta").val();

                meta_fb_desc_val.length > 0
                    ? ($(".snippet-fb-description-custom").text(
                        e.target.value
                    ),
                        $(".snippet-fb-description").hide(),
                        $(".snippet-fb-description-custom").show(),
                        $(".snippet-fb-description-default").hide())
                    : 0 == meta_fb_desc_val.length &&
                    ($(".snippet-fb-description-default").show(),
                        $(".snippet-fb-description-custom").hide(),
                        $(".snippet-fb-description").hide());
            }
        );

        siteseo_social_img("fb");
        $("#siteseo_social_fb_img_meta").on(
            "keyup paste change click",
            function () {
                siteseo_social_img("fb");
            }
        );

        // TWITTER
        $("#siteseo_social_twitter_title_meta").on(
            "keyup paste change click",
            function (e) {
                var meta_fb_title_val = $(
                    "#siteseo_social_twitter_title_meta"
                ).val();

                meta_fb_title_val.length > 0
                    ? ($(".snippet-twitter-title-custom").text(e.target.value),
                        $(".snippet-twitter-title").hide(),
                        $(".snippet-twitter-title-custom").show(),
                        $(".snippet-twitter-title-default").hide())
                    : 0 == meta_fb_title_val.length &&
                    ($(".snippet-twitter-title-default").show(),
                        $(".snippet-twitter-title-custom").hide(),
                        $(".snippet-twitter-title").hide());
            }
        );

        $("#siteseo_social_twitter_desc_meta").on(
            "keyup paste change click",
            function (e) {
                var meta_fb_desc_val = $(
                    "#siteseo_social_twitter_desc_meta"
                ).val();

                meta_fb_desc_val.length > 0
                    ? ($(".snippet-twitter-description-custom").text(
                        e.target.value
                    ),
                        $(".snippet-twitter-description").hide(),
                        $(".snippet-twitter-description-custom").show(),
                        $(".snippet-twitter-description-default").hide())
                    : 0 == meta_fb_desc_val.length &&
                    ($(".snippet-twitter-description-default").show(),
                        $(".snippet-twitter-description-custom").hide(),
                        $(".snippet-twitter-description").hide());
            }
        );

        siteseo_social_img("twitter");
        $("#siteseo_social_twitter_img_meta").on(
            "keyup paste change click",
            function () {
                siteseo_social_img("twitter");
            }
        );
    }
}

// Content Analysis - Toggle
function siteseo_ca_toggle() {
    const $ = jQuery;
    var stop = false;
    $(".gr-analysis-title .btn-toggle").on("click", function (e) {
        if (stop) {
            event.stopImmediatePropagation();
            event.preventDefault();
            stop = false;
        }
        $(this).toggleClass("open");
        $(this).attr('aria-expanded', ($(this).attr('aria-expanded') == "false" ? true : false));
        $(this).parent().parent().next(".gr-analysis-content").toggle();
        $(this).parent().parent().next(".gr-analysis-content").attr('aria-hidden', ($(this).parent().parent().next(".gr-analysis-content").attr('aria-hidden') == "true" ? false : true));
    });

    // Show all
    $("#expand-all").on("click", function (e) {
        e.preventDefault();
        $(".gr-analysis-content").show();
        $(".gr-analysis-title button").attr('aria-expanded', true);
        $(".gr-analysis-content").attr('aria-hidden', false);
    });
	
    // Hide all
    $("#close-all").on("click", function (e) {
        e.preventDefault();
        $(".gr-analysis-content").hide();
        $(".gr-analysis-title button").attr('aria-expanded', false);
        $(".gr-analysis-content").attr('aria-hidden', true);
    });
}

//Tagify
var input = document.querySelector(
    "input[id=siteseo_analysis_target_kw_meta]"
);

var target_kw = new Tagify(input, {
    originalInputValueFormat: (valuesArr) =>
        valuesArr.map((item) => item.value).join(","),
});

function siteseo_google_suggest(data){
    const $ = jQuery;

    var raw_suggestions = String(data);
    var suggestions_array = raw_suggestions.split(",");

    var i;
    for (i = 0; i < suggestions_array.length; i++) {
        if (
            suggestions_array[i] != null &&
            suggestions_array[i] != undefined &&
            suggestions_array[i] != "" &&
            suggestions_array[i] != "[object Object]"
        ) {
            document.getElementById("siteseo_suggestions").innerHTML +=
                '<li><a href="#" class="siteseo-suggest-btn components-button is-secondary">' +
                suggestions_array[i] +
                "</a></li>";
        }
    }

    $(".siteseo-suggest-btn").click(function (e) {
        e.preventDefault();

        target_kw.addTags($(this).text());
    });
}

jQuery(document).ready(function($){
	siteseo_analysis_init($);
});

function siteseo_analysis_init($){

    // default state
    if($('#toggle-preview').attr('data-toggle') == '1') {
        $('#siteseo_cpt .google-snippet-preview').addClass('mobile-preview');
    } else {
        $('#siteseo_cpt .google-snippet-preview').removeClass('mobile-preview');
    }
	
    $('#toggle-preview').off('click');
    $('#toggle-preview').on('click', function(){
        $('#toggle-preview').attr(
			'data-toggle',
			$('#toggle-preview').attr('data-toggle') == '1' ? '0' : '1'
        );
		
        $('#siteseo_cpt .google-snippet-preview').toggleClass('mobile-preview');
    });
	
    var siteseo_do_real_preview = function(){
		
        //Post ID
        if(typeof jQuery("#siteseo-tabs").attr("data_id") !== "undefined") {
            var post_id = jQuery("#siteseo-tabs").attr("data_id");
        } else if (typeof jQuery("#siteseo_content_analysis .wrap-siteseo-analysis").attr("data_id") !== "undefined") {
            var post_id = jQuery("#siteseo_content_analysis .wrap-siteseo-analysis").attr("data_id")
        }
		
		if(!post_id){
			return;
		}
		
        //Tax origin
        if (typeof jQuery("#siteseo-tabs").attr("data_tax") !== "undefined") {
            var tax_name = jQuery("#siteseo-tabs").attr("data_tax");
        } else if (typeof jQuery("#siteseo_content_analysis .wrap-siteseo-analysis").attr("data_tax") !== "undefined") {
            var tax_name = jQuery("#siteseo_content_analysis .wrap-siteseo-analysis").attr("data_tax")
        }

        //Origin
        if (typeof jQuery("#siteseo-tabs").attr("data_origin") !== "undefined") {
            var origin = jQuery("#siteseo-tabs").attr("data_origin");
        } else if (typeof jQuery("#siteseo_content_analysis .wrap-siteseo-analysis").attr("data_origin") !== "undefined") {
            var origin = jQuery("#siteseo_content_analysis .wrap-siteseo-analysis").attr("data_origin")
        }

        jQuery.ajax({
            method: "GET",
            url: siteseoAjaxRealPreview.siteseo_real_preview,
            data: {
                action: "siteseo_do_real_preview",
                post_id: post_id,
                tax_name: tax_name,
                origin: origin,
                post_type: jQuery("#siteseo_launch_analysis").attr(
                    "data_post_type"
                ),
                siteseo_analysis_target_kw: jQuery(
                    "#siteseo_analysis_target_kw_meta"
                ).val(),
                _ajax_nonce: siteseoAjaxRealPreview.siteseo_nonce,
            },
            beforeSend: function () {
                jQuery(".analysis-score p span")
                    .fadeIn()
                    .text(siteseoAjaxRealPreview.i18n.progress),
                    jQuery(".analysis-score p").addClass("loading");
            },
            success: function (s) {
				
                typeof s.data.og_title === "undefined"
                    ? (og_title = "")
                    : (og_title = s.data.og_title.values);
                typeof s.data.og_desc === "undefined"
                    ? (og_desc = "")
                    : (og_desc = s.data.og_desc.values);
                typeof s.data.og_img === "undefined"
                    ? (og_img = "")
                    : (og_img = s.data.og_img.values);
                typeof s.data.og_url === "undefined"
                    ? (og_url = "")
                    : (og_url = s.data.og_url.host);
                typeof s.data.og_site_name === "undefined"
                    ? (og_site_name = "")
                    : (og_site_name = s.data.og_site_name.values);
                typeof s.data.tw_title === "undefined"
                    ? (tw_title = "")
                    : (tw_title = s.data.tw_title.values);
                typeof s.data.tw_desc === "undefined"
                    ? (tw_desc = "")
                    : (tw_desc = s.data.tw_desc.values);
                typeof s.data.tw_img === "undefined"
                    ? (tw_img = "")
                    : (tw_img = s.data.tw_img.values);
                typeof s.data.meta_robots === "undefined"
                    ? (meta_robots = "")
                    : (meta_robots = s.data.meta_robots[0]);

                var data_arr = {
                    og_title: og_title,
                    og_desc: og_desc,
                    og_img: og_img,
                    og_url: og_url,
                    og_site_name: og_site_name,
                    tw_title: tw_title,
                    tw_desc: tw_desc,
                    tw_img: tw_img,
                };

                for (var key in data_arr) {
                    if (data_arr.length) {
                        if (data_arr[key].length > 1) {
                            key = data_arr[key].slice(-1)[0];
                        } else {
                            key = data_arr[key][0];
                        }
                    }
                }

                // Meta Robots
                meta_robots = meta_robots.toString();

                jQuery("#siteseo-advanced-alert").empty();

                var if_noindex = new RegExp("noindex");

                if (if_noindex.test(meta_robots)) {
                    jQuery("#siteseo-advanced-alert").append(
                        '<span class="impact high" aria-hidden="true"></span>'
                    );
                }

                // Google Preview
                title = '';
                if (s.data.title) {
                    title = s.data.title.substr(0, 60) + '...';
                }

                jQuery("#siteseo_cpt .google-snippet-preview .snippet-title").html(title),
                    jQuery("#siteseo_cpt .google-snippet-preview .snippet-title-default").html(title),
                    jQuery("#siteseo_titles_title_meta").attr("placeholder", title);

                meta_desc = '';
                if (s.data.meta_desc) {
                    meta_desc = s.data.meta_desc.substr(0, 160) + '...';
                }

                jQuery("#siteseo_cpt .google-snippet-preview .snippet-description").html(meta_desc),
                    jQuery("#siteseo_cpt .google-snippet-preview .snippet-description-default").html(meta_desc),
                    jQuery("#siteseo_titles_desc_meta").attr("placeholder", meta_desc);

                // Facebook Preview
                if (data_arr.og_title) {
                    jQuery("#siteseo_cpt #siteseo_social_fb_title_meta").attr("placeholder", data_arr.og_title[0]),
                        jQuery("#siteseo_cpt .facebook-snippet-preview .snippet-fb-title").html(data_arr.og_title[0]),
                        jQuery("#siteseo_cpt .facebook-snippet-preview .snippet-fb-title-default").html(data_arr.og_title[0]);
                }

                if (data_arr.og_desc) {
                    jQuery("#siteseo_cpt #siteseo_social_fb_desc_meta").attr("placeholder", data_arr.og_desc[0]),
                        jQuery("#siteseo_cpt .facebook-snippet-preview .snippet-fb-description").html(data_arr.og_desc[0]),
                        jQuery("#siteseo_cpt .facebook-snippet-preview .snippet-fb-description-default").html(data_arr.og_desc[0]);
                }

                if (data_arr.og_img) {
                    jQuery("#siteseo_cpt #siteseo_social_fb_img_meta").attr(
                        "placeholder",
                        data_arr.og_img[0]
                    ),
                        jQuery(
                            "#siteseo_cpt .facebook-snippet-preview .snippet-fb-img img"
                        ).attr("src", data_arr.og_img[0]),
                        jQuery(
                            "#siteseo_cpt .facebook-snippet-preview .snippet-fb-img-default img"
                        ).attr("src", data_arr.og_img[0]);
                }

                jQuery(
                    "#siteseo_cpt .facebook-snippet-preview .snippet-fb-url"
                ).html(data_arr.og_url),
                    jQuery(
                        "#siteseo_cpt .facebook-snippet-preview .snippet-fb-site-name"
                    ).html(data_arr.og_site_name);

                // Twitter Preview
                if (data_arr.tw_title) {
                    jQuery("#siteseo_cpt #siteseo_social_twitter_title_meta").attr(
                        "placeholder",
                        data_arr.tw_title[0]
                    ),
                        jQuery(
                            "#siteseo_cpt .twitter-snippet-preview .snippet-twitter-title"
                        ).html(data_arr.tw_title[0]),
                        jQuery(
                            "#siteseo_cpt .twitter-snippet-preview .snippet-twitter-title-default"
                        ).html(data_arr.tw_title[0]);
                }

                if (data_arr.tw_desc) {
                    jQuery("#siteseo_cpt #siteseo_social_twitter_desc_meta").attr(
                        "placeholder",
                        data_arr.tw_desc[0]
                    ),
                        jQuery(
                            "#siteseo_cpt .twitter-snippet-preview .snippet-twitter-description"
                        ).html(data_arr.tw_desc[0]),
                        jQuery(
                            "#siteseo_cpt .twitter-snippet-preview .snippet-twitter-description-default"
                        ).html(data_arr.tw_desc[0]);
                }

                if (data_arr.tw_img) {
                    jQuery("#siteseo_cpt #siteseo_social_twitter_img_meta").attr(
                        "placeholder",
                        data_arr.tw_img[0]
                    ),
                        jQuery(
                            "#siteseo_cpt .twitter-snippet-preview .snippet-twitter-img img"
                        ).attr("src", data_arr.tw_img[0]),
                        jQuery(
                            "#siteseo_cpt .twitter-snippet-preview .snippet-twitter-img-default img"
                        ).attr("src", data_arr.tw_img[0]);
                }

                jQuery(
                    "#siteseo_cpt .twitter-snippet-preview .snippet-twitter-url"
                ).html(data_arr.og_url);
				
				jQuery("#siteseo_cpt #siteseo_robots_canonical_meta").attr(
					"placeholder",
					s.data.canonical
				);
				
				jQuery("#siteseo-analysis-tabs").load(
					" #siteseo-analysis-tabs-1",
					"",
					siteseo_ca_toggle
				);
				jQuery('#siteseo-wrap-notice-target-kw').load(" #siteseo-notice-target-kw", '');
				
				jQuery(".analysis-score p").removeClass("loading"),
				jQuery("#siteseo_titles_title_counters_val").remove(),
				jQuery("#siteseo_titles_desc_counters_val").remove(),
				siteseo_titles_counters(),
				siteseo_meta_desc_counters(),
				siteseo_social();
            },
        });
    }
	
    siteseo_do_real_preview(),
	
	$(document).off("click", "#siteseo_launch_analysis", siteseo_do_real_preview),
	$(document).on("click", "#siteseo_launch_analysis", siteseo_do_real_preview),
    
	siteseo_ca_toggle();

    // Inspect URL
    $('#siteseo_inspect_url').on("click", function () {
        $(this).attr("disabled", "disabled");
        $('.spinner').css("visibility", "visible");
        $('.spinner').css("float", "none");

        //Post ID
        if (typeof jQuery("#siteseo-tabs").attr("data_id") !== "undefined") {
            var post_id = jQuery("#siteseo-tabs").attr("data_id");
        } else if (typeof jQuery("#siteseo_content_analysis .wrap-siteseo-analysis").attr("data_id") !== "undefined") {
            var post_id = jQuery("#siteseo_content_analysis .wrap-siteseo-analysis").attr("data_id")
        }

        jQuery.ajax({
            method: "POST",
            url: siteseoAjaxInspectUrl.siteseo_inspect_url,
            data: {
                action: "siteseo_inspect_url",
                post_id: post_id,
                _ajax_nonce: siteseoAjaxInspectUrl.siteseo_nonce,
            },
            success: function () {
                $('.spinner').css("visibility", "hidden");
                $('#siteseo_inspect_url').removeAttr("disabled");
                $("#siteseo-ca-tabs-1").load(" #siteseo-ca-tabs-1");
            }
        });
    });

}
