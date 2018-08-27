/**
 * Created by Asus on 11/7/2017.
 */
var base_url = window.location.origin;
var arrays = [];
var markers_offlines = [];
var map_offlines;
var listOfflines = [];
$("a[href='#smTab03']").on('shown.bs.tab', function(e) {

    $.ajax({
        url: base_url + '/admin/giam-sat-phan-phoi/ban-do-diem-ban',
        type: 'get',
        dataType: 'json',
    success: function (response) {
        $(".smTab03").html(response.html);

        var uluru = {lat:  21.028511, lng: 105.804817};
        map_offlines = new google.maps.Map(document.getElementById('map_offlines'), {
            zoom: 13,
            center: uluru,
            streetViewControl: false,
            mapTypeControl:false,
            fullscreenControl: true
        });

        var listPointSales = response.pointSales;
        var string = '';
        $( listPointSales ).each(function( index, value ) {
            listOfflines[value.id] = value;
            arrays.push({id: value.id, id_sale: value.refers.id});
            if (value.company_store != '') {
                name_store = value.company_store;
            } else {
                name_store = value.name;
            }
            string +=  '<li id="" data-id="'+ value.id +'">'+
                '<a href="#" class="has-avatar point_sale" data-id="'+ value.id +'">'+
                '<div class="task-avatar">' +
                '<div class="ht-rectangle ratio-11">' +
                '<div class="ht-inner border-rounded ht-bgcover bg-color-grey-300" style="background-image: url('+ value.image +');"></div>' +
                '</div></div>'+
                '<h2 class="task-title">'+name_store+'</h2>'+
                '<div class="task-meta">'+
                '<span class="task-datetime">'+value.address +'</span>'+
                '</div>'+
                '</a>'+
                '</li>';

            var contentString = '';
            if (value.company_store != '') {
                contentString += '<p>Cửa hàng: <strong>' + value.company_store + '</strong></p>';
            }
            contentString += '<p>Chủ cửa hàng: '+ value.name  +'</p>';
            contentString += '<p>Sđt: '+ value.phone  +'</p>';
            contentString += '<p>Địa chỉ: '+ value.address  +'</p>';
            contentString += '<p>Sale: '+ value.refers.name  +'</p>';
            contentString += '<p>Sdt Sale: '+ value.refers.phone  +'</p>';

            var infowindow = new google.maps.InfoWindow({
                content: contentString
            });

            var myLatLng ={lat:  Number(value.lat), lng: Number(value.lng)};

            var marker = new google.maps.Marker({
                position: myLatLng,
                map: map_offlines,
                title: value.name,

            });

            markers_offlines[value.id] = marker;

            marker.addListener('click', function() {
                infowindow.open(map_offlines, marker);
            });
        });

        $("#list-point-sale-map-offline").html(string);
    }
});
});

$(document).on('click', '.point_sale', function (e) {
    e.preventDefault();

    var id = $(this).attr('data-id');
    map_offlines.setCenter(markers_offlines[id].getPosition());

    $(".list-unstyled li.active").removeClass('active');
    $(this).parent().addClass('active');

    var value = listOfflines[id];

    var string = '<div class="single-info flex">' +
        '<div class="descr-label">Tên cửa hàng:</div>' +
        '<div class="descr-content"><b>'+ value.company_store +'</b></div>'+
        '</div>' +
        '<div class="single-info flex">' +
        '<div class="descr-label">Chủ cửa hàng:</div>' +
        '<div class="descr-content"><b>'+ value.name +'</b></div>' +
        '</div>' +
        '<div class="single-info flex">' +
        '<div class="descr-label">Số điện thoại:</div>' +
        '<div class="descr-content"><b>'+ value.phone +'</b></div>' +
        '</div>' +
        '<div class="single-info flex">' +
        '<div class="descr-label">Địa chỉ:</div>' +
        '<div class="descr-content"><b>'+ value.address +'</b></div>' +
        '</div>';
    $('#info-point-sale-map-offline').html(string);

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

$(document).on('change', '.group_staff_3', function (e) {
    e.preventDefault();
    var id = $(this).val();
    $.ajax({
        url: base_url + '/admin/giam-sat-phan-phoi/get-user-sale',
        data: {
            id: id,
        },
        type: 'POST',
        dataType: 'json',
        success: function(response) {
        var string = '';
        string +=  '<option value="">' + 'Chọn nhân viên' +  '</option>';
        $( response ).each(function( index, value ) {
            string += '<option value="' + value.id + '">' + value.name +  '</option>';
        });
        $('.name_staff_3').html(string);
        $('.name_staff_3').select2();
    }
});
});

$(document).on('change', '.name_staff_3', function (e) {
    e.preventDefault();
    var id = $(this).val();

    for (var i = 0; i < arrays.length; i++) {
        if (id != arrays[i].id_sale) {
            markers_offlines[arrays[i].id].setVisible(false);
            $("#offline-"+arrays[i].id).hide();
        } else {
            markers_offlines[arrays[i].id].setVisible(true);
            $("#offline-"+arrays[i].id).show();
        }
    }
});

$(document).on('keyup', '#offline_search', function () {
    var staff_id = $(".name_staff_3").val();
    $.ajax({
        type: "POST",
        url: base_url + '/admin/giam-sat-phan-phoi/search-list-point-sales',
        data: {
            key : $(this).val(),
            staff_id : staff_id,
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

                string +=  '<li id="" data-id="'+ value.id +'">'+
                    '<a href="#" class="has-avatar point_sale" data-id="'+ value.id +'">'+
                    '<div class="task-avatar">' +
                    '<div class="ht-rectangle ratio-11">' +
                    '<div class="ht-inner border-rounded ht-bgcover bg-color-grey-300" style="background-image: url('+ value.image +');"></div>' +
                    '</div></div>'+
                    '<h2 class="task-title">'+name_store+'</h2>'+
                    '<div class="task-meta">'+
                    '<span class="task-datetime">'+value.address +'</span>'+
                    '</div>'+
                    '</a>'+
                    '</li>';
            });

            $("#list-point-sale-map-offline").html(string);
        }
    });
});