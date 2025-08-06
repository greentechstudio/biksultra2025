#!/bin/bash

# ASR Cronjob Monitoring Script
# File: cronjob_monitor.sh
# Purpose: Monitor and log cronjob execution for Amazing Sultra Run

# Configuration
LOG_FILE="/home/[USERNAME]/asr_cronjob.log"
ERROR_LOG="/home/[USERNAME]/asr_cronjob_error.log"
PROJECT_PATH="/home/[USERNAME]/public_html"

# Create log directory if not exists
mkdir -p "$(dirname "$LOG_FILE")"
mkdir -p "$(dirname "$ERROR_LOG")"

# Function to log with timestamp
log_message() {
    echo "$(date '+%Y-%m-%d %H:%M:%S') - $1" >> "$LOG_FILE"
}

# Function to log errors
log_error() {
    echo "$(date '+%Y-%m-%d %H:%M:%S') - ERROR: $1" >> "$ERROR_LOG"
    echo "$(date '+%Y-%m-%d %H:%M:%S') - ERROR: $1" >> "$LOG_FILE"
}

# Start execution
log_message "=== Starting ASR Cleanup Process ==="

# Check if project directory exists
if [ ! -d "$PROJECT_PATH" ]; then
    log_error "Project directory not found: $PROJECT_PATH"
    exit 1
fi

# Navigate to project directory
cd "$PROJECT_PATH" || {
    log_error "Failed to navigate to project directory"
    exit 1
}

# Check if .env file exists
if [ ! -f ".env" ]; then
    log_error ".env file not found in project directory"
    exit 1
fi

# Check if artisan file exists and is executable
if [ ! -f "artisan" ]; then
    log_error "artisan file not found in project directory"
    exit 1
fi

if [ ! -x "artisan" ]; then
    log_message "Setting artisan file as executable"
    chmod +x artisan
fi

# Execute the cleanup command
log_message "Executing: php artisan registrations:process-unpaid"

# Run the command and capture output
OUTPUT=$(php artisan registrations:process-unpaid 2>&1)
EXIT_CODE=$?

# Log the result
if [ $EXIT_CODE -eq 0 ]; then
    log_message "Cleanup command executed successfully"
    log_message "Output: $OUTPUT"
else
    log_error "Cleanup command failed with exit code: $EXIT_CODE"
    log_error "Output: $OUTPUT"
fi

# Log completion
log_message "=== ASR Cleanup Process Completed ==="
log_message ""

# Optional: Clean old logs (keep last 30 days)
find "$(dirname "$LOG_FILE")" -name "asr_cronjob*.log" -mtime +30 -delete 2>/dev/null

exit $EXIT_CODE
