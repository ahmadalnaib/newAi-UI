# 💸 CheapUI.com

**CheapUI** is an AI-powered tool that helps developers instantly generate clean, reusable UI components. Whether you're building with Tailwind, Bootstrap, or plain HTML, CheapUI saves you time and energy by turning prompts into production-ready code.

> 🧪 Try it live: [https://cheapui.com](https://cheapui.com)

---

## ✨ Features

- ⚡ Generate UI components using AI (e.g., buttons, modals, tables)
- 🎨 Supports Tailwind CSS, Bootstrap, and custom styles
- 💬 Natural-language prompt input
- 🔍 Preview generated components instantly
- 📁 Export or copy HTML/JSX code with one click
- 🧠 Smart suggestions and improvements (ongoing)
- 🛠 No sign-up required to test it

---

## 🧰 Tech Stack

### Frontend

- Vue 3 
- Tailwind CSS


### Backend

- Laravel 10+ (API)
- OpenAI API and claude ai (AI generation)
- PostgreSQL 

### Others

- Vite
- Inertia.js 
- Stripe 
- DigitalOcean (deployment)

---

## 🚀 Getting Started (Local Dev)

### 1. Clone the Repositories


git clone https://github.com/ahmadalnaib/newAi-UI
cd  newAi-UI
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
