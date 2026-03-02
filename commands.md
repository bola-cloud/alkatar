composer require beyondcode/laravel-websockets:^1.14
php artisan vendor:publish --provider="BeyondCode\LaravelWebSockets\WebSocketsServiceProvider" --tag="migrations"


# 1. Create the service file
sudo nano /etc/systemd/system/laravel-websockets.service

# [Paste the content above into the editor, then press Ctrl+O, Enter, Ctrl+X]

[Unit]
Description=Laravel WebSocket Server
After=network.target

[Service]
Type=simple
User=root
Group=root
WorkingDirectory=/var/www/Hi-speed
ExecStart=/usr/bin/php artisan websockets:serve
Restart=always
RestartSec=5
StandardOutput=append:/var/www/Hi-speed/storage/logs/websockets.log
StandardError=append:/var/www/Hi-speed/storage/logs/websockets.log

[Install]
WantedBy=multi-user.target


# 2. Reload systemd to recognize the new service
sudo systemctl daemon-reload

# 3. Enable the service to start automatically on boot
sudo systemctl enable laravel-websockets

# 4. Start the service now
sudo systemctl start laravel-websockets

# 5. Check the status to ensure it's running
sudo systemctl status laravel-websockets