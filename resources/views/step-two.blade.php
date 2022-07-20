@include('template.header')

<body id="grad1">
<div class="container-fluid">
    <div class="row justify-content-center mt-0">
        <div class="col-11 col-sm-9 col-md-7 col-lg-6 text-center p-0 mt-3 mb-2">
            <div class="card px-0 pt-4 pb-0 mt-3 mb-3">
            <a href="{{ route('index') }}" class="btn-info mb-3" style="width: 130px;!important; padding:10px; margin-left:80%;!important; background:skyblue;"><i class="fa-solid fa-bars mr-2"></i> Students </a>
                <h3><strong>Sign Up Your User Account</strong></h3>
                <p>Fill all form field to go to next step</p>
                <div class="row">
                    <div class="col-md-12 mx-0">
                        <form id="msform" action="{{route('saveStepTwo')}}" class="submitform" method="POST">
                        @csrf
                            <!-- progressbar -->
                            <ul id="progressbar">
                                <li class="active" id="account"><strong>Create your account</strong></li>
                                <li class="active" id="personal"><strong>Educational Profiles</strong></li>
                                <li id="payment"><strong>Personal Details</strong></li>
                                <li id="confirm"><strong> Review Details</strong></li>
                            </ul>
                            <!-- fieldsets -->
                            <fieldset>
                                <input type="hidden" class="id" name="id" id="id" value="{{ $data->id ?? '' }}">
                                <div class="form-card">
                                    <h2 class="fs-title mb-5">Basic Info</h2>
                                    <input type="text" name="marks_obtained" value="{{ $data->marks_obtained ?? '' }}" placeholder="Total Marks"/>
                                    <!-- <input type="year" name="passing_year" value="{{ $data->passing_year ?? '' }}" placeholder="Passing Year"/> -->
                                    <div>
                                        <label class="d-inline text-dark">Select Year:</label> 
                                        <select class="form-control d-inline ml-2" style="width:120px;" name="passing_year" id="year">
                                            <option name="passing_year" value="2015" @if(!empty($data)){{ $data->passing_year == 2015 ? 'selected' : '' }}@endif>2015 </option>
                                            <option name="passing_year" value="2016" @if(!empty($data)){{ $data->passing_year == 2016 ? 'selected' : '' }}@endif>2016</option>
                                            <option name="passing_year" value="2017" @if(!empty($data)){{ $data->passing_year == 2017 ? 'selected' : '' }}@endif>2017</option>
                                            <option name="passing_year" value="2018" @if(!empty($data)){{ $data->passing_year == 2018 ? 'selected' : '' }}@endif>2018</option>
                                            <option name="passing_year" value="2019" @if(!empty($data)){{ $data->passing_year == 2019 ? 'selected' : '' }}@endif>2019</option>
                                            <option name="passing_year" value="2020" @if(!empty($data)){{ $data->passing_year == 2020 ? 'selected' : '' }}@endif>2020</option>
                                        </select>
                                    </div>
                                    <input type="hidden" name="steps" placeholder="" value="2"/>
                                </div>
                                <a href="{{route('StepOne', ['token' => $data->token])}}" type="button" name="previous" class="previous action-button-previous" value="Previous">Previous</a>
                                <input type="submit" name="next" class="next action-button" value="Next Step">
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
    // alert("welcome to Step Two....");
    $('.submitform').validate({
        ignore:[],
        rules:{
            marks_obtained:{
                required: true,
                digits: true
            },
            passing_year:{
                required: true,
            }
        },
        messages:{
            marks_obtained: {
                required: "Total Marks Is Required",
                digits: "Enter Total Marks in Numeric"
            },
            passing_year: {
                required: "Passing Year Is Required",
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