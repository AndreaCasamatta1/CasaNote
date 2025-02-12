from flask import Blueprint
from flask import request, render_template

app = Blueprint('base', __name__)

@app.route('/')
def root():
    return render_template('index.html')
