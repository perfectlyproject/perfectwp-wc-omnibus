#!/bin/sh

PLUGIN_SLUG="perfectwp-wc-omnibus"
TEXT_DOMAIN="pwp-wco"
PROJECT_PATH=$(pwd)
LANGUAGES_PATH="$PROJECT_PATH/languages"
DEFAULT_TEMPLATE_PATH="$LANGUAGES_PATH/$PLUGIN_SLUG.pot"

echo "Generating default template..."
mkdir -p "$LANGUAGES_PATH"
wp i18n make-pot $PROJECT_PATH $DEFAULT_TEMPLATE_PATH --domain=$TEXT_DOMAIN --exclude=$PROJECT_PATH --include=$PROJECT_PATH/src,$PROJECT_PATH/templates
echo "Done!"