require('dotenv').config();
const express = require('express');
const cors = require('cors');
const app = express();
const PORT = process.env.PORT || 3000;

// Middleware
app.use(cors());
app.use(express.json());

// Routes
app.get('/', (req, res) => {
  res.send('Node.js API for GDrive Clone is running!');
});

// Auth Routes
const authRoutes = require('./auth');
app.use('/api/auth', authRoutes);

// File Routes
const fileRoutes = require('./files');
app.use('/api/files', fileRoutes);

// Start the server
app.listen(PORT, () => {
  console.log(`Server is running on http://localhost:${PORT}`);
});
