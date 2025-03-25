#!/bin/bash

DB_USER="root"
DB_PASS=""
DB_NAME="wypozyczalnia"
ADMIN_EMAIL="admin2@example.com" # ЗМІНИ ЕМЕЙЛ !!!
ADMIN_PASSWORD="adminPassword"  # ЗМІНИ ПАРОЛЬ !!! бажано на легкий щоб не забути
ADMIN_FIRST_NAME="Admin"        # краще змінити
ADMIN_LAST_NAME="Adminowicz"    # теж краще змінити

HASHED_PASSWORD=$(echo -n "$ADMIN_PASSWORD" | openssl passwd -1 -stdin)

mysql -u "$DB_USER" -p"$DB_PASS" -D "$DB_NAME" -e "
    INSERT INTO adminLog (email, haslo, imie, nazwisko) 
    VALUES ('$ADMIN_EMAIL', '$HASHED_PASSWORD', '$ADMIN_FIRST_NAME', '$ADMIN_LAST_NAME');
"

echo "Адмін акаунт згенеровано і додано в базу даних."
