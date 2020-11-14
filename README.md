# covid19 immunization

About Covid19 Immunization Demo


Online Testing

Covid19 Immunization can be tested online either from 

https://qbtut.com/covid19_immunization/   or  from

https://hackathon20-ngwacham.quickbase.com/db/bqyvtr2kz

You can signup and Login. You will be required to perform security check by updating your Security Questions 2-way factor authentications and
you are good to go





For Local Testing(Optional)


To Run this application Locally, download the code from github  here https://github.com/pman56/covid19immunization .  You can have Xampp Server Install and ensure that PHP is running.
Our Applications leverages QuickBase Json and XML API Calls to perform all data manipulations activities
as regards to Insert, Update, Delete and Select etc.

The php Files that houses the Quickbase Configurable credentials includes 

# 1.) quickbase_pass.php : 
This is the first script to be updated. Here you will enter your Quickbase username and password to get
auth_ticket which you will be used later in the files below.

# 2.) Quickbase_token.php: 
Houses your quickbase access token, app_token, auth_ticket and so on.
# 3.) Quickbase_token1.php: 
Houses your quickbase access token etc.
# 4.)Quikbase_table: 
Houses all the table ID's used in the application.


# 5.)Signup_action.php: 
This is just for informations. Here you dont need to edit this two line of code below unless you want to replace
 your own quickbase domain or possibly change the users table Id

$users_table_db='users table id goes here';

$url4="https://hackathon20-ngwacham.quickbase.com/db/$users_table_db";

6.) call up browser at http://localhost/covid19_immunization/index.php
