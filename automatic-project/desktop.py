import os
import shutil
desktop_folder = "C:/Users/Alexa/Desktop/Inicio"

if not os.path.exists(desktop_folder):
    os.makedirs(desktop_folder)

icon_path = "%SystemRoot%\\System32\\SHELL32.dll"
icon_index = 7  

desktop_ini_path = os.path.join(desktop_folder, 'desktop.ini')

desktop_ini_content = f"""
[.ShellClassInfo]
IconResource={icon_path},{icon_index}
[ViewState]
Mode=
Vid=
FolderType=Generic
"""

with open(desktop_ini_path, 'w') as file:
    file.write(desktop_ini_content)

os.system(f'attrib +h "{desktop_ini_path}"')

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

for folder in destination_folders:
    folder_path = os.path.join(desktop_folder, folder)
    if not os.path.exists(folder_path):
        os.makedirs(folder_path)

def move_files():
    files = [f for f in os.listdir(desktop_folder) if os.path.isfile(os.path.join(desktop_folder, f))]

    for file in files:
        file_path = os.path.join(desktop_folder, file)
        file_ext = os.path.splitext(file)[1].lower()
        moved = False
        
        for category, extensions in destination_folders.items():
            if file_ext in extensions:
                dest_path = os.path.join(desktop_folder, category, file)
                shutil.move(file_path, dest_path)
                print(f"Movido: {file} -> {category}")
                moved = True
                break

        if not moved:
            print(f"No se movió el archivo: {file}")

move_files()

print("Carpeta 'Inicio' organizada y con icono personalizado.")
