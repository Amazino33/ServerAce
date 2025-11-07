# ServerAce

**A modern gig marketplace built with Laravel 12**  
Like Fiverr, but faster, cleaner, and fully open-source.

![Laravel](https://img.shields.io/badge/Laravel-12-red)
![Livewire](https://img.shields.io/badge/Livewire-3-orange)
![Alpine.js](https://img.shields.io/badge/Alpine.js-3-green)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5-purple)
![License](https://img.shields.io/badge/License-MIT-blue)

## Features
- Post gigs with **Basic / Standard / Premium** packages
- Real-time **image preview** (Alpine.js)
- **Live validation** (Livewire)
- SEO-friendly URLs with **Sluggable**
- Dynamic category dropdown
- Full Git + GitHub workflow

## Live Demo
Coming soon: https://serverace.onrender.com

## Tech Stack
- Laravel 12 + Breeze
- Livewire + Alpine.js
- MySQL
- Bootstrap 5
- Vite

## Setup
```bash
git clone https://github.com/Amazino33/ServerAce.git
cd ServerAce
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
npm run dev
php artisan serve
