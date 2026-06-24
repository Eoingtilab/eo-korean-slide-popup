#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
OUT_DIR="$ROOT_DIR/dist"
PLUGIN_DIR="eo-korean-slide-popup"

rm -rf "$OUT_DIR"
mkdir -p "$OUT_DIR/$PLUGIN_DIR"
rsync -a --exclude='.git' --exclude='dist' --exclude='tools' "$ROOT_DIR/" "$OUT_DIR/$PLUGIN_DIR/"
cd "$OUT_DIR"
zip -r "eo-korean-slide-popup.zip" "$PLUGIN_DIR"
