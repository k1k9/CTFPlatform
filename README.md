# CTF Platform
Simple CTF platform for hosting CTFs. This is a work in progress.

## Requirements
- Docker
- PHP >= 8.1
- MySQL

## Preinstallation
1. Create a database
2. Create a user with full access to the database
3. Create /src/config.json
```
{
    "dbHost": "db",
    "dbName": "dbname",
    "dbUser": "dbuser",
    "dbPass": "dbpassword",
    "siteName": "",
    "flagPrefix": "",
    "restrict": "0",    // TODO: Handling this
    "devmode": "0",     // Enables error logs
    "startDate": false, // TODO: Handling this
    "startTime": false  // TODO: Handling this
}
```

## Installation
1. Clone the repository
```bash
git clone https://github.com/k1k9/CTFPlatform.git
```
2. Install docker
3. Run the container
```bash
docker compose up --build -d
```
4. Visit http://localhost:8003/install.php
5. Remove /src/public/install.php
6. Visit http://localhost:8003

## Usage
### Pointing system
```php
($points > 0 && $points <= 30): $level = 'easy';
($points > 30 && $points <= 60): $level = 'medium';
($points > 60 && $points <= 100): $level = 'hard';
```

### Permissions
- 0: Forbidden
- 1: Casual user
- 2: Admin
- 3: CTF purpose

### Additional
When adding tasks, they are saved into src/tasks.json.