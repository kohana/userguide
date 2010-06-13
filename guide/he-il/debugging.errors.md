# Error/Exception Handling - טיפול בשגיאות וחריגים

Kohana מאפשרת לנו טיפול נוח בשגיאות וחריגים על ידי הפיכת השגיאות לחריגים בעזרת ה
[ErrorException](http://php.net/errorexception) של PHP.
Kohana יודעת להציג נתונים רבים אודות השגיאות והחריגים שזיהתה:

1. Exception class -
2. Error level - רמת השגיאה
3. Error message - הודעת שגיאה
4. Source of the error, with the error line highlighted - מקור השגיאה עם סימון השורה הבעייתית
5. A [debug backtrace](http://php.net/debug_backtrace) of the execution flow - אפשרות מעקב אחורנית אודות הקריאות השונות שבוצעו עד לקבלת השגיאה על מנת לעקוב לאחור אחר מקור השגיאה
6. Included files, loaded extensions, and global variables - קבצים שנכללו, סיומות שנטענו ומשתנים גלובאלים

## דוגמא להודעת שגיאה

לחץ על אחד הקישורים הממוספרים על מנת להציג או להסתיר את המידע הנוסף

<div>{{userguide/examples/error}}</div>

## Disabling Error/Exception Handling - ביטול הטיפול בשגיאות וחריגים

במידה וברצונך לבטל את הטיפול בשגיאות, ניתן לעשות זאת בעת הקריאה
 [Kohana::init] בצורה הבאה:

~~~
Kohana::init(array('errors' => FALSE));
~~~

חשוב לזכור שבדרך כלל נרצה שהשגיאות המפורטות יופיעו רק בעבודה לוקאלית ולא באתר אונליין