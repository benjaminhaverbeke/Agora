## Agora

# Project Description

Agora is an online voting platform based on majority rule. Groups can gather in assemblies, invite other users, and vote on topics through proposals. A campaign phase allows users to discuss proposals through the chat feature. A voting phase enables users to vote for their preferred proposal. Finally, the last phase presents the voting results for each topic.

# Online Version

An online version of the site is available at: 

Test credentials:

Email: test@test.fr

Password: Test_1234

# Prerequisites

Ensure the following software are installed in your system:

Wamp
Composer
Node.js

# Installation

Cloning the GitHub repository:

Copy the git clone code

```
https://github.com/benjaminhaverbeke/Agora.git
```

Using Wamp

Clone the GitHub repository into the www directory of your Wamp installation:

Make sure Wamp is running and the Apache server is started.

# Configuring Environment Variables

Symfony .env need to link your database 

```
DATABASE_URL="mysql://root:@127.0.0.1:3306/agora?serverVersion=8.3.0&charset=utf8mb4"
```

# Generate Schema Database

The database schema is generated using the command:

```
php bin/doctrine orm:schema-tool:create
```

# Installing PHP Dependencies

Go to the project's root directory and install PHP dependencies via Composer:
```
composer install
```
update dependencies
```
composer update
```
# Installing JavaScript Dependencies

Go to the project's root directory and install JavaScript dependencies via yarn:

```
yarn install
```

# Running the Project

Ensure that Wamp is running and the Apache server is started. Open your web browser and navigate to
http://localhost/your-project-directory.

# Troubleshooting
Wamp Issues: Ensure that no other programs are using the same port as Apache (usually port 80). Composer Issues: Make sure you have the latest version of Composer installed. Node.js Issues: Ensure that Node.js and yarn are correctly installed and their versions are up to date.

# License
This project is licensed under the MIT License.





