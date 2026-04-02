from flask import Flask, request
from google.cloud import datastore
import datetime
import json
import os

app = Flask(__name__)
client = datastore.Client()

@app.route('/api/audit/', methods=['POST'])
def audit_logger():
    """HTTP endpoint to log database changes to Datastore."""
    request_json = request.get_json(silent=True)
    
    if not request_json:
        return ('No data provided', 400)

    # Create a new entity of kind 'AuditLog'
    key = client.key('AuditLog')
    entity = datastore.Entity(key=key)
    
    entity.update({
        'timestamp': datetime.datetime.utcnow(),
        'action': request_json.get('action', 'UNKNOWN'),
        'item_id': request_json.get('item_id', 'UNKNOWN'),
        'details': json.dumps(request_json.get('details', {})),
        'user': request_json.get('user', 'system')
    })
    
    client.put(entity)
    return ('Audit log saved successfully', 200)

if __name__ == '__main__':
    # This is used for local testing. Gunicorn will run the app in production on App Engine.
    port = int(os.environ.get('PORT', 8080))
    app.run(host='0.0.0.0', port=port, debug=True)