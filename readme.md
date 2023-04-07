# Betfier Login Checker

#### This is a project that aims to test account logins on the Betfier website.

### How to use:
- cd src/<br>
- php -S localhost:8000
- Make POST request to route: localhost:8000/checker.php

### Route params:
- list
- list.email
- list.password

### Example body:
![img.png](img.png)

You can test as many accounts as you want, just send it in an array.

<strong> PS: Submit as x-www-form-urlencoded</strong>