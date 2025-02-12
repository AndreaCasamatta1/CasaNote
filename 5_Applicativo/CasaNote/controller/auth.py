from flask import Blueprint
from flask import request, render_template

app = Blueprint('auth', __name__)


@app.route('/profile')
def profile(username):
    return render_template('base/index.html')

@app.route('/login/')
def login():
    return render_template('base/index.html')

@app.route('/logout/')
def logout():
    return render_template('base/index.html')

@app.route('/register/')
def register():
    return render_template('base/index.html')
