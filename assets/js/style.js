// ========================================================================================
// onload page  
// ========================================================================================
/**
 * [downloadJSAtOnload description]
 * @return {[type]} [description]
 */
function downloadJSAtOnload() {
    setTimeout(function() {
        $("#loadpage-preloader").fadeOut();
    }, 500);
}
if (window.addEventListener)
    window.addEventListener("load", downloadJSAtOnload, false);
else if (window.attachEvent)
    window.attachEvent("onload", downloadJSAtOnload);
else window.onload = downloadJSAtOnload;


// ========================================================================================
// sidebar active
// ========================================================================================


/**
 * [description]
 * @param  {[type]} ) {                     var pathname [description]
 * @return {[type]}   [description]
 */
$(document).ready(function() {

    var pathname = location.protocol + '//' + location.host + location.pathname;

    // $('#nav-accordion').find('a[href="'+pathname+'"]').closest('.sub-menu').children("a").addClass('active');
    $('#nav-accordion').find('a[href="' + pathname + '"]').parents('.sub').css({
        'display': 'block'
    });
    $('#nav-accordion').find('a[href="' + pathname + '"]').addClass('active').closest(".dcjq-parent-li").children("a").addClass('active');

    var logingroup=$('#usergroupe').val();
    //alert(logingroup);
    //Non aktifkan script ini di ganti dengan helper isDealerAkses();
    /*if(logingroup=="Root"){
        //$('#kd_dealer').removeClass("disabled-action");
        $('#kd_dealer').removeAttr("disabled");
    }else{
        //$('#kd_dealer').addClass("disabled-action");
        $('#kd_dealer').prop("disabled",true);
    }*/
});


// ========================================================================================
// modal
// ========================================================================================



/**
 * [addForm description]
 * @param {[type]} url [description]
 */

function spinner() {
    return '<div class="modal-body">' +
        '<div class="modal-loading">' +
        '<i class="fa fa-spinner fa-spin"></i>' +
        '<h3>Mohon tunggu...</h3>' +
        '</div>' +
        '</div>';
}
/**
 * [error_page description]
 * @param  {[type]} status [description]
 * @return {[type]}        [description]
 */
function error_page(status) {
    return '<div class="modal-header"> ' +
        '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> ' +
        '<h4 class="modal-title" id="myModalLabel" style="cursor: move;">Error message</h4> ' +
        '</div> ' +

        '<div class="modal-body" style="height:50px"> ' +
        '   <h5 class="modal-title" id="myModalLabel"><i class="fa fa-warning fa-fw"></i> ' + status +
        '</h5></div> ' +
        ' <div class="modal-footer"> ' +
        '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button> ' +
        '</div>';
}

/**
 * [addForm description]
 * @param {[type]} url [description]
 */
function addForm(url) {
    //document.preventDefault();
    var modal_id = $("#modal-button").attr('data-target');
    var date = new Date();

    $(modal_id).find(".modal-content").html(spinner());

    $.getJSON(url, function(data, status) {
            //alert(status);
            //console.log(modal_id);
            if (status == 'success') {

                if (data.indexOf("A PHP Error") > -1) {
                    //jika terjadi error output
                    $(modal_id).find(".modal-content").html(error_page("Ada kegagalan proses. Silahkan hubungi IT"));
                } else {
                    //data berhasil di load
                    $(modal_id).find(".modal-content").html(data);
                }
                // console.log(data);
                // load jquery date and time
                date.setDate(date.getDate());

                $('.date').datepicker({
                    format: 'dd/mm/yyyy',
                    startDate: date,
                    autoclose: true
                });


                $('.datetime').datetimepicker({
                    format: 'DD/MM/YYYY',
                    minDate: date
                });

                // load jquery format number

                $(".input-number").ForceNumericOnly();


                // load jquery form vaidation
                $(".submit-btn").on('click', function(event) {
                    var formId = '#' + $(this).closest('form').attr('id');
                    var btnId = '#' + this.id;
                    $(formId).valid();

                    $(formId).validate({
                        focusInvalid: false,
                        invalidHandler: function(form, validator) {

                            if (!validator.numberOfInvalids())
                                return;

                            $('html, body').animate({
                                scrollTop: $(validator.errorList[0].element).offset().top
                            }, 1000);

                        }
                    });



                    if (jQuery(formId).valid()) {
                        // Do something
                        event.preventDefault();

                        addValid(formId, btnId);

                    }
                });


            } else if (status == "timeout") {
                $(modal_id).find(".modal-content").html(error_page("Ada kegagalan koneksi server. Silahkan hubungi IT"));
            } else if (status == "error" || status == "parsererror") {
                $(modal_id).find(".modal-content").html(error_page("Ada kegagalan proses. Silahkan hubungi IT"));
            } else {
                $(modal_id).find(".modal-content").html(error_page("Ada kegagalan proses. Silahkan hubungi IT"));
            }


        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            $(modal_id).find(".modal-content").html(error_page("Ada kegagalan proses. Silahkan hubungi IT\n\r" + textStatus));
        });
}

/**
 * [description]
 * @param  {[type]} ) {               $(".modal-button").click(function(){        var id [description]
 * @return {[type]}   [description]
 */

$(document).ready(function() {
    $(".modal-button").click(function() {
        var id = this.id;
        var url = $("#" + id).attr('url');
        var modal_id = $("#" + id).attr('data-target');
        var date = new Date();

        $(modal_id).find(".modal-content").html(spinner());
        $(modal_id).draggable({
            handle: ".modal-header"
        });

        $.getJSON(url, function(data, status) {

                if (status == 'success') {
                    // var modal_id = $("#modal-button").attr('data-target');

                    if (data.indexOf("A PHP Error") > -1) {
                        //jika terjadi error output
                        $(modal_id).find(".modal-content").html(error_page("Ada kegagalan proses. Silahkan hubungi IT"));
                    } else {
                        //data berhasil di load
                        $(modal_id).find(".modal-content").html(data);
                    }

                    date.setDate(date.getDate());

                    $('.date').datepicker({
                        format: 'dd/mm/yyyy',
                        startDate: date,
                        autoclose: true
                    });


                     $('.datetime').datetimepicker({
                        format: 'DD/MM/YYYY',
                        minDate: date
                    });




                } else if (status == "timeout") {
                    $(modal_id).find(".modal-content").html(error_page("Ada kegagalan koneksi server. Silahkan hubungi IT"));
                } else if (status == "error" || status == "parsererror") {
                    $(modal_id).find(".modal-content").html(error_page("Ada kegagalan proses. Silahkan hubungi IT"));
                } else {
                    $(modal_id).find(".modal-content").html(error_page("Ada kegagalan proses. Silahkan hubungi IT"));
                }


            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                var modal_id = $("#modal-button").attr('data-target');
                $(modal_id).find(".modal-content").html(error_page("Ada kegagalan proses. Silahkan hubungi IT\n\r" + textStatus));
            });


    });
});


// ========================================================================================
// modal dragable and resize 
// ========================================================================================


var modal_id = $("#modal-button").attr('data-target');

$(modal_id).draggable({
    handle: ".modal-header"
});

$(".xxd").draggable({
    handle: ".modal-header"
});


// ========================================================================================
// alert 
// ========================================================================================

// var myMessages = ['info', 'warning', 'error', 'success']; // define the messages types      

var myMessages = ['warning', 'error', 'success']; // define the messages types      


/**
 * [hideAllMessages description]
 * @return {[type]} [description]
 */
function hideAllMessages() {
    var messagesHeights = new Array(); // this array will store height for each

    for (i = 0; i < myMessages.length; i++) {
        messagesHeights[i] = $('.' + myMessages[i]).outerHeight();
        $('.' + myMessages[i]).css('top', -messagesHeights[i]); //move element outside viewport   
    }
}



/**
 * Posting data
 */

$(document).ready(function() {


    $(".update-btn").click(function() {
        var btnId = this.id;
        var defaultBtn = $(this).html();

        // alert ($(this).parents('form'));

        var formData = $("#updateForm").serialize();
        var act = $("#updateForm").attr('action');


        $("#" + btnId).addClass("disabled");
        $("#" + btnId).html("<i class='fa fa-spinner fa-spin'></i>");
        $(".alert-message").fadeIn();



        $.ajax({
            url: act + '/' + btnId,
            type: 'POST',
            data: formData,
            dataType: "json",
            success: function(result) {

                if (result.status == true) {

                    $('.success').animate({
                        top: "0"
                    }, 500);
                    $('.success').html(result.message);


                    if (result.location != null) {
                        setTimeout(function() {
                            location.replace(result.location)
                        }, 1000);
                    } else {
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    }
                } else {

                    $('.error').animate({
                        top: "0"
                    }, 500);
                    $('.error').html(result.message);

                    setTimeout(function() {
                        hideAllMessages();
                        $("#" + btnId).removeClass("disabled");
                        $("#" + btnId).html(defaultBtn);
                    }, 2000);
                }
            }

        });

        return false;


    });

    $(".delete-btn").click(function() {

        var btnId = this.id;
        var defaultBtn = $(this).html();
        var url = $(this).attr('url');
        var result = confirm("Apakah anda yakin ingin menghapus data ini ?");


        if (result) {

            $(this).html("<i data-toggle='tooltip' data-placement='left' title='hapus' class='fa fa-spinner fa-spin text-danger text'></i>");
            $(".alert-message").fadeIn();

            $.getJSON(url, function(data, status) {


                if (data.status == true) {

                    $('.success').animate({
                        top: "0"
                    }, 500);
                    $('.success').html(data.message);

                    if (data.location != null) {
                        setTimeout(function() {
                            location.replace(data.location)
                        }, 1000);
                    }


                } else {
                    $('.error').animate({
                        top: "0"
                    }, 500);
                    $('.error').html(data.message);
                    setTimeout(function() {
                        hideAllMessages();
                        $("#" + btnId).html(defaultBtn);
                    }, 2000);
                }

            });

            return false;
        }
    });

    
    $(".download-btn").click(function() {

      var btnId = this.id;
      var defaultBtn = $(this).html();
      var url = $(this).attr('url');



      $(this).html("<i class='fa fa-spinner fa-spin'></i> Loading");
      $(".alert-message").fadeIn();

      $.getJSON(url, function(data, status) {


          if (data.status == true) {

              $('.success').animate({
                  top: "0"
              }, 500);
              $('.success').html(data.message);

              // alert(http+"/stnk/download_udh?namafile=");

              location.replace(data.file);

              setTimeout(function(){
                  location.reload();
              }, 2000);



          } else {
              $('.error').animate({
                  top: "0"
              }, 500);
              $('.error').html(data.message);
              setTimeout(function() {
                  hideAllMessages();
                  $("#" + btnId).html(defaultBtn);
              }, 2000);
          }

      });

      return false;
    });


});

function addData() {
    var defaultBtn = $("#submit-btn").html();

    $("#submit-btn").addClass("disabled");
    $("#submit-btn").html("<i class='fa fa-spinner fa-spin'></i> Loading");
    $(".alert-message").fadeIn();
    $('#addForm :input').removeAttr("disabled");

    var formData = $("#addForm").serialize();
    var act = $("#addForm").attr('action');
    
    $.ajax({
        url: act,
        type: 'POST',
        data: formData,
        dataType: "json",
        success: function(result) {
            if (result.status == true) {
                $('.success').html(result.message);
                $('.success').animate({ top: "0" }, 500);

                if (result.location != null) {
                    setTimeout(function() {
                        location.replace(result.location)
                    }, 1000);
                } else {
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                }
            } else {

                $('.error').animate({top: "0"}, 500);
                $('.error').html(result.message);

                setTimeout(function() {
                    hideAllMessages();
                    $("#submit-btn").removeClass("disabled");
                    $("#submit-btn").html(defaultBtn);
                    return;
                }, 2000);
            }
        }

    });

    return false;
}


function addValid(formId, btnId) {
    // alert(formId);
    var defaultBtn = $(btnId).html();

    $(btnId).addClass("disabled");
    $(btnId).html("<i class='fa fa-spinner fa-spin'></i> Loading");
    $(".alert-message").fadeIn();
    $('#loadpage').removeClass("hidden");

    $(formId +" select").removeAttr("disabled");
    $(formId +" select").removeClass("disabled-action");
    var formData = $(formId).serialize();
    var act = $(formId).attr('action');

    $.ajax({
        url: act,
        type: 'POST',
        data: formData,
        dataType: "json",
        success: function(result) {

            if (result.status == true) {

                $('.success').html(result.message);
                $('.success').animate({ top: "0" }, 500);


                if (result.location != null) {
                    setTimeout(function() {
                        location.replace(result.location)

                    }, 1000);
                } else {
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                }
            } else {

                $('.error').animate({
                    top: "0"
                }, 500);
                $('.error').html(result.message);

                setTimeout(function() {
                    hideAllMessages();
                    $(btnId).removeClass("disabled");
                    $(btnId).html(defaultBtn);
                    $('#loadpage').addClass("hidden");
                }, 2000);
            }
        }

    });

    return false;
}
/**
 * Post data untuk update
 * @return {[type]} [description]
 * authors : Iswan P
 */
function updateData(url) {

    if (confirm('Yakin data ini akan di hapus')) {
        var defaultBtn = $("#submit-btn").html();

        $("#submit-btn").addClass("disabled");
        $("#submit-btn").html("<i class='fa fa-spinner fa-spin'></i> Loading");
        $(".alert-message").fadeIn();


        var formData = $("#addForm").serialize();
        var act = url; //$("#addForm").attr('action');     

        $.ajax({
            url: act,
            type: 'POST',
            //data: formData,
            dataType: "json",
            success: function(result) {

                if (result.status == true) {

                    $('.success').html(result.message);
                    $('.success').animate({ top: "0" }, 500);

                    setTimeout(function() {
                        //location.reload();
                        window.location = window.location
                    }, 1000);
                } else {

                    $('.error').animate({
                        top: "0"
                    }, 500);
                    $('.error').html(result.message);

                    setTimeout(function() {
                        hideAllMessages();
                        $("#submit-btn").removeClass("disabled");
                        $("#submit-btn").html(defaultBtn);
                    }, 2000);
                }
            }

        });
    }
    return false;
}
/**
 * Post data dengan bentuk JSON
 * @param {[string]} act [url posting]
 * authors : Iswan P
 */
function AddDataJSON(act) {
    var defaultBtn = $("#submit-btn").html();
    $('#keluar').addClass("disabled");
    $("#submit-btn").addClass("disabled");
    $("#submit-btn").html("<i class='fa fa-spinner fa-spin'></i> Sedang Prosess...");
    $(".alert-message").fadeIn();
    jsondata = ($('#datajson').html().replace("\\", ""));
    jsondata = jsondata.replace(/\\/g, '');

    var formData = {
        data: JSON.stringify(jsondata)
    };
    //var act = $("#addForm").attr('action');     

    $.ajax({
        url: act,
        type: 'POST',
        crossDomain: true,
        data: formData,
        dataType: "json",
        success: function(result) {
            //var result=JSON.parse(result);
            if (result.status == true) {

                $('.success').animate({
                    top: "10"
                }, 1000);
                $('.success').html(result.message);

                setTimeout(function() {
                    //location.reload();
                    window.location = window.location;
                }, 1000);
            } else {

                $('.error').animate({
                    top: "0"
                }, 700);
                $('.error').html(result.message);

                setTimeout(function() {
                    hideAllMessages();
                    $("#submit-btn").removeClass("disabled");
                    $("#keluar").removeClass("disabled");
                    $("#submit-btn").html(defaultBtn);
                }, 1000);
            }
        }

    });

    return false;
}
$(document).ready(function() {

    // Initially, hide them all
    hideAllMessages();

    // Show message
    /*for(var i=0;i<myMessages.length;i++)
    {
     showMessage(myMessages[i]);
    }*/

    // When message is clicked, hide it
    $('.message').click(function() {
        $(this).animate({
            top: -$(this).outerHeight()
        }, 500);
    });



    // ========================================================================================
    // footer height
    // ========================================================================================
    /*

        var winHeight = $( window ).height();
        var docHeight = $( document ).height();

        if(docHeight <= winHeight)
        {
            // alert(docHeight+' lebih kecil dari layar '+winHeight);
            $("#footer-content").addClass("footer-responsive");
        }
        else
        {

            $("#footer-content").removeClass("footer-responsive");
            // alert(docHeight+' lebih besar dari layar '+winHeight);
        }
    */



    // ========================================================================================
    // typeahead
    // ========================================================================================

    var ajaxUrl = $("#ajax-url").attr("url");
    var length = $("#ajax-url").attr("length");

    $("#keyword").typeahead({
        source:function(query,process){
          $('#fd, .fd').html("<i class='fa fa-spinner fa-spin'></i>");
          return $.get(ajaxUrl,function(data){
            data=$.parseJSON(data);
            $('#fd, .fd').html('');
            return process(data.keyword);
          })
        },
        minLength:length,
        limit:20
    }).focus();


    $(".form-control").click(function() {
        var id = this.id;
        var inputUrl = $(this).attr("typeaheadurl");
        var length = $(this).attr("length");

        // alert(ajaxUrl);
        if (inputUrl != null) {
            
            $("#"+id).typeahead({
                source:function(query,process){
                  $('#fd, .fd').html("<i class='fa fa-spinner fa-spin'></i>");
                  return $.get(inputUrl,function(data){
                    data=$.parseJSON(data);
                    $('#fd, .fd').html('');
                    return process(data.keyword);
                  })
                },
                minLength:length,
                limit:20
            }).focus();
        }
    });



    // ========================================================================================
    // checkbox typeahead
    // ========================================================================================

    $('.combobox').combobox({
        forceSelection: true,
        typeAhead: true
    });


    // ========================================================================================
    // filter input
    // ========================================================================================


    /*$('#keyword').on('keyup keypress blur change', function(e) {
        $("tbody").html($("#keyword").val());
    });*/



    $("#filterForm .form-control, #filterForm span").click(function() {
        var id = this.id;

        // alert(id);

        $('#filterForm .form-control').on('change', function(e) {
            $("#filterForm").submit();
        });
    });


    $("#filterForm .combobox").change(function() {
        var id = this.id;
        // alert(id);
        $("#filterForm").submit();

        // $("#filterForm").submit(); 
    });



    // ========================================================================================
    // date picker
    // ========================================================================================

    var date = new Date();
    date.setDate(date.getDate());

    $('.date').datepicker({
        format: 'dd/mm/yyyy',
        endDate: date,
        autoclose: true
    });

    $('.datetime').datetimepicker({
        format: 'DD/MM/YYYY',
        minDate: date/*,
        autoclose: true*/
    });

    /*$('.datetime').datetimepicker({
        format: 'dd/mm/yyyy',
        endDate: date,
        autoclose: true
    });*/

    // ========================================================================================
    // table
    // ========================================================================================
    // $("#tr-active").addClass('tr-active');
    $("#tr-active").css({
        'background-color': 'rgba(255, 193, 7, 0.33)'
    });
    setTimeout(function() {
        $("#tr-active").css({
            'background-color': '',
            'transition': 'background 4s linear',
            '-webkit-transition': 'background 4s linear',
            '-moz-transition': 'background 4s linear'
        });
    }, 1000);

    $(".input-number").ForceNumericOnly();

    // ========================================================================================
    // load jquery form vaidation
    // ========================================================================================
    $(".submit-main-btn").click(function(event) {
        var formId = '#' + $(this).closest('form').attr('id');
        var btnId = '#' + this.id;

        // alert(btnId);

        $(formId).valid();

        $(formId).validate({
            focusInvalid: false,
            invalidHandler: function(form, validator) {

                if (!validator.numberOfInvalids())
                    return;

                $('html, body').animate({
                    scrollTop: $(validator.errorList[0].element).offset().top
                }, 1000);

            }
        });

        if (jQuery(formId).valid()) {
            // Do something
            event.preventDefault();
            addValid(formId, btnId);

        }

    });




    // ========================================================================================
    // disable action button
    // ========================================================================================


    if ($('a').hasClass("remove-button")) {
        $(".remove-button").remove();
    }

    // ========================================================================================
    // table responsive
    // ========================================================================================


    var $table = $('table.table');
    if(!$table.hasClass('tablex')){
        $table.floatThead({
            scrollContainer: function($table) {
                return $table.closest('.table-responsive');
            }
        });
    }
    


    // ========================================================================================
    // panel heading cursor
    // ========================================================================================


    $(".tools").closest('.panel-heading').css({
        "cursor": "pointer"
    });




    // ========================================================================================
    // sticky header
    // ========================================================================================

    if ($("#tab-header").hasClass('sticky')) {
        $(function() {
            var stickyHeaderTop = $('#tab-header').offset().top;

            var stickyHeaderButtom = $(document).height() - 100;
            /*
                    alert(stickyHeaderTop);
                    alert(stickyHeaderButtom);*/
            $(window).scroll(function() {
                if ($(window).scrollTop() >= stickyHeaderTop) {
                    // if ($(window).scrollTop()  + $(window).height() >= stickyHeaderButtom) {

                    /*alert(stickyHeaderTop);
                    alert($(window).scrollTop());*/
                    $('.tab-content').css('margin-top', $('#tab-header').outerHeight(true));
                    $('#tab-header').addClass('tab-header');
                    /*$('#tab-header').css({
                        position: 'fixed',
                        bottom: '100px',
                        right: '27px'
                    });*/
                } else {
                    $('#tab-header').removeClass('tab-header');
                    $('#tab-header').css({
                        position: 'static',
                        bottom: '0px'
                    });
                    $('.tab-content').css('margin-top', '0px');
                }
            });
        });
    }



});

//document ready function ending


jQuery.fn.ForceNumericOnly =
    function() {
        return this.each(function() {
            $(this).keydown(function(e) {
                var key = e.charCode || e.keyCode || 0;
                // allow backspace, tab, delete, enter, arrows, numbers and keypad numbers ONLY
                // home, end, period, and numpad decimal
                return (
                    key == 8 ||
                    key == 9 ||
                    key == 13 ||
                    key == 46 ||
                    key == 110 ||
                    key == 190 ||
                    (key >= 35 && key <= 40) ||
                    (key >= 48 && key <= 57) ||
                    (key >= 96 && key <= 105));
            });
        });
    };

/**
 * [convertDate description]
 * @param  {[type]} inputFormat [description]
 * @return {[type]} dd/mm/yyyy  [description]
 */
function convertDate(inputFormat) {
    if(!inputFormat){return;}
    function pad(s) {
        return (s < 10) ? '0' + s : s;
    }
    var d = new Date(inputFormat);
    return [pad(d.getDate()), pad(d.getMonth() + 1), d.getFullYear()].join('/');
}

function DateToSql(inputFormat) {
    function pad(s) {
        return (s < 10) ? '0' + s : s;
    }
    var d = new Date(inputFormat);
    return [ d.getFullYear(),pad(d.getDate()), pad(d.getMonth() + 1)].join('');
}
// left padding s with c to a total of n chars
function pad_left(s, c, n) {
  if (! s || ! c || s.length >= n) {
    return s;
  }
  var max = (n - s.length)/c.length;
  for (var i = 0; i < max; i++) {
    s = c + s;
  }
  return s;
}
 
// right padding s with c to a total of n chars
function pad_right(s, c, n) {
  if (! s || ! c || s.length >= n) {
    return s;
  }
  var max = (n - s.length)/c.length;
  for (var i = 0; i < max; i++) {
    s += c;
  }
  return s;
}
function result_message(result){
    if (result.status == true){
   
        $('.success').animate({ top: "0" }, 500);
        $('.success').html(result.message);

        setTimeout(function(){
            location.reload();
        }, 2000);
    }else{

        $('.error').animate({ top: "0" }, 500);
        $('.error').html(result.message);

        setTimeout(function () {
            hideAllMessages();
            //$("#reject-btn").removeClass("disabled");
            //$("#reject-btn").html(defaultBtn);
        }, 4000);
    }
}
$.extend({
    ucwords : function(str) {
        strVal = '';
        str = str.split(' ');
        for (var chr = 0; chr < str.length; chr++) {
            strVal += str[chr].substring(0, 1).toUpperCase() + str[chr].substring(1, str[chr].length) + ' '
        }
        return strVal
    }
});
function stripslashes(str) {

 return(str)? str.replace(/\\'/g,'\'').replace(/\"/g,'"').replace(/\\\\/g,'\\').replace(/\\0/g,'\0'):false;
}


    // ========================================================================================
    // breadcrumb
    // ========================================================================================


$( "<div class='box-footer clearfix'></div>" ).insertAfter( ".breadcrumb" );