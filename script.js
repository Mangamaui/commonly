window.state = {
    results_loaded: false,
    image_errors: false,
    images_processed: 0,
    images_total: 2,
    refresh: false
}

var default_image = "images/egg.jpg";
var $form;

$(function() {
    $('input[type=text]').on('keypress', function() {
        $(this).css('color', '#4e395d');
    });

    $('input[type=text]').on('click', function() {
        $(this).select();
    });

    $form = $('form');

    $('#button').on('click', function(e) {


        if ($form.hasClass('loading')) {
            return false;
        };
        
        if (window.state.refresh) {
            clear_previous_Search();
        }

        var $inputfield1 = $('#twittername1');
        var $inputfield2 = $('#twittername2');

        var checkup = input_validation($inputfield1, $inputfield2);

        if (checkup) {
            $('.error').remove();

            e.preventDefault();
            var screenname1 = $inputfield1.val();
            var screenname2 = $inputfield2.val();
            var img1 = show_parent(screenname1);
            var img2 = show_parent(screenname2);

            $form.addClass('loading');

            $.when(load_image(img1))
                    .done(load_image_success_1)
                    .fail(load_image_error_1);

            $.when(load_image(img2))
                    .done(load_image_success_2)
                    .fail(load_image_error_2);
        };

        return false;

    });

    $('#close').click(function() {
        $('#boxwrapper').hide();
    });

});

//show error if account protected
function error_account_protected() {
    $error = ($('.error').data("ptd"));

    if ($error === 1) {
        $('input[type=text]').css('color', 'red');
    } else if ($error === 2) {
        $('#twittername1').css('color', 'red');
    } else if ($error === 3) {
        $('#twittername2').css('color', 'red');
    }
}


function show_parent(screenname) {
    var pic = "http://api.twitter.com/1/users/profile_image?screen_name=" + screenname + "&size=original";
    return pic;
}

//load image of twitter account
function load_image(src) {
    var $img = $(new Image()),
            dfd = $.Deferred();

    // hide image while loading
    $img.css("opacity", 0)
            .addClass("hidden-image");

    // load event
    $img.on("load", function(e) {
        dfd.resolve($img);
        e.preventDefault();
    })
            .on("error", function(e) {
        dfd.reject($img);
        e.preventDefault();
    });

    // bind src
    $img.attr("src", src);

    // add to body
    $("body").append($img);

    // promise
    return dfd.promise();
}

//swap image after succesful load
function load_image_success_1($img) {
    $('#twitterpic1').css('background-image', 'url(' + $img.attr('src') + ')');
    $img.remove();
    image_processed();
}

//swap image after succesful load
function load_image_success_2($img) {
    $('#twitterpic2').css('background-image', 'url(' + $img.attr('src') + ')');
    $img.remove();
    image_processed();
}

//show load error
function load_image_error_1($img) {
    window.state.image_errors = true;
    $img.remove();
    image_processed();
    $('#twittername1').css('color', 'red');
    $('#twitterpic1').css('background-image', 'url(' + default_image + ')');
}

//show load error
function load_image_error_2($img) {
    window.state.image_errors = true;
    $img.remove();
    image_processed();
    $('#twittername2').css('color', 'red');
    $('#twitterpic2').css('background-image', 'url(' + default_image + ')');
}

function image_processed() {
    var images_total = window.state.images_total;

    ++window.state.images_processed;
    if (window.state.images_processed >= images_total) {
        submit_form();
    }
}

function submit_form() {
    window.state.refresh = true;
    if (!window.state.image_errors) {

        $.get('backend.php', $('form').serialize(),
                function(response) {
                    $('#results').append(response);
                    var follow_count = $('td').length;
                    var response_text = $('<h4 class="result">Common Follows: ' + follow_count + '</h4>');
                    $('#results').prepend(response_text);
                    $('form').removeClass('loading');
                    error_account_protected();
                }
        );

    } else {
        $('#results').append('<div class="error">User doesn\'t exist</div>');
        $('form').removeClass('loading');
    }
}

//remove all previous entries || clear images & entries
function clear_previous_Search() {
    $('.result').remove();
    $('table').remove();
    $('#results').find('p').remove();
    $('.parentPic').css('background-image', 'url(' + default_image + ')');
    window.state.image_errors = false;
    window.state.images_processed = 0;
    window.state.refresh = false;
}

//validate data from inputfields
function input_validation($inputfield1, $inputfield2) {
    var error_msg;
    var bool = true;
    var input1 = $.trim($inputfield1.val()).length;
    var input2 = $.trim($inputfield2.val()).length;

    if (input1 === 0 && input2 === 0) {
        error_msg = "Both fields are empty!";
        bool = false;
    } else if (input1 === 0) {
        error_msg = "The left field is still empty!";
        bool = false;
    } else if (input2 === 0) {
        error_msg = "The right field is still empty!";
        bool = false;
    } else if ($inputfield1.val() === $inputfield2.val()) {
        error_msg = "Please enter 2 different users."
        bool = false;
    }

    if (!bool) {
        if ($('#results').find('.error').length === 0) {
            $('#results').append('<div class="error">' + error_msg + '</div>');
        } else {
            $('.error').html(error_msg);
        }
    }
    return bool;
}
