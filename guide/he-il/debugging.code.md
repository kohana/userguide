# Debugging - דיבוג

קוהנה כוללת מספר כלים חזקים על מנת לעזור לך לדבג את האפליקציה שלך.
הכלי הבסיסי הוא [Kohana::debug].
כלי זה יציג את כל המשתנים או משתנה מסויים מכל סוג שהוא, בדומה ל  [var_export](http://php.net/var_export) או [print_r](http://php.net/print_r),  רק שקוהנה יודעת להשתמש ב HTML להצגה נוחה יותר

~~~
// הצג נתונים אודות המשתנים $foo ו- $bar
echo Kohana::debug($foo, $bar);
~~~

קוהנה גם מאפשרת בקלות לצפות בקוד המקור של קובץ מסויים ע"י שימוש ב [Kohana::debug_source].

~~~
// הצגה של שורה מסויימת מקובץ מסויים
echo Kohana::debug_source(__FILE__, __LINE__);
~~~

במידה ואתה מעוניין להציג מידע על האפליקציה מבלי לחשוף את התקיית התקנה, ניתן להשתמש ב [Kohana::debug_path]:

~~~
// מציג "APPPATH/cache" במקום הנתיב האמיתי
echo Kohana::debug_file(APPPATH.'cache');
~~~
