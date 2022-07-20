<div id="data">
  <table class="table table-responsive" id="data-table">   
    <thead class="thead-light">
      <tr>
        <th><input type="checkbox" id="master"></th>
        <th class="col-sm-1" scope="col">#</th>
        <th class="col-sm-1" scope="col">Name</th>
        <th class="col-sm-1" scope="col">Email</th>
        <th class="col-sm-1" scope="col">Total Marks</th>
        <th class="col-sm-1" scope="col">Passing Year</th>
        <th class="col-sm-1" scope="col">First Name</th>
        <th class="col-sm-1" scope="col">Last Name</th>
        <th class="col-sm-1" scope="col">Contact</th>
        <th class="col-sm-1" scope="col">Image</th>
        <th class="col-sm-1" scope="col">Status</th>
        <th class="col-sm-1" scope="col" class="text-center">Action</th>
      </tr>
    </thead>
    <tbody id="content">
      {{session("pagenumber")}}
      @foreach($data as $datas)
      <tr class="tr_data" id="tr_{{$datas->id}}">
        <td><input type="checkbox" class="sub_chk" data-id="{{$datas->id}}"></td>
        <th scope="row">
          <input type="hidden" class="delete_id"  data-id="{{$datas->token}}" value="{{$datas->token}}">
          {{$datas->id}}
        </th>
        <td>{{$datas->name}}</td>
        <td>{{$datas->email}}</td>
        <td>{{$datas->marks_obtained}}</td>
        <td style="width: 30%;!important;" class="td_year_{{$datas->id}} year_td" data-id="{{$datas->id}}">
          <div id="year_{{$datas->id}}">
            <input id="id" name="id" type="hidden" class="student_id" value="{{$datas->id}}">
            <span class="span_year_{{$datas->id}} year_span" id="span_year" data-id="{{$datas->id}}">{{$datas->passing_year}}</span>
            <button class="btn-info ml-5 year_btn" data-id="{{$datas->id}}" id="year_btn_{{$datas->id}}" style="width:27px;!important; height:27px;!important;"><i class="fas fa-edit"></i></button>
          </div>
        </td>
        <td>{{$datas->first_name}}</td>
        <td>{{$datas->last_name}}</td>
        <td>{{$datas->contact}}</td>
        <td>
          <ul class="list-inline" >
            <li class="list-inline-item" id="image">
              <img alt="Avatar" id="mainimage" class="mainimage" src="{{url('/public/image/'.$datas->image)}}" data-id="{{$datas->id}}" data-action="zoom" width="100px" height="100px">
              <div class="modal fade myModal_{{$datas->id}}" id="myModal" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" >
                <div class="modal-dialog">
                  <div class="modal-content" id="modal-content">
                    <div class="modal-body" >
                      <form id="MyForm" action="" method="post">
                        
                      </form>
                    </div>   
                  </div>
                </div>                                                                       
              </div>    

            </li>
          </ul>
        </td>
        <td>
        <div id="statuscheck{{$datas->id}}" style="padding-top:35px;!important;">
          <div onclick="change({{ $datas->id }})">
            @if ($datas->status == 0)
            <button class="btn btn-danger status_btn" style="font-size: 12px; padding: 0 .75rem 0 .75rem;!important;" value="{{$datas->status}}">InActive</button>
            @else
            <button class="btn btn-success status_btn" style="font-size: 12px; padding: 0 .80rem 0 .80rem;!important;" value="{{$datas->status}}">Active</button>
            @endif
          </div>
        </div>
        </td>
        <td>
          <a type="button" id="{{$datas->id}}" data-id="{{ $datas->id }}" value="{{$datas->id}}" class="edit_data btn btn-info btn-sm d-inline" href="{{route('editRoute', ['token' => $datas->token])}}" style="margin-bottom: 5px"><i class="fa-solid fa-pen-to-square"></i></a>
          <a type="button" id="{{$datas->id}}" data-id="{{ $datas->id }}" value="{{$datas->id}}" class="delete_data btn btn-danger btn-sm d-inline " href="{{route('deleteData', ['token' => $datas->token])}}"><i class="fa-solid fa-trash-can"></i></a>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>

{{ $data->links('template.pagination') }}
<input type="hidden" name="hidden_page" id="hidden_page" value="1">
<script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
<script src="http://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.2/js/toastr.min.js"></script>

<!-- <script src="{{url('resources/views/js/custom.js')}}"></script> -->
<script>
  //*********************************  Status Active & InActive ***********************************//
  function change(id) {
    var selected_drobox_value = $('#status :selected').val();
    var recordLimit = $('#record-limit').val();
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
          success: function(data) {
            if(selected_drobox_value == 0 || selected_drobox_value == 1){
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
                  'status': selected_drobox_value
                },
                success: function(data) {
                  $("#table").html(data.html);
                }
              });
            }
            if(selected_drobox_value == ''){
              $('#content').html(data.html);
              $("#statuscheck" + id).load(window.location + "#statuscheck" + id);
            }
          }
        });
        swal("Your User Status Change Successfully!", {
          icon: "success",
        });
      } else {
        swal("Your Data  is safe!");
      }
    });
  }
  //************************************  Year dropdown  **************************************//   
  var append_html = "<div>"+
  "<select class='form-control d-inline passing_year' style='width:85px;' name='passing_year' id='year'>" +
    "<option name='passing_year' value='2015'>2015</option>" +
    "<option name='passing_year' value='2016'>2016</option>" +
    "<option name='passing_year' value='2017'>2017</option>" +
    "<option name='passing_year' value='2018'>2018</option>" +
    "<option name='passing_year' value='2019'>2019</option>" +
    "<option name='passing_year' value='2020'>2020</option>" +
    "</select>" +
    "<button class='btn-success ml-2 save_btn' type='submit' id='save_btn' style='width:25px;!important; height:25px;!important;'><i class='fas fa-save'></i></button>"+
    "</div>";
  $('.year_btn').click(function() {
    var data = $(this).data('id');
    $('.span_year_' + data).remove();
    $('#year_btn_' + data).remove();
    $('.td_year_' + data).append(append_html);
    $('.save_btn').on('click', function() {
      var passing_year = $('#year').val();
      var id = $(this).closest('.year_td').data('id');
      var status = $('#status').val();
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
        success: function(data) {
          $('.passing_year').remove();
          $('#save_btn').remove();
          toastr.options.timeOut = 900;
          toastr.options.fadeOut = 900;
          toastr.success('Year Changed Successfully');
          if(status == 0 || status == 1){
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
                  'status': status
                },
                success: function(data) {
                  $("#table").html(data.html);
                }

              });
          }
          if(status == ''){
            $("#year_"+id).load(window.location +"#year_"+id);
          }
        }
      });

    });
  });
  //********************************* To select all rows ***********************************//  
  $('#master').on('click', function(e) {
    if ($(this).is(':checked', true)) {
      $(".sub_chk").prop('checked', true);
    } else {
      $(".sub_chk").prop('checked', false);
    }
  });
  //************************************ Search Data  ****************************************//  
  $('#search').on('keyup', function() {
    var value = $(this).val();
    var statusValue = $('#status').val();
    var recordLimit = $('#record-limit').val();
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
        'status': statusValue,
        'record': recordLimit
      },
      success: function(data) {
        $('#table').html(data.html);
      }

    });
  });
  //********************************* Active multiple Student ***********************************// 
  $('.active_all').on('click', function(e) {
    e.preventDefault();
    var allVals = [];
    $(".sub_chk:checked").each(function() {
      allVals.push($(this).attr('data-id'));
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
      }).then((value) => {
        if (value == true) {
          var join_selected_values = allVals;
          $.ajax({
            url: '{{route("activeAll")}}',
            type: 'post',
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
              'id': join_selected_values
            },
            success: function(data) {
              if (data['success']) {
                $(".sub_chk:checked").each(function() {
                  swal("Student Status Changed Successfully!", {icon: "success",});
                  $("#content").load(window.location +"#content");
                });
              } else if (data['error']) {
                alert(data['error']);
              } else {
                alert('Whoops Something went wrong!!');
              }
            },
            error: function(data) {
              alert(data.responseText);
            }
          });
        }
      });
    }
  });

  //********************************* InActive multiple Student ***********************************// 
  $('.inactive_all').on('click', function(e) {
    e.preventDefault();
    var allVals = [];
    $(".sub_chk:checked").each(function() {
      allVals.push($(this).attr('data-id'));
    });
    if (allVals.length <= 0) {
      swal({
        text: "Please select atleast one student! ",
      });
    } else {
      var check = swal("Are you sure you want to Inactive this student(s)?", {
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
      }).then((value) => {
        if (value == true) {
          var join_selected_values = allVals;
          $.ajax({
            url: '{{route("inactiveAll")}}',
            type: 'post',
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
              'id': join_selected_values
            },
            success: function(data) {
              if (data['success']) {
                $(".sub_chk:checked").each(function() {
                  swal("Student Status Changed Successfully!", {icon: "success",});
                  $("#content").load(window.location +"#content");
                  // $("#tr_"+ allVals).load(window.location + "#tr_"+ allVals);
                  
                });
              } else if (data['error']) {
                alert(data['error']);
              } else {
                alert('Whoops Something went wrong!!');
              }
            },
            error: function(data) {
              alert(data.responseText);
            }
          });
        }
      });
    }
  });
//********************************* Open Image on Click ***********************************//
  $(".mainimage").click(function(){
    var data = $(this).data('id');
    var src = $(this).attr('src');   
    $("#myModal").modal("show");
    $("#myModal .modal-body #MyForm").html("<img style='width:100%; height:100%;' id='zoom-image' data-action='zoom' class='zoom-image' src='"+src+"'>");
    $("div").removeClass( "modal-backdrop" )
  });

  function check(e) {
    if(e.key === "Enter") {
      $('#myModal').modal('hide');
    }
} 
//*************************************** Zoom Image ***************************************//
  $('#modal-content').hover(function(){ 
    $(this).animate({
      width:"700px",
      height:"700px"
    });
  },
  function(){
    $(this).animate({
      width:"100%",
      height:"100%"
    });
});
//************************************ Pagination **********************************************//
  $('body').on('click', '.pagination a', function(e) {
    e.preventDefault();
    var search = $('#search').val();
    var status = $('#status').val();
    var recordLimit = $('#record-limit').val();
    var url = $(this).attr('href');
    $.ajax({
        url : url,
        type: "post",
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        data : {
            'search' : (search == null)? '' : search,
            'status' : status,
            'record' : recordLimit,
        },
        success:function(data){
            $('#table').html(data.html);
        },
    });
  });
</script>