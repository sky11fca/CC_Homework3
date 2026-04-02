# Use the official lightweight Python image.
FROM python:3.9-slim

# Allow statements and log messages to be sent straight to the logs
# without being buffered.
ENV PYTHONUNBUFFERED True

# Set the working directory in the container.
WORKDIR /app

# Copy over the requirements file and install dependencies.
COPY requirements.txt .
RUN pip install -r requirements.txt

# Copy the rest of the application code.
COPY . .

# Run the web service on container startup. Gunicorn is a production-ready
# WSGI server. The 'main:app' refers to the Flask 'app' instance in main.py.
CMD exec gunicorn --bind :$PORT --workers 1 --threads 8 --timeout 0 main:app