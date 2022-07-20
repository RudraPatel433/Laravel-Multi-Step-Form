//************************************* Function to call Table Data  ***************************************//  
function table_data() {
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
  $.ajax({
    type: "POST",
    dataType: "json",
    url: "{{route('table')}}",
    success: function (data) {
      $("#table").html(data.html);
    }
  });
}
$(document).ready(function () {
  //Table function
  table_data();
  //*********************************  Filter Data as Per Status  ***********************************//     
  $('#status').change(function (event) {
    var statusValue = $(this).val();
    event.preventDefault();
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $.ajax({
      type: "POST",
      dataType: "json",
      url: "{{route('status')}}",
      data: {
        'status': statusValue
      },
      success: function (data) {
        // $("#content").html('')
        $("#table").html(data.html);
      }

    });
  });
  //*********************************  Status Active & InActive ***********************************//
  function change(id) {
    var selected_drobox_value = $('#status :selected').val();
    swal({
      title: "Change Student Status",
      text: "Are you sure you want to Change Status?",
      icon: "warning",
      buttons: true,
      dangerMode: true,
    }).then((willDelete) => {
      if (willDelete) {
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        $.ajax({
          url: '{{ route("changeStatus") }}',
          method: 'POST',
          data: {
            id: id,
            selected_drobox_value: selected_drobox_value,
            "_token": '{{ csrf_token() }}',
          },
          success: function (data) {
            $('#content').html(data.html);
            $("#statuscheck" + id).load(window.location + "#statuscheck" + id);
          }
        });
        swal("Poof! Your User Status Change Successfully!", {
          icon: "success",
        });
      } else {
        swal("Your Data  is safe!");
      }
    });
  }
  //********************************* Active multiple Student ***********************************// 
  $('.active_all').on('click', function (e) {
    e.preventDefault();
    // console.log("clicked active button!");
    var allVals = [];
    var dataVals = [];
    $(".sub_chk:checked").each(function () {
      allVals.push($(this).attr('data-id'));
      dataVals.push($('input[name="status[]"]').val());
    });
    if (allVals.length <= 0) {
      swal({
        text: "Please select atleast one student! ",
      });
    } else {
      var check = swal("Are you sure you want to Active this student(s)?", {
        buttons: {
          cancle: {
            text: "No!",
            value: false
          },
          accept: {
            text: "Yes!",
            value: true
          }
        },
        icon: "warning"
      })
        .then((value) => {
          if (value == true) {
            var join_selected_values = allVals.join(",");
            var status = dataVals.join(",");
            $.ajax({
              url: '{{route("activeAll")}}',
              type: 'post',
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              data: {
                'id': join_selected_values,
                'status': status
              },
              success: function (data) {
                if (data['success']) {
                  $(".sub_chk:checked").each(function () {
                    alert("Status change");
                  });
                } else if (data['error']) {
                  alert(data['error']);
                } else {
                  alert('Whoops Something went wrong!!');
                }
              },
              error: function (data) {
                alert(data.responseText);
              }
            });
          }
        });
    }
  });
  //*********************************  Year dropdown  ***********************************//   
  var append_html = "<select class='form-control d-inline passing_year' style='width:85px;' name='passing_year' id='year'>" +
    "<option name='passing_year' value='2015'>2015</option>" +
    "<option name='passing_year' value='2016'>2016</option>" +
    "<option name='passing_year' value='2017'>2017</option>" +
    "<option name='passing_year' value='2018'>2018</option>" +
    "<option name='passing_year' value='2019'>2019</option>" +
    "<option name='passing_year' value='2020'>2020</option>" +
    "</select>" +
    "<button class='btn-success ml-2 save_btn' type='submit' id='save_btn' style='width:25px;!important; height:25px;!important;'><i class='fas fa-save'></i></button>";
  $('.year_btn').click(function () {
    var data = $(this).data('id');
    $('.span_year_' + data).remove();
    $('#year_btn_' + data).remove();
    $('.td_year_' + data).append(append_html);
    $('.save_btn').on('click', function () {
      var passing_year = $('#year').val();
      var id = $(this).closest('.year_td').data('id');
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "{{route('updateYear')}}",
        data: {
          'passing_year': passing_year,
          'id': id
        },
        success: function (data) {
          $('.passing_year').remove();
          $('#save_btn').remove();
          toastr.options.timeOut = 900;
          toastr.options.fadeOut = 900;
          toastr.success('Year Changed Successfully');
          $("#year_" + id).load("{{route('index')}} #year_" + id);

        }
      });

    });
  });
  //********************************* To select all rows ***********************************//  
  $('#master').on('click', function (e) {
    if ($(this).is(':checked', true)) {
      $(".sub_chk").prop('checked', true);
    } else {
      $(".sub_chk").prop('checked', false);
    }
  });
  //********************************* Search Data  ***********************************//  
  $('#search').on('keyup', function () {
    var value = $(this).val();
    // alert(value);
    var statusValue = $('#status').val();
    // alert(statusValue);
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $.ajax({
      type: 'post',
      url: '{{route("search")}}',
      data: {
        'search': value,
        'status': statusValue
      },
      success: function (data) {
        $('#table').html(data.html);
      }

    });
  });
});