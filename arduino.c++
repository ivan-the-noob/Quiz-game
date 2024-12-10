#include <Wire.h>
#include <LiquidCrystal_I2C.h>
#include <Keypad.h>
#include <WiFi.h>
#include <HTTPClient.h>

// LCD Setup
LiquidCrystal_I2C lcd(0x27, 16, 2); // Adjust address if necessary

// WiFi Credentials
const char* ssid = "Your_SSID"; // Replace with your Wi-Fi SSID
const char* password = "Your_PASSWORD"; // Replace with your Wi-Fi password
const char* serverURL = "http://192.168.1.100/get-question.php?level=easy"; // Replace with your server's IP or domain

// Keypad Setup
const byte ROWS = 4;
const byte COLS = 4;
char keys[ROWS][COLS] = {
  {'1', '2', '3', 'A'},
  {'4', '5', '6', 'B'},
  {'7', '8', '9', 'C'},
  {'*', '0', '#', 'D'}
};

byte rowPins[ROWS] = {19, 18, 5, 17};
byte colPins[COLS] = {16, 4, 0, 2};

Keypad keypad = Keypad(makeKeymap(keys), rowPins, colPins, ROWS, COLS);

// Button Pins
const int button1Pin = 34;  // Player 1 button
const int button2Pin = 35;  // Player 2 button
const int button3Pin = 32;  // Player 3 button

// Player Variables
int activePlayer = 0;  // 0 = no player, 1 = Player 1, 2 = Player 2, 3 = Player 3
String question = "";
String answer = "";

void setup() {
  // Initialize Serial, LCD, and WiFi
  Serial.begin(115200);
  lcd.begin();
  lcd.backlight();
  lcd.print("Connecting...");
  
  // Initialize Button Pins
  pinMode(button1Pin, INPUT_PULLUP);
  pinMode(button2Pin, INPUT_PULLUP);
  pinMode(button3Pin, INPUT_PULLUP);

  // Connect to Wi-Fi
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(1000);
    Serial.println("Connecting to WiFi...");
    lcd.setCursor(0, 1);
    lcd.print(".");
  }

  lcd.clear();
  lcd.print("WiFi Connected!");
  delay(1000);
}

void loop() {
  // Check Button Presses for Active Player
  checkPlayerSelection();

  if (activePlayer > 0) {
    // Fetch a question from the server
    fetchQuestion();

    // Display the question on the LCD
    lcd.clear();
    lcd.print("Q:");
    lcd.setCursor(0, 1);
    lcd.print(question);

    // Accept user input from the keypad
    String userAnswer = "";
    while (true) {
      char key = keypad.getKey();
      if (key) {
        if (key == '#') { // User submits their answer
          checkAnswer(userAnswer);
          break;
        } else if (key == '*') { // Clear the input
          userAnswer = "";
          lcd.setCursor(0, 1);
          lcd.print("                "); // Clear the second line
        } else { // Append the input
          userAnswer += key;
          lcd.setCursor(0, 1);
          lcd.print(userAnswer);
        }
      }
    }

    delay(5000); // Wait 5 seconds before fetching the next question
  }
}

// Function to check which player has pressed a button
void checkPlayerSelection() {
  if (digitalRead(button1Pin) == LOW) { // Player 1 selected
    activePlayer = 1;
    lcd.clear();
    lcd.print("Player 1's turn");
    delay(1000); // Short delay to avoid multiple presses
  }
  else if (digitalRead(button2Pin) == LOW) { // Player 2 selected
    activePlayer = 2;
    lcd.clear();
    lcd.print("Player 2's turn");
    delay(1000);
  }
  else if (digitalRead(button3Pin) == LOW) { // Player 3 selected
    activePlayer = 3;
    lcd.clear();
    lcd.print("Player 3's turn");
    delay(1000);
  }
}

void fetchQuestion() {
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;
    http.begin(serverURL);
    int httpCode = http.GET();

    if (httpCode > 0) {
      String payload = http.getString();
      Serial.println(payload);

      // Parse question and answer from plain text
      int delimiterIndex = payload.indexOf(',');
      if (delimiterIndex != -1) {
        question = payload.substring(0, delimiterIndex);
        answer = payload.substring(delimiterIndex + 1);
      } else {
        lcd.clear();
        lcd.print("Parse Error");
      }
    } else {
      lcd.clear();
      lcd.print("HTTP Error");
      Serial.println("HTTP Error: " + String(httpCode));
    }

    http.end();
  } else {
    lcd.clear();
    lcd.print("WiFi Error");
    Serial.println("WiFi not connected.");
  }
}

void checkAnswer(String userAnswer) {
  lcd.clear();
  if (userAnswer == answer) {
    lcd.print("Correct!");
    tone(25, 1000, 500); // Play a tone if correct (optional, GPIO 25)
  } else {
    lcd.print("Wrong!");
    tone(25, 200, 500);  // Play a different tone if wrong (optional, GPIO 25)
  }
}
