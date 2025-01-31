# import os
# import time
# import shutil

# # Rutas
# DOWNLOAD_FOLDER = "C:/Users/Alexa/Downloads"
# DESTINATION_FOLDERS = {
#     "Imágenes": [".jpg", ".jpeg", ".png", ".gif", ".bmp"],
#     "Documentos": [".pdf", ".docx", ".txt", ".xlsx", ".pptx"],
#     "Videos": [".mp4", ".avi", ".mov"],
#     "Música": [".mp3", ".wav", ".flac"],
#     "Programas": [".exe", ".msi", ".zip", ".rar"]
# }

# for folder in DESTINATION_FOLDERS:
#     folder_path = os.path.join(DOWNLOAD_FOLDER, folder)
#     if not os.path.exists(folder_path):
#         os.makedirs(folder_path)

# def organize_downloads():
#     for file in os.listdir(DOWNLOAD_FOLDER):
#         file_path = os.path.join(DOWNLOAD_FOLDER, file)
#         if os.path.isfile(file_path): 
#             file_ext = os.path.splitext(file)[1].lower()
#             for category, extensions in DESTINATION_FOLDERS.items():
#                 if file_ext in extensions:
#                     dest_path = os.path.join(DOWNLOAD_FOLDER, category, file)
#                     shutil.move(file_path, dest_path)
#                     print(f"Movido: {file} -> {category}")

# def monitor_downloads():
#     print("Monitoreando carpeta de descargas...")
#     before = set(os.listdir(DOWNLOAD_FOLDER))
#     while True:
#         time.sleep(5)  
#         after = set(os.listdir(DOWNLOAD_FOLDER))
#         new_files = after - before  
#         if new_files:
#             print("Nuevos archivos detectados:", new_files)
#             organize_downloads()
#         before = after

# if __name__ == "__main__":
#     monitor_downloads()
