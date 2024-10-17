#!/usr/bin/env bash

# Install Python dependencies
pip install -r requirements.txt

# Install Playwright and necessary browsers
playwright install --with-deps chromium
