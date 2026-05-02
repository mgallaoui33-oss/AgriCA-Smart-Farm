import google.generativeai as genai
import sys
import os
import mysql.connector # Nécessite: pip install mysql-connector-python
from PIL import Image

# 1. Configuration API
API_KEY = "ضع_هنا_مفتاح_API_الخاص_بك"
genai.configure(api_key=API_KEY)

# 2. Configuration Base de données
db_config = {
    "host": "localhost",
    "user": "root",
    "password": "",
    "database": "db_config"
}

def analyze_crop(image_path):
    """Analyse de l'image de la plante via Gemini"""
    try:
        if not os.path.exists(image_path):
            return "يا مالك، التصويرة ما وصلتش للمخ، ثبت في المسار!"

        model = genai.GenerativeModel('gemini-1.5-flash')
        prompt = """
        أنت خبير زراعي تونسي اسمك 'عسّاس السانية'.
        أمامك صورة لنبتة فول من سانية مالك. حلل الصورة وقولي حالة النبتة بالدارجة التونسية.
        """
        img = Image.open(image_path)
        response = model.generate_content([prompt, img])
        return response.text
    except Exception as e:
        return f"صار مشكل في التحليل: {str(e)}"

def check_humidity_and_pump(user_phone):
    """Vérifie l'humidité et active le sondage si < 29"""
    try:
        conn = mysql.connector.connect(**db_config)
        cursor = conn.cursor()

        # Calcul de la moyenne de l'humidité sur les 5 derniers enregistrements
        query_avg = "SELECT AVG(humidity_level) FROM (SELECT humidity_level FROM sensor_logs WHERE user_phone = %s ORDER BY log_time DESC LIMIT 5) as sub"
        cursor.execute(query_avg, (user_phone,))
        moyenne = cursor.fetchone()[0]

        if moyenne is not None and moyenne < 29:
            # Si l'humidité est basse, on active la pompe (ON)
            insert_query = "INSERT INTO sensor_logs (user_phone, pump_status, humidity_level) VALUES (%s, 'ON', %s)"
            cursor.execute(insert_query, (user_phone, moyenne))
            conn.commit()
            print(f"تنبيه: الرطوبة طاحت ({moyenne}%). السنطاج خدم أوتوماتيكيا.")
        else:
            print(f"الرطوبة مريغلة: {moyenne}%")

        cursor.close()
        conn.close()
    except Exception as e:
        print(f"خطأ في قاعدة البيانات: {str(e)}")

if __name__ == "__main__":
    # Si un chemin d'image est passé : Analyse IA
    if len(sys.argv) > 1:
        path = sys.argv[1]
        print(analyze_crop(path))
    
    # Exemple d'appel pour vérifier l'humidité (à automatiser via un cron job)
    # check_humidity_and_pump('94858266')
