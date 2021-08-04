<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Security\Models\User;
use App\Modules\Security\Models\Organizations;
use App\Modules\Security\Models\UserRoles;
use App\Modules\Security\Models\Roles;
use App\Modules\Security\Models\Modules;
use App\Modules\Security\Models\Screens;
use App\Modules\Security\Models\Applications;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;



class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


         $applications =  [
             [
              'app_code' => 'WEB',
              'app_description' => 'Web Application',
            ],
            
            [
              'app_code' => 'FMOB',
              'app_description' => 'Farmer Mobile Application',
            ],

            [
              'app_code' => 'CMOB',
              'app_description' => 'Customer Mobile Application',
            ],


          ];
        if(DB::table('applications')->count() == 0){
     
             DB::table('applications')->insert($applications);

         }

        $modules =  [
            [
              'app_id' => '1',
              'mod_code' => 'ORG',
              'mod_description' =>'Organization',
              'order_id'  => '1',
            ],
            
            [
              'app_id' => '1',
              'mod_code' => 'FMR',
              'mod_description' =>'Farmer',
              'order_id'  => '2',
            ],

            [
              'app_id' => '1',
              'mod_code' => 'RPT',
              'mod_description' =>'Reports',
              'order_id'  => '3',
            ],

            [
              'app_id' => '1',
              'mod_code' => 'SET',
              'mod_description' =>'Settings',
              'order_id'  => '4',
            ],

          ];     
         
         if(DB::table('modules')->count() == 0){
     
          DB::table('modules')->insert($modules);
         }

          $screens =  [
            [
              'mod_id' => '1',
              'scr_code' => 'ORGL',
              'scr_description' =>'Organizations',
              'scr_type'  => 'ADM',
              'order_id' => '1'
            ],
            
            [
              'mod_id' => '1',
              'scr_code' => 'ROL',
              'scr_description' =>'Roles',
              'scr_type'  => 'ADM',
              'order_id' => '1'
            ],

            [
              'mod_id' => '1',
              'scr_code' => 'USR',
              'scr_description' =>'Users',
              'scr_type'  => 'ADM',
              'order_id' => '2'
            ],

            [
              'mod_id' => '4',
              'scr_code' => 'SET',
              'scr_description' =>'Settings',
              'scr_type'  => 'ADM',
              'order_id' => '1'
            ], 

            [
              'mod_id' => '4',
              'scr_code' => 'CHP',
              'scr_description' =>'Change Password',
              'scr_type'  => 'ADM',
              'order_id' => '2'
            ], 
            [
              'mod_id' => '1',
              'scr_code' => 'ORGD',
              'scr_description' =>'Organization Details',
              'scr_type'  => 'CUST',
              'order_id' => '1'
            ], 
            [
              'mod_id' => '1',
              'scr_code' => 'ROL',
              'scr_description' =>'Roles',
              'scr_type'  => 'CUST',
              'order_id' => '1'
            ], 
            [
              'mod_id' => '1',
              'scr_code' => 'USR',
              'scr_description' =>'Users',
              'scr_type'  => 'CUST',
              'order_id' => '2'
            ], 
            [
              'mod_id' => '2',
              'scr_code' => 'FMR',
              'scr_description' =>'Farmers',
              'scr_type'  => 'CUST',
              'order_id' => '1'
            ], 
            [
              'mod_id' => '2',
              'scr_code' => 'CRP',
              'scr_description' =>'Crops',
              'scr_type'  => 'CUST',
              'order_id' => '2'
            ], 
            [
              'mod_id' => '4',
              'scr_code' => 'SUB',
              'scr_description' =>'Subscriptions',
              'scr_type'  => 'CUST',
              'order_id' => '3'
            ], 

          ];

          if(DB::table('screens')->count() == 0){

             DB::table('screens')->insert($screens);
          
          }


           $organizations =  [
            [
              'app_id'   => '1',
              'org_code' => 'admin',
              'org_name' => 'Agribridge',
              'is_super_admin' =>'1',
            ],
            [
              'app_id'   => '1',
              'org_code' => 'Administrator',
              'org_name' => 'Agribridge',
              'is_super_admin' =>'2',
            ],


           ];

           if(DB::table('organizations')->count() == 0){

              DB::table('organizations')->insert($organizations);
           
           }


          $roles =  [
            [
              'org_id'   => '1',
              'role_name' => 'Super Admin',
            ], 

            [
              'org_id'   => '2',
              'role_name' => 'Administrator',
            ],

           ];

           if(DB::table('roles')->count() == 0){

               DB::table('roles')->insert($roles);

           }
          

           $users =  [
            [
              'user_name' => 'Super Admin',
              'org_id' => '1',
              'mobile_number' => '8989898989',
              'email' => 'superadmin@gmail.com',
              'user_password' => bcrypt('Dev@123$'),
              'token' => Str::random('64'),
            ],
            [
              'user_name' => 'Project Admin',
              'org_id' => '2',
              'mobile_number' => '7878787878',
              'email' => 'projectadmin@gmail.com',
              'user_password' => bcrypt('Dev@123$'),
              'token' => Str::random('64'),
            ],
            [
              'user_name' => 'clientadmin',
              'org_id' => '2',
              'mobile_number' => '9898989898',
              'email' => 'clientadmin@gmail.com',
              'user_password' => bcrypt('Dev@123$'),
              'token' => Str::random('64'),
            ],

          ];

          if(DB::table('users')->count() == 0){

                DB::table('users')->insert($users);

           }

    }
}
