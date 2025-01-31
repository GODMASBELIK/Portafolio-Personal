from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.common.keys import Keys
import time

service = Service("./chromedriver.exe")
driver = webdriver.Chrome(service=service)

driver.get("https://www.google.com")

time.sleep(3)

Okey = driver.find_element(By.ID, "APjFqb")

Okey.clear()
time.sleep(2)
Okey.send_keys("Good day" + Keys.ENTER)

time.sleep(3)