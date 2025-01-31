const path = require("path");
const sqlite3 = require("sqlite3").verbose();


const dbPath = path.join(__dirname, "database.db");

const db = new sqlite3.Database(dbPath, (err) => {
    if (err) console.error("❌ Error al conectar con la base de datos:", err);
    else console.log("✅ Base de datos conectada en", dbPath);
});


db.run(`CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT UNIQUE,
    password TEXT
)`);

module.exports = db;
