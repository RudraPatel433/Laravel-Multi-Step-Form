@include('template.header')
<body  id="grad1">
<div class="container-fluid">
    <div class="row justify-content-center mt-0">
        <div class="col-11 col-sm-9 col-md-7 col-lg-6 text-center p-0 mt-3 mb-2">
            <div class="card px-0 pt-4 pb-0 mt-3 mb-3">
            <a href="{{ route('index') }}" class="btn-info mb-3" style="width: 130px!important; padding:10px; margin-left:80%!important; background:skyblue;"><i class="fa-solid fa-bars mr-2"></i> students </a>
                <p>Fill all form field to go to next step</p>
                <div class="row">
                    <div class="col-md-12 mx-0">
                        <form id="msform" action="{{route('index')}}" method="POST">
                        @csrf
                            <!-- progressbar -->
                            <ul id="progressbar">
                                <li class="active" id="account"><strong>Create your account</strong></li>
                                <li class="active" id="personal"><strong>Educational Profiles</strong></li>
                                <li class="active" id="payment"><strong>Personal Details</strong></li>
                                <li class="active" id="confirm"><strong> Review Details</strong></li>
                            </ul>
                            <fieldset>
                                <div class="form-card">
                                    <h2 class="fs-title text-center mb-5">Review Details</h2>
                                    <table class="table">
                                        <tr class="tr">
                                            <td>Full Name:</td>
                                            <td><strong>{{$data->name ?? ''}}</strong></td>
                                        </tr>
                                        <tr class="tr">
                                            <td>Email:</td>
                                            <td><strong>{{$data->email ?? ''}}</strong></td>
                                        </tr>
                                        <tr class="tr">
                                            <td>Marks Obtained:</td>
                                            <td><strong>{{$data->marks_obtained ?? ''}}</strong></td>
                                        </tr>
                                        <tr class="tr">
                                            <td>Passing Year:</td>
                                            <td><strong>{{$data->passing_year ?? ''}}</strong></td>
                                        </tr>
                                        <tr class="tr">
                                            <td>First Name:</td>
                                            <td><strong>{{$data->first_name ?? ''}}</strong></td>
                                        </tr>
                                        <tr class="tr">
                                            <td>Last Name:</td>
                                            <td><strong>{{$data->last_name ?? ''}}</strong></td>
                                        </tr>
                                        <tr class="tr">
                                            <td>Contact:</td>
                                            <td><strong>{{$data->contact ?? ''}}</strong></td>
                                        </tr>
                                        <tr class="tr">
                                            <td>Image:</td>
                                            <td><strong><img id="display-image-preview" src="{{url('/public/image/'.$data->image ?? '' )}}" alt="" class="mb-2" style="max-height: 50px;"></strong></td>
                                        </tr>
                                        <tr><td></td><td></td></tr>
                                    </table>
                                </div>
                            </fieldset>
                                <a href="{{route('getStepThree',['token' => $data->token])}}" type="button" name="previous" class="previous action-button-previous" value="">Previous</a>
                                <input type="submit" name="make_payment" class="next action-button" value="Confirm">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
@include('template.footer') 
<script type="text/javascript">
 $(document).ready(function (e) {
    //For Preiew Image
    $('#image').change(function(){    
      let reader = new FileReader();
      reader.onload = (e) => { 
        $('#display-image-preview').attr('src', e.target.result); 
      }
      reader.readAsDataURL(this.files[0]); 
    });
    $('body').keypress(function(e){
        if (e.keyCode == 13)
        {
            $('#msform').submit();
        }
    });
  });
</script>