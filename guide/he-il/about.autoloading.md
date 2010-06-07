# Autoloading - טעינה אוטומטית

Kohana יודע לנצל את יכולת הטעינה אוטומטית של PHP [autoloading](http://php.net/manual/language.oop5.autoload.php).
עובדה זו מבטלת את הצורך בשימוש ב  [include](http://php.net/include) או [require](http://php.net/require) לפני השימוש בבקר.

הבקרים (Classes) נטענים על ידי מטודת  [Kohana::auto_load], אשר יודעת לעשות את ההמרה משם בקר לשם קובץ:

1. בקרים צריכים להיות ממוקמים בתוך תקיית  `classes/`  השייכים ל [filesystem](about.filesystem)
2. כל קו תחתי בשם הבקר יהפוך לסלאש '/' ויחפש בתת תקיות בהתאם
3. שם הקובץ צריך להיות כתוב באותיות קטנות

כאשר קוראים לבקר שלא נטען (לדוגמא: `Session_Cookie`) קוהנה תחפש בעזרת פקודת [Kohana::find_file] את הקובץ `classes/session/cookie.php`.

## Custom Autoloaders - טעינה אוטומטית מותאמת אישית

[!!] הגדרת ברירת המחדל של הטעינה האוטומטית נמצאת בקובץ `application/bootstrap.php`.

בקרים נוספים ניתן להוסיף ע"י שימוש ב [spl_autoload_register](http://php.net/spl_autoload_register).