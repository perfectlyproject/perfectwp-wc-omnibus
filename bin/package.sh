#!/bin/sh

VERSION=$1
PLUGIN_SLUG="perfectwp-wc-omnibus"
PROJECT_PATH=$(pwd)
BUILD_PATH="${PROJECT_PATH}/build"
DEST_PATH="$BUILD_PATH/$PLUGIN_SLUG"

echo "Generating build directory..."
rm -rf "$BUILD_PATH"
mkdir -p "$DEST_PATH"
rm -rf "${PROJECT_PATH}/node_modules"
rm -rf "${PROJECT_PATH}/public"
rm -rf "${PROJECT_PATH}/vendor"

echo "Install dependencies and generate production assets"
npm install
npm run prod

echo "Cleaning up PHP dependencies..."
composer install --no-dev || exit "$?"

echo "Syncing files..."
rsync -rc --exclude-from="$PROJECT_PATH/.distignore" "$PROJECT_PATH/" "$DEST_PATH/" --delete --delete-excluded

echo "Replace version in files."
cd "$DEST_PATH" || exit
REPLACE_STRING_VERSION='{VERSION}'
sed -i "s/$REPLACE_STRING_VERSION/$VERSION/g" "perfectwp-wc-omnibus.php"
sed -i "s/$REPLACE_STRING_VERSION/$VERSION/g" "readme.txt"
sed -i "s/$REPLACE_STRING_VERSION/$VERSION/g" "src/Plugin.php"

echo "Generating zip file..."
cd "$BUILD_PATH" || exit
ZIP_FILENAME="${PLUGIN_SLUG}-v${VERSION}.zip";
zip -q -r "${ZIP_FILENAME}" "$PLUGIN_SLUG/"

cd "$PROJECT_PATH" || exit
mv "$BUILD_PATH/${ZIP_FILENAME}" "$PROJECT_PATH"
echo "${ZIP_FILENAME} file generated!"
rm -rf "$BUILD_PATH"

echo "Build done!"