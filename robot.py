from flask import Flask, render_template_string
from gpiozero import Motor, PWMOutputDevice, DistanceSensor
import time

# === Motor A (Left) ===
ENA = PWMOutputDevice(25)
motorA = Motor(forward=24, backward=23)

# === Motor B (Right) ===
ENB = PWMOutputDevice(5)
motorB = Motor(forward=27, backward=22)

# === Ultrasonic Sensor ===
ultra = DistanceSensor(echo=6, trigger=17, max_distance=2, threshold_distance=0.3)

# Start with medium speed
ENA.value = 0.5
ENB.value = 0.5

# === Motor Control ===
def forward():
    motorA.forward()
    motorB.forward()

def backward():
    motorA.backward()
    motorB.backward()

def left():
    motorA.backward()
    motorB.forward()

def right():
    motorA.forward()
    motorB.backward()

def stop():
    motorA.stop()
    motorB.stop()

def low_speed():
    ENA.value = 0.25
    ENB.value = 0.25

def medium_speed():
    ENA.value = 0.5
    ENB.value = 0.5

def high_speed():
    ENA.value = 0.75
    ENB.value = 0.75

# === Ultrasonic ===
def get_distance():
    return round(ultra.distance * 100, 1)  # convert to cm

# === Flask Web App ===
app = Flask(__name__)

html = """
<h1>River Cleaning Robot</h1>
<p>Distance: {{ distance }} cm</p>
<form action="/forward"><button>Forward</button></form>
<form action="/backward"><button>Backward</button></form>
<form action="/left"><button>Left</button></form>
<form action="/right"><button>Right</button></form>
<form action="/stop"><button>Stop</button></form>
<form action="/low"><button>Low Speed</button></form>
<form action="/medium"><button>Medium Speed</button></form>
<form action="/high"><button>High Speed</button></form>
"""

@app.route("/")
def home():
    dist = get_distance()
    return render_template_string(html, distance=dist)

@app.route("/forward")
def move_forward():
    forward()
    return home()

@app.route("/backward")
def move_backward():
    backward()
    return home()

@app.route("/left")
def move_left():
    left()
    return home()

@app.route("/right")
def move_right():
    right()
    return home()

@app.route("/stop")
def move_stop():
    stop()
    return home()

@app.route("/low")
def speed_low():
    low_speed()
    return home()

@app.route("/medium")
def speed_medium():
    medium_speed()
    return home()

@app.route("/high")
def speed_high():
    high_speed()
    return home()

if __name__ == "__main__":
    try:
        app.run(host="0.0.0.0", port=5000)
    finally:
        stop()
