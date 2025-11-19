Set shell = CreateObject("WScript.Shell")
' Path ke PHP yang akan menjalankan artisan schedule:run
command = "cmd.exe /C ""C:\laragon\bin\php\php-8.3.11-nts-Win32-vs16-x64\php.exe"" artisan schedule:run"
' Angka 0 = Sembunyikan jendela (Hidden Window)
shell.Run command, 0, false
