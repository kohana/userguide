# Cascading Filesystem - מערכת קבצים מדורגת

מערכת הקבצים של Kohana בנוייה ממבנה בסיסי יחיד אשר משוכפל לכל התקיות הנמצאות בנתיב המכונה
include path. להלן צורת המבנה:

1. application - אפליקציה
2. modules, in order added - מודולים, לפי סדר ההופעה
3. system - מערכת

קבצים הנמצאים בתקיות שמעל ה  include path  מקבלים קדימות על קבצים עם שם זהה, עובדה המאפשרת
לטעון ולדרוס פעולות קבצים על ידי טעינה של קבצים זהים לקבצים הקיימים, רק במיקום גבוה יותר יחסית. לדוגמא:

![Cascading Filesystem Infographic](img/cascading_filesystem.png)

אם יש מצב בו יש לנו קובץ מבט (view) בשם  layout.php הממוקם בתקייה application/views וגם קיים בתקייה  system/views
הקובץ הממוקם בתקייה application יהיה זה שיוחזר ברגע שננסה לגשת ל layout.php
בגלל שהוא נמצא גבוה יותר בסדר האינקלוד (include path order).
ואם נמחוק את הקובץ הנמצא בתקייה application/views, אז הקובץ שיוחזר יהיה הקובץ השני שממוקם ב system/views.