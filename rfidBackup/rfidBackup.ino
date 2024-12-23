#include <SPI.h>
#include <MFRC522.h>
#include <ESP8266HTTPClient.h>
#include <ESP8266WiFi.h>


// Network SSID
const char* ssid = "TSABIT";
const char* password = "bismillah2019";

const char* host = "192.168.1.3";

//Variable Led & Button
#define LED_PIN 15 // D8
#define BTN_PIN 5  // D1

//Variable Untuk RFID
#define SDA_PIN 2 //D4
#define RST_PIN 0 //D3



MFRC522 mfrc522(SDA_PIN, RST_PIN);

void setup() {
  Serial.begin(9600);



  // Set WiFi
  WiFi.hostname("NodeMCU");
  WiFi.begin(ssid, password);

  // Cek koneksi
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");

  }

  Serial.println("WiFi Connected");
  Serial.println("IP ADDRESS : ");
  Serial.println(WiFi.localIP());

  pinMode(LED_PIN, OUTPUT);
  pinMode(BTN_PIN, INPUT_PULLUP); // Menggunakan pull-up internal

  SPI.begin();
  mfrc522.PCD_Init();
  Serial.print("Tempelkan Kartu RFID Anda");
  Serial.println();
}

void loop() {
  // Baca status tombol
  if (digitalRead(BTN_PIN) == LOW) { // LOW karena pull-up digunakan
    digitalWrite(LED_PIN, HIGH);

    while (digitalRead(BTN_PIN) == LOW); // Tunggu tombol dilepas

    // Ubah mode absensi di web
    String LinkUbah = "http://192.168.1.3/absensi/ubahmode.php";
    HTTPClient http;
    WiFiClient client; // Objek WiFiClient untuk HTTPClient

    http.begin(client, LinkUbah); // Gunakan objek client untuk HTTP request
    int httpCode = http.GET();

    if (httpCode > 0) {
      // Jika response HTTP berhasil
      String payload = http.getString();
      Serial.println(payload);
    } else {
      // Jika ada error pada HTTP request
      Serial.printf("HTTP GET Failed, error: %s\n", http.errorToString(httpCode).c_str());
    }

    http.end();
  }

  // Matikan LED
  digitalWrite(LED_PIN, LOW);

  if(! mfrc522.PICC_IsNewCardPresent())
    return;

  if (! mfrc522.PICC_ReadCardSerial())
    return;

  String IDTAG = "";
  for(byte i=0; i < mfrc522.uid.size; i++)
  {
    IDTAG += mfrc522.uid.uidByte[i];
  }

  //Nyalakan LED
  digitalWrite(LED_PIN, HIGH);

  //kirim no kartu RFID ke Tabel tmprfid
  WiFiClient client;
  const int httpPort = 80;
  if(!client.connect(host, httpPort))
  {
    Serial.println("Connection Failed");
    return;
  }

  String Link;
  HTTPClient http;
  Link = "http://192.168.1.3/absensi/kirimkartu.php?nokartu=" + IDTAG;
  http.begin(client, Link);

  int httpCode = http.GET();
  String payload = http.getString();
  Serial.println(payload);
  http.end();

  delay(2000);


  
}
