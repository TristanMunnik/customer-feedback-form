# customer-feedback-form
A simple PHP Feedback form that has JS client side validation and PHP server side validation

# Features
- Clientside validation through JavaScript
- Serverside Validation through PHP
- Error handling
- Form data is persistance if there is validation errors

# Technologies Used
- HTML5
- JavaScipt
- PHP

# Validation Rules
- Name: required field
- Email: Required and be a valid format
- comment: required and at least 20 characters

# How it works
- User fills in the form
- JavaScript validated the input before submission
- If the validation passed, form will submit to PHP
- PHP will perform server-side validation
- On error, form redisplays with error messages and will preserve the user input

# Setup
- Place `feedback.php` in your web server directory
- Navigate to the file in your brower
- Test the validation by submitting data
