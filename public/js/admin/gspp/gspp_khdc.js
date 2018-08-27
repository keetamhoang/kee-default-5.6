// ke hoach di chuyen
var base_url = window.location.origin;
var map2;
$("a[href='#smTab02']").on('shown.bs.tab', function(e) {
    $.ajax({
        url: base_url + '/admin/giam-sat-phan-phoi/ke-hoach-di-chuyen',
        type: 'get',
        dataType: 'json',
        success: function (response) {
            $(".smTab02").html(response.html);

            $('.datepicker').datepicker( {
                format: 'dd/mm/yyyy'
            }).datepicker("setDate", new Date());

            var mapOptions = {
                zoom: 13,
                center: new google.maps.LatLng(21.0277644, 105.83415979999995),
            };
            map2 = new google.maps.Map(document.getElementById('map2'), mapOptions);
        }
    });
});



$(document).on('change','.datefilter_2',function(e) {
    var date = $(this).val();

    if ($(".name_staff_2").val() != 0) {
        var id = $(".name_staff_2").val();
        $('.list-date-sale-point-2').text(date);
        getVisitPlan(date,id);

    }
});

$(document).on('change', '.group_staff_2', function (e) {
    e.preventDefault();
    var id = $(this).val();
    var type =$(".group_staff_2").select2().find(":selected").data("type");
    $.ajax({
        url: base_url + '/admin/giam-sat-phan-phoi/get-user-sale',
        data: {
        id: id,
            type : type
    },
    type: 'POST',
        dataType: 'json',
        success: function(response) {
        var string = '';
        string +=  '<option value="">' + 'Chọn nhân viên' +  '</option>';
        $( response ).each(function( index, value ) {
            string += '<option value="' + value.id + '">' + value.name +  '</option>';
        });
        $('.name_staff_2').html(string);
        $('.name_staff_2').select2();
    }
});
});

$(document).on('change','.name_staff_2',function(e) {
    e.preventDefault();
    var id = $(this).val();
    var date = $('.datefilter_2').val();
    getVisitPlan(date,id);
});

function getVisitPlan(date,id) {

    $.ajax({
        url: base_url + '/admin/giam-sat-phan-phoi/get-visit-plan',
        data: {
        id: id,
            date : date
    },
    type: 'post',
        dataType: 'json',
        success: function(response) {

        if(response.status == 0) {

            var infoWindows = response.results;
            var string = '';

            $( infoWindows ).each(function( index, value ) {

                var contentString = '';
                if (value.company_store != '') {
                    contentString += '<p>Cửa hàng: <strong>' + value.company_store + '</strong></p>';
                }
                contentString += '<p>Chủ cửa hàng: '+ value.name  +'</p>';
                contentString += '<p>Sđt: '+ value.phone  +'</p>';
                contentString += '<p>Địa chỉ: '+ value.address  +'</p>';
                contentString += '<p>Sale: '+ value.sale_name  +'</p>';
                contentString += '<p>Sdt Sale: '+ value.sale_phone  +'</p>';

                var infowindow = new google.maps.InfoWindow({
                    content: contentString
                });

                var marker = new google.maps.Marker({
                    position: new google.maps.LatLng(value.lat, value.lng),
                    label: ' ' + index,
                    title: value.name,
                    map:map2
                });

                marker.addListener('click', function() {
                    infowindow.open(map2, marker);
                });

                if (value.company_store != '') {
                    name_store = value.company_store;
                } else {
                    name_store = value.name;
                }

                string +=  '<li id="point_sale" data-id="'+ value.id +'">'+
                    '<a class="point-sale" href="#" data-id="'+ value.id +'">'+
                    '<h2 class="task-title">'+name_store+'</h2>'+
                    '<div class="task-meta">'+
                    '<span class="task-datetime">'+value.address +'</span>'+
                    '</div>'+
                    '</a>'+
                    '</li>';
            });

            if (infoWindows.length > 1) {
                var directionsService = new google.maps.DirectionsService();
                var directionsDisplay = new google.maps.DirectionsRenderer({map: map2, suppressMarkers: true});

                var waypts = [];
                for (var i = 0; i < infoWindows.length; i++) {
                    waypts.push({
                        location: new google.maps.LatLng(infoWindows[i]['lat'], infoWindows[i]['lng']),
                        stopover: true
                    });
                }

                //avoiding highways
                directionsService.route({
                    origin: new google.maps.LatLng(infoWindows[0]['lat'], infoWindows[0]['lng']),
                    destination: new google.maps.LatLng(infoWindows[infoWindows.length -1]['lat'], infoWindows[infoWindows.length -1]['lng']),
                    waypoints: waypts,
                    optimizeWaypoints: true,
                    provideRouteAlternatives: true,
                    avoidHighways: true,
                    travelMode: google.maps.TravelMode.WALKING
                }, function (response, status) {
                    if (status === google.maps.DirectionsStatus.OK) {
                        directionsDisplay.setDirections(response);
                    } else {
                        window.alert('Directions request failed due to ' + status);
                    }
                });
            }

            $("#list-sale-point-khdc").html(string);

        }
    }
});
}

$(document).on('click', '#point_sale', function (e) {
    var id = $(this).attr('data-id');
    $(".list-unstyled li.active").removeClass('active');
    $(this).addClass('active');
    $.ajax({
        url: base_url + '/admin/giam-sat-phan-phoi/get-moving-maps',
        type: 'get',
        data: {
            id : id
        },
        dataType: 'html',
        success: function (response) {
            $("#discusses_point_sale").html(response);
        }
    });

});

$(document).on('keyup', '#visit_plan_search', function () {
    var staff_id = $(".name_staff_2").val();
    var day = $(".datefilter_2").val();

    $.ajax({
        type: "POST",
        url: base_url + '/admin/giam-sat-phan-phoi/search-list-point-sales',
        data: {
            key : $(this).val(),
            staff_id : staff_id,
            day : day,
        },
        dataType: 'json',
        success: function(respones){
            var string = '';
            $( respones ).each(function( index, value ) {

                if (value.company_store != '') {
                    name_store = value.company_store;
                } else {
                    name_store = value.name;
                }

                string += '<li id="point_sale" data-id="'+ value.id +'">'+
                    '<a class="point-sale" href="#" data-id="'+ value.id +'">'+
                    '<h2 class="task-title">'+name_store+'</h2>'+
                    '<div class="task-meta">'+
                    '<span class="task-datetime">'+value.address +'</span>'+
                    '</div>'+
                    '</a>'+
                    '</li>';
            });

            $("#list-sale-point-khdc").html(string);
        }
    });
});