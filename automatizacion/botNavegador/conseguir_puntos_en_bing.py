from selenium import webdriver
from selenium.webdriver.chrome.options import Options
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.chrome.service import Service
import time
import keyboard

def search_on_bing_mobile():
    count = 1
    mobile_emulation = {
        "deviceName": "Nexus 5"  
    }

    chrome_options = Options()
    chrome_options.add_experimental_option("mobileEmulation", mobile_emulation)

    driver_path = './chromedriver.exe'

    service = Service(driver_path) 
    driver = webdriver.Chrome(service=service, options=chrome_options)

    driver.get("https://www.bing.com")
    time.sleep(3)

    search_box = driver.find_element(By.NAME, "q")  

    print("Presiona 'F5' para iniciar o detener el programa.\n")

    running = False 

    while True:
        if keyboard.is_pressed("f5"):
            running = not running 
            print("\nPrograma " + ("iniciado" if running else "detenido") + ".")
            time.sleep(0.5)  

        if running:
            text = f"How to be {count}"
            
            try:
                search_box.clear() 
            except:
                search_box = driver.find_element(By.NAME, "q") 

            search_box.send_keys(text) 
            search_box.send_keys(Keys.RETURN)  
            count += 1
            time.sleep(6)  

            try:
                search_box = driver.find_element(By.NAME, "q")  
            except:
                pass  

            search_box.clear()  
            time.sleep(2) 
            search_box.send_keys(f"How to be {count}")
            search_box.send_keys(Keys.RETURN) 
            count += 1

search_on_bing_mobile()
