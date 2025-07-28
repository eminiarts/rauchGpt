# RauchGPT - Educational Chatbot Interface

This repo was created by Enes Emini and Lirjona Suka, as a simple chatbot interface for students to chat with a specific persona. This persona receives specific instructions on a topic in order to answer related questions by the students. 

## Chatbot Personalities

The application features different chatbot personalities, each designed for educational purposes:

- **Mia** - A 19-year-old from Ulm who shares her experiences with vaping/e-cigarettes. She provides honest insights about her journey, helping students understand the realities of nicotine addiction. See her full instructions in [`app/Http/Controllers/ChatStreamController.php`](https://github.com/eminiarts/rauchGpt/blob/main/app/Http/Controllers/ChatStreamController.php).

- **Dr. Martin** - An experienced cardiovascular disease specialist who explains medical topics in an age-appropriate manner for 7th-grade students. She focuses on educating about arteriosclerosis, heart attacks, strokes, and prevention methods. See her full instructions in [`app/Http/Controllers/DrMartinStreamController.php`](https://github.com/eminiarts/rauchGpt/blob/main/app/Http/Controllers/DrMartinStreamController.php).

## Key Features

- No database required - this app uses API keys for OpenAI integration
- Real-time streaming chat responses
- Educational focus with specialized personas
- Built with Laravel, Livewire, and Tailwind CSS

## Prerequisites

- PHP >= 8.2
- Composer
- Node.js & NPM
- OpenAI API Key

## Installation

1. Clone the repository:
```bash
git clone https://github.com/eminiarts/rauchGpt.git
cd rauchGpt
```

2. Install PHP dependencies:
```bash
composer install
```

3. Install Node dependencies:
```bash
npm install
```

4. Create environment file:
```bash
cp .env.example .env
```

5. Generate application key:
```bash
php artisan key:generate
```

6. Configure your OpenAI API key in `.env`:
```
OPENAI_API_KEY=your-api-key-here
```

## Usage

### Development

Run the development server with all services:
```bash
composer dev
```

This command starts:
- Laravel development server
- Queue listener
- Log viewer (Pail)
- Vite development server

Alternatively, run services individually:

```bash
# Start Laravel server
php artisan serve

# In another terminal, start Vite
npm run dev
```

### Production Build

Build assets for production:
```bash
npm run build
```

## Project Structure

- `/app/Http/Controllers/` - Contains the chat controllers for different personas
- `/resources/views/` - Blade templates and Livewire components
- `/routes/` - Application routes
- `/public/` - Public assets

## Configuration

The application uses environment variables for configuration. Key settings:

- `OPENAI_API_KEY` - Your OpenAI API key (required)
- `APP_URL` - Application URL
- `APP_ENV` - Environment (local/production)

## Testing

Run the test suite:
```bash
composer test
```

## License

This project is licensed under the MIT License.