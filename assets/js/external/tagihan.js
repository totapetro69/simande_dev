/**
 * js for tagihan
 */
var path = window.location.pathname.split('/');
var http = window.location.origin + '/' + path[1];
$(document).ready(function(e){
	$('.nav-tabs li a').on('shown.bs.tab', function(e) {
        e.preventDefault();
        var current_tab = e.target;
        var current_tab = e.target;
        var previous_tab = e.relatedTarget;
        $('#tabaktif').val(current_tab);
        var tab = $("#tabaktif").val();
        tab = (tab != '') ? tab.split('#')[1] : "tabs-1";
        switch (previous_tab.hash) {
            case '#tabs-2':
                $('#tabaktif').val('2');
            	$('.nav-tabs a[href="#tabs-2"]').tab('show');
            break;
            case "#tabs-3":
                $('#tabaktif').val('3');
            	$('.nav-tabs a[href="#tabs-3"]').tab('show');
            break;
            case "#tabs-4":
                $('#tabaktif').val('4');
            	$('.nav-tabs a[href="#tabs-4"]').tab('show');
            break;
            default:
                $('#tabaktif').val('1');
            	$('.nav-tabs a[href="#tabs-1"]').tab('show');
            break;
        }
    });
})

