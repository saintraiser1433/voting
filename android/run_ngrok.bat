@echo off
set "NGROK_URL=unbluffed-figgy-maranda.ngrok-free.dev"
set "PORT=80"
set "TOKEN=36NQvLIobzuly5nWSskmHlegDOG_6t7zbkKm7WQfkaJ1K8abS"

echo Adding Ngrok authtoken...
ngrok config add-authtoken %TOKEN%

if %errorlevel%==0 (
    echo Authtoken added successfully!
) else (
    echo Failed to add authtoken. Please check your token or Ngrok installation.
    pause
    exit /b
)

echo Starting Ngrok tunnel...
ngrok http --url=%NGROK_URL% %PORT%

pause