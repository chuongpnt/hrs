<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Laravel Flight Height Calculation Demo

This is a Laravel 12+PHP 8.3 demo project for calculating drone flight heights at multiple algorithm levels, designed to be run in a Docker environment.

## 🚀 Quick Start

### 1. Clone the Repository

```bash
git clone <your-repo-url>
```

### 2. Build and Run the Project with Docker Compose

```
docker compose up --build
```

### 3. Set Up Environment Configuration

```
cp .env.example .env
```


Edit the .env file as needed. For this demo, minimum changes:

```
DB_CONNECTION=sqlite
SESSION_DRIVER=file
```

### 4. Run Unit Tests

```
php artisan test
```

### 5. Export Output

Replace level1 and 2 with the group and input index you want to test.

Example for level 1:

```
http://localhost/flight-height/level1/2
```


### 6. How It Works

Flight data input is loaded from storage/input/levelX_Y.in files (e.g., level2_5.in).

Results are saved to storage/output/levelX_Y.out.

Level 1: Each flight consists of velocities (positive/negative). Drone can't go below ground.

Level 2: Each flight consists of accelerations (gravity = 10). Velocity is recalculated each tick.

The controller chooses logic based on the level parameter in the URL.

