import google.generativeai as genai
import sys
import os
from PIL import Image

# 1. إعداد المفتاح (تحصل عليه مجاناً من Google AI Studio)
API_KEY = "ضع_هنا_مفتاح_API_الخاص_بك"
genai.configure(api_key=API_KEY)

def analyze_crop(image_path):
    try:
        # التأكد من وجود الصورة
        if not os.path.exists(image_path):
            return "يا مالك، التصويرة ما وصلتش للمخ، ثبت في المسار!"

        # إعداد الموديل (Flash سريع جداً ومناسب للصور)
        model = genai.GenerativeModel('gemini-1.5-flash')

        # الـ Prompt (الأمر) بالتونسي لبرمجة شخصية الذكاء الاصطناعي
        prompt = """
        أنت خبير زراعي تونسي اسمك 'عسّاس السانية'.
        أمامك صورة لنبتة (غالباً فول) من سانية مالك.
        حلل الصورة بدقة وقولي:
        1. شنية حالة النبتة (لاباس عليها، وإلا مريضة).
        2. إذا مريضة، قولي شنوة اسم المرض بالدارجة التونسية وكيفاش يداويه.
        تكلم بلهجة تونسية فلاحية، بسيطة، ومباشرة لمالك. 
        لازم إجابتك تكون قصيرة ومفيدة.
        """

        # تحميل الصورة ومعالجتها
        img = Image.open(image_path)
        
        # طلب التحليل من الذكاء الاصطناعي
        response = model.generate_content([prompt, img])
        
        return response.text
    except Exception as e:
        return f"صار مشكل في التحليل يا مالك: {str(e)}"

if __name__ == "__main__":
    # استلام مسار الصورة كمعطى من PHP
    if len(sys.argv) > 1:
        path = sys.argv[1]
        result = analyze_crop(path)
        print(result)