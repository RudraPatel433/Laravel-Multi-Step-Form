@include('template.header')
<style>
    img {
        display: block;
        max-width: 100%;
    }

    .preview {
        overflow: hidden;
        width: 160px;
        height: 160px;
        margin: 10px;
        border: 1px solid red;
    }

    .modal-lg {
        max-width: 1000px !important;
    }
</style>

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
                            <form id="msform" action="{{route('saveStepThree')}}" class="submitform" enctype="multipart/form-data" method="POST">
                                @csrf
                                <!-- progressbar -->
                                <ul id="progressbar">
                                    <li class="active" id="account"><strong>Create your account</strong></li>
                                    <li class="active" id="personal"><strong>Educational Profiles</strong></li>
                                    <li class="active" id="payment"><strong>Personal Details</strong></li>
                                    <li id="confirm"><strong> Review Details</strong></li>
                                </ul>
                                <fieldset>
                                    <div class="form-card" id="card-form">
                                        <input type="hidden" id="id" name="id" placeholder="id" value="{{ $data->id ?? '' }}" />
                                        <h2 class="fs-title mb-5">Personal Details</h2>
                                        <input type="text" name="first_name" value="{{ $data->first_name ?? '' }}" placeholder="Enter Your First Name" />
                                        <input type="text" name="last_name" value="{{ $data->last_name ?? '' }}" placeholder="Enter Your Last Name" />
                                        <input type="text" name="contact" value="{{ $data->contact ?? '' }}" placeholder="Enter Your Contact" />
                                        <div id="card_{{ $data->id ?? '' }}">
                                        <input type="file" enctype="multipart/form-data" name="images" class="image" id="images" value="{{ $data->image ?? '' }}" />
                                        <img id="display-image-preview" src="{{url('/public/image/'.$data->image ?? '' )}}" value="{{ $data->image ?? '' }}" alt="" class="mb-2" style="max-height: 50px;">
                                        </div>
                                        <input type="hidden" name="steps" placeholder="" value="3" />
                                    </div>
                                    <a href="{{route('getStepTwo', ['token' => $data->token])}}" type="button" name="previous" class="previous action-button-previous" value="">Previous</a>
                                    <input type="submit" name="next" class="next action-button" value="Next Step">
                                </fieldset>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Crop Image</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="img-container">
                        <div class="row">
                            <div class="col-md-8">
                                <img id="image" name="image" src="https://avatars0.githubusercontent.com/u/3456749">
                            </div>
                            <div class="col-md-4">
                                <div class="preview" name="image"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="crop">Crop</button>
                </div>
            </div>
        </div>
    </div>

    </div>
    </div>
</body>
@include('template.footer')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
//****************************** Validation ****************************************//
        var value = $('.image').attr('value');
        $('.submitform').validate({
            
            ignore: [],
            rules: {
                first_name: {
                    lettersonly: true,
                    required: true,
                },
                last_name: {
                    lettersonly: true,
                    required: true,
                },
                contact: {
                    required: true,
                    number: true,
                    minlength: 10,
                    maxlength: 10,
                },
                images:{
                    required: function () {
                        if (value == "") {
                           return true;
                        }else{
                            return false;
                        }
                    }
                }
            },
            messages: {
                first_name: {
                    required: "First Name Is Required.",
                    lettersonly: "Please Enter Letters"

                },
                last_name: {
                    required: "Last Name Is Required.",
                    lettersonly: "Please Enter Letters"

                },
                contact: {
                    required: "Contact Number Is Required.",
                    number: "Enter Valid Contact Number",
                    minlength: "Enter Your 10 digits Contact Number",
                    maxlength: "Enter Your 10 digits Contact Number"
                },
            },
        });
    });
//****************************** Image Preview ****************************************//
    $(document).ready(function(e) {
        //For Preiew Image
        $('.image').change(function() {
            let reader = new FileReader();
            reader.onload = (e) => {
                $('#display-image-preview').attr('src', e.target.result);
            }
            reader.readAsDataURL(this.files[0]);
        });

        $('body').keypress(function(e){
        if (e.keyCode == 13)
        {
            $('.submitform').submit();
        }
    });
    });

//****************************** Crop Image ****************************************//
    var $modal = $('#modal');
    var image = document.getElementById('image');
    var cropper;

    $("body").on("change", ".image", function(e) {
        var files = e.target.files;
        var done = function(url) {
            image.src = url;
            $modal.modal('show');
        };
        var reader;
        var file;
        var url;
        if (files && files.length > 0) {
            file = files[0];
            if (URL) {
                done(URL.createObjectURL(file));
            } else if (FileReader) {
                reader = new FileReader();
                reader.onload = function(e) {
                    done(reader.result);
                };
                reader.readAsDataURL(file);
            }
        }
    });
    $modal.on('shown.bs.modal', function(e) {
        cropper = new Cropper(image, {
            aspectRatio: 1,
            viewMode: 9,
            preview: '.preview'
        })
    }).on('hidden.bs.modal', function() {
        cropper.destroy();
        cropper = null;
    });
    $("#crop").click(function() {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        canvas = cropper.getCroppedCanvas({
            width: 160,
            height: 700,
        });
        canvas.toBlob(function(blob) {
            url = URL.createObjectURL(blob);
            var reader = new FileReader();
            reader.readAsDataURL(blob);
            var id = $('#id').val();
            reader.onloadend = function() {
                var base64data = reader.result;

                $.ajax({
                    type: "POST",
                    url: "{{route('image')}}",
                    data: {
                        'image': base64data,
                        'id': id
                    },
                    success: function(data) {
                        $("card_"+id).load(window.location+"card_"+id);
                        $modal.modal('hide');

                    }
                });
            }
        });
    })

    
</script>