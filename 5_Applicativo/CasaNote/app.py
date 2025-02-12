from flask import Flask, url_for, request, render_template
from controller.base import app as base_app
from controller.auth import app as auth_app


app = Flask(__name__)

app.register_blueprint(base_app)
app.register_blueprint(auth_app)


if __name__ == '__main__':
    app.run(debug=True)