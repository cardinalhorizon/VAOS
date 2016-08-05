VAOS ALPHA INSTALL INSTRUCTIONS

THIS SYSTEM HAS YET TO BE VERIFIED WORKING WITH CPANEL

1. Create a new Database in MySQL.

2. modify the environment file with your database information (VAOS/.env)

3. using a command prompt, run the following commands:

php artisan key:generate

php artisan migrate

if you get no errors, you have successfully installed the VAOS API onto your server. For instructions on how to use the api, refer to the forums http://fourms.fsvaos.net/


Please note that VAOS is under a closed license for the time being. I will decide on the appropriate license when we get to beta and release.

Thanks,

Taylor Broad

Creator of VAOS