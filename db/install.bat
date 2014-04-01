@SetLocal

@title Regix installer

@set /p db_host= "DB host [localhost]: "
@set /p db_user= "DB user [test_user]: "
@set /p db_pass= "DB password [test_password]: "
@set /p db_database= "DB database [test_user]: "

@if [%db_host%]==[] set db_host=localhost
@if [%db_user%]==[] set db_user=test_user
@if [%db_pass%]==[] set db_pass=test_password
@if [%db_database%]==[] set db_database=test_user

@echo %db_host%

@echo Executing create.sql
@mysql -h %db_host% -u %db_user% -p %db_pass% -D %db_database% -e "source create.sql"

@cd test_data/
@for /r %%i in (*.sql) do (echo Executing %%i & mysql -h %db_host% -u %db_user% -p %db_pass% -D %db_database% -e "source %%i")
@cd ..

@EndLocal

@pause