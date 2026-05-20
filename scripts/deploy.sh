#!/usr/bin/env bash
set -euo pipefail

: "${FTP_HOST:?Missing FTP_HOST}"
: "${FTP_USER:?Missing FTP_USER}"
: "${FTP_PASS:?Missing FTP_PASS}"
: "${FTP_REMOTE:=/htdocs}"

if [ ! -d "public" ]; then
  echo "ERROR: public/ directory not found"
  exit 1
fi

if ! command -v lftp >/dev/null 2>&1; then
  echo "ERROR: lftp is not installed. Run: pkg install lftp -y"
  exit 1
fi

lftp -u "$FTP_USER","$FTP_PASS" "$FTP_HOST" <<LFTP
set ftp:ssl-force true
set ftp:ssl-protect-data true
set ftp:passive-mode true
set ssl:verify-certificate no
mirror -R --delete --verbose --no-perms \
  --exclude-glob "storage/*.jsonl" \
  --exclude-glob "storage/*.log" \
  --exclude-glob "storage/uploads/**" \
  public/ "${FTP_REMOTE%/}/"
bye
LFTP
