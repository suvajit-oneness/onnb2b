@extends('front.profile.layouts.app')

@section('profile-content')
    <div class="col-sm-7">
        @php
            $state_area = DB::select('SELECT state, area FROM retailer_list_of_occ GROUP BY area ORDER BY state ASC, area ASC');
        @endphp

        <div class="profile-card">
            <form class="createField" method="POST" action="{{ route('front.user.manage.update') }}">@csrf
                <h3>Edit Profile</h3>
                <div class="row">
                    @if (Auth::guard('web')->user()->user_type == 6)
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="floating-label">Name</label>
                                <input type="text" class="form-control" name="name"
                                    value="{{ Auth::guard('web')->user()->name }}" readonly>
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    @else
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="floating-label">First Name</label>
                                <input type="text" class="form-control" placeholder="First Name" name="fname"
                                    value="{{ Auth::guard('web')->user()->fname }}">
                                @error('fname')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="floating-label">Last Name</label>
                                <input type="text" class="form-control" placeholder="Last Name" name="lname"
                                    value="{{ Auth::guard('web')->user()->lname }}">
                                @error('lname')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    @endif
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="floating-label">Email Address</label>
                            <input type="email" class="form-control" placeholder="Email Address"
                                value="{{ Auth::guard('web')->user()->email }}" name="email">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="floating-label">Mobile Number</label>
                            <input type="number" class="form-control" placeholder="Mobile No"
                                value="{{ Auth::guard('web')->user()->mobile }}" name="mobile" onkeypress="return onlyNumberKey(event)">
                            @error('mobile')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="floating-label">WhatsApp Number</label>
                            <input type="number" class="form-control" placeholder="WhatsApp No"
                                value="{{ Auth::guard('web')->user()->whatsapp_no }}" name="whatsapp_no" onkeypress="return onlyNumberKey(event)">
                            @error('whatsapp_no')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-12 my-2">
                        <h5 class="text-dark">Address</h5>
                        <hr>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="floating-label">Address</label>
                            <textarea name="address" class="form-control" style="height: 100px;min-height: 100px;max-height: 150px" placeholder="Adresss">{{ Auth::guard('web')->user()->address }}</textarea>
                            @error('address')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="floating-label">Landmark</label>
                            <input type="text" class="form-control" placeholder="Landmark"
                                value="{{ Auth::guard('web')->user()->landmark }}" name="landmark">
                            @error('landmark')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="floating-label">State</label>
                            @php
                                $statesOnly = [];
                                foreach ($state_area as $stateKey => $stateValue) {
                                    if (!in_array($stateValue->state, $statesOnly)) {
                                        array_push($statesOnly, $stateValue->state);
                                    }
                                }
                                $selectedState = Auth::guard('web')->user()->state ? Auth::guard('web')->user()->state : $statesOnly[0];
                            @endphp
                            <select name="state" class="form-control" onchange="cityGenerate($(this).val())">
                                @foreach ($statesOnly as $statesOnlyvalue)
                                    <option value="{{ $statesOnlyvalue }}"
                                        {{ $selectedState == $statesOnlyvalue ? 'selected' : '' }}>
                                        {{ $statesOnlyvalue }}</option>
                                @endforeach
                            </select>
                            @error('state')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="floating-label">Area</label>
                            <select name="city" class="form-control"></select>
                            @error('city')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="floating-label">Pin</label>
                            <input type="number" class="form-control" placeholder="Pin"
                                value="{{ Auth::guard('web')->user()->pin }}" name="pin" onkeypress="return onlyNumberKey(event)">
                            @error('pin')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    @if (Auth::guard('web')->user()->user_type != 6)
                        <div class="col-sm-12 my-2">
                            <h5 class="text-dark">Other details</h5>
                            <hr>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="floating-label">Aadhar Card Number</label>
                                <input type="number" class="form-control" placeholder="Adhar Card Number"
                                    value="{{ Auth::guard('web')->user()->adhar_no }}" name="adhar_no" onkeypress="return onlyNumberKey(event)">
                                @error('adhar_no')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="floating-label">PAN card number</label>
                                <input type="text" class="form-control" placeholder="PAN card number"
                                    value="{{ Auth::guard('web')->user()->pan_no }}" name="pan_no">
                                @error('pan_no')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="floating-label">DOB</label>
                                <input type="date" class="form-control" placeholder="DOB"
                                    value="{{ Auth::guard('web')->user()->dob }}" name="dob">
                                @error('dob')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="floating-label">Anniversary date</label>
                                <input type="date" class="form-control" placeholder="Anniversary date"
                                    value="{{ Auth::guard('web')->user()->anniversary_date }}" name="anniversary_date">
                                @error('anniversary_date')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="floating-label">Gender</label>
                                <div class="btn-group custom-radio" role="group" aria-label="Basic radio toggle button group">
                                    
                                    <label class="btn cus_sel" for="btnradio1">Male
                                        <input type="radio" class="btn-check" name="gender" id="btnradio1"
                                        value="Male" autocomplete="off"
                                        {{ Auth::guard('web')->user()->gender == 'Male' ? 'checked' : '' }}>
                                    </label>
                                    <label class="btn cus_sel" for="btnradio2">Female
                                        <input type="radio" class="btn-check" name="gender" id="btnradio2"
                                        value="Female" autocomplete="off"
                                        {{ Auth::guard('web')->user()->gender == 'Female' ? 'checked' : '' }}>
                                    </label>

                                    
                                    <label class="btn cus_sel" for="btnradio3">Others
                                        <input type="radio" class="btn-check" name="gender" id="btnradio3"
                                        value="Others" autocomplete="off"
                                        {{ Auth::guard('web')->user()->gender == 'Others' ? 'checked' : '' }}>
                                    </label>
                                </div>
                                @error('gender')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    @endif
                    {{-- <div class="col-sm-12">
                        <div class="form-group">
                            <label class="floating-label">Social Id</label>
                            <input type="text" class="form-control" placeholder="Social id"
                                value="{{ Auth::guard('web')->user()->social_id }}" name="social_id">
                            @error('social_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div> --}}
                </div>

                <div class="profile-card-footer">
                    <button type="submit" class="btn sweetRedBtn">Update Details</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function cityGenerate(state) {
            const statesArr = '<?php echo json_encode($state_area); ?>';
            const parsedStatesArr = JSON.parse(statesArr);

            var city = '';
            $.each(parsedStatesArr, (key, value) => {
                if (value.state == state) {
                    city +=
                        `<option value="${value.area}" ${value.area == '{{ Auth::guard('web')->user()->city }}' ? 'selected' : '' }>${value.area}</option>`;
                }
            });
            $('select[name="city"]').html(city);
        }
        cityGenerate('{{ $selectedState }}');
    </script>
@endsection
