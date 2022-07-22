@extends('front.profile.layouts.app')

@section('profile-content')
<div class="col-sm-7">
    <form method="POST" action="{{route('front.user.address.create')}}">@csrf
        <div class="profile-card">
            <h3>Add Address</h3>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Country/Region" name="country" value="India">
                        <label class="floating-label">Country/Region</label>
                    </div>
                    @error('country')<p class="small text-danger mb-0">{{$message}}</p>@enderror
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="First Name" value="{{(old('fname')) ? old('fname') : Auth::guard('web')->user()->fname}}">
                        <label class="floating-label">First Name</label>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Last Name" value="{{(old('lname')) ? old('lname') : Auth::guard('web')->user()->lname}}">
                        <label class="floating-label">Last Name</label>
                    </div>
                </div>
                {{-- <div class="col-sm-12">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Company (Optional)">
                        <label class="floating-label">Company (Optional)</label>
                    </div>
                </div> --}}
                <div class="col-sm-12">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Address" name="address" value="{{old('address')}}">
                        <label class="floating-label">Address</label>
                    </div>
                    @error('address')<p class="small text-danger mb-0">{{$message}}</p>@enderror
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Landmark" name="landmark" value="{{old('landmark')}}">
                        <label class="floating-label">Landmark</label>
                    </div>
                    @error('landmark')<p class="small text-danger mb-0">{{$message}}</p>@enderror
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Location" id="location">
                        <label class="floating-label">Location</label>
                    </div>
                    @error('lat')<p class="small text-danger mb-0">{{$message}}</p>@enderror
                    @error('lng')<p class="small text-danger mb-0">{{$message}}</p>@enderror
                </div>
                {{-- <div class="col-sm-12">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Apartment, suite, etc. (optional)">
                        <label class="floating-label">Apartment, suite, etc. (optional)</label>
                    </div>
                </div> --}}
                <div class="col-sm-4">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="City" name="city" value="{{old('city')}}">
                        <label class="floating-label">City</label>
                    </div>
                    @error('city')<p class="small text-danger mb-0">{{$message}}</p>@enderror
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="State" name="state" value="{{old('state')}}">
                        <label class="floating-label">State</label>
                    </div>
                    @error('state')<p class="small text-danger mb-0">{{$message}}</p>@enderror
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Pin Code" name="pin" value="{{old('pin')}}">
                        <label class="floating-label">Pin Code</label>
                    </div>
                    @error('pin')<p class="small text-danger mb-0">{{$message}}</p>@enderror
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <input type="number" class="form-control" placeholder="Type" name="type" value="{{old('type')}}">
                        <label class="floating-label">Type</label>
                    </div>
                    @error('type')<p class="small text-danger mb-0">{{$message}}</p>@enderror
                </div>
            </div>

            <div class="profile-card-footer">
                <input type="hidden" name="user_id" value="{{ Auth::guard('web')->user()->id }}">
                <input type="hidden" id="lat" name="lat">
                <input type="hidden" id="lng" name="lng">
                <button type="submit" class="btn checkout-btn">Add Address</button>
            </div>
        </div>
    </form>
</div>
@endsection

@section('script')
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDPuZ9AcP4PHUBgbUsT6PdCRUUkyczJ66I&libraries=places,geometry&callback=initMap&v=weekly"></script>

<script>
    google.maps.event.addDomListener(window,'load',initialize(1));

    function initialize(){
        var autocomplete= new google.maps.places.Autocomplete(document.getElementById('location'));

        google.maps.event.addListener(autocomplete, 'place_changed', function(){
            var places = autocomplete.getPlace();
            console.log(places);
            $('#location').val(places.formatted_address);
            $('#lng').val(places.geometry.location.lng());
            $('#lat').val(places.geometry.location.lat());
        });
    }
</script>
@endsection