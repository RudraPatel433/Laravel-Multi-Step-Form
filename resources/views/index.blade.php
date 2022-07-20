@include('template.header')

<body id="grad1">
  <div class="container" style="margin-top: 50px; margin-bottom: 50px;">
    <div class="row justify-content-center">
      <div class="col-md-10">
        <div class="card display-card" id="card" style="margin-left: -190px;">
          <div class="card-header">Manage Student Data</div>
          <div class="card-body" id="card-body">
            <div class="row">
              <div class="col-sm-6">
                <a href="{{ route('getStepOne') }}" class="btn btn-primary pull-right mb-3">Create Student Data</a>
                <select name="record-limit" id="record-limit" class="form-control d-inline custom-control" style="width:155px; margin-right:20px; padding-left:10px; float:left;">
                  <!-- <option value="">Records Limit</option> -->
                  <option value="5">5</option>
                  <option value="10">10</option>
                  <option value="15">15</option>
                  <option value="20">20</option>
                </select>
              </div>
              <div class="col-sm-6">
                <button class="btn btn-success active_all" style="float:right;"><i class="fa-solid fa-square-check"></i></button>
                <button class="btn btn-danger inactive_all" style="float:right; margin-right:10px;"><i class="fa-solid fa-square-xmark"></i></button>
                <select name="status" id="status" class="form-control d-inline custom-control" style="width:155px; margin-right:20px; float:right;">
                  <option value="">Select status</option>
                  <option value="1">Active</option>
                  <option value="0">Inactive</option>
                </select>
                  <input type="search" class="form-control d-inline search" name="search" id="search" autocomplete="off" placeholder="search...." style="width:220px; margin-right:20px; float:right;">
              </div>
             </div>
            <div id="msgs"></div>
            <div id="msges"></div>
            <div id="table">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>

@include('template.footer')
<script src="http://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.2/js/toastr.min.js"></script>
<!-- <script src="{{url('resources/views/js/custom.js')}}"></script> -->
<script>
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
      success: function(data) {
        $("#table").html(data.html);
      }
    });
  }
  $(document).ready(function() {
    //Table function
    table_data();
    //*********************************  Filter Data as Per Status  ***********************************//     
    $('#status').change(function(event) {
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
        success: function(data) {
          // $("#content").html('')
          $("#table").html(data.html);
        }

      });
    });
    //*********************************  Record Limit  ***********************************// 
    $('#record-limit').change(function(){
      var recordLimit = $('#record-limit').val();
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "{{route('recordLimit')}}",
        data: {
          'record': recordLimit
        },
        success: function(data) {
          $("#table").html(data.html);
        }

      });
    });  
  });
</script>