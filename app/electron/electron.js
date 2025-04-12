const { app, BrowserWindow } = require('electron');
const path = require('path');

function createWindow() {
    const win = new BrowserWindow({
        width: 1200,
        height: 800,
        webPreferences: {
            contextIsolation: false,
            nodeIntegration: true,
        },
    });

    win.loadURL('http://localhost:8000');
}

app.whenReady().then(createWindow);
