import os
import shutil
import re
import win32com.client

desktop_folder = "C:/Users/Alexa/Desktop"
inicio_folder = os.path.join(desktop_folder, "Inicio")
destination_folders = {
    "Imágenes": [".jpg", ".jpeg", ".png", ".gif", ".bmp"],
    "Documentos": [".pdf", ".docx", ".txt", ".xlsx", ".pptx"],
    "Videos": [".mp4", ".avi", ".mov", ".mkv"],
    "Música": [".mp3", ".wav", ".flac"],
    "Programas": [".exe", ".msi", ".zip", ".rar"], 
    "Scripts": [".bat", ".sh"], 
    "Accesos Directos": [".lnk"],
    "Juegos": [], 
}

if not os.path.exists(inicio_folder):
    os.makedirs(inicio_folder)

for folder in destination_folders:
    folder_path = os.path.join(inicio_folder, folder)
    if not os.path.exists(folder_path):
        os.makedirs(folder_path)

def is_steam_link(file_path):
    if file_path.endswith(".lnk"):
        shell = win32com.client.Dispatch("WScript.Shell")
        shortcut = shell.CreateShortCut(file_path)
        target = shortcut.Targetpath
        steam_pattern = r"steam://rungameid/\d+"
        if re.match(steam_pattern, target):  
            return True
    elif file_path.endswith(".url"):
        with open(file_path, 'r') as url_file:
            content = url_file.read()
            steam_pattern = r"steam://rungameid/\d+"
            if re.search(steam_pattern, content): 
                return True
    return False

def organize_desktop():
    files = [f for f in os.listdir(desktop_folder) if os.path.isfile(os.path.join(desktop_folder, f))]

    files.sort()

    for file in files:
        file_path = os.path.join(desktop_folder, file)
        
        if is_steam_link(file_path):
            dest_path = os.path.join(inicio_folder, "Juegos", file)
            shutil.move(file_path, dest_path)
            print(f"Movido: {file} -> Juegos")
        else:
            file_ext = os.path.splitext(file)[1].lower()
            moved = False
            
            for category, extensions in destination_folders.items():
                if file_ext in extensions:
                    dest_path = os.path.join(inicio_folder, category, file)
                    shutil.move(file_path, dest_path)
                    print(f"Movido: {file} -> {category}")
                    moved = True
                    break
            
            if not moved:
                print(f"No se movió el archivo: {file}")

if __name__ == "__main__":
    organize_desktop()
