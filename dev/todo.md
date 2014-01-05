todo:


add admin login

add image upload to product

add shopping cart to session

add image upload to products

remove admin model if it's not being used

when user logs in add cart items to user


clear password reset after resetting

update error view

add expiration to password reset

add logout to menu/detect if user is logged in

fix view in update password

errors in update password

confirm password isn't reset until email link is clicked

add front end validation

add https for secure pages (login etc.)

require min css an js

do not reset password until user clicks confirmation link in email.

error message display on login/registration pages

change menu base on whether user is logged in

after registration, post success message

after login direct back to page the user was on previously

create table for admin config details (i.e. site name, email copy?)

make taxonomy model get_categories_by_names more efficient

after adding/upadting cat or tax, redirect to main tax page

implement move category feature

have tag delete/rename just pass id instead of type


create login for admin

add tag attributes to admin/product

set credentials for admin



set login validation in admin controller constructor

check for rounding errors in product model

verify indexes on all tables including:
	verifCode (first certain number of characters)
	userEmail

set password fields to fixed length (not var char)

set error modes for pdo in base model
	setting policy to throw errors for DB, not check for them in each of the models

move db_interaction to model

sign_up

view private account stuff on login

verify email

default 404

validate

maker sure there is an index on:
	User email

verify email

salt and hash password

generate unique salt for each email?

validate input (in class)

validation

move db_helper to lib

use db class in add product

document db handler


***LATER***

convert view variables into View::$data variables


