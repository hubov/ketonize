# ketonize v0.1
This project is a simple framework of a diet planning app. It has been created on Laravel Framework.

### Description
This framework allows to build an app with a custom set of:
- diets
- recipes
- ingredients

It automatically creates diet plans for users based on their calorie needs and dietary purpose.

#### User
Based on the user's survey: energy and nutrient requirements are calculated. The user decides about the number of meals per day and defines the type of diet (eg. vegan or keto).

#### Meal plan
A meal plan is prepared automatically based on the results of the user's needs.

#### Shopping lists
The user can generate shopping lists for meal preparation for a selected period of time with one click. He can freely change the quantities of ingredients on the list and delete them.

### Getting started
#### Dependencies
- Laravel 9.3
- PHP 8.0
- Nginx
- Composer

#### Installing
You can download this repository.

Do not forget to set a database.

Set up your .env file.

#### First steps
The app requires content to be run. Its database needs to be populated with ingredients and recipes to create diet plans. These can be added manually using the following links:
- `/recipes`
- `/ingredients`

### License
This source code is licensed according to MIT License.
