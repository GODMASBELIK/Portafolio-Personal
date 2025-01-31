const express = require("express");
const WebSocket = require("ws");
const bcrypt = require("bcryptjs");
const jwt = require("jsonwebtoken");
const db = require("../database/database");

const app = express();
const server = require("http").createServer(app);
const wss = new WebSocket.Server({ server });

const SECRET_KEY = "GODMASBELIK";
const cors = require("cors");
app.use(cors());
app.use(express.json());

const activeSessions = new Map();

app.get("/", (req, res) => {
    res.sendFile("/Program Files/Ampps/www/DWES-main/Club/chat-project/public/index.html");
});
app.get("/login", (req, res) => {
    res.sendFile("/Program Files/Ampps/www/DWES-main/Club/chat-project/public/login.html");
});
app.get("/register", (req, res) => {
    res.sendFile("/Program Files/Ampps/www/DWES-main/Club/chat-project/public/register.html");
});

app.post("/register", (req, res) => {
    console.log("Solicitud recibida en /register");
    const { username, password } = req.body;

    bcrypt.hash(password, 10, (err, hashedPassword) => {
        if (err) return res.status(500).json({ error: "Error en el servidor" });

        db.run(`INSERT INTO users (username, password) VALUES (?, ?)`, [username, hashedPassword], (err) => {
            if (err) return res.status(400).json({ error: "Usuario ya existe" });
            res.json({ message: "Usuario registrado con éxito" });
        });
    });
});

app.post("/login", (req, res) => {
    const { username, password } = req.body;

    db.get(`SELECT * FROM users WHERE username = ?`, [username], (err, user) => {
        if (err || !user) return res.status(400).json({ error: "Usuario no encontrado" });

        bcrypt.compare(password, user.password, (err, result) => {
            if (!result) return res.status(401).json({ error: "Contraseña incorrecta" });

            if (activeSessions.has(username)) {
                const oldSession = activeSessions.get(username);
                
                if (oldSession.ws && oldSession.ws.readyState === WebSocket.OPEN) {
                    oldSession.ws.send(JSON.stringify({ error: "SAME_SESSION" }));
                    oldSession.ws.close();
                }

                activeSessions.delete(username);
            }
            // Aqui se puede cambiar la duracion
            const token = jwt.sign({ username: user.username }, SECRET_KEY, { expiresIn: "1h" });
            activeSessions.set(username, { token, ws: null });
            res.json({ message: "Login exitoso", token });
        });
    });
});

app.use(express.static("../public"));

wss.on("connection", (ws, req) => {
    const token = req.headers['sec-websocket-protocol'];
    if (!token) {
        console.log("Token no proporcionado");
        return ws.close();
    }

    jwt.verify(token, SECRET_KEY, (err, decoded) => {
        if (err) {
            console.log("Token expirado, cerrando conexión.");
            ws.send(JSON.stringify({ error: "TOKEN_EXPIRED" }));
            return ws.close();
        }

        const username = decoded.username;

        if (activeSessions.has(username)) {
            const oldSession = activeSessions.get(username);
            
            if (oldSession.ws && oldSession.ws.readyState === WebSocket.OPEN) {
                console.log(`Cerrando sesión WebSocket anterior para ${username}`);
                oldSession.ws.send(JSON.stringify({ error: "SAME_SESSION" }));
                oldSession.ws.close();
            }
        }

        activeSessions.set(username, { token, ws });
        
        console.log("Usuario autenticado en WebSocket:", username);

        db.all("SELECT autor, mensaje FROM Mensaje", (err, rows) => {
            if (!err) {
                rows.forEach((row) => {
                    const mensajeConUsuario = { autor: row.autor, mensaje: row.mensaje.toString() };
                    ws.send(JSON.stringify(mensajeConUsuario));
                });
            }
        });

        ws.on("message", (message) => {
            const mensajeConUsuario = {
                autor: username,
                mensaje: message.toString() 
            };

            console.log("Mensaje recibido:", mensajeConUsuario);

            db.run(`INSERT INTO Mensaje (autor, mensaje) VALUES (?, ?)`, [username, message], (err) => {
                if (err) console.log("Error al insertar mensaje en la base de datos:", err);
            });

            if (mensajeConUsuario.mensaje.includes("file:")) {
                mensajeConUsuario.tipo = "imagen";
            } else {
                mensajeConUsuario.tipo = "texto";
            }

            broadcast(mensajeConUsuario, ws);
        });

        ws.on("close", () => {
            console.log(`Conexión cerrada para ${username}`);
            if (activeSessions.has(username) && activeSessions.get(username).ws === ws) {
                activeSessions.delete(username);
            }
        });
    });

    function broadcast(message, sender) {
        wss.clients.forEach((client) => {
            if (client.readyState === WebSocket.OPEN) {
                client.send(JSON.stringify(message));
            }
        });
    }
});

server.listen(8085, "0.0.0.0", () => {
    console.log("Servidor corriendo en http://localhost:8085");
});
