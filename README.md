<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

## Install & Setup

Since this backend is powered by Laravel, first you need to install **PHP** with extensions and **Composer** (PHP package manager). 

Also you need to install **MySQL** and create a database. 

After that rename `.env.example` file to `.env` and change following variables there:
- `DB_DATABASE=`name_of_db_you've_created_in_mysql
- `DB_USERNAME=`root_or_another_user_that_you've_created
- `DB_PASSWORD=`password_if_you've_set_one

It's worth to mention that if you've created a new user in MySQL you should grant proper privileges to him.


We also gonna use mail confirmation on some operations so we need **[Mailtrap](https://mailtrap.io/)** to make it easy.

 Sign up with your github profile, go to your `Inboxes` tab, `SMTP Settings`, in `Integrations` section choose `Laravel 7+` from drop down, copy those env variables and put them in `.env` file. Also add sender address to your env:
- `MAIL_FROM_ADDRESS=`test@gmail.com (for example)

## Pre-Launch

In app directory run:
- `composer install`
- `php artisan key:generate`
- `php artisan migrate --seed`

## Launch

To launch the app run:
-  `php artisan serve`

Your backend will be served on 8000 port by default.

## Miscellaneous

You can configure MySQL to start on every system startup by entering following command in command line: 
- `sudo systemctl enable mysql`

If you want to clean your database you can run:
- `php artisan migrate:fresh --seed`

If you want completely fresh database without seeder you can run:
- `php artisan migrate:fresh`


 Also consider following enironment variables that are already set:
 - `FRONTEND_URL=`http://localhost:3000 (default)
 - `EMAIL_VERIFICATION_TOKEN_LIFETIME=`5 (default)
 - `PASSWORD_RESET_TOKEN_LIFETIME=`10 (default)
 - `PASSWORD_RECOVER_TOKEN_LIFETIME=`10 (default)
 - `USERS_PAGINATE=`10 (default)
 - `POSTS_PAGINATE=`4 (default)

 If you want to change those variables you have to put desired variable in your `.env` file with your value, after that execute following commands:
 - `php artisan cache:clear`
 - `php artisan config:clear`

 And restart the backend to ensure it has seen new variables


 ## Routes

 All of the routes are prefixed with `api`. For example: *`http://localhost:8000/api/users`*

### **Authentication**
<hr>

POST `auth/register` Register new user

**Body Parameters**

- `first_name` - required, min 2, max 50
- `last_name` - required, min 2, max 50
- `email` - required, valid email, unique
- `password` - required, should have numbers, capital and small letters, special symbols. (123456789Aa!)

**Success response**

<pre>
{
    "token": $authToken
}
</pre>

The letter will be sent to given email with redirection link inside to `$frontendUrl/email-verification/$emailToken`
<hr>

POST `auth/login`Login

**Body Parameters**

- `email` - required, should be a valid email
- `password` - required

**Success response**

<pre>
{
    "token": $authToken
}
</pre>
<hr>

GET `auth/logout` Logout

**Headers Parameters**

- `Authorization: Bearer $authToken`

**Success response**

`No content`

<br>

### **Email verification**
<hr>

POST `email/verify/$emailToken` Verify email

**Query Parameters**

- `$emailToken` - will be received from email redirection

**Success response**

`No content`
<hr>

POST `email/resend` Resend a letter for email verification

**Headers Parameters**

- `Authorization: Bearer $authToken`

**Success response**

<pre>
{
    "message": "The email has been sent"
}
</pre>
The letter will be sent to given email with redirection link inside to `$frontendUrl/email-verification/$emailToken`

<br>

### **Forgot password**
<hr>

POST `forgot-password` Initialize forgot-password procedure

**Body parameters**

- `email` - required, valid email

**Success response**

<pre>
{
    "message": "You will receive a mail to recover the password if this is proper email"
}
</pre>
The letter will be sent to given email with redirection link inside to `$frontendUrl/forgot-password/$passwordToken`
<hr>

POST `recover/$passwordToken` Recover password

**Query parameters**

- `$passwordToken` - will be received from email redirection 

**Body parameters**

- `password` - required, should have numbers, capital and small letters, special symbols. (123456789Aa!)

**Success response**

<pre>
{
    "message": "You can use new password to login"
}
</pre>

<br>

### **Reset password**
<hr>

POST `reset-password` Request password reset

The email should be verified!

**Headers parameters**

- `Authorization: Bearer $authToken`

**Body parameters**

- `password` - required, should have numbers, capital and small letters, special symbols. (123456789Aa!)

**Success response**

<pre>
{
    "message": "Check your mailbox to confirm password resetting"
}
</pre>
The letter will be sent to given email with redirection link inside to `$frontendUrl/reset-password/$passwordToken`
<hr>

POST `reset-password/update/$passwordToken` Reset password

**Query parameters**

- `$passwordToken` - will be received from email redirection 

**Success response**

<pre>
{
    "message": "New password has been set"
}
</pre>

<br>

### **Posts**
<hr>

GET `users/$userId/posts` Get user's posts

The email should be verified!

**Headers parameters**

- `Authorization: Bearer $authToken`

**Query parameters**

- `$userId` - user id

**Success response**

<pre>
[
    {
        "id": id,
        "user_id": id,
        "created_at": number,
        "updated_at": number,
        "body": string,
        "tags": [
            {
                "name": string
            }
                ]
    }
]
</pre>
<hr>

POST `posts` Create a new post

The email should be verified!

**Headers parameters**

- `Authorization: Bearer $authToken`

**Body parameters**

- `body` - required, min 1, max 500

**Success response**

<pre>
{
    "id": id,
    "user_id": id,
    "created_at": number,
    "updated_at": number,
    "body": string,
    "tags": [
        {
            "name": string
        }
            ]
}
</pre>
<hr>

PUT/PATCH `posts/$postId` Update existing post

The email should be verified!

**Headers parameters**

- `Authorization: Bearer $authToken`

**Query parameters**

- `$postId` - post id 

**Body parameters**

- `body` - required, min 1, max 500

**Success response**

<pre>
{
    "id": id,
    "user_id": id,
    "created_at": number,
    "updated_at": number,
    "body": string,
    "tags": [
        {
            "name": string
        }
            ]
}
</pre>
<hr>

DELETE `posts/$postId` Delete existing post

The email should be verified!

**Headers parameters**

- `Authorization: Bearer $authToken`

**Query parameters**

- `$postId` - post id 

**Success response**

`No content`

<br>

### **Users**
<hr>

GET `users` Get all users

The email should be verified!

**Headers parameters**

- `Authorization: Bearer $authToken`

**Success response**

<pre>
[
    {
        "id": number,
        "user_id": number,
        "first_name": string,
        "last_name": string,
        "avatar": string,
        "verified": bool,
        "created_at": number,
        "updated_at": number,
    }
]
</pre>

<br>

### **Profile**
<hr>

GET `users/profile` Get profile of a current user

**Headers parameters**

- `Authorization: Bearer $authToken`

**Success response**

<pre>
{
    "id": number,
    "user_id": number,
    "first_name": string,
    "last_name": string,
    "avatar": string,
    "verified": bool,
    "created_at": number,
    "updated_at": number,
}
</pre>
<hr>

POST `users/profile/avatar` Attach avatar to the current profile

The email should be verified!

**Headers parameters**

- `Authorization: Bearer $authToken`

**Body parameters**

- `avatar` - required, should be an image, max 1024 kb

**Success response**

<pre>
{
    "id": number,
    "user_id": number,
    "first_name": string,
    "last_name": string,
    "avatar": string,
    "verified": bool,
    "created_at": number,
    "updated_at": number,
}
</pre>
<hr>

DELETE `users/profile/avatar` Delete avatar from the current profile

The email should be verified!

**Headers parameters**

- `Authorization: Bearer $authToken`

**Success response**

<pre>
{
    "id": number,
    "user_id": number,
    "first_name": string,
    "last_name": string,
    "avatar": string,
    "verified": bool,
    "created_at": number,
    "updated_at": number,
}
</pre>
<hr>
