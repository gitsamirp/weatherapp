Weather App
========================

## Intro
Retrieve current weather forecast using OpenWeatherMap API

### Requirements
Docker or PHP 7.4

### Instructions
Docker
1. Clone or download git repository to local folder
2. add OpenWeatherMap api key to .env at OPENWEATHERMAP_API_KEY
3. using Terminal traverse to code folder and run: 
   **docker-compose run --build**
4. Once docker container built run:
   **docker exec -it weather_app bash**
5. you should be in the container at location /var/www/html
6. Run **composer install**
7. once completed visit "localhost:8080"
