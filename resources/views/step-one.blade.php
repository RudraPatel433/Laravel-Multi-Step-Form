@include('template.header')
<body id="grad1">
<div class="container-fluid">

    <div class="row justify-content-center mt-0">
        <div class="col-11 col-sm-9 col-md-7 col-lg-6 text-center p-0 mt-3 mb-2">
            <div class="card px-0 pt-4 pb-0 mt-3 mb-3">
            <a href="{{ route('index') }}" class="btn-info mb-3" style="width: 130px;!important; padding:10px; margin-left:80%;!important; background:skyblue;"><i class="fa-solid fa-bars mr-2"></i> Students </a>
                <h2><strong>Sign Up Your User Account</strong></h2>
                <p>Fill all form field to go to next step</p>
                <div class="row">
                    <div class="col-md-12 mx-0">
                        <form id="msform" action="{{route('saveStepOne')}}" class="submitform" method="post">
                        @csrf
                            <!-- progressbar -->
                            <ul id="progressbar">
                                <li class="active" id="account"><strong>Create your account</strong></li>
                                <li id="personal"><strong>Educational Profiles</strong></li>
                                <li id="payment"><strong>Personal Details</strong></li>
                                <li id="confirm"><strong> Review Details</strong></li>
                            </ul>
                            <!-- fieldsets -->
                            <fieldset>
                                <div class="form-card">
                                <input type="hidden" class="id" name="id" id="id" value="{{ $data->id ?? '' }}">
                                <input type="hidden" class="token" name="token" id="token" value="{{ $data->token ?? '' }}">
                                    <h2 class="fs-title mb-5">Create your account</h2>
                                    <input type="text" name="name" value="{{ $data->name ?? '' }}" placeholder="Enter Your Name"/>
                                    <input type="email" name="email" value="{{ $data->email ?? '' }}" placeholder="Email Id"/>

                                    <input type="hidden" name="steps" placeholder="" value="1"/>
                                </div>
                                <input type="submit" name="next" class="next action-button" value="Next"/>
                            </fieldset>
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
$(document).ready(function(){
    // alert("welcome to Step One....");
    $('.submitform').validate({
        ignore:[],
        rules:{
            name:{
                required: true,
            },
            email:{
                required: true,
                email:true,
            }
        },
        messages:{
            name: "Name Is Required",
            email: {
                required: "Email Is Required",
                email: "Please Enter Valid Email"
            },
        },
    });

    $('body').keypress(function(e){
        if (e.keyCode == 13)
        {
            $('.submitform').submit();
        }
    });
});


</script>