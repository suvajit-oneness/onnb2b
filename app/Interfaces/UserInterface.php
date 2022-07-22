<?php

namespace App\Interfaces;

interface UserInterface
{
    /**
     * This method is for show user list
     *
     */
    public function listAll();
     /**
     * This method is for show user details
     * @param  $id
     *
     */
    public function listById($id);
    /**
     * This method is for create user
     *
     */
    public function create(array $data);
    /**
     * This method is for user update
     *
     *
     */
    public function update($id, array $data);
    /**
     * This method is for update user status
     * @param  $id
     *
     */
    public function toggle(array $params);
    /**
     * This method is for update user verification
     * @param  $id
     *
     */
    public function verification($id);
    /**
     * This method is for user delete
     * @param  $id
     *
     */
    public function delete($id);

   /**
     * This method is to get user activity details
     * @param str $id
     */
    public function useractivity();
    public function storeVisit();
    public function storeVisitlist($storeId);
    /**
     * This method is to get user activity details by Id
     * @param str $id
     */
    public function useractivitylistById($id);
    /**
     * This method is to create user activity details
     * @param str $data
     * return array
     */
    public function useractivitycreate(array $data);
    public function storeVisitCreate(array $data);
    /**
     * This method is to update user activity details
     * @param str $id
     */
    public function useractivityupdate($id,array $newDetails);
    /**
     * This method is to delete user activity details
     *
     */
    public function useractivitydelete($id);

    /**
     * This method is to get user attendance details
     *
     */
    public function userattendance();
    /**
     * This method is to get user attendance details by Id
     * @param str $id
     */
    public function userattendanceById($id);
    /**
     * This method is to create user attendance details
     * @param str $data
     * return array
     */
    public function userattendancecreate(array $data);
    /**
     * This method is to update user attendance details
     * @param str $id
     */
    public function userattendanceupdate(int $id,array $newDetails);
    /**
     * This method is to delete user attendance details
     *
     */
    public function userattendancedelete($id);



    /**
     * This method is to get user start day end day details
     *
     */
    public function userday();
    /**
     * This method is to get user start day end day details by Id
     * @param str $id
     */
    public function userdaylistById($id);
    /**
     * This method is to create user start day end day details
     * @param str $data
     * return array
     */
    public function userdaycreate(array $data);
    /**
     * This method is to update user start day end day details
     * @param str $id
     */
    public function userdayupdate(int $id,array $newDetails);
    /**
     * This method is to delete user start day end day details
     *
     */
    public function userdaydelete($id);

     /**
     * This method is to check validation of mobile no and generate otp
     * @param str $mobileNo
     *
     */


    public function otpGenerate($mobileNo);
    /**
     * This method is to check validation of otp
     * @param str $otp
     *
     */

    public function otpcheck($mobile, $otp);

    /**
     * This method is to get user  details by Id
     * @param str $id
     */
    public function userdetailsById($id);

    public function distributorcreate(array $data);
    public function distributorlist();
    public function distributorview($id);


    public function addressById($id);
    public function addressCreate(array $data);
    public function updateProfile(array $data);
    public function updatePassword(array $data);
    public function orderDetails();
    public function orderByStore(int $store_id);
    public function recommendedProducts();
    public function wishlist();
    public function couponList();

    /**
     * This method is to check login
     * @param str $otp
     *
     */
    public function mobilelogin($email,$password);



    /**
     * show activity in admin section
     */
    public function useractivitylog();
    public function getActivityByFilter($date,$time,$userId,$userType);
}
