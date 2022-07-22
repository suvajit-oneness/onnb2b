@extends('front.profile.layouts.app')

@section('profile-content')
    <div class="col-sm-7">
        <div class="profile-card">
            <form class="createField" action="{{ route('front.user.password.update') }}" method="POST">
                @csrf
                <h3>Change Password</h3>
                <div class="col-sm-9">
                    <div class="form-group">
                        <label for="form-label">Old Password<span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="old_password" placeholder="Old password">
                    </div>
                    @error('old_password')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-sm-9">
                    <div class="form-group">
                        <label for="form-label">New Password<span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="new_password" placeholder="New password">
                    </div>
                    @error('new_password')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-sm-9">
                    <div class="form-group">
                        <label for="form-label">Confirm Password<span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="confirm_password"
                            placeholder="Confirm new password">
                    </div>
                    @error('confirm_password')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-sm-9">
                    <button type="submit" class="btn btn-danger">Update Password</button>
                </div>
            </form>
        </div>
    </div>
@endsection
