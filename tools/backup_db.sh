#!/bin/bash

DIR="/opt/backups/database"
PARAMETERS="$(php $(dirname $0)/getParameters.php)"
FILENAME="petstock-assessment-$(git describe --always)-$(date -Iseconds).sql"

mysqldump $PARAMETERS > "$DIR/$FILENAME"
